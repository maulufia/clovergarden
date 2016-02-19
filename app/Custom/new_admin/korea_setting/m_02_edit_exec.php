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

    $nSns = new SnsClass(); //팝업

    $nSns->type     = $_POST['type'];
    $nSns->subject    = NullVal($_POST['subject'], 1, $list_link);
    $nSns->url        = RequestAll($_POST['url']);

    $arr_field = array
    (
        'type', 'subject', 'url'
    );

    $arr_value = array
    (
        $nSns->type, $nSns->subject, $nSns->url
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSns->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        $Conn->CommitTrans();    }

$Conn->disConnect();
//======================== DB Module End ===============================
	UrlReDirect(SUCCESS_EDIT, $list_link);
?>	