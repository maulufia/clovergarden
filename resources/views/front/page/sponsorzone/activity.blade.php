@extends('front.page.sponsorzone')

@section('sponsorzone')
<?php
$year = date('Y');
$month = date('m');
?>
<section class="wrap">
	<header>
		<h2 class="ti">봉사 스케쥴</h2>
	</header>
	<div class="schedule">


	</div>
</section>

<script type="text/javascript">	
$(function() {
	dateNumber({{ $year }},{{ $month }});
	$("#partner").simplyScroll();

	//tabs
	$( "#tabs" ).tabs();

	$('#tabs .menu').click(function(){
		$('#tabs .menu').removeClass("on");
		$(this).addClass('on');
	});
});

</script>
<script>
	function dateNumber (year,month){
		var view_link = '{{ url("/sponsorzone/calendar")}}';
		$.ajax({
			type: 'GET',
			url: view_link,
			data: { year:year , month:month, view:'{{ isset($_GET["view"]) ? $_GET["view"] : "" }}'},
			success: function (data) {
				$('.schedule').empty();
				$('.schedule').html(data);
			}
		});
	}
	$(document).ready(function(){
		dateNumber({{ $year }},{{ $month }});
	});
</script>
@stop