@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'A2';
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
  $nMember_win = new MemberClass(); //회원
	$nPoint_sum = new PointClass(); //회원

	//======================== DB Module Start ============================
	$Conn = new DBClass();

  $nMember->where = " where (user_state = '2' or user_state='-1' or user_state='5') and clover_win='S'";

  $nMember->total_record = $Conn->PageListCount
  (
      $nMember->table_name, $nMember->where, $search_key, $search_val
  );
  
  $ck_clover_win = null;
	if($nMember->total_record >= 4){
		$ck_clover_win = "S";
	}

  $nMember->where = " where (user_state = '2' or user_state='-1' or user_state='5')";

  $nMember->total_record = $Conn->PageListCount
  (
      $nMember->table_name, $nMember->where, $search_key, $search_val
  );

  $nMember->page_result = $Conn->PageList
  (
      $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by id desc', $nMember->sub_sql, $page_no, $nMember->page_view
  );

  $nMember_win->where = " where clover_win='S'";

  $nMember_win->total_record = $Conn->PageListCount
  (
      $nMember_win->table_name, $nMember_win->where, $search_key, $search_val
  );

  $nMember_win->page_result = $Conn->PageList
  (
      $nMember_win->table_name, $nMember_win, $nMember_win->where, $search_key, $search_val, 'order by reg_date desc', $nMember_win->sub_sql, $page_no, $nMember_win->page_view
  );

$mmode = isset($_GET['mmode']) ? $_GET['mmode'] : null;
if($mmode == "cwmode"){
	$sql = "update new_tb_member set clover_win='S' where id = '".$_GET['mseq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('기부왕이 선택되었습니다.');
	window.location = '/admin/member?item=list_normal';
	</script>

	";
}

if($mmode == "cdmode"){
	$sql = "update new_tb_member set clover_win='' where id = '".$_GET['mseq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('기부왕이 삭제되었습니다.');
	window.location = '/admin/member?item=list_normal';
	</script>

	";
}

$smode = isset($_GET['s_mode']) ? $_GET['s_mode'] : null;
if($smode == "ch_point"){

	$ck_email = explode("[@@]",$_GET['s_email']);
	$ck_name = explode("[@@]",$_GET['s_name']);
	

	$ck_send_price = $_GET['point_price']*count($ck_email);

/*
	if($ck_send_price > $_GET[inpoint_price]){
		echo "
		<script>
		alert('보유하신 포인트가 부족합니다.');
		window.location='/page.php?cate=6&dep01=7&dep02=0';
		</script>
		";
	}
*/
	for($p = 0; $p < count($ck_email); $p++){

		$sql = "
		insert into new_tb_point set
			signdate = '".time()."',
			depth = '".$_GET['point_depth']."',
			inpoint = '".$_GET['point_price']."',
			userid = '".$ck_email[$p]."'
		";
		mysql_query($sql);
	}
	echo "
	<script>
	alert('포인트가 지급되었습니다.');
	window.location = '/admin/member?item=list_normal';
	</script>
	";
}

	$Conn->DisConnect();
	//======================== DB Module End ===============================
  $search_val = ReXssChk($search_val);
?>
</head>
<body>
<script language="javascript">

function excel(){
$("#excel").click();
};

function excel2(){
$("#excel2").click();
};

</script>
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
            <input type="hidden" name="item" value="list_normal" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="search_val" value="{{ $search_val }}"/>
            <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
        </form>
				<form name="excel" method="post" action="{{ $excel_link }}" enctype="multipart/form-data" style="display:inline;">
					<input type="file" name="excel" id="excel" style="display:none;" onchange="submit();">
					{{ UserHelper::SubmitHidden() }}
				</form>

				<form name="excel2" method="post" action="{{ $excel_link2 }}" enctype="multipart/form-data" style="display:inline;">
					<input type="file" name="excel2" id="excel2" style="display:none;" onchange="submit();">
					{{ UserHelper::SubmitHidden() }}
				</form>

				<a href="javascript:excel();" style="padding:0 10px; background:#fff; height:20px; line-height:20px; float:right; border:1px solid #dbdbdb;">일괄등록</a> 

				<!-- <a href="javascript:excel2();" style="padding:0 10px; background:#fff; height:20px; line-height:20px; float:right; border:1px solid #dbdbdb;">회원 업로드</a>  -->
            </div>

			<table cellpadding=0 cellspacing=0 border=0 width=97% align=center>
			<tr height=30>
				<td style="font-size:15px;">
				<strong>● 이달의 기부왕</strong>
				</td>
			</tr>
			</table>
			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr>
<?php
    if(count($nMember_win->page_result) > 0){
        $row_no = $nMember_win->total_record - ($nMember_win->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMember_win->page_result); $i < $cnt_list; $i++) {
            $nMember_win->VarList($nMember_win->page_result, $i, null);
?>
				<td>
					<table cellpadding=0 cellspacing=0 border=0 width=90% align=center style="border:1px solid #e8e8e8;">
					<tr height=30>
						<td align=center>{{ $nMember_win->user_name }}</td>
					</tr>
					<tr height=30>
						<td align=center>{{ $nMember_win->user_id }}</td>
					</tr>
					<tr height=30>
						<td align=center><input type="button" value="삭제" onclick="window.location='{{ route('admin/member')}}?item=list_normal&mmode=cdmode&mseq={{ $nMember_win->seq }}';" style="border:1px solid #e8e8e8; padding:3px;background:#3952a8; font-weight:bold; color:#fff;"></td>
					</tr>
					</table>
				</td>
<?php
            $row_no = $row_no - 1;
        }
    }else{
?>
				<td>
					<table cellpadding=0 cellspacing=0 border=0 width=90% align=center style="border:1px solid #e8e8e8;">
					<tr height=100>
						<td align=center>선택된 기부왕이 없습니다.</td>
					</tr>
					</table>
				</td>
<?php
    }
?>

			</tr>
			</table>
			<br>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col style="width:50px;" />
                    <col  />
                    <col style="width:100px;" />
					<col style="width:80px;" />
                    <col style="width:80px;" />
					<col style="width:120px;" />
                    <col style="width:120px;" />
                    <col style="width:100px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" name="mailtoall" id="mailtoall" onclick="check_form('all')"></th>
                    <th>번호</th>
                    <th>아이디</th>
                    <th>이름</th>
					<th>지역</th>
					<th>소속정보</th>
					<th>연락처</th>
                    <th>보유포인트</th>
                    <th>가입일</th>
                    <th>기부왕선택</th>
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
			$nPoint_sum->VarList($nPoint_sum->page_result, 0, null);

			$use_point = $nPoint_sum->inpoint - $nPoint_sum->outpoint;
?>
                <tr <?php if($nMember->update_ck == 'Y'){ ?>bgcolor="eeeeee"<?php } ?>>
                    <td>
					<input type="checkbox" name="mailtov[]" id="mailtov{{ $i }}" value="{{ $nMember->user_id }}">
					<input type="hidden" name="mailtoname[]" id="mailtoname{{ $i }}" value="{{ $nMember->user_name }}">					
					</td>
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
					<td>{{ $use_point }}</td>
          <td>{{ str_replace('-','.',substr($nMember->reg_date,0,10)) }}</td>
          <td>
					<?php if($ck_clover_win == "S"){ ?>
						<?php if($nMember->clover_win == "S"){ ?>
						선택된회원
						<?php } else { ?>
						<input type="button" value="선택완료" onclick="alert('기부왕이 모두 찾습니다. 삭제 후 이용해주세요.');" style="border:1px solid #e8e8e8; padding:3px;background:#fd4f00; font-weight:bold; color:#fff;">
						<?php } ?>
						
					<?php } else { ?>
						<?php if($nMember->clover_win == "S"){ ?>
						선택된회원
						<?php } else { ?>
						<input type="button" value="선택" onclick="window.location='{{ route('admin/member')}}?item=list_normal&mmode=cwmode&mseq={{ $nMember->seq }}';" style="border:1px solid #e8e8e8; padding:3px;background:#3952a8; font-weight:bold; color:#fff;">
						<?php } ?>
					<?php } ?>
					</td>
                </tr>
<?php
            $row_no = $row_no - 1;
        }
    }else{
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
				<td align=center width=10%><input type="button" value="엑셀다운로드" onclick="window.location='{{ route('admin/member')}}?item=list_normal&type=exec_down';" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
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
				<td align=center width=20%>
				<input type="text" id="point_auto" value="" name="mpoint" size="9">
				<input type="hidden" id="in_point" value="{{ $use_point }}" name="inpoint" size="9">
				<input type="button" value="포인트지급" onclick="check_form('point')" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;">
				
				</td>
				<td align=center width=10%><input type="button" value="소식지발송" onclick="check_form('select')" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
				<td align=center width=10%><input type="button" value="회원삭제" onclick="check_form_del('select')" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
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
	} else if (type == "point"){
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
			alert("지급할 회원을 선택해주세요!");
			return;
		}

		var point_auto = document.getElementById("point_auto").value;
		var in_point = document.getElementById("in_point").value;
		if(point_auto == ""){
			alert("지급할 포인트를 입력해주세요!");
			return;
		}
		document.form_submit_point_ck.s_email.value = s_value;
		document.form_submit_point_ck.s_name.value = s_n_value;
		document.form_submit_point_ck.point_price.value = point_auto;
		document.form_submit_point_ck.inpoint_price.value = in_point;
		document.form_submit_point_ck.submit();
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

