<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C3';
    
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

    $nClovernews = new ClovernewsClass(); //후원기관
	$nClover   = new CloverClass();

//======================== DB Module Clovernewst ============================
$Conn = new DBClass();

   
    $nClovernews->where = "where seq ='".$seq."'";
    $nClovernews->read_result = $Conn->AllList($nClovernews->table_name, $nClovernews, "*", $nClovernews->where, null, null);

    if(count($nClovernews->read_result) != 0){
        $nClovernews->VarList($nClovernews->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }
	$nClover->total_record = $Conn->PageListCount
	(
		$nClover->table_name, $nClover->where, $search_key, $search_val
	);

	$nClover->page_result = $Conn->AllList
	(	
		$nClover->table_name, $nClover, "*", "where 1 order by seq desc limit ".$nClover->total_record, null, null
	);

$Conn->DisConnect();
//======================== DB Module End ===============================
if(count($nClover->page_result) != 0){
	$nClover->VarList($nClover->page_result, 0, array('comment'));
}
?>

    <script language="javascript">

        function sendSubmit()
        {
            var f = document.frm;


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
                    <td>
                        <input type="text" name="subject" style="width:100px;" value="<?=$nClovernews->subject?>"/>
                    </td>
					<th>후원기관</th>
                    <td>
                        <select name="clover_seq"/>
							<?
							for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
								$nClover->VarList($nClover->page_result, $i, null);
								if($nClovernews->clover_seq==$nClover->code) $checked = "selected"; else $checked = "";
							?>
								<option value="<?=$nClover->code?>" <?=$checked?>><?=$nClover->subject?></option>
							<?
								}
							?>
						</select>
                    </td>
                </tr>        
				<tr>
                    <th>썸네일</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clover/'.$nClovernews->file_edit[1])){
							echo "<img src='../../up_file/clover/".$nClovernews->file_edit[1]."' border='0' width='150px'>";
							echo "<div style='padding-top:20px;padding-bottom:0px;'>";
							echo $nClovernews->file_real[1]."</a><font color='gray'> (".$nClovernews->file_byte[1].")</font></div>";
						}
					?>
					<input type="file" name="upfile1" size="50" />
					<?php
						if(FileExists('../../up_file/clover/'.$nClovernews->file_edit[1])){
							echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}else{
							echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
						}
					?>
					</td>
                </tr>
				<tr>
                    <th>PDF</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clover/'.$nClovernews->file_edit[2])){
							echo $nClovernews->file_real[1]."</a><font color='gray'> (".$nClovernews->file_byte[1].")</font></div>";
						}
					?>
					<input type="file" name="upfile2" size="50" />
					<?php
						if(FileExists('../../up_file/clover/'.$nClovernews->file_edit[2])){
							echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}else{
							echo "<input type='checkbox' name='check_del2' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
						}
					?>
					</td>
                </tr>
				<tr>
				<?
				$multifile_edit = explode(",",$nClovernews->multifile_edit);
				$multifile_real = explode(",",$nClovernews->multifile_real);
				$multifile_byte = explode(",",$nClovernews->multifile_byte);
				
				?>
                <th>페이지1 이미지</th>
                    <td colspan="3">	
					
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[0])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[0]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del0' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[0]" size="50"/>
					</td>
                </tr>
				<th>페이지2 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[1])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[1]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[1]" size="50"/>
					</td>
                </tr>
				<th>페이지3 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[2])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[2]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del2' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[2]" size="50"/>
					</td>
                </tr>
				<th>페이지4 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[3])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[3]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del3' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[3]" size="50"/>
					</td>
                </tr>
				<th>페이지5 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[4])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[4]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del4' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[4]" size="50"/>
					</td>
                </tr>
				<th>페이지6 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[5])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[5]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del5' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[5]" size="50"/>
					</td>
                </tr>
				<th>페이지7 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[6])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[6]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del6' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[6]" size="50"/>
					</td>
                </tr>
				<th>페이지8 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[7])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[7]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del7' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[7]" size="50"/>
					</td>
                </tr>
				<th>페이지9 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[8])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[8]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del8' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[8]" size="50"/>
					</td>
                </tr>
				<th>페이지10 이미지</th>
                    <td colspan="3">
					<?php
						if(FileExists('../../up_file/clovernews/'.$multifile_edit[9])){
							echo "<img src='../../up_file/clovernews/".$multifile_edit[9]."' border='0' width='150px'>";
							echo "<input type='checkbox' name='check_multi_del9' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
						}
					?>
					<input type="file" name="multifile[9]" size="50"/>
					</td>
                </tr>

                </tbody>
            </table>
            <?=SubmitHidden()?>
            <input type="hidden" name="file_real1" value="<?=$nClovernews->file_real[1]?>"/>
            <input type="hidden" name="file_edit1" value="<?=$nClovernews->file_edit[1]?>"/>
            <input type="hidden" name="file_byte1" value="<?=$nClovernews->file_byte[1]?>"/>
			<input type="hidden" name="file_real2" value="<?=$nClovernews->file_real[2]?>"/>
            <input type="hidden" name="file_edit2" value="<?=$nClovernews->file_edit[2]?>"/>
            <input type="hidden" name="file_byte2" value="<?=$nClovernews->file_byte[2]?>"/>
			<input type="hidden" name="multifile_real[0]" value="<?=$multifile_real[0]?>"/>
            <input type="hidden" name="multifile_edit[0]" value="<?=$multifile_edit[0]?>"/>
            <input type="hidden" name="multifile_byte[0]" value="<?=$multifile_byte[0]?>"/>
			<input type="hidden" name="multifile_real[1]" value="<?=$multifile_real[1]?>"/>
            <input type="hidden" name="multifile_edit[1]" value="<?=$multifile_edit[1]?>"/>
            <input type="hidden" name="multifile_byte[1]" value="<?=$multifile_byte[1]?>"/>
			<input type="hidden" name="multifile_real[2]" value="<?=$multifile_real[2]?>"/>
            <input type="hidden" name="multifile_edit[2]" value="<?=$multifile_edit[2]?>"/>
            <input type="hidden" name="multifile_byte[2]" value="<?=$multifile_byte[2]?>"/>
			<input type="hidden" name="multifile_real[3]" value="<?=$multifile_real[3]?>"/>
            <input type="hidden" name="multifile_edit[3]" value="<?=$multifile_edit[3]?>"/>
            <input type="hidden" name="multifile_byte[3]" value="<?=$multifile_byte[3]?>"/>
			<input type="hidden" name="multifile_real[4]" value="<?=$multifile_real[4]?>"/>
            <input type="hidden" name="multifile_edit[4]" value="<?=$multifile_edit[4]?>"/>
            <input type="hidden" name="multifile_byte[4]" value="<?=$multifile_byte[4]?>"/>
			<input type="hidden" name="multifile_real[5]" value="<?=$multifile_real[5]?>"/>
            <input type="hidden" name="multifile_edit[5]" value="<?=$multifile_edit[5]?>"/>
            <input type="hidden" name="multifile_byte[5]" value="<?=$multifile_byte[5]?>"/>
			<input type="hidden" name="multifile_real[6]" value="<?=$multifile_real[6]?>"/>
            <input type="hidden" name="multifile_edit[6]" value="<?=$multifile_edit[6]?>"/>
            <input type="hidden" name="multifile_byte[6]" value="<?=$multifile_byte[6]?>"/>
			<input type="hidden" name="multifile_real[7]" value="<?=$multifile_real[7]?>"/>
            <input type="hidden" name="multifile_edit[7]" value="<?=$multifile_edit[7]?>"/>
            <input type="hidden" name="multifile_byte[7]" value="<?=$multifile_byte[7]?>"/>
			<input type="hidden" name="multifile_real[8]" value="<?=$multifile_real[8]?>"/>
            <input type="hidden" name="multifile_edit[8]" value="<?=$multifile_edit[8]?>"/>
            <input type="hidden" name="multifile_byte[8]" value="<?=$multifile_byte[8]?>"/>
			<input type="hidden" name="multifile_real[9]" value="<?=$multifile_real[9]?>"/>
            <input type="hidden" name="multifile_edit[9]" value="<?=$multifile_edit[9]?>"/>
            <input type="hidden" name="multifile_byte[9]" value="<?=$multifile_byte[9]?>"/>
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
