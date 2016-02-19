<?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/header.php'; ?>
<?php
    $page_key   = 'I2';
    $list_link  = 'p_02.php';
?>
<?php include $_SERVER[DOCUMENT_ROOT].'/_common/_global.php'; ?>
<?

$nAdm = new AdmClass(); //
$nAdm_2 = new AdmClass(); //

//======================== DB Module Start ============================
$Conn = new DBClass();



if($_POST[u_mode] == "update"){



     if(is_uploaded_file($_FILES['use_com_v']['tmp_name'])) {
          /*
               파일 업로드 함수
          */
          function file_upload($file, $folder, $allowExt, $file_name) {
               $ext = substr(strrchr($file['name'], '.'), 1);

 

               if($ext) {
                    $allow = explode(',', $allowExt);

 

                    if(is_array($allow)) {
                         $check = in_array($ext, $allow);
                    } else {
                         $check = ($ext == $allow) ? true : false;
                    }
               }

 

               if(!$ext || !$check) exit('<script type="text/javascript">alert(\''.$ext.' 파일은 업로드 하실수 없습니다!\'); history.go(-1);</script>');

 

               $upload_file = $folder.$file_name.'.'.strtolower($ext);

 

               if(@move_uploaded_file($file['tmp_name'], $upload_file)) {
                    @chmod($upload_file, 0707);


                    $return = $file_name.'.'.strtolower($ext);
                    return $return;
               } else {
                    exit('<script type="text/javascript">alert(\'업로드에 실패하였습니다!\'); history.go(-1);</script>');
               }
          }

 

          /*
               파일 업로드

 

               사용방법 :
                    첫번째 파라미터 : 업로드 파일의 정보를 담은 $_FILES 배열 변수를 넘김
                    두번째 파라미터 : 업로드할 폴터(절대 혹은 상대경로 모두 가능)를 넘김(경로뒤에 / 을 꼭 붙여야 함)
                    세번째 파라미터 : 허용할 확장자(,콤마로 구분)를 넘김
                    네번째 파라미터 : 새로 정의할 파일 이름(확장자 없이)을 넘김
          */
          $upload_return = file_upload($_FILES['use_com_v'], $_SERVER[DOCUMENT_ROOT].'/common/img/page/', 'gif,GIF,jpg,JPG,jpeg,JPEG,png,PNG', time());

 

          //echo $upload_return;

 

/* 파일 업로드가 진행중이 아닐때 */
     }


	$sql = "update new_tb_adm_tup set t_text='".$upload_return."' where t_name='use_com_v'";
	mysql_query($sql);
	UrlReDirect("내용이 수정되었습니다.", $list_link);
}



$nAdm->page_result = $Conn->AllList
(	
	$nAdm->table_name, $nAdm, "*", "where t_name='use_com_v' order by idx desc limit 1", null, null
);



$Conn->DisConnect();
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

			



			<form method="post" action="<?=$list_link?>" name="adm_t_form" enctype="multipart/form-data">
			<input type="hidden" name="u_mode" value="update">
			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr>
				<td><strong>이미지등록 및 수정</strong></td>
			</tr>
			<tr height=30><td><input type="file" name="use_com_v" value="" size="60"></td></tr>
			<tr>
				<td align=center><input type="submit" value="저장하기" style="border:1px solid #e8e8e8; padding:5px;background:#3952a8; font-weight:bold; color:#fff;"></td>
			</tr>
			</table>
			</form>




			<table cellpadding=0 cellspacing=0 border=0 width=100% align=center>
			<tr height=10><td></td></tr>
			<tr>
				<td>
				<?
					for($i=0, $cnt_list=count($nAdm->page_result); $i < $cnt_list; $i++) {
						$nAdm->VarList($nAdm->page_result, $i, null);
				?>
				<img src="/common/img/page/<?=$nAdm->t_text?>" width="790">
				<?
					}
				?>
				
				</td>
			</tr>
			<tr height=10><td></td></tr>

			</table>
        </div>

    </div>
    <!-- container -->
    <!-- footer -->
        <?php include $_SERVER[DOCUMENT_ROOT].'/new_admin/include/footer.php'; ?>
    <!-- //footer -->
</div>
<!-- //wrapper -->

</body>
</html>