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

    $nPopup = new PopupClass(); //팝업

    $nPopup->hidden     = $_POST['hidden'];
    $nPopup->popup_type = $_POST['popup_type'];
    $nPopup->start_date = RequestAll($_POST['start_date']);
    $nPopup->end_date   = RequestAll($_POST['end_date']);
    $nPopup->popup_top  = $_POST['popup_top'];
    $nPopup->popup_left = $_POST['popup_left'];
    $nPopup->subject    = NullVal($_POST['subject'], 1, $list_link);
    $nPopup->url        = RequestAll($_POST['url']);

    $nPopup->file_real[1] = $_POST['file_real1'];
    $nPopup->file_edit[1] = $_POST['file_edit1'];
    $nPopup->file_byte[1] = $_POST['file_byte1'];

    $nPopup->file_pre_name[1] = $nPopup->file_edit[1];

    $check_del[1] = $_POST['check_del1'];

    for($cnt_file=1; $cnt_file <= $nPopup->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/popup/', 'POPUP_'.$cnt_file.'_', $nPopup->file_volume[$cnt_file], $nPopup->file_mime_type[$cnt_file]);
            $nPopup->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nPopup->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nPopup->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nPopup->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nPopup->file_real[$cnt_file] = '';
                $nPopup->file_edit[$cnt_file] = '';
                $nPopup->file_byte[$cnt_file] = '';
            }else{
                $nPopup->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'hidden', 'popup_type', 'start_date', 'end_date', 'popup_top', 'popup_left', 'subject', 'url', 'file_real1', 'file_edit1', 'file_byte1'
    );

    $arr_value = array
    (
        $nPopup->hidden, $nPopup->popup_type, $nPopup->start_date, $nPopup->end_date, $nPopup->popup_top, $nPopup->popup_left, $nPopup->subject, $nPopup->url, $nPopup->file_real[1], $nPopup->file_edit[1], $nPopup->file_byte[1]
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nPopup->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nPopup->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nPopup->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/popup/'.$nPopup->file_pre_name[$cnt_file])) unlink('../../up_file/popup/'.$nPopup->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================
	UrlReDirect(POPUP.' '.SUCCESS_EDIT, $list_link);
?>	