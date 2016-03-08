@extends('front.page.login')

@section('login')
<section class="wrap">
	<header>
		<h2 class="ti">비밀번호 찾기</h2>
	</header>
	<div class="login">
		<form method="post" id="loginForm" action="{{ route('login', array('cate' => 5, 'dep01' => 2, 'type' => 'pw_step1')) }}" style="margin-top:120px;">
			<input type="text" name="user_id" placeholder="아이디(이메일)을 입력해주세요.">
			<input type="text" name="user_name" placeholder="이름을 입력해주세요." class="mt20">
			<input type="text" name="user_cell" placeholder="휴대폰 번호를 입력해주세요." class="mt20">
			<input type="hidden" name="list_link" value="{{ route('login', array('cate' => 5, 'dep01' => 2)) }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<a href="#" id="find" class="ml10 orange_big_btn2" style="position:absolute; margin-top:-90px; padding:45px 0 15px; height:80px; line-height:150%">비밀번호<br>찾기</a>
		</form>
		<div class="desc">* 아이디, 이름, 휴대폰번호를 입력하시면 이메일로 임시 비밀번호가 발송 됩니다.</div>
	</div>

	
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 아이디찾기
        $( "#find" ).click(function() {

            if($('input[name=user_id]').val() == ''){
                alert('아이디(이메일)를 입력하세요.');
                $('input[name=user_id]').focus();
                return false;
            }

            if($('input[name=user_name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=user_name]').focus();
                return false;
            }

            if($('input[name=user_cell]').val() == ''){
                alert('휴대폰 번호를 입력하세요.');
                $('input[name=user_cell]').focus();
                return false;
            }

            $( "#loginForm" ).submit();

        });

        // placeholder
        $('input, textarea').placeholder();
    });
})(jQuery);
</script>
@stop