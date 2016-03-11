@extends('front.page.login')

@section('login')
<section class="wrap">
	<header>
		<h2 class="ti">회원가입</h2>
	</header>
	<div class="login">
		<div class="btns">
			<a href="{{ route('login', array('cate' => 5, 'dep01' => 4)) }}" class="ml10 orange_big_btn2 resize">일반 회원<br>가입하기</a>
			<a href="javascript:FaceBookLogin();" class="ml10 orange_big_btn2 resize blue">
				페이스북<br>가입하기
			</a>
			<a href="/login/naver" class="ml10 orange_big_btn2 resize green">
				네이버<br>가입하기
			</a>
			<!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
			</fb:login-button> -->

		</div>
	</div>


	
</section>

<script>
	function FaceBookLogin() {
		$(location).attr('href', '/login/facebook');
	}
	
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