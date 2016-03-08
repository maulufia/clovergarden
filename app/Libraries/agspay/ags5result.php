<?php
$strPayType				= trim($_POST["PayType"]);
$strStoreId				= trim($_POST["StoreId"]);
$strResultCode			= trim($_POST["ResultCode"]);
$strResultMessage		= trim($_POST["ResultMessage"]);
$strMonthPayNo			= trim($_POST["MonthPayNo"]);
$OrderNo = trim($_POST["OrderNo"]);
$OrderName = trim($_POST["OrderName"]);
$OrderHandphone = trim($_POST["OrderHandphone"]);
$OrderEmail = trim($_POST["OrderEmail"]);



?>
<html>
<head>
<title>올더게이트</title>
<style type="text/css">
<!--
body { font-family:"돋움"; font-size:9pt; color:#000000; font-weight:normal; letter-spacing:0pt; line-height:180%; }
td { font-family:"돋움"; font-size:9pt; color:#000000; font-weight:normal; letter-spacing:0pt; line-height:180%; }
.clsright { padding-right:10px; text-align:right; }
.clsleft { padding-left:10px; text-align:left; }
-->
</style>
</head>
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0>
<table border=0 width=100% height=100% cellpadding=0 cellspacing=0>
	<tr>
		<td align=center>
		<table width=400 border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<td class=clsleft>자동이체 등록 결과</td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<td>
				<table width=400 border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td class=clsright width=150>등록형태 : </td>
						<td class=clsleft width=250>
<?
if(strcmp($strPayType, "mpay_reg") == 0) 
	echo "신용카드자동납부신청";
else if(strcmp($strPayType, "mpay_mdf") == 0)
	echo "신용카드자동납부정보변경";
else if(strcmp($strPayType, "mpay_del") == 0)
	echo "신용카드자동납부취소";
else if(strcmp($strPayType, "hp_reg") == 0)
	echo "핸드폰자동납부신청";
else if(strcmp($strPayType, "iche_reg") == 0)
	echo "계좌이체자동납부신청";
?>
						</td>
					</tr>
					<tr>
						<td class=clsright>상점아이디 : </td>
						<td class=clsleft><?=$strStoreId?></td>
					</tr>
					<tr>
						<td class=clsright>주문번호 : </td>
						<td class=clsleft><?=$OrderNo?></td>
					</tr>
					<tr>
						<td class=clsright>주문자이름 : </td>
						<td class=clsleft><?=$OrderName?></td>
					</tr>
					<tr>
						<td class=clsright>주문자핸드폰 : </td>
						<td class=clsleft><?=$OrderHandphone?></td>
					</tr>
					<tr>
						<td class=clsright>주문자이메일 : </td>
						<td class=clsleft><?=$OrderEmail?></td>
					</tr>
					<tr>
						<td class=clsright>처리결과 : </td>
						<td class=clsleft><?=$strResultCode?></td>
					</tr>
					<tr>
						<td class=clsright>처리결과메세지 : </td>
						<td class=clsleft><?=$strResultMessage?></td>
					</tr>
					<tr>
						<td class=clsright>납부등록번호 : </td>
						<td class=clsleft><?=$strMonthPayNo?></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<td class=clsleft>Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>
