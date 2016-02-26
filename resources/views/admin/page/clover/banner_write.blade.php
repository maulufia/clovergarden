@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'C6';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";
  
  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nClover = new CloverClass();
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
		f.action = "<?php echo $write_link; ?>";
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
            <h4 class="main-title">{{ $content_txt }}</h4>
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
			       <input type="hidden" name="view_n" value="1">
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
                    <th>제목</th>
                    <td>
                        <input type="text" name="subject" style="width:500px;"/>
                    </td>
                </tr>
                <tr>
                    <th>메인이미지</th>
                    <td><input type="file" name="upfile1" size="50" /> <span class="lmits">({{ $nClover->file_mime_type[1] }} : {{ $nClover->file_volume[1] }}{{ LOW_FILESIZE }})</span></td>
                </tr>
                <tr>
                    <th>이미지url</th>
                    <td>
                        <input type="text" name="group_name" style="width:500px;"/>
                    </td>
                </tr> 
                <tr>
                    <th>참여하기url</th>
                    <td>
                        <input type="text" name="news" style="width:500px;"/>
                    </td>
                </tr> 
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            
            </form>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <input type="image" src="/imgs/admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
                </div>
            </div>
        </div>
        <form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
            {{ UserHelper::SubmitHidden() }}
            
        </form>
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