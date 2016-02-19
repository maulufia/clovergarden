<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A1';
    $list_link   = 'm_01_list.php';
    $view_link   = 'm_01_view.php';
    $edit_link   = 'm_01_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nMember = new MemberClass(); //회원
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->where = "where seq ='".$seq."'";
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

	$cell = explode("-", $nMember->user_cell);
	$email = explode("@", $nMember->user_email);



$Conn->DisConnect();
//======================== DB Module End ===============================
?>
    <script language="javascript">
        function sendSubmit()
        {
            var f = document.frm;



			if(formCheckSub(f.user_name , "exp", "이름") == false){ return; }
            if(formCheckSub(f.user_name, "inj", "이름") == false){ return; }
            if(formCheckNum(f.user_name, "maxlen", 50, "이름") == false){ return; }

			if(formCheckSub(f.user_pw , "exp", "패스워드") == false){ return; }
            if(formCheckSub(f.user_pw, "inj", "패스워드") == false){ return; }            
			if(formCheckNum(f.user_pw, "minlen", 4, "패스워드") == false){ return; }
			if(formCheckNum(f.user_pw, "maxlen", 15, "패스워드") == false){ return; }

		
            $.blockUI();
            f.action = "<?=$edit_link?>";
            f.submit();
        }
    </script>
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
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
            <table class="bbs-write">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                </colgroup>
                <tbody>
                <tr>
                    <th>아이디</th>
                    <td colspan="3">					    
                        <?=$nMember->user_id?>
                    </td>
                </tr>
				<tr>
                    <th>이름</th>
                    <td>					    
                        <input type="text" name="user_name" style="width:150px;" value="<?=$nMember->user_name?>"/>
                    </td>
                    <th>패스워드</th>
                    <td>
                        <input type="text" name="user_pw" style="width:150px;"/>
                    </td>
                </tr>
               
                </tbody>
            </table>
			<?=SubmitHidden()?>
            </form>
           <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <input type="image" src="/new_admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
                </div>
            </div>
        </div>
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
        </form>
        <!-- //right_area -->
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>