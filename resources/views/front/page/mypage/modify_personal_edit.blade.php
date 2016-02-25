@extends('front.page.mypage')

@section('mypage')
<?php
$nMember = new MemberClass(); //회원

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nMember->where = "where user_id ='" . Auth::user()->user_id . "'";
    $nMember->read_result = $Conn->AllList
    (
        $nMember->table_name, $nMember, "*", $nMember->where, null, null
    );

    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
?>
<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script>
<script type="text/javascript" charset="UTF-8" src="http://s1.daumcdn.net/svc/attach/U03/cssjs/postcode/1429776925384/150423a.js"></script>

<script>    
function openDaumPostcode() {        
	new daum.Postcode({            
		oncomplete: function(data) {     
			//document.getElementById('member_addres').value = data.address;

			//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
			//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
			var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
			document.getElementById('post1').value = data.postcode1;
			document.getElementById('post2').value = data.postcode2;
			document.getElementById('addr1').value = addr;
			document.getElementById('addr2').focus();

		}       
	}).open();    
}
</script>
<section class="wrap">
	<header>
		<h2>개인정보수정</h2>
	</header>
	<article class="join">
		<h2 class="ti">회원정보</h2>
		<form method="post" id="joinForm" name="joinForm" action="{{ route('mypage', array('cate' => 6, 'dep01' => 5, 'dep02' => 0, 'type' => 'edit')) }}" style="display:inline;"  enctype="multipart/form-data">
		<div class="join_wrap">
			<table>
				<caption>회원가입</caption>
				<colgroup>
					<col>
					<col>
				</colgroup>

				<tr >
					<td colspan="2" align="right">
						<!-- <a href="#" id="pop_view" class="orange_big_btn">그룹생성 하러가기</a> -->
						* 기업 정보 수정과 관련한 사항은 1:1 문의 및 대표번호 (02-720-3235) 로 문의 부탁드립니다.
					</td>
				</tr>
				<tr >
					<td colspan="2">
						<h3 class="pt30">기본정보</h3>
					</td>
				</tr>
				<tr >
					<th scope="row">이름</th>
					<td><input type="text" name="user_name" placeholder="ex) 홍길동" class="w420" value="{{ $nMember->user_name }}"></td>
				</tr>
				<tr >
					<th scope="row">프로필사진 및 <BR> 기업로고이미지</th>
					<td>
						<div class="xm_left t_bold mt5 c_dark_gray fs14" id="file_name">
							<?php
								if(!empty($nMember->file_real[1])){
									echo $nMember->file_real[1];
								} else {
									echo '파일없음';
								}
							?>
						</div>
						<div class="xm_left ml10 upload">
							<input type="file" name="upfile1" onchange="change_file(this);"/>						
						</div>
						<div class="checkbox" style="line-height: 33px; margin-left: 15px; float: left;">
							@if(!empty($nMember->file_edit[1]))
								<input type='checkbox' id='check' name='check_del1' value='1' style='width:17px;border:0px;'/><label for='check'>삭제</label>
							@endif
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row">이메일(ID)</th>
					<td class="c_dark_gray t_bold fs14">{{ $nMember->user_id }}</td>
				</tr>
				<tr >
					<th scope="row">비밀번호</th>
					<td><input type="password" name="user_pw" placeholder="**********" class="w420"></td>
				</tr>
				<tr >
					<th scope="row">비밀번호 확인</th>
					<td><input type="password" name="user_repw" placeholder="**********" class="w420"></td>
				</tr>
				<tr >
					<th scope="row">성별</th>
					<td>
						<div id="radioForm" class="h200">
							<input type="radio" name="user_gender" id="M" value="M" <?php if($nMember->user_gender=='M') echo 'checked' ?>>
							<label for="M" class="mr20">남자</label>
							<input type="radio" name="user_gender" id="F" value="F" <?php if($nMember->user_gender=='F') echo 'checked' ?>>
							<label for="F">여자</label>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row">생년월일</th>
					<td>
						<input type="text" name="user_birth" placeholder="ex) 19850319" class="w420 onlyNumber" value="{{ $nMember->user_birth }}"> 
					</td>
				</tr>
				<tr >
					<th scope="row">주소</th>
					<td style="line-height:300%;">
						<input type="text" name="post1" id="post1" value="{{ $nMember->post1 }}" style="width:50px;"> - 
						<input type="text" name="post2" id="post2" value="{{ $nMember->post2 }}" style="width:50px;" > 
						<a onclick="javascript:openDaumPostcode()" class="green_btn resize">주소검색</a>
						<BR>
						<input type="text" name="addr1" id="addr1" value="{{ $nMember->addr1 }}" style="width:200px;"> 
						<input type="text" name="addr2" id="addr2" value="{{ $nMember->addr2 }}" style="width:200px;"> 
					</td>
				</tr>
				<tr >
					<th scope="row">휴대전화번호</th>
					<td>
						<input type="text" name="user_cell" placeholder="ex) 01012341234" class="w420 onlyNumber" value="{{ $nMember->user_cell }}"> 
					</td>
				</tr>
			</table>
		 </div>
		 {{ UserHelper::SubmitHidden() }}
		<input type="hidden" name="file_real1" value="{{ $nMember->file_real[1] }}"/>
		<input type="hidden" name="file_edit1" value="{{ $nMember->file_edit[1] }}"/>
		<input type="hidden" name="file_byte1" value="{{ $nMember->file_byte[1] }}"/>
		<div class="box2">
		<a href="#" id="save" class="orange_big_btn">수정</a> 
		<a href="javascript:if(confirm('회원 탈퇴입니다. 정기 후원 해지는 클로버가든 대표전화로 해지하셔야 합니다.')){ window.location='{{ route('userdrop', array('cate' => 6, 'dep01' => 5, 'dep02' => 0, 'type' => 'edit', 'mseq' => $nMember->seq)) }}'; }" class="gray_big_btn2">회원탈퇴</a>
		</div>
		</form>
	</article>
