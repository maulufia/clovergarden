<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 팝업 클래스
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    settype($nSns, 'object');

    class SnsClass
    {
        var $seq;               //고유번호
        var $type;            //상태값(1:노출/2:숨김/3:삭제)
        var $subject;           //제목
        var $url;               //url


        var $top_page_result;   //최상위 리스트 리턴값
        var $page_result;       //리스트 리턴값
        var $read_result;       //상세보기 리턴값
        var $result_data;       //리턴값

        var $where;             //SQL WHERE문
        var $order_by;          //정렬

        var $table_name;        //테이블명
        var $sub_sql;           //서브쿼리

        function SnsClass()
        {
            $this->table_name = 'new_tb_kor_sns';
            $this->sub_sql    = '';
            $this->page_view  = 10;
            $this->page_set   = 5;
            $this->page_where = 5;
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
            $pResultValue[$pCnt]->seq          = stripcslashes($pResult['seq']);
            $pResultValue[$pCnt]->type       = stripcslashes($pResult['type']);         
            $pResultValue[$pCnt]->subject      = stripcslashes($pResult['subject']);
            $pResultValue[$pCnt]->url          = stripcslashes($pResult['url']);
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    $this->JoinVar($pResult, $pCnt, $pJoin[$i] , 1, $pResultValue);
                }
            }
            return $pResultValue[$pCnt];
        }

        function VarList($pResult, $pCnt=0, $pJoin=null)
        {
            $this->seq          = $pResult[$pCnt]->seq;
            $this->type       = $pResult[$pCnt]->type;
            $this->subject      = $pResult[$pCnt]->subject;
            $this->url          = $pResult[$pCnt]->url;
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    $this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
                }
            }
        }

        function ArrClear($pJoin=null)
        {
            $this->seq          = '';
            $this->type       = '';
            $this->subject      = '';
            $this->url          = '';
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    $this->JoinVar($pResult, $pCnt, $pJoin[$i] , 3);
                }
            }
        }

        function JoinVar($pResult='', $pCnt=0, $pJoin=null, $pType='', $pResultValue='')
        {
            /*
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
            */
        }

        function ArrSns($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('제목');
                    $SelectValue = array('subject');
                    break;
                case 'type' :
                    $SelectField = array("페이스북","트위터","인스타그램","네이버블로그");
                    $SelectValue = array('1','2','3','4');
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