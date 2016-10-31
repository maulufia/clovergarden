<?php
settype($nClover, 'object');

class CloverClass
{
  var $seq;               //고유번호
  var $code;			//코드
  var $category;			//카테고리
  var $group;			//후원그룹
  var $people;		//후원개인
  var $news;				//소식
  var $file_real;
  var $file_edit;
  var $file_byte;
  var $subject;				//기관명
  var $content;           //기관소개
  var $hit;               //조회수
  var $reg_date;          //등록일

  // 왜 clover banner가 여기 있는가
  var $clover_id;

  //-- join column start --//
  var $comment_cnt;       //댓글수(서브쿼리)
  var $name;       //후원기관명(서브쿼리)
  var $clover;       //후원자(서브쿼리)3
  var $clover_group;

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

  function CloverClass()
  {
    $this->table_name = 'new_tb_clover';
    $this->sub_sql    = '';
    $this->admin_page_view  = 20;
    $this->page_view  = 15;
    $this->page_set   = 10;
    $this->page_where = 10;

    $this->file_real[1];
    $this->file_edit[1];
    $this->file_byte[1];
    $this->file_pre_name[1];

    $this->file_real[2];
    $this->file_edit[2];
    $this->file_byte[2];
    $this->file_pre_name[2];

    $this->file_up_cnt       = 2;
    $this->file_volume[1]    = 10;
    $this->file_mime_type[1] = 'image';
    $this->file_volume[2]    = 10;
    $this->file_mime_type[2] = 'image';

    // Added by YJM
    $this->mobile_thumbnail;
    $this->mobile_intro;
    $this->mobile_image1;
    $this->mobile_image2;
    $this->mobile_image3;
  }


