<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //����,���,Ŭ����
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //�����

    $nLanding = new LandingClass(); //����������

    $nLanding->code     = iconv("UTF-8", "EUC-KR", rawurldecode(strtoupper($_POST['randing_code'])));
    $randing_code_msg   = "<font color='red'>����,������ �����մϴ�.</font>(4~20�� �̳��� ���ֽʽÿ�.)";
    $randing_code_check = 'n';

if(PattenCheck($nLanding->code, 7) == true && $nLanding->code != ''){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nLanding->where = "where code = '".$nLanding->code."'";
    $nLanding->read_result = $Conn->SelectColumn($nLanding->table_name, 'count(*)', $nLanding->where);
    if(!$nLanding->read_result){
        $randing_code_msg   = "<font color='blue'>��� ������ �����ڵ� �Դϴ�.</font>";
        $randing_code_check = 'y';
    }else{
        $randing_code_msg = "<font color='red'>�̹� ��ϵ� �����ڵ� �Դϴ�.</font>";
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