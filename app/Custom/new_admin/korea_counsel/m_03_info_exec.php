<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $nCostcounsel= new CostcounselClass(); //온라인상담

    $seq = $_POST['seq'];
    $nCostcounsel->info = RequestAll($_POST['info']);

    $counsel_check = "n";
if($seq != ""){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDBQuery($nCostcounsel->table_name, "info = '".$nCostcounsel->info."' where seq = '".$seq."'");
    if($out_put){
        $Conn->CommitTrans();
        $counsel_check = "y";
    }else{
        $Conn->RollbackTrans();
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
}
    $arr_json = array
    (
        "counsel_check"   => iconv('EUC-KR', 'UTF-8', $counsel_check)
    );

    $json_return = json_encode($arr_json);
    echo '@@||@@'.urldecode($json_return);
?>