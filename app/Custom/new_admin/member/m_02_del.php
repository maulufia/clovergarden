<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $nClover   = new CloverClass(); 
	$list_link = './m_02_list.php';
//======================== DB Module Clovert ============================
$Conn = new DBClass();
	$_POST[s_email] = str_replace(",","','",$_POST[s_email]);
    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB('new_tb_member', "where user_id in ('".$_POST[s_email]."')");
    $out_put = $Conn->DeleteDB('new_tb_clover_mlist', "where id in ('".$_POST[s_email]."')");
    $out_put = $Conn->DeleteDB('new_tb_message', "where send_id in ('".$_POST[s_email]."')");
    $out_put = $Conn->DeleteDB('new_tb_point', "where userid in ('".$_POST[s_email]."')");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
    UrlReDirect(SUCCESS_DELETE, $list_link);
?>
