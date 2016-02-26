@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'C6';
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

  $nClover   = new CloverClass();
	$nClovermlist   = new ClovermlistClass();

//======================== DB Module Clovert ============================
$Conn = new DBClass();
	$nClover->table_name = $nClover->table_name."_banner";
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
                    <th>제목</th>
                    <td colspan="3">{{ $nClover->subject }}</td>
                </tr>
                <tr>
                    <th>배너이미지</th>
                    <td colspan="3">
                      <img src='/imgs/up_file/clover/{{ $nClover->file_edit[1] }}' border='0' width='150px'>
                      <div style='padding-top:20px;padding-bottom:0px;'>
                      <a href="#">{{ $nClover->file_real[1] }}</a>
                      <font color='gray'> ({{ $nClover->file_byte[1] }})</font></div>
                    </td>
                </tr>

				<tr>
                    <th>이미지URL</th>
                    <td colspan="3">
                    {{ $nClover->group_name }}
                    </td>
                </tr>
				<tr>
                    <th>참여하기URL</th>
                    <td colspan="3">
                    {{ $nClover->news }}
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ route('admin/clover', array('item' => 'banner')) }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/clover', array('item' => 'banner', 'seq' => $nClover->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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
