<?php
/****************************************************************************
* ���ϸ� : ags5ing.php
* ������������ : 2010/10/6
*
* �ô�����Ʈ �÷����ο��� ���ϵ� ����Ÿ�� �޾Ƽ� ���ϰ�����û�� �մϴ�.
*
* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.
****************************************************************************/

require "../agspay/PG.php";

/****************************************************************************
* [1] �ô�����Ʈ ������ ����� ���� ��ż��� IP/Port ��ȣ
****************************************************************************/

$strServerIP			= trim($_POST["ServerIP"]);					//��ż���IP
$strServerPort			= trim($_POST["ServerPort"]);				//��ż���Port
$strServerTimeOut		= trim($_POST["ServerTimeOut"]);		//��ż������ð�

//������ �α� ��� (�ڵ���ü ��� ����� ���� �α׸� ���� ������ �����մϴ�.)
$strLogDirectory		= "../agspay/";

/****************************************************************************
* [2] ags5pay.html �� ���� �Ѱܹ��� ����Ÿ
****************************************************************************/

$strPayType			= trim($_POST["PayType"]);		//������� (mpay_reg:���ν�û, mpay_mdf:������������)
$strSendData			= trim($_POST["SendData"]);		//���۵����� (����->�ô�����Ʈ)

/****************************************************************************
* [3] ��� �����͸� ������ ���� ����
****************************************************************************/

$strStoreId					= "";		//�������̵�
$strOrderId					= "";		//ȸ�����̵�
$strMonthPayNo				= "";		//���ε�Ϲ�ȣ
$strResultCode				= "";		//�������� (y:����, n:����)
$strResultMessage			= "";		//ó������޼���

if(xAction($strServerIP, $strServerPort, $strServerTimeOut, $strLogDirectory, $strSendData, &$strRecvData) == true)
{
	$objRecvData = xRecvData($strRecvData); 
	
	/****************************************************************************
	* �� ���� ���� ������ ���� ���� ���� ����
	* �� strPayType  = "mpay_reg"		ī���ڵ���ü���ν�û
	* �� strPayType  = "mpay_mdf"		ī���ڵ���ü������������
	****************************************************************************/
	if(strcmp($strPayType, "mpay_reg") == 0)
	{
		/****************************************************************************
		* [4] ī���ڵ���ü���ν�û
		* 
		* -- �̺κ��� ��� ó���� ���� Socket��� �Ŀ� ����� ó���ϴ� �κ��̴�.
		*
		* -- ������ ���� �� ������ ������ �Ŵ��� ����
		* 
		* �� "|" ���� �����ʿ��� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� ���� ���
		*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5]; 

		/****************************************************************************
		* ���⼭ DB �۾��� �� �ּ���.
		* DB �۾��� �Ͻ� ��� strResultCode ���� 'y' �Ǵ� 'n' �ϰ�쿡 �°� �۾��Ͻʽÿ�. 
		* ����) strResultCode ���� 'y' �ϰ�� ����
		* ����) strResultCode ���� 'n' �ϰ�� ����
		****************************************************************************/






	}
	else if(strcmp($strPayType, "mpay_mdf") == 0)
	{
		/****************************************************************************
		* [5] ī���ڵ���ü������������
		* 
		* -- �̺κ��� ��� ó���� ���� Socket��� �Ŀ� ����� ó���ϴ� �κ��̴�.
		*
		* -- ������ ���� �� ������ ������ �Ŵ��� ����
		* 
		* �� "|" ���� �����ʿ��� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� ���� ���
		*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* ���⼭ DB �۾��� �� �ּ���.
		* DB �۾��� �Ͻ� ��� strResultCode ���� 'y' �Ǵ� 'n' �ϰ�쿡 �°� �۾��Ͻʽÿ�. 
		* ����) strResultCode ���� 'y' �ϰ�� ����
		* ����) strResultCode ���� 'n' �ϰ�� ����
		****************************************************************************/






	}
	else if(strcmp($strPayType, "mpay_del") == 0)
	{
		/****************************************************************************
		* [5] ī���ڵ���ü�����������(����)ó��
		* 
		* -- �̺κ��� ��� ó���� ���� Socket��� �Ŀ� ����� ó���ϴ� �κ��̴�.
		*
		* -- ������ ���� �� ������ ������ �Ŵ��� ����
		* 
		* �� "|" ���� �����ʿ��� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� ���� ���
		*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* ���⼭ DB �۾��� �� �ּ���.
		* DB �۾��� �Ͻ� ��� strResultCode ���� 'y' �Ǵ� 'n' �ϰ�쿡 �°� �۾��Ͻʽÿ�. 
		* ����) strResultCode ���� 'y' �ϰ�� ����
		* ����) strResultCode ���� 'n' �ϰ�� ����
		****************************************************************************/






	}
	else if(strcmp($strPayType, "hp_reg") == 0) 
	{
		/****************************************************************************
		* [6] �ڵ��� �ڵ���ü���ν�û
		* 
		* -- �̺κ��� ��� ó���� ���� Socket��� �Ŀ� ����� ó���ϴ� �κ��̴�.
		*
		* -- ������ ���� �� ������ ������ �Ŵ��� ����
		* 
		* �� "|" ���� �����ʿ��� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� ���� ���
		*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* ���⼭ DB �۾��� �� �ּ���.
		* DB �۾��� �Ͻ� ��� strResultCode ���� 'y' �Ǵ� 'n' �ϰ�쿡 �°� �۾��Ͻʽÿ�. 
		* ����) strResultCode ���� 'y' �ϰ�� ����
		* ����) strResultCode ���� 'n' �ϰ�� ����
		****************************************************************************/





	}
    else if(strcmp($strPayType, "iche_reg") == 0) 
	{
		/****************************************************************************
		* [6] ������ü �ڵ���ü���ν�û
		* 
		* -- �̺κ��� ��� ó���� ���� Socket��� �Ŀ� ����� ó���ϴ� �κ��̴�.
		*
		* -- ������ ���� �� ������ ������ �Ŵ��� ����
		* 
		* �� "|" ���� �����ʿ��� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� ���� ���
		*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* ���⼭ DB �۾��� �� �ּ���.
		* DB �۾��� �Ͻ� ��� strResultCode ���� 'y' �Ǵ� 'n' �ϰ�쿡 �°� �۾��Ͻʽÿ�. 
		* ����) strResultCode ���� 'y' �ϰ�� ����
		* ����) strResultCode ���� 'n' �ϰ�� ����
		****************************************************************************/





	}
}
else
{
	$strResultCode			= "n";
	$strResultMessage		= $strRecvData;
}
?>
<html>
<head>
</head>
<body onload="javascript:frmags5ing.submit();">
<form name=frmags5ing method=post action=../agspay/ags5result.php>
<input type=hidden name=PayType value="<?=$strPayType?>">
<input type=hidden name=StoreId value="<?=$strStoreId?>">
<input type=hidden name=MonthPayNo value="<?=$strMonthPayNo?>">
<input type=hidden name=ResultCode value="<?=$strResultCode?>">
<input type=hidden name=ResultMessage value="<?=$strResultMessage?>">
<input type=hidden name=testdata value="<?=$objRecvData?>">
<input type=hidden name=OrderNo value="<?=$_POST[OrderNo]?>">
<input type=hidden name=OrderName value="<?=$_POST[OrderName]?>">
<input type=hidden name=OrderHandphone value="<?=$_POST[OrderHandphone]?>">
<input type=hidden name=OrderEmail value="<?=$_POST[OrderEmail]?>">

</form>
</body>
</html>