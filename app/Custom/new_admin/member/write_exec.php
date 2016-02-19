<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nMember = new MemberClass(); //회원
	
	$nMember->user_state    = "1";
    $nMember->user_id    = RequestAll($_POST['user_id']);
    $nMember->user_pw    = RequestAll($_POST['user_pw']);
	$nMember->user_name        = NullVal($_POST['user_name'], 1, $list_link);

    $arr_field = array
    (
       'user_state', 'user_id', 'user_pw', 'user_name'
    );

    $arr_value = array
    (
        $nMember->user_state, $nMember->user_id, $nMember->user_pw, $nMember->user_name
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nMember->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
    UrlReDirect(SUCCESS_WRITE, $list_link);
?>