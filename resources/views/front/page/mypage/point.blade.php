@extends('front.page.mypage')

@section('mypage')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nPoint = new PointClass(); //회원
	$nPoint_sum = new PointClass(); //회원
	$nPoint_outsum = new PointClass(); //회원


	$Conn = new DBClass();

  $nPoint->where = " where userid='" . Auth::user()->user_id . "'";
	$nPoint->page_view = 10;

  $nPoint->total_record = $Conn->PageListCount
  (
      $nPoint->table_name, $nPoint->where, $search_key, $search_val
  );

  $nPoint->page_result = $Conn->PageList
  (
      $nPoint->table_name, $nPoint, $nPoint->where, $search_key, $search_val, 'order by idx desc', $nPoint->sub_sql, $page_no, $nPoint->page_view
  );


	$nPoint_sum->page_result = $Conn->AllList
	(	
		$nPoint_sum->table_name, $nPoint_sum, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='" . Auth::user()->user_id . "' group by userid", null, null
	);

	$Conn->DisConnect();

?>

<section class="wrap">
	<header>
		<h2>나의 포인트정보</h2>
	</header>
	<?php
	if(count($nPoint_sum->page_result) > 0){
	for($i=0, $cnt_list=count($nPoint_sum->page_result); $i < $cnt_list; $i++) {
		$nPoint_sum->VarList($nPoint_sum->page_result, $i, null);

		$use_point = $nPoint_sum->inpoint - $nPoint_sum->outpoint;
	?>

	<article class="article_box2 article_box_lb">
		<h3>잔여포인트</h3>
			<span>{{ number_format($use_point) }}Point</span>
	</article>
	<article class="article_box2 article_box_last">
		<h3>기부된포인트</h3>
		<span>{{ number_format($nPoint_sum->outpoint) }}Point</span>
	</article>
	<?php
		}
	} else {
	?>
	<article class="article_box2 article_box_lb">
		<h3>잔여포인트</h3>


			<span>0Point</span>


	</article>
	<article class="article_box2 article_box_last">
		<h3>기부된포인트</h3>
		<span>0Point</span>
	</article>
	<?php
	}
	?>
</section>

<section class="mt_20">
	<header>
		<h2 class="ti">포인트조회 목록</h2>
	</header>

	<article class="brd_list">
		<h2 class="ti">포인트조회</h2>
		
		<table>
			<caption>게시판 목록</caption>
			<colgroup>
				<col class="colWidth158" style="width:200px;">
				<col class="colWidth158" style="width:200px;">
				<col class="colWidth158" style="width:200px;">
				<col class="colWidth158" style="width:200px;">
			</colgroup>
			<tr class="title">
				<th scope="col" class="first">내역</th>
				<th scope="col"><span>지급포인트</span></th>
				<th scope="col"><span>기부포인트</span></th>
				<th scope="col"><span>날짜</span></th>
			</tr>
<?php
    if(count($nPoint->page_result) > 0){
        $row_no = $nPoint->total_record - ($nPoint->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nPoint->page_result); $i < $cnt_list; $i++) {
            $nPoint->VarList($nPoint->page_result, $i, null);
?>

			<tr>
				<td class="normal">{{ $nPoint->depth }}</td>
				<td class="normal">{{ number_format($nPoint->inpoint) }}pt</td>
				<td class="normal">{{ number_format($nPoint->outpoint) }}pt</td>
				<td class="normal">{{ date("Y.m.d",$nPoint->signdate) }}</td>
			</tr>
<?php
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr height=100>
                    <td colspan="4" align=center>{{ NO_DATA }}</td>
                </tr>
<?php
    }
?>

		</table>
	</article>

	<div class="paging">
	<?php
		if($nPoint->total_record != 0){
			$nPage = new PageOut();
			$nPage->CustomPageList($nPoint->total_record, $page_no, $nPoint->page_view, $nPoint->page_set, $nPoint->page_where, 'pageNumber');
		}
	?>
	</div>
	<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
		{{ UserHelper::SubmitHidden() }}
	</form>

</section>
@stop