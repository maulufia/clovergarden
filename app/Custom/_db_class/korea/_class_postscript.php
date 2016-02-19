<?php
    settype($nPostScript, 'object');

    class PostScriptClass
    {
        var $seq;               //고유번호
        var $writer;
        var $subject;           //제목
		var $category;           //제목
        var $name;        //글쓴이
		var $content;        //글쓴이
		var $hit;        //조회수
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

        function PostScriptClass()
        {
            $this->table_name = 'new_tb_kor_postscript';
            $this->sub_sql    = '';
            $this->page_view  = 20;
			$this->admin_page_view  = 20;
            $this->page_set   = 10;
            $this->page_where = 10;
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
            $pResultValue[$pCnt]->subject       = stripcslashes($pResult['subject']);
			$pResultValue[$pCnt]->name       = stripcslashes($pResult['name']);
			$pResultValue[$pCnt]->content       = stripcslashes($pResult['content']);
			$pResultValue[$pCnt]->category       = stripcslashes($pResult['category']);
			$pResultValue[$pCnt]->hit      = stripcslashes($pResult['hit']);
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
            $this->subject       = $pResult[$pCnt]->subject;
			$this->name       = $pResult[$pCnt]->name;
            $this->content   = $pResult[$pCnt]->content;
			$this->category   = $pResult[$pCnt]->category;
			$this->hit      = $pResult[$pCnt]->hit;
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
            $this->subject       = '';		
			$this->name       = '';	
            $this->content       = '';
			$this->category       = '';
			$this->hit       = '';
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

        function ArrPostScript($pVal, $pName, $pOption, $pKind, $pWrite='')
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
				case 'kcategory' :
                    $SelectField = array("전체","눈성형","코성형","안면윤곽","가슴성형","바디라인","안티에이징","리프팅","남자성형","스킨클리닉");
                    $SelectValue = array("0",'1','2','3','4','5','6','7','8','9');
                    break;
				case 'category' :
                    $SelectField = array("전체","눈성형","코성형","안면윤곽","가슴성형","바디라인","안티에이징","리프팅","남자성형","스킨클리닉");
                    $SelectValue = array('0','1','2','3','4','5','6','7','8','9');
                    break;
            }
            if($pWrite == ''){
                WriteSelect($SelectField, $SelectValue, $pVal, $pName, $pOption);
            }elseif($pWrite == 'radio'){
                WriteCheck($pVal, $pName, $pWrite, join(',',$SelectValue), join(',',$SelectField));
            }elseif($pWrite == 'checkbox'){
                WriteMultiCheck($pVal, $pName, $pWrite, join(',',$SelectValue), join(',',$SelectField));
            }elseif($pWrite == 'list'){
                WriteList($SelectField, $SelectValue, $pVal, $pName, $pOption);
            }else{
                echo $SelectField[array_search($pVal, $SelectValue)];
            }
        }


    }//end class
?>