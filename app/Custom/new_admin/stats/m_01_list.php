<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'G1';
    $list_link  = 'm_01_list.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $start_date = RequestAll($_POST['start_date']);
    $end_date   = RequestAll($_POST['end_date']);

    if($start_date == ""){
        $start_date = date("Y-m-d",strtotime("-6 day"));
        $pre_start_date =  date("Y-m-d",strtotime("-1 year -6 day"));
    }else{
        $pre_start_date =  (substr($start_date,0,4)-1).'-'.substr($start_date,5,2).'-'.substr($start_date,8,2);
    }

    if($end_date == ""){
        $end_date = Date("Y-m-d");
        $pre_end_date = date("Y-m-d",strtotime("-1 year"));
    }else{
        $pre_end_date =  (substr($end_date,0,4)-1).'-'.substr($end_date,5,2).'-'.substr($end_date,8,2);
    }

    $nStats  = new StatsClass(); //통계
//======================== DB Module Start ============================
$Conn = new DBClass();

    $where1 = "where stats_date >= cast('".$start_date."' AS DATE) and stats_date <= cast('".$end_date."' AS DATE)";
    $nStats->page_result1 = $Conn->AllList
    (
        $nStats->table_name, $nStats, "count(*) as date_cnt, max(stats_date) as stats_date",
        $where1." group by stats_date asc", null, array("state")
    );

    $where2 = "where stats_date >= cast('".$pre_start_date."' AS DATE) and stats_date <= cast('".$pre_end_date."' AS DATE)";
    $nStats->page_result2 = $Conn->AllList
    (
        $nStats->table_name, $nStats, "count(*) as date_cnt, max(stats_date) as stats_date",
        $where2." group by stats_date asc", null, array("state")
    );

$Conn->DisConnect();
//======================== DB Module End ===============================

    $total_cnt = 0;
    if(count($nStats->page_result1) > 0){
        for($i=0, $cnt_list=count($nStats->page_result1); $i < $cnt_list; $i++) {
            $nStats->VarList($nStats->page_result1, $i, array('state'));
            if($nStats->date_cnt != ""){
                ${$nStats->stats_date.'_cnt'}  = $nStats->date_cnt;
                ${$nStats->stats_date.'_date'} = $nStats->stats_date;
                $total_cnt = $total_cnt + $nStats->date_cnt;
            }
        }
        $nStats->ArrClear();
    }

    $pre_total_cnt = 0;
    if(count($nStats->page_result2) > 0){
        for($i=0, $cnt_list=count($nStats->page_result2); $i < $cnt_list; $i++) {
            $nStats->VarList($nStats->page_result2, $i, array('state'));
            if($nStats->date_cnt != ""){
                ${'pre_'.$nStats->stats_date.'_cnt'}  = $nStats->date_cnt;
                ${'pre_'.$nStats->stats_date.'_date'} = $nStats->stats_date;
                $pre_total_cnt = $pre_total_cnt + $nStats->date_cnt;
            }
        }
        $nStats->ArrClear();
    }
