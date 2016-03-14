<?php 

header('Content-type: text/html; charset=euc-kr');
/********************************************************************************
*
* ���ϸ� : AGS_pay_ing.php
* ������������ : 2012/04/30
*
* �ô�����Ʈ �÷����ο��� ���ϵ� ����Ÿ�� �޾Ƽ� ���ϰ�����û�� �մϴ�.
*
* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.
*
*
*  �� ���ǻ��� ��
*  1.  "|"(������) ���� ����ó�� �� �����ڷ� ����ϴ� �����̹Ƿ� ���� �����Ϳ� "|"�� �������
*   ������ ���������� ó������ �ʽ��ϴ�.(���� ������ ���� ���� ���� ����)
********************************************************************************/

	/****************************************************************************
	*
	* [1] ���̺귯��(AGSLib.php)�� ��Ŭ��� �մϴ�.
	*
	****************************************************************************/
	require(app_path() . "/Libraries/agspay/lib/AGSLib.php");

	/****************************************************************************
	*
	* [2]. agspay4.0 Ŭ������ �ν��Ͻ��� �����մϴ�.
	*
	****************************************************************************/
	$agspay = new agspay40;

	/****************************************************************************
	*
	* [3] AGS_pay.html �� ���� �Ѱܹ��� ����Ÿ
	*
	****************************************************************************/
	
	foreach($_POST as $key => $row) {
		$_POST[$key] = iconv("UTF-8", "EUC-KR", $row);
	}

	/*������*/
	//$agspay->SetValue("AgsPayHome","C:/htdocs/agspay");			//�ô�����Ʈ ������ġ ���丮 (������ �°� ����)
	$agspay->SetValue("AgsPayHome", app_path() . "/Libraries/agspay");			//�ô�����Ʈ ������ġ ���丮 (������ �°� ����)
	$agspay->SetValue("StoreId",trim($_POST["StoreId"]));		//�������̵�
	$agspay->SetValue("Type", "Pay");							//������(�����Ұ�)
	$agspay->SetValue("log","false");							//true : �αױ��, false : �αױ�Ͼ���.
	$agspay->SetValue("logLevel","INFO");						//�α׷��� : DEBUG, INFO, WARN, ERROR, FATAL (�ش� �����̻��� �α׸� ��ϵ�)
	$agspay->SetValue("RecvLen", 7);							//���� ������(����) üũ ������ 6 �Ǵ� 7 ����. 
	
	$agspay->SetValue("AuthTy",trim($_POST["AuthTy"]));			//��������
	
	$agspay->SetValue("UseNetCancel","true");					//true : ����� ���. false: ����� �̻�� // !! ������ �̻���. AuthTy �� ������ �� ���� ��������� ��
	
	$agspay->SetValue("SubTy",trim($_POST["SubTy"]));			//�����������
	$agspay->SetValue("OrdNo",trim($_POST["OrdNo"]));			//�ֹ���ȣ
	$agspay->SetValue("Amt",trim($_POST["Amt"]));				//�ݾ�
	$agspay->SetValue("UserEmail",trim($_POST["UserEmail"]));	//�ֹ����̸���
	//$agspay->SetValue("UserEmail",trim($_POST["UserEmail"]));	//�ֹ����̸��� (����)
	$agspay->SetValue("ProdNm",trim($_POST["ProdNm"]));			//��ǰ��
	$AGS_HASHDATA 		= trim( $_POST["AGS_HASHDATA"] );		//��ȣȭ HASHDATA

	/*�ſ�ī��&������»��*/
	$agspay->SetValue("MallUrl",trim($_POST["MallUrl"]));		//MallUrl(�������Ա�) - ���� ������ ��������߰�
	$agspay->SetValue("UserId",trim($_POST["UserId"]));			//ȸ�����̵�


	/*�ſ�ī����*/
	$agspay->SetValue("OrdNm",trim($_POST["OrdNm"]));			//�ֹ��ڸ�
	$agspay->SetValue("OrdPhone",trim($_POST["OrdPhone"])) ;		//�ֹ��ڿ���ó
	$agspay->SetValue("OrdAddr", trim($_POST["OrdAddr"] . "-" . $_POST["OrdAddr2"] . " " . $_POST["addr1"] . " " . $_POST["addr2"]));		//�ֹ����ּ� ��������߰�
	// $agspay->SetValue("RcpNm",trim($_POST["RcpNm"]));			//�����ڸ�
	$agspay->SetValue("RcpNm", trim($_POST["OrdNm"]));			//�����ڸ�
	// $agspay->SetValue("RcpPhone",trim($_POST["RcpPhone"]));		//�����ڿ���ó
	$agspay->SetValue("RcpPhone", trim($_POST["OrdPhone"]));		//�����ڿ���ó
	// $agspay->SetValue("DlvAddr", trim($_POST["DlvAddr"]));		//������ּ�
	$agspay->SetValue("DlvAddr", trim($_POST["OrdAddr"] . "-" . $_POST["OrdAddr2"] . " " . $_POST["addr1"] . " " . $_POST["addr2"]));		//������ּ�
	$agspay->SetValue("Remark",trim($_POST["Remark"]));			//���
	$agspay->SetValue("DeviId",trim($_POST["DeviId"]));			//�ܸ�����̵�
	$agspay->SetValue("AuthYn",trim($_POST["AuthYn"]));			//��������
	$agspay->SetValue("Instmt",trim($_POST["Instmt"]));			//�Һΰ�����
	$agspay->SetValue("UserIp",$_SERVER["REMOTE_ADDR"]);		//ȸ�� IP

	/*�ſ�ī��(ISP)*/
	$agspay->SetValue("partial_mm",trim($_POST["partial_mm"]));		//�Ϲ��ҺαⰣ
	$agspay->SetValue("noIntMonth",trim($_POST["noIntMonth"]));		//�������ҺαⰣ
	$agspay->SetValue("KVP_CURRENCY",trim($_POST["KVP_CURRENCY"]));	//KVP_��ȭ�ڵ�
	$agspay->SetValue("KVP_CARDCODE",trim($_POST["KVP_CARDCODE"]));	//KVP_ī����ڵ�
	$agspay->SetValue("KVP_SESSIONKEY",$_POST["KVP_SESSIONKEY"]);	//KVP_SESSIONKEY
	$agspay->SetValue("KVP_ENCDATA",$_POST["KVP_ENCDATA"]);			//KVP_ENCDATA
	$agspay->SetValue("KVP_CONAME",trim($_POST["KVP_CONAME"]));		//KVP_ī���
	$agspay->SetValue("KVP_NOINT",trim($_POST["KVP_NOINT"]));		//KVP_������=1 �Ϲ�=0
	$agspay->SetValue("KVP_QUOTA",trim($_POST["KVP_QUOTA"]));		//KVP_�Һΰ���

	/*�ſ�ī��(�Ƚ�)*/
	$agspay->SetValue("CardNo",trim($_POST["CardNo"]));			//ī���ȣ
	$agspay->SetValue("MPI_CAVV",$_POST["MPI_CAVV"]);			//MPI_CAVV
	$agspay->SetValue("MPI_ECI",$_POST["MPI_ECI"]);				//MPI_ECI
	$agspay->SetValue("MPI_MD64",$_POST["MPI_MD64"]);			//MPI_MD64

	/*�ſ�ī��(�Ϲ�)*/
	$agspay->SetValue("ExpMon",trim($_POST["ExpMon"]));				//��ȿ�Ⱓ(��)
	$agspay->SetValue("ExpYear",trim($_POST["ExpYear"]));			//��ȿ�Ⱓ(��)
	$agspay->SetValue("Passwd",trim($_POST["Passwd"]));				//��й�ȣ
	$agspay->SetValue("SocId",trim($_POST["SocId"]));				//�ֹε�Ϲ�ȣ/����ڵ�Ϲ�ȣ

	/*������ü���*/
	$agspay->SetValue("ICHE_OUTBANKNAME",trim($_POST["ICHE_OUTBANKNAME"]));		//��ü�����
	$agspay->SetValue("ICHE_OUTACCTNO",trim($_POST["ICHE_OUTACCTNO"]));			//��ü���¹�ȣ
	$agspay->SetValue("ICHE_OUTBANKMASTER",trim($_POST["ICHE_OUTBANKMASTER"]));	//��ü���¼�����
	$agspay->SetValue("ICHE_AMOUNT",trim($_POST["ICHE_AMOUNT"]));				//��ü�ݾ�

	/*�ڵ������*/
	$agspay->SetValue("HP_SERVERINFO",trim($_POST["HP_SERVERINFO"]));	//SERVER_INFO(�ڵ�������)
	$agspay->SetValue("HP_HANDPHONE",trim($_POST["HP_HANDPHONE"]));		//HANDPHONE(�ڵ�������)
	$agspay->SetValue("HP_COMPANY",trim($_POST["HP_COMPANY"]));			//COMPANY(�ڵ�������)
	$agspay->SetValue("HP_ID",trim(isset($_POST["HP_ID"]) ? $_POST["HP_ID"] : null));					//HP_ID(�ڵ�������)
	$agspay->SetValue("HP_SUBID",trim(isset($_POST["HP_SUBID"]) ? $_POST["HP_SUBID"] : null));				//HP_SUBID(�ڵ�������)
	$agspay->SetValue("HP_UNITType",trim(isset($_POST["HP_UNITType"]) ? $_POST["HP_UNITType"] : null));		//HP_UNITType(�ڵ�������)
	$agspay->SetValue("HP_IDEN",trim($_POST["HP_IDEN"]));				//HP_IDEN(�ڵ�������)
	$agspay->SetValue("HP_IPADDR",trim($_POST["HP_IPADDR"]));			//HP_IPADDR(�ڵ�������)

	/*ARS���*/
	$agspay->SetValue("ARS_NAME",trim($_POST["ARS_NAME"]));				//ARS_NAME(ARS����)
	$agspay->SetValue("ARS_PHONE",trim($_POST["ARS_PHONE"]));			//ARS_PHONE(ARS����)

	/*������»��*/
	$agspay->SetValue("VIRTUAL_CENTERCD",trim($_POST["VIRTUAL_CENTERCD"]));	//�����ڵ�(�������)
	$agspay->SetValue("VIRTUAL_DEPODT",trim(!empty($_POST["VIRTUAL_DEPODT"]) ? $_POST["VIRTUAL_DEPODT"] : date("Ymd") + 5));		//�Աݿ�����(�������)
	$agspay->SetValue("ZuminCode",trim($_POST["ZuminCode"]));				//�ֹι�ȣ(�������)
	$agspay->SetValue("MallPage", trim(isset($_POST["MallPage"]) ? $_POST["MallPage"] : null));					//���� ��/��� �뺸 ������(�������)
	$agspay->SetValue("VIRTUAL_NO",trim($_POST["VIRTUAL_NO"]));				//������¹�ȣ(�������)

	/*����ũ�λ��*/
	$agspay->SetValue("ES_SENDNO",trim($_POST["ES_SENDNO"]));				//����ũ��������ȣ

	/*������ü(����) ���� ��� ����*/
	$agspay->SetValue("ICHE_SOCKETYN",trim($_POST["ICHE_SOCKETYN"]));			//������ü(����) ��� ����
	$agspay->SetValue("ICHE_POSMTID",trim($_POST["ICHE_POSMTID"]));				//������ü(����) �̿����ֹ���ȣ
	$agspay->SetValue("ICHE_FNBCMTID",trim($_POST["ICHE_FNBCMTID"]));			//������ü(����) FNBC�ŷ���ȣ
	$agspay->SetValue("ICHE_APTRTS",trim($_POST["ICHE_APTRTS"]));				//������ü(����) ��ü �ð�
	$agspay->SetValue("ICHE_REMARK1",trim($_POST["ICHE_REMARK1"]));				//������ü(����) ��Ÿ����1
	$agspay->SetValue("ICHE_REMARK2",trim($_POST["ICHE_REMARK2"]));				//������ü(����) ��Ÿ����2
	$agspay->SetValue("ICHE_ECWYN",trim($_POST["ICHE_ECWYN"]));					//������ü(����) ����ũ�ο���
	$agspay->SetValue("ICHE_ECWID",trim($_POST["ICHE_ECWID"]));					//������ü(����) ����ũ��ID
	$agspay->SetValue("ICHE_ECWAMT1",trim($_POST["ICHE_ECWAMT1"]));				//������ü(����) ����ũ�ΰ����ݾ�1
	$agspay->SetValue("ICHE_ECWAMT2",trim($_POST["ICHE_ECWAMT2"]));				//������ü(����) ����ũ�ΰ����ݾ�2
	$agspay->SetValue("ICHE_CASHYN",trim($_POST["ICHE_CASHYN"]));				//������ü(����) ���ݿ��������࿩��
	$agspay->SetValue("ICHE_CASHGUBUN_CD",trim($_POST["ICHE_CASHGUBUN_CD"]));	//������ü(����) ���ݿ���������
	$agspay->SetValue("ICHE_CASHID_NO",trim($_POST["ICHE_CASHID_NO"]));			//������ü(����) ���ݿ������ź�Ȯ�ι�ȣ

	/*������ü-�ڷ���ŷ(����) ���� ��� ����*/
	$agspay->SetValue("ICHEARS_SOCKETYN", trim($_POST["ICHEARS_SOCKETYN"]));	//�ڷ���ŷ������ü(����) ��� ����
	$agspay->SetValue("ICHEARS_ADMNO", trim($_POST["ICHEARS_ADMNO"]));			//�ڷ���ŷ������ü ���ι�ȣ       
	$agspay->SetValue("ICHEARS_POSMTID", trim($_POST["ICHEARS_POSMTID"]));		//�ڷ���ŷ������ü �̿����ֹ���ȣ
	$agspay->SetValue("ICHEARS_CENTERCD", trim($_POST["ICHEARS_CENTERCD"]));	//�ڷ���ŷ������ü �����ڵ�      
	$agspay->SetValue("ICHEARS_HPNO", trim($_POST["ICHEARS_HPNO"]));			//�ڷ���ŷ������ü �޴�����ȣ   

	/****************************************************************************
	*
	* [4] �ô�����Ʈ ���������� ������ ��û�մϴ�.
	*
	****************************************************************************/
	$agspay->startPay();
	
	/****************************************************************************
	*
	* [5] ��������� ���� ����DB ���� �� ��Ÿ �ʿ��� ó���۾��� �����ϴ� �κ��Դϴ�.
	*
	*	�Ʒ��� ��������� ���Ͽ� �� �������ܺ� ����������� ����Ͻ� �� �ֽ��ϴ�.
	*	
	*	-- ������ --
	*	��üID : $agspay->GetResult("rStoreId")
	*	�ֹ���ȣ : $agspay->GetResult("rOrdNo")
	*	��ǰ�� : $agspay->GetResult("rProdNm")
	*	�ŷ��ݾ� : $agspay->GetResult("rAmt")
	*	�������� : $agspay->GetResult("rSuccYn") (����:y ����:n)
	*	����޽��� : $agspay->GetResult("rResMsg")
	*
	*	1. �ſ�ī��
	*	
	*	�����ڵ� : $agspay->GetResult("rBusiCd")
	*	�ŷ���ȣ : $agspay->GetResult("rDealNo")
	*	���ι�ȣ : $agspay->GetResult("rApprNo")
	*	�Һΰ��� : $agspay->GetResult("rInstmt")
	*	���νð� : $agspay->GetResult("rApprTm")
	*	ī����ڵ� : $agspay->GetResult("rCardCd")
	*
	*	2.������ü(���ͳݹ�ŷ/�ڷ���ŷ)
	*	����ũ���ֹ���ȣ : $agspay->GetResult("ES_SENDNO") (����ũ�� ������)
	*
	*	3.�������
	*	��������� ���������� ������¹߱��� �������� �ǹ��ϸ� �Աݴ����·� ���� ���� �Ա��� �Ϸ��� ���� �ƴմϴ�.
	*	���� ������� �����Ϸ�� �����Ϸ�� ó���Ͽ� ��ǰ�� ����Ͻø� �ȵ˴ϴ�.
	*	������ ���� �߱޹��� ���·� �Ա��� �Ϸ�Ǹ� MallPage(���� �Ա��뺸 ������(�������))�� �Աݰ���� ���۵Ǹ�
	*	�̶� ��μ� ������ �Ϸ�ǰ� �ǹǷ� �����Ϸῡ ���� ó��(��ۿ�û ��)��  MallPage�� �۾����ּž� �մϴ�.
	*	�������� : $agspay->GetResult("rAuthTy") (������� �Ϲ� : vir_n ��Ŭ�� : vir_u ����ũ�� : vir_s)
	*	�������� : $agspay->GetResult("rApprTm")
	*	������¹�ȣ : $agspay->GetResult("rVirNo")
	*
	*	4.�ڵ�������
	*	�ڵ��������� : $agspay->GetResult("rHP_DATE")
	*	�ڵ������� TID : $agspay->GetResult("rHP_TID")
	*
	*	5.ARS����
	*	ARS������ : $agspay->GetResult("rHP_DATE")
	*	ARS���� TID : $agspay->GetResult("rHP_TID")
	*
	****************************************************************************/
	
	if($agspay->GetResult("rSuccYn") == "y")
	{ 
		if($agspay->GetResult("AuthTy") == "virtual"){
			//������°����� ��� �Ա��� �Ϸ���� ���� �Աݴ�����(������� �߱޼���)�̹Ƿ� ��ǰ�� ����Ͻø� �ȵ˴ϴ�. 

		}else{
			// ���������� ���� ����ó���κ�
			//echo ("������ ����ó���Ǿ����ϴ�. [" . $agspay->GetResult("rSuccYn")."]". $agspay->GetResult("rResMsg").". " );
			// dd(iconv("EUC-KR", "UTF-8", $agspay->GetResult("rSuccYn")) . " " . iconv("EUC-KR", "UTF-8", $agspay->GetResult("rResMsg")) );
		}
	}
	else
	{
		// �������п� ���� ����ó���κ�
		//echo ("������ ����ó���Ǿ����ϴ�. [" . $agspay->GetResult("rSuccYn")."]". $agspay->GetResult("rResMsg").". " );
		//dd($agspay);
		dd(iconv("EUC-KR", "UTF-8", $agspay->GetResult("rResMsg")));
		dd(iconv("EUC-KR", "UTF-8", $agspay->GetResult("rSuccYn")) . " " . iconv("EUC-KR", "UTF-8", $agspay->GetResult("rResMsg")) );
	}
	

	/*******************************************************************
	* [6] ������ ����ó������ ������ ��� $agspay->GetResult("NetCancID") ���� �̿��Ͽ�                                     
	* ��������� ���� ��Ȯ�ο�û�� �� �� �ֽ��ϴ�.
	* 
	* �߰� �����ͼۼ����� �߻��ϹǷ� ������ ����ó������ �ʾ��� ��쿡�� ����Ͻñ� �ٶ��ϴ�. 
	*
	* ����� :
	* $agspay->checkPayResult($agspay->GetResult("NetCancID"));
	*                           
	*******************************************************************/
	
	/*
	$agspay->SetValue("Type", "Pay"); // ����
	$agspay->checkPayResult($agspay->GetResult("NetCancID"));
	*/
	
	/*******************************************************************
	* [7] ����DB ���� �� ��Ÿ ó���۾� ������н� �������                                      
	*   
	* $cancelReq : "true" ������ҽ���, "false" ������ҽ������.
	*
	* ��������� ���� ����ó���κ� ���� �� �����ϴ� ���    
	* �Ʒ��� �ڵ带 �����Ͽ� �ŷ��� ����� �� �ֽ��ϴ�.
	*	��Ҽ������� : $agspay->GetResult("rCancelSuccYn") (����:y ����:n)
	*	��Ұ���޽��� : $agspay->GetResult("rCancelResMsg")
	*
	* ���ǻ��� :
	* �������(virtual)�� ������� ����� �������� �ʽ��ϴ�.
	*******************************************************************/
	
	// ����ó���κ� ������н� $cancelReq�� "true"�� �����Ͽ� 
	// ������Ҹ� ����ǵ��� �� �� �ֽ��ϴ�.
	// $cancelReq�� "true"������ ���������� �������� �Ǵ��ϼž� �մϴ�.
	
	/*
	$cancelReq = "false";

	if($cancelReq == "true")
	{
		$agspay->SetValue("Type", "Cancel"); // ����
		$agspay->SetValue("CancelMsg", "DB FAIL"); // ��һ���
		$agspay->startPay();
	}
	*/
	

