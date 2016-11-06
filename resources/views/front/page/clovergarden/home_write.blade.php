@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

	$nClover = new CloverClass(); //클로버목록
	$nPoint = new PointClass(); //회원
	$nAdm_4 = new AdmClass(); //
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$nClover->where = "where seq ='".$seq."'";

		$nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", $nClover->where, null, null);
		if(count($nClover->read_result) != 0){
			$nClover->VarList($nClover->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}


	$nPoint->page_result = $Conn->AllList
	(
		$nPoint->table_name, $nPoint, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='" . Auth::user()->user_id . "' group by userid", null, null
	);


$nAdm_4->page_result = $Conn->AllList
(
	$nAdm_4->table_name, $nAdm_4, "*", "where t_name='use_v_2' order by idx desc limit 1", null, null
);

	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>
<?php
if(Auth::user()->user_state == 4){
	echo "
	<script>
	alert('기업담당자는 이용이 불가합니다.');
	window.location = '/';
	</script>
	";
}

//*******************************************************************************
// MD5 결제 데이터 암호화 처리
// 형태 : 상점아이디(StoreId) + 주문번호(OrdNo) + 결제금액(Amt)
//*******************************************************************************


$StoreId 	= "clovergd";
$OrdNo 		= date("Ymd_his")."_".$nClover->code;

$AGS_HASHDATA = md5($StoreId . $OrdNo );


?>
<script language=javascript src="http://www.allthegate.com/plugin/AGSWallet_utf8.js"></script>
<script language=javascript>
<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 올더게이트 플러그인 설치를 확인합니다.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

StartSmartUpdate();

function Pay(form){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// MakePayMessage() 가 호출되면 올더게이트 플러그인이 화면에 나타나며 Hidden 필드
	// 에 리턴값들이 채워지게 됩니다.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(form.Flag.value == "enable"){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 입력된 데이타의 유효성을 검사합니다.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(Check_Common(form) == true){
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 올더게이트 플러그인 설치가 올바르게 되었는지 확인합니다.
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////

			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("플러그인 설치 후 다시 시도 하십시오.");
			}else{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// 올더게이트 플러그인 설정값을 동적으로 적용하기 JavaScript 코드를 사용하고 있습니다.
				// 상점설정에 맞게 JavaScript 코드를 수정하여 사용하십시오.
				//
				// [1] 일반/무이자 결제여부
				// [2] 일반결제시 할부개월수
				// [3] 무이자결제시 할부개월수 설정
				// [4] 인증여부
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [1] 일반/무이자 결제여부를 설정합니다.
				//
				// 할부판매의 경우 구매자가 이자수수료를 부담하는 것이 기본입니다. 그러나,
				// 상점과 올더게이트간의 별도 계약을 통해서 할부이자를 상점측에서 부담할 수 있습니다.
				// 이경우 구매자는 무이자 할부거래가 가능합니다.
				//
				// 예제)
				// 	(1) 일반결제로 사용할 경우
				// 	form.DeviId.value = "9000400001";
				//
				// 	(2) 무이자결제로 사용할 경우
				// 	form.DeviId.value = "9000400002";
				//
				// 	(3) 만약 결제 금액이 100,000원 미만일 경우 일반할부로 100,000원 이상일 경우 무이자할부로 사용할 경우
				// 	if(parseInt(form.Amt.value) < 100000)
				//		form.DeviId.value = "9000400001";
				// 	else
				//		form.DeviId.value = "9000400002";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				form.DeviId.value = "9000400001";

				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [2] 일반 할부기간을 설정합니다.
				//
				// 일반 할부기간은 2 ~ 12개월까지 가능합니다.
				// 0:일시불, 2:2개월, 3:3개월, ... , 12:12개월
				//
				// 예제)
				// 	(1) 할부기간을 일시불만 가능하도록 사용할 경우
				// 	form.QuotaInf.value = "0";
				//
				// 	(2) 할부기간을 일시불 ~ 12개월까지 사용할 경우
				//		form.QuotaInf.value = "0:3:4:5:6:7:8:9:10:11:12";
				//
				// 	(3) 결제금액이 일정범위안에 있을 경우에만 할부가 가능하게 할 경우
				// 	if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
				// 		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
				// 	else
				// 		form.QuotaInf.value = "0";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				//결제금액이 5만원 미만건을 할부결제로 요청할경우 결제실패
				if(parseInt(form.Amt.value) < 50000)
					form.QuotaInf.value = "0";
				else
					form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";

				////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [3] 무이자 할부기간을 설정합니다.
				// (일반결제인 경우에는 본 설정은 적용되지 않습니다.)
				//
				// 무이자 할부기간은 2 ~ 12개월까지 가능하며,
				// 올더게이트에서 제한한 할부 개월수까지만 설정해야 합니다.
				//
				// 100:BC
				// 200:국민
				// 201:NH
				// 300:외환
				// 310:하나SK
				// 400:삼성
				// 500:신한
				// 800:현대
				// 900:롯데
				//
				// 예제)
				// 	(1) 모든 할부거래를 무이자로 하고 싶을때에는 ALL로 설정
				// 	form.NointInf.value = "ALL";
				//
				// 	(2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "200-2:3:4:5:6";
				//
				// 	(3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "300-2:3:4:5:6";
				//
				// 	(4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
				//
				//	(5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정
				//	form.NointInf.value = "NONE";
				//
				//	(6) 전카드사 특정개월수만 무이자를 하고 싶은경우(2:3:6개월)
				//	form.NointInf.value = "100-2:3:6,200-2:3:6,201-2:3:6,300-2:3:6,310-2:3:6,400-2:3:6,500-2:3:6,800-2:3:6,900-2:3:6";
				//
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if(form.DeviId.value == "9000400002")
					form.NointInf.value = "ALL";

				if(MakePayMessage(form) == true){
					Disable_Flag(form);

					var openwin = window.open("{!! route('agspay/AGS_progress') !!}","popup","width=300,height=160"); //"지불처리중"이라는 팝업창연결 부분

					form.submit();
				}else{
					alert("지불에 실패하였습니다.");// 취소시 이동페이지 설정부분
				}
			}
		}
	}
}

