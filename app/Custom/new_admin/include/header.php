<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=10">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="keywords" content="">
<meta name="decription" content="">
<meta name="distribution" content="global, korea">
<title></title>
<link rel="stylesheet" type="text/css" href="../css/base.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/content.css" />
<link rel="stylesheet" type="text/css" href="../css/layout.css" />
<link rel="stylesheet" type="text/css" href="../../new_css/jquery-ui.css?css"/>
<link rel="stylesheet" type="text/css" href="../../new_css/jquery.lightbox-0.5.css?css"/>

<script type="text/javascript" src="../../new_js/default.js"></script>
<script type="text/javascript" src="../../new_js/frmCheck.js"></script>
<script type="text/javascript" src="../../new_js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../../new_js/jquery-ui.min.js"></script>
<!-- jquery plugin -->
<script type="text/javascript" src="../../new_js/blockUI_plugin/jquery.blockUI.js"></script>
<script type="text/javascript" src="../../new_js/autoresize_plugin/jquery.autoresize.js"></script>
<script type="text/javascript" src="../../new_js/cluetip_plugin/jquery.cluetip.js"></script>
<script type="text/javascript" src="../../new_js/lightbox_plugin/jquery.lightbox-0.5.js"></script>
<!-- //jquery plugin -->
<script type="text/javascript">

    $(document).ready(function()
    {
        //$('a[rel=lightbox]').lightBox();
        $('a[rel=lightbox]').attr("href","#;");
        $.blockUI.defaults =
        {
            message: "<img src='../../new_images/ajax-loader.gif'>",
            css:{ width:'15%', top:'40%', left:'43%', textAlign:'center', margin:0, border:'none', padding:'15px', cursor:'wait', color:'#000', opacity:.5, '-webkit-border-radius':'10px', '-moz-border-radius':'10px' },
            overlayCSS:{ backgroundColor:'#FCFCFC', opacity:0.6 },
            growlCSS:{ centerY : 0, top:'10px', left:'', right:'10px', border:'none', padding:'5px', opacity:0.6, cursor:null, color:'#fff',backgroundColor:'#000','-webkit-border-radius':'10px', '-moz-border-radius':'10px' },
            baseZ:1000, fadeIn:200, fadeOut:400, showOverlay:true, focusInput:true
        };
    });

</script>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_user.php'; ?>