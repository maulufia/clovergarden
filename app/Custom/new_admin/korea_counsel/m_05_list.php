<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'B5';
    $code       = 'B05';
    $list_link  = 'm_05_list.php';
    $edit_link  = 'm_05_edit.php';
    $write_link = 'm_05_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPhotocounsel   = new PhotocounselClass(); //수술갤러리

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nPhotocounsel->total_record = $Conn->PageListCount
    (
        $nPhotocounsel->table_name, $nPhotocounsel->where, $search_key, $search_val
    );

    $nPhotocounsel->page_result = $Conn->PageList
    (
        $nPhotocounsel->table_name, $nPhotocounsel, $nPhotocounsel->where, $search_key, $search_val, 'order by seq desc', $nPhotocounsel->sub_sql, $page_no, $nPhotocounsel->admin_page_view, array('comment')
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
                    f.action = "m_05_delete_exec.php";
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
                    <?$nPhotocounsel->ArrPhotocounsel(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <table class="bbs-list" style="margin:0px">
                <colgroup>
                    <col style="width:150px;" />
                    <col />
                </colgroup>
                <tr>
                    <td class="subject"><font style='color:red'>관리자</font> <font style='color:blue'>회원</font> <font style='color:gray'>비회원</font></td>
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
                    <col />
					<col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:50px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
                    <th>번호</th>
                    <th>카테고리</th>
                    <th>제목</th>
					<th>작성자</th>
					<th>처리상태</th>
                    <th>조회수</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nPhotocounsel->page_result) > 0){
        $row_no = $nPhotocounsel->total_record - ($nPhotocounsel->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nPhotocounsel->page_result); $i < $cnt_list; $i++) {
            $nPhotocounsel->VarList($nPhotocounsel->page_result, $i, array('comment'));
?>
                <tr>
					<td><input type="checkbox" name="delete_seq[]" value="<?=$nPhotocounsel->seq?>" /></td>
                    <td><?=$row_no?></td>
					<td><?=$nPhotocounsel->ArrPhotocounsel($nPhotocounsel->category, null, null, 'category', 1)?></td>
                    <td class="subject">
                        <a href="javascript:pageLink('<?=$nPhotocounsel->seq?>','<?=$row_no?>','','<?=$edit_link?>');"><?=$nPhotocounsel->subject?></a>
                    </td>
					<td><?if($nPhotocounsel->writer=='admin') echo "<font style='color:red'>".$nPhotocounsel->name."</font>"; else if($nPhotocounsel->writer) echo "<font style='color:blue'>".$nPhotocounsel->name."</font>"; else  echo "<font style='color:gray'>".$nPhotocounsel->name."</font>"; ?></td>
					<td><?if($nPhotocounsel->answer != null) { echo "<font style='color:blue'>완료</font>"; } else { echo "<font style='color:red'>처리중</font>"; }?></td>
					<td><?=$nPhotocounsel->hit?></td>
                    <td><?=str_replace('-','.',substr($nPhotocounsel->reg_date,0,10))?></td>                    
                </tr>
<?
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="8"><?=NO_DATA?></td>
                </tr>
<?
    }
?>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            <?=CateHidden()?>
            </form>
            <div class="paging-area">
            <?php
                if($nPhotocounsel->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nPhotocounsel->total_record, $page_no, $nPhotocounsel->admin_page_view, $nPhotocounsel->page_set, $nPhotocounsel->page_where, 'pageNumber');
                }
            ?>
            </div>
<!--             <div class="btn-area tmargin">
                <div class="fleft ">
                    <a href="<?=$list_link?>"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="javascript:pageLink('','','','<?=$write_link?>');"><img src="/new_admin/images/btn_write.gif" alt="writing" /></a>
                </div>
            </div> -->
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
            <?=CateHidden()?>
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
