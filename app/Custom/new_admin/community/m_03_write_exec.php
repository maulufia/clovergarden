<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nBanner = new BannerClass(); //후원기관

    $nBanner->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
    $nBanner->file_real[1] = $_POST['file_real1'];
    $nBanner->file_edit[1] = $_POST['file_edit1'];
    $nBanner->file_byte[1] = $_POST['file_byte1'];
	$nBanner->url = $_POST['url'];

    for($cnt_file=1; $cnt_file <= $nBanner->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/banner/', $nBanner->code.'_'.$cnt_file.'_', $nBanner->file_volume[$cnt_file], $nBanner->file_mime_type[$cnt_file]);
            $nBanner->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nBanner->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nBanner->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nBanner->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nBanner->subject, $nBanner->file_real[1], $nBanner->file_edit[1], $nBanner->file_byte[1], $nBanner->url
    );

//======================== DB Module Bannert ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nBanner->table_name, $arr_field, $arr_value);
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