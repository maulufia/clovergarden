<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C7';
    $list_link  = 'm_07_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nMember = new MemberClass(); //회원
	$nClover = new CloverClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();

	$nClover->page_view = 10000;
	$nClover->total_record = $Conn->PageListCount
	(
		$nClover->table_name, $nClover->where, $search_key, $search_val
	);
	
	$nClover->page_result = $Conn->PageList
	(	
		$nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->page_view, null
	);


	if(count($nClover->page_result) > 0){
		$row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
		for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
			$nClover->VarList($nClover->page_result, $i,  array('comment'));
			$clover_name[$nClover->code] = $nClover->subject;
		}
	}

    $nMember->where = " where clover_seq_adm != ''";

    $nMember->total_record = $Conn->PageListCount
    (
        $nMember->table_name, $nMember->where, $search_key, $search_val
    );

    $nMember->page_result = $Conn->PageList
    (
        $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by clover_seq_adm_type desc', $nMember->sub_sql, $page_no, $nMember->page_view
    );

	if($_GET[mod_mode] == "modify_seq"){

		$sql = "update ".$nMember->table_name." set clover_seq_adm_type='".$_GET[modify_v]."' where seq='".$_GET[mod_seq]."'";
		mysql_query($sql);
			echo "
			<script>
			alert('정보가 수정되었습니다.');
			window.location='./m_07_list.php';
			</script>
			";

	}

$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val)
?>
</head>
<body>

<!-- wrapper -->
<div id="wrapper">
    <!-- top_area -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/top.php'; ?>
    <!-- //top_area -->
    <!-- container -->
    <div id="container">
        <!-- left_area -->
            <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/left.php'; ?>
        <!-- //left_area -->
        <!-- right_area -->
        <div id="right_area">
            <h4 class="main-title"><?=$content_txt?></h4>
			<div class="bbs-search">
                <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
                    <?$nMember->ArrMember(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>

				<a href="javascript:excel();" style="padding:0 10px; background:#fff; height:20px; line-height:20px; float:right; border:1px solid #dbdbdb;">일괄등록</a> 

				<!-- <a href="javascript:excel2();" style="padding:0 10px; background:#fff; height:20px; line-height:20px; float:right; border:1px solid #dbdbdb;">회원 업로드</a>  -->
            </div>

            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col style="width:100px;" />
					<col style="width:100px;" />
					<col style="width:200px;" />
                    <col  />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>날짜</th>
					<th>이름</th>
					<th>기존후원</th>
					<th>변경후원 요청</th>
					<th>처리상태</th>
                </tr>
                </thead>
                <tbody>
<?php

		
    if(count($nMember->page_result) > 0){

        $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
            $nMember->VarList($nMember->page_result, $i, null);
			$ex_clover_seq_adm = explode("[@@@@]",$nMember->clover_seq_adm);
			$ex_date_or_type = explode("[@@]",$nMember->clover_seq_adm_type);
?>
                <tr>
                    <td><?=$row_no?></td>
                    <td><?=date("Y-m-d",$ex_date_or_type[0])?></td>					
                    <td><?=$nMember->user_name?></td>
                    <td>
					<?
					$ex_seq_clover_1 = explode("[@@@]",$ex_clover_seq_adm[0]);
					for($j=0; $j<count($ex_seq_clover_1); $j++){
						$v_ex = explode("[@@]",$ex_seq_clover_1[$j]);
						echo $clover_name[$v_ex[0]]." ".number_format($v_ex[1])."원 <BR>";
					}
					?>
					</td>
                    <td>
					<?
					$ex_seq_clover_1 = explode("[@@@]",$ex_clover_seq_adm[1]);
					for($j=0; $j<count($ex_seq_clover_1); $j++){
						$v_ex = explode("[@@]",$ex_seq_clover_1[$j]);
						echo $clover_name[$v_ex[0]]." ".number_format($v_ex[1])."원 <BR>";
					}
					?>
					</td>
					<td>
					<?
					if($ex_date_or_type[1] == "ok"){
						echo "<a href='./m_07_list.php?mod_mode=modify_seq&modify_v=".$ex_date_or_type[0]."&mod_seq=".$nMember->seq."'><font color='blue'><B>처리</B><BR>(클릭시 미처리로 변경됨)</font></a>";
					} else {
						echo "<a href='./m_07_list.php?mod_mode=modify_seq&modify_v=".$ex_date_or_type[0]."[@@]ok&mod_seq=".$nMember->seq."'><font color='red'>미처리<BR>(클릭시 처리로 변경됨)</font></a>";
					}
					?>
					</td>
                </tr>
<?
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="9"><?=NO_DATA?></td>
                </tr>
<?
    }


?>
                </tbody>
            </table>
            <?=SubmitHidden()?>

			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr>
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
				<td align=center width=10%><input type="button" value="소식지발송" onclick="check_form('select')" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
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


//-->
</script>
        <form name="form_submit_ck" method="post" action="./m_04_email.php?cktype=member" style="display:inline">
            <input type="hidden" name="s_mode" value="ch_send">
			<input type="hidden" name="s_email" value="">
			<input type="hidden" name="s_name" value="">
        </form>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
        </form>
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->

</body>
</html>