</section>

<div class="mem_find_popup open" style = "display:none;" id = "search_result">
	<div class="bg"></div>
	<div id="member_find" class="mFind small">
		<a href="javascript:check_group_popup('hide');" class="close xm_right mt10 mr10" title="쪽지 레이어 닫기"><img src="/imgs/Closeicon.png" alt="팝업창 닫기"></a>
		<div class="find_cont">
			<form method="post" onsubmit = "check_group_search(); return false;">
				<fieldset>
					<legend>찾기</legend>					
					<input id="group_find_str" type="text" value=""><a href="javascript:check_group_search2();" class="green_btn ml10">찾기</a>					
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

function change_file(id){
  var id_val = $(id).val();
	if(id_val != null){
		$('#file_name').html($(id).val());
	}else{
		$('#file_name').html('파일없음');
	}
}
function check_group_search2(){
	var group_name =  $("#group_find_str").val();
	$.ajax({
		type: "POST",
		url: "/page/member/check_group_name.php",
		data:  {group_state:encodeURIComponent($("select[name=group_state]").val()),group_name:group_name},
		cache: false,
		async: false,
		success: function(data) {
			$("#group_result").html(data);
		}
	});
}

function check_group_search(){
	var group_name = $("input[name=group_name1]").val();

	if (group_name==null)
	{
		group_name =  $("#group_find_str").val();
	}
	$.ajax({
		type: "POST",
		url: "/page/member/check_group_name.php",
		data:  {group_state:encodeURIComponent($("select[name=group_state]").val()),group_name:group_name},
		cache: false,
		async: false,
		success: function(data) {
			$("#group_result").html(data);
		}
	});
}
function check_group_popup(type)
{
	if(type=='hide'){
		$("#search_result").hide();
	}else if(type=='show'){
		if($('#group_name1').val() == ''){
			alert('소속정보를 입력해주세요');
			return;
		}
		$('#group_find_str').val($('#group_name1').val());
		check_group_search();
		
		$("#search_result").show();
	}
}



function set_group(group_name)
{
	document.getElementById("group_name1").value = group_name;
	$('#group_name1').attr("readonly",true);
	check_group_popup('hide');
}

</script>


<script type="text/javascript">
(function($) {
    $(function() {
		
		// 숫자만 입력
	    $(".join_wrap").on("keyup", ".onlyNumber", function() {
			$(this).val( $(this).val().replace(/[^0-9]/gi,"") );
		});

        // 회원가입
        $( "#save" ).click(function() {

            if($('input[name=user_name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=user_name]').focus();
                return false;
            }

            if( $('input[name=user_pw]').val() != '') {
				if( $('input[name=user_repw]').val() == '') {
					alert('비밀번호를 입력하세요.');
					$('input[name=user_repw]').focus();
					return false;
				}
            }

			if( $('input[name=user_repw]').val() != '') {
				if( $('input[name=user_pw]').val() == '') {
					alert('비밀번호를 입력하세요.');
					$('input[name=user_pw]').focus();
					return false;
				}
            }


			if ( $('input[name=user_pw]').val() != $('input[name=user_repw]').val() ) {
				alert("입력하신 비밀번호가 일치하지 않습니다.");
				$('input[name=user_repw]').focus();
				return;
			}

            if ( $("input:radio[name='user_gender']").is(":checked") == false ) { 
				alert("성별을 체크해 주세요"); 
				$("input:radio[name='user_gender']").focus();
				return false;
			}

			if($('input[name=user_birth]').val() == ''){
                alert('생년월일을 입력하세요.');
                $('input[name=user_birth]').focus();
                return false;
            }

			if($('input[name=user_cell]').val() == ''){
                alert('휴대전화번호를 입력하세요.');
                $('input[name=user_cell]').focus();
                return false;
            }

            $( "#joinForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop