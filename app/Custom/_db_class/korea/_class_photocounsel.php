<?php
    settype($nPhotocounsel, 'object');

    class PhotocounselClass
    {
        var $seq;               //고유번호
		var $writer;				//작성자
        var $name;				//이름
        var $category;          //제목
		var $password;        //패스워드
        var $cell;        //글쓴이
		var $email;        //연락처
		var $reserve;           //받는형식
        var $subject;           //내용
		var $content;           //답변
		var $file_real;
        var $file_edit;
        var $file_byte;
		var $answer;           //답변
        var $hit;               //조회수
        var $reg_date;          //등록일

        //-- join column start --//
        var $comment_cnt;       //댓글수(서브쿼리)
        //-- join column end --//

        var $file_up_cnt;       //파일업로드갯수
        var $file_volume;       //파일용량제한
        var $file_mime_type;    //파일업로드타입
        var $file_pre_name;     //파일삭제변수

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
        var $sub_sql;           //서브쿼리

        function PhotocounselClass()
        {
            $this->table_name = 'new_tb_kor_photocounsel';
            $this->sub_sql    = '';
			$this->admin_page_view  = 20;
            $this->page_view  = 15;
            $this->page_set   = 10;
            $this->page_where = 10;

			$this->file_real[3];
            $this->file_edit[3];
            $this->file_byte[3];
            $this->file_pre_name[3];

            $this->file_up_cnt       = 3;
            $this->file_volume[1]    = 10;
            $this->file_mime_type[1] = 'image';
			$this->file_volume[2]    = 10;
            $this->file_mime_type[2] = 'image';
			$this->file_volume[3]    = 10;
            $this->file_mime_type[3] = 'image';
        }

        /*
        function __destruct()
        {
            $this->table_name = '';
            $this->sub_sql    = '';
        }
        */

        function ArrList($pResult, $pCnt, $pJoin=null)
        {
            $pResultValue = array();
            $pResultValue[$pCnt]->seq           = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->writer       = stripcslashes($pResult['writer']);
            $pResultValue[$pCnt]->name       = stripcslashes($pResult['name']);
            $pResultValue[$pCnt]->category       = stripcslashes($pResult['category']);
			$pResultValue[$pCnt]->password    = stripcslashes($pResult['password']);
            $pResultValue[$pCnt]->cell    = stripcslashes($pResult['cell']);
			$pResultValue[$pCnt]->email    = stripcslashes($pResult['email']);
			$pResultValue[$pCnt]->reserve    = stripcslashes($pResult['reserve']);
			$pResultValue[$pCnt]->subject    = stripcslashes($pResult['subject']);
			$pResultValue[$pCnt]->content    = stripcslashes($pResult['content']);
			$pResultValue[$pCnt]->answer    = stripcslashes($pResult['answer']);
			$pResultValue[$pCnt]->file_real[1]   = stripcslashes($pResult['file_real1']);
            $pResultValue[$pCnt]->file_edit[1]   = stripcslashes($pResult['file_edit1']);
            $pResultValue[$pCnt]->file_byte[1]   = stripcslashes($pResult['file_byte1']);
			$pResultValue[$pCnt]->file_real[2]   = stripcslashes($pResult['file_real2']);
            $pResultValue[$pCnt]->file_edit[2]   = stripcslashes($pResult['file_edit2']);
            $pResultValue[$pCnt]->file_byte[2]   = stripcslashes($pResult['file_byte2']);
			$pResultValue[$pCnt]->file_real[3]   = stripcslashes($pResult['file_real3']);
            $pResultValue[$pCnt]->file_edit[3]   = stripcslashes($pResult['file_edit3']);
            $pResultValue[$pCnt]->file_byte[3]   = stripcslashes($pResult['file_byte3']);
            $pResultValue[$pCnt]->hit           = stripcslashes($pResult['hit']);
            $pResultValue[$pCnt]->reg_date      = stripcslashes($pResult['reg_date']);
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $pResultValue[$pCnt]->comment_cnt = stripcslashes($pResult['comment_cnt']);
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 1, $pResultValue);
                }
            }
            return $pResultValue[$pCnt];
        }

        function VarList($pResult, $pCnt=0, $pJoin=null)
        {
            $this->seq           = $pResult[$pCnt]->seq;
			$this->writer       = $pResult[$pCnt]->writer;
            $this->name       = $pResult[$pCnt]->name;
            $this->category       = $pResult[$pCnt]->category;
			$this->password    = $pResult[$pCnt]->password;
			$this->cell    = $pResult[$pCnt]->cell;
			$this->email    = $pResult[$pCnt]->email;
			$this->reserve    = $pResult[$pCnt]->reserve;
			$this->subject    = $pResult[$pCnt]->subject;
			$this->content    = $pResult[$pCnt]->content;
			$this->answer    = $pResult[$pCnt]->answer;
            $this->file_real[1]   = $pResult[$pCnt]->file_real[1];
            $this->file_edit[1]   = $pResult[$pCnt]->file_edit[1];
            $this->file_byte[1]   = $pResult[$pCnt]->file_byte[1];
			$this->file_real[2]   = $pResult[$pCnt]->file_real[2];
            $this->file_edit[2]   = $pResult[$pCnt]->file_edit[2];
            $this->file_byte[2]   = $pResult[$pCnt]->file_byte[2];
			$this->file_real[3]   = $pResult[$pCnt]->file_real[3];
            $this->file_edit[3]   = $pResult[$pCnt]->file_edit[3];
            $this->file_byte[3]   = $pResult[$pCnt]->file_byte[3];
            $this->hit           = $pResult[$pCnt]->hit;
            $this->reg_date      = $pResult[$pCnt]->reg_date;
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $this->comment_cnt = $pResult[$pCnt]->comment_cnt;
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
                }
            }
        }

        function ArrClear($pJoin=null)
        {
            $this->seq           = '';
			$this->writer       = '';
            $this->name       = '';
            $this->category       = '';
			$this->password    = '';
            $this->cell      = '';
			$this->email      = '';
			$this->reserve      = '';
			$this->subject      = '';
			$this->content      = '';
			$this->answer      = '';
            $this->file_real[1]   = '';
            $this->file_edit[1]   = '';
            $this->file_byte[1]   = '';
			$this->file_real[2]   = '';
            $this->file_edit[2]   = '';
            $this->file_byte[2]   = '';
			$this->file_real[3]   = '';
            $this->file_edit[3]   = '';
            $this->file_byte[3]   = '';
            $this->hit           = '';
            $this->reg_date      = '';
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $this->comment_cnt = '';
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 3);
                }
            }
        }

        function JoinVar($pResult='', $pCnt=0, $pJoin=null, $pType='', $pResultValue='')
        {
            if($pType == '1'){
                switch($pJoin)
                {
                    case 'comment' :
                        $pResultValue[$pCnt]->comment_cnt = stripcslashes($pResult['comment_cnt']);
                        break;
                }
            }elseif($pType == '2'){
                switch($pJoin)
                {
                    case 'comment' :
                        $this->comment_cnt = $pResult[$pCnt]->comment_cnt;
                        break;
                }
            }elseif($pType == '3'){
                switch($pJoin)
                {
                    case 'comment' :
                        $this->comment_cnt = '';
                        break;
                }
            }
        }

        function ArrPhotocounsel($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('제목','내용');
                    $SelectValue = array('subject','content');
                    break;
                case 'hidden' :
                    $SelectField = array("<font color='blue'>노출</font>","<font color='red'>숨김</font>");
                    $SelectValue = array('1','2');
                    break;
				case 'category' :
                    $SelectField = array("전체","눈성형","코성형","안면윤곽","가슴성형","바디라인","안티에이징","리프팅","남자성형","스킨클리닉");
                    $SelectValue = array('1','2','3','4','5','6','7','8','9','10');
                    break;
				case 'reserve' :
                    $SelectField = array("이메일","SMS");
                    $SelectValue = array('1','2');
                    break;
				case 'gender' :
                    $SelectField = array("여자","남자");
                    $SelectValue = array('1','2');
                    break;
				case 'city' :
                    $SelectField = array("서울","인천","대전","대구","부산","광주","울산","경기도","강원도","충청도","경상도","전라도","제주도");
                    $SelectValue = array('1','2','3','4','5','6','7','8','9','10','11','12','13');
                    break;
				case 'cell' :
                    $SelectField = array("010","011","016","017","018","019");
                    $SelectValue = array('010','011','016','017','018','019');
                    break;
				case 'email' :
                    $SelectField = array("naver.com","hanmail.net","hotmail.com","nate.com","yahoo.co.kr","empas.com","dreamwiz.com","freechal.com","lycos.co.kr","korea.com","gmail.com","hanmir.com","paran.com","직접입력");
                    $SelectValue = array("naver.com","hanmail.net","hotmail.com","nate.com","yahoo.co.kr","empas.com","dreamwiz.com","freechal.com","lycos.co.kr","korea.com","gmail.com","hanmir.com","paran.com","직접입력");
                    break;
            }
            if($pWrite == ''){
                WriteSelect($SelectField, $SelectValue, $pVal, $pName, $pOption);
            }elseif($pWrite == 'radio'){
                WriteCheck($pVal, $pName, $pWrite, join(',',$SelectValue), join(',',$SelectField));
            }elseif($pWrite == 'checkbox'){
                WriteMultiCheck($pVal, $pName, $pWrite, join(',',$SelectValue), join(',',$SelectField));
            }else{
                echo $SelectField[array_search($pVal, $SelectValue)];
            }
        }


    }//end class
?>