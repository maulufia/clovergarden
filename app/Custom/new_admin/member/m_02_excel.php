<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();
require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자
require_once $_SERVER["DOCUMENT_ROOT"]."/Excel/reader.php";

	$list_link = $_POST['list_link'];
	$data = new Spreadsheet_Excel_Reader();

	$data->setOutputEncoding('utf-8');

	$data->read($_FILES['excel']['tmp_name']);

	error_reporting(E_ALL ^ E_NOTICE);

	$tmp = explode(".", $_FILES['excel']['name']);// echo "tmp : "; print_r($tmp);
	$Extension = $tmp[count($tmp) - 1];
  
	srand((double)microtime() * 1000000000);
	$Rnd = rand(1000000000, 9999999999);
	$Temp = date("YmdHis");
	$newName = $Temp . $Rnd . "." . $Extension; // .".".
  
	$nClovermlist   = new ClovermlistClass(); //후원기관
	$nMember = new MemberClass(); //회원


	$edit_cnt = 0;
	$arr_value = array();
	$arr_field = array
	(
		'clover_seq', 'name', 'group_name', 'id', 'price', 'type', 'day', 'address', 'bank', 'banknum', 'reg_date', 'start', 'bankdate', 'order_adm_ck'
	);
	$arr_field1 = array
	(
		'user_name', 'group_name', 'user_id', 'user_pw','user_state','member_t'
	);
	for($i=2; $i<=$data->sheets[0]['numRows']; $i++) {
		$tmpNameArr = explode("(", $data->sheets[0]['cells'][$i][1]);
		
		if($data->sheets[0]['cells'][$i][2] != ""){


			if($data->sheets[0]['cells'][$i][7] < 10){
				$day_zero = "0".$data->sheets[0]['cells'][$i][7];
			} else {
				$day_zero = $data->sheets[0]['cells'][$i][7];
			}

			$day_zero = $data->sheets[0]['cells'][$i][7];
			/*
			if($data->sheets[0]['cells'][$i][7] > date('d')){
				$start = date('Ym').$day_zero;
			} else {
				$start = (date('Ym',mktime()+(86400*30))).$day_zero;
			}
			*/
			$start = $data->sheets[0]['cells'][$i][13].$day_zero;

			//if($data->sheets[0]['cells'][$i][10] == ""){
				$date_view_insert = date("Y-m-d H:i:s");
			//} else {
				//$date_view_insert = $data->sheets[0]['cells'][$i][10]." ".date("H:i:s");
			//}
			$arr_value[$edit_cnt] = array(
				$data->sheets[0]['cells'][$i][2], $data->sheets[0]['cells'][$i][3], $data->sheets[0]['cells'][$i][4], $data->sheets[0]['cells'][$i][5],  $data->sheets[0]['cells'][$i][8], $data->sheets[0]['cells'][$i][6], $data->sheets[0]['cells'][$i][7], $data->sheets[0]['cells'][$i][9], $data->sheets[0]['cells'][$i][10], $data->sheets[0]['cells'][$i][11], $date_view_insert, $start, $data->sheets[0]['cells'][$i][12], 'y'
			);




			$arr_value1[$edit_cnt] = array(
				$data->sheets[0]['cells'][$i][3], $data->sheets[0]['cells'][$i][4], $data->sheets[0]['cells'][$i][5],  "4297f44b13955235245b2497399d7a93", "5", $data->sheets[0]['cells'][$i][6]
			);

		}
		if($data->sheets[0]['numRows'] != $edit_cnt) $edit_cnt = $edit_cnt + 1;
	}


	//======================== DB Module Clovert ============================
	$Conn = new DBClass();

		$Conn->InsertMultiDB2($nMember->table_name, $arr_field1, $arr_value1);

		$Conn->StartTrans();


		$out_put = $Conn->InsertMultiDB($nClovermlist->table_name, $arr_field, $arr_value);
		if($out_put){
			
			$Conn->CommitTrans();
			
			
		}else{

			$Conn->RollbackTrans();
			$Conn->disConnect();
			JsAlert(ERR_DATABASE, 1, $list_link);
		}


	$Conn->disConnect();
	//======================== DB Module End ===============================

    UrlReDirect(SUCCESS_WRITE, $list_link);
?>