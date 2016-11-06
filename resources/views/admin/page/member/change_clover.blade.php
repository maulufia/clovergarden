@extends('admin.common.husk')

@section('content')
  <?php
  // initiate (공통)
  $page_key   = 'A9';
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
  $nClover = new CloverClass(); //

  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nClover->page_view = 10000;
  $nClover->total_record = $Conn->PageListCount
  (
    $nClover->table_name, $nClover->where, $search_key, $search_val
  );

  $nClover->page_result = $Conn->PageList
  (
    $nClover->table_name, $nClover, $nClover->where, null, null, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->page_view, null
  );

  $clover_name = null;
  if(count($nClover->page_result) > 0){
    $row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
    for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
      $nClover->VarList($nClover->page_result, $i,  array('comment'));
      $clover_name[$nClover->code] = $nClover->subject;
    }
  }

  $nMember->where = " where clover_seq_adm != ''";

  $nMember->total_record = $Conn->PageListCount
  (
  $nMember->table_name, $nMember->where, $search_key, $search_val
  );

  $nMember->page_result = $Conn->PageList
  (
  $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by clover_seq_adm_type desc', $nMember->sub_sql, $page_no, $nMember->page_view
  );

$mod_mode = isset($_GET['mod_mode']) ? $_GET['mod_mode'] : null;
if($mod_mode == "modify_seq"){
  $sql = "update ".$nMember->table_name." set clover_seq_adm_type='".$_GET['modify_v']."' where id='".$_GET['mod_seq']."'";
  mysql_query($sql);
  echo "
  <script>
  alert('정보가 수정되었습니다.');
  window.location='/admin/member?item=change_clover';
  </script>
  ";
}

$mclover_update_type = isset($_GET['mclover_update_type']) ? $_GET['mclover_update_type'] : null;
if($mclover_update_type == "delete"){
  $sql = "update ".$nMember->table_name." set clover_seq='', clover_seq_adm='', clover_seq_adm_type='' where id='".$_GET['seq']."'";
  mysql_query($sql);
  echo "
  <script>
  alert('정보가 삭제되었습니다.');
  window.location='/admin/member?item=change_clover';
  </script>
  ";
}

