<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A1';
    $list_link  = 'm_01_list.php';
    $write_link = 'write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nMember = new MemberClass(); //회원
?>
    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;

            if(formCheckSub(f.user_id , "exp", "아이디") == false){ return; }
            if(formCheckSub(f.user_id, "uid", "아이디") == false){ return; }
            if(formCheckNum(f.user_id, "maxlen", 10, "아이디") == false){ return; }
			
			if(formCheckSub(f.user_name , "exp", "담당자") == false){ return; }
            if(formCheckSub(f.user_name, "inj", "담당자") == false){ return; }
            if(formCheckNum(f.user_name, "maxlen", 50, "담당자") == false){ return; }

            if(formCheckSub(f.user_pw , "exp", "패스워드") == false){ return; }
            if(formCheckSub(f.user_pw, "inj", "패스워드") == false){ return; }            
			if(formCheckNum(f.user_pw, "minlen", 4, "패스워드") == false){ return; }
			if(formCheckNum(f.user_pw, "maxlen", 15, "패스워드") == false){ return; }

            $.blockUI();
            f.action = "<?=$write_link?>";
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
                        <input type="text" name="user_id" style="width:150px;"/>
						 <input type="hidden" name="user_state" style="width:150px;" value="1"/>
                    </td>
                </tr>
				<tr>
					<th>이름</th>
                    <td>
                        <input type="text" name="user_name" style="width:150px;"/>
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