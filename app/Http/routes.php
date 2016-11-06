<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* SAMPLE
Route::get('/', function () {
    return view('front.home');
}); */

// Route::get('/login', 'MainController@showLogin')->middleware('auth');


/*
|--------------------------------------------------------------------------
| JSON API Routes
|--------------------------------------------------------------------------
|
| This route is for JSON response API
| BY JM
|
*/

$api = app('Dingo\Api\Routing\Router');

// auth.api middleware should receive 'api_token' parameter
// This route need login
$api->version('v1', ['middleware' => 'auth.api'], function($api) {
	$api->get('/user', 'clovergarden\Http\Controllers\Api\ApiController@getUser');
	$api->get('/user/me', 'clovergarden\Http\Controllers\Api\ApiController@getMyDetail');
	$api->get('/user/me/support', 'clovergarden\Http\Controllers\Api\ApiController@getSupList'); // !deprecated
	$api->get('/user/me/support/year/{year}', 'clovergarden\Http\Controllers\Api\ApiController@getSupportListByYear');
	$api->get('/user/me/support/getAvailableYears', 'clovergarden\Http\Controllers\Api\ApiController@getAvailableSupportYear');
	$api->post('/user/me/support/change', 'clovergarden\Http\Controllers\Api\ApiController@changeClover');
	$api->post('/user/profile/upload', 'clovergarden\Http\Controllers\Api\ApiController@uploadProfilePic');
	$api->get('/user/me/companies', 'clovergarden\Http\Controllers\Api\ApiController@getListOfCompanyUser');

	$api->post('/timeline/comment/write', 'clovergarden\Http\Controllers\Api\ApiController@writeTimelineComment');
	$api->post('/timeline/comment/modify/{comment_id}', 'clovergarden\Http\Controllers\Api\ApiController@modifyTimelineComment');
	$api->post('/timeline/comment/delete/{comment_id}', 'clovergarden\Http\Controllers\Api\ApiController@deleteTimelineComment');

	$api->post('/timeline/like/{board_id}', 'clovergarden\Http\Controllers\Api\ApiController@toggleBoardLike');

	// Get Timeline List
	$api->get('/user/me/timeline', 'clovergarden\Http\Controllers\Api\ApiController@getUserTimeline');

	// Get All Timeline With Login
	$api->get('/timeline/all/withlogin', 'clovergarden\Http\Controllers\Api\ApiController@getTimelineAll');
});

// auth.api.charge middleware should receive 'api_token' parameter
// This route need Charge(clover) login
$api->version('v1', ['middleware' => 'auth.api.charge'], function($api) {
	$api->post('/timeline/write', 'clovergarden\Http\Controllers\Api\ApiController@writeTimeline');
	$api->post('/timeline/modify/{board_id}', 'clovergarden\Http\Controllers\Api\ApiController@modifyTimeline');
	$api->post('/timeline/delete/{board_id}', 'clovergarden\Http\Controllers\Api\ApiController@deleteTimeline');
});

$api->version('v1', function($api) {
	// Login
	$api->post('/user/login', 'clovergarden\Http\Controllers\Api\ApiController@login');

	// Login with Naver
	$api->post('/user/login/naver', 'clovergarden\Http\Controllers\Api\ApiController@loginNaver');

	// Sign Up
	$api->post('/user/signup', 'clovergarden\Http\Controllers\Api\ApiController@signup');

	// Check Duplicate ID
	$api->post('/user/check/id', 'clovergarden\Http\Controllers\Api\ApiController@checkDuplicateID');

	// Find ID
	$api->post('/user/findId', 'clovergarden\Http\Controllers\Api\ApiController@findId');

	// Find PW
	$api->post('/user/findPw', 'clovergarden\Http\Controllers\Api\ApiController@findPw');

	// Get List of Companies
	$api->get('/company/list', 'clovergarden\Http\Controllers\Api\ApiController@getListOfCompany');

	// Get Company's Detail
	$api->get('/company/{id}', 'clovergarden\Http\Controllers\Api\ApiController@getCompanyDetail');

	// Get Notices
	$api->get('/board/notice', 'clovergarden\Http\Controllers\Api\ApiController@getNotices');

	// Get FAQs
	$api->get('/board/faq', 'clovergarden\Http\Controllers\Api\ApiController@getFaqs');

	// Gend Auth SMS
	$api->post('/sms/auth', 'clovergarden\Http\Controllers\Api\ApiController@sendAuthSms');

	// Get All Timeline
	$api->get('/timeline/all', 'clovergarden\Http\Controllers\Api\ApiController@getTimelineAll');

	// Get Specific Clover Timeline
	$api->get('/timeline/clover/{clover_code}', 'clovergarden\Http\Controllers\Api\ApiController@getTimelineClover');

	// Get Timeline Details
	$api->get('/timeline/{board_id}', 'clovergarden\Http\Controllers\Api\ApiController@getTimelineDetail');

	// Get Timeline Comments
	$api->get('/timeline/comment/{board_id}', 'clovergarden\Http\Controllers\Api\ApiController@getTimelineComment');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['auth']], function () {

});

