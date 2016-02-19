<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 사용자지정 function
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    /*------------------------------------------------------------------------------------------------------*
     * NullVal(값, 자바스크립트 타입번호(JsAlert), URL이동, 숫자여부)
     * pNumeric - "string":모든타입, "numeric":숫자만
     * 값 존재여부 확인(nullValReturn : True, False 반환)
     *------------------------------------------------------------------------------------------------------*/

	/** @smtp Mail 보내기
	 *
	 * @param $fromName 보내는 사람 이름
	 * @param $fromEmail 보내는 사람 메일
	 * @param $toName 받는 사람 이름
	 * @param $toEmail 받는 사람 메일
	 * @param $subject 메일제목
	 * @param $contents 메일 내용
	 * @param $isDebug 디버깅할때 1로 해서 사용하세요.
	 * @return sendmail_flag 성공(true) 실패(false) 여부
	 */


	function sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $contents, $isDebug=0){
		//Configuration
		$smtp_host = "localhost";
		$port = 25;
		$type = "text/html";
		$charSet = "UTF-8";


		//Open Socket
		$fp = @fsockopen($smtp_host, $port, $errno, $errstr, 1);
		if($fp){

			$returnMessage = fgets($fp, 128);
			if($isDebug)
				print "CONNECTING MSG:".$returnMessage."\n";
			fputs($fp, "HELO YA\r\n");
			$returnMessage = fgets($fp, 128);
			if($isDebug)
				print "GREETING MSG:".$returnMessage."\n";

			fputs($fp, "auth login\r\n");
			fgets($fp,128);
			fputs($fp, base64_encode("clovergarden100")."\r\n");
			fgets($fp,128);
			fputs($fp, base64_encode("clovergarden1001")."\r\n");
			fgets($fp,128);

			fputs($fp, "MAIL FROM: <".$fromEmail.">\r\n");
			$returnvalue[0] = fgets($fp, 128);
			fputs($fp, "rcpt to: <".$toEmail.">\r\n");
			$returnvalue[1] = fgets($fp, 128);

			if($isDebug){
				print "returnvalue:";
				print_r($returnvalue);
			}

			//Data
			fputs($fp, "data\r\n");
			$returnMessage = fgets($fp, 128);
			if($isDebug)
				print "data:".$returnMessage;
			fputs($fp, "Return-Path: ".$fromEmail."\r\n");
			$fromName = "=?".$fromName."?B?".base64_encode($fromName)."?=";
			fputs($fp, "From: ".$fromName." <".$fromEmail.">\r\n");
			fputs($fp, "To: <".$toEmail.">\r\n");
			$subject = "=?".$charSet."?B?".base64_encode($subject)."?=";

			fputs($fp, "Subject: ".$subject."\r\n");
			fputs($fp, "Content-Type: ".$type."; charset=\"".$charSet."\"\r\n");
			fputs($fp, "Content-Transfer-Encoding: base64\r\n");
			fputs($fp, "\r\n");
			$contents= chunk_split(base64_encode($contents));

			fputs($fp, $contents);
			fputs($fp, "\r\n");
			fputs($fp, "\r\n.\r\n");
			$returnvalue[2] = fgets($fp, 128);

			//Close Connection
			fputs($fp, "quit\r\n");
			fclose($fp);

			//Message
			if (ereg("^250", $returnvalue[0])&&ereg("^250", $returnvalue[1])&&ereg("^250", $returnvalue[2])){
				$sendmail_flag = true;
			}else {
				$sendmail_flag = false;
				print "NO :".$errno.", STR : ".$errstr;
			}
		}

    	return $sendmail_flag;
	}


	function NullVal($pStr, $pNum, $pUrl, $pNumeric='string')
    {
        if($pStr == '' || $pStr == null){
           JsAlert(NO_PATH, $pNum, $pUrl);
        }
        if($pNumeric == 'numeric'){
            if(is_numeric($pStr) == false){
                JsAlert(NO_PATH, $pNum, $pUrl);
            }else if((int)($pStr > 9000000)){
                JsAlert(NO_PATH, $pNum, $pUrl);
            }
        }
        return $pStr;
    }

    function NullValReturn($pStr, $pNum='string')
    {
        $valResult = false;
        if($pStr != "" && $pStr != null){
            if($pNum == ""){
                $valResult = true;
            }else if($pNum == 'numeric'){
                if(is_numeric($pStr) == true){
                    if($pStr < 9000000) $valResult = true;
                }
            }
        }
        return $valResult;
    }

    function NullNumber($pNum)
    {
        if($pNum){
            if(is_numeric($pNum) == false){
                $pNum = 1;
            }else if($pNum > 9000000){
                $pNum = 1;
            }
        }else{
            $pNum = 1;
        }
        return $pNum;
    }

    /*------------------------------------------------------------------------------------------------------*
     * 자바스크립트 alert & 페이지이동 처리
     * JsAlert(alert 내용, 자바스크립트 타입번호, URL이동)
     *------------------------------------------------------------------------------------------------------*/
    function JsAlert($pMsg, $pNum=0, $pUrl='')
    {
        switch($pNum)
        {
            case 0 :
                $sMsg = 'history.back();';
                break;
            case 1 :
                $sMsg = "location.href = '".$pUrl."';";
                break;
            case 2 :
                $sMsg = "window.opener='nothing';window.open('', '_parent', '');window.close();";
                break;
            case 3 :
                $sMsg = "opener.location.href = '".$pUrl."';self.close();";
                break;
            case 4 :
                $sMsg = 'opener.location.reload();';
                break;
            case 5 :
                $sMsg = "opener.location.reload();window.opener='nothing';window.open('','_parent','');window.close();";
                break;
            case 6 :
                $sMsg = ''; //alert만 출력
                break;
        }
        if($pMsg) $pMsg = "alert('".$pMsg."');";
		echo "<!doctype html><html><head>";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        echo "<meta http-equiv='Content-Script-Type' content='text/javascript'>";
        echo "<meta http-equiv='Content-Style-Type' content='text/css'>";
        echo "<script language='javascript'>".$pMsg.$sMsg."</script>";
        exit;
    }

    /*------------------------------------------------------------------------------------------------------*
     * UrlReDirect(경고창, URL이동)
     *------------------------------------------------------------------------------------------------------*/
    function UrlReDirect($pMsg, $pUrl)
    {
        if($pMsg) $pMsg = "alert('".$pMsg."');";
        echo "<!doctype html><html><head>";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        echo "<meta http-equiv='Content-Script-Type' content='text/javascript'>";
        echo "<meta http-equiv='Content-Style-Type' content='text/css'>";
        echo "</head><body><script language='javascript'>".$pMsg."location.replace('".$pUrl."');</script></body></html>";
        exit;
    }

    /*------------------------------------------------------------------------------------------------------*
     * 이미지
     *------------------------------------------------------------------------------------------------------*/
     /*
    function ImageView($pUrl, $pFile, $pWidth='700', $pUser='')
    {
        if(FileExists($pUrl.$pFile)){ //파일존재여부확인
            $MimeType = strtolower(substr($pFile, strrpos($pFile,'.') +1 )); //확장자
            if($MimeType == 'gif' || $MimeType == 'jpg' || $MimeType == 'jpeg' || $MimeType == 'bmp' || $MimeType == 'png'){
                $ImageSizes = getimagesize($pUrl.$pFile);
                $pWidth  = $ImageSizes[0];
                $pHeight = "height='".$ImageSizes[1]."'";
                $pDocumentUrl = str_replace('mobileservice/', '', $pUrl.$pFile); //기본경로 적용 replace
                if(!$pUser){
                    echo "<p><center><img src='".$pDocumentUrl."' width='".$pWidth."' ".$pHeight."></center><br/></p>";
                }else{
                    if($pWidth > 360){
                        $pWidth = '100%';
                    }
                    echo "<p><center><div style='width:100%;'><img src='".$pDocumentUrl."' width='".$pWidth."'></div></center><br/></p>";
                };
            };
        };
    }
    */

    function ImageView($pUrl, $pFile, $pWidth='100', $pAlign='', $pDocUrl='', $pMobile='')
    {
        if(FileExists($pUrl.$pFile)){ //파일존재여부확인
            $MimeType = strtolower(substr($pFile, strrpos($pFile,'.') +1 )); //확장자
            if($MimeType == 'gif' || $MimeType == 'jpg' || $MimeType == 'jpeg' || $MimeType == 'bmp' || $MimeType == 'png'){
                $ImageSizes = getimagesize($pUrl.$pFile);
                $pWidth  = $ImageSizes[0];
                $pHeight = "height='".$ImageSizes[1]."'";
                if(!$pMobile){
                    echo "<p ".$pAlign."><img src='".$pDocUrl.$pUrl.$pFile."' width='".$pWidth."' ".$pHeight."><br/></p>";
                }else{
                    if($pWidth > 360){
                        $pWidth = '100%';
                    }
                    echo "<p ".$pAlign."><div style='width:100%;'><img src='".$pDocUrl.$pUrl.$pFile."' width='".$pWidth."'></div><br/></p>";
                };
            };
        };
    }

    /*------------------------------------------------------------------------------------------------------*
     * 라디오버튼 & 체크박스 & 셀렉트박스
     * 체크박스   : WriteCheck(선택값, "name=''", ,"radio", "예,아니오", "선택,선택안함", "")
     *------------------------------------------------------------------------------------------------------*/
	 
	function WriteList($pSelectField, $pSelectValue, $pVal, $pName, $pOption='', $pReturn='')
    {
        $pMsg = '<div class=sub_cate_depth2><ul '.$pName.'>';
		$Cnt=count($pSelectField);

        for($i=0; $i < $Cnt; $i++) {	
            $pMsg = $pMsg . "<li class='list".$pSelectValue[$i]."' ".$pOption." onclick='javascript:setCate(".$pSelectValue[$i].")'>".$pSelectField[$i]."</li>";
        }
        $pMsg = $pMsg . '</ul></div>';
        $pMsg = str_replace("class='list".$pVal."'", "class='list".$pVal." on'", $pMsg);
        if(!$pReturn){
            echo $pMsg;
        }else{
            return $pMsg;
        }
    }

    function WriteMultiCheck($pVal, $pName, $pType, $pValue, $pOption='', $pReturn='')
    {
        $pMultiVal = explode(',',$pVal);
        $pValue    = explode(',',$pValue);
        $pSpOption = explode(',',$pOption);
        for($i=0, $Cnt=count($pValue); $i < $Cnt; $i++) {
            $pMsg = $pMsg . "<input type='".$pType."' ".$pName." value='".$pValue[$i]."'/>".$pSpOption[$i];
        }
        if($pVal){
            for($i=0, $Cnt=count($pMultiVal); $i < $Cnt; $i++) {
                if($pMultiVal[$i]){
                    $pMsg = str_replace("value='".$pMultiVal[$i]."'", "value='".$pMultiVal[$i]."' checked", $pMsg);
                }
            }
        }
        if(!$pReturn){
            echo $pMsg;
        }else{
            return $pMsg;
        }
    }

    function WriteCheck($pVal, $pName, $pType, $pValue, $pOption='', $pReturn='')
    {
        $pValue    = explode(',',$pValue);
        $pSpOption = explode(',',$pOption);
        for($i=0, $Cnt=count($pValue); $i < $Cnt; $i++) {
            $pMsg = $pMsg . "<input type='".$pType."' ".$pName." value='".$pValue[$i]."'/>".$pSpOption[$i];
        }
        if($pVal) $pMsg = str_replace("value='".$pVal."'", "value='".$pVal."' checked", $pMsg);
        if(!$pReturn){
            echo $pMsg;
        }else{
            return $pMsg;
        }
    }

    function WriteSelect($pSelectField, $pSelectValue, $pVal, $pName, $pOption='', $pReturn='')
    {
        $pMsg = '<select '.$pName.'>';
        if($pOption) $pMsg = $pMsg . "<option value=''>".$pOption."</option>";
        for($i=0, $Cnt=count($pSelectField); $i < $Cnt; $i++) {
            $pMsg = $pMsg . "<option value='".$pSelectValue[$i]."'>".$pSelectField[$i]."</option>";
        }
        $pMsg = $pMsg . '</select>';
        if($pVal) $pMsg = str_replace("value='".$pVal."'", "value='".$pVal."' selected", $pMsg);
        if(!$pReturn){
            echo $pMsg;
        }else{
            return $pMsg;
        }
    }

    function WriteValue($pVal, $pValue, $pOption)
    {
        $pValue    = explode(',',$pValue);
        $pSpOption = explode(',',$pOption);
        for($i=0, $Cnt=count($pValue); $i < $Cnt; $i++) {
            if($pVal == $pValue[$i]) {
                $pMsg = $pSpOption[$i];
                break;
            }
        }
        echo $pMsg;
    }

    /*------------------------------------------------------------------------------------------------------*
     * 문자열 자르기
     *------------------------------------------------------------------------------------------------------*/
    function StringSplit($pVal, $pNum, $pStr='..'){
        preg_match('/([\x00-\x7e]|..)*/', substr($pVal, 0, $pNum), $returnStr);
        if($pNum < strlen($pVal)) $returnStr[0] = $returnStr[0].$pStr;
        return $returnStr[0];
    }


	function resizeString($Str, $size, $addStr="...")  {
		if(mb_strlen($Str, "UTF-8") > $size) return mb_substr($Str, 0, $size, "UTF-8").$addStr;
		else return $Str;
	}

    /*------------------------------------------------------------------------------------------------------*
     * 새글표시
     *------------------------------------------------------------------------------------------------------*/
    function NewData($pDate, $pNum=1){
        $pNowDate = date('Y-m-d');
        $pNowTime = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $pChkTime = ($pNowTime - 86400) * $pNum;
        $pChkDate = date('Y-m-d', $pChkTime);
        $pRegTime = mktime(0, 0, 0, substr($pDate,5,2), substr($pDate,8,2), substr($pDate,0,4));
        if($pChkTime <= $pRegTime) {
            echo " <font style='color:red; font-size: 11px;'>New</font>";
        }
    }

	function NewCheck($pDate, $pNum=1){
        $pNowDate = date('Y-m-d');
        $pNowTime = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $pChkTime = ($pNowTime - 86400) * $pNum;
        $pChkDate = date('Y-m-d', $pChkTime);
        $pRegTime = mktime(0, 0, 0, substr($pDate,5,2), substr($pDate,8,2), substr($pDate,0,4));
        if($pChkTime <= $pRegTime) {
            return true;
        }else{
			return false;
		}
    }

    /*------------------------------------------------------------------------------------------------------*
     * 날짜기간
     *------------------------------------------------------------------------------------------------------*/
    function dateDiff($date1, $date2){
        $exp_date1 = explode("-",$date1);
        $exp_date2 = explode("-",$date2);
        $tm1 = mktime(0,0,0,$exp_date1[1],$exp_date1[2],$exp_date1[0]);
        $tm2 = mktime(0,0,0,$exp_date2[1],$exp_date2[2],$exp_date2[0]);
        return ($tm1 - $tm2) / 86400;
    }

    /*------------------------------------------------------------------------------------------------------*
     * Replace 처리여부
     *------------------------------------------------------------------------------------------------------*/
    function RequestXss($pStr) //크로스 사이트 스크립트 처리
    {
        return XssChk(trim($pStr));
    }

    function RequestReXss($pStr) //크로스 사이트 스크립트 반환
    {
        return ReXssChk(trim($pStr));
    }

    function RequestDb($pStr) //인젝션공격 처리
    {
        return DbChk(trim($pStr));
    }

    function RequestAll($pStr) //크로스 사이트 스크립트 & 인젝션공격 처리
    {
        return DbChk(XssChk(trim($pStr)));
    }

    /*------------------------------------------------------------------------------------------------------*
    * 정규식 패턴체크
    *------------------------------------------------------------------------------------------------------*/
    function PattenCheck($pStr, $pNum, $pPattern="")
    {
        $emailPatten = "/^([a-z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-z0-9_\-]+\.)+))([a-z]{2,4}|[0-9]{1,3})(\]?)+$/";

        $ptn[0]  = '/^[0-9]*$/';                       // 0:숫자만
        $ptn[1]  = '/^[a-zA-Z\d\-_]*$/';               // 1:영문만
        $ptn[2]  = "/^[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]+$/";         // 2:한글만
        $ptn[3]  = "/^[0-9|a-zA-Z\d\-_]+$/";           // 3:한글만
        $ptn[4]  = "/^([0-9|a-z|A-Z|_]{4,20})+$/";     // 4:아이디
        $ptn[5]  = "/^[a-z|A-Z|ㄱ-ㅎ|ㅏ-ㅣ|가-힣]+$/"; // 5:성명
        $ptn[6]  = $emailPatten;                       // 6:이메일
        $ptn[7]  = "/^([0-9|a-z|A-Z]{4,20})+$/";       // 7:숫자영문
        $ptn[20] = $pPattern;                          // 20:기타
        if(preg_match($ptn[$pNum], $pStr)){
            $pattenResult = '1'; //true
        }else{
            $pattenResult = '0'; //false
        }
        return $pattenResult;
    }


    /*------------------------------------------------------------------------------------------------------*
     * 크로스 사이트 스크립트
     *------------------------------------------------------------------------------------------------------*/
    function XssChk($pStr)
    {
        $pStr = str_replace("'", "''", $pStr);
        $pStr = str_replace('""', '&#34;', $pStr);
        $pStr = str_replace('<', '&lt;', $pStr);
        $pStr = str_replace('>', '&gt;', $pStr);
        $pStr = str_replace('(', '&#40;', $pStr);
        $pStr = str_replace(')', '&#41;', $pStr);
        $pStr = str_replace('|', '&#124;', $pStr);
        //$pStr = htmlspecialchars($pStr);
        return $pStr;
    }

    function ReXssChk($pStr)
    {
        $pStr = str_replace("''", "'", $pStr);
        $pStr = str_replace('&#34;', '""', $pStr);
        $pStr = str_replace('&lt;', '<', $pStr);
        $pStr = str_replace('&gt;', '>', $pStr);
        $pStr = str_replace('&#40;', '(', $pStr);
        $pStr = str_replace('&#41;', ')', $pStr);
        $pStr = str_replace('&#124;', '|', $pStr);
        //return stripslashes($pStr);
        return $pStr;
    }

    /*------------------------------------------------------------------------------------------------------*
     * Replace
     *------------------------------------------------------------------------------------------------------*/
    function RepVal($pStr)
    {
        return str_replace("'", "''", $pStr);
    }

    function RepBr($pStr)
    {
        return nl2br($pStr);
    }

    function RepFile($pStr)
    {
        $pStr = str_replace(';', '_', $pStr);
        $pStr = str_replace("'", '_', $pStr);
		$pStr = str_replace('"', '_', $pStr);
        return $pStr;
    }

    function RepEditor($pStr)
    {
        $pStr = str_replace("'", "''", $pStr);
        $pStr = preg_replace('/select/i', '', $pStr);
        $pStr = preg_replace('/insert/i', '', $pStr);
        $pStr = preg_replace('/update/i', '', $pStr);
        $pStr = preg_replace('/delete/i', '', $pStr);
        $pStr = preg_replace('/drop/i', '', $pStr);
        $pStr = preg_replace('/union/i', '', $pStr);
        $pStr = preg_replace('/varchar/i', '', $pStr);
        /*
        $pStr = preg_replace('/db_owner/i', '', $pStr);
        $pStr = preg_replace('/db_name\(\)/i', '', $pStr);
        $pStr = preg_replace('/openrowset\(\)/i', '', $pStr);
        $pStr = preg_replace('/sysobjects/i', '', $pStr);
        $pStr = preg_replace('/is_srvrolemember/i', '', $pStr);
        $pStr = preg_replace('/cookie/i', '', $pStr);
        $pStr = preg_replace('/shutdown/i', '', $pStr);
        $pStr = preg_replace('/@variable/i', '', $pStr);
        $pStr = preg_replace('/exec/i', '', $pStr);
        $pStr = preg_replace('/xp_cmdshell/i', '', $pStr);
        $pStr = preg_replace('/xp_stratmail/i', '', $pStr);
        $pStr = preg_replace('/xp_sendmail/i', '', $pStr);
        $pStr = preg_replace('/xp_grantlogin/i', '', $pStr);
        $pStr = preg_replace('/xp_makewebtask/i', '', $pStr);
        $pStr = preg_replace('/xp_dirtree/i', '', $pStr);
        $pStr = preg_replace('/sp_/i', '', $pStr);
        */
        return $pStr;
    }

    function DbChk($pStr)
    {
        $pStr = str_replace('--', '', $pStr);
        $pStr = str_replace('/*', '', $pStr);
        $pStr = str_replace('*/', '', $pStr);
        $pStr = str_replace('%', Chr(37), $pStr);
        $pStr = preg_replace('/script/i', '', $pStr);
        $pStr = preg_replace('/\.js/i', '', $pStr);
        $pStr = preg_replace('/select/i', '', $pStr);
        $pStr = preg_replace('/insert/i', '', $pStr);
        $pStr = preg_replace('/update/i', '', $pStr);
        $pStr = preg_replace('/delete/i', '', $pStr);
        $pStr = preg_replace('/drop/i', '', $pStr);
        $pStr = preg_replace('/union/i', '', $pStr);
        $pStr = preg_replace('/varchar/i', '', $pStr);
        /*
        $pStr = preg_replace('/db_owner/i', '', $pStr);
        $pStr = preg_replace('/db_name\(\)/i', '', $pStr);
        $pStr = preg_replace('/openrowset\(\)/i', '', $pStr);
        $pStr = preg_replace('/sysobjects/i', '', $pStr);
        $pStr = preg_replace('/is_srvrolemember/i', '', $pStr);
        $pStr = preg_replace('/cookie/i', '', $pStr);
        $pStr = preg_replace('/shutdown/i', '', $pStr);
        $pStr = preg_replace('/@variable/i', '', $pStr);
        $pStr = preg_replace('/exec/i', '', $pStr);
        $pStr = preg_replace('/xp_cmdshell/i', '', $pStr);
        $pStr = preg_replace('/xp_stratmail/i', '', $pStr);
        $pStr = preg_replace('/xp_sendmail/i', '', $pStr);
        $pStr = preg_replace('/xp_grantlogin/i', '', $pStr);
        $pStr = preg_replace('/xp_makewebtask/i', '', $pStr);
        $pStr = preg_replace('/xp_dirtree/i', '', $pStr);
        $pStr = preg_replace('/sp_/i', '', $pStr);
        */
        //return addslashes($pStr);
        return $pStr;
    }
?>