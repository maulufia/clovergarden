<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'F1';
    $list_link  = 'm_01_list.php';
    $write_link = 'm_01_write_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPopup = new PopupClass(); //팝업
?>
    <script language="javascript">

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
        });

        function sendSubmit()
        {
            var f = document.frm;
            if(formCheckSub(f.subject , "exp", "제목") == false){ return; }
            if(formCheckSub(f.subject, "inj", "제목") == false){ return; }
            if(formCheckNum(f.subject, "maxlen", 80, "제목") == false){ return; }

            if(formCheckSub(f.start_date, "exp", "시작일") == false){ return; }
            if(formCheckSub(f.end_date, "exp", "종료일") == false){ return; }

            if(formCheckSub(f.popup_top, "num", "창위치 상단") == false){ return; }
            if(formCheckSub(f.popup_left, "num", "창위치 좌측") == false){ return; }

            if(formCheckSub(f.upfile1, "exp", "팝업이미지") == false){ return; }
            if(formCheckSub(f.upfile1, "pho", "팝업이미지") == false){ return; }


            $.blockUI();
            f.action = "<?=$write_link?>";
            f.submit();
        }

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
            <form id="frm" name="frm" method="post" enctype="multipart/form-data" style="display:inline;">
            <table class="bbs-write">
                <caption><?=$content_txt?></caption>
                <colgroup>
                    <col style="width:150px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>제목</th>
                    <td>
                        <input type="text" name="subject" style="width:450px;"/>
                    </td>
                </tr>
                <tr>
                    <th>기간</th>
                    <td>
                        시작일 :<input type="text" name="start_date" id='start_date' style="width:80px;cursor:pointer;" readonly/> ~
                        종료일 :<input type="text" name="end_date" id='end_date' style="width:80px;cursor:pointer;" readonly/>
                    </td>
                </tr>
                <tr>
                    <th>창위치</th>
                    <td>
                        상단 <input type="text" name="popup_top" style="width:50px;" maxlength="4"/>px
                        <span style="padding-left:20px;">좌측 <input type="text" name="popup_left" style="width:50px;" maxlength="4"/>px</span>
                    </td>
                </tr>
                <tr>
                    <th>팝업이미지</th>
                    <td><input type="file" name="upfile1" size="50" /> <span class="lmits">(<?=$nPopup->file_mime_type[1]?> : <?=$nPopup->file_volume[1]?><?=LOW_FILESIZE?>)</span></td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td>
                        <input type="text" name="url" style="width:650px;"/>
                    </td>
                </tr>
                <tr>
                    <th>URL Target</th>
                    <td>
                        <?$nPopup->ArrPopup(null, "name='popup_type'", null, 'popup_type')?>
                    </td>
                </tr>
                <tr>
                    <th>노출여부</th>
                    <td>
                        <?$nPopup->ArrPopup(null, "name='hidden'", null, 'hidden')?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            </form>
            <div class="btn-area">
                <div class="fleft">
                    <a href="javascript:pageLink('','','','');"><img src="/new_admin/images/btn_list.gif" alt="list" /></a>
                </div>
                <div class="fright">
                    <input type="image" src="/new_admin/images/btn_save.gif" alt="save" onclick="javascript:sendSubmit()"/>
                </div>
            </div>
        </div>
        <form name="form_submit" method="post" action="<?=$list_link?>" style="display:inline">
            <?=SubmitHidden()?>
        </form>
        <!-- //right_area -->
    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->
</body>
</html>