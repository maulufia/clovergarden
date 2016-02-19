/*------------------------------------------------------------------------------------------------------
    url, form object, callback function, 전송타입(get, post), 데이터처리(html, json, script, xml)

    var ajax = new Ajax();
    ajax.ajax("textHtml.jsp", document.getElementById("frm"), callback, "post");
------------------------------------------------------------------------------------------------------*/
    function Ajax()
    {
        this.post = function(url, frm, callback, type)
        {
            $.post(url, frm.serialize(), callback, type);
        };
        this.get = function(url, frm, callback, type)
        {
            $.get(url, frm.serialize(), callback, type);
        };
        this.ajax = function(url, frm, callback, methodType, type)
        {
            if(!type) type = "php";
            if(methodType == "post"){
                this.post(url, $(frm), callback, type);
            }else{
                this.get(url, $(frm), callback, type);
            }
        };
    }

    //이미지 width
    function imageResizer(className, maxWidth){
        j$("."+className+" img").each(function() {
            var imgwidth = j$(this).attr("width");
            if (imgwidth > maxWidth) {
                var imgheight = j$(this).attr("height");
                var ratio = imgwidth/imgheight;
                var newHeight = Math.round(maxWidth/ratio);
                j$(this).attr({width:maxWidth, height:newHeight});
            }
          })
    }

    //이미지 height
    function imageResizerHeight(className, maxHeight){
        j$("."+className+" img").each(function() {
            var imgwidth  = j$(this).attr("width");
            var imgheight = j$(this).attr("height");
            if(imgheight > maxHeight){
                var ratioWidth = imgheight/imgwidth;
                var newWidth = Math.round(maxHeight/ratioWidth);
                j$(this).attr({width:newWidth, height:maxHeight});
            }
          })
    }

    //이미지 슬라이딩
    function imageSlideList(pWidth)
    {
        j$(document).ready(function(){
          var currentPosition = 0;
          var slideWidth = pWidth;
          var slides = j$('.slide');
          var numberOfSlides = slides.length;

          j$('#slidesContainer').css('overflow', 'hidden');

          slides
            .wrapAll('<div id="slideInner"></div>')
            .css({
              'float' : 'left',
              'width' : slideWidth
            });

          j$('#slideInner').css('width', slideWidth * numberOfSlides);
          j$('#slideshow')
            .prepend('<span class="control" id="leftControl">left</span>')
            .append('<span class="control" id="rightControl">right</span>');

          manageControls(currentPosition);

          j$('.control')
            .bind('click', function(){
            currentPosition = (j$(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;

            manageControls(currentPosition);
            j$('#slideInner').animate({
              'marginLeft' : slideWidth*(-currentPosition)
            });
          });

          function manageControls(position){
            if(position==0){ j$('#leftControl').hide() } else{ j$('#leftControl').show() }
            if(position==numberOfSlides-1){ j$('#rightControl').hide() } else{ j$('#rightControl').show() }
          }
        });
    }

    //display toggle
    function divToggle(pId){
     j$("#"+pId).toggle();
    }

    //display block
    function divShow(pId){
     j$("#"+pId).show();
    }

    //display none
    function divHide(pId){
     j$("#"+pId).hide();
    }

    //display block, none
    function displayClick(pId,pStyle){
        if(pStyle == "hide"){
            j$("#"+pId).hide();
        }else if(pStyle == "show"){
            j$("#"+pId).show();
        }
    }

    function vodPlay(src,auto,w,h)
    {
        var width_val  = 448;
        var height_val = 298;
        if(w) width_val  = w;
        if(h) height_val = h;

        ie = "<object id=\"vodpalyer\" name=\"vodpalyer\" width="+width_val+" height="+height_val+" viewastext style=\"z-index:1\""
            + " classid=\"CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95\" "
            + " codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701\""
            + " standby=\"Loading Microsoft Windows Media Player components...\" type=\"application/x-oleobject\">"
            + "<param name=\"FileName\" value=\""+src+"\">"
            + "<param name=\"ANIMATIONATSTART\" value=\"1\">"
            + "<param name=\"AUTOSTART\" value=\""+auto+"\">"
            + "<param name=\"BALANCE\" value=\"0\">"
            + "<param name=\"CURRENTMARKER\" value=\"0\">"
            + "<param name=\"CURRENTPOSITION\" value=\"0\">"
            + "<param name=\"DISPLAYMODE\" value=\"4\">"
            + "<param name=\"ENABLECONTEXTMENU\" value=\"0\">"
            + "<param name=\"ENABLED\" value=\"1\">"
            + "<param name=\"FULLSCREEN\" value=\"0\">"
            + "<param name=\"INVOKEURLS\" value=\"1\">"
            + "<param name=\"PLAYCOUNT\" value=\"1\">"
            + "<param name=\"RATE\" value=\"1\">"
            + "<param name=\"SHOWCONTROLS\" value=\"1\">"
            + "<param name=\"SHOWSTATUSBAR\" value=\"-1\">"
            + "<param name=\"STRETCHTOFIT\" value=\"0\">"
            + "<param name=\"TRANSPARENTATSTART\" value=\"1\">"
            + "<param name=\"UIMODE\" value=\"FULL\">"
            + "<param name=\"displaybackcolor\" value=\"0\">"
            + "</object>"

        ff = "<embed id=\"vodpalyer\" name=\"vodpalyer\" animationatstart=\"0\" autostart=\"1\" displaybackcolor=\"black\" showcontrols=\"1\" showstatusbar=\"1\" showtracker=\"1\""
            + " showpositioncontrols=\"0\" pluginspage=\"http://www.microsoft.com/korea/windows/windowsmedia/\""
            + " src="+src+" type=\"video/x-ms-asf-plugin\" width="+width_val+"  height="+height_val+" ></embed>"

        var sUserAgent = navigator.userAgent.toLowerCase();
        var isIE = (sUserAgent.indexOf("msie 6.") != -1 && (sUserAgent.indexOf("msie 7.") < 0 || sUserAgent.indexOf("opera") == -1 && window.document.all)) ? true:false;
        if(isIE){
            document.write(ie);
        }else{
            document.write(ff);
        }
    }

    function vodPlayFile(src)
    {
        vodpalyer.Filename = src;
        vodpalyer.AutoStart = "True";
    }

    function isAgm(agm, len, val){
        var agmL = agm.length - 1;
        return agmL < len ? val : agm[len];
    };

    //브라우져 별 아이디 객체 스타일
    function getStyleObject(objectId) {
        if(document.getElementById && document.getElementById(objectId)){
            return document.getElementById(objectId).style;
        }else if (document.all && document.all(objectId)){
            return document.all(objectId).style;
        }else if (document.layers && document.layers[objectId]){
            return document.layers[objectId];
        }else{
            return false;
        }
    };

    //브라우져 별 아이디 객체
    function getObject(objectId) {
        if(document.getElementById && document.getElementById(objectId)){
            return document.getElementById(objectId);
        }else if (document.all && document.all(objectId)){
            return document.all(objectId);
        }else if (document.layers && document.layers[objectId]){
            return document.layers[objectId];
        }else{
            return false;
        }
    };

    function writeEmbed(url,wd,ht){
        var id      = isAgm(writeEmbed.arguments, 3, null);
        var n       = 0;
        while(getObject("writeValue_" + n)) n++;
        document.write("<textarea id=\"writeValue_" + n + "\" style=\"display:none;\" cols=\"0\" rows=\"0\">");
        document.write("    <object name=\"swfPlayer\" id=\"swfPlayer\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"" + wd + "\" height=\"" + ht + "\" id=\"fmedia_\"" + n + ">");
        document.write("    <param name=\"movie\" value=\"" + url + "\">");
        document.write("    <param name=\"menu\" value=\"false\">");
        document.write("    <param name=\"wmode\" value=\"transparent\" >");
        document.write("    <param name=\"quality\" value=\"high\">");
        document.write("    <param name=\"allowScriptAccess\" value=\"always\">");
        document.write("    <param name=\"FlashVars\" value=\"" + id + "\">");
        document.write("    <embed src=\"" + url + "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"" + wd + "\" height=\"" + ht + "\" wmode=\"transparent\" allowScriptAccess=\"always\" name=\"ExtInterface\" FlashVars=\"" + id + "\"></embed>");
        document.write("    </object>");
        document.write("</textarea>")

        document.write(getObject("writeValue_" + n).value);
    };

    // addLoadEvent
    function addLoadEvent(func) {
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = func;
        } else {
            window.onload = function() {
                oldonload();
                func();
            }
        }
    }

    function bgOverflow(){
        var container = document.getElementById("container");
        if(typeof container == "object"){
            var right_area = document.getElementById("right_area");
            var cWidth = document.compatMode == "CSS1Compat" ? document.documentElement.clientWidth : document.body.clientWidth;
            if(cWidth < 961){
                container.style.width = "960"+"px";
                right_area.style.width = "740"+"px";
            }else{
                var right_area_width = cWidth - 230;
                container.style.width = "100%";
                right_area.style.width = right_area_width + "px";
            }
        }
    }

    /*
    addLoadEvent(bgOverflow);
    window.onresize=function(){
        bgOverflow();
    }
    */

    function showToggle(ele){
        var ele = document.getElementById(ele);
        if(ele.style.display != 'block'){
            ele.style.display ="block";
            ele.style.zIndex  = "10001";
        }else{
            ele.style.display ="none";
            ele.style.zIndex  = "0";
        }
        return false;
    }

    /*** 레이어 팝업창 띄우기 ***/
    function popupLayer(s,w,h)
    {
        if (!w) w = 600;
        if (!h) h = 400;

        var pixelBorder = 3;
        var titleHeight = 12;
        w += pixelBorder * 2;
        h += pixelBorder * 2 + titleHeight;

        var bodyW = document.body.clientWidth;
        var bodyH = document.body.clientHeight;

        var posX = (bodyW - w) / 2;
        var posY = (bodyH - h) / 2;

        hiddenSelectBox('hidden');

        /*** 백그라운드 레이어 ***/
        var obj = document.createElement("div");
        with (obj.style){
            position = "absolute";
            left = 0;
            top = 0;
            width = "100%";
			height = "100%";
            height = document.body.scrollHeight;
            backgroundColor = "#000000";
            filter = "Alpha(Opacity=50)";
            opacity = "0.5";
        }
        obj.id = "objPopupLayerBg";
        document.body.appendChild(obj);

        /*** 내용프레임 레이어 ***/
        var obj = document.createElement("div");
        with (obj.style){
            position = "absolute";
            left = posX + document.body.scrollLeft;
            top = posY + document.body.scrollTop - 80;
            width = w;
            height = h;
			top = "50%";
			left = "50%";
            backgroundColor = "#FFFFFF";
            border = "3px solid #000000";
        }
        obj.id = "objPopupLayer";
        document.body.appendChild(obj);

        /*** 타이틀바 레이어 ***/
        var bottom = document.createElement("div");
        with (bottom.style){
            position = "absolute";
            width = (w - pixelBorder * 2) + 6;
            height = titleHeight + 7;
            left = 0;
            top = h - titleHeight - pixelBorder * 3;
            padding = "2px 0 0 0";
            textAlign = "right";
            backgroundColor = "#000000";
            color = "#ffffff";
            font = "bold 11px tahoma";
        }
        bottom.innerHTML = "<a href='javascript:closeLayer()' class=white>close</a>&nbsp;&nbsp;&nbsp;";
        obj.appendChild(bottom);

        /*** 아이프레임 ***/
        var ifrm = document.createElement("iframe");
        with (ifrm.style){
            width = w - 6;
            height = h - pixelBorder * 2 - titleHeight - 3;
            //border = "3 solid #000000";
        }
        ifrm.frameBorder = 0;
        ifrm.src = s;
        //ifrm.className = "scroll";
        obj.appendChild(ifrm);
    }
    function closeLayer()
    {
        hiddenSelectBox('visible');
        _ID('objPopupLayer').parentNode.removeChild( _ID('objPopupLayer') );
        _ID('objPopupLayerBg').parentNode.removeChild( _ID('objPopupLayerBg') );
    }

    function hiddenSelectBox(mode)
    {
        var obj = document.getElementsByTagName('select');
        for (i=0;i<obj.length;i++){
            obj[i].style.visibility = mode;
        }
    }

    function _ID(obj){return document.getElementById(obj)}
