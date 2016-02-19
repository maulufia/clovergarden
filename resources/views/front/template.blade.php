<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Clover Garden</title>

    <!-- 파비콘 링크 -->
    <link rel="shortcut icon" href="/common/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/common/img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="144x144" href="/common/img/apple-touch-icon-ipad-retina-152x152.png">
	
    <!-- 스타일시트 링크 -->
    <link rel="stylesheet" href="/common/css/style.css">
    <link rel="stylesheet" href="/common/js/jquery.simplyscroll.css" media="all" type="text/css"><!-- Partner css -->


	<!-- 공통 -->
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="/common/js/vendor/jquery.js"></script>

	<!-- Partner script -->
	<script type="text/javascript" src="/common/js/jquery.simplyscroll.js"></script>

	<!-- Placeholder script -->
	<script src="/common/js/jquery.placeholder.js"></script>

	<!-- Tab script -->
	<script src="/common/js/vendor/jquery-ui.js"></script>

    <!-- 환경 스크립트 -->
    <!--[if lt IE 9]>
    <script src="/common/js/vendor/html5.js"></script>
    <script src="/common/js/vendor/respond.js"></script>
    <![endif]-->
</head>

@include('front.common.pagekey')

<body <?if($sub_cate==1&&$dep01==0&&$dep02==0&&$_GET['type']=='write'){?>onload="javascript:Enable_Flag(frmAGS_pay);"<?}?>
>
<div id="global-wrap">
    
@include('front.header')

@include('front.footer')
