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

    $nSelf = new SelfClass(); //수술갤러리
  
	$nSelf->name       = NullVal($_POST['name'], 1, $list_link);
	$nSelf->surgery       = NullVal($_POST['surgery'], 1, $list_link);
	$nSelf->content        = RepEditor($_POST['content']);
    $nSelf->file_real[1] = $_POST['file_real1'];
    $nSelf->file_edit[1] = $_POST['file_edit1'];
    $nSelf->file_byte[1] = $_POST['file_byte1'];
	$nSelf->file_real[2] = $_POST['file_real1'];
    $nSelf->file_edit[2] = $_POST['file_edit1'];
    $nSelf->file_byte[2] = $_POST['file_byte1'];

    $nSelf->file_pre_name[1] = $nSelf->file_edit[1];
	$nSelf->file_pre_name[2] = $nSelf->file_edit[2];

    $check_del[1] = $_POST['check_del1'];
	$check_del[2] = $_POST['check_del2'];


    for($cnt_file=1; $cnt_file <= $nSelf->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/korea/self/', $nSelf->code.'_'.$cnt_file.'_', $nSelf->file_volume[$cnt_file], $nSelf->file_mime_type[$cnt_file]);
            $nSelf->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSelf->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSelf->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSelf->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nSelf->file_real[$cnt_file] = '';
                $nSelf->file_edit[$cnt_file] = '';
                $nSelf->file_byte[$cnt_file] = '';
            }else{
                $nSelf->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'name', 'surgery', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2'
    );

    $arr_value = array
    (
        $nSelf->name, $nSelf->surgery, $nSelf->content, $nSelf->file_real[1], $nSelf->file_edit[1], $nSelf->file_byte[1], $nSelf->file_real[2], $nSelf->file_edit[2], $nSelf->file_byte[2]
    );

//======================== DB Module Selft ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSelf->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nSelf->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nSelf->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/korea/self/'.$nSelf->file_pre_name[$cnt_file])) unlink('../../up_file/korea/self/'.$nSelf->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>