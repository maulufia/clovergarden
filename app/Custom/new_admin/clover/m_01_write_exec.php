<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nClover = new CloverClass(); //후원기관
	$nMember = new MemberClass(); //회원

    $nClover->subject = NullVal(RequestAll($_POST['subject']), 1, $list_link);
	$nClover->content        = RepEditor($_POST['content']);
	$nClover->code        = RepEditor($_POST['code']);
	$nClover->category        = RepEditor($_POST['category']);
	$nClover->view_n        = RepEditor($_POST['view_n']);
	$nClover->hot        = RepEditor($_POST['hot']);
    $nClover->file_real[1] = $_POST['file_real1'];
    $nClover->file_edit[1] = $_POST['file_edit1'];
    $nClover->file_byte[1] = $_POST['file_byte1'];
	$nClover->file_real[2] = $_POST['file_real2'];
    $nClover->file_edit[2] = $_POST['file_edit2'];
    $nClover->file_byte[2] = $_POST['file_byte2'];

    for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '../../up_file/clover/', $nClover->code.'_'.$cnt_file.'_', $nClover->file_volume[$cnt_file], $nClover->file_mime_type[$cnt_file]);
            $nClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'code', 'category', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'view_n', 'hot'
    );

	$arr_field1 = array
	(
		'user_name', 'group_name', 'user_id', 'user_pw','user_state'
	);

    $arr_value = array
    (
        $nClover->subject, $nClover->content, $nClover->code,  $nClover->category,  $nClover->file_real[1], $nClover->file_edit[1], $nClover->file_byte[1], $nClover->file_real[2], $nClover->file_edit[2], $nClover->file_byte[2], $nClover->view_n, $nClover->hot
    );

	$arr_value1 = array(
		$nClover->subject, '', $nClover->code,  "81dc9bdb52d04dc20036dbd8313ed055", "6"
	);


//======================== DB Module Clovert ============================
$Conn = new DBClass();

	$Conn->insertDB($nMember->table_name, $arr_field1, $arr_value1);

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nClover->table_name, $arr_field, $arr_value);
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
