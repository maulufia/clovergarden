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

  $seq        = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;
  $row_no     = isset($_GET['row_no']) ? $_GET['row_no'] : 0;

  $nMember = new MemberClass(); //회원
  $nMember_groupname = new MemberClass(); //회원
	$nClovermlist_login = new ClovermlistClass(); //후원목록
	$nClovermlist_login_b = new ClovermlistClass(); //후원목록
	$nClovermlist_login_ing = new ClovermlistClass(); //후원목록
	$nClovermlist = new ClovermlistClass(); //후원목록
	$nClover_m = new CloverClass(); //클로버목록

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->where = "where id ='".$seq."'";
    $nMember->read_result = $Conn->AllList
    (
        $nMember->table_name, $nMember, "*", $nMember->where, null, null
    );

    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }




	$nClover_m->read_result = $Conn->AllList
	(
		$nClover_m->table_name, $nClover_m, "*", $nClover_m->where, null, null
	);

	$nClover_m->page_result = $Conn->AllList
	(
		$nClover_m->table_name, $nClover_m, "*", "order by reg_date desc", null, null
	);


	$nMember_groupname->read_result = $Conn->AllList
	(
		$nMember_groupname->table_name, $nMember_groupname, "*", "where group_name != '' group by group_name order by id desc", null, null
	);

	$nMember_groupname->page_result = $Conn->AllList
	(
		$nMember_groupname->table_name, $nMember_groupname, "*", "where group_name != '' group by group_name order by id desc", null, null
	);

	$group_name_v = null;
	if(count($nMember_groupname->read_result) != 0){
		for($i=0, $cnt_list=count($nMember_groupname->page_result); $i < $cnt_list; $i++) {
			$nMember_groupname->VarList($nMember_groupname->page_result, $i, null);
			$group_name_v[$i] = $nMember_groupname->group_name;
		}
	}

	if(count($nClover_m->read_result) != 0){
		for($i=0, $cnt_list=count($nClover_m->page_result); $i < $cnt_list; $i++) {
			$nClover_m->VarList($nClover_m->page_result, $i, null);
			$clober_code[$i] = $nClover_m->code;
			$clober_name[$i] = $nClover_m->subject;
			$clober_name_scode[$nClover_m->code] = $nClover_m->subject;
		}
	}

	$sql = "update new_tb_member set update_ck='' where seq='".$seq."'";
	mysql_query($sql);

