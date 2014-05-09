(function($){
	var Blocks = {
		init : function(){
			this.addBoostrapCarouselSwipeSupport();
		},
		addBoostrapCarouselSwipeSupport : function(){
			$('.blocks_carousel_bootstrap').swiperight(function(){
				$(this).carousel('prev');
			});
			
			$('.blocks_carousel_bootstrap').swipeleft(function(){
				$(this).carousel('next');
			});
		}
	}

	$(document).ready(Blocks.init());
	
})(jQuery);
