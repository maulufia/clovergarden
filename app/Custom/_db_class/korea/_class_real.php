<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   :
     * @description :
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    settype($nReal, 'object');

    class RealClass
    {
        var $seq;               //고유번호
        var $subject;           //제목
		var $file_real;
        var $file_edit;
        var $file_byte;
        var $content;           //내용
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

        function RealClass()
        {
            $this->table_name = 'new_tb_kor_real';
            $this->sub_sql    = '';
			$this->admin_page_view  = 20;
            $this->page_view  = 30;
            $this->page_set   = 10;
            $this->page_where = 10;

            $this->file_real[4];
            $this->file_edit[4];
            $this->file_byte[4];
            $this->file_pre_name[4];

            $this->file_up_cnt       = 4;
            $this->file_volume[1]    = 10;
            $this->file_volume[2]    = 10;
			$this->file_volume[3]    = 10;
			$this->file_volume[4]    = 10;
            $this->file_mime_type[1] = 'image';
            $this->file_mime_type[2] = 'image';
			$this->file_mime_type[3] = 'image';
            $this->file_mime_type[4] = 'image';

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
			$pResultValue[$pCnt]->subject       = stripcslashes($pResult['subject']);
			$pResultValue[$pCnt]->file_real[1]   = stripcslashes($pResult['file_real1']);
            $pResultValue[$pCnt]->file_edit[1]   = stripcslashes($pResult['file_edit1']);
            $pResultValue[$pCnt]->file_byte[1]   = stripcslashes($pResult['file_byte1']);
            $pResultValue[$pCnt]->file_real[2]   = stripcslashes($pResult['file_real2']);
            $pResultValue[$pCnt]->file_edit[2]   = stripcslashes($pResult['file_edit2']);
            $pResultValue[$pCnt]->file_byte[2]   = stripcslashes($pResult['file_byte2']);
			$pResultValue[$pCnt]->file_real[3]   = stripcslashes($pResult['file_real3']);
            $pResultValue[$pCnt]->file_edit[3]   = stripcslashes($pResult['file_edit3']);
            $pResultValue[$pCnt]->file_byte[3]   = stripcslashes($pResult['file_byte3']);
			$pResultValue[$pCnt]->file_real[4]   = stripcslashes($pResult['file_real4']);
            $pResultValue[$pCnt]->file_edit[4]   = stripcslashes($pResult['file_edit4']);
            $pResultValue[$pCnt]->file_byte[4]   = stripcslashes($pResult['file_byte4']);
            $pResultValue[$pCnt]->content       = stripcslashes($pResult['content']);
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
			$this->subject       = $pResult[$pCnt]->subject;
			$this->file_real[1] = $pResult[$pCnt]->file_real[1];
            $this->file_edit[1] = $pResult[$pCnt]->file_edit[1];
            $this->file_byte[1] = $pResult[$pCnt]->file_byte[1];
			$this->file_real[2] = $pResult[$pCnt]->file_real[2];
            $this->file_edit[2] = $pResult[$pCnt]->file_edit[2];
            $this->file_byte[2] = $pResult[$pCnt]->file_byte[2];
			$this->file_real[3] = $pResult[$pCnt]->file_real[3];
            $this->file_edit[3] = $pResult[$pCnt]->file_edit[3];
            $this->file_byte[3] = $pResult[$pCnt]->file_byte[3];
			$this->file_real[4] = $pResult[$pCnt]->file_real[4];
            $this->file_edit[4] = $pResult[$pCnt]->file_edit[4];
            $this->file_byte[4] = $pResult[$pCnt]->file_byte[4];
            $this->content       = $pResult[$pCnt]->content;
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
            $this->subject       = '';
			$this->file_real[1] = '';
            $this->file_edit[1] = '';
            $this->file_byte[1] = '';
			$this->file_real[2] = '';
            $this->file_edit[2] = '';
            $this->file_byte[2] = '';
			$this->file_real[3] = '';
            $this->file_edit[3] = '';
            $this->file_byte[3] = '';
			$this->file_real[4] = '';
            $this->file_edit[4] = '';
            $this->file_byte[4] = '';
            $this->content       = '';
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

        function ArrReal($pVal, $pName, $pOption, $pKind, $pWrite='')
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