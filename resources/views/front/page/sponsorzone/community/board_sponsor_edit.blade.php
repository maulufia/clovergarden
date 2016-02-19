@extends('front.page.sponsorzone')

@section('sponsorzone')
<?php

$page_key   = 'H1';

$seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');


$nFree = new FreeClass(); //자유게시판

//======================== DB Module Start ============================
$Conn = new DBClass();

   
    $nFree->where = "where seq ='".$seq."'";
    $nFree->read_result = $Conn->AllList($nFree->table_name, $nFree, "*", $nFree->where, null, null);

    if(count($nFree->read_result) != 0){
        $nFree->VarList($nFree->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================

?>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
window.onload =function(){
 CKEDITOR.replace('content', {
		enterMode:'1',
        filebrowserUploadUrl : 'http://{{ $_SERVER["HTTP_HOST"] }}/ckeditor/upload.php',
        filebrowserImageUploadUrl : 'http://{{ $_SERVER["HTTP_HOST"] }}/ckeditor/upload.php?command=QuickUpload&type=Images'
    });
};
</script>

<section class="wrap">
	<header>
		<h2>글쓰기</h2>
	</header>
	<article class="brd_write">
		<h2 class="ti">제목/내용</h2>
		<form method="post" id="wrtForm" action="{{ $edit_link }}" style="display:inline;"  enctype="multipart/form-data">
		<table>
			<caption>게시판 글쓰기</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<tr >
				<th scope="row" class="first">제목</th>
				<td><input type="text" name="subject" value="{{ $nFree->subject }}"></td>
			</tr>
			<tr >
				<th scope="row" class="first">이름</th>
				<td><input type="text" name="writer" value="{{ Auth::user()->user_name }}"></td>
			</tr>
			<tr >
				<th scope="row">내용</th>
				<td><textarea name="content" id="content">{{ $nFree->content }}</textarea></td>
			</tr>
		</table>
		{{ UserHelper::SubmitHidden() }}
		<div class="xm_right mt24"><a href="<?=$list_link?>" class="green_btn mr10">목록보기</a> <a href="#" id="save" class="orange_btn">저장</a></div>
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