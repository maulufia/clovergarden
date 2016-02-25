<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="x-ua-compatible" content="IE=10">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="keywords" content="">
  <meta name="decription" content="">
  <meta name="distribution" content="global, korea">
  <title>Clover Garden :: Admin</title>
  <link rel="stylesheet" type="text/css" href="/css/admin/base.css" />
  <link rel="stylesheet" type="text/css" href="/css/admin/common.css" />
  <link rel="stylesheet" type="text/css" href="/css/admin/content.css" />
  <link rel="stylesheet" type="text/css" href="/css/admin/layout.css" />
  <link rel="stylesheet" type="text/css" href="/css/new_css/jquery-ui.css?css"/>
  <link rel="stylesheet" type="text/css" href="/css/new_css/jquery.lightbox-0.5.css?css"/>

  <script type="text/javascript" src="/js/new_js/default.js"></script>
  <script type="text/javascript" src="/js/new_js/frmCheck.js"></script>
  <script type="text/javascript" src="/js/new_js/jquery-1.4.2.js"></script>
  <script type="text/javascript" src="/js/new_js/jquery-ui.min.js"></script>
  <!-- jquery plugin -->
  <script type="text/javascript" src="/js/new_js/blockUI_plugin/jquery.blockUI.js"></script>
  <script type="text/javascript" src="/js/new_js/autoresize_plugin/jquery.autoresize.js"></script>
  <script type="text/javascript" src="/js/new_js/cluetip_plugin/jquery.cluetip.js"></script>
  <script type="text/javascript" src="/js/new_js/lightbox_plugin/jquery.lightbox-0.5.js"></script>
  <!-- //jquery plugin -->
  <script type="text/javascript">

      $(document).ready(function()
      {
          //$('a[rel=lightbox]').lightBox();
          $('a[rel=lightbox]').attr("href","#;");
          $.blockUI.defaults =
          {
              message: "<img src='/imgs/new_images/ajax-loader.gif'>",
              css:{ width:'15%', top:'40%', left:'43%', textAlign:'center', margin:0, border:'none', padding:'15px', cursor:'wait', color:'#000', opacity:.5, '-webkit-border-radius':'10px', '-moz-border-radius':'10px' },
              overlayCSS:{ backgroundColor:'#FCFCFC', opacity:0.6 },
              growlCSS:{ centerY : 0, top:'10px', left:'', right:'10px', border:'none', padding:'5px', opacity:0.6, cursor:null, color:'#fff',backgroundColor:'#000','-webkit-border-radius':'10px', '-moz-border-radius':'10px' },
              baseZ:1000, fadeIn:200, fadeOut:400, showOverlay:true, focusInput:true
          };
      });

  </script>
  
  <!-- 기존 user.php include 자리 -->
  <script type="text/javascript">
    //공통
    function pageLink(seq,row_no,mode,url)
    {
        var forms   = document.form_submit;
        var seq_num = ""
        forms.seq.value    = seq;
        forms.row_no.value = row_no;
        forms.mode.value   = mode;
        if(seq) { seq_num  = "&seq="+seq; }
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
  
  @yield('content')
  