<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $seq        = NullVal($_POST['seq'], 1, $list_link, 'numeric');
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nPostscript = new PostscriptClass(); //수술갤러리
  
    $nPostscript->subject = NullVal($_POST['subject'], 1, $list_link);
	$nPostscript->name       = NullVal($_POST['name'], 1, $list_link);
	$nPostscript->category       = NullVal($_POST['category'], 1, $list_link);
	$nPostscript->content        = RepEditor($_POST['content']);


    $arr_field = array
    (
        'subject', 'name', 'category', 'content'
    );

    $arr_value = array
    (
        $nPostscript->subject, $nPostscript->name, $nPostscript->category, $nPostscript->content
    );

//======================== DB Module Postscriptt ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nPostscript->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>