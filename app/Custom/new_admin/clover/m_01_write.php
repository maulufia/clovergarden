<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C1';
    
    $list_link  = 'm_01_list.php';
    $write_link = 'm_01_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

	$nClover = new CloverClass();

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
			<input type="hidden" name="view_n" value="1">
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
                    <th>기관명</th>
                    <td>
                        <input type="text" name="subject" style="width:100px;"/>
                    </td>
					<th>구분</th>
                    <td>
                        <?$nClover->ArrClover(null, "name='category'", null, 'category')?>
                    </td>
                </tr>
				<tr>
					<th>고유코드</th>
                    <td colspan="3">
                       <input type="text" name="code" style="width:100px;"/>
                    </td>
                </tr>
				<tr>
					<th>온도계설정</th>
                    <td colspan="3">
						<select name="hot">
							<option value="1" selected>1도
							<option value="2">2도
							<option value="3">3도
						</select>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3">
                    <textarea name="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>메인이미지</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nClover->file_mime_type[1]?> : <?=$nClover->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                    <th>내용이미지</th>
                    <td colspan="3"><input type="file" name="upfile2" size="50" /> <span class="lmits">(<?=$nClover->file_mime_type[1]?> : <?=$nClover->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
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
