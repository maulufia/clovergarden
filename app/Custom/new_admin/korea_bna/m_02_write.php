<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C2';
    $code       = 'C02';
    $list_link  = 'm_02_list.php';
    $write_link = 'm_02_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

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
                    <th>이름</th>
                    <td colspan="3">
                        <input type="text" name="name" style="width:100px;"/>
                    </td>
                </tr>
				<tr>
                    <th>수술부위</th>
                    <td colspan="3">
                        <input type="text" name="surgery" style="width:150px;"/>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
                    <textarea name="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>수술전이미지</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nSelf->file_mime_type[1]?> : <?=$nSelf->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
                </tr> 
				<tr>
                    <th>수술후이미지</th>
                    <td colspan="3"><input type="file" name="upfile2" size="50" /> <span class="lmits">(<?=$nSelf->file_mime_type[2]?> : <?=$nSelf->file_volume[2]?><?=LOW_FILESIZE?>)</span></td>
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
