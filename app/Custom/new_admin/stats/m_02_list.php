<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'G2';
    $list_link  = 'm_02_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $now_year = RequestAll($_POST['now_year']);
    $stats_type = RequestAll($_POST['stats_type']);

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
            ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] = ${substr($nStats->stats_date,0,4)}[Abs(substr($nStats->stats_date,5,2))] + $nStats->date_cnt;
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
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/top.php'; ?>
    <!-- //top_area -->
    <!-- container -->
    <div id="container">
        <!-- left_area -->
            <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/left.php'; ?>
        <!-- //left_area -->
        <!-- right_area -->
        <div id="right_area">
            <h4 class="main-title"><?=$content_txt?></h4>
            <div class="bbs-search" style="margin-bottom:20px;">
                <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
                    <select name="now_year" id="now_year" onchange="this.form.submit();">
                    <?
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
                <caption><?=$content_txt?></caption>
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
                    <th colspan="12"><?=$now_year?>년</th>
                    <th colspan="12" class="sub"><?=$now_year-1?>년</th>
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
                    <td class="first"><?=$i?>일</td>
                    <?
                    for($j=1, $j_cnt=12; $j <= $j_cnt; $j++) {
                    ?>
                        <td>
                        <?
                            if(${$now_year.'-'.$j.'-'.$i.'_cnt'}){
                                echo number_format(${$now_year.'-'.$j.'-'.$i.'_cnt'});
                            }else{
                                 echo 'ㆍ';
                            }
                        ?>
                        </td>
                    <?
                    }
                    for($k=1, $k_cnt=12; $k <= $k_cnt; $k++) {
                    ?>
                        <td class="sub">
                        <?
                            if(${($now_year-1).'-'.$k.'-'.$i.'_cnt'}){
                                echo number_format(${($now_year-1).'-'.$k.'-'.$i.'_cnt'});
                            }else{
                                echo 'ㆍ';
                            }
                        ?>
                        </td>
                    <?
                    }
                    ?>
                </tr>
                <?
                }
                ?>
                <tr>
                    <th>월별<br/>통계</th>
                    <td><?=number_format(${$now_year}[1])?></td>
                    <td><?=number_format(${$now_year}[2])?></td>
                    <td><?=number_format(${$now_year}[3])?></td>
                    <td><?=number_format(${$now_year}[4])?></td>
                    <td><?=number_format(${$now_year}[5])?></td>
                    <td><?=number_format(${$now_year}[6])?></td>
                    <td><?=number_format(${$now_year}[7])?></td>
                    <td><?=number_format(${$now_year}[8])?></td>
                    <td><?=number_format(${$now_year}[9])?></td>
                    <td><?=number_format(${$now_year}[10])?></td>
                    <td><?=number_format(${$now_year}[11])?></td>
                    <td><?=number_format(${$now_year}[12])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[1])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[2])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[3])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[4])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[5])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[6])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[7])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[8])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[9])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[10])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[11])?></td>
                    <td class="sub"><?=number_format(${($now_year-1)}[12])?></td>
                </tr>
                <tr>
                    <th>총합</th>
                    <td colspan="12"><b><?=number_format(${$now_year}[0])?></b></td>
                    <td colspan="12" class="sub"><b><?=number_format(${($now_year-1)}[0])?></b></td>
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
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
        </form>
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>
