<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nFaq = new FaqClass(); //자주묻는질문

    $nFaq->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nFaq->writer_name       = NullVal($_POST['writer_name'], 1, $list_link);
	$nFaq->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name',  'content'
    );

    $arr_value = array
    (
        $nFaq->subject, $nFaq->writer_name, $nFaq->content
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nFaq->table_name, $arr_field, $arr_value);
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