$clover_cencle = isset($_GET['clover_cencle']) ? $_GET['clover_cencle'] : null;
if($clover_cencle == "update"){
	$sql = "update ".$nClovermlist_login->table_name." set ing_cencle='".$_GET['value_t']."'where clover_seq='".$_GET['c_code']."' and id='".$_GET['mbid']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('정기후원이 해지되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$price_v = isset($_GET['price_v']) ? $_GET['price_v'] : null;
$clover_seq = isset($_GET['clover_seq']) ? $_GET['clover_seq'] : null;
if($price_v != "" && $_GET['clover_seq'] != ""){
	$sql = "update ".$nClovermlist_login->table_name." set price='" . $price_v . "', clover_seq='".$_GET['company_code']."', day='".$_GET['type_t']."' where seq='" . $clover_seq . "'";
	mysql_query($sql);
	echo "
	<script>
	alert('수정되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$dmode = isset($_GET['dmode']) ? $_GET['dmode'] : null;
if($dmode == "delmlist" && $_GET['clover_seq'] != ""){
	$sql = "delete from ".$nClovermlist_login->table_name." where seq='".$_GET['clover_seq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('삭제되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$mclover_type = isset($_GET['mclover_type']) ? $_GET['mclover_type'] : null;
if($mclover_type == "delete"){
	$sql = "delete from ".$nClovermlist_login_ing->table_name." where seq='".$_GET['dseq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('삭제되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

if($mclover_type == "order_adm_ck_y"){
	$sql = "update ".$nClovermlist_login_ing->table_name." set order_adm_ck='y' where seq='".$_GET['dseq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('승인되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

if($dmode == "delgroup"){
	$sql = "update ".$nMember->table_name." set group_name='' where seq='".$_GET['seq']."'";
	mysql_query($sql);

	echo "
	<script>
	alert('퇴사처리 되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$gmode = isset($_GET['gmode']) ? $_GET['gmode'] : null;
if($gmode == "chgroup"){
	if($_GET['group_name_v'] != ''){
		$user_state_query = ", user_state='5'";
	} else {
		$user_state_query = ", user_state='2'";
	}
	$sql = "update ".$nMember->table_name." set group_name='".$_GET['group_name_v']."' $user_state_query where id='".$_GET['seq']."'";
	mysql_query($sql);

	$sql = "delete from new_tb_clovercomment where writer like '%".$_GET['del_com_id']."%'";
	mysql_query($sql);

	echo "
	<script>
	alert('소속정보가 수정되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$ingtype = isset($_GET['ingtype']) ? $_GET['ingtype'] : null;
if($ingtype == "update"){
	$sql = "update ".$nMember->table_name." set order_cencle='".$_GET['value_t']."' where seq='".$_GET['seq']."'";
	mysql_query($sql);
	echo "
	<script>
	alert('정기후원해지 되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_GET['seq']."';
	</script>
	";
}

$cmode = isset($_GET['cmode']) ? $_GET['cmode'] : null;
if($cmode == "clover_insert"){
	for($i = 0; $i<count($_POST['clover_date']); $i++){
		if($_POST['member_t'][$i] == "M"){
			$day = 5;
		} else {
			$day = 0;
		}
		if($_POST['clover_price'][$i] != ""){
			$sql = "
			insert into new_tb_clover_mlist set
			type = 1,
			clover_seq = '".$_POST['clover_company'][$i]."',
			name = '".$_POST['m_name']."',
			group_name = '".$_POST['m_group']."',
			id = '".$_POST['m_id']."',
			price = '".$_POST['clover_price'][$i]."',
			day='".$day."',
			reg_date='".$_POST['clover_date'][$i]."-11 10:24:31'
			";
			mysql_query($sql);
			echo "
			<script>
			alert('후원이 등록되었습니다.');
			window.location = '/admin/member?item=list_normal&type=edit&seq=".$_POST['seq']."';
			</script>
			";

		}
	}
}

$omode = isset($_POST['omode']) ? $_POST['omode'] : null;
if($omode == "order"){
	$nClovermlist->otype    = NullVal($_POST['otype'], 1, $list_link); // 이체/신용
	$nClovermlist->clover_seq    = $_POST['clover_company_v']; // 기관코드

	$nClovermlist->name    = $_POST['oname']; // 이름
	$nClovermlist->birth    = $_POST['birth']; // 생년월일
	$nClovermlist->id    = $_POST['oid']; // 아이디

	$nClovermlist->price    = $_POST['price_v']; // 금액
	$nClovermlist->day    = $_POST['day']; // 약정일
	$nClovermlist->start_y = $_POST['start_y']; // 약정일
	$nClovermlist->start_m    = $_POST['start_m']; // 약정일

	$date_ck = $nClovermlist->start_y.$nClovermlist->start_m;
	if($nClovermlist->day < 10){
		$day_zero = "0".$nClovermlist->day;
	} else {
		$day_zero = $nClovermlist->day;
	}
	/*
	if($_POST['day'] > date('d')){
		$start = date('Ym').$day_zero;
	} else {
		$start = (date('Ym',mktime()+(86400*30))).$day_zero;
	}
	*/
	$start = $date_ck.$day_zero;
	$nClovermlist->start    = $start; // 시작월
	$nClovermlist->zip    = $_POST['zip']; // 우편번호
	$nClovermlist->address    = $_POST['address']; // 주소
	$nClovermlist->cell    = $_POST['cell']; // 휴대폰

	if($_POST['otype']=="자동이체"){
		$nClovermlist->bank    = $_POST['bank'];
		$nClovermlist->banknum    = $_POST['banknum'];
		$nClovermlist->bankdate    = "";
	}else if($_POST['otype']=="신용카드"){
		$nClovermlist->bank    = $_POST['bank'];
		$nClovermlist->banknum    = $_POST['banknum'];
		$nClovermlist->bankdate    = $_POST['bankdate'];
	}

	$arr_field = array
    (
        'otype', 'clover_seq', 'name', 'group_name', 'birth', 'id', 'price', 'day', 'start', 'zip', 'address', 'cell', 'email', 'bank', 'banknum', 'bankdate'
    );

	$arr_value = array(
		$nClovermlist->otype, $nClovermlist->clover_seq, $nClovermlist->name, $_POST['gname'], $nClovermlist->birth, $nClovermlist->id, $nClovermlist->price, $nClovermlist->day, $nClovermlist->start, $nClovermlist->zip, $nClovermlist->address, $nClovermlist->cell, $nClovermlist->email, $nClovermlist->bank, $nClovermlist->banknum, $nClovermlist->bankdate
	);

	$Conn->StartTrans();

	$out_put = $Conn->InsertDB($nClovermlist->table_name, $arr_field, $arr_value);

	if($out_put){
		$Conn->CommitTrans();
	}else{

		$Conn->RollbackTrans();
		$Conn->disConnect();

		JsAlert(ERR_DATABASE, 1, $list_link);
	}
	echo "
	<script>
	alert('정기후원이 등록되었습니다.');
	window.location = '/admin/member?item=list_normal&type=edit&seq=".$_POST['seq']."';
	</script>
	";
}






	$nClovermlist_login->page_result = $Conn->AllList
	(
		$nClovermlist_login->table_name, $nClovermlist_login, "*", "where id='".$nMember->user_id."' and start='' order by reg_date desc", null, null
	);


	$nClovermlist_login_ing->page_result = $Conn->AllList
	(
		$nClovermlist_login_ing->table_name, $nClovermlist_login_ing, "*", "where id='".$nMember->user_id."' and start != '' and type='0' order by clover_seq, start desc", null, null
	);



	$nClovermlist_login_b->page_result = $Conn->AllList
	(
		$nClovermlist_login_b->table_name, $nClovermlist_login_b, "*", "where id='".$nMember->user_id."' and bank != '' and type='0' group by bank,banknum order by reg_date desc", null, null
	);


$Conn->DisConnect();



//======================== DB Module End ===============================
?>
    <script language="javascript">
        function sendSubmit()
        {
            var f = document.frm;

/*
			if(formCheckSub(f.user_pw, "exp", "비밀번호") == false){ return; }
			if(formCheckSub(f.user_pw, "inj", "비밀번호") == false){ return; }
			if(formCheckNum(f.user_pw, "minlen", 6, "비밀번호") == false){ return; }
			if(formCheckNum(f.user_pw, "maxlen", 20, "비밀번호") == false){ return; }
*/
            $.blockUI();
            f.action = "{!! $edit_link !!}";
            f.submit();
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
		<form method="post" action="{{ $edit_link }}" name='order_form'>
			<input type="hidden" name="omode" value="order">
			<input type="hidden" name="seq" value="{{ $_REQUEST['seq'] }}">

			<input type="hidden" name="item" value="list_normal" />
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />

			<input type="hidden" name="oid" value="{{ $nMember->user_id }}">
			<input type="hidden" name="gname" value="{{ $nMember->group_name }}">
			<input type="hidden" name="oname" value="{{ $nMember->user_name }}">
			<input type="hidden" name="birth" value="{{ $nMember->user_birth }}">
			<input type="hidden" name="zip" value="{{ $nMember->post1 }}-{{ $nMember->post2 }}">
			<input type="hidden" name="address" value="{{ $nMember->addr1 }} {{ $nMember->addr2 }}">
			<input type="hidden" name="cell" value="{{ $nMember->user_cell }}">
			<input type="hidden" name="clover_company_v" value="">
			<input type="hidden" name="otype" value="">
			<input type="hidden" name="price_v" value="">
			<input type="hidden" name="day" value="">
			<input type="hidden" name="bank" value="">
			<input type="hidden" name="banknum" value="">
			<input type="hidden" name="bankdate" value="">
			<input type="hidden" name="start_y" value="">
			<input type="hidden" name="start_m" value="">
		</form>

        <div id="right_area">
            <h4 class="main-title">{{ $content_txt }}</h4>
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
			<input type='hidden' name="l_id" value='{{ $nMember->user_id }}'>
            <table class="bbs-write">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:15%;" />
                    <col style="width:85%;" />
                </colgroup>
                <tbody>
				<?php if($nMember->user_state==3){ ?>
					<tr>
						<th>탈퇴사유</th>
						<td><?php $nMember->ArrMember($nMember->user_drop, "name='user_drop'", '탈퇴사유를 선택해 주세요', 'drop') ?></td>
					</tr>
					<tr>
						<th>기타탈퇴사유</th>
						<td>
							<textarea name="user_dropmsg" style="width:684px; height:147px; overflow-y:scroll;">{{ $nMember->user_dropmsg }}</textarea><br />
						</td>
					</tr>
				<?php } else { ?>
               	<tr>
					<th>이름</th>
					<td><input type="text" class="joinforms" name="user_name" value="{{ $nMember->user_name }}"></td>
				</tr>
				<tr>
					<th>아이디</th>
					<td>
						{{ $nMember->user_id }}
					</td>
				</tr>
				<tr>
					<th>패스워드</th>
					<td>
						<input class="joinforms" name="user_pw" type="password" maxlength="15" style="ime-mode:disabled;" onkeydown="checkKeycode(event)" onkeypress="handlerEng()">
					</td>
				</tr>
				<tr>
					<th>생년월일</th>
					<td>
					<input type="text" class="joinforms" name="user_birth" value="{{ $nMember->user_birth }}">

					</td>
				</tr>
				<tr>
					<th>성별</th>
					<td>

						<div id="radioForm" class="h200">
							<input type="radio" name="user_gender" id="M" value="M" <?php if($nMember->user_gender == "M"){ ?>checked<?php } ?>>
							<label for="M" class="mr20">남자</label>
							<input type="radio" name="user_gender" id="F" value="F" <?php if($nMember->user_gender == "F"){ ?>checked<?php } ?>>
							<label for="F">여자</label>
						</div>

					</td>
				</tr>
				<tr>
					<th>휴대번호</th>
					<td>
						{{ $nMember->user_cell }}
					</td>
				</tr>

        <tr>
          <th>
            주소
          </th>
          <td>
            <input type="text" name="addr1" value="{{ $nMember->addr1 }}" style="width: 200px;" />
          </td>
        </tr>

        <tr>
          <th>
            세부주소
          </th>
          <td>
            <input type="text" name="addr2" value="{{ $nMember->addr2 }}" style="width: 200px;" />
          </td>
        </tr>



				<tr>
					<th>계좌정보</th>
					<td>
						<?php
						if(count($nClovermlist_login_b->page_result) > 0){

							for($i=0, $cnt_list=count($nClovermlist_login_b->page_result); $i < $cnt_list; $i++) {
								$nClovermlist_login_b->VarList($nClovermlist_login_b->page_result, $i, null);

						?>
						<p style="font-size:15px; font-weight:bold;<?php if($i == 0){ ?>color:blue;<?php } ?>">
							* 후원계좌 : {{ $nClovermlist_login_b->bank }} - {{ $nClovermlist_login_b->banknum }}
						</p>
						<?php
							}
						} else { ?>
						회원님의 계좌정보가 존재하지 않습니다.
						<?php } ?>
					</td>
				</tr>


				<tr>
					<th>소속정보</th>
					<td>
						[
						<?php if($nMember->user_state == 2){ ?>개인<?php } ?>
						<?php if($nMember->user_state == 5){ ?>기업<?php } ?>
						<?php if($nMember->user_state == 4){ ?>기업담당자<?php } ?>
						]
						{{ $nMember->group_name }}

						<select name="" onchange="del_group_func( this.value ,'{{ $_REQUEST['seq'] }}','{{ $nMember->user_id }}');">
							<option value="" <?php if($nMember->group_name == ''){ ?>selected<?php } ?>>퇴사처리
							<?php
							for($g = 0; $g < count($group_name_v); $g++){
							?>
							<option value="{{ $group_name_v[$g] }}" <?php if($group_name_v[$g] == $nMember->group_name){ ?>selected<?php } ?>>{{ $group_name_v[$g] }}
						<?php } ?>
						</select>
<script type="text/javascript">
<!--
function del_group_func(group_name_ck, getseq, del_com_idv){
	if(confirm('소속정보를 수정하시겠습니까?')){
		window.location = "{{ route('admin/member') }}?item=list_normal&type=edit&seq="+getseq+"&gmode=chgroup&del_com_id="+del_com_idv+"&group_name_v="+group_name_ck;
	}
}
//-->
</script>
					</td>
				</tr>
				<tr>
					<th>회원등급</th>
					<td>
						<?php if($nMember->user_state == 2){ ?>개인<?php } ?>
						<?php if($nMember->user_state == 5){ ?>기업<?php } ?>
						<?php if($nMember->user_state == 4){ ?>기업담당자<?php } ?>
					</td>
				</tr>


				<tr>
					<th>후원 목록</th>
					<td>

						<p style="font-size:15px; font-weight:bold;">정기후원</p>
						<p style="width:100%; height:5px;"></p>
						<p style="width:100%; height:1px; background:#e8e8e8;"></p>
						<p style="width:100%; height:5px;"></p>
						<?php
						if(count($nClovermlist_login_ing->page_result) > 0){

							for($i=0, $cnt_list=count($nClovermlist_login_ing->page_result); $i < $cnt_list; $i++) {
								$nClovermlist_login_ing->VarList($nClovermlist_login_ing->page_result, $i, null);


								$substr_date[0] = substr($nClovermlist_login_ing->start,0,4);
								$substr_date[1] = substr($nClovermlist_login_ing->start,4,2);
								$substr_date[2] = substr($nClovermlist_login_ing->start,6,2);


								$ck_group_name[] = $nClovermlist_login_ing->clover_seq;
								if($nClovermlist_login_ing->ing_cencle == '' || $nClovermlist_login_ing->ing_cencle == 'y') {
									$clover_seq_ing = $nClovermlist_login_ing->clover_seq.'y';
								} else {
									$clover_seq_ing = $nClovermlist_login_ing->clover_seq.'n';
								}

								$ck_group_name_ing[] = $clover_seq_ing;
						?>
						<p style="font-size:15px; font-weight:bold;<?php if($nMember->order_cencle == 'n'){ ?>color:red;<?php } else if($i == 0){ ?>color:blue;<?php } else if ($nClovermlist_login_ing->order_ck == 'h') { ?>color:green;<?php } else if($nClovermlist_login_ing->ing_cencle == 'n'){ ?>color:red;<?php } ?>">

							결제정보 : {{ $nClovermlist_login_ing->otype }} |
							{{ $substr_date[0] }}년 {{ $substr_date[1] }}월 {{ $substr_date[2] }}일 {{ $clober_name_scode[$nClovermlist_login_ing->clover_seq] }}  {{ number_format($nClovermlist_login_ing->price) }}원
              {{ $nClovermlist_login_ing->address }}
							<?php if($nClovermlist_login_ing->order_adm_ck == 'n' || $nClovermlist_login_ing->order_adm_ck == ''){ ?>
							<input type="button" value="승인" style="padding:5px;" onclick="window.location = '{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_REQUEST['seq'] }}&dseq={{ $nClovermlist_login_ing->seq }}&mclover_type=order_adm_ck_y';">
							<?php } ?>
							<input type="button" value="삭제" style="padding:5px;" onclick="if(confirm('삭제하시겠습니까?')){window.location = '{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_REQUEST['seq'] }}&dseq={{ $nClovermlist_login_ing->seq }}&mclover_type=delete'; }">
						</p>
						<?php
							}
						} else { ?>
						<p style="font-size:15px; font-weight:bold;">
						회원님의 계좌정보가 존재하지 않습니다.
						</p>
						<?php } ?>
<script type="text/javascript">
<!--

function c_m_func(seq, nli_seq){
	if(confirm('후원기관수정을 수정하시겠습니까?')){
		window.location = "{{ route('admin/member') }}?item=list_normal&type=edit&seq="+seq+"&c_m_seq="+nli_seq+"&clover_m_type=modify&value_ck="+$('#c_com_n_v').val();
	}
}
//-->
</script>
						<p style="width:100%; height:5px;"></p>
						<p style="font-size:15px; font-weight:bold;">
						<input type="button" value="정기후원추가" style="padding:5px;" onclick="$('#order_view_line').show();$('#order_view_data').show();">
						<?php if($nMember->order_cencle == 'n'){ ?>
							<input type="button" value="정기후원해지풀기" style="padding:5px;" onclick="if(confirm('정기회원으로 등록하시겠습니까?')){window.location ='{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_GET['seq'] }}&ingtype=update&value_t='; }">
						<?php } else { ?>
							<input type="button" value="정기후원해지" style="padding:5px;" onclick="if(confirm('후원을 해지 하시겠습니까?\n귀하의 출금일 기준으로 해지 됩니다.\n그 동안 후원 해 주셔서 대단히 감사합니다.')){window.location = '{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_REQUEST['seq'] }}&ingtype=update&value_t=n'; }">
						<?php } ?>

<?php
$my_group_code = @array_unique($ck_group_name);
$my_group_code = @array_values($my_group_code);

$my_group_name_ing = @array_unique($ck_group_name_ing);
$my_group_name_ing = @array_values($my_group_name_ing);

for($mg = 0; $mg<count($my_group_code); $mg++){

	$my_group_name_ing[$mg] = str_replace($my_group_code[$mg],"",$my_group_name_ing[$mg]);
?>

<?php if($my_group_name_ing[$mg] == 'y'){ ?>
	<input type="button" value="{{ $clober_name_scode[$my_group_code[$mg]] }} 정기후원해지" style="padding:5px;" onclick="if(confirm('후원을 해지 하시겠습니까?\n귀하의 출금일 기준으로 해지 됩니다.\n그 동안 후원 해 주셔서 대단히 감사합니다.')){window.location = '{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_REQUEST['seq'] }}&clover_cencle=update&value_t=n&mbid={{ $nMember->user_id }}&c_code={{ $my_group_code[$mg] }}'; }">
<?php } else { ?>
	<input type="button" value="{{ $clober_name_scode[$my_group_code[$mg]] }} 정기후원해지풀기" style="padding:5px;" onclick="if(confirm('정기후원으로 등록하시겠습니까?')){window.location = '{{ route('admin/member') }}?item=list_normal&type=edit&seq={{ $_REQUEST['seq'] }}&clover_cencle=update&value_t=y&mbid={{ $nMember->user_id }}&c_code={{ $my_group_code[$mg] }}'; }">
<?php } ?>

<?php } ?>
						</p>
						<p style="width:100%; height:5px;display:none;" id="order_view_line"></p>
						<p style="font-size:15px; font-weight:bold;display:none;" id="order_view_data">
							<select name="otype" id="otype">
								<option value="자동이체">자동이체
								<option value="신용카드">신용카드
								<option value="point">포인트
							</select>
							<select name="clover_company_value_2">
								<?php for($j=0; $j<count($clober_code); $j++){ ?>
								<option value="{{ $clober_code[$j] }}">{{ $clober_name[$j] }}
								<?php } ?>
							</select>
							<input type="text" name="price_v_value" value=""> 원
							<select id="start_y" name="start_y">
								<?php for($y = 2010; $y < 2101; $y++){ ?>
								  <option value="{{ $y }}" <?php if(date('Y') == $y){ ?>selected<?php } ?>>{{ $y }}년</option>
								<?php } ?>
							</select>

							<select id="start_m" name="start_m">
								<?php for($m = 1; $m < 13; $m++){
								if($m < 10){
									$m = '0'.$m;
								}
								?>
								  <option value="{{ $m }}" <?php if(date('m') == $m){ ?>selected<?php } ?>>{{ $m }}월</option>
								<?php } ?>
							</select>

							<select id="selectGroup" name="day_value">
							  <option value="5">5일</option>
							  <option value="10">10일</option>
							  <option value="15">15일</option>
							  <option value="20">20일</option>
							  <option value="25">25일</option>
							  <option value="30">30일</option>
							</select>
							<br>
							<input type="text" name="bank_value" placeholder="은행명" value="{{ $nClovermlist_login_b->bank }}">
							<input type="text" name="banknum_value" placeholder="계좌번호" value="{{ $nClovermlist_login_b->banknum }}">
							<input type="text" name="bankdate_value" placeholder="유효기간" value="{{ $nClovermlist_login_b->bankdate }}">

							<input type="button" value="확인" onclick="order_func()">

						</p>



<script type="text/javascript">
<!--
function order_func(){
	var fm = document.order_form;
	var dfm = document.frm;
	fm.clover_company_v.value = dfm.clover_company_value_2.value;
	fm.price_v.value = dfm.price_v_value.value;
	fm.day.value = dfm.day_value.value;
	fm.bank.value = dfm.bank_value.value;
	fm.banknum.value = dfm.banknum_value.value;
	fm.bankdate.value = dfm.bankdate_value.value;
	fm.start_y.value = dfm.start_y.value;
	fm.start_m.value = dfm.start_m.value;
	fm.otype.value = dfm.otype.value;

	if(fm.price_v.value == ''){
		alert('금액을 입력해주세요.');
		return;
	}
	if(fm.bank.value == ''){
		alert('은행명을 입력해주세요.');
		return;
	}
	if(fm.banknum.value == ''){
		alert('계좌번호를 입력해주세요.');
		return;
	}

	fm.submit();
}
//-->
</script>


<BR><BR>
						<p style="font-size:15px; font-weight:bold;">일시후원</p>
						<p style="width:100%; height:5px;"></p>
						<p style="width:100%; height:1px; background:#e8e8e8;"></p>
						<p style="width:100%; height:5px;"></p>

						<?php

						$all_price = 0;
						if(count($nClovermlist_login->page_result) > 0){

							for($i=0, $cnt_list=count($nClovermlist_login->page_result); $i < $cnt_list; $i++) {
								$nClovermlist_login->VarList($nClovermlist_login->page_result, $i, null);

								$Conn = new DBClass();
									$nClover_m->where = "where code ='".$nClovermlist_login->clover_seq."'";
									$nClover_m->read_result = $Conn->AllList
									(
										$nClover_m->table_name, $nClover_m, "*", $nClover_m->where, null, null
									);

									if(count($nClover_m->read_result) != 0){
										$nClover_m->VarList($nClover_m->read_result, 0, null);

										$clover_name = $nClover_m->subject;
										$clover_code = $nClover_m->code;
									}
								$Conn->DisConnect();

								$view_date = explode("-",$nClovermlist_login->reg_date);
								if($nClovermlist_login->day > 0){
									$type_v = "정기후원";
								} else {
									$type_v = "일시후원";
								}

								$all_price += $nClovermlist_login->price;
						?>
						<p style="font-size:15px; font-weight:bold;<?php if($i == 0){ ?>color:blue;<?php } ?>">
							{{ $view_date[0] }}년 {{ $view_date[1] }}월

							<select name="clover_company" id="clover_company{{ $i }}">
								<?php for($j=0; $j<count($clober_code); $j++){ ?>
								<option value="{{ $clober_code[$j] }}" <?php if($clover_code == $clober_code[$j]){ ?>selected<?php } ?>>{{ $clober_name[$j] }}
								<?php } ?>
							</select>
							<select name="member_t" id="member_t{{ $i }}">
								<option value="0" <?php if($type_v == "일시후원"){ ?>selected<?php } ?>>일시후원
							</select>

							{{ number_format($nClovermlist_login->price) }}<?php if($nClovermlist_login->otype == 'point'){ ?>포인트<?php } else { ?>원<?php } ?>
							<input type="text" name="price_m" id="price_m{{ $i }}" value="{{ $nClovermlist_login->price }}" size="10">
							<input type="hidden" name="mclover_seq" id="mclover_seq{{ $i }}" value="{{ $nClovermlist_login->seq }}" size="10">
							<input type="button" value="적용" onclick="update_price_func('price_m{{ $i }}','mclover_seq{{ $i }}','clover_company{{ $i }}','member_t{{ $i }}','{{ $_GET['seq'] }}');">
							<input type="button" value="삭제" onclick="del_price_func('mclover_seq{{ $i }}','{{ $_GET['seq'] }}');">
							<?php if($nClovermlist_login->order_adm_ck == 'n' || $nClovermlist_login->order_adm_ck == ''){ ?>
							<input type="button" value="승인" onclick="window.location = '{{ route('admin/member') }}?seq={{ $_GET['seq'] }}&dseq={{ $nClovermlist_login->seq }}&mclover_type=order_adm_ck_y';">
							<?php } ?>
						</p>
						<?php
							}
						} else { ?>
						회원님의 후원 목록이 존재하지 않습니다.
						<?php } ?>
						<p style="font-size:15px; font-weight:bold;">총 후원금액 : {{ number_format($all_price) }}원</p>
					</td>
				</tr>


				<?php } ?>
                </tbody>
            </table>
<script type="text/javascript">
<!--
function update_price_func(price_id, clover_id, company_id, type_id, getseq){
	var price_id_el = document.getElementById(price_id);
	var clover_id_el = document.getElementById(clover_id);
	var company_id_el = document.getElementById(company_id);
	var type_id_el = document.getElementById(type_id);
	if(price_id_el.value == ""){
		alert('수정금액을 입력하세요.');
		return;
	}

	window.location = "{{ route('admin/member') }}?seq="+getseq+"&price_v="+price_id_el.value+"&company_code="+company_id_el.value+"&type_t="+type_id_el.value+"&clover_seq="+clover_id_el.value;
}

function del_price_func(clover_id, getseq){
	if(confirm('삭제하시겠습니까?')){
		var clover_id_el = document.getElementById(clover_id);
		window.location = "{{ route('admin/member') }}?seq="+getseq+"&clover_seq="+clover_id_el.value+"&dmode=delmlist";
	}
}

//-->
</script>
			{{ UserHelper::SubmitHidden() }}
            </form>

<form method="post" action="{{ $edit_link }}">
	<input type="hidden" name="item" value="list_normal" />
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	<input type="hidden" name="c_mode" value="clover_insert">
	<input type="hidden" name="m_name" value="{{ $nMember->user_name }}">
	<input type="hidden" name="m_group" value="{{ $nMember->group_name }}">
	<input type="hidden" name="m_id" value="{{ $nMember->user_id }}">
	<input type="hidden" name="seq" value="{{ $_REQUEST['seq'] }}">


		<table cellpadding=0 cellspacing=0 border=0 width=100% align=center style="border-top:2px solid #9e9e9e;border-bottom:2px solid #9e9e9e;">
		<tr height=30>
			<td align=center style="font-weight:bold; color:#616161; background:#f5f3f2; width:133px;">회원후원등록</td>
			<td style="padding:10px;">
			<table cellpadding=0 cellspacing=0 border=0 align=center width=100% style="border:1px solid #e8e8e8;">
			<tr height=30 bgcolor=e8e8e8>
				<th style="border-right:1px solid #fff;">후원일시</th>
				<th style="border-right:1px solid #fff;">후원타입</th>
				<th style="border-right:1px solid #fff;">후원재단</th>
				<th >후원금액</th>
			</tr>
			<?php for($k=0; $k<10; $k++){ ?>
			<tr height=30>
				<td width=25% style="border:1px solid #e8e8e8;" align=center><input type="text" name="clover_date[]" value="{{ date('Y') }}-{{ date('m') }}" > </td>
				<td width=25% style="border:1px solid #e8e8e8;" align=center>
				<select name="member_t[]">
					<!-- <option value="M" selected>정기후원 -->
					<option value="D" selected>일시후원
				</select>
				</td>
				<td width=25% style="border:1px solid #e8e8e8;" align=center>
				<select name="clover_company[]">
					<?php for($j=0; $j<count($clober_code); $j++){ ?>
					<option value="{{ $clober_code[$j] }}">{{ $clober_name[$j] }}
					<?php } ?>
				</select>
				</td>
				<td width=25% style="border:1px solid #e8e8e8;" align=center>
				<input type="text" name="clover_price[]" value="" >
				</td>
			</tr>
			<?php } ?>


			<tr height=30>
				<td align=center colspan=4><input type="image" src="/imgs/admin/images/btn_save.gif" alt="save" onclick="this.submit()"/></td>
			</tr>

			</table>
			</td>
		</tr>
		</table>

</form>


           <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <input type="image" src="/imgs/admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
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
