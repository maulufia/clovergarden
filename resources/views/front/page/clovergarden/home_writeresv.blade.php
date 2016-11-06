@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

	$nClover = new CloverClass(); //클로버목록
	$nPoint = new PointClass(); //포인트
	$nMember = new MemberClass();
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

		//======================== DB Module Start ============================
		$nPoint->page_result = $Conn->AllList
		(
			$nPoint->table_name, $nPoint, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='" . Auth::user()->user_id . "' group by userid", null, null
		);
		$nMember->page_result = $Conn->AllList
		(
			$nMember->table_name, $nMember, "*", "where user_id='" . Auth::user()->user_id . "'", null, null
		);
		$user_name_ck = '';
		for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
			$nMember->VarList($nMember->page_result, $i, null);
			$user_name_ck = $nMember->user_name;
			$post1 = $nMember->post1;
			$post2 = $nMember->post2;
			$addr1 = $nMember->addr1;
			$addr2 = $nMember->addr2;

		}
$nAdm_4->page_result = $Conn->AllList
(
	$nAdm_4->table_name, $nAdm_4, "*", "where t_name='use_v_3' order by idx desc limit 1", null, null
);
	$Conn->DisConnect();
	//======================== DB Module End ===============================
if(Auth::user()->user_state == 4){
	echo "
	<script>
	alert('기업담당자는 이용이 불가합니다.');
	window.location = '/';
	</script>
	";
}

$a = 13000;
$b = $a%1000;
?>

<script>
function Display(value){
	if(value == "신용카드"){
		$('.bank').hide();
		$('.banknum').hide();
		$('.card').show();
		$('.cardnum').show();
		$('.carddate').show();
		$('#point_v').hide();
		$('#point_v2').show();
		$('#v6').show();
		$('#v7').show();
		$('#v8').show();
		$('#v9').show();
		$('#v10').show();
		$('#v11').show();
		$('#v12').show();
	}else if(value == "자동이체"){

		$('.bank').show();
		$('.banknum').show();
		$('.card').hide();
		$('.cardnum').hide();
		$('.carddate').hide();
		$('#point_v').hide();
		$('#point_v2').show();
		$('#v6').show();
		$('#v7').show();
		$('#v8').show();
		$('#v9').show();
		$('#v10').show();
		$('#v11').show();
		$('#v12').show();
	} else if (value == "point"){
		$('#point_v').show();
		$('#point_v2').show();
		$('#v1').hide();
		$('#v2').hide();
		$('#v3').hide();
		$('#v4').hide();
		$('#v5').hide();
		$('#v6').hide();
		/*
		$('#v7').hide();
		$('#v8').hide();
		$('#v9').hide();
		$('#v10').hide();
		$('#v11').hide();
		$('#v12').hide();
		*/

	}
}
</script>


