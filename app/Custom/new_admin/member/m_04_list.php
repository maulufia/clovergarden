<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A4';
    $list_link  = 'm_04_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nEmail = new EmailClass(); //회원

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nEmail->total_record = $Conn->PageListCount
    (
        $nEmail->table_name, $nEmail->where, $search_key, $search_val
    );

    $nEmail->page_result = $Conn->PageList
    (
        $nEmail->table_name, $nEmail, $nEmail->where, $search_key, $search_val, 'order by reg_date desc', $nEmail->sub_sql, $page_no, $nEmail->page_view
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
                    <col style="width:50px;" />                  
                    <col style="width:100px;" />
					<col  />
                    <col style="width:100px;" />
					<col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" name="mailtoall" id="mailtoall" onclick="check_form('all')"></th>
                    <th>번호</th>
                    <th>이름</th>
                    <th>이메일</th>
                    <th>신청일</th>
					<th>비고</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nEmail->page_result) > 0){
        $row_no = $nEmail->total_record - ($nEmail->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nEmail->page_result); $i < $cnt_list; $i++) {
            $nEmail->VarList($nEmail->page_result, $i, null);
?>
                <tr>
                    <td>
					<input type="checkbox" name="mailtov[]" id="mailtov<?=$i?>" value="<?=$nEmail->email?>">
					<input type="hidden" name="mailtoname[]" id="mailtoname<?=$i?>" value="<?=$nEmail->name?>">
					
					</td>         
                    <td><?=$row_no?></td>         
                    <td><?=$nEmail->name?></td>
					<td><?=$nEmail->email?></td>
                    <td><?=str_replace('-','.',substr($nEmail->reg_date,0,10))?></td>
					<td><a href="m_04_email.php?email=<?=$nEmail->email?>&name=<?=$nEmail->name?>">소식지보내기</a></td>
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
            
			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr>
				<td align=center>
			<div class="paging-area">
            <?php
                if($nEmail->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nEmail->total_record, $page_no, $nEmail->page_view, $nEmail->page_set, $nEmail->page_where, 'pageNumber');
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

        <form name="form_submit_ck" method="post" action="./m_04_email.php?cktype=nomember" style="display:inline">
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