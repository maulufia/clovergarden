@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'C8';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";

  // Check admin
  $is_admin = Auth::user()->user_state > 6 ? true : false;
?>
<script language="javascript">
	function sendSubmit()
	{
    var f = document.frm;

    $.blockUI();
    f.action = "<?php echo $write_link; ?>";
    f.submit();
  }

  $(document).ready(function()
  {
    $('#due_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/imgs/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
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
			       <input type="hidden" name="view_n" value="1">
            <table class="bbs-write">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:100px;" />
                    <col style="width:350px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                  <tr>
                    <th>후원기관(코드)</th>
                    <td colspan="3"><input type="text" name="clover_code" style="width: 80px;" @unless ($is_admin) readonly value="{{ Auth::user()->user_id }}" @endunless /></td>
                  </tr>
                  <tr>
                    <th>후원 마감일</th>
                    <td colspan="3"><input type="text" id="due_date" name="due_date" style="width: 80px;" readonly /></td>
                  </tr>
                  <tr>
                    <th>내용</th>
                    <td colspan="3"><textarea name="text"></textarea></td>
                  </tr>
                  <tr>
                    <th>썸네일 이미지</th>
                    <td colspan="3">
                      <input type="file" name="image_path1" size="50" />
                    </td>
                  </tr>
                  <tr>
                    <th>내용 이미지 1</th>
                    <td colspan="3">
                      <input type="file" name="image_path2" size="50" />
                    </td>
                  </tr>
                  <tr>
                    <th>내용 이미지 2</th>
                    <td colspan="3">
                      <input type="file" name="image_path3" size="50" />
                    </td>
                  </tr>
                  <tr>
                    <th>권한</th>
                    <td colspan="3">
                      <select name="limitation">
                        <option value="public">전체</option>
                        <option value="protected">후원자만</option>
                        <option value="private">나만보기</option>
                      </select>
                    </td>
                  </tr>
            </table>
            {{ UserHelper::SubmitHidden() }}

            </form>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
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
