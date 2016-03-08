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

  $nSchedule   = new ScheduleClass(); //수술갤러리
	$nSchedulepeo   = new SchedulepeoClass();
  
  //======================== DB Module Start ============================
  $Conn = new DBClass();

	if(Auth::user()->user_state < 7){
		$nSchedule->where = "where clover_seq='".$group_name."'";
	}

  $nSchedule->total_record = $Conn->PageListCount
  (
      $nSchedule->table_name, $nSchedule->where, $search_key, $search_val
  );

  $nSchedule->page_result = $Conn->PageList
  (
      $nSchedule->table_name, $nSchedule, $nSchedule->where, $search_key, $search_val, 'order by seq desc', $nSchedule->sub_sql, $page_no, $nSchedule->admin_page_view, null
  );

 $nSchedulepeo->page_result1 = $Conn->AllList
  (
      $nSchedulepeo->table_name, $nSchedulepeo, "*, count(*) as comment_cnt",
      "where 1", null, array("comment")
  );

  $Conn->DisConnect();
  //======================== DB Module End ===============================
  $search_val = ReXssChk($search_val);

  if(count($nSchedulepeo->page_result1) > 0){
      for($i=0, $cnt_list=count($nSchedulepeo->page_result1); $i < $cnt_list; $i++) {
          $nSchedulepeo->VarList($nSchedulepeo->page_result1, $i, array('comment'));

              ${'id_'.$nSchedulepeo->schedule_seq}  = $nSchedulepeo->comment_cnt;
      }
      $nSchedulepeo->ArrClear();
  }
?>
    <script language="javascript">

        function sendSubmit(pType)
        {
            var f = document.send_frm;
            if(pType == "delete"){
                if(confirm("선택한 항목을 삭제하시겠습니까?")){
                    if($("input[name='delete_seq[]']:checked").length == "0"){
                        alert("삭제 항목을 한개이상 체크해주세요.");
                        return;
                    }
                    f.action = "{!! $delete_link !!}";
                    f.submit();
                }else{
                    return;
                }
            }
        }

    </script>
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
            <div class="bbs-search" style="margin-bottom:0px;">
                <form name="frm" method="post" action="{{ $list_link }}" style="display:inline">
                    <?php $nSchedule->ArrSchedule(null, "name='search_key'", null, 'search') ?>
                    <input type="text" name="search_val" value="{{ $search_val }}"/>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
                </form>
            </div>
            <table class="bbs-list" style="margin:0px">
                <colgroup>
                    <col style="width:80px;" />
                    <col />
                </colgroup>
                <tr>
                    <td></td>
                    <td style="text-align:right;"><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
                </tr>
            </table>
            <form id="send_frm" name="send_frm" method="post" style="display:inline;">
            <table class="bbs-list">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
					<col style="width:50px;" />
                    <col style="width:50px;" />
					<col style="width:150px;" />
                    <col />
                    <col style="width:150px;" />
                    <col style="width:80px;" />
                    <col style="width:100px;" />
                </colgroup>
                <thead>
                <tr>
					<th>선택</th>
                    <th>번호</th>
					<th>이미지</th>
                    <th>제목</th>
                    <th>모집인원/필요인원</th>
                    <th>조회수</th>
                    <th>작성일</th>                    
                </tr>
                </thead>
                <tbody>
				<?php
					if(count($nSchedule->page_result) > 0){
						$row_no = $nSchedule->total_record - ($nSchedule->admin_page_view * ($page_no - 1));
						for($i=0, $cnt_list=count($nSchedule->page_result); $i < $cnt_list; $i++) {
							$nSchedule->VarList($nSchedule->page_result, $i, null);
							$service_people = explode(",",$nSchedule->service_people);



							$Conn = new DBClass();
								$nSchedulepeo->page_result = $Conn->AllList
								(	
									$nSchedulepeo->table_name, $nSchedulepeo, "*", "where schedule_seq='".$nSchedule->seq."' order by seq desc", null, null
								);

							$Conn->DisConnect();

				?>
								<tr>
									<td><input type="checkbox" name="delete_seq[]" value="{{ $nSchedule->seq }}" /></td>
									<td>{{ $row_no }}</td>
									<td>
									<?php
										if(!empty($nSchedule->file_edit[1])){
											echo "<img src='/imgs/up_file/schedule/".$nSchedule->file_edit[1]."' border='0' width='130'>";                  
										}else{
											echo "<img src='/imgs/no-image.jpg' alt='no image' width='130'>"; 
										}
									?>
									</td>
									<td class="subject">
										<a href="{{ route('admin/service', array('item' => 'home', 'seq' => $nSchedule->seq, 'row_no' => $row_no, 'type' => 'view')) }}">{{ $nSchedule->subject }} ({{ $nSchedule->start_date }})</a>
									</td>
									<td>{{ count($nSchedulepeo->page_result) }}/{{ $nSchedule->people }}</td>
									<td>{{ $nSchedule->hit }}</td>
									<td>{{ str_replace('-','.',substr($nSchedule->reg_date,0,10)) }}</td>                    
								</tr>
				<?php
							$row_no = $row_no - 1;
						}
					}else{
				?>
								<tr>
									<td colspan="6">{{ NO_DATA }}</td>
								</tr>
				<?php
					}
				?>
                </tbody>
            </table>
            {{ UserHelper::SubmitHidden() }}
            </form>
            <div class="paging-area">
            <?php
                if($nSchedule->total_record != 0){
                    $nPage = new PageOut();
                    $nPage->AdminPageList($nSchedule->total_record, $page_no, $nSchedule->admin_page_view, $nSchedule->page_set, $nSchedule->page_where, 'pageNumber');
                }
            ?>
            </div>
            <div class="btn-area tmargin">
                <div class="fleft ">
                    <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/service', array('item' => 'home', 'type' => 'write')) }}"><img src="/imgs/admin/images/btn_write.gif" alt="writing" /></a>
                </div>
            </div>
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
            {{ UserHelper::SubmitHidden() }}
        </form>
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