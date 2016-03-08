<?php
    settype($nSponsor, 'object');

    class SponsorClass
    {
        var $seq;               //고유번호
		var $file_real;
        var $file_edit;
        var $file_byte;
        var $subject;				//기관명
		var $content;           //기관소개
        var $url;               //조회수
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

        function SponsorClass()
        {
            $this->table_name = 'new_tb_sponsor';
            $this->sub_sql    = '';
			$this->admin_page_view  = 20;
            $this->page_view  = 15;
            $this->page_set   = 10;
            $this->page_where = 10;

            $this->file_real[1];
            $this->file_edit[1];
            $this->file_byte[1];
            $this->file_pre_name[1];

            $this->file_up_cnt       = 1;
            $this->file_volume[1]    = 10;
            $this->file_mime_type[1] = 'image';
        }


        function ArrList($pResult, $pCnt, $pJoin=null)
        {
            $pResultValue = array();
            $pResultValue[$pCnt] = new StdClass();
            $pResultValue[$pCnt]->seq           = stripcslashes($pResult['seq']);
			$pResultValue[$pCnt]->subject    = stripcslashes($pResult['subject']);
            $pResultValue[$pCnt]->content    = stripcslashes($pResult['content']);
			$pResultValue[$pCnt]->file_real[1]   = stripcslashes($pResult['file_real1']);
            $pResultValue[$pCnt]->file_edit[1]   = stripcslashes($pResult['file_edit1']);
            $pResultValue[$pCnt]->file_byte[1]   = stripcslashes($pResult['file_byte1']);
			$pResultValue[$pCnt]->url    = stripcslashes($pResult['url']);
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
			$this->subject    = $pResult[$pCnt]->subject;
			$this->content    = $pResult[$pCnt]->content;
			$this->file_real[1]   = $pResult[$pCnt]->file_real[1];
            $this->file_edit[1]   = $pResult[$pCnt]->file_edit[1];
            $this->file_byte[1]   = $pResult[$pCnt]->file_byte[1];
			$this->url    = $pResult[$pCnt]->url;
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
            $this->content      = '';
			$this->file_real[1]   = '';
            $this->file_edit[1]   = '';
            $this->file_byte[1]   = '';
			$this->url      = '';
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

        function ArrSponsor($pVal, $pName, $pOption, $pKind, $pWrite='')
        {
            switch($pKind)
            {
                case 'search' :
                    $SelectField = array('제목','내용');
                    $SelectValue = array('subject','content');
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