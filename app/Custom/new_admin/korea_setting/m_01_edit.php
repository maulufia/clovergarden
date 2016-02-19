<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key  = 'F1';
    $list_link = 'm_01_list.php';
    $edit_link = 'm_01_edit_exec.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?
    $seq        = NullVal($_REQUEST['seq'], 1, $list_link, 'numeric');
    $row_no     = NullNumber($_POST['row_no']);
    $page_no    = NullNumber($_POST['page_no']);
    $search_key = RequestAll($_POST['search_key']);
    $search_val = RequestAll($_POST['search_val']);

    $nPopup = new PopupClass(); //팝업

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nPopup->read_result = $Conn->AllList($nPopup->table_name, $nPopup, "*", "where seq ='".$seq."'", null, null);
    if(count($nPopup->read_result) != 0){
        $nPopup->VarList($nPopup->read_result, 0, null);
    }else{
        $Conn->DisConnect();
        JsAlert(NO_DATA, 1, $list_link);
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
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

            if(f.check_del1.checked == true && f.upfile1.value == "")
            {
                if(formCheckSub(f.upfile1, "exp", "팝업이미지") == false){ return; }
            }
            if(formCheckSub(f.upfile1, "pho", "팝업이미지") == false){ return; }

            $.blockUI();
            f.action = "<?=$edit_link?>";
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
                    <col style="width:100px;" />
                    <col style="width:350px;" />
                    <col style="width:100px;" />
                    <col />
                </colgroup>
                <tbody>
                <tr>
                    <th>노출여부</th>
                    <td colspan="3"><?$nPopup->ArrPopup($nPopup->hidden, "name='hidden'", null, 'hidden')?></td>
                </tr>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="text" name="subject" style="width:450px;" value="<?=$nPopup->subject?>"/>
                    </td>
                </tr>
                <tr>
                    <th>기간</th>
                    <td colspan="3">
                        시작일 :<input type="text" name="start_date" id='start_date' style="width:80px;cursor:pointer;" value="<?=$nPopup->start_date?>" readonly/> ~
                        종료일 :<input type="text" name="end_date" id='end_date' style="width:80px;cursor:pointer;" value="<?=$nPopup->end_date?>" readonly/>
                    </td>
                </tr>
                <tr>
                    <th>창위치</th>
                    <td colspan="3">
                        상단 <input type="text" name="popup_top" style="width:50px;" maxlength="4" value="<?=$nPopup->popup_top?>"/>px
                        <span style="padding-left:20px;">좌측 <input type="text" name="popup_left" style="width:50px;" maxlength="4" value="<?=$nPopup->popup_left?>"/>px</span>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align:middle;">팝업이미지</th>
                    <td colspan="3">
                        <?php
                            if(FileExists('../../up_file/popup/'.$nPopup->file_edit[1])){
                                echo "<img src='../../up_file/popup/".$nPopup->file_edit[1]."' border='0'>";
                                echo "<div style='padding-top:20px;padding-bottom:0px;'>[ 팝업이미지 ] ";
                                echo "<a href=".Chr(34)."javascript:downFile('".$nPopup->seq."','','1','../../_db_file/_file_popup.php')".Chr(34).">";
                                echo $nPopup->file_real[1]."</a><font color='gray'> (".$nPopup->file_byte[1].")</font></div>";
                            }
                        ?>
                        <input type="file" name="upfile1" size="50" />
                        <?php
                            if(FileExists('../../up_file/popup/'.$nPopup->file_edit[1])){
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;'/> <font color='red'>삭제</font>";
                            }else{
                                echo "<input type='checkbox' name='check_del1' value='1' style='width:17px;border:0px;' disabled/> <font color='gray'>삭제</font>";
                            }
                        ?>
                        <span class="lmits">(<?=$nPopup->file_mime_type[1]?> : <?=$nPopup->file_volume[1]?><?=LOW_FILESIZE?>)</span>
                    </td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td colspan="3">
                        <input type="text" name="url" style="width:650px;" value="<?=$nPopup->url?>"/>
                    </td>
                </tr>
                <tr>
                    <th>URL Target</th>
                    <td colspan="3"><?$nPopup->ArrPopup($nPopup->popup_type, "name='popup_type'", null, 'popup_type')?></td>
                </tr>
                </tbody>
            </table>
            <?=SubmitHidden()?>
            <input type="hidden" name="file_real1" value="<?=$nPopup->file_real[1]?>"/>
            <input type="hidden" name="file_edit1" value="<?=$nPopup->file_edit[1]?>"/>
            <input type="hidden" name="file_byte1" value="<?=$nPopup->file_byte[1]?>"/>
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