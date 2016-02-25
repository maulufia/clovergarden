<?php 
    header( "Content-type: application/vnd.ms-excel" );   
    header( "Content-type: application/vnd.ms-excel; charset=utf-8");  
    header( "Content-Disposition: attachment; filename = member_list.xls" );   
    header( "Content-Description: PHP4 Generated Data" );  

    $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
    $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
    $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

    $nMember = new MemberClass(); //회원
    $nMember_win = new MemberClass(); //회원

    //======================== DB Module Start ============================
    $Conn = new DBClass();

	$nMember->page_view  = 200000;

    $nMember->where = " where (user_state = '2' or user_state='-1' or user_state='5') and clover_win='S'";

    $nMember->total_record = $Conn->PageListCount
    (
        $nMember->table_name, $nMember->where, $search_key, $search_val
    );
	if($nMember->total_record >= 4){
		$ck_clover_win = "S";
	}


    $nMember->where = " where (user_state = '2' or user_state='-1' or user_state='5')";

    $nMember->total_record = $Conn->PageListCount
    (
        $nMember->table_name, $nMember->where, $search_key, $search_val
    );

    $nMember->page_result = $Conn->PageList
    (
        $nMember->table_name, $nMember, $nMember->where, $search_key, $search_val, 'order by reg_date desc', $nMember->sub_sql, $page_no, $nMember->page_view
    );


    $nMember_win->where = " where clover_win='S'";

    $nMember_win->total_record = $Conn->PageListCount
    (
        $nMember_win->table_name, $nMember_win->where, $search_key, $search_val
    );

    $nMember_win->page_result = $Conn->PageList
    (
        $nMember_win->table_name, $nMember_win, $nMember_win->where, $search_key, $search_val, 'order by reg_date desc', $nMember_win->sub_sql, $page_no, $nMember_win->page_view
    );

    $Conn->DisConnect();
    //======================== DB Module End ===============================

 
  
echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";  
?>  

            <table class="bbs-list" border="1">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:50px;" />
                    <col  />
                    <col style="width:100px;" />
					<col style="width:80px;" />
                    <col style="width:80px;" />
					<col style="width:120px;" />
                    <col style="width:120px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
                    <th>번호</th>
                    <th>아이디</th>
                    <th>이름</th>
					<th>지역</th>
					<th>정보동의</th>
					<th>연락처</th>
                    <th>이메일</th>
                    <th>가입일</th>
                </tr>
                </thead>
                <tbody>
<?php
    if(count($nMember->page_result) > 0){
        $row_no = $nMember->total_record - ($nMember->page_view * ($page_no - 1));
        for($i=0, $cnt_list=count($nMember->page_result); $i < $cnt_list; $i++) {
            $nMember->VarList($nMember->page_result, $i, null);
?>
                <tr>
                    <td>{{ $row_no }}</td>
                    <td><?php if($nMember->user_state==-1) echo "<font style='color:red'>[탈퇴회원]</font>"; ?><a href="javascript:pageLink('{{ $nMember->seq }}','{{ $row_no }}','','{{ $edit_link }}');">{{ $nMember->user_id }}</a></td>					
                    <td>{{ $nMember->user_name }}</td>
					<td>
                    <?php
                        if(isset($nMember->user_addr)) {
                            if($nMember->user_addr!=null) $nMember->ArrMember($nMember->user_addr, null, null, 'city', 1);
                        }
                    ?>
                    </td>
                    <td>
                    <?php
                        if(isset($nMember->user_receive)) {
                            if($nMember->user_receive != 0) $nMember->ArrMember($nMember->user_receive, null, null, 'receive', 1);
                        }
                    ?>
                    </td>
					<td>{{ $nMember->user_cell }}</td>
					<td></td>
                    <td>{{ str_replace('-','.',substr($nMember->reg_date,0,10)) }}</td>
                </tr>
<?php
            $row_no = $row_no - 1;
        }
    }else{
?>
                <tr>
                    <td colspan="9">{{ NO_DATA }}</td>
                </tr>
<?php
    }
?>
                </tbody>
            </table>
