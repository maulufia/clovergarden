@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'B3';
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

  $nBanner = new BannerClass(); //후원기관

  //======================== DB Module Bannert ============================
  $Conn = new DBClass();
   
  $nBanner->where = "where seq ='".$seq."'";
  $nBanner->read_result = $Conn->AllList($nBanner->table_name, $nBanner, "*", $nBanner->where, null, null);

  if(count($nBanner->read_result) != 0){
      $nBanner->VarList($nBanner->read_result, 0, null);
  }else{
      $Conn->DisConnect();
      JsAlert(NO_DATA, 1, $list_link);
  }

  $Conn->DisConnect();
  //======================== DB Module End ===============================
?>

    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;

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
                    <th>기관명</th>
                    <td colspan="2">
                        <input type="text" name="subject" style="width:100px;" value="{{ $nBanner->subject }}"/>
                    </td>
                </tr>        
				<tr>
                    <th>기관링크</th>
                    <td colspan="2">
                        <input type="text" name="url" style="width:300px;" value="{{ $nBanner->url }}"/>
                    </td>
                </tr>
                <tr>
                    <th>기관로고</th>
                    <td colspan="3">
                      <img src='/imgs/up_file/Banner/{{ $nBanner->file_edit[1] }}' border='0' width='150px'>
                      <div style='padding-top:20px;padding-bottom:0px;'>
                      <a href="#">{{ $nBanner->file_real[1] }}</a><font color='gray'> ({{ $nBanner->file_byte[1] }})</font></div>
                      <input type="file" name="upfile1" size="50" />
                      @if(!empty($nBanner->file_edit[1]))
                        <input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>
                      @else
                        <input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>
                      @endif
                      <span class="lmits">({{ $nBanner->file_mime_type[1] }} : {{ $nBanner->file_volume[1] }}{{ LOW_FILESIZE }})</span>
                    </td>
                </tr>

                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            <input type="hidden" name="file_real1" value="{{ $nBanner->file_real[1] }}"/>
            <input type="hidden" name="file_edit1" value="{{ $nBanner->file_edit[1] }}"/>
            <input type="hidden" name="file_byte1" value="{{ $nBanner->file_byte[1] }}"/>
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