<?php
$page_key   = 'A6';
$list_link  = 'm_06_list.php';

$nAdm = new AdmClass(); //
$nAdm_2 = new AdmClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();


$u_mode = isset($_POST['u_mode']) ? $_POST['u_mode'] : null;
if($u_mode == "update"){
	$sql = "update new_tb_adm_tup set t_text='".$_POST['use_v']."' where t_name='use_v'";
	mysql_query($sql);

	$sql = "update new_tb_adm_tup set t_text='".$_POST['pric_v']."' where t_name='pric_v'";
	mysql_query($sql);

	UrlReDirect("¾à°üÀÌ ¼öÁ¤µÇ¾ú½À´Ï´Ù.", $list_link);
}



$nAdm->page_result = $Conn->AllList
(	
	$nAdm->table_name, $nAdm, "*", "where t_name='use_v' order by idx desc limit 1", null, null
);

$nAdm_2->page_result = $Conn->AllList
(	
	$nAdm_2->table_name, $nAdm_2, "*", "where t_name='pric_v' order by idx desc limit 1", null, null
);


$Conn->DisConnect();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Clover Garden</title>

    <!-- 파비콘 링크 -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="144x144" href="/imgs/apple-touch-icon-ipad-retina-152x152.png">
	
    <!-- 스타일시트 링크 -->
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('js/jquery.simplyscroll.css') }}" media="all" type="text/css"><!-- Partner css -->

	<!-- 공통 -->
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<!-- <script src="/common/js/vendor/jquery.js"></script> --> <!-- 왜 CDN을 받고 또 받아왔는지 이해 불가 -->

	<!-- Partner script -->
	<script type="text/javascript" src="{{ url('js/jquery.simplyscroll.js') }}"></script>

	<!-- Placeholder script -->
	<script src="{{ url('js/jquery.placeholder.js') }}"></script>

	<!-- Tab script -->
	<script src="{{ url('js/vendor/jquery-ui.js') }}"></script>

    <!-- 환경 스크립트 -->
    <!--[if lt IE 9]>
    <script src="/js/vendor/html5.js"></script>
    <script src="/js/vendor/respond.js"></script>
    <![endif]-->
</head>
<body>
<table cellpadding=0 cellspacing=0 border=0 width='499' style="background:url('/imgs/new_images/pri_bg_<?php if($_GET["view"] == "use"){ ?>2<?php } else { ?>1<?php } ?>.jpg');">
<tr height=554>
	<td>
<?php if($_GET['view'] == "use"){ ?>
				<?php
					for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
						$nAdm->VarList($nAdm->page_result, $i, null);
				?>
				<textarea name="use_v" style="position:absolute;left:30px;top:60px;width:438px;border:none; height:480px; line-height:200%; font-size:11px;">{{ $nAdm->t_text }}</textarea>
				<?php
					}
				?>
<?php } else { ?>
				<?php
					for($i=0, $cnt_list=count($nAdm_2->page_result); $i < $cnt_list; $i++) {
						$nAdm_2->VarList($nAdm_2->page_result, $i, null);
				?>
				<textarea name="pric_v" style="position:absolute;left:30px;top:60px;width:438px;border:none; height:480px; line-height:200%; font-size:11px;">{{ $nAdm_2->t_text }}</textarea>
				<?php
					}
				?>
<?php } ?>	
	</td>
</tr>
</table>

</body>
</html>