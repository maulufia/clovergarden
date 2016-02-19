<?php
    settype($nEmail, 'object');

    class EmailClass
    {
        var $seq;               //고유번호
		var $name;			//작성자
        var $email;				//이메일
        var $reg_date;          //등록일

        //-- join column start --//
        var $comment_cnt;       //댓글수(서브쿼리)
        //-- join column end --//


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

        function EmailClass()
        {
            $this->table_name = 'new_tb_email';
            $this->sub_sql    = '';
			$this->admin_page_view  = 20;
            $this->page_view  = 15;
            $this->page_set   = 10;
            $this->page_where = 10;
        }


        function ArrList($pResult, $pCnt, $pJoin=null)
        {
            $pResultValue = array();
            $pResultValue[$pCnt]->seq           = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->name       = stripcslashes($pResult['name']);
			$pResultValue[$pCnt]->email    = stripcslashes($pResult['email']);
			$pResultValue[$pCnt]->reg_date    = stripcslashes($pResult['reg_date']);
			$pResultValue[$pCnt]->send_date    = stripcslashes($pResult['send_date']);
			$pResultValue[$pCnt]->send_subject    = stripcslashes($pResult['send_subject']);
			$pResultValue[$pCnt]->send_content    = stripcslashes($pResult['send_content']);
			$pResultValue[$pCnt]->count_sn    = stripcslashes($pResult['count_sn']);
			
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
			$this->name       = $pResult[$pCnt]->name;
			$this->email    = $pResult[$pCnt]->email;
			$this->reg_date    = $pResult[$pCnt]->reg_date;
			$this->send_date    = $pResult[$pCnt]->send_date;
			$this->send_subject    = $pResult[$pCnt]->send_subject;
			$this->send_content    = $pResult[$pCnt]->send_content;
			$this->count_sn    = $pResult[$pCnt]->count_sn;

			
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
			$this->name       = '';
			$this->email    = '';
            $this->reg_date      = '';
            $this->send_date      = '';
            $this->send_subject      = '';
            $this->send_content      = '';
            $this->count_sn      = '';
			
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

        function ArrEmail($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('성명','이메일');
                    $SelectValue = array('name','email');
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