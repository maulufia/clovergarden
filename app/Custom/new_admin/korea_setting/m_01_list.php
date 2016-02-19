<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'F1';
    $list_link  = 'm_01_list.php';
    $edit_link  = 'm_01_edit.php';
    $write_link = 'm_01_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPopup = new PopupClass(); //팝업

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nPopup->total_record = $Conn->PageListCount
    (
        $nPopup->table_name, $nPopup->where, $search_key, $search_val
    );
    $nPopup->page_result = $Conn->PageList
    (
        $nPopup->table_name, $nPopup, $nPopup->where, $search_key, $search_val, 'order by seq desc', $nPopup->sub_sql, $page_no, $nPopup->page_view
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
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
                    f.action = "m_01_delete_exec.php";
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
                    <?$nPopup->ArrPopup(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <table class="bbs-list">
                <colgroup>
                    <col />
                </colgroup>
                <tr>
                    <td style="text-align:right;"><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:50px;" />					
                    <col style="width:150px;" />
                    <col />
                    <col style="width:80px;" />
                    <col style="width:80px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>기간</th>
                    <th>노출여부</th>
                    <th>삭제</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nPopup->page_result) > 0){
        $row_no = $nPopup->total_record - ($nPopup->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nPopup->page_result); $i < $cnt_list; $i++) {
            $nPopup->VarList($nPopup->page_result, $i, null);
?>
                <tr>
                    <td><?=$row_no?></td>
                    <td>
                        <a href="javascript:pageLink('<?=$nPopup->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><?=$nPopup->subject?></a>
                    </td>
                    <td><?=$nPopup->start_date?> ~ <?=$nPopup->end_date?></td>
                    <td><?$nPopup->ArrPopup($nPopup->hidden, null, null, 'hidden', 1)?></td>
                    <td><input type="checkbox" name="delete_seq[]" value="<?=$nPopup->seq?>" /></td>
                </tr>
<?
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td class="tac" colspan="5"><?=NO_DATA?></td>
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
                if($nPopup->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nPopup->total_record, $page_no, $nPopup->page_view, $nPopup->page_set, $nPopup->page_where, 'pageNumber');
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