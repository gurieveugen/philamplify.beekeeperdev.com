<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php
    get_header();

    $options 		 = $GLOBALS['gcoptions']->getAll();
?>
<div class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
</div>
<div id="main" class="center-wrap cf">
	<article id="content" class="main-content cf">
	
	<?php if ( have_posts() ) : the_post(); ?>
	<?php
		$post_categories = wp_get_post_categories(get_the_id(), array('fields' => 'all'));		
		$cats            = array();
		$cats_links      = '';
		if($post_categories)
		{
			foreach ($post_categories as $cat) 
			{
				if($cat->term_id != 1) $cats[] = '<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a>';
			}	
			$cats_links = implode(', ', $cats);
			$cats_links = ($cats_links == '') ? '' : ' in '.$cats_links;
		}
	?>
		<p class="entry-meta">
			Posted on <?php the_date(); echo $cats_links; ?><!-- <a href="#">Category Name</a> -->
		</p>
		<?php the_content(); ?>
		<?php echo $options['comments_instructions']; ?>
        <div class="comments-section">
			<div id="disqus_thread"></div>
		</div>
	
	<?php endif; ?>
	
	</article>

	<?php get_sidebar('blog'); ?>
</div>
<?php get_footer(); ?>