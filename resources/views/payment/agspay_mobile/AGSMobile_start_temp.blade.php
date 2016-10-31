<?php
    ///////////////////////////////////////////////////////
    //
    // 금액 위변조를 막기 위해,
    // 가격 정보 (Amt) 의 경우 JavaScript로 변경할 수 없습니다.
    // 반드시 ServerScript(asp,php,jsp)에서 가격정보를 세팅한 후 Form에 입력하여 주세요.
    //
    ///////////////////////////////////////////////////////

    $dutyfree = 0; //면세 금액 (amt 중 면세 금액 설정)
    $store_id = "clovergd";
    // $store_id = "aegis"; // 테스트할 경우

    $OrdNo = date("Ymd_his") . "_" . $nClover->code;

    //올더게이트
    $strAegis = "https://www.allthegate.com";
    $strCsrf = "csrf.real.js";

?>

<!DOCTYPE html>
<html>
	<head>
		<title>일시후원 신청하기</title>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi"/>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/mobile-pay.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
		<script type="text/javascript" src="/js/mobile-pay.js"></script>
		<script type="text/javascript" charset="euc-kr" src="{{ $strAegis }}/payment/mobilev2/csrf/{{ $strCsrf }}"></script>
		<script type="text/javascript" charset="euc-kr">

    function doPay(form) {

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
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
        //  (1) 일반결제로 사용할 경우
        //  form.DeviId.value = "9000400001";
        //
        //  (2) 무이자결제로 사용할 경우
        //  form.DeviId.value = "9000400002";
        //
        //  (3) 만약 결제 금액이 100,000원 미만일 경우 일반할부로 100,000원 이상일 경우 무이자할부로 사용할 경우
        //  if(parseInt(form.Amt.value) < 100000)
        //      form.DeviId.value = "9000400001";
        //  else
        //      form.DeviId.value = "9000400002";
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // [2] 일반 할부기간을 설정합니다.
        //
        // 일반 할부기간은 2 ~ 12개월까지 가능합니다.
        // 0:일시불, 2:2개월, 3:3개월, ... , 12:12개월
        //
        // 예제)
        //  (1) 할부기간을 일시불만 가능하도록 사용할 경우
        //  form.QuotaInf.value = "0";
        //
        //  (2) 할부기간을 일시불 ~ 12개월까지 사용할 경우
        //      form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
        //
        //  (3) 결제금액이 일정범위안에 있을 경우에만 할부가 가능하게 할 경우
        //  if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
        //      form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
        //  else
        //      form.QuotaInf.value = "0";
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //결제금액이 5만원 미만건을 할부결제로 요청할경우 일시불로 결제
        if(parseInt(form.Amt.value) < 50000)
            form.QuotaInf.value = "0";
        else {
            form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // [3] 무이자 할부기간을 설정합니다.
        // (일반결제인 경우에는 본 설정은 적용되지 않습니다.)
        //
        // 무이자 할부기간은 2 ~ 12개월까지 가능하며,
        // 올더게이트에서 제한한 할부 개월수까지만 설정해야 합니다.
        //
        // 100:BC
        // 200:국민
        // 300:외환
        // 400:삼성
        // 500:신한
        // 800:현대
        // 900:롯데
        //
        // 예제)
        //  (1) 모든 할부거래를 무이자로 하고 싶을때에는 ALL로 설정
        //  form.NointInf.value = "ALL";
        //
        //  (2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
        //  form.NointInf.value = "200-2:3:4:5:6";
        //
        //  (3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
        //  form.NointInf.value = "300-2:3:4:5:6";
        //
        //  (4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
        //  form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
        //
        //  (5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정
        //  form.NointInf.value = "NONE";
        //
        //  (6) 전카드사 특정개월수만 무이자를 하고 싶은경우(2:3:6개월)
        //  form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";
        //
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//	모든 할부거래를 무이자
		if(form.DeviId.value == "9000400002") {
			form.NointInf.value = "ALL";
		}


        AllTheGate.pay(document.form);
        return false;
    }

		</script>

	</head>
	<body>
		<div id="mobile-pay-container"><form method="POST" action="<?=$strAegis?>/payment/mobilev2/intro.jsp" onsubmit="return proceedValidation(true);" name="frmags5pay">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="hidden" name="OrdNo" value="{{ $OrdNo }}"/>
			<input type="hidden" name="StoreNm" value="클로버가든"/>
			<input type="hidden" name="StoreId" maxlength="20" value="<?=$store_id?>"/>
			<input type="hidden" name="MallUrl" value="http://<?=$_SERVER["HTTP_HOST"]?>"/>
			<input type="hidden" name="ProdNm" value="{{ $nClover->subject }} 일시후원"/>
			<input type="hidden" name="Amt" value="10000"/>
			<input type="hidden" name="OrdNm" value="{{ Auth::user()->user_name }}"/>
			<input type="hidden" name="OrdPhone" value="01011111234"/>
			<input type="hidden" name="OrdAddr" value="서울시 강남구 청담동">
			<input type="hidden" name="UserId" maxlength="20" value="{{ Auth::user()->user_name }}"/>
			<input type="hidden" name="UserEmail" value="{{ Auth::user()->user_id }}"/>
			<input type="hidden" name="CancelUrl" value="{{ route('AGSMobile_cancel') }}">
			<input type="hidden" name="RtnUrl" value="{{ route('AGSMobile_approve') }}">
			<input type="hidden" name="AppRtnScheme" value="clovergardenapp://">

			<div class="block" style="line-height: 30px">
				<span class="title middle">후원기관</span>
				<span style="margin-left: 30px">{{ $nClover->subject }}</span>
				<input type="hidden" name="clover_seq" value="{{ $nClover->code }}">
				<input type="hidden" name="clover_name" value="{{ $nClover->subject }}">
			</div>

			<div class="block">
				<span class="title">후원금액</span>
				<div id="price-container">
					<a class="price-button on">1만원</a>
					<a class="price-button">3만원</a>
					<a class="price-button">5만원</a>
					<a class="price-button">7만원</a>
					<a class="price-button">10만원</a>
					<a class="price-button">기타</a>
					<input type="hidden" name="price" value="10000" />
				</div>
				<div id="price-input" style="display: none;">
					<input type="text" name="price_option" placeholder="금액 입력" pattern="[0-9]*" inputmode="numeric" onkeyup="inputNumberFormat(this)" />
					<span>,000원</span>
				</div>
			</div>

			<div class="block">
				<span class="title">결제방법</span>
				<div id="method-temp">
					<ul>
						<li class="on"><a>신용카드 & 가상계좌 & 휴대폰</a></li>
						<li><a>포인트</a></li>
						<input type="hidden" name="otype" value="결제모듈" />
					</ul>

					<table id="payment-point" class="payment-info" style="display: none;">
						<colgroup>
							<col width="100px">
							<col width="auto">
						</colgroup>
						<tbody>
							<tr>
								<td><span class="subtitle">보유 포인트</span></td>
								<?php
									if($cnt_list=count($nPoint->page_result) < 1){
										// 포인트 없음
									} else {
										for($i=0, $cnt_list=count($nPoint->page_result); $i < $cnt_list; $i++) {
											$nPoint->VarList($nPoint->page_result, $i, null);

											$use_point = $nPoint->inpoint - $nPoint->outpoint;
										}
									}
									?>
								<td>{{ number_format($use_point)}}</td>
								<input type="hidden" name="usepoint" value="{{ $use_point }}" />
							</tr>
              <tr>
                <td>
                  <span class="subtitle">사용 포인트</span>
                </td>
                <td id="point">10,000</td>
              </tr>
						</tbody>
					</table>

				</div>
			</div>

			<div class="block">
				<span class="title">후원자 기본정보</span>
				<table id="personal-info">
					<colgroup>
						<col width="100px">
						<col width="auto">
					</colgroup>
					<tbody>
						<tr>
							<td><span class="subtitle">후원자</span></td>
							<td><span style="color: #35CB02;">{{ Auth::user()->user_name }}</span><input type="hidden" name="name" style="width:312px" value="{{ Auth::user()->user_name }}"></td>
						</tr>
						<tr>
							<td><span class="subtitle">생년월일</span></td>
							<td><input type="number" class="medium" name="birth" max-length=8 pattern="[0-9]*" inputmode="numeric" placeholder="예) 920227" /></td>
						</tr>
						<tr>
							<td><span class="subtitle">휴대폰번호</span></td>
							<?php
								$login_cell = '';;
								if(Auth::check()) $login_cell = Auth::user()->user_cell;
								$login_cell1 = substr($login_cell, 0, 3);
								$login_cell2 = substr($login_cell, 3, -4);
								$login_cell3 = substr($login_cell, -4);
							?>
							<td><input type="number" class="small" name="cell1" value="{{ $login_cell1 }}" pattern="[0-9]*" inputmode="numeric" /> - <input type="number" class="small" name="cell2" value="{{ $login_cell2 }}" pattern="[0-9]*" inputmode="numeric" /> - <input type="number" class="small" name="cell3" value="{{ $login_cell3 }}" pattern="[0-9]*" inputmode="numeric" /></td>
						</tr>
						<tr>
							<td><span class="subtitle">우편번호</span></td>
							<td><input type="number" class="small" name="zip1" id="postcode1" value="{{ $post1 }}" pattern="[0-9]*" inputmode="numeric" /> - <input type="number" class="small" name="zip2" id="postcode2" value="{{ $post1 }}" pattern="[0-9]*" inputmode="numeric" /> <a href="#" class="btn small" onclick="execDaumPostcode()">우편번호 찾기</a></td>
						</tr>
						<tr>
							<td><span class="subtitle">주소</span></td>
							<td><input type="text" class="full" name="addr" id="address" value="{{ $addr }}" /></td>
						</tr>
            <tr>
							<td><span class="subtitle">세부 주소</span></td>
							<td><input type="text" class="full" name="addr2" id="address2" value="{{ $addr2 }}" /></td>
						</tr>
					</tbody>
				</table>
			</div>

			<input type="submit" id="btnSubmit" class="btn full" value="일시후원 신청하기" onclick="doPay(document.form);" />
			<input type="submit" id="btnSubmitPoint" class="btn full" value="일시후원 신청하기" onclick="doPayByPoint(frmags5pay); return false;" style="display: none;"/>

    </form></div>

		<!-- iOS에서는 position:fixed 버그가 있음, 적용하는 사이트에 맞게 position:absolute 등을 이용하여 top,left값 조정 필요 -->
		<div id="postLayer" style="display:none;position:absolute;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
			<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
		</div>

	</body>
</html>
