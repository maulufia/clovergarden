<?php

// 파일 저장 폴더는 반드시 777 권한으로 되어있어야 파일이 올라갑니다.
$up_url = 'http://'.$_SERVER[HTTP_HOST].'/up_file/editor/'; 						// 기본 업로드 URL
$up_dir = $_SERVER[DOCUMENT_ROOT].'/up_file/editor/'; 			// 기본 업로드 폴더 
//$datename = date('YmdGis');								//중복된 파일이 없도록 하기 위해서

// 업로드 DIALOG 에서 전송된 값
$funcNum = $_GET['CKEditorFuncNum'] ;
$CKEditor = $_GET['CKEditor'] ;			
$langCode = $_GET['langCode'] ; 		

if(isset($_FILES['upload']['tmp_name']))
{   
	$fileNameOrg   = $_FILES['upload']['name'];
	$fileExp       = explode(".",$fileNameOrg);
	$fileExpCnt    = count($fileExp) - 1;
	$fileExtension = strtolower($fileExp[$fileExpCnt]); //확장자
	$file_name   = date("Ymdhis").'.'.$fileExtension; //업로드명 설정 $pStr.date("Ymdhis").$randNum.'.'.$fileExtension; 

	$ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));    
	//$uploadfile = $file_dir . $datename . "." . $ext;		
	/*
	저장할 파일이름과 경로 (한글 파일 오류로 인해 파일이름 년월일시분초 로 변경)
	파일이름의 중복을 피하기 위해서 date 함수를 사용하여 파일이름대신 년월일시분초로 파일이름을 저장했습니다 !
	사용하시려면 아래 $file_name 부분에 $uploadfile 을 사용하시면 됩니다 ! 파일이름은 마음대로 조정 가능 !
	*/
		
	if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext)    
	{        
		echo "<script> alert('이미지만 가능합니다. 다시 업로드 해주세요.'); </script>"; 
		exit;
	}     
	
	$save_dir = sprintf('%s/%s', $up_dir, $file_name);   
	$save_url = sprintf('%s/%s', $up_url, $file_name);
	

	if (move_uploaded_file($_FILES["upload"]["tmp_name"],$save_dir))
	{        
		//chmod($save_dir, 0777);			//파일 권한은 사용자 마음대로 해주세요 !
		echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$save_url', '업로드완료');</script>";
	}
}
?> 