function check_form_del(type){
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
					s_value = s_value +","+ chka[i].value;
					s_n_value = s_n_value +","+ chkn[i].value;
				}
			}
		}
		if(s_value == ""){
			alert("삭제할 회원을 선택해주세요!");
			return;
		}
		document.form_submit_ckdel.s_email.value = s_value;
		document.form_submit_ckdel.s_name.value = s_n_value;
		document.form_submit_ckdel.submit();

	}
	return;
}

//-->
</script>

		<form name="form_submit_point_ck" method="get" action="{{ route('admin/member') }}" style="display:inline">
			<input type="hidden" name="item" value="list_normal">
			<input type="hidden" name="s_mode" value="ch_point">
			<input type="hidden" name="point_price" value="">
			<input type="hidden" name="inpoint_price" value="">
			<input type="hidden" name="s_email" value="">
			<input type="hidden" name="s_name" value="">
			<input type="hidden" name="point_depth" value="관리자 지급">
		</form>

        <form name="form_submit_ck" method="post" action="./m_04_email.php?cktype=member" style="display:inline">
            <input type="hidden" name="s_mode" value="ch_send">
			<input type="hidden" name="s_email" value="">
			<input type="hidden" name="s_name" value="">
        </form>
        <form name="form_submit_ckdel" method="post" action="{{ route('admin/member')}}?item=list_normal&type=del&cktype=member" style="display:inline">
          <input type="hidden" name="s_mode" value="ch_send">
					<input type="hidden" name="s_email" value="">
					<input type="hidden" name="s_name" value="">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
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