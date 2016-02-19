<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C4';
    $code       = 'C04';
    $list_link  = 'm_04_list.php';
    $write_link = 'm_04_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

	$nBeforeAfter   = new BeforeAfterClass(); //성형갤러리

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
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;"/>
                    </td>
                </tr>
				<tr>
                    <th>카테고리</th>
                    <td colspan="3">
                         <?=$nBeforeAfter->ArrBeforeAfter(0, 'name="category"', null, 'kcategory', null)?>
                    </td>
                </tr>
				<tr>
                    <th>미디어<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <input type="text" name="media_seq" style="width:50px;"/>
                    </td>
					<th>리얼스토리<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <input type="text" name="real_seq" style="width:50px;" />
                    </td>
                </tr>
				<tr>
                    <th>메인이미지</th>
                    <td colspan="3"><input type="file" name="upfile4" size="50" /> <span class="lmits">(<?=$nBeforeAfter->file_mime_type[4]?> : <?=$nBeforeAfter->file_volume[4]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                    <th>정면</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nBeforeAfter->file_mime_type[1]?> : <?=$nBeforeAfter->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                    <th>45도</th>
                    <td colspan="3"><input type="file" name="upfile2" size="50" /> <span class="lmits">(<?=$nBeforeAfter->file_mime_type[2]?> : <?=$nBeforeAfter->file_volume[2]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
				<tr>
                    <th>측면</th>
                    <td colspan="3"><input type="file" name="upfile3" size="50" /> <span class="lmits">(<?=$nBeforeAfter->file_mime_type[3]?> : <?=$nBeforeAfter->file_volume[3]?><?=LOW_FILESIZE?>)</span></td>
                </tr>

          
                </tbody>
            </table>
            <?=SubmitHidden()?>
            <?=CateHidden()?>
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
