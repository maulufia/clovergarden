<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A4';
    $list_link  = 'm_04_list.php';
    $write_link = 'email_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nEmail = new EmailClass(); //회원







/*
$to = "kdhlove741@naver.com";
$subject = "HTML email";

$message = '
<img src="http://clovergarden.co.kr/nimg/151026_DM.jpg" width="800" height="3454" border="0" usemap="#Map" />
<map name="Map" id="Map">
  <area shape="rect" coords="21,95,150,130" href="http://naver.com" target="_self" />
</map>
';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <manager@clovergarden.co.kr>' . "\r\n";

mail($to,$subject,$message,$headers);
*/

?>

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

<script language="javascript">

	function sendSubmit()
	{
		var f = document.frm;

		$.blockUI();
		f.action = "<?=$write_link?>";
		f.submit();
	}
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

				<input type="hidden" name="list_link" value="<?=$list_link?>">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                </colgroup>
                <tbody>
                <tr>
                    <th>받는사람</th>
                    <td colspan="3">
						<?
						if($_POST[s_mode] == "ch_send"){
						$ex_email = explode("[@@]", $_POST[s_email]);
						$ex_name = explode("[@@]", $_POST[s_name]);
						?>
							<input type="hidden" name="sentall" value="sentall">
							<input type="hidden" name="cktype" value="<?=$_GET[cktype]?>">

							<?
							for($i=0; $i<count($ex_name); $i++){
							?>
							<input type="hidden" name="name[]" value="<?=$ex_name[$i]?>">
							<input type="hidden" name="email[]" value="<?=$ex_email[$i]?>">
							<?=$ex_name[$i]?> [<?=$ex_email[$i]?>] <BR>
							<?}?>
						<?} else {?>
                        <?=$_GET[name]?> [<?=$_GET[email]?>]
						<?}?>
                    </td>
                </tr>
				<tr>
					<th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:150px;"/>
                    </td>
				</tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3">
                    <textarea name="content"></textarea>
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