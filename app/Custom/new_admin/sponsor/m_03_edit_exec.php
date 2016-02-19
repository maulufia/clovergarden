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

    $nSCompany = new SCompanyClass(); //후원기관
  
    $nSCompany->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nSCompany->content        = RepEditor($_POST['content']);
    $nSCompany->file_real[1] = $_POST['file_real1'];
    $nSCompany->file_edit[1] = $_POST['file_edit1'];
    $nSCompany->file_byte[1] = $_POST['file_byte1'];
	
    $nSCompany->file_pre_name[1] = $nSCompany->file_edit[1];

    $check_del[1] = $_POST['check_del1'];

    for($cnt_file=1; $cnt_file <= $nSCompany->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/scompany/', $nSCompany->code.'_'.$cnt_file.'_', $nSCompany->file_volume[$cnt_file], $nSCompany->file_mime_type[$cnt_file]);
            $nSCompany->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSCompany->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSCompany->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSCompany->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nSCompany->file_real[$cnt_file] = '';
                $nSCompany->file_edit[$cnt_file] = '';
                $nSCompany->file_byte[$cnt_file] = '';
            }else{
                $nSCompany->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1'
    );

    $arr_value = array
    (
        $nSCompany->subject, $nSCompany->content, $nSCompany->file_real[1], $nSCompany->file_edit[1], $nSCompany->file_byte[1]
    );

//======================== DB Module SCompanyt ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSCompany->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nSCompany->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nSCompany->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/scompany/'.$nSCompany->file_pre_name[$cnt_file])) unlink('../../up_file/scompany/'.$nSCompany->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);

?>