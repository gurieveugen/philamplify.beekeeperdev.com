var disqus_shortname   = 'philamplify'; // required: replace example with your forum shortname
var disqus_identifier; //made of post id and guid
var disqus_url;
var urlArray           = [];
var newsroom_url_array = [];
var disqusPublicKey    = "OQayG6vSjiJn3lHkJ7th2geCSHpphVduQXc5TC0jk10xiYfFq1o19mvEWS7l8OJ1";
var msnry              = null;

(function($){
	$(function() {
		
		$( '#nav li:has(ul), .nav-tablet li:has(ul)' ).doubleTapToGo();
		
		$('#ico-menu').click(function() {
			$('.nav-box').toggle().toggleClass('open');
			$(this).toggleClass('open');
		});
		
		$('.form-poll input[type="radio"], .yop-poll-widget input[type="radio"], .filters-area select, .form-story select, .select-socials-filter, .form-efl input[type="radio"]').styler();
		
		$('.data-box .btn-box').click(function(e){
			$(this).toggleClass('open');
			$(this).parent().find('.content').slideToggle(200);
			
			$('.r-comments').each(function(){
				if($(this).hasClass("open")) $(this).removeClass('open');
			});			

			var comments   = $('.data-box .content .holder');
			var identifier = $(this).data('identifier');
			var url        = $(this).data('url');

			loadDisqus(comments, identifier, url);			
			e.preventDefault();
		});

		$('.r-box .cf .link-view').click(function(e){
			$(this).parent().parent().toggleClass('open');
			if($(this).parent().parent().hasClass('open'))
			{
				$(this).parent().parent().find('.content').slideDown(200);
			}
			else
			{
				$(this).parent().parent().find('.content').slideUp(200);
			}
			e.preventDefault();
		});
		
		$('.slider-area .slides').cycle({ 
			fx:     'scrollHorz',
			speed:  500,
			timeout: parseInt(default_settings.seconds)*1000,
			prev: '.slider-area .link-prev',
			next: '.slider-area .link-next'			
		});

		$('.mainslides').cycle({ 
			fx:     'scrollHorz',
			speed:  500,
			timeout: 0,
			pager:  '.slider-area .switcher',
			pagerAnchorBuilder: function(idx, slide) {
				return '.slider-area .switcher li:eq(' + idx + ') a';
			}
		});
		// =========================================================
		// FANCYBOX
		// =========================================================
		$('.fancybox-media').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			helpers : {
				media : {}
			}
		});
		// =========================================================
		// Subscribe AJAX
		// =========================================================
		$('.form-subscribe-ajax').submit(function(e){			
			jQuery.ajax({
    			type: "POST",
    			url: default_settings.ajaxurl + '?action=subscribe',
    			dataType: 'json',
    			data: $(this).serialize(),    			
    			success: function(data){  
    				if(data.add_subscriber)  				
    				{
    					window.location.href = default_settings.redirecturl + '/subscribe-ncrp-newsletter/';
    				}
    				alert(data.msg);
    			}
    		});
			e.preventDefault();
		});
		// =========================================================
		// MORE STORIES AJAX
		// =========================================================
		$('.more-stories-ajax').click(function(e){			
			var offset = default_settings.stories_count * default_settings.more_count;
			jQuery.ajax({
    			type: "POST",
    			url: default_settings.ajaxurl + '?action=moreStories',
    			dataType: 'json',
    			data: { offset: offset},    			
    			success: function(data){   
    				if(data.result)
    				{
    					var append_html = $(data.html);
    					default_settings.more_count++;   

    					$(default_settings.stories_container).append(append_html);
    					$(default_settings.stories_container).masonry('appended', append_html, true); 
    					setTimeout(function() { 
    						$(default_settings.stories_container).masonry('layout');
    					}, 1000);
    					
    				} 				    				
    			}
    		});
			e.preventDefault();
		});
		// =========================================================
		// AGREE AJAX
		// =========================================================
		$('.btn-agree').click(function(e){	
			var info = $(this).parent().parent().find('.info');						
			jQuery.ajax({
    			type: "POST",
    			url: default_settings.ajaxurl + '?action=agreeDisagree',
    			dataType: 'json',    	
    			data: {
    				type: 'agree',
    				post_id: $(this).parent().data('postId'),
    				recommendation_id: $(this).parent().data('id'),
    				id: default_settings.ip
    			},		
    			success: function(data){       
    				showUserInformation();
    				if(data.success)
    				{    					
    					info.html('<p class="info"><strong>' + data.percent + '%</strong> of ' + data.sum + ' people <strong class="blue">AGREE</strong></p>'); 	
    				}
    				alert(data.msg);
    			}
    		});
			e.preventDefault();
		});
		// =========================================================
		// DISAGREE AJAX
		// =========================================================
		$('.btn-disagree').click(function(e){
			var info = $(this).parent().parent().find('.info');						
			jQuery.ajax({
    			type: "POST",
    			url: default_settings.ajaxurl + '?action=agreeDisagree',
    			dataType: 'json',    
    			data: {
    				type: 'disagree',
    				post_id: $(this).parent().data('postId'),
    				recommendation_id: $(this).parent().data('id'),
    				id: default_settings.ip
    			},					
    			success: function(data){  
    				showUserInformation();
    				if(data.success)
    				{
    					info.html('<p class="info"><strong>' + data.percent + '%</strong> of ' + data.sum + ' people <strong class="blue">AGREE</strong></p>');    					
    				}
    				alert(data.msg); 				
    			}
    		});
			e.preventDefault();
		});

		// =========================================================
		// OPEN IN NEW WINDOW SOCIAL SHARE
		// =========================================================
		$('.social-share-buttons li a').click(function(e){
			window.open($(this).attr('href'),'displayWindow', 'width=700,height=400,left=200,top=100,location=no, directories=no,status=no,toolbar=no,menubar=no');
			e.preventDefault();
		});

		// =========================================================
		// MASONRY BRICS
		// =========================================================		
		$(window).load(function(){ 
			$(default_settings.stories_container).masonry({ itemSelector: '.box-story' });			
		});
		// =========================================================
		// FILTER CLICK
		// =========================================================
		$('.filter-icons li a').click(function(e){
			var id          = '#' + $(this).data('id');
			var selected    = $(this).data('selected');
			var notselected = $(this).data('notselected');
			var selector    = id.replace('#filter-', '.');

			$(this).toggleClass('selected');
			$(selector).toggleClass('hide');
			$(default_settings.stories_container).masonry('layout');
			if($(this).hasClass('selected'))
			{
				$(this).html('<img src="' + selected + '" alt="" />');
			}
			else
			{
				$(this).html('<img src="' + notselected + '" alt="" />');
			}
			
			e.preventDefault();
		});
		// =========================================================
		// TWEET CLICK
		// =========================================================
		$('.just-tweet').click(function(e){
			var url = 'https://twitter.com/share?text=' + $(this).data('text') + '&url=';
			window.open(url,'displayWindow', 'width=700,height=400,left=200,top=100,location=no, directories=no,status=no,toolbar=no,menubar=no');
			e.preventDefault();
		});
		// =========================================================
		// COMMENTS CLICK
		// =========================================================
		$('.link-comments').click(function(e){
			if($('.data-box .btn-box').hasClass('open'))
			{
				$('.data-box .btn-box').removeClass('open');
				$('.data-box .btn-box').parent().find('.content').slideToggle(200);
			}

			$('.r-comments').each(function(){
				if($(this).hasClass("open")) $(this).removeClass('open');
			});			

			var comments   = $('#r-comments-' + $(this).data('id'));
			var identifier = $(this).data('identifier');
			var url        = $(this).data('url');

			loadDisqus(comments, identifier, url);
			comments.addClass('open');
			e.preventDefault();
		});
		// =========================================================
		// SUBMIT STORY
		// =========================================================
		$('.form-share-story-ajax').submit(function(e){
			if($(this).find('[name=video_title]').val() == '' &&
				$(this).find('[name=photo_title]').val() == '' &&
				$(this).find('[name=media_title]').val() == '' &&
				$(this).find('[name=story_title]').val() == '')
			{
				e.preventDefault();
				alert('"Story title" is required. Please fill in this field!');
			}
			if(!$(this).find('[name=i_agree]').prop("checked"))
			{
				e.preventDefault();
				alert('You did not agree with Terms of Use and Privacy Policy!');
			}
		});
		// =========================================================
		// GET ALL URLS FOR DISQUS
		// =========================================================
		$('.link-comments').each(function () {
			var url = $(this).attr('data-url');
			urlArray.push('link:' + url);
		});		
		$('.disqus-comment').each(function(){			
			newsroom_url_array.push('link:' + $(this).data('url'));
		});		
		getAllCounts();
		// =========================================================
		// SELECT STATE CHANGE
		// =========================================================
		$('select.select-state').change(function(){
			var state = $('.state-' + $(this).val());			
			if(state.selector != '.state-ALL')
			{
				$(default_settings.stories_container).find('.box-story').each(function(){
					if(!$(this).hasClass('hide')) $(this).addClass('hide');
				});
				if(typeof(state) != 'undefined')
				{
					state.each(function(){
						$(this).removeClass('hide');
					});		
				}
				
			}
			else
			{
				$(default_settings.stories_container).find('.box-story').removeClass('hide');
			}
			$(default_settings.stories_container).masonry('layout');

			$('.filter-icons li a').each(function(){
				if(!$(this).hasClass('selected')) $(this).addClass('selected');
				$(this).html('<img src="' + $(this).data('selected') + '" alt="" />');
			});
		});
		// =========================================================
		// SELECT INDUSTRY CHANGE
		// =========================================================
		$('select.select-industry').change(function(e){			
			var state = $('.industry-' + $(this).val());			
			
			if(state.selector != '.industry--1')
			{
				$(default_settings.stories_container).find('.box-story').each(function(){
					if(!$(this).hasClass('hide')) $(this).addClass('hide');
				});
				if(typeof(state) != 'undefined')
				{
					state.each(function(){
						$(this).removeClass('hide');
					});		
				}
			}
			else
			{				
				$(default_settings.stories_container).find('.box-story').each(function(){
					$(this).removeClass('hide');
				});
			}
			$(default_settings.stories_container).masonry('layout');

			$('.filter-icons li a').each(function(){
				if(!$(this).hasClass('selected')) $(this).addClass('selected');
				$(this).html('<img src="' + $(this).data('selected') + '" alt="" />');
			});
		});
		// =========================================================
		// SET DEGAULT TEXT TO ASSESSMENT FORM
		// =========================================================		
		if(form_defaults !== undefined)
		{			
			//$("input[name='subject']").val(form_defaults.subject);
			//$("textarea[name='msg']").text(form_defaults.message);
		}
		// =========================================================
		// CLOSE LIGHTBOX IF CLICK MASK
		// =========================================================
		$('.lightbox-mask').click(function(e){
			$('.lightbox').each(function(){
				if(!$(this).hasClass('hide')) $(this).addClass('hide');
			});
			$(this).addClass('hide');
			e.preventDefault();
		});		
		// =========================================================
		// SHOW EMAIL LIGHTBOX
		// =========================================================
		$('.show-email-lightbox').click(function(e){
			var email = $(this).attr('href');
			var top   = $(document).scrollTop() + 100;
      //commented on April 30, 2014 BG-YS-DEV
			//$("#email-lightbox input[name='email']").val(email);
			$('#email-lightbox').removeClass('hide');
			$('.lightbox-mask').removeClass('hide');
			$('#email-lightbox').css({ top: top + 'px'});
			e.preventDefault();
		});
		disqus_config();

		// =========================================================
		// ADD DISQUS TO NEWS PAGES
		// =========================================================
		if($('.comments-section').html() != undefined)
		{			
			loadDisqus($('.comments-section'), $('.comments-section').data('id'), $('.comments-section').data('url'));	
		}
		

	});
	
})(jQuery);



