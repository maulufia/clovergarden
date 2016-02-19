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
		$option->list_link = route('sponsorzone') . "?cate=" . $sub_cate . "&dep01=" . $dep01 . "&dep02=" . $dep02;
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
		
	}
	
	// 이용안내
	public function showInformation() {
		
	}

  // 고객 센터	
	public function showCustomer() {
		
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
	  
  /*
  |--------------------------------------------------------------------------
  | POST Method Controller
  |--------------------------------------------------------------------------
  |
  | Because of Spaghetti code, creating post method controller
  |
  */
  
  public function postMethodControl() {
	  $sub_cate = 0;
		$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
		$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
		
		// Other Options for board
		$option = new \StdClass();
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		$option->seq = isset($_GET['seq']) ? $_GET['seq'] : null;
		
		$view_name = \CateHelper::viewnameHelper($sub_cate, $dep01, $dep02, $option);
		
		$search_exec = array(
										'front.page.sponsorzone.community.board_sponsor'
									);
		$board_write = array( // 좋은 디자인은 아니다
									'front.page.sponsorzone.community.board_sponsor_write',
									'front.page.sponsorzone.community.board_company_write'
									);
		$board_edit = array( // 좋은 디자인은 아니다
									'front.page.sponsorzone.community.board_sponsor_edit',
									'front.page.sponsorzone.community.board_company_edit'
									);
		
		foreach ($search_exec as $fe) {
			if($fe == $view_name) {
				return $this->showSponsorZone();
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
}
