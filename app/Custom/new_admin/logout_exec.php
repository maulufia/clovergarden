<?
    ob_start();
    header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
    include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php';
    include $_SERVER[DOCUMENT_ROOT].'/_common/_user.php';

    setcookie('login_id', '', time()-3600, '/');
    setcookie('login_name', '', time()-3600, '/');
    setcookie('login_state', '', time()-3600, '/');
	setcookie('login_cell', '', time()-3600, '/');
	setcookie('login_email', '', time()-3600, '/');
	setcookie('login_state', '', time()-3600, '/');

    UrlReDirect(SUCCESS_LOGOUT, 'index.php');
?>
