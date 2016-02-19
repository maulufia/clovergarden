<?php
    settype($nMember, 'object');

    class MemberClass
    {
        var $seq;                 //고유번호
		var $user_state;		  //등급
		var $user_name;           //이름
        var $user_id;			  //아이디
        var $user_pw;             //비밀번호
        var $user_birth;          //생년월일
		var $user_birthtype;          //양음력
		var $user_gender;         //성별
		var $user_tel;           //연락처1
        var $user_cell;           //연락처2
		var $user_zipcode;           //우편번호
		var $user_addr;           //주소
        var $user_email;          //이메일
		var $user_receive;            //메일및SMS승인
		var $user_interest;        //관심분야
		var $user_route;        //경로
		var $user_drop;        //탈퇴사유
		var $user_dropmsg;        //기타탈퇴사유
		var $reg_date;            //가입날짜
        var $date_cnt;

        var $top_page_result;   //최상위 리스트 리턴값
        var $page_result;       //리스트 리턴값
        var $read_result;       //상세보기 리턴값
        var $result_data;       //리턴값

        var $total_record;      //총레코드갯수
        var $where;             //SQL WHERE문
        var $order_by;          //정렬

        var $page_view;         //페이지글수
        var $page_set;          //페이지 번호 갯수
        var $page_where;        //페이지 이동 갯수

        var $table_name;        //테이블명

        function MemberClass()
        {
            $this->table_name = 'new_tb_kor_member';
			$this->sub_sql    = '';
            $this->page_view  = 20;
            $this->page_set   = 5;
            $this->page_where = 5;
        }

        /*
        function __destruct()
        {
            $this->table_name = '';
        }
        */

        function ArrList($pResult, $pCnt, $pJoin=null)
        {
            $pResultValue = array();
            $pResultValue[$pCnt]->seq          = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->user_state          = stripcslashes($pResult['user_state']);
            $pResultValue[$pCnt]->user_name      = stripcslashes($pResult['user_name']);
			$pResultValue[$pCnt]->user_id      = stripcslashes($pResult['user_id']);
            $pResultValue[$pCnt]->user_pw      = stripcslashes($pResult['user_pw']);
            $pResultValue[$pCnt]->user_birth    = stripcslashes($pResult['user_birth']);
			$pResultValue[$pCnt]->user_birthtype    = stripcslashes($pResult['user_birthtype']);
			$pResultValue[$pCnt]->user_gender = stripcslashes($pResult['user_gender']);
            $pResultValue[$pCnt]->user_tel = stripcslashes($pResult['user_tel']);
			$pResultValue[$pCnt]->user_cell    = stripcslashes($pResult['user_cell']);
			$pResultValue[$pCnt]->user_zipcode = stripcslashes($pResult['user_zipcode']);
			$pResultValue[$pCnt]->user_addr    = stripcslashes($pResult['user_addr']);
			$pResultValue[$pCnt]->user_email   = stripcslashes($pResult['user_email']);
			$pResultValue[$pCnt]->user_receive   = stripcslashes($pResult['user_receive']);
			$pResultValue[$pCnt]->user_interest   = stripcslashes($pResult['user_interest']);
			$pResultValue[$pCnt]->user_route   = stripcslashes($pResult['user_route']);
			$pResultValue[$pCnt]->user_drop   = stripcslashes($pResult['user_drop']);
			$pResultValue[$pCnt]->user_dropmsg   = stripcslashes($pResult['user_dropmsg']);
			$pResultValue[$pCnt]->reg_date   = stripcslashes($pResult['reg_date']);
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'state' :
                            $pResultValue[$pCnt]->date_cnt = stripcslashes($pResult['date_cnt']);
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 1, $pResultValue);
                }
            }
            return $pResultValue[$pCnt];
        }

        function VarList($pResult, $pCnt=0, $pJoin=null)
        {
            $this->seq        = $pResult[$pCnt]->seq;
			$this->user_state        = $pResult[$pCnt]->user_state;
            $this->user_name    = $pResult[$pCnt]->user_name;
			$this->user_id    = $pResult[$pCnt]->user_id;
            $this->user_pw    = $pResult[$pCnt]->user_pw;
            $this->user_birth  = $pResult[$pCnt]->user_birth;
			$this->user_birthtype  = $pResult[$pCnt]->user_birthtype;
			$this->user_gender  = $pResult[$pCnt]->user_gender;
			$this->user_tel  = $pResult[$pCnt]->user_tel;
			$this->user_cell  = $pResult[$pCnt]->user_cell;
			$this->user_zipcode  = $pResult[$pCnt]->user_zipcode;
			$this->user_addr  = $pResult[$pCnt]->user_addr;
			$this->user_email  = $pResult[$pCnt]->user_email;
			$this->user_receive  = $pResult[$pCnt]->user_receive;
			$this->user_interest  = $pResult[$pCnt]->user_interest;
			$this->user_route  = $pResult[$pCnt]->user_route;
			$this->user_drop  = $pResult[$pCnt]->user_drop;
			$this->user_dropmsg  = $pResult[$pCnt]->user_dropmsg;
			$this->reg_date  = $pResult[$pCnt]->reg_date;
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'state' :
                            $this->date_cnt = $pResult[$pCnt]->date_cnt;
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
                }
            }
        }

        function ArrClear($pJoin=null)
        {
            $this->seq        = '';
			$this->user_state    = '';
            $this->user_name    = '';
			$this->user_id    = '';
            $this->user_pw    = '';
            $this->user_birth  = '';
			$this->user_birthtype  = '';
			$this->user_gender  = '';
			$this->user_tel  = '';
			$this->user_cell  = '';
			$this->user_zipcode  = '';
			$this->user_addr  = '';
			$this->user_email  = '';
			$this->user_receive  = '';
			$this->user_interest  = '';
			$this->user_route  = '';
			$this->user_drop  = '';
			$this->user_dropmsg  = '';
			$this->reg_date  = '';
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'state' :
                            $this->date_cnt = '';
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 3);
                }
            }
        }

		function ArrMember($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('이름','휴대폰','이메일');
                    $SelectValue = array('user_name','user_cell','user_email');
                    break;
				case 'drop' :
                    $SelectField = array("사용의 불편","필요한 정보 부재","개인정보유출우려","수술 후 이용이 불편","기타 사유");
                    $SelectValue = array('1','2','3','4','5');
                    break;
				case 'birthtype' :
                    $SelectField = array("양력","음력");
                    $SelectValue = array('1','2');
                    break;
			    case 'gender' :
                    $SelectField = array("여자","남자");
                    $SelectValue = array('1','2');
                    break;
				case 'receive' :
                    $SelectField = array("동의","동의안함");
                    $SelectValue = array('1','2');
                    break;
				case 'cell' :
                    $SelectField = array("010","011","016","017","018","019");
                    $SelectValue = array('010','011','016','017','018','019');
                    break;
				case 'tel' :
                    $SelectField = array("02 (서울)","031 (경기)","032 (인천)","033 (강원)","041 (충남)","042 (대전)","043 (충북)","044 (세종)","051 (부산)","052 (울산)","053 (대구)","054 (경북)","055 (경남)","061 (전남)","062 (광주)","063 (전북)","064 (제주)","070 (인터넷)");
                    $SelectValue = array("02","031","032","033","041","042","043","044","051","052","053","054","055","061","062","063","064","070");
                    break;
				case 'interest' :
                    $SelectField = array("눈성형","코성형","안면윤곽","가슴성형","바디라인","지방이식","보톡스/필러","리프팅","스킨클리닉","남자성형");
                    $SelectValue = array('1','2','3','4','5','6','7','8','9','10');
                    break;
				case 'city' :
                    $SelectField = array("서울","인천","대전","대구","부산","광주","울산","경기도","강원도","충청도","경상도","전라도","제주도");
                    $SelectValue = array('1','2','3','4','5','6','7','8','9','10','11','12','13');
                    break;
				case 'route' :
                    $SelectField = array("지인소개","소문","잡지/신문방송","버스/지하철 광고","인터넷검색","인터넷카페","인터넷배너","기타");
                    $SelectValue = array('1','2','3','4','5','6','7','8');
                    break;
				case 'email' :
                    $SelectField = array("naver.com","hanmail.net","hotmail.com","nate.com","yahoo.co.kr","empas.com","dreamwiz.com","freechal.com","lycos.co.kr","korea.com","gmail.com","hanmir.com","paran.com","직접입력");
                    $SelectValue = array("naver.com","hanmail.net","hotmail.com","nate.com","yahoo.co.kr","empas.com","dreamwiz.com","freechal.com","lycos.co.kr","korea.com","gmail.com","hanmir.com","paran.com","직접입력");
                    break;
            }
            if($pWrite == ''){
                WriteSelect($SelectField, $SelectValue, $pVal, $pName, $pOption);
            }elseif($pWrite == 'radio' || $pWrite == 'checkbox'){
                WriteCheck($pVal, $pName, $pWrite, join(',',$SelectValue), join(',',$SelectField));
            }else{
                echo $SelectField[array_search($pVal, $SelectValue)];
            }
        }

    }//end class
?>