$Conn->DisConnect();
//======================== DB Module End ===============================
$search_val = ReXssChk($search_val)
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
          <form name="frm" method="post" action="{{ route('admin/member', array('item' => 'change_clover')) }}" style="display:inline">
            <?php $nMember->ArrMember(null, "name='search_key'", null, 'search') ?>
            <input type="text" name="search_val" value="{{ $search_val }}"/>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
          </form>
        </div>

        <form id="send_frm" name="send_frm" method="post" style="display:inline;">
          <table class="bbs-list">
            <caption>{{ $content_txt }}</caption>
            <colgroup>
              <col style="width:50px;" />
              <col style="width:100px;" />
              <col style="width:100px;" />
              <col style="width:200px;" />
              <col  />
            </colgroup>
            <thead>
              <tr>
                <th>번호</th>
                <th>날짜</th>
                <th>이름</th>
                <th>기존후원</th>
                <th>변경후원 요청</th>
                <th>처리상태</th>
                <th>삭제</th>
              </tr>
            </thead>
            <tbody>
              <?php


              if(count($nMember->page_result) > 0){

                $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
                for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
                  $nMember->VarList($nMember->page_result, $i, null);
                  $ex_clover_seq_adm = explode("[@@@@]",$nMember->clover_seq_adm);
                  $ex_date_or_type = explode("[@@]",$nMember->clover_seq_adm_type);
                  ?>
                  <tr>
                    <td>{{ $row_no }}</td>
                    <td>{{ date("Y-m-d",$ex_date_or_type[0]) }}</td>
                    <td>{{ $nMember->user_name }}</td>
                    <td>
                      <?php
                      $ex_seq_clover_1 = explode("[@@@]",$ex_clover_seq_adm[0]);
                      for($j=0; $j<count($ex_seq_clover_1); $j++){
                        $v_ex = explode("[@@]",$ex_seq_clover_1[$j]);
                        if(isset($v_ex[0]) && isset($v_ex[1])) {
                          echo $clover_name[$v_ex[0]]." ".number_format($v_ex[1])."원 <BR>";
                        }
                      }
                      ?>
                    </td>
                    <td>
                      <?php
                      $ex_seq_clover_1 = explode("[@@@]",$ex_clover_seq_adm[1]);
                      for($j=0; $j<count($ex_seq_clover_1); $j++){
                        $v_ex = explode("[@@]",$ex_seq_clover_1[$j]);
                        echo $clover_name[$v_ex[0]]." ".number_format($v_ex[1])."원 <BR>";
                      }
                      ?>
                    </td>
                    <td>
                      <?php
                      if(isset($ex_date_or_type[1]) && $ex_date_or_type[1] == "ok"){
                        echo "<a href='" . route('admin/member') . "?item=change_clover&mod_mode=modify_seq&modify_v=".$ex_date_or_type[0]."&mod_seq=".$nMember->seq."'><font color='blue'><B>처리</B><BR>(클릭시 미처리로 변경됨)</font></a>";
                      } else {
                        echo "<a href='" . route('admin/member') . "?item=change_clover&mod_mode=modify_seq&modify_v=".$ex_date_or_type[0]."[@@]ok&mod_seq=".$nMember->seq."'><font color='red'>미처리<BR>(클릭시 처리로 변경됨)</font></a>";
                      }
                      ?>
                    </td>
                    <td><input type="button" value="삭제" style="padding:5px;" onclick="if(confirm('삭제하시겠습니까?')){window.location = '{{ route('admin/member') }}?item=change_clover&seq={{ $nMember->seq }}&mclover_update_type=delete'; }"></td>
                  </tr>
                  <?php
                  $row_no = $row_no - 1;
                }
              } else {
                ?>
                <tr>
                  <td colspan="9">{{ NO_DATA }}</td>
                </tr>
                <?php
              }


              ?>
            </tbody>
          </table>
          {{ UserHelper::SubmitHidden() }}

          <table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
            <tr>
              <td align=center>
                <div class="paging-area">
                  <?php
                  if($nMember->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nMember->total_record, $page_no, $nMember->page_view, $nMember->page_set, $nMember->page_where, 'pageNumber');
                  }
                  ?>
                </div>

              </td>
              <!-- <td align=center width=10%><input type="button" value="소식지발송" onclick="check_form('select')" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td> -->
            </tr>
          </table>

        </form>


      </div>

      <script type="text/javascript">
      <!--
      function check_form(type){
        f = document.send_frm;
        if(type == "all"){
          var chk = document.getElementById("mailtoall");
          if(chk.checked == true){
            var chka = document.getElementsByName("mailtov[]");
            for (i=0; i<chka.length; i++)
            chka[i].checked = true;
          } else {
            var chka = document.getElementsByName("mailtov[]");
            for (i=0; i<chka.length; i++)
            chka[i].checked = false;
          }
        } else {
          var s_value = "";
          var s_n_value = "";
          var chka = document.getElementsByName("mailtov[]");
          var chkn = document.getElementsByName("mailtoname[]");
          for (i=0; i<chka.length; i++){
            if(chka[i].checked == true){
              if(s_value == ""){
                s_value = chka[i].value;
                s_n_value = chkn[i].value;
              } else {
                s_value = s_value +"[@@]"+ chka[i].value;
                s_n_value = s_n_value +"[@@]"+ chkn[i].value;
              }
            }
          }
          if(s_value == ""){
            alert("보내실 회원을 선택해주세요!");
            return;
          }
          document.form_submit_ck.s_email.value = s_value;
          document.form_submit_ck.s_name.value = s_n_value;
          document.form_submit_ck.submit();

        }
        return;
      }


      //-->
      </script>
      <form name="form_submit_ck" method="post" action="./m_04_email.php?cktype=member" style="display:inline">
        <input type="hidden" name="s_mode" value="ch_send">
        <input type="hidden" name="s_email" value="">
        <input type="hidden" name="s_name" value="">
      </form>
      <!-- //right_area -->
      <form name="form_submit" method="post" action="{{ route('admin/member')}}?item=change_clover" style="display:inline">
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
