@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;
	$_GET['select_month'] = isset($_GET['select_month']) ? $_GET['select_month'] : '';
	$_GET['select_year'] = isset($_GET['select_year']) ? $_GET['select_year'] : '';
	$_GET['gname'] = isset($_GET['gname']) ? $_GET['gname'] : '';

	$nClover = new CloverClass(); //클로버목록
	$nClovermlist   = new ClovermlistClass(); //성형갤러리
	$nClovermlist_nomal   = new ClovermlistClass(); //성형갤러리
	$nClovernews   = new ClovernewsClass(); //성형갤러리
	$nMember = new MemberClass();
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$Conn->UpdateDBQuery($nClover->table_name, "hit = hit + 1 where seq = '".$seq."'");
		$nClover->where = "where seq ='".$seq."'";

		$nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", $nClover->where, null, null);
		if(count($nClover->read_result) != 0){
			$nClover->VarList($nClover->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}
		if($_GET['select_month'] != "" && $_GET['select_year'] == ""){
			if($_GET['select_month'] < 10){
				$_GET['select_month'] = "0".$_GET['select_month'];
			}
			$view_search_date = date('Y').$_GET['select_month'];
		} else if ($_GET['select_year'] != "" && $_GET['select_month'] == "") {
			$view_search_date = $_GET['select_year'].date('m');
		} else if ($_GET['select_month'] != "" && $_GET['select_year'] != "") {
			if($_GET['select_month'] < 10){
				$_GET['select_month'] = "0".$_GET['select_month'];
			}
			$view_search_date = $_GET['select_year'].$_GET['select_month'];
		} else {
			$view_search_date = date('Ym',time()-(86400*31));
		}
		$nClovermlist->where = "where clover_seq='".$nClover->code."' and start like '%".$view_search_date."%'";

		$nClovermlist->total_record = $Conn->PageListCount
		(
			$nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
		);
		$group_query = '';
		if($_GET['gname'] != ''){
			$group_query = "and group_name = '".$_GET['gname']."'";
		}
		$nClovermlist->page_result = $Conn->AllList
		(	
			$nClovermlist->table_name, $nClovermlist, "*", "where clover_seq='".$nClover->code."' and group_name != '' and start like '%".$view_search_date."%' $group_query group by id order by seq desc limit ".$nClovermlist->total_record."", null, null
		);

		$nClovermlist->total_price = $Conn->SelectColumn
		(	
			$nClovermlist->table_name,"sum(price)", "where clover_seq='".$seq."' order by seq desc limit ".$nClovermlist->total_record."", null, null
		);


		$nClovermlist_nomal->where = "where clover_seq='".$nClover->code."' and group_name='' and start like '%".$view_search_date."%'";
		$nClovermlist_nomal->total_record = $Conn->PageListCount
		(
			$nClovermlist_nomal->table_name, $nClovermlist_nomal->where, $search_key, $search_val
		);
		
		$nClovermlist_nomal->page_result = $Conn->AllList
		(	
			$nClovermlist_nomal->table_name, $nClovermlist_nomal, "*", "where clover_seq='".$nClover->code."' and group_name='' order by seq desc limit ".$nClovermlist_nomal->total_record."", null, null
		);

		$nClovermlist_nomal->total_price = $Conn->SelectColumn
		(	
			$nClovermlist_nomal->table_name,"sum(price)", "where clover_seq='".$seq."' order by seq desc limit ".$nClovermlist_nomal->total_record."", null, null
		);



		$nClovernews->where = " where clover_seq ='".$nClover->code."'";
		$nClovernews->total_record = $Conn->PageListCount
		(
			$nClovernews->table_name, $nClovernews->where, $search_key, $search_val
		);

		$nClovernews->page_result = $Conn->AllList
		(	
			$nClovernews->table_name, $nClovernews, "*", "where clover_seq='".$nClover->code."' order by seq desc limit ".$nClovernews->total_record."", null, null
		);

	$Conn->DisConnect();
	//======================== DB Module End ===============================

?>


<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nClovercomment = new ClovercommentClass(); //클로버응원댓글
	$nClovermlist_group = new ClovermlistClass(); //
//======================== DB Module Clovercommentt ============================
	$Conn = new DBClass();

	$nClovercomment->where = " where clover_seq ='".$seq."'";
	$nClovercomment->total_record = $Conn->PageListCount
	(
		$nClovercomment->table_name, $nClovercomment->where, $search_key, $search_val
	);

	$nClovercomment->page_result = $Conn->PageList
	(	
		$nClovercomment->table_name, $nClovercomment, $nClovercomment->where, $search_key, $search_val, 'order by reg_date desc', $nClovercomment->sub_sql, $page_no, $nClovercomment->page_view, null
	);

	if(count($nClovercomment->page_result) != 0){
		$nClovercomment->VarList($nClovercomment->page_result, 0, null);
	}


	$nClovermlist_group->read_result = $Conn->AllList($nClovermlist_group->table_name, $nClovermlist_group, "sum(price) price", "where clover_seq ='".$nClover->code."'", $nClovermlist_group->sub_sql, null);

	if(count($nClovermlist_group->read_result) != 0){
		$nClovermlist_group->VarList($nClovermlist_group->read_result, 0, array('comment'));
	}

	$total_price = $nClovermlist_group->price;

	
	$nClovermlist_group->read_result = $Conn->AllList($nClovermlist_group->table_name, $nClovermlist_group, "sum(price) price", "where clover_seq ='".$nClover->code."' and start like '%".date("Ym")."%'", $nClovermlist_group->sub_sql, null);

	if(count($nClovermlist_group->read_result) != 0){
		$nClovermlist_group->VarList($nClovermlist_group->read_result, 0, array('comment'));
	}
	$month_price = $nClovermlist_group->price;


	$nClovermlist_group->page_result = $Conn->AllList
    (
        $nClovermlist_group->table_name, $nClovermlist_group, "*",
        "where clover_seq ='".$nClover->code."' and start like '%".$view_search_date."%' group by group_name order by seq asc", null, null
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap">
	<header>
		<h2 class="ti">클로버가든 목록</h2>
	</header>
	<article class="brd_tab_list tab195">
		<h2>후원기관</h2>
		
		<!-- showcase -->
		<!-- <div id="showcase" class="showcase">
			<div class="showcase-slide">
				<div class="showcase-content"> -->
					<a href="#">				
						<img src='/imgs/up_file/clover/{{ $nClover->file_edit[2] }}' border='0' width='100%' />
					</a>
				<!-- </div>
			</div>
			
		</div> -->
		<!-- //showcase -->
		<div class="xm_right right_mini_box">
			<div class="mini_box">
				<div class="mb10">
					<h3>{{ date("Y년m월") }}</h3>
				</div>

				<div>
					<h3 class="mt5">{{ date("m월") }} {{ number_format($month_price) }}원</h3>
					<span>total {{ number_format($total_price) }}원</span>
				</div>

				<a href="{{ $writeresv_link }}&seq={{ $nClover->seq }}" class="mt10 green_big_btn resize_91_70">정기<br>후원</a>
				<a href="{{ $write_link }}&seq={{ $nClover->seq }}" class="mt5 orange_big_btn resize_91_70">일시<br>후원</a>
			</div>
		</div>

		
		<div class="xm_clr"></div>

		<div id="tabs" class="tab">
			<ul>
				<li class="menu tabs-1"><a href="#tabs-1">기관소개</a></li>
				<li class="menu tabs-2"><a href="#tabs-2">후원자</a></li>
				<li class="menu tabs-3"><a href="#tabs-3">소식지</a></li>
				<li class="menu tabs-4 last"><a href="#tabs-4">응원댓글</a></li>
			</ul>

			<div id="tabs-1" class="tabCont">
			
				<section class="pt30 cont">
					<h2 class="ti">페이스북 참여</h2>
					<div class="facebook_box">
						<aside class="fb">
							<a href="#" class="img mt_3"><img src="/imgs/Clover.png" alt="" /></a>
							<p class="nanum fs14 c_dark_blue t_bold  mt_3">클로버가든을 페이스북으로 만나보세요</p>
						</aside>

						<aside class="fb">
							<!-- 페이스북 좋아요! -->
							<div id="fb-root"></div>
							<div class="fb-like xm_left" data-href="http://ucon.kr/clover/main.html" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div> <!-- data-href 정식 URL로 변경해야함, 관련 스크립트는 페이지 하단 확인 -->
						</aside>

						<aside class="fb">
							<a href="#" class="img mt_3 xm_left" style="position:absolute"><img src="/imgs/FacebookPhoto2.png" alt="" /></a>
						</aside>
					</div>
				</section>
				<div class="xm_clr"></div>

				<div class="mt30 h200">
					{!! $nClover->content !!}
				</div>
				<div class="mt30 h200" style="text-align:center;">
				

				<input type="button" style="width:130px; height:50px; background:#92c154; font-weight:bold; font-size:15px;color:#fff;border:none;" value="정기후원" onclick="window.location='{{ $writeresv_link }}&seq={{ $nClover->seq }}';">
				&nbsp;&nbsp;&nbsp;
				<input type="button" style="width:130px; height:50px; background:#fc5101; font-weight:bold; font-size:15px;color:#fff;border:none;" value="일시후원" onclick="window.location='{{ $write_link }}&seq={{ $nClover->seq }}';">
				</div>
			</div>

	<script type="text/javascript" src="/js/jquery.imageScroller.js"></script>
	<style type="text/css">
	.prtner_scroll2 {overflow:hidden;margin:0 auto; margin-top:30px;}
	.scroll2 {float:left;}

	div#scroller_2 {position:relative;height:150px; width:788px; margin:0; clear:both; overflow:hidden;}

	/*좌우버튼*/
	div#scroller_2 .title2 { height:100px; position:absolute; top:0px; left:0px; }
	#btn12, #btn22 {cursor:pointer}
	#btn12 { position:absolute; top:55px; left:0px; }
	#btn22 { position:absolute; top:55px; left:765px; z-index:10; }

	ul#scrollerFrame2 { position:absolute; top:0px; left:35px; width:785px; padding:0;margin:0;list-style:none; overflow:hidden; }
	ul#scrollerFrame2 li {position:relative; float:left; height:215px; margin-right:10px; text-align:left; }

	/*설명 부분*/
	ul#scrollerFrame2 li p {margin:0;padding:0}
	ul#scrollerFrame2 li p.price{font-family:verdana;font-size:12px;font-weight:bold;margin-top:7px;text-align:center;color:#0a62cf}
	ul#scrollerFrame2 li .banner3 img { width: 51px; height: 51px; }
	
	.prtner_scroll1 {overflow:hidden;margin:0 auto; margin-top:5px;}
	.scroll1 {float:left;}

	div#scroller_1 {position:relative;height:70px; width:788px; margin:0; clear:both; overflow:hidden;}

	/*좌우버튼*/
	div#scroller_1 .title2 { height:100px; position:absolute; top:0px; left:0px; }


	div#scroller_1 {position:relative;height:70px; width:788px; margin:0; clear:both; overflow:hidden;}

	/*좌우버튼*/
	div#scroller_1 .title2 { height:100px; position:absolute; top:0px; left:0px; }
	#btn11, #btn21 {cursor:pointer}
	#btn11 { position:absolute; top:25px; left:0px; }
	#btn21 { position:absolute; top:25px; left:765px; z-index:10; }

	ul#scrollerFrame1 { position:absolute; top:0px; left:35px; width:785px; padding:0;margin:0;list-style:none; overflow:hidden; }
	ul#scrollerFrame1 li {position:relative; float:left; height:215px; margin-right:10px; text-align:left; }

	/*설명 부분*/
	ul#scrollerFrame1 li p {margin:0;padding:0}
	ul#scrollerFrame1 li p.price{font-family:verdana;font-size:12px;font-weight:bold;margin-top:7px;text-align:center;color:#0a62cf}
	ul#scrollerFrame1 li img { }	


	.prtner_scroll3 {overflow:hidden;margin:0 auto; margin-top:0px;}
	.scroll3 {float:left;}

	div#scroller_3 {position:relative;height:150px; width:788px; margin:0; clear:both; overflow:hidden;}

	/*좌우버튼*/
	div#scroller_3 .title2 { height:100px; position:absolute; top:0px; left:0px; }
	#btn13, #btn23 {cursor:pointer}
	#btn13 { position:absolute; top:55px; left:0px; }
	#btn23 { position:absolute; top:55px; left:765px; z-index:10; }

	ul#scrollerFrame3 { position:absolute; top:0px; left:35px; width:785px; padding:0;margin:0;list-style:none; overflow:hidden; }
	ul#scrollerFrame3 li {position:relative; float:left; height:215px; margin-right:10px; text-align:left; }

	/*설명 부분*/
	ul#scrollerFrame3 li p {margin:0;padding:0}
	ul#scrollerFrame3 li p.price{font-family:verdana;font-size:12px;font-weight:bold;margin-top:7px;text-align:center;color:#0a62cf}
	ul#scrollerFrame3 li img { }		

	</style>

	<script type="text/javascript">
	$(function(){		     
		$("#scroller_1").imageScroller({
			next:"btn11",                   //다음 버튼 ID값
			prev:"btn21",                   //이전 버튼 ID값
			frame:"scrollerFrame1",         //스크롤러 프레임 ID값  
			width:100,                     //이미지 가로 크기
			child:"li",                    //스크롤 지정 태그
			auto:false                      //오토 롤링 (해제는 false)
		});
	});
	</script>

			<div id="tabs-2" class="pt30 tabCont">
				<h3 class="banner_title2">
				<ul style="height:40px;color:#333333;">
					<li style="float:left;">
						<select name="select_year" style="padding:10px; font-size:13px;border:1px solid #a9a9a9;" onchange="window.location='{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $_GET["seq"] }}&gname={{ $_GET["gname"] }}&select_year='+this.value+'&select_month={{ $_GET["select_month"] }}';">
						<?php
						$y_before = date('Y')-5;
						for($y = $y_before; $y <= date('Y'); $y++){
						?>
						
							<?php if($_GET['select_year'] != ""){ ?>
								<option value="{{ $y }}" <?php if($_GET['select_year'] == $y){echo "selected";} ?>>{{ $y }}년
							<?php } else { ?>
								<option value="{{ $y }}" <?php if(date('Y') == $y){echo "selected";} ?>>{{ $y }}년
							<?php } ?>
						
						<?php } ?>
						</select>					
					</li>
					<?php for($m=1; $m<13; $m++){
						if($_GET['select_month'] != ""){
							if($_GET['select_month'] == $m) $db_color = 'eeeded';
							else $db_color = 'fff';
						} else {
							if(date('m',time()-(86400*31)) == $m) $db_color = 'eeeded';
							else $db_color = 'fff';
						}					
					?>
						<li style="background:#{{ $db_color }};cursor:pointer;border:1px solid #a9a9a9;margin-left:5px;float:left;width:48px;height:28px;padding-top:11px;text-align:center; font-size:13px;" onclick="window.location='{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $_GET["seq"] }}&gname={{ $_GET["gname"] }}&select_year={{ $_GET["select_year"] }}&select_month={{ $m }}';">{{ $m }}월</li>
					<?php } ?>
				</ul>
				</h3>
				<h3 class="banner_title">단체</h3>
				

				<div class="prtner_scroll1">
					<div class="scroll1">
						<div id="scroller_1">
							<div class="title2">
								<span id="btn11" style="float:left;"><img src="/imgs/LeftSideSymbol.png"></span>
								<span id="btn21" style="float:right;"><img src="/imgs/RightSideSymbol.png"></span>
							</div>

							<ul id="scrollerFrame1">
						<?php
							// 자기가 속한 그룹을 앞으로 빼내기
							$my_group = Auth::check() ? Auth::user()->group_name : null;
							foreach($nClovermlist_group->page_result as $key => $pr) {
								if($pr->group_name == $my_group) {
									$my_group_splice = array_splice($nClovermlist_group->page_result, $key, 1);
									array_unshift($nClovermlist_group->page_result, $my_group_splice[0]);
								}
							}			
						
							if(count($nClovermlist_group->page_result) > 0){
								$row_no = $nClovermlist_group->total_record - ($nClovermlist_group->page_view * ($page_no - 1));
								for($i=0, $cnt_list=count($nClovermlist_group->page_result); $i < $cnt_list; $i++) {
									$nClovermlist_group->VarList($nClovermlist_group->page_result, $i,  array('comment'));
									if($nClovermlist_group->group_name){

										if($_GET['select_month'] != ''){
											$_GET['select_month'] = (int)$_GET['select_month'];
										} else {
											$_GET['select_month'] = '';
										}
						?>
								<li>
									<div class="banner_wrap">
										<div class="banner5" <?php if($_GET['gname'] == $nClovermlist_group->group_name){?>style='background:#f7f7f7;'<?php } ?>>
											<br>
											<a href="{{ route('clovergarden') }}?cate={{ $_GET['cate'] }}&dep01={{ $_GET['dep01'] }}&dep02={{ $_GET['dep02'] }}&type=view&seq={{ $_GET['seq'] }}&gname={{ $nClovermlist_group->group_name }}&select_year={{ $_GET['select_year'] }}&select_month={{ $_GET['select_month'] }}">{{ $nClovermlist_group->group_name }}</a>
										</div>				
									</div>	
								</li>
								<?php
											$row_no = $row_no - 1;
											}
										}
									}else{
								?>
										{{ NO_DATA }}
								<?php
									}
								?>
							</ul>
						</div>
					</div>
				</div>


				<div class="xm_clr"></div>

<?php if($_GET['gname'] == ''){ ?>


<?php } else { ?>
			<h3 class="banner_title">후원</h3>
							<ul id="scrollerFrame2" style='position:relative;'>


					<?php
						if(count($nClovermlist->page_result) > 0){
							for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
								$nClovermlist->VarList($nClovermlist->page_result, $i, null);
								$image = explode('@',$nClovermlist->id);

								$Conn = new DBClass();


									$nMember->where = "where user_id = '".$nClovermlist->id."'";


									$nMember->read_result = $Conn->AllList
									(
										$nMember->table_name, $nMember, "*", $nMember->where, null, null
									);

									if(count($nMember->read_result) != 0){
										$nMember->VarList($nMember->read_result, 0, null);
									}else{
										$Conn->DisConnect();
										//JsAlert(NO_DATA, 1, $list_link);
									}
								$Conn->DisConnect();

					?>
									<li style='height:150px;'>

					<div class="banner_wrap">

						<div class="banner3"><a href="{{ route('userinfo') }}?cate=8&user_id={{ $nClovermlist->id }}">
				
						<?php if(count($nMember->read_result) == 0){ ?><img src="/imgs/photo05.png" style="height:51px; width:51px;"><?php } else { ?><img src="/imgs/up_file/member/{{ $nMember->file_edit[1] }}" onerror="this.src='/imgs/photo05.png'" style="height:51px; width:51px;"><?php } ?>
						
						</a></div>
						<div class="banner4">
						{{ $nClovermlist->group_name }}<br>
						<?php if($nClovermlist->day) $img_v = "clover_ing"; else $img_v = "clover_set"; ?>
						<img src="/imgs/up_file/member/{{ $img_v }}.jpg" />
						<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nClovermlist->id }}">{{ $nClovermlist->name }}님</a></div>
						
					</div>
						
									</li>

					<?php
							
							}
						}else{
					?>
						{{ NO_DATA }}
					<?php
						}
					?>
							</ul>
