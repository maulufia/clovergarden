<?php
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

	for($i=2; $i<=$data->sheets[0]['numRows']; $i++) {
		$tmpNameArr = explode("(", $data->sheets[0]['cells'][$i][1]);

		if($data->sheets[0]['cells'][$i][1] != ""){
			$arr_field = array
			(
				'clover_seq', 'name', 'group_name', 'id', 'price','type'
			);

			$arr_value[$edit_cnt] = array(
				$data->sheets[0]['cells'][$i][1], $data->sheets[0]['cells'][$i][2], $data->sheets[0]['cells'][$i][3], $data->sheets[0]['cells'][$i][4],  $data->sheets[0]['cells'][$i][5], 1
			);


			$arr_field1 = array
			(
				'user_name', 'group_name', 'user_id', 'user_pw','user_state'
			);

			$arr_value1[$edit_cnt] = array(
				$data->sheets[0]['cells'][$i][2], $data->sheets[0]['cells'][$i][3], $data->sheets[0]['cells'][$i][4],  "81dc9bdb52d04dc20036dbd8313ed055", "5"
			);

		}
		if($data->sheets[0]['numRows'] != $edit_cnt) $edit_cnt = $edit_cnt + 1;
	}
	
	//======================== DB Module Clovert ============================
	$Conn = new DBClass();

		$Conn->InsertMultiDB($nMember->table_name, $arr_field1, $arr_value1);

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