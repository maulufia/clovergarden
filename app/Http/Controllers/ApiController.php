<?php

namespace clovergarden\Http\Controllers;

use Auth, URL, Hash, DB, Input;
use DateTime;

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
	  $user = array(
	      'user_id' => Input::get('id'),
	      'password' => Input::get('passwd')
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
	  	
	  	return response()->json(['success' => 'login_success', 'token' => $api_token], 401);
	  } else {
	  	return response()->json(['error' => 'invalid_credentials'], 401);
	  }
	  
	  return response()->json(['error' => 'invalid_credentials'], 401);
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
		
		$password_bcrypt = Hash::make($password);
		
		$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2, 'user_id' => $user_email, 'password' => $password_bcrypt, 'user_name' => $user_name, 'user_cell' => $phone, 'social_type' => 'n']
				);
		
		return response()->json(['success' => 'join_success', 'id' => $id], 401);
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
			return response()->json(['id' => $user->user_id], 401);
		}
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
		return json_encode($user);
	}
	
	/**
	 * 내 정보 불러오기
	 * @return [JSON] 유저 이름, 소속 기업, 썸네일(URL), 총 후원 금액
	 */
	public function getMyDetail()
	{
		$member = DB::table('new_tb_member')->where('user_id', '=', Auth::user()->user_id)->first();
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->sum('price'); // 정기후원은 테스트 확인
		
		$user_detail = array(
				'user_name' => $member->user_name,
				'group_name' => $member->group_name,
				'thumbnail' => $this->BASE_URL . "/imgs/up_file/member/" . $member->file_edit1,
				'total_support' => $member_sup
			);
		
		return json_encode($user_detail);
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
			$fileName = Auth::user()->user_id . '.' . $fileExt;
			Input::file('upload_pic')->move($destinationPath, $fileName);
		
			// Manipulate Database
			$user = DB::table('new_tb_member')->where('user_id', '=', Auth::user()->user_id)->update(['file_edit1' => "imgs/up_file/member/" . $fileName]);

			
			return response()->json(['success' => 'upload_success'], 401);
		}
		
		return response()->json(['error' => 'failed_to_upload'], 401);
	}
	
	/**
	 * 내 후원 내역 불러오기
	 * @return [JSON] otype 후원 형태
	 * @return [JSON] clover_seq 후원 기관 코드 (id 아님)
	 * @return [JSON] price 후원 금액
	 * @return [JSON] 이하 생략
	 */
	public function getSupList()
	{
		$member_sup = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->get(); // 정기후원은 테스트 확인
		return json_encode($member_sup);
	}
	
	/**
	 * 후원 기관 목록 불러옴
	 * @return seq 							후원 기관 ID
	 * @return code 						후원 기관 변경 때 쓰이는 code
	 * @return subject 					후원 기관 명
	 * @return mobile_intro 		후원 기관 소개 (모바일용)
	 * @return mobile_thumbnail 후원 기관 리스트 썸네일 (모바일용)
	 * @return mobile_image 		후원 기관 이미지 (모바일용)
	 */
	public function getListOfCompany()
	{
		$companyList = DB::table('new_tb_clover')->select('seq', 'code', 'subject', 'mobile_intro', 'mobile_thumbnail', 'mobile_image')->get();
		return json_encode($companyList);
	}	
	
	/**
	 * 후원 기관 세부 내역
	 * @param  [Integer] $company_id 후원 기관 id
	 * @return [JSON] 	             getListOfCompany()의 return 형식과 같음
	 */
	public function getCompanyDetail($company_id)
	{
		$company = DB::table('new_tb_clover')->where('seq', '=', $company_id)->select('seq', 'code', 'subject', 'mobile_intro', 'mobile_thumbnail', 'mobile_image')->first();
		return json_encode($company);
	}
	
	/**
	 * 공지사항 받아오기
	 * @return [JSON] 제목, 내용, 등록일
	 */
	public function getNotices()
	{
		$notices = DB::table('new_tb_notice')->select('subject', 'content', 'reg_date')->get();
		return json_encode($notices);
	}
	
	/**
	 * 자주하는 질문 받아오기
	 * @return [JSON] 제목, 내용, 등록일
	 */
	public function getFaqs()
	{
		$faqs = DB::table('new_tb_faq')->select('subject', 'content', 'reg_date')->get();
		return json_encode($faqs);
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
		
		return json_encode($data);
	}
	
	/**
	 * 사용자의 타임라인을 받아온다.
	 * 후원기관 목록을 얻어오는 것은 나중에 private 메소드로 분리할 것.
	 * protected-urgency, public-urgency 순. 일반과 보고서는 날짜순으로만 정렬.
	 * @param int only_report 리포트만 받아온다. (0: false, 1: true)
	 * @return JSON 게시물 리스트 + 댓글 수
	 */
	public function getTimelineList()
	{	
		$clover_seq_adm_type = explode("[@@]", Auth::user()->clover_seq_adm_type);
		$clover_list = null;
		$clover_codes = array();
		
		if(count($clover_seq_adm_type) > 1){ // 후원변경 신청. 관리자의 승인이 된 경우
			if($clover_seq_adm_type[1] == 'ok')
				$clover_list = explode("[@@@]", Auth::user()->clover_seq);
		} else { // 후원변경 신청. 관리자의 승인이 안된 경우
			$ex_clover_seq_adm = explode("[@@@@]", Auth::user()->clover_seq_adm);
			$clover_list = explode("[@@@]", $ex_clover_seq_adm[0]);
		}
		
		// 코드 배열 생성
		foreach ($clover_list as $cl) {
			array_push($clover_codes, explode("[@@]", $cl)[0]);
		}
		
		// 후원변경 신청을 하지 않은 경우
		if (Auth::user()->clover_seq == '' || empty($clover_list[0])) {
			$clover_list = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->select('clover_seq')->get();
		
			// 코드 배열 생성
			$clover_codes = array(); // 예외 대비 초기화
			foreach ($clover_list as $cl) {
				array_push($clover_codes, $cl->clover_seq);
			}
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
																	->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																	->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'))
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
																	->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																	->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'))
																	->groupBy('cg_board.id')
																	->get();															
		
		// Public인 article 받아오기
		$articles_public_urgency = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'urgency')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'))
																->groupBy('cg_board.id')
																->get();
																
		$articles_public_normal = DB::table('cg_board')
																->where('limitation', '=', 'public')
																->where('type', '=', 'normal')
																->leftJoin('cg_board_comment', 'cg_board.id', '=', 'cg_board_comment.board_id')
																->select('cg_board.*', DB::raw('count(cg_board_comment.id) as comment_count'))
																->groupBy('cg_board.id')
																->get();
		
		// 보고서 받아오기
		$articles_report = DB::table('new_tb_clovernews')
												->where(function($query) use ($clover_codes) {
													foreach ($clover_codes as $cc) {
														$query->orWhere('clover_seq', '=', $cc);
													}
												})
												->get();
												
		// 일반 게시물, 보고서 소팅
		$articles_before = array();
		foreach ($articles_protected_normal as $apu) {
			array_push($articles_before, $apu);
		}
		foreach ($articles_public_normal as $apu) {
			array_push($articles_before, $apu);
		}
		foreach ($articles_report as $ar) {
			array_push($articles_before, $ar);
		}
		
		usort($articles_before, array("clovergarden\Http\Controllers\ApiController", "sortTimeline"));
		
		// 순서를 설정하여 리턴
		$articles = array();
		if (Input::get('only_report') ? Input::get('only_report') == 0 : true) {
			foreach ($articles_protected_urgency as $apu) {
				array_push($articles, $apu);
			}
			foreach ($articles_public_urgency as $apu) {
				array_push($articles, $apu);
			}
			foreach ($articles_before as $ab) {
				array_push($articles, $ab);
			}
		} else { // 보고서만 받아온다.
			foreach ($articles_report as $ar) {
				array_push($articles, $ar);
			}
		}
							
		return json_encode($articles);
	}
	
	/**
	 * 타임라인 게시물 세부 내용
	 * API 콜 시 조회수 +1
	 * @param int board_id 게시물 ID
	 * @return JSON 게시물 상세
	 */
	public function getTimelineDetail($board_id)
	{	
		$article = DB::table('cg_board')->where('id', '=', $board_id)->get();	
		DB::table('cg_board')->where('id', '=', $board_id)->increment('hit');
		
		return json_encode($article);
	}
	
	/**
	 * 타임라인 글쓰기
	 * @param string type 게시물 타입 (urgency: 긴급 후원, normal: 일반, report: 보고서 // report는 쓰이지 않음)
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @param file image 이미지 파일
	 * @param string limitation 권한 (public: 전체 공개, protected: 후원자만 공개, private: 비공개)
	 * @return JSON 성공 메시지
	 */
	public function writeTimeline()
	{
		$clover_code = Auth::user()->user_id;
		$type = Input::get('type');
		$text = Input::get('text');
		$image_path = null;
		$limitation = Input::get('limitation');
		
		// Image Upload
		if (Input::file('image') && Input::file('image')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';
			
			$fileExt = Input::file('image')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '.' . $fileExt;
			Input::file('image')->move($destinationPath, $fileName);
			
			$image_path = $fileName;
		}
		
		// Manipulate Database
		$dt = new DateTime;
		$article = DB::table('cg_board')->insert(
			['clover_code' => $clover_code,
			 'type' => $type,
			 'text' => $text,
			 'image_path' => $this->BASE_URL . "/imgs/up_file/board/" . $image_path,
			 'limitation' => $limitation,
			 'created_at' => $dt->format('y-m-d [H:i:s]'),
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]	
		);
		
		return response()->json(['success' => 'write_success'], 401);
	}
	
	/**
	 * 타임라인 수정
	 * @param int board_id ID
	 * @param string type 게시물 타입 (urgency: 긴급 후원, normal: 일반, report: 보고서 // report는 쓰이지 않음)
	 * @param string text 게시물 텍스트 (제목 아님)
	 * @param file image 이미지 파일
	 * @param string limitation 권한 (public: 전체 공개, protected: 후원자만 공개, private: 비공개)
	 * @return JSON 성공 메시지
	 */
	public function modifyTimeline($board_id)
	{
		$clover_code = Auth::user()->user_id;
		$type = Input::get('type');
		$text = Input::get('text');
		$image_path = null;
		$limitation = Input::get('limitation');
		
		// Image Upload
		if (Input::file('image') && Input::file('image')->isValid()) {
			$destinationPath = '/home/clovergarden/cg_app/public/imgs/up_file/board';
			
			$fileExt = Input::file('image')->getClientOriginalExtension();
			$fileName = Auth::user()->user_id . '_' . mt_rand(0, 99999) . '.' . $fileExt;
			Input::file('image')->move($destinationPath, $fileName);
			
			$image_path = $fileName;
		}
		
		// Manipulate Database
		$dt = new DateTime;
		$article = DB::table('cg_board')->where('id', '=', $board_id)->where('clover_code', '=', $clover_code)->update(
			['clover_code' => $clover_code,
			 'type' => $type,
			 'text' => $text,
			 'image_path' => $this->BASE_URL . "/imgs/up_file/board/" . $image_path,
			 'limitation' => $limitation,
			 'updated_at' => $dt->format('y-m-d [H:i:s]')
		 ]	
		);
		
		if (!$article) {
			return response()->json(['error' => 'update_failed'], 401);
		}
		
		return response()->json(['success' => 'update_success'], 401);
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
			return response()->json(['error' => 'delete_failed'], 401);
		}
		
		return response()->json(['success' => 'delete_success'], 401);
	}
	
	/**
	 * 타임라인 좋아요
	 * @param int board_id ID
	 * @return JSON 성공 메시지
	 */
	public function likeTimeline($board_id)
	{	
		$article = DB::table('cg_board')->where('id', '=', $board_id)->increment('like');
		
		return response()->json(['success' => 'like_success'], 401);
	}
	
	/**
	 * 타임라인 댓글 불러오기
	 * @param int board_id 게시물 ID
	 * @return JSON 댓글 리스트 (+ properties)
	 */
	public function getTimelineComment($board_id)
	{		
		$comments = DB::table('cg_board_comment')->where('board_id', '=', $board_id)->get();
		
		return json_encode($comments);
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
		
		return response()->json(['success' => 'write_comment_success'], 401);
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
			return response()->json(['error' => 'modify_comment_failed'], 401);
		}
		
		return response()->json(['success' => 'modify_comment_success'], 401);
	}
	
	/**
	 * 타임라인 댓글 삭제
	 * @param int comment_id 댓글 ID
	 * @return JSON 성공 메시지
	 */
	public function deleteTimelineComment($comment_id)
	{
		// Manipulate Database
		$dt = new DateTime;
		$comment = DB::table('cg_board_comment')->where('id', '=', $comment_id)->where('member_id', '=', Auth::user()->id)->delete();

		if (!$comment) {
			return response()->json(['error' => 'delete_comment_failed'], 401);
		}
		
		return response()->json(['success' => 'delete_comment_success'], 401);
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
			$org_clover_list = DB::table('new_tb_clover_mlist')->where('id', '=', Auth::user()->user_id)->where('order_adm_ck', '=', 'y')->select('price')->get();
		
			// 코드 배열 생성
			$org_clover_prices = 0; // 예외 대비 초기화
			foreach ($org_clover_list as $cl) {
				$org_clover_prices += $cl->price;
			}
		}
		
		// Original 가격과 새로운 가격 비교
		if ($org_clover_prices != $sum_of_prices) {
			return response()->json(['error' => 'price_not_matching'], 401);
		}

		// 중복된 값 확인
		if ( count($clover_codes) != count(array_unique($clover_codes)) ) {
			return response()->json(['error' => 'duplicated_clover_code'], 401);
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
		
		return response()->json(['success' => 'success_change_clover'], 401);
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
				$result = response()->json(['success' => 'send_success', 'rest' => $count], 401);
			}	else if($result_send == "reserved") {
				$result = response()->json(['success' => 'reserved_success', 'rest' => $count], 401);
			}	else if($result_send == "3205") {
				$result = response()->json(['error' => 'wrong_type_of_phone_number'], 401);
			}	else if($result_send == "0044") {
				$result = response()->json(['error' => 'spam_message'], 401);
			}	else {
				$result = response()->json(['error' => $result_send], 401);
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
	
}