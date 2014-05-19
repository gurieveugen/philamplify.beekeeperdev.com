(function($){
	$(function() {
		// =========================================================
		// Initialize Social Feed
		// =========================================================
		$('.socials-holder article').each(function(){ $(this).addClass('hide'); });
		$('.feed-all').each(function(){ $(this).removeClass('hide'); });

		// =========================================================
		// Social filter button click
		// =========================================================
		$('.socials-filter > li > a').click(function(e){
			var social = $(this).data('social');

			$('.socials-filter').find('.active').removeClass('active');
			$(this).parent().addClass('active');
			
			$('.socials-holder article').each(function(){
				$(this).addClass('hide');
			});

			$('.feed-' + social).each(function(){
				$(this).removeClass('hide');
			});
			
			e.preventDefault();
		});

		$('.select-socials-filter').change(function(){
			var social = $(this).val();

			$('.socials-filter').find('.active').removeClass('active');
			$(this).parent().addClass('active');
			
			$('.socials-holder article').each(function(){
				$(this).addClass('hide');
			});

			$('.feed-' + social).each(function(){
				$(this).removeClass('hide');
			});
		});
	});	
})(jQuery);