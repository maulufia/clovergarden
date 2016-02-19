<?php
    /*------------------------------------------------------------------------------------------------------*
    * page
    *------------------------------------------------------------------------------------------------------*/
    $key_large   = substr(strtoupper($page_key), 0, 1);
    $key_small   = substr(strtoupper($page_key), 1, 1);
    ${$page_key} = " class='on'";
    ${$page_key."_BOLD"} = " class='twb'";


    /*------------------------------------------------------------------------------------------------------*
    * left title
    *------------------------------------------------------------------------------------------------------*/
    switch($key_large)
    {
        case 'A' :
            $title_txt = '회원관리';
            break;
        case 'B' :
            $title_txt = '커뮤니티';
            break;
        case 'C' :
            $title_txt = '후원기관';
            break;
        case 'D' :
            $title_txt = '클로버';
            break;
        case 'E' :
            $title_txt = '고객센터';
            break;
		case 'F' :
            $title_txt = '팝업관리';
            break;
		case 'H' :
            $title_txt = '봉사스케쥴';
            break;
		case 'G' :
            $title_txt = '통계';
            break;
		case 'I' :
            $title_txt = '페이지관리';
            break;
    }

    /*------------------------------------------------------------------------------------------------------*
    * content title
    *------------------------------------------------------------------------------------------------------*/
    switch($page_key)
    {
        case 'A1' :
            $content_txt = '관리자';
            break;
        case 'A2' :
            $content_txt = '일반회원';
            break;
		case 'A3' :
            $content_txt = '기업회원';
            break;
	    case 'A4' :
            $content_txt = '홍보소식지신청자';
            break;
	    case 'A5' :
            $content_txt = '홍보소식지발송이력';
            break;			
	    case 'A6' :
            $content_txt = '약관 관리';
            break;			
	    case 'A7' :
            $content_txt = '정기소식지발송이력';
            break;			
	    case 'A8' :
            $content_txt = '탈퇴회원';
            break;			
			//---------------------------------------
        case 'B1' :
            $content_txt = '타임라인';
            break;
        case 'B2' :
            $content_txt = '후원자유게시판';
            break;
		case 'B3' :
            $content_txt = '배너관리';
            break;
        case 'B4' :
            $content_txt = '기관자유게시판';
            break;
            //---------------------------------------
        case 'C1' :
            $content_txt = '후원기관목록';
            break;
		case 'C2' :
            $content_txt = '후원등록/연동관리';
            break;
		case 'C3' :
            $content_txt = '후원기관소식';
            break;
		case 'C4' :
            $content_txt = '후원목록';
            break;
		case 'C5' :
            $content_txt = '* 후원목록';
            break;
		case 'C6' :
            $content_txt = '후원기관 배너등록';
            break;
		case 'C7' :
            $content_txt = '후원대상 변경신청';
            break;
            //---------------------------------------
        case 'D1' :
            $content_txt = '함께하는사람들';
            break;
		case 'D2' :
            $content_txt = '이달의 클로버';
            break;
		case 'D3' :
            $content_txt = '메인] 함께하는 제휴업체';
            break;
		case 'D4' :
            $content_txt = '메인] 이달의 클로버';
            break;
			//---------------------------------------
        case 'E1' :
            $content_txt = '새소식';
            break;
		case 'E2' :
            $content_txt = '1:1문의';
			break;
		case 'E3' :
            $content_txt = '자주묻는질문';  
			break;
			//---------------------------------------
        case 'F1' :
            $content_txt = '팝업관리';
            break;
			//---------------------------------------
        case 'H1' :
            $content_txt = '봉사스케쥴';
            break;
            //---------------------------------------
        case 'G1' :
            $content_txt = '방문통계-일별';
            break;
        case 'G2' :
            $content_txt = '방문통계-월별';
            break;

	
            //---------------------------------------
        case 'I1' :
            $content_txt = '페이지관리 - 회사소개';
            break;
        case 'I2' :
            $content_txt = '페이지관리 - 이용안내(기업)';
            break;
        case 'I3' :
            $content_txt = '페이지관리 - 이용안내(후원금신청)';
            break;
        case 'I4' :
            $content_txt = '페이지관리 - 이용안내(개인)';
            break;
	
	}
?>
