<?php

namespace clovergarden\Http\Controllers;

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
	
	// 메인
	public function showAdmin() {
		return view('admin.main');
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
	
}

?>