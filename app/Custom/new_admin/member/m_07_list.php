<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A7';
    $list_link  = 'm_05_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nEmail = new EmailClass(); //회원
	$nEmail_2 = new EmailClass(); //회원

	$nEmail->where  = "where send_ck = 'member'";
	$nEmail->table_name = $nEmail->table_name."_send";
	$nEmail_2->table_name = $nEmail_2->table_name."_send";
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nEmail->total_record = $Conn->PageListCount
    (
        $nEmail->table_name, "where send_ck = 'member' group by send_date", $search_key, $search_val
    );
    $nEmail->page_result = $Conn->PageList
    (
        $nEmail->table_name, $nEmail, $nEmail->where, $search_key, $search_val, 'group by send_date', ', count(seq) count_sn', $page_no, $nEmail->page_view
    );

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
                    <?$nEmail->ArrEmail(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <form action="./m_04_email.php" id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:50px;" />                  
                    <col style="width:100px;" />
                    <col style="width:120px;" />
					<col  />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>발송일</th>
                    <th>받는사람</th>
                    <th>제목</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nEmail->page_result) > 0){
        $row_no = $nEmail->total_record - ($nEmail->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nEmail->page_result); $i < $cnt_list; $i++) {
            $nEmail->VarList($nEmail->page_result, $i, null);
			$Conn = new DBClass();

				$nEmail_2->page_result = $Conn->AllList
				(	
					$nEmail_2->table_name, $nEmail_2, "*", "where send_ck = 'member' and send_date='".$nEmail->send_date."'", null, null
				);


			$Conn->DisConnect();

?>
                <tr>
                    <td><?=$row_no?></td>         
                    <td><?=date("Y.m.d H:i:s",$nEmail->send_date)?></td>
					<td><?=$nEmail->name?>외 <?=$nEmail->count_sn-1?>명</td>
                    <td><a href="javascript:$('#tr_view<?=$i?>').show();"><?=$nEmail->send_subject?></a></td>
                </tr>
                <tr style="display:none;" id="tr_view<?=$i?>">
                    <td colspan=4 align=center style="padding:10px;">
						<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
						<tr>
							<th width="100">이름</th>
							<th width="190">이메일</th>
							<th>내용</th>
						</tr>
						<?php
							if(count($nEmail_2->page_result) > 0){
								for($j=0, $cnt_listj=count($nEmail_2->page_result); $j < $cnt_listj; $j++) {
									$nEmail_2->VarList($nEmail_2->page_result, $j, null);

						?>
						<tr>
							<td><?=$nEmail_2->name?></td>
							<td><?=$nEmail_2->email?></td>
							<?if($j%count($nEmail_2->page_result) == 0){?>
							<td rowspan='<?=count($nEmail_2->page_result)?>' valign=top style="padding:5px;text-align:left;">
							<strong>제목:<?=$nEmail_2->send_subject?></strong><BR>
							<?=$nEmail_2->send_content?>
							</td>
							<?}?>
						</tr>
						<?		}
						}?>
						<tr height=30><td colspan=4><a href="javascript:$('#tr_view<?=$i?>').hide();"><strong>닫기</strong></a></td></tr>
						</table>
					</td>         
                </tr>

<?
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="5"><?=NO_DATA?></td>
                </tr>
<?
    }
?>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            
			<div class="paging-area">
            <?php
                if($nEmail->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nEmail->total_record, $page_no, $nEmail->page_view, $nEmail->page_set, $nEmail->page_where, 'pageNumber');
                }
            ?>

            </div>
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

        <form name="form_submit_ck" method="post" action="./m_04_email.php" style="display:inline">
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