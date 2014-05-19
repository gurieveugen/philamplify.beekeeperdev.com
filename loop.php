<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>

<?php
if(is_page('newsroom')){
    echo 'test';
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args  = array(
    'category_name' => 'news',
    'paged' => $paged
);

if(is_search())
{
	global $wp_query;
	$args  = array_merge( $wp_query->query_vars, array( 'post_type' => array('assessment', 'post', 'page') ) );	
}

query_posts( $args );
if ( have_posts() ) : ?>

<div class="posts-holder">
<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div class="holder cf">
			<?php 
			if(has_post_thumbnail(get_the_ID()))
			{
				$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
				$news_image        = wp_get_attachment_image_src($post_thumbnail_id ,'news-image', false);
				$news_tablet_image = wp_get_attachment_image_src($post_thumbnail_id ,'news-tablet-image', false);
				$news_image        = $news_image[0];
				$news_tablet_image = $news_tablet_image[0];
				?>
				<a href="<?php the_permalink(); ?>" class="image">
					<img src="<?php echo $news_image; ?>" class="tablet-hide" alt="">
					<img src="<?php echo $news_tablet_image; ?>" class="tablet-visible" alt="">
				</a>
				<?php
			}
			?>
			
			<div class="content">
				<?php the_excerpt(); ?>
			</div>
		</div>
		<div class="post-meta-holder">
			<ul class="post-meta cf">
				<li class="date"><?php the_time('F j, Y'); ?></li>
				<?php 
				if($categories = get_the_category())
				{
					foreach($categories as $category) 
					{
						if($category->term_id != 1) echo '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
					}
				}
				?>
				<li class="disqus-comment" data-url="<?php echo get_permalink(); ?>"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></li>
			</ul>
		</div>
		
	</article><!-- #post -->

<?php endwhile; ?>
</div> <!-- .posts-holder -->
	
<?php theme_paging_nav(); ?>

<?php else: ?>
	
	<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
	
	<div class="page-content">

		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'theme' ); ?></p>
		<?php get_search_form(); ?>

	</div><!-- .page-content -->
	
<?php endif; ?>