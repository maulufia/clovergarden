<?php
/****************************************************************************
* 파일명 : ags5ing.php
* 최종수정일자 : 2010/10/6
*
* 올더게이트 플러그인에서 리턴된 데이타를 받아서 소켓결제요청을 합니다.
*
* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.
****************************************************************************/

require "../agspay/PG.php";

/****************************************************************************
* [1] 올더게이트 결제시 사용할 로컬 통신서버 IP/Port 번호
****************************************************************************/

$strServerIP			= trim($_POST["ServerIP"]);					//통신서버IP
$strServerPort			= trim($_POST["ServerPort"]);				//통신서버Port
$strServerTimeOut		= trim($_POST["ServerTimeOut"]);		//통신서버대기시간

//상점측 로그 경로 (자동이체 등록 결과에 대한 로그를 남길 폴더를 설정합니다.)
$strLogDirectory		= "../agspay/";

/****************************************************************************
* [2] ags5pay.html 로 부터 넘겨받을 데이타
****************************************************************************/

$strPayType			= trim($_POST["PayType"]);		//등록형태 (mpay_reg:납부신청, mpay_mdf:납부정보변경)
$strSendData			= trim($_POST["SendData"]);		//전송데이터 (상점->올더게이트)

/****************************************************************************
* [3] 결과 데이터를 저장할 변수 선언
****************************************************************************/

$strStoreId					= "";		//상점아이디
$strOrderId					= "";		//회원아이디
$strMonthPayNo				= "";		//납부등록번호
$strResultCode				= "";		//성공여부 (y:성공, n:실패)
$strResultMessage			= "";		//처리결과메세지

if(xAction($strServerIP, $strServerPort, $strServerTimeOut, $strLogDirectory, $strSendData, &$strRecvData) == true)
{
	$objRecvData = xRecvData($strRecvData); 
	
	/****************************************************************************
	* ※ 결제 형태 변수의 값에 따른 결제 구분
	* ＊ strPayType  = "mpay_reg"		카드자동이체납부신청
	* ＊ strPayType  = "mpay_mdf"		카드자동이체납부정보변경
	****************************************************************************/
	if(strcmp($strPayType, "mpay_reg") == 0)
	{
		/****************************************************************************
		* [4] 카드자동이체납부신청
		* 
		* -- 이부분은 등록 처리를 위한 Socket통신 후에 결과를 처리하는 부분이다.
		*
		* -- 데이터 길이 및 데이터 설명은 매뉴얼 참고
		* 
		* ※ "|" 값은 저희쪽에서 구분자로 사용하는 문자이므로 결제 데이터에 "|"이 있을 경우
		*   결제가 정상적으로 처리되지 않습니다.(수신 데이터 길이 에러 등의 사유)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5]; 

		/****************************************************************************
		* 여기서 DB 작업을 해 주세요.
		* DB 작업을 하실 경우 strResultCode 값이 'y' 또는 'n' 일경우에 맞게 작업하십시오. 
		* 주의) strResultCode 값이 'y' 일경우 성공
		* 주의) strResultCode 값이 'n' 일경우 실패
		****************************************************************************/






	}
	else if(strcmp($strPayType, "mpay_mdf") == 0)
	{
		/****************************************************************************
		* [5] 카드자동이체납부정보변경
		* 
		* -- 이부분은 등록 처리를 위한 Socket통신 후에 결과를 처리하는 부분이다.
		*
		* -- 데이터 길이 및 데이터 설명은 매뉴얼 참고
		* 
		* ※ "|" 값은 저희쪽에서 구분자로 사용하는 문자이므로 결제 데이터에 "|"이 있을 경우
		*   결제가 정상적으로 처리되지 않습니다.(수신 데이터 길이 에러 등의 사유)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* 여기서 DB 작업을 해 주세요.
		* DB 작업을 하실 경우 strResultCode 값이 'y' 또는 'n' 일경우에 맞게 작업하십시오. 
		* 주의) strResultCode 값이 'y' 일경우 성공
		* 주의) strResultCode 값이 'n' 일경우 실패
		****************************************************************************/






	}
	else if(strcmp($strPayType, "mpay_del") == 0)
	{
		/****************************************************************************
		* [5] 카드자동이체납부정보취소(해지)처리
		* 
		* -- 이부분은 등록 처리를 위한 Socket통신 후에 결과를 처리하는 부분이다.
		*
		* -- 데이터 길이 및 데이터 설명은 매뉴얼 참고
		* 
		* ※ "|" 값은 저희쪽에서 구분자로 사용하는 문자이므로 결제 데이터에 "|"이 있을 경우
		*   결제가 정상적으로 처리되지 않습니다.(수신 데이터 길이 에러 등의 사유)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* 여기서 DB 작업을 해 주세요.
		* DB 작업을 하실 경우 strResultCode 값이 'y' 또는 'n' 일경우에 맞게 작업하십시오. 
		* 주의) strResultCode 값이 'y' 일경우 성공
		* 주의) strResultCode 값이 'n' 일경우 실패
		****************************************************************************/






	}
	else if(strcmp($strPayType, "hp_reg") == 0) 
	{
		/****************************************************************************
		* [6] 핸드폰 자동이체납부신청
		* 
		* -- 이부분은 등록 처리를 위한 Socket통신 후에 결과를 처리하는 부분이다.
		*
		* -- 데이터 길이 및 데이터 설명은 매뉴얼 참고
		* 
		* ※ "|" 값은 저희쪽에서 구분자로 사용하는 문자이므로 결제 데이터에 "|"이 있을 경우
		*   결제가 정상적으로 처리되지 않습니다.(수신 데이터 길이 에러 등의 사유)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* 여기서 DB 작업을 해 주세요.
		* DB 작업을 하실 경우 strResultCode 값이 'y' 또는 'n' 일경우에 맞게 작업하십시오. 
		* 주의) strResultCode 값이 'y' 일경우 성공
		* 주의) strResultCode 값이 'n' 일경우 실패
		****************************************************************************/





	}
    else if(strcmp($strPayType, "iche_reg") == 0) 
	{
		/****************************************************************************
		* [6] 계좌이체 자동이체납부신청
		* 
		* -- 이부분은 등록 처리를 위한 Socket통신 후에 결과를 처리하는 부분이다.
		*
		* -- 데이터 길이 및 데이터 설명은 매뉴얼 참고
		* 
		* ※ "|" 값은 저희쪽에서 구분자로 사용하는 문자이므로 결제 데이터에 "|"이 있을 경우
		*   결제가 정상적으로 처리되지 않습니다.(수신 데이터 길이 에러 등의 사유)
		****************************************************************************/
		$strStoreId					= $objRecvData[1];
		$strOrderId					= $objRecvData[2];
		$strMonthPayNo				= $objRecvData[3];
		$strResultCode				= $objRecvData[4];
		$strResultMessage			= $objRecvData[5];

		/****************************************************************************
		* 여기서 DB 작업을 해 주세요.
		* DB 작업을 하실 경우 strResultCode 값이 'y' 또는 'n' 일경우에 맞게 작업하십시오. 
		* 주의) strResultCode 값이 'y' 일경우 성공
		* 주의) strResultCode 값이 'n' 일경우 실패
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
