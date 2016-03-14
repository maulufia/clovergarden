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
    
    <!-- Flash -->
    @include('flash::message') <!-- Javascript Alert을 쓰고 싶으면 message_alert을 쓴다 -->
</head>

<!-- pagekey.blade.php -->
<?php
// 원래 checkPage 펑션 있던 자리
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

@if(isset($popup) && $popup == 'y')
    <!-- get popup image url -->
    <?php
      $popup_img = DB::table('new_tb_adm_tup')->where('t_name', '=', 'popup')->get();
      $popup_img = $popup_img[0]->t_text;
    ?>
  
    <!-- POPUP -->
    <div id="popup-blackscreen"></div>
    <div id="popup-wrapper">
        <div id="popup">
            <div class="popup-content"><!-- 내용 --><img src="/imgs/page/{{ $popup_img }}" width="100%" /></div>
            <div class="popup-footer">
                <input type="checkbox" id="popup_checkbox"> 오늘 하루동안 이 창을 열지 않음
                <button type="button" class="close" onclick="closePopup();">×</button>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            if(getCookie('clovergarden_popup') == 'done') {
                closePopup();
            } else {
                showPopup();
            }
        });
        
        function showPopup(object) {
            $('#popup-blackscreen').show();
            $('#popup-wrapper').show();
        }
        
        function closePopup(object) {
            if($('#popup_checkbox').is(':checked')) {
                setCookieAt00('clovergarden_popup', 'done', 1);
            }
            
            $('#popup-blackscreen').hide();
            $('#popup-wrapper').hide();
        }
        
        // 쿠키 가져오기  
        function getCookie( name ) {  
           var nameOfCookie = name + "=";  
           var x = 0;  
           while ( x <= document.cookie.length )  
           {  
               var y = (x+nameOfCookie.length);  
               if ( document.cookie.substring( x, y ) == nameOfCookie ) {  
                   if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )  
                       endOfCookie = document.cookie.length;  
                   return unescape( document.cookie.substring( y, endOfCookie ) );  
               }  
               x = document.cookie.indexOf( " ", x ) + 1;  
               if ( x == 0 )  
                   break;  
           }  
           return "";  
        }   
        
        function setCookieAt00(name, value, expiredays) {   
            var todayDate = new Date();   
            todayDate = new Date(parseInt(todayDate.getTime() / 86400000) * 86400000 + 54000000);  
            if ( todayDate > new Date() )  
            {  
            expiredays = expiredays - 1;  
            }  
            todayDate.setDate( todayDate.getDate() + expiredays );   
             document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"   
          }  
    </script>
@endif
    
@include('front.common.header')

@yield('sidebar')

@yield('content')

@include('front.common.footer')
