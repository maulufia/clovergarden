@extends('front.page.clovergarden')

@section('clovergarden')
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
					<td>정기후원</td>
					<?php
						$mClover = new CloverModel();
						$clover_name_v = $mClover->getCloverList();
					?>
					<td>{{ $clover_name_v[$_POST['clover_seq']] }}</td>
					<td class="xm_tright mr10 last">{{ number_format($_POST['price']) }} 원</td>
				</tr>
			</table>
		</div>
		<div class="xm_clr"></div>

		<h2 class="xm_left">후원금 납입방법</h2>
		<div class="xm_left mt20 ml10 c_light_gray_3 fs11"></div>
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
						{{ $_POST['otype'] }}
						</div>
					</div>	
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">납입자</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['name'] }}</div>
					</div>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">약정일</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['day'] }}일</div>
					</div>
				</td>
			</tr>		
<?php if($_POST['otype'] == '신용카드'){ ?>
			<tr >
				<th scope="row" class="first xm_tleft pl30">은행명</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['bank'] }}</div>
					</div>
				</td>
			</tr>			
			<tr >
				<th scope="row" class="first xm_tleft pl30">카드번호</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['banknum'] }}</div>
					</div>
				</td>
			</tr>	
			<tr >
				<th scope="row" class="first xm_tleft pl30">유효기간</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['bankdate'] }}</div>
					</div>
				</td>
			</tr>		
<?php } else if ($_POST['otype'] == '자동이체'){ ?>
			<tr >
				<th scope="row" class="first xm_tleft pl30">은행명</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['bank'] }}</div>
					</div>
				</td>
			</tr>			
			<tr >
				<th scope="row" class="first xm_tleft pl30">계좌번호</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $_POST['banknum'] }}</div>
					</div>
				</td>
			</tr>
<?php } ?>
		</table>

		</form>
	</article>
</section>
@stop