function loadDisqus(source, identifier, url) 
{
	if (window.DISQUS) 
	{
		jQuery('#disqus_thread').insertAfter(source);
		/** if Disqus exists, call it's reset method with new parameters **/

		DISQUS.reset({
			reload: true,
			config: function () { 
				this.page.identifier = identifier.toString();    //important to convert it to string
				this.page.url        = url;
			}
		});
	} 
	else 
	{
		jQuery('<div id="disqus_thread"></div>').insertAfter(source);
		disqus_identifier = identifier;
		disqus_url        = url;	   
		var dsq           = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src           = 'http://' + disqus_shortname + '.disqus.com/embed.js';
		jQuery('head').append(dsq);
	}
}

function getAllCounts()
{

	if(urlArray.length > 0)
	{
		$.ajax({
			type: 'POST',
			url: default_settings.ajaxurl + '?action=disqusCounts',
			data: { api_key: disqusPublicKey, forum : disqus_shortname, thread : urlArray },
			dataType: 'json',
			success: function (result) {
				for (var i in result.response) {
					var countText = " comments";
					var count = result.response[i].posts;
					if (count == 1) countText = " Comment";
					$('a.link-comments[data-url="' + result.response[i].link + '"]').html(count + countText);
				}
			}
		});
	}
	// =========================================================
	// LOAD NEWSROOM COMMENT COUNT'S
	// =========================================================
	if(newsroom_url_array.length > 0) 
	{		
		$.ajax({
			type: 'POST',
			url: default_settings.ajaxurl + '?action=disqusCounts',
			data: { api_key: disqusPublicKey, forum : disqus_shortname, thread : newsroom_url_array },
			dataType: 'json',
			success: function (result) {
				for (var i in result.response) {
					var countText = " comments";
					var count = result.response[i].posts;
					if (count == 1) countText = " Comment";
					$('li.disqus-comment[data-url="' + result.response[i].link + '"]').html(count + countText);
				}
			}
		});	
	}
}

function showUserInformation()
{
	$.ajax({
		type: 'POST',
		url: default_settings.ajaxurl + '?action=showUserInformation',
		data: { ip: default_settings.ip },
		dataType: 'json',
		success: function (result) {
			if(result.show) 
			{
				var top = $(document).scrollTop() + 100;
				$('.lightbox-mask').removeClass('hide');
				$('#thank-you-lightbox').removeClass('hide');				
				$('#thank-you-lightbox').css({ top: top + 'px'});
			}
		}
	});
}

function disqus_config() {
  if(typeof this.callbacks != 'undefined'){
    this.callbacks.onNewComment = [function(comment) {
      showUserInformation();      
    }];
  }
}