<?php } ?>

				<div class="xm_clr"></div>

				<h3 class="banner_title">개인</h3>


							<ul id="scrollerFrame3" style="position:relative;">


					<?php

						if(count($nClovermlist_nomal->page_result) > 0){
							for($i=0, $cnt_list=count($nClovermlist_nomal->page_result); $i < $cnt_list; $i++) {
								$nClovermlist_nomal->VarList($nClovermlist_nomal->page_result, $i, null);
								$image = explode('@',$nClovermlist_nomal->id);

								$Conn = new DBClass();


									$nMember->where = "where user_id = '".$nClovermlist_nomal->id."'";


									$nMember->read_result = $Conn->AllList
									(
										$nMember->table_name, $nMember, "*", $nMember->where, null, null
									);

									if(count($nMember->read_result) != 0){
										$nMember->VarList($nMember->read_result, 0, null);
									}else{
										$Conn->DisConnect();
										//JsAlert(NO_DATA, 1, $list_link);
									}
								$Conn->DisConnect();

					?>
									<li style='height:150px;'>

					<div class="banner_wrap">

						<div class="banner3"><a href="{{ route('userinfo') }}?cate=8&user_id={{ $nClovermlist_nomal->id }}">

						<?php if(count($nMember->read_result) == 0){ ?><img src="/imgs/photo05.png" style="height:51px; width:51px;"><?php } else { ?><img src="/imgs/up_file/member/{{ $nMember->file_edit[1] }}" onerror="this.src='/imgs/photo05.png'" style="height:51px; width:51px;"><?php } ?>
						
						</a></div>
						<div class="banner4">
						{{ $nClovermlist_nomal->group_name }}<br>
						<?php if($nClovermlist_nomal->day) $img_v = "clover_ing"; else $img_v = "clover_set"; ?>
						<img src="/imgs/{{ $img_v }}.jpg" />
						<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nClovermlist_nomal->id }}">{{ $nClovermlist_nomal->name }}님</a></div>
						
					</div>
						
									</li>

					<?php
							
							}
						}else{
					?>
						{{ NO_DATA }}
					<?php
						}
					?>
							</ul>
