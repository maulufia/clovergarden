<?php
    session_start();
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_global.php'); //변수,상수,클래스
    require_once($_SERVER[DOCUMENT_ROOT].'/_common/_user.php'); //사용자

    $cate_seq      = iconv("UTF-8", "EUC-KR", rawurldecode($_POST['cate_seq']));
    $operation_seq = iconv("UTF-8", "EUC-KR", rawurldecode($_POST['operation_seq']));

    $nCate        = new CateClass(); //카테고리
    $nOperation   = new OperationClass(); //성형갤러리
    $nCenterImage = new CenterImageClass(); //성형이미지노출

    $center_image_check = 'n';

if($cate_seq != "" && $operation_seq != ""){
//======================== DB Module Start ============================
$Conn = new DBClass();

    $nCenterImage->read_result = $Conn->SelectColumn($nCenterImage->table_name, 'count(*)', "where cate_seq = '".$cate_seq."' and operation_seq = '".$operation_seq."'");
    if(!$nCenterImage->read_result){
            $nCate->read_result = $Conn->AllList($nCate->table_name, $nCate, '*', "where seq = '".$cate_seq."'");
            if(count($nCate->read_result) != 0){
                $nCate->VarList($nCate->read_result, 0, null);

                    $arr_field = array('cate_seq', 'operation_seq', 'sort_num');
                    $arr_value = array($cate_seq, $operation_seq, '0');
                    $out_put = $Conn->insertDB($nCenterImage->table_name, $arr_field, $arr_value);
                    if($out_put){
                        $Conn->CommitTrans();
                        $center_image_msg   = SUCCESS_ADD;
                        $center_image_check = 'y';

                        //성형이미지노출 리스트
                        $nCenterImage->page_result = $Conn->AllList
                        (
                            $nCenterImage->table_name.' A left outer join '.$nCate->table_name.' B on A.cate_seq = B.seq',
                            $nCenterImage, 'A.*, B.cate_name1, B.cate_name2, B.cate_name3, B.cate_name4', "where A.operation_seq ='".$operation_seq."' order by A.seq desc", null, array('cate')
                        );
                    }else{
                        $Conn->RollbackTrans();
                        $center_image_msg = ERR_DATABASE;
                    }
            }else{
                $center_image_msg = "등록된 카테고리가 없습니다.";
            }
    }else{
        $center_image_msg = "이미 등록된 카테고리 입니다.";
    }

$Conn->DisConnect();
//======================== DB Module End ===============================
}else{
    $center_image_msg = NO_PATH;
}
    $arr_json = array
    (
        "center_image_msg"   => iconv('EUC-KR', 'UTF-8', $center_image_msg),
        "center_image_check" => $center_image_check
    );

    if(count($nCenterImage->page_result) > 0){
        for($i=0, $cnt_list=count($nCenterImage->page_result); $i < $cnt_list; $i++) {
            $nCenterImage->VarList($nCenterImage->page_result, $i, array('cate'));
            $arr_json['list_center_seq'][$i]           = iconv('EUC-KR', 'UTF-8', $nCenterImage->seq);
            $arr_json['list_center_cate_seq'][$i]      = iconv('EUC-KR', 'UTF-8', $nCenterImage->cate_seq);
            $arr_json['list_center_operation_seq'][$i] = iconv('EUC-KR', 'UTF-8', $nCenterImage->operation_seq);
            $arr_json['list_center_sort_num'][$i]      = iconv('EUC-KR', 'UTF-8', $nCenterImage->sort_num);
            $arr_json['list_center_cate_name1'][$i]    = iconv('EUC-KR', 'UTF-8', $nCenterImage->cate_name1);
            $arr_json['list_center_cate_name2'][$i]    = iconv('EUC-KR', 'UTF-8', $nCenterImage->cate_name2);
            $arr_json['list_center_cate_name3'][$i]    = iconv('EUC-KR', 'UTF-8', $nCenterImage->cate_name3);
            $arr_json['list_center_cate_name4'][$i]    = iconv('EUC-KR', 'UTF-8', $nCenterImage->cate_name4);
        }
    }

    $json_return = json_encode($arr_json);
    echo '@@||@@'.urldecode($json_return);
?>