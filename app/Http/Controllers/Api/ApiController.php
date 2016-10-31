<?php

namespace clovergarden\Http\Controllers\Api;

use clovergarden\Http\Controllers\Controller;
use Auth, URL, Hash, DB, Input, Request;
use DateTime;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use FCM;

class ApiController extends Controller
{

	private $BASE_URL = "http://clovergarden.co.kr";

	public function __construct() {
		// constructor
	}

	/**
	 * 로그인 API
	 *
	 * @param  $id 			 로그인 ID
	 * @param	 $passwd   패스워드
	 * @return [JSON]    오류 시 JSON 텍스트, 승인 시 Array('success', token) 반환
	 */
	public function login()
	{
		// MD5로 로그인 가능한 지 체크
		$passwd = Input::get('passwd') ? Input::get('passwd') : 'dummypassword'; // 패스워드 초기화를 하지 않으면 Auth::attept()가 user_id로만 로그인 해버림
	  $user = array(
	      'user_id' => Input::get('id'),
	      'password' => $passwd
	  );

	  $passwd_md5 = md5(Input::get('passwd'));

	  $user_select = DB::select('select * from new_tb_member where user_id = :id && password = :pw', ['id' => $user['user_id'], 'pw' => $passwd_md5] );

	  if($user_select) { // MD5로 로그인 가능하다면, 패스워드를 bcrypt로 바꿈
	  	$bcrypt = Hash::make($user['password']);
	  	$md5tobcrypt = DB::update('update new_tb_member set password = :bcrypt where user_id = :id', ['bcrypt' => $bcrypt, 'id' => $user['user_id']]);
	  }

	  // bcrypt로 로그인
	  if (Auth::attempt($user)) {
			if(Auth::user()->user_state < 0) {
				return response()->json(['error' => 'is_seceded_member'], 401);
			}

			if(Auth::user()->user_state == 4) {
				if(Auth::user()->login_ck != 'y') {
					return response()->json(['error' => 'is_not_accepted_yet'], 401);
				}
			}

	  	// 로그인 성공
	  	$api_token = "";

	  	if ($api_token = Auth::user()->api_token) {

	  	} else { // 토큰이 없을 시 새로 생성
		  	$api_token = substr( Hash::make(rand()), 0, 60);
		  	DB::table('new_tb_member')->where('user_id', '=', $user['user_id'])->update(['api_token' => $api_token]);
		  }

	  	return response()->json(['success' => 'login_success', 'token' => $api_token]);
	  } else {
	  	return response()->json(['error' => 'invalid_credentials'], 401);
	  }

	  return response()->json(['error' => 'invalid_credentials'], 401);
	}

	/**
	 * 네이버 로그인 API
	 * 네이버 API에서 받은 access_token을 이용하여 클로버가든 로그인 기능을 수행한다.
	 *
	 * @param  token   네이버 access_token
	 * @return [JSON]  성공시 success 메시지
	 */
	public function loginNaver()
	{
		$token_access_naver = Input::get('token');
		// $token_access_naver = "AAAAOKyDNNSmU1Zdu4MdEo3LWNoDuccujo7UU6oqvuCQ4++xo1SraDgFLXwl6GtJEEHfx9re3Gk09+66H4B0UYNq+EI=";
		$phone = Input::get('phone');

		// 네이버 ID 받아와 데이터베이스에 존재하는지 확인
		$response = (new \GuzzleHttp\Client)->get('https://apis.naver.com/nidlogin/nid/getUserProfile.xml', [
        'headers' => [
        		'Accept' => '*/*',
            'Authorization' => 'Bearer ' . $token_access_naver,
        ],
    ]);

    $xmlparser = xml_parser_create();
    $result_array = array();
    $xml = simplexml_load_string($response->getBody());

    $xmlJSON = array();
    if($xml->result[0]->resultcode == '00'){
			foreach($xml->response->children() as $response => $k){
				$xmlJSON['response'][(string)$response] = (string) $k;
			}
		} else {
			return response()->json(['error' => 'failed_to_retrieve_data_from_naver', 'msg' => $xml->result], 401);
		}

    $api_token = "";
    $login_id_naver = $xmlJSON['response']['email'];
    $user_select = DB::table('new_tb_member')->select('id', 'social_type', 'api_token')->where('user_id', '=', $login_id_naver)->first();
    if($user_select) {
    	if ($api_token = $user_select->api_token) {

	  	} else { // 토큰이 없을 시 새로 생성
		  	$api_token = substr( Hash::make(rand()), 0, 60);
		  	DB::table('new_tb_member')->where('user_id', '=', $login_id_naver)->update(['api_token' => $api_token]);
		  }

		  return response()->json(['success' => 'login_success', 'token' => $api_token]);
		} else { // If not exist, add the user in DB
			if (!$phone) {
				return response()->json(['error' => 'need_phone_number'], 403);
			}

			$api_token = substr( Hash::make(rand()), 0, 60);
			$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2,
				'user_id' => $login_id_naver,
				'user_name' => $xmlJSON['response']['name'],
				'user_cell' => $phone,
				'social_type' => 'n',
				'api_token' => $api_token,
				'profile_image' => $xmlJSON['response']['profile_image']]
				);

