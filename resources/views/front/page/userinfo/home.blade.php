@extends('front.page.userinfo')

@section('userinfo')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  $nMember = new MemberClass(); //회원
	$nClovermlist_login = new ClovermlistClass(); //후원목록

	$nClovernews = new ClovernewsClass(); //
	$nClover = new CloverClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();
	$nClovermlist_login->page_result = $Conn->AllList
	(	
		$nClovermlist_login->table_name, $nClovermlist_login, "*", "where id='".$_GET['user_id']."' group by clover_seq order by reg_date desc", null, null
	);

	$nMember->where = "where user_id = '".$_GET['user_id']."'";

	$nMember->read_result = $Conn->AllList
	(
		$nMember->table_name, $nMember, "*", $nMember->where, null, null
	);

	if(count($nMember->read_result) != 0){
		$nMember->VarList($nMember->read_result, 0, null);
	}else{
		$Conn->DisConnect();
		//JsAlert(NO_DATA, 1, $list_link);
	}
$Conn->DisConnect();



//======================== DB Module End ===============================
?>



<section class="wrap">
	<h2 class="ti">회원상세정보</h2>
		<div class="mem_info">
			<img src="/imgs/up_file/member/{{ $nMember->file_edit[1] }}" onerror="this.src='/imgs/S57Profile.png'" alt="" class="xm_left mr30">
			<table>
		 	<caption>회원상세정보</caption>
			<colgroup>
                <col class="colWidth105">
                <col>
            </colgroup>
			<tr>
				<th scope="row" class="c_orange xm_tleft">
					이름
				</th>
				<td class="t_bold">{{ $nMember->user_name }}	<a href="{{ route('mypage') }}?cate=6" class="memo">쪽지보내기</a></td>
			</tr>
			<tr>
				<th scope="row" class="c_orange xm_tleft">
					소속명
				</th>
				<td class="t_bold">{{ $nMember->group_name }}</td>
			</tr>
			<tr>
				<th scope="row" class="c_orange xm_tleft">
					이메일
				</th>
				<td class="t_bold">
				<?php
				$ex_email = explode("@",$nMember->user_id);
				$len_email = strlen($ex_email[0])-3;
				$email_dot = null;
				for($l=0; $l < $len_email; $l++){
					$email_dot .= "*"; 
				}
				?>
				{{ substr($ex_email[0],0,3) }}{{ $email_dot }}@<?php echo $ex_email[1] ?>
				
				</td>
			</tr>
			<tr>
				<th scope="row" class="c_orange xm_tleft">
					휴대폰번호
				</th>
				<td class="t_bold">010********</td>
			</tr>

		</table>
		</div>    			

		<header>
		<h2>후원대상기관</h2>
	</header>



<?php

if(count($nClovermlist_login->page_result) > 0){

for($i=0, $cnt_list=count($nClovermlist_login->page_result); $i < $cnt_list; $i++) {
	$nClovermlist_login->VarList($nClovermlist_login->page_result, $i, null);

	$view_date = explode("-",$nClovermlist_login->reg_date);
	if($nClovermlist_login->type == 0){
		$type_v = "일시후원";
	} else {
		$type_v = "정기후원";
	}



	$Conn = new DBClass();

	$nClover->where = "where code='".$nClovermlist_login->clover_seq."'";


	$nClover->total_record = $Conn->PageListCount
	(
		$nClover->table_name, $nClover->where, $search_key, $search_val
	);

	$nClover->page_result = $Conn->PageList
	(	
		$nClover->table_name, $nClover, $nClover->where, $search_key, $search_val, 'order by view_n desc, seq desc', $nClover->sub_sql, $page_no, $nClover->page_view, null
	);

	if(count($nClover->page_result) != 0){
		$nClover->VarList($nClover->page_result, 0, null);
	}


	$Conn->DisConnect();

?>
<?php
	if(count($nClover->page_result) > 0){
		$row_no = $nClover->total_record - ($nClover->page_view * ($page_no - 1));
		for($j=0, $cnt_list=count($nClover->page_result); $j < $cnt_list; $j++) {
			$nClover->VarList($nClover->page_result, $j,  array('comment'));

?>
<div class="mt10 box4">
			<div class="img">
			<a href="{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $nClover->seq }}">					
				<img src='/imgs/up_file/clover/{{ $nClover->file_edit[1] }}' border='0' style='width: 100%;'>
			</a>					
		</div>
			<div class="title"><a href="{{ route('clovergarden') }}?cate=1&dep01=0&dep02=0&type=view&seq={{ $nClover->seq }}">{{ $nClover->subject }}</a></div>
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

</section>
@stop