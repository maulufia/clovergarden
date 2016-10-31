<?php

namespace clovergarden\Http\Controllers;

use Input, DB, Auth, Redirect, Hash;

class ChargeController extends Controller
{
	public function __construct()
	{
		include(app_path().'/Custom/_common/_global.php');
	}

	public function showProgress()
	{
		return view('payment.agspay.AGS_progress');
	}

	public function showAGSPay()
	{
		return view('payment.agspay.AGS_pay_ing');
	}

	public function showAGSVirAcctResult()
	{
		return view('payment.agspay.AGS_VirAcctResult');
	}

	/**************** MOBILE ****************/
	public function showAGSPayMobileResv()
	{
		$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

		$nClover = new \CloverClass(); //클로버목록
		$nPoint = new \PointClass(); //포인트
		$nMember = new \MemberClass();
		$nAdm_4 = new \AdmClass(); //

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nClover->where = "where seq ='".$seq."'";

		$nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", $nClover->where, null, null);
		if(count($nClover->read_result) != 0){
			$nClover->VarList($nClover->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}

		//======================== DB Module Start ============================
		$nPoint->page_result = $Conn->AllList
		(
			$nPoint->table_name, $nPoint, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='" . Auth::user()->user_id . "' group by userid", null, null
		);
		$nMember->page_result = $Conn->AllList
		(
			$nMember->table_name, $nMember, "*", "where user_id='" . Auth::user()->user_id . "'", null, null
		);
		$user_name_ck = '';
		for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
			$nMember->VarList($nMember->page_result, $i, null);
			$user_name_ck = $nMember->user_name;
			$post1 = $nMember->post1;
			$post2 = $nMember->post2;
			$addr = $nMember->addr1;
			$addr2 = $nMember->addr2;
		}
		$nAdm_4->page_result = $Conn->AllList
		(
			$nAdm_4->table_name, $nAdm_4, "*", "where t_name='use_v_3' order by idx desc limit 1", null, null
			);
		$Conn->DisConnect();
		//======================== DB Module End ===============================

		return view('payment.agspay_mobile.AGSMobile_start_resv', ['nClover' => $nClover,
																															 'nPoint' => $nPoint,
																															 'post1' => $post1,
																															 'post2' => $post2,
																															 'addr' => $addr,
																															 'addr2' => $addr2
																															]);
	}

	public function showAGSPayMobileTemp()
	{
		$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

		$nClover = new \CloverClass(); //클로버목록
		$nPoint = new \PointClass(); //포인트
		$nMember = new \MemberClass();
		$nAdm_4 = new \AdmClass(); //

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nClover->where = "where seq ='".$seq."'";

		$nClover->read_result = $Conn->AllList($nClover->table_name, $nClover, "*", $nClover->where, null, null);
		if(count($nClover->read_result) != 0){
			$nClover->VarList($nClover->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}

		//======================== DB Module Start ============================
		$nPoint->page_result = $Conn->AllList
		(
			$nPoint->table_name, $nPoint, "sum(inpoint) inpoint, sum(outpoint) outpoint", "where userid='" . Auth::user()->user_id . "' group by userid", null, null
		);
		$nMember->page_result = $Conn->AllList
		(
			$nMember->table_name, $nMember, "*", "where user_id='" . Auth::user()->user_id . "'", null, null
		);
		$user_name_ck = '';
		for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
			$nMember->VarList($nMember->page_result, $i, null);
			$user_name_ck = $nMember->user_name;
			$post1 = $nMember->post1;
			$post2 = $nMember->post2;
			$addr = $nMember->addr1;
			$addr2 = $nMember->addr2;
		}
		$nAdm_4->page_result = $Conn->AllList
		(
			$nAdm_4->table_name, $nAdm_4, "*", "where t_name='use_v_3' order by idx desc limit 1", null, null
			);
		$Conn->DisConnect();
		//======================== DB Module End ===============================

		return view('payment.agspay_mobile.AGSMobile_start_temp', ['nClover' => $nClover,
																															 'nPoint' => $nPoint,
																															 'post1' => $post1,
																															 'post2' => $post2,
																															 'addr' => $addr,
																															 'addr2' => $addr2
																															]);
	}

