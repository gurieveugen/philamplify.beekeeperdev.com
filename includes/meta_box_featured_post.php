<?php

class MetaBoxFeaturedPost{
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{
		add_action('save_post', array($this, 'savePost'), 0);	
		add_action('add_meta_boxes', array($this, 'metaBoxFeaturedPost'));
		add_image_size('featured-image', 241, 231, true);
	}

	/**
	 * Add meata box
	 */
	public function metaBoxFeaturedPost($post_type)
	{
		$post_types = array('post');
		if(in_array($post_type, $post_types))
		{
			add_meta_box('metaBoxFeaturedPost', __('Additional options'), array($this, 'metaBoxFeaturedPostRender'), $post_type, 'side', 'high');	
		}
		
	}

	/**
	 * render Additional options Meta box
	 */
	public function metaBoxFeaturedPostRender($post)
	{
		$meta = $this->getMeta($post->ID);
		$url  = get_post_meta($post->ID, 'destination_url', true);

		wp_nonce_field( 'featured_post_box', 'featured_post_box_nonce' );
		?>	
		<div class="gcslider">
			<p>
				<label for="featured_post_featured_post"><?php _e('Featured post ( on the front page )'); ?>:</label>
				<input type="hidden" name="featured_post"  value="off">
				<input type="checkbox" name="featured_post" id="featured_post_featured_post" <?php echo $this->checked($meta); ?> >				
			</p>			
			<p>
				<label for="destination_url"><?php _e('Destination url'); ?>:</label>
				<input type="text" name="destination_url" value="<?php echo $url; ?>">	
			</p>			
		</div>	
		<?php
	}

	/**
	 * Check or no
	 * @param  boolean $yes
	 * @return string
	 */
	public function checked($yes = false)
	{
		return ($yes) ? 'checked' : '';
	}

	/**
	 * Get meta array
	 * @param  integer $id
	 * @return array
	 */
	public function getMeta($id)
	{
		return get_post_meta($id, 'featured_post', true);
	}

	/**
	 * Save post
	 * @param  integer $post_id 
	 * @return integer
	 */
	public function savePost($post_id)
	{
		// =========================================================
		// Check nonce
		// =========================================================
		if(!isset( $_POST['featured_post_box_nonce'])) return $post_id;
		if(!wp_verify_nonce($_POST['featured_post_box_nonce'], 'featured_post_box')) return $post_id;
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
		if(isset($_POST['featured_post']))
		{
			$featured_post = ($_POST['featured_post'] == 'on') ? true : false;
			update_post_meta($post_id, 'featured_post', $featured_post);
		}
		if(isset($_POST['destination_url']))
		{			
			update_post_meta($post_id, 'destination_url', $_POST['destination_url']);
		}

		return $post_id;
	}

	/**
	 * Get all featured items
	 * @return array
	 */
	public function getItems()
	{
		$args = array(
			'posts_per_page'   => 500,
			'offset'           => 0,
			'category'         => '',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => 'featured_post',
			'meta_value'       => 1,
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true );
		
		$posts = get_posts($args);
		foreach ($posts as $post) 
		{
			if(has_post_thumbnail($post->ID))
			{
				$id          = get_post_thumbnail_id($post->ID);
				$img         = wp_get_attachment_image_src($id ,'featured-image', false);					
				$post->image = $img[0];
			}
			$post->destination_url = get_post_meta($post->ID, 'destination_url', true);
			$post->permalink = get_permalink($post->ID);
			$items[] = $post;
		}
		return $items;
	}

	/**
	 * Get all fetured posts HTML
	 * @return string
	 */
	public function getFeaturedPosts()
	{
		$out   = '';
		$items = $this->getItems();
		
		for ($i=0; $i < count($items); $i+=3) 
		{ 
			$out.= '<div class="columns cf relative">';
			for ($j=0; $j < 3; $j++) 
			{ 				
				$index = $i + $j;
				if(isset($items[$index]))
				{	
					$url = ($items[$index]->destination_url != '') ? $items[$index]->destination_url : $items[$index]->permalink;
					$out.= '<div class="column">';
					$out.= '<div class="image">';
					$out.= '<a href="'.$url.'"><img src="'.$items[$index]->image.'" alt=""></a>';
					$out.= '</div>';

					$out.= '<p>'.$this->getAnons($items[$index]->post_content).'</p>';
					$out.= '<a href="'.$url.'" class="link-arrow-big pc-visible learn-more-bottom">Learn More</a>';
					$out.= '</div>';	
				}
			}
			$out.= '</div>';
		}
		return $out;
	}	

	/**
	 * Get first part before <!--more-->
	 * @param  string $txt 
	 * @return string
	 */
	public function getAnons($txt)
	{
		$str = explode('<!--more-->', $txt);
		return $str[0];
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['meta_box_featured_post'] = new MetaBoxFeaturedPost();