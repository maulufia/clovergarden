<?php
    settype($nMessage, 'object');

    class MessageClass
    {
        var $seq;                 //고유번호
		var $send_id;		  //보낸사람
		var $receive_id;		  //받는사람
		var $content;		  //내용
        var $hit;                 //조회수
		var $reg_date;            //보낸날짜

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

        function MessageClass()
        {
            $this->table_name = 'new_tb_message';
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
            $pResultValue[$pCnt] = new StdClass();
            $pResultValue[$pCnt]->seq          = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->send_id          = stripcslashes($pResult['send_id']);
            $pResultValue[$pCnt]->receive_id      = stripcslashes($pResult['receive_id']);
			$pResultValue[$pCnt]->content      = stripcslashes($pResult['content']);
			$pResultValue[$pCnt]->hit      = stripcslashes($pResult['hit']);
			$pResultValue[$pCnt]->reg_date      = stripcslashes($pResult['reg_date']);
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
			$this->send_id        = $pResult[$pCnt]->send_id;
            $this->receive_id    = $pResult[$pCnt]->receive_id;
			$this->content    = $pResult[$pCnt]->content;
			$this->hit    = $pResult[$pCnt]->hit;	
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
			$this->send_id    = '';
            $this->receive_id    = '';
			$this->content    = '';
			$this->hit    = '';	
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

		function ArrMessage($pVal, $pName, $pOption, $pKind, $pWrite='')
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