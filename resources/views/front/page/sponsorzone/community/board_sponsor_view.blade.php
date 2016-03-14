@extends('front.page.sponsorzone')

@section('sponsorzone')
<?php

	$nFree = new FreeClass(); //뉴스

	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$Conn->UpdateDBQuery($nFree->table_name, "hit = hit + 1 where seq = '".$seq."'");
		$nFree->where = "where seq ='".$seq."'";

		$nFree->read_result = $Conn->AllList($nFree->table_name, $nFree, "*", $nFree->where, null, null);
		if(count($nFree->read_result) != 0){
			$nFree->VarList($nFree->read_result, 0, null);
			$board_name = explode(',',$nFree->writer);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}
	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>
<section class="wrap">
	<header>
		<h2 class="ti">자유게시판 보기</h2>
	</header>
	<article class="brd_view_title">
		<h2 class="ti">자유게시판 제목/날짜/작성자</h2>
		<div class="brd_view_title_1">
			<h3>제목</h3>
			<span>{{ $nFree->subject }}</span>
		</div>
		<div class="xm_clr"></div>

		<div class="brd_view_title_2">
			<h4>날짜</h4>
			<span class="mr30">{{ date('Y-m-d',strtotime($nFree->reg_date)) }}</span>

			<h4>작성자</h4>
			<span>{{ $board_name[0] }}님</span>
		</div>
	</article>

	<article class="brd_view_cont">
		<h2 class="ti">내용</h2>
		{!! $nFree->content !!}
	</article>

	<article class="brd_view_cont">
		@include('front.page.sponsorzone.community.reply')
	</article>

	<div class="xm_right mt24">
	<a href="{{ $list_link }}" class="orange_btn">목록보기</a>
	<?php
	 	if(Auth::check()) {
			if($board_name[1] == Auth::user()->user_id || Auth::user()->user_id == 'master@clovergarden.co.kr'){
	?>
		<form id="form_del" method="POST" action="{{ route('sponsorzone', array('cate' => 0, 'dep01' => 1, 'dep02' => 1, 'type' => 'del', 'seq' => $_GET['seq'])) }}" style="display: inline;">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<a href="javascript:if(confirm('삭제하시겠습니까?')){ document.getElementById('form_del').submit(); }" class="orange_btn">삭제하기</a>
		</form>
		<?php } ?>
		<?php if($board_name[1] == Auth::user()->user_id || Auth::user()->user_id == 'master@clovergarden.co.kr'){ ?><a href="{{ $list_link }}&type=edit&seq={{ $_GET['seq'] }}" class="orange_btn">수정하기</a><?php } ?>
	<?php
		}
	?>
	
	</div>
</section>
@stop