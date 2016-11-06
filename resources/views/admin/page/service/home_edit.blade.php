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

  $nSchedule = new ScheduleClass();
  $nSchedulepeo   = new SchedulepeoClass();

  //======================== DB Module Start ============================
  $Conn = new DBClass();


  $nSchedule->where = "where seq ='".$seq."'";
  $nSchedule->read_result = $Conn->AllList($nSchedule->table_name, $nSchedule, "*", $nSchedule->where, null, null);

  if(count($nSchedule->read_result) != 0){
    $nSchedule->VarList($nSchedule->read_result, 0, null);
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
  <script type="text/javascript" src="/others/ckeditor/ckeditor.js"></script>
  <script type="text/javascript">
  window.onload =function(){
    CKEDITOR.replace('content', {
      enterMode:'1',
      filebrowserUploadUrl : '{{ route("fileupload") }}',
      filebrowserImageUploadUrl : '{!! route("fileupload", array("command" => "QuickUpload", "type" => "Images", "_token" => csrf_token() )) !!}'
    });
  };
  </script>
  <script language="javascript">

  function sendSubmit()
  {
    var f = document.frm;

    if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
    if(formCheckSub(f.subject, "inj", "제목") == false){ return; }


    if(formCheckSub(f.clover_seq , "exp", "후원기관코드") == false){ return; }
    if(formCheckSub(f.clover_seq, "inj", "후원기관코드") == false){ return; }


    if(formCheckSub(f.work_date , "exp", "봉사일") == false){ return; }
    if(formCheckSub(f.work_date, "inj", "봉사일") == false){ return; }


    $.blockUI();
    f.action = "<?php echo $edit_link; ?>";
    f.submit();
  }
  $(document).ready(function()
  {
    $('#start_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/imgs/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
    $.datepicker.regional['ko'] = {
      closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
      monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
      dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
      dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['ko']);
  });

  $(document).ready(function()
  {
    $('#start_date2').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/imgs/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
    $.datepicker.regional['ko'] = {
      closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
      monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
      dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
      dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['ko']);
  });
  $(document).ready(function()
  {
    $('#work_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/imgs/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
    $.datepicker.regional['ko'] = {
      closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
      monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
      dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
      dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['ko']);
  });

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
        <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
          <table class="bbs-write">
            <caption>{{ $content_txt }}</caption>
            <colgroup>
              <col style="width:100px;" />
              <col style="width:500px;" />
              <col style="width:100px;" />
              <col />
            </colgroup>
            <tbody>
              <tr>
                <th>제목</th>
                <td colspan="3">
                  <input type="text" name="subject" style="width:300px;" value="{{ $nSchedule->subject }}"/>
                </td>
              </tr>
              <tr>
                <th>후원기관코드</th>
                <td colspan="3">
                  <input type="text" name="clover_seq" style="width:100px;" value="{{ $nSchedule->clover_seq }}"/>
                </td>
              </tr>
              <tr>
                <th>필요인원</th>
                <td colspan="3">
                  <input type="text" name="people" style="width:100px;" value="{{ $nSchedule->people }}"/>
                </td>
              </tr>


              <tr>
                <th>봉사일</th>
                <td colspan="3">
                  <input type="text" name="work_date" id='work_date' value="{{ $nSchedule->work_date }}" style="width:100px;cursor:pointer;" readonly/>
                </td>
              </tr>

              <tr>
                <th>신청마감일</th>
                <td colspan="3">
                  <input type="text" name="start_date" value="{{ $nSchedule->start_date }}" style="width:100px;cursor:pointer;display:none;" readonly/>

                  <input type="text" name="start_date2" id='start_date2' value="{{ $nSchedule->start_date2 }}" style="width:100px;cursor:pointer;" readonly/>
                </td>
              </tr>

              <tr>
                <th style="vertical-align:middle;">내용</th>
                <td colspan="2">
                  <textarea name="content">{{ $nSchedule->content }}</textarea>
                </td>
              </tr>
              <tr>
                <th>기관로고</th>
                <td colspan="3">
                  @if(!empty($nSchedule->file_edit[1]))
                    <img src='/imgs/up_file/schedule/{{ $nSchedule->file_edit[1] }}' border='0' width='150px'>
                    <div style='padding-top:20px;padding-bottom:0px;'>
                      <a href="#">{{ $nSchedule->file_real[1] }}</a>
                      <font color='gray'> ({{ $nSchedule->file_byte[1] }})</font></div>
                    @else
                      <img src='/imgs/no-image.jpg' alt='no image' width='150'>
                    @endif
                    <input type="file" name="upfile1" size="50" />

                    @if(!empty($nSchedule->file_edit[1]))
                      <input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/><font color='red'>삭제</font>
                    @else
                      <input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>
                    @endif
                    <span class="lmits">({{ $nSchedule->file_mime_type[1] }} : {{ $nSchedule->file_volume[1] }}{{ LOW_FILESIZE }})</span>
                  </td>
                </tr>
                <tr>
                  <th>
                    접수자
                  </th>
                  <td colspan="3">
                    <a href="#!" onclick="addVolunteer()" style="float: right;">추가</a>
                    <ul id="volunteer-list">
                    @foreach($nSchedulepeo->page_result as $sp)
                        <li>
                          <input type="hidden" name="v_ids[]" value="{{ $sp->seq }}" />
                          <input type="hidden" name="v_deletes[]" value="0" />
                          <span>이름: </span><input type="text" name="v_names[]" value="{{ $sp->name }}" />
                          <span>번호: </span><input type="text" name="v_phones[]" value="{{ $sp->phone }}" />
                          <a href="#!" onclick="deleteVolunteer(this)" style="color: red; margin-left: 10px;">제거</a>
                        </li>
                    @endforeach
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>

            <script type="text/javascript">
              function addVolunteer() {
                $('#volunteer-list').append("<li><input type='hidden' name='v_ids[]' /><input type='hidden' name='v_deletes[]' value='0' /><span>이름: </span><input type='text' name='v_names[]' value=''/><span> 번호: </span><input type='text' name='v_phones[]' value='' /><a href='#!' onclick='deleteVolunteer(this)' style='color: red; margin-left: 10px;'>제거</a></li>");
              }

              function deleteVolunteer(object) {
                $(object).parent('li').children('input').attr('readonly', 'readonly').css('background', 'gray');
                $(object).parent('li').children('input[name="v_deletes[]"]').val('1');
                $(object).remove();
              }
            </script>


            {{ UserHelper::SubmitHidden() }}
            <input type="hidden" name="file_real1" value="{{ $nSchedule->file_real[1] }}"/>
            <input type="hidden" name="file_edit1" value="{{ $nSchedule->file_edit[1] }}"/>
            <input type="hidden" name="file_byte1" value="{{ $nSchedule->file_byte[1] }}"/>
          </form>
          <div class="btn-area">
            <div class="fleft">
              <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
            </div>
            <div class="fright">
              <input type="image" src="/imgs/admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
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
