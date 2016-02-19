<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="keywords" content="클로버가든">
<meta name="decription" content="클로버가든">
<meta name="distribution" content="global, korea">
<title>클로버가든 관리자</title>
<link rel="stylesheet" type="text/css" href="./css/base.css" />
<link rel="stylesheet" type="text/css" href="./css/common.css" />
<link rel="stylesheet" type="text/css" href="./css/content.css" />
<link rel="stylesheet" type="text/css" href="./css/layout.css" />
<link rel="stylesheet" type="text/css" href="../new_css/jquery-ui.css?css"/>

<script type="text/javascript" src="../new_js/default.js"></script>
<script type="text/javascript" src="../new_js/frmCheck.js"></script>
<script type="text/javascript" src="../new_js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../new_js/jquery-ui.min.js"></script>

</head>
<script language="javascript">

    function checkKeycode(e){
        var keycode;
        if(window.event){
            keycode = window.event.keyCode;
        }else if(e){
            keycode = e.which;
        }
        if(keycode == 13)
        {
            sendSubmit();
        }
    }

    function sendSubmit()
    {
        var f = document.frm;

        if(formCheckSub(f.idu, "exp", "아이디") == false){ return; }
        if(formCheckSub(f.idu, "inj", "아이디") == false){ return; }

        if(formCheckSub(f.passwd, "exp", "비밀번호") == false){ return; }
        if(formCheckSub(f.passwd, "inj", "비밀번호") == false){ return; }
        if(formCheckNum(f.passwd, "maxlen", 20, "비밀번호") == false){ return; }

        f.action = "login_exec.php";
        f.submit();
    }

</script>

<body class="login">
<div id="login-wrapper">
    <div class="login-top"><img src="/common/img/TopLogo.png"></div>
    <div class="login">
        <div class="login-image"><!--<img src="./images/.jpg" alt="ADMINISTRATOR" />--></div>
        <form name="frm" id="frm" method="post">
        <div class="login-input">
            <div class="id">
                <img src="./images/login_txt_id.gif" alt="USER ID" />
                <input name="idu" id="idu" type="text" tabindex="1" value="" maxlength="50" style="ime-mode:disabled;" onkeydown="checkKeycode(event)" >
            </div>
            <div class="pw">
                <img src="./images/login_txt_pw.gif" alt="PASSWORD" />
                <input name="passwd" id="passwd" type="password" tabindex="2" value="" maxlength="15" style="ime-mode:disabled;" onkeydown="checkKeycode(event)" onkeypress="handlerEng()">
            </div>
        </div>
       </form>
        <div class="login-btn"><input type="image" src="./images/btn_login.gif" alt="LOGIN" tabindex="3" onclick="javascript:sendSubmit()"/></div>
    </div>
</div>
</body>
</html>
