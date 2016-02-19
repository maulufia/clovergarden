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

    $nReal = new RealClass(); //수술갤러리
  
    $nReal->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nReal->content = $_POST['content'];
    $nReal->file_real[1] = $_POST['file_real1'];
    $nReal->file_edit[1] = $_POST['file_edit1'];
    $nReal->file_byte[1] = $_POST['file_byte1'];
	$nReal->file_real[2] = $_POST['file_real2'];
    $nReal->file_edit[2] = $_POST['file_edit2'];
    $nReal->file_byte[2] = $_POST['file_byte2'];
	$nReal->file_real[3] = $_POST['file_real3'];
    $nReal->file_edit[3] = $_POST['file_edit3'];
    $nReal->file_byte[3] = $_POST['file_byte3'];
	$nReal->file_real[4] = $_POST['file_real4'];
    $nReal->file_edit[4] = $_POST['file_edit4'];
    $nReal->file_byte[4] = $_POST['file_byte4'];

    $nReal->file_pre_name[1] = $nReal->file_edit[1];
	$nReal->file_pre_name[2] = $nReal->file_edit[2];
	$nReal->file_pre_name[3] = $nReal->file_edit[3];
	$nReal->file_pre_name[4] = $nReal->file_edit[4];


    $check_del[1] = $_POST['check_del1'];
	$check_del[2] = $_POST['check_del2'];
	$check_del[3] = $_POST['check_del3'];
	$check_del[4] = $_POST['check_del4'];

    for($cnt_file=1; $cnt_file <= $nReal->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/korea/bna/', $nReal->code.'_'.$cnt_file.'_', $nReal->file_volume[$cnt_file], $nReal->file_mime_type[$cnt_file]);
            $nReal->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nReal->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nReal->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nReal->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nReal->file_real[$cnt_file] = '';
                $nReal->file_edit[$cnt_file] = '';
                $nReal->file_byte[$cnt_file] = '';
            }else{
                $nReal->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'file_real3', 'file_edit3', 'file_byte3', 'file_real4', 'file_edit4', 'file_byte4'
    );

    $arr_value = array
    (
        $nReal->subject, $nReal->content,  $nReal->file_real[1], $nReal->file_edit[1], $nReal->file_byte[1], $nReal->file_real[2], $nReal->file_edit[2], $nReal->file_byte[2], $nReal->file_real[3], $nReal->file_edit[3], $nReal->file_byte[3], $nReal->file_real[4], $nReal->file_edit[4], $nReal->file_byte[4]
    );

//======================== DB Module Realt ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nReal->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nReal->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nReal->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/korea/bna/'.$nReal->file_pre_name[$cnt_file])) unlink('../../up_file/korea/bna/'.$nReal->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>