<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //����,���,Ŭ����
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //�����

    $seq = iconv("UTF-8", "EUC-KR", rawurldecode($_POST['seq']));

    $nCounsel = new CounselClass(); //�¶��λ��

    $counsel_check = "n";
if($seq != ""){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $Conn->StartTrans();
    $out_put = $Conn->UpdateDBQuery($nCounsel->table_name, "counsel_state = '2' where seq = '".$seq."'");
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