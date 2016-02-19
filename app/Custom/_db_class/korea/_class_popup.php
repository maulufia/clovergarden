<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 팝업 클래스
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    settype($nPopup, 'object');

    class PopupClass
    {
        var $seq;               //고유번호
        var $hidden;            //상태값(1:노출/2:숨김/3:삭제)
        var $popup_type;        //팝업스타일(1:현재창/2:새창)
        var $start_date;        //시작일
        var $end_date;          //종료일
        var $edit_date;         //최종수정일
        var $popup_top;         //팝업창상단
        var $popup_left;        //팝업창좌측
        var $subject;           //제목
        var $url;               //url
        var $file_real;
        var $file_edit;
        var $file_byte;

        var $file_up_cnt;       //파일업로드갯수
        var $file_volume;       //파일용량제한
        var $file_mime_type;    //파일업로드타입
        var $file_pre_name;     //파일삭제변수

        var $top_page_result;   //최상위 리스트 리턴값
        var $page_result;       //리스트 리턴값
        var $read_result;       //상세보기 리턴값
        var $result_data;       //리턴값

        var $where;             //SQL WHERE문
        var $order_by;          //정렬

        var $table_name;        //테이블명
        var $sub_sql;           //서브쿼리

        function PopupClass()
        {
            $this->table_name = 'new_tb_kor_popup';
            $this->sub_sql    = '';
            $this->page_view  = 10;
            $this->page_set   = 5;
            $this->page_where = 5;

            $this->file_real[1];
            $this->file_edit[1];
            $this->file_byte[1];
            $this->file_pre_name[1];

            $this->file_up_cnt       = 1;
            $this->file_volume[1]    = 3;
            $this->file_mime_type[1] = 'image';
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
            $pResultValue[$pCnt]->hidden       = stripcslashes($pResult['hidden']);
            $pResultValue[$pCnt]->popup_type   = stripcslashes($pResult['popup_type']);
            $pResultValue[$pCnt]->start_date   = stripcslashes($pResult['start_date']);
            $pResultValue[$pCnt]->end_date     =  stripcslashes($pResult['end_date']);
            $pResultValue[$pCnt]->edit_date    =  stripcslashes($pResult['edit_date']);
            $pResultValue[$pCnt]->popup_top    =  stripcslashes($pResult['popup_top']);
            $pResultValue[$pCnt]->popup_left   =  stripcslashes($pResult['popup_left']);
            $pResultValue[$pCnt]->subject      = stripcslashes($pResult['subject']);
            $pResultValue[$pCnt]->url          = stripcslashes($pResult['url']);
            $pResultValue[$pCnt]->file_real[1] = stripcslashes($pResult['file_real1']);
            $pResultValue[$pCnt]->file_edit[1] = stripcslashes($pResult['file_edit1']);
            $pResultValue[$pCnt]->file_byte[1] = stripcslashes($pResult['file_byte1']);
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
            $this->hidden       = $pResult[$pCnt]->hidden;
            $this->popup_type   = $pResult[$pCnt]->popup_type;
            $this->start_date   = $pResult[$pCnt]->start_date;
            $this->end_date     = $pResult[$pCnt]->end_date;
            $this->edit_date    = $pResult[$pCnt]->edit_date;
            $this->popup_top    = $pResult[$pCnt]->popup_top;
            $this->popup_left   = $pResult[$pCnt]->popup_left;
            $this->subject      = $pResult[$pCnt]->subject;
            $this->url          = $pResult[$pCnt]->url;
            $this->file_real[1] = $pResult[$pCnt]->file_real[1];
            $this->file_edit[1] = $pResult[$pCnt]->file_edit[1];
            $this->file_byte[1] = $pResult[$pCnt]->file_byte[1];
            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    $this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
                }
            }
        }

        function ArrClear($pJoin=null)
        {
            $this->seq          = '';
            $this->hidden       = '';
            $this->popup_type   = '';
            $this->start_date   = '';
            $this->end_date     = '';
            $this->edit_date    = '';
            $this->popup_top    = '';
            $this->popup_left   = '';
            $this->subject      = '';
            $this->url          = '';
            $this->file_real[1] = '';
            $this->file_edit[1] = '';
            $this->file_byte[1] = '';
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

        function ArrPopup($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('제목');
                    $SelectValue = array('subject');
                    break;
                case 'hidden' :
                    $SelectField = array("노출","숨김");
                    $SelectValue = array('1','2');
                    break;
                case 'popup_type' :
                    $SelectField = array("현재창","새창");
                    $SelectValue = array('1','2');
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