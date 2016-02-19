<?php
    settype($nMember, 'object');

    class MemberClass
    {
        var $seq;                 //고유번호
		var $user_state;		  //등급
		var $user_name;           //이름
		var $group_name;           //그룹이름
        var $user_id;			  //아이디
        var $user_pw;             //비밀번호
        var $user_birth;          //생년월일
		var $user_gender;         //성별		
        var $user_cell;           //연락처2
		var $file_real;
        var $file_edit;
        var $file_byte;
		var $reg_date;            //가입날짜
        var $date_cnt;

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

        function MemberClass()
        {
            $this->table_name = 'new_tb_member';
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
            $pResultValue[$pCnt]->seq          = stripcslashes($pResult['id']);
            $pResultValue[$pCnt]->user_state          = stripcslashes($pResult['user_state']);
            $pResultValue[$pCnt]->group_state          = stripcslashes($pResult['group_state']);
            $pResultValue[$pCnt]->user_name      = stripcslashes($pResult['user_name']);
            $pResultValue[$pCnt]->group_name      = stripcslashes($pResult['group_name']);
            $pResultValue[$pCnt]->user_id      = stripcslashes($pResult['user_id']);
            $pResultValue[$pCnt]->user_pw      = stripcslashes($pResult['password']);
            $pResultValue[$pCnt]->user_birth    = stripcslashes($pResult['user_birth']);			
            $pResultValue[$pCnt]->user_gender = stripcslashes($pResult['user_gender']);            
            $pResultValue[$pCnt]->user_cell    = stripcslashes($pResult['user_cell']);
            $pResultValue[$pCnt]->file_real[1]   = stripcslashes($pResult['file_real1']);
            $pResultValue[$pCnt]->file_edit[1]   = stripcslashes($pResult['file_edit1']);
            $pResultValue[$pCnt]->file_byte[1]   = stripcslashes($pResult['file_byte1']);
            $pResultValue[$pCnt]->reg_date   = stripcslashes($pResult['reg_date']);
            $pResultValue[$pCnt]->member_ok   = stripcslashes($pResult['member_ok']);
            $pResultValue[$pCnt]->clover_seq   = stripcslashes($pResult['clover_seq']);
            $pResultValue[$pCnt]->clover_win   = stripcslashes($pResult['clover_win']);
            $pResultValue[$pCnt]->clover_seq_adm   = stripcslashes($pResult['clover_seq_adm']);
            $pResultValue[$pCnt]->clover_seq_adm_type   = stripcslashes($pResult['clover_seq_adm_type']);
            $pResultValue[$pCnt]->update_ck   = stripcslashes($pResult['update_ck']);
            $pResultValue[$pCnt]->post1   = stripcslashes($pResult['post1']);
            $pResultValue[$pCnt]->post2   = stripcslashes($pResult['post2']);
            $pResultValue[$pCnt]->addr1   = stripcslashes($pResult['addr1']);
            $pResultValue[$pCnt]->addr2   = stripcslashes($pResult['addr2']);
            $pResultValue[$pCnt]->order_cencle   = stripcslashes($pResult['order_cencle']);
            $pResultValue[$pCnt]->login_ck   = stripcslashes($pResult['login_ck']);

			
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
			$this->user_state        = $pResult[$pCnt]->user_state;
			$this->group_state        = $pResult[$pCnt]->group_state;
            $this->user_name    = $pResult[$pCnt]->user_name;
			$this->group_name    = $pResult[$pCnt]->group_name;
			$this->user_id    = $pResult[$pCnt]->user_id;
            $this->user_pw    = $pResult[$pCnt]->user_pw;
            $this->user_birth  = $pResult[$pCnt]->user_birth;			
			$this->user_gender  = $pResult[$pCnt]->user_gender;			
			$this->user_cell  = $pResult[$pCnt]->user_cell;
			$this->file_real[1]   = $pResult[$pCnt]->file_real[1];
            $this->file_edit[1]   = $pResult[$pCnt]->file_edit[1];
            $this->file_byte[1]   = $pResult[$pCnt]->file_byte[1];
			$this->reg_date  = $pResult[$pCnt]->reg_date;
			$this->member_ok  = $pResult[$pCnt]->member_ok;
			$this->clover_seq  = $pResult[$pCnt]->clover_seq;
			$this->clover_win  = $pResult[$pCnt]->clover_win;
			$this->clover_seq_adm  = $pResult[$pCnt]->clover_seq_adm;
			$this->clover_seq_adm_type  = $pResult[$pCnt]->clover_seq_adm_type;
			$this->update_ck  = $pResult[$pCnt]->update_ck;
			$this->post1  = $pResult[$pCnt]->post1;
			$this->post2  = $pResult[$pCnt]->post2;
			$this->addr1  = $pResult[$pCnt]->addr1;
			$this->addr2  = $pResult[$pCnt]->addr2;
			$this->order_cencle = $pResult[$pCnt]->order_cencle;
			$this->login_ck = $pResult[$pCnt]->login_ck;
			

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
			$this->user_state    = '';
			$this->group_state    = '';
            $this->user_name    = '';
			$this->group_name    = '';
			$this->user_id    = '';
            $this->user_pw    = '';
            $this->user_birth  = '';			
			$this->user_gender  = '';			
			$this->user_cell  = '';
			$this->file_real[1]   = '';
            $this->file_edit[1]   = '';
            $this->file_byte[1]   = '';
			$this->reg_date  = '';
			$this->clover_seq  = '';
			$this->clover_win  = '';
			$this->clover_seq_adm  = '';
			$this->clover_seq_adm_type  = '';
			$this->update_ck  = '';
			$this->post1  = '';
			$this->post2  = '';
			$this->addr1  = '';
			$this->addr2  = '';
			$this->order_cencle  = '';
			$this->login_ck  = '';
			
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
                    $SelectField = array('이름','휴대폰','이메일');
                    $SelectValue = array('user_name','user_cell','user_id');
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