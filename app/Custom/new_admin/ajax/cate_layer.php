<?php
    session_start();
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<?php
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $form_name      = NullVal($_REQUEST['form_name'], 3, null);
    $cate_full_name = NullVal($_REQUEST['cate_full_name'], 3, null);
    $cate_seq       = NullVal($_REQUEST['cate_seq'], 3, null);

    $nCate = new CateClass(); //카테고리

//======================== DB Module Start ============================
$Conn = new DBClass();

    $nCate->page_result1 = $Conn->AllList($nCate->table_name, $nCate, '*', "where cate_seq1 = '1' order by cate_seq1 asc, cate_seq2 asc, cate_seq3 asc, cate_seq4 asc");
    $nCate->page_result2 = $Conn->AllList($nCate->table_name, $nCate, '*', "where cate_seq1 = '2' order by cate_seq1 asc, cate_seq2 asc, cate_seq3 asc, cate_seq4 asc");
    $nCate->page_result3 = $Conn->AllList($nCate->table_name, $nCate, '*', "where cate_seq1 = '3' order by cate_seq1 asc, cate_seq2 asc, cate_seq3 asc, cate_seq4 asc");
    $nCate->page_result4 = $Conn->AllList($nCate->table_name, $nCate, '*', "where cate_seq1 = '4' order by cate_seq1 asc, cate_seq2 asc, cate_seq3 asc, cate_seq4 asc");
    $nCate->page_result5 = $Conn->AllList($nCate->table_name, $nCate, '*', "where cate_seq1 = '5' order by cate_seq1 asc, cate_seq2 asc, cate_seq3 asc, cate_seq4 asc");

$Conn->DisConnect();
//======================== DB Module End ===============================
?>
<style>

    body,table,input,textarea,select {
        font:9pt tahoma;
        color:#000000;
    }
    body {margin:0 0 0 5px}
    img {border:0;}
    form {display:inline;}
    a {text-decoration:none; color:#000000;}
    a:hover {text-decoration:none; color:#006697;}
    a.white {text-decoration:none; color:#FFFFFF;}
    a.white:hover {text-decoration:none; color:#FFFFFF;}
    .white {color:#FFFFFF;}
    .top_cate {font-weight:bold;}

    #treeCategory {
        float:left;
        width:270px;
        height:500px;
        /*overflow-y:auto;*/
        overflow-x:hidden;
        border:1px solid #cccccc;
        padding:2px;
    }
    #treeCategory1 {
        float:left;
        width:270px;
        height:900px;
        overflow-y:auto;
        overflow-x:hidden;
        border:1px solid #cccccc;
        padding:10px;
    }
    #treeCategory2 {
        float:left;
        width:100%;
        height:408px;
        overflow-y:auto;
        overflow-x:hidden;
        border:1px solid #cccccc;
        padding:10px;
    }
    #treeCategory .gap {
        display:none;
        padding-top:20px;
    }
    #treeCategory .subTree {
        position:relative;
        left:-120px;
    }
    #treeCategory .cursor {
        background:url('/new_admin/images/icon_tree.gif') no-repeat;
        padding-left:15px;
        cursor:pointer;
        vertical-align:top;
        width:130px;
        overflow:hidden;
    }

</style>
<script language="javascript">

    function openerCateSeq(cate_seq, cate_name) {
        parent.document.<?=$form_name?>.<?=$cate_seq?>.value = cate_seq;
        parent.document.<?=$form_name?>.<?=$cate_full_name?>.value = unescape(cate_name);
        parent.closeLayer();
    }

