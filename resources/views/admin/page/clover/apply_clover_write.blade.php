@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'C2';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
?>
<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
window.onload =function(){
 CKEDITOR.replace('content', {
		enterMode:'1',
        filebrowserUploadUrl : 'http:///ckeditor/upload.php',
        filebrowserImageUploadUrl : 'http:///ckeditor/upload.php?command=QuickUpload&type=Images'
    });
};
</script>
<script language="javascript">

	function sendSubmit()
	{
		var f = document.frm;

		$.blockUI();
		f.action = "{{ $write_link }}";
		f.submit();
	}
</script>
</head>
<body>
<!-- wrapper -->
<div id="wrapper">
    <!-- top_area -->
        @include('admin.common.top')
    <!-- //top_area -->
    <!-- container -->
    <div id="container">
        <!-- left_area -->
            @include('admin.common.left')
        <!-- //left_area -->
        <!-- right_area -->
        <div id="right_area">
            <h4 class="main-title">후원기관 소식지 등록</h4>
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
            <table class="bbs-write">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:100px;" />
                    <col style="width:350px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>후원기관명</th>
                    <td colspan="2">
                        <input type="text" name="subject" style="width:100px;"/>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">소식지 편집</th>
                    <td colspan="2">
                    <textarea name="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>이북 URL</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">({{ $nClover->file_mime_type[1] }} : {{ $nClover->file_volume[1] }}{{ LOW_FILESIZE }})</span></td>
                </tr>
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            
            </form>
            <div class="btn-area">
                <div class="fright">
                    <input type="image" src="/new_admin/images/btn_save.gif" alt="save" onclick="javascript:alert('소식지 정상 등록 되었습니다');"/>
                </div>
            </div>
        </div>
        <!-- //right_area -->
    </div>
    <!-- container -->
    <!-- footer -->
        @include('admin.common.footer')
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>
@stop
