<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C6';
    
    $list_link  = 'm_06_list.php';
    $edit_link = 'm_06_edit.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nClover   = new CloverClass(); //성형갤러리
	$nClovermlist   = new ClovermlistClass(); //성형갤러리

//======================== DB Module Clovert ============================
$Conn = new DBClass();
	$nClover->table_name = $nClover->table_name."_banner";
    $nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", "where seq ='".$seq."'", $nClover->sub_sql, null);

    if(count($nClover->read_result) != 0){
        $nClover->VarList($nClover->read_result, 0, array('comment'));
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

	$nClovermlist->where = "where clover_seq='".$nClover->code."'";
	$nClovermlist->total_record = $Conn->PageListCount
    (
        $nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
    );

	$nClovermlist->page_result = $Conn->AllList
	(	
		$nClovermlist->table_name, $nClovermlist, "*", "where 1 order by seq desc limit ".$nClovermlist->total_record."", null, null
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
                    <col style="width:100px;" />
                    <col style="width:500px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><?=$nClover->subject?></td>
                </tr>
                <tr>
                    <th>배너이미지</th>
                    <td colspan="3">
                    <?php
                        if(FileExists('../../up_file/clover/'.$nClover->file_edit[1])){
                            echo "<img src='../../up_file/clover/".$nClover->file_edit[1]."' border='0' width='150px'>";
                            echo "<div style='padding-top:20px;padding-bottom:0px;'>";
                            echo "<a href=".Chr(34)."javascript:downFile('".$nClover->seq."','".$code."','1','../../_db_file/_file_operation.php')".Chr(34).">";
                            echo $nClover->file_real[1]."</a><font color='gray'> (".$nClover->file_byte[1].")</font></div>";
                        }
                    ?>
                    </td>
                </tr>

				<tr>
                    <th>이미지URL</th>
                    <td colspan="3">
                    <?=$nClover->group_name?>
                    </td>
                </tr>
				<tr>
                    <th>참여하기URL</th>
                    <td colspan="3">
                    <?=$nClover->news?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('<?=$nClover->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><img src="/new_admin/images/btn_modify.gif" alt="edit" /></a>
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