?>
<html>
<head>
</head>
<body onload="javascript:frmAGS_pay_ing.submit();">
	
<form name=frmAGS_pay_ing method="POST" action="{{ route('clovergarden', array('cate' => 1, 'dep01' => 0, 'dep02' => 0, 'type' => 'write_result', 'seq' => 2)) }}"> 
	
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<?php
// UTF-8 ���ڵ��� �ӽ� ������
$AuthTy = $agspay->GetResult('AuthTy');
$SubTy = $agspay->GetResult('SubTy');
$rStoreId = $agspay->GetResult('rStoreId');
$rOrdNo = $agspay->GetResult('rOrdNo');
$rProdNm = $agspay->GetResult('ProdNm');
$rAmt = $agspay->GetResult('rAmt');
$rOrdNm = $agspay->GetResult('OrdNm');

$rSuccYn = $agspay->GetResult('rSuccYn');
$rResMsg = $agspay->GetResult('rResMsg');
$rApprTm = $agspay->GetResult('rApprTm');

$rBusiCd = $agspay->GetResult('rBusiCd');
$rApprNo = $agspay->GetResult('rApprNo');
$rCardCd = $agspay->GetResult('rCardCd');
$rDealNo = $agspay->GetResult('rDealNo');

$rCardNm = $agspay->GetResult('rCardNm');
$rMembNo = $agspay->GetResult('rMembNo');
$rAquiCd = $agspay->GetResult('rAquiCd');
$rAquiNm = $agspay->GetResult('rAquiNm');

