@extends('front.page.sponsorzone')

@section('sponsorzone')
<?php
if(!Auth::check()){
	JsAlert('로그인후 이용해주세요.', 1, $list_link);
}
?>
<section class="wrap">
	<header>
		<h2>글쓰기</h2>
	</header>
	<article class="brd_write">
		<h2 class="ti">제목/내용</h2>
		<form method="POST" id="wrtForm" style="display:inline;" action="{{ $write_link }}"  enctype="multipart/form-data">
		<table>
			<caption>게시판 글쓰기</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<tr >
				<th scope="row" class="first">제목</th>
				<td><input type="text" name="subject" placeholder="제목을 입력해주세요."></td>
			</tr>
			<tr >
				<th scope="row" class="first">이름</th>
				<td><input type="text" name="writer" placeholder="제목을 입력해주세요." value="{{ Auth::user()->user_name }}"></td>
			</tr>
			<tr >
				<th scope="row">내용</th>
				<td><textarea name="content" id="content" placeholder="내용을 입력해주세요."></textarea></td>
			</tr>
		</table>
		<?php UserHelper::SubmitHidden() ?>
		<div class="xm_right mt24"><a href="{{ $list_link }}" class="green_btn mr10">목록보기</a> <a href="#" id="save" class="orange_btn">저장</a></div>
		</form>
	</article>
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 글쓰기
        $( "#save" ).click(function() {

            if($('input[name=subject]').val() == ''){
                alert('제목을 입력하세요.');
                $('input[name=subject]').focus();
                return false;
            }

            if($('textarea#contents').val() == ''){
                alert('내용을 입력하세요.');
                $('#contents').focus();
                return false;
            }

            $( "#wrtForm" ).submit();

        });

        // placeholder
        $('input, textarea').placeholder();
    });
})(jQuery);
</script>
@stop