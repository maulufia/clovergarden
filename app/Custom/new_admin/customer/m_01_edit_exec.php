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


    $nNotice = new NoticeClass(); //새소식
  
    $nNotice->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nNotice->writer_name       = NullVal($_POST['writer_name'], 1, $list_link);
	$nNotice->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name', 'content'
    );

    $arr_value = array
    (
        $nNotice->subject, $nNotice->writer_name, $nNotice->content
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nNotice->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
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