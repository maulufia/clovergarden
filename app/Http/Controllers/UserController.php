<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect, Hash;

class UserController extends Controller
{
	
	/*
	  |--------------------------------------------------------------------------
	  | User Control Methods
	  |--------------------------------------------------------------------------
	  |
	  | These methods below are for user control.
	  |
	  */
	  
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