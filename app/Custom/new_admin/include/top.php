<?PublicUserLoginMsg('http://clovergarden.co.kr/new_admin', '로그인 후 이용 가능합니다.');?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?	
	$nMoney = new MoneyClass(); //

	//======================== DB Module Moneyt ============================
	$Conn = new DBClass();

		$nMoney->read_result = $Conn->AllList($nMoney->table_name, $nMoney, "*", "where seq ='1'", $nMoney->sub_sql, null);

		if(count($nMoney->read_result) != 0){
			$nMoney->VarList($nMoney->read_result, 0, array('comment'));
		}

	$Conn->DisConnect();
	//======================== DB Module End ===============================
?>

<script language="javascript">

function moneyEdit()
{

		var today = $("#money_today").val();
		var month = $("#money_month").val();
		var string_value = "today="+encodeURIComponent(today)+"&month="+encodeURIComponent(month);
		$.ajax({
			url: '../../new_admin/include/money_exec.php',
			type: 'GET',
			data: string_value,
			error: function(){
				alert("<?=ERR_DATABASE?>");
				return;
			},
			success: function(data){
				data = data.split("@@||@@");
				var data_result = eval("("+data[1]+")");
				if(data_result.counsel_check == 'y'){
					alert("수정완료 되었습니다.");
				}else{
					alert("수정작업이 실패되었습니다.");
				}
			}
		})

}

</script>

<div id="top_area">
	
    <div id="nav_right">
		<B><?=$login_name?></B> 님 환영합니다. | <a href="/new_admin/logout_exec.php">로그아웃</a>
		<?if($login_state==1){?>
		<div style="margin-top:15px; text-align:right;">
			<strong>금일 기부 금액 ￦ </strong> <input type="text" name="today" id="money_today" value="<?=$nMoney->today?>">    <strong>누적 기부 금액 ￦</strong> <input type="text" name="month" id="money_month" value="<?=$nMoney->month?>"> 
			<a href="javascript:moneyEdit();"><img src="/new_admin/images/btn_small_modify.gif" alt="수정" align="center"></a>
		</div>
		<?}?>
	</div>
    <h1><a href="http://colovergarden.co.kr" target="_blank"><img src="/common/img/TopLogo.png" alt="" style="width:150px;"/></a></h1>

    <div id="nav_main">
        <ul>
			<?if($login_state > 6){?>
            <li class="first<?if($key_large == 'A'){ echo ' on'; }?>"><a href="../member/m_01_list.php">회원관리</a></li>			
			<li<?if($key_large == 'C'){ echo " class='on'"; }?>><a href="../clover/m_01_list.php">후원기관관리</a></li>
			<li<?if($key_large == 'H'){ echo " class='on'"; }?>><a href="../service/m_01_list.php">봉사스케쥴관리</a></li>
			<?}else{?>
			<li<?if($key_large == 'C'){ echo " class='on'"; }?>><a href="../clover/m_03_list.php">후원기관관리</a></li>
			<li<?if($key_large == 'H'){ echo " class='on'"; }?>><a href="../service/m_01_list.php">봉사스케쥴관리</a></li>
			<?}?>
						
			<?if($login_state > 6){?>
            <li<?if($key_large == 'B'){ echo " class='on'"; }?>><a href="../community/m_01_list.php">커뮤니티관리</a></li>
            
            <li<?if($key_large == 'D'){ echo " class='on'"; }?>><a href="../sponsor/m_01_list.php">클로버관리</a></li>
			
			<li<?if($key_large == 'E'){ echo " class='on'"; }?>><a href="../customer/m_01_list.php">고객센터</a></li>
			<li<?if($key_large == 'G'){ echo " class='on'"; }?>><a href="../stats/m_01_list.php">통계관리</a></li>
			<li<?if($key_large == 'I'){ echo " class='on'"; }?>><a href="../page/p_01.php">페이지관리</a></li>
			<?}?>
        </ul>
    </div>

</div>