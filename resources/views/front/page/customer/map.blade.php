@extends('front.page.customer')

@section('customer')
<section class="wrap">
	<header>
		<h2 class="ti">찾아오시는 길</h2>
	</header>
	<article class="brd_view_title">
		<h2 class="ti">주소/대표전화/찾아오시는길</h2>
		<div class="brd_view_title_1">
			<h3>주소</h3>
			<span>서울특별시 중구 남대문로 1가 18번지 (대일빌딩 3층)</span>
		</div>

		<div class="brd_view_title_2">
			<h4>대표전화</h4>
			<span class="mr30">02-735-1963</span>

			<h4>Fax</h4>
			<span>02-720-1016</span>
		</div>

		<div class="brd_view_title_2">
			<h4>찾아오시는길</h4>
			<span>종각역(1호선) 5번 출구 또는 을지로입구(2호선) 3번 출구 (도보 3분)</span>
		</div>
	</article>

	<!-- Google Map -->
	<div id="map_canvas"></div>
</section>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>  
<script type="text/javascript">  
$(document).ready(function() {  
	var latlng = new google.maps.LatLng(37.5678912, 126.98308969999993);  
	var myOptions = {  
		  zoom : 16,  
		  center : latlng,  
		  mapTypeId : google.maps.MapTypeId.ROADMAP  
	}  
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);  
	var marker = new google.maps.Marker({  
		position : latlng,  
		icon: "img/cloverPointer.png",                                     // 아이콘 설정할 때
		title: "서울특별시 중구 남대문로 1가 18번지 (대일빌딩 3층)",       // 말풍선
		map : map  
	});  
	  
}); 
</script>
@stop