?>
    <style>
    .odd{background-color:#ffc;}
    .even{background-color:#ffc;}
    </style>
    <script type="text/javascript" src="../../new_js/highcharts_plugin/jquery.highcharts.js"></script>
    <script type="text/javascript" src="../../new_js/highcharts_plugin/jquery.exporting.js"></script>
    <script language="javascript">

        function sendSubmit(){
            var f = document.frm;
            var start_date_value = $("#start_date").val();
            var end_date_value   = $("#end_date").val();
            var start_dates      = start_date_value.split("-");
            var end_dates        = end_date_value.split("-");

            var date1 = new Date(start_dates[0],start_dates[1],start_dates[2]).valueOf();
            var date2 = new Date(end_dates[0],end_dates[1],end_dates[2]).valueOf();

            //if((date2 - date1) > 432000000 ){
            if((date2 - date1) > 518400000 ){
                alert("일주일이상 날짜간격은 차트가 나오지 않습니다.");
            }
            f.submit();
        }

        $(document).ready(function()
        {
            $('#start_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
            $('#end_date').datepicker({showOn: "both", buttonImageOnly: true, buttonImage: "/new_images/calendar.png", numberOfMonths: 1, showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
            $.datepicker.regional['ko'] = {
                closeText: '닫기', prevText: '이전달', nextText: '다음달', currentText: '오늘',
                monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNames: ['일','월','화','수','목','금','토'], dayNamesShort: ['일','월','화','수','목','금','토'], dayNamesMin: ['일','월','화','수','목','금','토'],
                dateFormat: 'yy-mm-dd', firstDay: 0, isRTL: false
            };
            $.datepicker.setDefaults($.datepicker.regional['ko']);

            //$('tr:odd').addClass('odd');
            $('#stats tr:even').addClass('even');

            var start_date_value = "<?=$start_date?>";
            var end_date_value   = "<?=$end_date?>";
            var start_dates      = start_date_value.split("-");
            var end_dates        = end_date_value.split("-");

            var date1 = new Date(start_dates[0],start_dates[1],start_dates[2]).valueOf();
            var date2 = new Date(end_dates[0],end_dates[1],end_dates[2]).valueOf();

            //if((date2 - date1) <= 432000000 ){
            if((date2 - date1) <= 518400000 ){
                var chart;
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'stats_chart',
                        type: 'line',
                        marginRight: 130,
                        marginBottom: 25
                    },
                    title: {
                        text: '[ 방문통계 ]',
                        x: -20 //center
                    },
                    subtitle: {
                        text: '(일주일)',
                        x: -20
                    },
                    xAxis: {
                        categories: [
                        <?php
                       for($i=0, $cnt_list=dateDiff($end_date, $start_date); $i <= $cnt_list; $i++) {
                            $cnt_view_day       = date("Y-m-d", strtotime($start_date."+".$i." day"));
                            $cnt_pre_view_day   = date("Y-m-d", strtotime($pre_start_date."+".$i." day"));
                            $chart_view_day     = str_replace('-','\n',date("m/d", strtotime($start_date."+".$i." day")));
                            $chart_pre_view_day = str_replace('-','\n',date("m/d", strtotime($pre_start_date."+".$i." day")));
                            if($i != 0){ $comma = ','; }
                            $chart_start_cnt = $chart_start_cnt.$comma.${$cnt_view_day.'_cnt'};
                            $chart_pre_start_cnt = $chart_pre_start_cnt.$comma.${'pre_'.$cnt_pre_view_day.'_cnt'};
                        ?>
                            <?=$comma?>'<?=$chart_view_day?>(<?=$chart_pre_view_day?>)'
                        <?}?>
                        ]
                    },
                    yAxis: {
                        title: {
                            text: ''
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function() {
                                return '<b>'+ this.series.name +'</b><br/>'+
                                //this.x +': '+ this.y +'명';
                                this.y +'명';
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 100,
                        borderWidth: 0
                    },
                    series: [{
                        name: '<?=substr($start_date,0,4)?>',
                        data: [<?=$chart_start_cnt?>]
                    }, {
                        name: '<?=substr($pre_start_date,0,4)?>',
                        data: [<?=$chart_pre_start_cnt?>]
                    }]
                });
            }
        });

    </script>
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
            <div class="bbs-search" style="margin-bottom:0px;">
                <form name="frm" method="post" action="<?=$list_link?>" style="display:inline">
                    시작일 <input type="text" name="start_date" id='start_date' value="<?=$start_date?>" style="width:80px;cursor:pointer;" readonly/> ~
                    종료일 <input type="text" name="end_date" id='end_date' value="<?=$end_date?>" style="width:80px;cursor:pointer;" readonly/>
                    <a href="javascript:sendSubmit()"><img src="/new_admin/images/btn_search.gif" alt="search"/></a>
                </form>
            </div>
            <table id="stats" class="bbs-stats">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:25%" />
                    <col style="width:25%" />
                    <col style="width:25%" />
                    <col style="width:25%" />
                </colgroup>
                <thead>
                <tr>
                    <th>일자</th>
                    <th>접속수</th>
                    <th class="sub">전년도 일자</th>
                    <th class="sub">전년도 접속수</th>
                </tr>
                </thead>
                <tbody>
                <?php
                for($i=0, $cnt_list=dateDiff($end_date, $start_date); $i <= $cnt_list; $i++) {
                    $view_day = date("Y-m-d", strtotime($start_date."+".$i." day"));
                    $pre_view_day = date("Y-m-d", strtotime($pre_start_date."+".$i." day"));
                ?>
                <tr>
                    <td><?=$view_day?></td>
                    <td><?if(${$view_day.'_cnt'}){ echo number_format(${$view_day.'_cnt'}); }else{ echo 'ㆍ';}?></td>
                    <td><?=$pre_view_day?></td>
                    <td><?if(${'pre_'.$pre_view_day.'_cnt'}){ echo number_format(${'pre_'.$pre_view_day.'_cnt'}); }else{ echo 'ㆍ';}?></td>
                </tr>
                <?
                }
                ?>
                <tr>
                    <td>total</td>
                    <td><b><?=number_format($total_cnt)?></b></td>
                    <td>전년도 total</td>
                    <td><b><?=number_format($pre_total_cnt)?></b></td>
                </tr>
                </tbody>
            </table>
            <div id="stats_chart" style="width:890px;height:400px;margin:0 auto;padding-top:20px;"></div>
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
