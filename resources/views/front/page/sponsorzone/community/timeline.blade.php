@extends('front.page.sponsorzone')

@section('sponsorzone')

<?php
	$nClover2 = new CloverClass(); //클로버응원댓글

	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

	$nClover = new CloverClass(); //클로버응원댓글

	$nClovercomment = new ClovercommentClass(); //클로버응원댓글

	$nClovercomment2 = new ClovercommentClass(); //클로버응원댓글

	$nMember = new MemberClass(); //회원

//======================== DB Module Clovercommentt ============================
	$Conn = new DBClass();

	$nClover->page_result = $Conn->AllList($nClover->table_name, $nClover, "*", $nClover->where, null, null);

	$nClovercomment->total_record = $Conn->PageListCount
	(
		$nClovercomment->table_name, $nClovercomment->where, $search_key, $search_val
	);

	$nClovercomment->page_result = $Conn->PageList
	(	
		$nClovercomment->table_name, $nClovercomment, $nClovercomment->where, $search_key, $search_val, 'order by reg_date desc', $nClovercomment->sub_sql, $page_no, $nClovercomment->page_view, null
	);

	if(count($nClovercomment->page_result) != 0){
		$nClovercomment->VarList($nClovercomment->page_result, 0, null);
	}

	$group_name = Auth::check() ? Auth::user()->group_name : null; // TEMP
	$nClovercomment2->where = "where group_name='" . $group_name . "' and group_name != ''";
	$nClover2->page_result = $Conn->AllList($nClover2->table_name, $nClover2, "*", $nClover2->where, null, null);

	$nClovercomment2->total_record = $Conn->PageListCount
	(
		$nClovercomment2->table_name, $nClovercomment2->where, $search_key, $search_val
	);

	$nClovercomment2->page_result = $Conn->PageList
	(	
		$nClovercomment2->table_name, $nClovercomment2, $nClovercomment2->where, $search_key, $search_val, 'order by reg_date desc', $nClovercomment2->sub_sql, $page_no, $nClovercomment2->page_view, null
	);

	if(count($nClovercomment2->page_result) != 0){
		$nClovercomment2->VarList($nClovercomment2->page_result, 0, null);
	}



$Conn->DisConnect();
//======================== DB Module End ===============================
	$search_val = ReXssChk($search_val)

?>
<?php
	if(count($nClover->page_result) > 0){
        for($i=0, $cnt_list=count($nClover->page_result); $i < $cnt_list; $i++) {
            $nClover->VarList($nClover->page_result, $i, array('name'));
            ${$nClover->seq} = $nClover->subject;
        }
        $nClover->ArrClear();
    }
?>

