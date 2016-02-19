<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

//======================== DB Module Start ============================
$Conn = new DBClass();
if($_POST[sentall] == "sentall"){
	$ex_email = $_POST['email'];
	$ex_name = $_POST['name'];
	$mailok = 0;
	$mailno = 0;
	$Conn->StartTrans();
	for($i=0; $i<count($ex_name); $i++){
		$_POST['content'] = str_replace("\\","",$_POST['content']);
		$mail = sendMail("클로버가든", "master@clovergarden.co.kr", $ex_name[$i], $ex_email[$i], $_POST['subject'], $_POST['content'], $isDebug=0);
		if($mail){
			$sql = "update new_tb_email set send_ck='ok', send_date='".mktime()."', send_subject='".$_POST[subject]."' where email='".$ex_email[$i]."' and name='".$ex_name[$i]."'";
			mysql_query($sql);

			$sql = "insert into new_tb_email_send set name='".$ex_name[$i]."', email='".$ex_email[$i]."', send_date='".mktime()."', send_subject='".$_POST[subject]."', send_content='".$_POST[content]."', send_ck='".$_POST[cktype]."'";
			mysql_query($sql);

			$mailok++;
		}else{
			$mailno++;
		}

	}

	UrlReDirect("메일 전송 성공 ".$mailok."건, 메일 전송 실패 ".$mailno."건", $list_link);
} else {
    $Conn->StartTrans();
	$_POST['content'] = str_replace("\\","",$_POST['content']);
    $mail = sendMail("클로버가든", "master@clovergarden.co.kr", $_POST['name'], $_POST['email'], $_POST['subject'], $_POST['content'], $isDebug=0);
    if($mail){
		$sql = "update new_tb_email set send_ck='ok' where email='".$_POST['email']."' and name='".$_POST['name']."'";
		mysql_query($sql);

		$sql = "insert into new_tb_email_send set name='".$_POST['name']."', email='".$_POST['email']."', send_date='".mktime()."', send_subject='".$_POST[subject]."', send_content='".$_POST[content]."', send_ck='".$_POST[cktype]."'";
		mysql_query($sql);

        UrlReDirect("메일 전송 성공", $list_link);
    }else{
        JsAlert("메일 전송 실패", 1, $list_link);
    }
}

$Conn->disConnect();
//======================== DB Module End ===============================
    
?>

