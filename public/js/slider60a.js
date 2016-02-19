(function($){
    function _class(that,opt){
        this.obj=that;
        this.distance=opt.distance/50; //自动速度
        this.mdistance=(opt.mdistance===false)?this.distance*2:opt.mdistance/280;//触碰速度
        this.now_distance = this.distance; //当前移动速度
        this.objwidth=parseInt(this.obj.outerWidth()); //可视宽度
        this.ul=this.obj.children("ul");
        this.li=this.ul.children("li");
        this.ul.width=Number(this.li.outerWidth()+parseFloat(this.li.eq(2).css("margin-left")))*this.li.length-parseFloat(this.li.eq(2).css("margin-left"));
        this.left=(opt.left===false)?false:$(opt.left);// 左边按钮 - 왼쪽버튼selector
        this.right=(opt.right===false)?false:$(opt.right);// 右边按钮 - 오른쪽버튼selector
        this.direction=opt.direction;// 默认移动方向
        this.now_direction=this.direction;// 当前移动方向
        this.mouse_w=opt.mouse_w;// 左边鼠标移动范围
        this.rightw;// 右边鼠标移动范围
        this.rebound=(this.mouse_w===false)?opt.rebound:false;//是否回走
        this.init();
    }

    _class.prototype={
        init:function(){
            var that=this;
            that.obj.css({'position':'relative','overflow':'hidden'});
            that.li.css({'float':'left','width':that.li.innerWidth()});
            that.ul.css({'position':'absolute','left':'0','width':that.ul.width*2});
            if (that.ul.width < that.objwidth){
                return;
            }
            if(that.rebound===false) that.ul.append(that.li.clone());
            that.ul.children().each(function(){
                $(this).find('a').css({'position':'relative'});
            });
            if(that.mouse_w !== false){
                if (that.objwidth < (that.mouse_w * 2)){
                    that.mouse_w = (that.objwidth - Number(that.li.outerWidth())) / 2 ;
                }
                that.rightw=that.objwidth-that.mouse_w;
            }
            setInterval(function(){that.move()},20);
            that.event();
        },
        event:function(){
            var that=this;

            if(that.mouse_w !== false){
                that.obj.mousemove(function(e){
                    var x=e.pageX-that.obj.offset().left;
                    if (x<that.mouse_w)
                    {
                        that.now_distance = that.mdistance*(1-(x/that.mouse_w));
                        that.now_direction='right';
                    }else if (x>that.rightw)
                    {
                        that.now_distance = that.mdistance*((x-that.rightw)/that.mouse_w);
                        that.now_direction='left';
                    }else{
                        that.now_distance = 0;
                    }
                });
            }
            if(that.left !== false){
                that.left.mouseenter(function(){
                    that.now_direction = that.direction = 'left';
                    that.now_distance = that.mdistance;
                });
            }
            if(that.right !== false){
                that.right.mouseenter(function(){
                    that.now_direction = that.direction = 'right';
                    that.now_distance = that.mdistance;
                });
            }
            that.li.mouseenter(function(){
                that.now_distance = 0;
            });

		

            that.obj.mouseleave(function(){
                that.now_distance = that.distance;
                that.now_direction=that.direction;
            });
        },
        move:function(){
            var that=this;
            var now_left;
            var then_left = parseFloat(that.ul.css('left'));
            if(that.now_direction==='left'){
                now_left = then_left - that.now_distance;
            }else{
                now_left = then_left + that.now_distance;
            }
            that.ul.css({'left':now_left+'px'});

            if(that.rebound===true && (now_left>=0 || (Math.abs(now_left)+that.objwidth)>=that.ul.width)){
                if(now_left>=0){
                    that.now_direction = that.direction = 'left'
                }else if((Math.abs(now_left)+that.objwidth)>=that.ul.width){
                    that.now_direction = that.direction = 'right'
                }
            }else if(that.rebound===false){
                if(now_left < - that.ul.width){
                    that.ul.css({"left":(now_left + that.ul.width) +'px'});
                }else if(now_left > 0){
                    that.ul.css({"left":(now_left - that.ul.width) +'px'});
                }
            }
        }
    }

    var defaultOption={
        mouse_w:false,//触碰的范围
        direction:"left", //默认方向
        left:false,      //左边按钮ID - 왼쪽버튼selector
        right:false, //右边按钮ID - 오른쪽버튼selector
        mdistance:false,//触碰后速度    1秒 100像素 默认为 自动的2倍
        distance:"0", //自动速度     1秒 50像素
        rebound:false//是否回走
    };

    $.fn.slider58 = function ( opt ) {
        $.extend(defaultOption,opt);
        this.each(function(){
            new _class($(this),defaultOption);
        });
    }
	$.fn.slider59 = function ( opt ) {
        $.extend(defaultOption,opt);
        this.each(function(){
            new _class($(this),defaultOption);
        });
    }
})(window.jQuery);

$(function(){
    $('#demo').slider58({
        direction:"left", //默认方向 - 기본방향설정
        left:".btn_real_prev",      //左边按钮ID - 왼쪽버튼selector
        right:".btn_real_next", //右边按钮ID - 오른쪽버튼selector
        mdistance:"300",//触碰后速度  1秒 300像素 - 버튼마우스오버시 이동속도
        distance:"50" //自动速度   1秒 50像素 - 자동으로 움직일경우 속도
    });

	
});