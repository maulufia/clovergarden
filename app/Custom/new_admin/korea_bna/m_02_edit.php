<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C2';
    $code       = 'C02';
    $list_link   = 'm_02_list.php';
    $view_link   = 'm_02_view.php';
    $edit_link   = 'm_02_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nSelf = new SelfClass(); //수술갤러리

//======================== DB Module Selft ============================
$Conn = new DBClass();

   
    $nSelf->where = "where seq ='".$seq."'";
    $nSelf->read_result = $Conn->AllList($nSelf->table_name, $nSelf, "*", $nSelf->where, null, null);

    if(count($nSelf->read_result) != 0){
        $nSelf->VarList($nSelf->read_result, 0, null);
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
                    <th>이름</th>
                    <td colspan="3">
                        <input type="text" name="name" style="width:300px;" value="<?=$nSelf->name?>"/>
                    </td>
                </tr>
				<tr>
                    <th>수술부위</th>
                    <td colspan="3">
                        <input type="text" name="surgery" style="width:150px;" value="<?=$nSelf->surgery?>"/>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
                    <textarea name="content"><?=$nSelf->content?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>수술전이미지</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/self/'.$nSelf->file_edit[1])){
                                echo "<img src='../../up_file/korea/self/".$nSelf->file_edit[1]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nSelf->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nSelf->file_real[1]."</a><font color='gray'> (".$nSelf->file_byte[1].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile1" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/self/'.$nSelf->file_edit[1])){
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nSelf->file_mime_type[1]?> : <?=$nSelf->file_volume[1]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
				<tr>
                    <th>수술후이미지</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/self/'.$nSelf->file_edit[2])){
                                echo "<img src='../../up_file/korea/self/".$nSelf->file_edit[2]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nSelf->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nSelf->file_real[1]."</a><font color='gray'> (".$nSelf->file_byte[2].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile2" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/self/'.$nSelf->file_edit[2])){
                                echo "<input type='checkbox' name='check_del2' value='2' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del2' value='2' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nSelf->file_mime_type[2]?> : <?=$nSelf->file_volume[2]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            <input type="hidden" name="file_real1" value="<?=$nSelf->file_real[1]?>"/>
            <input type="hidden" name="file_edit1" value="<?=$nSelf->file_edit[1]?>"/>
            <input type="hidden" name="file_byte1" value="<?=$nSelf->file_byte[1]?>"/>
			<input type="hidden" name="file_real2" value="<?=$nSelf->file_real[2]?>"/>
            <input type="hidden" name="file_edit2" value="<?=$nSelf->file_edit[2]?>"/>
            <input type="hidden" name="file_byte2" value="<?=$nSelf->file_byte[2]?>"/>
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
