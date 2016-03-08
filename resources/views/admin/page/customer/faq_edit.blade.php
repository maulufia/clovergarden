@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'E3';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
  
  $seq = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;
  $row_no = isset($_REQUEST['row_no']) ? $_REQUEST['row_no'] : 0;

  $nFaq = new FaqClass(); //자주묻는질문

  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nFaq->where = "where seq ='".$seq."'";
  $nFaq->read_result = $Conn->AllList($nFaq->table_name, $nFaq, "*", $nFaq->where, null, null);

  if(count($nFaq->read_result) != 0){
      $nFaq->VarList($nFaq->read_result, 0, null);
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
    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;

            if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
            if(formCheckSub(f.subject, "inj", "제목") == false){ return; }
	
			if(formCheckSub(f.content , "exp", "내용") == false){ return; }
            if(formCheckSub(f.content, "inj", "내용") == false){ return; }

            $.blockUI();
            f.action = "<?php echo $edit_link; ?>";
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
            <table class="bbs-write">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:100px;" />
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;" value="{{ $nFaq->subject }}"/>
                    </td>
                </tr>
				<tr>
                    <th>작성자</th>
                    <td colspan="3">
                        <input type="text" name="writer_name" style="width:100px;" value="{{ $nFaq->writer_name }}"/>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
					<textarea name="content">{{ $nFaq->content }}</textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            </form>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
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