@extends('front.page.login')

@section('login')
<?php
	$explode_email = explode("@",$nMember->user_id);

	$email_id = strlen($explode_email[0]);
	$id_replace_ck = round($email_id/3);
	$sub_str_v = substr($explode_email[0],$id_replace_ck,$id_replace_ck);

	if($id_replace_ck == 1){
		$place_count = "*";
	} else if ($id_replace_ck == 2) {
		$place_count = "**";
	} else if ($id_replace_ck == 3) {
		$place_count = "***";
	} else if ($id_replace_ck == 4) {
		$place_count = "****";
	} else if ($id_replace_ck == 5) {
		$place_count = "*****";
	} else {
		$place_count = "******";
	}
	$explode_email[0] = str_replace($sub_str_v,$place_count,$explode_email[0]);

?>

<section class="wrap">
	<header>
		<h2 class="ti">아이디 찾기</h2>
	</header>
	<article class="box1">
		<h2 class="ti">고객 아이디</h2>
		회원님의 아이디는 <span class="c_orange">{{ $explode_email[0] }}{{ '@' . $explode_email[1] }} 입니다.
	</article>

	<div class="box2">
		<a href="{{ route('login') }}" class="orange_big_btn mr20">로그인 하기</a>
		<a href="{{ route('login', array('cate' => 5, 'dep01' => 2)) }}" class="green_big_btn">비밀번호 찾기</a>
	</div>
</section>
@stop