Route::group(['middleware' => ['web']], function () {

	# MAIN
	Route::get('/', 'MainController@showHome')->name('home');
	Route::post('/execemail', 'MainController@execEmail')->name('execemail');

	# POPUP
	Route::get('/popup', 'MainController@showPopup')->name('popup');

	# SPONSORZONE
	Route::get('/sponsorzone', 'MainController@showSponsorZone')->name('sponsorzone');
	Route::get('/sponsorzone/calendar', 'MainController@showCalendar')->name('sponsorzone_calendar');
	Route::get('/sponsorzone/calendar_detail', 'MainController@showCalendarDetail')->name('sponsorzone_calendar_detail');
	Route::post('/sponsorzone', 'MainController@postMethodControl');

	# CLOVERGARDEN
	Route::get('/clovergarden', 'MainController@showCloverGarden')->name('clovergarden');
	Route::post('/clovergarden', 'MainController@postMethodControl');
	Route::get('/clovergarden/getLatestSupportInfo/{email}', 'MainController@getLatestSupportInfo');

	# COMPANION
	Route::get('/companion', 'MainController@showCompanion')->name('companion');
	Route::post('/companion', 'MainController@postMethodControl');

	# INFORMATION
	Route::get('/information', 'MainController@showInformation')->name('information');

	# CUSTOMER
	Route::get('/customer', 'MainController@showCustomer')->name('customer');
	Route::post('/customer', 'MainController@showCustomer');
	Route::post('/customer/writeqna', 'AdminController@writeQna')->name('customer/writeqna');

	# MESSAGE
	Route::post('/message/sendgroupcreate', 'MsgController@sendGroupCreate')->name('msg_groupcreate');
	Route::post('/message/send', 'MsgController@send')->name('msg_send');

  # LOGIN
	Route::get('/login', 'MainController@showLogin')->name('login')->middleware('auth');
	Route::post('/login', 'UserController@loginControl');

	# LOGIN (SOCIAL)
	Route::any('/login/facebook', 'UserController@snsLoginControl');
	Route::any('/login/naver', 'UserController@snsLoginControl');
	Route::any('/login/kakao', 'UserController@snsLoginControl');
	Route::get('/login/facebook/callback', 'UserController@snsLoginCallbackFacebook');
	Route::get('/login/naver/callback', 'UserController@snsLoginCallbackNaver');
	Route::get('/login/kakao/callback', 'UserController@snsLoginCallbackKakao');

	Route::get('login/checkgroup', 'MainController@checkGroup')->name('checkgroup');
	Route::get('login/checkgroupname', 'MainController@showCheckGroupName')->name('checkgroupname');
	Route::get('login/checkid', 'MainController@checkId')->name('check_id');
	Route::post('login/checksns', 'MainController@checkSns')->name('check_sns');

	# SIGNUP
	Route::post('login/signup', 'MainController@postMethodControl')->name('signup');

	# MYPAGE
	Route::get('/mypage', 'MainController@showMypage')->name('mypage')->middleware('auth');
	Route::post('/mypage', 'MainController@postMethodControl');
	Route::get('/mypage/checkmember', 'MainController@showCheckMember')->name('mypage/checkmember');
	Route::post('/mypage/checkpassword', 'UserController@checkPassword')->name('checkpassword');
	Route::get('/mypage/dropout', 'UserController@userDrop')->name('userdrop')->middleware('auth');

	# SITEMAP
	Route::get('/sitemap', 'MainController@showSitemap')->name('sitemap');

	# USERINFO
	Route::get('/userinfo', 'MainController@showUserinfo')->name('userinfo');

	# LOGOUT
	Route::get('/logout', function() {
		Auth::logout();
		Flash::success('로그아웃이 완료되었습니다.');
		return Redirect::route('home');
	})->name('logout');

	# ADMIN / LOGIN
	Route::get('/admin', 'AdminController@showLogin')->name('admin/login')->middleware('auth.admin');
	Route::post('/admin', function () { // 다른 쪽으로 정리 필요
		// MD5로 로그인 가능한 지 체크
	  $user = array(
	      'user_id' => Input::get('idu'),
	      'password' => Input::get('passwd')
	  );
	  $passwd_md5 = md5(Input::get('passwd'));

	  $user_select = DB::select('select * from new_tb_member where user_id = :id && password = :pw', ['id' => $user['user_id'], 'pw' => $passwd_md5] );
	  if($user_select) { // MD5로 로그인 가능하다면, 패스워드를 bcrypt로 바꿈
	  	$bcrypt = Hash::make($user['password']);
	  	$md5tobcrypt = DB::update('update new_tb_member set password = :bcrypt where user_id = :id', ['bcrypt' => $bcrypt, 'id' => $user['user_id']]);
	  }

	  // bcrypt로 로그인
	  if (Auth::attempt($user)) {
	  	if( !(Auth::user()->user_state == 10 || Auth::user()->user_state == 1) ) {
	  		Flash::error('관리자만 이용 가능합니다.');
	  		return Redirect::route('logout');
	  	}

	  	return Redirect::intended('/admin/main');
      //return Redirect::to('home')
          //->with('flash_notice', 'You are successfully logged in.');
	  }

	  // authentication failure! lets go back to the login page
	  return Redirect::route('admin/login')
	      ->with('flash_error', 'Your username/password combination was incorrect.')
	      ->withInput();
	});

	# ADMIN
	Route::get('/admin/main', 'AdminController@showAdmin')->name('admin/main')->middleware('auth.admin');

	# ADMIN / LOGOUT
	Route::get('/admin/logout', function() {
		Auth::logout();
		return Redirect::route('admin/login');
	})->name('admin/logout');

	# ADMIN / MEMBER
	Route::get('/admin/member', 'AdminController@showMember')->name('admin/member')->middleware('auth.admin');
	Route::post('/admin/member', 'AdminController@postMember');

	# ADMIN / CLOVER
	Route::get('/admin/clover', 'AdminController@showClover')->name('admin/clover')->middleware('auth.admin');
	Route::post('/admin/clover', 'AdminController@postClover');

	# ADMIN / SERVICE
	Route::get('/admin/service', 'AdminController@showService')->name('admin/service')->middleware('auth.admin');
	Route::post('/admin/service', 'AdminController@postService');

	# ADMIN / COMMUNITY
	Route::get('/admin/community', 'AdminController@showCommunity')->name('admin/community')->middleware('auth.admin');
	Route::post('/admin/community', 'AdminController@postCommunity');

	# ADMIN / SPONSOR
	Route::get('/admin/sponsor', 'AdminController@showSponsor')->name('admin/sponsor')->middleware('auth.admin');
	Route::post('/admin/sponsor', 'AdminController@postSponsor');

	# ADMIN / CUSTOMER
	Route::get('/admin/customer', 'AdminController@showCustomer')->name('admin/customer')->middleware('auth.admin');
	Route::post('/admin/customer', 'AdminController@postCustomer');

	# ADMIN / STAT
	Route::any('/admin/stat', 'AdminController@showStat')->name('admin/stat')->middleware('auth.admin');

	# ADMIN / PAGE
	Route::any('/admin/page', 'AdminController@showPage')->name('admin/page')->middleware('auth.admin');

	# ADMIN / SETTING
	Route::any('/admin/setting', 'AdminController@showSetting')->name('admin/setting')->middleware('auth.admin');

	# MOBILE / BOARDS
	Route::get('/mobile/notice/list/{id}', 'MainController@showMobileNews')->name('mobile/notice/list');
	Route::get('/mobile/faq/list/{id}', 'MainController@showMobileFAQ')->name('mobile/faq/list');

	## SUB ROUTE ##
	Route::post('/ckeditor/upload', 'FileController@upload')->name('fileupload');
	Route::get('/agspay/AGS_progress', 'ChargeController@showProgress')->name('agspay/AGS_progress');
	Route::post('/agspay/AGS_pay_ing', 'ChargeController@showAGSPay')->name('agspay/AGS_pay_ing');
	Route::any('/agspay/AGS_VirAcctResult', 'ChargeController@showVirAcctResult')->name('agspay/AGS_VirAcctResult');

	Route::get('/agspay/AGSMobile_start_resv', 'ChargeController@showAGSPayMobileResv')->name('AGSMobile_start_resv')->middleware('auth.api');
	Route::post('/agspay/AGSMobile_start_resv_ing', 'ChargeController@execReserveSupport')->name('AGSMobile_start_resv_ing');

	Route::get('/agspay/AGSMobile_start_temp', 'ChargeController@showAGSPayMobileTemp')->name('AGSMobile_start_temp')->middleware('auth.api');
	Route::post('/mobile/execTempPointSupport', 'ChargeController@execTempPointSupport')->name('execTempPointSupport');
	Route::any('/agspay/AGSMobile_approve', 'ChargeController@showAGSPayMobileApprove')->name('AGSMobile_approve');
	Route::any('/agspay/AGSMobile_cancel', 'ChargeController@showAGSPayMobileCancel')->name('AGSMobile_cancel');

	Route::post('/mobile/point_approve', 'ChargeController@showPayWithPointApprove')->name('point_approve');

	# API DOCS #
	Route::get('docs', function(){
		return View::make('docs."api.v1".index');
	});

});