$ICHE_OUTBANKNAME = $agspay->GetResult('ICHE_OUTBANKNAME');
$ICHE_OUTBANKMASTER = $agspay->GetResult('ICHE_OUTBANKMASTER');
$ICHE_AMOUNT = $agspay->GetResult('ICHE_AMOUNT');

$rHP_HANDPHONE = $agspay->GetResult('HP_HANDPHONE');
$rHP_COMPANY = $agspay->GetResult('HP_COMPANY');
$rHP_TID = $agspay->GetResult('rHP_TID');
$rHP_DATE = $agspay->GetResult('rHP_DATE');

$rARS_PHONE = $agspay->GetResult('ARS_PHONE');

$rVirNo = $agspay->GetResult('rVirNo');
$VIRTUAL_CENTERCD = $agspay->GetResult('VIRTUAL_CENTERCD');

$ES_SENDNO = $agspay->GetResult('ES_SENDNO');
?>

<!-- �� ���� ���� ��� ���� -->
<input type=hidden name=AuthTy value="{{ iconv('EUC-KR', 'UTF-8', $AuthTy) }}">		<!-- �������� -->
<input type=hidden name=SubTy value="{{ iconv('EUC-KR', 'UTF-8', $SubTy) }}">			<!-- ����������� -->
<input type=hidden name=rStoreId value="{{ iconv('EUC-KR', 'UTF-8', $rStoreId) }}">	<!-- �������̵� -->
<input type=hidden name=rOrdNo value="{{ iconv('EUC-KR', 'UTF-8', $rOrdNo) }}">		<!-- �ֹ���ȣ -->
<input type=hidden name=rProdNm value="{{ iconv('EUC-KR', 'UTF-8', $rProdNm) }}">		<!-- ��ǰ�� -->
<input type=hidden name=rAmt value="{{ iconv('EUC-KR', 'UTF-8', $rAmt) }}">			<!-- �����ݾ� -->
<input type=hidden name=rOrdNm value="{{ iconv('EUC-KR', 'UTF-8', $rOrdNm) }}">		<!-- �ֹ��ڸ� -->
<input type=hidden name=AGS_HASHDATA value="{{ $AGS_HASHDATA }}">				<!-- ��ȣȭ HASHDATA -->

