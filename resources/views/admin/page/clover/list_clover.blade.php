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

  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nClover   = new CloverClass(); //후원기관

  //======================== DB Module Clovert ============================
  $Conn = new DBClass();

  $nClover->total_record = $Conn->PageListCount
  (
  $nClover->table_name, $nClover->where, $search_key, $search_val
);

$nClover->page_result = $Conn->PageList
(
$nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->admin_page_view, array('comment')
);

if(isset($_POST['order_seq'][0]) && $_POST['order_seq'][0]){
  for($i=0; $i<count($_POST['order_seq']); $i++){
    $sql_update = "update ".$nClover->table_name." set view_n='".$_POST['order_num'][$i]."' where seq='".$_POST['order_seq'][$i]."'";
    mysql_query($sql_update);
  }
  echo "<script>alert('정렬이 수정되었습니다.'); window.location='".$list_link."'; </script>";
}
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
      f.action = "<?php echo $delete_link; ?>";
      f.submit();
    }else{
      return;
    }
  } else {
    f.action = "<?php echo $list_link; ?>";
    f.submit();
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
            <?php $nClover->ArrClover(null, "name='search_key'", null, 'search') ?>
            <input type="text" name="search_val" value="{{ $search_val }}"/>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
          </form>
        </div>
        <table class="bbs-list" style="margin:0px">
          <colgroup>
            <col style="width:80px;" />
            <col />
          </colgroup>
          <tr>
            <td style="text-align:left;"><a href="javascript:sendSubmit('orderby')">[ 정렬수정 ]</a></td>
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
              <col style="width:50px;" />
              <col style="width:100px;" />
              <col style="width:150px;" />
              <col />
              <col style="width:80px;" />
              <col style="width:100px;" />
              <col style="width:50px;" />
            </colgroup>
            <thead>
              <tr>
                <th>선택</th>
                <th>정렬</th>
                <th>번호</th>
                <th>구분</th>
                <th>썸네일</th>
                <th>기관명(코드값)</th>
                <th>조회수</th>
                <th>작성일</th>
                <th>온도계</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(count($nClover->page_result) > 0){
                $row_no = $nClover->total_record - ($nClover->admin_page_view * ($page_no - 1));
                for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
                  $nClover->VarList($nClover->page_result, $i, array('comment'));
                  ?>
                  <tr>
                    <td><input type="checkbox" name="delete_seq[]" value="{{ $nClover->seq }}" /></td>
                    <td>
                      <input type="hidden" name="order_seq[]" value="{{ $nClover->seq }}" />
                      <input type="text" size="4" name="order_num[]" value="{{ $nClover->view_n }}" />
                    </td>
                    <td>{{ $row_no }}</td>
                    <td>{{ $nClover->ArrClover($nClover->category, null, null, 'category', 1) }}</td>
                    <td>
                      <img src='/imgs/up_file/clover/{{ $nClover->file_edit[1] }}' border='0' width='130px'>
                    </td>
                    <td class="subject">
                      <a href="{{ route('admin/clover', array('item' => 'list_clover', 'seq' => $nClover->seq, 'row_no' => $row_no, 'type' => 'view')) }}">{{ $nClover->subject }} (<font color='red'>{{ $nClover->code }}</font>)</a>
                    </td>
                    <td>{{ $nClover->hit }}</td>
                    <td>{{ str_replace('-','.',substr($nClover->reg_date,0,10)) }}</td>
                    <td>{{ $nClover->hot }}도</td>
                  </tr>
                  <?php
                  $row_no = $row_no - 1;
                }
              }else{
                ?>
                <tr>
                  <td colspan="7">{{ NO_DATA }}</td>
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
          if($nClover->total_record != 0){
            $nPage = new PageOut();
            $nPage->AdminPageList($nClover->total_record, $page_no, $nClover->admin_page_view, $nClover->page_set, $nClover->page_where, 'pageNumber');
          }
          ?>
        </div>
        <div class="btn-area tmargin">
          <div class="fleft ">
            <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
          </div>
          <div class="fright">
            <a href="{{ route('admin/clover', array('item' => 'list_clover', 'type' => 'write')) }}"><img src="/imgs/admin/images/btn_write.gif" alt="writing" /></a>
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
