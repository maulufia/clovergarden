<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nPopup = new PopupClass(); //팝업

    $nPopup->hidden     = $_POST['hidden'];
    $nPopup->popup_type = $_POST['popup_type'];
    $nPopup->start_date = RequestAll($_POST['start_date']);
    $nPopup->end_date   = RequestAll($_POST['end_date']);
    $nPopup->popup_top  = $_POST['popup_top'];
    $nPopup->popup_left = $_POST['popup_left'];
    $nPopup->subject    = NullVal($_POST['subject'], 1, $list_link);
    $nPopup->url        = RequestAll($_POST['url']);

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
    $out_put = $Conn->insertDB($nPopup->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
    UrlReDirect(POPUP.' '.SUCCESS_WRITE, $list_link);
?>