<input type=hidden name=rSuccYn value="{{ iconv('EUC-KR', 'UTF-8', $rSuccYn) }}">	<!-- �������� -->
<input type=hidden name=rResMsg value="{{ iconv('EUC-KR', 'UTF-8', $rResMsg) }}">	<!-- ����޽��� -->
<input type=hidden name=rApprTm value="{{ iconv('EUC-KR', 'UTF-8', $rApprTm) }}">	<!-- �����ð� -->

<!-- �ſ�ī�� ���� ��� ���� -->
<input type=hidden name=rBusiCd value="{{ iconv('EUC-KR', 'UTF-8', $rBusiCd) }}">		<!-- (�ſ�ī�����)�����ڵ� -->
<input type=hidden name=rApprNo value="{{ iconv('EUC-KR', 'UTF-8', $rApprNo) }}>">		<!-- (�ſ�ī�����)���ι�ȣ -->
<input type=hidden name=rCardCd value="{{ iconv('EUC-KR', 'UTF-8', $rCardCd) }}">	<!-- (�ſ�ī�����)ī����ڵ� -->
<input type=hidden name=rDealNo value="{{ iconv('EUC-KR', 'UTF-8', $rDealNo) }}">			<!-- (�ſ�ī�����)�ŷ���ȣ -->

