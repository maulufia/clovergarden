<?php

namespace clovergarden\Http\Controllers;

use Auth, Redirect;
use Flash;

class MsgController extends Controller
{
	
	public function __construct() {
		include(app_path().'/Custom/_common/_global.php');
	}
	
	/*
  |--------------------------------------------------------------------------
  | Message Control Methods
  |--------------------------------------------------------------------------
  |
  | These methods below are for message control.
  |
  */
  
  public function send() {
	  $nMessage = new \MessageClass(); //쪽지

		$nMessage->send_id    = $_POST['send_id']; // 작성자ID
		$nMessage->receive_id    = $_POST['receive_id'];
		$nMessage->content    = $_POST['content']; // 클로버아이디

		$more_mode    = isset($_POST['more_mode']) ? $_POST['more_mode'] : null;

		$arr_field = array
	    (
	        'send_id', 'receive_id', 'content'
	    );
		$Conn = new \DBClass();

		if($more_mode == "more"){
			$more_data = $nMessage->receive_id;

			for($i=0; $i<count($more_data); $i++){


				$arr_value[$i] = array($nMessage->send_id, $more_data[$i], $nMessage->content);

				//======================== DB Module Start ============================
					$Conn->StartTrans();

					$out_put = $Conn->InsertDB($nMessage->table_name, $arr_field, $arr_value[$i]);

					if($out_put){
						$Conn->CommitTrans();
					}else{
						$Conn->RollbackTrans();
						$Conn->disConnect();
					}
			}
		} else {
		$arr_value = array($nMessage->send_id, $nMessage->receive_id, $nMessage->content);

		//======================== DB Module Start ============================
			$Conn->StartTrans();

			$out_put = $Conn->InsertDB($nMessage->table_name, $arr_field, $arr_value);

			if($out_put){
				$Conn->CommitTrans();
			}else{
				$Conn->RollbackTrans();
				$Conn->disConnect();
			}

		//======================== DB Module End ===============================
		}

		$Conn->disConnect();
		
		Flash::success(SUCCESS_WRITE);
		return redirect()->route('mypage');
  }
  
  public function sendGroupCreate() {
  	$Conn = new \DBClass();
  	$nMessage = new \MessageClass(); //쪽지
  	
		$group_mode = isset($_POST['group_mode']) ? $_POST['group_mode'] : null;
		if($group_mode == "send_group_make"){
			$nMessage->send_id    = Auth::user()->user_id;
			$nMessage->receive_id    = "master@clovergarden.co.kr";
			$S_content = "
			단체/그룹명 : ".$_POST['group_name']."<BR>
			이름 : ".$_POST['name']."<BR>
			연락처 : ".$_POST['tel']."<BR>
			소속원 수 : ".$_POST['group_number']."<BR>
			단체 홈페이지 주소 : ".$_POST['group_homepage']."<BR>
			내용 : ".$_POST['contents']."
			";

			$nMessage->content    = $S_content; // 클로버아이디

			$arr_field = array
			(
				'send_id', 'receive_id', 'content'
			);

			$arr_value = array($nMessage->send_id, $nMessage->receive_id, $nMessage->content);

			//======================== DB Module Start ============================

			$Conn->StartTrans();

			$out_put = $Conn->InsertDB($nMessage->table_name, $arr_field, $arr_value);

			if($out_put){
				$Conn->CommitTrans();
			}else{

				$Conn->RollbackTrans();
				$Conn->disConnect();
			}
			//======================== DB Module End ===============================
		}
		
		$Conn->DisConnect();
		  
		Flash::success(SUCCESS_WRITE);
  	return redirect()->route('information');
  }
	
}

?>