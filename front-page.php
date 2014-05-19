<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>

<?php get_header(); ?>

<section class="section-media">
	<div class="holder">
		<div class="center-wrap">
			<div class="mainslides">
			<?php echo $GLOBALS['mainslider']->getSlides(); ?>	
			</div>
		</div>
	</div>
</section>
<section class="slider-area">
	<div class="center-wrap">
		<div class="title-row cf">
			<h2 class="title-section">Latest Foundation Assessments</h2>
			<div class="slider-control">
				<a href="#" class="link-prev pc-hide-dib">Previous</a>
				<?php echo $GLOBALS['mainslider']->getSwitcher(); ?>				
				<a href="#" class="link-next pc-hide-dib">Next</a>
			</div>
		</div>
		<a href="#" class="link-prev pc-visible">Previous</a>
		<a href="#" class="link-next pc-visible">Next</a>
		<?php echo $GLOBALS['slider']->getSlides(); ?>		
	</div>
</section>
<section class="section-socials cf">
	<div class="holder">
		<div class="center-wrap cf">
			<?php 
			$GLOBALS['social_feed']->displayFeed(); 
			get_sidebar('blog');
			?>
		</div>
	</div>
</section>
<section class="section-columns">
	<div class="center-wrap cf">
		<h2 class="title-section lightgreen">How Philamplify Works</h2>
		<?php echo $GLOBALS['meta_box_featured_post']->getFeaturedPosts(); ?>
	</div>
</section>

<?php get_footer(); ?>