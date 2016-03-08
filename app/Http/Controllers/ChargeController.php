<?php

namespace clovergarden\Http\Controllers;

use Input, DB, Auth, Redirect, Hash;

class ChargeController extends Controller
{
	public function __construct() {
		include(app_path().'/Custom/_common/_global.php');
	}
	
	public function showProgress() {
		return view('payment.agspay.AGS_progress');
	}
	
	public function showAGSPay() {
		return view('payment.agspay.AGS_pay_ing');
	}
}