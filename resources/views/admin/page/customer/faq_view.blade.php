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

  $nFaq   = new FaqClass(); //자주묻는질문

  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nFaq->read_result = $Conn->AllList($nFaq->table_name, $nFaq, "*", "where seq ='".$seq."'", $nFaq->sub_sql, null);

  if(count($nFaq->read_result) != 0){
      $nFaq->VarList($nFaq->read_result, 0, array('comment'));
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
                    <th>제목</th>
                    <td>{{ $nFaq->subject }}</td>
					           <th>조회수</th>
                    <td>{{ $nFaq->hit }}</td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td>{{ $nFaq->writer_name }}</td>
					           <th>작성일</th>
                    <td>{{ str_replace('-','.',$nFaq->reg_date) }}</td>
                </tr>
			           	<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content"><?php echo $nFaq->content; ?></td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/customer', array('item' => 'faq', 'seq' => $nFaq->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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