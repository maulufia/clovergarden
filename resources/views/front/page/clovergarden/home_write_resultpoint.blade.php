@extends('front.page.clovergarden')

@section('clovergarden')
<?php
  $nClovermlist = new ClovermlistClass(); //후원기관

  $nClovermlist->otype = "point";
  $nClovermlist->clover_seq = $_POST['clover_seq'];
  $nClovermlist->clover_name = $_POST['clover_name'];
	$nClovermlist->name        = Auth::user()->user_name;
	$nClovermlist->id        = Auth::user()->user_id;
  $nClovermlist->price = $_POST['opoint'];
	$nClovermlist->type = 1;
	$rOrdNo = "point".rand(15349,99999);

    $arr_field = array
    (
        'otype','order_num','clover_seq', 'name',"group_name", 'id', 'price', 'order_adm_ck'
    );

    $arr_value = array
    (
        $nClovermlist->otype, $rOrdNo, $nClovermlist->clover_seq, $nClovermlist->name, $group_name, $nClovermlist->id,  $nClovermlist->price, 'y'
    );

//======================== DB Module Clovert ============================
$Conn = new DBClass();


	if($nClovermlist->price%1000 != 0){
		JsAlert("천원 단위로만 후원이 가능합니다.", 0);
		exit;
	}

	$sql = "update new_tb_member set update_ck='Y' where user_id='" . Auth::user()->user_id . "'";
	mysql_query($sql);

  $Conn->StartTrans();
  $out_put = $Conn->insertDB($nClovermlist->table_name, $arr_field, $arr_value);
  if($out_put){
      $Conn->CommitTrans();
  }else{
      $Conn->RollbackTrans();
      $Conn->disConnect();
  }
  
  $clover_name_v = (new \CloverModel())->getCloverList();
	$sql = "
	insert into new_tb_point set
		signdate = '".time()."',
		depth = '".$clover_name_v[$nClovermlist->clover_seq]." 일시 후원',
		outpoint = '".$nClovermlist->price."',
		userid = '" . Auth::user()->user_id . "'
	";
	mysql_query($sql);



$Conn->disConnect();
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
					<th scope="col" class="last">후원포인트</th>
				</tr>
				<tr >
					<td>일시후원</td>
					<td>{{ $nClovermlist->clover_name }}</td>
					<td class="xm_tright mr10 last">{{ number_format($nClovermlist->price) }} 원</td>
				</tr>
			</table>
		</div>
		<div class="xm_clr"></div>

		<h2 class="xm_left">후원금 납입방법</h2>
		<div class="xm_left mt20 ml10 c_light_gray_3 fs11"><!-- 회비 출금일은 자동이체의 경우 매월 1일이며, 신용카드/휴대폰은 본인의 결제일입니다. (은행, 카드사, 통신사 영업일 기준) --></div>
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
						<div class="mr20">포인트</div>
					</div>	
				</td>
			</tr>
			<tr >
				<th scope="row" class="first xm_tleft pl30">납입자</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">{{ $nClovermlist->name }}</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="first xm_tleft pl30">구분</th>
				<td colspan="3">
					<div class="xm_left radioForm h200">
						<div class="mr20">
						<?php
							$userHelper = new UserHelper();
							$state = $userHelper->state;
						?>
						@if(!is_null($state))
							{{ $state }}
						@else
							비회원
						@endif
						</div>
					</div>	
				</td>
			</tr>
			
		</table>
		
		</form>
	</article>
</section>
@stop