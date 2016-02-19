<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'B5';
    $code       = 'B05';
    $list_link   = 'm_05_list.php';
    $view_link   = 'm_05_view.php';
    $edit_link   = 'm_05_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPhotocounsel = new PhotocounselClass(); //수술갤러리

//======================== DB Module Start ============================
$Conn = new DBClass();

   
    $nPhotocounsel->where = "where seq ='".$seq."'";
    $nPhotocounsel->read_result = $Conn->AllList($nPhotocounsel->table_name, $nPhotocounsel, "*", $nPhotocounsel->where, null, null);

    if(count($nPhotocounsel->read_result) != 0){
        $nPhotocounsel->VarList($nPhotocounsel->read_result, 0, null);
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
	 CKEDITOR.replace('answer', {
			enterMode:'2',
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
					<th>작성자</th>
					<td><?=$nPhotocounsel->name?></th>
					<th>조회수</th>
					<td><?=$nPhotocounsel->hit?></th>
				</tr>
				<tr>
					<th>제목</th>
					<td><font color="#52acde">[<?$nPhotocounsel->ArrPhotocounsel($nPhotocounsel->category, null, null, 'category', 1)?>]</font> <?=$nPhotocounsel->subject?></th>
					<th>작성일</th>
					<td><?=date('Y-m-d',strtotime($nPhotocounsel->reg_date))?></th>
				</tr>
				<tr>
					<th>연락처</th>
					<td><?=$nPhotocounsel->cell?></th>
					<th>이메일</th>
					<td><?=$nPhotocounsel->email?></th>
				</tr>
				<tr>
					<th>답변방법</th>
					<td colspan="3" class="check"><?$nPhotocounsel->ArrPhotocounsel($nPhotocounsel->reserve, "name='reserve[]' disabled", null, 'reserve', 'checkbox')?></td>
				</tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="2">
					<div><?=$nPhotocounsel->content?></div>
                    </td>
                </tr>
                <tr>
                    <th>파일첨부1</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/counsel/'.$nPhotocounsel->file_edit[1])){
                                echo "<img src='../../up_file/korea/counsel/".$nPhotocounsel->file_edit[1]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nPhotocounsel->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nPhotocounsel->file_real[1]."</a><font color='gray'> (".$nPhotocounsel->file_byte[1].")</font></div>";
                            }else{
								echo "첨부된 파일이 없습니다.";
							}
                        ?>
                    </td>
                </tr>
				<tr>
                    <th>파일첨부2</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/counsel/'.$nPhotocounsel->file_edit[2])){
                                echo "<img src='../../up_file/korea/counsel/".$nPhotocounsel->file_edit[2]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nPhotocounsel->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nPhotocounsel->file_real[2]."</a><font color='gray'> (".$nPhotocounsel->file_byte[2].")</font></div>";
                            }else{
								echo "첨부된 파일이 없습니다.";
							}
                        ?>
                    </td>
                </tr>
				<tr>
                    <th>파일첨부2</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/korea/counsel/'.$nPhotocounsel->file_edit[3])){
                                echo "<img src='../../up_file/korea/counsel/".$nPhotocounsel->file_edit[3]."' border='0' width='190px'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nPhotocounsel->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                                echo $nPhotocounsel->file_real[3]."</a><font color='gray'> (".$nPhotocounsel->file_byte[3].")</font></div>";
                            }else{
								echo "첨부된 파일이 없습니다.";
							}
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            

			<form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
				<div style="display:none">
				<?$nPhotocounsel->ArrPhotocounsel($nPhotocounsel->reserve, "name='reserve[]'", null, 'reserve', 'checkbox')?>
				<input type="hidden" name="phone" value="<?=$nPhotocounsel->cell?>">
				<input type="hidden" name="name" value="<?=$nPhotocounsel->name?>">
				<input type="hidden" name="email" value="<?=$nPhotocounsel->email?>">
				</div>
				<table class="bbs-write">
					<caption><?=$content_txt?></caption>
					<colgroup>
						<col style="width:100px;" />
						<col />
					</colgroup>
					<tbody>
					<tr>
						<th style="vertical-align:middle;">답변내용</th>
						<td><textarea name="answer"><?=$nPhotocounsel->answer?></textarea></th>
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
