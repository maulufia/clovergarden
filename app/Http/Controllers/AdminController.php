<?php

namespace clovergarden\Http\Controllers;

class AdminController extends Controller
{
	
	/*
	  |--------------------------------------------------------------------------
	  | Admin Site Route Methods
	  |--------------------------------------------------------------------------
	  |
	  | These methods below are for admin pages.
	  |
	  */
	
	// 메인
	public function showAdmin() {
		return view('admin.main');
	}
	
}

?>