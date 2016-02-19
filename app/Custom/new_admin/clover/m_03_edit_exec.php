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

    $nClovernews = new ClovernewsClass(); //후원기관
  
    $nClovernews->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nClovernews->clover_seq        = $_POST['clover_seq'];
    $nClovernews->file_real[1] = $_POST['file_real1'];
    $nClovernews->file_edit[1] = $_POST['file_edit1'];
    $nClovernews->file_byte[1] = $_POST['file_byte1'];	
	$nClovernews->file_real[2] = $_POST['file_real2'];
    $nClovernews->file_edit[2] = $_POST['file_edit2'];
    $nClovernews->file_byte[2] = $_POST['file_byte2'];

    $nClovernews->file_pre_name[1] = $nClovernews->file_edit[1];
	$nClovernews->file_pre_name[2] = $nClovernews->file_edit[2];

	$multifile_real[0] = $_POST['multifile_real[0]'];
	$multifile_real[1] = $_POST['multifile_real[1]'];
	$multifile_real[2] = $_POST['multifile_real[2]'];
	$multifile_real[3] = $_POST['multifile_real[3]'];
	$multifile_real[4] = $_POST['multifile_real[4]'];
	$multifile_real[5] = $_POST['multifile_real[5]'];
	$multifile_real[6] = $_POST['multifile_real[6]'];
	$multifile_real[7] = $_POST['multifile_real[7]'];
	$multifile_real[8] = $_POST['multifile_real[8]'];
	$multifile_real[9] = $_POST['multifile_real[9]'];

	$multifile_edit[0] = $_POST['multifile_edit[0]'];
	$multifile_edit[1] = $_POST['multifile_edit[1]'];
	$multifile_edit[2] = $_POST['multifile_edit[2]'];
	$multifile_edit[3] = $_POST['multifile_edit[3]'];
	$multifile_edit[4] = $_POST['multifile_edit[4]'];
	$multifile_edit[5] = $_POST['multifile_edit[5]'];
	$multifile_edit[6] = $_POST['multifile_edit[6]'];
	$multifile_edit[7] = $_POST['multifile_edit[7]'];
	$multifile_edit[8] = $_POST['multifile_edit[8]'];
	$multifile_edit[9] = $_POST['multifile_edit[9]'];

	$multifile_byte[0] = $_POST['multifile_byte[0]'];
	$multifile_byte[1] = $_POST['multifile_byte[1]'];
	$multifile_byte[2] = $_POST['multifile_byte[2]'];
	$multifile_byte[3] = $_POST['multifile_byte[3]'];
	$multifile_byte[4] = $_POST['multifile_byte[4]'];
	$multifile_byte[5] = $_POST['multifile_byte[5]'];
	$multifile_byte[6] = $_POST['multifile_byte[6]'];
	$multifile_byte[7] = $_POST['multifile_byte[7]'];
	$multifile_byte[8] = $_POST['multifile_byte[8]'];
	$multifile_byte[9] = $_POST['multifile_byte[9]'];

	$multifile_pre_name[0] = $multifile_edit[0];
	$multifile_pre_name[1] = $multifile_edit[1];
	$multifile_pre_name[2] = $multifile_edit[2];
	$multifile_pre_name[3] = $multifile_edit[3];
	$multifile_pre_name[4] = $multifile_edit[4];
	$multifile_pre_name[5] = $multifile_edit[5];
	$multifile_pre_name[6] = $multifile_edit[6];
	$multifile_pre_name[7] = $multifile_edit[7];
	$multifile_pre_name[8] = $multifile_edit[8];
	$multifile_pre_name[9] = $multifile_edit[9];

    $check_del[1] = $_POST['check_del1'];
	$check_del[2] = $_POST['check_del2'];

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
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nClovernews->file_real[$cnt_file] = '';
                $nClovernews->file_edit[$cnt_file] = '';
                $nClovernews->file_byte[$cnt_file] = '';
            }else{
                $nClovernews->file_pre_name[$cnt_file] = '';
            }
        }
    }

	$check_multi_del[0] = $_POST['check_multi_del0'];
	$check_multi_del[1] = $_POST['check_multi_del1'];
	$check_multi_del[2] = $_POST['check_multi_del2'];
	$check_multi_del[3] = $_POST['check_multi_del3'];
	$check_multi_del[4] = $_POST['check_multi_del4'];
	$check_multi_del[5] = $_POST['check_multi_del5'];
	$check_multi_del[6] = $_POST['check_multi_del6'];
	$check_multi_del[7] = $_POST['check_multi_del7'];
	$check_multi_del[8] = $_POST['check_multi_del8'];
	$check_multi_del[9] = $_POST['check_multi_del9'];

	$countarray = count($_FILES['multifile']['name']);
	for($i=0; $i < $countarray; $i++) {

        if($_FILES['multifile']['name'][$i]){
			
            $arr_mfile[$i] = FileMultiUpload($_FILES['multifile']['name'][$i],$_FILES['multifile']['size'][$i], $_FILES['multifile']['tmp_name'][$i],  '../../up_file/clovernews/', $i.'_', 10, 'image');            
			$multifile_real[$i] = $arr_mfile[$i][0];
            $multifile_edit[$i] = $arr_mfile[$i][1];
            $multifile_byte[$i] = $arr_mfile[$i][2];
        }else{
            if($check_multi_del[$i] == '1'){
                $multifile_real[$i] = '';
                $multifile_edit[$i] = '';
                $multifile_byte[$i] = '';
            }else{
				$multifile_pre_name[$i] = '';
			}
        }
	}
	$nClovernews->multifile_real = join(',',$multifile_real);
	$nClovernews->multifile_edit = join(',',$multifile_edit);
	$nClovernews->multifile_byte = join(',',$multifile_byte);


    $arr_field = array
    (
        'subject', 'clover_seq', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'multifile_real', 'multifile_edit', 'multifile_byte', 'url'
    );

    $arr_value = array
    (
		$nClovernews->subject, $nClovernews->clover_seq, $nClovernews->file_real[1], $nClovernews->file_edit[1], $nClovernews->file_byte[1], $nClovernews->file_real[2], $nClovernews->file_edit[2], $nClovernews->file_byte[2], $nClovernews->multifile_real, $nClovernews->multifile_edit, $nClovernews->multifile_byte, $nClovernews->url
    );

//======================== DB Module Clovernewst ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nClovernews->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nClovernews->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nClovernews->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/sponsor/'.$nClovernews->file_pre_name[$cnt_file])) unlink('../../up_file/sponsor/'.$nClovernews->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>