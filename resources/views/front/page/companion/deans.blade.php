@extends('front.page.companion')

@section('companion')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
		
	$nSponsorpeople = new SponsorpeopleClass(); //
//======================== DB Module Sponsorpeoplet ============================
	$Conn = new DBClass();


	$nSponsorpeople->total_record = $Conn->PageListCount
	(
		$nSponsorpeople->table_name, $nSponsorpeople->where, $search_key, $search_val
	);

	$nSponsorpeople->page_result = $Conn->PageList
	(	
		$nSponsorpeople->table_name, $nSponsorpeople, $nSponsorpeople->where, $search_key, $search_val, 'order by reg_date desc', $nSponsorpeople->sub_sql, $page_no, $nSponsorpeople->page_view, null
	);

	if(count($nSponsorpeople->page_result) != 0){
		$nSponsorpeople->VarList($nSponsorpeople->page_result, 0, null);
	}


$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)
?>
<section class="wrap">
	<header>
		<h2 class="ti">이달의 클로버</h2>
	</header>

	<article>
		<h2>역대 클로버 소개</h2>

		<?php
			if(count($nSponsorpeople->page_result) > 0){
				$row_no = $nSponsorpeople->total_record - ($nSponsorpeople->page_view * ($page_no - 1));
				for($i=0, $cnt_list=count($nSponsorpeople->page_result); $i < $cnt_list; $i++) {
					$nSponsorpeople->VarList($nSponsorpeople->page_result, $i,  array('comment'));

		?>
		<div class="box6 <?php if($i%4==3) echo "box6_last"; ?>">
			<div class="img">
			<img src='/imgs/up_file/sponsorpeople/{{ $nSponsorpeople->file_edit[1] }}' class='btn-add-data{{ $i }}' style='cursor:pointer;' border='0' width='100%'>
			</div>

<?php
$r_comment2 = htmlspecialchars($nSponsorpeople->subject);
$r_comment2 = str_replace("\r\n","<br />",$r_comment2);
$r_comment2 = str_replace("\n","<br />",$r_comment2);
$r_comment2 = str_replace("&lt;br&gt;","<br />",$r_comment2);
$r_comment2 = stripslashes($r_comment2);
?>
			<table cellpadding=0 cellspacing=0 border=0 width=180 align=center>
			<tr>
				<td style="word-break:break-all;font-weight:bold;" align="center"><?php echo $r_comment2 ?></td>
			</tr>
			</table>
			
		</div>
<style type='text/css'>
<!--
#PopJobReg ,#PopJobReg2{{ $i }} { width: 732px;  height: 711px; margin: -379px 0 0 -398px;  }
#PopJobReg .line , #PopJobReg2{{ $i }} .line{ border-top:1px solid #adadad; width:100%; height:1px; margin:10px 0; }
-->
</style>

		<!------------------------- 팝업 ( 채용공고등록 ppt13_2 )-------------------------------->
		<div id="PopJobReg2{{ $i }}" class="popup2">
			<div class="close"  id="PopJobRegClose2{{ $i }}"><img src="/imgs/btn_pop_close.png" alt="" /></div>
			<div class="pop-cnt">
				<div class="job_list_wrap">
					<h4>상세보기</h4>

					<div class="fr" style="border:1px solid #e8e8e8; height:600px;">
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
		<!------------------------- 팝업 ( 지원자리스트 )-------------------------------->

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

		<div class="xm_clr"></div>

		<div class="paging">
		<?php
			if($nSponsorpeople->total_record != 0){
				$nPage = new PageOut();
				$nPage->CustomPageList($nSponsorpeople->total_record, $page_no, $nSponsorpeople->page_view, $nSponsorpeople->page_set, $nSponsorpeople->page_where, 'pageNumber','');
			}
		?>
		</div>
		<form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
			{{ UserHelper::SubmitHidden() }}
		</form>
	</article>
</section>
@stop