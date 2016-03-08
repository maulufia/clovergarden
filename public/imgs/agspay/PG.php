<?
function xRecvData($strRecvData)
{
	$objRecvData = array();
	$objRecvData = explode("|", $strRecvData);

	return $objRecvData;
}

function xAction($strServerIP, $strServerPort, $strServerTimeOut, $strLogDirectory, $strSendData, &$strRecvData)
{
	$strTemp = "";
	$strFileName = "";
	$blnSuccess = true;
	
	if(strlen($strLogDirectory) == 0)
		$strFileName = "";
	else
		$strFileName = $strLogDirectory."PG_".date("Ymd").".log";

	$strTemp = ">>>>>>>>>>>>>>>>>>>>>>>>> 서비스 시작 : ".date("Y/m/d H:i:s");
	xLog($strFileName, $strTemp);

	if(strlen($strServerIP) == 0)
	{
		$strRecvData = ">>> 서버아이디정보가 없습니다.";
		xLog($strFileName, $strRecvData);

		$blnSuccess = false;
	}

	if($blnSuccess == true)
	{
		if(strlen($strServerPort) == 0)
		{
			$strRecvData = ">>> 서버포트정보가 없습니다.";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		}
	}

	if($blnSuccess == true)
	{ 
		if(strlen($strServerTimeOut) == 0)
		{
			$strRecvData = ">>> 서버타임아웃정보가 없습니다.";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		} 
	}

	if($blnSuccess == true)
	{
		if(strlen($strSendData) == 0)
		{
			$strRecvData = ">>> 서버로 전송할 데이타가 없습니다.";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		}
	}

	if($blnSuccess == true)
	{
		$fp = fsockopen($strServerIP, $strServerPort, &$errno, &$errstr, $strServerTimeOut);

		if(!$fp)
		{
			$strRecvData = ">>> fsockopen Error";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		}
	}

	if($blnSuccess == true)
	{
		fputs($fp, $strSendData);

		socket_set_timeout($fp, $strServerTimeOut);

		$strTemp = "▶▶▶ ".date("H:i:s")." [ServerIP:".$strServerIP.", ServerPort:".$strServerPort."] => 보낸전문[".strlen($strSendData)."] = ".$strSendData;
		xLog($strFileName, $strTemp);

		$strRecvLength = fgets($fp, 7);

		xLog($strFileName, $strRecvLength);

		if(strlen($strRecvLength) == 0)
		{
			$strRecvData = ">>> fgets Error : strlen(strRecvLength) = 0";
			xLog($strFileName, $strRecvData);
			
			fclose($fp);

			$blnSuccess = false;
		}
	}

	if($blnSuccess == true)
	{
		$strRecvData = fgets($fp, $strRecvLength + 1);

		if(strlen($strRecvData) == 0)
		{
			$strRecvData = ">>> fgets Error : strlen(strRecvData) = 0";
			xLog($strFileName, $strRecvData);
			
			fclose($fp);

			$blnSuccess = false;
		}
		else
		{
			$strTemp = "◀◀◀ ".date("H:i:s")." [ServerIP:".$strServerIP.", ServerPort:".$strServerPort."] => 결과전문[".strlen($strRecvData)."] = ".$strRecvData;
			xLog($strFileName, $strTemp);

			fclose($fp);
		}
	}

	/****************************************************************************
	* PHP 버전에 따라 수신 데이터 길이 체크시 페이지오류가 발생할 수 있습니다
	* 에러메세지:수신 데이터(길이) 체크 에러 통신오류에 의한 승인 실패
	* 데이터 길이 체크 오류시 아래와 같이 변경하여 사용하십시오
	*
	* $strRecvLength = fgets($fp, 6);
	* $strRecvData = fgets($fp, $strRecvLength);
	****************************************************************************/

	$strTemp = ">>>>>>>>>>>>>>>>>>>>>>>>> 서비스 끝 : ".date("Y/m/d H:i:s");
	xLog($strFileName, $strTemp);

	return $blnSuccess;
}

function xLog($strFileName, $strData)
{
	if(strlen($strFileName) == 0)
		return;

	if(strlen($strData) == 0)
		return;

	$fp = fopen($strFileName, "a");

	if($fp)
	{
		fwrite($fp, $strData);
		fwrite($fp, "\r\n");
		fclose($fp);
	}
}
?> 