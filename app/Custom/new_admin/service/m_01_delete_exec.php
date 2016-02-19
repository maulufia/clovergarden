<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);


    $seq = NullVal(join(',',$_POST['delete_seq']), 1, $list_link);

    $nSchedule   = new ScheduleClass(); //수술갤러리
	$nSchedulepeo = new SchedulepeoClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSchedule->table_name, "where seq in (".$seq.")");
    $out_put2 = $Conn->DeleteDB($nSchedulepeo->table_name, "where schedule_seq in (".$seq.")");


    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
    UrlReDirect(SUCCESS_DELETE, $list_link);
?>
