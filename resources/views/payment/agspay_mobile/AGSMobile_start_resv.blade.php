<!DOCTYPE html>
<html>
	<head>
		<title>정기후원 신청하기</title>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi"/>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/mobile-pay.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="/js/mobile-pay.js"></script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

	</head>
	<body>
		<div id="mobile-pay-container"><form method="POST" action="{{ route('AGSMobile_start_resv_ing') }}" onsubmit="return proceedValidation(false);" name="frmags5pay">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />

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
					<input type="number" name="price_option" placeholder="금액 입력" pattern="[0-9]*" inputmode="numeric" onkeyup="inputNumberFormat(this)" />
					<span>,000원</span>
				</div>
			</div>

			<div class="block">
				<span class="title">결제방법</span>
				<div id="method-reserve">
					<ul>
						<li class="on"><a>자동이체</a></li>
						<li><a>신용카드</a></li>
						<li><a>포인트</a></li>
						<input type="hidden" name="otype" value="자동이체" />
					</ul>

					<table id="payment-bankbook" class="payment-info">
						<colgroup>
							<col width="100px">
							<col width="auto">
						</colgroup>
						<tbody>
							<tr>
								<td><span class="subtitle bank">출금은행</span<</td>
								<td>
									<select name="bank" title="출금은행 선택하세요">
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
							<tr>
								<td><span class="subtitle book">출금계좌</span<</td>
								<td>
									<input type="number" class="full" name="banknum" pattern="[0-9]*" inputmode="numeric" />
									<span style="display: block; font-size: 12px; float: right; color: #CAC9C9">-없이 숫자만 입력해주세요</span>
								</td>
							</tr>
							<tr>
								<td><span class="subtitle date">출금일</span<</td>
								<td>
									<select class="withButton" name="date">
									  <option value="5">5일</option>
									  <option value="10">10일</option>
									  <option value="15">15일</option>
									  <option value="20">20일</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table id="payment-card" class="payment-info" style="display: none;">
						<colgroup>
							<col width="100px">
							<col width="auto">
						</colgroup>
						<tbody>
							<tr>
								<td><span class="subtitle bank">카드사</span<</td>
								<td>
									<select name="card" title="카드사를 선택하세요" style="width:158px">
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
							<tr>
								<td><span class="subtitle book">카드번호</span<</td>
								<td>
									<input type="number" class="full" name="cardnum" pattern="[0-9]*" inputmode="numeric" />
									<span style="display: block; font-size: 12px; float: right; color: #CAC9C9">-없이 숫자만 입력해주세요</span>
								</td>
							</tr>
							<tr>
								<td><span class="subtitle expire">유효기간</span<</td>
								<td>
									<input type="number" class="small" name="carddate1" pattern="[0-9]*" inputmode="numeric" />
									<span>월</span>
									<input type="number" class="small" name="carddate2" pattern="[0-9]*" inputmode="numeric" />
									<span>일</span>
								</td>
							</tr>
							<tr>
								<td><span class="subtitle date">출금일</span<</td>
								<td>
									<select class="withButton" name="date">
									  <option value="5">5일</option>
									  <option value="10">10일</option>
									  <option value="15">15일</option>
									  <option value="20">20일</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

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
								<input type="hidden" name="usepoint" value="{{ $user_point }}" />
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
							<td><span style="color: #35CB02;">유종민</span><input type="hidden" name="name" style="width:312px" value="유종민"></td>
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

			<input type="submit" class="btn full" value="정기후원 신청하기" />

    </form></div>

    <!-- iOS에서는 position:fixed 버그가 있음, 적용하는 사이트에 맞게 position:absolute 등을 이용하여 top,left값 조정 필요 -->
		<div id="postLayer" style="display:none;position:absolute;top:0;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
			<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
		</div>
	</body>
</html>
