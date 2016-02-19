<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key  = 'B3';
    $code      = 'B03';
    $list_link = 'm_03_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nCostcounsel    = new CostcounselClass(); //온라인상담

	$date_search = RequestAll($_POST['date_search']);
	$start_date = RequestAll($_POST['start_date']);
    $end_date   = RequestAll($_POST['end_date']);

    if($start_date == ""){
        $start_date = date("Y-m-d",strtotime("-6 day"));
    }

    if($end_date == ""){
        $end_date = Date("Y-m-d");
    }

//======================== DB Module Start ============================
$Conn = new DBClass();

	if($date_search == "1"){
	    $nCostcounsel->where = " where (substring(reg_date,1,10) >= cast('".$start_date."' AS DATE) and substring(reg_date,1,10) <= cast('".$end_date."' AS DATE))";
	}

    $nCostcounsel->total_record = $Conn->PageListCount
    (
        $nCostcounsel->table_name, $nCostcounsel->where, $search_key, $search_val
    );
    $nCostcounsel->page_result = $Conn->PageList
    (
        $nCostcounsel->table_name, $nCostcounsel, $nCostcounsel->where, $search_key, $search_val, 'order by seq desc', $nCostcounsel->sub_sql, $page_no, $nCostcounsel->page_view
    );

$Conn->DisConnect();
//======================== DB Module End ===============================
?>
    <script language="javascript">

        function slideView(pId,pPre)
        {
            $("#"+pId).slideToggle("fast");
        }


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
            }else{
                if(confirm("처리상태를 일괄 수정하시겠습니까?")){
                    f.action = "m_03_edit_exec.php";
                    f.submit();
                }else{
                    return;
                }
            }
        }

        function infoEdit(seq)
        {
            if(seq){
				var info = $("#info"+seq).val();
                var string_value = "seq="+encodeURIComponent(seq)+"&info="+encodeURIComponent(info);
                $.ajax({
                    url: 'm_03_info_exec.php',
                    type: 'POST',
                    data: string_value,
                    error: function(){
                        alert("<?=ERR_DATABASE?>");
                        return;
                    },
                    success: function(data){
						data = data.split("@@||@@");
						var data_result = eval("("+data[1]+")");
						if(data_result.counsel_check == 'y'){
							alert("비고 수정완료 되었습니다.");
						}else{
							alert("비고 수정작업이 실패되었습니다.");
						}
                    }
                })
            }
        }

		function excel_download()
		{
			document.excel_send.submit();
        }


    </script>
	<script type="text/javascript" src="../../new_js/highcharts_plugin/jquery.highcharts.js"></script>
    <script type="text/javascript" src="../../new_js/highcharts_plugin/jquery.exporting.js"></script>
    <script language="javascript">

		$(document).ready(function()
        {
            $('#start_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
            $('#end_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
            $.datepicker.regional['ko'] = {
                closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
                monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
                dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
            };
            $.datepicker.setDefaults($.datepicker.regional['ko']);
        });

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
            <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
            <div class="bbs-search" style="margin-bottom:0px;">
				<?$nCostcounsel->ArrCostcounsel(null, "name='search_key'", null, 'search')?>
				<input type="text" name="search_val" value="<?=$search_val?>"/>
				기간검색 <input type="checkbox" name="date_search" id="date_search" value="1" <?if($date_search == '1'){ echo 'checked'; }?>/>
				<input type="text" name="start_date" id='start_date' value="<?=$start_date?>" style="width:80px;cursor:pointer;" readonly/> ~
				<input type="text" name="end_date" id='end_date' value="<?=$end_date?>" style="width:80px;cursor:pointer;" readonly/>
				<input type="image" src="/new_admin/images/btn_search.gif" alt="search"/>
            </div>
            </form>
            <table class="bbs-list">
                <colgroup>
                    <col style="width:80px;" />
                    <col />
                    <col style="width:150px;" />
                </colgroup>
                <tr>
                    <td><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
                    <td></td>
                    <td>
                    <?if(count($nKakaocounsel->page_result) > 0){?>
                        <a href="javascript:sendSubmit()">
                    <?}else{?>
                        <a href="javascript:alert('<?=NO_DATA?>')">
                    <?}?>
                        [ 처리상태일괄적용 ]</a>
                    </td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:30px;" />
					<col style="width:50px;" />
                    <col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:80px;" />
                    <col style="width:80px;" />
					<col style="width:80px;" />
                    <col />
                    <col style="width:120px;" />
                    <col style="width:150px;" />
                </colgroup>
                <thead>
                <tr>
                    <th></th>
                    <th>번호</th>
					<th>수술부위</th>
                    <th>이름</th>
                    <th>성별</th>
                    <th>연령</th>
					<th>거주지역</th>
                    <th>휴대폰번호</th>
                    <th>등록일</th>
                    <th>처리상태</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nCostcounsel->page_result) > 0){
        $row_no = $nCostcounsel->total_record - ($nCostcounsel->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nCostcounsel->page_result); $i < $cnt_list; $i++) {
            $nCostcounsel->VarList($nCostcounsel->page_result, $i, null);
?>
                <tr>
                    <td><input type="checkbox" name="delete_seq[]" value="<?=$nCostcounsel->seq?>" /></td>
                    <td><?=$row_no?></td>
					<td><?$nCostcounsel->ArrCostcounsel($nCostcounsel->category, null, null, 'category',1)?></td>
                    <td><?=$nCostcounsel->name?></td>
                    <td><?$nCostcounsel->ArrCostcounsel($nCostcounsel->gender, null, null, 'gender',1)?></td>
                    <td><?=$nCostcounsel->age?></td>
					<td><?$nCostcounsel->ArrCostcounsel($nCostcounsel->city, null, null, 'city',1)?></td>
                    <td><?=$nCostcounsel->cell?></td>
                    <td><?=$nCostcounsel->reg_date?></td>
                    <td>
						<?$nCostcounsel->ArrCostcounsel($nCostcounsel->counsel_step, "name='edit_counsel_step[]'", null, 'counsel_step')?>
                        <?$nCostcounsel->ArrCostcounsel($nCostcounsel->counsel_state, "name='edit_counsel_state[]'", null, 'counsel_state')?>
                    </td>
                </tr>
				<tr>
					 <td colspan="2" style="text-align:center">비고</td>
				     <td colspan="10" class="subject">
						<span>
						 <textarea type="text" name="info" id="info<?=$nCostcounsel->seq?>" style="width:750px;" maxlength="200"><?=$nCostcounsel->info?></textarea><a href="javascript:infoEdit(<?=$nCostcounsel->seq?>);"><img src="/new_admin/images/btn_small_modify.gif" alt="수정" align="center"/></a>
						<input type="hidden" name="edit_seq[]" value="<?=$nCostcounsel->seq?>" />
						</span>
					 </td>
				</tr>
                <tr>
                    <td colspan="2" style="text-align:center">상담내용</td>
                    <td class="subject" colspan="8" style="background-color:#C9D6F0;">
                        <div style="color:#000"><?=strip_tags(stripcslashes($nCostcounsel->content))?></div>
                        <div id="content_view<?=$i?>" style="display:none;padding:5px;">
                            <textarea style="width:100%;height:100%" disabled><?=$nCostcounsel->content?></textarea>
                        </div>
                    </td>
                </tr>
				<?
							$row_no = $row_no - 1;
						}
					}else{
				?>
								<tr>
									<td class="tac" colspan="10"><?=NO_DATA?></td>
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
                if($nCostcounsel->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nCostcounsel->total_record, $page_no, $nCostcounsel->page_view, $nCostcounsel->page_set, $nCostcounsel->page_where, 'pageNumber');
                }
            ?>
            </div>
            <div class="btn-area tmargin">
                <div class="fleft ">
                    <a href="<?=$list_link?>"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright"></div>
            </div>
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
			<input type="hidden" name="date_search" value="<?=$date_search?>" />
			<input type="hidden" name="start_date" value="<?=$start_date?>" />
			<input type="hidden" name="end_date" value="<?=$end_date?>" />
        </form>
        <form name="excel_send" method="post" action="stat_excel.php" style="display:inline">
            <?=SubmitHidden()?>
			<input type="hidden" name="date_search" value="<?=$date_search?>" />
			<input type="hidden" name="start_date" value="<?=$start_date?>" />
			<input type="hidden" name="end_date" value="<?=$end_date?>" />
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
