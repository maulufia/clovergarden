<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C5';
    
    $list_link  = 'm_05_list.php';
    $view_link  = 'm_05_view.php';
    $write_link = 'm_05_write.php';
	$excel_link = 'm_05_excel.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nClovermlist   = new ClovermlistClass(); //후원기관
	$nClover   = new CloverClass(); //후원기관

//======================== DB Module Clovermlistt ============================
$Conn = new DBClass();

	$nClovermlist->where = " where type=1";
    $nClovermlist->total_record = $Conn->PageListCount
    (
        $nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
    );

    $nClovermlist->page_result = $Conn->PageList
    (
        $nClovermlist->table_name, $nClovermlist, $nClovermlist->where, $search_key, $search_val, 'order by seq desc', $nClovermlist->sub_sql, $page_no, $nClovermlist->admin_page_view, array('comment')
    );


    $nClover->page_result = $Conn->AllList
    (
        $nClover->table_name, $nClover, "*",
        "where 1 order by seq asc", null, null
    );


$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val);
    if(count($nClover->page_result) > 0){
        for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
            $nClover->VarList($nClover->page_result, $i, null);
            ${$nClover->code.'_name'} = $nClover->subject;
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
<script language="javascript">

function pop(seq,group){
var url = "http://clovergarden.co.kr/new_admin/clover/m_02_popup.php?clover_seq="+seq+"&group="+group;
var option="width=540,height=600,scrollbars=yes,toolbar=no,location=no,status=yes,menubar=no,resizable=yes"; 

window.open(url,'popCdr',option);
return false;
}

function excel(){
$("#excel").click();
};

function update(){
$("#excel").click();
};

</script>
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
                    <?$nClovermlist->ArrClovermlist(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
				<form name="excel" method="post" action="<?=$excel_link?>" enctype="multipart/form-data" style="display:inline;">
					<input type="file" name="excel" id="excel" style="display:none;" onchange="if(confirm('업로드하시겠습니까?')){ submit(); }">
					<?=SubmitHidden()?>
				</form>
				<a href="javascript:excel();" style="padding:0 10px; background:#fff; height:20px; line-height:20px; float:right; border:1px solid #dbdbdb;">후원내역 업로드</a>
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
					<col style="width:80px;" />
					<col style="width:80px;" />
					<col style="width:100px;" />
					<col style="width:100px;" />
					<col style="width:80px;" />
                    <col style="width:100px;" />
					<col style="width:100px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
					<th>번호</th>
					<th>구분</th>
					<th>이름</th>
                    <th>정기/일시</th>
					<th>기관명</th>
					<th>금액</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nClovermlist->page_result) > 0){
        $row_no = $nClovermlist->total_record - ($nClovermlist->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
            $nClovermlist->VarList($nClovermlist->page_result, $i, array('comment'));
?>
                <tr>
					<td><input type="checkbox" name="delete_seq[]" value="<?=$nClovermlist->seq?>" /></td>
                    <td><?=$row_no?></td>
					<td><?if($nClovermlist->group_name) echo $nClovermlist->group_name; else echo "개인";?></td>
					<td>
						<?=$nClovermlist->name?>
					</td>
                    <td>
                        <?if($nClovermlist->orde_num) echo "일시"; else echo "<b>정기</b>";?>
                    </td>
					<td>
						<?=${$nClovermlist->clover_seq.'_name'}?>
					</td>
					<td>
						<?=number_format($nClovermlist->price)?>원
					</td>
                    <td><?=str_replace('-','.',substr($nClovermlist->reg_date,0,10))?></td>                    
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
                if($nClovermlist->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nClovermlist->total_record, $page_no, $nClovermlist->admin_page_view, $nClovermlist->page_set, $nClovermlist->page_where, 'pageNumber');
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