<input type=hidden name=rCardNm value="{{ iconv('EUC-KR', 'UTF-8', $rCardNm) }}">	<!-- (�Ƚ�Ŭ��,�Ϲݻ��)ī���� -->
<input type=hidden name=rMembNo value="{{ iconv('EUC-KR', 'UTF-8', $rMembNo) }}>">	<!-- (�Ƚ�Ŭ��,�Ϲݻ��)��������ȣ -->
<input type=hidden name=rAquiCd value="{{ iconv('EUC-KR', 'UTF-8', $rAquiCd) }}">		<!-- (�Ƚ�Ŭ��,�Ϲݻ��)���Ի��ڵ� -->
<input type=hidden name=rAquiNm value="{{ iconv('EUC-KR', 'UTF-8', $rAquiNm) }}">	<!-- (�Ƚ�Ŭ��,�Ϲݻ��)���Ի�� -->

<!-- ������ü ���� ��� ���� -->
<input type=hidden name=ICHE_OUTBANKNAME value="{{ iconv('EUC-KR', 'UTF-8', $ICHE_OUTBANKNAME) }}">		<!-- ��ü����� -->
<input type=hidden name=ICHE_OUTBANKMASTER value="{{ iconv('EUC-KR', 'UTF-8', $ICHE_OUTBANKMASTER) }}">	<!-- ��ü���¿����� -->
<input type=hidden name=ICHE_AMOUNT value="{{ iconv('EUC-KR', 'UTF-8', $ICHE_AMOUNT) }}">					<!-- ��ü�ݾ� -->

