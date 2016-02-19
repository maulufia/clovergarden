<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'E3';
    
    $list_link  = 'm_03_list.php';
    $view_link  = 'm_03_view.php';
    $write_link = 'm_03_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nFaq   = new FaqClass(); //자주묻는질문

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nFaq->total_record = $Conn->PageListCount
    (
        $nFaq->table_name, $nFaq->where, $search_key, $search_val
    );

    $nFaq->page_result = $Conn->PageList
    (
        $nFaq->table_name, $nFaq, $nFaq->where, $search_key, $search_val, 'order by seq desc', $nFaq->sub_sql, $page_no, $nFaq->admin_page_view, array('comment')
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val);

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
                    <?$nFaq->ArrFaq(null, "name='search_key'", null, 'search')?>
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
                    <col />
                    <col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>조회수</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
				<?php
					if(count($nFaq->page_result) > 0){
						$row_no = $nFaq->total_record - ($nFaq->admin_page_view * ($page_no - 1));
						for($i=0, $cnt_list=count($nFaq->page_result); $i < $cnt_list; $i++) {
							$nFaq->VarList($nFaq->page_result, $i, array('comment'));
				?>
								<tr>
									<td><input type="checkbox" name="delete_seq[]" value="<?=$nFaq->seq?>" /></td>
									<td><?=$row_no?></td>
									<td class="subject">
										<a href="javascript:pageLink('<?=$nFaq->seq?>','<?=$row_no?>','view','<?=$view_link?>');"><?=$nFaq->subject?></a>
									</td>
									<td><?=$nFaq->writer_name?></td>
									<td><?=$nFaq->hit?></td>
									<td><?=str_replace('-','.',substr($nFaq->reg_date,0,10))?></td>                    
								</tr>
				<?
							$row_no = $row_no - 1;
						}
					}else{
				?>
								<tr>
									<td colspan="6"><?=NO_DATA?></td>
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
                if($nFaq->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nFaq->total_record, $page_no, $nFaq->admin_page_view, $nFaq->page_set, $nFaq->page_where, 'pageNumber');
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
