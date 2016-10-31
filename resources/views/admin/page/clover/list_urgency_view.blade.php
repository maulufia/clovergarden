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

  $seq = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;

  // Get post
  $post = DB::table('cg_board')->where('id', '=', $seq)->first();
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
            <col style="width:100px;" />
            <col style="width:500px;" />
            <col style="width:100px;" />
            <col />
          </colgroup>
          <tbody>
            <tr>
              <th>후원기관(코드)</th>
              <td colspan="3">{{ $post->clover_code }}</td>
            </tr>
            <tr>
              <th>후원 마감일</th>
              <td colspan="3">{{ $post->due }}</td>
            </tr>
            <tr>
              <th>내용</th>
              <td colspan="3">{{ $post->text }}</td>
            </tr>
            <tr>
              <th>썸네일 이미지</th>
              <td colspan="3">
                <img src='{{ $post->image_path1 }}' border='0' width='150px'>
              </td>
            </tr>
            <tr>
              <th>내용 이미지 1</th>
              <td colspan="3">
                <img src='{{ $post->image_path2 }}' border='0' width='150px'>
              </td>
            </tr>
            <tr>
              <th>내용 이미지 2</th>
              <td colspan="3">
                <img src='{{ $post->image_path3 }}' border='0' width='150px'>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="btn-area">
          <div class="fleft">
            <a href="{{ route('admin/clover', array('item' => 'list_urgency')) }}"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
          </div>
          <div class="fright">
            <a href="{{ route('admin/clover', array('item' => 'list_urgency', 'seq' => $post->id, 'type' => 'edit')) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
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
