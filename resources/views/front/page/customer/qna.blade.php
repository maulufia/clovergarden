@extends('front.page.customer')

@section('customer')
<section class="wrap">
	<header>
		<h2>클로버가든에게 문의하기</h2>
	</header>
	<article class="brd_write">
		<h2 class="ti">제목/내용</h2>
		<form method="post" id="wrtForm" action="{{ route('customer/writeqna') }}" style="display:inline;"  enctype="multipart/form-data">
		<table>
			<caption>게시판 글쓰기</caption>
			<colgroup>
				<col class="colWidth199">
				<col class="colWidth583">
			</colgroup>
			<tr >
				<th scope="row" class="first">이름</th>
				<td><input type="text" name="name"></td>
			</tr>
			<tr >
				<th scope="row" class="first">이메일</th>
				<td>
					<div class="xm_left"><input type="text" name="email1" class="w167"> <strong class="fs14 c_light_gray_3">@</strong> <input type="text" name="email2" class="w167" readonly> </div>
					<div class="xm_left styled-select" style="margin-top:2px; margin-left:10px">
					   <select id="selectEmail">
						  <option value="">메일선택</option>
						  <option value="nate.com">nate.com</option>
						  <option value="naver.com">naver.com</option>
						  <option value="daum.net">daum.net</option>
						  <option value="gmail.com">gmail.com</option>
						  <option value="direct">직접입력</option>
					   </select>
					</div>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">연락처</th>
				<td>
					<div class="xm_left mr10 styled-select" style="width:82px; margin:2px 10px 0 0;">
					   <select name="cell1">
						  <option value="">선택</option>
						  <option value="010">010</option>
						  <option value="011">011</option>
						  <option value="016">016</option>
						  <option value="017">017</option>
						  <option value="018">018</option>
						  <option value="019">019</option>
					   </select>
					</div>
					<div class="xm_left">
						<input type="number" name="cell2" class="w97 mr10 onlyNumber"> 
						<input type="number" name="cell3" class="w97 onlyNumber"> 
					</div>
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">답변방법</th>
				<td>
					<div id="radioForm" class="h200">
						<input type="radio" name="receive" id="email_receive" value="0" checked>
						<label for="email_receive" class="mr20">이메일로 답변받음</label>
						<input type="radio" name="receive" id="tel_receive" value="1">
						<label for="tel_receive">전화로 답변받음</label>
					</div>
					<i class="fa fa-caret-right mr5"></i>전화로 답변받음을 신청하시면 고객센터에서 직접 전화로 문의사항을 상담해 드립니다.
				</td>
			</tr>
			<tr >
				<th scope="row" class="first">제목</th>
				<td><input type="text" name="subject" placeholder="제목을 입력해주세요."></td>
			</tr>
			<tr >
				<th scope="row">문의내용</th>
				<td><textarea name="content" id="contents" placeholder="내용을 입력해주세요."></textarea></td>
			</tr>
		</table>
		{{ UserHelper::SubmitHidden() }}
		<div class="box2"><a href="#" id="save" class="orange_big_btn">보내기</a></div>
		</form>
	</article>
</section>

<script type="text/javascript">
(function($) {
    $(function() {
       
		// 메일선택
        $('#selectEmail').change(function(){
	        if( $("#selectEmail option:selected").val() == 'direct') {
	        	$('input[name=email2]').removeAttr("disabled");
				$('input[name=email2]').attr("readonly",false);
	        	$('input[name=email2]').focus();
	        } else {
		        $('input[name=email2]').val($("#selectEmail option:selected").val());
		        $('input[name=email2]').attr("readonly",true);
		    }
	    });

        // 숫자만 입력
	    $('.board_write').on('keyup', '.onlyNumber', function() {
				$(this).val( $(this).val().replace(/[^0-9]/gi,"") );
			});

		// placeholder
        $('input, textarea').placeholder();

        // 글쓰기
        $( "#save" ).click(function() {

            if($('input[name=name]').val() == ''){
                alert('이름을 입력하세요.');
                $('input[name=name]').focus();
                return false;
            }

            if($('input[name=email1]').val() == ''){
                alert('이메일을 입력하세요.');
                $('input[name=email1]').focus();
                return false;
            }

            if($('input[name=email2]').val() == ''){
                alert('이메일을 입력하세요.');
                $('#selectEmail').focus();
                return false;
            }
            
            if( $('select[name=cell1]').val() == '') {
            	alert('연락처를 입력하세요.');
                $('select[name=cell1]').focus();
                return false;
            }

            if($('input[name=cell2]').val() == ''){
                alert('연락처를 입력하세요.');
                $('input[name=cell2]').focus();
                return false;
            }

            if($('input[name=cell3]').val() == ''){
                alert('연락처를 입력하세요.');
                $('input[name=cell3]').focus();
                return false;
            }

            if ( $("input:radio[name='receive']").is(":checked") == false ) { 
				alert("답변방법을 체크해 주세요"); 
				return false;
			}

            if($('input[name=subject]').val() == ''){
                alert('제목을 입력하세요.');
                $('input[name=subject]').focus();
                return false;
            }

            if($('textarea#content').val() == ''){
                alert('내용을 입력하세요.');
                $('#contents').focus();
                return false;
            }

            $( "#wrtForm" ).submit();

        });
    });
})(jQuery);
</script>
@stop