@extends('front.page.sponsorzone')

@section('sponsorzone')

<?php
	$nFree = new FreeClass(); //
	$nFree_g = new FreeClass(); //
	$nClovercomment = new ClovercommentClass(); //
	$nClovercomment_g = new ClovercommentClass(); //
	$nClover = new CloverClass(); //
	$nMember = new MemberClass(); //
	$nMoney = new MoneyClass(); //
	$nClovermlist_login = new ClovermlistClass(); //

	//======================== DB Module Freet ============================
	$Conn = new DBClass();

		$nMember->where = "where user_state > 1";
		$nMember->total_record = $Conn->PageListCount
		(
			$nMember->table_name, $nMember->where, $search_key, $search_val
		);

		$nClover->total_record = $Conn->PageListCount
		(
			$nClover->table_name, $nClover->where, $search_key, $search_val
		);

		$nFree->page_result = $Conn->AllList
		(	
			$nFree->table_name, $nFree, "*", "where 1 order by seq desc limit 5", null, null
		);

		$nFree_g->page_result = $Conn->AllList
		(	
			$nFree_g->table_name, $nFree_g, "*", "where group_name='".$group_name."' order by seq desc limit 5", null, null
		);



		$nClovercomment->page_result = $Conn->AllList
		(	
			$nClovercomment->table_name, $nClovercomment, "*", "where 1 order by seq desc limit 5", null, null
		);


		$nClovercomment_g->page_result = $Conn->AllList
		(	
			$nClovercomment_g->table_name, $nClovercomment_g, "*", "where group_name='".$group_name."' order by seq desc limit 5", null, null
		);


		$nMoney->read_result = $Conn->AllList($nMoney->table_name, $nMoney, "*", "where seq ='1'", $nMoney->sub_sql, null);

		if(count($nMoney->read_result) != 0){
			$nMoney->VarList($nMoney->read_result, 0, array('comment'));
		}



		$nClovermlist_login->read_result = $Conn->AllList($nClovermlist_login->table_name, $nClovermlist_login, "*, sum(price) price", "where id ='$login_id'", $nClovermlist_login->sub_sql, null);

		if(count($nClovermlist_login->read_result) != 0){
			$nClovermlist_login->VarList($nClovermlist_login->read_result, 0, array('comment'));
		}

		$nClovermlist_login->where = "where id='".$login_id."' group by id";
		$nClovermlist_login->total_record = $Conn->PageListCount
		(
			$nClovermlist_login->table_name, $nClovermlist_login->where, $search_key, $search_val
		);

	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>

<section class="wrap">
	<header>
		<h2>후원자 현황</h2>
	</header>

	
	<article class="article_box article_box_lb">
		<h3>누적 기부 금액</h3>
		<span>{{ number_format($nMoney->month) }} 원</span>
	</article>
	<article class="article_box">
		<h3>누적 후원기관 수</h3>	    			
		<span>{{ number_format($nClover->total_record) }} 개</span>
	</article>
	<article class="article_box article_box_last">
		<h3>누적 후원자 수</h3>
		<span>{{ number_format($nMember->total_record) }} 명</span>
	</article>
</section>

<?php
if(Auth::check()){
?>
<!-- 비로그인시 숨김 -->
<section class="wrap">
	<header>
		<h2>{{ Auth::user()->user_name }} 님은?</h2>
	</header>
	<article class="article_box article_box_lb">
		<h3><?php if($nClovermlist_login->group_name != ""){ ?>{{ $nClovermlist_login->group_name }} <?php } ?>후원금액</h3>
		<span>{{ number_format($nClovermlist_login->price) }} 원</span>
	</article>
	<article class="article_box">
		<h3>누적 후원기관 수</h3>	    			
		<span>{{ number_format($nClovermlist_login->total_record) }} 개</span>
	</article>
	<article class="article_box article_box_bg article_box_last">
		<h3 class="all_view"><a href="{{ route('mypage') }}?cate=6&dep01=3&dep02=1"><img src="/imgs/Plusicon.png" alt="" /> 후원현황 전체보기</a></h3>
	</article>
</section>
<!-- //비로그인시 숨김 -->
<?php } ?>

