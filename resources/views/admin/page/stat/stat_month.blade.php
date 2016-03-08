@extends('admin.common.husk')

@section('content')
<?php
  // initiate (공통)
  $page_key   = 'G2';
  $cate_result = CateHelper::adminCateHelper($page_key);
  $key_large = $cate_result->key_large;
  $title_txt = $cate_result->title_txt;
  $content_txt = $cate_result->content_txt;
  ${$page_key} = " class=on";
  ${$page_key."_BOLD"} = " class=twb";
  
  $now_year = isset($_POST['now_year']) ? $_POST['now_year'] : null;
  $stats_type = isset($_POST['stats_type']) ? $_POST['stats_type'] : null;

  if($now_year == ""){
      $now_year = Date("Y");
  }
  $where = "where (substring(stats_date,1,4) = '".($now_year-1)."' or substring(stats_date,1,4) = '".$now_year."')";
  $nStats  = new StatsClass(); //통계
  
  //======================== DB Module Start ============================
  $Conn = new DBClass();

  $nStats->page_result = $Conn->AllList
  (
      $nStats->table_name, $nStats, "count(*) as date_cnt, max(stats_date) as stats_date",
      $where." group by stats_date asc", null, array("state")
  );

  $Conn->DisConnect();
  //======================== DB Module End ===============================

  $total_cnt = 0;
  if(count($nStats->page_result) > 0){
      for($i=0, $cnt_list=count($nStats->page_result); $i < $cnt_list; $i++) {
          $nStats->VarList($nStats->page_result, $i, array('state'));
          ${substr($nStats->stats_date,0,4).'-'.Abs(substr($nStats->stats_date,5,2)).'-'.Abs(substr($nStats->stats_date,8,2)).'_cnt'} = $nStats->date_cnt;
          
          // 초기화. 스파게티 코드의 폐해
          ${substr($nStats->stats_date,0,4)} = isset(${substr($nStats->stats_date,0,4)}) ? ${substr($nStats->stats_date,0,4)} : array();
          ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] = isset(${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))]) ? ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] : null;
          
          ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] = ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] + $nStats->date_cnt;
          
          ${substr($nStats->stats_date,0,4)}[0] = isset(${substr($nStats->stats_date,0,4)}[0]) ? ${substr($nStats->stats_date,0,4)}[0] : null; // 초기화. 스파게티 코드의 폐해
          ${substr($nStats->stats_date,0,4)}[0] = ${substr($nStats->stats_date,0,4)}[0] + $nStats->date_cnt;
      }
      $nStats->ArrClear();
  }
?>

