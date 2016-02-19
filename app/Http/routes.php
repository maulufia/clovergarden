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
	
	# SPONSORZONE
	Route::get('/sponsorzone', 'MainController@showSponsorZone')->name('sponsorzone');
	Route::get('/sponsorzone/calendar', 'MainController@showCalendar')->name('sponsorzone_calendar');
	Route::post('/sponsorzone', 'MainController@postMethodControl');
	
	# CLOVERGARDEN
	Route::get('/clovergarden', 'MainController@showCloverGarden')->name('clovergarden');
	
  # LOGIN
	Route::get('/login', 'MainController@showLogin')->name('login')->middleware('auth');
	Route::post('/login', function () { // 다른 쪽으로 정리 필요
		// MD5로 로그인 가능한 지 체크
	  $user = array(
	      'user_id' => Input::get('idu'),
	      'password' => md5(Input::get('passwd'))
	  );
	  
	  $user_select = DB::select('select * from new_tb_member where user_id = :id && password = :pw', ['id' => $user['user_id'], 'pw' => $user['password']] );
	  if($user_select) { // MD5로 로그인 가능하다면, 패스워드를 bcrypt로 바꿈
	  	$bcrypt = Hash::make($user['password']);
	  	$md5tobcrypt = DB::update('update new_tb_member set password = :bcrypt where user_id = :id', ['bcrypt' => $bcrypt, 'id' => $user['user_id']]);
	  }
	  
	  // bcrypt로 로그인
	  if (Auth::attempt($user)) {
	  	return Redirect::intended('/');
	      //return Redirect::to('home')
	          //->with('flash_notice', 'You are successfully logged in.');
	  }
	  
	  // authentication failure! lets go back to the login page
	  return Redirect::route('login')
	      ->with('flash_error', 'Your username/password combination was incorrect.')
	      ->withInput();
	});
	
	# LOGOUT
	Route::get('/logout', function() {
		Auth::logout();
		return Redirect::route('home');
	})->name('logout');
});
