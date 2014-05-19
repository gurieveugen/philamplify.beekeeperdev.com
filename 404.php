<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>
<div class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1><?php _e( 'Not found', 'theme' ); ?></h1>
		</div>
	</div>
</div>
<div id="main" class="center-wrap cf">
	<div id="content">
		<div class="page-content">
			<h2><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'theme' ); ?></h2>
			<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'theme' ); ?></p>
		</div>
	</div>
</div>
<?php get_footer(); ?>