<?php

namespace clovergarden\Http\Controllers;

use Input, DB, Auth, Route, Redirect, Hash;
use Socialite;
use Flash;

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

	public function snsLoginControl() {
		$driver = Route::getCurrentRoute()->getPath();
		switch($driver) {
			case 'login/facebook':
				return Socialite::driver('facebook')->redirect();
				break;
			case 'login/naver':
				return Socialite::driver('naver')->redirect();
				break;
			case 'login/kakao':
				return Socialite::driver('kakao')->redirect();
				break;
		}
	}

	public function snsLoginCallbackFacebook() {
		$user = Socialite::driver('facebook')->user(); // get data from user (sns)

		$user_name = $user->getName();
		$user_email = $user->getEmail();

		// Check whether user is exist
		$user_select = DB::table('new_tb_member')->select('id', 'social_type')->where('user_id', '=', $user_email)->get();
		if($user_select) {
			if(is_null($user_select[0]->social_type)) {
				Flash::warning('이미 가입되어 있는 아이디입니다. 일반회원으로 로그인해 주시길 바랍니다.');
				return redirect()->route('login');
			}

			if($user_select[0]->social_type == 'n') {
				Flash::warning('이미 가입되어 있는 아이디입니다. 네이버 로그인을 이용해 주시길 바랍니다.');
				return redirect()->route('login');
			}

			Auth::loginUsingId($user_select[0]->id);
		} else { // If not exist, add the user in DB
			$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2, 'user_id' => $user_email, 'user_name' => $user_name, 'social_type' => 'f']
				);

			Auth::loginUsingId($id);
		}

		Flash::success('정상적으로 로그인되었습니다.');
		return redirect()->intended('/');
	}

	public function snsLoginCallbackNaver() {
		$user = Socialite::driver('naver')->user(); // get data from user (sns)
		$user_name = $user->getName();
		$user_email = $user->getEmail();

		// Check whether user is exist
		$user_select = DB::table('new_tb_member')->select('id', 'social_type')->where('user_id', '=', $user_email)->get();
		if($user_select) {
			if(is_null($user_select[0]->social_type)) {
				Flash::warning('이미 가입되어 있는 아이디입니다. 일반회원으로 로그인해 주시길 바랍니다.');
				return redirect()->route('login');
			}

			if($user_select[0]->social_type == 'f') {
				Flash::warning('이미 가입되어 있는 아이디입니다. 페이스북 로그인을 이용해 주시길 바랍니다.');
				return redirect()->route('login');
			}

			Auth::loginUsingId($user_select[0]->id);
		} else { // If not exist, add the user in DB
			$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2, 'user_id' => $user_email, 'user_name' => $user_name, 'social_type' => 'n']
				);

			Auth::loginUsingId($id);
		}

		Flash::success('정상적으로 로그인되었습니다.');
		return redirect()->intended('/');
	}

	public function snsLoginCallbackKakao() {
		$user = Socialite::driver('kakao')->user(); // get data from user (sns)
		$user_name = $user->getName();
		$user_email = $user->getEmail();

		// Check whether user is exist
		$user_select = DB::table('new_tb_member')->select('id')->where('user_id', '=', $user_email)->get();
		if($user_select) {
			Auth::loginUsingId($user_select[0]->id);
		} else { // If not exist, add the user in DB
			$id = DB::table('new_tb_member')->insertGetId(
				['user_state' => 2, 'user_id' => $user_email, 'user_name' => $user_name]
				);

			Auth::loginUsingId($id);
		}

		return redirect()->route('home');
	}

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
		  		Flash::error('회원탈퇴된 회원입니다.');
		  		return Redirect::route('home');
		  	}

		  	if(Auth::user()->user_state == 4) {
		  		if(Auth::user()->login_ck != 'y') {
		  			Auth::logout();
		  			Flash::error('승인 전인 기업회원입니다.');
		  			return Redirect::route('home');
		  		}
		  	}

		  	Flash::success('정상적으로 로그인되었습니다.');
		  	return Redirect::intended('/');
	      //return Redirect::to('home')
	          //->with('flash_notice', 'You are successfully logged in.');
		  } else {
		  	Flash::error('비밀번호 또는 아이디가 정확하지 않습니다.');
		  	return Redirect::intended('/login');
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

	  $nMember = new \MemberClass();     //회원

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nMember->where = "where user_name = '".$user_name."' and user_cell = '".$user_cell."'";

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, '*', $nMember->where, null, null);

	    if(count($nMember->read_result) != 0){
	        $nMember->VarList($nMember->read_result);
	    }else{
	        $Conn->DisConnect();

	        Flash::error('입력하신 이름(실명), 연락처와 일치하는 회원 정보가 없습니다.');
	        return redirect()->route('login', array('cate' => 5, 'dep01' => 1));
	    }

		$Conn->DisConnect();
		//======================== DB Module End ===============================

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
																						 'nMember' => $nMember
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

		if($nMember->read_result) {
			$subject = "[클로버가든] 비밀번호 찾기 메일입니다.";
			$rand_num = rand(10000,99999);
			$user_pw    = RequestAll(md5(strtolower($rand_num)));
			$content = "<html><head></head><body>회원님의 임시비밀번호는 <B>".$rand_num."</B>입니다. 로그인 후 수정해 주시기 바랍니다.</body></html>";


	    $Conn->StartTrans();
	    
	    $mail = \MailHelper::sendMail($user_id, $subject, $content);

	    if($mail){
				$sql = "update new_tb_member set password='" . $user_pw . "' where user_id='".$user_id."'";
				mysql_query($sql);

        Flash::success('메일 전송되었습니다.');
				return redirect()->route('login', array('cate' => 5, 'dep01' => 2));
	    } else {
        Flash::error('메일 전송 실패');
				return redirect()->route('login', array('cate' => 5, 'dep01' => 2));
	    }

			$Conn->DisConnect();
			//======================== DB Module End ===============================
		} else {
			Flash::error('입력하신 이메일(아이디), 이름(실명), 연락처와 일치하는 회원 정보가 없습니다.');
			return redirect()->route('login', array('cate' => 5, 'dep01' => 2));
		}
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
  		return redirect()->route('mypage', array('cate' => 6, 'dep01' => 3, 'dep02' => 0));
    } else {
			return redirect()->route('mypage', array('cate' => 6, 'dep01' => 3, 'dep02' => 0, 'type' => 'edit'));
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
  	// 탈퇴 시도하려는 회원과 동일회원인지 비교
  	if(Auth::user()->id != $_GET['mseq']) {
  		Flash::error('잘못된 접근입니다.');
  		return redirect()->route('home');
  	}

  	// 회원 탈퇴 보안에 문제가 많음. 1.탈퇴하려는 회원과 현재 회원이 같음을 비교해야 함.
  	$nMember = new \MemberClass(); //회원

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

	  $nMember->where = "where user_id ='" . Auth::user()->user_id . "'";

		$sql = "update ".$nMember->table_name." set user_state='-1' ".$nMember->where;
		mysql_query($sql);

		$Conn->DisConnect();
		//======================== DB Module End ===============================

		// 로그아웃
		Flash::success('회원탈퇴가 완료 되었습니다.');
		return redirect()->route('logout');
  }


}

?>