</div>
			<div id="tabs-3" class="tabCont">

				<?php
					if(count($nClovernews->page_result) > 0){
						for($i=0, $cnt_list=count($nClovernews->page_result); $i < $cnt_list; $i++) {
							$nClovernews->VarList($nClovernews->page_result, $i,  array('comment'));

				?>
				<div class="box5-wrapper">
					<div class="box5">
						<div class="img">
							<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank">
							<img src='/imgs/up_file/clover/{{ $nClovernews->file_edit[1] }}' border='0' width='100%'>
						</a>
						</div>
						<div class="title">
							<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank"><img src="/imgs/pdf.jpg"></a>
							<?php if($nClovernews->category==1){ ?><img src="/imgs/dot1.jpg"><?php } else { ?><img src="/imgs/dot2.jpg"><?php } ?>
						@if(!empty($nClovernews->file_edit[2]))
							<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank" style="display: block;">
						@endif	
							{{ $nClovernews->subject }}
							</a>
						</div>
					</div>
				</div>
				
				<?php
						}
					} else {
				?>
						<div style="height:200px; line-height:200px; text-align:center;">
							{{ NO_DATA }}
						</div>
				<?php
					}
				?>
			</div>

			<div id="tabs-4" class="tabCont">

				<div class="pt30 pb10 comment_box">
					<form method="post" id="wrtForm" action="{{ $list_link }}&type=comment_write" style="display:inline;"  enctype="multipart/form-data">
						{{ UserHelper::SubmitHidden() }}
						<input type="hidden" name="list_link" value="{{ route('clovergarden') }}?cate={{ $_GET['cate'] }}&dep01={{ $_GET['dep01'] }}&dep02={{ $_GET['dep02'] }}&type=view&seq={{ $_GET['seq'] }}#tabs-4">
						<input type="hidden" name="clover_seq" value="{{ $seq }}">
						<input type="text" name="subject" value="" style="width:620px; height:30px">
						<?php if(Auth::check()){ ?>
						<a href="javascript:document.getElementById('wrtForm').submit();" id="comment" class="green_btn" style="width:100px; height:34px; line-height:34px">응원댓글쓰기</a>
						<?php } else { ?>
						<a href="javascript:alert('로그인 후 이용해주세요');" id="comment" class="green_btn" style="width:100px; height:34px; line-height:34px">응원댓글쓰기</a>
						<?php } ?>
					</form>
				</div>

				<table>
					<caption>게시판 목록</caption>
					<colgroup>
						<col class="colWidth180">
						<col class="colWidth458">
						<col class="colWidth105">
					</colgroup>
					<?php
						if(count($nClovercomment->page_result) > 0){
							$row_no = $nClovercomment->total_record - ($nClovercomment->page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nClovercomment->page_result); $i < $cnt_list; $i++) {
								$nClovercomment->VarList($nClovercomment->page_result, $i,  array('comment'));
								$board_name = explode(',',$nClovercomment->writer);
								$board_image = explode('@',$board_name[1]);
								$ex_writer = explode(",",$nClovercomment->writer);
					?>
					<tr>
						<th scope="row">
							<a href="{{ route('userinfo') }}?cate=8&user_id={{ $ex_writer[1] }}" class="mr10 xm_left" style="border-radius:50%; height:51px; width:51px; border:1px solid #dbdbdb; overflow:hidden;  display:inline-block;">
							<img src="/imgs/up_file/member/{{ $board_image[0] }}.jpg" onerror="this.src='/imgs/photo05.png'" class="xm_left mr10" style="width: 51px; height: 51px;">
							</a>
							<div class="name">
								<a href="{{ route('userinfo') }}?cate=8&user_id={{ $ex_writer[1] }}">
									@if(!empty($nClovercomment->group_name))
									{{ $nClovercomment->group_name }}
									@else
										개인회원
									@endif
										<br>{{ $board_name[0] }}님
								</a>
							</div>
						</th>
						<td class="subject">{{ $nClovercomment->subject }}</td>
						<td class="date">{{ date('Y-m-d',strtotime($nClovercomment->reg_date)) }}</td>
					</tr>


					<?php
								$row_no = $row_no - 1;
							}
						} else {
					?>
							<tr>
								<td colspan="4" style="height:200px; text-align:center;">{{ NO_DATA }}</td>
							</tr>
					<?php
						}
					?>
				</table>

				<div class="paging">
				<?php
					if($nClovercomment->total_record != 0){
						$nPage = new PageOut();
						$nPage->CustomPageList($nClovercomment->total_record, $page_no, $nClovercomment->page_view, $nClovercomment->page_set, $nClovercomment->page_where, 'pageNumber','');
					}
				?>
				</div>
				<form name="form_submit" method="post" action="{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $nClovercomment->seq }}#tabs-4" style="display:inline">
					{{ UserHelper::SubmitHidden() }}
				</form>
			</div>
		</div>
	</article>