<section class="wrap">
	<header>
		<h2 class="ti">타임라인 목록</h2>
	</header>
	<article class="brd_tab_list">
		<h2 class="ti">전체/그룹</h2>
		
		<div id="tabs" class="tab">
			<ul>
				<li class="menu on"><a href="#tabs-1">최근 타임라인</a></li>
				<li class="menu last"><a href="#tabs-2"><?php if($group_name){ ?>{{ $group_name }}<?php }else{ ?>소속 그룹<?php } ?></a></li>
			</ul>

			<div id="tabs-1" class="tabCont">

			<script type="text/javascript" src="/js/new_js/jquery.newsticker.js"></script>
			<script type="text/javascript">
			$(function(){

			//#############세번째 샘플
					$('#newsticker3').Vnewsticker({
						speed: 500,         //스크롤 스피드
						pause: 3000,        //잠시 대기 시간
						mousePause: true,   //마우스 오버시 일시정지(true=일시정지)
						showItems: 7,       //스크롤 목록 갯수 지정(1=한줄만 보임)
						direction : "up"    //up=위로스크롤, 공란=아래로 스크롤
				});

			});
			</script>



			<ul id="newsticker3" style="line-height:200%;border:1px solid #fff;">
			<ul>
				<?php
					if(count($nClovercomment->page_result) > 0){
						$row_no = $nClovercomment->total_record - ($nClovercomment->page_view * ($page_no - 1));
						for($i=0, $cnt_list=count($nClovercomment->page_result); $i < $cnt_list; $i++) {
							$nClovercomment->VarList($nClovercomment->page_result, $i,  array('comment'));
							$board_name = explode(',',$nClovercomment->writer);

							$Conn = new DBClass();
								$nMember->where = "where user_id ='".$board_name[1]."'";
								$nMember->read_result = $Conn->AllList
								(
									$nMember->table_name, $nMember, "*", $nMember->where, null, null
								);
							
								if(count($nMember->read_result) != 0){
									$nMember->VarList($nMember->read_result, 0, null);

									$group_name = $nMember->group_name;
								}	
							$Conn->DisConnect();


							$board_image = explode('@',$board_name[1]);

				?>
				<li>
					<table>
						<caption>게시판 목록</caption>
						<colgroup>
							<col class="colWidth180">
							<col class="colWidth110">
							<col class="colWidth380">
							<col class="colWidth105">
						</colgroup>
						<tr	>
							<th scope="row">
								<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nMember->user_id }}">
								<img src="/imgs/{{ $board_image[0] }}.jpg" onerror="this.src='/imgs/photo05.png'" class="xm_left mr10"> 
								</a>
								<div class="name">
									<a href="{{ route('userinfo') }}?cate=8&user_id=<?=$nMember->user_id?>">
									<?php if($group_name != ""){ ?>{{ $group_name }}<br><?php }?>{{ $board_name[0] }}님
									</a>
								</div>
								
							</th>
							<td class="normal"><a href="{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $nClovercomment->clover_seq }}">{{ ${$nClovercomment->clover_seq} }}</a></td>
							<td class="subject">{{ $nClovercomment->subject }}</td>
							<td class="date">{{ date('Y-m-d',strtotime($nClovercomment->reg_date)) }}</td>
						</tr>	
					</table>
				</li>
				<?php
							$row_no = $row_no - 1;
						}
					}else{
				?>
						<li>{{ NO_DATA }}</li>
				<?php
					}
				?>
			</ul>
			<ul>
			</div>
		<?php if(Auth::check()){ ?>
			<div id="tabs-2" class="tabCont">
				<table>
					<caption>게시판 목록</caption>
					<colgroup>
						<col class="colWidth180">
						<col class="colWidth110">
						<col class="colWidth380">
						<col class="colWidth105">
					</colgroup>
					<?php
						if(count($nClovercomment2->page_result) > 0){
							$row_no = $nClovercomment2->total_record - ($nClovercomment2->page_view * ($page_no - 1));
							for($i=0, $cnt_list=count($nClovercomment2->page_result); $i < $cnt_list; $i++) {
								$nClovercomment2->VarList($nClovercomment2->page_result, $i,  array('comment'));
								$board_name = explode(',',$nClovercomment2->writer);

								$Conn = new DBClass();
									$nMember->where = "where user_id ='".$board_name[1]."'";
									$nMember->read_result = $Conn->AllList
									(
										$nMember->table_name, $nMember, "*", $nMember->where, null, null
									);
								
									if(count($nMember->read_result) != 0){
										$nMember->VarList($nMember->read_result, 0, null);

										$group_name = $nMember->group_name;
									}	
								$Conn->DisConnect();


								$board_image = explode('@',$board_name[1]);

					?>
					<tr>
						<th scope="row">
							<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nMember->user_id }}">
							<img src="/imgs/{{ $board_image[0] }}.jpg" onerror="this.src='/imgs/photo05.png'" class="xm_left mr10"> 
							</a>
							<div class="name">
								<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nMember->user_id }}">
								<?php if($group_name != ""){ ?>{{ $group_name }}<br><?php } ?>{{ $board_name[0] }}님
								</a>
							</div>
						</th>
						<td class="normal"><a href="{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $nClovercomment2->clover_seq }}">{{ ${$nClovercomment2->clover_seq} }}</a></td>
						<td class="subject">{{ $nClovercomment2->subject }}</td>
						<td class="date">{{ date('Y-m-d',strtotime($nClovercomment2->reg_date)) }}</td>
					</tr>


					<?php
								$row_no = $row_no - 1;
							}
						}else{
					?>
							<tr>
								<td colspan="4" style="height:200px; text-align:center;">기업회원만 이용가능합니다.</td>
							</tr>
					<?php
						}
					?>
				</table>

				<div class="paging">
				</div>
			</div>
<?php } else { ?>
			<table>
				<caption>게시판 목록</caption>
				<colgroup>
					<col class="colWidth180">
					<col class="colWidth110">
					<col class="colWidth380">
					<col class="colWidth105">
				</colgroup>
						<tr>
							<td colspan="4" style="height:200px; text-align:center;">기업회원만 이용가능합니다.</td>
						</tr>
			</table>
<?php } ?>

		</div>
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
    });
})(jQuery);
</script>

@stop