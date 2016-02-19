<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'H1';
    
    $list_link   = 'm_01_list.php';
    $view_link   = 'm_01_view.php';
    $edit_link   = 'm_01_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nSchedule = new ScheduleClass(); //자유게시판

//======================== DB Module Start ============================
$Conn = new DBClass();

   
    $nSchedule->where = "where seq ='".$seq."'";
    $nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);

    if(count($nSchedule->read_result) != 0){
        $nSchedule->VarList($nSchedule->read_result, 0, null);
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

		if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
		if(formCheckSub(f.subject, "inj", "제목") == false){ return; }


		if(formCheckSub(f.clover_seq , "exp", "후원기관코드") == false){ return; }
		if(formCheckSub(f.clover_seq, "inj", "후원기관코드") == false){ return; }


		if(formCheckSub(f.work_date , "exp", "봉사일") == false){ return; }
		if(formCheckSub(f.work_date, "inj", "봉사일") == false){ return; }
	

            $.blockUI();
            f.action = "<?=$edit_link?>";
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
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:300px;" value="<?=$nSchedule->subject?>"/>
                    </td>
                </tr>
				<tr>
                    <th>후원기관코드</th>
                    <td colspan="3">
                        <input type="text" name="clover_seq" style="width:100px;" value="<?=$nSchedule->clover_seq?>"/>
                    </td>
                </tr>
				<tr>
                    <th>필요인원</th>
                    <td colspan="3">
                        <input type="text" name="people" style="width:100px;" value="<?=$nSchedule->people?>"/>
                    </td>
                </tr>


				<tr>
                    <th>봉사일</th>
                    <td colspan="3">
                        <input type="text" name="work_date" id='work_date' value="<?=$nSchedule->work_date?>" style="width:100px;cursor:pointer;" readonly/>
                    </td>
                </tr>

				<tr>
                    <th>신청마감일</th>
                    <td colspan="3">
                        <input type="text" name="start_date" value="<?=$nSchedule->start_date?>" style="width:100px;cursor:pointer;display:none;" readonly/>
						
                        <input type="text" name="start_date2" id='start_date2' value="<?=$nSchedule->start_date2?>" style="width:100px;cursor:pointer;" readonly/>
                    </td>
                </tr>

				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
					<textarea name="content"><?=$nSchedule->content?></textarea>
                    </td>
                </tr>
				<tr>
                    <th>기관로고</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/schedule/'.$nSchedule->file_edit[1])){
                                echo "<img src='../../up_file/schedule/".$nSchedule->file_edit[1]."' border='0' width='150px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nSchedule->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nSchedule->file_real[1]."</a><font color='gray'> (".$nSchedule->file_byte[1].")</font></div>";
                            }else{
								echo "<img src='/common/img/no-image.jpg' alt='no image' width='150'>";
							}
                        ?>
                        <input type="file" name="upfile1" size="50" />
                        <?php
                            if(FileExists('../../up_file/schedule/'.$nSchedule->file_edit[1])){
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nSchedule->file_mime_type[1]?> : <?=$nSchedule->file_volume[1]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <?=SubmitHidden()?>
			<input type="hidden" name="file_real1" value="<?=$nSchedule->file_real[1]?>"/>
            <input type="hidden" name="file_edit1" value="<?=$nSchedule->file_edit[1]?>"/>
            <input type="hidden" name="file_byte1" value="<?=$nSchedule->file_byte[1]?>"/>
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
