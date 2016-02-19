<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'C2';
    
    $list_link  = 'm_02_list.php';
    $view_link  = 'm_02_view.php';
    $write_link = 'm_02_write.php';
	
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

	$code       = RequestAll($_POST['code']);
	$group  = RequestAll($_POST['group_name']);

    $nClovermlist   = new ClovermlistClass(); //후원기관
	$nClover   = new CloverClass(); //후원기관
	$nClovermlist_group = new ClovermlistClass(); //

//======================== DB Module Clovermlistt ============================
$Conn = new DBClass();


	if($code!=null)
	$nClover->where = " where code = '".$code."'";
	

	if($group!=null)
	$group_where = " and group_name = '".$group."'";

	$nClovermlist_group->read_result = $Conn->AllList($nClovermlist_group->table_name, $nClovermlist_group, "sum(price) price", "where 1 ".$group_where." order by seq", $nClovermlist_group->sub_sql, null);

	if(count($nClovermlist_group->read_result) != 0){
		$nClovermlist_group->VarList($nClovermlist_group->read_result, 0, null);
		$my_money = $nClovermlist_group->price;
	}




	
	$nClovermlist->cate_result = $Conn->AllList($nClovermlist->table_name, $nClovermlist, '*', "where 1 group by group_name");

	$nClover->sub_sql = ",(select sum(price) from ".$nClovermlist->table_name." where clover_seq = ".$nClover->table_name.".code ".$group_where.") as comment_cnt,
	(select count(clover_seq) from ".$nClovermlist->table_name." where clover_seq = ".$nClover->table_name.".code ".$group_where.") as clover";

    $nClover->total_record = $Conn->PageListCount
    (
        $nClover->table_name, $nClover->where, $search_key, $search_val
    );

    $nClover->page_result = $Conn->PageList
    (
        $nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by seq desc', $nClover->sub_sql, $page_no, $nClover->admin_page_view, array('comment','clover')
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
    $search_val = ReXssChk($search_val);

	if(count($nClovermlist->cate_result) > 0){
        for($i=0, $cnt_list=count($nClovermlist->cate_result); $i < $cnt_list; $i++) {
            $nClovermlist->VarList($nClovermlist->cate_result, $i);

			$cate_field[0] = "전체";
			$cate_value[0] = "";

            $cate_field[$i+1] = $nClovermlist->group_name;
            $cate_value[$i+1] = $nClovermlist->group_name;
			
        }
    }
?>
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
</head>
<body>
<?
if($group == ""){
	$group_n = "선택하세요";
} else {
	$group_n = $group;
}
?>
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
					<font color="red">후원기업</font> : <?WriteSelect($cate_field, $cate_value, null, "name='group_name' onChange='submit()'", $group_n)?>
                    <!-- <?$nClover->ArrClover(null, "name='search_key'", null, 'search')?> -->
                    <!-- <input type="text" name="search_val" value="<?=$search_val?>"/>
                    <input type="image" src="/new_admin/images/btn_search.gif" alt="search"/> -->
					
                </form>
				<span style="float:right;"><?=number_format($nClovermlist_group->price)?>원</span>
            </div>
            <table class="bbs-list" style="margin:0px">
                <colgroup>
                    <col style="width:80px;" />
                    <col />
                </colgroup>
                <tr>
                    <td></td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:50px;" />		
					<col style="width:100px;" />	
                    <col />
					<col style="width:150px;" />
                    <col style="width:150px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
					<th>구분</th>
                    <th>기관명</th>
					<th>총 후원금</th>
                    <th>후원자수</th>
					<th>후원금 비중</th>
                    <th>상세내역</th>                    
                </tr>
                </thead>
                <tbody>

<?php
	$total_cnt = 0;
    if(count($nClover->page_result) > 0){
        $row_no = $nClover->total_record - ($nClover->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
            $nClover->VarList($nClover->page_result, $i, array('comment','clover'));

			$total_cnt = $total_cnt + $nClover->comment_cnt;

		}
	}
	$nClover->ArrClear();

?>
<?php

    if(count($nClover->page_result) > 0){
        $row_no = $nClover->total_record - ($nClover->admin_page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
            $nClover->VarList($nClover->page_result, $i, array('comment','clover'));

?>
                <tr>
                    <td><?=$row_no?></td>
					<td>
                        <?=$nClover->ArrClover($nClover->category, null, null, 'category', 1)?>
                    </td>
                    <td>
                        <?=$nClover->subject?>
                    </td>
					<td>
                        <?=number_format($nClover->comment_cnt)?>
                    </td>
					<td><?=number_format($nClover->clover)?></td>
					<td><?=round(($nClover->comment_cnt/$total_cnt)*100,2)?>%</td>
                    <td><a href="#" onclick="javascript:pop('<?=$nClover->code?>','<?=$group?>');">자세히</a></td>                    
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
