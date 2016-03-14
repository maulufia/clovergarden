<?php
	$seq = isset($_GET['seq']) ? $_GET['seq'] : 0;

	$nSchedule  = new ScheduleClass(); //봉사스케쥴
	$nSchedulepeo  = new SchedulepeoClass(); //봉사스케쥴
	$nSchedulepeo_mod  = new SchedulepeoClass(); //봉사스케쥴
	//======================== DB Module Start ============================
	$Conn = new DBClass();

	$Conn->UpdateDBQuery($nSchedule->table_name, "hit = hit + 1 where seq = '".$seq."'");
	$nSchedule->where = "where seq ='".$seq."'";

	$nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);

	if(count($nSchedule->read_result) != 0){
		$nSchedule->VarList($nSchedule->read_result, 0, null);
	}else{
		$Conn->DisConnect();
	}

	$nSchedulepeo->page_result = $Conn->AllList
	(	
		$nSchedulepeo->table_name, $nSchedulepeo, "*", "where schedule_seq='".$nSchedule->seq."' order by seq desc", null, null
	);

	$nSchedulepeo->total_record =  $Conn->PageListCount($nSchedulepeo->table_name, " where schedule_seq='".$seq."' and writer = '" . Auth::user()->user_id . "' order by seq desc", $search_key, $search_val);
	
	$checkable = dateDiff(date("Y-m-d"),$nSchedule->start_date2);

	$nSchedulepeo_mod->page_result = $Conn->AllList
	(	
		//$nSchedulepeo_mod->table_name, $nSchedulepeo_mod, "*", "where schedule_seq='".$nSchedule->seq."' and writer='".$login_id."' and name != '".$login_name."' and phone != '".$login_cell."' order by seq desc", null, null
		$nSchedulepeo_mod->table_name, $nSchedulepeo_mod, "*", "where schedule_seq='".$nSchedule->seq."' and writer='" . Auth::user()->user_id . "' order by seq desc", null, null
	);		

	$Conn->DisConnect();
	//======================== DB Module End ===============================

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

    return $str;
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




<style>
 .editor img { max-width: 461px!important;  height: auto!important;}
