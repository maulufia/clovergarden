@extends('front.page.sitemap')

@section('sitemap')
<section class="wrap">
	<header>
		<h2 class="ti">사이트맵</h2>
	</header>
	
	<div class="sitemap">
		
		<ul class="depth">
			<li><h3>후원자마당</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('sponsorzone', array('cate' => 0, 'dep01' => 0)) }}">후원자마당</a></li>
					<li><a href="{{ route('sponsorzone', array('cate' => 0, 'dep01' => 1, 'dep02' => 0)) }}">커뮤니티</a></li>
					<li><a href="{{ route('sponsorzone', array('cate' => 0, 'dep01' => 2)) }}">봉사스케줄</a></li>
				</ul>
			</li>
		</ul>

		<ul class="depth">
			<li><h3>클로버가든</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('clovergarden', array('cate' => 1, 'dep01' => 0)) }}">목록</a></li>
					<li><a href="{{ route('clovergarden', array('cate' => 1, 'dep01' => 1)) }}">소식지</a></li>
				</ul>
			</li>
		</ul>

		<ul class="depth last">
			<li><h3>함께하는 사람들</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('companion', array('cate' => 2, 'dep01' => 0)) }}">함께하는 사람들</a></li>
					<li><a href="{{ route('companion', array('cate' => 2, 'dep01' => 1)) }}">이달의 클로버</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="xm_clr"></div>

	<div class="sitemap mt45">
		<ul class="depth">
			<li><h3>이용안내</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('information', array('cate' => 3, 'dep01' => 0)) }}">일반</a></li>
					<li><a href="{{ route('information', array('cate' => 3, 'dep01' => 1)) }}">단체</a></li>
					<li><a href="{{ route('information', array('cate' => 3, 'dep01' => 2)) }}">업무제휴</a></li>
				</ul>
			</li>
		</ul>

		<ul class="depth">
			<li><h3>고객센터</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 0)) }}">새소식</a></li>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 1)) }}">1:1 문의</a></li>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 2)) }}">자주하는 질문</a></li>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 3)) }}">회사소개</a></li>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 4)) }}">찾아오시는 길</a></li>
				</ul>
			</li>
		</ul>

		<ul class="depth last">
			<li><h3>마이페이지</h3></li>
			<li>
				<ul>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 0)) }}">회원쪽지 보내기</a></li>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 1)) }}">포인트조회</a></li>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 2)) }}">나의활동</a></li>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 3)) }}">공제센터</a></li>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 4)) }}">개인정보수정</a></li>
					<li><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 5)) }}">결제정보수정</a></li>
				</ul>
			</li>
		</ul>

	</div>	    		
</section>
@stop