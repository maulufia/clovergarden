<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect, URL, Hash, DB;

class MainController extends Controller
{
	
	public function __construct() {
		include(app_path().'/Custom/_common/_global.php');
	}
	
	/*
	  |--------------------------------------------------------------------------
	  | Main Site Route Methods
	  |--------------------------------------------------------------------------
	  |
	  | These methods below are for main pages.
	  |
	  */
	
	// 메인
	public function showHome() {
		$sub_cate = null; // initiate
		
		return view('front.home', ['sub_cate' => $sub_cate,
															]);
	}
	
	// 후원자마당
	public function showSponsorZone() {
		$sub_cate = 0; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('sponsorzone') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		$cate_02_result = \CateHelper::checkPage($sub_cate,'sub_cate_02');
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'edit_link' => $option->edit_link
														 ]);
	}
	
	// 클로버가든
	public function showCloverGarden() {
		$sub_cate = 1; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('clovergarden') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->writeresv_link = $option->list_link.'&type=writeresv';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'writeresv_link' => $option->writeresv_link,
														 'edit_link' => $option->edit_link
														 ]);
	}
	
	// 함께하는 사람들
	public function showCompanion() {
		$sub_cate = 2; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('companion') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'edit_link' => $option->edit_link
														 ]);
	}
	
	// 이용안내
	public function showInformation() {
		$sub_cate = 3; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('information') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'edit_link' => $option->edit_link
														 ]);
	}

  // 고객 센터	
	public function showCustomer() {
		$sub_cate = 4; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('customer') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'edit_link' => $option->edit_link
														 ]);
	}
	
	// 로그인
	public function showLogin() {
		$sub_cate = 5; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		return view($view_name, ['cate' => $sub_cate,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result
														 ]);
	}
	
	// 마이페이지
	public function showMypage() {
		$sub_cate = 6; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		$option->list_link = route('mypage') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
    $cate_01 = \CateHelper::checkPage($sub_cate,'cate');
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = "";
		$cate_02_result = \CateHelper::checkPage($sub_cate,'sub_cate_02');
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		if($view_name == null) // view_name이 null이면(인증필요) 리다이렉트
			return redirect()->route('login');
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01' => $cate_01,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result,
														 'seq' => $option->seq,
														 'list_link' => $option->list_link,
														 'view_link' => $option->view_link,
														 'write_link' => $option->write_link,
														 'edit_link' => $option->edit_link
														 ]);
	}
	
	// 사이트맵
	public function showSitemap() {
		$sub_cate = 7; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02);
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result
														 ]);
	}
	
	public function showUserinfo() {
		$sub_cate = 8; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02);
		
		return view($view_name, ['cate' => 0,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result
														 ]);
	}
	
	/*
  |--------------------------------------------------------------------------
  | Sub Route (AJAX)
  |--------------------------------------------------------------------------
  |
  | These contains pages which will be used for AJAX call.
  |
  */
  
  public function showPopup() {
		return view('front.popup.popup');
  }
  
  public function showCalendar() {
  	return view('front.page.sponsorzone.calendar');
  }
  
  public function showTempSupportResultPoint() {
  	$sub_cate = 1; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;

  	return view('front.page.clovergarden.home_write_resultpoint', ['cate' => 1,
																																	 'sub_cate' => $sub_cate,
																																	 'cate_file' => $cate_file,
																																	 'cate_name' => $cate_name,
																																	 'sub_cate' => $sub_cate,
																																	 'dep01' => $dep01,
																																	 'dep02' => $dep02,
																																	 'dep02_active' => $dep02_active,
																																	 'cate_01_result' => $cate_01_result,
																																	 'cate_01_count' => $cate_01_count,
																																	 'cate_01_type' => $cate_01_type,
																																	 'cate_02_result' => $cate_02_result
																																	 ]);
  }
  
  public function showReserveSupportResult($data) {
  	$sub_cate = 1; // initiate
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		$dep02_active = isset($dep02) ? $dep02 : 0;
		
		$cate_file = \CateHelper::checkPage($sub_cate,'cate'); //대분류 이름
    $cate_name = \CateHelper::checkPage($sub_cate,'name'); //대분류 이름
		$cate_01_result = \CateHelper::checkPage($sub_cate,'sub_cate_01');
		$cate_01_count = count($cate_01_result);
		// $cate_01_type = $this->checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
		$cate_01_type = ""; 
		$cate_02_result = 0;

  	return view('front.page.clovergarden.home_writeresv_result', ['cate' => 1,
														 'sub_cate' => $sub_cate,
														 'cate_file' => $cate_file,
														 'cate_name' => $cate_name,
														 'sub_cate' => $sub_cate,
														 'dep01' => $dep01,
														 'dep02' => $dep02,
														 'dep02_active' => $dep02_active,
														 'cate_01_result' => $cate_01_result,
														 'cate_01_count' => $cate_01_count,
														 'cate_01_type' => $cate_01_type,
														 'cate_02_result' => $cate_02_result
														 ]);
	}
	
	public function showCheckMember() {
		return view('front.page.mypage.checkmember');
	}
	
	public function showCheckGroupName() {
  	return view('front.page.login.check_group_name');
  }
	  
  /*
  |--------------------------------------------------------------------------
  | POST Method Controller
  |--------------------------------------------------------------------------
  |
  | Because of Spaghetti code, creating post method controller
  |
  */
  
  public function postMethodControl() {
	  $sub_cate = isset($_REQUEST['cate']) ? $_REQUEST['cate'] : 0;
		$dep01 = isset($_REQUEST['dep01']) ? $_REQUEST['dep01'] : 0;
		$dep02 = isset($_REQUEST['dep02']) ? $_REQUEST['dep02'] : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
		$option->seq = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : null;
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		$search_sponsor_exec = array(
										'front.page.sponsorzone.community.board_sponsor'
									);
		$search_clover_exec = array(
										'front.page.clovergarden.newsletter'
									);
		$search_customer_exec = array(
										'front.page.customer.home',
										'front.page.customer.faq'
									);
		$search_mypage_exec = array(
										'front.page.mypage.messagebox.messagebox_send',
										'front.page.mypage.messagebox.messagebox_get',
										'front.page.mypage.point'
									);
		$board_write = array( // 좋은 디자인은 아니다
									'front.page.sponsorzone.community.board_sponsor_write',
									'front.page.sponsorzone.community.board_company_write'
									);
		$board_edit = array( // 좋은 디자인은 아니다
									'front.page.sponsorzone.community.board_sponsor_edit',
									'front.page.sponsorzone.community.board_company_edit'
									);
		$cheer_write = array( // 좋은 디자인은 아니다
									'front.page.clovergarden.home_comment_write'
									);
		$support_temp_point = array( // 좋은 디자인은 아니다
									'front.page.clovergarden.home_write_resultpoint'
									);
		$support_reserve = array( // 좋은 디자인은 아니다
											'front.page.clovergarden.home_writeresv'
											);
		$modify_personal = array( // 좋은 디자인은 아니다
											'front.page.mypage.modify_personal_edit'
											);
		$signup = array(
							'front.page.login.signup_write'
							);
		
		foreach ($search_sponsor_exec as $fe) {
			if($fe == $view_name) {
				return $this->showSponsorZone();
			}
		}
		
		foreach ($search_clover_exec as $fe) {
			if($fe == $view_name) {
				return $this->showCloverGarden();
			}
		}
		
		foreach ($search_customer_exec as $fe) {
			if($fe == $view_name) {
				return $this->showCustomer();
			}
		}
		
		foreach ($search_mypage_exec as $fe) {
			if($fe == $view_name) {
				return $this->showMypage();
			}
		}
		
		foreach ($board_write as $fe) {
			if($fe == $view_name) {
				return $this->writePost();
			}
		}
		
		foreach ($board_edit as $fe) {
			if($fe == $view_name) {
				return $this->editPost();
			}
		}
		
		foreach ($cheer_write as $fe) {
			if($fe == $view_name) {
				return $this->writeCheer();
			}
		}
		
		foreach ($support_temp_point as $fe) {
			if($fe == $view_name) {
				return $this->showTempSupportResultPoint();
			}
		}
		
		foreach ($support_reserve as $fe) {
			if($fe == $view_name) {
				return $this->execReserveSupport();
			}
		}
		
		foreach ($modify_personal as $fe) {
			if($fe == $view_name) {
				return $this->modifyPersonal();
			}
		}
		
		foreach ($signup as $fe) {
			if($fe == $view_name) {
				return $this->userSignUp();
			}
		}
	}
	
	/*
  |--------------------------------------------------------------------------
  | Payment Controller
  |--------------------------------------------------------------------------
  |
  | executing Payment
  |
  */
  
 	private function execTempSupportPoint() {
 		// NON
 	}
	
	private function execReserveSupport() {
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
		$nClovermlist->day    = $_POST['day']; // 약정일

		if($nClovermlist->day < 10){
			$day_zero = "0".$nClovermlist->day;
		} else {
			$day_zero = $nClovermlist->day;
		}
		if($_POST['day'] > date('d')){
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
		$nClovermlist->address    = $_POST['addr1']."-".$_POST['addr2']; // 주소
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
		
	  return $this->showReserveSupportResult($data);
	}
	
	/*
  |--------------------------------------------------------------------------
  | Database Controller
  |--------------------------------------------------------------------------
  |
  | executing CRUD <- Need to be moved to MODEL
  |
  */
  
  public function checkId() {
		$nMember = new \MemberClass(); //회원

		$mem_id_check = "n";

		$user_id = rawurldecode(strtoupper($_GET['user_id']));

		if($user_id != ''){
			//======================== DB Module Start ============================
			$Conn = new \DBClass();

				$nMember->where = "where user_id = '".$user_id."'";
				$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);
				if($nMember->read_result){
				   $mem_id_check = "m";
				}else{
				   $mem_id_check = "y";
				}

			$Conn->DisConnect();
			//======================== DB Module End ===============================
		}

		$arr_json = array
		(
			"mem_id_check"   => $mem_id_check,
		);
		$json_return = json_encode($arr_json);
		echo urldecode($json_return);
  }
  
  public function checkSns() {
		if($_POST['action']=='go'){

		   /******************** 인증정보 ********************/
			$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
			// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
			$sms['user_id'] = base64_encode("clovergarden1000"); //SMS 아이디.
			$sms['secure'] = base64_encode("5decd3e1b30d73176110964056803208");//인증키

			$_POST['msg'] = "요청하신 인증번호는 ".$_POST['msg_n']." 입니다.";
			$sms['msg'] = base64_encode(stripslashes($_POST['msg']));
			$_POST['smsType'] = 'S';
			if($_POST['smsType'] == 'L') {
				$sms['subject'] = base64_encode($_POST['subject']); //제목
			}
			$str_phone = substr($_POST['m_phone'],0,3);
			$str_phone2 = substr($_POST['m_phone'],3,4);
			$str_phone3 = substr($_POST['m_phone'],7,4);
			$phone_s = $str_phone."-".$str_phone2."-".$str_phone3;

			$_POST['sphone1'] = '02';
			$_POST['sphone2'] = '720';
			$_POST['sphone3'] = '3235';
			$_POST['rphone'] = $phone_s;
			$sms['rphone'] = base64_encode($_POST['rphone']);
			$sms['sphone1'] = base64_encode($_POST['sphone1']);
			$sms['sphone2'] = base64_encode($_POST['sphone2']);
			$sms['sphone3'] = base64_encode($_POST['sphone3']);


			$phone_aaa = $sms['sphone1'].$sms['sphone2'].$sms['sphone3'];
			$sms['rdate'] = '';
			$sms['rtime'] = '';
			$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
			$sms['returnurl'] = '';
			$sms['testflag'] = '';
			$sms['destination'] = '';
			$returnurl = '';
			$sms['repeatFlag'] = '';
			$sms['repeatNum'] = '';
			$sms['repeatTime'] = '';
			$sms['smsType'] = base64_encode($_POST['smsType']); // LMS일경우 L

			$nointeractive = 1; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

			$host_info = explode("/", $sms_url);
			$host = $host_info[2];
			$path = $host_info[3];

			srand((double)microtime()*1000000);
			$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
			//print_r($sms);

			// 헤더 생성
			$header = "POST /".$path ." HTTP/1.0\r\n";
			$header .= "Host: ".$host."\r\n";
			$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

			// 본문 생성
			$data = null;
			foreach($sms AS $index => $value){
				$data .="--$boundary\r\n";
				$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
				$data .= "\r\n".$value."\r\n";
				$data .="--$boundary\r\n";
			}
			$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

			$fp = fsockopen($host, 80);

			if ($fp) {
				fputs($fp, $header.$data);
				$rsp = '';
				while(!feof($fp)) {
					$rsp .= fgets($fp,8192);
				}
				fclose($fp);
				$msg = explode("\r\n\r\n",trim($rsp));
				$rMsg = explode(",", $msg[1]);
				$Result= $rMsg[0]; //발송결과
				$Count= $rMsg[1]; //잔여건수

				//발송결과 알림
				if($Result=="success") {
					$alert = "성공";
					$alert .= " 잔여건수는 ".$Count."건 입니다.";
				}
				else if($Result=="reserved") {
					$alert = "성공적으로 예약되었습니다.";
					$alert .= " 잔여건수는 ".$Count."건 입니다.";
				}
				else if($Result=="3205") {
					$alert = "잘못된 번호형식입니다.";
				}

				else if($Result=="0044") {
					$alert = "스팸문자는발송되지 않습니다.";
				}

				else {
					$alert = "[Error]".$Result;
				}
			}
			else {
				$alert = "Connection Failed";
			}

			if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
				echo "<script>alert('".$alert ."')</script>";
			}
			else if($nointeractive!="1") {
				echo "<script>alert('".$alert ."')</script>";
			} else {
				echo "<script>alert('인증번호가 발송되었습니다.')</script>";
			}
			//echo "<script>location.href='".$returnurl."';</script>";
		}
  }
  
  public function checkGroup() {
		$nMember = new \MemberClass(); //회원

		$mem_group_check = "n";

		$group_name = rawurldecode($_GET['group_name']);
		$group_state = rawurldecode($_GET['group_state']);

		if($group_name != ''){
			//======================== DB Module Start ============================
			$Conn = new \DBClass();

				$nMember->where = "where group_name = '".$group_name."' and user_state='".$group_state."'";
				$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);
				if($nMember->read_result){
				   $mem_group_check = "m";
				}else{
				   $mem_group_check = "y";
				}

			$Conn->DisConnect();
			//======================== DB Module End ===============================
		}

		$arr_json = array
		(
			"mem_group_check"   => $mem_group_check
		);
		$json_return = json_encode($arr_json);
		echo urldecode($json_return);
  }
  
  public function execEmail() {
    $nEmail= new \EmailClass(); //온라인상담

    $nEmail->name = $_POST['name'];
		$nEmail->email = $_POST['email']."@".$_POST['email2'];

    $counsel_check = "n";

    $arr_field = array
    (
        'name', 'email'
    );

    $arr_value = array
    (
        $nEmail->name, $nEmail->email
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$Conn->StartTrans();
		$out_put = $Conn->insertDB($nEmail->table_name, $arr_field, $arr_value);
		if($out_put){
			$Conn->CommitTrans();
			$counsel_check = "y";
		}else{
			$Conn->RollbackTrans();
		}

		$Conn->DisConnect();
		//======================== DB Module End ===============================

    $arr_json = array
    (
        "counsel_check"   => iconv('EUC-KR', 'UTF-8', $counsel_check)
    );

    $json_return = json_encode($arr_json);
    // echo '@@||@@'.urldecode($json_return); 무얼 위한 기능인지 모르겠다. AJAX였어서 그런 듯.
    
    return redirect()->route('home');
  }
  
  private function writePost() {
  	$nFree = new \FreeClass(); //자유게시판

		$nFree->writer    = $_POST['writer'] . "," . Auth::user()->user_id; // 작성자ID
		$nFree->subject    = $_POST['subject']; // 제목
		$nFree->content    = RepEditor($_POST['content']); // 내용


    $arr_field = array
    (
        'writer', 'subject', 'content', 'group_name'
    );

		$arr_value = array($nFree->writer, $nFree->subject, $nFree->content, Auth::user()->group_name);
		
		
	
  	//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$Conn->StartTrans();

		$out_put = $Conn->InsertDB($nFree->table_name, $arr_field, $arr_value);

		if($out_put) {
			$Conn->CommitTrans();
		} else {
			$Conn->RollbackTrans();
			$Conn->disConnect();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('sponsorzone', array('cate' => 0, 'dep01' => 1, 'dep02' => 1));
  }
  
  private function editPost() {
		$nFree = new \FreeClass();

		$nFree->writer    = $_POST['writer'] . "," . Auth::user()->user_id; // 작성자ID
		$nFree->subject    = $_POST['subject']; // 제목
		$nFree->content    = RepEditor($_POST['content']); // 내용

    $arr_field = array
    (
        'subject', 'content'
    );

    $arr_value = array
    (
        $nFree->subject, $nFree->content
    );
    
    $seq = $_POST['seq'];

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$Conn->StartTrans();
		$out_put = $Conn->UpdateDB($nFree->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
		if(!$out_put){
			$Conn->RollbackTrans();
			$Conn->disConnect();
		}else{
			$Conn->CommitTrans();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('sponsorzone', array('cate' => 0,
																									'dep01' => 1,
																									'dep02' => 1,
																									'type' => 'view',
																									'seq' => $seq
																									));
  }
  
  private function writeCheer() {
    $nClovercomment = new \ClovercommentClass(); //클로버코멘트

		$nClovercomment->writer    = Auth::user()->user_name . ',' . Auth::user()->user_id; // 작성자ID
		$nClovercomment->subject    = $_POST['subject']; // 제목
		$nClovercomment->clover_seq    = $_POST['clover_seq']; // 클로버아이디
	    
		$arr_field = array
	    (
	        'group_name', 'writer', 'subject', 'clover_seq'
	    );

		$arr_value = array( Auth::user()->group_name, $nClovercomment->writer, $nClovercomment->subject, $nClovercomment->clover_seq);

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$Conn->StartTrans();

		$out_put = $Conn->InsertDB($nClovercomment->table_name, $arr_field, $arr_value);

		if($out_put){
			$Conn->CommitTrans();
		}else{

			$Conn->RollbackTrans();
			$Conn->disConnect();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		//http://52.79.83.28/clovergarden?cate=1&dep01=0&dep02=0&type=view&seq=16#tabs-4
		$seq = isset($_POST['seq']) ? $_POST['seq'] : 0;
		return redirect()->to(route('clovergarden').'?cate=1&dep01=0&dep02=0&type=view&seq='.$seq.'#tabs-4');
		//return redirect()->route('clovergarden', array('cate' => 1, 'dep01' => 0, 'dep02' => 0, 'type' => 'view', 'seq' => $seq, '\#tab-4' => ''));
  }
  
  private function modifyPersonal() {
    $nMember = new \MemberClass(); //회원

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nMember->where = "where user_id ='" . Auth::user()->user_id . "'";

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);
		if(count($nMember->read_result) != 0){
			$nMember->VarList($nMember->read_result, 0, null);
		}else{
			$Conn->DisConnect();
		}

		$Conn->DisConnect();
		//======================== DB Module End ===============================

		$nMember->user_name        = $_POST['user_name'];
		$nMember->group_name        = isset($_POST['group_name1']) ? $_POST['group_name1'] : '';

		$file_name = explode('@',$nMember->user_id);

		if($_POST['user_pw'] != null){
			$nMember->user_pw = Hash::make(strtolower($_POST['user_pw']));
		} else {
			$nMember->user_pw =  $nMember->user_pw;
		}

		$nMember->user_birth     = $_POST['user_birth'];
		$nMember->user_gender     = $_POST['user_gender'];    
    $nMember->user_cell = $_POST['user_cell'];
    $nMember->post1 = $_POST['post1'];
    $nMember->post2 = $_POST['post2'];
    $nMember->addr1 = $_POST['addr1'];
    $nMember->addr2 = $_POST['addr2'];
    $nMember->group_state = isset($_POST['group_state']) ? $_POST['group_state'] : ''; // NOT NULL임

		if($nMember->group_state == 3){
			$nMember->group_name = '';
			$user_state = 2;
		} else {
			$nMember->group_name = $nMember->group_name;
			$user_state = 5;
		}

    $nMember->file_real[1] = $_POST['file_real1'];
    $nMember->file_edit[1] = $_POST['file_edit1'];
    $nMember->file_byte[1] = $_POST['file_byte1'];
	
    $nMember->file_pre_name[1] = $nMember->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

    /* 파일 업로드 손 봐야 함 */
    for($cnt_file=1; $cnt_file <= $nMember->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/member/', '', $nMember->file_volume[$cnt_file], $nMember->file_mime_type[$cnt_file],$file_name[0]);
            $nMember->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nMember->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nMember->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nMember->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nMember->file_real[$cnt_file] = '';
                $nMember->file_edit[$cnt_file] = '';
                $nMember->file_byte[$cnt_file] = '';
            }else{
                $nMember->file_pre_name[$cnt_file] = '';
            }
        }
    }	

    $arr_field = array
    (
        'user_name', 'password','user_birth', 'user_gender', 'user_cell', 'file_real1', 'file_edit1', 'file_byte1', 'post1', 'post2', 'addr1', 'addr2'
    );

    $arr_value = array
    (
        $nMember->user_name, $nMember->user_pw, $nMember->user_birth, $nMember->user_gender, $nMember->user_cell, $nMember->file_real[1], $nMember->file_edit[1], $nMember->file_byte[1], $nMember->post1, $nMember->post2, $nMember->addr1, $nMember->addr2
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nMember->table_name, $arr_field, $arr_value, "where user_id = '" . Auth::user()->user_id . "'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    } else {
		for($cnt_file=1; $cnt_file <= $nMember->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nMember->file_pre_name[$cnt_file] != ''){
                if(FileExists('/imgs/up_file/member/'.$nMember->file_pre_name[$cnt_file])) unlink('/imgs/up_file/member/'.$nMember->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		
		return redirect()->route('mypage', array('cate' => 6, 'dep01' => 5, 'dep02' => 0));
  }
  
  private function userSignUp() {
    $nMember = new \MemberClass(); //회원

		$nMember->user_state = $_POST['user_state'];
		if($nMember->user_state==1){
			//
		}
		
		$nMember->user_name        = $_POST['user_name'];

		if($nMember->user_state==2){
			$nMember->group_name        = $_POST['group_name1'];
		}else if($nMember->user_state==3){
			$nMember->group_name        = $_POST['group_name2'];
		}else if($nMember->user_state==4){
			$nMember->group_name        = $_POST['group_name3'];
		}
	
    $nMember->user_id    = RequestAll(strtolower($_POST['user_id']));
    $nMember->user_pw    = Hash::make(strtolower($_POST['user_pw']));
		$nMember->user_birth     = $_POST['user_birth'];
		$nMember->user_gender     = $_POST['user_gender'];    
    $nMember->user_cell = $_POST['user_cell'];
    $nMember->group_state = $_POST['group_state'];
    $nMember->member_t = $_POST['member_t'];
    $nMember->post1 = $_POST['post1'];
    $nMember->post2 = $_POST['post2'];
    $nMember->addr1 = $_POST['addr1'];
    $nMember->addr2 = $_POST['addr2'];

    $arr_field = array
    (
        'user_state', 'group_state', 'user_name', 'group_name', 'user_id', 'password','user_birth', 'user_gender', 'user_cell', 'member_t', 'post1', 'post2', 'addr1', 'addr2'
    );

    $arr_value = array
    (
        $nMember->user_state, $nMember->group_state, $nMember->user_name, $nMember->group_name, $nMember->user_id, $nMember->user_pw, $nMember->user_birth, $nMember->user_gender, $nMember->user_cell, $nMember->member_t, $nMember->post1, $nMember->post2, $nMember->addr1, $nMember->addr2
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nMember->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		if($nMember->user_state == 4){
			// 승인 전 기업회원. 관리자의 문의 후 리다이렉트
		} else {
			// 자동 로그인
			$id = DB::table('new_tb_member')->where('user_id', $nMember->user_id)->value('id');
			Auth::loginUsingId($id);
			
			return redirect()->route('home');
		}
  }
}
