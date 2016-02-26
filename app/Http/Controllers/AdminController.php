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
	
	public function postClover() {
		// Other Options for board
		$option = new \StdClass();
		$option->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : null;
		$option->type = isset($_GET['type']) ? $_GET['type'] : null;
		
		if(is_null($option->type) || $option->type == 'view')
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
}

?>