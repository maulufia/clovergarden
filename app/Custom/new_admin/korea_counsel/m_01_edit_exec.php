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

    $cate_seq = $_POST['cate_seq'];

    $nBeforecounsel = new BeforecounselClass(); //수술갤러리

	$nBeforecounsel->answer        = RepEditor($_POST['answer']);

	$nBeforecounsel->reserve = $_POST['reserve'];

    $arr_field = array
    (
        'answer'
    );

    $arr_value = array
    (
        $nBeforecounsel->answer
    );

//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nBeforecounsel->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");

	if(in_array(3,$nBeforecounsel->reserve)){
	   /******************** 인증정보 ********************/
		$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
		$sms['user_id'] = base64_encode("eightps"); //SMS 아이디.
		$sms['secure'] = base64_encode("e9c16c58aa9a2310c4681b74863a7299") ;//인증키
		$msg="에이트성형외과입니다. 문의주신 상담글이 처리되었으니 확인부탁드립니다^^♥";
		$sms['msg'] = base64_encode(stripslashes($msg));
		$sms['rphone'] = base64_encode($_POST['phone']);
		$sms['sphone1'] = base64_encode("02");
		$sms['sphone2'] = base64_encode("1855");
		$sms['sphone3'] = base64_encode("0858");
		$sms['rdate'] = base64_encode($_POST['rdate']);
		$sms['rtime'] = base64_encode($_POST['rtime']);
		$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$sms['returnurl'] = base64_encode($_POST['list_link']);
		$sms['testflag'] = base64_encode($_POST['testflag']);
		$sms['destination'] = urlencode(base64_encode($_POST['destination']));
		$sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
		$sms['repeatNum'] = base64_encode($_POST['repeatNum']);
		$sms['repeatTime'] = base64_encode($_POST['repeatTime']);
		$sms['smsType'] = base64_encode($_POST['smsType']); // LMS일경우 L
		$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3]."/".$host_info[4];

		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		//print_r($sms);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) { 
			fputs($fp, $header.$data);
			$rsp = '';
			while(!feof($fp)) { 
				$rsp .= fgets($fp,8192); 
			}
			fclose($fp);
			$msg = explode("\r\n\r\n",trim($rsp));
			$rMsg = explode(",", $msg[1]);
			$Result= $rMsg[0]; //발송결과
			$Count= $rMsg[1]; //잔여건수

			//발송결과 알림
			if($Result=="success") {
				$alert = "답변이 등록 되었습니다.";
			}
			else if($Result=="reserved") {
				$alert = "성공적으로 예약되었습니다.";
				$alert .= " 잔여건수는 ".$Count."건 입니다.";
			}
			else if($Result=="3205") {
				$alert = "잘못된 번호형식입니다.";
			}

			else if($Result=="0044") {
				$alert = "스팸문자는발송되지 않습니다.";
			}
			
			else {
				$alert = "[Error]".$Result;
			}
		}
		else {
			$alert = "Connection Failed";
		}
	}

	if(in_array(2,$nBeforecounsel->reserve)){
		$mail_html = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
						<html>
						 <head>
						  <title> New Document </title>
							<meta http-equiv='content-type' content='text/html; charset=utf-8'>
							<meta http-equiv='Content-Script-Type' content='text/javascript'>
							<meta http-equiv='Content-Style-Type' content='text/css'>
							<meta http-equiv='X-UA-Compatible' content='IE=edge'>
						 </head>
							
						 <body>
							<div style='width:698px; height:893px; margin:0 auto; border:1px solid #d4d4d4; overflow:hidden;'>
								<img src='http://eightps8.cafe24.com/common/img/email/counsel.jpg' usemap='#Map' border='0'>
								<map name='Map' id='Map'>
								  <area shape='rect' coords='242,462,464,504' href='http://eightps.com' target='_blank' onfocus='blur'/>
								  <area shape='rect' coords='152,630,206,646' href='http://eightps.com/page.php?cate=9&dep01=0' target='_blank' onfocus='blur' />
								  <area shape='rect' coords='235,628,343,647' href='http://eightps.com/board.php?cate=10&dep01=0' target='_blank' onfocus='blur' />
								  <area shape='rect' coords='372,629,425,647' href='http://eightps.com/board.php?cate=11&dep01=2' target='_blank' onfocus='blur' />
								  <area shape='rect' coords='453,630,539,647' href='http://eightps.com/board.php?cate=12&dep01=0' target='_blank' onfocus='blur' />
								</map>
							</div>								
						 </body>
						</html>";
		$mail = sendMail("에이트성형외과", "eightps8@gmail.com", $_POST['name'], $_POST['email'], "[에이트성형외과] 문의주신 상담글이 처리되었습니다.",$mail_html, $isDebug=0);
	}

    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

$Conn->disConnect();
//======================== DB Module End ===============================
UrlReDirect(SUCCESS_EDIT, $list_link);
?>