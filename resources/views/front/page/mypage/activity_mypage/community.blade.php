@extends('front.page.mypage')

@section('mypage')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
	
	$nFree = new FreeClass(); //
	$nClovercomment = new ClovercommentClass(); //
	$nSchedulepeo = new SchedulepeoClass(); //
	$nClover_m = new CloverClass(); //클로버목
	$nSchedule   = new ScheduleClass(); //수술갤러리
//======================== DB Module Freet ============================
	$Conn = new DBClass();
	$nFree->where = " where writer like '%". Auth::user()->user_id ."%'";
	$nFree->total_record = $Conn->PageListCount
	(
		$nFree->table_name, $nFree->where, $search_key, $search_val
	);
	
	$nFree->page_view = 9999; // 무한대 (페이지네이션 없는 기간만 임시)
	$nFree->page_result = $Conn->PageList
	(	
		$nFree->table_name, $nFree, $nFree->where, $search_key, $search_val, 'order by reg_date desc', $nFree->sub_sql, $page_no, $nFree->page_view, null
	);

	if(count($nFree->page_result) != 0){
		$nFree->VarList($nFree->page_result, 0, null);
	}

	$nClovercomment->where = " where writer like '%" . Auth::user()->user_id . "%'";
	$nClovercomment->total_record = $Conn->PageListCount
	(
		$nClovercomment->table_name, $nClovercomment->where, $search_key, $search_val
	);

	$nClovercomment->page_view = 9999; // 무한대 (페이지네이션 없는 기간만 임시)
	$nClovercomment->page_result = $Conn->PageList
	(	
		$nClovercomment->table_name, $nClovercomment, $nClovercomment->where, $search_key, $search_val, 'order by reg_date desc', $nClovercomment->sub_sql, $page_no, $nClovercomment->page_view, null
	);

	if(count($nClovercomment->page_result) != 0){
		$nClovercomment->VarList($nClovercomment->page_result, 0, null);
	}

	$nSchedulepeo->where = " where writer = '". Auth::user()->user_id . "'";

	$nSchedulepeo->total_record =  $Conn->PageListCount($nSchedulepeo->table_name, " where writer = '" . Auth::user()->user_id . "' group by writer, schedule_seq order by seq desc", $search_key, $search_val);
	
	if($nSchedulepeo->total_record < 1){
		$nSchedulepeo->total_record = 0;
	}

	$nSchedulepeo->page_view = 9999; // 무한대 (페이지네이션 없는 기간만 임시)
	$nSchedulepeo->page_result = $Conn->PageList
	(	
		$nSchedulepeo->table_name, $nSchedulepeo, $nSchedulepeo->where, $search_key, $search_val, 'group by writer, schedule_seq order by reg_date desc', $nSchedulepeo->sub_sql, $page_no, $nSchedulepeo->page_view, null
	);

	if(count($nSchedulepeo->page_result) != 0){
		$nSchedulepeo->VarList($nSchedulepeo->page_result, 0, null);
	}




	$nClover_m->read_result = $Conn->AllList
	(
		$nClover_m->table_name, $nClover_m, "*", $nClover_m->where, null, null
	);

	$nSchedule->read_result = $Conn->AllList
	(	
		$nSchedule->table_name, $nSchedule, "*", "order by seq desc", null, null
	);
	$nSchedule_code = array();
	if(count($nSchedule->read_result) != 0){
		for($i=0, $cnt_list=count($nSchedule->read_result); $i < $cnt_list; $i++) {
			$nSchedule->VarList($nSchedule->read_result, $i, null);
			$nSchedule_code[$nSchedule->seq] = $nSchedule->clover_seq;
		}
	}	

	if(count($nClover_m->read_result) != 0){
		for($i=0, $cnt_list=count($nClover_m->read_result); $i < $cnt_list; $i++) {
			$nClover_m->VarList($nClover_m->read_result, $i, null);
			$clober_name_scode[$nClover_m->code] = $nClover_m->subject;
		}
	}	
$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>

<section class="wrap">
	<header>
		<h2>나의 활동정보</h2>
	</header>
	<article class="article_box article_box_lb">
		<h3>내가 쓴 글</h3>
		<span>{{ $nFree->total_record }} 개</span>
	</article>
	<article class="article_box">
		<h3>나의 응원댓글</h3>	    			
		<span>{{ $nClovercomment->total_record }} 개</span>
	</article>
	<article class="article_box article_box_last">
		<h3>봉사활동 참여 수</h3>
		<span>{{ $nSchedulepeo->total_record }} 번</span>
	</article>
