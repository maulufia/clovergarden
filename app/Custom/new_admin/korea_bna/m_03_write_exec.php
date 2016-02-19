<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nPostscript = new PostscriptClass(); //수술갤러리
	$nPostscript->subject       = NullVal($_POST['subject'], 1, $list_link);
	$nPostscript->name       = NullVal($_POST['name'], 1, $list_link);
	$nPostscript->category       = $_POST['category'];
	$nPostscript->content        = RepEditor($_POST['content']);
	$nPostscript->writer        = "admin";
	

    $arr_field = array
    (
        'writer', 'subject', 'name', 'category', 'content'
    );

    $arr_value = array
    (
       $nPostscript->writer, $nPostscript->subject, $nPostscript->name, $nPostscript->category, $nPostscript->content
    );

//======================== DB Module Postscriptt ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nPostscript->table_name, $arr_field, $arr_value);
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
