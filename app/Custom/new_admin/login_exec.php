<?php
    ob_start();
    header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
    include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php';
    include $_SERVER[DOCUMENT_ROOT].'/_common/_user.php';

    $checkId = NullVal(RequestAll(strtolower($_POST['idu'])), 1, 'index.php');
    $checkPw = NullVal(RequestAll(strtolower($_POST['passwd'])), 1, 'index.php');

    $nMember = new MemberClass();     //관리자
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, '*', "where user_id ='".$checkId."'", null, null);
		if(count($nMember->read_result) != 0){
			$nMember->VarList($nMember->read_result);
		}else{
			$Conn->DisConnect();
			JsAlert(ERR_LOGIN, 1, 'index.php');
		}

	$Conn->DisConnect();
	//======================== DB Module End ===============================
    if(strcmp($nMember->user_id, $checkId) != 0){
        JsAlert(ERR_LOGIN, 1, 'index.php');
    }elseif(strcmp($nMember->user_pw, md5($checkPw)) != 0){
        JsAlert(ERR_LOGIN, 1, 'index.php');
    }else{
        switch($nMember->user_state)
        {
            case 1 : //관리자
				setcookie('login_id', base64_encode($nMember->user_id), 0, '/');
				setcookie('login_name', base64_encode($nMember->user_name), 0, '/');
				setcookie('login_cell', base64_encode($nMember->user_cell), 0, '/');
				setcookie('login_email', base64_encode($nMember->user_email), 0, '/');
				setcookie('login_state', base64_encode($nMember->user_state), 0, '/');
                UrlReDirect(null, './member/m_01_list.php');
                break;   
            case 10 : //관리자
				setcookie('login_id', base64_encode($nMember->user_id), 0, '/');
				setcookie('login_name', base64_encode($nMember->user_name), 0, '/');
				setcookie('login_cell', base64_encode($nMember->user_cell), 0, '/');
				setcookie('login_email', base64_encode($nMember->user_email), 0, '/');
				setcookie('login_state', base64_encode($nMember->user_state), 0, '/');
                UrlReDirect(null, './member/m_01_list.php');
                break;   
			case 6 : //일반회원
                setcookie('login_id', base64_encode($nMember->user_id), 0, '/');
				setcookie('login_name', base64_encode($nMember->user_name), 0, '/');
				setcookie('login_state', base64_encode($nMember->user_state), 0, '/');
				setcookie('group_name', base64_encode($nMember->user_id), 0, '/');
                UrlReDirect(null, './service/m_01_list.php');
                break;   
			case 4 : //일반회원
                setcookie('login_id', base64_encode($nMember->user_id), 0, '/');
				setcookie('login_name', base64_encode($nMember->user_name), 0, '/');
				setcookie('login_state', base64_encode($nMember->user_state), 0, '/');
				setcookie('group_name', base64_encode($nMember->group_name), 0, '/');
                UrlReDirect(null, './service/m_01_list.php');
                break;  
			case 2 : //일반회원
                JsAlert(ERR_MEMBER_JOIN, 1, 'index.php');
                break;
			case 5 : //일반회원
                JsAlert('관리자 및 기업회원만 이용가능합니다.', 1, 'index.php');
                break;
        }
    }
?>