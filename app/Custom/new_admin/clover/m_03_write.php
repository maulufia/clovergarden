<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C3';
    
    $list_link  = 'm_03_list.php';
    $write_link = 'm_03_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

	$nClovernews   = new ClovernewsClass(); //후원기관

	$nClover   = new CloverClass();

	//======================== DB Module Clovernewst ============================
	$Conn = new DBClass();

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
                    <col style="width:100px;" />
                    <col style="width:350px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td>
                        <input type="text" name="subject" style="width:100px;"/>
                    </td>
					<th>후원기관</th>
                    <td>
                        <select name="clover_seq"/>
							<?
							for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
								$nClover->VarList($nClover->page_result, $i, null);
							?>
								<option value="<?=$nClover->code?>"><?=$nClover->subject?></option>
							<?
								}
							?>
						</select>
                    </td>
                </tr>
                <tr>
					<th>분류</th>
                    <td>
						<?$nClovernews->ArrClovernews(null, "name='category'", null, 'category')?>
					</td>
                    <th>썸네일</th>
                    <td><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nClovernews->file_mime_type[1]?> : <?=$nClovernews->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                    <th>PDF</th>
                    <td colspan="3"><input type="file" name="upfile2" size="50" /> <span class="lmits">(<?=$nClovernews->file_mime_type[2]?> : <?=$nClovernews->file_volume[2]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                <th>페이지1 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[0]" size="50"/></td>
                </tr>
				<th>페이지2 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[1]" size="50"/></td>
                </tr>
				<th>페이지3 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[2]" size="50"/></td>
                </tr>
				<th>페이지4 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[3]" size="50"/></td>
                </tr>
				<th>페이지5 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[4]" size="50"/></td>
                </tr>
				<th>페이지6 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[5]" size="50"/></td>
                </tr>
				<th>페이지7 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[6]" size="50"/></td>
                </tr>
				<th>페이지8 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[7]" size="50"/></td>
                </tr>
				<th>페이지9 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[8]" size="50"/></td>
                </tr>
				<th>페이지10 이미지</th>
                    <td colspan="3"><input type="file" name="multifile[9]" size="50"/></td>
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
