<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nClovernews = new ClovernewsClass(); //후원기관

    $nClovernews->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nClovernews->clover_seq        = $_POST['clover_seq'];
    $nClovernews->file_real[1] = $_POST['file_real1'];
    $nClovernews->file_edit[1] = $_POST['file_edit1'];
    $nClovernews->file_byte[1] = $_POST['file_byte1'];
	$nClovernews->file_real[2] = $_POST['file_real2'];
    $nClovernews->file_edit[2] = $_POST['file_edit2'];
    $nClovernews->file_byte[2] = $_POST['file_byte2'];
    $nClovernews->category = $_POST['category'];



	$nClovernews->url = $_POST['url'];

    for($cnt_file=1; $cnt_file <= $nClovernews->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/clover/', $nClovernews->code.'_'.$cnt_file.'_', $nClovernews->file_volume[$cnt_file], $nClovernews->file_mime_type[$cnt_file]);
            $nClovernews->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClovernews->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClovernews->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClovernews->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }


	$countarray = count($_FILES['multifile']['name']);

	for($i=0; $i < $countarray; $i++) {

        if($_FILES['multifile']['name'][$i]){
			
            $arr_mfile[$i] = FileMultiUpload($_FILES['multifile']['name'][$i],$_FILES['multifile']['size'][$i], $_FILES['multifile']['tmp_name'][$i],  '../../up_file/clovernews/', $i.'_', 10, 'image');            
			$multifile_real[$i] = $arr_mfile[$i][0];
            $multifile_edit[$i] = $arr_mfile[$i][1];
            $multifile_byte[$i] = $arr_mfile[$i][2];
        }
	}
	@$nClovernews->multifile_real = join(',',$multifile_real);
	@$nClovernews->multifile_edit = join(',',$multifile_edit);
	@$nClovernews->multifile_byte = join(',',$multifile_byte);

    $arr_field = array
    (
        'subject', 'clover_seq', 'category', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'multifile_real', 'multifile_edit', 'multifile_byte', 'url'
    );

    $arr_value = array
    (
        $nClovernews->subject, $nClovernews->clover_seq, $nClovernews->category, $nClovernews->file_real[1], $nClovernews->file_edit[1], $nClovernews->file_byte[1], $nClovernews->file_real[2], $nClovernews->file_edit[2], $nClovernews->file_byte[2], $nClovernews->multifile_real, $nClovernews->multifile_edit, $nClovernews->multifile_byte, $nClovernews->url
    );

//======================== DB Module Clovernewst ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nClovernews->table_name, $arr_field, $arr_value);
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