<section class="wrap">
	<h2 class="ti">후원자 기본정보</h2>

	<article class="brd_write">
		<form name=frmags5pay method=post action="{{ $writeresv_link }}" style="display:inline;" onsubmit="return order_submit_func();" enctype="multipart/form-data">
		<input type=hidden class=formbox_input name=PreAmount>
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
							<input type="radio" name="otype" value="자동이체" id="iche" checked onchange="Display(this.value)">
							<label for="iche" class="mr20">자동이체</label>

							<input type="radio" name="otype" value="신용카드" id="card" onchange="Display(this.value)">
							<label for="card" class="mr20">신용카드</label>
							<input type="radio" name="otype" value="point" id="point" onchange="Display(this.value)">
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
				<td>
					{{ $nClover->subject }}
					<input type=hidden name="clover_seq" value="{{ $nClover->code }}">
					<input type=hidden name="clover_name" value="{{ $nClover->subject }}">
				</td>
			</tr>


			<tr >
				<th scope="row" class="first xm_tleft pl30">정기 후원금액</th>
				<td>
						<div class="radioForm">
						<input type="radio" name="price_v_ck" value="10000" id='1m' checked onclick="$('#view_price').val('10000');$('#supporting_agency').val('10000');">
						<label for="1m" class="mr20">1만원</label>
						<input type="radio" name="price_v_ck" value="30000" id='3m' onclick="$('#view_price').val('30000');$('#supporting_agency').val('30000');">
						<label for="3m" class="mr20">3만원</label>
						<input type="radio" name="price_v_ck" value="50000" id='5m' onclick="$('#view_price').val('50000');$('#supporting_agency').val('50000');">
						<label for="5m" class="mr20">5만원</label>
						<input type="radio" name="price_v_ck" value="100000" id='10m' onclick="$('#view_price').val('100000');$('#supporting_agency').val('100000');">
						<label for="10m" class="mr20">10만원</label>
						<input type="radio" name="price_v_ck" value="100000" id='etc' onclick="$('#view_price').val('');$('#supporting_agency').val('');">
						<label for="etc" class="mr20">기타</label>
						<input type="text" name="price" id='view_price' style="width:90px" onkeyup="$('#supporting_agency').val(this.value);" value="10000">원
						</div>
				</td>
			</tr>

			<tr id="point_v" style="display:none;">
				<th scope="row" class="first xm_tleft pl30">보유포인트</th>
				<td>
					<input type="hidden" name="supporting_agency" value="10000" id="supporting_agency" style="width:100px">
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
			<?php if(Auth::check()){ ?>
			<tr >
				<th scope="row" class="first xm_tleft pl30">* 후원자</th>
				<td>
					<div class="xm_left radioForm h200">
					    <div class="mr20">
								{{ Auth::user()->user_name }}<input type="hidden" name="name" value="{{ Auth::user()->user_name }}">
								<a href="#" id="btn_loadExInfo" class="ml10 green_btn" style="width:160px" onclick="loadExInfo()" >기존 후원 정보 불러오기</a>
							</div>
					</div>
				</td>
			</tr>
			<?php } else { ?>
			<tr >
				<th scope="row" class="first xm_tleft pl30">성명</th>
				<td><input type="text" name="name" style="width:312px" value=""></td>
			</tr>
			<?php } ?>
			<tr>
				<th scope="row" class="first xm_tleft pl30">* 생년월일</th>
				<td><input type=text class=formbox_input name="birth" style=width:100px maxlength=8 value=""></td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">* 휴대폰번호</th>
				<td>
					<div class="xm_left">
						<?php
							$login_cell = '';;
							if(Auth::check()) $login_cell = Auth::user()->user_cell;
							$login_cell1 = substr($login_cell, 0, 3);
							$login_cell2 = substr($login_cell, 3, -4);
							$login_cell3 = substr($login_cell, -4);
						?>
						<input type="text" name="cell1" class="w97 mr10 onlyNumber" value="{{ $login_cell1 }}">
						<input type="text" name="cell2" class="w97 mr10 onlyNumber" value="{{ $login_cell2 }}">
						<input type="text" name="cell3" class="w97 onlyNumber" value="{{ $login_cell3 }}">
					</div>
					<div class="xm_left ml10 mt5 checkbox">
						<input type="checkbox" id="sms" name="sms" checked="checked"><label for="sms" class="fs14 t_bold">SMS수신</label>
					</div>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">우편번호</th>
				<td>
					<input type="text" name="zip1" id="postcode1" class="w97 onlyNumber" value='{{ $post1 }}'> <strong class="fs14 c_light_gray_3">-</strong>
					<input type="text" name="zip2" id="postcode2" class="w97 onlyNumber" value='{{ $post2 }}'>
					<a href="#" class="ml10 green_btn" style="width:100px" onclick="sample6_execDaumPostcode()" >우편번호 찾기</a>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">주소</th>
				<td><input type="text" name="addr1" style="width:240px;" id="address" value="{{ $addr1 }}"> <input type="text" name="addr2" class="ml10" style="width:240px;" id="address2" value="{{ $addr2 }}"></td>
			</tr>


			<tr class="bank" id='v1'>
				<th scope="row" class="first xm_tleft pl30">* 출금은행</th>
				<td>
				<select name="bank" title="출금은행 선택하세요" style="width:158px">
					<option value="">선택</option>
					<option value="기업은행">기업은행</option>
					<option value="국민은행">국민은행</option>
					<option value="외환은행">외환은행</option>
					<option value="수협은행">수협은행</option>
					<option value="농협은행">농협은행</option>
					<option value="우리은행">우리은행</option>
					<option value="제일은행">제일은행</option>
					<option value="씨티은행">씨티은행</option>
					<option value="광주은행">광주은행</option>
					<option value="제주은행">제주은행</option>
					<option value="전북은행">전북은행</option>
					<option value="경남은행">경남은행</option>
					<option value="새마을은행">새마을은행</option>
					<option value="신협은행">신협은행</option>
					<option value="우체국은행">우체국은행</option>
					<option value="하나은행">하나은행</option>
					<option value="신한은행">신한은행</option>
					<option value="대구은행">대구은행</option>
					<option value="부산은행">부산은행</option>


				</select>

				</td>
			</tr>

			<tr class="banknum" id='v2'>
				<th scope="row" class="first xm_tleft pl30">* 출금계좌</th>
				<td><input type=text name="banknum" class=formbox_input style=width:350px value=""> * - 없이 숫자만 입력 바랍니다. </td>
			</tr>

			<tr  id='v3' class="card" style="display:none;">
				<th scope="row" class="first xm_tleft pl30">* 카드사</th>
				<td>

				<select name="card" id="card_ck" title="카드사를 선택하세요" style="width:158px">
					<option value="">선택</option>
					<option value="BC카드">BC카드</option>
					<option value="KB카드">KB카드</option>
					<option value="NH카드">NH카드</option>
					<option value="수협카드">수협카드</option>
					<option value="한미카드">한미카드</option>
					<option value="우리카드">우리카드</option>
					<option value="씨티카드">씨티카드</option>
					<option value="외환카드">외환카드</option>
					<option value="제주카드">제주카드</option>
					<option value="광주은행">광주은행</option>
					<option value="전북카드">전북카드</option>
					<option value="산은캐피탈">산은캐피탈</option>
					<option value="주택비자">주택비자</option>
					<option value="하나SK카드">하나SK카드</option>
					<option value="삼성카드">삼성카드</option>
					<option value="신한카드">신한카드</option>
					<option value="현대카드">현대카드</option>
					<option value="롯데카드">롯데카드</option>
					<option value="해외VISA">해외VISA</option>


				</select>



				</td>
			</tr>

			<tr  id='v4' class="cardnum" style="display:none;">
				<th scope="row" class="first xm_tleft pl30">* 카드번호</th>
				<td><input type=text name="cardnum" class=formbox_input style=width:350px> * - 없이 숫자만 입력 바랍니다. </td>
			</tr>
			<tr  id='v5' class="carddate" style="display:none;">
				<th scope="row" class="first xm_tleft pl30">* 유효기간</th>
				<td><input type=text name="carddate1" class=formbox_input style=width:50px> 월 <input type=text name="carddate2"  class=formbox_input style=width:80px> 년</td>
			</tr>
			<tr id='v6'>
				<th scope="row" class="first xm_tleft pl30">* 출금일</th>
				<td>
					<select id="selectGroup" name="day">
					  <option value="5">5일</option>
					  <option value="10">10일</option>
					  <option value="15">15일</option>
					  <option value="20">20일</option>
					</select>
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




		<div class="clover_terms" style="width:100%">

			<div class="ml10 clover_terms_wrap" style="width:100%">
				<div class="title"><i class="fa fa-circle-o c_light_gray_3 mr5"></i>정기 후원 이용 약관</div>

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
		<div class="mt20 box2"><a href="#" class="gray_big_btn2">취소</a> <input type="submit" id="save" class="ml10 orange_big_btn" value="신청완료" style="border:0;"></div>
		{{ UserHelper::SubmitHidden() }}
		</form>
	</article>
