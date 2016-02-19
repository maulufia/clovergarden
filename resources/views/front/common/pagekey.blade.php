<!-- NOT USED ANYMORE !! -->

<?php
function checkPage($category,$select){
	
	if(!isset($category)) $category = -1; // 스파게티 코드의 최후

	switch($category){

		case 0:
			$cate['cate'] = "sponsor";
			$cate['name'] = "후원자마당";
			$step02 = array('타임라인','후원자 자유게시판','기관 자유게시판');
			$cate['sub_cate_01'] = array('후원자마당','커뮤니티','참여활동');
			$cate['sub_cate_02'] = array('',$step02,'');
			return $cate[$select];
			break;

		case 1:
			$cate['cate'] = "clover";
			$cate['name'] = "클로버가든";
			$cate['sub_cate_01'] = array('목록','소식지');
			return $cate[$select];
			break;

		case 2:
			$cate['cate'] = "people";
			$cate['name'] = "함께하는사람들";
			$cate['sub_cate_01'] = array('함께하는사람들','이달의 클로버');
			return $cate[$select];
			break;

		case 3:
			$cate['cate'] = "guide";
			$cate['name'] = "이용안내";
			$cate['sub_cate_01'] = array('개인','기업','후원금신청');
			//$cate[sub_cate_01_type] = array('','','popup');
			return $cate[$select];
			break;

		case 4:
			$cate['cate'] = "customer";
			$cate['name'] = "고객센터";
			$cate['sub_cate_01'] = array('새소식','1:1문의','자주하는 질문','회사소개','찾아오시는 길');
			return $cate[$select];
			break;

		case 5:
			$cate['cate'] = "member";
			$cate['name'] = "회원";
			$cate['sub_cate_01'] = array('로그인','아이디 찾기','패스워드 찾기','회원가입');
			return $cate[$select];
			break;

		case 6:
			$cate['cate'] = "mypage";
			$cate['name'] = "마이페이지";
			$step02 = array('보낸쪽지','받은쪽지');
			$step04 = array('커뮤니티','나눔 히스토리','나눔 메일박스');

			$cate['sub_cate_01'] = array('회원쪽지보내기','쪽지보관함','포인트조회','나의 활동','공제센터','개인정보수정','후원기관변경','관리자모드');
			$cate['sub_cate_02'] = array('',$step02,'',$step04,'','','');

			//$cate['sub_cate_01'] = array('나의활동','포인트조회','회원쪽지보내기','쪽지보관함','개인정보수정','결제정보수정','관리자모드','공제센터');
			//$cate[sub_cate_02] = array($step04,'','',$step04,'','','');




			return $cate[$select];
			break;

		case 7:
			$cate['cate'] = "sitemap";
			$cate['name'] = "사이트맵";

			$cate['sub_cate_01'] = array('사이트맵');

			return $cate[$select];
			break;
		case 8:
			$cate['cate'] = "profile";
			$cate['name'] = "회원 상세정보";

			$cate['sub_cate_01'] = array('회원 상세정보');

			return $cate[$select];
			break;

	}
}
?>

<?php
$sub_cate = isset($_GET['cate']) ? $_GET['cate'] : 0;
if($sub_cate==null);
$dep01 = isset($_GET['dep01']) ? $_GET['dep01'] : 0;
if($dep01==null) $dep01=0;
$dep02 = isset($_GET['dep02']) ? $_GET['dep02'] : 0;
if($dep02==null) $dep02_active=0;
else $dep02_active = $dep02;

//게시판타입
$type_get = isset($_GET['type']) ? $_GET['type'] : null;
if($type_get != 'list' && $type_get != null){
	$board_type = '_'.$type_get;
	$type = '_'.$type_get;
}

$view_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=view';
$write_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=write';
$writeresv_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=writeresv';
$step1_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active.'&type=step1';
$list_link = '/page.php?cate='.$sub_cate.'&dep01='.$dep01.'&dep02='.$dep02_active;

$cate_file = checkPage($sub_cate,'cate');//대분류 이름
$cate_name = checkPage($sub_cate,'name');//대분류 이름
$cate_01_result = checkPage($sub_cate,'sub_cate_01');
$cate_01 = checkPage($sub_cate,'cate');
$cate_01_count = count($cate_01_result);
// $cate_01_type = checkPage($sub_cate,'sub_cate_01_type'); // TEMP 이상한 코드
$cate_02_result = checkPage($sub_cate,'sub_cate_02');
?>