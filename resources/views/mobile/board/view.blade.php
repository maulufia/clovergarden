<!DOCTYPE html>
<html>
	<head>
		<title>{{ $title }}</title>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi"/>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/mobile-pay.css" />
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  </head>
  <body>
    <section class="wrap">
    	<article class="brd_view_title" style="margin-top: 0px;">
    		<div class="brd_view_title_1">
    			<h3>제목</h3>
    			<span>{{ $post->subject }}</span>
    		</div>
    		<div class="xm_clr"></div>

    		<div class="brd_view_title_2">
    			<h4>날짜</h4>
    			<span class="mr30">{{ date('Y-m-d',strtotime($post->reg_date)) }}</span>

    			<h4>작성자</h4>
    			<span>클로버가든</span>
    		</div>
    	</article>

    	<article class="brd_view_cont">
    		<h2 class="ti">내용</h2>
    		<?php echo $post->content ?>
    	</article>
    </section>
  </body>
</html>
