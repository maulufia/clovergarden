<?php

    /*------------------------------------------------------------------------------------------------------*
    * 기본변수 선언, 타입선언 <- LARAVEL 에서 전혀 동작하지 않음 <- AppServiceProvider.php로 전환
    *------------------------------------------------------------------------------------------------------*/
    /*
    global $page_key, $title_txt, $content_txt, $key_large, $key_small;

    global $list_link;         //리스트 및 현재페이지
    global $view_link;         //상세보기페이지
    global $write_link;        //등록페이지
    global $edit_link;         //수정페이지
    global $delete_link;       //삭제페이지
    global $back_link;         //back페이지이동

    global $seq;               //고유번호
    global $code;              //고유코드
    global $mode;              //구분값
    global $search_key;        //검색필드
    global $search_val;        //검색단어
    global $page_no;           //페이지번호
    global $row_no;            //리스트번호

    global $out_put;           //결과리턴(0:성공/1:실패)
    global $file_num;          //파일번호
    global $cnt_list;          //카운트
    global $cnt_list1;         //카운트1
    global $cnt_list2;         //카운트2
    global $cnt_file;          //파일카운트
    global $json_return;       //json리턴값
    global $arr_json;          //json배열
    global $arr_field;         //테이블 필드 배열
    global $arr_value;         //테이블 값 배열
    global $arr_file;          //파일 배열
    global $check_del;         //파일삭제 */

    /*------------------------------------------------------------------------------------------------------*
    * 추가변수 선언
    *------------------------------------------------------------------------------------------------------*/
    global $cate_seq;          //카테고리번호
    global $operation_seq;     //성형갤러리번호

    /*------------------------------------------------------------------------------------------------------*
     * 기본상수선언
     *------------------------------------------------------------------------------------------------------*/
    $nowFileName = '/_common/_global.php'; $fullRoot = realpath(__FILE__);
    //$nowFileName = '\_common\_global.php'; $fullRoot = realpath(__FILE__); //local
    if(!$fullRoot) $fullRoot = __FILE__;
    $documentRoot = str_replace($nowFileName, '', $fullRoot); unset($fullRoot);

    define('DEFAULT_ROOT', $documentRoot);
    define('DEFAULT_URL',  'default.php');
    define('MAIN_URL',     'index.php');

    define('NO_LOGIN',            '로그인 정보가 없습니다.');
	define('OK_LOGIN',            '로그인 회원은 접근할 수 없습니다.');
    define('SUCCESS_LOGOUT',      '로그아웃이 완료되었습니다.');
	define('SUCCESS_LOGIN',       '정상적으로 로그인되었습니다.');
	define('SUCCESS_FINDPW',      '가입하신 이메일주소로 임시패스워드가 전송되었습니다.');
    define('ERR_LOGIN',           '비밀번호 또는 아이디가 정확하지 않습니다.');
	define('ERR_FINDID',          '입력하신 이름(실명), 연락처와\n일치하는 회원 정보가 없습니다.');
	define('ERR_FINDPW',          '입력하신 이메일(아이디), 이름(실명), 연락처와\n일치하는 회원 정보가 없습니다.');
    define('ERR_GRADE',           '접근 권한이 없습니다.');
    define('ERR_MEMBER_JOIN',     '회원가입 처리중입니다.');
    define('ERR_MEMBER_BLOCK',    '회원가입이 불가능한 아이디입니다.');
    define('ERR_MEMBER_QUIT',     '탈퇴한 회원입니다.');
    define('SUCCESS_MEMBER_JOIN', '회원가입이 정상적으로 완료되었습니다.');
    define('OVERLAP_MEMBER',      '회원 아이디 또는 주민등록번호가 이미 등록되어있습니다.');
    define('NO_PASSWORD',         '비밀번호가 일치하지 않습니다.');
    define('ERR_SECRET',          '비밀글입니다. \n\n게시물의 비밀번호를 입력하셔야 접근이 가능합니다.');
    define('ERR_WEBSITE',         '등록된 사이트가 존재하지 않습니다.');
    define('NO_PATH',             '잘못된 경로의 접근입니다.');
    define('NO_DATA',             '등록된 데이터가 없습니다.');
    define('NO_HIGH_DATA',        '등록된 카테고리 데이터가 없습니다.');
    define('NO_EXIST_DATA',       '이미 등록된 데이터가 존재합니다.');
    define('NO_DATABASE',         '데이터베이스 연결에 실패했습니다.');
    define('NO_FILES',            '등록된 파일이 존재하지 않습니다.');
    define('ERR_MIME_TYPE',       '업로드가 제한된 파일형식입니다.');
    define('LOW_FILESIZE',        'MB 이하');
    define('ERR_FILESIZE1',       '제한된 용량(');
    define('ERR_FILESIZE2',       'MB)을 초과하였습니다.');
    define('ERR_DATA',            '데이터 등록시 잘못된 형식이 있습니다.\n\n문제가 계속될 경우 관리자에게 문의해 주십시오.\n\n{ 데이터는 롤백되어 변경(입력,수정,삭제) 되지않았습니다. }');
    define('ERR_DATABASE',        '예기치 않은 오류가 발생하여 정상적으로 작업을 처리하지 못했습니다.');
    define('ERR_CODE',            '잘못된 코드입니다.');
    define('NO_PREVIOUS',         '이전 데이터가 없습니다.');
    define('NO_NEXT',             '다음 데이터가 없습니다.');
    define('ERR_LOW_DATA',        '하위 데이터가 존재하여 카테고리를 삭제할 수 없습니다.');
    define('NO_COMMENT',          '등록된 댓글이 없습니다.');
    define('NO_EDIT_DATA',        '수정이 불가능한 데이터입니다.');
    define('COMPLETE_DATA',       '완료 데이터는 수정이 불가능합니다.');
    define('SUCCESS_APPLY',       '정상적으로 적용 완료되었습니다.');
    define('SUCCESS_CHANGE',      '정상적으로 변경 완료되었습니다.');
    define('SUCCESS_WRITE',       '정상적으로 등록되었습니다.');
    define('SUCCESS_EDIT',        '정상적으로 수정되었습니다.');
    define('SUCCESS_DELETE',      '정상적으로 삭제되었습니다.');
    define('SUCCESS_ADD',         '정상적으로 추가되었습니다.');
    define('SUCCESS',             '정상적으로 완료되었습니다.');

    define('SUCCESS_WRITE_COMMENT', '댓글이 정상적으로 등록되었습니다.');
    define('SUCCESS_EDIT_COMMENT',  '댓글이 정상적으로 수정되었습니다.');
    define('SUCCESS_DELETE_COMMENT','댓글이 정상적으로 삭제되었습니다.');



    /*------------------------------------------------------------------------------------------------------*
     * default include
     *------------------------------------------------------------------------------------------------------*/
    require_once(DEFAULT_ROOT.'/_common/_pageout.php');                 //페이징 처리
    require_once(DEFAULT_ROOT.'/_common/_function.php');                //사용자지정 함수
    require_once(DEFAULT_ROOT.'/_common/_upfile.php');                  //파일업로드 함수
    require_once(DEFAULT_ROOT.'/_common/_json.php');                    //json
    require_once(DEFAULT_ROOT.'/_db_class/_class_db.php');              //DB관련 처리 함수

    /*------------------------------------------------------------------------------------------------------*
     * 클로버가든 table class include
     *------------------------------------------------------------------------------------------------------*/
	require_once(DEFAULT_ROOT.'/_db_class/_class_adm.php');            //후원자 유게시판
	require_once(DEFAULT_ROOT.'/_db_class/_class_free.php');            //후원자 유게시판
	require_once(DEFAULT_ROOT.'/_db_class/_class_free_com.php');            //기관 자유게시판
	require_once(DEFAULT_ROOT.'/_db_class/_class_timeline.php');            //타임라인
	require_once(DEFAULT_ROOT.'/_db_class/_class_notice.php');            //새소식
	require_once(DEFAULT_ROOT.'/_db_class/_class_faq.php');            //자주묻는질문
	require_once(DEFAULT_ROOT.'/_db_class/_class_onetoone.php');            //1:1문의
	require_once(DEFAULT_ROOT.'/_db_class/_class_schedule.php');            //봉사스케쥴
	require_once(DEFAULT_ROOT.'/_db_class/_class_schedulepeo.php');            //봉사스케쥴
	require_once(DEFAULT_ROOT.'/_db_class/_class_clover.php');            //클로버목록
	require_once(DEFAULT_ROOT.'/_db_class/_class_clovercomment.php');            //클로버응원댓글
	require_once(DEFAULT_ROOT.'/_db_class/_class_clovermlist.php');            //클로버정기후원목록
	require_once(DEFAULT_ROOT.'/_db_class/_class_member.php');            //회원
	require_once(DEFAULT_ROOT.'/_db_class/_class_stats.php');            //통계
	require_once(DEFAULT_ROOT.'/_db_class/_class_sponsor.php');            //함께하는사람들
	require_once(DEFAULT_ROOT.'/_db_class/_class_banner.php');            //함께하는사람들
	require_once(DEFAULT_ROOT.'/_db_class/_class_sponsorpeople.php');            //역대클로버
	require_once(DEFAULT_ROOT.'/_db_class/_class_message.php');            //역대클로버
	require_once(DEFAULT_ROOT.'/_db_class/_class_money.php');            //후원금액
	require_once(DEFAULT_ROOT.'/_db_class/_class_email.php');            //이메일
	require_once(DEFAULT_ROOT.'/_db_class/_class_clovernews.php');            //이메일
    require_once(DEFAULT_ROOT.'/_db_class/_class_scompany.php');            //이메일
	require_once(DEFAULT_ROOT.'/_db_class/_class_mclover.php');            //이메일
	require_once(DEFAULT_ROOT.'/_db_class/_class_reply.php');            //이메일
	require_once(DEFAULT_ROOT.'/_db_class/_class_point.php');            //포인트
    /*------------------------------------------------------------------------------------------------------*
     * 권한,페이지 분류 include
     *------------------------------------------------------------------------------------------------------*/
    // require_once(DEFAULT_ROOT.'/new_admin/include/pagekey.php');         //페이지 key value
    // require_once(DEFAULT_ROOT.'/update.php');         //페이지 key value // 임시로 삭제, 이상한 파일임
?>