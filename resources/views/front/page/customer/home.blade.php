@extends('front.page.customer')

@section('customer')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
	
	$nNotice = new NoticeClass(); //
//======================== DB Module Noticet ============================
	$Conn = new DBClass();
	$nNotice->where = " where 1";
	$nNotice->total_record = $Conn->PageListCount
	(
		$nNotice->table_name, $nNotice->where, $search_key, $search_val
	);

	$nNotice->page_result = $Conn->PageList
	(	
		$nNotice->table_name, $nNotice, $nNotice->where, $search_key, $search_val, 'order by reg_date desc', $nNotice->sub_sql, $page_no, $nNotice->page_view, null
	);

	if(count($nNotice->page_result) != 0){
		$nNotice->VarList($nNotice->page_result, 0, null);
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
				if(count($nNotice->page_result) > 0){
					$row_no = $nNotice->total_record - ($nNotice->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nNotice->page_result); $i < $cnt_list; $i++) {
						$nNotice->VarList($nNotice->page_result, $i,  array('comment'));

			?>
			<tr>
				<td class="no">{{ $row_no }}</td>
				<td class="subject"><a href="{{ $view_link }}&seq={{ $nNotice->seq }}">{{ $nNotice->subject }}</a></td>
				<td class="date">{{ date('Y-m-d',strtotime($nNotice->reg_date)) }}</td>
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
	</article>


	<div class="search_box">
		<form name="frm" id="searchForm" method="post" action="{{ $list_link }}" style="display:inline">
			<input type="hidden" name="search_key" value="subject">
			<div class="styled-select xm_left">
				{{ $nNotice->ArrNotice(null, "name='search_key'", null, 'search') }}
			</div>
			<input type="text" name="search_val" value="{{ $search_val }}"/>
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<a href="#" id="search" class="green_btn">검색</a>
		</form>
	</div>

	<div class="paging">
		<?php
			if($nNotice->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nNotice->total_record, $page_no, $nNotice->page_view, $nNotice->page_set, $nNotice->page_where, 'pageNumber','');
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