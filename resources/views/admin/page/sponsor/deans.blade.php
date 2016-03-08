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

  $nSponsorpeople   = new SponsorpeopleClass(); //후원기관

  //======================== DB Module Sponsorpeoplet ============================
  $Conn = new DBClass();

  $nSponsorpeople->total_record = $Conn->PageListCount
  (
      $nSponsorpeople->table_name, $nSponsorpeople->where, $search_key, $search_val
  );

  $nSponsorpeople->page_result = $Conn->PageList
  (
      $nSponsorpeople->table_name, $nSponsorpeople, $nSponsorpeople->where, $search_key, $search_val, 'order by seq desc', $nSponsorpeople->sub_sql, $page_no, $nSponsorpeople->admin_page_view, array('comment')
  );

  $Conn->DisConnect();
  //======================== DB Module End ===============================
  $search_val = ReXssChk($search_val);
?>
    <script language="javascript">

        function sendSubmit(pType)
        {
            var f = document.send_frm;
            if(pType == "delete"){
                if(confirm("선택한 항목을 삭제하시겠습니까?")){
                    if($("input[name='delete_seq[]']:checked").length == "0"){
                        alert("삭제 항목을 한개이상 체크해주세요.");
                        return;
                    }
                    f.action = "{!! $delete_link !!}";
                    f.submit();
                }else{
                    return;
                }
            }
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
            <div class="bbs-search" style="margin-bottom:0px;">
                <form name="frm" method="post" action="{{ $list_link }}" style="display:inline">
                    <?php $nSponsorpeople->ArrSponsorpeople(null, "name='search_key'", null, 'search') ?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" name="search_val" value="{{ $search_val }}"/>
                    <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <table class="bbs-list" style="margin:0px">
                <colgroup>
                    <col style="width:80px;" />
                    <col />
                </colgroup>
                <tr>
                    <td></td>
                    <td style="text-align:right;"><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
					<col style="width:50px;" />
                    <col style="width:50px;" />
					<col style="width:150px;" />
					<col style="width:200px;" />
                    <col />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
                    <th>번호</th>
					<th>썸네일</th>
					<th>상단배너</th>
                    <th>제목</th>                    
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nSponsorpeople->page_result) > 0){
        $row_no = $nSponsorpeople->total_record - ($nSponsorpeople->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nSponsorpeople->page_result); $i < $cnt_list; $i++) {
            $nSponsorpeople->VarList($nSponsorpeople->page_result, $i, array('comment'));
?>
                <tr>
					<td><input type="checkbox" name="delete_seq[]" value="{{ $nSponsorpeople->seq }}" /></td>
                    <td>{{ $row_no }}</td>
					<td>
            @if(!empty($nSponsorpeople->file_edit[1]))
              <img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[1] }}' border='0' width='130px'>
            @endif
					</td>
					<td>
					@if(!empty($nSponsorpeople->file_edit[2]))
            <img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[2] }}' border='0' width='180px'>
          @endif
					</td>
<?php
$r_comment2 = htmlspecialchars($nSponsorpeople->subject);
$r_comment2 = str_replace("\r\n","<BR>",$r_comment2);
$r_comment2 = str_replace("\n","<BR>",$r_comment2);
$r_comment2 = str_replace("&lt;br&gt;","<BR>",$r_comment2);
$r_comment2 = stripslashes($r_comment2);
?>

                    <td class="subject">
                        <a href="javascript:pageLink('{{ $nSponsorpeople->seq }}','{{ $row_no }}','view','{{ $view_link }}');"><?php echo $r_comment2; ?></a>
                    </td>
                    <td>{{ str_replace('-','.',substr($nSponsorpeople->reg_date,0,10)) }}</td>                    
                </tr>
<?php
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="6">{{ NO_DATA }}</td>
                </tr>
<?php
    }
?>
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}  
            </form>
            <div class="paging-area">
            <?php
                if($nSponsorpeople->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nSponsorpeople->total_record, $page_no, $nSponsorpeople->admin_page_view, $nSponsorpeople->page_set, $nSponsorpeople->page_where, 'pageNumber');
                }
            ?>
            </div>
            <div class="btn-area tmargin">
                <div class="fleft ">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ $write_link }}"><img src="/imgs/admin/images/btn_write.gif" alt="writing" /></a>
                </div>
            </div>
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
            {{ UserHelper::SubmitHidden() }}     
        </form>
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