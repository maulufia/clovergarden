@extends('front.page.companion')

@section('companion')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
		
	$nSponsor = new SponsorClass(); //
	$nSponsorpeople = new SponsorpeopleClass(); //
//======================== DB Module Sponsort ============================
	$Conn = new DBClass();


	$nSponsor->total_record = $Conn->PageListCount
	(
		$nSponsor->table_name, $nSponsor->where, $search_key, $search_val
	);

	$nSponsor->page_result = $Conn->PageList
	(	
		$nSponsor->table_name, $nSponsor, $nSponsor->where, $search_key, $search_val, 'order by reg_date desc', $nSponsor->sub_sql, $page_no, $nSponsor->page_view, null
	);

	$nSponsorpeople->page_result = $Conn->AllList
	(	
		$nSponsorpeople->table_name, $nSponsorpeople, "*", "where 1 order by seq desc limit 3", null, null
	);

	if(count($nSponsor->page_result) != 0){
		$nSponsor->VarList($nSponsor->page_result, 0, null);
	}


$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap mt50">
	<header>
		<h2 class="ti">함께하는 사람들</h2>
	</header>
	<article class="brd_tab_list tab195">
		<h2 class="ti">함께하는 사람들</h2>
		
		<!-- Rotate -->
		<div id="featured">
			<ul class="ui-tabs-nav">
				<li class="ui-tabs-nav-item"><a href="#fragment-1" class="none"><span>1</span></a></li>
				<li class="ui-tabs-nav-item"><a href="#fragment-2"><span>2</span></a></li>
				<li class="ui-tabs-nav-item"><a href="#fragment-3"><span>3</span></a></li>
			</ul>
			<!--  Rotate Content -->
			<?php
			for($i=0, $cnt_list=count($nSponsorpeople->page_result); $i < $cnt_list; $i++) {
				$nSponsorpeople->VarList($nSponsorpeople->page_result, $i, null);
			?>
				<div id="fragment-{{ $i+1 }}" class="ui-tabs-panel" style="">
				<img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[2] }}' class='btn-add-data{{ $i }}' border='0' width='100%' height='223'>
				</div>


		<style type='text/css'>
		<!--
		#PopJobReg ,#PopJobReg2{{ $i }} { width: 732px;  height: 711px; margin: -379px 0 0 -398px;  }
		#PopJobReg .line , #PopJobReg2{{ $i }} .line{ border-top:1px solid #adadad; width:100%; height:1px; margin:10px 0; }
		-->
		</style>

				<!------------------------- 팝업 ( 채용공고등록 ppt13_2 )-------------------------------->
				<div id="PopJobReg2{{ $i }}" class="popup2">
					<div class="close" id="PopJobRegClose2{{ $i }}"><img src="/imgs/btn_pop_close.png" alt="" /></div>
					<div class="pop-cnt">
						<div class="job_list_wrap">
							<h4>상세보기</h4>

							<div class="fr" style="border:1px solid #000; height:600px;">
							<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
							<tr>
								<td style="padding:10px;" valign="top">
								<div style="overflow:auto; width:100%; height:580px;">
								{{ $nSponsorpeople->content }}
								</div>
								</td>
							</tr>
							</table>
							</div> <!-- fr -->
						</div>
					</div>
				</div>




				<script type="text/javascript">
				<!--
				$(".btn-add-data{{ $i }}").click(function(){
					toggleLayer($('#PopJobReg2{{ $i }}'),'show');
				});
				$("#PopJobRegClose2{{ $i }}").click(function(){
					toggleLayer($('#PopJobReg2{{ $i }}'),'hide');
				});		
				//-->
				</script>

			<?php
				}
			?>
			<!--  //Rotate Content -->
		</div>
<script type="text/javascript">
<!--

 function toggleLayer(sel,type){
	if(type=="hide"){
			sel.hide();
	}else if(type=="show"){
			sel.show();
	}
 }


//-->
</script>
		<!-- //Rotate -->					

		<div class="xm_left mt45">
			<?php
				if(count($nSponsor->page_result) > 0){
					$row_no = $nSponsor->total_record - ($nSponsor->page_view * ($page_no - 1));
					for($i=0, $cnt_list=count($nSponsor->page_result); $i < $cnt_list; $i++) {
						$nSponsor->VarList($nSponsor->page_result, $i,  array('comment'));

			?>
			<div class="box7 <?php if($i%3==2) echo 'box7_last'; ?>">
				@if (!empty($nSponsor->url))
					<a href="{{ $nSponsor->url }}" target="_blank">
				@else
					<a href="#">
				@endif
					<img src='/imgs/up_file/sponsor/{{ $nSponsor->file_edit[1] }}' border='0' style='width:205px; height:60px;'>
					<p>{{ RepBr($nSponsor->content) }}</p>
				</a>
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
		</div>
		<div class="xm_clr"></div>

		<div class="paging">
		<?php
			if($nSponsor->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nSponsor->total_record, $page_no, $nSponsor->page_view, $nSponsor->page_set, $nSponsor->page_where, 'pageNumber','');
			}
		?>
		</div>
		<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
			{{ UserHelper::SubmitHidden() }}
		</form>
	</article>
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        //tabs
        $( "#tabs" ).tabs();

		$('#tabs .menu').click(function(){
			$('#tabs .menu').removeClass("on");
			$(this).addClass('on');
		});

		// 응원댓글쓰기
        $( "#comment" ).click(function() {

            if($('input[name=keyword]').val() == ''){
                alert('응원댓글을 입력하세요.');
                $('input[name=keyword]').focus();
                return false;
            }

            $( "#commentForm" ).submit();

        });

        //Rotate
        $("#featured").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 10000, true);
    });
})(jQuery);
</script>
@stop