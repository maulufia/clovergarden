@extends('front.page.login')

@section('login')
<section class="wrap">
	<header>
		<h2 class="ti">로그인</h2>
	</header>
	<div class="login">
		<form method="POST" id="loginForm" action="/login">
			<input type="text" name="idu" placeholder="이메일을 입력해주세요.">
			<input type="password" name="passwd" placeholder="비밀번호를 입력해주세요." class="mt20">
			{{ UserHelper::SubmitHidden() }}
			<input type="submit" value="LOGIN" id="login" class="ml10 orange_big_btn2" style="position:absolute; margin-top:-33px;border:none;">
			<a href="#" onclick="javascript:FaceBookLoginAPI();"  scope="public_profile,email" class="ml10 orange_big_btn2 blue" style="position: absolute; margin-top: -33px; left: 537px;">
				페이스북<br>로그인
			</a>
			
			<div id="fb-root"></div>

		</form>
	</div>
	
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // placeholder
        $('input, textarea').placeholder();

        // 로그인
        $( "#login" ).click(function() {

            if($('input[name=idu]').val() == ''){
                alert('이메일을 입력하세요.');
                $('input[name=idu]').focus();
                return false;
            }

            if($('input[name=passwd]').val() == ''){
                alert('비밀번호를 입력하세요.');
                $('input[name=passwd]').focus();
                return false;
            }

            $( "#loginForm" ).submit();

        });
    });
})(jQuery);

</script>

<script>
  window.fbAsyncInit = function() {         //페이스북 sdk 초기화 작업
  FB.init({
    appId      : '231731487016840',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });
 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    if (response.status === 'connected') {
 
      //FaceBookLoginAPI();                 // 페이스북 로그인 되었을 경우 바로 로그인 되게 할경우 주석 해지.
    } else if (response.status === 'not_authorized') {
      FB.login();
    } else {
 
      FB.login();
    }
  });
  };
 
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_KR/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));
 
   function FaceBookLoginAPI() {
   FB.login(function(logresponse){
            var fbname;  
            var accessToken = logresponse.authResponse.accessToken; 
        FB.api('/me', function(response) {
                $.post("../../login.php", { name: response.name, id: response.id+'__fac', facebook:"facebook", image: response.user_picture_path}, 
					
                    function(postphpdata) { 
					
                    if(accessToken){
						
                       location.replace('./index.php');
                    }
                }); 
				//alert(response.user_picture_path);
          alert('Good to see you, ' + response.name + '.'+ response.id+ response.email);
        });
    }, {scope: 'publish_stream'});
}   
</script>
@stop