function Enable_Flag(form){
        form.Flag.value = "enable"
}

function Disable_Flag(form){
        form.Flag.value = "disable"
}

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("상점아이디를 입력하십시오.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("상점명을 입력하십시오.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("주문번호를 입력하십시오.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("상품명을 입력하십시오.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("금액을 입력하십시오.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("상점URL을 입력하십시오.");
		return false;
	}
	else if(form.OrdNm.value == ""){
		alert("성함을 입력하십시오.");
		return false;
	}
	else if(form.OrdPhone.value == ""){
		alert("휴대폰번호를 입력하십시오.");
		return false;
	}
	else if(form.OrdAddr.value == "" || form.addr1.value == ""){
		alert("주소를 입력하십시오.");
		return false;
	}
	return true;
}

function Display(form){
	if(form.Job.value == "onlycard" || form.TempJob.value == "onlycard"){
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "none";
	}else if(form.Job.value == "onlyhp" || form.TempJob.value == "onlyhp"){
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "none";
		document.all.hp.style.display= "";
		document.all.virtual.style.display= "none";
	}else if(form.Job.value == "onlyvirtual" || form.TempJob.value == "onlyvirtual" ){
		document.all.card_hp.style.display= "none";
		document.all.card.style.display= "";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "";
	}else if(form.Job.value == "onlyiche" || form.TempJob.value == "onlyiche"  ){
		document.all.card_hp.style.display= "none";
		document.all.card.style.display= "none";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "none";
	}else{
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "";
		document.all.hp.style.display= "";
		document.all.virtual.style.display= "";
	}
}
-->
</script>


<SCRIPT>
function mon(n) {
    var obj_point = document.getElementById('point_v');
    for(var i=1; i<4; i++) {
        obj = document.getElementById('span_'+i);
        if ( n == i ) {
            obj.style.display = '';
            obj_point.style.display = '';
        } else {
            obj.style.display = 'none';
            obj_point.style.display = 'none';
        }

    }
		if(n == 3){
			$("#point_v2").show();
		} else if (n == 1){
			$("#point_v2").show();
		} else if(n == 2) {
			$("#point_v2").show();
		}
}


function point_form(form){

	if(form.opoint.value == "" || form.opoint.value < 1){
		alert('후원할 포인트를 입력해주세요');
		form.opoint.focus();
		return;
	}

	if(parseInt(form.opoint.value) > parseInt(form.usepoint.value)){
		alert('보유한 포인트보다 후원할 포인트가 더 많습니다.');
		form.opoint.focus();
		return;
	}

	if(parseInt(form.opoint.value) < 1000){
		alert('포인트는 1000포인트 이상부터 사용하실 수 있습니다.');
		form.opoint.focus();
		return;
	}

	//form.action = "{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=resultpoint&seq=2";
	form.action = "{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=write_resultpoint";
	form.submit();
}
</SCRIPT>
<section class="wrap">
	<h2 class="ti">후원자 기본정보</h2>

	<article class="brd_write">
		<form method="post" id="wrtForm" name="frmAGS_pay" action="{{ route('agspay/AGS_pay_ing') }}">
		<input type="hidden" name="clover_seq" value="{{ $nClover->code }}">
		<input type="hidden" name="clover_name" value="{{ $nClover->subject }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<h2 class="xm_left">후원기관/금액 선택</h2>
		<div class="xm_clr"></div>


		<div class="supporting_agency mt20">
			<div class="title mb10">후원금 납입방법</div>

			<table>
				<caption>후원금 납입방법</caption>
				<colgroup>
					<col class="colWidth199">
					<col class="colWidth583">
				</colgroup>
				<tr >
					<th scope="row" class="first xm_tleft pl30">납입방법</th>
					<td>
						<div class="radioForm">
							<input type="radio" name="Job" id="banking" value="onlyiche" checked onclick="mon(1)">
							<label for="banking" class="mr20">신용카드 & 계좌이체 & 무통장입금</label>
							<!--<input type="radio" name="Job" id="card" value="onlycard" onclick="mon(2)">
							<label for="card" class="mr20">신용카드</label>-->
							<input type="radio" name="Job" id="point" value="point" onclick="mon(3)">
							<label for="point" class="mr20">포인트</label>
						</div>

					</td>
				</tr>

			</table>
		</div>



		<div class="supporting_agency">

		<table>
			<caption>후원자 기본정보</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<tr >
				<th scope="row" class="first xm_tleft pl30">후원기관</th>
				<td>{{ $nClover->subject }}</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">일시 후원금액</th>
				<td>
					<!-- <div class="styled-select" style="margin-left:0;">
						<select id="selectGroup" name="Amt">
						  <option value="10000">1만원</option>
						  <option value="30000">3만원</option>
						  <option value="50000">5만원</option>
						  <option value="100000">10만원</option>
						</select>
					</div>
 -->
											<div class="radioForm">
						<input type="radio" name="price_v_ck" value="10000" id='1m' checked onclick="$('#view_price').val('10000');$('#view_price2').val('10000');$('#opoint').val('10000');">
						<label for="1m" class="mr20">1만원</label>
						<input type="radio" name="price_v_ck" value="30000" id='3m' onclick="$('#view_price').val('30000');$('#view_price2').val('30000');$('#opoint').val('30000');">
						<label for="3m" class="mr20">3만원</label>
						<input type="radio" name="price_v_ck" value="50000" id='5m' onclick="$('#view_price').val('50000');$('#view_price2').val('50000');$('#opoint').val('50000');">
						<label for="5m" class="mr20">5만원</label>
						<input type="radio" name="price_v_ck" value="100000" id='10m' onclick="$('#view_price').val('100000');$('#view_price2').val('100000');$('#opoint').val('100000');">
						<label for="10m" class="mr20">10만원</label>
						<input type="radio" name="price_v_ck" value="100000" id='etc' onclick="$('#view_price').val('');$('#view_price2').val('');$('#opoint').val('');">
						<label for="etc" class="mr20">기타</label>

						<input type="text" name="price" id='view_price' style="width:80px" onkeyup="$('#opoint').val(this.value);$('#view_price2').val(this.value);" value="10000">원
						<input type="hidden" name="Amt" id='view_price2' style="width:90px" onkeyup="$('#opoint').val(this.value);" value="10000">
						</div>

				</td>
			</tr>
			<tr id="point_v" style="display:none;">
				<th scope="row" class="first xm_tleft pl30">포인트</th>
				<td>
					<div class="radioForm">
					<table style="border:none;" cellpadding="0" cellspacing="0" width="100%" border=1>
					<tr>
						<td style="border:none;" width="60" style="text-align:left;"><input type="text" name="opoint" id="opoint" value="10000" style="width:100px;"></td>
						<td style="border:none;">
						<?php
							if($cnt_list=count($nPoint->page_result) < 1){
								echo "0";
							} else {
							for($i=0, $cnt_list=count($nPoint->page_result); $i < $cnt_list; $i++) {
								$nPoint->VarList($nPoint->page_result, $i, null);

								$use_point = $nPoint->inpoint - $nPoint->outpoint;
						?>
							보유 포인트 : {{ number_format($use_point) }}
						<?php
							}}
						?>
						<input type="hidden" name="usepoint" value="{{ $use_point }}" style="width:100px">
						</td>
					</tr>
					</table>




					</div>



				</td>
			</tr>
			<input type=hidden name=StoreId value="{{ $StoreId }}">
			<input type=hidden name=UserId value="{{ Auth::user()->user_id }}">
			<input type=hidden name=OrdNo value="{{ $OrdNo }}"></td>
			<input type=hidden name=MallUrl value="http://www.clovergarden.com">
			<input type=hidden name=StoreNm value="클로버가든">
			<input type=hidden name=ProdNm value="{{ $nClover->subject }}">
			<input type=hidden name=RcpNm value="">
			<input type=hidden name=RcpPhone value="">
			<input type=hidden name=DlvAddr value="">
			<input type=hidden name=Remark value="">
			<input type=hidden name=CardSelect value="">
			<input type=hidden name=MallPage value="/agspay/AGS_VirAcctResult">
			<!--<tr >
				<th scope="row" class="first xm_tleft pl30">포인트 사용</th>
				<td><input type="text" name="supporting_agency" style="width:100px"> /11,000pt
				<div class="xm_right mr30 mt5 checkbox">
						 <input type="checkbox" id="m_info2" name="m_info2" checked="checked"><label for="m_info2" class="fs14 t_bold">모두사용</label>
					</div>
				</td>
			</tr>-->

		</table>

			<div class="c_orange h180 mt5 mb10" id="point_v2" style="float:right;">
				후원금액 및 포인트는 1,000원 단위로 사용 가능합니다.
			</div>
		</div>
		<div class="xm_clr"></div>

		<h2 class="xm_left">후원자 기본정보</h2>
		<div class="xm_left mt20 ml20"><i class="fa fa-caret-right mr5"></i><span class="c_light_gray_3">후원자 정보란에 가입하신 내용으로 기부금 영수증이 발송됩니다.</span></div>
		<table>
			<caption>후원자 기본정보</caption>
			<colgroup>
				<col class="colWidth147">
				<col class="colWidth598">
			</colgroup>
			<tr >
				<th scope="row" class="first xm_tleft pl30">후원자</th>
				<td>
					<div class="xm_left radioForm h200">
					<?php if(Auth::check()) { ?>
					    <div class="mr20">
								{{ Auth::user()->user_name }}
								<a href="#" id="btn_loadExInfo" class="ml10 green_btn" style="width:160px" onclick="loadExInfo()" >기존 후원 정보 불러오기</a>
							</div>
					<?php } ?>
					</div>

					<!--<div class="xm_right mr30 mt5 checkbox">
						 <input type="checkbox" id="m_info" name="m_info" checked="checked"><label for="m_info" class="fs14 t_bold">회원기본정보와 동일</label>
					</div>-->
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">성명</th>
				<td><input type="text" name="OrdNm" style="width:312px" value="{{ Auth::user()->user_name }}"></td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">휴대폰번호</th>
				<td>
					<div class="xm_left">
						<?php
							$login_cell = '';;
							if(Auth::check()) $login_cell = Auth::user()->user_cell;
							$login_cell1 = substr($login_cell, 0, 3);
							$login_cell2 = substr($login_cell, 3, -4);
							$login_cell3 = substr($login_cell, -4);
						?>
						<input type="text" name="OrdPhone" class="w97 mr10 onlyNumber" value="{{ $login_cell }}">
						<!-- <input type="text" name="OrdPhone2" class="w97 mr10 onlyNumber" value="{{ $login_cell2 }}">
						<input type="text" name="OrdPhone3" class="w97 onlyNumber" value="{{ $login_cell3 }}">  -->
					</div>
					<div class="xm_left ml10 mt5 checkbox">
						<input type="checkbox" id="sms" name="sms" checked="checked"><label for="sms" class="fs14 t_bold">SMS수신</label>
					</div>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">우편번호</th>
				<td>
					<input type="text" name="OrdAddr" id="postcode1" class="w97 onlyNumber" value="{{ Auth::user()->post1 }}"> <strong class="fs14 c_light_gray_3">-</strong>
					<input type="text" name="OrdAddr2" id="postcode2" class="w97 onlyNumber" value="{{ Auth::user()->post2 }}">
					<a href="#" class="ml10 green_btn" style="width:100px" onclick="sample6_execDaumPostcode()" >우편번호 찾기</a>
				</td>
			</tr>


			<tr >
				<th scope="row" class="first xm_tleft pl30">주소</th>
				<td><input type="text" name="addr1" style="width:240px;" id="address" value="{{ Auth::user()->addr1 }}"> <input type="text" name="addr2" class="ml10" style="width:240px;" id="address2" value="{{ Auth::user()->addr2 }}"></td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">이메일주소</th>
				<td>
					<?php
						$login_email = explode('@', Auth::user()->user_id);
					?>
					<div class="xm_left"><input type="text" name="UserEmail" class="w130" value="{{ Auth::user()->user_id }}">&nbsp; <!--<strong class="fs14 c_light_gray_3">@</strong> <input type="text" name="UserEmail2" class="w130" disabled="disabled" value="{{ $login_email[1] }}"> </div> -->
					<!--<div class="xm_left styled-select" style="margin-top:2px; margin-left:10px">
					   <select id="selectEmail">
						  <option value="">메일선택</option>
						  <option value="nate.com">nate.com</option>
						  <option value="naver.com">naver.com</option>
						  <option value="daum.net">daum.net</option>
						  <option value="gmail.com">gmail.com</option>
						  <option value="direct">직접입력</option>
					   </select>
					</div>-->
					<div class="xm_right mt5 mr30 checkbox">
						<input type="checkbox" id="email" name="email" checked="checked"><label for="email" class="fs14 t_bold">e-mail 수신</label>
					</div>
				</td>
			</tr>
			<tr height=10 id='v7'>
				<td colspan=2 style="border:none;">
		<h2 class="xm_left">(선택) 법인 기본정보</h2>
		<div class="xm_left mt20 ml20"><i class="fa fa-caret-right mr5"></i><span class="c_light_gray_3">법인으로 후원을 신청하는 경우이며, 기부금 영수증은 법인으로 발송됩니다.</span></div>
				</td>
			</tr>

			<tr id='v8'>
				<th scope="row" class="first xm_tleft pl30">사업자번호</th>
				<td><input type=text class=formbox_input style=width:100px value=""></td>
			</tr>

			<tr id='v9'>
				<th scope="row" class="first xm_tleft pl30">상호</th>
				<td><input type=text class=formbox_input style=width:100px value=""></td>
			</tr>

			<tr id='v10'>
				<th scope="row" class="first xm_tleft pl30">대표자</th>
				<td><input type=text class=formbox_input style=width:100px value=""></td>
			</tr>

			<tr id='v11'>
				<th scope="row" class="first xm_tleft pl30">업태</th>
				<td><input type=text class=formbox_input style=width:100px value=""></td>
			</tr>

			<tr id='v12'>
				<th scope="row" class="first xm_tleft pl30">종목</th>
				<td><input type=text class=formbox_input style=width:100px value=""></td>
			</tr>
		</table>

		<!--<div class="supporting_agency2 mt20">
			<div class="title"><i class="fa fa-circle-o c_light_gray_3 mr5"></i>기부 영수증 발급</div>
			<div class="c_orange h180 mt5 mb10">
				연말정산 기부금 영수증 발급을 원하시는 분은 후원자님의 주민번호를 정확히 입력해주세요.<br>기부금 영수증은 국세청 연말정산 간소화 서비스에서 발급하실 수 있습니다.
			</div>

			<table>
				<caption>후원자 기본정보</caption>
				<colgroup>
					<col class="colWidth147">
					<col class="colWidth598">
				</colgroup>
				<tr >
					<th scope="row" class="first xm_tleft pl30">주민등록번호</th>
					<td>
						<input type="text" name="jumin1" class="w97 onlyNumber"> <strong class="fs14 c_light_gray_3 ml5">-</strong>
						<input type="text" name="jumin2" class="w97 onlyNumber">
					</td>
				</tr>
			</table>
		</div>-->





		<!--<div class="supporting_agency2 mt20" style="border-bottom:0; padding-bottom:0; margin-bottom:0">
			<div class="title"><i class="fa fa-circle-o c_light_gray_3 mr5"></i>홈페이지 회원가입 신청</div>
			<div class="c_light_gray_3 h180 mt5 mb10">
				홈페이지에 가입하시면 후원신청내역 및 후원과 관련된 다양한 서비스를 이용하실 수 있습니다.
			</div>

			<table>
				<caption>후원자 기본정보</caption>
				<colgroup>
					<col>
					<col>
					<col>
					<col>
				</colgroup>
				<tr >
					<th scope="row" class="first xm_tleft pl30">아이디(e-mail)</th>
					<td colspan="3">
						<input type="text" name="id">
						<a href="#" class="ml10 gray_big_btn_94_27">중복확인</a>
					</td>
				</tr>
				<tr >
					<th scope="row" class="first xm_tleft pl30">비밀번호</th>
					<td>
						<input type="password" name="pwd">
					</td>
					<th scope="row" class="first xm_tleft pl30">비밀번호 확인</th>
					<td>
						<input type="password" name="re_pwd">
					</td>
				</tr>
			</table>
		</div>-->

		<div class="clover_terms" style="width:100%">

			<div class="ml10 clover_terms_wrap" style="width:100%">
				<div class="title"><i class="fa fa-circle-o c_light_gray_3 mr5"></i>일시 후원 이용 약관</div>

				<?php
					for($i=0, $cnt_list=count($nAdm_4->page_result); $i < $cnt_list; $i++) {
						$nAdm_4->VarList($nAdm_4->page_result, $i, null);
				?>
				<textarea>{{ $nAdm_4->t_text }}</textarea>
				<?php
					}
				?>

			</div>
		</div>
		<div class="xm_clr"></div>
		<div class="nanum fs14 checkbox">
			<input type="checkbox" id="agree" name="agree" checked="checked"><label for="agree" class="fs14 t_bold">이용약관과 개인정보취급방침에 동의합니다.</label>
		</div>
		<div class="mt20 box2">
		<a href="#" class="gray_big_btn2">취소</a>
		<span id="span_1" style="display:;"><a href="javascript:Pay(frmAGS_pay);" id="save" class="ml10 orange_big_btn">신청완료</a></span>
		<span id="span_2" style="display:none;"><a href="javascript:Pay(frmAGS_pay);" id="save" class="ml10 orange_big_btn">신청완료</a></span>
		<span id="span_3" style="display:none;"><a href="javascript:point_form(frmAGS_pay);" id="save" class="ml10 orange_big_btn">신청완료</a></span>
		</div>


<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->

<!-- 각 결제 공통 사용 변수 -->
<input type=hidden name=Flag value="">				<!-- 스크립트결제사용구분플래그 -->
<input type=hidden name=AuthTy value="">			<!-- 결제형태 -->
<input type=hidden name=SubTy value="">				<!-- 서브결제형태 -->
<input type=hidden name=AGS_HASHDATA value="{{ $AGS_HASHDATA }}">	<!-- 암호화 HASHDATA -->

<!-- 신용카드 결제 사용 변수 -->
<input type=hidden name=DeviId value="">			<!-- (신용카드공통)		단말기아이디 -->
<input type=hidden name=QuotaInf value="0">			<!-- (신용카드공통)		일반할부개월설정변수 -->
<input type=hidden name=NointInf value="NONE">		<!-- (신용카드공통)		무이자할부개월설정변수 -->
<input type=hidden name=AuthYn value="">			<!-- (신용카드공통)		인증여부 -->
<input type=hidden name=Instmt value="">			<!-- (신용카드공통)		할부개월수 -->
<input type=hidden name=partial_mm value="">		<!-- (ISP사용)			일반할부기간 -->
<input type=hidden name=noIntMonth value="">		<!-- (ISP사용)			무이자할부기간 -->
<input type=hidden name=KVP_RESERVED1 value="">		<!-- (ISP사용)			RESERVED1 -->
<input type=hidden name=KVP_RESERVED2 value="">		<!-- (ISP사용)			RESERVED2 -->
<input type=hidden name=KVP_RESERVED3 value="">		<!-- (ISP사용)			RESERVED3 -->
<input type=hidden name=KVP_CURRENCY value="">		<!-- (ISP사용)			통화코드 -->
<input type=hidden name=KVP_CARDCODE value="">		<!-- (ISP사용)			카드사코드 -->
<input type=hidden name=KVP_SESSIONKEY value="">	<!-- (ISP사용)			암호화코드 -->
<input type=hidden name=KVP_ENCDATA value="">		<!-- (ISP사용)			암호화코드 -->
<input type=hidden name=KVP_CONAME value="">		<!-- (ISP사용)			카드명 -->
<input type=hidden name=KVP_NOINT value="">			<!-- (ISP사용)			무이자/일반여부(무이자=1, 일반=0) -->
<input type=hidden name=KVP_QUOTA value="">			<!-- (ISP사용)			할부개월 -->
<input type=hidden name=CardNo value="">			<!-- (안심클릭,일반사용)	카드번호 -->
<input type=hidden name=MPI_CAVV value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=MPI_ECI value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=MPI_MD64 value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=ExpMon value="">			<!-- (일반사용)			유효기간(월) -->
<input type=hidden name=ExpYear value="">			<!-- (일반사용)			유효기간(년) -->
<input type=hidden name=Passwd value="">			<!-- (일반사용)			비밀번호 -->
<input type=hidden name=SocId value="">				<!-- (일반사용)			주민등록번호/사업자등록번호 -->

<!-- 계좌이체 결제 사용 변수 -->
<input type=hidden name=ICHE_OUTBANKNAME value="">	<!-- 이체계좌은행명 -->
<input type=hidden name=ICHE_OUTACCTNO value="">	<!-- 이체계좌예금주주민번호 -->
<input type=hidden name=ICHE_OUTBANKMASTER value=""><!-- 이체계좌예금주 -->
<input type=hidden name=ICHE_AMOUNT value="">		<!-- 이체금액 -->

<!-- 핸드폰 결제 사용 변수 -->
<input type=hidden name=HP_SERVERINFO value="">		<!-- 서버정보 -->
<input type=hidden name=HP_HANDPHONE value="">		<!-- 핸드폰번호 -->
<input type=hidden name=HP_COMPANY value="">		<!-- 통신사명(SKT,KTF,LGT) -->
<input type=hidden name=HP_IDEN value="">			<!-- 인증시사용 -->
<input type=hidden name=HP_IPADDR value="">			<!-- 아이피정보 -->

<!-- ARS 결제 사용 변수 -->
<input type=hidden name=ARS_PHONE value="">			<!-- ARS번호 -->
<input type=hidden name=ARS_NAME value="">			<!-- 전화가입자명 -->

<!-- 가상계좌 결제 사용 변수 -->
<input type=hidden name=ZuminCode value="">			<!-- 가상계좌입금자주민번호 -->
<input type=hidden name=VIRTUAL_CENTERCD value="">	<!-- 가상계좌은행코드 -->
<input type=hidden name=VIRTUAL_NO value="">		<!-- 가상계좌번호 -->

<input type=hidden name=mTId value="">

<!-- 에스크로 결제 사용 변수 -->
<input type=hidden name=ES_SENDNO value="">			<!-- 에스크로전문번호 -->

<!-- 계좌이체(소켓) 결제 사용 변수 -->
<input type=hidden name=ICHE_SOCKETYN value="">		<!-- 계좌이체(소켓) 사용 여부 -->
<input type=hidden name=ICHE_POSMTID value="">		<!-- 계좌이체(소켓) 이용기관주문번호 -->
<input type=hidden name=ICHE_FNBCMTID value="">		<!-- 계좌이체(소켓) FNBC거래번호 -->
<input type=hidden name=ICHE_APTRTS value="">		<!-- 계좌이체(소켓) 이체 시각 -->
<input type=hidden name=ICHE_REMARK1 value="">		<!-- 계좌이체(소켓) 기타사항1 -->
<input type=hidden name=ICHE_REMARK2 value="">		<!-- 계좌이체(소켓) 기타사항2 -->
<input type=hidden name=ICHE_ECWYN value="">		<!-- 계좌이체(소켓) 에스크로여부 -->
<input type=hidden name=ICHE_ECWID value="">		<!-- 계좌이체(소켓) 에스크로ID -->
<input type=hidden name=ICHE_ECWAMT1 value="">		<!-- 계좌이체(소켓) 에스크로결제금액1 -->
<input type=hidden name=ICHE_ECWAMT2 value="">		<!-- 계좌이체(소켓) 에스크로결제금액2 -->
<input type=hidden name=ICHE_CASHYN value="">		<!-- 계좌이체(소켓) 현금영수증발행여부 -->
<input type=hidden name=ICHE_CASHGUBUN_CD value="">	<!-- 계좌이체(소켓) 현금영수증구분 -->
<input type=hidden name=ICHE_CASHID_NO value="">	<!-- 계좌이체(소켓) 현금영수증신분확인번호 -->

<!-- 텔래뱅킹-계좌이체(소켓) 결제 사용 변수 -->
<input type=hidden name=ICHEARS_SOCKETYN value="">	<!-- 텔레뱅킹계좌이체(소켓) 사용 여부 -->
<input type=hidden name=ICHEARS_ADMNO value="">		<!-- 텔레뱅킹계좌이체 승인번호 -->
<input type=hidden name=ICHEARS_POSMTID value="">	<!-- 텔레뱅킹계좌이체 이용기관주문번호 -->
<input type=hidden name=ICHEARS_CENTERCD value="">	<!-- 텔레뱅킹계좌이체 은행코드 -->
<input type=hidden name=ICHEARS_HPNO value="">		<!-- 텔레뱅킹계좌이체 휴대폰번호 -->

<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->
		</form>
	</article>
</section>


<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function sample6_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById("postcode1").value = data.postcode1;
                document.getElementById("postcode2").value = data.postcode2;
                document.getElementById("address").value = fullAddr;

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("address2").focus();
            }
        }).open();
    }

		function loadExInfo() {
			$.getJSON('/clovergarden/getLatestSupportInfo/' + '{{ Auth::user()->user_id }}', function(data) {
				if (data == -1) {
					alert('기존 후원 정보가 존재하지 않습니다');
					return;
				}

				// Set data
				var fm = document.frmAGS_pay;
				fm.OrdNm.value = data.name;
				fm.OrdPhone.value = data.cell;

				// Parse zip number
				var zip = data.zip.split('-');
				fm.OrdAddr.value = zip[0];
				fm.OrdAddr2.value = zip[1];

				fm.addr1.value = data.address;
				fm.addr2.value = ""; // 초기화

				fm.UserEmail.value = data.email;
			});
		}
</script>
@stop
