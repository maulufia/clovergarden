<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C4';
    $code       = 'C04';
    $list_link  = 'm_04_list.php';
    $edit_link = 'm_04_edit.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nBeforeAfter   = new BeforeAfterClass(); //성형갤러리

//======================== DB Module BeforeAftert ============================
$Conn = new DBClass();

    $nBeforeAfter->read_result = $Conn->AllList($nBeforeAfter->table_name, $nBeforeAfter, "*", "where seq ='".$seq."'", $nBeforeAfter->sub_sql, null);

    if(count($nBeforeAfter->read_result) != 0){
        $nBeforeAfter->VarList($nBeforeAfter->read_result, 0, array('comment'));
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

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
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><?=$nBeforeAfter->subject?></td>
                </tr>
				<tr>
                    <th>카테고리</th>
                    <td colspan="3">
                        <?=$nBeforeAfter->ArrBeforeAfter($nBeforeAfter->category, null, null, 'kcategory',1)?>
                    </td>
                </tr>
				<tr>
                    <th>미디어<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <?=$nBeforeAfter->media_seq?>
                    </td>
					<th>리얼스토리<br>아이디</th>
                    <td style="vertical-align: middle;">
                        <?=$nBeforeAfter->real_seq?>
                    </td>
                </tr>
				<tr>
                    <th>메인이미지</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[4])){
                            echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[4]."' border='0' width='190px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nBeforeAfter->file_real[1]."</a><font color='gray'> (".$nBeforeAfter->file_byte[4].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>정면</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[1])){
                            echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[1]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nBeforeAfter->file_real[1]."</a><font color='gray'> (".$nBeforeAfter->file_byte[1].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>45도</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[2])){
                            echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[2]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nBeforeAfter->file_real[1]."</a><font color='gray'> (".$nBeforeAfter->file_byte[2].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>측면</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nBeforeAfter->file_edit[3])){
                            echo "<img src='../../up_file/korea/bna/".$nBeforeAfter->file_edit[3]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nBeforeAfter->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nBeforeAfter->file_real[1]."</a><font color='gray'> (".$nBeforeAfter->file_byte[3].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('<?=$nBeforeAfter->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><img src="/new_admin/images/btn_modify.gif" alt="edit" /></a>
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
