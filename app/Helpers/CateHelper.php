<?php

class CateHelper {
	
	private $cate_factory;

	/*
  |--------------------------------------------------------------------------
  | Helpers
  |--------------------------------------------------------------------------
  |
  | Temporary
  |
  */
  
  private static function getCateFactory() {
  	$cate_factory = array(
									'sponsorzone',
									'clovergarden',
									'companion',
									'information',
									'customer',
									'login',
									'',
									'sitemap',
									'userinfo'
								);
  	
  	return $cate_factory;
  }
	  
	public static function getCateName($cate) {
		// 대분류 뷰 네임
		$cate_factory = CateHelper::getCateFactory();
		
		return $cate_factory[$cate];
	}
	
	// 카테고리 헬퍼
	public static function viewnameHelper($sub_cate, $dep01, $dep02, $option = null) {
		$view_name = "";
		
		// 대분류 뷰 네임
		$cate_factory = CateHelper::getCateFactory();
		$cate_name = $cate_factory[$sub_cate];
		
		// 소분류 뷰 네임
		$dep01_factory = new \StdClass();
		$dep01_factory->sponsorzone = array(
																	'home',
																	'community',
																	'activity'
																);
		$dep01_factory->clovergarden = array(
																	'home',
																	'newsletter'
																);
		$dep01_factory->companion = array(
																	'home',
																	'deans'
																);
		$dep01_factory->information = array(
																	'home',
																	'company',
																	'apply_support'
																);
		$dep01_factory->customer = array(
																	'home',
																	'qna',
																	'faq',
																	'about',
																	'map'
																);
		$dep01_factory->login = array(
														'home',
														'find_id',
														'find_pw',
														'signup'
														);
		$dep01_factory->mypage = array(
														'sendmessage',
														'messagebox_send',
														'messagebox_get',
														'point',
														'activiy_community',
														'activiy_history',
														'activiy_mailbox',
														'modify_personal',
														'change_clover',
														'mode_management',
														);
		$dep01_factory->sitemap = array(
															'home'
														);
		$dep01_factory->userinfo = array(
															'home'
														);
		
		$dep01_name = $dep01_factory->$cate_name;
		$dep01_name = $dep01_name[$dep01];
		
		// 소소분류 뷰 네임
		$dep02_factory = new \StdClass();
		$dep02_factory->community = array(
															'timeline',
															'board_sponsor',
															'board_company'
													);
		if(isset($dep02_factory->$dep01_name)) { // dep02는 존재하지 않을 수도 있음
			$dep02_name = $dep02_factory->$dep01_name;
			$dep02_name = ".".$dep02_name[$dep02];
		} else {
			$dep02_name = '';
		}
		
		// 보드 타입 검사
		$type = null;
		if(isset($option) && !is_null($option->type)) {
			$type = "_".$option->type;
		}
		
		$view_name = "front.page." . $cate_name . "." . $dep01_name . $dep02_name . $type;
		
		// 인증이 필요한 페이지 체크
		if(!Auth::check()) {
			$view_need_auth = array(
												'front.page.sponsorzone.activity',
												'front.page.clovergarden.home_write',
												'front.page.clovergarden.home_writeresv'
											);
			foreach ($view_need_auth as $va) {
				if($va == $view_name) {
					$view_name = null;
				}
			}
		}
		
		return $view_name;
	}
	
	// 페이지 체크 헬퍼 <- 헬퍼 쪽으로 이동 필요 
	public static function checkPage($category,$select){
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
}