<?php
	$Conn = new DBClass();

	$nReply = new ReplyClass(); //

	$nReply->page_result = $Conn->AllList
	(	
		$nReply->table_name, $nReply, "*", "where seq='".$_GET['seq']."' order by idx desc", null, null
	);

	$Conn->DisConnect();
?>
<form method="POST" action="" name="reply_form"> <!-- PHP_SELF -->

		<input type="hidden" name="rmode" value="">
		<input type="hidden" name="type" value="view">
		<input type="hidden" name="cate" value="{{ $_GET['cate'] }}">
		<input type="hidden" name="dep01" value="{{ $_GET['dep01'] }}">
		<input type="hidden" name="dep02" value="{{ $_GET['dep02'] }}">
		<input type="hidden" name="seq" value="{{ $_GET['seq'] }}">
		<input type="hidden" name="wip" value=""> <!-- $REMOTE_ADDR -->
		<input type="hidden" name="wid" value="{{ Auth::check() ? Auth::user()->user_id : '' }}">
		<input type="hidden" name="wname" value="{{ $login_name }}">
		<input type="hidden" name="ridx" value="">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
		<?php
			$gmode = isset($_GET['gmode']) ? $_GET['gmode'] : '';
			$comment = isset($_GET['comment']) ? $_GET['comment'] : '';
			if($gmode == "mod"){
		?>		 
			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr height=100>
				<td width=15% style="border:1px solid #e8e8e8;" align=center bgcolor="92c154"><font color="ffffff"><strong>내용</strong></font></td>
				<td style="border:1px solid #e8e8e8;" align=center>
				<?php
				$_GET['cv'] = str_replace("<BR>","\r\n",$_GET['cv']);
				?>
				<textarea name="comment" style="width:98%; height:90px">{{ $_GET['cv'] }}</textarea>
				</td>
			</tr>
			<tr height=40>
				<td align=center colspan="2"><a href="javascript:reply_func('replym','{{ $_GET['idx'] }}');" class="green_btn">댓글수정</a></td>
			</tr>
			</table>
		<?php } else { ?>
			@if(Auth::check())
				<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
				<tr height=100>
					<td width=15% style="border:1px solid #e8e8e8;" align=center bgcolor="92c154"><font color="ffffff"><strong>내용</strong></font></td>
					<td style="border:1px solid #e8e8e8;" align=center>
					<textarea name="comment" style="width:98%; height:90px">{!! $comment !!}</textarea>
					</td>
				</tr>
				<tr height=40>
					<td align=center colspan="2"><a href="javascript:reply_func('replyi','');" class="orange_btn">댓글등록</a></td>
				</tr>
				</table>
			@endif
		<?php } ?>
		<br>


		<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
		<?php
			for($i=0, $cnt_list=count($nReply->page_result); $i < $cnt_list; $i++) {
				$nReply->VarList($nReply->page_result, $i, null);


				$r_comment = htmlspecialchars($nReply->comment);
				$r_comment = str_replace("\n","<BR>",$r_comment);
				$r_comment = str_replace("&lt;br&gt;","<BR>",$r_comment);
				$r_comment = stripslashes($r_comment);

		$Conn = new DBClass();
		$nMember = new MemberClass();

		$nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", "where user_id ='".$nReply->wid."'", $nMember->sub_sql, null);

		if(count($nMember->read_result) != 0){
			$nMember->VarList($nMember->read_result, 0, array('comment'));
		}

		$Conn->DisConnect();
				if($nMember->user_state > 9){
					$color_f = "fd4f00";
					$level_icon = "/imgs/new_images/ico_l1.gif";
				} else if ($nMember->user_state == 1) {
					$color_f = "000000";
					$level_icon = "/imgs/new_images/ico_l4.gif";
				} else if ($nMember->user_state == 3) {
					$color_f = "000000";
					$level_icon = "/imgs/new_images/ico_l3.gif";
				} else if ($nMember->user_state == 6) {
					$color_f = "666666";
					$level_icon = "/imgs/new_images/ico_l2.gif";
				} else if ($nMember->user_state == 5) {
					$color_f = "666666";
					$level_icon = "/imgs/new_images/ico_l5.gif";
				} else if ($nMember->user_state == 4) {
					$color_f = "666666";
					$level_icon = "/imgs/new_images/ico_l6.gif";
				} else {
					$color_f = "000000";
					$level_icon = "/imgs/new_images/ico_l4.gif";
				}

				if($nReply->wname == "관리자"){
					$nReply->wname = "클로버가든";
				} else {
					$nReply->wname = $nReply->wname;
				}

		?>
		<tr height=30>
			<td><img src="{{ $level_icon }}" style="vertical-align:middle;"><font color="{{ $color_f }}">{{ $nReply->wname }}</font>({{ date("Y-m-d H:i:s",$nReply->signdate) }}) | {{ $nReply->wip }}</td>
		</tr>
		<tr height=1>
			<td bgcolor="e8e8e8"></td>
		</tr>
		<tr>
			<td align=left style="line-height:100%; padding-top:15px;">
			{!! $r_comment !!}
			</td>
		</tr>
		<tr height=40>
			<td align=right colspan="2">
			<?php if(Auth::check() && $nReply->wid == Auth::user()->user_id){ ?>
				<a href="{{ CateHelper::getCateName($_GET['cate']) }}?cate={{ $_GET['cate'] }}&dep01={{ $_GET['dep01'] }}&dep02={{ $_GET['dep02'] }}&type={{ $_GET['type'] }}&seq={{ $_GET['seq'] }}&cv={{ $r_comment }}&gmode=mod&idx={{ $nReply->idx }}" class="green_btn">댓글수정</a>
				<a href="javascript:reply_func('replyd','{{ $nReply->idx }}');" class="orange_btn">댓글삭제</a>
			<?php } ?>
			
			</td>
		</tr>
		<?php
			}
		?>

		</table>


	</form>
<script type="text/javascript">
<!--
function reply_func(value, ridx){
	var f = document.reply_form;
	if(f.wid.value == ""){
		alert("로그인 후 이용해 주세요.");
		return;
	}
	if(value == "replyi"){
		if(f.comment.value == ""){
			alert("댓글 내용을 입력해주세요.");
			return;
		}
	}

	if(value == "replym"){
		if(f.comment.value == ""){
			alert("댓글 내용을 입력해주세요.");
			return;
		}
	}

	f.rmode.value = value;
	f.ridx.value = ridx;
	f.submit();
}


//-->
</script>


