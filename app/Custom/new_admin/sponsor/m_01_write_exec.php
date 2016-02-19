<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nSponsor = new SponsorClass(); //후원기관

    $nSponsor->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nSponsor->content        = RepEditor($_POST['content']);
    $nSponsor->file_real[1] = $_POST['file_real1'];
    $nSponsor->file_edit[1] = $_POST['file_edit1'];
    $nSponsor->file_byte[1] = $_POST['file_byte1'];
	$nSponsor->url = $_POST['url'];

    for($cnt_file=1; $cnt_file <= $nSponsor->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/sponsor/', $nSponsor->code.'_'.$cnt_file.'_', $nSponsor->file_volume[$cnt_file], $nSponsor->file_mime_type[$cnt_file]);
            $nSponsor->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSponsor->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSponsor->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSponsor->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nSponsor->subject, $nSponsor->content, $nSponsor->file_real[1], $nSponsor->file_edit[1], $nSponsor->file_byte[1], $nSponsor->url
    );

//======================== DB Module Sponsort ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSponsor->table_name, $arr_field, $arr_value);
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
