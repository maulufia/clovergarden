<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'H1';
    
    $list_link  = 'm_01_list.php';
    $edit_link = 'm_01_edit.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nSchedule   = new ScheduleClass(); //자유게시판
	$nSchedulepeo   = new SchedulepeoClass(); //자유게시판

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", "where seq ='".$seq."'", $nSchedule->sub_sql, null);

    if(count($nSchedule->read_result) != 0){
        $nSchedule->VarList($nSchedule->read_result, 0, array('comment'));
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

	$nSchedulepeo->page_result = $Conn->AllList
	(	
		$nSchedulepeo->table_name, $nSchedulepeo, "*", "where schedule_seq='".$nSchedule->seq."' order by seq desc", null, null
	);


$Conn->DisConnect();
//======================== DB Module End ===============================

?>
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

             <table class="bbs-view">
                <caption><?=$content_txt?></caption>
                 <colgroup>
                    <col style="width:120px;" />
                    <col style="width:500px;" />
                    <col style="width:120px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td><?=$nSchedule->subject?></td>
					<th>조회수</th>
                    <td><?=$nSchedule->hit?></td>
                </tr>
				<tr>
                    <th>봉사일</th>
                    <td colspan="3"><?=$nSchedule->work_date?></td>
                </tr>
				<tr>
                    <th>신청마감일</th>
                    <td colspan="3"><?=$nSchedule->start_date2?></td>
                </tr>
                <tr>
                    <th>모집인원/필요인원</th>
                    <td><?=count($nSchedulepeo->page_result)?>/<?=$nSchedule->people?></td>
					<th>작성일</th>
                    <td><?=str_replace('-','.',$nSchedule->reg_date)?></td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content"><?=$nSchedule->content?></td>
                </tr>
				<tr>
                    <th>이미지</th>
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
                    </td>
                </tr>
				<tr>
					<th>접수자</th>
					<td colspan="3">


						<ul>
						<?
						if(count($nSchedulepeo->page_result)>0){
						for($i=0, $cnt_list=count($nSchedulepeo->page_result); $i < $cnt_list; $i++) {
							$nSchedulepeo->VarList($nSchedulepeo->page_result, $i, null);
						?>
						<li><?=$nSchedulepeo->name?> (<?=$nSchedulepeo->phone?>) 신청일 - <?=$nSchedulepeo->reg_date?></li>
						<?
							}
						}else{
						?>
							<li style="width:100%;">신청인원이 없습니다.</li>
						<?
							}				
						?>
						</ul>


					</td>
				</tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('<?=$nSchedule->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><img src="/new_admin/images/btn_modify.gif" alt="edit" /></a>
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