<section class="wrap last">	    		
	<h2 class="ti">타임라인/자유게시판</h2>
	<article class="tabView">
		<header>
			<h2>타임라인</h2>
		</header>
		<div id="tabs" class="tab">
			<ul>
				<li class="menu on"><a href="#tabs-1">전체</a></li>
				<li class="menu last"><a href="#tabs-2">{{ $group_name }}</a></li>
			</ul>
			<div id="tabs-1" class="tabCont">
				<ul>
					<?php
					if(count($nClovercomment->page_result)>0){
					for($i=0, $cnt_list=count($nClovercomment->page_result); $i < $cnt_list; $i++) {
						$nClovercomment->VarList($nClovercomment->page_result, $i, null);
					?>
					<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=0">{{ $nClovercomment->subject }}</a></li>
					<?php
						}
					}else{					
					?>
						<li style="text-align:center;">{{ NO_DATA }}</li>
					<?php } ?>
				</ul>
				<a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=0"><img src="/imgs/Plusicon.png" alt="" class="plus" /></a>
			</div>
			<div id="tabs-2" class="tabCont">
				<ul>
					<?php
					if(count($nClovercomment_g->page_result)>0){
					for($i=0, $cnt_list=count($nClovercomment_g->page_result); $i < $cnt_list; $i++) {
						$nClovercomment_g->VarList($nClovercomment_g->page_result, $i, null);
					?>
					<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=0">{{ $nClovercomment_g->subject }}</a></li>
					<?php
						}
					}else{					
					?>
						<li style="text-align:center;">{{ NO_DATA }}</li>
					<?php } ?>
				</ul>
				<a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=0"><img src="/imgs/Plusicon.png" alt="" class="plus" /></a>
			</div>

		</div>
	</article>

	<article class="tabView last">
		<header>
			<h2>자유게시판</h2>
		</header>
		<div id="tabs2" class="tab">
			<ul>
				<li class="menu on"><a href="#tabs2-1">전체</a></li>
				<li class="menu last"><a href="#tabs2-2">{{ $group_name }}</a></li>
			</ul>
			<div id="tabs2-1" class="tabCont">
				<ul>
					<?php
					if(count($nFree->page_result)>0){
					for($i=0, $cnt_list=count($nFree->page_result); $i < $cnt_list; $i++) {
						$nFree->VarList($nFree->page_result, $i, null);
					?>
					<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=1&type=view&seq={{ $nFree->seq }}">{{ $nFree->subject }}</a></li>
					<?php
						}
					}else{					
					?>
						<li style="text-align:center;">{{ NO_DATA }}</li>
					<?php } ?>
				</ul>
				<a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=1"><img src="/imgs/Plusicon.png" alt="" class="plus" /></a>
			</div>

			<div id="tabs2-2" class="tabCont">
				<ul>
					<?php
					if(count($nFree_g->page_result)>0){
					for($i=0, $cnt_list=count($nFree_g->page_result); $i < $cnt_list; $i++) {
						$nFree_g->VarList($nFree_g->page_result, $i, null);
					?>
					<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=1&type=view&seq={{ $nFree->seq }}">{{ $nFree_g->subject }}</a></li>
					<?php
						}
					}else{					
					?>
						<li style="text-align:center;">{{ NO_DATA }}</li>
					<?php } ?>
				</ul>
				<a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=1"><img src="/imgs/Plusicon.png" alt="" class="plus" /></a>
			</div>

		</div>
	</article>
</section>


<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        $( "#tabs" ).tabs();
		$( "#tabs2" ).tabs();

		$('#tabs .menu').click(function(){
			$('#tabs .menu').removeClass("on");
			$(this).addClass('on');
		});

		$('#tabs2 .menu').click(function(){
			$('#tabs2 .menu').removeClass("on");
			$(this).addClass('on');
		});

    });
})(jQuery);
</script>

@stop