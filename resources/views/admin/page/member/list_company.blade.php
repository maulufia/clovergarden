@extends('admin.common.husk')

@section('content')
<?php
    // initiate (공통)
    $page_key   = 'A3';
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
		$nPoint_sum = new PointClass(); //회원

		//======================== DB Module Start ============================
		$Conn = new DBClass();

    $nMember->where = " where user_state = '4'";

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
	window.location = '/admin/member?item=list_company';
	</script>
	";

}

$ckmode = isset($_GET['ckmode']) ? $_GET['ckmode'] : null;
if($ckmode == "login_ok"){
	$sql = "update ".$nMember->table_name." set login_ck='y' where id='".$_GET['seq']."' and user_id='".$_GET['mid']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('기업회원이 승인되었습니다.');
	window.location = '/admin/member?item=list_company';
	</script>
	";
}
if($ckmode == "login_no"){
	$sql = "update ".$nMember->table_name." set login_ck='n' where id='".$_GET['seq']."' and user_id='".$_GET['mid']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('기업회원이 거절되었습니다.');
	window.location = '/admin/member?item=list_company';
	</script>
	";
}

if($ckmode == "member_delete"){
	$sql = "delete from ".$nMember->table_name." where seq='".$_GET['seq']."' and user_id='".$_GET['mid']."' ";
	mysql_query($sql);

	$sql = "delete from new_tb_clover_mlist where seq='".$_GET['seq']."' and id='".$_GET['mid']."' ";
	mysql_query($sql);

	$sql = "delete from new_tb_message where seq='".$_GET['seq']."' and send_id='".$_GET['mid']."' ";
	mysql_query($sql);

	$sql = "delete from new_tb_point where seq='".$_GET['seq']."' and userid='".$_GET['mid']."' ";
	mysql_query($sql);


	echo "
	<script>
	alert('기업회원이 삭제되었습니다.');
	window.location = '/admin/member?item=list_company';
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
                    <?php $nMember->ArrMember(null, "name='search_key'", null, 'search') ?>
                    <input type="hidden" name="item" value="list_company" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" name="search_val" value="{{ $search_val }}"/>
                    <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col  />
                    <col style="width:100px;" />
					<col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:120px;" />
                    <col style="width:100px;" />
                    <col style="width:100px;" />
                    <col style="width:150px;" />
                    <col style="width:100px;" />
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
                    <th>보유포인트</th>
                    <th>포인트</th>
                    <th>승인 / 삭제</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nMember->page_result) > 0){
        $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
            $nMember->VarList($nMember->page_result, $i, null);

			$Conn = new DBClass();

				$nPoint_sum->page_result = $Conn->AllList
				(	
					$nPoint_sum->table_name, $nPoint_sum, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='".$nMember->user_id."' group by userid", null, null
				);

			$Conn->DisConnect();

			if(count($nPoint_sum->page_result) > 0){
				for($ip=0, $cnt_listp=count($nPoint_sum->page_result); $ip < $cnt_listp; $ip++) {
					$nPoint_sum->VarList($nPoint_sum->page_result, $ip, null);

					$use_point = $nPoint_sum->inpoint - $nPoint_sum->outpoint;
				}
			} else {
					$use_point = 0;
			}
?>
                <tr>
                    <td>{{ $row_no }}</td>
                    <td><?php if($nMember->user_state==3) echo "<font style='color:red'>[탈퇴회원]</font>"; ?><a href="javascript:pageLink('{{ $nMember->seq }}','{{ $row_no }}','','{{ $edit_link }}');">{{ $nMember->user_id }}</a></td>					
                    <td>{{ $nMember->user_name }}</td>
					<td>
						<?php
							if(isset($nMember->user_addr)) {
								if($nMember->user_addr!=null) $nMember->ArrMember($nMember->user_addr, null, null, 'city', 1);
							}
						?>
					</td>
					<td>{{ $nMember->group_name }}</td>					<td>{{ $nMember->user_cell }}</td>
                    <td>{{ str_replace('-','.',substr($nMember->reg_date,0,10)) }}</td>
					<td>{{ number_format($use_point) }}원</td>
                    <td>
					<input type="text" id="point_{{ $row_no }}" value="" name="mpoint" size="9">
					<input type="button" value="적용" onclick="point_func('{{ $nMember->user_id }}','{{ $row_no }}');">
					</td>
					 <td>
					 <?php if($nMember->login_ck == 'y'){ ?>
					<input type="button" value="거절" onclick="login_ck_func('{{ $nMember->user_id }}','{{ $nMember->seq }}','login_no');">
					<?php } else { ?>
					<input type="button" value="승인" onclick="login_ck_func('{{ $nMember->user_id }}','{{ $nMember->seq }}','login_ok');">
					<?php } ?>
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

<form method="post" action="{{ $list_link }}" name="point_form">
	<input type="hidden" name="item" value="list_company" />
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	<input type="hidden" name="point_mode" value="ins">
	<input type="hidden" id="point" name="point" value="">
	<input type="hidden" id="point_mid" name="point_mid" value="">
	<input type="hidden" name="point_depth" value="관리자지급">
</form>
<script type="text/javascript">
<!--
function point_func(mid, point_v){
	var frm = document.point_form;
	var point_value = document.getElementById("point_"+point_v);

	if(point_value.value == ""){
		alert("지급할 포인트를 입력해주세요.");
		return;
	}

	
	var point_value_s = document.getElementById("point");
	var point_mid = document.getElementById("point_mid");

	point_value_s.value = point_value.value;
	point_mid.value = mid;

	frm.submit();
}

function login_ck_func(mid, seq, type){
	window.location = '{{ route("admin/member") }}?item=list_company&mid='+mid+'&seq='+seq+'&ckmode='+type;
}

function dell_func(mid, seq){
	if(confirm('삭제하시겠습니까?')){
		window.location = '{{ route("admin/member") }}?item=list_company&mid='+mid+'&seq='+seq+'&ckmode=member_delete';
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