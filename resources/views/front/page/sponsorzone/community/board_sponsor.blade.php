@extends('front.page.sponsorzone')

@section('sponsorzone')

<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nFree = new FreeClass(); //
//======================== DB Module Freet ============================
	$Conn = new DBClass();


	$nFree->total_record = $Conn->PageListCount
	(
		$nFree->table_name, $nFree->where, $search_key, $search_val
	);

	$nFree->page_result = $Conn->PageList
	(	
		$nFree->table_name, $nFree, $nFree->where, $search_key, $search_val, 'order by reg_date desc', $nFree->sub_sql, $page_no, $nFree->page_view, null
	);

	if(count($nFree->page_result) != 0){
		$nFree->VarList($nFree->page_result, 0, null);
	}


$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap">
	<header>
		<h2 class="ti">자유게시판 목록</h2>
	</header>

	<article class="brd_list">
		<h2 class="ti">NO/제목/날짜</h2>
		
		<table>
			<caption>게시판 목록</caption>
			<colgroup>
				<col class="colWidth89">
				<col class="colWidth458">
				<col class="colWidth105">
				<col class="colWidth105">
			</colgroup>
			<tr class="title">
				<th scope="col" class="first">NO</th>
				<th scope="col"><span>제목</span></th>
				<th scope="col"><span>작성자</span></th>
				<th scope="col"><span>날짜</span></th>
			</tr>
			<?php
				if(count($nFree->page_result) > 0){
					$row_no = $nFree->total_record - ($nFree->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nFree->page_result); $i < $cnt_list; $i++) {
						$nFree->VarList($nFree->page_result, $i,  array('comment'));
						$board_name = explode(',',$nFree->writer);
			?>
			<tr>
				<td class="no">{{ $row_no }}</td>
				<td class="subject"><a href="{{ $view_link }}&seq={{ $nFree->seq }}">{{ $nFree->subject }}</a></td>
				<td class="writer">{{ $board_name[0] }}</td>
				<td class="date">{{ date('Y-m-d',strtotime($nFree->reg_date)) }}</td>
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
	</article>

	<div class="search_box">
		<form name="frm" id="searchForm" method="POST" action="{{ $list_link }}" style="display:inline">
			<div class="styled-select xm_left">
				<?php $nFree->ArrFree(null, "name='search_key'", null, 'search') ?>
			</div>
			<input type="text" name="search_val" value="{{ $search_val }}"/>
			<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
			<a href="#" id="search" class="green_btn">검색</a>
			<a href="{{ $write_link }}" class="ml10 orange_btn">글쓰기</a>
		</form>
	</div>

	<div class="paging">
		<?php
			if($nFree->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nFree->total_record, $page_no, $nFree->page_view, $nFree->page_set, $nFree->page_where, 'pageNumber','');
			}
		?>
	</div>

	<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
		{{ UserHelper::SubmitHidden() }}
	</form>
	</div>
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 검색
        $( "#search" ).click(function() {

            if($('input[name=search_val]').val() == ''){
                alert('검색어를 입력하세요.');
                $('input[name=search_val]').focus();
                return false;
            }

            $( "#searchForm" ).submit();

        });
    });
})(jQuery);
</script>

@stop