</section>

<section class="wrap">
	<header>
		<h2 class="ti">커뮤니티 목록</h2>
	</header>
	<article class="brd_tab_3_list">
		<h2 class="ti">내가 쓴 글 / 나의 응원댓글 / 봉사홀동 참여 수</h2>
		
		<div id="tabs" class="tab">
			<ul>
				<li class="menu tabs-1"><a href="#tabs-1">내가 쓴 글</a></li>
				<li class="menu tabs-2"><a href="#tabs-2">나의 응원댓글</a></li>
				<li class="menu tabs-3 last"><a href="#tabs-3">봉사활동 참여 수</a></li>
			</ul>
			<div class="xm_clr"></div>

			<div id="tabs-1" class="tabCont">
				<table>
					<caption>게시판 목록</caption>
					<colgroup>
						<col class="colWidth89">
						<col class="colWidth598">
						<col class="colWidth105">
					</colgroup>
					<tr class="title">
						<th scope="col" class="first">NO</th>
						<th scope="col"><span>제목</span></th>
						<th scope="col"><span>날짜</span></th>
					</tr>
					<?php
						if(count($nFree->page_result) > 0){
							$row_no = $nFree->total_record - ($nFree->page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nFree->page_result); $i < $cnt_list; $i++) {
								$nFree->VarList($nFree->page_result, $i,  array('comment'));
							
					?>
					<tr>
						<td class="no">{{ $row_no }}</td>
						<td class="subject"><a href="{{ $view_link }}&seq={{ $nFree->seq }}">{{ $nFree->subject }}</a></td>
						<td class="date">{{ date('Y-m-d',strtotime($nFree->reg_date)) }}</td>
					</tr>
					

					<?php
								$row_no = $row_no - 1;
							}
						}else{
					?>
							<tr>
								<td colspan="3" style="height:200px; text-align:center;">{{ NO_DATA }}</td>
							</tr>
					<?php
						}
					?>
				</table>

				<div class="paging">
					<?php
						// if($nFree->total_record != 0){
						if(false){ // 페이지네이션 삭제
							$nPage = new PageOut();
							$nPage->CustomPageList($nFree->total_record, $page_no, $nFree->page_view, $nFree->page_set, $nFree->page_where, 'pageNumber','');
						}
					?>
				</div>

			</div>

			<div id="tabs-2" class="tabCont">
				<table>
					<caption>내가 단 댓글 목록</caption>
					<colgroup>
						<col class="colWidth89">
						<col class="colWidth598">
						<col class="colWidth105">
					</colgroup>
					<tr class="title">
						<th scope="col" class="first">NO</th>
						<th scope="col"><span>댓글</span></th>
						<th scope="col"><span>날짜</span></th>
					</tr>
					<?php
						if(count($nClovercomment->page_result) > 0){
							$row_no = $nClovercomment->total_record - ($nClovercomment->page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nClovercomment->page_result); $i < $cnt_list; $i++) {
								$nClovercomment->VarList($nClovercomment->page_result, $i,  array('comment'));

								$view_link = route('clovergarden') . "?cate=1&dep01=0&dep02=0&type=view";

					?>
					<tr>
						<td class="no">{{ $row_no }}</td>
						<td class="subject"><a href="{{ $view_link }}&seq={{ $nClovercomment->clover_seq }}#tabs-4">{{ $nClovercomment->subject }}</a></td>
						<td class="date">{{ date('Y-m-d',strtotime($nClovercomment->reg_date)) }}</td>
					</tr>
					<?php
								$row_no = $row_no - 1;
							}
						}else{
					?>
							<tr>
								<td colspan="3" style="height:200px; text-align:center;">{{ NO_DATA }}</td>
							</tr>
					<?php
						}
					?>
				</table>

				<div class="paging">
					<?php
						// if($nClovercomment->total_record != 0){
						if(false){ // 페이지네이션 삭제
							$nPage = new PageOut();
							$nPage->CustomPageList($nClovercomment->total_record, $page_no, $nClovercomment->page_view, $nClovercomment->page_set, $nClovercomment->page_where, 'pageNumber','');
						}
					?>
				</div>
			</div>

			<div id="tabs-3" class="tabCont">
				<table>
					<caption>내가 쓴 글 목록</caption>
					<colgroup>
						<col class="colWidth89">
						<col class="colWidth147">
						<col class="colWidth451">
						<col class="colWidth105">
					</colgroup>
					<tr class="title">
						<th scope="col" class="first">NO</th>
						<th scope="col"><span>기관명</span></th>
						<th scope="col"><span>프로그램</span></th>
						<th scope="col"><span>날짜</span></th>
					</tr>
					<?php
						$s_value_v = null;
						if(count($nSchedulepeo->page_result) > 0){
							$row_no = $nSchedulepeo->total_record - ($nSchedulepeo->page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nSchedulepeo->page_result); $i < $cnt_list; $i++) {
								$nSchedulepeo->VarList($nSchedulepeo->page_result, $i,  array('comment'));

								$Conn = new DBClass();
									$nSchedule  = new ScheduleClass(); //봉사스케쥴
									$nSchedule->where = "where seq ='".$nSchedulepeo->schedule_seq."'";
									$nSchedule->read_result = $Conn->AllList
									(
										$nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null
									);
								
									if(count($nSchedule->read_result) != 0){
										$nSchedule->VarList($nSchedule->read_result, 0, null);

										$s_value_v = $nSchedule->subject;
									}	
								$Conn->DisConnect();


					?>
					<tr>
						<td class="no">{{ $row_no }}</td>
						<td class="normal"><a href="{{ route('sponsorzone') }}?cate=0&dep01=2&dep02=0&view={{ $nSchedulepeo->schedule_seq }}">
						{{ isset($nSchedule_code[$nSchedulepeo->schedule_seq]) ? $clober_name_scode[$nSchedule_code[$nSchedulepeo->schedule_seq]] : '' }}				
						</a></td>
						<td class="subject"><a href="{{ route('sponsorzone') }}?cate=0&dep01=1&dep02=2&type=view&seq={{ $nSchedulepeo->schedule_seq }}">{{ $s_value_v }}</a></td>
						<td class="date">{{ date('Y-m-d',strtotime($nSchedulepeo->reg_date)) }}</td>
					</tr>
					<?php
								$row_no = $row_no - 1;
							}
						}else{
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
						//if($nSchedulepeo->total_record != 0){
						if(false){ // 페이지네이션 삭제
							$nPage = new PageOut();
							$nPage->CustomPageList($nSchedulepeo->total_record, $page_no, $nSchedulepeo->page_view, $nSchedulepeo->page_set, $nSchedulepeo->page_where, 'pageNumber','');
						}
					?>
				</div>
				<form name="form_submit" method="post" action="{{ route('mypage') }}?cate=6&dep01=1&dep02=0#tabs-1!" style="display:inline">
					{{ UserHelper::SubmitHidden() }}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
			</div>
		</div>
	</article>
</section>

<script type="text/javascript">

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
} 

(function($) {
    $(function() {
        $("#partner").simplyScroll();


        //tabs
        $( "#tabs" ).tabs();

				$('#tabs .menu').click(function(){
					// jQuery tabs메소드 문제인지 URL이 변경되지 않아 강제로 변경 적용
					window.location.href = $(this).children('a').attr('href') + '!';
					
					$('#tabs .menu').removeClass("on");
					$(this).addClass('on');
					
					// 클릭한 탭에 따라서 ACTION URL을 변경해줌
					var tabUrl = '/mypage?cate=6&dep01=1&dep02=0#' + $(this).attr('aria-controls') + '!';
					$('form[name=form_submit').attr('action', tabUrl);
				});


        $( ".tabCont" ).hide();

	
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

$(document).ready(function() {

	var hash = window.location.hash.substr(1); // by YJM
	hash = hash.replace(/\!/g, '');

	if (hash == null || hash == '')
	{
		hash = "tabs-1";
	}
	
	// 초기 로딩 시 form action설정
	var tabUrl = '/mypage?cate=6&dep01=1&dep02=0#' + hash + '!';
	$('form[name=form_submit').attr('action', tabUrl);

	$( "#"+hash ).show();
	$('#tabs .menu.'+hash).addClass('on');


});
</script>
@stop