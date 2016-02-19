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

    $nBeforeAfter = new BeforeAfterClass(); //수술갤러리
  
    $nBeforeAfter->subject = NullVal($_POST['subject'], 1, $list_link);
	$nBeforeAfter->category       = NullVal($_POST['category'], 1, $list_link);

	$nBeforeAfter->media_seq = $_POST['media_seq'];
	$nBeforeAfter->real_seq = $_POST['real_seq'];

    $nBeforeAfter->file_real[1] = $_POST['file_real1'];
    $nBeforeAfter->file_edit[1] = $_POST['file_edit1'];
    $nBeforeAfter->file_byte[1] = $_POST['file_byte1'];
	$nBeforeAfter->file_real[2] = $_POST['file_real2'];
    $nBeforeAfter->file_edit[2] = $_POST['file_edit2'];
    $nBeforeAfter->file_byte[2] = $_POST['file_byte2'];
	$nBeforeAfter->file_real[3] = $_POST['file_real3'];
    $nBeforeAfter->file_edit[3] = $_POST['file_edit3'];
    $nBeforeAfter->file_byte[3] = $_POST['file_byte3'];
	$nBeforeAfter->file_real[4] = $_POST['file_real4'];
    $nBeforeAfter->file_edit[4] = $_POST['file_edit4'];
    $nBeforeAfter->file_byte[4] = $_POST['file_byte4'];

    $nBeforeAfter->file_pre_name[1] = $nBeforeAfter->file_edit[1];
	$nBeforeAfter->file_pre_name[2] = $nBeforeAfter->file_edit[2];
	$nBeforeAfter->file_pre_name[3] = $nBeforeAfter->file_edit[3];
	$nBeforeAfter->file_pre_name[4] = $nBeforeAfter->file_edit[4];


    $check_del[1] = $_POST['check_del1'];
	$check_del[2] = $_POST['check_del2'];
	$check_del[3] = $_POST['check_del3'];
	$check_del[4] = $_POST['check_del4'];

    for($cnt_file=1; $cnt_file <= $nBeforeAfter->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/korea/bna/', $nBeforeAfter->code.'_'.$cnt_file.'_', $nBeforeAfter->file_volume[$cnt_file], $nBeforeAfter->file_mime_type[$cnt_file]);
            $nBeforeAfter->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nBeforeAfter->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nBeforeAfter->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nBeforeAfter->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nBeforeAfter->file_real[$cnt_file] = '';
                $nBeforeAfter->file_edit[$cnt_file] = '';
                $nBeforeAfter->file_byte[$cnt_file] = '';
            }else{
                $nBeforeAfter->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'category', 'media_seq', 'real_seq', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'file_real3', 'file_edit3', 'file_byte3', 'file_real4', 'file_edit4', 'file_byte4'
    );

    $arr_value = array
    (
        $nBeforeAfter->subject, $nBeforeAfter->category, $nBeforeAfter->media_seq, $nBeforeAfter->real_seq, $nBeforeAfter->file_real[1], $nBeforeAfter->file_edit[1], $nBeforeAfter->file_byte[1], $nBeforeAfter->file_real[2], $nBeforeAfter->file_edit[2], $nBeforeAfter->file_byte[2], $nBeforeAfter->file_real[3], $nBeforeAfter->file_edit[3], $nBeforeAfter->file_byte[3], $nBeforeAfter->file_real[4], $nBeforeAfter->file_edit[4], $nBeforeAfter->file_byte[4]
    );

//======================== DB Module Realt ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nBeforeAfter->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nBeforeAfter->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nBeforeAfter->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_pre_name[$cnt_file])) unlink('../../up_file/korea/bna/'.$nBeforeAfter->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>