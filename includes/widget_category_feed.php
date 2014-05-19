<?php
/**
 * Register new widget
 */
add_action('widgets_init', create_function('', 'register_widget( "CategoryFeed" );'));


class CategoryFeed extends WP_Widget {
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct() 
	{
		$widget_ops     = array('classname' => '', 'description' => 'Category feed widget' );		
		parent::__construct('categoryfeed', 'Category feed widget', $widget_ops);
	}

	function widget($args, $instance) 
	{
		extract($args);
		$title     = strip_tags($instance['title']);
		$title_url = strip_tags($instance['title_url']);
		$count     = intval($instance['count']);
		$category  = ($instance['category'] == 0) ? '' :  $instance['category'];

		echo $before_widget;		
		// =========================================================
		// Print featured widget
		// =========================================================
		$args = array(
			'posts_per_page'   => $count,
			'offset'           => 0,
			'cat'         	   => $category,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'fields'		   => 'ids',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'assessment',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true );

		$posts = get_posts($args);
		if($title != '') echo $before_title.'<a href="'.$title_url.'">'.$title.'</a>'.$after_title;
		echo '<ul class="list">';
		foreach ($posts as $value) 
		{
			$item_title = get_the_title($value);
			$ask        = strpos($item_title, '?');			
			$class = ($ask === false) ? 'ico-a' : 'ico-q';
			?>
			<li>
				<a href="<?php echo get_permalink($value); ?>" class="<?php echo $class; ?>"><?php echo $item_title; ?></a>
			</li>
			<?php
		}
		echo '</ul><!-- /.list -->';
		echo $after_widget;
	}

	function form($instance) 
	{	
		$title     = $instance['title'];     
		$title_url = $instance['title_url']; 
		$count     = $instance['count'];     
		$category  = $instance['category'];
		
		?>		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('title_url'); ?>"><?php _e('Title URL'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('title_url'); ?>" name="<?php echo $this->get_field_name('title_url'); ?>" type="text" value="<?php echo esc_attr($title_url); ?>" />
			</label>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo intval($count); ?>" />
			</label>
		</p>			
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category'); ?>: 
				<?php echo $this->getCategoriesControl($this->get_field_name('category'), $this->get_field_id('category'), $category); ?>				
			</label>
		</p>		
		<?php
	}

	/**
	 * Get Categories select control
	 * @param  string  $name    
	 * @param  string  $id      
	 * @param  integer $current 
	 * @return string
	 */
	function getCategoriesControl($name, $id, $current = 0)
	{
		$out  = '';
		$args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false); 

		$categories = get_categories($args);

		if($categories)
		{
			$out = '<select name="'.$name.'" id="'.$id.'">';			
			foreach ($categories as &$cat) 
			{
				$selected = $this->selected($current == $cat->term_id);
				$out.= sprintf('<option value="%s" %s>%s</option>', $cat->term_id, $selected, $cat->name);
			}
			$out.= '</select>';
		}
		return $out;
	}

	/**
	 * Helper for select control
	 * @param  boolean $yes 
	 * @return string
	 */
	function selected($yes = true)
	{
		return ($yes) ? 'selected' : '';
	}

	/**
	 * Update all edits
	 * @param  array $new_instance 
	 * @param  array $old_instance 
	 * @return array               
	 */
	function update($new_instance, $old_instance) 
	{
		$instance              = $old_instance;		
		$instance['title']     = strip_tags($new_instance['title']);		
		$instance['title_url'] = strip_tags($new_instance['title_url']);		
		$instance['count']     = intval($new_instance['count']);				
		$instance['category']  = intval($new_instance['category']);	
		
		return $instance;
	}
}