@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'E2';
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

  $nOnetoone   = new OnetooneClass(); //1:1문의

  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nOnetoone->read_result = $Conn->AllList($nOnetoone->table_name, $nOnetoone, "*", "where seq ='".$seq."'", $nOnetoone->sub_sql, null);

  if(count($nOnetoone->read_result) != 0){
      $nOnetoone->VarList($nOnetoone->read_result, 0, array('comment'));
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
                    <td>{{ $nOnetoone->subject }}</td>
					           <th>답변방법</th>
                    <td><?php $nOnetoone->ArrOnetoone($nOnetoone->receive, '', '', 'receive', 1) ?></td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td>{{ $nOnetoone->name }}</td>
					<th>작성일</th>
                    <td>{{ str_replace('-','.',$nOnetoone->reg_date) }}</td>
                </tr>
				<tr>
                    <th>연락처</th>
                    <td>{{ $nOnetoone->cell }}</td>
					<th>이메일</th>
                    <td>{{ $nOnetoone->email }}</td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content">{{ $nOnetoone->content }}</td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/customer', array('item' => 'qna', 'seq' => $nOnetoone->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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