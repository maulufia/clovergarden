<?php
    settype($nFaq, 'object');

    class FaqClass
    {
        var $seq;               //고유번호
		var $writer;				//작성자ID
		var $writer_name;				//작성자 이름
        var $subject;				//제목
		var $content;           //답변
        var $hit;               //조회수
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

        function FaqClass()
        {
            $this->table_name = 'new_tb_faq';
            $this->sub_sql    = '';
			$this->admin_page_view  = 20;
            $this->page_view  = 15;
            $this->page_set   = 10;
            $this->page_where = 10;
        }


        function ArrList($pResult, $pCnt, $pJoin=null)
        {
            $pResultValue = array();
            $pResultValue[$pCnt] = new StdClass();
            $pResultValue[$pCnt]->seq           = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->writer    = isset($pResult['writer']) ? stripcslashes($pResult['writer']) : null;
			$pResultValue[$pCnt]->writer_name    = stripcslashes($pResult['writer_name']);
			$pResultValue[$pCnt]->subject    = stripcslashes($pResult['subject']);
            $pResultValue[$pCnt]->content    = stripcslashes($pResult['content']);
			$pResultValue[$pCnt]->hit    = stripcslashes($pResult['hit']);
			$pResultValue[$pCnt]->reg_date    = stripcslashes($pResult['reg_date']);
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $pResultValue[$pCnt]->comment_cnt = isset($pResult['comment_cnt']) ? stripcslashes($pResult['comment_cnt']) : null;
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
			$this->writer           = $pResult[$pCnt]->writer;
			$this->writer_name           = $pResult[$pCnt]->writer_name;
			$this->subject    = $pResult[$pCnt]->subject;
			$this->content    = $pResult[$pCnt]->content;
			$this->hit    = $pResult[$pCnt]->hit;
			$this->reg_date    = $pResult[$pCnt]->reg_date;
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            if(isset($pResult[$pCnt]->comment_cnt))
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
			$this->subject    = '';
			$this->writer    = '';
			$this->writer_name    = '';
            $this->content      = '';
			$this->hit      = '';
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

        function ArrFaq($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('제목','내용','작성자');
                    $SelectValue = array('subject','content','writer_name');
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