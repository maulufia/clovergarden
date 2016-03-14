@extends('front.page.clovergarden')

@section('clovergarden')
<?php
/**********************************************************************************************
*
* 파일명 : AGS_pay_result.php
* 작성일자 : 2012/04/30
*
* 소켓결제결과를 처리합니다.
*
* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.
*
**********************************************************************************************/

// 인코딩 다시 변환 // 미리 인코딩해서 보내주는 방식으로 변경
/*
foreach($_POST as $key => $row) {
	$_POST[$key] = iconv("EUC-KR", "UTF-8", $row);
} */

//공통사용
$AuthTy 		= isset($_POST["AuthTy"]) ? $_POST["AuthTy"] : null;				//결제형태
$SubTy 			= isset($_POST["SubTy"]) ? $_POST["SubTy"] : null;				//서브결제형태
$rStoreId 		= isset($_POST["rStoreId"]) ? $_POST["rStoreId"] : null;			//업체ID
$rAmt 			= isset($_POST["rAmt"]) ? $_POST["rAmt"] : null;				//거래금액
$rOrdNo 		= isset($_POST["rOrdNo"]) ? $_POST["rOrdNo"] : null;				//주문번호
$rProdNm 		= isset($_POST["rProdNm"]) ? $_POST["rProdNm"] : null;			//상품명
$rOrdNm			= isset($_POST["rOrdNm"]) ? $_POST["rOrdNm"] : null;				//주문자명

//소켓통신결제(신용카드,핸드폰,일반가상계좌)시 사용
$rSuccYn 		= isset($_POST["rSuccYn"]) ? $_POST["rSuccYn"] : null;			//성공여부
$rResMsg 		= isset($_POST["rResMsg"]) ? $_POST["rResMsg"] : null;			//실패사유
$rApprTm 		= isset($_POST["rApprTm"]) ? $_POST["rApprTm"] : null;			//승인시각

//신용카드공통
$rBusiCd 		= isset($_POST["rBusiCd"]) ? $_POST["rBusiCd"] : null;			//전문코드
$rApprNo 		= isset($_POST["rApprNo"]) ? $_POST["rApprNo"] : null;			//승인번호
$rCardCd 		= isset($_POST["rCardCd"]) ? $_POST["rCardCd"] : null;			//카드사코드
$rDealNo 		= isset($_POST["rDealNo"]) ? $_POST["rDealNo"] : null;			//거래고유번호

//신용카드(안심,일반)
$rCardNm 		= isset($_POST["rCardNm"]) ? $_POST["rCardNm"] : null;			//카드사명
$rMembNo 		= isset($_POST["rMembNo"]) ? $_POST["rMembNo"] : null;			//가맹점번호
$rAquiCd 		= isset($_POST["rAquiCd"]) ? $_POST["rAquiCd"] : null;			//매입사코드
$rAquiNm 		= isset($_POST["rAquiNm"]) ? $_POST["rAquiNm"] : null;			//매입사명


//계좌이체
$ICHE_OUTBANKNAME	= isset($_POST["ICHE_OUTBANKNAME"]) ? $_POST["ICHE_OUTBANKNAME"] : null;		//이체계좌은행명
$ICHE_OUTACCTNO 	= isset($_POST["ICHE_OUTACCTNO"]) ? $_POST["ICHE_OUTACCTNO"] : null;			//이체계좌번호
$ICHE_OUTBANKMASTER = isset($_POST["ICHE_OUTBANKMASTER"]) ? $_POST["ICHE_OUTBANKMASTER"] : null;		//이체계좌소유주
$ICHE_AMOUNT 		= isset($_POST["ICHE_AMOUNT"]) ? $_POST["ICHE_AMOUNT"] : null;			//이체금액

