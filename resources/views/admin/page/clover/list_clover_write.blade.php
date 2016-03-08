@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'C1';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

  $seq = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;
  $row_no = isset($_REQUEST['row_no']) ? $_REQUEST['row_no'] : 0;
  
  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nClover = new CloverClass();
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
<script language="javascript">

	function sendSubmit()
	{
		var f = document.frm;

		$.blockUI();
		f.action = "{!! $write_link !!}";
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
                    <th>기관명</th>
                    <td>
                        <input type="text" name="subject" style="width:100px;"/>
                    </td>
					<th>구분</th>
                    <td>
                        <?php $nClover->ArrClover(null, "name='category'", null, 'category') ?>
                    </td>
                </tr>
				<tr>
					<th>고유코드</th>
                    <td colspan="3">
                       <input type="text" name="code" style="width:100px;"/>
                    </td>
                </tr>
				<tr>
					<th>온도계설정</th>
                    <td colspan="3">
						<select name="hot">
							<option value="1" selected>1도
							<option value="2">2도
							<option value="3">3도
						</select>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3">
                    <textarea name="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>메인이미지</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">({{ $nClover->file_mime_type[1] }} : {{ $nClover->file_volume[1] }}{{ LOW_FILESIZE }})</span></td>
                </tr>
				<tr>
                    <th>내용이미지</th>
                    <td colspan="3"><input type="file" name="upfile2" size="50" /> <span class="lmits">({{ $nClover->file_mime_type[1] }} : {{ $nClover->file_volume[1] }}{{ LOW_FILESIZE }})</span></td>
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
