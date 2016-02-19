/**
* @class
* jquery.layerPopup.js 占쏙옙占쏙옙占쏙옙트 클占쏙옙占쏙옙 占쏙옙占싱어를 占쌕울옙
*
* @example
*
*
* @name layerPopup
* @author JsYang <yakuyaku@gmail.com>
* @since 2009-10-16
* @version 1.0
*/

(function($) {

	$.layerPopup = function(event,options) {
		$.fn.layerPopup.defaults.self = true;
		return $.layerPopup.impl.init(event, false, options);
	};

	$.layerPopup.close = function() {
		$.layerPopup.impl.close();
	};

	$.fn.layerPopup = function(el,options) {
		$(this).click( function(event){
			 $.layerPopup.impl.init(event, el, options);
		});
	}

	$.layerPopup.impl = {

		layer: null,

		opts : null,

		status : 0 ,

		currentTarget: null,

		background: null,

		init : function(e, el, options) {
			var s = this;

			if(!e) e = e || window.event;
			s.currentTarget = (e.target) ? e.target : e.srcElement;
			s.opts  = $.extend({}, $.fn.layerPopup.defaults, options);

			if(el) s.layer = $(el);
			else  s.layer = $(this.opts.name);

			if(s.opts.backgroundDisplay) s.makeBackground();

			s.show();

			s.bindEvents();

		},

		show : function(){
			if( this.status == 0 ) {

				$(this.currentTarget).addClass(this.opts.active);
				this.layer.css({
					"position": "absolute",
					"zIndex" : "1000" ,
					"top"    :  this.opts.top  ,
					"left"   :  this.opts.left
				});

				if(this.opts.center) this.setCenter();

				this.layer.fadeIn(this.opts.speed);
				if(this.opts.backgroundDisplay)  this.background.fadeIn(this.opts.speed);
				this.status = 1;
			}
		},

		close : function() {
			if( this.status == 1) {
				this.unbindEvents();
				$(this.currentTarget).removeClass(this.opts.active);
				this.layer.fadeOut(this.opts.speed);
				if(this.opts.backgroundDisplay)  this.background.fadeOut(this.opts.speed);
				this.status = 0;
			};
		},

		bindEvents : function(){
			var s = this;

			$(this.opts.closeButton).bind("click.layerPopup", function(e){
				e.preventDefault();
				s.close();
			});

	/*		$(document).bind('keydown.layerPopup', function (e) {
				if ( s.status == 1 && e.keyCode == 27) { // ESC
					e.preventDefault();
					s.close();
				}
			}); */

			$(window).bind('resize.layerPopup', function() {
				s.setCenter();
			});

		},

		unbindEvents : function(){
			$(this.opts.name).unbind('click.layerPopup');
			$(document).unbind('keydown.layerPopup');
			$(window).unbind('resize.layerPopup');
		},

		setCenter : function(){
			this.layer.css('top',  $(window).scrollTop() + $(window).height()/2-this.layer.height()/2);
			this.layer.css('left', $(window).width()/2-this.layer.width()/2);
		},

		makeBackground: function() {
			if(!this.background) {
				this.background = $("<div></div>");
				this.background.css({
						"display" : "inline" ,
						"position" : "fixed" ,
						"height" : "100%" ,
						"width"  : "100%" ,
						"top"  : "0px" ,
						"left" : "0px" ,
						"background" : "#000000" ,
						"border" : "1px solid #cecece" ,
						"zIndex" : "2" ,
						"opacity": "0.5",
						"overflow" : "hidden"
					});
				$('body').prepend(this.background);
			};
		}
	};

	$.fn.layerPopup.defaults = {
		name  : '#DataLayer' ,
		closeButton : '#close' ,
		active : 'activeBgLayer' ,
		backgroundDisplay  : true ,
		center : true ,
		speed  : 'fast' ,
		left  : '100px' ,
		top   : '200px'
	};



})(jQuery);
