@extends('front.page.mypage')

@section('mypage')
<section class="wrap">
	<header>
		<h2><a href="{{ route('mypage', array('cate' => 6, 'dep01' => 0)) }}">회원쪽지 보내기</a> | <a href="{{ route('mypage', array('cate' => 6, 'dep01' => 0, 'send_mode' => 'more')) }}">다수 쪽지 보내기</a></h2>
	</header>
	<article class="brd_write">
		<h2 class="ti">제목/내용</h2>
		<form method="post" id="wrtForm" action="{{ route('msg_send') }}" style="display:inline;"  enctype="multipart/form-data">
		<table>
			<caption>회원쪽지 보내기</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<?php
				$send_mode = isset($_GET['send_mode']) ? $_GET['send_mode'] : null;
				if($send_mode == "more"){
			?>
			<input type="hidden" name="more_mode" value="more">
			<tr >
				<th scope="row" class="first">받는사람1</th>
				<td>
					<input type="hidden" name="send_id" value="{{ Auth::user()->user_id }},{{ Auth::user()->user_name }}">
					<input type="text" name="receive_id[]" id="receive_id1" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id1');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">받는사람2</th>
				<td>
					<input type="text" name="receive_id[]" id="receive_id2" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id2');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">받는사람3</th>
				<td>
					<input type="text" name="receive_id[]" id="receive_id3" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id3');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">받는사람4</th>
				<td>
					<input type="text" name="receive_id[]" id="receive_id4" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id4');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">받는사람5</th>
				<td>
					<input type="text" name="receive_id[]" id="receive_id5" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id5');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<?php } else { ?>
			<tr >
				<th scope="row" class="first">받는사람</th>
				<td>
					<input type="hidden" name="send_id" value="{{ Auth::user()->user_id }},{{ Auth::user()->user_name }}">
					<input type="text" name="receive_id" id="receive_id" class="w460 receive" readonly="readonly">
					<a href="javascript:check_group_popup('show','receive_id');" class="green_btn resize ml10">찾기</a>		
				</td>
			</tr>
			<?php } ?>
			<tr >
				<th scope="row">쪽지내용</th>
				<td><textarea name="content" id="content" placeholder="쪽지내용을 입력해주세요."></textarea></td>
			</tr>
		</table>
		<div class="box2"><a href="javascript:document.getElementById('wrtForm').submit();" id="save" class="orange_big_btn">보내기</a></div>
		{{ UserHelper::SubmitHidden() }}
		</form>
	</article>
</section>
<div class="mem_find_popup open" style = "display:none;" id = "search_result">
	<div class="bg"></div>
	<div id="member_find" class="mFind small">
		<a href="javascript:check_group_popup('hide');" class="close xm_right mt10 mr10" title="쪽지 레이어 닫기"><img src="/imgs/Closeicon.png" alt="팝업창 닫기"></a>
		<div class="find_cont">
			<form method="post" onsubmit = "check_group_search(); return false;">
				<input id="group_get_str" name="user_get_str" type="hidden" value="">
				<fieldset>
					<legend>찾기</legend>					
					<input id="group_find_str" name="user_id_str" type="text" value=""><a href="javascript:check_group_search();" class="green_btn ml10">찾기</a>					
					<h3 class="mb0">검색결과</h3>
					<ul class="mem_result" style = "height:260px;" id = "group_result">
						<p align ="center">검색결과가 없습니다.</p>
					</ul>					
				</fieldset>
			</form>
		</div>
	</div>
</div>

<script>

function check_group_search(get_id){

	$.ajax({
		type: "GET",
		//url: "/page/mypage/check_member.php?getck="+$("input[name=user_get_str]").val()+"&user_id="+$("input[name=user_id_str]").val(),
		url: "{{ route('mypage/checkmember') }}?getck=" + $("input[name=user_get_str]").val() + "&user_id=" + $("input[name=user_id_str]").val(),
		cache: false,
		async: false,
		success: function(data) {
			$("#group_result").html(data);
		}
	});
}
function check_group_popup(type,getid)
{
	if(type=='hide'){
		$("#search_result").hide();
	}else if(type=='show'){
		$('#group_find_str').val($('#group_name1').val());
		$('#group_get_str').val(getid);
		check_group_search(getid);
		$("#search_result").show();
	}
}

function set_id(user_id, getidv)
{
	$("#"+getidv).val(user_id);
	check_group_popup('hide');
}
</script>
@stop