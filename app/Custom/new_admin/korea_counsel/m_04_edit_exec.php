<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $check_edit_seq = NullVal($_POST['edit_seq'], 1, $list_link);
    $check_edit_counsel_step = NullVal($_POST['edit_counsel_step'], 1, $list_link);
    $check_edit_counsel_state = NullVal($_POST['edit_counsel_state'], 1, $list_link);

    $nKakaocounsel = new KakaocounselClass(); //온라인상담

    $edit_cnt = 0;
    $arr_field= array('counsel_step', 'counsel_state');
    $arr_value = array();
    $arr_where = array();

    for($i=0, $cnt_list=count($_POST['edit_seq']); $i < $cnt_list; $i++) {
        if($_POST['edit_seq'][$i] != '' && $_POST['edit_counsel_step'][$i] != '' && $_POST['edit_counsel_state'][$i] != ''){
            $arr_value[$edit_cnt] = array($_POST['edit_counsel_step'][$i], $_POST['edit_counsel_state'][$i]);
            $arr_where[$edit_cnt] = $_POST['edit_seq'][$i];
            if($cnt_list != $edit_cnt) $edit_cnt = $edit_cnt + 1;
        }
    }

//======================== DB Module Start ============================
$Conn = new DBClass();


    $Conn->StartTrans();
    if($edit_cnt != '0') $out_put = $Conn->UpdateMultiDB($nKakaocounsel->table_name, $arr_field, $arr_value, $arr_where, 'seq');
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================

	UrlReDirect(SUCCESS_EDIT, $list_link);

?>
