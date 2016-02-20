@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
		
	$nClover = new CloverClass(); //
	$nClover_banner = new CloverClass(); //
//======================== DB Module Clovert ============================
	$Conn = new DBClass();
	$nClover->total_record = $Conn->PageListCount
	(
		$nClover->table_name, $nClover->where, $search_key, $search_val
	);

	$nClover->where = "where subject != '클로버가든'";
	$nClover->page_result = $Conn->PageList
	(	
		$nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->page_view, null
	);


	$nClover_banner->page_result = $Conn->AllList
	(	
		$nClover_banner->table_name."_banner", $nClover_banner, "*", "where 1 order by seq desc", null, null
	);

	if(count($nClover->page_result) != 0){
		$nClover->VarList($nClover->page_result, 0, null);
	}


$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<link rel="stylesheet" href="/css/flexslider.css" type="text/css" media="screen" />
<script defer src="/js/jquery.flexslider.js"></script>

<!-- Optional FlexSlider Additions -->
<script src="/js/jquery.easing.js"></script>
<script src="/js/jquery.mousewheel.js"></script>

<script type="text/javascript">
$(window).load(function(){
  $('.flexslider').flexslider({
	animation: "slide",
	animationLoop: true,
	itemWidth: '100%',
	controlNav: true,
	slideshowSpeed: 3000,
	pauseOnHover: false
  });
});
</script>


<section class="wrap">
	<header>
		<h2>후원기관</h2>
	</header>
	<section id="" style = "height:223px;">
		<div class="flexslider">
		  <ul class="slides">
			<?php
			if(count($nClover_banner->page_result) > 0){

				for($i=0, $cnt_list=count($nClover_banner->page_result); $i < $cnt_list; $i++) {
					$nClover_banner->VarList($nClover_banner->page_result, $i, null);
			?>
			<li style="position:relative;">
				<a href="{{ route('clovergarden', ['seq' => $nClover_banner->seq] ) }}&cate=1&dep01=0&dep02=0&type=view">
				<img src='/imgs/up_file/clover/{{ $nClover_banner->file_edit[1] }}' style="width:789px; height:223px">
				<img src="/imgs/go_btn.gif" style="width:129px; height:32px; position:absolute; bottom:10px; right:20px;"></a>
			</li>
			<?php }} ?>
		  </ul>
		</div>
	</section>

	<?php
		if(count($nClover->page_result) > 0){
			$row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
			for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
				$nClover->VarList($nClover->page_result, $i,  array('comment'));

	?>
	<div class="box4 <?php if($i%3==2) echo 'box4_last"'; ?>">
		<div style="position:relative;left:100px;top:5px;">
			<img src='/imgs/hot_{{ $nClover->hot }}.png' border='0'>
		</div>
		<div class="img">
			<a href="{{ $view_link }}&seq={{ $nClover->seq }}">					
				<img src='/imgs/up_file/clover/{{ $nClover->file_edit[1] }}' border='0' style='width: 100%;'>
			</a>
		</div>
		<div class="title">
		<a href="{{ $view_link }}&seq={{ $nClover->seq }}">{{ $nClover->subject }}</a></div>
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
		if($nClover->total_record != 0){
			$nPage = new PageOut();
			$nPage->CustomPageList($nClover->total_record, $page_no, $nClover->page_view, $nClover->page_set, $nClover->page_where, 'pageNumber','');
		}
	?>
	</div>
	<form name="form_submit" method="get" action="page.php" style="display:inline">
		{{ UserHelper::SubmitHidden() }}
	</form>
</section>
<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 검색
        $( "#search" ).click(function() {

            if($('input[name=keyword]').val() == ''){
                alert('검색어를 입력하세요.');
                $('input[name=keyword]').focus();
                return false;
            }

            $( "#searchForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop