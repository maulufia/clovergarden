@extends('front.page.login')

@section('login')
<section class="wrap">
	<header>
		<h2 class="ti">아이디 찾기</h2>
	</header>
	<div class="login">
		<form method="post" id="loginForm" action="{{ route('login', array('cate' => 5, 'dep01' => 1, 'type' => 'id_step1')) }}" style="margin-top:140px;">
			<input type="text" name="user_name" placeholder="이름을 입력해주세요.">
			<input type="text" name="user_cell" placeholder="연락처를 입력해주세요." class="mt20">
			<input type="hidden" name="list_link" value="{{ route('login', array('cate' => 5, 'dep01' => 1)) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<a href="#" id="find" class="ml10 orange_big_btn2" style="position:absolute; margin-top:-33px;">아이디 찾기</a>
		</form>
		<div class="desc">* 이름과 휴대폰번호를 입력하시면 아이디를 확인하실 수 있습니다.</div>
	</div>

	
</section>

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // placeholder
        $('input, textarea').placeholder();

        // 아이디찾기
        $( "#find" ).click(function() {

            if($('input[name=user_name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=user_name]').focus();
                return false;
            }

            if($('input[name=user_cell]').val() == ''){
                alert('연락처를 입력하세요.');
                $('input[name=user_cell]').focus();
                return false;
            }

            $( "#loginForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop