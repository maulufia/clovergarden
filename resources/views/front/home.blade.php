@extends('front.common.husk')

@section('content')
<?php	
	$nFree = new FreeClass(); //
	$nClovercomment = new ClovercommentClass(); //
	$nMoney = new MoneyClass(); //
	$nClovermlist = new ClovermlistClass(); //

	$nSCompany = new SCompanyClass(); //
	$nMClover = new MCloverClass(); //
	$nClover_m = new CloverClass(); //클로버목록
	$nMember = new MemberClass();
	$nMember_win = new MemberClass(); //회원

	$nClover_banner = new CloverClass(); //
	$nSchedule  = new ScheduleClass(); //봉사스케쥴
		
	//======================== DB Module Freet ============================
	$Conn = new DBClass();


	
		$sql = "
		insert into new_tb_stats set
		user_ip='".$_SERVER['REMOTE_ADDR']."|".date("Y-m-d")."',
		stats_date='".date("Y-m-d")."'
		";
		mysql_query($sql);
		$nFree->page_result = $Conn->AllList
		(	
			$nFree->table_name, $nFree, "*", "where 1 order by seq desc limit 5", null, null
		);

		$nClovercomment->page_result = $Conn->AllList
		(	
			$nClovercomment->table_name, $nClovercomment, "*", "where 1 order by seq desc limit 5", null, null
		);


		$nClovercomment->page_result = $Conn->AllList
		(	
			$nClovercomment->table_name, $nClovercomment, "*", "where 1 order by seq desc limit 5", null, null
		);


		$nClovermlist->page_result = $Conn->AllList
		(	
			$nClovermlist->table_name, $nClovermlist, "*", "where 1 order by price desc limit 4", null, null
		);


		$nMember_win->page_result = $Conn->AllList
		(	
			$nMember_win->table_name, $nMember_win, "*", "where clover_win='S' limit 4", null, null
		);


		$nSCompany->page_result = $Conn->AllList
		(	
			$nSCompany->table_name, $nSCompany, "*", "where 1 order by seq desc limit 3", null, null
		);

		$nMClover->page_result = $Conn->AllList
		(	
			$nMClover->table_name, $nMClover, "*", "where 1 order by seq desc limit 3", null, null
		);


		
		$nMoney->read_result = $Conn->AllList($nMoney->table_name, $nMoney, "*", "where seq ='1'", $nMoney->sub_sql, null);

		if(count($nMoney->read_result) != 0){
			$nMoney->VarList($nMoney->read_result, 0, array('comment'));
		}

		$nClover_banner->page_result = $Conn->AllList
		(	
			$nClover_banner->table_name."_banner", $nClover_banner, "*", "where 1 order by seq desc", null, null
		);

		$nSchedule->where = "where (1) order by work_date asc";
		$nSchedule->page_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);


	$Conn->DisConnect();
	//======================== DB Module End ===============================

?>
<link rel="stylesheet" href="/css/flexslider.css" type="text/css" media="screen" />
<script src="/js/modernizr.js"></script>
<script defer src="/js/jquery.flexslider.js"></script>

<!-- Optional FlexSlider Additions -->
<script src="/js/jquery.easing.js"></script>
<script src="/js/jquery.mousewheel.js"></script>

<script type="text/javascript">
$(window).load(function(){
  $('.flexslider').flexslider({
	animation: "slide",
	animationLoop: true,
	itemWidth: '100%',
	controlNav: true,
	slideshowSpeed: 3000,
	pauseOnHover: false
  });
});
</script>


<script language="javascript">

function emailUpdate()
{

		if($('input[name=name]').val() == ''){
			alert('이름을 입력하세요.');
			$('input[name=name]').focus();
			return false;
		}

		if($('input[name=email]').val() == ''){
			alert('이메일을 입력하세요.');
			$('input[name=email]').focus();
			return false;
		}

		if($('input[name=email2]').val() == ''){
			alert('이메일을 입력하세요.');
			$('input[name=email2]').focus();
			return false;
		}

		/*
		var email_name = $("#email_name").val();
		var email_address1 = $("#email_address1").val();
		var email_address2 = $("#email_address2").val();
		var string_value = "name="+encodeURIComponent(email_name)+"&email1="+encodeURIComponent(email_address1)+"&email2="+encodeURIComponent(email_address2); */
		
		$('#newsForm').submit();
		/*
		$.ajax({
			url: "{{ route('execemail')}}",
			type: 'POST',
			data: string_value,
			error: function(response, status, error){
				alert("{{ ERR_DATABASE }}");
				console.log(reponse);
			},
			success: function(data){
				data = data.split("@@||@@");
				var data_result = eval("("+data[1]+")");
				if(data_result.counsel_check == 'y'){
					alert("소식지 신청이 완료되었습니다.");
				}else{
					alert("소식지 신청이 실패되었습니다.");
				}
			}
		}) */

}

</script>

<section id="" style = "height:223px;">
	<div class="flexslider">
	  <ul class="slides">
		<?php
		if(count($nClover_banner->page_result) > 0){
			for($i=0, $cnt_list=count($nClover_banner->page_result); $i < $cnt_list; $i++) {
				$nClover_banner->VarList($nClover_banner->page_result, $i, null);
		?>
		<li style="position:relative;">
			<a href="{{ route('clovergarden', ['seq' => $nClover_banner->clover_id] ) }}&cate=1&dep01=0&dep02=0&type=view">
			<img src='/imgs/up_file/clover/{{ $nClover_banner->file_edit[1] }}' style="width:789px; height:223px">                  
			<img src="/imgs/go_btn.gif" style="width:129px; height:32px; position:absolute; bottom:10px; right:20px;"></a>
			<p class="main_banner_link"><a href="{{ route('clovergarden', ['seq' => $nClover_banner->clover_id] ) }}&cate=1&dep01=0&dep02=0&type=view" class="mt5 orange_big_btn resize_91_702">참여하기</a></p>			
			
		</li>
		<?php }}?>
	  </ul>
	</div>
</section>

<!-- News -->
<section class="content pt20">
	<div class="new-alert">
		<?php if(!Auth::check()){ ?>
		<header>
			<h1>클로버가든<br /><span class="c_orange">소식</span> <span>받아보기</span></h1>
		</header>

		<p>온라인 소식지는 회원가입 없이 신청이 가능합니다.<br /><span>이메일로 클로버가든의 활동 소식</span>을 받아보세요!</p>

		<div class="new-form">
			<form method="post" id="newsForm" action="{{ route('execemail') }}">
				<div class="xm_left">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="text" name="name" id="email_name" placeholder="이름" title="이름" class="in1" /><br />
					<input type="text" name="email" id="email_address1" placeholder="이메일" title="이메일" class="in2 mt5" /> <span class="c_dark_green fs14">@</span> <input type="text" name="email2" id="email_address2" title="이메일 도메인주소" class="in3 mt5" />
				</div>
				<div class="ml10">
					<input type="image" id="newsRequest" src="/imgs/NewSproposal.png" alt="소식지 선정" />
				</div>
			</form>
		</div>
		<?php } else { ?>
		<?php
			$cloverFavoreModel = new CloverFavoreModel();
			$favoreList = $cloverFavoreModel->getFavoreList();
		?>
		@if(!is_null($favoreList->name) && $favoreList->order_adm_ck == 'y')
			<p style="font-size:15px; font-weight:bold;">
				<font color="66b050">{{ $favoreList->name }}</font>님의 마지막 후원은 <font color="ed6c0a">{{ $favoreList->clover_name}}</font> 입니다. 				
			</p>
		@else
			회원님의 후원 목록이 존재하지 않습니다.
		@endif
		<?php }?>
	</div>
</section>
<?php
function conv_subject($subject, $len, $suffix='')
{
    return get_text(cut_str($subject, $len, $suffix));
}
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

function get_text($str, $html=0)
{
    /* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
    $source[] = "/  /";
    $target[] = " &nbsp;";
    */

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    $source[] = "/</";
    $target[] = "&lt;";
    $source[] = "/>/";
    $target[] = "&gt;";
    //$source[] = "/\"/";
    //$target[] = "&#034;";
    $source[] = "/\'/";
    $target[] = "&#039;";
    //$source[] = "/}/"; $target[] = "&#125;";
    if ($html) {
        $source[] = "/\n/";
        $target[] = "<br/>";
    }

    return preg_replace($source, $target, $str);
}

function cut_str($str, $len, $suffix="…")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if ($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}

?>
<section class="cont">
	<h2 class="ti">제휴업체 및 기관</h2>
	<article class="article_box">
		<header>
			<h2>함께하는 제휴업체</h2>
			<a href="{{ route('companion') }}" class="more"><img src="{{ url('imgs/Plusicon.png') }}" alt="더보기" /></a>
		</header>
		<div class="xm_clr"></div>

		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="height:100px;">
		<tr style="height:100px;">
			<td width="30" align="left" style="height:100px;"><img src="{{ url('imgs/new_images/slider_l.png') }}" id="img_width" alt="Previous" onclick="slideshow3.move(-1)" style="margin:0;" /></td>
			<td width="220" align="center" id="slider3" style="height:100px;">
			<ul style="height:100px;">
		<?php
			for($i=0, $cnt_list=count($nSCompany->page_result); $i < $cnt_list; $i++) {
				$nSCompany->VarList($nSCompany->page_result, $i, null);
		?>
				<li style="width:210px; text-align:left;margin-top:15px;margin-left:7px;">
					<a href="{{ route('companion') }}" class="img">
					<?php					
						echo "<img src='/imgs/up_file/scompany/".$nSCompany->file_edit[1]."' border='0' style='width:95px; height:69px;'>";
					?>
					</a>
					<strong>{{ conv_subject($nSCompany->subject, 10, '…') }}</strong><br />
					{{ conv_subject($nSCompany->content, 33, '…') }}
				</li>

		<?php
			}
		?>
			</ul>
			</td>
			<td align="right" style="height:100px;"><img src="{{ url('imgs/new_images/slider_r.png') }}" id="img_width" alt="Previous" onclick="slideshow3.move(1)" style="margin-left:7px;margin-right:0;" /></td>
		</tr>
		</table>




	</article>


	<article class="article_brd_box2">
		<header>
			<h2>이달의 클로버</h2>
		</header>
		<div class="xm_clr"></div>


		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="height:100px;">
		<tr style="height:100px;">
			<td width="30" align="left" style="height:100px;"><img src="{{ url('imgs/new_images/slider_l.png') }}" id="img_width" alt="Previous" onclick="slideshow.move(-1)" style="margin:0;" /></td>
			<td width="220" align="center" id="slider" style="height:100px;">
			<ul style="height:100px;">

		<?php
			for($i=0, $cnt_list=count($nMClover->page_result); $i < $cnt_list; $i++) {
				$nMClover->VarList($nMClover->page_result, $i, null);
		?>
				<li style="width:210px; text-align:left;margin-top:15px;margin-left:7px;">
					<a href="{{ route('companion', array('dep01' => 1)) }}" class="img">
					<?php					
						echo "<img src='/imgs/up_file/sponsor/".$nMClover->file_edit[1]."' border='0' style='width:95px; height:69px;'>";
					?>
					</a>
					<strong>{{ conv_subject($nMClover->subject, 10, '…') }}</strong><br />
					{{ conv_subject($nMClover->content, 30, '…') }}
				</li>

		<?php
			}
		?>


			</ul>
			</td>
			<td align="right" style="height:100px;"><img src="{{ url('imgs/new_images/slider_r.png') }}" id="img_width" alt="Previous" onclick="slideshow.move(1)" style="margin-left:7px;margin-right:0;" /></td>
		</tr>
		</table>

		<script type="text/javascript">
		<!--
		var TINY={};

		function T$(i){return document.getElementById(i)}
		function T$$(e,p){return p.getElementsByTagName(e)}

		TINY.slider=function(){
			function slide(n,p){this.n=n; this.init(p)}
			slide.prototype.init=function(p){
				var s=T$(p.id), u=this.u=T$$('ul',s)[0], c=T$$('li',u), l=c.length, i=this.l=this.c=0;
				if(p.navid&&p.activeclass){this.g=T$$('li',T$(p.navid)); this.s=p.activeclass}
				this.a=p.auto||0; this.p=p.resume||0; this.v=p.vertical||0; s.style.overflow='hidden';
				for(i;i<l;i++){if(c[i].parentNode==u){this.l++}}
				if(this.v){;
					u.style.top=0; this.h=p.height||c[0].offsetHeight; u.style.height=(this.l*this.h)+'px'
				}else{
					u.style.left=0; this.w=220; u.style.width=(this.l*this.w)+'px'
				}
				this.pos(p.position||0,this.a?1:0)
			},
			slide.prototype.auto=function(){
				this.u.ai=setInterval(new Function(this.n+'.move(1,1)'),this.a*1000)
			},
			slide.prototype.move=function(d,a){
				var n=this.c+d, i=d==1?n==this.l?0:n:n<0?this.l-1:n; this.pos(i,a)
			},
			slide.prototype.pos=function(p,a){
				clearInterval(this.u.ai); clearInterval(this.u.si);
				var o=this.v?parseInt(this.u.style.top):parseInt(this.u.style.left),
				t=this.v?p*this.h:p*this.w, d=t>Math.abs(o)?1:-1; t=t*-1; this.c=p;
				if(this.g){for(var i=0;i<this.l;i++){this.g[i].className=i==p?this.s:''}}
				this.u.si=setInterval(new Function(this.n+'.slide('+t+','+d+','+a+')'),20)
			},
			slide.prototype.slide=function(t,d,a){
				var o=this.v?parseInt(this.u.style.top):parseInt(this.u.style.left);
				if(o==t){
					clearInterval(this.u.si); if(a||(this.a&&this.p)){this.auto()}
				}else{
					var v=o-Math.ceil(Math.abs(t-o)*.15)*d+'px';
					this.v?this.u.style.top=v:this.u.style.left=v
				}
			};
			return{slide:slide}
		}();

		var slideshow3=new TINY.slider.slide('slideshow3',{
			id:'slider3',
			auto:false,
			resume:true,
			vertical:false,
			activeclass:'current',
			position:0
		});

		var slideshow=new TINY.slider.slide('slideshow',{
			id:'slider',
			auto:false,
			resume:true,
			vertical:false,
			activeclass:'current',
			position:0
		});	
		//-->
		</script>
		</article>



	<article class="article_box article_last_box">
		<div class="mt20">
			<h2 class="pt5">누적 기부 금액</h2>
			<span class="won">{{ number_format($nMoney->month) }}원</span>
			<div class="xm_clr"></div>

			<h2 class="pt15">기관 전달 금액</h2>
			<span class="mt10 won">{{ number_format($nMoney->today) }}원</span>
		</div>
	</article>
