<?php
    settype($nMember, 'object');

    class PointClass
    {
        var $seq;                 //������ȣ
		var $user_state;		  //���
		var $user_name;           //�̸�
		var $group_name;           //�׷��̸�
        var $user_id;			  //���̵�
        var $user_pw;             //��й�ȣ
        var $user_birth;          //�������
		var $user_gender;         //����		
        var $user_cell;           //����ó2
		var $file_real;
        var $file_edit;
        var $file_byte;
		var $reg_date;            //���Գ�¥
        var $date_cnt;

        //-- join column start --//
        var $comment_cnt;       //��ۼ�(��������)
        //-- join column end --//

        var $file_up_cnt;       //���Ͼ��ε尹��
        var $file_volume;       //���Ͽ뷮����
        var $file_mime_type;    //���Ͼ��ε�Ÿ��
        var $file_pre_name;     //���ϻ�������

        var $top_page_result;   //�ֻ��� ����Ʈ ���ϰ�
        var $page_result;       //����Ʈ ���ϰ�
        var $read_result;       //�󼼺��� ���ϰ�
        var $result_data;       //���ϰ�

        var $total_record;      //�ѷ��ڵ尹��
        var $where;             //SQL WHERE��
        var $order_by;          //����

        var $page_view;         //�������ۼ�
        var $page_set;          //������ ��ȣ ����
        var $page_where;        //������ �̵� ����

        var $table_name;        //���̺��
        var $sub_sql;           //��������

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
            $pResultValue[$pCnt]->idx          = stripcslashes($pResult['idx']);
			$pResultValue[$pCnt]->signdate          = stripcslashes($pResult['signdate']);
			$pResultValue[$pCnt]->depth          = stripcslashes($pResult['depth']);
            $pResultValue[$pCnt]->inpoint      = stripcslashes($pResult['inpoint']);
			$pResultValue[$pCnt]->outpoint      = stripcslashes($pResult['outpoint']);
			$pResultValue[$pCnt]->usepoint      = stripcslashes($pResult['usepoint']);
            $pResultValue[$pCnt]->userid      = stripcslashes($pResult['userid']);
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
            $this->idx        = $pResult[$pCnt]->idx;
			$this->signdate        = $pResult[$pCnt]->signdate;
			$this->depth        = $pResult[$pCnt]->depth;
            $this->inpoint    = $pResult[$pCnt]->inpoint;
			$this->outpoint    = $pResult[$pCnt]->outpoint;
			$this->usepoint    = $pResult[$pCnt]->usepoint;
            $this->userid    = $pResult[$pCnt]->userid;
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
                    $SelectField = array('�̸�','�޴���','�̸���');
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