//핸드폰
$rHP_TID 		= isset($_POST["rHP_TID"]) ? $_POST["rHP_TID"] : null;			//핸드폰결제TID
$rHP_DATE 		= isset($_POST["rHP_DATE"]) ? $_POST["rHP_DATE"] : null;			//핸드폰결제날짜
$rHP_HANDPHONE 	= isset($_POST["rHP_HANDPHONE"]) ? $_POST["rHP_HANDPHONE"] : null;		//핸드폰결제핸드폰번호
$rHP_COMPANY 	= isset($_POST["rHP_COMPANY"]) ? $_POST["rHP_COMPANY"] : null;		//핸드폰결제통신사명(SKT,KTF,LGT)

//ARS
$rARS_PHONE = isset($_POST["rARS_PHONE"]) ? $_POST["rARS_PHONE"] : null;				//ARS결제전화번호

//가상계좌
$rVirNo 		= isset($_POST["rVirNo"]) ? $_POST["rVirNo"] : null;				//가상계좌번호 가상계좌추가
$VIRTUAL_CENTERCD = isset($_POST["VIRTUAL_CENTERCD"]) ? $_POST["VIRTUAL_CENTERCD"] : null;	//가상계좌 입금은행코드

//이지스에스크로
$ES_SENDNO	= isset($_POST["ES_SENDNO"]) ? $_POST["ES_SENDNO"] : null;				//이지스에스크로(전문번호)

//*******************************************************************************
//* MD5 결제 데이터 정상여부 확인
//* 결제전 AGS_HASHDATA 값과 결제 후 rAGS_HASHDATA의 일치 여부 확인
//* 형태 : 상점아이디(StoreId) + 주문번호(OrdNo) + 결제금액(Amt)
//*******************************************************************************

$AGS_HASHDATA	= $_POST["AGS_HASHDATA"];				
$rAGS_HASHDATA	= md5($rStoreId . $rOrdNo . (int)$rAmt);				

if($AGS_HASHDATA == $rAGS_HASHDATA){
	$errResMsg   = "";
} else {
	$errResMsg   = "결재금액 변조 발생. 확인 바람";
}
?>

