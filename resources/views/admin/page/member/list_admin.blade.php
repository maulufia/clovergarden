@extends('admin.common.husk')

@section('content')
  <?php
  // initiate (공통)
  $page_key   = 'A1';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

  $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nMember = new MemberClass(); //회원
  $nMoney = new MoneyClass(); //

  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nMember->where = " where user_state = '1' or user_state = '10' ";

  $nMember->total_record = $Conn->PageListCount
  (
  $nMember->table_name, $nMember->where, $search_key, $search_val
);

$nMember->page_result = $Conn->PageList
(
$nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by id desc', $nMember->sub_sql, $page_no, $nMember->page_view
);

$nMoney->read_result = $Conn->AllList($nMoney->table_name, $nMoney, "*", "where seq ='1'", $nMoney->sub_sql, null);

if(count($nMoney->read_result) != 0){
  $nMoney->VarList($nMoney->read_result, 0, array('comment'));
}

$money_update = isset($_GET['money_update']) ? $_GET['money_update'] : '';
if($money_update == "update"){
  $update_sql = "update new_tb_money set today='".$_GET['today']."', month='".$_GET['month']."', master_key='".$_GET['master_key']."' where seq='1'";
  mysql_query($update_sql);
  echo "
  <script>
  alert('기부금액 및 마스터키가 적용되었습니다.');
  window.location = '/admin/member?item=list_admin';
  </script>
  ";
  // Redirect 필요
}
$Conn->DisConnect();
//======================== DB Module End ===============================

$search_val = ReXssChk($search_val);
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
        <div class="bbs-search">
          <form name="frm" method="post" action="{{ $list_link }}" style="display:inline">
            <?php $nMember->ArrMember(null, "name='search_key'", null, 'search') ?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="search_val" value="{{ $search_val }}"/>
            <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
          </form>
        </div>

        <form method="get" action="{{ route('admin/member', array('item' => 'list_admin')) }}">
          <input type="hidden" name="money_update" value="update">
          <table cellpadding=10>
            <tr height=30px>
              <td><b>누적 기부 금액</b></td>
              <td><input type="text" name="month" value="{{ $nMoney->month }}">원</td>
              <td width=50></td>
              <td><b>기관 전달 금액</b></td>
              <td><input type="text" name="today" value="{{ $nMoney->today }}">원</td>
              <td width=50></td>
              <td><b>마스터키</b></td>
              <td><input type="text" name="master_key" value="{{ $nMoney->master_key }}">원</td>
              <td><input type="submit" value="적용"></td>
            </tr>
          </table>
        </form>

        <form id="send_frm" name="send_frm" method="post" style="display:inline;">
          <table class="bbs-list">
            <caption>{{ $content_txt }}</caption>
            <colgroup>
              <col style="width:50px;" />
              <col width="400px" />
              <col  />
              <col style="width:100px;" />
            </colgroup>
            <thead>
              <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>이름</th>
                <th>가입일</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(count($nMember->page_result) > 0){
                $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
                for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
                  $nMember->VarList($nMember->page_result, $i, null);
                  ?>
                  <tr>
                    <td>{{ $row_no }}</td>
                    <td> <a href="javascript:pageLink('{{ $nMember->seq }}','{{ $row_no }}','','{{ $view_link }}');">{{ $nMember->user_id }}</a></td>
                    <td>{{ $nMember->user_name }}</td>
                    <td>{{ str_replace('-','.',substr($nMember->reg_date,0,10)) }}</td>
                  </tr>
                  <?php
                  $row_no = $row_no - 1;
                }
              }else{
                ?>
                <tr>
                  <td colspan="4">{{ NO_DATA }}</td>
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
          if($nMember->total_record != 0){
            $nPage = new PageOut();
            $nPage->AdminPageList($nMember->total_record, $page_no, $nMember->page_view, $nMember->page_set, $nMember->page_where, 'pageNumber');
          }
          ?>
        </div>
        <div class="btn-area tmargin">
          <div class="fleft ">
            <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
          </div>
          <div class="fright">
            <a href="{{ route('admin/member', array('item' => 'list_admin', 'type' => 'write')) }}"><img src="/imgs/admin/images/btn_write.gif" alt="writing" /></a>
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
