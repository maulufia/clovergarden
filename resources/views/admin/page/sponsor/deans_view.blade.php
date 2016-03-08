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

  $nSponsorpeople   = new SponsorpeopleClass(); //스폰서

  //======================== DB Module Sponsorpeoplet ============================
  $Conn = new DBClass();

  $nSponsorpeople->read_result = $Conn->AllList($nSponsorpeople->table_name, $nSponsorpeople, "*", "where seq ='".$seq."'", $nSponsorpeople->sub_sql, null);

  if(count($nSponsorpeople->read_result) != 0){
      $nSponsorpeople->VarList($nSponsorpeople->read_result, 0, array('comment'));
  }else{
      $Conn->DisConnect();
      JsAlert(NO_DATA, 1, $list_link);
  }

  $Conn->DisConnect();
  //======================== DB Module End ===============================
?>
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

             <table class="bbs-view">
                <caption>{{ $content_txt }}</caption>
                 <colgroup>
                    <col style="width:100px;" />
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
<?php
$r_comment2 = htmlspecialchars($nSponsorpeople->subject);
$r_comment2 = str_replace("\r\n","<BR>",$r_comment2);
$r_comment2 = str_replace("\n","<BR>",$r_comment2);
$r_comment2 = str_replace("&lt;br&gt;","<BR>",$r_comment2);
$r_comment2 = stripslashes($r_comment2);
?>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><?php echo $r_comment2; ?></td>
                </tr>
                <tr>
                    <th>이미지</th>
                    <td colspan="3">
                      @if(!empty($nSponsorpeople->file_edit[1]))
                        <img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[1] }}' border='0' width='150px'>
                        <div style='padding-top:20px;padding-bottom:0px;'>
                        <a href="#">{{ $nSponsorpeople->file_real[1] }}</a>
                        <font color='gray'> ({{ $nSponsorpeople->file_byte[1] }})</font></div>
                      @endif
                    </td>
                </tr>
				<tr>
                    <th>상단배너</th>
                    <td colspan="3">
                      @if(!empty($nSponsorpeople->file_edit[2]))
                        <img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[2] }}' border='0' width='150px'>
                        <div style='padding-top:20px;padding-bottom:0px;'>
                        <a href="#">{{ $nSponsorpeople->file_real[2] }}</a>
                        <font color='gray'> ({{ $nSponsorpeople->file_byte[2] }})</font></div>
                      @endif
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content">{!! $nSponsorpeople->content !!}</td>
                </tr>

                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/sponsor', array('item' => 'deans', 'seq' => $nSponsorpeople->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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