			return response()->json(['success' => 'login_success', 'token' => $api_token]);
		}
	}

	/**
	 * 회원가입
	 * @param  $id 		 회원  아이디 (이메일)
	 * @param  $name 	 회원 이름
	 * @param  $passwd 비밀번호
	 * @param  $phone  핸드폰 번호
	 * @return [JSON]  성공 메시지(join_success), 회원 id (database id)
	 */
	public function signup()
	{
		$user_email = Input::get('id');
		$user_name = Input::get('name');
		$password = Input::get('passwd');
		$phone = Input::get('phone');

		// 핸드폰 번호 중복될 경우 에러 리턴 (DBMS에서는 보장하지 않음)
		if (DB::table('new_tb_member')->where('user_cell', '=', $phone)->get()) {
			return response()->json(['error' => 'duplicated_phone_number'], 403);
		}

		$password_bcrypt = Hash::make($password);

		$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2, 'user_id' => $user_email, 'password' => $password_bcrypt, 'user_name' => $user_name, 'user_cell' => $phone, 'social_type' => 'n']
				);

		return response()->json(['success' => 'join_success', 'id' => $id]);
	}

	/**
	 * 아이디 중복 검사
	 * @param 	id		회원  아이디 (이메일)
	 * @return 	JSON 	중복 시 true, 아닐 시 false
	 */
	public function checkDuplicateID()
	{
		$user_email = Input::get('id');

		$user = DB::table('new_tb_member')->where('user_id', '=', $user_email)->first();
		if ($user) {
			return response()->json(['is_duplicate' => true]);
		} else {
			return response()->json(['is_duplicate' => false]);
		}
	}

	/**
	 * 아이디 찾기
	 * @param  $user_name 회원 이름
	 * @param  $phone 		핸드폰 번호
	 * @return [JSON] 		아이디
	 */
	public function findId()
	{
		$user_name = Input::get('user_name');
		$phone = Input::get('phone');

		$user = DB::table('new_tb_member')->where('user_name', '=', $user_name)->where('user_cell', '=', $phone)->first();
		if ($user) {
			return response()->json(['id' => $user->user_id]);
		}

		return response()->json(['error' => 'failed_to_find_id'], 401);
	}

	/**
	 * 비밀번호 찾기
	 * @param  $user_name 회원 이름
	 * @param  $phone 		핸드폰 번호
	 * @return [JSON] 	  sendSms 리턴값
	 */
	public function findPw()
	{
		$user_id = Input::get('user_id');
		$user_name = Input::get('user_name');
		$phone = Input::get('phone');

		// Generate temporary password
		$temp_password = mt_rand(100000, 999999);

		// update Database
		$user = DB::table('new_tb_member')->where('user_id', '=', $user_id)->where('user_cell', '=', $phone)->update(['password' => Hash::make($temp_password)]);

		// send message
		$msg = "회원님의 임시비밀번호는 " . $temp_password . "입니다. 로그인 후 수정해 주시기 바랍니다.";

		return $this->sendSms($msg, $this->formatPhoneNumberForSms($phone));
	}

	/**
	 * 유저 정보를 불러옴 (로그인 한)
	 * @return [JSON] user information
	 */
	public function getUser()
	{
		$user = DB::table('new_tb_member')->where('user_id', '=', Auth::user()->user_id)->first();
		return json_encode($user, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 내 정보 불러오기
	 * @return [JSON] 유저 이름, 소속 기업, 썸네일(URL), 총 후원 금액
	 */
	public function getMyDetail()
	{
		$member = DB::table('new_tb_member')->where('user_id', '=', Auth::user()->user_id)->first();
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->sum('price'); // 정기후원은 테스트 확인

		// 썸네일 포매팅
		$thumbnail = null;
		if ($member->file_edit1) {
			$thumbnail = $this->BASE_URL . "/imgs/up_file/member/" . $member->file_edit1;
		}

		// 기관담당자
		$isCompanySup = false;
		if ($member->user_state == 6) {
			$isCompanySup = true;
		}

		// 관리자(마스터)
		$isMaster = false;
		if ($member->user_state == 1 || $member->user_state == 10) {
			$isMaster = true;
		}

		$user_detail = array(
				'user_name' => $member->user_name,
				'user_id' => $member->id,
				'group_name' => $member->group_name,
				'thumbnail' => $thumbnail,
				'total_support' => $member_sup,
				'isCompanySup' => $isCompanySup,
				'isMaster' => $isMaster
			);

		return json_encode($user_detail, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 프로필 사진 업로드
	 * @param  upload_pic 파일
	 * @return JSON 성공 시 success
	 */
	public function uploadProfilePic()
	{
		// Image Upload
		if (Input::file('upload_pic')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/member';

			$fileExt = Input::file('upload_pic')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '.' . $fileExt . date("YmdHms");
			Input::file('upload_pic')->move($destinationPath, $fileName);

			// Manipulate Database
			$user = DB::table('new_tb_member')->where('user_id', '=', Auth::user()->user_id)->update(['file_edit1' => $fileName]);


			return response()->json(['success' => 'upload_success']);
		}

		return response()->json(['error' => 'failed_to_upload'], 500);
	}

	/**
	 * 내 후원 내역 불러오기 (deprecated)
	 * @return [JSON] otype 후원 형태
	 * @return [JSON] clover_seq 후원 기관 코드 (id 아님)
	 * @return [JSON] price 후원 금액
	 * @return [JSON] 이하 생략
	 */
	public function getSupList()
	{
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')
																									->leftJoin('new_tb_clover', 'new_tb_clover_mlist.clover_seq', '=', 'new_tb_clover.code')
																									->select('new_tb_clover_mlist.*', 'new_tb_clover.subject as clover_name')
																									->get(); // FIXME: 정기후원은 테스트 확인. 일시후원 시 후원일자 형식이 다른 것 수정 필요.

		// 일시 후원일 경우 reg_date 필드를 start 필드로 포매팅하여 등록 (형식 변경: yyyy-mm-dd hh:mm:ss -> yyyymmdd)
		foreach ($member_sup as $ms) {
			if ($ms->otype != "자동이체") { // 일시후원인 경우
				$ms->start = str_replace("-", "", substr($ms->reg_date, 0, 10));
			}
		}

		// 정렬 (orderBy 'start')
		usort($member_sup, array("clovergarden\Http\Controllers\Api\ApiController", "sortSupportList"));

		return json_encode($member_sup, JSON_UNESCAPED_UNICODE);
	}

	public function getAvailableSupportYear()
	{
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')
																									->leftJoin('new_tb_clover', 'new_tb_clover_mlist.clover_seq', '=', 'new_tb_clover.code')
																									->select('new_tb_clover_mlist.*', 'new_tb_clover.subject as clover_name')
																									->get();

		// 일시 후원일 경우 reg_date 필드를 start 필드로 포매팅하여 등록 (형식 변경: yyyy-mm-dd hh:mm:ss -> yyyymmdd)
		foreach ($member_sup as $ms) {
			if ($ms->otype != "자동이체") { // 일시후원인 경우
				$ms->start = str_replace("-", "", substr($ms->reg_date, 0, 10));
			}
		}

		// 정렬 (orderBy 'start')
		usort($member_sup, array("clovergarden\Http\Controllers\Api\ApiController", "sortSupportList"));

		// 검색 가능 연도 반환
		$years = array();
		foreach ($member_sup as $ms) {
			if (!in_array(substr($ms->start, 0, 4), $years)) {
				array_push($years, substr($ms->start, 0, 4));
			}
		}

		return json_encode($years, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 내 후원 내역 연도별 불러오기
	 * @param int year 연도(yyyy)
	 * @return JSON 후원 내역 리스트
	 */
	public function getSupportListByYear($year)
	{
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')
																									->leftJoin('new_tb_clover', 'new_tb_clover_mlist.clover_seq', '=', 'new_tb_clover.code')
																									->select('new_tb_clover_mlist.*', 'new_tb_clover.subject as clover_name')
																									->get();

		// 일시 후원일 경우 reg_date 필드를 start 필드로 포매팅하여 등록 (형식 변경: yyyy-mm-dd hh:mm:ss -> yyyymmdd)
		foreach ($member_sup as $ms) {
			if ($ms->otype != "자동이체") { // 일시후원인 경우
				$ms->start = str_replace("-", "", substr($ms->reg_date, 0, 10));
			}
		}

		// 정렬 (orderBy 'start')
		usort($member_sup, array("clovergarden\Http\Controllers\Api\ApiController", "sortSupportList"));

		// year
		$year_start = $year . "0101";
		$year_end = $year . "1231";

		// year에 해당하는 데이터만 반환
		$result_sup = array();
		foreach ($member_sup as $ms) {
			if ($ms->start >= $year_start && $ms->start <= $year_end) {
				array_push($result_sup, $ms);
			}
		}

		return json_encode($result_sup, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 후원 기관 목록 불러옴
	 *
	 * @return seq 							후원 기관 ID
	 * @return code 						후원 기관 변경 때 쓰이는 code
	 * @return subject 					후원 기관 명
	 * @return mobile_intro 		후원 기관 소개 (모바일용)
	 * @return mobile_thumbnail 후원 기관 리스트 썸네일 (모바일용)
	 * @return mobile_image 		후원 기관 이미지 (모바일용)
	 */
	public function getListOfCompany()
	{
		$companyList = DB::table('new_tb_clover')->select('seq', 'code', 'subject', 'mobile_intro', 'mobile_thumbnail', 'mobile_image1', 'mobile_image2', 'mobile_image3')->orderBy('view_n', 'desc')->get();
		return json_encode($companyList, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 내 후원 기관 목록 불러옴
	 *
	 * @return JSON 후원 기관 목록 API와 같은 형식
	 */
	public function getListOfCompanyUser()
	{
		$clover_codes = $this->getUserCloverCodesWithPrices()['clover_codes'];

		$companyList = array();
		if ($clover_codes) {
			$clover_prices = $this->getUserCloverCodesWithPrices()['clover_prices'];

			$companyList = DB::table('new_tb_clover')->where(function($query) use ($clover_codes) {
																				foreach ($clover_codes as $cc) {
																					$query->orWhere('code', '=', $cc);
																				}
																			})->select('seq', 'code', 'subject', 'mobile_intro', 'mobile_thumbnail', 'mobile_image1', 'mobile_image2', 'mobile_image3')->get();

			// 가격 추가
			if ($companyList) {
				foreach ($companyList as $key => $value) {
					$value->price = $clover_prices[$key];
				}
			}
		} else {
			// 후원 기관 없음
		}

		return json_encode($companyList, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 후원 기관 세부 내역
	 *
	 * @param  [Integer] $company_id 후원 기관 id
	 * @return [JSON] 	             getListOfCompany()의 return 형식과 같음
	 */
	public function getCompanyDetail($company_id)
	{
		$company = DB::table('new_tb_clover')->where('seq', '=', $company_id)->select('seq', 'code', 'subject', 'mobile_intro', 'mobile_thumbnail', 'mobile_image1', 'mobile_image2', 'mobile_image3')->first();
		return json_encode($company, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 공지사항 받아오기
	 * @return [JSON] 제목, 내용, 등록일
	 */
	public function getNotices()
	{
		$notices = DB::table('new_tb_notice')->select('seq as id', 'subject', 'content', 'reg_date')->get();
		return json_encode($notices, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 자주하는 질문 받아오기
	 * @return [JSON] 제목, 내용, 등록일
	 */
	public function getFaqs()
	{
		$faqs = DB::table('new_tb_faq')->select('seq as id', 'subject', 'content', 'reg_date')->get();
		return json_encode($faqs, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 인증 번호 발송 API
	 * @param [Integer] phone_num 핸드폰 번호
	 * @return [JSON] 						인증 번호
	 */
	public function sendAuthSms()
	{
		$phone = Input::get('phone_num');

		// generate Auth Number
		$auth_num = mt_rand(100000, 999999);
		$msg = "요청하신 인증번호는 " . $auth_num . " 입니다.";
		$result = $this->sendSms($msg, $this->formatPhoneNumberForSms($phone));
		$data = array('auth_code' => $auth_num);

		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 사용자의 타임라인을 받아온다.
	 *
	 * @param int (Optional) only_report 리포트만 받아온다. (0: false, 1: true)
	 * @return JSON 게시물 리스트 + 댓글 수
	 */
	public function getUserTimeline()
	{
		$clover_codes = $this->getUserCloverCodesWithPrices()['clover_codes'];
		$only_report = Input::get('only_report') ? Input::get('only_report') == 1 : false;

		// 타임라인 받아오기
		return $this->getTimelineFromDBForMe($clover_codes, $only_report);
	}

	/**
	 * 전체 타임라인을 받아온다. (public만)
	 *
	 * @return JSON 게시물 리스트 + 댓글 수
	 */
	public function getTimelineAll()
	{
		$clover_codes = null;
		if (Auth::check()) {
			$clover_codes = $this->getUserCloverCodesWithPrices()['clover_codes'];
		}

		// 타임라인 받아오기
		return $this->getTimelineFromDBForAll($clover_codes);
	}

	/**
	 * 특정 후원기관의 public 게시물만 받아옴 (당연히 보고서는 받아오지 않음. urgency, normal 순)
	 *
	 * @param  string $clover_code 	후원기관 코드 (예: a01)
	 * @return JSON             		게시물 리스트 + 댓글 수
	 */
	public function getTimelineClover($clover_code)
	{
		// Public인 article 받아오기
		$articles_public_urgency = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'urgency')
																->where('clover_code', '=', $clover_code)
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.subject as clover_name', 'new_tb_member.file_edit1 as profile_image')
																->groupBy('cg_board.id')
																->get();

		$articles_public_normal = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'normal')
																->where('clover_code', '=', $clover_code)
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.subject as clover_name', 'new_tb_member.file_edit1 as profile_image')
																->groupBy('cg_board.id')
																->get();

		$articles = array();
		foreach ($articles_public_urgency as $apu) {
			array_push($articles, $apu);
		}
		foreach ($articles_public_normal as $apu) {
			array_push($articles, $apu);
		}

		return json_encode($articles, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 타임라인 게시물 세부 내용
	 * API 콜 시 조회수 +1
	 * @param int board_id 게시물 ID
	 * @return JSON 게시물 상세
	 */
	public function getTimelineDetail($board_id)
	{
		$article = DB::table('cg_board')->where('cg_board.id', '=', $board_id)
																		->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																		->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																		->select('cg_board.*', 'new_tb_clover.subject as clover_name', 'new_tb_member.file_edit1 as profile_image')
																		->first();
		DB::table('cg_board')->where('id', '=', $board_id)->increment('hit');

		return json_encode($article, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 타임라인 글쓰기
	 * @param string type 게시물 타입 (urgency: 긴급 후원, normal: 일반, report: 보고서 // report는 쓰이지 않음)
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @param file image1 이미지 파일 1 (썸네일)
	 * @param file image2 이미지 파일 2
	 * @param file image3 이미지 파일 3
	 * @param string limitation 권한 (public: 전체 공개, protected: 후원자만 공개, private: 비공개)
	 * @param date (긴급후원인 경우. 아닌 경우 null을 보내면 됨) 후원 마감일
	 * @return JSON 성공 메시지
	 */
	public function writeTimeline()
	{
		$clover_code = Auth::user()->user_id;
		$type = Input::get('type');
		$text = Input::get('text');
		$image_path1 = null;
		$image_path2 = null;
		$image_path3 = null;
		$limitation = Input::get('limitation');
		$due_date = Input::get('due_date');

		// Validation
		if ($type == null || $type == "") {
			return response()->json(['error' => 'type_should_be_assigned']);
		}

		if ($text == null || $text == "") {
			return response()->json(['error' => 'text_will_not_be_empty']);
		}

		if ($limitation == null || $limitation == "") {
			return response()->json(['error' => 'limitation_will_not_be_empty']);
		}

		if ($type == "urgency") {
			if ($due_date == null || $due_date == "") {
				return response()->json(['error' => 'due_date_should_be_assigned']);
			}
		}

		// Image Upload
		if (Input::file('image1') && Input::file('image1')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image1')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '1.' . $fileExt;
			Input::file('image1')->move($destinationPath, $fileName);

			$image_path1 = $fileName;
			$image_path1 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path1;
		}

		// Image Upload
		if (Input::file('image2') && Input::file('image2')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image2')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '2.' . $fileExt;
			Input::file('image2')->move($destinationPath, $fileName);

			$image_path2 = $fileName;
			$image_path2 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path2;
		}

		// Image Upload
		if (Input::file('image3') && Input::file('image3')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image3')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '3.' . $fileExt;
			Input::file('image3')->move($destinationPath, $fileName);

			$image_path3 = $fileName;
			$image_path3 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path3;
		}

		// Manipulate Database
		$dt = new DateTime;
		$article = DB::table('cg_board')->insert(
			['clover_code' => $clover_code,
			 'type' => $type,
			 'text' => $text,
			 'image_path1' => $image_path1,
			 'image_path2' => $image_path2,
			 'image_path3' => $image_path3,
			 'limitation' => $limitation,
			 'due' => $due_date,
			 'created_at' => $dt->format('y-m-d [H:i:s]'),
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]
		);

		// FCM 메시징
		$notificationBuilder = new PayloadNotificationBuilder('클로버가든');
		$notificationBuilder->setBody('새 게시물이 등록되었어요. 지금 바로 확인해보세요')
												->setSound('default');
		$notification = $notificationBuilder->build();

		$topic = new Topics();
		$topic->topic('news');

		$topicResponse = FCM::sendToTopic($topic, null, $notification, null);

		if ($topicResponse->isSuccess()) {
			// 메시지 전송 성공
		} else {
			$topicResponse->shouldRetry();
		}

		return response()->json(['success' => 'write_success']);
	}

	/**
	 * 타임라인 수정
	 * @param int board_id ID
	 * @param string type 게시물 타입 (urgency: 긴급 후원, normal: 일반, report: 보고서 // report는 쓰이지 않음)
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @param file image1 이미지 파일 1 (썸네일)
	 * @param file image2 이미지 파일 2
	 * @param file image3 이미지 파일 3
	 * @param string limitation 권한 (public: 전체 공개, protected: 후원자만 공개, private: 비공개)
	 * @param date (긴급후원인 경우. 아닌 경우 null을 보내면 됨) 후원 마감일
	 * @return JSON 성공 메시지
	 */
	public function modifyTimeline($board_id)
	{
		$clover_code = Auth::user()->user_id;
		$type = Input::get('type');
		$text = Input::get('text');
		$image_path1 = null;
		$image_path2 = null;
		$image_path3 = null;
		$limitation = Input::get('limitation');
		$due_date = Input::get('due_date');

		// Validation
		if (!$type) {
			return response()->json(['error' => 'type_should_be_assigned']);
		}

		if (!$text) {
			return response()->json(['error' => 'text_will_not_be_empty']);
		}

		if (!$limitation) {
			return response()->json(['error' => 'limitation_will_not_be_empty']);
		}

		if ($type == "urgency") {
			if (!$due_date) {
				return response()->json(['error' => 'due_date_should_be_assigned']);
			}
		}

		// Image Upload
		if (Input::file('image1') && Input::file('image1')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image1')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '1.' . $fileExt;
			Input::file('image1')->move($destinationPath, $fileName);

			$image_path1 = $fileName;
			$image_path1 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path1;
		}

		// Image Upload
		if (Input::file('image2') && Input::file('image2')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image2')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '2.' . $fileExt;
			Input::file('image2')->move($destinationPath, $fileName);

			$image_path2 = $fileName;
			$image_path2 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path2;
		}

		// Image Upload
		if (Input::file('image3') && Input::file('image3')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';

			$fileExt = Input::file('image3')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '3.' . $fileExt;
			Input::file('image3')->move($destinationPath, $fileName);

			$image_path3 = $fileName;
			$image_path3 = $this->BASE_URL . "/imgs/up_file/board/" . $image_path3;
		}

		// Manipulate Database
		$dt = new DateTime;
		$article = DB::table('cg_board')->where('id', '=', $board_id)->where('clover_code', '=', $clover_code)->update(
			['clover_code' => $clover_code,
			 'type' => $type,
			 'text' => $text,
			 'image_path1' => $image_path1,
			 'image_path2' => $image_path2,
			 'image_path3' => $image_path3,
			 'limitation' => $limitation,
			 'due' => $due_date,
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]
		);

		if (!$article) {
			return response()->json(['error' => 'update_failed'], 500);
		}

		return response()->json(['success' => 'update_success']);
	}

	/**
	 * 타임라인 삭제
	 * @param int board_id ID
	 * @return JSON 성공 메시지
	 */
	public function deleteTimeline($board_id)
	{
		$clover_code = Auth::user()->user_id;

		$article = DB::table('cg_board')->where('id', '=', $board_id)->where('clover_code', '=', $clover_code)->delete();

		if (!$article) {
			return response()->json(['error' => 'delete_failed'], 500);
		}

		return response()->json(['success' => 'delete_success']);
	}

	/**
	 * 타임라인 좋아요 (deprecated)
	 *
	 * @param int board_id ID
	 * @return JSON 성공 메시지
	 */
	public function likeTimeline($board_id)
	{
		$article = DB::table('cg_board')->where('id', '=', $board_id)->increment('like');

		return response()->json(['success' => 'like_success']);
	}

	/**
	 * 타임라인 댓글 불러오기
	 * @param int board_id 게시물 ID
	 * @return JSON 댓글 리스트 (+ properties)
	 */
	public function getTimelineComment($board_id)
	{
		$comments = DB::table('cg_board_comment')->where('board_id', '=', $board_id)
																						 ->leftJoin('new_tb_member', 'cg_board_comment.member_id', '=', 'new_tb_member.id')
																						 ->select('cg_board_comment.*', 'new_tb_member.user_name', 'new_tb_member.file_edit1 as profile_image')
																						 ->get();

		return json_encode($comments, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 타임라인 댓글 글쓰기
	 * @param int board_id 게시물 ID
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @return JSON 성공 메시지
	 */
	public function writeTimelineComment()
	{
		$board_id = Input::get('board_id');
		$member_id = Auth::user()->id;
		$text = Input::get('text');

		// Manipulate Database
		$dt = new DateTime;
		$comment = DB::table('cg_board_comment')->insert(
			['board_id' => $board_id,
			 'member_id' => $member_id,
			 'text' => $text,
			 'created_at' => $dt->format('y-m-d [H:i:s]'),
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]
		);

		return response()->json(['success' => 'write_comment_success']);
	}

	/**
	 * 타임라인 댓글 수정
	 * @param int comment_id 댓글 ID
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @return JSON 성공 메시지
	 */
	public function modifyTimelineComment($comment_id)
	{
		$text = Input::get('text');

		// Manipulate Database
		$dt = new DateTime;
		$comment = DB::table('cg_board_comment')->where('id', '=', $comment_id)->where('member_id', '=', Auth::user()->id)->update(
			['text' => $text,
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]
		);

		if (!$comment) {
			return response()->json(['error' => 'modify_comment_failed'], 500);
		}

		return response()->json(['success' => 'modify_comment_success']);
	}

	/**
	 * 타임라인 댓글 삭제
	 * @param int comment_id 댓글 ID
	 * @return JSON 성공 메시지
	 */
	public function deleteTimelineComment($comment_id)
	{
		// Manipulate Database
		if (Auth::user()->user_state != 1 && Auth::user()->user_state != 10) {
			$comment = DB::table('cg_board_comment')->where('id', '=', $comment_id)->where('member_id', '=', Auth::user()->id)->delete();
		} else { // 관리자인 경우 (글쓴이에 관계없이 삭제 가능)
			$comment = DB::table('cg_board_comment')->where('id', '=', $comment_id)->delete();
		}

		if (!$comment) {
			return response()->json(['error' => 'delete_comment_failed'], 500);
		}

		return response()->json(['success' => 'delete_comment_success']);
	}

	/**
	 * 후원 기관 변경
	 * @param string clover_codes 후원 변경 코드. ','로 구분
	 * @param string clover_prices 후원 변경 금액. ','로 구분
	 * @return JSON 성공 메시지
	 */
	public function changeClover()
	{
		// Clover 코드 가져오기
		$clover_codes = Input::get('clover_codes');
		$clover_codes_extract = explode(',', $clover_codes);

		$clover_codes = array();
		foreach ($clover_codes_extract as $cce) {
			array_push($clover_codes, trim($cce));
		}

		// Clover 가격 가져오기
		$clover_prices = Input::get('clover_prices');
		$clover_prices_extract = explode(',', $clover_prices);

		$clover_prices = array();
		$sum_of_prices = 0;
		foreach ($clover_prices_extract as $cpe) {
			array_push($clover_prices, trim($cpe));
			$sum_of_prices += trim($cpe);
		}

		// Original 가격 가져오기
		$org_clover_seq_adm_type = explode("[@@]", Auth::user()->clover_seq_adm_type);
		$org_clover_list = null;
		$org_clover_prices = 0;

		if(count($org_clover_seq_adm_type) > 1){ // 후원변경 신청. 관리자의 승인이 된 경우
			if($org_clover_seq_adm_type[1] == 'ok')
				$org_clover_list = explode("[@@@]", Auth::user()->clover_seq);
		} else { // 후원변경 신청. 관리자의 승인이 안된 경우
			$ex_clover_seq_adm = explode("[@@@@]", Auth::user()->clover_seq_adm);
			$org_clover_list = explode("[@@@]", $ex_clover_seq_adm[0]);
		}

		// Original 가격 합 계산
		foreach ($org_clover_list as $cl) {
			if ( isset(explode("[@@]", $cl)[1]) )
				$org_clover_prices += explode("[@@]", $cl)[1];
		}

		// 후원변경 신청을 하지 않은 경우
		if (Auth::user()->clover_seq == '' || empty($org_clover_list[0])) {
			// $org_clover_list = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->select('price')->get();
			$clover_prices = $this->getUserCloverCodesWithPrices()['clover_prices'];

			// 코드 배열 생성
			$org_clover_prices = 0; // 예외 대비 초기화
			foreach ($clover_prices as $cl) {
				$org_clover_prices += $cl;
			}
		}

		// Original 가격과 새로운 가격 비교
		if ($org_clover_prices != $sum_of_prices) {
			return response()->json(['error' => 'price_not_matching'], 200);
		}

		// 중복된 값 확인
		if ( count($clover_codes) != count(array_unique($clover_codes)) ) {
			return response()->json(['error' => 'duplicated_clover_code'], 200);
		}

		// 에러 없을 시 Manipulate Database
		$seq_clover_ex = null;
		foreach ($clover_codes as $k => $v) {
			if ($k == 0) {
				$seq_clover_ex = $v . "[@@]" . $clover_prices[$k];
			} else {
				$seq_clover_ex .= "[@@@]" . $v . "[@@]" . $clover_prices[$k];
			}
		}

		DB::table('new_tb_member')->where('id', '=', Auth::user()->id)->update(
			['clover_seq' => $seq_clover_ex,
			 'clover_seq_adm' => Auth::user()->clover_seq . "[@@@@]" . $seq_clover_ex,
			 'clover_seq_adm_type' => time(),
			 'update_ck' => 'Y']
		);

		return response()->json(['success' => 'success_change_clover']);
	}

	/**
	 * 게시물 좋아요 토글
	 * 동일 게시물 한번 더 호출 시 좋아요 취소된다
	 *
	 * @param  [int] $board_id  게시물 ID
	 * @return [JSON]           성공 메시지
	 */
	public function toggleBoardLike($board_id)
	{
		$is_like = DB::table('cg_board_like')->where('user_id', '=', Auth::user()->id)->where('board_id', '=', $board_id)->get();

		if (!$is_like) {
			// cg_board_like 테이블에 데이터 추가
			DB::table('cg_board_like')->where('user_id', '=', Auth::user()->id)->where('board_id', '=', $board_id)->insert(
				['user_id' => Auth::user()->id,
				 'board_id' => $board_id
			 ]
			);

			// cg_board 테이블에 like + 1
			DB::table('cg_board')->where('id', '=', $board_id)->increment('like', 1);

			return response()->json(['success' => 'success_like_board']);
		} else {
			// cg_board_like 테이블에 데이터 삭제
			DB::table('cg_board_like')->where('user_id', '=', Auth::user()->id)->where('board_id', '=', $board_id)->delete();

			// cg_board 테이블에 like - 1
			DB::table('cg_board')->where('id', '=', $board_id)->decrement('like', 1);

			return response()->json(['success' => 'success_dislike_board']);
		}
	}

	/**
	 * SMS 전송 Private 메소드. 메소드 전송만 담당
	 * @param  [String] $msg   	메시지 내용
	 * @param  [String] $phone 	보낼 핸드폰 번호 ('-' 포함해야 함)
	 * @return [JSON]       		전송 결과
	 */
	private function sendSms($msg, $phone)
	{
		$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		$sms['user_id'] = base64_encode("clovergarden1000"); //SMS 아이디.
		$sms['secure'] = base64_encode("5decd3e1b30d73176110964056803208");//인증키

		$sms['msg'] = base64_encode(stripslashes($msg));

		$sms['rphone'] = base64_encode($phone);
		$sms['sphone1'] = base64_encode('02');
		$sms['sphone2'] = base64_encode('720');
		$sms['sphone3'] = base64_encode('3235');

		$sms['rdate'] = '';
		$sms['rtime'] = '';
		$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$sms['returnurl'] = '';
		$sms['testflag'] = base64_encode(''); // 테스트일 경우 'Y'
		$sms['destination'] = '';
		$returnurl = '';
		$sms['repeatFlag'] = '';
		$sms['repeatNum'] = '';
		$sms['repeatTime'] = '';
		$sms['smsType'] = base64_encode('S'); // LMS일경우 L

		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3];

		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		$data = null;
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) {
			fputs($fp, $header.$data);
			$rsp = '';

			while(!feof($fp)) {
				$rsp .= fgets($fp,8192);
			}

			fclose($fp);
			$msg = explode("\r\n\r\n",trim($rsp));
			$rMsg = explode(",", $msg[1]);
			$result_send = $rMsg[0]; //발송결과
			$count= $rMsg[1]; //잔여건수

			//발송결과 알림
			if($result_send == "success") {
				$result = response()->json(['success' => 'send_success', 'rest' => $count]);
			}	else if($result_send == "reserved") {
				$result = response()->json(['success' => 'reserved_success', 'rest' => $count]);
			}	else if($result_send == "3205") {
				$result = response()->json(['error' => 'wrong_type_of_phone_number'], 403);
			}	else if($result_send == "0044") {
				$result = response()->json(['error' => 'spam_message'], 403);
			}	else {
				$result = response()->json(['error' => $result_send], 403);
			}
		}

		return $result;
	}

	/**
	 * 핸드폰 번호 포매팅 메소드. 하이픈('-')을 포함해서 리턴한다
	 * @param  [Integer] $phone 	핸드폰 번호
	 * @return [String]   				하이픈 포함된 핸드폰 번호
	 */
	private function formatPhoneNumberForSms($phone)
	{
		$str_phone1 = substr($phone, 0, 3);
		$str_phone2 = substr($phone, 3, 4);
		$str_phone3 = substr($phone, 7, 4);

		return $str_phone1 . "-" . $str_phone2 . "-" . $str_phone3;
	}

	/**
	 * 일반 게시물 & 보고서 소팅 메소드
	 * @param  stdClass $a [description]
	 * @param  stdClass $b [description]
	 * @return int 1일 때 swap
	 */
	private function sortTimeline($a, $b)
	{
		$a_sortingkey = isset($a->created_at) ? $a->created_at : $a->reg_date;
		$b_sortingkey = isset($b->created_at) ? $b->created_at : $b->reg_date;

		if ($a_sortingkey == $b_sortingkey) {
			return 0;
		}

		return ($a_sortingkey > $b_sortingkey) ? -1 : 1;
	}

	private function sortSupportList($a, $b)
	{
		if ($a->clover_seq == $b->clover_seq) {
			return ($a->start > $b->start) ? -1 : 1;
		}

		return strcmp($a->clover_seq, $b->clover_seq);
	}

	/**
	 * 나의 타임라인 받아오기 private 메소드
	 * protected-urgency, public-urgency 순. 일반(protected-normal, public-normal)과 보고서는 날짜순으로만 정렬
	 *
	 * @param  array $clover_codes  클로버 seq 배열
	 * @return array                타임라인 리스트
	 */
	private function getTimelineFromDBForMe($clover_codes = null, $only_report = false)
	{
		$user_id = 0;
		if (Auth::check()) {
			$user_id = Auth::user()->id;
		}

		// 클로버코드 배열이 비어있을 경우 빈 배열(json) 리턴 (orWhere에서 비어있는 배열이면 전체 DB가 리턴되는 버그가 있음)
		if (count($clover_codes) == 0) {
			return json_encode(array(), JSON_UNESCAPED_UNICODE);
		}

		// Protected, 후원 기관인 article 받아오기
		$articles_protected_urgency = DB::table('cg_board')
																	->where('limitation', '=', 'protected')
																	->where(function($query) use ($clover_codes) {
																		foreach ($clover_codes as $cc) {
																			$query->orWhere('clover_code', '=', $cc);
																		}
																	})
																	->where('type', '=', 'urgency')
																	->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																	->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																	->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																	->leftJoin('cg_board_like', function($join) use ($user_id) {
																		$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																					->where('cg_board_like.user_id', '=', $user_id);
																	})
																	->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																	->groupBy('cg_board.id')
																	->get();

		$articles_protected_normal = DB::table('cg_board')
																	->where('limitation', '=', 'protected')
																	->where(function($query) use ($clover_codes) {
																		foreach ($clover_codes as $cc) {
																			$query->orWhere('clover_code', '=', $cc);
																		}
																	})
																	->where('type', '=', 'normal')
																	->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																	->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																	->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																	->leftJoin('cg_board_like', function($join) use ($user_id) {
																		$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																					->where('cg_board_like.user_id', '=', $user_id);
																	})
																	->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																	->groupBy('cg_board.id')
																	->get();

		// Public인 article 받아오기
		$articles_public_urgency = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where(function($query) use ($clover_codes) {
																	foreach ($clover_codes as $cc) {
																		$query->orWhere('clover_code', '=', $cc);
																	}
																})
																->where('type', '=', 'urgency')
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																->groupBy('cg_board.id')
																->get();

		$articles_public_normal = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where(function($query) use ($clover_codes) {
																	foreach ($clover_codes as $cc) {
																		$query->orWhere('clover_code', '=', $cc);
																	}
																})
																->where('type', '=', 'normal')
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																->groupBy('cg_board.id')
																->get();

		// 보고서 받아오기
		$articles_report = DB::table('new_tb_clovernews')
												->where(function($query) use ($clover_codes) {
													foreach ($clover_codes as $cc) {
														$query->orWhere('new_tb_clovernews.clover_seq', '=', $cc);
													}
												})
												->leftJoin('new_tb_clover', 'new_tb_clovernews.clover_seq', '=', 'new_tb_clover.code')
												->leftJoin('new_tb_member', 'new_tb_clovernews.clover_seq', '=', 'new_tb_member.user_id')
												->select('new_tb_clovernews.*', 'new_tb_clover.subject as clover_name', 'new_tb_member.file_edit1 as profile_image')
												->orderBy('reg_date', 'desc')
												->get();

		// 일반 게시물, 보고서 소팅
		$articles_before = array();
		if (isset($articles_protected_normal)) {
			foreach ($articles_protected_normal as $apu) {
				array_push($articles_before, $apu);
			}
		}
		foreach ($articles_public_normal as $apu) {
			array_push($articles_before, $apu);
		}
		if (isset($articles_report)) {
			foreach ($articles_report as $ar) {
				array_push($articles_before, $ar);
			}
		}

		usort($articles_before, array("clovergarden\Http\Controllers\Api\ApiController", "sortTimeline"));

		// 순서를 설정하여 리턴
		$articles = array();
		if (!$only_report) {
			if (isset($articles_protected_urgency)) {
				foreach ($articles_protected_urgency as $apu) {
					array_push($articles, $apu);
				}
			}
			foreach ($articles_public_urgency as $apu) {
				array_push($articles, $apu);
			}
			foreach ($articles_before as $ab) {
				array_push($articles, $ab);
			}
		} else { // 보고서만 받아온다.
			if (isset($articles_report)) {
				foreach ($articles_report as $ar) {
					array_push($articles, $ar);
				}
			}
		}

		return json_encode($articles, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 전체 타임라인 받아오기 private 메소드
	 * protected-urgency, public-urgency 순. 일반(protected-normal, public-normal)과 보고서는 날짜순으로만 정렬
	 *
	 * @param  array $clover_codes  클로버 seq 배열
	 * @return array                타임라인 리스트
	 */
	private function getTimelineFromDBForAll($clover_codes = null, $only_report = false)
	{
		$user_id = 0;
		if (Auth::check()) {
			$user_id = Auth::user()->id;
		}

		// 클로버코드 배열이 비어있을 경우 $clover_codes를 예외 문자로 지정 (orWhere에서 비어있는 배열이면 전체 DB가 리턴되는 버그가 있음)
		if (count($clover_codes) == 0) {
			$clover_codes = array('???');
		}

		if ($clover_codes) { // clover_codes가 null이면 protected 게시물은 받아오지 않는다. (전체 타임라인 받아올 때 사용)
			// Protected, 후원 기관인 article 받아오기
			$articles_protected_urgency = DB::table('cg_board')
																		->where('limitation', '=', 'protected')
																		->where(function($query) use ($clover_codes) {
																			foreach ($clover_codes as $cc) {
																				$query->orWhere('clover_code', '=', $cc);
																			}
																		})
																		->where('type', '=', 'urgency')
																		->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																		->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																		->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																		->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																		->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																		->groupBy('cg_board.id')
																		->get();

			$articles_protected_normal = DB::table('cg_board')
																		->where('limitation', '=', 'protected')
																		->where(function($query) use ($clover_codes) {
																			foreach ($clover_codes as $cc) {
																				$query->orWhere('clover_code', '=', $cc);
																			}
																		})
																		->where('type', '=', 'normal')
																		->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																		->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																		->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																		->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																		->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																		->groupBy('cg_board.id')
																		->get();
		}

		// Public인 article 받아오기
		$articles_public_urgency = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'urgency')
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																->groupBy('cg_board.id')
																->get();

		$articles_public_normal = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'normal')
																->leftJoin('new_tb_clover', 'cg_board.clover_code', '=', 'new_tb_clover.code')
																->leftJoin('new_tb_member', 'cg_board.clover_code', '=', 'new_tb_member.user_id')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->leftJoin('cg_board_like', function($join) use ($user_id) {
																			$join->on('cg_board_like.board_id', '=', 'cg_board.id')
																						->where('cg_board_like.user_id', '=', $user_id);
																		})
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'), 'new_tb_clover.seq as clover_id', 'new_tb_clover.subject as clover_name', 'new_tb_member.id as member_id', 'new_tb_member.file_edit1 as profile_image', 'cg_board_like.is_like as is_Ilike')
																->groupBy('cg_board.id')
																->get();

		// 보고서 받아오기
		if ($clover_codes) { // clover_codes가 null이면 보고서를 받아오지 않는다. (보고서는 무조건 protected or private)
			$articles_report = DB::table('new_tb_clovernews')
													->where(function($query) use ($clover_codes) {
														foreach ($clover_codes as $cc) {
															$query->orWhere('new_tb_clovernews.clover_seq', '=', $cc);
														}
													})
													->leftJoin('new_tb_clover', 'new_tb_clovernews.clover_seq', '=', 'new_tb_clover.code')
													->leftJoin('new_tb_member', 'new_tb_clovernews.clover_seq', '=', 'new_tb_member.user_id')
													->select('new_tb_clovernews.*', 'new_tb_clover.subject as clover_name', 'new_tb_member.file_edit1 as profile_image')
													->orderBy('reg_date', 'desc')
													->get();
		}

		// 일반 게시물, 보고서 소팅
		$articles_before = array();
		if (isset($articles_protected_normal)) {
			foreach ($articles_protected_normal as $apu) {
				array_push($articles_before, $apu);
			}
		}
		foreach ($articles_public_normal as $apu) {
			array_push($articles_before, $apu);
		}
		if (isset($articles_report)) {
			foreach ($articles_report as $ar) {
				array_push($articles_before, $ar);
			}
		}

		usort($articles_before, array("clovergarden\Http\Controllers\Api\ApiController", "sortTimeline"));

		// 순서를 설정하여 리턴
		$articles = array();
		if (!$only_report) {
			if (isset($articles_protected_urgency)) {
				foreach ($articles_protected_urgency as $apu) {
					array_push($articles, $apu);
				}
			}
			foreach ($articles_public_urgency as $apu) {
				array_push($articles, $apu);
			}
			foreach ($articles_before as $ab) {
				array_push($articles, $ab);
			}
		} else { // 보고서만 받아온다.
			if (isset($articles_report)) {
				foreach ($articles_report as $ar) {
					array_push($articles, $ar);
				}
			}
		}

		return json_encode($articles, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 사용자가 후원하는 기관의 코드 배열 반환
	 *
	 * @param  $option 'WithPrice' 일 경우 가격도 반환
	 * @return array 기관 코드 배열
	 */
	private function getUserCloverCodesWithPrices()
	{
		$clover_seq_adm_type = explode("[@@]", Auth::user()->clover_seq_adm_type);
		$clover_list = null;
		$rValue = array();
		$clover_codes = array();
		$clover_prices = array();

		if(count($clover_seq_adm_type) > 1){ // 후원변경 신청. 관리자의 승인이 된 경우
			if($clover_seq_adm_type[1] == 'ok')
				$clover_list = explode("[@@@]", Auth::user()->clover_seq);

		} else { // 후원변경 신청. 관리자의 승인이 안된 경우
			$ex_clover_seq_adm = explode("[@@@@]", Auth::user()->clover_seq_adm);
			$clover_list = explode("[@@@]", $ex_clover_seq_adm[0]);
		}

		// 코드 배열 생성 (코드 + 가격)
		if (!empty($clover_list[0])) { // explode 때문에 $clover_list가 없더라도 0 인덱스에 빈 스트링이 들어간다.
			foreach ($clover_list as $cl) {
				array_push($clover_codes, explode("[@@]", $cl)[0]);
				array_push($clover_prices, explode("[@@]", $cl)[1]);
			}
		}

		// 후원변경 신청을 하지 않은 경우 (디버깅 필요함)
		if (Auth::user()->clover_seq == '' || empty($clover_list[0])) {
			$clover_list = DB::table('new_tb_clover_mlist')
											->where('id', '=', Auth::user()->user_id)
											->where('order_adm_ck', '=', 'y')
											->select('clover_seq', 'price')
											->groupBy('clover_seq')
											->get();

			// 코드 배열 생성
			$clover_codes = array(); // 예외 대비 초기화
			$clover_prices = array(); // 예외 대비 초기화
			foreach ($clover_list as $cl) {
				array_push($clover_codes, $cl->clover_seq);
				array_push($clover_prices, $cl->price);
			}
		}

		$rValue['clover_codes'] = $clover_codes;
		$rValue['clover_prices'] = $clover_prices;

		return $rValue;
	}

}
