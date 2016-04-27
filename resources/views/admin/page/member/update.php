<?php
// 정기 후원 업데이트 용. 단단히 잘못된 디자인 패턴

$Result = new mysqli("localhost","clovergarden","clovergarden12*!", 'clovergarden100') || die("Failed Database Connecting");
//$Result     = mysql_select_db("clovergarden100");
@mysql_query('set names utf8;', $DB_Connect); // 문자셋이 다른경우 사용
if(!$Result){
   echo "Failed Database connecting.(@Master Configuration)";
   $mcfg_db_is_connected = false;
   exit;   
} else {
   $mcfg_db_is_connected  = true; 
}  


function dateDiff_ck($date1, $date2){ 
	$_date1 = explode("-",$date1); 
	$_date2 = explode("-",$date2);

	$tm1 = mktime(0,0,0,$_date1[1],$_date1[2],$_date1[0]); 
	$tm2 = mktime(0,0,0,$_date2[1],$_date2[2],$_date2[0]);
	return ($tm2 - $tm1) / (86400*30);
}



if($_GET['clover_m_type'] == "modify"){

	$sql = "update new_tb_clover_mlist set clover_seq='".$_GET[value_ck]."' where seq='".$_GET[c_m_seq]."'";
	mysql_query($sql);
	echo "
	<script>
	alert('후원기관이 수정되었습니다.');
	window.location = './m_02_edit.php?seq=".$_GET[seq]."';
	</script>
	";
	exit;
}



