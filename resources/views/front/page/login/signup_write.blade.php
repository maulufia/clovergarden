@extends('front.page.login')

@section('login')
<?php
$nMessage = new MessageClass(); //쪽지
//======================== DB Module Start ============================
$Conn = new DBClass();

$group_mode = isset($_POST['group_mode']) ? $_POST['group_mode'] : null;
if($group_mode == "send_group_make"){
	$nMessage->send_id    = "no_member@clovergarden.co.kr";
	$nMessage->receive_id    = "master@clovergarden.co.kr";
	$S_content = "
	단체/그룹명 : ".$_POST['group_name']."<BR>
	이름 : ".$_POST['name']."<BR>
	연락처 : ".$_POST['tel']."<BR>
	소속원 수 : ".$_POST['group_number']."<BR>
	단체 홈페이지 주소 : ".$_POST['group_homepage']."<BR>
	내용 : ".$_POST['contents']."
	";

	$nMessage->content    = $S_content; // 클로버아이디

	$arr_field = array
	(
		'send_id', 'receive_id', 'content'
	);


	$arr_value = array($nMessage->send_id, $nMessage->receive_id, $nMessage->content);

	//======================== DB Module Start ============================

	$Conn->StartTrans();

	$out_put = $Conn->InsertDB($nMessage->table_name, $arr_field, $arr_value);

	if($out_put){
		$Conn->CommitTrans();
	} else {
		$Conn->RollbackTrans();
		$Conn->disConnect();
	}
	//======================== DB Module End ===============================
}

$Conn->DisConnect();

?>

<script language=javascript>
function Display(){
	if($('input#per_supporter').is(":checked") == true){
		$('#person1').show();
		$('#person2').show();
		$('#group').hide();
		$('#company').hide();
	}else if($('input#gro_receive').is(":checked") == true){
		$('#person1').hide();
		$('#person2').hide();
		$('#group').show();
		$('#company').hide();
	}else if($('input#com_supporter').is(":checked") == true){
		$('#person1').hide();
		$('#person2').hide();
		$('#group').hide();
		$('#company').show();
	}
}
</script>


<!-- 팝업 -->
<div id="popup_new" class="new_group_popup">		<!-- 팝업이 자동으로 열리지 않게 하려면 open 클래스를 삭제하시면 됩니다. -->
<div class="bg"></div>
<div id="new_group">
	<a href="#" class="close xm_right mt10 mr10" title="쪽지 레이어 닫기"><img src="/imgs/Closeicon.png" alt="팝업창 닫기"></a>
	<div class="new_group_cont">
		<form method="post" id="wrtForm" action="{{ route('login', array('cate' => 5, 'dep01' => 4)) }}">
			<input type="hidden" name="group_mode" value="send_group_make">
			<input type="hidden" name="cate" value="{{ $cate }}">
			<input type="hidden" name="dep01" value="{{ $dep01 }}">

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
						<td><input type="text" name="group_name" value="{{ $group_name }}"></td>
					</tr>
					<tr >
						<th scope="row">이름</th>
						<td><input type="text" name="name" value="{{ $login_name }}"></td>
					</tr>
					<tr >
						<th scope="row">연락처</th>
						<td><input type="text" name="tel" value="{{ $login_cell }}"></td>
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
				
				
				<div class="box2"><a href="#" id="save2" class="orange_big_btn">보내기</a></div>
				<div class="mt10 t_bold xm_tcenter c_orange">* 기업 및 대규모 단체는 대표전화 (02-735-1963)으로 부탁드립니다.</div>
			</fieldset>
		</form>
	</div>
</div>
</div>
<!-- //팝업 -->

<script type="text/javascript">
(function($) {
    $(function() {
        // 그룹 만들기
        $( "#save2" ).click(function() {

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
		<h2>회원 기본정보</h2>
	</header>
	<article class="join">
		<h2 class="ti">회원정보</h2>
		<form method="post" id="joinForm" name="joinForm" action="{{ route('signup', array('cate' => 5, 'dep01' => 3, 'type' => 'write')) }}" style="display:inline;"  enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		
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
						<h3>회원등급</h3>
					</td>
				</tr>
				<tr>
					<th colspan="2" scope="row">
						<div class="xm_left radioForm">
							<input type="radio" name="user_state" id="per_supporter" onchange="javascript:Display();" value="2" checked>
							<label for="per_supporter" class="mr20">개인</label>
							<!-- <input type="radio" name="user_state" id="gro_receive" onchange="javascript:Display();" value="3">
							<label for="gro_receive" class="mr20">기관담당자</label> -->
							<input type="radio" name="user_state" id="com_supporter" onchange="javascript:Display();" value="4">
							<label for="com_supporter">기업담당자</label>
						</div>							    
					</th>
				</tr>
				<tr> 
					<td colspan="2">
						<h3 class="pt30">소속정보</h3>
						<p id="person1">
							단체(기업,동아리,단체)로 활동하시고자 하는 회원분은 단체명 조회를 통해<br>소속을 입력하세요. (소속이 없는 개인회원일 경우, 입력하지 않으셔도 됩니다.)
						</p>
					</td>
				</tr>
				<tr id="person2"> 
					<th scope="row">
						<div class="styled-select" style="margin-left:0;">
							<select id="selectGroup" id="check_group_state" name="group_state" onchange="if(this.value == 4){$('#group_name1').show();$('#group_name_bt').show();}else{$('#group_name1').hide();$('#group_name_bt').hide();}">
							  <option value="3">개인</option>
							  <option value="4">기업</option>
							</select>
						</div>
					</th>
					<td><input type="text" name="group_name1" id="group_name1" style="display:none;"> <a href="javascript:check_group_popup('show');" id="group_name_bt" style="display:none;" class="green_btn resize">조회</a></td>
				</tr>

				<tr id="group" style="display:none;"> 
					<th scope="row">단체명</th>
					<td><input type="text" name="group_name2" id="group_name2" placeholder="ex) 동아리"> <a href="#" onclick="javascript:check_group(2,3)" class="green_btn resize">중복확인</a></td>
					<input type="hidden" id="mem_group_check_result2" name="mem_group_check_result2" value="n">
				</tr>

				<tr id="company" style="display:none;"> 
					<th scope="row">기업명</th>
					<td><input type="text" name="group_name3" id="group_name3" placeholder="ex) 삼성전자"> <a href="#" onclick="javascript:check_group(3,4)" class="green_btn resize">중복확인</a></td>
					<input type="hidden" id="mem_group_check_result3" name="mem_group_check_result3" value="n">
				</tr>
				

				<tr >
					<td colspan="2">
						<h3 class="pt30">기본정보</h3>
					</td>
				</tr>
				<tr style="display:none;">
					<th scope="row">회원타입</th>
					<td>
						<div class="xm_left radioForm">
							<input type="radio" name="member_t" id="member_t1" value="D" checked>
							<label for="member_t1" class="mr20">일시후원</label>
							<input type="radio" name="member_t" id="member_t2" value="M">
							<label for="member_t2" class="mr20">정기후원</label>
							<input type="radio" name="member_t" id="member_t3" value="MD">
							<label for="member_t3">일시+정기후원</label>
						</div>						
					</td>
				</tr>

				<tr >
					<th scope="row">이름</th>
					<td><input type="text" name="user_name" placeholder="ex) 홍길동"></td>
				</tr>
				<tr >
					<th scope="row">이메일(ID)</th>
					<td>
						<input type="text" name="user_id" placeholder="abcdefg@abc.com" id="user_id"> <a href="#" onclick="javascript:check_id()" class="green_btn resize">중복확인</a>
						<input type="hidden" id="mem_id_check_result" name="mem_id_check_result" value="n">
					</td>
				</tr>
				<tr >
					<th scope="row">비밀번호</th>
					<td><input type="password" name="user_pw" placeholder="**********"></td>
				</tr>
				<tr >
					<th scope="row">비밀번호 확인</th>
					<td><input type="password" name="user_repw" placeholder="**********"></td>
				</tr>
				<tr >
					<th scope="row">성별</th>
					<td>
						<div id="radioForm" class="h200">
							<input type="radio" name="user_gender" id="M" value="M">
							<label for="M" class="mr20">남자</label>
							<input type="radio" name="user_gender" id="F" value="F">
							<label for="F">여자</label>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row">생년월일</th>
					<td>
						<input type="number" name="user_birth" placeholder="ex) 19850319" class="onlyNumber">
					</td>
				</tr>

				<tr >
					<th scope="row">주소</th>
					<td style="line-height:300%;">
						<input type="text" name="post1" id="post1" placeholder="" style="width:50px;"> - 
						<input type="text" name="post2" id="post2" placeholder="" style="width:50px;" > 
						<a onclick="javascript:openDaumPostcode()" class="green_btn resize">주소검색</a>
						<BR>
						<input type="text" name="addr1" id="addr1" placeholder="" style="width:200px;"> 
						<input type="text" name="addr2" id="addr2" placeholder="" style="width:200px;"> 
					</td>
				</tr>

				<tr >
					<th scope="row">휴대전화번호</th>
					<td>
						<?php
						$rand_num = rand(153487, 999999);
						//echo $rand_num;
						?>

						<input type="number" name="user_cell" placeholder="ex) 01012341234" class="onlyNumber"> 
						<a href="#" onclick="javascript:sms_ck_func('{{ $rand_num }}')" class="green_btn resize">인증번호요청</a>
					</td>
				</tr>
				<tr >
					<th scope="row">인증번호입력</th>
					<td>
						<input type="number" name="user_sms" class="onlyNumber"> 
					</td>
				</tr>
						<input type="hidden" name="ck_search"> 
						<input type="hidden" name="ck_email_search"> 
			</table>
		 </div>
		 {{-- UserHelper::SubmitHidden() --}}
		<div class="box2"><a href="#" id="save" class="orange_big_btn">가입하기</a></div>
		</form>
	</article>
</section>
<script type="text/javascript">
<!--
function sms_ck_func(rand_n){
	var phone_v = $("input[name=user_cell]").val();
	if (phone_v == "")
	{
		alert('휴대폰 번호를 입력하세요.');
		return;
	}

	$.ajax({
		type: "POST",
		url: "{{ route('check_sns') }}",
		data:  {m_phone:phone_v, msg_n:rand_n, action:'go', _token:'{{ csrf_token() }}' },
		cache: false,
		async: false,
		success: function(data) {
			$("#group_result").html(data);
		},
		error: function(response, error, status) {
			console.log(response);
		}
	});
}
//-->
</script>
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

<script type="text/javascript">
(function($) {
    $(function() {
        $("#partner").simplyScroll();
        
		// 숫자만 입력
	    $(".join").on("keyup", ".onlyNumber", function() {
			$(this).val( $(this).val().replace(/[^0-9]/gi,"") );
		});

	    // placeholder
        $('input, textarea').placeholder();
        
        // 회원가입
        $( "#save" ).click(function() {

            /*if($("#selectGroup option:selected").val() == '') {
            	alert('소속을 선택하세요.');
                $('#selectGroup').focus();
                return false;
            }
			*/

			if($("#selectGroup option:selected").val() == 4){
				if($('input[name=ck_search]').val() == ''){
					alert('소속정보를 먼저 조회해주세요.');
					$('input[name=group_name1]').focus();
					return false;
				}
			} else {
				if($('input[name=ck_search]').val() == ''){
					$('input[name=group_name1]').val('');
				}
			}

			if($('input[name=ck_email_search]').val() == ''){
				alert('이메일을 조회해주세요.');
				$('input[name=user_email]').focus();
				return false;
			}
            if($('input[name=user_name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=user_name]').focus();
                return false;
            }

            if($('input[name=user_email]').val() == ''){
                alert('이메일을 입력하세요.');
                $('input[name=user_email]').focus();
                return false;
            }

            if( $('input[name=user_pw]').val() == '') {
            	alert('비밀번호를 입력하세요.');
                $('input[name=user_pw]').focus();
                return false;
            }

            if( $('input[name=user_repw]').val() == '') {
            	alert('비밀번호를 입력하세요.');
                $('input[name=user_repw]').focus();
                return false;
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

			if($('input[name=user_sms]').val() == ''){
                alert('인증번호를 입력하세요.');
                $('input[name=user_sms]').focus();
                return false;
            }
			if($('input[name=user_sms]').val() != '{{ $rand_num }}'){
                alert('인증번호를 확인해주세요.');
                $('input[name=user_sms]').focus();
                return false;
            }

            $( "#joinForm" ).submit();

        });
    });
})(jQuery);



</script>


<script>
function check_group_search2(){
	var group_name =  $("#group_find_str").val();
	$.ajax({
		type: "GET",
		url: "{{ route('checkgroupname') }}",
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
		type: "GET",
		url: "{{ route('checkgroupname') }}",
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
	$("input[name=ck_search]").val('ok');
	check_group_popup('hide');
}

function check_id(){
	$.ajax({
		url: '{{ route("check_id")}}',
		type: 'GET',
		data:  {user_id:encodeURIComponent($("#user_id").val())},
		error: function(){
			alert("예상치않은 오류가발생하였습니다.");
			$("#mem_id_check_result").val("n");
			return false;
		},
		success: function(data){
			var data_result = eval("("+data+")");
			if(data_result.mem_id_check == "n"){
				alert("아이디를 입력하세요.");
				$("#mem_id_check_result").val("n");
			}else if(data_result.mem_id_check == "m"){
				alert("사용 불가능한 아이디 입니다.");
				$("#mem_id_check_result").val("n");
			}else if(data_result.mem_id_check == "y"){
				alert("사용 가능한 아이디 입니다.");
				$("#mem_id_check_result").val("y");
				$("input[name=ck_email_search]").val('ok');
			}
		}
	});
}

function check_group(num, state){
	$.ajax({
		url: '{{ route("checkgroup") }}',
		type: 'GET',
		data:  {group_name:encodeURIComponent($("#group_name"+num).val()), group_state:state},
		error: function(){
			alert("예상치않은 오류가발생하였습니다.");
			$("#mem_group_check_result"+num).val("n");
			return false;
		},
		success: function(data){
			var data_result = eval("("+data+")");
			if(data_result.mem_group_check == "n"){
				alert("그룹명을 입력하세요.");
				$("#mem_group_check_result"+num).val("n");
			}else if(data_result.mem_group_check == "m"){
				alert("사용 불가능한 그룹명 입니다.");
				$("#mem_group_check_result"+num).val("n");
			}else if(data_result.mem_group_check == "y"){
				alert("사용 가능한 그룹명 입니다.");
				$("#mem_group_check_result"+num).val("y");
			}
		}
	});
}
</script>
@stop