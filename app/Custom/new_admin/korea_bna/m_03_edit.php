<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C3';
    $code       = 'C03';
    $list_link   = 'm_03_list.php';
    $view_link   = 'm_03_view.php';
    $edit_link   = 'm_03_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPostscript = new PostscriptClass(); //수술갤러리

//======================== DB Module Postscriptt ============================
$Conn = new DBClass();

   
    $nPostscript->where = "where seq ='".$seq."'";
    $nPostscript->read_result = $Conn->AllList($nPostscript->table_name, $nPostscript, "*", $nPostscript->where, null, null);

    if(count($nPostscript->read_result) != 0){
        $nPostscript->VarList($nPostscript->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================

?>
	<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
window.onload =function(){
 CKEDITOR.replace('content', {
		enterMode:'1',
        filebrowserUploadUrl : 'http://<?=$_SERVER[HTTP_HOST]?>/ckeditor/upload.php',
        filebrowserImageUploadUrl : 'http://<?=$_SERVER[HTTP_HOST]?>/ckeditor/upload.php?command=QuickUpload&type=Images'
    });
};
</script>
    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;

            /*if(formCheckSub(f.cate_num , "exp", "성형부위") == false){ return; }

            if(formCheckSub(f.operation_name , "exp", "이름") == false){ return; }
            if(formCheckSub(f.operation_name, "inj", "이름") == false){ return; }
            if(formCheckNum(f.operation_name, "maxlen", 120, "이름") == false){ return; }

            if(f.check_del1.checked == true && f.upfile1.value == "")
            {
                if(formCheckSub(f.upfile1, "exp", "수술전 이미지") == false){ return; }
            }
            if(f.check_del2.checked == true && f.upfile2.value == "")
            {
                if(formCheckSub(f.upfile2, "exp", "수술후 이미지") == false){ return; }
            }
            if(formCheckSub(f.upfile1, "pho", "수술전 이미지") == false){ return; }
            if(formCheckSub(f.upfile2, "pho", "수술후 이미지") == false){ return; }

            */

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
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;" value="<?=$nPostscript->subject?>"/>
                    </td>
                </tr>
				<tr>
                    <th>작성자</th>
                    <td colspan="3">
                        <input type="text" name="name" style="width:100px;" value="<?=$nPostscript->name?>"/>
                    </td>
                </tr>
				<tr>
                    <th>수술부위</th>
                    <td colspan="3">
                        <?$nPostscript->ArrPostscript($nPostscript->category, "name=category", null, 'category')?>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3">
                    <textarea name="content"><?=$nPostscript->content?></textarea>
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
            <?=CateHidden()?>
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
