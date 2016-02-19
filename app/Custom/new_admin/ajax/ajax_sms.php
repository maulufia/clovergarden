<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $seq = iconv("UTF-8", "EUC-KR", rawurldecode($_POST['seq']));
    $nCounsel = new CounselClass(); //온라인상담

if($seq != ""){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nCounsel->read_result = $Conn->AllList($nCounsel->table_name, $nCounsel, '*', "where seq = '".$seq."'");
    if(count($nCounsel->read_result) != 0){
        $nCounsel->VarList($nCounsel->read_result, 0, null);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
}
    $arr_json = array
    (
        "sms_seq"     => iconv('EUC-KR', 'UTF-8', $nCounsel->seq),
        "sms_name"    => iconv('EUC-KR', 'UTF-8', $nCounsel->name),
        "sms_gender"  => iconv('EUC-KR', 'UTF-8', $nCounsel->gender),
        "sms_age"     => iconv('EUC-KR', 'UTF-8', $nCounsel->age),
        "sms_cell"    => iconv('EUC-KR', 'UTF-8', str_replace('-','',$nCounsel->cell)),
        "sms_part"    => iconv('EUC-KR', 'UTF-8', $nCounsel->part),
        "sms_content" => iconv('EUC-KR', 'UTF-8', $nCounsel->content)
    );

    $json_return = json_encode($arr_json);
    echo '@@||@@'.urldecode($json_return);
?>