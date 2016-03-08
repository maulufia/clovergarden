<?php

namespace clovergarden\Http\Controllers;

use Input, DB, Auth, Redirect, Hash;

class UserController extends Controller
{
	
	public function __construct() {
		include(app_path().'/Custom/_common/_global.php');
	}
	
	/*
	  |--------------------------------------------------------------------------
	  | User Control Methods
	  |--------------------------------------------------------------------------
	  |
	  | These methods below are for user control.
	  |
	  */
	  
  public function loginControl() {
  	$type = isset($_GET['type']) ? $_GET['type'] : null;
		if(is_null($type)){
			// MD5로 로그인 가능한 지 체크
		  $user = array(
		      'user_id' => Input::get('idu'),
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
		  		Auth::logout();
		  		return Redirect::route('home')->with('flash_notification.message', '회원탈퇴된 회원입니다.');
		  	}
		  	
		  	if(Auth::user()->user_state == 4) {
		  		if(Auth::user()->login_ck != 'y') {
		  			Auth::logout();
		  			return Redirect::route('home')->with('flash_notification.message', '승인 전인 기업회원입니다.');
		  		}
		  	}
		  	
		  	return Redirect::intended('/')->with('flash_notification.message', '정상적으로 로그인되었습니다.');
	      //return Redirect::to('home')
	          //->with('flash_notice', 'You are successfully logged in.');
		  } else {
		  	return Redirect::intended('/login')->with('flash_notification.message', '비밀번호 또는 아이디가 정확하지 않습니다.');
		  }
		  
		  // authentication failure! lets go back to the login page
		  return Redirect::route('login')
		      ->with('flash_error', 'Your username/password combination was incorrect.')
		      ->withInput();
		} else { // 로그인 POST가 아닐경우 (아이디 찾기, 비밀번호 찾기)
			if($type == 'id_step1') { // 아이디 찾기
				$user_data = new \StdClass();
				$user_data->user_name = isset($_POST['user_name']) ? $_POST['user_name'] : null;
				$user_data->user_cell = isset($_POST['user_cell']) ? $_POST['user_cell'] : null;
				
				return $this->showID($user_data);
			}
			
			if($type == 'pw_step1') { // 비밀번호 찾기
				$user_data = new \StdClass();
				$user_data->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
				$user_data->user_name = isset($_POST['user_name']) ? $_POST['user_name'] : null;
				$user_data->user_cell = isset($_POST['user_cell']) ? $_POST['user_cell'] : null;
				
				return $this->showPW($user_data);
			}
		}
  }
  
  private function showID($user_data) {
		$sub_cate = 5; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;
		
		$user_name = $user_data->user_name;
		$user_cell = $user_data->user_cell;
		
		return view('front.page.login.show_id', ['cate' => $sub_cate,
																						 'sub_cate' => $sub_cate,
																						 'cate_file' => $cate_file,
																						 'cate_name' => $cate_name,
																						 'sub_cate' => $sub_cate,
																						 'dep01' => $dep01,
																						 'dep02' => $dep02,
																						 'dep02_active' => $dep02_active,
																						 'cate_01_result' => $cate_01_result,
																						 'cate_01_count' => $cate_01_count,
																						 'cate_01_type' => $cate_01_type,
																						 'cate_02_result' => $cate_02_result,
																						 'user_name' => $user_name,
																						 'user_cell' => $user_cell
																						 ]);
  }
  
  private function showPW($user_data) {
		$user_id          = $user_data->user_id;
    $user_name        = $user_data->user_name;
    $user_cell        = $user_data->user_cell;
    
    $list_link = route('login', array('cate' => 5, 'dep01' => 2));

    $nMember = new \MemberClass();     //회원

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nMember->where = "where user_id= '".$user_id."' and user_name = '".$user_name."' and user_cell = '".$user_cell."'";

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, '*', $nMember->where, null, null);

		$subject = "클로버가든 비밀번호 찾기 메일입니다.";

		$rand_num = rand(10000,99999);
		$user_pw    = RequestAll(md5(strtolower($rand_num)));
		$content = "회원님의 임시비밀번호는 <B>".$rand_num."</B>입니다. 로그인 후 수정해 주시기 바랍니다.";


    $Conn->StartTrans();
    
    //$mail = sendMail("클로버가든", "master@clovergarden.co.kr", $user_name, $user_id, $subject, $content, $isDebug=0);
    $mail = \MailHelper::sendMail("클로버가든", "master@clovergarden.co.kr", $user_name, $user_id, $subject, $content, $isDebug=0);

    if($mail){
		$sql = "update new_tb_member set password='" . $user_pw . "' where user_id='".$user_id."'";
		mysql_query($sql);
        UrlReDirect("메일 전송 되었습니다.", $list_link);
    } else {
        JsAlert("메일 전송 실패", 1, $list_link);
    }

		$Conn->DisConnect();
		//======================== DB Module End ===============================
  }
  
  public function checkMember() {
  	// NON
  }
  
  public function checkPassword() {
    $checkPw = strtolower($_POST['passwd']);

    $nMember = new \MemberClass();     //회원
		
		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, '*', "where user_id ='" . Auth::user()->user_id . "'", null, null);
    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result);
    }else{
        $Conn->DisConnect();
    }

		$Conn->DisConnect();
		//======================== DB Module End ===============================
    if(strcmp($nMember->user_id, Auth::user()->user_id) != 0){
      // 에러 리다이렉트
    	// 이 구문이 왜 필요한지 개발한 사람은 아는 걸까?
    } elseif( !($this->isMD5Password($checkPw, $nMember->user_pw) || $this->isBcryptPassword($checkPw, $nMember->user_pw)) ){
      // 비밀번호가 MD5, Bcrypt 둘 다 틀릴 경우
  		return redirect()->route('mypage', array('cate' => 6, 'dep01' => 5, 'dep02' => 0));
    } else {
			return redirect()->route('mypage', array('cate' => 6, 'dep01' => 5, 'dep02' => 0, 'type' => 'edit'));  
    }

  }
  
  // return true if password correct
  private function isMD5Password($password_input, $password_db) {
  	if ($password_db == md5($password_input)) {
  		return true;
  	}
  	return false;
  }
  
  // return true if password correct
  private function isBcryptPassword($password_input, $password_db) {
  	if (Hash::check($password_input, $password_db)) {
  		return true;
  	}
  	return false;
  }
  
  public function userdrop() {
  	// 회원 탈퇴 보안에 문제가 많음. 1.탈퇴하려는 회원과 현재 회원이 같음을 비교해야 함.
  	$nMember = new \MemberClass(); //회원

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

	  $mseq = $_GET['mseq'];
	  $nMember->where = "where user_id ='" . Auth::user()->user_id . "'";
	  
		$sql = "update ".$nMember->table_name." set user_state='-1' ".$nMember->where;
		mysql_query($sql);
	
		$Conn->DisConnect();
		//======================== DB Module End ===============================
		
		// 로그아웃
		return redirect()->route('logout');
  }

	
}

?>