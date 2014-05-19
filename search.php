<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>
<header class="page-title">
	<div class="holder">
		<div class="center-wrap">			
			<h1><?php printf( __( 'Search Results for: %s', 'theme' ), get_search_query() ); ?></h1>
		</div>
	</div>
</header>
<div class="center-wrap cf" id="main">
	<div id="content">
	<?php if ( have_posts() ) : ?>
		<?php include("loop.php"); ?>
	<?php else : ?>
		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'theme' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>