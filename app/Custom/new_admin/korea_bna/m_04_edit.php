<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C4';
    $code       = 'C04';
    $list_link   = 'm_04_list.php';
    $view_link   = 'm_04_view.php';
    $edit_link   = 'm_04_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nBeforeAfter = new BeforeAfterClass(); //수술갤러리

//======================== DB Module BeforeAftert ============================
$Conn = new DBClass();

   
    $nBeforeAfter->where = "where seq ='".$seq."'";
    $nBeforeAfter->read_result = $Conn->AllList($nBeforeAfter->table_name, $nBeforeAfter, "*", $nBeforeAfter->where, null, null);

    if(count($nBeforeAfter->read_result) != 0){
        $nBeforeAfter->VarList($nBeforeAfter->read_result, 0, null);
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
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;" value="<?=$nBeforeAfter->subject?>"/>
                    </td>
                </tr>
				<tr>
                    <th>카테고리</th>
                    <td colspan="3">
                        <?=$nBeforeAfter->ArrBeforeAfter($nBeforeAfter->category, 'name="category"', null, 'kcategory', null)?>
                    </td>
                </tr>
				<tr>
                    <th>미디어<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <input type="text" name="media_seq" style="width:50px;" value="<?=$nBeforeAfter->media_seq?>"/>
                    </td>
					<th>리얼스토리<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <input type="text" name="real_seq" style="width:50px;" value="<?=$nBeforeAfter->real_seq?>"/>
                    </td>
                </tr>
				<tr>
                    <th>메인이미지</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[4])){
                                echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[4]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nBeforeAfter->file_bna[4]."</a><font color='gray'> (".$nBeforeAfter->file_byte[4].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile4" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[4])){
                                echo "<input type='checkbox' name='check_del4' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del4' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nBeforeAfter->file_mime_type[4]?> : <?=$nBeforeAfter->file_volume[4]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
				<tr>
                    <th>정면</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[1])){
                                echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[1]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nBeforeAfter->file_bna[1]."</a><font color='gray'> (".$nBeforeAfter->file_byte[1].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile1" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[1])){
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nBeforeAfter->file_mime_type[1]?> : <?=$nBeforeAfter->file_volume[1]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
				<tr>
                    <th>45도</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[2])){
                                echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[2]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nBeforeAfter->file_bna[2]."</a><font color='gray'> (".$nBeforeAfter->file_byte[2].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile2" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[2])){
                                echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nBeforeAfter->file_mime_type[2]?> : <?=$nBeforeAfter->file_volume[2]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
				<tr>
                    <th>측면</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[3])){
                                echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[3]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nBeforeAfter->file_bna[3]."</a><font color='gray'> (".$nBeforeAfter->file_byte[3].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile3" size="50" />
                        <?php
                            if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[3])){
                                echo "<input type='checkbox' name='check_del3' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del3' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nBeforeAfter->file_mime_type[3]?> : <?=$nBeforeAfter->file_volume[3]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            <input type="hidden" name="file_real1" value="<?=$nBeforeAfter->file_real[1]?>"/>
            <input type="hidden" name="file_edit1" value="<?=$nBeforeAfter->file_edit[1]?>"/>
            <input type="hidden" name="file_byte1" value="<?=$nBeforeAfter->file_byte[1]?>"/>
			<input type="hidden" name="file_real2" value="<?=$nBeforeAfter->file_real[2]?>"/>
            <input type="hidden" name="file_edit2" value="<?=$nBeforeAfter->file_edit[2]?>"/>
            <input type="hidden" name="file_byte2" value="<?=$nBeforeAfter->file_byte[2]?>"/>
			<input type="hidden" name="file_real3" value="<?=$nBeforeAfter->file_real[3]?>"/>
            <input type="hidden" name="file_edit3" value="<?=$nBeforeAfter->file_edit[3]?>"/>
            <input type="hidden" name="file_byte3" value="<?=$nBeforeAfter->file_byte[3]?>"/>
			<input type="hidden" name="file_real4" value="<?=$nBeforeAfter->file_real[4]?>"/>
            <input type="hidden" name="file_edit4" value="<?=$nBeforeAfter->file_edit[4]?>"/>
            <input type="hidden" name="file_byte4" value="<?=$nBeforeAfter->file_byte[4]?>"/>
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
