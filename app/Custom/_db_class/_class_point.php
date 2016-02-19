<?php
    settype($nMember, 'object');

    class PointClass
    {
        var $seq;                 //고유번호
		var $user_state;		  //등급
		var $user_name;           //이름
		var $group_name;           //그룹이름
        var $user_id;			  //아이디
        var $user_pw;             //비밀번호
        var $user_birth;          //생년월일
		var $user_gender;         //성별		
        var $user_cell;           //연락처2
		var $file_real;
        var $file_edit;
        var $file_byte;
		var $reg_date;            //가입날짜
        var $date_cnt;

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

        function PointClass()
        {
            $this->table_name = 'new_tb_point';
			$this->sub_sql    = '';
            $this->page_view  = 20;
            $this->page_set   = 5;
            $this->page_where = 5;


            $this->file_real[1];
            $this->file_edit[1];
            $this->file_byte[1];
            $this->file_pre_name[1];

            $this->file_up_cnt       = 1;
            $this->file_volume[1]    = 10;
            $this->file_mime_type[1] = 'image';
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
            $pResultValue[$pCnt]->idx          = stripcslashes($pResult['idx']);
			$pResultValue[$pCnt]->signdate          = stripcslashes($pResult['signdate']);
			$pResultValue[$pCnt]->depth          = stripcslashes($pResult['depth']);
            $pResultValue[$pCnt]->inpoint      = stripcslashes($pResult['inpoint']);
			$pResultValue[$pCnt]->outpoint      = stripcslashes($pResult['outpoint']);
			$pResultValue[$pCnt]->usepoint      = stripcslashes($pResult['usepoint']);
            $pResultValue[$pCnt]->userid      = stripcslashes($pResult['userid']);
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
            $this->idx        = $pResult[$pCnt]->idx;
			$this->signdate        = $pResult[$pCnt]->signdate;
			$this->depth        = $pResult[$pCnt]->depth;
            $this->inpoint    = $pResult[$pCnt]->inpoint;
			$this->outpoint    = $pResult[$pCnt]->outpoint;
			$this->usepoint    = $pResult[$pCnt]->usepoint;
            $this->userid    = $pResult[$pCnt]->userid;
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
            $this->idx        = '';
			$this->signdate    = '';
			$this->depth    = '';
            $this->inpoint    = '';
			$this->outpoint    = '';
			$this->usepoint    = '';
            $this->userid    = '';
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