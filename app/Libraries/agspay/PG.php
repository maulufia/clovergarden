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

	$strTemp = ">>>>>>>>>>>>>>>>>>>>>>>>> ���� ���� : ".date("Y/m/d H:i:s");
	xLog($strFileName, $strTemp);

	if(strlen($strServerIP) == 0)
	{
		$strRecvData = ">>> �������̵������� �����ϴ�.";
		xLog($strFileName, $strRecvData);

		$blnSuccess = false;
	}

	if($blnSuccess == true)
	{
		if(strlen($strServerPort) == 0)
		{
			$strRecvData = ">>> ������Ʈ������ �����ϴ�.";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		}
	}

	if($blnSuccess == true)
	{ 
		if(strlen($strServerTimeOut) == 0)
		{
			$strRecvData = ">>> ����Ÿ�Ӿƿ������� �����ϴ�.";
			xLog($strFileName, $strRecvData);

			$blnSuccess = false;
		} 
	}

	if($blnSuccess == true)
	{
		if(strlen($strSendData) == 0)
		{
			$strRecvData = ">>> ������ ������ ����Ÿ�� �����ϴ�.";
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

		$strTemp = "������ ".date("H:i:s")." [ServerIP:".$strServerIP.", ServerPort:".$strServerPort."] => ��������[".strlen($strSendData)."] = ".$strSendData;
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
			$strTemp = "������ ".date("H:i:s")." [ServerIP:".$strServerIP.", ServerPort:".$strServerPort."] => �������[".strlen($strRecvData)."] = ".$strRecvData;
			xLog($strFileName, $strTemp);

			fclose($fp);
		}
	}

	/****************************************************************************
	* PHP ������ ���� ���� ������ ���� üũ�� ������������ �߻��� �� �ֽ��ϴ�
	* �����޼���:���� ������(����) üũ ���� ��ſ����� ���� ���� ����
	* ������ ���� üũ ������ �Ʒ��� ���� �����Ͽ� ����Ͻʽÿ�
	*
	* $strRecvLength = fgets($fp, 6);
	* $strRecvData = fgets($fp, $strRecvLength);
	****************************************************************************/

	$strTemp = ">>>>>>>>>>>>>>>>>>>>>>>>> ���� �� : ".date("Y/m/d H:i:s");
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