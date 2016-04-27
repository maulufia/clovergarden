@extends('admin.common.husk')

@section('content')
<?php
    // initiate (공통)
    $page_key   = 'H1';
    $cate_result = CateHelper::adminCateHelper($page_key);
    $key_large = $cate_result->key_large;
    $title_txt = $cate_result->title_txt;
    $content_txt = $cate_result->content_txt;
    ${$page_key} = " class=on";
    ${$page_key."_BOLD"} = " class=twb";

    $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
    $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
    $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
    
    $seq        = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;
    $row_no     = NullNumber($_GET['row_no']);

    $nSchedule   = new ScheduleClass(); //자유게시판
	$nSchedulepeo   = new SchedulepeoClass(); //자유게시판

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", "where seq ='".$seq."'", $nSchedule->sub_sql, null);

    if(count($nSchedule->read_result) != 0){
        $nSchedule->VarList($nSchedule->read_result, 0, array('comment'));
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

	$nSchedulepeo->page_result = $Conn->AllList
	(	
		$nSchedulepeo->table_name, $nSchedulepeo, "*", "where schedule_seq='".$nSchedule->seq."' order by seq desc", null, null
	);


$Conn->DisConnect();
//======================== DB Module End ===============================

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

             <table class="bbs-view">
                <caption>{{ $content_txt }}</caption>
                 <colgroup>
                    <col style="width:120px;" />
                    <col style="width:500px;" />
                    <col style="width:120px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td>{{ $nSchedule->subject }}</td>
					<th>조회수</th>
                    <td>{{ $nSchedule->hit }}</td>
                </tr>
				<tr>
                    <th>봉사일</th>
                    <td colspan="3">{{ $nSchedule->work_date }}</td>
                </tr>
				<tr>
                    <th>신청마감일</th>
                    <td colspan="3">{{ $nSchedule->start_date2 }}</td>
                </tr>
                <tr>
                    <th>상태</th>
                    <td colspan="3">
                        <form method="POST" action="{{ route('admin/service', array('item' => 'home', 'type' => 'edit_status')) }}">
                        <input type="hidden" name="seq" value={{ $seq }} />
                        <input type="hidden" name="row_no" value={{ $row_no }} />
                        <input type="hidden" name="_token" value={{ csrf_token() }} />
                            <?php
                                $checkable = dateDiff(date("Y-m-d"), $nSchedule->start_date2);
                            ?>
                            <input type="radio" name="is_on" {{ $nSchedule->is_on == 'n' ? 'checked' : '' }} onclick="javascript:form.submit();" value="n"> 마감&nbsp;&nbsp;&nbsp;
                            <!--<input type="radio" name="is_on" {{ $nSchedule->is_on == 'y' ? 'checked' : '' }} onclick="javascript:form.submit();" value="y"> 진행 중&nbsp;&nbsp;&nbsp;-->
                            <input type="radio" name="is_on" {{ $nSchedule->is_on == 'a' ? 'checked' : '' }} onclick="javascript:form.submit();" value="a"> 자동
                            <span style="font-size: 11px; font-style: italic;">* 클릭 시 마감-진행을 수동으로 조절할 수 있습니다. 자동으로 선택된 경우 날짜에 맞춰 마감이 됩니다.</span>
                        </form>
                    </td>
                </tr>
                <tr>
                    <th>모집인원/필요인원</th>
                    <td>{{ count($nSchedulepeo->page_result) }}/{{ $nSchedule->people }}</td>
					<th>작성일</th>
                    <td>{{ str_replace('-','.',$nSchedule->reg_date) }}</td>
                </tr>
				<tr>
                    <th style="vertical-align:middle;">내용</th>
                    <td colspan="3" class="content">{!! $nSchedule->content !!}</td>
                </tr>
				<tr>
                    <th>이미지</th>
                    <td colspan="3">
                    @if(!empty($nSchedule->file_edit[1]))
                        <img src='/imgs/up_file/schedule/{{ $nSchedule->file_edit[1] }}' border='0' width='150px'>
                        <div style='padding-top:20px;padding-bottom:0px;'>
                        <a href="#">{{ $nSchedule->file_real[1] }}</a>
                        <font color='gray'> ({{ $nSchedule->file_byte[1] }})</font></div>
                    @else
						<img src='/imgs/no-image.jpg' alt='no image' width='150'>
                    @endif
                    </td>
                </tr>
				<tr>
					<th>접수자</th>
					<td colspan="3">


						<ul>
						<?php
						if(count($nSchedulepeo->page_result)>0){
						for($i=0, $cnt_list=count($nSchedulepeo->page_result); $i < $cnt_list; $i++) {
							$nSchedulepeo->VarList($nSchedulepeo->page_result, $i, null);
						?>
						<li>{{ $nSchedulepeo->name }} ({{ $nSchedulepeo->phone }}) 신청일 - {{ $nSchedulepeo->reg_date }}</li>
						<?php
							}
						} else {
						?>
							<li style="width:100%;">신청인원이 없습니다.</li>
						<?php
							}				
						?>
						</ul>


					</td>
				</tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="{{ route('admin/service', array('item' => 'home')) }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/service', array('item' => 'home', 'seq' => $nSchedule->seq, 'row_no' => $row_no, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
                </div>
            </div>
        </div>
        <form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
            {{ UserHelper::SubmitHidden() }}
        </form>
        <!-- //right_area -->
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