	// 일시 후원 (AGS) 성공 시 뷰
	public function showAGSPayMobileApprove()
	{
		$tracking_id = $_REQUEST["tracking_id"];
		$transaction = $_REQUEST["transaction"];
		$StoreId = $_REQUEST["StoreId"];
		$log_path = null;
		// log파일 저장할 폴더의 경로를 지정합니다.
	  // 경로의 값이 null로 되어있을 경우 "현재 작업 디렉토리의 /lib/log/"에 저장됩니다.

		$agsMobile = new \AGSMobile($store_id,$tracking_id,$transaction, $log_path);
		$agsMobile->setLogging(false); //true : 로그기록, false : 로그기록안함.

		return view('payment.agspay_mobile.AGSMobile_approve', ['agsMobile' => $agsMobile]);
	}

	public function showPayWithPointApprove($clover_name, $price)
	{
		return view('mobile.payment.point_approve', ['clover_name' => $clover_name,
																								 'price' => $price
																							 ]);
	}

	// 일시 후원 취소 시 뷰
	public function showAGSPayMobileCancel()
	{
		return view('payment.agspay_mobile.AGSMobile_cancel');
	}

	// 모바일 정기후원
	public function execReserveSupport() {
    $nClovermlist = new \ClovermlistClass(); //쪽지
		$nPoint = new \PointClass(); //포인트

		$nClovermlist->otype    = $_POST['otype']; // 이체/신용
		$nClovermlist->clover_seq    = $_POST['clover_seq']; // 기관코드

		$nClovermlist->name    = $_POST['name']; // 이름
		$nClovermlist->birth    = $_POST['birth']; // 생년월일
		$nClovermlist->id    = Auth::user()->user_id; // 아이디

		if($nClovermlist->otype == "point"){
			$nClovermlist->price    = $_POST['supporting_agency']; // 금액
			$order_adm_ck_v = "y";
		} else {
			$nClovermlist->price    = $_POST['price']; // 금액
			$order_adm_ck_v = "n";
		}

		if($nClovermlist->price%1000 != 0){
			JsAlert("천원 단위로만 후원이 가능합니다.", 0);
			exit;
		}
		$nClovermlist->day    = $_POST['date']; // 약정일

		if($nClovermlist->day < 10){
			$day_zero = "0".$nClovermlist->day;
		} else {
			$day_zero = $nClovermlist->day;
		}
		if($_POST['date'] > date('d')){
			$start = date('Ym').$day_zero;
		} else {
			$start = (date('Ym',time()+(86400*30))).$day_zero;
		}
		if($nClovermlist->otype == "point"){
			$nClovermlist->start    = date('Ymd'); // 시작월
			$nClovermlist->day = date('d');
		} else {
			$nClovermlist->start    = $start; // 시작월
			$nClovermlist->day = $nClovermlist->day;
		}
		$nClovermlist->zip    = $_POST['zip1']."-".$_POST['zip2']; // 우편번호
		$nClovermlist->address    = $_POST['addr']; // 주소
		$nClovermlist->cell    = $_POST['cell1']."-".$_POST['cell2']."-".$_POST['cell3']; // 휴대폰
		$nClovermlist->email    = Auth::user()->user_id; // 이메일 = ID

		if($_POST['otype']=="자동이체"){
			$nClovermlist->bank    = $_POST['bank'];
			$nClovermlist->banknum    = $_POST['banknum'];
			$nClovermlist->bankdate    = "";
		}else if($_POST['otype']=="신용카드"){
			$nClovermlist->bank    = $_POST['card'];
			$nClovermlist->banknum    = $_POST['cardnum'];
			$nClovermlist->bankdate    = $_POST['carddate2'].'년'.$_POST['carddate1'].'월';
		}

		$arr_field = array
	    (
	        'otype', 'clover_seq', 'name', 'group_name', 'birth', 'id', 'price', 'day', 'start', 'zip', 'address', 'cell', 'email', 'bank', 'banknum', 'bankdate', 'order_ck' ,'order_adm_ck'
	    );

		$arr_value = array(
			$nClovermlist->otype, $nClovermlist->clover_seq, $nClovermlist->name, Auth::user()->group_name, $nClovermlist->birth, $nClovermlist->id, $nClovermlist->price, $nClovermlist->day, $nClovermlist->start, $nClovermlist->zip, $nClovermlist->address, $nClovermlist->cell, $nClovermlist->email, $nClovermlist->bank, $nClovermlist->banknum, $nClovermlist->bankdate, 'h', $order_adm_ck_v
		);

		//======================== DB Module Start ============================
		$Conn = new \DBClass();


		$nPoint->read_result = $Conn->AllList($nPoint->table_name, $nPoint, 'sum(inpoint) inpoint, sum(outpoint) outpoint', "where userid ='". Auth::user()->user_id ."' group by userid", null, null);
		if(count($nPoint->read_result) != 0){
			$nPoint->VarList($nPoint->read_result);
		}

		if(count($nPoint->read_result) != 0){
			$nPoint->VarList($nPoint->read_result);
		}

		// 원래 포인트 지급 자체가 제대로 되지 않음
		$nPoint->inpoint = isset($nPoint->inpoint) ? $nPoint->inpoint : 0;
		$nPoint->outpoint = isset($nPoint->outpoint) ? $nPoint->outpoint : 0;

		$use_point = $nPoint->inpoint - $nPoint->outpoint;
		if($nClovermlist->price > $use_point && $nClovermlist->otype == "point"){
			JsAlert("보유하신 포인트보다 후원할 포인트가 더 많습니다.", 0);
			exit;
		} else if ($nClovermlist->price < 1000 && $nClovermlist->otype == "point") {
			JsAlert("포인트는 1000포인트 이상부터 사용하실 수 있습니다.", 0);
			exit;
		}
		$sql = "update new_tb_member set update_ck='Y' where user_id='". Auth::user()->user_id ."'";
		mysql_query($sql);
		if($nClovermlist->otype == "point"){
			$clover_name_v = (new \CloverModel())->getCloverList();
			$sql = "
			insert into new_tb_point set
				signdate = '".time()."',
				depth = '" . $clover_name_v[$nClovermlist->clover_seq] . " 정기 후원',
				outpoint = '".$nClovermlist->price."',
				userid = '" . Auth::user()->user_id . "'
			";
			mysql_query($sql);
		}


		$Conn->StartTrans();

		$out_put = $Conn->InsertDB($nClovermlist->table_name, $arr_field, $arr_value);

		if($out_put){
			$Conn->CommitTrans();
		}else{
			$Conn->RollbackTrans();
			$Conn->disConnect();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================

		$data = new \StdClass();
		$data->otype = $nClovermlist->otype;
		$data->clover_seq = $nClovermlist->clover_seq;
		$data->name = $nClovermlist->name;
		$data->birth = $nClovermlist->birth;
		$data->id = Auth::user()->user_id;
		$data->price = $nClovermlist->price;
		$data->day = $nClovermlist->day;
		$data->start = $nClovermlist->start;

		$data->bank = $nClovermlist->bank;
		$data->banknum = $nClovermlist->banknum;
		$data->bankdate = $nClovermlist->bankdate;

		JsAlert("정기후원이 신청되었습니다.", 1, 'clovergardenapp://?');
	}

	// 모바일 일시후원 - 포인트
	public function execTempPointSupport()
	{
		$nClovermlist = new \ClovermlistClass(); //후원기관

	  $nClovermlist->otype = "point";
	  $nClovermlist->clover_seq = $_POST['clover_seq'];
	  $nClovermlist->clover_name = $_POST['clover_name'];
		$nClovermlist->name        = Auth::user()->user_name;
		$nClovermlist->id        = Auth::user()->user_id;
	  $nClovermlist->price = $_POST['price'];
		$nClovermlist->type = 1;
		$rOrdNo = "point".rand(15349,99999);

    $arr_field = array
    (
        'otype','order_num','clover_seq', 'name',"group_name", 'id', 'price', 'order_adm_ck'
    );

    $arr_value = array
    (
        $nClovermlist->otype, $rOrdNo, $nClovermlist->clover_seq, $nClovermlist->name, $group_name, $nClovermlist->id,  $nClovermlist->price, 'y'
    );

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

		if($nClovermlist->price%1000 != 0){
			JsAlert("천원 단위로만 후원이 가능합니다.", 0);
			exit;
		}

		$sql = "update new_tb_member set update_ck='Y' where user_id='" . Auth::user()->user_id . "'";
		mysql_query($sql);

	  $Conn->StartTrans();
	  $out_put = $Conn->insertDB($nClovermlist->table_name, $arr_field, $arr_value);
	  if($out_put){
	      $Conn->CommitTrans();
	  }else{
	      $Conn->RollbackTrans();
	      $Conn->disConnect();
	  }

	  $clover_name_v = (new \CloverModel())->getCloverList();
		$sql = "
		insert into new_tb_point set
			signdate = '".time()."',
			depth = '".$clover_name_v[$nClovermlist->clover_seq]." 일시 후원',
			outpoint = '".$nClovermlist->price."',
			userid = '" . Auth::user()->user_id . "'
		";
		mysql_query($sql);

		$Conn->disConnect();

		return $this->showPayWithPointApprove($nClovermlist->clover_name, $nClovermlist->price);
	}
}
