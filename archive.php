<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>
<?php 
global $post;
if (is_category()): $title = sprintf( __( 'Category Archives: %s', 'theme' ), single_cat_title( '', false ) );
elseif(is_tag()): $title = sprintf( __( 'Tag Archives: %s', 'theme' ), single_tag_title( '', false ) );
elseif (is_day()): $title = sprintf( __( 'Daily Archives: %s', 'theme' ), get_the_date() );
elseif (is_month()): $title = sprintf( __( 'Monthly Archives: %s', 'theme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'theme' ) ) );
elseif (is_year()): $title = sprintf( __( 'Yearly Archives: %s', 'theme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'theme' ) ) );
elseif (is_author()): $title = sprintf( __( 'All posts by %s', 'theme' ), '<span class="vcard"><a class="url fn n" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" title="'.esc_attr( get_the_author() ).'" rel="me">'.get_the_author().'</a></span>');
else: $title = __('Archives', 'theme');
endif;
?>
<header class="page-title">
	<div class="holder">
		<div class="center-wrap">			
			<h1><?php echo $title; ?></h1>
		</div>
	</div>
</header>
<div class="center-wrap cf" id="main">
<div id="content" role="main">
	<?php include("loop.php"); ?>
</div>

<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>