@extends('front.common.husk')

@include('front.common.sidebar')

@section('content')

<!-- Wrapper -->
<div id="wrapper">
	<div class="menu_position"><a href="#">Home</a> &gt; {{ $cate_name }} &gt; <strong>{{ $cate_01_result[$dep01] }}</strong></div>
	
	@yield('wrapper')
	
</div>
<!-- Wrapper -->

<!-- 팝업 -->
<div class="new_group_popup"></div>
<!-- //팝업 -->

<script type="text/javascript">
	(function($) {
		$(function() {
			$("#partner").simplyScroll();
		});
	})(jQuery);
	function popup(html){
		$( ".new_group_popup" ).addClass('open');
		$( ".new_group_popup" ).load('/page/{{ $cate_file }}/'+html+'.php');
	}
</script>

</div> <!-- /content -->

@stop