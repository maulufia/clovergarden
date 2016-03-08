@extends('front.page.sponsorzone')

@section('sponsorzone')
<?php
	if(Auth::user()->user_state < 10){
		if(Auth::user()->user_state != 4){
			JsAlert('기업회원만 이용가능합니다.', 1, $list_link);
		}
	}

	$page_key   = 'H1';

	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;
	$row_no     = isset($_POST['row_no']) ? $_POST['row_no'] : 1;
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nSchedule = new ScheduleClass(); //자유게시판

	//======================== DB Module Start ============================
	$Conn = new DBClass();

	   
	    $nSchedule->where = "where seq ='".$seq."'";
	    $nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);

	    if(count($nSchedule->read_result) != 0){
	        $nSchedule->VarList($nSchedule->read_result, 0, null);
	    }else{
	        $Conn->DisConnect();
	        JsAlert(NO_DATA, 1, $list_link);
	    }

	$Conn->DisConnect();
	//======================== DB Module End ===============================

?>
<script type="text/javascript" src="/others/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
window.onload =function(){
 CKEDITOR.replace('content', {
    enterMode:'1',
        filebrowserUploadUrl : '{{ route("fileupload") }}',
        filebrowserImageUploadUrl : '{!! route("fileupload", array("command" => "QuickUpload", "type" => "Images", "_token" => csrf_token() )) !!}'
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
			<input type="hidden" name="seq" value="{{ $seq }}" />
		<table>
			<caption>게시판 글쓰기</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<tr >
				<th scope="row" class="first">제목</th>
				<td><input type="text" name="subject" value="{{ $nSchedule->subject }}"></td>
			</tr>
			<tr >
				<th scope="row" class="first">이름</th>
				<td><input type="text" name="writer" value="{{ Auth::user()->user_name }}"></td>
			</tr>
			<tr >
				<th scope="row">내용</th>
				<td><textarea name="content" id="content">{{ $nSchedule->content }}</textarea></td>
			</tr>
		</table>
		{{ UserHelper::SubmitHidden() }}
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