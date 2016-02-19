<?php
    settype($nStats, 'object');

    class StatsClass
    {
        var $user_ip;           //접속자IP
        var $stats_date;        //날짜
        var $stats_cnt;         //카운트

        var $date_cnt;

        var $page_result;       //리스트 리턴값
        var $read_result;       //상세보기 리턴값
        var $result_data;       //리턴값

        var $total_record;      //총레코드갯수
        var $where;             //SQL WHERE문
        var $order_by;          //정렬

        var $table_name;        //테이블명

        function StatsClass()
        {
            $this->table_name = 'new_tb_stats';
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
            $pResultValue[$pCnt]->user_ip    = stripcslashes($pResult['user_ip']);
            $pResultValue[$pCnt]->stats_date = stripcslashes($pResult['stats_date']);
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
            $this->user_ip    = $pResult[$pCnt]->user_ip;
            $this->stats_date = $pResult[$pCnt]->stats_date;
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
            $this->user_ip    = '';
            $this->stats_date = '';
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

        function JoinVar($pResult='', $pCnt=0, $pJoin=null, $pType='', $pResultValue='')
        {
            if($pType == '1'){
                switch($pJoin)
                {
                    case 'state' :
                        $pResultValue[$pCnt]->date_cnt = stripcslashes($pResult['date_cnt']);
                        break;
                }
            }elseif($pType == '2'){
                switch($pJoin)
                {
                    case 'state' :
                        $this->date_cnt = $pResult[$pCnt]->date_cnt;
                        break;
                }
            }elseif($pType == '3'){
                switch($pJoin)
                {
                    case 'state' :
                        $this->date_cnt = '';
                        break;
                }
            }
        }

    }//end class
?>