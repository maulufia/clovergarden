<?php

class UserHelper {
  
  public $login_id;
  public $group_name;
  public $login_state;
  public $login_name;
  public $login_cell;
  public $login_email;
  public $use_point;

  public $login_cell1;
  public $login_cell2;
  public $login_cell3;

  public function __construct() {
    if(Auth::check()) {
      $login_id = Auth::user()->user_id;
      $login_name = Auth::user()->user_name;
      $login_state = 0; // ?
      $group_name = Auth::user()->group_name;
      $login_cell = Auth::user()->user_cell;
      $login_email = Auth::user()->user_id;
      $use_point = Auth::user()->m_point;
      
      $login_cell1 = substr($login_cell,0,3);
      $login_cell2 = substr($login_cell,3,-4);
      $login_cell3 = substr($login_cell,-4);
    }
  }

  public function setUserState($login_state) {
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
  }

	
  /*------------------------------------------------------------------------------------------------------*
   * 일반사용자 로그인 안되어 있으면 사용할 수 없음
   *------------------------------------------------------------------------------------------------------*/
  public static function PublicUserLogin($pUrl)
  {
      global $login_id;
      if(!base64_decode($login_id)) JsAlert(NO_LOGIN, 1, $pUrl);
  }

  public static function PublicUserLoginMsg($pUrl, $pMsg)
  {
      global $login_id;
      if(!base64_decode($login_id)){
          if($pMsg) JsAlert($pMsg, 1, $pUrl);
          else UrlReDirect(null, $pUrl);
      }
  }

	public static function PublicUserLoginBack($pMsg)
  {
      global $login_id;
      if(!base64_decode($login_id)){
          JsAlert($pMsg, 1, '/page.php?cate=13&dep03=0');
      }
  }

  /*------------------------------------------------------------------------------------------------------*
   * 일반사용자 로그인되어 있으면 사용할 수 없음
   *------------------------------------------------------------------------------------------------------*/
  public static function PublicUserLogOut($pUrl)
  {
      global $login_id;
      if(base64_decode($login_id)) JsAlert(NO_PATH, 1, $pUrl);
  }

  public static function PublicUserLogOutMsg($pUrl, $pMsg)
  {
      global $login_id;
      if(base64_decode($login_id)){
          if($pMsg) JsAlert($pMsg, 1, $pUrl);
          else UrlReDirect(null, $pUrl);
      }
  }

	public static function PublicUserLogOutBack($pMsg)
  {
      global $login_id;
      if(base64_decode($login_id)){
          JsAlert($pMsg, 0);
      }
  }
    
	/*------------------------------------------------------------------------------------------------------*
	 * 공통 hidden input
	 *------------------------------------------------------------------------------------------------------*/
	public static function SubmitHidden() {
	  $seq = isset($_REQUEST['seq']) ? $_REQUEST['seq'] : 0;
    $row_no = isset($_REQUEST['row_no']) ? $_REQUEST['row_no'] : 0;
    $page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
    $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
    $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
    $list_link = isset($_REQUEST['list_link']) ? $_REQUEST['list_link'] : '';
    $sub_cate = isset($_REQUEST['sub_cate']) ? $_REQUEST['sub_cate'] : 0;
    $dep01 = isset($_REQUEST['dep01']) ? $_REQUEST['dep01'] : 0;
    $dep02 = isset($_REQUEST['dep02']) ? $_REQUEST['dep02'] : 0;
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	  
    echo "<input type='hidden' name='seq' value='".$seq."'>".Chr(10);
	  echo "<input type='hidden' name='row_no' value='".$row_no."'>".Chr(10);
	  echo "<input type='hidden' name='page_no' value='".$page_no."'>".Chr(10);
	  echo "<input type='hidden' name='search_key' value='".$search_key."'>".Chr(10);
	  echo "<input type='hidden' name='search_val' value='".$search_val."'>".Chr(10);
	  echo "<input type='hidden' name='list_link' value='".$list_link."'>".Chr(10);
	  echo "<input type='hidden' name='sub_cate' value='".$sub_cate."'>".Chr(10);
		echo "<input type='hidden' name='dep01' value='".$dep01."'>".Chr(10);
		echo "<input type='hidden' name='dep02' value='".$dep02."'>".Chr(10);
		echo "<input type='hidden' name='type' value='".$type."'>".Chr(10);
		echo "<input type='hidden' name='mode' value='".$mode."'>".Chr(10);
    echo "<input type='hidden' name='_token' value='".csrf_token()."'>";
	}

	public static function SubmitHidden2() {
	  global $seq, $row_no, $page_no, $search_key, $search_val, $list_link, $sub_cate, $dep01, $dep02, $type, $mode;
	  echo "<input type='hidden' name='seq' value='".$seq."'>".Chr(10);
	  echo "<input type='hidden' name='row_no' value='".$row_no."'>".Chr(10);
	  echo "<input type='hidden' name='page_no' value='".$page_no."'>".Chr(10);
	  echo "<input type='hidden' name='page_no2' value='".$page_no2."'>".Chr(10);
	  echo "<input type='hidden' name='search_key' value='".$search_key."'>".Chr(10);
	  echo "<input type='hidden' name='search_val' value='".$search_val."'>".Chr(10);
	  echo "<input type='hidden' name='list_link' value='".$list_link."'>".Chr(10);
	  echo "<input type='hidden' name='sub_cate' value='".$sub_cate."'>".Chr(10);
		echo "<input type='hidden' name='dep01' value='".$dep01."'>".Chr(10);
		echo "<input type='hidden' name='dep02' value='".$dep02."'>".Chr(10);
		echo "<input type='hidden' name='type' value='".$type."'>".Chr(10);
		echo "<input type='hidden' name='mode' value='".$mode."'>".Chr(10);
    echo "<input type='hidden' name='_token' value='".csrf_token()."'>";
	}
}

?>