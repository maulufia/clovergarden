@extends('front.page.login')

@section('login')
<?php

$nAdm = new AdmClass(); //
$nAdm_2 = new AdmClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();

$nAdm->page_result = $Conn->AllList
(	
	$nAdm->table_name, $nAdm, "*", "where t_name='pric_v' order by idx desc limit 1", null, null
);

$Conn->DisConnect();



?>
			<section class="wrap">
	    		<header>
					<h2 class="nanum">개인정보의 수집 및 이용목적</h2>
				</header>
	    		<article class="box3">
	    			<h2 class="ti">이용약관</h2>
	    			<textarea>
<?php
	for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
		$nAdm->VarList($nAdm->page_result, $i, null);
?>
{{ $nAdm->t_text }}
<?php
	}
?>
					</textarea>
	    		</article>

	    		<div class="box2" style="line-height:300%; height:160px">
	    			<strong>본인은 아래의 내용을 확인하고, 클로버가든이 다음과 같이 본인의 개인(신용)정보를 처리(수집,이용,제공 등)하는 것에 동의합니다</strong>
	    			<form method="GET" id="radioForm" action="{{ route('login') }}">
	    				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	    				<input type="hidden" name="dep01" value="3" />
	    				<input type="hidden" name="type" value="write" />
					    <input type="radio" name="agree" id="agree1" value="Y">
					    <label for="agree1" class="mr20">동의</label>
					    <input type="radio" name="agree" id="agree2" value="N" checked>
					    <label for="agree2">동의하지 않음</label>
					    <div class="box2">
				    		<a href="#" id="join" class="orange_big_btn">가입하기</a>
				    	</div>
					</form>
	    		</div>
	    		
	    	</section>
<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 가입하기
        $( "#join" ).click(function() {

            if ( $("#agree2").is(":checked") == true ) { 
				alert("개인(신용)정보를 처리하는것에 체크해 주세요"); 
				return false;
			}

            $( "#radioForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop