<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nSchedule = new ScheduleClass(); //수술갤러리

    $nSchedule->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nSchedule->people       = NullVal($_POST['people'], 1, $list_link);
	$nSchedule->content        = RepEditor($_POST['content']);
	$nSchedule->work_date        = NullVal($_POST['work_date'], 1, $list_link);
	$nSchedule->start_date        = NullVal($_POST['start_date'], 1, $list_link);
	$nSchedule->start_date2        = NullVal($_POST['start_date2'], 1, $list_link);
	$nSchedule->clover_seq        = NullVal($_POST['clover_seq'], 1, $list_link);
    $nSchedule->file_real[1] = $_POST['file_real1'];
    $nSchedule->file_edit[1] = $_POST['file_edit1'];
    $nSchedule->file_byte[1] = $_POST['file_byte1'];


    for($cnt_file=1; $cnt_file <= $nSchedule->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/schedule/', $nSchedule->code.'_'.$cnt_file.'_', $nSchedule->file_volume[$cnt_file], $nSchedule->file_mime_type[$cnt_file]);
            $nSchedule->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSchedule->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSchedule->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSchedule->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'people', 'content', 'file_real1', 'file_edit1', 'file_byte1','work_date','start_date','start_date2','clover_seq'
    );

    $arr_value = array
    (
        $nSchedule->subject, $nSchedule->people, $nSchedule->content, $nSchedule->file_real[1], $nSchedule->file_edit[1], $nSchedule->file_byte[1], $nSchedule->work_date, $nSchedule->start_date, $nSchedule->start_date2, $nSchedule->clover_seq
    );
//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSchedule->table_name, $arr_field, $arr_value);
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
