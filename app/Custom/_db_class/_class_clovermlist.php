<?php
    settype($nClovermlist, 'object');

    class ClovermlistClass
    {
        var $seq;               //고유번호
		var $otype;               //이체/신용
		var $order_num;			//후원그룹
		var $clover_seq;			//후원그룹
		var $name;	
		var $birth;		
		var $group_name;			
		var $id;
        var $price;
        var $day;
		var $start;
		var $zip;
        var $address;	
		var $cell;
		var $email;
		var $bank;
		var $banknum;
		var $bankdate;
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

        function ClovermlistClass()
        {
            $this->table_name = 'new_tb_clover_mlist';
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
            $pResultValue[$pCnt]->seq           = isset($pResult['seq']) ? stripcslashes($pResult['seq']) : 0;
			$pResultValue[$pCnt]->otype           = isset($pResult['otype']) ? stripcslashes($pResult['otype']) : '';
			$pResultValue[$pCnt]->order_num       = isset($pResult['order_num']) ? stripcslashes($pResult['order_num']) : 0;
			$pResultValue[$pCnt]->clover_seq       = isset($pResult['clover_seq']) ? stripcslashes($pResult['clover_seq']) : 0;
            $pResultValue[$pCnt]->name       = isset($pResult['name']) ? stripcslashes($pResult['name']) : '';
		    $pResultValue[$pCnt]->birth       = isset($pResult['birth']) ? stripcslashes($pResult['birth']) : '';
            $pResultValue[$pCnt]->group_name       = isset($pResult['group_name']) ? stripcslashes($pResult['group_name']) : '';
			$pResultValue[$pCnt]->id    = isset($pResult['id']) ? stripcslashes($pResult['id']) : '';
            $pResultValue[$pCnt]->price    = isset($pResult['price']) ? stripcslashes($pResult['price']) : 0;
			$pResultValue[$pCnt]->day   = isset($pResult['day']) ? stripcslashes($pResult['day']) : 0;
			$pResultValue[$pCnt]->start   = isset($pResult['start']) ? stripcslashes($pResult['start']) : '';
			$pResultValue[$pCnt]->zip   = isset($pResult['zip']) ? stripcslashes($pResult['zip']) : '';
            $pResultValue[$pCnt]->address   = isset($pResult['address']) ? stripcslashes($pResult['address']) : '';
			$pResultValue[$pCnt]->cell   = isset($pResult['cell']) ? stripcslashes($pResult['cell']) : '';
			$pResultValue[$pCnt]->email   = isset($pResult['email']) ? stripcslashes($pResult['email']) : '';
			$pResultValue[$pCnt]->bank   = isset($pResult['bank']) ? stripcslashes($pResult['bank']) : '';
			$pResultValue[$pCnt]->banknum   = isset($pResult['banknum']) ? stripcslashes($pResult['banknum']) : 0;
			$pResultValue[$pCnt]->bankdate   = isset($pResult['bankdate']) ? stripcslashes($pResult['bankdate']) : '';
			$pResultValue[$pCnt]->reg_date    = isset($pResult['regdate']) ? stripcslashes($pResult['reg_date']) : '';
			$pResultValue[$pCnt]->type    = isset($pResult['type']) ? stripcslashes($pResult['type']) : '';
			$pResultValue[$pCnt]->order_ck    = isset($pResult['order_ck']) ? stripcslashes($pResult['order_ck']) : '';
			$pResultValue[$pCnt]->order_adm_ck    = isset($pResult['order_adm_ck']) ? stripcslashes($pResult['order_adm_ck']) : '';
			$pResultValue[$pCnt]->ing_cencle    = isset($pResult['ing_cencle']) ? stripcslashes($pResult['ing_cencle']) : '';


            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $pResultValue[$pCnt]->comment_cnt = stripcslashes($pResult['comment_cnt']);
                            break;
						case 'name' :
                            $pResultValue[$pCnt]->name = stripcslashes($pResult['name']);
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
			$this->otype           = $pResult[$pCnt]->otype;
			$this->order_num       = $pResult[$pCnt]->order_num;
			$this->clover_seq       = $pResult[$pCnt]->clover_seq;
            $this->name       = $pResult[$pCnt]->name;
			$this->birth       = $pResult[$pCnt]->birth;
            $this->group_name       = $pResult[$pCnt]->group_name;
			$this->id    = $pResult[$pCnt]->id;
			$this->price    = $pResult[$pCnt]->price;
			$this->day   = $pResult[$pCnt]->day;
			$this->start   = $pResult[$pCnt]->start;
			$this->zip   = $pResult[$pCnt]->zip;
            $this->address   = $pResult[$pCnt]->address;
			$this->cell   = $pResult[$pCnt]->cell;
			$this->email   = $pResult[$pCnt]->email;
			$this->bank   = $pResult[$pCnt]->bank;
			$this->banknum   = $pResult[$pCnt]->banknum;
			$this->bankdate   = $pResult[$pCnt]->bankdate;
			$this->reg_date    = $pResult[$pCnt]->reg_date;
			$this->type    = $pResult[$pCnt]->type;
			$this->order_ck    = $pResult[$pCnt]->order_ck;
			$this->order_adm_ck    = $pResult[$pCnt]->order_adm_ck;
			$this->ing_cencle    = $pResult[$pCnt]->ing_cencle;

			

            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            if(isset($pResult[$pCnt]->comment_cnt))
                                $this->comment_cnt = $pResult[$pCnt]->comment_cnt;
                            break;

					    case 'name' :
                            $this->name = $pResult[$pCnt]->name;
                            break;
                    }
                    //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
                }
            }
        }

        function ArrClear($pJoin=null)
        {
            $this->seq            = '';
			$this->otype            = '';
			$this->order_num       = '';
			$this->clover_seq       = '';
            $this->name        = '';
			$this->birth        = '';
            $this->group_name    = '';
			$this->id     = '';
			$this->price = '';
			$this->day    = '';
			$this->start   = '';
			$this->zip   = '';
            $this->address    = '';
			$this->cell    = '';
			$this->email    = '';
			$this->bank    = '';
			$this->banknum    = '';
			$this->bankdate    = '';
			$this->reg_date     = '';
			$this->type     = '';
			$this->order_ck     = '';
			$this->order_ck     = '';
			$this->ing_cencle     = '';

            if(count($pJoin)){
                for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
                    switch($pJoin[$i])
                    {
                        case 'comment' :
                            $this->comment_cnt = '';
                            break;
						case 'name' :
                            $this->name = '';
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
					case 'name' :
                        $pResultValue[$pCnt]->name = stripcslashes($pResult['name']);
                        break;
                }
            }elseif($pType == '2'){
                switch($pJoin)
                {
                    case 'comment' :
                        $this->comment_cnt = $pResult[$pCnt]->comment_cnt;
                        break;
					case 'name' :
                        $this->name = $pResult[$pCnt]->name;
                        break;
                }
            }elseif($pType == '3'){
                switch($pJoin)
                {
                    case 'comment' :
                        $this->comment_cnt = '';
                        break;
					case 'name' :
                        $this->name = '';
                        break;
                }
            }
        }

        function ArrClovermlist($pVal, $pName, $pOption, $pKind, $pWrite='')
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