</section>


<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
	function order_submit_func(){
		var fm = document.frmags5pay;
		if(fm.otype[0].checked == true){
			if(fm.birth.value == ''){
				alert('생년월일을 입력해주세요.');
				return false;
			}
			if(fm.cell1.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell2.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell3.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.bank.value == ''){
				alert('은행명을 선택해주세요.');
				return false;
			}
			if(fm.banknum.value == ''){
				alert('계좌번호를 입력해주세요.');
				return false;
			}
		} else if(fm.otype[1].checked == true){
			if(fm.birth.value == ''){
				alert('생년월일을 입력해주세요.');
				return false;
			}
			if(fm.cell1.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell2.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell3.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if($('#card_ck').val() == ''){
				alert('카드사를 선택해주세요.');
				return false;
			}
			if(fm.cardnum.value == ''){
				alert('카드번호를 입력해주세요.');
				return false;
			}
			if(fm.carddate1.value == ''){
				alert('유효기간을 입력해주세요.');
				return false;
			}
			if(fm.carddate2.value == ''){
				alert('유효기간을 입력해주세요.');
				return false;
			}
		} else if(fm.otype[2].checked == true){
			if(fm.birth.value == ''){
				alert('생년월일을 입력해주세요.');
				return false;
			}
			if(fm.cell1.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell2.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
			if(fm.cell3.value == ''){
				alert('휴대폰 번호를 입력해주세요.');
				return false;
			}
		}
		if(confirm('기입해주신 정보로 후원이 신청됩니다.')){

		}
	}

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
			var fm = document.frmags5pay;
			fm.birth.value = data.birth;

			// Parse cellphone number
			var cell = data.cell.split('-');
			fm.cell1.value = cell[0];
			fm.cell2.value = cell[1];
			fm.cell3.value = cell[2];

			// Parse zip number
			var zip = data.zip.split('-');
			fm.zip1.value = zip[0];
			fm.zip2.value = zip[1];

			fm.addr1.value = data.address;
			fm.addr2.value = ""; // 초기화

			// 자동이체 (계좌)
			fm.bank.value = data.bank;
			fm.banknum.value = data.banknum;

			// 신용카드
			fm.card.value = data.

			fm.day.value = data.day;
		});
	}
</script>
@stop
