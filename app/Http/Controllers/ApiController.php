<?php

namespace clovergarden\Http\Controllers;

use Auth, URL, Hash, DB, Input;

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
	 * @param  $phone 핸드폰 번호
	 * @return [JSON] 아이디
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
	private function formatPhoneNumberForSms($phone) {
		$str_phone1 = substr($phone, 0, 3);
		$str_phone2 = substr($phone, 3, 4);
		$str_phone3 = substr($phone, 7, 4);
		
		return $str_phone1 . "-" . $str_phone2 . "-" . $str_phone3;
	}
	
}