</style>
	<table class="xm_left detail mt20">
		<caption>봉사 스케쥴</caption>
		<colgroup>
			<col>
			<col>
		</colgroup>
		<tr>
			<th scope="row">제목</th>
			<td>{{ $nSchedule->subject }}</td>
		</tr>
		<tr>
			<th scope="row" rowspan='2'>활동 내용</th>
			<td class="editor" style='border-bottom:none;'>
			<?php
				$nSchedule->content = str_replace("&nbsp;","",$nSchedule->content);
			?>
			{!! conv_subject($nSchedule->content, 353, '…') !!}
			<p style="position:relative;left:400px;float:right;text-align:right;padding:5px; font-weight:bold; color:#000; background:#e8e8e8;display:none;">

			</p>
			</td>
		</tr>
		<tr>
			<td class="editor" align=right>
			<a href="{{ route('sponsorzone', array('cate' => 0, 'dep01' => 1, 'dep02' => 2, 'type' => 'view', 'seq' => $seq)) }}">
			더보기
			</a>
			</td>
		</tr>
		<tr>
			<th scope="row">신청마감일</th>
			<td class="editor">{{ $nSchedule->start_date2 }}</td>
		</tr>
		<tr>
			<th scope="row">필요 인원</th>
			<td>{{ $nSchedule->people }}명</td>
		</tr>
		<tr>
			<th scope="row">신청 인원<br>({{ count($nSchedulepeo->page_result) }}명)</th>
			<td>
				<ul>
				<?php
				if(count($nSchedulepeo->page_result)>0){
				for($i=0, $cnt_list=count($nSchedulepeo->page_result); $i < $cnt_list; $i++) {
					$nSchedulepeo->VarList($nSchedulepeo->page_result, $i, null);
				?>
				<li>{{ $nSchedulepeo->name }}</li>
				<?php
					}
				}else{
				?>
					<li style="width:100%;">신청인원이 없습니다.</li>
				<?php
					}				
				?>
				</ul>
			</td>
		</tr>
	</table>

	<div class="xm_left mt20 ml15">
		@if(isset($nSchedule->file_edit[1]))
			<img src='/imgs/up_file/schedule/{{ $nSchedule->file_edit[1] }}' border='0' width='170px'>			
		@else
			<img src='/imgs/no-image.jpg' alt='no image' width='170'>
		@endif
		
		@if($nSchedule->is_on == 'a') <!-- is_on이 auto일 때 -->
			@if($checkable >= 0 || $nSchedule->people<=count($nSchedulepeo->page_result))
			<a href="#" class="gray_big_btn mt10" style="display:block">마감 되었습니다</a>
				@if($nSchedulepeo->total_record > 0)
					<a href="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'schedule_seq' => $nSchedule->seq ,'type' => 'del')) }}" class="orange_big_btn mt10" style="display:block;position:relative;top:-4px;">취소하기</a>

				<script type="text/javascript">
				(function($) {
					$(function() {
						$( "#modsave" ).click(function() {

							$('.pop_mod').show();
							

						});


					});
				})(jQuery);
				</script>

				@endif
			@else
			<form method="post" id="wrtForm" action="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'type' => 'write')) }}" style="display:inline;"  enctype="multipart/form-data">
				{{ UserHelper::SubmitHidden() }}
					@if($nSchedulepeo->total_record > 0)
					<a href="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'schedule_seq' => $nSchedule->seq, 'type' => 'del')) }}" class="orange_big_btn mt10" style="display:block">취소하기</a>
					<!-- <a href="#" class="orange_big_btn mt10" id="modsave" style="display:block">수정하기</a>	 -->	
					@else
						<a href="#" class="orange_big_btn mt10" id="save" style="display:block">신청하기</a>		
					@endif
			</form>
				
			<script type="text/javascript">
			(function($) {
				$(function() {
					// 글쓰기
					$( "#save" ).click(function() {

						$('.pop').show();
						

					});

					$( "#modsave" ).click(function() {

						$('.pop_mod').show();
						

					});


				});
			})(jQuery);
			</script>
			@endif
			
		@elseif($nSchedule->is_on == 'y') <!-- is_on이 yes일 때 -->
			<form method="post" id="wrtForm" action="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'type' => 'write')) }}" style="display:inline;"  enctype="multipart/form-data">
				{{ UserHelper::SubmitHidden() }}
					@if($nSchedulepeo->total_record > 0)
					<a href="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'schedule_seq' => $nSchedule->seq, 'type' => 'del')) }}" class="orange_big_btn mt10" style="display:block">취소하기</a>
					<!-- <a href="#" class="orange_big_btn mt10" id="modsave" style="display:block">수정하기</a>	 -->	
					@else
						<a href="#" class="orange_big_btn mt10" id="save" style="display:block">신청하기</a>		
					@endif
			</form>
				
			<script type="text/javascript">
			(function($) {
				$(function() {
					// 글쓰기
					$( "#save" ).click(function() {

						$('.pop').show();
						

					});

					$( "#modsave" ).click(function() {

						$('.pop_mod').show();
						

					});


				});
			})(jQuery);
			</script>
			
		@else <!-- is_on이 no일 때 -->
			<a href="#" class="gray_big_btn mt10" style="display:block">마감 되었습니다</a>
				@if($nSchedulepeo->total_record > 0)
					<a href="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'schedule_seq' => $nSchedule->seq ,'type' => 'del')) }}" class="orange_big_btn mt10" style="display:block;position:relative;top:-4px;">취소하기</a>

				<script type="text/javascript">
				(function($) {
					$(function() {
						$( "#modsave" ).click(function() {

							$('.pop_mod').show();
							

						});


					});
				})(jQuery);
				</script>

				@endif
		@endif
		
	</div>
	<style>
		.pop * {  font-family: 'Nanum Gothic',나눔고딕,NanumGothic; margin:0;  letter-spacing: -1px; }
		.pop {width:432px; height:570px; padding:24px 44px 0 30px; position:fixed; border:2px solid #dbdbdb; margin-left:140px; top:50%; margin-top:-300px; background:#fff; z-index:10000;}
		.pop p.tit { color:#95c161; font-size:19px; margin:0; margin-bottom:28px;}
		.pop-reg-info { color:#666; margin:25px 0; }
		.reg-list li span { color:#fd4f00; padding-right:9px;   font-size: 13px;  }
		.reg-list input { width:124px; height:19px; border:1px solid #cccccc;}
		.reg-list input.tel { width:167px; }
		.main-reg .people { margin-right:32px; }
		.add-reg { height:310px; overflow:hidden; }
		.add-reg li { margin-top:8px; }
		.add-reg .people { margin-right:18px; }
		.pop .reg-btn-right { padding:12px 0 11px 0 ;border-bottom:1px solid #cccccc; overflow:hidden; }
		.pop .reg-btn-right span { cursor:pointer; font-size:0; width:22px; height:22px; margin-right:11px; float:left; }
		.reg-plus { background:url("/imgs/btn_plus.png") no-repeat 0 0; }
		.reg-minus { background:url("/imgs/btn_minus.png") no-repeat 0 0;  }
		.pop .btn-area-center { text-align:center;}
		.pop .btn-orange-save { width:142px; height:37px; text-align:center; line-height:37px; font-size:14px; color:#fff; background:#fd5000; border:0; margin:0; }

	
	
		.add-reg2 { height:310px; overflow:hidden; }
		.add-reg2 li { margin-top:8px; }
		.add-reg2 .people { margin-right:18px; }	
		.pop_mod * {  font-family: 'Nanum Gothic',나눔고딕,NanumGothic; margin:0;  letter-spacing: -1px; }
		.pop_mod {width:432px; height:570px; padding:24px 44px 0 30px; position:fixed; border:2px solid #dbdbdb; margin-left:140px; top:50%; margin-top:-300px; background:#fff; z-index:10000;}
		.pop_mod p.tit { color:#95c161; font-size:19px; margin:0; margin-bottom:28px;}
		.pop_mod .reg-btn-right { padding:12px 0 11px 0 ;border-bottom:1px solid #cccccc; overflow:hidden; }
		.pop_mod .reg-btn-right span { cursor:pointer; font-size:0; width:22px; height:22px; margin-right:11px; float:left; }
		.pop_mod .btn-area-center { text-align:center;}
		.pop_mod .btn-orange-save { width:142px; height:37px; text-align:center; line-height:37px; font-size:14px; color:#fff; background:#fd5000; border:0; margin:0; }
	</style>
	<script type="text/javascript">
	
    function addPeople()
    {
        if($(".add-reg li").length >= 10){
			alert('최대 10명까지만 추가 가능합니다.');
			return ;      
		}
		var _tmp_html= $('#temp_reg_list').html();
		$('.add-reg').append('<li>'+_tmp_html+'</li>');
	}


    function addPeople()
    {
        if($(".add-reg li").length >= 10){
			alert('최대 10명까지만 추가 가능합니다.');
			return ;      
		}
		var _tmp_html= $('#temp_reg_list').html();
		$('.add-reg').append('<li>'+_tmp_html+'</li>');
	}

    function delPeople()
    {
        if($(".add-reg").length < 1){ return ; }		
		$('.add-reg li:last').remove();
	}


    function addPeople2()
    {
        if($(".add-reg2 li").length >= 10){
			alert('최대 10명까지만 추가 가능합니다.');
			return ;      
		}
		var _tmp_html= $('#temp_reg_list2').html();
		$('.add-reg2').append('<li>'+_tmp_html+'</li>');
	}

    function delPeople2()
    {
        if($(".add-reg2").length < 1){ return ; }		
		$('.add-reg2 li:last').remove();
	}


	function popclose(){
		$('.pop').hide();
	}

	function popmodclose(){
		$('.pop_mod').hide();
	}

	</script>
	<div class="pop" style="display:none;">
		<div class="close" style="position:absolute; top:15px; right:15px; cursor:pointer;" onclick="javascript:popclose();"><img src="/imgs/Closeicon.png"></div>
		<form method="post" id="wrtForm" action="{{ route('sponsorzone' ,array('cate' => 0, 'dep01' => 2, 'type' => 'write')) }}" style="display:inline;"  enctype="multipart/form-data">
		<input type="hidden" name="seq" value="{{ $nSchedule->seq }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<div>
			<p class="tit">봉사신청하기</p>
			<ul class="reg-list main-reg">
				<li>
					<span class="tit_people">신청자</span>
					<input type="text" name="name[0]" class="people"  value="{{ Auth::user()->user_name }}" readonly/>
					<span class="tit_phone">연락처</span>
					<input type="text" name="phone[0]" class="tel" value="{{ Auth::user()->user_cell }}" readonly/>
				</li>
			</ul>
			<div  class="reg-btn-right">
				<p style="float:right;">
				<span class="reg-plus" onclick="addPeople();" >추가</span>
				<span class="reg-minus"  onclick="delPeople();" >삭제</span>
				</p>
			</div>
			<ul class="reg-list add-reg">
				<li>
				<span>참여자</span>
				<input type="text" name="name[]" value="{{ Auth::user()->user_name }}" class="people" />
				<span>연락처</span>
				<input type="text" name="phone[]" value="{{ Auth::user()->user_cell }}" class="tel" />
				</li>
			</ul>

			<!-- 신청자 추가용 -->
			<div id="temp_reg_list" style="display:none;">
					<span>참여자</span>
					<input type="text" name="name[]"  class="people" />
					<span>연락처</span>
					<input type="text" name="phone[]"  class="tel" />
			</div>
		</div>
		<div class="pop-reg-info">
		* 신청자 성명, 연락처는 봉사활동 공지 및 안내에만 한하여 사용되어 집니다.<br>
		* 신청자도 봉사활동에 참여할 경우, '추가 신청자'란에 신청자의 정보도 기입되어야 합니다.<br>
		* 10인 이상 단체 봉사활동은 클로버가든 08-720-3235 로 연락 부탁드립니다.
		</div>
		<div class="btn-area-center" style="position:relative;top:-10px;">
			<button class="btn-orange-save">신청하기</button>
		</div>		
	</form>
	</div>


	<div class="pop_mod" style="display:none;"> <!-- 안 쓰는 부분인 것 같다 -->
		<div class="close" style="position:absolute; top:15px; right:15px; cursor:pointer;" onclick="javascript:popmodclose();"><img src="/imgs/Closeicon.png"></div>
		<form method="post" id="wrtForm_mod" action="/page/sponsor/page_2_0_mode_exec.php" style="display:inline;"  enctype="multipart/form-data">
		<input type="hidden" name="seq" value="{{ $nSchedule->seq }}">
		<div>
			<p class="tit">봉사신청하기</p>
			<ul class="reg-list main-reg">
				<li>
					<span class="tit_people">신청자</span>
					<input type="text" name="name[0]" class="people"  value="{{ Auth::user()->user_name }}" readonly/>
					<span class="tit_phone">연락처</span>
					<input type="text" name="phone[0]" class="tel" value="{{ Auth::user()->user_cell }}" readonly/>
					<input type="hidden" name="modseq[0]" class="tel" value="" readonly/>
				</li>
			</ul>

			<div  class="reg-btn-right">
				<p style="float:right;">
				<span class="reg-plus" onclick="addPeople2();" >추가</span>
				<span class="reg-minus"  onclick="delPeople2();" >삭제</span>
				</p>
			</div>
			<ul class="reg-list add-reg2">
				<?php
				if(count($nSchedulepeo_mod->page_result)>0){
				for($i=0, $cnt_list=count($nSchedulepeo_mod->page_result); $i < $cnt_list; $i++) {
					$nSchedulepeo_mod->VarList($nSchedulepeo_mod->page_result, $i, null);
				?>
				<li>
					<span>참여자</span>
					<input type="text" name="name[]" value="{{ $nSchedulepeo_mod->name }}" class="people" />
					<span>연락처</span>
					<input type="text" name="phone[]" value="{{ $nSchedulepeo_mod->phone }}"  class="tel" />		
					<input type="hidden" name="modseq[]" class="tel" value="{{ $nSchedulepeo_mod->seq }}" readonly/>
				</li>

				
				<?php
					}
				}
				?>
			</ul>

			<!-- 신청자 추가용 -->
			<div id="temp_reg_list2" style="display:none;">
					<span>참여자</span>
					<input type="text" name="name[]"  class="people" />
					<span>연락처</span>
					<input type="text" name="phone[]"  class="tel" />
					<input type="hidden" name="modseq[]" class="tel" value="insert" readonly/>
			</div>
		</div>
		<div class="pop-reg-info">
		<br>
		* 신청자 성명, 연락처는 봉사활동 공지 및 안내에만 한하여 사용되어 집니다.<br>
		* 신청자도 봉사활동에 참여할 경우, '추가 신청자'란에 신청자의 정보도 기입되어야 합니다.<br>
		* 10인 이상 단체 봉사활동은 클로버가든 08-720-3235 로 연락 부탁드립니다.
		</div>
		<div class="btn-area-center" style="position:relative;top:10px;">
		

			<!-- <button class="btn-orange-save">수정하기</button> -->
		</div>		
	</form>
	</div>