<?php
if($rSuccYn == "y"){
	$code = explode('_',$rOrdNo);

  $nClovermlist = new ClovermlistClass(); //후원기관
  
  $order_adm_ck = 'y';
  if($AuthTy == 'virtual') {
  	$order_adm_ck = 'n';
  }

  DB::table('new_tb_clover_mlist')->insert(['order_num' => $rOrdNo,
  																					'otype' => $AuthTy,
  																					'clover_seq' => $code[2],
  																					'name' => $rOrdNm,
  																					'id' => Auth::user()->user_id,
  																					'price' => $rAmt,
  																					'order_adm_ck' => $order_adm_ck ]);
}
?>
<section class="wrap">
	<h2 class="ti">후원내역 신청확인/후원금 납입방법/후원금 결제확인</h2>

	<article class="brd_write">
		<form method="post" id="wrtForm" action="#">
		<h2>후원내역 신청확인</h2>
		<div class="c_light_gray_3">회원님이 신청하신 후원내역은 아래와 같습니다.</div>

		<div class="supporting_list mt10">
			<table>
				<caption>후원내역 신청확인</caption>
				<colgroup>
					<col>
					<col>
					<col>
				</colgroup>
				<tr >
					<th scope="col">구분</th>
					<th scope="col">후원기관</th>
					<th scope="col" class="last">후원금액</th>
				</tr>
				<tr >
					<td>일시후원</td>
					<td>{{ $rProdNm }}</td>
					<td class="xm_tright mr10 last">{{ $rAmt }} 원</td>
				</tr>
			</table>
		</div>
		<div class="xm_clr"></div>

		<h2 class="xm_left">후원금 납입방법</h2>
		<div class="xm_left mt20 ml10 c_light_gray_3 fs11">회비 출금일은 자동이체의 경우 매월 1일이며, 신용카드/휴대폰은 본인의 결제일입니다. (은행, 카드사, 통신사 영업일 기준)</div>
		<table>
			<caption>후원자 기본정보</caption>
			<colgroup>
				<col class="colWidth147">
				<col class="colWidth254">
				<col class="colWidth147">
				<col class="colWidth254">
			</colgroup>
			<tr >
				<th scope="row" class="first0 xm_tleft pl30">납입방법</th>
				<td colspan="3" class="first0">
					<div class="xm_left radioForm h200">
						<div class="mr20">
						<?php
						if($AuthTy == "card")
						{
							if($SubTy == "isp")
							{
								echo "신용카드결제-안전결제(ISP)";
							}	
							else if($SubTy == "visa3d")
							{
								echo "신용카드결제-안심클릭";
							}
							else if($SubTy == "normal")
							{
								echo "신용카드결제-일반결제";
							}
							
						}
						else if($AuthTy == "iche")
						{
							echo "계좌이체";
						}
						else if($AuthTy == "hp")
						{
							echo "핸드폰결제";
						}
						else if($AuthTy == "ars")
						{
							echo "ARS결제";
						}
						else if($AuthTy == "virtual")
						{
							echo "가상계좌결제";
						}
						?>
						</div>
					</div>	
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">납입자</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $rOrdNm }}</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="first xm_tleft pl30">&nbsp;</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20"><?php if(isset($state)) echo $state; else echo "비회원"; ?></div>
					</div>	
				</td>
			</tr>
			<?php
				if($AuthTy == "hp" ) {
			?>
				<tr>
					<th scope="row" class="first xm_tleft pl30">통신사</th>
					<td>
						{{ $rHP_COMPANY }}	
					</td>
					<th scope="row" class="first xm_tleft pl30">결제핸드폰번호</div></th>
					<td class="mr10">
						{{ $rHP_HANDPHONE }}
					</td>
				</tr>
			<?php				
				}
			?>
			<?php		
				if($AuthTy == "card" && $rSuccYn == "y") {
			?>
				<tr>
					<th scope="row" class="first xm_tleft pl30">카드사명</th>
					<td>
						{{ $rCardNm }}	
					</td>
					<th scope="row" class="first xm_tleft pl30">승인번호</div></th>
					<td class="mr10">
						{{ $rApprNo }}
					</td>
				</tr>
			<?php		
				}
			?>


			<?php
				if($AuthTy == "iche" ) {
			?>
			
				<tr>
					<th scope="row" class="first xm_tleft pl30">은행명</th>
					<td>
						<div class="xm_left styled-select" style="margin-top:2px; margin-left:0">
						   {{ $ICHE_OUTBANKNAME }}{{ getCenter_cd($ICHE_OUTBANKNAME) }}
						</div>	
					</td>
					<th scope="row" class="first xm_tleft pl30">입금계좌소유주</th>
					<td class="mr10">
						{{ $ICHE_OUTBANKMASTER }}
					</td>
				</tr>
			<?php				
				}
			?>					
		
		<?php
				if($AuthTy == "virtual" ) {
			?>
			
				<tr>
					<th scope="row" class="first xm_tleft pl30">은행명</th>
					<td>
						<div class="xm_left styled-select" style="margin-top:2px; margin-left:0">
						   {{ $VIRTUAL_CENTERCD }}{{ getCenter_cd($VIRTUAL_CENTERCD) }}
						</div>	
					</td>
					<th scope="row" class="first xm_tleft pl30">입금계좌</th>
					<td class="mr10">
						{{ $rVirNo }}
					</td>
				</tr>
			<?php				
				}
			?>					
		</table>

		<!-- <div class="comment_box">
			<h2 class="xm_left">응원의 한마디</h2>
			<div class="xm_left mt20 ml10 c_light_gray_3" style="font-size:11px">미등록시 자동 응원 메세지가 등록됩니다.</div>
			<div class="xm_clr"></div>
			<textarea name="comment" class="comment_area mt0" placeholder="응원합니다!"></textarea>
			<a href="#" class="ml5 gray_big_btn_60_33">등록</a>
		</div> -->

<!-- 		<h2>후원금 결제확인</h2>
		<div class="supporting_list mt10">
			<table>
				<tr >
					<th scope="col">후원신청금 합계</th>
					<th scope="col">포인트 결제</th>
					<th scope="col" class="last">결제금액</th>
				</tr>
				<tr >
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="xm_tright mr10 last">원</td>
				</tr>
			</table>
			<div class="mt10 c_light_gray_3">자동이체의 경우 매 월 1일 회비가 출금되며, 신용카드/휴대폰의 경우 결제일에 출금됩니다.</div>
		</div> -->
		
		</form>
	</article>
</section>



<script language=javascript> // "지불처리중" 팝업창 닫기
<!--
var openwin = window.open("{!! route('agspay/AGS_progress') !!}","popup","width=300,height=160");
openwin.close();
-->
</script>
<script language=javascript>
<!--
/***********************************************************************************
* ◈ 영수증 출력을 위한 자바스크립트
*		
*	영수증 출력은 [카드결제]시에만 사용하실 수 있습니다.
*  
*   ※당일 결제건에 한해서 영수증 출력이 가능합니다.
*     당일 이후에는 아래의 주소를 팝업(630X510)으로 띄워 내역 조회 후 출력하시기 바랍니다.
*	  ▷ 팝업용 결제내역조회 패이지 주소 : 
*	     	 http://www.allthegate.com/support/card_search.html
*		→ (반드시 스크롤바를 'yes' 상태로 하여 팝업을 띄우시기 바랍니다.) ←
*
***********************************************************************************/
function show_receipt() 
{
	if("{{ $rSuccYn }}"== "y" && "{{ $AuthTy }}"=="card")
	{
		var send_dt = appr_tm.value;
		
		url="http://www.allthegate.com/customer/receiptLast3.jsp"
		url=url+"?sRetailer_id="+sRetailer_id.value;
		url=url+"&approve="+approve.value;
		url=url+"&send_no="+send_no.value;
		url=url+"&send_dt="+send_dt.substring(0,8);
		
		window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=420,height=700,top=0,left=150");
	}
	else
	{
		alert("해당하는 결제내역이 없습니다");
	}
}
-->
</script>



					
				
<?php
	function getCenter_cd($VIRTUAL_CENTERCD){
		if($VIRTUAL_CENTERCD == "39"){
			echo "경남은행";
		}else if($VIRTUAL_CENTERCD == "34"){
			echo "광주은행";
		}else if($VIRTUAL_CENTERCD == "04"){
			echo "국민은행";
		}else if($VIRTUAL_CENTERCD == "11"){
			echo "농협중앙회";
		}else if($VIRTUAL_CENTERCD == "31"){
			echo "대구은행";
		}else if($VIRTUAL_CENTERCD == "32"){
			echo "부산은행";
		}else if($VIRTUAL_CENTERCD == "02"){
			echo "산업은행";
		}else if($VIRTUAL_CENTERCD == "45"){
			echo "새마을금고";
		}else if($VIRTUAL_CENTERCD == "07"){
			echo "수협중앙회";
		}else if($VIRTUAL_CENTERCD == "48"){
			echo "신용협동조합";
		}else if($VIRTUAL_CENTERCD == "26"){
			echo "(구)신한은행";
		}else if($VIRTUAL_CENTERCD == "05"){
			echo "외환은행";
		}else if($VIRTUAL_CENTERCD == "20"){
			echo "우리은행";
		}else if($VIRTUAL_CENTERCD == "71"){
			echo "우체국";
		}else if($VIRTUAL_CENTERCD == "37"){
			echo "전북은행";
		}else if($VIRTUAL_CENTERCD == "23"){
			echo "제일은행";
		}else if($VIRTUAL_CENTERCD == "35"){
			echo "제주은행";
		}else if($VIRTUAL_CENTERCD == "21"){
			echo "(구)조흥은행";
		}else if($VIRTUAL_CENTERCD == "03"){
			echo "중소기업은행";
		}else if($VIRTUAL_CENTERCD == "81"){
			echo "하나은행";
		}else if($VIRTUAL_CENTERCD == "88"){
			echo "신한은행";
		}else if($VIRTUAL_CENTERCD == "27"){
			echo "한미은행";
		}
	}
?>
@stop