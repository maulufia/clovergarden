<?php

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

use DB;

class ConfigModel extends Model
{
	
	public function getConfig($name) {
		$result = DB::table('cg_config')->where('name', '=', $name)->get();
		
		return $result[0];
	}
	
	public function setConfig($name, $content) {
		DB::table('cg_config')->where('name', '=', $name)->update(['content' => $content]);
	}
}