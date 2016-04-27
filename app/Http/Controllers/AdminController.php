<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect, Hash;
use DB;
use Flash;

class AdminController extends Controller
{
	
	function __construct() {
		include(app_path().'/Custom/_common/_global.php');
	}
	
	/*
  |--------------------------------------------------------------------------
  | Admin Site Route Methods
  |--------------------------------------------------------------------------
  |
  | These methods below are for admin pages.
  |
  */
	
	public function showLogin() {
		return view('admin.login');
	}
	
	public function showAdmin() {
		return $this->showMember();
	}
	
	public function showMember() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'list_admin';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/member', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->excel_link = $option->list_link.'&type=excel'; // 안 좋은 디자인
		$option->excel_link2 = $option->list_link.'&type=excel2'; // 안 좋은 디자인
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;

		$view_name = 'admin.page.member.' . $item;
		if(!is_null($option->type))
			$view_name .=  '_' . $option->type;

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link,
															'excel_link' => $option->excel_link,
															'excel_link2' => $option->excel_link2
														]);
	}
	
	public function showClover() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'list_clover';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/clover', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->excel_link = $option->list_link.'&type=excel'; // 안 좋은 디자인
		$option->excel_link2 = $option->list_link.'&type=excel2'; // 안 좋은 디자인
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.clover.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link,
															'excel_link' => $option->excel_link,
															'excel_link2' => $option->excel_link2
														]);
	}
	
	public function showService() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'home';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/service', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.service.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link
														]);
	}
	
	public function showCommunity() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'timeline';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/community', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.community.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link
														]);
	}
	
	public function showSponsor() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'companion';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/sponsor', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.sponsor.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link
														]);
	}
	
	public function showCustomer() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'news';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/customer', array('item' => $item));
		$option->write_link = $option->list_link.'&type=write';
		$option->view_link = $option->list_link.'&type=view';
		$option->edit_link = $option->list_link.'&type=edit';
		$option->delete_link = $option->list_link.'&type=del';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.customer.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'write_link' => $option->write_link,
															'view_link' => $option->view_link,
															'edit_link' => $option->edit_link,
															'delete_link' => $option->delete_link
														]);
	}
	
	public function showStat() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'stat_day';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/stat', array('item' => $item));
		$option->view_link = $option->list_link.'&type=view';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.stat.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'view_link' => $option->view_link
														]);
	}
	
	public function showPage() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'intro';
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/page', array('item' => $item));
		$option->view_link = $option->list_link.'&type=view';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.page.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'view_link' => $option->view_link
														]);
	}
	
	public function showSetting() {
		$item = isset($_GET['item']) ? $_GET['item'] : 'popup';
		
		// 팝업 (현재는 메인에만 적용됨)
		$configModel = new \ConfigModel;
		$popup_status = $configModel->getConfig('popup')->content;
		$popup_link = $configModel->getConfig('popup_link')->content;
		
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/setting', array('item' => $item));
		$option->view_link = $option->list_link.'&type=view';
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		$view_name = 'admin.page.setting.' . $item;
		if(!is_null($option->type)) {
			$view_name .=  '_' . $option->type;
		}

		return view($view_name, ['list_link' => $option->list_link,
															'view_link' => $option->view_link,
															'popup_status' => $popup_status,
															'popup_link' => $popup_link
														]);
	}
	
	public function writeQna() {
    $nOnetoone = new \OnetooneClass(); //1:1문의

		$nOnetoone->writer    = isset($_POST['writer']) ? $_POST['writer'] : null; // 작성자ID
		$nOnetoone->name    = $_POST['name']; // 이름
		$nOnetoone->email    = $_POST['email1']."@".$_POST['email2']; // 이메일
		$nOnetoone->cell    = $_POST['cell1']."-".$_POST['cell2']."-".$_POST['cell3']; // 연락처
		$nOnetoone->receive = $_POST['receive'];
		$nOnetoone->subject    = $_POST['subject']; // 제목
		$nOnetoone->content    = RepEditor($_POST['content']); // 내용

    $arr_field = array
    (
        'writer', 'name', 'email', 'cell', 'receive', 'subject', 'content'
    );

		$arr_value = array($nOnetoone->writer, $nOnetoone->name, $nOnetoone->email, $nOnetoone->cell, $nOnetoone->receive, $nOnetoone->subject, $nOnetoone->content);

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$Conn->StartTrans();

		$out_put = $Conn->InsertDB($nOnetoone->table_name, $arr_field, $arr_value);

		if($out_put){
			$Conn->CommitTrans();
		}else{

			$Conn->RollbackTrans();
			$Conn->disConnect();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		// Send Email to Master
		$subject = "[알림] 1:1문의가 접수되었습니다.";
		$content = "<html>
									<head>
									</head>
									<body>
										<p><b>발신자: </b>{$nOnetoone->name} {$nOnetoone->email}</p><p>
										<p><b>내용: </b>{$nOnetoone->content}</p>
									</body>
									</html>";
		$mail = \MailHelper::sendMail(EMAIL_MASTER, $subject, $content);

		Flash::success(SUCCESS_WRITE);
		return redirect()->route('customer');
	}
	
	/*
  |--------------------------------------------------------------------------
  | POST Method Controller
  |--------------------------------------------------------------------------
  |
  | Because of Spaghetti code, creating post method controller
  |
  */
  
  	
	public function postMember() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
			return $this->showMember();

		if($option->type == 'write')
			return $this->addMemberAdmin();
		
		if($option->type == 'edit') {
			if($option->item == 'list_admin') {
				return $this->editMemberAdmin();
			}
			
			if($option->item == 'list_normal') {
				if(isset($_POST['l_id']))
					return $this->editMemberNormal();
					
				return $this->showMember();
			}
		}
		
		if($option->type == 'del')
			return $this->delMemberAdmin();
		
		if($option->type == 'excel')
			return $this->excelMemberUpload();
		
		return 'error';
	}
	
	public function postClover() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view' || $option->type == 'detail')
			return $this->showClover();
		
		if($option->item == 'list_clover') {
			if($option->type == 'edit')
				return $this->editClover();
			if($option->type == 'write')
				return $this->writeClover();
			if($option->type == 'del')
				return $this->delClover();
		}
	
		if($option->item == 'news') {
			if($option->type == 'edit')
				return $this->editCloverNews();
			if($option->type == 'write')
				return $this->writeCloverNews();
			if($option->type == 'del')
				return $this->delCloverNews();
		}
		
		if($option->item == 'banner') {
			if($option->type == 'edit')
				return $this->editCloverBanner();
			if($option->type == 'write')
				return $this->writeCloverBanner();
			if($option->type == 'del')
				return $this->delCloverBanner();
		}
		
		return 'error';
	}
	
	public function postService() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
			return $this->showService();
		
		if($option->item == 'home') {
			if($option->type == 'edit')
				return $this->editService();
			if($option->type == 'edit_status')
				return $this->editServiceStatus();
			if($option->type == 'write')
				return $this->writeService();
			if($option->type == 'del')
				return $this->delService();
		}
		
		return 'error';
	}
	
	public function postCommunity() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
			return $this->showCommunity();
		
		if($option->item == 'timeline') {
			if($option->type == 'write')
				return $this->writeTimeline();
			if($option->type == 'del')
				return $this->delTimeline();
		}
		
		if($option->item == 'board_sponsor') {
			if($option->type == 'write')
				return $this->writeSponsorPost();
			if($option->type == 'edit')
				return $this->editSponsorPost();
			if($option->type == 'del')
				return $this->delSponsorPost();
		}
		
		if($option->item == 'banner') {
			if($option->type == 'write')
				return $this->writeCommunityBanner();
			if($option->type == 'edit')
				return $this->editCommunityBanner();
			if($option->type == 'del')
				return $this->delCommunityBanner();
		}
		
		return 'error';
	}
	
	public function postSponsor() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
			return $this->showSponsor();

		if($option->item == 'companion') {
			if($option->type == 'write')
				return $this->writeCompanion();
			if($option->type == 'edit')
				return $this->editCompanion();
			if($option->type == 'del')
				return $this->delCompanion();
		}
		
		if($option->item == 'deans') {
			if($option->type == 'write')
				return $this->writeDeans();
			if($option->type == 'edit')
				return $this->editDeans();
			if($option->type == 'del')
				return $this->delDeans();
		}
		
		if($option->item == 'main_companion') {
			if($option->type == 'write')
				return $this->writeMainCompanion();
			if($option->type == 'del')
				return $this->delMainCompanion();
		}
		
		if($option->item == 'main_deans') {
			if($option->type == 'write')
				return $this->writeMainDeans();
			if($option->type == 'edit')
				return $this->editMainDeans();
			if($option->type == 'del')
				return $this->delMainDeans();
		}

		return 'error';
	}
  
  public function postCustomer() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
			return $this->showCustomer();

		if($option->item == 'news') {
			if($option->type == 'write')
				return $this->writeNews();
			if($option->type == 'edit')
				return $this->editNews();
			if($option->type == 'del')
				return $this->delNews();
		}
		
		if($option->item == 'qna') {
			if($option->type == 'del')
				return $this->delQna();
		}
		
		if($option->item == 'faq') {
			if($option->type == 'write')
				return $this->writeFaq();
			if($option->type == 'edit')
				return $this->editFaq();
			if($option->type == 'del')
				return $this->delFaq();
		}

		return 'error';
	}
	
	/*
  |--------------------------------------------------------------------------
  | Database Controller
  |--------------------------------------------------------------------------
  |
  | executing CRUD
  |
  */
  
  public function addMemberAdmin() {
    $nMember = new \MemberClass(); //회원
	
		$nMember->user_state    = "1";
    $nMember->user_id    = $_POST['user_id'];
    $nMember->password    = Hash::make(strtolower($_POST['user_pw']));
		$nMember->user_name  = $_POST['user_name'];

    $arr_field = array
    (
       'user_state', 'user_id', 'password', 'user_name'
    );

    $arr_value = array
    (
        $nMember->user_state, $nMember->user_id, $nMember->password, $nMember->user_name
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
		
		return redirect()->route('admin/member', array('item' => 'list_admin'));
  }
  
  public function editMemberAdmin() {
    $seq        = $_POST['seq'];

    $nMember = new \MemberClass(); //리얼스토리

    $nMember->user_pw    = Hash::make(strtolower($_POST['user_pw']));
    $nMember->user_name  = $_POST['user_name'];

    $arr_field = array
    (
       'password', 'user_name'
    );

    $arr_value = array
    (
       $nMember->user_pw, $nMember->user_name
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nMember->table_name, $arr_field, $arr_value, "where id = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    } else {
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/member', array('item' => 'list_admin'));
  }
  
  public function delMemberAdmin() { // 문제 있음
    $nClover   = new \CloverClass(); 
    
    DB::table('new_tb_member')->where('id', '=', $_POST['seq'])->delete();

		return redirect()->route('admin/member', array('item' => 'list_admin'));
  }
  
  public function editMemberNormal() {
    $nMember = new \MemberClass(); //회원

		$user_id = $_POST['l_id'];
		//======================== DB Module Start ============================
		$Conn = new \DBClass();

		$nMember->where = "where user_id ='" . $user_id . "'";

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);
		if(count($nMember->read_result) != 0){
			$nMember->VarList($nMember->read_result, 0, null);
		}else{
			$Conn->DisConnect();
		}

		$Conn->DisConnect();
		//======================== DB Module End ===============================

		$nMember->user_name = $_POST['user_name'];

		$file_name = explode('@',$nMember->user_id);
		if($_POST['user_pw'] == ''){
			$nMember->user_birth = $_POST['user_birth'];
			$arr_field = array
			(
				'user_name','user_birth'
			);

			$arr_value = array
			(
				$nMember->user_name, $nMember->user_birth
			);
		} else {
			if($_POST['user_pw'] != null){
				$nMember->user_pw =  Hash::make(strtolower($_POST['user_pw']));
			}else{
				$nMember->user_pw =  $nMember->user_pw;
			}
			$nMember->user_birth = $_POST['user_birth'];
			$arr_field = array
			(
				'user_name', 'password','user_birth'
			);

			$arr_value = array
			(
				$nMember->user_name, $nMember->user_pw, $nMember->user_birth
			);
		}


		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nMember->table_name, $arr_field, $arr_value, "where user_id = '" . $user_id . "'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    } else {
			for($cnt_file=1; $cnt_file <= $nMember->file_up_cnt; $cnt_file++) { // 이건 뭐지?
				/*
        if($check_del[$cnt_file] == 1 && $nMember->file_pre_name[$cnt_file] != ''){
          if(FileExists('../../up_file/member/'.$nMember->file_pre_name[$cnt_file]))
          	unlink('../../up_file/member/'.$nMember->file_pre_name[$cnt_file]);
        } */
      }
      $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/member', array('item' => 'list_normal', 'type' => 'edit', 'seq' => $_POST['seq']));
  }
  
  private function excelMemberUpload() {
		require_once app_path() . "/Libraries/Excel/reader.php";

		$data = new \Spreadsheet_Excel_Reader();

		$data->setOutputEncoding('utf-8');

		$data->read($_FILES['excel']['tmp_name']);

		error_reporting(E_ALL ^ E_NOTICE);

		$tmp = explode(".", $_FILES['excel']['name']);// echo "tmp : "; print_r($tmp);
		$Extension = $tmp[count($tmp) - 1];
	  
		srand((double)microtime() * 1000000000);
		$Rnd = rand(1000000000, 9999999999);
		$Temp = date("YmdHis");
		$newName = $Temp . $Rnd . "." . $Extension; // .".".
	  
		$nClovermlist   = new \ClovermlistClass(); //후원기관
		$nMember = new \MemberClass(); //회원

		$edit_cnt = 0;
		$arr_value = array();
		$arr_field = array
		(
			'clover_seq', 'name', 'group_name', 'id', 'price', 'type', 'day', 'address', 'bank', 'banknum', 'reg_date', 'start', 'bankdate', 'order_adm_ck'
		);
		$arr_field1 = array
		(
			'user_name', 'group_name', 'user_id', 'password','user_state','member_t'
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
		$Conn = new \DBClass();

		$Conn->InsertMultiDB2($nMember->table_name, $arr_field1, $arr_value1);

		$Conn->StartTrans();


		$out_put = $Conn->InsertMultiDB($nClovermlist->table_name, $arr_field, $arr_value);
		if($out_put){			
			$Conn->CommitTrans();
		}else{
			$Conn->RollbackTrans();
			$Conn->disConnect();
			JsAlert(ERR_DATABASE, 1, route('admin/member', array('item' => 'list_normal')));
		}


		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/member', array('item' => 'list_normal'));
  }
  
  private function editClover() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nClover = new \CloverClass(); //후원기관
  
    $nClover->subject = $_POST['subject'];
		$nClover->content        = RepEditor($_POST['content']);
		$nClover->code        = RepEditor($_POST['code']);
		$nClover->category        = RepEditor($_POST['category']);
		$nClover->hot        = RepEditor($_POST['hot']);

    $nClover->file_real[1] = $_POST['file_real1'];
    $nClover->file_edit[1] = $_POST['file_edit1'];
    $nClover->file_byte[1] = $_POST['file_byte1'];
		$nClover->file_real[2] = $_POST['file_real2'];
    $nClover->file_edit[2] = $_POST['file_edit2'];
    $nClover->file_byte[2] = $_POST['file_byte2'];
	
    $nClover->file_pre_name[1] = $nClover->file_edit[1];
		$nClover->file_pre_name[2] = $nClover->file_edit[2];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;
		$check_del[2] = isset($_POST['check_del2']) ? $_POST['check_del2'] : null;

    for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClover->code.'_'.$cnt_file.'_', $nClover->file_volume[$cnt_file], $nClover->file_mime_type[$cnt_file]);
            $nClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nClover->file_real[$cnt_file] = '';
                $nClover->file_edit[$cnt_file] = '';
                $nClover->file_byte[$cnt_file] = '';
            }else{
                $nClover->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'code', 'category', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'hot'
    );

    $arr_value = array
    (
        $nClover->subject, $nClover->content, $nClover->code, $nClover->category, $nClover->file_real[1], $nClover->file_edit[1], $nClover->file_byte[1], $nClover->file_real[2], $nClover->file_edit[2], $nClover->file_byte[2], $nClover->hot
    );

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nClover->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }else{
        for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nClover->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/clover/'.$nClover->file_pre_name[$cnt_file])) unlink('../../up_file/clover/'.$nClover->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/clover', array('item' => 'list_clover', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
  }
	
	private function writeClover() {
    $nClover = new \CloverClass(); //후원기관
		$nMember = new \MemberClass(); //회원

    $nClover->subject = $_POST['subject'];
		$nClover->content        = RepEditor($_POST['content']);
		$nClover->code        = RepEditor($_POST['code']);
		$nClover->category        = RepEditor($_POST['category']);
		$nClover->view_n        = RepEditor($_POST['view_n']);
		$nClover->hot        = RepEditor($_POST['hot']);
    $nClover->file_real[1] = isset($_POST['file_real1']) ? $_POST['file_real1'] : null;
    $nClover->file_edit[1] = isset($_POST['file_edit1']) ? $_POST['file_edit1'] : null;
    $nClover->file_byte[1] = isset($_POST['file_byte1']) ? $_POST['file_byte1'] : null;
		$nClover->file_real[2] = isset($_POST['file_real2']) ? $_POST['file_real2'] : null;
    $nClover->file_edit[2] = isset($_POST['file_edit2']) ? $_POST['file_edit2'] : null;
    $nClover->file_byte[2] = isset($_POST['file_byte2']) ? $_POST['file_byte2'] : null;

    for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClover->code.'_'.$cnt_file.'_', $nClover->file_volume[$cnt_file], $nClover->file_mime_type[$cnt_file]);
            $nClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'code', 'category', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'view_n', 'hot'
    );

		$arr_field1 = array
		(
			'user_name', 'group_name', 'user_id', 'user_pw','user_state'
		);

    $arr_value = array
    (
        $nClover->subject, $nClover->content, $nClover->code,  $nClover->category,  $nClover->file_real[1], $nClover->file_edit[1], $nClover->file_byte[1], $nClover->file_real[2], $nClover->file_edit[2], $nClover->file_byte[2], $nClover->view_n, $nClover->hot
    );

		$arr_value1 = array(
			$nClover->subject, '', $nClover->code,  "81dc9bdb52d04dc20036dbd8313ed055", "6"
		);

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

		$Conn->insertDB($nMember->table_name, $arr_field1, $arr_value1);

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nClover->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/clover', array('item' => 'list_clover'));
	}
	
	private function delClover() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];
    $nClover   = new \CloverClass(); 

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nClover->table_name, "where seq in (".$seq[0].")");
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/clover', array('item' => 'list_clover'));
	}
	
	private function editCloverNews() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nClovernews = new \ClovernewsClass(); //후원기관
  
    $nClovernews->subject = $_POST['subject'];
		$nClovernews->clover_seq  = $_POST['clover_seq'];
    $nClovernews->file_real[1] = isset($_POST['file_real1']) ? $_POST['file_real1'] : null;
    $nClovernews->file_edit[1] = isset($_POST['file_edit1']) ? $_POST['file_edit1'] : null;
    $nClovernews->file_byte[1] = isset($_POST['file_byte1']) ? $_POST['file_byte1'] : null;
		$nClovernews->file_real[2] = isset($_POST['file_real2']) ? $_POST['file_real2'] : null;
    $nClovernews->file_edit[2] = isset($_POST['file_edit2']) ? $_POST['file_edit2'] : null;
    $nClovernews->file_byte[2] = isset($_POST['file_byte2']) ? $_POST['file_byte2'] : null;

    $nClovernews->file_pre_name[1] = $nClovernews->file_edit[1];
		$nClovernews->file_pre_name[2] = $nClovernews->file_edit[2];


		for($i = 0; $i < 10; $i++) {
			$multifile_real[$i] = isset($_POST['multifile_real[$i]']) ? $_POST['multifile_real[$i]'] : null;
		}
		
		for($i = 0; $i < 10; $i++) {
			$multifile_edit[$i] = isset($_POST['multifile_edit[$i]']) ? $_POST['multifile_edit[$i]'] : null;
		}
		
		for($i = 0; $i < 10; $i++) {
			$multifile_byte[$i] = isset($_POST['multifile_byte[$i]']) ? $_POST['multifile_byte[$i]'] : null;
		}

		$multifile_pre_name[0] = $multifile_edit[0];
		$multifile_pre_name[1] = $multifile_edit[1];
		$multifile_pre_name[2] = $multifile_edit[2];
		$multifile_pre_name[3] = $multifile_edit[3];
		$multifile_pre_name[4] = $multifile_edit[4];
		$multifile_pre_name[5] = $multifile_edit[5];
		$multifile_pre_name[6] = $multifile_edit[6];
		$multifile_pre_name[7] = $multifile_edit[7];
		$multifile_pre_name[8] = $multifile_edit[8];
		$multifile_pre_name[9] = $multifile_edit[9];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;
		$check_del[2] = isset($_POST['check_del2']) ? $_POST['check_del2'] : null;

    for($cnt_file=1; $cnt_file <= $nClovernews->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClovernews->code.'_'.$cnt_file.'_', $nClovernews->file_volume[$cnt_file], $nClovernews->file_mime_type[$cnt_file]);
            $nClovernews->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClovernews->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClovernews->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClovernews->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nClovernews->file_real[$cnt_file] = '';
                $nClovernews->file_edit[$cnt_file] = '';
                $nClovernews->file_byte[$cnt_file] = '';
            }else{
                $nClovernews->file_pre_name[$cnt_file] = '';
            }
        }
    }
    
    for($i = 0; $i < 10; $i++) {
			$check_multi_del[$i] = isset($_POST['check_multi_del$i']) ? $_POST['check_multi_del$i'] : null;
		}

		$countarray = count($_FILES['multifile']['name']);
		for($i=0; $i < $countarray; $i++) {

	        if($_FILES['multifile']['name'][$i]){
				
	            $arr_mfile[$i] = FileMultiUpload($_FILES['multifile']['name'][$i],$_FILES['multifile']['size'][$i], $_FILES['multifile']['tmp_name'][$i],  '/home/clovergarden/cg_app/public/imgs/up_file/clovernews/', $i.'_', 10, 'image');            
				$multifile_real[$i] = $arr_mfile[$i][0];
	            $multifile_edit[$i] = $arr_mfile[$i][1];
	            $multifile_byte[$i] = $arr_mfile[$i][2];
	        }else{
	            if($check_multi_del[$i] == '1'){
	                $multifile_real[$i] = '';
	                $multifile_edit[$i] = '';
	                $multifile_byte[$i] = '';
	            }else{
					$multifile_pre_name[$i] = '';
				}
	        }
		}
		$nClovernews->multifile_real = join(',',$multifile_real);
		$nClovernews->multifile_edit = join(',',$multifile_edit);
		$nClovernews->multifile_byte = join(',',$multifile_byte);


    $arr_field = array
    (
        'subject', 'clover_seq', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'multifile_real', 'multifile_edit', 'multifile_byte', 'url'
    );

    $arr_value = array
    (
		$nClovernews->subject, $nClovernews->clover_seq, $nClovernews->file_real[1], $nClovernews->file_edit[1], $nClovernews->file_byte[1], $nClovernews->file_real[2], $nClovernews->file_edit[2], $nClovernews->file_byte[2], $nClovernews->multifile_real, $nClovernews->multifile_edit, $nClovernews->multifile_byte, $nClovernews->url
    );

		//======================== DB Module Clovernewst ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nClovernews->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }else{
        for($cnt_file=1; $cnt_file <= $nClovernews->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nClovernews->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/sponsor/'.$nClovernews->file_pre_name[$cnt_file])) unlink('../../up_file/sponsor/'.$nClovernews->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/clover', array('item' => 'news'));
	}
	
	private function writeCloverNews() {
    $nClovernews = new \ClovernewsClass(); //후원기관

    $nClovernews->subject = $_POST['subject'];
		$nClovernews->clover_seq = $_POST['clover_seq'];
    $nClovernews->file_real[1] = isset($_POST['file_real1']) ? $_POST['file_real1'] : null;
    $nClovernews->file_edit[1] = isset($_POST['file_edit1']) ? $_POST['file_edit1'] : null;
    $nClovernews->file_byte[1] = isset($_POST['file_byte1']) ? $_POST['file_byte1'] : null;
		$nClovernews->file_real[2] = isset($_POST['file_real2']) ? $_POST['file_real2'] : null;
    $nClovernews->file_edit[2] = isset($_POST['file_edit2']) ? $_POST['file_edit2'] : null;
    $nClovernews->file_byte[2] = isset($_POST['file_byte2']) ? $_POST['file_byte2'] : null;
    $nClovernews->category = $_POST['category'];
		$nClovernews->url = isset($_POST['url']) ? $_POST['url'] : null;

    for($cnt_file=1; $cnt_file <= $nClovernews->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClovernews->clover_seq.'_'.$cnt_file.'_', $nClovernews->file_volume[$cnt_file], $nClovernews->file_mime_type[$cnt_file]);
            $nClovernews->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClovernews->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClovernews->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClovernews->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

		$countarray = count($_FILES['multifile']['name']);

		for($i=0; $i < $countarray; $i++) {

	        if($_FILES['multifile']['name'][$i]){
				
	            $arr_mfile[$i] = FileMultiUpload($_FILES['multifile']['name'][$i],$_FILES['multifile']['size'][$i], $_FILES['multifile']['tmp_name'][$i],  '/home/clovergarden/cg_app/public/imgs/up_file/clovernews/', $i.'_', 10, 'image');            
				$multifile_real[$i] = $arr_mfile[$i][0];
	            $multifile_edit[$i] = $arr_mfile[$i][1];
	            $multifile_byte[$i] = $arr_mfile[$i][2];
	        }
		}
		@$nClovernews->multifile_real = join(',',$multifile_real);
		@$nClovernews->multifile_edit = join(',',$multifile_edit);
		@$nClovernews->multifile_byte = join(',',$multifile_byte);

    $arr_field = array
    (
        'subject', 'clover_seq', 'category', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2', 'multifile_real', 'multifile_edit', 'multifile_byte', 'url'
    );

    $arr_value = array
    (
        $nClovernews->subject, $nClovernews->clover_seq, $nClovernews->category, $nClovernews->file_real[1], $nClovernews->file_edit[1], $nClovernews->file_byte[1], $nClovernews->file_real[2], $nClovernews->file_edit[2], $nClovernews->file_byte[2], $nClovernews->multifile_real, $nClovernews->multifile_edit, $nClovernews->multifile_byte, $nClovernews->url
    );

		//======================== DB Module Clovernewst ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nClovernews->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/clover', array('item' => 'news'));
	}
	
	private function delCloverNews() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nClovernews   = new \ClovernewsClass(); 

		//======================== DB Module Clovernewst ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nClovernews->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/clover', array('item' => 'news'));
	}
	
	private function writeCloverBanner() {
    $nClover = new \CloverClass(); //후원기관

    $nClover->subject = $_POST['subject'];
		$nClover->group_name = RepEditor($_POST['group_name']);
		$nClover->news        = RepEditor($_POST['news']);
    $nClover->file_real[1] = isset($_POST['file_real1']) ? $_POST['file_real1'] : null;
    $nClover->file_edit[1] = isset($_POST['file_edit1']) ? $_POST['file_edit1'] : null;
    $nClover->file_byte[1] = isset($_POST['file_byte1']) ? $_POST['file_byte1'] : null;

    for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if(!empty($_FILES[$parsing_file]['name'])){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClover->code.'_'.$cnt_file.'_', $nClover->file_volume[$cnt_file], $nClover->file_mime_type[$cnt_file]);
            $nClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'group_name', 'news', 'file_real1', 'file_edit1', 'file_byte1'
    );

    $arr_value = array
    (
        $nClover->subject, $nClover->group_name, $nClover->news, $nClover->file_real[1], $nClover->file_edit[1], $nClover->file_byte[1]
    );

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
		$nClover->table_name = $nClover->table_name."_banner";
    $out_put = $Conn->insertDB($nClover->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
    return redirect()->route('admin/clover', array('item' => 'banner'));
	}
	
	private function editCloverBanner() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nClover = new \CloverClass(); //후원기관
  
    $nClover->subject = $_POST['subject'];
		$nClover->group_name        = RepEditor($_POST['group_name']);
		$nClover->news        = RepEditor($_POST['news']);
                                                                     
    $nClover->file_real[1] = $_POST['file_real1'];
    $nClover->file_edit[1] = $_POST['file_edit1'];
    $nClover->file_byte[1] = $_POST['file_byte1'];
	
    $nClover->file_pre_name[1] = $nClover->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;
    
    for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if(!empty($_FILES[$parsing_file]['name'])){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/clover/', $nClover->code.'_'.$cnt_file.'_', $nClover->file_volume[$cnt_file], $nClover->file_mime_type[$cnt_file]);
            dd($arr_file);
            $nClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if(isset($check_del[$cnt_file]) && $check_del[$cnt_file] == '1'){
                $nClover->file_real[$cnt_file] = '';
                $nClover->file_edit[$cnt_file] = '';
                $nClover->file_byte[$cnt_file] = '';
            }else{
                $nClover->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'group_name', 'news', 'file_real1', 'file_edit1', 'file_byte1'
    );

    $arr_value = array
    (
        $nClover->subject, $nClover->group_name, $nClover->news, $nClover->file_real[1], $nClover->file_edit[1], $nClover->file_byte[1]
    );

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
		$nClover->table_name = $nClover->table_name."_banner";
    $out_put = $Conn->UpdateDB($nClover->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }else{
        for($cnt_file=1; $cnt_file <= $nClover->file_up_cnt; $cnt_file++) {
            if(isset($check_del[$cnt_file]) && $check_del[$cnt_file] == 1 && $nClover->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/clover/'.$nClover->file_pre_name[$cnt_file])) unlink('../../up_file/clover/'.$nClover->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/clover', array('item' => 'banner'));
	}
	
	private function delCloverBanner() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nClover   = new \CloverClass(); 

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
		$nClover->table_name = $nClover->table_name."_banner";
    $out_put = $Conn->DeleteDB($nClover->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/clover', array('item' => 'banner'));
	}
	
	private function writeService() {
    $nSchedule = new \ScheduleClass(); //수술갤러리

    $nSchedule->subject = $_POST['subject'];
		$nSchedule->people       = $_POST['people'];
		$nSchedule->content        = $_POST['content'];
		$nSchedule->work_date        = $_POST['work_date'];
		$nSchedule->start_date        = $_POST['start_date'];
		$nSchedule->start_date2        = $_POST['start_date2'];
		$nSchedule->clover_seq        = $_POST['clover_seq'];
    $nSchedule->file_real[1] = isset($_POST['file_real1']) ? $_POST['file_real1'] : null;
    $nSchedule->file_edit[1] = isset($_POST['file_edit1']) ? $_POST['file_edit1'] : null;
    $nSchedule->file_byte[1] = isset($_POST['file_byte1']) ? $_POST['file_byte1'] : null;


    for($cnt_file=1; $cnt_file <= $nSchedule->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/schedule/', $nSchedule->clover_seq.'_'.$cnt_file.'_', $nSchedule->file_volume[$cnt_file], $nSchedule->file_mime_type[$cnt_file]);
            $nSchedule->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSchedule->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSchedule->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSchedule->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'people', 'content', 'file_real1', 'file_edit1', 'file_byte1','work_date','start_date','start_date2','clover_seq'
    );

    $arr_value = array
    (
        $nSchedule->subject, $nSchedule->people, $nSchedule->content, $nSchedule->file_real[1], $nSchedule->file_edit[1], $nSchedule->file_byte[1], $nSchedule->work_date, $nSchedule->start_date, $nSchedule->start_date2, $nSchedule->clover_seq
    );
    
		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSchedule->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/service', array('item' => 'home'));
	}
	
	private function editService() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nSchedule = new \ScheduleClass(); //
  
    $nSchedule->subject = $_POST['subject'];
		$nSchedule->people       = $_POST['people'];
		$nSchedule->start_date       = $_POST['start_date'];
		$nSchedule->start_date2       = $_POST['start_date2'];
		$nSchedule->clover_seq        = $_POST['clover_seq'];
		$nSchedule->content        = RepEditor($_POST['content']);
		$nSchedule->work_date        = RepEditor($_POST['work_date']);

    $nSchedule->file_real[1] = $_POST['file_real1'];
    $nSchedule->file_edit[1] = $_POST['file_edit1'];
    $nSchedule->file_byte[1] = $_POST['file_byte1'];
	
    $nSchedule->file_pre_name[1] = $nSchedule->file_edit[1];
    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

    for($cnt_file=1; $cnt_file <= $nSchedule->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/schedule/', $nSchedule->clover_seq.'_'.$cnt_file.'_', $nSchedule->file_volume[$cnt_file], $nSchedule->file_mime_type[$cnt_file]);
            $nSchedule->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSchedule->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSchedule->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSchedule->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nSchedule->file_real[$cnt_file] = '';
                $nSchedule->file_edit[$cnt_file] = '';
                $nSchedule->file_byte[$cnt_file] = '';
            }else{
                $nSchedule->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'people', 'content', 'file_real1', 'file_edit1', 'file_byte1','work_date','start_date','start_date2','clover_seq'
    );

    $arr_value = array
    (
        $nSchedule->subject, $nSchedule->people, $nSchedule->content, $nSchedule->file_real[1], $nSchedule->file_edit[1], $nSchedule->file_byte[1], $nSchedule->work_date, $nSchedule->start_date, $nSchedule->start_date2, $nSchedule->clover_seq
    );

    $arr_field = array
    (
        'subject', 'people', 'content', 'file_real1', 'file_edit1', 'file_byte1','work_date','start_date','start_date2','clover_seq'
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSchedule->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    } else {
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/service', array('item' => 'home', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function editServiceStatus() {
		$seq = $_POST['seq'];
    $row_no = $_POST['row_no'];
    
    $is_on = $_POST['is_on'];
    
    DB::table('new_tb_schedule')->where('seq', '=', $seq)->update(['is_on' => $is_on]);
    
		return redirect()->route('admin/service', array('item' => 'home', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delService() {
    $seq = $_POST['delete_seq'];

    $nSchedule   = new \ScheduleClass(); //수술갤러리
		$nSchedulepeo = new \SchedulepeoClass(); //

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSchedule->table_name, "where seq in (".$seq[0].")");
    $out_put2 = $Conn->DeleteDB($nSchedulepeo->table_name, "where schedule_seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/service', array('item' => 'home'));
	}
	
	private function writeTimeline() {
    $nTimeline = new \TimelineClass(); //타임라인

    $nTimeline->subject = $_POST['subject'];
		$nTimeline->writer_name = $_POST['writer_name'];

    $arr_field = array
    (
        'subject', 'writer_name'
    );

    $arr_value = array
    (
        $nTimeline->subject, $nTimeline->writer_name
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nTimeline->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    } else {
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/community', array('item' => 'timeline'));
	}
	
	private function delTimeline() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nTimeline   = new \ClovercommentClass(); //타임라인

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nTimeline->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/community', array('item' => 'timeline'));
	}
	
	private function editSponsorPost() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nFree = new \FreeClass(); //수술갤러리
  
    $nFree->subject = $_POST['subject'];
    $nFree->write_name       = $_POST['write_name'];
		$nFree->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'write_name', 'content'
    );

    $arr_value = array
    (
        $nFree->subject, $nFree->writer, $nFree->content
    );

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

		return redirect()->route('admin/community', array('item' => 'board_sponsor', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delSponsorPost() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

		$nFree   = new \FreeClass(); //수술갤러리

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nFree->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/community', array('item' => 'board_sponsor')));
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
    return redirect()->route('admin/community', array('item' => 'board_sponsor'));
	}
	
	private function writeCommunityBanner() {
    $nBanner = new \BannerClass(); //후원기관

    $nBanner->subject = $_POST['subject'];
		$nBanner->url = $_POST['url'];

    for($cnt_file=1; $cnt_file <= $nBanner->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/Banner/', '_'.$cnt_file.'_', $nBanner->file_volume[$cnt_file], $nBanner->file_mime_type[$cnt_file]);
            $nBanner->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nBanner->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nBanner->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nBanner->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nBanner->subject, $nBanner->file_real[1], $nBanner->file_edit[1], $nBanner->file_byte[1], $nBanner->url
    );

		//======================== DB Module Bannert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nBanner->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/community', array('item' => 'banner'));
	}
	
	private function editCommunityBanner() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nBanner = new \BannerClass(); //후원기관
  
    $nBanner->subject = $_POST['subject'];
		$nBanner->url        = $_POST['url'];
    $nBanner->file_real[1] = $_POST['file_real1'];
    $nBanner->file_edit[1] = $_POST['file_edit1'];
    $nBanner->file_byte[1] = $_POST['file_byte1'];
	
    $nBanner->file_pre_name[1] = $nBanner->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

    for($cnt_file=1; $cnt_file <= $nBanner->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/Banner/', '_'.$cnt_file.'_', $nBanner->file_volume[$cnt_file], $nBanner->file_mime_type[$cnt_file]);
            $nBanner->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nBanner->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nBanner->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nBanner->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nBanner->file_real[$cnt_file] = '';
                $nBanner->file_edit[$cnt_file] = '';
                $nBanner->file_byte[$cnt_file] = '';
            }else{
                $nBanner->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nBanner->subject, $nBanner->file_real[1], $nBanner->file_edit[1], $nBanner->file_byte[1], $nBanner->url
    );

		//======================== DB Module Bannert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nBanner->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        for($cnt_file=1; $cnt_file <= $nBanner->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nBanner->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/Banner/'.$nBanner->file_pre_name[$cnt_file])) unlink('../../up_file/Banner/'.$nBanner->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/community', array('item' => 'banner', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delCommunityBanner() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nBanner   = new \BannerClass(); 

		//======================== DB Module Bannert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nBanner->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    } else {
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/community', array('item' => 'banner'));
	}
	
	private function writeCompanion() {
    $nSponsor = new \SponsorClass(); //후원기관

    $nSponsor->subject = $_POST['subject'];
		$nSponsor->content        = RepEditor($_POST['content']);
		$nSponsor->url = $_POST['url'];

    for($cnt_file=1; $cnt_file <= $nSponsor->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsor/', '_'.$cnt_file.'_', $nSponsor->file_volume[$cnt_file], $nSponsor->file_mime_type[$cnt_file]);
            $nSponsor->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSponsor->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSponsor->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSponsor->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nSponsor->subject, $nSponsor->content, $nSponsor->file_real[1], $nSponsor->file_edit[1], $nSponsor->file_byte[1], $nSponsor->url
    );

		//======================== DB Module Sponsort ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSponsor->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/sponsor', array('item' => 'companion'));
	}
	
	private function editCompanion() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nSponsor = new \SponsorClass(); //후원기관
  
    $nSponsor->subject = $_POST['subject'];
		$nSponsor->content        = RepEditor($_POST['content']);
		$nSponsor->url        = $_POST['url'];
    $nSponsor->file_real[1] = $_POST['file_real1'];
    $nSponsor->file_edit[1] = $_POST['file_edit1'];
    $nSponsor->file_byte[1] = $_POST['file_byte1'];
	
    $nSponsor->file_pre_name[1] = $nSponsor->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

    for($cnt_file=1; $cnt_file <= $nSponsor->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsor/', '_'.$cnt_file.'_', $nSponsor->file_volume[$cnt_file], $nSponsor->file_mime_type[$cnt_file]);
            $nSponsor->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSponsor->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSponsor->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSponsor->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nSponsor->file_real[$cnt_file] = '';
                $nSponsor->file_edit[$cnt_file] = '';
                $nSponsor->file_byte[$cnt_file] = '';
            }else{
                $nSponsor->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'url'
    );

    $arr_value = array
    (
        $nSponsor->subject, $nSponsor->content, $nSponsor->file_real[1], $nSponsor->file_edit[1], $nSponsor->file_byte[1], $nSponsor->url
    );

		//======================== DB Module Sponsort ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSponsor->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }else{
        for($cnt_file=1; $cnt_file <= $nSponsor->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nSponsor->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/sponsor/'.$nSponsor->file_pre_name[$cnt_file])) unlink('../../up_file/sponsor/'.$nSponsor->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
		
		return redirect()->route('admin/sponsor', array('item' => 'companion', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delCompanion() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nSponsor   = new \SponsorClass(); 

		//======================== DB Module Sponsort ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSponsor->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/sponsor', array('item' => 'companion'));
	}
	
	private function writeDeans() {
    $nSponsorpeople = new \SponsorpeopleClass(); //후원기관

    $nSponsorpeople->subject = $_POST['subject'];
		$nSponsorpeople->content        = RepEditor($_POST['content']);

    for($cnt_file=1; $cnt_file <= $nSponsorpeople->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsorpeople/', '_'.$cnt_file.'_', $nSponsorpeople->file_volume[$cnt_file], $nSponsorpeople->file_mime_type[$cnt_file]);
            $nSponsorpeople->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSponsorpeople->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSponsorpeople->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSponsorpeople->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2'
    );

    $arr_value = array
    (
        $nSponsorpeople->subject, $nSponsorpeople->content, $nSponsorpeople->file_real[1], $nSponsorpeople->file_edit[1], $nSponsorpeople->file_byte[1], $nSponsorpeople->file_real[2], $nSponsorpeople->file_edit[2], $nSponsorpeople->file_byte[2]
    );

		//======================== DB Module Sponsorpeoplet ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSponsorpeople->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/sponsor', array('item' => 'deans'));
	}
	
	private function editDeans() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nSponsorpeople = new \SponsorpeopleClass(); //후원기관
  
    $nSponsorpeople->subject = $_POST['subject'];
		$nSponsorpeople->content        = RepEditor($_POST['content']);

    $nSponsorpeople->file_real[1] = $_POST['file_real1'];
    $nSponsorpeople->file_edit[1] = $_POST['file_edit1'];
    $nSponsorpeople->file_byte[1] = $_POST['file_byte1'];

		$nSponsorpeople->file_real[2] = $_POST['file_real2'];
    $nSponsorpeople->file_edit[2] = $_POST['file_edit2'];
    $nSponsorpeople->file_byte[2] = $_POST['file_byte2'];
	
    $nSponsorpeople->file_pre_name[1] = $nSponsorpeople->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

		$nSponsorpeople->file_pre_name[2] = $nSponsorpeople->file_edit[2];

    $check_del[2] = isset($_POST['check_del2']) ? $_POST['check_del2'] : null;


    for($cnt_file=1; $cnt_file <= $nSponsorpeople->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsorpeople/', '_'.$cnt_file.'_', $nSponsorpeople->file_volume[$cnt_file], $nSponsorpeople->file_mime_type[$cnt_file]);
            $nSponsorpeople->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSponsorpeople->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSponsorpeople->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSponsorpeople->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nSponsorpeople->file_real[$cnt_file] = '';
                $nSponsorpeople->file_edit[$cnt_file] = '';
                $nSponsorpeople->file_byte[$cnt_file] = '';
            }else{
                $nSponsorpeople->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'file_real2', 'file_edit2', 'file_byte2'
    );

    $arr_value = array
    (
        $nSponsorpeople->subject, $nSponsorpeople->content, $nSponsorpeople->file_real[1], $nSponsorpeople->file_edit[1], $nSponsorpeople->file_byte[1], $nSponsorpeople->file_real[2], $nSponsorpeople->file_edit[2], $nSponsorpeople->file_byte[2]
    );

		//======================== DB Module Sponsorpeoplet ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nSponsorpeople->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }else{
        for($cnt_file=1; $cnt_file <= $nSponsorpeople->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nSponsorpeople->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/sponsorpeople/'.$nSponsorpeople->file_pre_name[$cnt_file])) unlink('../../up_file/sponsorpeople/'.$nSponsorpeople->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/sponsor', array('item' => 'deans', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delDeans() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nSponsorpeople   = new \SponsorpeopleClass(); 

		//======================== DB Module Sponsorpeoplet ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSponsorpeople->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
    return redirect()->route('admin/sponsor', array('item' => 'deans'));
	}
	
	private function writeMainCompanion() {
    $nSCompany = new \SCompanyClass(); //후원기관

    $nSCompany->subject = $_POST['subject'];
		$nSCompany->content        = RepEditor($_POST['content']);

    for($cnt_file=1; $cnt_file <= $nSCompany->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/scompany/', '_'.$cnt_file.'_', $nSCompany->file_volume[$cnt_file], $nSCompany->file_mime_type[$cnt_file]);
            $nSCompany->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nSCompany->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nSCompany->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nSCompany->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1'
    );

    $arr_value = array
    (
        $nSCompany->subject, $nSCompany->content, $nSCompany->file_real[1], $nSCompany->file_edit[1], $nSCompany->file_byte[1]
    );

		//======================== DB Module SCompanyt ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nSCompany->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/sponsor', array('item' => 'main_companion')));
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
    return redirect()->route('admin/sponsor', array('item' => 'main_companion'));
	}
	
	private function delMainCompanion() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nSCompany   = new \SCompanyClass(); 

		//======================== DB Module SCompanyt ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nSCompany->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/sponsor', array('item' => 'main_companion')));
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
    return redirect()->route('admin/sponsor', array('item' => 'main_companion'));
	}
	
	private function writeMainDeans() {
    $nMClover = new \MCloverClass(); //후원기관

    $nMClover->subject = $_POST['subject'];
		$nMClover->content        = RepEditor($_POST['content']);
		$nMClover->name = $_POST['name'];

    for($cnt_file=1; $cnt_file <= $nMClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsor/', '_'.$cnt_file.'_', $nMClover->file_volume[$cnt_file], $nMClover->file_mime_type[$cnt_file]);
            $nMClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nMClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nMClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nMClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'name'
    );

    $arr_value = array
    (
        $nMClover->subject, $nMClover->content, $nMClover->file_real[1], $nMClover->file_edit[1], $nMClover->file_byte[1], $nMClover->name
    );

		//======================== DB Module MClovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nMClover->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/sponsor', array('item' => 'main_deans')));
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/sponsor', array('item' => 'main_deans'));
	}
	
	private function editMainDeans() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nMClover = new \MCloverClass(); //후원기관
  
    $nMClover->subject = $_POST['subject'];
		$nMClover->content        = RepEditor($_POST['content']);
		$nMClover->name        = $_POST['name'];
    $nMClover->file_real[1] = $_POST['file_real1'];
    $nMClover->file_edit[1] = $_POST['file_edit1'];
    $nMClover->file_byte[1] = $_POST['file_byte1'];
	
    $nMClover->file_pre_name[1] = $nMClover->file_edit[1];

    $check_del[1] = isset($_POST['check_del1']) ? $_POST['check_del1'] : null;

    for($cnt_file=1; $cnt_file <= $nMClover->file_up_cnt; $cnt_file++) {
        $parsing_file = 'upfile'.$cnt_file;
        if($_FILES[$parsing_file]['name']){
            $arr_file[$cnt_file] = FileUpload($_FILES[$parsing_file], '/home/clovergarden/cg_app/public/imgs/up_file/sponsor/', '_'.$cnt_file.'_', $nMClover->file_volume[$cnt_file], $nMClover->file_mime_type[$cnt_file]);
            $nMClover->file_real[$cnt_file] = RepFile($arr_file[$cnt_file][0]);
            $nMClover->file_edit[$cnt_file] = $arr_file[$cnt_file][1];
            $nMClover->file_byte[$cnt_file] = $arr_file[$cnt_file][2];
            if($arr_file[$cnt_file][3] == ''){
                JsAlert(ERR_MIME_TYPE);
            }
            if($arr_file[$cnt_file][4] == ''){
                JsAlert(ERR_FILESIZE1.$nMClover->file_volume[$cnt_file].ERR_FILESIZE2);
            }
            $check_del[$cnt_file] = 1;
        }else{
            if($check_del[$cnt_file] == '1'){
                $nMClover->file_real[$cnt_file] = '';
                $nMClover->file_edit[$cnt_file] = '';
                $nMClover->file_byte[$cnt_file] = '';
            }else{
                $nMClover->file_pre_name[$cnt_file] = '';
            }
        }
    }

    $arr_field = array
    (
        'subject', 'content', 'file_real1', 'file_edit1', 'file_byte1', 'name'
    );

    $arr_value = array
    (
        $nMClover->subject, $nMClover->content, $nMClover->file_real[1], $nMClover->file_edit[1], $nMClover->file_byte[1], $nMClover->name
    );

		//======================== DB Module MClovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nMClover->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/sponsor', array('item' => 'main_deans')));
    }else{
        for($cnt_file=1; $cnt_file <= $nMClover->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nMClover->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/sponsor/'.$nMClover->file_pre_name[$cnt_file])) unlink('../../up_file/sponsor/'.$nMClover->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/sponsor', array('item' => 'main_deans'));
	}
	
	private function delMainDeans() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

    $nMClover   = new \MCloverClass(); 

		//======================== DB Module MClovert ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nMClover->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, route('admin/sponsor', array('item' => 'main_deans')));
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/sponsor', array('item' => 'main_deans'));
	}
	
	private function writeNews() {
    $nNotice = new \NoticeClass(); //새소식

    $nNotice->subject = $_POST['subject'];
		$nNotice->writer_name       = $_POST['writer_name'];
		$nNotice->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name',  'content'
    );

    $arr_value = array
    (
        $nNotice->subject, $nNotice->writer_name, $nNotice->content
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nNotice->table_name, $arr_field, $arr_value);

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
  
		return redirect()->route('admin/customer', array('item' => 'news'));
	}
	
	private function editNews() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];


    $nNotice = new \NoticeClass(); //새소식
  
    $nNotice->subject = $_POST['subject'];
		$nNotice->writer_name       = $_POST['writer_name'];
		$nNotice->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name', 'content'
    );

    $arr_value = array
    (
        $nNotice->subject, $nNotice->writer_name, $nNotice->content
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nNotice->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
        $Conn->CommitTrans();
	}

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/customer', array('item' => 'news', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delNews() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

		$nNotice   = new \NoticeClass(); //새소식

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nNotice->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/customer', array('item' => 'news'));
	}
	
	private function delQna() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

		$nOnetoone   = new \OnetooneClass(); //1:1문의

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nOnetoone->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/customer', array('item' => 'qna'));
	}
	
	private function writeFaq() {
    $nFaq = new \FaqClass(); //자주묻는질문

    $nFaq->subject = $_POST['subject'];
		$nFaq->writer_name       = $_POST['writer_name'];
		$nFaq->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name',  'content'
    );

    $arr_value = array
    (
        $nFaq->subject, $nFaq->writer_name, $nFaq->content
    );

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->insertDB($nFaq->table_name, $arr_field, $arr_value);
    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/customer', array('item' => 'faq'));
	}
	
	private function editFaq() {
    $seq        = $_POST['seq'];
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $nFaq = new \FaqClass(); //자주묻는질문
    $nFaq->subject = $_POST['subject'];
		//$nFaq->writer       = NullVal($_POST['writer'], 1, $list_link);
		$nFaq->writer_name       = $_POST['writer_name'];
		$nFaq->content        = RepEditor($_POST['content']);

    $arr_field = array
    (
        'subject', 'writer_name', 'content'
    );

    $arr_value = array
    (
        $nFaq->subject, $nFaq->writer_name, $nFaq->content
    );

		//======================== DB Module Start ============================

		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nFaq->table_name, $arr_field, $arr_value, "where seq = '".$seq."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        //JsAlert(ERR_DATABASE, 1, $list_link);
    } else {
        $Conn->CommitTrans();
		}

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/customer', array('item' => 'faq', 'seq' => $seq, 'row_no' => $row_no, 'type' => 'view'));
	}
	
	private function delFaq() {
    $row_no     = $_POST['row_no'];
    $page_no    = $_POST['page_no'];
    $search_key = $_POST['search_key'];
    $search_val = $_POST['search_val'];

    $seq = $_POST['delete_seq'];

		$nFaq   = new \FaqClass(); //자주묻는질문

		//======================== DB Module Start ============================
		$Conn = new \DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB($nFaq->table_name, "where seq in (".$seq[0].")");

    if($out_put){
        $Conn->CommitTrans();
    }else{
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================
    
		return redirect()->route('admin/customer', array('item' => 'faq'));
	}
	
}

?>