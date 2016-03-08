<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 파일관련 function
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    /*------------------------------------------------------------------------------------------------------*
    * 파일 업로드
    *------------------------------------------------------------------------------------------------------*/
    function FileUpload($pUpFile, $pPath, $pStr, $pSize, $pStyle='file', $pName='name')
    {
        $typeProc = false; //파일업로드제한
        $sizeProc = false; //파일사이즈제한
        $randNum  = rand(100,9999);
        
        if($pUpFile['type']){
            $fileNameOrg   = $pUpFile['name'];
            $fileExp       = explode(".",$fileNameOrg);
            $fileExpCnt    = count($fileExp) - 1;
            $fileExtension = strtolower($fileExp[$fileExpCnt]); //확장자
			if($pName=='name'){
            $fileNewName   = $pStr.date("Ymdhis").$randNum.'.'.$fileExtension; //업로드명 설정
			}else{
			$fileNewName   = $pName.'.'.$fileExtension;
			}
            if(strpos('#|alz|aspx|asp|asa|asax|htm|html|php|php3|jsp|js|vbs|css|xml|ini|config|cab|dll|exe|sql|msi|','|$fileExtension|') == ''){
                if($pStyle == 'image'){
                    if(strpos("#|jpg|jpeg|gif|bmp|png|image/jpg|image/jpeg|image/pjpeg|image/gif|image/bmp|image/x-png|","|$fileExtension|")){
                        $typeProc = true;
                    }
                }else{
                    $typeProc = true;
                }
                if($typeProc == true){
                    if($pUpFile['size'] < (int)($pSize * 1024 * 1024)){
                        $sizeProc = true;
                        if(!is_dir($pPath)){
                            mkdir($pPath, 0777);
                            clearstatcache();
                        }
                        move_uploaded_file($pUpFile['tmp_name'], $pPath.$fileNewName); //파일업로드
                        //chmod($pPath.$fileNewName, 0777 );
                    }
                    if($pUpFile['size'] >= 1048576) $fileSizeResult = (int)($pUpFile['size']/1048576).'Mb';
                    elseif($pUpFile['size'] >= 1024) $fileSizeResult = (int)($pUpFile['size']/1024).'Kb';
                    else $fileSizeResult = (int)($pUpFile['size']).'Byte';
                }
            }
        }
        $fileUploadResult = Array($fileNameOrg, $fileNewName, $fileSizeResult, $typeProc, $sizeProc);
        return $fileUploadResult;
    }


	function FileMultiUpload($pUpFile, $pUpSize, $pUptmp, $pPath, $pStr, $pSize, $pStyle='file', $pName='name')
    {
        $typeProc = false; //파일업로드제한
        $sizeProc = false; //파일사이즈제한
        $randNum  = rand(100,9999);
        if($pUptmp){
            $fileNameOrg   = $pUpFile;
            $fileExp       = explode(".",$fileNameOrg);
            $fileExpCnt    = count($fileExp) - 1;
            $fileExtension = strtolower($fileExp[$fileExpCnt]); //확장자
			if($pName=='name'){
            $fileNewName   = $pStr.date("Ymdhis").$randNum.'.'.$fileExtension; //업로드명 설정
			}else{
			$fileNewName   = $pName.'.'.$fileExtension;
			}
            if(strpos('#|alz|aspx|asp|asa|asax|htm|html|php|php3|jsp|js|vbs|css|xml|ini|config|cab|dll|exe|sql|msi|','|$fileExtension|') == ''){
                if($pStyle == 'image'){
                    if(strpos("#|jpg|jpeg|gif|bmp|png|image/jpg|image/jpeg|image/pjpeg|image/gif|image/bmp|image/x-png|","|$fileExtension|")){
                        $typeProc = true;
                    }
                }else{
                    $typeProc = true;
                }
                if($typeProc == true){
                    if($pUpSize < (int)($pSize * 1024 * 1024)){
                        $sizeProc = true;
                        if(!is_dir($pPath)){
                            mkdir($pPath, 0777);
                            clearstatcache();
                        }
                        move_uploaded_file($pUptmp, $pPath.$fileNewName); //파일업로드
                        //chmod($pPath.$fileNewName, 0777 );
                    }
                    if($pUpSize >= 1048576) $fileSizeResult = (int)($pUpSize/1048576).'Mb';
                    elseif($pUpSize >= 1024) $fileSizeResult = (int)($pUpSize/1024).'Kb';
                    else $fileSizeResult = (int)($pUpSize).'Byte';
                }
            }
        }
        $fileUploadResult = Array($fileNameOrg, $fileNewName, $fileSizeResult, $typeProc, $sizeProc);
        return $fileUploadResult;
    }

    function FileCopyUpload($pFile, $pCopyPath, $pPath, $pStr)
    {
        $randNum       = rand(100,9999);
        $fileNameOrg   = $pFile;
        $fileExp       = explode(".",$pFile);
        $fileExpCnt    = count($fileExp) - 1;
        $fileExtension = strtolower($fileExp[$fileExpCnt]); //확장자
        $fileNewName   = $pStr.date("Ymdhis").$randNum.'.'.$fileExtension;

        copy($pCopyPath.$fileNameOrg, $pPath.$fileNewName);
        $fileSizeIcon = filesize($pPath.$fileNewName);

        if($fileSizeIcon >= 1048576) $fileSizeResult = (int)($fileSizeIcon/1048576).'Mb';
        elseif($fileSizeIcon >= 1024) $fileSizeResult = (int)($fileSizeIcon/1024).'Kb';
        else $fileSizeResult = (int)($fileSizeIcon).'Byte';

        clearstatcache();
        $fileCopyUploadResult = Array($fileNameOrg, $fileNewName, $fileSizeResult);
        return $fileCopyUploadResult;
    }

    /*------------------------------------------------------------------------------------------------------*
     * 파일 다운로드
     *------------------------------------------------------------------------------------------------------*/
    function FileStream($pPath, $pFileEdit, $pFileReal)
    {
         header('Content-Type:application/octet-stream');
         header('Content-Disposition:attachment; filename='.$pFileReal);
         header('Content-Transfer-Encoding:binary');
         header('Content-Length:'.(string)(filesize($pPath.$pFileEdit)));
         //header("Content-Description: PHP3 Generated Data");
         //header("Content-Description: PHP5 Generated Data");
         header('Cache-Control:cache, must-revalidate');
         header('Pragma:no-cache');
         header('Expires:0');
         $fopenBinary = fopen($pPath.$pFileEdit, "rb");
         while(!feof($fopenBinary))
         {
              echo fread($fopenBinary, 100*1024);
              flush();
         }
         fclose($fopenBinary);
         clearstatcache();
    }

    /*------------------------------------------------------------------------------------------------------*
     * 파일확인
     *------------------------------------------------------------------------------------------------------*/
    function FileExists($pFile)
    {
        if(is_file($pFile)) {
            $fileResult = true; //파일이 존재함
        } else {
            $fileResult = false;
        }
        clearstatcache();
        return $fileResult;
    }

?>