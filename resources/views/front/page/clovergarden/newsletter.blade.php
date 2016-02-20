@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
		
	$nClovernews = new ClovernewsClass(); //
//======================== DB Module Clovernewst ============================
	$Conn = new DBClass();


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
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap">
	<header>
		<h2 class="ti">클로버가든 목록</h2>
	</header>
	<article class="brd_tab_list tab195">
		<h2>후원기관</h2>
		
		<!-- showcase -->
		<div id="showcase" class="showcase">
			<div class="showcase-slide">
				<div class="showcase-content">
					<a href="#"><img src="/imgs/S09Titleimage.png" alt=""></a>
				</div>
			</div>
			
			<div class="showcase-slide">
				<div class="showcase-content">
					<a href="#"><img src="/imgs/S10Titleimage.png" alt=""></a>
				</div>
			</div>
			
			<div class="showcase-slide">
				<div class="showcase-content">
					<a href="#"><img src="/imgs/S11Titleimage.png" alt=""></a>
				</div>
			</div>
		</div>
		<!-- //showcase -->
		<div class="xm_clr"></div>



			
		<?php
			if(count($nClovernews->page_result) > 0){
				$row_no = $nClovernews->total_record - ($nClovernews->page_view * ($page_no - 1));
				for($i=0, $cnt_list=count($nClovernews->page_result); $i < $cnt_list; $i++) {
					$nClovernews->VarList($nClovernews->page_result, $i,  array('comment'));

		?>
		<div class="box5 <?php if($i%4==3) echo "box5_last"; ?>">
			<div class="img">
				<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank">
					<img src='/imgs/up_file/clover/{{ $nClovernews->file_edit[1] }}' border='0' width='100%'>
			</a>
			</div>
			<div class="title">
				<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank"><img src="/imgs/pdf.jpg"></a>
			<?php if($nClovernews->category==1){ ?><img src="/imgs/dot1.jpg"><?php }else{ ?><img src="/imgs/dot2.jpg"><?php } ?>
			<a href="/imgs/up_file/clover/{{ $nClovernews->file_edit[2] }}" target="_blank">{{ $nClovernews->subject }}</a>
			</div>
		</div>
		<?php
					$row_no = $row_no - 1;
				}
			}else{
		?>
				<div style="height:200px; line-height:200px; text-align:center;">
					{{ NO_DATA }}
				</div>
		<?php
			}
		?>

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