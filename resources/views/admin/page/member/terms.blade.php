@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'A6';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

	$nAdm = new AdmClass(); //
	$nAdm_2 = new AdmClass(); //
	$nAdm_3 = new AdmClass(); //
	$nAdm_4 = new AdmClass(); //
	//======================== DB Module Start ============================
	$Conn = new DBClass();

	$u_mode = isset($_POST['u_mode']) ? $_POST['u_mode'] : null;
	if($u_mode == "update"){
		
		DB::table('new_tb_adm_tup')->where('t_name', 'use_v')->update(['t_text' => $_POST['use_v']]);
		DB::table('new_tb_adm_tup')->where('t_name', 'pric_v')->update(['t_text' => $_POST['pric_v']]);
		DB::table('new_tb_adm_tup')->where('t_name', 'use_v_2')->update(['t_text' => $_POST['use_v_2']]);
		DB::table('new_tb_adm_tup')->where('t_name', 'use_v_3')->update(['t_text' => $_POST['use_v_3']]);

		UrlReDirect("약관이 수정되었습니다.", '/admin/member?item=terms');
	}



	$nAdm->page_result = $Conn->AllList
	(	
		$nAdm->table_name, $nAdm, "*", "where t_name='use_v' order by idx desc limit 1", null, null
	);

	$nAdm_2->page_result = $Conn->AllList
	(	
		$nAdm_2->table_name, $nAdm_2, "*", "where t_name='pric_v' order by idx desc limit 1", null, null
	);

	$nAdm_3->page_result = $Conn->AllList
	(	
		$nAdm_3->table_name, $nAdm_3, "*", "where t_name='use_v_2' order by idx desc limit 1", null, null
	);

	$nAdm_4->page_result = $Conn->AllList
	(	
		$nAdm_4->table_name, $nAdm_4, "*", "where t_name='use_v_3' order by idx desc limit 1", null, null
	);
	$Conn->DisConnect();
?>
</head>
<body>
   
<!-- wrapper -->
<div id="wrapper">
    <!-- top_area -->
        @include('admin.common.top')
    <!-- //top_area -->
    <!-- container -->
    <div id="container">
        <!-- left_area -->
            @include('admin.common.left')
        <!-- //left_area -->
        <!-- right_area -->
        <div id="right_area">

      <h4 class="main-title">{{ $content_txt }}</h4>
			<form method="post" action="{{ $list_link }}" name="adm_t_form">
				
				<input type="hidden" name="u_mode" value="update" />
				<input type="hidden" name="item" value="terms" />
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			
			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr>
				<td><strong>하단 이용 약관</strong></td>
			</tr>
			<tr height=10><td></td></tr>
			<tr>
				<td>
				<?php						
					for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
						$nAdm->VarList($nAdm->page_result, $i, null);
				?>
				<textarea name="use_v" style="width:99%; height:300px; line-height:200%; font-size:11px;">{{ $nAdm->t_text }}</textarea>
				<?php
					}
				?>

				
				
				</td>
			</tr>
			<tr height=10><td></td></tr>
			<tr>
				<td><strong>회원 가입 및 개인정보취급방침</strong></td>
			</tr>
			<tr height=10><td></td></tr>
			<tr>
				<td>
				<?php
					for($i=0, $cnt_list=count($nAdm_2->page_result); $i < $cnt_list; $i++) {
						$nAdm_2->VarList($nAdm_2->page_result, $i, null);
				?>
				<textarea name="pric_v" style="width:99%; height:300px; line-height:200%; font-size:11px;">{{ $nAdm_2->t_text }}</textarea>
				<?php
					}
				?>
				
				</td>
			</tr>
			<tr height=10><td></td></tr>

			<tr height=10><td></td></tr>
			<tr>
				<td><strong>일시 후원 약관</strong></td>
			</tr>
			<tr height=10><td></td></tr>
			<tr>
				<td>
				<?php
					for($i=0, $cnt_list=count($nAdm_3->page_result); $i < $cnt_list; $i++) {
						$nAdm_3->VarList($nAdm_3->page_result, $i, null);
				?>
				<textarea name="use_v_2" style="width:99%; height:300px; line-height:200%; font-size:11px;">{{ $nAdm_3->t_text }}</textarea>
				<?php
					}
				?>
				
				</td>
			</tr>
			<tr height=10><td></td></tr>


			<tr height=10><td></td></tr>
			<tr>
				<td><strong>정기 후원 약관</strong></td>
			</tr>
			<tr height=10><td></td></tr>
			<tr>
				<td>
				<?php
					for($i=0, $cnt_list=count($nAdm_4->page_result); $i < $cnt_list; $i++) {
						$nAdm_4->VarList($nAdm_4->page_result, $i, null);
				?>
				<textarea name="use_v_3" style="width:99%; height:300px; line-height:200%; font-size:11px;">{{ $nAdm_4->t_text }}</textarea>
				<?php
					}
				?>
				
				</td>
			</tr>
			<tr height=10><td></td></tr>


			<tr>
				<td align=center><input type="submit" value="저장하기" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
			</tr>
			</table>
			</form>

        </div>

    </div>
    <!-- container -->
    <!-- footer -->
        @include('admin.common.footer')
    <!-- //footer -->
</div>
<!-- //wrapper -->

</body>
</html>
@stop