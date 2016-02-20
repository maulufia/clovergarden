@extends('front.page.information')

@section('information')
<?php
$page_key   = 'I3';

$nAdm = new AdmClass(); //
$nAdm_2 = new AdmClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();

$nAdm->page_result = $Conn->AllList
(	
	$nAdm->table_name, $nAdm, "*", "where t_name='use_inm' order by idx desc limit 1", null, null
);



$Conn->DisConnect();

?>


<?php
	for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
		$nAdm->VarList($nAdm->page_result, $i, null);
?>
<div class="mt30">
<img src="/imgs/page/{{ $nAdm->t_text }}" width="790">
</div>
<?php
	}
?>
@stop
