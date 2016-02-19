<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C1';
    $code       = 'C01';
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

    $nReal   = new RealClass(); //성형갤러리

//======================== DB Module Realt ============================
$Conn = new DBClass();

    $nReal->read_result = $Conn->AllList($nReal->table_name, $nReal, "*", "where seq ='".$seq."'", $nReal->sub_sql, null);

    if(count($nReal->read_result) != 0){
        $nReal->VarList($nReal->read_result, 0, array('comment'));
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
                    <col style="width:100px;" />
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><?=$nReal->subject?></td>
                </tr>
                </tr>
				<tr>
                    <th>썸네일</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nReal->file_edit[4])){
                            echo "<img src='../../up_file/korea/bna/".$nReal->file_edit[4]."' border='0' width='190px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nReal->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nReal->file_real[1]."</a><font color='gray'> (".$nReal->file_byte[4].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>좌측이미지</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nReal->file_edit[1])){
                            echo "<img src='../../up_file/korea/bna/".$nReal->file_edit[1]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nReal->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nReal->file_real[1]."</a><font color='gray'> (".$nReal->file_byte[1].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>우측이미지</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nReal->file_edit[2])){
                            echo "<img src='../../up_file/korea/bna/".$nReal->file_edit[2]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nReal->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nReal->file_real[1]."</a><font color='gray'> (".$nReal->file_byte[2].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th>상단이미지</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/korea/bna/'.$nReal->file_edit[3])){
                            echo "<img src='../../up_file/korea/bna/".$nReal->file_edit[3]."' border='0' width='250px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nReal->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nReal->file_real[1]."</a><font color='gray'> (".$nReal->file_byte[3].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content"><?=$nReal->content?></td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('<?=$nReal->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><img src="/new_admin/images/btn_modify.gif" alt="edit" /></a>
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
