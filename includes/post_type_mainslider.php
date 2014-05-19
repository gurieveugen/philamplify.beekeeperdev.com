<?php

class MainSlider{
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
		add_action('init', array($this, 'createPostTypeMainSlider'));		
		add_action('save_post', array($this, 'saveMainSlider'), 0);	
		add_action('add_meta_boxes', array($this, 'metaBoxMainSlider'));
		add_filter('manage_edit-mainslide_columns', array($this, 'columnThumb'));	
		add_action('manage_posts_custom_column', array($this, 'columnthumbShow'), 10, 2);		
		add_image_size('mainslide-image', 460, 290, true);
		add_image_size('slide-thumb-image', 100, 100, true);
		$this->loadItems();				
	}

	/**
	 * Create GCEvents post type and his taxonomies
	 */
	public function createPostTypeMainSlider()
	{

		$post_labels = array(
			'name'               => __('Main slides'),
			'singular_name'      => __('Main slide'),
			'add_new'            => __('Add new'),
			'add_new_item'       => __('Add new slide'),
			'edit_item'          => __('Edit slide'),
			'new_item'           => __('New slide'),
			'all_items'          => __('Main slides'),
			'view_item'          => __('View slide'),
			'search_items'       => __('Search slide'),
			'not_found'          => __('Main slide not found'),
			'not_found_in_trash' => __('Main slide not found in trash'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Main slides'));

		$post_args = array(
			'labels'             => $post_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mainslide' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail'));

		register_post_type('mainslide', $post_args);
	}

	/**
	 * Register new column
	 * @param  array $columns 
	 * @return array
	 */
	public function columnThumb($columns)
	{
		return array_merge($columns, array(
			'thumb'           => __('Image'),			
			'quote'           => __('Quote'),
			'quote_source'    => __('Quote source'),
			'video_url'       => __('YouTube url'),
			'destination_url' => __('destination_url')));
	}

	/**
	 * Display new column
	 * @param  string  $column  
	 * @param  integer $post_id           
	 */
	public function columnThumbShow($column, $post_id)
	{		
		$meat  = $this->getMeta($post_id);
		switch ($column) 
		{			
			case 'quote':				
				$quote = isset($meat['quote']) ? $meat['quote'] : '';
				echo $quote;
				break;
			case 'quote_source':				
				$quote_source = isset($meat['quote_source']) ? $meat['quote_source'] : '';
				echo $quote_source;
				break;
			case 'video_url':				
				$video_url = isset($meat['video_url']) ? $meat['video_url'] : '';
				printf('<a href="%s">%s</a>', $video_url, $video_url);
				break;
			case 'destination_url':				
				$destination_url = isset($meat['destination_url']) ? $meat['destination_url'] : '';
				printf('<a href="%s">%s</a>', $destination_url, $destination_url);
				break;
		}			
	}

	/**
	 * Add GCEvents meata box
	 */
	public function metaBoxMainSlider($post_type)
	{
		$post_types = array('mainslide');
		if(in_array($post_type, $post_types))
		{
			add_meta_box('metaBoxMainSlider', __('MainSlider settings'), array($this, 'metaBoxMainSliderRender'), $post_type, 'side', 'high');	
		}
		
	}

	/**
	 * render MainSlider Meta box
	 */
	public function metaBoxMainSliderRender($post)
	{
		$meta = $this->getMeta($post->ID);
		wp_nonce_field( 'mainslider_box', 'mainslider_box_nonce' );
		?>	
		<div class="gcslider">
			<p>
				<label for="mainslider_video_url"><?php _e('YouTuve URL'); ?>:</label>
				<input type="text" name="meta[video_url]" id="mainslider_video_url" value="<?php echo $meta['video_url']; ?>" class="w100">
			</p>			
			<p>
				<label for="mainslider_destination_url"><?php _e('Destination URL'); ?>:</label>
				<input type="text" name="meta[destination_url]" id="mainslider_destination_url" value="<?php echo $meta['destination_url']; ?>" class="w100">
			</p>			
			<p>
				<label for="mainslider_quote"><?php _e('Quote'); ?>:</label>
				<textarea name="meta[quote]" id="mainslider_quote" cols="30" rows="10" class="w100" maxlength="230"><?php echo $meta['quote']; ?></textarea>				
			</p>			
			<p>
				<label for="mainslider_quote_source"><?php _e('Quote source'); ?>:</label>
				<input type="text" name="meta[quote_source]" id="mainslider_quote_source" value="<?php echo $meta['quote_source']; ?>" class="w100">
			</p>			
		</div>	
		<?php
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
	public function saveMainSlider($post_id)
	{
		// =========================================================
		// Check nonce
		// =========================================================
		if(!isset( $_POST['mainslider_box_nonce'])) return $post_id;
		if(!wp_verify_nonce($_POST['mainslider_box_nonce'], 'mainslider_box')) return $post_id;
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
		if(isset($_POST['meta']))
		{
			update_post_meta($post_id, 'meta', $_POST['meta']);
		}

		return $post_id;
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
			'post_type'        => 'mainslide',
			'post_status'      => 'publish');
		$arr = get_posts($all);
		foreach ($arr as $key => &$value) 
		{
			$images = null;
			if(has_post_thumbnail($value->ID))
			{
				$post_thumbnail_id = get_post_thumbnail_id($value->ID);
				$slide_image       = wp_get_attachment_image_src($post_thumbnail_id ,'slide-image', false);
				$slide_thumb_image = wp_get_attachment_image_src($post_thumbnail_id ,'slide-thumb-image', false);
				$images['full']    = $slide_image[0];
				$images['small']   = $slide_thumb_image[0];
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

	/**
	 * Get slides
	 * @return string
	 */
	public function getSlides()
	{		
		if($this->items)
		{
			foreach ($this->items as &$item) 
			{
				if(has_post_thumbnail($item->ID))
				{
					$post_thumbnail_id = get_post_thumbnail_id($item->ID);
					$img = wp_get_attachment_image_src($post_thumbnail_id ,'mainslide-image', false);
					$img = $img[0];
					$output.= '<div class="mainslide cf">';
					$output.= '<div class="text pc-hide">';
					$output.= '<h1>'.$item->post_title.'</h1>';
					$output.= '<p>'.$item->post_content.'</p>';
					$output.= '</div>';

					$output.= '<div class="video-box">';					
					$output.= '<img src="'.$img.'" alt="">';
					$output.= '<a href="'.$item->meta['video_url'].'" class="ico-video fancybox-media">play</a>';
					$output.= '</div>';

					$output.= '<div class="text">';
					$output.= '<div class="pc-visible">';
					$output.= '<h1>'.$item->post_title.'</h1>';
					$output.= '<p>'.$item->post_content.'</p>';
					$output.= '</div>';
					$output.= '<div class="quotes-holder">';
					$output.= '<blockquote class="box-quote q1 cf">';
					$output.= '<q>“'.$item->meta['quote'].'”</q>';
					$output.= '<cite>-- '.$item->meta['quote_source'].'</cite>';
					$output.= '<a href="'.$item->meta['destination_url'].'" class="link-arrow">Share Your Stories</a>';
					$output.= '</blockquote>';
					$output.= '</div>';
					$output.= '</div>';		
					$output.= '</div>';		
				}
			}
		}
		
		return $output; 
	}

	/**
	 * Get switcher control
	 * @return string
	 */
	public function getSwitcher()
	{		
		if(!$this->items) return '';
		if(count($this->items) == 1) return '';

		$output = '<ul class="switcher">';
		for ($i=1; $i <= count($this->items); $i++) 
		{ 
			if($i == 1) $output .= '<li class="active"><a href="#"></a></li>';
			else $output .= '<li><a href="#"></a></li>';
		}
		$output.= '</ul>';

		return $output;				
	}

	/**
	 * Set active mainslider item
	 * @param  boolean $yes 
	 * @return string
	 */
	private function active($yes = false)
	{
		if($yes) return 'active';
		return '';
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['mainslider'] = new MainSlider();