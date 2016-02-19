<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C3';
    
    $list_link  = 'm_03_list.php';
	$edit_link  = 'm_03_edit.php';
    $view_link  = 'm_03_view.php';
    $write_link = 'm_03_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nClovernews   = new ClovernewsClass(); //후원기관
	$nClover   = new CloverClass(); //후원기관

//======================== DB Module Clovernewst ============================
$Conn = new DBClass();
	if($login_state < 7){
		$nClovernews->where = "where clover_seq = '".$login_id."'";
	}
    $nClovernews->total_record = $Conn->PageListCount
    (
        $nClovernews->table_name, $nClovernews->where, $search_key, $search_val
    );

    $nClovernews->page_result = $Conn->PageList
    (
        $nClovernews->table_name, $nClovernews, $nClovernews->where, $search_key, $search_val, 'order by seq desc', $nClovernews->sub_sql, $page_no, $nClovernews->admin_page_view, array('comment')
    );
	$nClover->page_result = $Conn->AllList
	(	
		$nClover->table_name, $nClover, "*", "where 1 order by seq desc", null, null
	);

$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val);

    if(count($nClover->page_result) > 0){
        for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
            $nClover->VarList($nClover->page_result, $i, null);
     
			${$nClover->code.'_name'}  = $nClover->subject;
    
        }
        $nClover->ArrClear();
    }

?>
    <script language="javascript">

        function sendSubmit(pType)
        {
            var f = document.send_frm;
            if(pType == "delete"){
                if(confirm("선택한 항목을 삭제하시겠습니까?")){
                    if($("input[name='delete_seq[]']:checked").length == "0"){
                        alert("삭제 항목을 한개이상 체크해주세요.");
                        return;
                    }
                    f.action = "m_03_delete_exec.php";
                    f.submit();
                }else{
                    return;
                }
            }
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
            <div class="bbs-search" style="margin-bottom:0px;">
                <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
                    <?$nClovernews->ArrClovernews(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <table class="bbs-list" style="margin:0px">
                <colgroup>
                    <col style="width:80px;" />
                    <col />
                </colgroup>
                <tr>
                    <td></td>
                    <td style="text-align:right;"><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
					<col style="width:50px;" />
                    <col style="width:50px;" />
					<col style="width:80px;" />
					<col style="width:120px;" />
					<col style="width:150px;" />
                    <col />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
                    <th>번호</th>
					<th>분류</th>
					<th>후원기관</th>
					<th>썸네일</th>
                    <th>제목</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nClovernews->page_result) > 0){
        $row_no = $nClovernews->total_record - ($nClovernews->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nClovernews->page_result); $i < $cnt_list; $i++) {
            $nClovernews->VarList($nClovernews->page_result, $i, array('comment'));
?>
                <tr>
					<td><input type="checkbox" name="delete_seq[]" value="<?=$nClovernews->seq?>" /></td>
                    <td><?=$row_no?></td>
					<td><?=$nClovernews->ArrClovernews($nClovernews->category, null, null, 'category', 1)?></td>
					<td> <a href="javascript:pageLink('<?=$nClovernews->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><?=${$nClovernews->clover_seq.'_name'}?></a></td>
					<td>
					<?php
                        if(FileExists('../../up_file/clover/'.$nClovernews->file_edit[1])){
                            echo "<img src='../../up_file/clover/".$nClovernews->file_edit[1]."' border='0' width='200px;'>";                  
                        }
                    ?>
					</td>
                    <td class="subject">
                        <!-- <a href="../../page/clover/page_1_0_admin.php?seq=<?=$nClovernews->seq?>" target="_blank"> --><a href="javascript:pageLink('<?=$nClovernews->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><?=$nClovernews->subject?></a>
                    </td>
                    <td><?=str_replace('-','.',substr($nClovernews->reg_date,0,10))?></td>                    
                </tr>
<?
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="7"><?=NO_DATA?></td>
                </tr>
<?
    }
?>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            
            </form>
            <div class="paging-area">
            <?php
                if($nClovernews->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nClovernews->total_record, $page_no, $nClovernews->admin_page_view, $nClovernews->page_set, $nClovernews->page_where, 'pageNumber');
                }
            ?>
            </div>
            <div class="btn-area tmargin">
                <div class="fleft ">
                    <a href="<?=$list_link?>"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('','','','<?=$write_link?>');"><img src="/new_admin/images/btn_write.gif" alt="writing" /></a>
                </div>
            </div>
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
            
        </form>
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>
