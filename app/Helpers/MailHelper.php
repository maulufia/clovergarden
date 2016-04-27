<?php

class MailHelper {
	static function sendMail($toEmail, $subject, $contents) {
		//$headers = 'MIME-Version: 1.0'."\r\n";
		//$headers .= 'Content-Type: text/html; charset=utf-8'."\r\n";
		
		//$subject = 'This is information mail again.'; // 스팸 X (영어로 전달하면 스팸처리가 안된다...?)
		//$subject = '=?UTF-8?B?'.base64_encode($subject).'?='; // 한글깨짐
		//$subject = "=?EUC-KR?B?".base64_encode(iconv("UTF-8","EUC-KR",$subject))."?="; // 스팸분류
		
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=UTF-8";
		$headers[] = "From: 클로버가든 <director@clovergarden.co.kr>";
		$headers[] = "Reply-To: 클로버가든 <director@clovergarden.co.kr>";
		$headers[] = "Subject: {$subject}";

		//return mail($toEmail, '3', '3');
		
		
		//****************************************//
		//******************TEST******************//
		//****************************************//
		
		//$toEmail = 'ins-hjinnbdb@isnotspam.com'; // 스팸 체커
		//$subject = 'This is information mail.';
		//$content = 'This is the content!';
		
		//****************************************//
		//****************************************//
		//****************************************//
		
		return mail($toEmail, $subject, $contents, implode("\r\n", $headers), "-fdirector@clovergarden.co.kr");
	}
}
?>