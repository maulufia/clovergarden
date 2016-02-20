<?php

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class CloverModel extends Model
{
	
	private $nClover_m;
	private $conn;
	
	function __construct() {
		$this->nClover_m = new \CloverClass(); //클로버목록
		$this->conn = new \DBClass();
	}
	
	/*
	  |--------------------------------------------------------------------------
	  | Clover Model Methods (Public)
	  |--------------------------------------------------------------------------
	  |
	  | These methods below are for clover model
	  |
	  */
	
	// 메인
	public function getCloverList() {
		$nClover_m = $this->nClover_m;
		$Conn = $this->conn;
		
		$nClover_m->where = "";
		$nClover_m->page_result = $Conn->AllList
		(	
			$nClover_m->table_name, $nClover_m, "*", "order by seq desc limit 10000", null, null
		);

		for($i=0, $cnt_list=count($nClover_m->page_result); $i < $cnt_list; $i++) {
			$nClover_m->VarList($nClover_m->page_result, $i, null);

			$clover_name_v[$nClover_m->code] = $nClover_m->subject;
		}
		
		return $clover_name_v;

	}
	
}

?>