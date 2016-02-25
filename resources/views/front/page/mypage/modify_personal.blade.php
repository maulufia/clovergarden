@extends('front.page.mypage')

@section('mypage')
<section class="wrap">
	<header>
		<h2 class="ti">개인정보수정</h2>
	</header>
	<div class="edit">
		<form method="POST" id="wrtForm" action="{{ route('checkpassword') }}" style="display:inline;"  enctype="multipart/form-data">
			<div class="xm_left mt20" style="margin-left:134px;">
				<div class="xm_left">
					<input type="password" name="passwd" placeholder="비밀번호를 입력해주세요." class="pwd">
				</div>

				<div class="xm_left">					    
					<a href="javascript:document.getElementById('wrtForm').submit();" id="edit" class="ml10 mt_3 orange_big_btn">개인정보수정</a>
				</div>
			</div>
			{{ UserHelper::SubmitHidden() }}
		</form>
		<div class="xm_clr"></div>
		<div class="desc">* 정보 보호를 위해 비밀번호를 한번 더 입력해주세요.</div>
	</div>
	
</section>
@stop