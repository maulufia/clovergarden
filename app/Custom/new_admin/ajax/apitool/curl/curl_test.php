<?
$str = "";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://61.72.254.201/curl_test.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
curl_exec($ch);
$result=ob_get_contents();
ob_end_clean();
curl_close ($ch);
echo $result;

?>