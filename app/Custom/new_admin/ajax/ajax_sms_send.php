<?php
    include_once "./apitool/class/json.class.php";
    include_once "./apitool/config.php";
    include_once "./apitool/class/now_sms_send.php";
    include_once "./apitool/curl/curl.php";
    include_once "./apitool/class/result_code.php";

    $sms_seq          = $_POST['sms_seq'];
    $sms_cell         = $_POST['sms_cell'];
    $sms_send_content = $_POST['sms_send_content'];
    $sms_from_cell    = $_POST['sms_from_cell'];
    $sms_type_set     = $_POST['sms_type_set'];
/*
    $sms_seq          = "";
    $sms_cell         = "01071390272";
    $sms_send_content = "안녕하세요 테스트입니다.";
    $sms_from_cell    = "01071390272";
    $sms_type_set     = "-1";
*/
    // -1  config.php 설정된 값을 그대로 설정
    // A : SMS만 허용(80바이트 넘으면 수신 불가)
    // B : SMS만 허용(80바이트 넘으면 나누어서 전송)
    // C : LMS 허용
    // D : MMS 허용

    $data = new now_sms_send;
    $caller    = $sms_from_cell;    //발신자 전화번호
    $toll      = $sms_cell;         //수신자 전화번호
    $subject   = "";                //제목
    $msg       = $sms_send_content; //내용
    $html_type = 0;                 //0:단문/1:HTML
    if($sms_type_set == ''){
        $type_set = "-1";
    }else{
        $type_set = $sms_type_set;
    }

    $rs = $data->set($caller, $toll, $msg, 1, $subject, $type = $type_set );

    if($rs[0]==true){
        $data->send();
        $json_return = json_encode(array("result"=>true));
    }else{
        $json_return = json_encode(array("result"=>false));
    }
    echo '@@||@@'.urldecode($json_return);
?>