</script>
</head>
<body class="scroll">
    <div align="center" style="HEIGHT:447px; overflow:auto; WIDTH:395">
    <table width=100% border=0>
        <tr>
            <td valign=top style="padding:10px;">
                <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
    if(count($nCate->page_result1) > 0){
        for($i=0, $cnt_list=count($nCate->page_result1); $i < $cnt_list; $i++) {
            $nCate->VarList($nCate->page_result1, $i, null);
            switch($nCate->depth_num)
            {
                case "1" :
                    $depth_width = "0";
                    $cate_name   = $nCate->cate_name1;
                    $cate_top    = " class='top_cate'";
                break;
                case "2" :
                    $depth_width = "20";
                    $cate_name   = $nCate->cate_name2;
                break;
                case "3" :
                    $depth_width = "40";
                    $cate_name   = $nCate->cate_name3;
                break;
                case "4" :
                    $depth_width = "80";
                    $cate_name   = $nCate->cate_name4;
                break;
            }
            if($nCate->add_check == "0"){
                $depth_icon  = "page";
                $clcik_cate  = "openerCateSeq('".$nCate->seq."','".$cate_name."')";
            }else{
                $depth_icon  = "Folder";
                $clcik_cate  = "alert('하위카테고리를 선택해 주세요.')";
            }
?>
                    <tr>
                        <td>
                            <span style="width:<?=$depth_width?>;"></span>
                            <img src="../images/treeicons/Icons/<?=$depth_icon?>.gif" align="absMiddle" border="0">
                            <font color="" <?=$cate_top?>><a href="javascript:<?=$clcik_cate?>"><?=$cate_name?></a></font> </a>
                        </td>
                    </tr>
<?
            $cate_top    = "";
        }
    }
    $nCate->ArrClear();
    if(count($nCate->page_result2) > 0){
        for($i=0, $cnt_list=count($nCate->page_result2); $i < $cnt_list; $i++) {
            $nCate->VarList($nCate->page_result2, $i, null);
            switch($nCate->depth_num)
            {
                case "1" :
                    $depth_width = "0";
                    $cate_name   = $nCate->cate_name1;
                    $cate_top    = " class='top_cate'";
                break;
                case "2" :
                    $depth_width = "20";
                    $cate_name   = $nCate->cate_name2;
                break;
                case "3" :
                    $depth_width = "40";
                    $cate_name   = $nCate->cate_name3;
                break;
                case "4" :
                    $depth_width = "80";
                    $cate_name   = $nCate->cate_name4;
                break;
            }
            if($nCate->add_check == "0"){
                $depth_icon  = "page";
                $clcik_cate  = "openerCateSeq('".$nCate->seq."','".$cate_name."')";
            }else{
                $depth_icon  = "Folder";
                $clcik_cate  = "alert('하위카테고리를 선택해 주세요.')";
            }
?>
                    <tr>
                        <td>
                            <span style="width:<?=$depth_width?>;"></span>
                            <img src="../images/treeicons/Icons/<?=$depth_icon?>.gif" align="absMiddle" border="0">
                            <font color="" <?=$cate_top?>><a href="javascript:<?=$clcik_cate?>"><?=$cate_name?></a></font> </a>
                        </td>
                    </tr>
<?
            $cate_top    = "";
        }
    }
    $nCate->ArrClear();
    if(count($nCate->page_result3) > 0){
        for($i=0, $cnt_list=count($nCate->page_result3); $i < $cnt_list; $i++) {
            $nCate->VarList($nCate->page_result3, $i, null);
            switch($nCate->depth_num)
            {
                case "1" :
                    $depth_width = "0";
                    $cate_name   = $nCate->cate_name1;
                    $cate_top    = " class='top_cate'";
                break;
                case "2" :
                    $depth_width = "20";
                    $cate_name   = $nCate->cate_name2;
                break;
                case "3" :
                    $depth_width = "40";
                    $cate_name   = $nCate->cate_name3;
                break;
                case "4" :
                    $depth_width = "80";
                    $cate_name   = $nCate->cate_name4;
                break;
            }
            if($nCate->add_check == "0"){
                $depth_icon  = "page";
                $clcik_cate  = "openerCateSeq('".$nCate->seq."','".$cate_name."')";
            }else{
                $depth_icon  = "Folder";
                $clcik_cate  = "alert('하위카테고리를 선택해 주세요.')";
            }
?>
                    <tr>
                        <td>
                            <span style="width:<?=$depth_width?>;"></span>
                            <img src="../images/treeicons/Icons/<?=$depth_icon?>.gif" align="absMiddle" border="0">
                            <font color="" <?=$cate_top?>><a href="javascript:<?=$clcik_cate?>"><?=$cate_name?></a></font> </a>
                        </td>
                    </tr>
<?
            $cate_top    = "";
        }
    }
    $nCate->ArrClear();
    if(count($nCate->page_result4) > 0){
        for($i=0, $cnt_list=count($nCate->page_result4); $i < $cnt_list; $i++) {
            $nCate->VarList($nCate->page_result4, $i, null);
            switch($nCate->depth_num)
            {
                case "1" :
                    $depth_width = "0";
                    $cate_name   = $nCate->cate_name1;
                    $cate_top    = " class='top_cate'";
                break;
                case "2" :
                    $depth_width = "20";
                    $cate_name   = $nCate->cate_name2;
                break;
                case "3" :
                    $depth_width = "40";
                    $cate_name   = $nCate->cate_name3;
                break;
                case "4" :
                    $depth_width = "80";
                    $cate_name   = $nCate->cate_name4;
                break;
            }
            if($nCate->add_check == "0"){
                $depth_icon  = "page";
                $clcik_cate  = "openerCateSeq('".$nCate->seq."','".$cate_name."')";
            }else{
                $depth_icon  = "Folder";
                $clcik_cate  = "alert('하위카테고리를 선택해 주세요.')";
            }
?>
                    <tr>
                        <td>
                            <span style="width:<?=$depth_width?>;"></span>
                            <img src="../images/treeicons/Icons/<?=$depth_icon?>.gif" align="absMiddle" border="0">
                            <font color="" <?=$cate_top?>><a href="javascript:<?=$clcik_cate?>"><?=$cate_name?></a></font> </a>
                        </td>
                    </tr>
<?
            $cate_top    = "";
        }
    }
    $nCate->ArrClear();
    if(count($nCate->page_result5) > 0){
        for($i=0, $cnt_list=count($nCate->page_result5); $i < $cnt_list; $i++) {
            $nCate->VarList($nCate->page_result5, $i, null);
            switch($nCate->depth_num)
            {
                case "1" :
                    $depth_width = "0";
                    $cate_name   = $nCate->cate_name1;
                    $cate_top    = " class='top_cate'";
                break;
                case "2" :
                    $depth_width = "20";
                    $cate_name   = $nCate->cate_name2;
                break;
                case "3" :
                    $depth_width = "40";
                    $cate_name   = $nCate->cate_name3;
                break;
                case "4" :
                    $depth_width = "80";
                    $cate_name   = $nCate->cate_name4;
                break;
            }
            if($nCate->add_check == "0"){
                $depth_icon  = "page";
                $clcik_cate  = "openerCateSeq('".$nCate->seq."','".$cate_name."')";
            }else{
                $depth_icon  = "Folder";
                $clcik_cate  = "alert('하위카테고리를 선택해 주세요.')";
            }
?>
                    <tr>
                        <td>
                            <span style="width:<?=$depth_width?>;"></span>
                            <img src="../images/treeicons/Icons/<?=$depth_icon?>.gif" align="absMiddle" border="0">
                            <font color="" <?=$cate_top?>><a href="javascript:<?=$clcik_cate?>"><?=$cate_name?></a></font> </a>
                        </td>
                    </tr>
<?
            $cate_top    = "";
        }
    }
?>

                </table>
                <style type="text/css">
                <!--
                input.c {height:13px; width:13px; margin-left:0px; margin-right:6px; margin-bottom:0px; margin-top:1px;}
                -->
                </style>
            </td>
        </tr>
    </table>
    </div>
</body>
</html>