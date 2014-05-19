<?php
/**
 * Register new widget
 */
add_action('widgets_init', create_function('', 'register_widget( "SocialShare" );'));


class SocialShare extends WP_Widget {
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct() 
	{
		$widget_ops     = array('classname' => 'socialshare', 'description' => 'SocialShare widget' );		
		parent::__construct('socialshare', 'SocialShare widget', $widget_ops);
	}

	function widget($args, $instance) 
	{
		global $post;
		extract($args);
		$post_thumbnail_id              = get_post_thumbnail_id($post->ID);
		$url                            = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$title                          = strip_tags($instance['title']);	
		$meta                           = get_post_meta($post->ID, 'meta', true);	
		$tfl                            = isset($meta['tfl']) ? urlencode($meta['tfl']) : '';	
		$linkedin_title                 = isset($meta['linkedin_title']) ? urlencode($meta['linkedin_title']) : '';
		$linkedin_description           = isset($meta['linkedin_description']) ? urlencode($meta['linkedin_description']) : '';
		$facebook_title                 = isset($meta['facebook_title']) ? urlencode($meta['facebook_title']) : '';
		$facebook_description           = isset($meta['facebook_description']) ? urlencode($meta['facebook_description']) : '';
		$picture                        = has_post_thumbnail($post->ID) ? wp_get_attachment_image_src($post_thumbnail_id,'assessment-image', false) : TDU.'/images/logo.png';
		if(is_array($picture)) $picture = $picture[0];
		$share_text                     = (isset($meta['tweet_text']) && $meta['tweet_text'] != '') ? $meta['tweet_text'] : $instance['share_text'];
		$twitter                        = (isset($instance['twitter']) && $instance['twitter'] != '') ? 'https://twitter.com/intent/tweet?text='.urlencode($share_text) : '';
		$facebook                       = ($instance['facebook'] == true) ? sprintf('https://www.facebook.com/dialog/feed?app_id=1423814364535515&redirect_uri=%s&link=%s&caption=%s&description=%s&picture=%s', $url, $url, $facebook_title, $facebook_description, $picture) : '';		
		$google_plus                    = ($instance['google_plus'] == true) ? 'https://plus.google.com/share?url='.$url : '';
		$linkedin                       = ($instance['linkedin'] == true) ? 'http://www.linkedin.com/shareArticle?mini=true&url='.$url.'&title='.$linkedin_title.'&summary='.$linkedin_description : '';	
		$twitter_btn                    = ($twitter != '') ? sprintf('<li><a href="%s"><img alt="" src="'.TDU.'/images/ico-twitter-1.png"></a></li>', $twitter) : '';
		$facebook_btn                   = ($facebook != '') ? sprintf('<li><a href="%s"><img alt="" src="'.TDU.'/images/ico-facebook-1.png"></a></li>', $facebook) : '';
		$google_plus_btn                = ($google_plus != '') ? sprintf('<li><a href="%s"><img alt="" src="'.TDU.'/images/ico-google-1.png"></a></li>', $google_plus) : '';
		$linkedin_btn                   = ($linkedin != '') ? sprintf('<li><a href="%s"><img alt="" src="'.TDU.'/images/ico-in-1.png"></a></li>', $linkedin) : '';		
		$twitter_accounts               = get_post_meta($post->ID, 'twitter_accounts', true);	
		$email_accounts                 = get_post_meta($post->ID, 'email_accounts', true);	
		$email_picture                  = get_post_meta($post->ID, 'email_picture', true);	

		echo $before_widget;		
		// =========================================================
		// Print featured widget
		// =========================================================				
		?>
		<div class="w-block">			
			<?php if($title != '') echo $before_title.$title.$after_title; ?>
			<ul class="social-list social-share-buttons">
				<?php
				echo $twitter_btn;
				echo $facebook_btn;
				echo $google_plus_btn;
				echo $linkedin_btn;
				?>				
			</ul>
		</div>
		<?php
		if($twitter_accounts)
		{
			?>
			<div class="w-block">
				<h3>Tweet At Foundation Leadership</h3>
				<ul class="social-feed">
					<?php 
					foreach ($twitter_accounts as $t_account) 
					{
						?>
						<li>
							<div class="cell">
								<a href="#" class="just-tweet" data-account="<?php echo $t_account['account']; ?>" data-text="<?php echo $tfl; ?>"><img alt="" src="<?php echo $t_account['picture_name']; ?>"></a>
							</div>
							<div class="cell">
								<strong class="name"><a href="#" class="just-tweet" data-account="<?php echo $t_account['account']; ?>" data-text="<?php echo $tfl; ?>">@<?php echo $t_account['account']; ?></a></strong>
								<p><?php echo $t_account['first_name']; ?> <?php echo $t_account['last_name']; ?></p>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}
		?>
		<?php
		if($email_accounts)
		{			
			?>
			<div class="w-block">
				<h3>Email Foundation Leadership</h3>
				<ul class="social-feed">
					<li>
							<div class="cell">
								<a class="show-email-lightbox" href="#"><img alt="" src="<?php echo $email_picture; ?>"></a>
							</div>
							<div class="cell">	
								<?php 
								foreach ($email_accounts as $e_account) 
								{
									$mail = $e_account['account'];
									?>
									<p><a class="show-email-lightbox" href="#"><?php echo $e_account['first_name']; ?> <?php echo $e_account['last_name']; ?></a></p>
									<br>
									<?php
								}
								?>
							</div>
					</li>
				</ul>
			</div>
			<?php
		}
		?>		
		<?php
		echo $after_widget;
	}

	function form($instance) 
	{		
		$title            = $instance['title'];     		
		$twitter          = $instance['twitter'];
        $share_text       = $instance['share_text'];
		$facebook         = $instance['facebook'];
		$google_plus      = $instance['google_plus'];
		$linkedin         = $instance['linkedin'];		

		?>		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter account'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" />
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('share_text'); ?>"><?php _e('Share Text'); ?>: 
				<textarea class="widefat" id="<?php echo $this->get_field_id('share_text'); ?>" name="<?php echo $this->get_field_name('share_text'); ?>"><?php echo esc_attr($share_text); ?></textarea>
			</label>
		</p>			
		<p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook show'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="checkbox" <?php echo $this->checked($facebook); ?> />
			</label>
		</p>				
		<p>
			<label for="<?php echo $this->get_field_id('google_plus'); ?>"><?php _e('Google plus show'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('google_plus'); ?>" name="<?php echo $this->get_field_name('google_plus'); ?>" type="checkbox" <?php echo $this->checked($google_plus); ?> />
			</label>
		</p>				
		<p>
			<label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin show'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="checkbox" <?php echo $this->checked($linkedin); ?> />
			</label>
		</p>
		<?php
	}

	/**
	 * Update all edits
	 * @param  array $new_instance 
	 * @param  array $old_instance 
	 * @return array               
	 */
	function update($new_instance, $old_instance) 
	{		
		$instance                     = $old_instance;		
		$instance['title']            = strip_tags($new_instance['title']);				
		$instance['twitter']          = strip_tags($new_instance['twitter']);
        $instance['share_text']          = strip_tags($new_instance['share_text']);
		$instance['facebook']         = ($new_instance['facebook'] == 'on') ? true : false;
		$instance['google_plus']      = ($new_instance['google_plus'] == 'on') ? true : false;
		$instance['linkedin']         = ($new_instance['linkedin'] == 'on') ? true : false;
		$instance['twitter_accounts'] = $this->clearEmptyAccounts($new_instance['twitter_accounts']);
		$instance['email_accounts']   = $this->clearEmptyAccounts($new_instance['email_accounts']);

		return $instance;
	}

	/**
	 * Clear empty array accounts
	 * @param  array $arr
	 * @return array     
	 */
	function clearEmptyAccounts($arr)
	{
		$new_arr = array();
		if($arr)
		{
			foreach ($arr as &$el) 
			{
				if($el['account'] != '') $new_arr[] = $el;
			}
		}
		return $new_arr;
	}

	/**
	 * Helper function for checkbox control
	 * @param  boolean $yes 
	 * @return string       
	 */
	function checked($yes = true)
	{
		return ($yes) ? 'checked' : '';
	}
}