</section>
<div class="div_popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1000;">

</div>
<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

		var hash = window.location.hash.substring(1);
		if (hash=='')
		{
			hash = "tabs-1";
		}
        //tabs
		<?php
		if($_GET['select_month'] != ""){
			echo 'hash = "tabs-2";';
		}
		if($_GET['select_year'] != ""){
			echo 'hash = "tabs-2";';
		}
		if($_GET['gname'] != ""){
			echo 'hash = "tabs-2";';
		}
		?>
        $( "#tabs" ).tabs();

        //tab4보기
        $( ".tabCont" ).hide();
        $( "#"+hash ).show();

		$('#tabs .menu').click(function(){
			$('#tabs .menu').removeClass("on");
			$(this).addClass('on');
		});
		
		$('#tabs .menu.'+hash).addClass('on');
		// 응원댓글쓰기
        $( "#comment" ).click(function() {

            if($('input[name=keyword]').val() == ''){
                alert('응원댓글을 입력하세요.');
                $('input[name=keyword]').focus();
                return false;
            }

            $( "#commentForm" ).submit();

        });
    });
})(jQuery);
</script>

<script type="text/javascript" src="/js/jquery.aw-showcase.js"></script>
<script type="text/javascript">
 
$(document).ready(function()
{
	$("#showcase").awShowcase(
	{
		content_width:			789,
		content_height:			223,
		auto:					true,
		interval:				3000,
		loading:				true,
		arrows:					false,
		buttons:				true,
		stoponclick:			false,
		transition_delay:		0,
		transition_speed:		1000,		
	});
});


$(document).ready(function()
{
	$("#showcase").awShowcase(
	{
		content_width:			789,
		content_height:			223,
		auto:					true,
		interval:				3000,
		loading:				true,
		arrows:					false,
		buttons:				false,
		stoponclick:			false,
		transition_delay:		0,
		transition_speed:		1000,		
	});
});

function news_popup(id){
	$('.div_popup').empty();
	$('.div_popup').show();
	$('.div_popup').load('/page/clover/page_1_0_view.php?seq='+id);
}

function news_popup_close(){
	$('.div_popup').empty();
	$('.div_popup').hide();
}

</script>

<!-- 페이스북 좋아요 -->
<script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
@stop