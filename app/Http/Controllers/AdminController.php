<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect, Hash;

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
		// Other Options for board
		$option = new \StdClass();
		$option->list_link = route('admin/member');
		$option->write_link = $option->list_link.'?type=write';
		$option->view_link = $option->list_link.'?type=view';
		$option->edit_link = $option->list_link.'?type=edit';
		$option->delete_link = $option->list_link.'?type=del';
		$option->excel_link = $option->list_link.'?type=excel'; // 안 좋은 디자인
		$option->excel_link2 = $option->list_link.'?type=excel2'; // 안 좋은 디자인
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;

		$item = isset($_REQUEST['item']) ? $_REQUEST['item'] : 'list_admin';
		
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
		
		return 'error';
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
  
  public function postMethodControl() {

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

		//======================== DB Module Clovert ============================
		$Conn = new \DBClass();
		$_POST['s_email'] = str_replace(",","','",$_POST['s_email']);
    $Conn->StartTrans();
    $out_put = $Conn->DeleteDB('new_tb_member', "where user_id in ('".$_POST['s_email']."')");
    $out_put = $Conn->DeleteDB('new_tb_clover_mlist', "where id in ('".$_POST['s_email']."')");
    $out_put = $Conn->DeleteDB('new_tb_message', "where send_id in ('".$_POST['s_email']."')");
    $out_put = $Conn->DeleteDB('new_tb_point', "where userid in ('".$_POST['s_email']."')");

    if($out_put){
        $Conn->CommitTrans();
    } else {
        $Conn->RollbackTrans();
        $Conn->disConnect();
    }

		$Conn->disConnect();
		//======================== DB Module End ===============================

		return redirect()->route('admin/member', array('item' => 'list_normal'));
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
	
}

?>