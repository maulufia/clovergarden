@extends('front.page.customer')

@section('customer')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
	
	$nFaq = new FaqClass(); //
//======================== DB Module Faqt ============================
	$Conn = new DBClass();
	$nFaq->where = " where 1";
	$nFaq->total_record = $Conn->PageListCount
	(
		$nFaq->table_name, $nFaq->where, $search_key, $search_val
	);

	$nFaq->page_result = $Conn->PageList
	(	
		$nFaq->table_name, $nFaq, $nFaq->where, $search_key, $search_val, 'order by reg_date desc', $nFaq->sub_sql, $page_no, $nFaq->page_view, null
	);

	if(count($nFaq->page_result) != 0){
		$nFaq->VarList($nFaq->page_result, 0, null);
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
				<col class="colWidth598">
				<col class="colWidth105">
			</colgroup>
			<tr class="title">
				<th scope="col" class="first">NO</th>
				<th scope="col"><span>제목</span></th>
				<th scope="col"><span>날짜</span></th>
			</tr>
			<?php
				if(count($nFaq->page_result) > 0){
					$row_no = $nFaq->total_record - ($nFaq->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nFaq->page_result); $i < $cnt_list; $i++) {
						$nFaq->VarList($nFaq->page_result, $i,  array('comment'));

			?>
			<tr>
				<td class="no">{{ $row_no }}</td>
				<td class="subject"><a href="{{ $view_link }}&seq={{ $nFaq->seq }}">{{ $nFaq->subject }}</a></td>
				<td class="date">{{ date('Y-m-d',strtotime($nFaq->reg_date)) }}</td>
			</tr>
			<?php
						$row_no = $row_no - 1;
					}
				} else {
			?>
					<tr>
						<td colspan="3" style="height:200px; text-align:center;">{{ NO_DATA }}</td>
					</tr>
			<?php
				}
			?>
		</table>
	</article>

	<div class="search_box">
		<form name="frm" id="searchForm" method="post" action="{{ $list_link }}" style="display:inline">
			<input type="hidden" name="search_key" value="subject">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<div class="styled-select xm_left">
				{{ $nFaq->ArrFaq(null, "name='search_key'", null, 'search') }}
			</div>
			<input type="text" name="search_val" value="{{ $search_val }}"/>
			<a href="#" id="search" class="green_btn">검색</a>
		</form>
	</div>

	<div class="paging">
		<?php
			if($nFaq->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nFaq->total_record, $page_no, $nFaq->page_view, $nFaq->page_set, $nFaq->page_where, 'pageNumber','');
			}
		?>
	</div>
	<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
		{{ UserHelper::SubmitHidden() }}
	</form>
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