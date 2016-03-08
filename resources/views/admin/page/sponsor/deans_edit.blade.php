@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'D2';
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

  $nSponsorpeople = new SponsorpeopleClass(); //후원기관

  //======================== DB Module Sponsorpeoplet ============================
  $Conn = new DBClass();

  $nSponsorpeople->where = "where seq ='".$seq."'";
  $nSponsorpeople->read_result = $Conn->AllList($nSponsorpeople->table_name, $nSponsorpeople, "*", $nSponsorpeople->where, null, null);

  if(count($nSponsorpeople->read_result) != 0){
      $nSponsorpeople->VarList($nSponsorpeople->read_result, 0, null);
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
                    <td colspan="2">
						<textarea name="subject" style="width:500px; height:50px;">{{ $nSponsorpeople->subject }}</textarea>
                        <!-- <input type="text" name="subject" style="width:500px;" value=""/> -->
                    </td>
                </tr>        
                <tr>
                    <th>이미지</th>
                    <td colspan="3">
                        <?php
                            if(!empty($nSponsorpeople->file_edit[1])){
                                echo "<img src='/imgs/up_file/sponsorpeople/".$nSponsorpeople->file_edit[1]."' border='0' width='150px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".">";
                                echo $nSponsorpeople->file_real[1]."</a><font color='gray'> (".$nSponsorpeople->file_byte[1].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile1" size="50" />
                        <?php
                            if(!empty($nSponsorpeople->file_edit[1])){
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">({{ $nSponsorpeople->file_mime_type[1] }} : {{ $nSponsorpeople->file_volume[1] }}{{ LOW_FILESIZE }})</span>
                    </td>
                </tr>
				<tr>
                    <th>상단배너</th>
                    <td colspan="3">
                        <?php
                            if(!empty($nSponsorpeople->file_edit[2])){
                                echo "<img src='/imgs/up_file/sponsorpeople/".$nSponsorpeople->file_edit[2]."' border='0' width='150px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".">";
                                echo $nSponsorpeople->file_real[2]."</a><font color='gray'> (".$nSponsorpeople->file_byte[2].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile2" size="50" />
                        <?php
                            if(!empty($nSponsorpeople->file_edit[2])){
                                echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">({{ $nSponsorpeople->file_mime_type[2] }} : {{ $nSponsorpeople->file_volume[2] }}{{ LOW_FILESIZE }})</span>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
					<textarea name="content"><?php echo $nSponsorpeople->content; ?></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            <input type="hidden" name="file_real1" value="{{ $nSponsorpeople->file_real[1] }}"/>
            <input type="hidden" name="file_edit1" value="{{ $nSponsorpeople->file_edit[1] }}"/>
            <input type="hidden" name="file_byte1" value="{{ $nSponsorpeople->file_byte[1] }}"/>
			      <input type="hidden" name="file_real2" value="{{ $nSponsorpeople->file_real[2] }}"/>
            <input type="hidden" name="file_edit2" value="{{ $nSponsorpeople->file_edit[2] }}"/>
            <input type="hidden" name="file_byte2" value="{{ $nSponsorpeople->file_byte[2] }}"/>
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