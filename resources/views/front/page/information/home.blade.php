@extends('front.page.information')

@section('information')
<?php
$page_key   = 'I2';

$nAdm = new AdmClass(); //
$nAdm_2 = new AdmClass(); //
//======================== DB Module Start ============================
$Conn = new DBClass();

$nAdm->page_result = $Conn->AllList
(	
	$nAdm->table_name, $nAdm, "*", "where t_name='use_com' order by idx desc limit 1", null, null
);

$Conn->DisConnect();

?>

<section class="wrap">
	<header>
		<h2 class="ti">이용안내 - 개인</h2>
	</header>
	<div style="float:right; height:60px;margin-top:10px;" >
	<?php if(Auth::check()){ ?>
		<a href="#" id="pop_view" class="orange_big_btn">그룹생성 하러가기</a>
	<?php } else { ?>
		<a href="{{ route('login', array('flash' => 'need_login')) }}" class="orange_big_btn">그룹생성 하러가기</a>
	<?php } ?>
	</div>

	<?php
		for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
			$nAdm->VarList($nAdm->page_result, $i, null);
	?>
	<div class="mt30">
	<img src="/imgs/page/{{ $nAdm->t_text }}" width="790">
	</div>
	<?php
		}
	?>

</section>


<!-- 팝업 -->
@if(Auth::check())
<div id="popup_new" class="new_group_popup">		<!-- 팝업이 자동으로 열리지 않게 하려면 open 클래스를 삭제하시면 됩니다. -->
<div class="bg"></div>
<div id="new_group">
	<a href="#" class="close xm_right mt10 mr10" title="쪽지 레이어 닫기"><img src="/imgs/Closeicon.png" alt="팝업창 닫기"></a>
	<div class="new_group_cont">
		<form method="post" id="wrtForm" action="{{ route('msg_groupcreate') }}">
			<input type="hidden" name="group_mode" value="send_group_make">
			<input type="hidden" name="cate" value="{{ $cate }}">
			<input type="hidden" name="dep01" value="{{ $dep01 }}">
			<input type="hidden" name="dep02" value="{{ $dep02 }}">
			<input type="hidden" name="type" value="{{ isset($_GET['type']) ? $_GET['type'] : null }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<fieldset>
				<legend>새 단체 / 그룹 만들기</legend>
				
				<h3 class="nanum">새 단체 / 그룹 만들기</h3>
				<table class="group_table">
					<caption>새 단체 / 그룹 만들기</caption>
					<colgroup>
						<col>
						<col>
					</colgroup>
					<tr >
						<th scope="row">단체/그룹명</th>
						<td><input type="text" name="group_name" value="{{ Auth::user()->group_name }}"></td>
					</tr>
					<tr >
						<th scope="row">이름</th>
						<td><input type="text" name="name" value="{{ Auth::user()->user_name }}"></td>
					</tr>
					<tr >
						<th scope="row">연락처</th>
						<td><input type="text" name="tel" value="{{ Auth::user()->user_cell }}"></td>
					</tr>
					<tr >
						<th scope="row">소속원 수</th>
						<td><input type="text" name="group_number"></td>
					</tr>
					<tr >
						<th scope="row">단체 홈페이지 주소</th>
						<td><input type="text" name="group_homepage"></td>
					</tr>
					<tr >
						<th scope="row" class="xm_vtop pt15">내용</th>
						<td><textarea id="contents" name="contents"></textarea></td>
					</tr>
				</table>
				
				
				<div class="box2"><a href="#" id="save" class="orange_big_btn">보내기</a></div>
				<div class="mt10 t_bold xm_tcenter c_orange">* 기업 및 대규모 단체는 대표전화 (02-735-1963)으로 부탁드립니다.</div>
			</fieldset>
		</form>
	</div>
</div>
</div>
<!-- //팝업 -->
@endif

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();

        // 그룹 만들기
        $( "#save" ).click(function() {

            if($('input[name=group_name]').val() == ''){
                alert('단체/그룹명을 입력하세요.');
                $('input[name=group_name]').focus();
                return false;
            }

             if($('input[name=name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=name]').focus();
                return false;
            }

            if($('textarea#contents').val() == ''){
                alert('내용을 입력하세요.');
                $('#contents').focus();
                return false;
            }
			
            $( "#wrtForm" ).submit();

        });


        // 팝업 닫기
        $('#new_group .close').click(function(){
			$( "#popup_new" ).hide();
		});

        $('#pop_view').click(function(){
			$( "#popup_new" ).show();
		});

    });
})(jQuery);
</script>
@stop