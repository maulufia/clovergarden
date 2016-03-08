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
    $row_no     = NullNumber($_GET['row_no']);

    $nMember = new MemberClass(); //회원
    //======================== DB Module Start ============================
    $Conn = new DBClass();

    $nMember->where = "where id ='".$seq."'";
    $nMember->read_result = $Conn->AllList
    (
        $nMember->table_name, $nMember, "*", $nMember->where, null, null
    );

    if(count($nMember->read_result) != 0){
        $nMember->VarList($nMember->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

	$cell = explode("-", $nMember->user_cell);
	// $email = explode("@", $nMember->user_email);

    $Conn->DisConnect();
    //======================== DB Module End ===============================
?>
    <script language="javascript">
        function sendSubmit()
        {
            var f = document.frm;



			if(formCheckSub(f.user_name , "exp", "이름") == false){ return; }
            if(formCheckSub(f.user_name, "inj", "이름") == false){ return; }
            if(formCheckNum(f.user_name, "maxlen", 50, "이름") == false){ return; }

			if(formCheckSub(f.user_pw , "exp", "패스워드") == false){ return; }
            if(formCheckSub(f.user_pw, "inj", "패스워드") == false){ return; }            
			if(formCheckNum(f.user_pw, "minlen", 4, "패스워드") == false){ return; }
			if(formCheckNum(f.user_pw, "maxlen", 15, "패스워드") == false){ return; }

		
            $.blockUI();
            f.action = "{!! $edit_link !!}";
            f.submit();
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
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
            <table class="bbs-write">
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
                    <td colspan="3">					    
                        {{ $nMember->user_id }}
                    </td>
                </tr>
				<tr>
                    <th>이름</th>
                    <td>					    
                        <input type="text" name="user_name" style="width:150px;" value="{{ $nMember->user_name }}"/>
                    </td>
                    <th>패스워드</th>
                    <td>
                        <input type="text" name="user_pw" style="width:150px;"/>
                    </td>
                </tr>
               
                </tbody>
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