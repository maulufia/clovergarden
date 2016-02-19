<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'A3';
    $list_link   = 'm_03_list.php';
    $view_link   = 'm_03_view.php';
    $edit_link   = 'm_03_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nMember = new MemberClass(); //회원
	$nClovermlist_login = new ClovermlistClass(); //후원목록
	$nClover_m = new CloverClass(); //클로버목록
	
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->where = "where seq ='".$seq."'";
    $nMember->read_result = $Conn->AllList
    (
        $nMember->table_name, $nMember, "*", $nMember->where, null, null
    );

    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

	$cell = explode("-", $nMember->user_cell);
	$email = explode("@", $nMember->user_email);


	$nClovermlist_login->page_result = $Conn->AllList
	(	
		$nClovermlist_login->table_name, $nClovermlist_login, "*", "where id='".$nMember->user_id."' order by reg_date desc", null, null
	);


$Conn->DisConnect();



//======================== DB Module End ===============================
?>
    <script language="javascript">
        function sendSubmit()
        {
            var f = document.frm;

			if(formCheckSub(f.user_pw, "exp", "비밀번호") == false){ return; }
			if(formCheckSub(f.user_pw, "inj", "비밀번호") == false){ return; }
			if(formCheckNum(f.user_pw, "minlen", 6, "비밀번호") == false){ return; }
			if(formCheckNum(f.user_pw, "maxlen", 20, "비밀번호") == false){ return; }

            $.blockUI();

            f.action = "<?=$edit_link?>";
            f.submit();
        }
    </script>
</head>
<body>


<!-- wrapper -->
<div id="wrapper">

    <!-- top_area -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/top.php'; ?>
    <!-- //top_area -->
    <!-- container -->

    <div id="container">
        <!-- left_area -->
            <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/left.php'; ?>
        <!-- //left_area -->
        <!-- right_area -->

        <div id="right_area">
            <h4 class="main-title"><?=$content_txt?></h4>
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
            <table class="bbs-write">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:15%;" />
                    <col style="width:85%;" />
                </colgroup>
                <tbody>
				<?if($nMember->user_state==3){?>
					<tr>
						<th>탈퇴사유</th>
						<td><?$nMember->ArrMember($nMember->user_drop, "name='user_drop'", '탈퇴사유를 선택해 주세요', 'drop')?></td>
					</tr>
					<tr>
						<th>기타탈퇴사유</th>
						<td>
							<textarea name="user_dropmsg" style="width:684px; height:147px; overflow-y:scroll;"><?=$nMember->user_dropmsg?></textarea><br />
						</td>
					</tr>
				<?}else{?>
               	<tr>
					<th>이름</th>
					<td><input type="text" class="joinforms" name="user_name" value="<?=$nMember->user_name?>"></td>
				</tr>
				<tr>
					<th>아이디</th>
					<td>
						<?=$nMember->user_id?>
					</td>
				</tr>
				<tr>
					<th>패스워드</th>
					<td>
						<input class="joinforms" name="user_pw" type="password" maxlength="15" style="ime-mode:disabled;" onkeydown="checkKeycode(event)" onkeypress="handlerEng()">
					</td>
				</tr>
				<tr>
					<th>생년월일</th>
					<td>
						<select name="year">
						<?php

					
							$birth_date = mktime();
							$birth_select_year = date('Y',strtotime($nMember->user_birth));
							$birth_select_month = date('m',strtotime($nMember->user_birth));
							$birth_select_day = date('d',strtotime($nMember->user_birth));

							$birth_year_start = date('Y',strtotime('-100 year', $birth_date));
							$birth_year_end = date('Y');
							for($i=$birth_year_start; $i<=$birth_year_end; $i++){
						?>
							<option <?if($i==$birth_select_year) echo 'selected';?> value="<?=$i?>"><?=$i?></option>
						<?php
							}
						?>
						</select>
						년
						<select name="month">
						<?php
							for($j=1; $j<=12; $j++){
						?>
							<option <?if($j==$birth_select_month) echo 'selected';?> value="<?=$j?>"><?=$j?></option>
						<?php
							}
						?>
						</select>
						월
						<select name="day">
						<?php
							for($k=1; $k<=31; $k++){
						?>
							<option <?if($k==$birth_select_day) echo 'selected';?> value="<?=$k?>"><?=$k?></option>
						<?php
							}
						?>
						</select>
						일

					</td>
				</tr>
				<tr>
					<th>성별</th>
					<td>

						<div id="radioForm" class="h200">
							<input type="radio" name="user_gender" id="M" value="M" <?if($nMember->user_gender == "M"){?>checked<?}?>>
							<label for="M" class="mr20">남자</label>
							<input type="radio" name="user_gender" id="F" value="F" <?if($nMember->user_gender == "F"){?>checked<?}?>>
							<label for="F">여자</label>
						</div>

					</td>
				</tr>
				<tr>
					<th>휴대번호</th>
					<td>
						<?=$nMember->user_cell?>
					</td>
				</tr>

				<tr>
					<th>소속정보</th>
					<td>
						[
						<?if($nMember->group_state == 3){?>단체<?}?>
						<?if($nMember->group_state == 4){?>기업<?}?>
						]
						<?=$nMember->group_name?>
					</td>
				</tr>
				<tr>
					<th>회원등급</th>
					<td>
						<?if($nMember->user_state == 2){?>개인<?}?>
						<?if($nMember->user_state == 3){?>기관담당자<?}?>
						<?if($nMember->user_state == 4){?>기업담당자<?}?>
					</td>
				</tr>


				<!-- <tr>
					<th>후원 목록</th>
					<td>
						<?



						if(count($nClovermlist_login->page_result) > 0){

							for($i=0, $cnt_list=count($nClovermlist_login->page_result); $i < $cnt_list; $i++) {
								$nClovermlist_login->VarList($nClovermlist_login->page_result, $i, null);

								$Conn = new DBClass();
									$nClover_m->where = "where code ='".$nClovermlist_login->clover_seq."'";
									$nClover_m->read_result = $Conn->AllList
									(
										$nClover_m->table_name, $nClover_m, "*", $nClover_m->where, null, null
									);
								
									if(count($nClover_m->read_result) != 0){
										$nClover_m->VarList($nClover_m->read_result, 0, null);

										$clover_name = $nClover_m->subject;
									}	
								$Conn->DisConnect();
						?>
						<p style="font-size:15px; font-weight:bold;">				
							<font color="66b050"><?=$nClovermlist_login->name?></font>님이  <font color="ed6c0a"><?=$clover_name?></font>에 후원하였습니다. 				
						</p>
						<?
							}
						} else {?>
						회원님의 후원 목록이 존재하지 않습니다.
						<?}?>
					</td>
				</tr> -->


				<?}?>
                </tbody>
            </table>
			<?=SubmitHidden()?>
            </form>
           <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <input type="image" src="/new_admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
                </div>
            </div>
        </div>
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
        </form>
        <!-- //right_area -->
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<?

?>
<!-- //wrapper -->
</body>
</html>