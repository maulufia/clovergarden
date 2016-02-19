<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C3';
    
    $list_link  = 'm_03_list.php';
    $edit_link = 'm_03_edit.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nClovernews   = new ClovernewsClass(); //스폰서

//======================== DB Module Clovernewst ============================
$Conn = new DBClass();

    $nClovernews->read_result = $Conn->AllList($nClovernews->table_name, $nClovernews, "*", "where seq ='".$seq."'", $nClovernews->sub_sql, null);

    if(count($nClovernews->read_result) != 0){
        $nClovernews->VarList($nClovernews->read_result, 0, array('comment'));
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
                    <th>기관명</th>
                    <td colspan="3"><?=$nClovernews->subject?></td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">기관소개</th>
                    <td colspan="3" class="content"><?=RepBr($nClovernews->content)?></td>
                </tr>
				<tr>
                    <th>기관링크</th>
                    <td colspan="3"><a href="<?=$nClovernews->url?>" target="_blank"><?=$nClovernews->url?></a></td>
                </tr>
                <tr>
                    <th>기관로고</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/sponsor/'.$nClovernews->file_edit[1])){
                            echo "<img src='../../up_file/sponsor/".$nClovernews->file_edit[1]."' border='0' width='150px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nClovernews->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nClovernews->file_real[1]."</a><font color='gray'> (".$nClovernews->file_byte[1].")</font></div>";
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
                    <a href="javascript:pageLink('<?=$nClovernews->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><img src="/new_admin/images/btn_modify.gif" alt="edit" /></a>
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
