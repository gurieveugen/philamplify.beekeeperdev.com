<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>
<header class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1>Newsroom</h1>
		</div>
	</div>
</header>
<div id="main" class="center-wrap cf">
	<div id="content" class="cf">
		<?php include("loop.php"); ?>
	</div>
	<?php get_sidebar('blog'); ?>
</div>
<?php get_footer(); ?>
