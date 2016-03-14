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
  
  $nMClover   = new MCloverClass();

  //======================== DB Module MClovert ============================
  $Conn = new DBClass();

  $nMClover->total_record = $Conn->PageListCount
  (
      $nMClover->table_name, $nMClover->where, $search_key, $search_val
  );

  $nMClover->page_result = $Conn->PageList
  (
      $nMClover->table_name, $nMClover, $nMClover->where, $search_key, $search_val, 'order by seq desc', $nMClover->sub_sql, $page_no, $nMClover->admin_page_view, array('comment')
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
                    <?php $nMClover->ArrMClover(null, "name='search_key'", null, 'search') ?>
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
                    <col />
                    <col style="width:80px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					          <th>선택</th>
                    <th>번호</th>
					          <th>썸네일</th>
                    <th>업체명</th>
                    <th>조회수</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nMClover->page_result) > 0){
        $row_no = $nMClover->total_record - ($nMClover->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMClover->page_result); $i < $cnt_list; $i++) {
            $nMClover->VarList($nMClover->page_result, $i, array('comment'));
?>
                <tr>
        					<td><input type="checkbox" name="delete_seq[]" value="{{ $nMClover->seq }}" /></td>
                            <td>{{ $row_no }}</td>
        					<td>
        					  <img src='/imgs/up_file/sponsor/{{ $nMClover->file_edit[1] }}' border='0'>
        					</td>
                    <td class="subject">
                        <a href="javascript:pageLink('{{ $nMClover->seq }}','{{ $row_no }}','view','{{ $view_link }}');">{{ $nMClover->subject }}</a>
                    </td>
					          <td>{{ $nMClover->hit or null }}</td>
                    <td>{{ str_replace('-','.',substr($nMClover->reg_date,0,10)) }}</td>                    
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
                if($nMClover->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nMClover->total_record, $page_no, $nMClover->admin_page_view, $nMClover->page_set, $nMClover->page_where, 'pageNumber');
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