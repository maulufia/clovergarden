<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key  = 'F2';
    $list_link = 'm_02_list.php';
    $edit_link = 'm_02_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nSns = new SnsClass(); //팝업

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nSns->read_result = $Conn->AllList($nSns->table_name, $nSns, "*", "where seq ='".$seq."'", null, null);
    if(count($nSns->read_result) != 0){
        $nSns->VarList($nSns->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
?>
    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;

            if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
            if(formCheckSub(f.subject, "inj", "제목") == false){ return; }
            if(formCheckNum(f.subject, "maxlen", 80, "제목") == false){ return; }

			if(formCheckSub(f.url , "exp", "URL") == false){ return; }

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
                    <col style="width:100px;" />
                    <col style="width:350px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>종류</th>
                    <td colspan="3"><?$nSns->ArrSns($nSns->type, "name='type'", null, 'type')?></td>
                </tr>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:450px;" value="<?=$nSns->subject?>"/>
                    </td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td colspan="3">
                        <input type="text" name="url" style="width:650px;" value="<?=$nSns->url?>"/>
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