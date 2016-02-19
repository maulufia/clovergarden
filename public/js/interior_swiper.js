$(function(){	
	var contentSwiper = $('.swiper-content').swiper({
		noSwiping :true,
		autoplay : 7000,
		noSwipingClass : 'swiper-wrapper',
		slidesPerView: 1,
		calculateHeight: true,
		resizeReInit: true,
		onSlideChangeStart: function(){
			$('.swiper-nav .swiper-slide-active').removeClass('swiper-slide-active')
			var activeNav = $('.swiper-nav .swiper-slide').eq(contentSwiper.activeIndex).addClass('swiper-slide-active')
			if (!activeNav.hasClass('swiper-slide-visible')) {
				if (activeNav.index()>navSwiper.activeIndex) {
					var thumbsPerNav = Math.floor(navSwiper.width/activeNav.width())-1
					navSwiper.swipeTo(activeNav.index()-thumbsPerNav)
				}
				else {
					navSwiper.swipeTo(activeNav.index())
				}	
			}
		}
	});

	var navSwiper = $('.swiper-nav').swiper({
		slidesPerView:'auto',
		noSwiping :true,
		mode:'horizental',
		noSwipingClass : 'swiper-wrapper',
		onSlideClick: function(){
			contentSwiper.swipeTo( navSwiper.clickedSlideIndex )
		}
	});

$(document).on("click", "button.prev", function(e) {
e.preventDefault();
contentSwiper.swipePrev();
});  

$(document).on("click", "button.next", function(e) {
e.preventDefault();
contentSwiper.swipeNext();
});


});
