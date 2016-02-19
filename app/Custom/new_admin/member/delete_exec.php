<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/common/function/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/common/function/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $mode       = NullVal($_POST['mode'], 1, $list_link);
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nMember = new MemberClass(); //회원
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", "where seq = '".$seq."'");
    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result);

        $Conn->StartTrans();
        $out_put = $Conn->DeleteDB($nMember->table_name, "where seq = '".$seq."'");

        if(!$out_put){
            $Conn->RollbackTrans();
            $Conn->disConnect();
            JsAlert(ERR_DATABASE, 1, $list_link);
        }
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
UrlReDirect(SUCCESS_DELETE, $list_link);
?>
