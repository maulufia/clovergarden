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
<title>�ô�����Ʈ</title>
<style type="text/css">
<!--
body { font-family:"����"; font-size:9pt; color:#000000; font-weight:normal; letter-spacing:0pt; line-height:180%; }
td { font-family:"����"; font-size:9pt; color:#000000; font-weight:normal; letter-spacing:0pt; line-height:180%; }
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
				<td class=clsleft>�ڵ���ü ��� ���</td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<td>
				<table width=400 border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td class=clsright width=150>������� : </td>
						<td class=clsleft width=250>
<?
if(strcmp($strPayType, "mpay_reg") == 0) 
	echo "�ſ�ī���ڵ����ν�û";
else if(strcmp($strPayType, "mpay_mdf") == 0)
	echo "�ſ�ī���ڵ�������������";
else if(strcmp($strPayType, "mpay_del") == 0)
	echo "�ſ�ī���ڵ��������";
else if(strcmp($strPayType, "hp_reg") == 0)
	echo "�ڵ����ڵ����ν�û";
else if(strcmp($strPayType, "iche_reg") == 0)
	echo "������ü�ڵ����ν�û";
?>
						</td>
					</tr>
					<tr>
						<td class=clsright>�������̵� : </td>
						<td class=clsleft><?=$strStoreId?></td>
					</tr>
					<tr>
						<td class=clsright>�ֹ���ȣ : </td>
						<td class=clsleft><?=$OrderNo?></td>
					</tr>
					<tr>
						<td class=clsright>�ֹ����̸� : </td>
						<td class=clsleft><?=$OrderName?></td>
					</tr>
					<tr>
						<td class=clsright>�ֹ����ڵ��� : </td>
						<td class=clsleft><?=$OrderHandphone?></td>
					</tr>
					<tr>
						<td class=clsright>�ֹ����̸��� : </td>
						<td class=clsleft><?=$OrderEmail?></td>
					</tr>
					<tr>
						<td class=clsright>ó����� : </td>
						<td class=clsleft><?=$strResultCode?></td>
					</tr>
					<tr>
						<td class=clsright>ó������޼��� : </td>
						<td class=clsleft><?=$strResultMessage?></td>
					</tr>
					<tr>
						<td class=clsright>���ε�Ϲ�ȣ : </td>
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
