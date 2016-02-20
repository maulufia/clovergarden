<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect, URL;

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
  
  public function showCalendar() {
  	return view('front.page.sponsorzone.calendar');
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

  	return view('front.page.clovergarden.home_writeresv_result', ['cate' => 0,
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
  | POST Method Controller
  |--------------------------------------------------------------------------
  |
  | Because of Spaghetti code, creating post method controller
  |
  */
  
  public function postMethodControl() {
	  $sub_cate = isset($_GET['cate']) ? $_GET['cate'] : 0;
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		
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
		$support_reserve = array( // 좋은 디자인은 아니다
											'front.page.clovergarden.home_writeresv'
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
		
		foreach ($support_reserve as $fe) {
			if($fe == $view_name) {
				return $this->execReserveSupport();
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
			$sql = "
			insert into new_tb_point set
				signdate = '".mktime()."',
				depth = '".$nClovermlist->clover_name." 정기 후원',
				outpoint = '".$nClovermlist->price."',
				userid = '".$login_id."'
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
  | executing CRUD
  |
  */
  
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
}