</section>
<div class="xm_clr"></div>

<section class="cont">
	<h2 class="ti">게시글</h2>
	<article class="article_brd_box">
		<header>
			<h2>타임라인</h2>
			<a href="{{ route('sponsorzone') }}?cate=&dep01=1&dep02=0" class="more"><img src="{{ url('imgs/Plusicon.png') }}" alt="더보기" /></a>
		</header>
		<div class="xm_clr"></div>

		<ul>
			<?php
			for($i=0, $cnt_list=count($nClovercomment->page_result); $i < $cnt_list; $i++) {
				$nClovercomment->VarList($nClovercomment->page_result, $i, null);
				$nClovercomment->subject = conv_subject($nClovercomment->subject, 28, ''); 
			?>
			<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=0">{{ $nClovercomment->subject }}</a></li>
			<?php
				}
			?>
		</ul>
	</article>

	<article class="article_brd_box">
		<header>
			<h2>자유게시판</h2>
			<a href="{{ route('sponsorzone') }}?cate=0&dep01=1&dep02=1" class="more"><img src="{{ url('imgs/Plusicon.png') }}" alt="더보기" /></a>
		</header>
		<div class="xm_clr"></div>

		<ul>
			<?php
			for($i=0, $cnt_list=count($nFree->page_result); $i < $cnt_list; $i++) {
				$nFree->VarList($nFree->page_result, $i, null);
				$nFree->subject = conv_subject($nFree->subject, 25, ''); 
			?>
			<li><a href="{{ route('sponsorzone') }}/?cate=0&dep01=1&dep02=1&type=view&seq={{ $nFree->seq }}">{{ $nFree->subject }}</a></li>
			<?php
				}
			?>
		</ul>
	</article>
	<script type="text/javascript" src="{{ url('js/new_js/jquery.newsticker.js') }}"></script>
	<script type="text/javascript">
	$(function(){

	//#############세번째 샘플
			$('#newsticker3').Vnewsticker({
				speed: 500,         //스크롤 스피드
				pause: 3000,        //잠시 대기 시간
				mousePause: true,   //마우스 오버시 일시정지(true=일시정지)
				showItems: 5,       //스크롤 목록 갯수 지정(1=한줄만 보임)
				direction : "up"    //up=위로스크롤, 공란=아래로 스크롤
		});

	});
	</script>
	<article class="article_brd_box article_last_box">
		<header>
			<h2>봉사스케쥴</h2>
			<a href="{{ route('sponsorzone') }}?cate=0&dep01=2&dep02=0" class="more"><img src="{{ url('imgs/Plusicon.png') }}" alt="더보기" /></a>
		</header>
		<div class="xm_clr"></div>
		<div style="height:10px;"></div>
		<div id="newsticker3" style="line-height:200%;">
		<ul>
			<?php
			if(count($nSchedule->page_result) > 0){
				for($i=0, $cnt_list=count($nSchedule->page_result); $i < $cnt_list; $i++) {
					$nSchedule->VarList($nSchedule->page_result, $i, null);
					$nSchedule->subject = conv_subject($nSchedule->subject, 28, ''); 

					?>
					<li><a href="{{ route('sponsorzone') }}?cate=0&dep01=2&dep02=0">{{ $nSchedule->subject }}</a></li>
					<?php
					
				
				}
				$nSchedule->ArrClear();
			}
			?>

		</ul>
		<div>
	</article>
	</section>
	<div class="xm_clr"></div>

	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.0";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
	<section class="cont">
		<h2 class="ti">페이스북 참여</h2>
		<div class="facebook_box">
			<aside class="fb">
				<a href="#" class="img mt_3"><img src="{{ url('imgs/Clover.png') }}" alt="" /></a>
				<p class="nanum fs16 c_dark_blue t_bold  mt_3">클로버가든을 페이스북으로 만나보세요</p>
			</aside>
			<aside class="fb last">
				<div class="fb-like" data-href="http://clovergarden.co.kr" data-width="500" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
			</aside>
		</div>
	</section>
	<div class="xm_clr"></div>

	<section class="cont" style="margin-top:15px">
		<div class="container">
			<h3 class="nanum">{{ date("n") }}월 기부왕</h3>
				<ul class="mini_box">

				<?php
				for($i=0, $cnt_list=count($nMember_win->page_result); $i < $cnt_list; $i++) {
					$nMember_win->VarList($nMember_win->page_result, $i, null);

				?>
				<li>
					<a href="{{ route('userinfo') }}?cate=8&user_id={{ $nMember_win->user_id }}"><img src="imgs/up_file/member/{{ $nMember_win->file_edit[1] }}" onerror="this.src='imgs/photo05.png'" style="height:51px; width:51px;"></a>
					<div>{{ $nMember_win->group_name }}<br /><a href="{{ route('userinfo') }}?cate=8&user_id={{ $nMember_win->user_id }}">{{ $nMember_win->user_name }} 님</a></div>
				</li>
				<?php
					}
				?>
				
			</ul>
		</div>
	</section>
	<div class="xm_clr"></div>

	<script type="text/javascript">
	(function($) {
		$(function() {

			// Partner Slide
			$("#partner").simplyScroll();

			// placeholder
			$('input, textarea').placeholder();


		});
	})(jQuery);
	</script>

@stop