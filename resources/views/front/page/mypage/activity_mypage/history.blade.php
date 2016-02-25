@extends('front.page.mypage')

@section('mypage')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
	
	$nClovermlist = new ClovermlistClass(); //
	$nClover = new CloverClass(); //

	$nClovermlist_mymoney = new ClovermlistClass(); //
	$nClovermlist_allmoney = new ClovermlistClass(); //


	$nClovermlist_group = new ClovermlistClass(); //

//======================== DB Module Clovermlistt ============================
	$Conn = new DBClass();
	$nClovermlist->where = " where id like '%" . Auth::user()->user_id . "%' and order_adm_ck='y'";
	$nClovermlist->total_record = $Conn->PageListCount
	(
		$nClovermlist->table_name, $nClovermlist->where, $search_key, $search_val
	);

	$nClovermlist->page_result = $Conn->PageList
	(	
		$nClovermlist->table_name, $nClovermlist, $nClovermlist->where, $search_key, $search_val, 'order by start desc', $nClovermlist->sub_sql, $page_no, $nClovermlist->page_view, null
	);

	if(count($nClovermlist->page_result) != 0){
		$nClovermlist->VarList($nClovermlist->page_result, 0, null);
	}

    $nClover->page_result = $Conn->AllList
    (
        $nClover->table_name, $nClover, "*",
        "where 1 order by seq asc", null, null
    );

  $y_date = isset($_GET['y_date']) ? $_GET['y_date'] : null;
	if(!is_null($y_date)){
		$ydate_where = $y_date;
	} else {
		$ydate_where = date('Y');
	}

	$s_date = isset($_GET['s_date']) ? $_GET['s_date'] : null;
	if(!is_null($s_date)){
		$date_where = $s_date;
	} else {
		$date_where = date('m');
	}

	$sql_date_where = $ydate_where.$date_where;
    $nClovermlist_group->page_result = $Conn->AllList
    (
        $nClovermlist_group->table_name, $nClovermlist_group, "*, sum(price) price",
        "where id like '%" . Auth::user()->user_id . "%' and start like '%".$sql_date_where."%' group by clover_seq order by seq asc", null, null
    );

	$nClovermlist_mymoney->read_result = $Conn->AllList($nClovermlist_mymoney->table_name, $nClovermlist_mymoney, "sum(price) price", "where id = '" . Auth::user()->user_id . "' and start like '%".$sql_date_where."%' group by id", $nClovermlist_mymoney->sub_sql, null);
	if(count($nClovermlist_mymoney->read_result) != 0){
		$nClovermlist_mymoney->VarList($nClovermlist_mymoney->read_result, 0, null);
		$my_money = $nClovermlist_mymoney->price;
	}


	$nClovermlist_allmoney->read_result = $Conn->AllList($nClovermlist_allmoney->table_name, $nClovermlist_allmoney, "sum(price) price", "where group_name = '" . Auth::user()->group_name . "' and start like '%".$sql_date_where."%' group by group_name", $nClovermlist_allmoney->sub_sql, null);
	if(count($nClovermlist_allmoney->read_result) != 0){
		$nClovermlist_allmoney->VarList($nClovermlist_allmoney->read_result, 0, null);
		$all_money = $nClovermlist_allmoney->price;
	}

	$clover_percent = @round(($my_money/$all_money)*100,1);
	if($clover_percent == ""){
		$clover_percent = 0;
	} else if ($clover_percent > 100){
		$clover_percent = 100;
	}

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

	if($clover_percent >= 0 && $clover_percent < 25){
		$back_n = 25;
	} else if ($clover_percent > 25 && $clover_percent < 50) {
		$back_n = 50;
	} else if ($clover_percent > 50 && $clover_percent < 75) {
		$back_n = 75;
	} else if ($clover_percent > 75 && $clover_percent < 101) {
		$back_n = 100;
	} else {
		$back_n = 100;
	}

	$ex_date_v = explode("-",$sql_date_where);
?>
<section class="wrap">
	<header>
		<h2 class="ti">후원내역 목록</h2>
	</header>

	<article class="brd_list">
		<h2 class="ti">NO/기관명/후원금액/날짜</h2>
		<table>
			<caption>게시판 목록</caption>
			<colgroup>
				<col class="colWidth89">
				<col class="colWidth147" style='width:180px'>
				<col class="colWidth451">
				<col class="colWidth105">
			</colgroup>
			<tr class="title">
				<th scope="col" class="first">NO</th>
				<th scope="col"><span>기관명</span></th>
				<th scope="col"><span>후원금액</span></th>
				<th scope="col"><span>날짜</span></th>
			</tr>
			<?php
				if(count($nClovermlist->page_result) > 0){
					$row_no = $nClovermlist->total_record - ($nClovermlist->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nClovermlist->page_result); $i < $cnt_list; $i++) {
						$nClovermlist->VarList($nClovermlist->page_result, $i,  array('comment'));
			?>
			<tr>
				<td class="no">{{ $row_no }}</td>
				<td class="normal"><b><?php if($nClovermlist->start != ""){ ?>[정기]<?php } else { ?>[일시]<?php } ?></b>{{ ${$nClovermlist->clover_seq.'_name'} }}</td>
				<td class="normal"><?php if($nClovermlist->otype == "point"){ ?>포인트<?php } ?>{{ number_format($nClovermlist->price) }}원</td>
				<td class="date">
				<?php if($nClovermlist->start != ""){ ?>
					{{ $nClovermlist->start }}
				<?php } else { ?>
					{{ str_replace("-","",substr($nClovermlist->reg_date,0,10)) }}
				<?php } ?>
				</td>
			</tr>
			<?php
						$row_no = $row_no - 1;
					}
				} else {
			?>
					<tr>
						<td colspan="4" style="height:200px; text-align:center;">{{ NO_DATA }}</td>
					</tr>
			<?php
				}
			?>
		</table>
	</article>


	<div class="paging">
		<?php
			if($nClovermlist->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nClovermlist->total_record, $page_no, $nClovermlist->page_view, $nClovermlist->page_set, $nClovermlist->page_where, 'pageNumber','');
			}
		?>
	</div>
	<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
		{{ UserHelper::SubmitHidden() }}
	</form>
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 검색
        $( "#search" ).click(function() {

            if($('input[name=search_val]').val() == ''){
                alert('검색어를 입력하세요.');
                $('input[name=search_val]').focus();
                return false;
            }

            $( "#searchForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop