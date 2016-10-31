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

  $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';

  // Check admin
  $is_admin = Auth::user()->user_state > 6 ? true : false;

  // Get board List
  if ($is_admin) {
    if (!$search_val) {
      $posts = DB::table('cg_board')->where('type', '=', 'urgency')->paginate(20);
    } else {
      $posts = DB::table('cg_board')->where('type', '=', 'urgency')->where('text', 'like', '%' . $search_val . '%')->paginate(20);
    }
  } else {
    if (!$search_val) {
      $posts = DB::table('cg_board')->where('type', '=', 'urgency')->where('clover_code', '=', Auth::user()->user_id)->paginate(20);
    } else {
      $posts = DB::table('cg_board')->where('type', '=', 'urgency')->where('clover_code', '=', Auth::user()->user_id)->where('text', 'like', '%' . $search_val . '%')->paginate(20);
    }
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
          f.action = "<?php echo $delete_link; ?>";
          f.submit();
        }else{
          return;
        }
      } else {
        f.action = "<?php echo $list_link; ?>";
        f.submit();
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="search_val" value="{{ $search_val }}"/>
            <input type="image" src="/imgs/admin/images/btn_search.gif" alt="search"/>
          </form>
        </div>
        <table class="bbs-list" style="margin:0px">
            <colgroup>
                <col style="width:80px;" />
                <col />
            </colgroup>
            <tr>
                <td style="text-align:left;"></td>
                <td></td>
                <td style="text-align:right;"><a href="javascript:sendSubmit('delete')">[ 선택삭제 ]</a></td>
            </tr>
        </table>
        <form id="send_frm" name="send_frm" method="post" style="display:inline;">
          <table class="bbs-list">
            <caption></caption>
            <colgroup>
              <col style="width:50px;" />
              <col style="width:50px;" />
              <col style="width:50px;" />
              <col style="width:150px;" />
              <col />
              <col style="width:160px;" />
            </colgroup>
            <thead>
              <tr>
                <th>선택</th>
                <th>번호</th>
                <th>후원기관(코드)</th>
                <th>썸네일</th>
                <th>내용</th>
                <th>마감일</th>
              </tr>
            </thead>
            <tbody>
            @if (count($posts) > 0)
              <?php
                $row_no = $posts->total() - (20 * ($page_no - 1));
              ?>
              @foreach ($posts as $key => $post)
                <tr>
                  <td><input type="checkbox" name="delete_seq[]" value="{{ $post->id }}" /></td>
                  <td>{{ $row_no-- }}</td>
                  <td>{{ $post->clover_code }}</td>
                  <td>
                    <img src='{{ $post->image_path1 }}' border='0' width='130px'>
                  </td>
                  <td class="subject">
                    <a href="{{ route('admin/clover', array('item' => 'list_urgency', 'seq' => $post->id, 'row_no' => $row_no, 'type' => 'view')) }}">
                      {{ $post->text }}
                    </a>
                  </td>
                  <td>{{ $post->due }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4">{{ NO_DATA }}</td>
              </tr>
            @endif
            </tbody>
          </table>
          {{ UserHelper::SubmitHidden() }}

        </form>

        <div class="paging-area">
          <?php
            $posts->setPath('/admin/clover?item=list_urgency')
          ?>
          {{ $posts->links() }}
        </div>

        <div class="btn-area tmargin">
          <div class="fleft ">
            <a href="{{ $list_link }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
          </div>
          <div class="fright">
            <a href="{{ route('admin/clover', array('item' => 'list_urgency', 'type' => 'write')) }}"><img src="/imgs/admin/images/btn_write.gif" alt="writing" /></a>
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
