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

  $nClover   = new CloverClass(); //성형갤러리
  $nClovermlist   = new ClovermlistClass(); //성형갤러리

  //======================== DB Module Clovert ============================
  $Conn = new DBClass();

  $nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", "where seq ='".$seq."'", $nClover->sub_sql, null);

  if(count($nClover->read_result) != 0){
    $nClover->VarList($nClover->read_result, 0, array('comment'));
  }else{
    $Conn->DisConnect();
    JsAlert(NO_DATA, 1, $list_link);
  }

  $nClovermlist->where = "where clover_seq='".$nClover->code."'";
  $nClovermlist->total_record = $Conn->PageListCount
  (
  $nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
);

$nClovermlist->page_result = $Conn->AllList
(
$nClovermlist->table_name, $nClovermlist, "*", "where 1 order by seq desc limit ".$nClovermlist->total_record."", null, null
);

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
            <tr>
              <th>기관명 (후원자)</th>
              <td colspan="3">{{ $nClover->subject }} ({{ number_format($nClovermlist->total_record) }}명)</td>
            </tr>
            <tr>
              <th>온도계</th>
              <td colspan="3">{{ $nClover->hot }}도</td>
            </tr>
            <tr>
              <th>후원자</th>
              <td colspan="3">
                <h2 style="margin-bottom:5px; font-size:14px;">정기후원자</h2>
                <table>
                  <colgroup>
                    <col style="width:50px;" />
                    <col style="width:100px;" />
                    <col style="width:100px;" />
                    <col />
                    <col style="width:100px;" />
                    <col style="width:100px;" />
                  </colgroup>
                  <thead>
                    <tr>
                      <th>번호</th>
                      <th>그룹명</th>
                      <th>이름</th>
                      <th>아이디</th>
                      <th>금액</th>
                      <th>결제일</th>
                    </tr>
                  </thead>
                  <?php
                  if(count($nClovermlist->page_result) > 0){
                    for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
                      $nClovermlist->VarList($nClovermlist->page_result, $i, null);
                      ?>
                      <tr>
                        <td>{{ $i+1 }}</td>
                        <td>
                          {{ $nClovermlist->group_name }}
                        </td>
                        <td>
                          {{ $nClovermlist->name }}
                        </td>
                        <td>
                          {{ $nClovermlist->id }}
                        </td>
                        <td>
                          {{ number_format($nClovermlist->price) }}원
                        </td>
                        <td>
                          매월 {{ sprintf('%02d',$nClovermlist->day) }}일
                        </td>
                      </tr>
                      <?php

                    }
                  }else{
                    ?>

                    <?php
                  }
                  ?>
                </table>
              </td>
            </tr>
            <tr>
              <th style="vertical-align:middle;">기관소개</th>
              <td colspan="3" class="content"><?php echo $nClover->content; ?></td>
            </tr>
            <tr>
              <th>메인이미지</th>
              <td colspan="3">
                <img src='/imgs/up_file/clover/{{ $nClover->file_edit[1] }}' border='0' width='150px'>
                <div style='padding-top:20px;padding-bottom:0px;'>
                  <a href="/imgs/up_file/clover/{{ $nClover->file_edit[1] }}" download>
                    {{ $nClover->file_real[1] }}</a>
                    <font color='gray'> ({{ $nClover->file_byte[1] }})</font>
                  </div>
                </td>
              </tr>

              <tr>
                <th>내용이미지</th>
                <td colspan="3">
                  <img src='/imgs/up_file/clover/{{ $nClover->file_edit[2] }}' border='0' width='300px'>
                  <div style='padding-top:20px;padding-bottom:0px;'>
                    <a href="/imgs/up_file/clover/{{ $nClover->file_edit[2] }}" download>
                    {{ $nClover->file_real[2] }}</a>
                    <font color='gray'> ({{ $nClover->file_byte[2] }})</font>
                  </div>
                </td>
                </tr>

                <tr>
                  <th>[모바일]<br />썸네일이미지</th>
                  <td colspan="3">
                    <img src='{{ $nClover->mobile_thumbnail }}' border='0' width='300px'>
                  </td>
                </tr>

                <tr>
                  <th>[모바일]<br />상단 이미지</th>
                  <td colspan="3">
                    <img src='{{ $nClover->mobile_image1 }}' border='0' width='300px'>
                  </td>
                </tr>

                <tr>
                  <th>[모바일]<br />내용 이미지 1</th>
                  <td colspan="3">
                    <img src='{{ $nClover->mobile_image2 }}' border='0' width='300px'>
                  </td>
                </tr>

                <tr>
                  <th>[모바일]<br />내용 이미지 2</th>
                  <td colspan="3">
                    <img src='{{ $nClover->mobile_image3 }}' border='0' width='300px'>
                  </td>
                </tr>

                <tr>
                  <th>[모바일]<br />소개글</th>
                  <td colspan="3">
                    <p>
                      <textarea readonly style="width: 600px; height: 200px;">{{ $nClover->mobile_intro }}</textarea>
                    </p>
                  </td>
                </tr>

              </tbody>
            </table>
            <div class="btn-area">
              <div class="fleft">
                <a href="javascript:pageLink('','','','');"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
              </div>
              <div class="fright">
                <a href="{{ route('admin/clover', array('item' => 'list_clover', 'seq' => $nClover->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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
