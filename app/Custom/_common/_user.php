<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 로그인, input hidden function 및 사용자 javascript
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    $login_id   = isset($_COOKIE['login_id']) ? base64_decode($_COOKIE['login_id']) : '';
	$group_name   = isset($_COOKIE['group_name']) ? base64_decode($_COOKIE['group_name']) : '';
	$login_state   = isset($_COOKIE['login_state']) ? base64_decode($_COOKIE['login_state']) : '';
	$login_name   = isset($_COOKIE['login_name']) ? base64_decode($_COOKIE['login_name']) : '';
	$login_cell   = isset($_COOKIE['login_cell']) ? base64_decode($_COOKIE['login_cell']) : '';
	$login_email    = isset($_COOKIE['login_id']) ? explode("@",base64_decode($_COOKIE['login_id'])) : '';

	$login_cell1 = substr($login_cell,0,3);
	$login_cell2 = substr($login_cell,3,-4) ;
	$login_cell3 = substr($login_cell,-4) ;

	
	switch($login_state){
		case 1:
			$state = '관리자';
			break;
		case 2:
			$state = '개인';
			break;
		case 3:
			$state = '단체';
			break;
		case 4:
			$state = '기업';
			break;
	}
?>