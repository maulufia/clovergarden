@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'D4';
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

  $nMClover   = new MCloverClass(); //스폰서

  //======================== DB Module MClovert ============================
  $Conn = new DBClass();

  $nMClover->read_result = $Conn->AllList($nMClover->table_name, $nMClover, "*", "where seq ='".$seq."'", $nMClover->sub_sql, null);

  if(count($nMClover->read_result) != 0){
      $nMClover->VarList($nMClover->read_result, 0, array('comment'));
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
                <tr>
                    <th>업체명</th>
                    <td >{{ $nMClover->subject }}</td>
                </tr>

			          <tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content">{{ RepBr($nMClover->content) }}</td>
                </tr>
		        		<tr>
                    <th>이름</th>
                    <td colspan="3">{{ $nMClover->name }}</td>
                </tr>
                <tr>
                    <th>썸네일</th>
                    <td colspan="3">
                      <img src='/imgs/up_file/sponsor/{{ $nMClover->file_edit[1] }}' border='0' width='150px'>
                      <div style='padding-top:20px;padding-bottom:0px;'>
                      <a href="#">{{ $nMClover->file_real[1] }}</a>
                      <font color='gray'> ({{ $nMClover->file_byte[1] }})</font></div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/sponsor', array('item' => 'main_deans', 'seq' => $nMClover->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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