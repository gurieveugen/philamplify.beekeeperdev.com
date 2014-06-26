<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php 
	global $post;
	$options             = $GLOBALS['gcoptions']->getAll(); 
	$socials['facebook'] = (isset($options['facebook_url']) && strlen($options['facebook_url'])) ? $options['facebook_url'] : '';
	$socials['twitter']  = (isset($options['twitter_url']) && strlen($options['twitter_url'])) ? $options['twitter_url'] : '';
	$socials['youtube']  = (isset($options['youtube_url']) && strlen($options['youtube_url'])) ? $options['youtube_url'] : '';
	$socials['rss']      = (isset($options['rss_url']) && strlen($options['rss_url'])) ? $options['rss_url'] : '';
	$title               = (wp_title( ' ', false, 'right' ) == '') ? get_bloginfo('name') : (wp_title( ' ', false, 'right' ) == '');
	$thumb_id            = get_post_thumbnail_id();
	$image_src           = $thumb_id != '' ? wp_get_attachment_image_src($thumb_id, 'thumb') : '';
	$image               = $image_src != '' ? $image_src[0] : '';
	$meta                = get_post_meta($post->ID, 'meta', true);
	$google_title        = isset($meta['google_title']) ? $meta['google_title'] : '';
	$google_description  = isset($meta['google_description']) ?$meta['google_description'] : '';
	$google_picture      = isset($meta['google_picture']) ?$meta['google_picture'] : '';
?>
<!DOCTYPE html>
<!-- <html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/Other"> -->
<html itemscope itemtype="http://schema.org/Blog" >
<head>
	<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
	
	<title><?php echo $title; ?></title>


	<!-- GOOGLE SNIPPET -->
	<meta itemprop="name" content="<?php echo $google_title; ?>">
	<meta itemprop="description" content="<?php echo $google_description; ?>">	
	<!-- GOOGLE SNIPPET END -->

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="stylesheet" media="(max-width: 970px)" href="<?php echo TDU; ?>/css/tablet.css" />
	<link rel="stylesheet" media="(max-width: 600px)" href="<?php echo TDU; ?>/css/mobile.css" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); 
		wp_head(); ?>
	<script type="text/javascript" src="<?php echo TDU; ?>/js/doubletaptogo.js" ></script>
	<script type="text/javascript" src="<?php echo TDU; ?>/js/jquery.formstyler.min.js" ></script>
	<script type="text/javascript" src="<?php echo TDU; ?>/js/jquery.cycle.all.js"></script>
	
	<!--[if lt IE 9]>
		<script type="text/javascript" src="<?php echo TDU; ?>/js/html5.js"></script>
		<style>
			body{min-width:980px}
		</style>
	<![endif]-->
	<!--[if lte IE 9]>
		<script type="text/javascript" src="<?php echo TDU; ?>/js/jquery.placeholder.min.js"></script>
		<script type="text/javascript">
			jQuery(function(){
				jQuery('input, textarea').placeholder();
			});
		</script>
	<![endif]-->
</head>
<body <?php body_class(); ?>>

	<div id="wrapper">
		<header id="header">
			<div class="center-wrap">
				<div class="holder cf">
					<div id="ico-menu" class="pc-hide"><img src="<?php echo TDU; ?>/images/ico-menu-t.png" alt=""></div>
					<strong class="logo"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
						<img class="pc-visible" src="<?php echo TDU; ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
						<img class="tablet-visible" src="<?php echo TDU; ?>/images/logo-tablet.png" alt="<?php bloginfo('name'); ?>">
						<img class="mobile-visible" src="<?php echo TDU; ?>/images/logo-mobile.png" alt="<?php bloginfo('name'); ?>">
					</a></strong>
					<div class="right mobile-hide">
						<ul class="socials socials-2">
							<?php array_walk($socials, 'printSocials');	?>							
						</ul>
						<?php get_search_form(); ?>						
					</div>
				</div>
				<?php wp_nav_menu( array(
					'container'       => 'nav',
					'container_class' => 'pc-visible',
					'container_id'    => 'main-nav',
					'theme_location'  => 'primary_nav',
					'menu_id'         => 'nav'
				)); ?>
				<div class="hide nav-box">
					<?php get_template_part('searchform', 'tablet'); ?>
					<!-- <form action="#" class="search-form-tablet cf">
						<input type="text" placeholder="Search">
						<input type="submit" value="Search">
					</form> -->
					<ul class="socials cf">
						<li><a href="#"><img src="<?php echo TDU; ?>/images/ico-facebook.png" alt=""></a></li>
						<li><a href="#"><img src="<?php echo TDU; ?>/images/ico-twitter.png" alt=""></a></li>
						<li><a href="#"><img src="<?php echo TDU; ?>/images/ico-youtube.png" alt=""></a></li>
						<li><a href="#"><img src="<?php echo TDU; ?>/images/ico-rss.png" alt=""></a></li>
					</ul>
					<?php wp_nav_menu( array(
						'container' => 'nav',
						'container_class' => 'nav-tablet-block',
						'theme_location' => 'primary_nav',
						'menu_class' => 'nav-tablet'
					)); ?>
				</div>
			</div>
		</header>