  function ArrList($pResult, $pCnt, $pJoin=null)
  {
    $pResultValue = array();
    $pResultValue[$pCnt] = new StdClass();
    $pResultValue[$pCnt]->seq           = stripcslashes($pResult['seq']);
    $pResultValue[$pCnt]->code       = stripcslashes($pResult['code']);
    $pResultValue[$pCnt]->category       = stripcslashes($pResult['category']);
    $pResultValue[$pCnt]->group       = isset($pResult['group']) ? stripcslashes($pResult['group']) : '';
    $pResultValue[$pCnt]->people       = stripcslashes($pResult['people']);
    $pResultValue[$pCnt]->news       = stripcslashes($pResult['news']);
    $pResultValue[$pCnt]->subject    = stripcslashes($pResult['subject']);
    $pResultValue[$pCnt]->content    = stripcslashes($pResult['content']);
    $pResultValue[$pCnt]->hot    = stripcslashes($pResult['hot']);
    $pResultValue[$pCnt]->view_n    = stripcslashes($pResult['view_n']);
    $pResultValue[$pCnt]->file_real[1]   = stripcslashes($pResult['file_real1']);
    $pResultValue[$pCnt]->file_edit[1]   = stripcslashes($pResult['file_edit1']);
    $pResultValue[$pCnt]->file_byte[1]   = stripcslashes($pResult['file_byte1']);
    $pResultValue[$pCnt]->file_real[2]   = stripcslashes($pResult['file_real2']);
    $pResultValue[$pCnt]->file_edit[2]   = stripcslashes($pResult['file_edit2']);
    $pResultValue[$pCnt]->file_byte[2]   = stripcslashes($pResult['file_byte2']);
    $pResultValue[$pCnt]->hit    = stripcslashes($pResult['hit']);
    $pResultValue[$pCnt]->reg_date    = stripcslashes($pResult['reg_date']);
    $pResultValue[$pCnt]->group_name    = stripcslashes($pResult['group_name']);
    $pResultValue[$pCnt]->clover_id   = isset($pResult['clover_id']) ? stripcslashes($pResult['clover_id']) : 0;
    $pResultValue[$pCnt]->mobile_thumbnail = stripcslashes($pResult['mobile_thumbnail']);
    $pResultValue[$pCnt]->mobile_intro = stripcslashes($pResult['mobile_intro']);
    $pResultValue[$pCnt]->mobile_image1 = stripcslashes($pResult['mobile_image1']);
    $pResultValue[$pCnt]->mobile_image2 = stripcslashes($pResult['mobile_image2']);
    $pResultValue[$pCnt]->mobile_image3 = stripcslashes($pResult['mobile_image3']);

    if(count($pJoin)){
      for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
        switch($pJoin[$i])
        {
          case 'comment' :
          $pResultValue[$pCnt]->comment_cnt = isset($pResult['comment_cnt']) ? stripcslashes($pResult['comment_cnt']) : null;
          break;
          case 'name' :
          $pResultValue[$pCnt]->name = stripcslashes($pResult['name']);
          break;
          case 'clover' :
          $pResultValue[$pCnt]->clover = stripcslashes($pResult['clover']);
          break;
          case 'clover_group' :
          $pResultValue[$pCnt]->clover_group = stripcslashes($pResult['clover_group']);
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
    $this->code       = $pResult[$pCnt]->code;
    $this->category       = $pResult[$pCnt]->category;
    $this->group       = $pResult[$pCnt]->group;
    $this->people       = $pResult[$pCnt]->people;
    $this->news       = $pResult[$pCnt]->news;
    $this->subject    = $pResult[$pCnt]->subject;
    $this->content    = $pResult[$pCnt]->content;
    $this->hot    = $pResult[$pCnt]->hot;
    $this->view_n    = $pResult[$pCnt]->view_n;
    $this->file_real[1]   = $pResult[$pCnt]->file_real[1];
    $this->file_edit[1]   = $pResult[$pCnt]->file_edit[1];
    $this->file_byte[1]   = $pResult[$pCnt]->file_byte[1];
    $this->file_real[2]   = $pResult[$pCnt]->file_real[2];
    $this->file_edit[2]   = $pResult[$pCnt]->file_edit[2];
    $this->file_byte[2]   = $pResult[$pCnt]->file_byte[2];
    $this->hit    = $pResult[$pCnt]->hit;
    $this->reg_date    = $pResult[$pCnt]->reg_date;
    $this->group_name    = $pResult[$pCnt]->group_name;
    $this->clover_id = $pResult[$pCnt]->clover_id;
    $this->mobile_thumbnail = $pResult[$pCnt]->mobile_thumbnail;
    $this->mobile_intro = $pResult[$pCnt]->mobile_intro;
    $this->mobile_image1 = $pResult[$pCnt]->mobile_image1;
    $this->mobile_image2 = $pResult[$pCnt]->mobile_image2;
    $this->mobile_image3 = $pResult[$pCnt]->mobile_image3;

    if(count($pJoin)){
      for($i=0, $cnt=count($pJoin); $i < $cnt; $i++) {
        switch($pJoin[$i])
        {
          case 'comment' :
          if(isset($pResult[$pCnt]->comment_cnt))
          $this->comment_cnt = $pResult[$pCnt]->comment_cnt;
          break;

          case 'name' :
          if(isset($pResult[$pCnt]->name))
          $this->name = $pResult[$pCnt]->name;
          break;
          case 'clover' :
          $this->clover = $pResult[$pCnt]->clover;
          break;
          case 'clover_group' :
          $this->clover_group = $pResult[$pCnt]->clover_group;
          break;
        }
        //$this->JoinVar($pResult, $pCnt, $pJoin[$i] , 2);
      }
    }
  }

  function ArrClear($pJoin=null)
  {
    $this->seq           = '';
    $this->code       = '';
    $this->category       = '';
    $this->group       = '';
    $this->people       = '';
    $this->news       = '';
    $this->subject    = '';
    $this->content      = '';
    $this->file_real[1]   = '';
    $this->file_edit[1]   = '';
    $this->file_byte[1]   = '';
    $this->file_real[2]   = '';
    $this->file_edit[2]   = '';
    $this->file_byte[2]   = '';
    $this->hit      = '';
    $this->reg_date      = '';
    $this->group_name      = '';

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
          case 'clover' :
          $this->clover = '';
          break;
          case 'clover_group' :
          $this->clover_group = '';
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
        case 'clover' :
        $pResultValue[$pCnt]->name = stripcslashes($pResult['clover']);
        break;
        case 'clover_group' :
        $pResultValue[$pCnt]->name = stripcslashes($pResult['clover_group']);
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
        case 'clover' :
        $this->clover = $pResult[$pCnt]->clover;
        break;
        case 'clover_group' :
        $this->clover_group = $pResult[$pCnt]->clover_group;
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
        case 'clover' :
        $this->clover = '';
        break;
        case 'clover_group' :
        $this->clover_group = '';
        break;
      }
    }
  }

  function ArrClover($pVal, $pName, $pOption, $pKind, $pWrite='')
  {
    switch($pKind)
    {
      case 'search' :
      $SelectField = array('제목','내용');
      $SelectValue = array('subject','content');
      break;
      case 'category' :
      $SelectField = array('아동','환경','질병','노인','저소득지역','교육','여성');
      $SelectValue = array('1','2','3','4','5','6','7');
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
