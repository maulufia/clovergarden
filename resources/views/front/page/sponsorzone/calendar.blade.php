<?php

	$year = $_GET['year'];
	$month = $_GET['month'];
	//년 ,월 현재 일 로 부터 하나씩 감소
	$prevYear = $year;
	$prevMonth = $month - 1;

	if($prevMonth < 1) {
		$prevYear--;
		$prevMonth = 12;
	}
	 
	//년, 월 현재일로 부터 하나씩 증가 
	$nextYear = $year;
	$nextMonth =  $month + 1;

	if($nextMonth > 12) {
		$nextYear++;
		$nextMonth = 1;
	}

	$nSchedule  = new ScheduleClass(); //봉사스케쥴
	$nSchedulepeo  = new SchedulepeoClass(); //봉사스케쥴
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$nSchedule->where = "where (substring(start_date,1,4) = '".$year."') order by seq asc";
		$nSchedule->page_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);


		

	function conv_subject($subject, $len, $suffix='')
	{
		return get_text(cut_str($subject, $len, $suffix));
	}
	function html_symbol($str)
	{
		return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
	}

	function get_text($str, $html=0)
	{
		/* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
		$source[] = "/  /";
		$target[] = " &nbsp;";
		*/

		// 3.31
		// TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
		if ($html == 0) {
			$str = html_symbol($str);
		}

		$source[] = "/</";
		$target[] = "&lt;";
		$source[] = "/>/";
		$target[] = "&gt;";
		//$source[] = "/\"/";
		//$target[] = "&#034;";
		$source[] = "/\'/";
		$target[] = "&#039;";
		//$source[] = "/}/"; $target[] = "&#125;";
		if ($html) {
			$source[] = "/\n/";
			$target[] = "<br/>";
		}

		return $str;
	}

	function cut_str($str, $len, $suffix="…")
	{
		$arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
		$str_len = count($arr_str);

		if ($str_len >= $len) {
			$slice_str = array_slice($arr_str, 0, $len);
			$str = join("", $slice_str);

			return $str . ($str_len > $len ? $suffix : '');
		} else {
			$str = join("", $arr_str);
			return $str;
		}
	}

	// TEMP
	$arr_people = null;
	$arr_service_people = null;
	$arr_board_name = null;
	
	//======================== DB Module End ===============================
	if(count($nSchedule->page_result) > 0){
		for($i=0, $cnt_list=count($nSchedule->page_result); $i < $cnt_list; $i++) {
			$nSchedule->VarList($nSchedule->page_result, $i, null);
			$nSchedule->subject = conv_subject($nSchedule->subject, 8, ''); 

			$nSchedulepeo->page_result = $Conn->AllList
			(	
				$nSchedulepeo->table_name, $nSchedulepeo, "*", "where schedule_seq='".$nSchedule->seq."' order by seq desc", null, null
			);
			$arr_board_name[$i][$nSchedule->work_date."_on"] = $arr_board_name[$nSchedule->work_date]."<a class='orange_small_btn' href='javascript:dateDetail($nSchedule->seq)'>".$nSchedule->subject."</a><br>";
			$arr_board_name[$i][$nSchedule->work_date."_off"] = $arr_board_name[$nSchedule->work_date]."<a class='gray_small_btn' href='javascript:dateDetail($nSchedule->seq)'>".$nSchedule->subject."</a><br>";
			$service_people = explode(',',$nSchedule->service_people);
			//$arr_service_people[$nSchedule->work_date] = $arr_board_people[$nSchedule->work_date].count($service_people);
			$arr_service_people[$nSchedule->work_date] = count($nSchedulepeo->page_result);
			$arr_people[$nSchedule->work_date] = $arr_board_people[$nSchedule->work_date].$nSchedule->people;
			
		
		}
		$nSchedule->ArrClear();
	}
$Conn->DisConnect();

