@extends('front.page.mypage')

@section('mypage')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
	
	$nMessage = new MessageClass(); //

//======================== DB Module Messaget ============================
	$Conn = new DBClass();
	$nMessage->where = " where receive_id like '%" . Auth::user()->user_id . "%'";
	$nMessage->total_record = $Conn->PageListCount
	(
		$nMessage->table_name, $nMessage->where, $search_key, $search_val
	);

	$nMessage->page_result = $Conn->PageList
	(	
		$nMessage->table_name, $nMessage, $nMessage->where, $search_key, $search_val, 'order by reg_date desc', $nMessage->sub_sql, $page_no, $nMessage->page_view, null
	);

	if(count($nMessage->page_result) != 0){
		$nMessage->VarList($nMessage->page_result, 0, null);
	}

	$typev = isset($_GET['typev']) ? $_GET['typev'] : null;
	if($typev == "del"){
		$sql = "delete from new_tb_message where seq='".$_GET['seq']."'";
		mysql_query($sql);
		echo "<script>alert('삭제 되었습니다.'); window.location='{{ route('mypage', array('cate' => 6, 'dep01' => 1, 'dep02' => 1)) }}'; </script>";
	}
	$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap">
	<header>
		<h2 class="ti">보낸쪽지 목록</h2>
	</header>
	<article class="brd_list">
		<h2 class="ti">NO/제목/날짜</h2>
		
		<table>
			<caption>게시판 목록</caption>
			<colgroup>
				<col class="colWidth89">
				<col class="colWidth147">
				<col class="colWidth451">
				<col class="colWidth105">
			</colgroup>
			<tr class="title">
				<th scope="col" class="first">NO</th>
				<th scope="col"><span>보낸사람</span></th>
				<th scope="col"><span>내용</span></th>
				<th scope="col"><span>날짜</span></th>
			</tr>
			<?php
				if(count($nMessage->page_result) > 0){
					$row_no = $nMessage->total_record - ($nMessage->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nMessage->page_result); $i < $cnt_list; $i++) {
						$nMessage->VarList($nMessage->page_result, $i,  array('comment'));
						$send = explode(',',$nMessage->send_id);

			?>
			<tr>
				<td class="no">{{ $row_no }}</td>
				<td class="normal">{{ isset($send[1]) ? $send[1] : '' }}</td>
				<td class="subject"><?php echo $nMessage->content; ?> <a href="javascript:if(confirm('삭제하시겠습니까?')){ window.location='{{ $list_link }}&seq={{ $nMessage->seq }}&typev=del'; }"><font color="#fd4f00"><?php if($nMessage->hit>0) echo "[삭제하기]"; else echo "[삭제하기]"; ?></font></a></td>
				<td class="date">{{ date('Y-m-d',strtotime($nMessage->reg_date)) }}</td>
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
		<form name="frm" id="searchForm" method="post" action="{{ $list_link }}" style="display:inline">
			<input type="hidden" name="search_key" value="subject">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="text" name="search_val" value="{{ $search_val }}"/>
			<a href="#" id="search" class="green_btn">검색</a>
		</form>
	</div>

	<div class="paging">
		<?php
			if($nMessage->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nMessage->total_record, $page_no, $nMessage->page_view, $nMessage->page_set, $nMessage->page_where, 'pageNumber','');
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