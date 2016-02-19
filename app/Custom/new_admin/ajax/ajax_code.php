<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $nLanding = new LandingClass(); //랜딩페이지

    $nLanding->code     = iconv("UTF-8", "EUC-KR", rawurldecode(strtoupper($_POST['randing_code'])));
    $randing_code_msg   = "<font color='red'>숫자,영문만 가능합니다.</font>(4~20자 이내로 해주십시오.)";
    $randing_code_check = 'n';

if(PattenCheck($nLanding->code, 7) == true && $nLanding->code != ''){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nLanding->where = "where code = '".$nLanding->code."'";
    $nLanding->read_result = $Conn->SelectColumn($nLanding->table_name, 'count(*)', $nLanding->where);
    if(!$nLanding->read_result){
        $randing_code_msg   = "<font color='blue'>사용 가능한 랜딩코드 입니다.</font>";
        $randing_code_check = 'y';
    }else{
        $randing_code_msg = "<font color='red'>이미 등록된 랜딩코드 입니다.</font>";
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
}
    $arr_json = array
    (
        "randing_code_msg"   => iconv('EUC-KR', 'UTF-8', $randing_code_msg),
        "randing_code_check" => $randing_code_check
    );
    $json_return = json_encode($arr_json);
    echo '@@||@@'.urldecode($json_return);
?>