<!-- �ڵ��� ���� ��� ���� -->
<input type=hidden name=rHP_HANDPHONE value="{{ iconv('EUC-KR', 'UTF-8', $rHP_HANDPHONE) }}">		<!-- �ڵ�����ȣ -->
<input type=hidden name=rHP_COMPANY value="{{ iconv('EUC-KR', 'UTF-8', $rHP_COMPANY) }}">			<!-- ��Ż��(SKT,KTF,LGT) -->
<input type=hidden name=rHP_TID value="{{ iconv('EUC-KR', 'UTF-8', $rHP_TID) }}">					<!-- ����TID -->
<input type=hidden name=rHP_DATE value="{{ iconv('EUC-KR', 'UTF-8', $rHP_DATE) }}">				<!-- �������� -->

<!-- ARS ���� ��� ���� -->
<input type=hidden name=rARS_PHONE value="{{ iconv('EUC-KR', 'UTF-8', $rARS_PHONE) }}">			<!-- ARS��ȣ -->

<!-- ������� ���� ��� ���� -->
<input type=hidden name=rVirNo value="{{ iconv('EUC-KR', 'UTF-8', $rVirNo) }}">					<!-- ������¹�ȣ -->
<input type=hidden name=VIRTUAL_CENTERCD value="{{ iconv('EUC-KR', 'UTF-8', $VIRTUAL_CENTERCD) }}">	<!--�Աݰ�����������ڵ�(�츮����:20) -->

<!-- ����������ũ�� ���� ��� ���� -->
<input type=hidden name=ES_SENDNO value="{{ iconv('EUC-KR', 'UTF-8', $ES_SENDNO) }}">				<!-- ����������ũ��(������ȣ) -->

</form>
</body> 
</html>