?>
<div class="date">
	<a href="javascript:dateNumber({{ $prevYear }},{{ $prevMonth }})"><i class="fa fa-caret-left"></i></a> {{ $year }}년 {{ $month }}월 <a href="javascript:dateNumber({{ $nextYear }},{{ $nextMonth }})"><i class="fa fa-caret-right"></i></a>
</div>

<table class="calendar">
	<caption>봉사 스케쥴</caption>
	<colgroup>
		<col class="colWidth113">
		<col class="colWidth113">
		<col class="colWidth113">
		<col class="colWidth113">
		<col class="colWidth113">
		<col class="colWidth113">
		<col class="colWidth113">
	</colgroup>
	<tr>
		<th scope="col" class="orange">일</th>
		<th scope="col">월</th>
		<th scope="col">화</th>
		<th scope="col">수</th>
		<th scope="col">목</th>
		<th scope="col">금</th>
		<th scope="col" class="blue last">토</th>
	</tr>
	<tbody>
	<?php
	$today_year = date('Y'); //현재년도
	$today_month = date('m'); //현재월

	$last_day = date("t", strtotime($year."-".$month."-01")); //마지막 일
	$start_week = date("w", strtotime($year."-".$month."-01")); //시작주
	$total_week = ceil(($last_day + $start_week) / 7);//총몇주
	$last_week = date('w', strtotime($year."-".$month."-".$last_day));//마지막주

	$day=1;

	//총 주 수에 맞춰서 세로줄 만들기
		for($i=1; $i <= $total_week; $i++){
			echo "<tr>";

				//총 가로칸 만들기
				for ($j=0; $j<7; $j++){
					echo "<td style='line-height:200%;'>";
						if (!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))){
							
							$today = date('Y-m-d');
							$calendar_date = date('Y-m-d',strtotime($year."-".$month."-".$day));
							$reserv = dateDiff($today,$calendar_date);

							$service_people_count = $arr_service_people[date('Y-m-d',strtotime($year."-".$month."-".$day))];
							$people_count = $arr_people[date('Y-m-d',strtotime($year."-".$month."-".$day))];

							if($reserv > 0 || $service_people_count >= $people_count){ 
								$ckeck = "_off";
							}else{
								$ckeck = "_on";
							}

							if($j == 0){			
								$day_class = 'c_orange'; //일요일									
							}else if($j == 6){
								$day_class = 'c_light_blue'; //토요일									
							}else{
								$day_class = ''; //평일									
							}


							// 12. 오늘 날자면 굵은 글씨
							$class_name = null;
							if($today_year == $year && $today_month == $month && $day == date("j")){
								$class_name = "today";
							}

							// 13. 날자 출력
							echo "<span style='display:block;' class='".$class_name." ".$day_class." ".$year.$month.$day."'>";
							echo $day;
							echo "</span>";
							for($k=0; $k<count($arr_board_name); $k++){
								echo $arr_board_name[$k][date('Y-m-d',strtotime($year."-".$month."-".$day)).$ckeck]; 
							}

							
							// 14. 날자 증가
							$day++;
						}
					echo "</td>";
				}

			echo "</tr>";
		}
	?>
	</tbody>
</table>
<div class="schedule_submit" style="display:none;">

</div>

<script>
	function dateNumber (year,month){
		var view_link = '{{ route("sponsorzone_calendar") }}';
		$.ajax({
			type: 'GET',
			url: view_link,
			data: { year:year , month:month},
			success: function (data) {
				$('.schedule').empty();
				$('.schedule').html(data);
			},
			error: function(error) {
				console.log(error);
			}
		});
	}


	function dateDetail(seq){
		var detail_link = '/page/sponsor/calendar_detail.php';
		$.ajax({
			type: 'GET',
			url: detail_link,
			data: { seq:seq},
			success: function (data) {
				$('.schedule_submit').empty();
				$('.schedule_submit').html(data);
				$('.schedule_submit').show();
			}
		});
	}

	
</script>
<?php
if(isset($_GET['view'])){
?>
<script type="text/javascript">
<!--
	dateDetail({{ $_GET['view'] }});
//-->
</script>

<?php } ?>
