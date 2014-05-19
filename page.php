<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
</div>
<div id="main" class="center-wrap cf">
	<article id="content" class="main-content cf">
		<?php the_content(); ?>
	</article>
	<?php get_sidebar(); ?>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
