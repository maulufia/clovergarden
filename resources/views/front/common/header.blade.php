<script type="text/javascript">

    //공통
    function pageLink(seq,row_no,mode,url)
    {
        var forms   = document.form_submit;
        var seq_num = ""
        forms.seq.value    = seq;
        forms.row_no.value = row_no;
        forms.mode.value   = mode;
        if(seq) { seq_num  = "?seq="+seq; }
        if(mode == "list"){
            forms.search_key.value = "";
            forms.search_val.value = "";
            forms.page_no.value    = "";
            forms.row_no.value     = "";
            forms.mode.value      = "";
        }
        else if(mode == "delete")
        {
            if(confirm("정말 삭제하시겠습니까?")){
                if(url) { forms.action = url+seq_num; }
            }else{
                return;
            }
        }
        if(url){ forms.action = url+seq_num; }
        forms.submit();
    }

    //페이지이동
    function pageNumber(page_no)
    {
        var forms = document.form_submit;

        forms.page_no.value = page_no;
        forms.submit();
    }
    function pageNumber2(page_no)
    {
        var forms = document.form_submit2;

        forms.page_no.value = page_no;
        forms.submit();
    }

    function pageNumber3(page_no)
    {
        var forms = document.form_submit3;

        forms.page_no.value = page_no;
        forms.submit();
    }
    //새창
    function pageLinkBlank(seq,url)
    {
        var forms = document.form_submit_blank;
        forms.seq.value = seq;
        forms.target    = "_blank";
        forms.action    = url;
        forms.submit();
    }

    //파일
    function downFile(seq,code,file_num,url) {
        iTarget.location.href = url+"?seq="+seq+"&code="+code+"&file_num="+file_num;
        setTimeout("linkBlank();",1000);
    }

    function linkBlank() {
        iTarget.location.href = '/_db_file/null.php';
    }

    //댓글
    function pageLinkCom(seq,com_seq,mode,url)
    {
        var forms = document.form_submit_comment;
        forms.seq.value     = seq;
        forms.com_seq.value = com_seq;
        forms.mode.value    = mode;
        if(mode == "list"){
            forms.mode.value = "";
        }else if(mode=="delete"){
            if(confirm("정말 삭제하시겠습니까?")){
                if(url) { forms.action = url; }
            }else{
                return;
            }
        }
        if(url) { forms.action = url; }
        forms.submit();
    }

</script>

<header id="header">
	<div class="wrap">
		 <ul>
		 <?php if(!Auth::check()){ ?>
			 <li><a href="{{ route('login') }}">로그인</a></li>
			 <li><a href="{{ route('login', array('dep01' => 3)) }}">회원가입</a></li>
	     <?php } else { ?>
			 <li><a href="{{ route('logout') }}">로그아웃</a></li>
			 <li><a href="{{ route('mypage') }}">마이페이지</a></li>
		 <?php } ?>
			 <li><a href="{{ route('sitemap') }}">사이트맵</a></li>
		 </ul>
	 </div>

	 <!-- Logo -->
	 <div id="logo">
		<a href="/"><img src="{{ url('imgs/TopLogo.png') }}" alt="clover garden" /></a>
	</div>

	<!-- Nav -->

	<nav id="nav">
		<ul>
			<li><a href="{{ route('sponsorzone') }}" <?php if($sub_cate==0 && !is_null($sub_cate)) echo "class='on'";?>>후원자마당</a></li>
			<li><a href="{{ route('clovergarden') }}" <?php if($sub_cate==1) echo "class='on'";?>>클로버가든</a></li>
			<li><a href="{{ route('companion') }}" <?php if($sub_cate==2) echo "class='on'";?>>함께하는 사람들</a></li>
			<li><a href="{{ route('information') }}" <?php if($sub_cate==3) echo "class='on'";?>>이용안내</a></li>
			<li><a href="{{ route('customer') }}" <?php if($sub_cate==4) echo "class='on'";?>>고객센터</a></li>
		</ul>
	</nav>
</header>