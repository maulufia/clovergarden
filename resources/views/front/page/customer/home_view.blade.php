@extends('front.page.customer')

@section('customer')
<?php
	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nNotice = new NoticeClass(); //뉴스

	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$Conn->UpdateDBQuery($nNotice->table_name, "hit = hit + 1 where seq = '".$seq."'");
		$nNotice->where = "where seq ='".$seq."'";

		$nNotice->read_result = $Conn->AllList($nNotice->table_name, $nNotice, "*", $nNotice->where, null, null);
		if(count($nNotice->read_result) != 0){
			$nNotice->VarList($nNotice->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}

	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>
<section class="wrap">
	<header>
		<h2 class="ti">새소식 보기</h2>
	</header>
	<article class="brd_view_title">
		<h2 class="ti">새소식 제목/날짜/작성자</h2>
		<div class="brd_view_title_1">
			<h3>제목</h3>
			<span>{{ $nNotice->subject }}</span>
		</div>
		<div class="xm_clr"></div>

		<div class="brd_view_title_2">
			<h4>날짜</h4>
			<span class="mr30">{{ date('Y-m-d',strtotime($nNotice->reg_date)) }}</span>

			<h4>작성자</h4>
			<span>클로버가든</span>
		</div>
	</article>

	<article class="brd_view_cont">
		<h2 class="ti">내용</h2>
		<?php
			echo $nNotice->content;
		?>
	</article>

	<div class="xm_right mt24"><a href="{{ $list_link }}" class="orange_btn">목록보기</a></div>
</section>
@stop