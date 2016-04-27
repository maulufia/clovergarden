@extends('front.page.mypage')

@section('mypage')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nMember = new MemberClass(); //회원
	$nClovermlist_login = new ClovermlistClass(); //후원목록
	$nClover_m = new CloverClass(); //클로버목록
	$nClovernews = new ClovernewsClass(); //
	
	//======================== DB Module Start ============================
	$Conn = new DBClass();

	$nClovermlist_login->page_result = $Conn->AllList
	(	
		$nClovermlist_login->table_name, $nClovermlist_login, "*", "where id='" . Auth::user()->user_id . "'and order_adm_ck = 'y' group by clover_seq order by reg_date desc", null, null
	);

	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>

<section class="wrap">
	<header>
		<h2 class="ti">클로버가든 목록</h2>
	</header>
	<article class="brd_tab_list tab195">
		<h2>후원기관</h2>

		<?php
		if(count($nClovermlist_login->page_result) > 0){
			for($i=0, $cnt_list = count($nClovermlist_login->page_result); $i < $cnt_list; $i++) {
				$nClovermlist_login->VarList($nClovermlist_login->page_result, $i, null);

				$view_date = explode("-",$nClovermlist_login->reg_date);
				if($nClovermlist_login->type == 0){
					$type_v = "일시후원";
				} else {
					$type_v = "정기후원";
				}

				$Conn = new DBClass();

				$nClovernews->where = "where clover_seq='".$nClovermlist_login->clover_seq."'";
				$nClovernews->total_record = $Conn->PageListCount
				(
					$nClovernews->table_name, $nClovernews->where, $search_key, $search_val
				);

				$nClovernews->page_result = $Conn->PageList
				(	
					$nClovernews->table_name, $nClovernews, $nClovernews->where, $search_key, $search_val, 'order by reg_date desc', $nClovernews->sub_sql, $page_no, $nClovernews->page_view, null
				);
				
				if(count($nClovernews->page_result) != 0){
					$nClovernews->VarList($nClovernews->page_result, 0, null);
				}

				$Conn->DisConnect();

		?>
			<?php
				if(count($nClovernews->page_result) > 0){
					$row_no = $nClovernews->total_record - ($nClovernews->page_view * ($page_no - 1));
					for($j=0, $cnt_news = count($nClovernews->page_result); $j < $cnt_news; $j++) {
						$nClovernews->VarList($nClovernews->page_result, $j,  array('comment'));

						$Conn = new DBClass();

						$nClover_m->read_result = $Conn->AllList($nClover_m->table_name, $nClover_m, "*", "where code ='".$nClovermlist_login->clover_seq."'", $nClover_m->sub_sql, null);

						if(count($nClover_m->read_result) != 0){
							$nClover_m->VarList($nClover_m->read_result, 0, array('comment'));
						}

						$Conn->DisConnect();

			?>
			<div class="box5-wrapper">
				<div class="box5 <?php if($i%4==3) echo "box5_last"; ?>">
					<div class="img">
					<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}">
						<img src='/imgs/up_file/clover/{{ $nClovernews->file_edit[1] }}' border='0' width='100%'>
					</a>
					</div>
					<div class="title">
						<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank">
							<img src="/imgs/pdf.jpg">
						<?php if($nClovernews->category==1){ ?><img src="/imgs/dot1.jpg"><?php } else { ?><img src="/imgs/dot2.jpg"><?php } ?> <span style="display: block;">{{ $nClovernews->subject }}</span>
						</a>
					</div>
				</div>
			</div>
			<?php
						$row_no = $row_no - 1;
					}
				}
			?>
		<?php
			}
		} else { ?>
		회원님의 후원 목록이 존재하지 않습니다.
		<?php } ?>
		
		<div class="xm_clr"></div>

		<div class="paging">
		<?php
			if($nClovernews->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nClovernews->total_record, $page_no, $nClovernews->page_view, $nClovernews->page_set, $nClovernews->page_where, 'pageNumber','');
			}
		?>
		</div>
		<form name="form_submit" method="POST" action="{{ $list_link }}" style="display:inline">
			{{ UserHelper::SubmitHidden() }}
		</form>
		
	</article>
</section>
<div class="div_popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1000;">

</div>
<script type="text/javascript" src="/js/jquery.aw-showcase.js"></script>
<script type="text/javascript">

$(document).ready(function()
{
	$("#showcase").awShowcase(
	{
		content_width:			789,
		content_height:			223,
		auto:					true,
		interval:				3000,
		loading:				true,
		arrows:					false,
		buttons:				false,
		stoponclick:			false,
		transition_delay:		0,
		transition_speed:		1000,		
	});
});

function news_popup(id){
	$('.div_popup').empty();
	$('.div_popup').show();
	$('.div_popup').load('/page/clover/page_1_0_view.php?seq='+id);
}

function news_popup_close(){
	$('.div_popup').empty();
	$('.div_popup').hide();
}
</script>
@stop