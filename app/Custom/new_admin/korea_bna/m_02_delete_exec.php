<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $code       = NullVal($_POST['code'], 1, $list_link);
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = NullVal(join(',',$_POST['delete_seq']), 1, $list_link);

    $nSelf   = new SelfClass(); 

//======================== DB Module Selft ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSelf->table_name, "where seq in (".$seq.")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
    UrlReDirect(OPERATION.' '.SUCCESS_DELETE, $list_link);
?>
