<?php
	require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
	require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자
?>
<header id="header">
	<div class="wrap">
		 <ul>
		 <?if($login_id==null){?>
			 <li><a href="/page.php?cate=5&dep01=0">로그인</a></li>
			 <li><a href="/page.php?cate=5&dep01=3">회원가입</a></li>
	     <?}else{?>
			 <li><a href="/logout_exec.php">로그아웃</a></li>
			 <li><a href="/page.php?cate=6">마이페이지</a></li>
		 <?}?>
			 <li><a href="/page.php?cate=7">사이트맵</a></li>
		 </ul>
	 </div>

	 <!-- Logo -->
	 <div id="logo">
		<a href="index.php"><img src="/common/img/TopLogo.png" alt="clover garden" /></a>
	</div>

	<!-- Nav -->

	<nav id="nav">
		<ul>
			<li><a href="/page.php?cate=0" <?if($sub_cate==0 && $sub_cate != '') echo "class='on'";?>>후원자마당</a></li>
			<li><a href="/page.php?cate=1" <?if($sub_cate==1) echo "class='on'";?>>클로버가든</a></li>
			<li><a href="/page.php?cate=2" <?if($sub_cate==2) echo "class='on'";?>>함께하는 사람들</a></li>
			<li><a href="/page.php?cate=3" <?if($sub_cate==3) echo "class='on'";?>>이용안내</a></li>
			<li><a href="/page.php?cate=4" <?if($sub_cate==4) echo "class='on'";?>>고객센터</a></li>
		</ul>
	</nav>
</header>