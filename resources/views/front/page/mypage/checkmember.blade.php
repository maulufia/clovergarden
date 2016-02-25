<?php
    $user_id = $_GET['user_id'];
    $getck = $_GET['getck'];

    $nMember        = new MemberClass(); //회원
	if($user_id){
	//======================== DB Module Start ============================
	$Conn = new DBClass();

		$nMember->where = " where user_id like '%".$user_id."%' or user_name like '%".$user_id."%' and user_state > 1 ";

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
	<li><a href="javascript:set_id('{{ $nMember->user_id }},{{ $nMember->user_name }}','{{ $getck }}');">{{ $nMember->user_id }}[{{ $nMember->user_name }}]</a></li>
<?php
        }
    } else {
?>
	<li style="text-align:center;">{{ NO_DATA }}</li>
<?php
    }
?>
    