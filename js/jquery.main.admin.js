(function($){
	$(function() {
		// =========================================================
		// DELETE ALL SUBSCRIBERS
		// =========================================================
		$('#reset-subscribers').click(function(e){	
			var result = confirm("Are you sure want to delete all subscribers?");
			if (result == true) 
			{
				jQuery.ajax({
					type: "POST",
					url: default_settings.ajaxurl + '?action=resetSubscribers',
					dataType: 'json',
					success: function(data)
					{
						if(data.empty == true)
						{
							location.reload(true);
						}
					}
				});    
			}
			
			e.preventDefault();
		});

		// =========================================================
		// ADD RECOMMENDATION
		// =========================================================
		$('.add-recommendation').click(function(e){
			var count = $('.recommendation-table').data('count') + 1;
			$('.recommendation-table tbody').append(
				'<tr>' +
				'<td><input type="text" style="width:20px;" class="w100" value="" name="recommendations[' + count + '][order]"></td>' +
				'<td><input type="text" name="recommendations[' + count + '][title]" value="" class="w100">' + '</td>' +
				'<td><textarea name="recommendations[' + count + '][content]" class="w100"></textarea>' + '</td>' +
				'<td>0</td>' +
				'<td>0</td>' +
				'<td>0</td>' +
				'</tr>');

			$('.recommendation-table').data('count', count);
			e.preventDefault();
		});
		// =========================================================
		// ADD TWITTER ACCOUNT
		// =========================================================
		$('.add-twitter-account').click(function(e){
			var count = $('.twitter-accounts-table').data('count') + 1;
			$('.twitter-accounts-table tbody').append(
				'<tr>' +	
				'<td><input class="w100" type="text" name="twitter_accounts[' + count + '][account]" value=""></td>' +
				'<td><input class="w100" type="text" name="twitter_accounts[' + count + '][first_name]" value=""></td>' +
				'<td><input class="w100" type="text" name="twitter_accounts[' + count + '][last_name]" value=""></td>' +
				'<td><input class="w100" type="text" name="twitter_accounts[' + count + '][picture_name]" value=""></td>' +	
                '<td><input class="w100" type="text" name="twitter_accounts[' + count + '][share_text]" value=""></td>' +						
				'</tr>');

			$('.twitter-accounts-table').data('count', count);
			e.preventDefault();
		});
		// =========================================================
		// ADD EMAIL ACCOUNT
		// =========================================================
		$('.add-email-account').click(function(e){
			var count = $('.email-accounts-table').data('count') + 1;
			$('.email-accounts-table tbody').append(
				'<tr>' +	
				'<td><input class="w100" type="text" name="email_accounts[' + count + '][account]" value=""></td>' +
				'<td><input class="w100" type="text" name="email_accounts[' + count + '][first_name]" value=""></td>' +
				'<td><input class="w100" type="text" name="email_accounts[' + count + '][last_name]" value=""></td>' +						
				'</tr>');

			$('.email-accounts-table').data('count', count);
			e.preventDefault();
		});
		// =========================================================
		// ADD INDUSTRY
		// =========================================================
		$('.add-industry').click(function(e){
			var count = $('.industry-table').data('count') + 1;
			$('.industry-table tbody').append(
				'<tr>' +	
				'<td><input type="text" name="assessments_options[industry_keys][' + count + ']" value="' + (parseInt(count)-1) + '" class="w100"></td>' +
				'<td><input type="text" name="assessments_options[industry][' + count + ']" value="" class="w100"></td>' +
				'<td></td>' +									
				'</tr>');

			$('.industry-table').data('count', count);
			e.preventDefault();
		});
		// =========================================================
		// REMOVE INDUSTRY
		// =========================================================
		$('.remove-industry').click(function(e){
			var result = confirm("Are you sure want to delete this item?");
			if (result == true) 
			{
				$(this).parent().parent().remove();
			}
			e.preventDefault();
		});
		
		// =========================================================
		// Remove recommendation
		// =========================================================
		$('.remove-recommendation').click(function(e){
			var result = confirm("Are you sure want to delete this item?");
			if (result == true) 
			{
				$(this).parent().parent().remove();
			}
			e.preventDefault();
		});
		
	});	
})(jQuery);