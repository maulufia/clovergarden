
@section('sidebar')

<?php /* 무슨 이런 코드가 다 있어?
if($_POST[cate] != ""){ $_GET[cate] = $_POST[cate];}
if($_POST[dep01] != ""){ $_GET[dep01] = $_POST[dep01];}
if($_POST[dep02] != ""){ $_GET[dep02] = $_POST[dep02];}
if($_POST[type] != ""){ $_GET[type] = $_POST[type];} */
?>

<script src="/js/modernizr.js"></script>

<div class="content">
	<!-- Lnb -->
	<div id="lnb">
		<h2><img src="/imgs/Clover2.png" alt="" />{{ isset($cate_01_result[$dep01]) ? $cate_01_result[$dep01] : '' }}</h2>
		<ul class="left_nav">
			<?php
				for($i=0; $i<$cate_01_count; $i++){
			?>
			<?php if($cate_01_result[$i] != '공제센터'){ ?>
				<li
					<?php
						if(isset($cate_02_result) && is_array($cate_02_result)) {
							$cate_02_result[7] = null;
						}
						
						if($cate_02_result[$i]!='') echo "class='depth1'";
					?>
				>

					<?php
						$dep02_para = "";
						if($cate_02_result[$i]) {
							$dep02_para = "&dep02=0";
						}

						$link = "/". CateHelper::getCateName($sub_cate) ."?cate=".$sub_cate."&dep01=".$i.$dep02_para ;
					?>
					
					<a href="{{ $link }}" class="<?php if($i == $dep01) echo 'on'; ?>">
						<?php if($i == $dep01){ ?>
						<i class="fa fa-chevron-down mr5"></i>
						<?php }else if($cate_02_result[$i]){ ?>
						<i class="fa fa-plus mr5"></i>
						<?php }else{ ?>
						<i class="fa fa-minus mr5"></i>
						<?php }?>
						{{ $cate_01_result[$i] }}
					</a>

					<?php if($cate_02_result[$i]!=null){ ?>
					
					<ul>
						<?php
							for($j=0; $j<count($cate_02_result[$i]); $j++){
								$link = "/" . CateHelper::getCateName($sub_cate) . "?cate=" .$sub_cate."&dep01=".$i."&dep02=".$j;
						?>
						
			      <li>
							<a href="{{ $link }}"  class="<?php if($i == $dep01 && $j==$dep02 && $dep02!=null) echo 'on'; ?>">
								<?php if($i == $dep01 && $j==$dep02 && $dep02!=null){ ?>
								<i class="fa fa-chevron-down mr5"></i>
								<?php }else{ ?>
								<i class="fa fa-minus mr5"></i>
								<?php } ?>
								{{ $cate_02_result[$i][$j] }}
							</a>
						</li>
						<?php } ?>
			    </ul>
			    
					 <?php } ?>
				</li>
			<?php
				}}
			?>
		</ul>
		
		<?php
			//================= UserInit =================
			if(Auth::check()) {
				$login_id = Auth::user()->user_id;
	      $login_name = Auth::user()->user_name;
	      $login_state = Auth::user()->user_state; // 정확하지 않음
	      $group_name = Auth::user()->group_name;
	      $use_point = Auth::user()->m_point;
	      $login_cell = Auth::user()->user_cell;
	      $login_email = Auth::user()->user_id;
	      $post1 = Auth::user()->post1;
	      $post2 = Auth::user()->post2;
	      $addr1 = Auth::user()->addr1;
	      $addr2 = Auth::user()->addr2;
			}
			
			//================= Other Vars Init =================
			$page_no = isset($_GET['page_no']) ? $_GET['page_no'] : 0;
			$search_key = isset($_POST['search_key']) ? $_POST['search_key'] : '';
			$search_val = isset($_POST['search_val']) ? $_POST['search_val'] : 0;
		?>
		
		<?php if(isset($login_id)){
				$login_image = explode('@',$login_id);
		?>
		
		<?php
		$nFree = new FreeClass();
		$nClovercomment = new ClovercommentClass();
		$nSchedule = new ScheduleClass();
		$nMember = new MemberClass();
		$nMessage = new MessageClass();

		$nPoint = new PointClass(); //회원
		$nPoint_sum = new PointClass(); //회원
		$nPoint_outsum = new PointClass(); //회원
		$nClover_m = new CloverClass(); //클로버목록
		$nClovermlist_login = new ClovermlistClass(); //

		//======================== DB Module Freet ============================
			$Conn = new DBClass();

			$nFree->where = "where writer like '%".$login_id."%'";
			$nFree->total_record = $Conn->PageListCount
			(
				$nFree->table_name, $nFree->where, $search_key, $search_val
			);

			$nClovercomment->where = "where writer like '%".$login_id."%'";
			$nClovercomment->total_record = $Conn->PageListCount
			(
				$nClovercomment->table_name, $nClovercomment->where, $search_key, $search_val
			);

			$nClover_m->where = "";
			$nClover_m->page_result = $Conn->AllList
			(	
				$nClover_m->table_name, $nClover_m, "*", "order by seq desc limit 10000", null, null
			);

			for($i=0, $cnt_list=count($nClover_m->page_result); $i < $cnt_list; $i++) {
				$nClover_m->VarList($nClover_m->page_result, $i, null);

				$clover_name_v[$nClover_m->code] = $nClover_m->subject;
			}

			$nSchedule->where = "where service_people like '%".$login_id."%'";
			$nSchedule->total_record = $Conn->PageListCount
			(
				$nSchedule->table_name, $nSchedule->where, $search_key, $search_val
			);

			$nMessage->where = "where receive_id like '%".$login_id."%' and hit = '0'";
			$nMessage->total_record = $Conn->PageListCount
			(
				$nMessage->table_name, $nMessage->where, $search_key, $search_val
			);


			$nMember->where = "where user_id = '".$login_id."'";

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



			$nPoint->where = " where userid='".$login_id."'";
			$nPoint->page_view = 10;

			$nPoint->total_record = $Conn->PageListCount
			(
				$nPoint->table_name, $nPoint->where, $search_key, $search_val
			);

			$nPoint->page_result = $Conn->PageList
			(
				$nPoint->table_name, $nPoint, $nPoint->where, $search_key, $search_val, 'order by idx desc', $nPoint->sub_sql, $page_no, $nPoint->page_view
			);


			$nPoint_sum->page_result = $Conn->AllList
			(	
				$nPoint_sum->table_name, $nPoint_sum, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='".$login_id."' group by userid", null, null
			);


			$nClovermlist_login->read_result = $Conn->AllList($nClovermlist_login->table_name, $nClovermlist_login, "*, sum(price) price", "where id ='$login_id'", $nClovermlist_login->sub_sql, null);

			if(count($nClovermlist_login->read_result) != 0){
				$nClovermlist_login->VarList($nClovermlist_login->read_result, 0, array('comment'));
			}

			$nClovermlist_login->where = "where id='".$login_id."' and order_adm_ck = 'y'";
			$nClovermlist_login->total_record = $Conn->PageListCount
			(
				$nClovermlist_login->table_name, $nClovermlist_login->where, $search_key, $search_val
			);

		$Conn->DisConnect();
		//======================== DB Module End ===============================
		?>

		<aside>
			<a href="{{ route('mypage') }}?cate=6" style="border-radius:50%; height:51px; width:51px; border:1px solid #dbdbdb; overflow:hidden; display:inline-block;"  class="xm_left mr10"><img src="/imgs/up_file/member/{{ $nMember->file_edit[1] }}" onerror="this.src='/imgs/photo05.png'" style="height:51px; width:51px;"></a>
			<div class="aside_box xm_left t_bold nanum"><?php if($group_name!=null) echo $group_name; else echo "소속그룹없음"; ?><br />
			{{ $login_name }}<?php if($login_state==2){ ?><img src="/imgs/grade2.jpg" style="margin-left:3px;"><?php }else if($login_state==4){ ?><img src="/imgs/grade4.jpg" style="margin-left:3px;"><?php } ?></div>
			<div class="xm_clr"></div>
			<div class="aside_box mt10">
				<h3><a href="{{ route('mypage') }}?cate=6&dep01=3&dep02=0">나의 활동정보</a></h3>
				<ul>
	<?php
	if(count($nPoint_sum->page_result) > 0){
	for($i=0, $cnt_list=count($nPoint_sum->page_result); $i < $cnt_list; $i++) {
		$nPoint_sum->VarList($nPoint_sum->page_result, $i, null);

		$use_point = $nPoint_sum->inpoint - $nPoint_sum->outpoint;
	?>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=2&dep02=0">가용 후원포인트 {{ number_format($use_point) }}원</a></li>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=3&dep02=0&tabs=tabs-2">나의 후원금액 {{ number_format($nClovermlist_login->price) }}원</a></li>
	<?php
		}
	} else {
	?>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=2&dep02=0">가용 후원포인트 {{ number_format($use_point) }}원</a></li>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=3&dep02=0&tabs=tabs-2">나의 후원금액 {{ (int)$nClovermlist_login->price }}원</a></li>
	<?php } ?>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=3&dep02=1">후원활동 횟수 {{ number_format($nClovermlist_login->total_record) }}</a></li>
					<li><a href="{{ route('mypage') }}?cate=6&dep01=1&dep02=1">쪽지 {{ $nMessage->total_record }}</a></li>
				</ul>
			</div>
			
		</aside>
		<?php } ?>

		<div><a href="{{ route('information') }}?cate=3&dep01=2"><img src="/imgs/ContactButton.png" alt="" /></a></div>

	</div>
	
	<!-- Lnb -->
	@stop