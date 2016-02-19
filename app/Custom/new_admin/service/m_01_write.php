<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'H1';
    
    $list_link  = 'm_01_list.php';
    $write_link = 'm_01_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>

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

		if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
		if(formCheckSub(f.subject, "inj", "제목") == false){ return; }


		if(formCheckSub(f.clover_seq , "exp", "후원기관코드") == false){ return; }
		if(formCheckSub(f.clover_seq, "inj", "후원기관코드") == false){ return; }


		if(formCheckSub(f.work_date , "exp", "봉사일") == false){ return; }
		if(formCheckSub(f.work_date, "inj", "봉사일") == false){ return; }


		$.blockUI();
		f.action = "<?=$write_link?>";
		f.submit();
	}



	$(document).ready(function()
	{
		$('#start_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});         
		$.datepicker.regional['ko'] = {
			closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
			dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);
	});


	$(document).ready(function()
	{
		$('#start_date2').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});         
		$.datepicker.regional['ko'] = {
			closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
			dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);
	});

	$(document).ready(function()
	{
		$('#work_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});         
		$.datepicker.regional['ko'] = {
			closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
			dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);
	});

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
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;"/>
                    </td>
                </tr>

				<?
				if($login_state < 7){
					$clover_id = $group_name;
				}
				?>
				<tr>
                    <th>후원기관코드</th>
                    <td colspan="3">
                        <input type="text" name="clover_seq" style="width:100px;" value="<?=$clover_id?>" <?if($login_state < 7){?> readonly<?}?> />
                    </td>
                </tr>
				<tr>
                    <th>필요인원</th>
                    <td colspan="3">
                        <input type="text" name="people" style="width:100px;" value=""/>
                    </td>
                </tr>
				<tr>
                    <th>봉사일</th>
                    <td colspan="3">
                        <input type="text" name="work_date" id='work_date' value="<?=$nSponsor->date?>" style="width:100px;cursor:pointer;" readonly/>
                    </td>
                </tr>

				<tr>
                    <th>신청마감일</th>
                    <td colspan="3">
                        <input type="text" name="start_date" value="<?=date('Y-m-d');?>" style="width:100px;cursor:pointer;display:none;" readonly/>
						
                        <input type="text" name="start_date2" id='start_date2' value="<?=$nSponsor->date?>" style="width:100px;cursor:pointer;" readonly/>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
                    <textarea name="content"></textarea>
                    </td>
                </tr>
				<tr>
                    <th>이미지</th>
                    <td colspan="3"><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nSponsor->file_mime_type[1]?> : <?=$nSponsor->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
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