$query = "select user_id from new_tb_member where order_cencle != 'n' and user_id != 'master@clovergarden.co.kr' order by seq desc";
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){


	$ck_query = "
		SELECT * FROM (
			select * from new_tb_clover_mlist where id='".$row[user_id]."' and start != '' and otype != 'point' and ing_cencle != 'n' order by start desc
		) AS aliasTable
		GROUP BY
			aliasTable.clover_seq
	";



	$ck_result = mysql_query($ck_query);
	$today_ck = date('Ym').'01';
	while($ck_row = mysql_fetch_array($ck_result)){
		if($ck_row['start'] < $today_ck){

			$mquery = "
				SELECT * FROM (
					select * from new_tb_clover_mlist where id='".$row['user_id']."' and start != '' and start < '".date('Ym')."01' and otype != 'point' and seq='".$ck_row['seq']."' order by start desc
				) AS aliasTable
				GROUP BY
					aliasTable.clover_seq
			";
			$mresult = mysql_query($mquery);
			while($mrow = mysql_fetch_array($mresult)){


				$ck_date_y = substr($mrow['start'],0,4);
				$ck_date_m = substr($mrow['start'],4,2);
				$ck_date_d = substr($mrow['start'],6,2);
				if($ck_date_d == ''){
					$ck_date_d = '05';
				}

				//echo $ck_date_y.'-'.$ck_date_m.'-'.$ck_date_d.'= '.$mrow[seq].' <BR>';
				$date_return = dateDiff_ck($ck_date_y.'-'.$ck_date_m.'-'.$ck_date_d , date('Y-m-d') );
				$date_return = floor($date_return);
				if($date_return > 0){


					for($i=1; $i<=$date_return; $i++){
						$_date_view = explode("-",$ck_date_y.'-'.$ck_date_m.'-'.$ck_date_d); 
						$tm_view = mktime(0,0,0,$_date_view[1],$_date_view[2],$_date_view[0]); 
						$datemktime = $tm_view+(86400*30*$i);
						$ck_date = date('Ymd',$datemktime);
						if($ck_date <= date('Ymd')){
							$insert_date = date('Ym',$datemktime);
							$insert_date_line = date('Y-m',$datemktime);
							if($mrow['day'] < 10){
								$date_day = "0".$mrow['day'];
							} else {
								$date_day = $mrow['day'];
							}
							$sql = "
							insert into new_tb_clover_mlist set
								type = '".$mrow['type']."',
								otype = '".$mrow['otype']."',
								order_num = '".$mrow['order_num']."',
								clover_seq = '".$mrow['clover_seq']."',
								name = '".$mrow['name']."',
								group_name = '".$mrow['group_name']."',
								birth = '".$mrow['birth']."',
								id = '".$mrow['id']."',
								price = '".$mrow['price']."',
								day = '".$mrow['day']."',
								start = '".$insert_date.$date_day."',
								zip = '".$mrow['zip']."',
								address = '".$mrow['address']."',
								cell = '".$mrow['cell']."',
								email = '".$mrow['email']."',
								bank = '".$mrow['bank']."',
								banknum = '".$mrow['banknum']."',
								bankdate = '".$mrow['bankdate']."',
								reg_date = '".$insert_date_line."-".$date_day." 00:00:00',
								order_adm_ck = 'y'					
							;
							";
							//echo $sql."<BR>";
							mysql_query($sql);
						}
					}
				}
			}
		}
	}





	$ck_query = "
		SELECT * FROM (
			select * from new_tb_clover_mlist where id='".$row['user_id']."' and start != '' and otype = 'point' and ing_cencle != 'n' order by start desc
		) AS aliasTable
		GROUP BY
			aliasTable.clover_seq
	";

	$ck_result = mysql_query($ck_query);
	$today_ck = date('Ym').'01';
	while($ck_row = mysql_fetch_array($ck_result)){

		if($ck_row[start] < $today_ck){
			$mquery = "
				SELECT * FROM (
					select * from new_tb_clover_mlist where id='".$row['user_id']."' and start != '' and start < '".date('Ym')."01' and otype = 'point' and seq='".$ck_row['seq']."' order by start desc
				) AS aliasTable
				GROUP BY
					aliasTable.clover_seq
			";
			//echo $mquery."<BR>";
			$mresult = mysql_query($mquery);
			while($mrow = mysql_fetch_array($mresult)){
				$p_query = "select sum(inpoint) inpoint, sum(outpoint) outpoint from new_tb_point where userid='".$mrow['id']."' group by userid";
				$p_result = mysql_query($p_query);
				$p_row = mysql_fetch_array($p_result);
				$use_point = $p_row['inpoint'] - $p_row['outpoint'];
				if($use_point > $mrow['price']){

					$ck_date_y = substr($mrow['start'],0,4);
					$ck_date_m = substr($mrow['start'],4,2);
					$ck_date_d = substr($mrow['start'],6,2);
					if($mrow['day'] < 10){
						$date_day = "0".$mrow['day'];
					} else {
						$date_day = $mrow['day'];
					}
					$sql = "
					insert into new_tb_clover_mlist set
						type = '".$mrow['type']."',
						otype = '".$mrow['otype']."',
						order_num = '".$mrow['order_num']."',
						clover_seq = '".$mrow['clover_seq']."',
						name = '".$mrow['name']."',
						group_name = '".$mrow['group_name']."',
						birth = '".$mrow['birth']."',
						id = '".$mrow['id']."',
						price = '".$mrow['price']."',
						day = '".$mrow['day']."',
						start = '".date('Ym').$date_day."',
						zip = '".$mrow['zip']."',
						address = '".$mrow['address']."',
						cell = '".$mrow['cell']."',
						email = '".$mrow['email']."',
						bank = '".$mrow['bank']."',
						banknum = '".$mrow['banknum']."',
						bankdate = '".$mrow['bankdate']."',
						reg_date = '".date('Y-m-d')." 00:00:00',
						order_adm_ck = 'y'
					;
					";
					//echo $sql."<BR>";
					mysql_query($sql);

					$c_query = "select * from new_tb_clover where code='".$mrow['clover_seq']."' order by seq limit 1";
					$c_result = mysql_query($c_query);
					$c_row = mysql_fetch_array($c_result);
					
					$sql = "
					insert into new_tb_point set
						signdate = '".mktime()."',
						depth = '".$c_row['subject']." 후원',
						outpoint = '".$mrow['price']."',
						userid = '".$mrow['id']."'
					";
					mysql_query($sql);
				}
			}
		}
	}

}



?>