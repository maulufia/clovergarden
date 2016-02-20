<?php

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class CloverFavoreModel extends Model
{
	
	private $nClover_m;
	private $nCloverFavore;
	private $conn;
	private $login_id;
	
	function __construct() {
		$this->nClover_m = new \CloverClass(); //클로버목록
		$this->nCloverFavore = new \ClovermlistClass(); //클로버목록
		$this->conn = new \DBClass();
		$this->login_id = Auth::user() ? Auth::user()->user_id : null;
		
		if(is_null($this->login_id)) {
			throw new Exception("Login ID must exist");
		}
	}
	
	public function getFavoreList() {
		$nClover_m = $this->nClover_m;
		$nCloverFavore = $this->nCloverFavore;
		$Conn = $this->conn;
		
		$nCloverFavore->page_result = $Conn->AllList
		(	
			$nCloverFavore->table_name, $nCloverFavore, "*", "where id='" . $this->login_id . "' order by reg_date desc limit 1", null, null
		);
		
		$clover_name = null;
		
		if(count($nCloverFavore->page_result) > 0){
			for($i=0, $cnt_list=count($nCloverFavore->page_result); $i < $cnt_list; $i++) {
				$nCloverFavore->VarList($nCloverFavore->page_result, $i, null);

				$nClover_m->where = "where code ='".$nCloverFavore->clover_seq."'";
				$nClover_m->read_result = $Conn->AllList
				(
					$nClover_m->table_name, $nClover_m, "*", $nClover_m->where, null, null
				);

				if(count($nClover_m->read_result) != 0){
					$nClover_m->VarList($nClover_m->read_result, 0, null);

					$clover_name = $nClover_m->subject;
				}	
				$Conn->DisConnect();
			}
		}
		
		$nCloverFavore->clover_name = $clover_name;
		
		return $nCloverFavore;
	}
	
}