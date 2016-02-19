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

<!-- pagekey.blade.php -->
<?php
// 원래 checkPage 펑션 있던 자리
?>

<?php /*
    $sub_cate = isset($_GET['cate']) ? $_GET['cate'] : 0;
    if($sub_cate==null);
    $dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
    if($dep01==null) $dep01=0;
    $dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
    if($dep02==null) $dep02_active=0;
    else $dep02_active = $dep02;

    //게시판타입
    $type_get = isset($_GET['type']) ? $_GET['type'] : null;
    if($type_get != 'list' && $type_get != null){
        $board_type = '_'.$type_get;
        $type = '_'.$type_get;
    }

    $view_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=view';
    $write_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=write';
    $writeresv_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=writeresv';
    $step1_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=step1';
    $list_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active;

    $cate_file = checkPage($sub_cate,'cate');//대분류 이름
    $cate_name = checkPage($sub_cate,'name');//대분류 이름
    $cate_01_result = checkPage($sub_cate,'sub_cate_01');
    $cate_01 = checkPage($sub_cate,'cate');
    $cate_01_count = count($cate_01_result);
    // $cate_01_type = checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
    $cate_02_result = checkPage($sub_cate,'sub_cate_02'); */
?>

<!-- END OF pagekey.blade.php -->

<body
<?php
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    if($sub_cate == 1 && $dep01 == 0 && $dep02 == 0 && $type == 'write') {
?> 
    onload="javascript:Enable_Flag(frmAGS_pay);"
<?php
    }
?>
>
    <div id="global-wrap">       
    
@include('front.common.header')

@yield('sidebar')

@yield('content')

@include('front.common.footer')
