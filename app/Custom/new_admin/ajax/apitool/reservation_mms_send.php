<?
error_reporting(E_ALL ^ E_NOTICE); //에러 보이게
include_once "./class/json.class.php";
include_once "./config.php";
include_once "./class/reservation_mms_send.php";
include_once "./curl/mms_curl.php";
include_once "./class/result_code.php";

@extract($_POST);
/*
example form 파일에서 들어오는 POST값들
$sms_from = "발신자"
$susin1 = "수신자1", $susin2="수신자2", $susin3="수신자3"
대량 발송의 경우 위의 방식보다는 jQuery 를 이용하여 데이터를 문서에 나온 방식 중 하나로
전송하시기 바랍니다.
$sms_to = 다중전송을 위한 변수
$tpye_set="msg타입"			//A,B,C,D
$sms_msg = "msg 내용"

$year =년
$month = 월
$day = 일
$c = 시
$m = 분
*/

if($susin1){
	$sms_to=$susin1;
}
if($susin2){
	$sms_to.=",".$susin2;
}
if($susin3){
	$sms_to.=",".$susin3;
}

//파일 업로드 부분
$file_name = $_FILES['file_form']['name'];
$uploadfile = "./mms_file/".$file_name;
move_uploaded_file($_FILES['file_form']['tmp_name'], $uploadfile);

$data = new reservation_mms_send;

//////발신자 전화번호 셋팅/////
$caller = $sms_from;//발신자번호

/*
type 1 => ex : 01011112222(한사람), ex : 01011112222,01022221111(단체는 콤마(,)로 구분해 주면됩니다.) 
type 2 => ex : array 형태로 넣어 주시면됩니다.
두가지 중에 편하신 방법으로 진행 하시기 바랍니다.
*/
$toll = $sms_to;//수신번호
///////////[메세지 내용 셋팅]//////////
//MMS 사용시 제목을 넣으실수 있습니다.(안넣어도 무방함. 일부 스마트 폰에서 제목으로 표시됩니다.)
$subject = "제목";
///////////[메세지 내용 셋팅]//////////
$msg = $sms_msg;//메세지
//////////////[단문인지 HTML 형식인지 셋팅]////////
//단문 0  ,  html 1
$html_type = 1;
///////////////////////////////////////////////////
//////////////SMS, LMS, MMS  셋팅//////////////////
// D : MMS 허용
$type_set = "D";  //mms 라 무조건 D 값으로 셋팅.

//////////////////////////////[예약 시간 설정]/////////
/*
$year = "2011";   //년
$month = "12";   //월
$day = "01";   //일
$c = "15";   //시
$m = "30";   //분
$s = "00";    //초
*/

//예약 문자 설정 시간입니다. 위의 형태로 넘겨 주시면 됩니다.
$s ="00";
$reservation = mktime($c,$m,$s,$month,$day,$year);

///////////[MMS 이미지 파일을 mms_file 폴더에 넣고 파일 이름만 입력해 주세요.]////////
//사이즈를 두 개의 종류로 맞추어 주세요.
//	176 x 144
//	160 x 120

$mms_file = $file_name;         //"업로드된 이미지 파일 이름";

/////////////////////////////////////////
$rs = $data->set($caller, $toll, $msg, 1, $mms_file, $reservation,$subject , $type = $type_set );

if($rs[0]==true){
	$result = $data->send();   //결과값 리턴.
	echo json_encode($result);
	exit;
}else{
	echo json_encode(array("result"=>false,"msg"=>$rs[1]));
	exit;
}
?>