<?php
  $group_state = $_GET['group_state'];
  $group_name = $_GET['group_name'];

	if($group_state==1) {JsAlert(NO_PATH, 6);}

  $nMember        = new MemberClass(); //회원

	if($group_name){
		//======================== DB Module Start ============================
		$Conn = new DBClass();

		$nMember->where = " where user_state = '4' and group_name like '%".$group_name."%'";

		$nMember->page_result = $Conn->AllList
		(
			$nMember->table_name, $nMember, "*", $nMember->where, null, null
		);

		$Conn->DisConnect();
		//======================== DB Module End ===============================
	}
?>

<?php
    if(count($nMember->page_result) > 0){
        for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
            $nMember->VarList($nMember->page_result, $i, null);
?>
	<li><a href="javascript:set_group('{{ $nMember->group_name }}');">{{ $nMember->group_name }}</a></li>
<?php
        }
    }else{
?>
	<li style="text-align:center;">{{ NO_DATA }}</li>
<?php
    }
?>