</head>
<body>
<!-- wrapper -->
<div id="wrapper">
    <!-- top_area -->
        @include('admin.common.top')
    <!-- //top_area -->
    <!-- container -->
    <div id="container">
        <!-- left_area -->
            @include('admin.common.left')
        <!-- //left_area -->
        <!-- right_area -->
        <div id="right_area">
            <h4 class="main-title">{{ $content_txt }}</h4>
            <div class="bbs-search" style="margin-bottom:20px;">
                <form name="frm" method="post" action="{{ $list_link }}" style="display:inline">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <select name="now_year" id="now_year" onchange="this.form.submit();">
                    <?php
                        for($s=2012, $s_cnt=(date("Y")+1); $s <= $s_cnt; $s++) {
                            echo "<option value='".$s."'";
                            if($now_year == $s) echo 'selected';
                            echo '>'.$s.'</option>';
                        }
                    ?>
                    </select> 년도
                </form>
            </div>
            <table id="stats" class="bbs-stats">
                <caption>{{ $content_txt }}</caption>
                <colgroup>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                    <col style="width:4%"/>
                </colgroup>
                <thead>
                <tr>
                    <th></th>
                    <th colspan="12">{{ $now_year }}년</th>
                    <th colspan="12" class="sub">{{ $now_year-1 }}년</th>
                <tr>
                    <th></td>
                    <th>1월</th>
                    <th>2월</th>
                    <th>3월</th>
                    <th>4월</th>
                    <th>5월</th>
                    <th>6월</th>
                    <th>7월</th>
                    <th>8월</th>
                    <th>9월</th>
                    <th>10월</th>
                    <th>11월</th>
                    <th>12월</th>
                    <th class="sub">1월</th>
                    <th class="sub">2월</th>
                    <th class="sub">3월</th>
                    <th class="sub">4월</th>
                    <th class="sub">5월</th>
                    <th class="sub">6월</th>
                    <th class="sub">7월</th>
                    <th class="sub">8월</th>
                    <th class="sub">9월</th>
                    <th class="sub">10월</th>
                    <th class="sub">11월</th>
                    <th class="sub">12월</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $day_cnt = 1;
                for($i=1, $cnt_list=31; $i <= $cnt_list; $i++) {
                ?>
                <tr>
                    <td class="first">{{ $i }}일</td>
                    <?php
                    for($j=1, $j_cnt=12; $j <= $j_cnt; $j++) {
                    ?>
                        <td>
                        <?php
                            if(isset(${$now_year.'-'.$j.'-'.$i.'_cnt'})){
                                echo number_format(${$now_year.'-'.$j.'-'.$i.'_cnt'});
                            }else{
                                 echo 'ㆍ';
                            }
                        ?>
                        </td>
                    <?php
                    }
                    for($k=1, $k_cnt=12; $k <= $k_cnt; $k++) {
                    ?>
                        <td class="sub">
                        <?php
                            if(isset(${($now_year-1).'-'.$k.'-'.$i.'_cnt'})){
                                echo number_format(${($now_year-1).'-'.$k.'-'.$i.'_cnt'});
                            }else{
                                echo 'ㆍ';
                            }
                        ?>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <th>월별<br/>통계</th>
                    <td>{{ isset(${$now_year}[1]) ? number_format(${$now_year}[1]) : null }}</td>
                    <td>{{ isset(${$now_year}[2]) ? number_format(${$now_year}[2]) : null }}</td>
                    <td>{{ isset(${$now_year}[3]) ? number_format(${$now_year}[3]) : null }}</td>
                    <td>{{ isset(${$now_year}[4]) ? number_format(${$now_year}[4]) : null }}</td>
                    <td>{{ isset(${$now_year}[5]) ? number_format(${$now_year}[5]) : null }}</td>
                    <td>{{ isset(${$now_year}[6]) ? number_format(${$now_year}[6]) : null }}</td>
                    <td>{{ isset(${$now_year}[7]) ? number_format(${$now_year}[7]) : null }}</td>
                    <td>{{ isset(${$now_year}[8]) ? number_format(${$now_year}[8]) : null }}</td>
                    <td>{{ isset(${$now_year}[9]) ? number_format(${$now_year}[9]) : null }}</td>
                    <td>{{ isset(${$now_year}[10]) ? number_format(${$now_year}[10]) : null }}</td>
                    <td>{{ isset(${$now_year}[11]) ? number_format(${$now_year}[11]) : null }}</td>
                    <td>{{ isset(${$now_year}[12]) ? number_format(${$now_year}[12]) : null }}</td>
                    
 
                    <td class="sub">{{ isset(${($now_year-1)}[1]) ? number_format(${($now_year-1)}[1]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[2]) ? number_format(${($now_year-1)}[2]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[3]) ? number_format(${($now_year-1)}[3]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[4]) ? number_format(${($now_year-1)}[4]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[5]) ? number_format(${($now_year-1)}[5]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[6]) ? number_format(${($now_year-1)}[6]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[7]) ? number_format(${($now_year-1)}[7]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[8]) ? number_format(${($now_year-1)}[8]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[9]) ? number_format(${($now_year-1)}[9]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[10]) ? number_format(${($now_year-1)}[10]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[11]) ? number_format(${($now_year-1)}[11]) : null }}</td>
                    <td class="sub">{{ isset(${($now_year-1)}[12]) ? number_format(${($now_year-1)}[12]) : null }}</td>
                </tr>
                <tr>
                    <th>총합</th>
                    <td colspan="12"><b>{{ isset(${$now_year}[0]) ? number_format(${$now_year}[0]) : null }}</b></td>
                    <td colspan="12" class="sub"><b>{{ isset(${($now_year-1)}[0]) ? number_format(${($now_year-1)}[0]) : null }}</b></td>
                </tr>
                </tbody>
            </table>
            <div class="paging-area"></div>
            <div class="btn-area tmargin">
                <div class="fleft "></div>
                <div class="fright"></div>
            </div>
        </div>
        <!-- //right_area -->
        <form name="form_submit" method="post" action="{{ $list_link }}" style="display:inline">
            {{ UserHelper::SubmitHidden() }}
        </form>
    </div>
    <!-- container -->
    <!-- footer -->
        @include('admin.common.footer')
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>
@stop