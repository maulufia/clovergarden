@extends('front.page.mypage')

@section('mypage')
	<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nClover = new CloverClass(); //
	$nMember = new MemberClass();     //회원
	$nClovermlist = new ClovermlistClass(); //
	//======================== DB Module Clovert ============================
	$Conn = new DBClass();
	$nClover->page_view = 10000;

	$nClover->total_record = $Conn->PageListCount
	(
	$nClover->table_name, $nClover->where, $search_key, $search_val
);


$nClover->page_result = $Conn->PageList
(
$nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->page_view, null
);

if(count($nClover->page_result) != 0){
	$nClover->VarList($nClover->page_result, 0, null);
}


$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, '*', "where user_id ='" . Auth::user()->user_id . "'", null, null);
if(count($nMember->read_result) != 0){
	$nMember->VarList($nMember->read_result);
}else{
	$Conn->DisConnect();
	JsAlert(ERR_LOGIN, 1, $list_link);
}

$nClovermlist->page_result = $Conn->AllList
(
$nClovermlist->table_name, $nClovermlist, "*", "where id='" . Auth::user()->user_id . "' and order_adm_ck='y' and start != '' group by clover_seq order by reg_date desc limit 10000", null, null
);

$Conn->DisConnect();
//======================== DB Module End ===============================
$search_val = ReXssChk($search_val);
?>


<section class="wrap">
	<header>
		<h2 class="ti">결제정보수정</h2>
	</header>

	<article class="join mt50">
		<h2 class="ti">결제정보수정</h2>
		<p class="c_orange">
			<font color="000000">정기후원의 후원대상기관을 자유롭게 변경할 수 있습니다.</font><BR>
				* 25일 이후의 변경신청은 익월부터 적용됩니다.
			</p>
			<form method="get" name="editForm" id="editForm" action="{{ route('mypage', array('cate' => 6, 'dep01' => 4, 'dep02' => 0, 'type' => 'edit')) }}">
				<input type="hidden" name="cate" value="{{ $_GET['cate'] }}">
				<input type="hidden" name="dep01" value="{{ $_GET['dep01'] }}">
				<input type="hidden" name="dep02" value="{{ isset($_GET['dep02']) ? $_GET['dep02'] : 0 }}">

				<div class="join_wrap">
					<table>
						<caption>결제정보수정</caption>
						<colgroup>
							<col>
							<col>
						</colgroup>
						<tr>
							<th scope="row" style="height:40px; vertical-align:top">{{ Auth::user()->user_name }} 님의 현재 후원내역</th>
						</tr>
						<tr>
							<td valign=top align="center">
								<br>
								<table cellpadding=0 cellspacing=0 border=0 align=center>
									<?php
									$ex_date_or_type = explode("[@@]",$nMember->clover_seq_adm_type);
									$cloverModel = new CloverModel();
									$clover_name_v = $cloverModel->getCloverList();

									if(count($ex_date_or_type) > 1){ // 후원변경 신청. 관리자의 승인이 된 경우
										if($ex_date_or_type[1] == 'ok')
										$ex_seq_clover = explode("[@@@]",$nMember->clover_seq);
									} else { // 후원변경 신청. 관리자의 승인이 안된 경우
										$ex_clover_seq_adm = explode("[@@@@]",$nMember->clover_seq_adm);
										$ex_seq_clover = explode("[@@@]",$ex_clover_seq_adm[0]);
									}
									?>

									<?php
									$ex_date_or_type_result = isset($ex_date_or_type[1]) ? $ex_date_or_type[1] : null;
									if($nMember->clover_seq != '' && !empty($ex_seq_clover[0])) { // 후원변경 신청을 한 경우 (승인 결과는 무관). $ex_seq_clover[0]가 비어있는 지 살펴보는 이유는 첫 후원변경 시 값이 들어오지 않기 때문. 디자인 에러임. 멋져!
										$sum_price_v = 0;
										for($j=0; $j<count($ex_seq_clover); $j++){
											$v_ex = explode("[@@]",$ex_seq_clover[$j]);
											?>
											<tr height=50>
												<?php if($v_ex[1] < 1){ ?>
													<td align="center" colspan=2 class="fa-minus-square">후원내역이 없습니다.</td>
													<?php } else { ?>
														<td width="300" align="right" style="color:#000;font-size:20px;font-weight:bold;">
															{{ $clover_name_v[$v_ex[0]] }}
														</td>
														<td align="left" class="fa-minus-square">{{ number_format($v_ex[1]) }}원<input type="hidden" name="select_money_1" value="{{ $v_ex[1] }}" style="width:100px; height:40px; margin-top:5px;" align="middle" ></td>
														<?php } ?>
													</tr>

													<?php
													$sum_price_v += $v_ex[1];
												}?>

												<?php } else { // 후원변경 신청을 처음에 한 경우 혹은 변경신청을 처음에 안했던 경우 ?>

													<?php
													$price_sum = 0;
													if(count($nClovermlist->page_result) > 0){
														for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
															$nClovermlist->VarList($nClovermlist->page_result, $i, null);
															?>
															<tr>
																<td width="150" style="width:150px; padding:10px;font-size:15px;color:#000;font-weight:bold;">
																	{{ $clover_name_v[$nClovermlist->clover_seq] }}
																</td>
																<td align="left" class="fa-minus-square">{{ number_format($nClovermlist->price) }}원</td>
															</tr>
															<?php
															$price_sum += $nClovermlist->price;
															$clover_sql_v[$i] = $nClovermlist->clover_seq;
														}
													} else {?>
														<td align="left" colspan=2 class="fa-minus-square">후원내역이 없습니다.</td>
														<?php
													}
													$sum_price_v = $price_sum;
													?>
													<?php } ?>



												</table>

											</td>
										</tr>

									</table>
								</div>
							</form>
							<br><br>

							<form method="POST" name="editForm2" id="editForm2" action="{{ route('mypage', array('cate' => 6, 'dep01' => 4, 'dep02' => 0, 'type' => 'edit')) }}">
								<input type="hidden" name="cate" value="{{ $_GET['cate'] }}">
								<input type="hidden" name="dep01" value="{{ $_GET['dep01'] }}">
								<input type="hidden" name="dep02" value="{{ isset($_GET['dep02']) ? $_GET['dep02'] : 0 }}">
								<input type="hidden" name="sum_price_ck" value="{{ $sum_price_v }}">
								<input type="hidden" name="clover_sql_v" value="{{ isset($clover_sql_v) ? $clover_sql_v[0] : '' }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="join_wrap">
									<table>
										<caption>결제정보수정</caption>
										<colgroup>
											<col>
											<col>
										</colgroup>
										<tr>
											<th scope="row" style="height:70px; vertical-align:top">후원대상 변경요청</th>
											<th scope="row" style="height:70px;text-align:right; vertical-align:top"></th>
										</tr>
										<tr>
											<td valign=top colspan=2>
												<table cellpadding=0 cellspacing=0 border=0 align=center id="drop_table">
													<tr style="display:none;" id="add_drop">
														<td width="150">
															<select name="cseq2[]" id="selectGroup" style="width:150px; border:1px solid #dcdcdc;padding:10px;font-size:15px;">
																<?php
																if(count($nClover->page_result) > 0){
																	$row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
																	for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
																		$nClover->VarList($nClover->page_result, $i,  array('comment'));

																		?>

																		<option value="{{ $nClover->code }}" <?php if($nMember->clover_seq == $nClover->code){ ?>selected<?php }?>>{{ $nClover->subject }}</option>
																		<?php
																		$row_no = $row_no - 1;
																	}
																}
																?>
															</select>
														</td>
														<td align="left" class="fa-minus-square"><input type="text" name="select_money[]" value="" style="width:100px; height:40px; margin-top:5px;" align="middle" > 원</td>
													</tr>

													<tr>
														<td rowspan="50" style="font-size:15px; color:#000; font-weight:bold;">
															후원대상 변경요청
															<a href="javascript:plus_add();" style="padding:1px 5px 1px 5px;background:#e8e8e8;font-size:20px;">+</a>
															<a href="javascript:plus_del();" style="padding:1px 8px 1px 8px;background:#e8e8e8;font-size:20px;">-</a>
														</td>
														<td width="150">
															<select name="cseq2[]" id="selectGroup" style="width:150px; border:1px solid #dcdcdc;padding:10px;font-size:15px;">
																<?php
																if(count($nClover->page_result) > 0){
																	$row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
																	for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
																		$nClover->VarList($nClover->page_result, $i,  array('comment'));

																		?>

																		<option value="{{ $nClover->code }}" <?php if($nMember->clover_seq == $nClover->code){ ?>selected<?php } ?>>{{ $nClover->subject }}</option>
																		<?php
																		$row_no = $row_no - 1;
																	}
																}
																?>
															</select>
														</td>
														<td align="left" class="fa-minus-square"><input type="text" name="select_money[]" value="" style="width:100px; height:40px; margin-top:5px;" align="middle" > 원</td>
													</tr>
												</table>
												<script type="text/javascript">
												<!--
												function plus_add()
												{
													var _tmp_html= $('#add_drop').html();
													$('#drop_table').append('<tr>'+_tmp_html+'</tr>');
												}

												function plus_del()
												{
													if($("#drop_table tr").length < 3){ return ; }
													$('#drop_table tr:last').remove();
												}

												//-->
												</script>
											</td>
										</tr>

									</table>
								</div>
								<div class="box2"><a href="javascript:submitChangeClover();" class="orange_big_btn">저장</a> <a href="#" class="ml10 gray_big_btn2">취소</a></div>
							</form>

							<div class="xm_right mt10 nanum fs16 c_orange" style='text-align:right;'>
								* 총 후원금액과 동일한 금액을 입력해주셔야 변경이 가능합니다.<BR>
									* 후원금액의 단위는 천원으로, 후원금액의 변경은 유선 (02-720-3235)로 문의 부탁드립니다.
								</div>
							</article>
						</section>
					</div>
				</div>
				<div class="xm_clr"></div>

				<script type="text/javascript">
				function submitChangeClover() {
					var c = confirm("정말로 후원기관 변경을 하시겠습니까?");
					if (c) {
						document.editForm2.submit();
					} else {
						return;
					}
				}
				</script>
			@stop
