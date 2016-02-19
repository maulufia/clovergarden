<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C2';
    
    $list_link  = 'm_02_popup.php';
    $view_link  = 'm_02_view.php';
    $write_link = 'm_02_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

	$clover_seq       = $_REQUEST['clover_seq'];
	$group  = $_REQUEST['group'];

    $nClovermlist   = new ClovermlistClass(); //후원기관

//======================== DB Module Clovermlistt ============================
$Conn = new DBClass();

	if($group!=null)
	$group_where = " and group_name like '".$group."'";

	$nClovermlist->where = " where clover_seq like '".$clover_seq."'".$group_where;

    $nClovermlist->total_record = $Conn->PageListCount
    (
        $nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
    );

    $nClovermlist->page_result = $Conn->PageList
    (
        $nClovermlist->table_name, $nClovermlist, $nClovermlist->where, $search_key, $search_val, 'order by seq desc', $nClovermlist->sub_sql, $page_no, $nClovermlist->admin_page_view, array('comment','clover')
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val);

?>
</head>
<body>
<!-- wrapper -->
<div id="wrapper">
    <!-- container -->
    <div id="container" style="width: 100%;">
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
					<col style="width:50px;" />
					<col style="width:100px;" />
                    <col style="width:80px;" />		
					<col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
					<th>후원구분</th>
                    <th>기부자</th>
					<th>후원금</th>
                                  
                </tr>
                </thead>
                <tbody>
					<?php

						if(count($nClovermlist->page_result) > 0){
							$row_no = $nClovermlist->total_record - ($nClovermlist->admin_page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
								$nClovermlist->VarList($nClovermlist->page_result, $i, array('comment','clover'));

					?>
									<tr>
										<td><?=$row_no?></td>
										<td>	
											<?if($nClovermlist->day) echo "정기후원"; else echo "일시후원";?>
										</td>
										<td>	
											<?=$nClovermlist->name?>
										</td>
										<td>
											<?=$nClovermlist->price?>
										</td>              
									</tr>
					<?
								$row_no = $row_no - 1;
							}
						}else{
					?>
						<tr>
							<td colspan="4"><?=NO_DATA?></td>
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
                if($nClovermlist->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nClovermlist->total_record, $page_no, $nClovermlist->admin_page_view, $nClovermlist->page_set, $nClovermlist->page_where, 'pageNumber');
                }
            ?>
            </div>
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
			<input type="hidden" name="clover_seq" value="<?=$clover_seq?>">
			<input type="hidden" name="group_name" value="<?=$group?>">
            <?=SubmitHidden()?>            
        </form>
    </div>
    <!-- container -->
</div>
<!-- //wrapper -->
</body>
</html>
