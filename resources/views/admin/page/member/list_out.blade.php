@extends('admin.common.husk')

@section('content')
<?php
    // initiate (공통)
    $page_key   = 'A8';
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

    //======================== DB Module Start ============================
    $Conn = new DBClass();

	$nMember->where = " where user_state = '-1'";

    $nMember->total_record = $Conn->PageListCount
    (
        $nMember->table_name, $nMember->where, $search_key, $search_val
    );

    $nMember->page_result = $Conn->PageList
    (
        $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by reg_date desc', $nMember->sub_sql, $page_no, $nMember->page_view
    );

$point_mode = isset($_POST['point_mode']) ? $_POST['point_mode'] : null;
if($point_mode == "ins"){
	$sql = "
	insert into new_tb_point set
		signdate = '".time()."',
		depth = '".$_POST['point_depth']."',
		inpoint = '".$_POST['point']."',
		userid = '".$_POST['point_mid']."'
	";
	mysql_query($sql);
	echo "
	<script>
	alert('포인트가 지급되었습니다.');
	window.location = '/admin/member?item=list_normal';
	</script>
	";

}

$ckmode = isset($_GET['ckmode']) ? $_GET['ckmode'] : null;
if($ckmode == "member_delete"){
	$sql = "delete from ".$nMember->table_name." where id='".$_GET['seq']."' and user_id='".$_GET['mid']."' ";
	mysql_query($sql);
	echo "
	<script>
	alert('탈퇴회원이 삭제되었습니다.');
	window.location = '/admin/member?item=list_out';
	</script>
	";
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
                    {{ $nMember->ArrMember(null, "name='search_key'", null, 'search') }}
                    <input type="hidden" name="item" value="list_out" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" name="search_val" value="{{ $search_val }}"/>
                    <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption>{{ $content_txt }}></caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col  />
                    <col style="width:100px;" />
					<col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:120px;" />
                    <col style="width:100px;" />
                    <col style="width:80px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>아이디</th>
                    <th>이름</th>
					<th>지역</th>
					<th>소속정보</th>
					<th>연락처</th>
                    <th>가입일</th>
                    <th>삭제</th>
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
                    <td><?php if($nMember->user_state==-1) echo "<font style='color:red'>[탈퇴회원]</font>"; ?><a href="{{ route('admin/member', array('item' => 'list_normal', 'seq' => $nMember->seq, 'row_no' => $row_no, 'type' => 'edit')) }}">{{ $nMember->user_id }}</a></td>					
                    <td>{{ $nMember->user_name }}</td>
					<td>
                    <?php
                        if(isset($nMember->user_addr)) {
                            if($nMember->user_addr != null) $nMember->ArrMember($nMember->user_addr, null, null, 'city', 1);
                        }
                    ?>
                    </td>
					<td>{{ $nMember->group_name }}</td>
                    <td>{{ $nMember->user_cell }}</td>
                    <td>{{ str_replace('-','.',substr($nMember->reg_date,0,10)) }}</td>
                    <td>
					<input type="button" value="삭제" onclick="dell_func('{{ $nMember->user_id }}','{{ $nMember->seq }}');">
					</td>
                </tr>
<?php
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="10">{{ NO_DATA }}</td>
                </tr>
<?php
    }
?>
                </tbody>
            </table>

            {{ UserHelper::SubmitHidden() }}
            </form>

<form method="post" action="{{ route('admin/member', array('item' => 'list_out')) }}" name="point_form">
	<input type="hidden" name="point_mode" value="ins">
	<input type="hidden" id="point" name="point" value="">
	<input type="hidden" id="point_mid" name="point_mid" value="">
	<input type="hidden" name="point_depth" value="관리자지급">
</form>
<script type="text/javascript">
<!--

function dell_func(mid, seq){
	if(confirm('삭제하시겠습니까?')){
		window.location = '{{ route("admin/member") }}?item=list_out&mid='+mid+'&seq='+seq+'&ckmode=member_delete';
	}
}
//-->
</script>

			<div class="paging-area">
            <?php
                if($nMember->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nMember->total_record, $page_no, $nMember->page_view, $nMember->page_set, $nMember->page_where, 'pageNumber');
                }
            ?>
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