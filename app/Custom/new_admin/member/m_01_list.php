<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A1';
    $list_link  = 'm_01_list.php';
    $view_link  = 'm_01_view.php';
    $write_link = 'm_01_write.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nMember = new MemberClass(); //회원
	$nMoney = new MoneyClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->where = " where user_state = '1'";

    $nMember->total_record = $Conn->PageListCount
    (
        $nMember->table_name, $nMember->where, $search_key, $search_val
    );

    $nMember->page_result = $Conn->PageList
    (
        $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by seq desc', $nMember->sub_sql, $page_no, $nMember->page_view
    );

	$nMoney->read_result = $Conn->AllList($nMoney->table_name, $nMoney, "*", "where seq ='1'", $nMoney->sub_sql, null);

	if(count($nMoney->read_result) != 0){
		$nMoney->VarList($nMoney->read_result, 0, array('comment'));
	}

if($_GET[money_update] == "update"){
	$update_sql = "update new_tb_money set today='".$_GET[today]."', month='".$_GET[month]."', master_key='".$_GET[master_key]."' where seq='1'";
	mysql_query($update_sql);
	echo "
	<script>
	alert('기부금액 및 마스터키가 적용되었습니다.');
	window.location='./m_01_list.php';
	</script>
	";
}
$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val)
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
			<div class="bbs-search">
                <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
                    <?$nMember->ArrMember(null, "name='search_key'", null, 'search')?>
                    <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>

			<form method="get" action="<?=$PHP_SELF?>">
			<input type="hidden" name="money_update" value="update">
			<table cellpadding=10>
			<tr height=30px>
				<td><b>누적 기부 금액</b></td>
				<td><input type="text" name="month" value="<?=$nMoney->month?>">원</td>
				<td width=50></td>
				<td><b>기관 전달 금액</b></td>
				<td><input type="text" name="today" value="<?=$nMoney->today?>">원</td>
				<td width=50></td>
				<td><b>마스터키</b></td>
				<td><input type="text" name="master_key" value="<?=$nMoney->master_key?>">원</td>
				<td><input type="submit" value="적용"></td>
			</tr>
			</table>
			</form>

            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col  />
                    <col  />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>가입일</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nMember->page_result) > 0){
        $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
            $nMember->VarList($nMember->page_result, $i, null);
?>
                <tr>
                    <td><?=$row_no?></td>
                    <td> <a href="javascript:pageLink('<?=$nMember->seq?>','<?=$row_no?>','','<?=$view_link?>');"><?=$nMember->user_id?></a></td>
                    <td><?=$nMember->user_name?></td>
                    <td><?=str_replace('-','.',substr($nMember->reg_date,0,10))?></td>
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
                if($nMember->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nMember->total_record, $page_no, $nMember->page_view, $nMember->page_set, $nMember->page_where, 'pageNumber');
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