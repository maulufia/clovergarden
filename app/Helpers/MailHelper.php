<?php

class MailHelper {
	static function sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $contents, $isDebug=0) {
		//$headers = 'MIME-Version: 1.0'."\r\n";
		//$headers .= 'Content-Type: text/html; charset=utf-8'."\r\n";

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=utf-8";
		$headers[] = "From: 클로버가든 <director@clovergarden.co.kr>";
		$headers[] = "Reply-To: 클로버가든 <director@clovergarden.co.kr>";
		$headers[] = "Subject: {$subject}";

		//return mail($toEmail, '3', '3');
		return mail($toEmail, $subject, $contents, implode("\r\n", $headers), "-fdirector@clovergarden.co.kr");
	}
}
?>