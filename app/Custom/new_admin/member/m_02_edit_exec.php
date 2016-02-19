<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $list_link = NullVal($_POST['list_link'], 0, null);

    $nMember = new MemberClass(); //회원

	$login_id = $_POST[l_id];
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$nMember->where = "where user_id ='".$login_id."'";

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);
		if(count($nMember->read_result) != 0){
			$nMember->VarList($nMember->read_result, 0, null);
		}else{
			$Conn->DisConnect();
			JsAlert(NO_DATA, 1, $list_link);
		}

	$Conn->DisConnect();
	//======================== DB Module End ===============================

	$nMember->user_name        = NullVal($_POST['user_name'], 1, $list_link);

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
		if($_POST['user_pw']!=null){
			$nMember->user_pw =  RequestAll(md5(strtolower($_POST['user_pw'])));
		}else{
			$nMember->user_pw =  $nMember->user_pw;
		}
		$nMember->user_birth = $_POST['user_birth'];
		$arr_field = array
		(
			'user_name', 'user_pw','user_birth'
		);

		$arr_value = array
		(
			$nMember->user_name, $nMember->user_pw, $nMember->user_birth
		);
	}


//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDB($nMember->table_name, $arr_field, $arr_value, "where user_id = '".$login_id."'");
    if(!$out_put){
        $Conn->RollbackTrans();
        $Conn->disConnect();
        JsAlert(ERR_DATABASE, 1, $list_link);
    }else{
		for($cnt_file=1; $cnt_file <= $nMember->file_up_cnt; $cnt_file++) {
            if($check_del[$cnt_file] == 1 && $nMember->file_pre_name[$cnt_file] != ''){
                if(FileExists('../../up_file/member/'.$nMember->file_pre_name[$cnt_file])) unlink('../../up_file/member/'.$nMember->file_pre_name[$cnt_file]);
            }
        }
        $Conn->CommitTrans();
    }

$Conn->disConnect();
//======================== DB Module End ===============================

UrlReDirect(SUCCESS_EDIT, $list_link);
?>
