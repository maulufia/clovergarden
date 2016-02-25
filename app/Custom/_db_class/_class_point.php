<?php
    settype($nMember, 'object');

    class PointClass
    {
        var $seq;                 //°íÀ¯¹øÈ£
		var $user_state;		  //µî±Þ
		var $user_name;           //ÀÌ¸§
		var $group_name;           //±×·ìÀÌ¸§
        var $user_id;			  //¾ÆÀÌµð
        var $user_pw;             //ºñ¹Ð¹øÈ£
        var $user_birth;          //»ý³â¿ùÀÏ
		var $user_gender;         //¼ºº°		
        var $user_cell;           //¿¬¶ôÃ³2
		var $file_real;
        var $file_edit;
        var $file_byte;
		var $reg_date;            //°¡ÀÔ³¯Â¥
        var $date_cnt;

        //-- join column start --//
        var $comment_cnt;       //´ñ±Û¼ö(¼­ºêÄõ¸®)
        //-- join column end --//

        var $file_up_cnt;       //ÆÄÀÏ¾÷·Îµå°¹¼ö
        var $file_volume;       //ÆÄÀÏ¿ë·®Á¦ÇÑ
        var $file_mime_type;    //ÆÄÀÏ¾÷·ÎµåÅ¸ÀÔ
        var $file_pre_name;     //ÆÄÀÏ»èÁ¦º¯¼ö

        var $top_page_result;   //ÃÖ»óÀ§ ¸®½ºÆ® ¸®ÅÏ°ª
        var $page_result;       //¸®½ºÆ® ¸®ÅÏ°ª
        var $read_result;       //»ó¼¼º¸±â ¸®ÅÏ°ª
        var $result_data;       //¸®ÅÏ°ª

        var $total_record;      //ÃÑ·¹ÄÚµå°¹¼ö
        var $where;             //SQL WHERE¹®
        var $order_by;          //Á¤·Ä

        var $page_view;         //ÆäÀÌÁö±Û¼ö
        var $page_set;          //ÆäÀÌÁö ¹øÈ£ °¹¼ö
        var $page_where;        //ÆäÀÌÁö ÀÌµ¿ °¹¼ö

        var $table_name;        //Å×ÀÌºí¸í
        var $sub_sql;           //¼­ºêÄõ¸®

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
            $pResultValue[$pCnt] = new StdClass();
            $pResultValue[$pCnt]->idx          = isset($pResult['idx']) ? stripcslashes($pResult['idx']) : 0;
			$pResultValue[$pCnt]->signdate          = isset($pResult['signdate']) ? stripcslashes($pResult['signdate']) : 0;
			$pResultValue[$pCnt]->depth          = isset($pResult['depth']) ? stripcslashes($pResult['depth']) : 0;
            $pResultValue[$pCnt]->inpoint      = isset($pResult['inpoint']) ? stripcslashes($pResult['inpoint']) : 0;
			$pResultValue[$pCnt]->outpoint      = isset($pResult['outpoint']) ? stripcslashes($pResult['outpoint']) : 0;
			$pResultValue[$pCnt]->usepoint      = isset($pResult['usepoint']) ? stripcslashes($pResult['usepoint']) : 0;
            $pResultValue[$pCnt]->userid      = isset($pResult['userid']) ? stripcslashes($pResult['userid']) : '';
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
            $this->idx        = isset($pResult[$pCnt]) ? $pResult[$pCnt]->idx : null;
			$this->signdate        = isset($pResult[$pCnt]) ? $pResult[$pCnt]->signdate : null;
			$this->depth        = isset($pResult[$pCnt]) ? $pResult[$pCnt]->depth : null;
            $this->inpoint    = isset($pResult[$pCnt]) ? $pResult[$pCnt]->inpoint : null;
			$this->outpoint    = isset($pResult[$pCnt]) ? $pResult[$pCnt]->outpoint : null;
			$this->usepoint    = isset($pResult[$pCnt]) ? $pResult[$pCnt]->usepoint : null;
            $this->userid    = isset($pResult[$pCnt]) ? $pResult[$pCnt]->userid : null;
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
                    $SelectField = array('ÀÌ¸§','ÈÞ´ëÆù','ÀÌ¸ÞÀÏ');
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