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
| DebugBar File
|--------------------------------------------------------------------------
|
| These are DebugBar files. manually connected.
|
*/

Route::get('/_debugbar/assets/stylesheets', [
    'as' => 'debugbar-css',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
]);

Route::get('/_debugbar/assets/javascript', [
    'as' => 'debugbar-js',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
]);

Route::get('/_debugbar/open', [
    'as' => 'debugbar-open',
    'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
]);

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
	Route::post('/sponsorzone', 'MainController@postMethodControl');
	
	# CLOVERGARDEN
	Route::get('/clovergarden', 'MainController@showCloverGarden')->name('clovergarden');
	Route::post('/clovergarden', 'MainController@postMethodControl');
	
	# COMPANION
	Route::get('/companion', 'MainController@showCompanion')->name('companion');
	
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
	Route::post('/login', function () { // 다른 쪽으로 정리 필요
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
	  	if(Auth::user()->user_state < 0) {
	  		return Redirect::route('logout')->with('flash_error', '회원탈퇴된 회원입니다.');
	  	}
	  	
	  	if(Auth::user()->user_state == 4) {
	  		if(Auth::user()->login_ck != 'y')
	  			return Redirect::route('login')->with('flash_error', '승인 전인 기업회원입니다.');
	  	}
	  	return Redirect::intended('/');
      //return Redirect::to('home')
          //->with('flash_notice', 'You are successfully logged in.');
	  }
	  
	  // authentication failure! lets go back to the login page
	  return Redirect::route('login')
	      ->with('flash_error', 'Your username/password combination was incorrect.')
	      ->withInput();
	});
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
		return Redirect::route('home');
	})->name('logout');
	
	# ADMIN / LOGIN
	Route::get('/admin', 'AdminController@showLogin')->name('admin/login');
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
	  		return Redirect::route('logout')->with('flash_error', '관리자만 이용 가능합니다');
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
	Route::get('/admin/main', 'AdminController@showAdmin')->name('admin/main');
	
	# ADMIN / MEMBER
	Route::get('/admin/member', 'AdminController@showMember')->name('admin/member');
	Route::post('/admin/member', 'AdminController@postMember');

});
