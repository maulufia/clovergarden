/*------------------------------------------------------------------------------------------------------
    ※ 폼체크 JavaScript

    정규패턴식 변수정의
------------------------------------------------------------------------------------------------------*/
    var onlyNumber   = /^[0-9]+$/
    var onlyEnglish  = /^[a-z|A-Z| ]+$/
    var onlyKorea    = /^[ㄱ-ㅎ|ㅏ-ㅣ|가-힣| ]+$/
    var emailCheck1  = /^([a-z]([a-z|0-9|_]{3,19})@([a-z|A-Z|0-9]{2,20})\.([a-z|A-Z]{2,5}))+$/
    var emailCheck2  = /^([a-z]([a-z|0-9|_]{3,19})@([a-z|A-Z|0-9]{2,20})\.([a-z|A-Z]{2,10})\.([a-z|A-Z]{2,5}))+$/
    var idCheck      = /^([a-z|0-9|_]{6,15})+$/
    var nicNameCheck = /^([가-힣a-zA-Z0-9_-~`!@#$^&*=+]{2,20})+$/

    var emailAfterCheck1    = /^(([a-z|0-9]{2,20})\.([a-z|A-Z]{2,10})\.([a-z|A-Z]{2,5}))+$/
    var emailAfterCheck2    = /^(([a-z|0-9]{2,20})\.([a-z|A-Z]{2,5}))+$/
    var injectionCheck      = /\/\*|\*\/|@variable|xp_cmdshell|xp_stratmail|xp_sendmail|xp_grantlogin|xp_makewebtask|xp_dirtree|db_owner|xp_|sp_|db_|union|sysobjects|is_srvrolemember|cookie|shutdown|alter|\.js|script|create|declare|select|insert|drop|update|delete|truncate|cmdshell|execmaster|exec|netlocalgroupadministratthens|netuser|kill|xmp|count\(|asc\(|mid\(|char\(|varchar\(|db_name\(\)|openrowset\(\)/
    var injectionEditeCheck = /\/\*|\*\/|@variable|xp_cmdshell|xp_stratmail|xp_sendmail|xp_grantlogin|xp_makewebtask|xp_dirtree|db_owner|xp_|sp_|db_|union|sysobjects|is_srvrolemember|cookie|shutdown|alter|create|declare|truncate|cmdshell|execmaster|exec|netlocalgroupadministratthens|netuser|kill|xmp|count\(|asc\(|mid\(|char\(|varchar\(|db_name\(\)|openrowset\(\)/

    var nicTypeMsg   = "_-~`!@#$^&*=+";
    var imageTypeMsg = "gif, jpg, jpeg, bmp, png";
    var imageType    = /\.(gif|jpg|jpeg|bmp|png)$/
    var movieTypeMsg = "wmv, asf";
    var movieType    = /\.(wmv|asf)$/
    var wordTypeMsg  = "pdf, ppt, pptx, xls, xlsx, csv, doc, docx, hwp, txt";
    var wordType     = /\.(pdf|ppt|pptx|xls|xlsx|csv|doc|docx|hwp|txt)$/
    var comTypeMsg   = "alz, zip";
    var comType      = /\.(alz|zip)$/
    var swfTypeMsg   = "swf";
    var swfType      = /\.(swf)$/
    var flvTypeMsg   = "flv";
    var flvType      = /\.(flv)$/
    var inSTypeMsg   = "gif, jpg, jpeg, bmp, swf";
    var inSType      = /\.(gif|jpg|jpeg|bmp|swf)$/
    var exiTypeMsg   = "업로드 제한";
    var exiType      = /\.(aspx|asp|asa|asax|htm|html|js|jsp|php|vbs|css|xml|config|cab|dll|exe|msi|ini)$/

    var juminFront   = ""; // 주민번호 앞자리 저장 변수

/*------------------------------------------------------------------------------------------------------
    객체속성 체크함수 - elementPrototype(해당객체)
    ⓟElement      - 객체
    ⓟTypeName     - 객체타입
    ⓟTagName      - 객체태그
    ⓟElementName  - 객체이름
    ⓟElementValue - 객체값
------------------------------------------------------------------------------------------------------*/
    function elementPrototype(checkElement)
    {
        if (checkElement.length > 1 && objectYN(checkElement.options) == false) // 라디오박스or체크박스 = true
        {
            return {
                Element : checkElement[0],
                TypeName : checkElement[0].type.toUpperCase(),
                TagName : checkElement[0].tagName.toUpperCase(),
                ElementName : checkElement[0].name,
                ElementValue : checkElement[0].value
            }
        }
        else
        {
            return {
                Element : checkElement,
                TypeName : checkElement.type.toUpperCase(),
                TagName : checkElement.tagName.toUpperCase(),
                ElementName : checkElement.name,
                ElementValue : checkElement.value
            }
        }
    }

/*------------------------------------------------------------------------------------------------------
    폼안의 어트리뷰트 체크함수 - elementAttribute(해당객체)
------------------------------------------------------------------------------------------------------*/
    function elementAttribute(checkElement)
    {
        // 경고창에 표시될 객체명
        if (objectYN(checkElement.getAttribute("title")) == true) {
            aL_Title = checkElement.getAttribute("title");
        } else {
            aL_Title = "";
        }
        return {
            aL_Title  : aL_Title,                               // 객체명

            aL_Exp : checkElement.getAttribute("exp"),          // 필수입력
            aL_Num : checkElement.getAttribute("num"),          // 숫자만입력
            aL_Eng : checkElement.getAttribute("eng"),          // 영어만입력
            aL_Kor : checkElement.getAttribute("kor"),          // 한글만입력
            aL_Uid : checkElement.getAttribute("uid"),          // ID형식 체크
            aL_Nic : checkElement.getAttribute("nic"),          // 닉네임형식 체크
            aL_Inj : checkElement.getAttribute("inj"),          // 인젝션공격성 문자열 체크

            aL_Eml : checkElement.getAttribute("eml"),          // E-mail 형식 체크
            aL_Ela : checkElement.getAttribute("ela"),          // E-mail 뒷부분 형식 체크

            aL_Pho : checkElement.getAttribute("pho"),          // 이미지만 업로드
            aL_Mov : checkElement.getAttribute("mov"),          // 영상만 업로드
            aL_Wor : checkElement.getAttribute("wor"),          // 문서만 업로드
            aL_Com : checkElement.getAttribute("com"),          // 압축파일만 업로드
            aL_Swf : checkElement.getAttribute("swf"),          // 플래시파일만 업로드
            aL_Flv : checkElement.getAttribute("flv"),          // flv파일만 업로드
            aL_Ins : checkElement.getAttribute("ins"),          // 이미지/플래시파일만 업로드
            aL_Exi : checkElement.getAttribute("exi"),          // 업로드 제한 확장자

            aL_Ju1 : checkElement.getAttribute("ju1"),          // 주민등록번호 앞자리 체크
            aL_Ju2 : checkElement.getAttribute("ju2"),          // 주민등록번호 뒷자리 체크
            aL_Jum : checkElement.getAttribute("jum"),          // 주민등록번호 체크

            aL_MaxLen : checkElement.getAttribute("maxlen"),    // 최대 입력글자수 제한
            aL_MinLen : checkElement.getAttribute("minlen"),    // 최소 입력글자수 제한
            aL_MaxNum : checkElement.getAttribute("maxnum"),    // 최대 숫자 제한
            aL_MinNum : checkElement.getAttribute("minnum")     // 최소 숫자 제한
        }
    }

/*------------------------------------------------------------------------------------------------------
    객체 true,false 검사 - objectYN(검사할 객체)
------------------------------------------------------------------------------------------------------*/
    function objectYN(obj)
    {
        if (typeof(obj) != "undefined" && obj != "" && obj != null) {
            return true;
        } else {
            return false;
        }
    }

/*------------------------------------------------------------------------------------------------------
    객체 true,false 검사(어트리뷰트 전용) - attYN(검사할 객체)
------------------------------------------------------------------------------------------------------*/
    function attYN(obj)
    {
        if (typeof(obj) != "undefined" && obj != null) {
            return true;
        } else {
            return false;
        }
    }

/*------------------------------------------------------------------------------------------------------
    앞, 뒤 공백 제거 - strReplace(객체 값)
------------------------------------------------------------------------------------------------------*/
    function strReplace(Field)
    {
        Field = Field.replace(/(^\s*)|(\s*$)/g, "")
      //Field = Field.replace("<p>&nbsp;</p>", "")
        return Field;
    }

/*------------------------------------------------------------------------------------------------------
    폼안의 객체검사 - formCheck(검사할 폼)
    사용법 :if(formCheck(frm) == true){ frm.action = "액션페이지";frm.submit(); }
------------------------------------------------------------------------------------------------------*/
    function formCheck(form)
    {
        var obj, att, f;
        var elementLength = form.elements.length;

        for (f=0; f<elementLength; f++)
        {
            obj = elementPrototype(form.elements[f]);   // 객체속성 체크함수
            att = elementAttribute(obj.Element);        // 객체 어트리뷰트 체크함수

            if (attYN(att.aL_Exp) == true) { if (fn_Exp(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Num) == true) { if (fn_Num(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Eng) == true) { if (fn_Eng(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Kor) == true) { if (fn_Kor(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Uid) == true) { if (fn_Uid(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Nic) == true) { if (fn_Nic(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Inj) == true) { if (fn_Inj(obj.Element, att.aL_Title) == false) { return false; } }

            if (attYN(att.aL_Eml) == true) { if (fn_Eml(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Ela) == true) { if (fn_Ela(obj.Element, att.aL_Title) == false) { return false; } }

            if (attYN(att.aL_Pho) == true) { if (fn_Pho(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Mov) == true) { if (fn_Mov(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Wor) == true) { if (fn_Wor(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Com) == true) { if (fn_Com(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Swf) == true) { if (fn_Swf(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Flv) == true) { if (fn_Flv(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Ins) == true) { if (fn_Ins(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Exi) == true) { if (fn_Exi(obj.Element, att.aL_Title) == false) { return false; } }

            if (attYN(att.aL_Ju1) == true) { if (fn_Ju1(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Ju2) == true) { if (fn_Ju2(obj.Element, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_Jum) == true) { if (fn_Jum(obj.Element, att.aL_Title) == false) { return false; } }

            if (attYN(att.aL_MaxLen) == true) { if (fn_Max(obj.Element, att.aL_MaxLen, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_MinLen) == true) { if (fn_Min(obj.Element, att.aL_MinLen, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_MaxNum) == true) { if (fn_Mxn(obj.Element, att.aL_MaxNum, att.aL_Title) == false) { return false; } }
            if (attYN(att.aL_MinNum) == true) { if (fn_Mnn(obj.Element, att.aL_MinNum, att.aL_Title) == false) { return false; } }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    폼안의 객체검사 - formCheckSub(검사할 폼, 구분자, 메세지)
    사용법 : if(formCheckSub(폼.이름, "구분자", "메세지") == false){ return false; }
------------------------------------------------------------------------------------------------------*/
    function formCheckSub(chk, gb, msg)
    {
        if (gb == "exp") { if (fn_Exp(chk, msg) == false) { return false; } }
        if (gb == "num") { if (fn_Num(chk, msg) == false) { return false; } }
        if (gb == "eng") { if (fn_Eng(chk, msg) == false) { return false; } }
        if (gb == "kor") { if (fn_Kor(chk, msg) == false) { return false; } }
        if (gb == "uid") { if (fn_Uid(chk, msg) == false) { return false; } }
        if (gb == "nic") { if (fn_Nic(chk, msg) == false) { return false; } }
        if (gb == "inj") { if (fn_Inj(chk, msg) == false) { return false; } }

        if (gb == "eml") { if (fn_Eml(chk, msg) == false) { return false; } }
        if (gb == "ela") { if (fn_Ela(chk, msg) == false) { return false; } }

        if (gb == "pho") { if (fn_Pho(chk, msg) == false) { return false; } }
        if (gb == "mov") { if (fn_Mov(chk, msg) == false) { return false; } }
        if (gb == "wor") { if (fn_Wor(chk, msg) == false) { return false; } }
        if (gb == "com") { if (fn_Com(chk, msg) == false) { return false; } }
        if (gb == "swf") { if (fn_Swf(chk, msg) == false) { return false; } }
        if (gb == "flv") { if (fn_Flv(chk, msg) == false) { return false; } }
        if (gb == "ins") { if (fn_Ins(chk, msg) == false) { return false; } }
        if (gb == "exi") { if (fn_Exi(chk, msg) == false) { return false; } }

        if (gb == "ju1") { if (fn_Ju1(chk, msg) == false) { return false; } }
        if (gb == "ju2") { if (fn_Ju2(chk, msg) == false) { return false; } }
        if (gb == "jum") { if (fn_Jum(chk, msg) == false) { return false; } }
    }

    function formCheckNum(chk, gb, number, msg)
    {
        if (gb == "maxlen") { if (fn_Max(chk, number, msg) == false) { return false; } }
        if (gb == "minlen") { if (fn_Min(chk, number, msg) == false) { return false; } }
        if (gb == "maxnum") { if (fn_Mxn(chk, number, msg) == false) { return false; } }
        if (gb == "minnum") { if (fn_Mnn(chk, number, msg) == false) { return false; } }
    }

    function formCheckSubEdit(chk1, chk2, gb1, gb2, msg)
    {
        if (gb2 == "edexp") { if (fn_Edexp(chk1, chk2, gb1, msg) == false) { return false; } }
        if (gb2 == "edinj") { if (fn_Edinj(chk1, chk2, gb1, msg) == false) { return false; } }
        if (gb2 == "edfckexp") { if (fn_EdFckexp(chk1, chk2, gb1, msg) == false) { return false; } }
    }

/*------------------------------------------------------------------------------------------------------
    필수입력 체크함수 - fn_Exp(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Exp(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN"  || obj.TypeName == "NUMBER"  || obj.TypeName == "HIDDEN" || obj.TypeName == "TEL")
        {
            if (strReplace(obj.ElementValue) == "" || strReplace(obj.ElementValue.toLowerCase()) == "<p>&nbsp;</p>" || strReplace(obj.ElementValue) == "&nbsp;")
            {
                if (obj.TypeName == "HIDDEN")
                {
                    alert("[ "+ alertMsg +" ] 값이 없습니다.\n\n관리자에게 문의해 주십시오.");
                    return false;
                }
                else
                {
                    alert("[ "+ alertMsg +" ] 필수입력 사항입니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        else if (obj.TypeName == "FILE")
        {
            if (strReplace(obj.ElementValue) == "")
            {
                alert("[ "+ alertMsg +" ] 필수 등록사항입니다.");
                obj.Element.focus(); return false;
            }
        }
        else if (obj.TypeName == "SELECT-ONE")
        {
            if (strReplace(obj.ElementValue) == "")
            {
                alert("[ "+ alertMsg +" ] 필수선택 사항입니다.");
                obj.Element.focus(); return false;
            }
        }
        else if (obj.TypeName == "RADIO")
        {
            var Radio_YN = "N";
            var RadioName = document.getElementsByName(obj.ElementName);

            if (objectYN(RadioName.length) == true)
            {
                for (r=0; r<RadioName.length; r++)
                {
                    if (RadioName[r].checked == true){
                        Radio_YN = "Y";
                    }
                }
                if (Radio_YN == "N")
                {
                    alert("[ "+ alertMsg +" ] 필수선택 사항입니다.");
                    RadioName[0].focus(); return false;
                }
            }
        }
        else if (obj.TypeName == "CHECKBOX")
        {
            var CheckBox_YN = "N";
            var CheckBoxName = document.getElementsByName(obj.ElementName);

            if (objectYN(CheckBoxName.length) == true)
            {
                for (c=0; c<CheckBoxName.length; c++)
                {
                    if (CheckBoxName[c].checked == true){
                        CheckBox_YN = "Y";
                    }
                }
                if (CheckBox_YN == "N")
                {
                    alert("[ "+ alertMsg +" ] 필수선택 사항입니다.");
                    CheckBoxName[0].focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    숫자 체크함수 - fn_Num(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Num(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN"  || obj.TypeName == "NUMBER"  || obj.TypeName == "HIDDEN" || obj.TypeName == "TEL")
            {
                if (onlyNumber.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 숫자만 입력해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    영어 체크함수 - fn_Eng(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Eng(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (onlyEnglish.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 영문만 입력해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    한글 체크함수 - fn_Kor(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Kor(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (onlyKorea.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 한글만 입력해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    ID 체크함수 - fn_Uid(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Uid(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (idCheck.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 영문소문자,숫자만 입력해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    닉네임 체크함수 - fn_Nic(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Nic(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (nicNameCheck.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ nicTypeMsg +" ] 특수문자만 허용됩니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    인젝션공격성 문자열 체크함수 - fn_Inj(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Inj(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (injectionCheck.test(obj.ElementValue.toLowerCase()) == true)
                {
                    alert("[ "+ alertMsg +" ] [ "+ obj.ElementValue.toLowerCase().match(injectionCheck) +" ] 문자는 입력할 수 없습니다.");
                    //obj.Element.value = obj.ElementValue.replace(obj.ElementValue.match(injectionCheck), "");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    Email 전체 체크함수 - fn_Eml(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Eml(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (emailCheck1.test(obj.ElementValue) == false && emailCheck2.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    Email 뒷부분 체크함수 - fn_Ela(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Ela(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (emailAfterCheck1.test(obj.ElementValue) == false && emailAfterCheck2.test(obj.ElementValue) == false)
                {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    이미지파일 체크함수 - fn_Pho(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Pho(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (imageType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ imageTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    동영상파일 체크함수 - fn_Mov(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Mov(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (movieType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ movieTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    문서파일 체크함수 - fn_Wor(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Wor(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (wordType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ wordTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    압축파일 체크함수 - fn_Com(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Com(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (comType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ comTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    플래시파일 체크함수 - fn_Swf(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Swf(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (swfType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ swfTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

    function fn_Flv(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (flvType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ flvTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    이미지/플래시파일 체크함수 - fn_Ins(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Ins(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (inSType.test(obj.ElementValue.toLowerCase()) == false)
                {
                    alert("[ "+ alertMsg +" ] [ "+ inSTypeMsg +" ] 파일만 업로드해 주십시오.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    업로드 제한 확장자 체크함수 - fn_Exi(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Exi(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "FILE")
            {
                if (exiType.test(obj.ElementValue.toLowerCase()) == true)
                {
                    alert("[ "+ alertMsg +" ] [ "+ exiTypeMsg +" ] 확장자 파일입니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    에디터 체크함수 - fn_Edexp(해당객체1, 해당객체2, 구분, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Edexp(checkElement1, checkElement2, gbEditName, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수
        if (gbEditName == "easy")
        {
            obj.ElementValue = eval(obj.ElementName +".getHtml()"); // 에디터 객체값 다시 정의
        }
        if (strReplace(obj.ElementValue) == "" || strReplace(obj.ElementValue.toLowerCase()) == "<p>&nbsp;</p>" || strReplace(obj.ElementValue) == "&nbsp;")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                alert("[ "+ alertMsg +" ] 필수입력 사항입니다.");
                checkElement2.focus(); return false;
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    FCK 에디터 체크함수 - fn_EdFckexp(해당객체1, 해당객체2, 구분, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_EdFckexp(checkElement1, checkElement2, gbEditName, alertMsg)
    {
        if(checkElement2 == "" || checkElement2.toLowerCase() == "<p>&nbsp;</p>" || checkElement2.toLowerCase() == "<p></p>" || checkElement2.toLowerCase() == "<br />" || checkElement2.toLowerCase() == "<br>" || checkElement2.toLowerCase() == "&nbsp;")
        {
            alert("[ 내용 ] 필수입력 사항입니다.");
            checkElement1.EditorDocument.body.focus();
            return false;
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    에디터 인젝션 체크함수 - fn_Edinj(해당객체1, 해당객체2, 구분, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Edinj(checkElement1, checkElement2, gbEditName, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수
        if (gbEditName == "easy")
        {
            obj.ElementValue = eval(obj.ElementName +".getHtml()"); // 에디터 객체값 다시 정의
        }
        if (strReplace(obj.ElementValue) != "" && strReplace(obj.ElementValue.toLowerCase()) != "<p>&nbsp;</p>" || strReplace(obj.ElementValue) == "&nbsp;")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if (injectionEditeCheck.test(obj.ElementValue.toLowerCase()) == true)
                {
                    alert("[ "+ alertMsg +" ] [ "+ obj.ElementValue.toLowerCase().match(injectionEditeCheck) +" ] 문자는 입력할 수 없습니다.");
                    obj.Element.value = obj.ElementValue.replace(obj.ElementValue.match(injectionEditeCheck), "");
                    checkElement2.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    주민등록번호 앞자리 체크함수 - fn_Ju1(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Ju1(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if(formCheckSub(checkElement, "num", alertMsg) == false)
                {
                    return false;
                }
                if (strReplace(obj.ElementValue).length != 6) {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        juminFront = obj.ElementValue;
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    주민등록번호 뒷자리 체크함수 - fn_Ju2(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Ju2(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if(formCheckSub(checkElement, "num", alertMsg) == false)
                {
                    return false;
                }
                if (strReplace(obj.ElementValue).length != 7)
                {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
                if (strReplace(juminFront) != "" && strReplace(obj.ElementValue) != "")
                {
                    if (JuminNumberCheckResult(juminFront, obj.ElementValue) == true)
                    {
                        alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                        obj.Element.focus(); return false;
                    }
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    주민등록번호 전체 체크함수 - fn_Jum(해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Jum(checkElement, alertMsg)
    {
        var obj = elementPrototype(checkElement); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "")
        {
            if (obj.TypeName == "TEXT" || obj.TypeName == "PASSWORD" || obj.TypeName == "TEXTAREA" || obj.TypeName == "HIDDEN")
            {
                if(formCheckSub(checkElement, "num", alertMsg) == false)
                {
                    return false;
                }
                if (strReplace(obj.ElementValue).length != 13)
                {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
                if (JuminNumberCheckResult(obj.ElementValue.substring(0, 6), obj.ElementValue.substring(6, 13)) == true)
                {
                    alert("[ "+ alertMsg +" ] 형식이 올바르지 않습니다.");
                    obj.Element.focus(); return false;
                }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    주민등록번호 유효성 검사 - JuminNumberCheckResult(주민등록번호 앞자리, 주민등록번호 뒷자리)
------------------------------------------------------------------------------------------------------*/
    function JuminNumberCheckResult(strJumin1, strJumin2)
    {
        var juminNumber = strJumin1 + strJumin2;

        ju = new Array(13);
        for (var i=0; i<13; i++)
        {
            ju[i] = parseInt(juminNumber.charAt(i));
        }

        var jn = ju[0]*2 + ju[1]*3 + ju[2]*4 + ju[3]*5 + ju[4]*6 + ju[5]*7 + ju[6]*8 + ju[7]*9 + ju[8]*2 + ju[9]*3 + ju[10]*4 + ju[11]*5;
        var jn = jn % 11;
        var re = 11 - jn;

        if(re > 9){ re = re % 10; }
        if(re != ju[12]){//올바르지 않은 번호
            return true;
        }else{//올바른 번호
            return false;
        }
    }

/*------------------------------------------------------------------------------------------------------
    사업자등록번호 유효성 검사 - checkBizNumberCheck(사업자등록번호)
------------------------------------------------------------------------------------------------------*/
    function checkBizNumberCheck(bizID)
    {
        var checkID = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5, 1);
        var i, Sum  = 0, c2, remander;
        bizID = bizID.replace(/-/gi,'');

        for (i=0; i<=7; i++)
        {
            Sum += checkID[i] * bizID.charAt(i);
        }
        c2 = "0" + (checkID[8] * bizID.charAt(8));
        c2 = c2.substring(c2.length - 2, c2.length);
        Sum += Math.floor(c2.charAt(0)) + Math.floor(c2.charAt(1));
        remander = (10 - (Sum % 10)) % 10 ;

        if(bizID.length != 10)
        {
            return false;
        }
        else if (Math.floor(bizID.charAt(9)) != remander)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


/*------------------------------------------------------------------------------------------------------
    업로드파일이 없을때 체크함수 - fn_Fyn(업로드파일 객체, 업로드파일이 없을때 체크객체, 메세지)
    사용법 : if (fn_Fyn(폼.이름, 폼.이름, "메세지") == false) { return false; }
------------------------------------------------------------------------------------------------------*/
    function fn_Fyn(checkElement1, checkElement2, alertMsg)
    {
        if (strReplace(checkElement1.value) == "")
        {
            if (strReplace(checkElement2.value) == "")
            {
                if(formCheckSub(checkElement2, "exp", alertMsg) == false) { return false; }
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    객체1/객체2 비교함수 (서로 같아야한다) - fn_Tpt(비교객체1, 비교객체2, 메세지)
    사용법 : if (fn_Tpt(폼.이름, 폼.이름, "메세지") == false) { return false; }
------------------------------------------------------------------------------------------------------*/
    function fn_Tpt(checkElement1, checkElement2, alertMsg)
    {
        if (strReplace(checkElement1.value) != "" && strReplace(checkElement2.value) != "")
        {
            if (checkElement1.value != checkElement2.value)
            {
                alert("[ "+ alertMsg +" ] 서로 일치하지 않습니다.");
                checkElement1.focus(); return false;
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    숫자 체크함수 - fn_Max(해당객체, 해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Max(checkElement1, checkElement2, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수
        if (strReplace(obj.ElementValue) != "" && checkElement2 != "" && checkElement2 != null)
        {
            if (checkElement2 < getLen(obj.ElementValue)) {
                //alert("[ "+ alertMsg +" ] 입력된 글자수가 "+checkElement2+"자보다 작아야 합니다.\n\n(영문 "+checkElement2+"자, 한글 "+Math.floor(checkElement2/2)+"자까지 가능합니다.)");
                alert("[ "+ alertMsg +" ] 입력된 글자수가 "+checkElement2+"자보다 작아야 합니다.");
                obj.Element.focus(); return false;
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    숫자 체크함수 - fn_Min(해당객체, 해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Min(checkElement1, checkElement2, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "" && checkElement2 != "" && checkElement2 != null)
        {
            if (checkElement2 > getLen(obj.ElementValue)) {
                alert("[ "+ alertMsg +" ] 입력된 글자수가 "+checkElement2+"자보다 커야 합니다.");
                obj.Element.focus(); return false;
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    숫자 체크함수 - fn_Mxn(해당객체, 해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Mxn(checkElement1, checkElement2, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "" && checkElement2 != "" && checkElement2 != null)
        {
            if (parseInt(checkElement2) < parseInt(obj.ElementValue)) {
                alert("[ "+ alertMsg +" ] 입력된 숫자는 "+checkElement2+"보다 작아야 합니다.");
                obj.Element.focus(); return false;
            }
        }
        return true;
    }

/*------------------------------------------------------------------------------------------------------
    숫자 체크함수 - fn_Mnn(해당객체, 해당객체, 메세지)
------------------------------------------------------------------------------------------------------*/
    function fn_Mnn(checkElement1, checkElement2, alertMsg)
    {
        var obj = elementPrototype(checkElement1); // 객체속성 체크함수

        if (strReplace(obj.ElementValue) != "" && checkElement2 != "" && checkElement2 != null)
        {
            if (parseInt(checkElement2) > parseInt(obj.ElementValue)) {
                alert("[ "+ alertMsg +" ] 입력된 숫자는 "+checkElement2+"보다 커야 합니다.");
                obj.Element.focus(); return false;
            }
        }
        return true;
    }

/*=====================================================================================================
    기타 function
======================================================================================================*/
    function strTrim(fld)
    {
        var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
        fld.value = fld.value.replace(pattern, "");
        return fld.value;
    }

    function getLen(str) {
        var temp;
        var len = str.length;
        var tot_cnt = 0;
        for(k=0; k < len; k++){
            temp = str.charAt(k);
            if(escape(temp).length > 4){ tot_cnt += 2; }
            else{ tot_cnt++; }
        }
        return tot_cnt;
    }

    function delHref(href)
    {
        if(confirm("삭제하시겠습니까?"))
        document.location.href = href;
    }

    function onlyNum()
    {
        if((event.keyCode<48)||(event.keyCode>57))
        event.returnValue=false;
    }

    function engChk()
    {
         if((event.keyCode < 48) || (event.keyCode > 57) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
              event.returnValue=false;
    }

    function handlerHomepage()
    {
         if((event.keyCode != 46) && (event.keyCode < 48) || (event.keyCode > 57) && (event.keyCode < 65) || (event.keyCode > 90)&& (event.keyCode < 97) || (event.keyCode > 122))
         event.returnValue = false;
    }

    function handlerEng()
    {
         if((event.keyCode < 48) || (event.keyCode > 57) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
         event.returnValue = false;
    }
