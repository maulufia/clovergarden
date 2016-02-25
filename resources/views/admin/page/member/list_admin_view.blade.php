@extends('admin.common.husk')

@section('content')
<?php
    // initiate (공통)
    $page_key   = 'A1';
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
    $row_no     = NullNumber($_POST['row_no']);

    $nMember = new MemberClass(); //회원

    //======================== DB Module Start ============================
    $Conn = new DBClass();

    $nMember->where = "where id='".$seq."'";
    $nMember->read_result = $Conn->AllList($nMember->table_name, $nMember, "*", $nMember->where, null, null);

    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result, 0, null);
    }else{
        $Conn->DisConnect();
    }

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
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                    <col style="width:15%;" />
                    <col style="width:35%;" />
                </colgroup>
                <tbody>
				<tr>
                    <th>아이디</th>
                    <td>
                        {{ $nMember->user_id }}
                    </td>
					<th>이름</th>
                    <td>
                        {{ $nMember->user_name }}
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/imgs/admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <a href="{{ route('admin/member', array('item' => 'list_admin', 'type' => 'edit', 'seq' => $nMember->seq, 'row_no' => $row_no)) }}"><img src="/imgs/admin/images/btn_modify.gif" alt="edit" /></a>
                    <a href="javascript:pageLink('{{ $nMember->seq }}','','delete','{{ $delete_link }}');"><img src="/imgs/admin/images/btn_delete.gif" alt="erase" /></a>
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