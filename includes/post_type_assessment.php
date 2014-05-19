<?php

class Assessment{
	//                __  _                 
	//   ____  ____  / /_(_)___  ____  _____
	//  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
	// / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
	// \____/ .___/\__/_/\____/_/ /_/____/  
	//     /_/                              
	public $items = null;
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{		
		// =========================================================
		// HOOKS
		// =========================================================
		add_action('init', array($this, 'createPostTypeAssessment'));		
		add_action('save_post', array($this, 'saveAssessment'), 0);	
		add_action('add_meta_boxes', array($this, 'metaBoxAssessment'));
		add_filter('manage_edit-assessment_columns', array($this, 'columnThumb'));	
		add_action('manage_posts_custom_column', array($this, 'columnthumbShow'), 10, 2);
		add_image_size('assessment-image', 460, 282, true);
		add_image_size('assessment-thumb-image', 100, 100, true);
		$this->loadItems();				
	}

	/**
	 * Create GCEvents post type and his taxonomies
	 */
	public function createPostTypeAssessment()
	{

		$post_labels = array(
			'name'               => __('Assessments'),
			'singular_name'      => __('Assesment'),
			'add_new'            => __('Add new'),
			'add_new_item'       => __('Add new assessment'),
			'edit_item'          => __('Edit assessment'),
			'new_item'           => __('New assessment'),
			'all_items'          => __('Assessments'),
			'view_item'          => __('View assessment'),
			'search_items'       => __('Search assessment'),
			'not_found'          => __('Assesment not found'),
			'not_found_in_trash' => __('Assesment not found in trash'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Assessments'));

		$post_args = array(
			'labels'             => $post_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'assessment' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt'));

		register_post_type('assessment', $post_args);
	}

	/**
	 * Register new column
	 * @param  array $columns 
	 * @return array
	 */
	public function columnThumb($columns)
	{
		return array_merge($columns, array(
			'thumb'        => __('Image'), 
			'quote_first'  => __('Quote first'),
			'quote_second' => __('Quote second'),
			'pdf_url'      => __('PDF URL'),
			'video_url'    => __('YouTube video')));
	}

	/**
	 * Display new column
	 * @param  string  $column  
	 * @param  integer $post_id           
	 */
	public function columnThumbShow($column, $post_id)
	{		
		switch ($column) 
		{
			case 'quote_first':				
				$quote_first = isset($meat['quote_first']) ? $meat['quote_first'] : '';
				echo $quote_first;
				break;
			case 'quote_second':				
				$quote_second = isset($meat['quote_second']) ? $meat['quote_second'] : '';
				echo $quote_second;
				break;
			case 'pdf_url':				
				$pdf_url = isset($meat['pdf_url']) ? $meat['pdf_url'] : '';
				printf('<a href="%s">%s</a>', $pdf_url, $pdf_url);
				break;
		}			
	}

	/**
	 * Add GCEvents meata box
	 */
	public function metaBoxAssessment($post_type)
	{
		$post_types = array('assessment');
		if(in_array($post_type, $post_types))
		{
			add_meta_box('metaBoxAssessment', __('Assessment settings'), array($this, 'metaBoxAssessmentRender'), $post_type, 'side', 'high');	
			add_meta_box('metaBoxAssessmentRec', __('Recommendations'), array($this, 'metaBoxRecommendationsRender'), $post_type, 'normal', 'high');
		}
		
	}

	/**
	 * render Assessment Meta box
	 */
	public function metaBoxAssessmentRender($post)
	{
		$meta = $this->getMeta($post->ID);
		wp_nonce_field( 'assessment_box', 'assessment_box_nonce' );
		?>	
		<div class="gcAssessment">
			<p>
				<label for="mainslider_tweet_text"><?php _e('Tweet text'); ?>:</label>
				<input type="text" name="meta[tweet_text]" id="mainslider_tweet_text" value="<?php echo $meta['tweet_text']; ?>" class="w100">
			</p>	

			<p>
				<label for="mainslider_tfl"><?php _e('Tweet At Foundation Leadership text'); ?>:</label>
				<input type="text" name="meta[tfl]" id="mainslider_tfl" value="<?php echo $meta['tfl']; ?>" class="w100">
			</p>	

			<p>
				<label for="mainslider_google_title"><?php _e('Google share title'); ?>:</label>
				<input type="text" name="meta[google_title]" id="mainslider_google_title" value="<?php echo $meta['google_title']; ?>" class="w100">
			</p>	
			<p>
				<label for="mainslider_google_description"><?php _e('Google share description'); ?>:</label>
				<input type="text" name="meta[google_description]" id="mainslider_google_description" value="<?php echo $meta['google_description']; ?>" class="w100">
			</p>

			<p>
				<label for="mainslider_linkedin_title"><?php _e('Linkedin share title'); ?>:</label>
				<input type="text" name="meta[linkedin_title]" id="mainslider_linkedin_title" value="<?php echo $meta['linkedin_title']; ?>" class="w100">
			</p>	
			<p>
				<label for="mainslider_linkedin_description"><?php _e('Linkedin share description'); ?>:</label>
				<input type="text" name="meta[linkedin_description]" id="mainslider_linkedin_description" value="<?php echo $meta['linkedin_description']; ?>" class="w100">
			</p>				

			<p>
				<label for="mainslider_facebook_title"><?php _e('Facebook share title'); ?>:</label>
				<input type="text" name="meta[facebook_title]" id="mainslider_facebook_title" value="<?php echo $meta['facebook_title']; ?>" class="w100">
			</p>	
			<p>
				<label for="mainslider_facebook_description"><?php _e('Facebook share description'); ?>:</label>
				<input type="text" name="meta[facebook_description]" id="mainslider_facebook_description" value="<?php echo $meta['facebook_description']; ?>" class="w100">
			</p>

			<p>
				<label for="mainslider_video_url"><?php _e('YouTube URL'); ?>:</label>
				<input type="text" name="meta[video_url]" id="mainslider_video_url" value="<?php echo $meta['video_url']; ?>" class="w100">
			</p>			
			<p>
				<label for="mainslider_pdf_url"><?php _e('PDF URL'); ?>:</label>
				<input type="text" name="meta[pdf_url]" id="mainslider_pdf_url" value="<?php echo $meta['pdf_url']; ?>" class="w100">
			</p>			
			<p>
				<label for="mainslider_quote_first"><?php _e('Quote first'); ?>:</label>
				<textarea name="meta[quote_first]" id="mainslider_quote_first" cols="30" rows="10" class="w100"><?php echo $meta['quote_first']; ?></textarea>				
			</p>	
			<p>
				<label for="mainslider_qf_source"><?php _e('Quote first - Source'); ?>:</label>
				<input type="text" name="meta[qf_source]" id="mainslider_qf_source" value="<?php echo $meta['qf_source']; ?>" class="w100">
			</p>						
			<p>
				<label for="mainslider_qf_source_url"><?php _e('Quote first - Source URL'); ?>:</label>
				<input type="text" name="meta[qf_source_url]" id="mainslider_qf_source_url" value="<?php echo $meta['qf_source_url']; ?>" class="w100">
			</p>						
			<p>
				<label for="mainslider_quote_second"><?php _e('Quote second'); ?>:</label>
				<textarea name="meta[quote_second]" id="mainslider_quote_second" cols="30" rows="10" class="w100"><?php echo $meta['quote_second']; ?></textarea>				
			</p>	
			<p>
				<label for="mainslider_qs_source"><?php _e('Quote second - Source'); ?>:</label>
				<input type="text" name="meta[qs_source]" id="mainslider_qs_source" value="<?php echo $meta['qs_source']; ?>" class="w100">
			</p>						
			<p>
				<label for="mainslider_qs_source_url"><?php _e('Quote second - Source URL'); ?>:</label>
				<input type="text" name="meta[qs_source_url]" id="mainslider_qs_source_url" value="<?php echo $meta['qs_source_url']; ?>" class="w100">
			</p>	
			<p>
				<label for="mainslider_form_subject"><?php _e('Form subject'); ?>:</label>
				<input type="text" name="meta[form_subject]" id="mainslider_form_subject" value="<?php echo $meta['form_subject']; ?>" class="w100">
			</p>						
			<p>
				<label for="mainslider_form_message"><?php _e('Form message'); ?>:</label>
				<textarea name="meta[form_message]" id="mainslider_form_message" cols="30" rows="10" class="w100"><?php echo $meta['form_message']; ?></textarea>				
			</p>
        </div>
		<?php
	}

	/**
	 * Render Recommendations Meta Box
	 */
	public function metaBoxRecommendationsRender($post)
	{
		$recommendations  = get_post_meta($post->ID, 'recommendations', true);		
		$twitter_accounts = get_post_meta($post->ID, 'twitter_accounts', true);	
		$email_accounts   = get_post_meta($post->ID, 'email_accounts', true);	
		$email_picture    = get_post_meta($post->ID, 'email_picture', true);	
		?>
		<table class="gctable recommendation-table" data-count="<?php echo count($recommendations); ?>">
			<thead>
				<tr>
					<th><?php _e('#'); ?></th>
					<th><?php _e('Recommendation title'); ?></th>
					<th><?php _e('Recommendation content'); ?></th>
					<th><?php _e('Featured'); ?></th>
					<th><?php _e('AGREE'); ?></th>
					<th><?php _e('DISAGREE'); ?></th>
					<th><?php _e('ALL'); ?></th>	
					<th><?php _e('Delete'); ?></th>
				</tr>
			</thead>
			<tbody>				
				<?php 					
					if($recommendations)
					{
						foreach ($recommendations as $key => $recommendation) 
						{	
                            $order         = sprintf('recommendations[%s][%s]', $key, 'order');
							$title_name    = sprintf('recommendations[%s][%s]', $key, 'title');
							$content_name  = sprintf('recommendations[%s][%s]', $key, 'content');
							$featured_name = sprintf('recommendations[%s][%s]', $key, 'featured');
                            $agree         = sprintf('recommendations[%s][%s]', $key, 'agree');
                            $disagree      = sprintf('recommendations[%s][%s]', $key, 'disagree');
							$last_key      = $key;
							echo '<tr>';
							printf('<td><input type="text" name="%s" value="%s" class="w100" style="width:20px;"></td>', $order, $recommendation['order']);
							printf('<td><input type="text" name="%s" value="%s" class="w100"></td>', $title_name, $recommendation['title']);
							printf('<td><textarea name="%s" class="w100">%s</textarea></td>', $content_name, $recommendation['content']);
							printf('<td><input type="hidden" name="%s" value="0"><input type="checkbox" name="%s" value="1" %s></td>', $featured_name, $featured_name, $this->checked($recommendation['featured']));
							printf('<td><input type="hidden" name="%s" value="%s">%s</td>', $agree, (int)$recommendation['agree'], (int)$recommendation['agree']);
							printf('<td><input type="hidden" name="%s" value="%s">%s</td>', $disagree, (int)$recommendation['disagree'], (int)$recommendation['disagree']);
							printf('<td>%s</td>', (int)$recommendation['agree'] + (int)$recommendation['disagree']);
							printf('<td><button type="button" class="button button-red remove-recommendation">%s</button></td>', __('Remove item'));
							echo '</tr>';
						}						
					}
				?>				
			</tbody>
		</table>
		<button type="button" class="button add-recommendation"><?php _e('Add recommendation'); ?></button>

		<h1>Twitter accounts</h1>
		<table class="gctable twitter-accounts-table" data-count="<?php echo count($twitter_accounts); ?>">
			<thead>
				<tr>
					<th>Account</th>
					<th>First name</th>
					<th>Last name</th>
					<th>Picture URL</th>                    
				</tr>
			</thead>
			<tbody>
				<?php
					if($twitter_accounts)
					{
						foreach ($twitter_accounts as $key => $t_account) 
						{
							echo '<tr>';
							printf('<td><input class="w100" type="text" name="twitter_accounts[%s][account]" value="%s"></td>', $key, $t_account['account']);
							printf('<td><input class="w100" type="text" name="twitter_accounts[%s][first_name]" value="%s"></td>', $key, $t_account['first_name']);
							printf('<td><input class="w100" type="text" name="twitter_accounts[%s][last_name]" value="%s"></td>', $key, $t_account['last_name']);
							printf('<td><input class="w100" type="text" name="twitter_accounts[%s][picture_name]" value="%s"></td>', $key, $t_account['picture_name']);                            
							echo '</tr>';
						}
					}
				?>				
			</tbody>
		</table>	
		<button type="button" class="button add-twitter-account"><?php _e('Add twitter account'); ?></button>		

		<h1>Email accounts</h1>			
		<table class="gctable email-accounts-table" data-count="<?php echo count($email_accounts); ?>">
			<thead>
				<tr>
					<th>Account</th>
					<th>First name</th>
					<th>Last name</th>					                    
				</tr>
			</thead>
			<tbody>
				<?php
					if($email_accounts)
					{
						foreach ($email_accounts as $key => $e_account) 
						{
							echo '<tr>';
							printf('<td><input class="w100" type="text" name="email_accounts[%s][account]" value="%s"></td>', $key, $e_account['account']);
							printf('<td><input class="w100" type="text" name="email_accounts[%s][first_name]" value="%s"></td>', $key, $e_account['first_name']);
							printf('<td><input class="w100" type="text" name="email_accounts[%s][last_name]" value="%s"></td>', $key, $e_account['last_name']);							
							echo '</tr>';
						}
					}
				?>				
			</tbody>
		</table>	
		<p>
			<label for="email_picture"><?php _e('Picture section emails'); ?></label>
			<input type="text" class="w100" name="email_picture" value="<?php echo $email_picture; ?>">
		</p>
		<button type="button" class="button add-email-account"><?php _e('Add email account'); ?></button>
		<?php
	}

	/**
	 * Helper function for checkbox
	 * @param  boolean $yes 
	 * @return string
	 */
	public function checked($yes = true)
	{
		return (intval($yes)) ? 'checked' : '';
	}

	/**
	 * Get meta array
	 * @param  integer $id
	 * @return array
	 */
	public function getMeta($id)
	{
		return get_post_meta($id, 'meta', true);
	}
	
	/**
	 * Save post
	 * @param  integer $post_id 
	 * @return integer
	 */
	public function saveAssessment($post_id)
	{

		// =========================================================
		// Check nonce
		// =========================================================
		if(!isset( $_POST['assessment_box_nonce'])) return $post_id;
		if(!wp_verify_nonce($_POST['assessment_box_nonce'], 'assessment_box')) return $post_id;
		if(defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

		// =========================================================
		// Check the user's permissions.
		// =========================================================
		if ( 'page' == $_POST['post_type'] ) 
		{			
			if (!current_user_can( 'edit_page', $post_id)) return $post_id;
		} 
		else 
		{
			if(!current_user_can( 'edit_post', $post_id)) return $post_id;
		}

		// =========================================================
		// Save
		// =========================================================	
		if(isset($_POST['email_picture']))
		{
			update_post_meta($post_id, 'email_picture', $_POST['email_picture']);
		}				
		if(isset($_POST['recommendations']))
		{
			if(is_array($_POST['recommendations']))
			{
				$new_recommendations = $this->clearEmptyItems($_POST['recommendations']);
				$new_recommendations = $this->sort($new_recommendations);
				update_post_meta($post_id, 'recommendations', $new_recommendations);
			}			
		}		
		if(isset($_POST['twitter_accounts']))
		{
			if(is_array($_POST['twitter_accounts']))
			{
				$new_twitter_accounts = $this->clearEmptyItems($_POST['twitter_accounts'], array('account'));				
				update_post_meta($post_id, 'twitter_accounts', $new_twitter_accounts);
			}			
		}		
		if(isset($_POST['email_accounts']))
		{
			if(is_array($_POST['email_accounts']))
			{
				$new_email_accounts = $this->clearEmptyItems($_POST['email_accounts'], array('account'));				
				update_post_meta($post_id, 'email_accounts', $new_email_accounts);
			}			
		}	
		if(isset($_POST['meta']))
		{
			update_post_meta($post_id, 'meta', $_POST['meta']);
		}

		return $post_id;
	}

	/**
	 * Clear array from empty items
	 * @param  array $arr 
	 * @return array      
	 */
	public function clearEmptyItems($arr, $fields = array('title', 'content'))
	{
		$new_arr = array();
		foreach ($arr as $el) 
		{
			$empty = false;
			foreach ($fields as $field) 
			{
				if(isset($el[$field]))
				{
					if($el[$field] == '') $empty = true; 	
				}
				else $empty = true;
				
			}
			if(!$empty) $new_arr[] = $el;
		}
		return $new_arr;
	}

	/**
	 * Sort recommendations array
	 * @param  array $arr
	 * @return array
	 */
	public function sort($arr)
	{
        $new_arr = array();
        foreach ($arr as $key => $row)
        {
            $new_arr[$key] = $row['order'];
        }
        array_multisort($new_arr, SORT_ASC, $arr);		
		return $arr;
	}

	/**
	 * Get post type imtes
	 * @param  integer $count
	 * @return array        
	 */
	public function getItems($count = -1)
	{
		$all = array(
			'posts_per_page'   => $count,
			'offset'           => 0,			
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => 'assessment',
			'post_status'      => 'publish');
		$arr = get_posts($all);
		foreach ($arr as $key => &$value) 
		{
			$images = null;
			if(has_post_thumbnail($value->ID))
			{
				$post_thumbnail_id      = get_post_thumbnail_id($value->ID);
				$assessment_image       = wp_get_attachment_image_src($post_thumbnail_id ,'assessment-image', false);
				$assessment_thumb_image = wp_get_attachment_image_src($post_thumbnail_id ,'assessment-thumb-image', false);
				$images['full']         = $assessment_image[0];
				$images['small']        = $assessment_thumb_image[0];
			}
			$value->images = $images;
			$value->meta   = $this->getMeta($value->ID);
		}
		return $arr;
	}

	/**
	 * Load items
	 * @param  integer $count 
	 */
	public function loadItems($count = -1)	
	{
		$this->items = $this->getItems($count);
	}	
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['assessment'] = new Assessment();