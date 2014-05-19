<?php
/**
 * Register new widget
 */
add_action('widgets_init', create_function('', 'register_widget( "Subscribe" );'));


class Subscribe extends WP_Widget {
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct() 
	{
		$widget_ops     = array('classname' => 'w-col', 'description' => 'Subscribe widget' );		
		parent::__construct('subscribe', 'Subscribe widget', $widget_ops);
	}

	function widget($args, $instance) 
	{
		extract($args);
		$title     = strip_tags($instance['title']);		

		echo $before_widget;		
		// =========================================================
		// Print featured widget
		// =========================================================		
		if($title != '') echo $before_title.$title.$after_title;
		?>
		<form action="#" class="form-signup form-subscribe-ajax">
			<input type="email" placeholder="EMAIL ADDRESS" name="email" required>
			<input type="hidden" value="<?php echo getIP(); ?>" name="ip">
			<?php wp_nonce_field(AJAX::SUBSCRIBE_NONCE, 'security_subscribe'); ?>
			<input type="submit" class="btn-dark-green" value="Subscribe">			
		</form>
		<?php
		echo $after_widget;
	}

	function form($instance) 
	{	
		$title     = $instance['title'];     		

		?>		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
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
		$instance              = $old_instance;		
		$instance['title']     = strip_tags($new_instance['title']);				

		return $instance;
	}
}