<?php
/*
 * @package WordPress
 * Template Name: Stories Page
*/
?>
<?php get_header(); ?>
<?php the_post(); ?>
<header class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
</header>
<div class="main-stories center-wrap cf">
	<a href="<?php bloginfo('url'); ?>/share-your-story" class="btn-submit-stories">
		<img src="<?php echo TDU; ?>/images/ico-story.png" alt="">
		Submit your own stories!
	</a>
	
	<div class="filters-area">
		<strong class="title">Filter</strong>
		<div class="item">
			<label class="mobile-hide-dib">Media Type:</label>
			<ul class="icons filter-icons">
				<li><a href="#" class="selected" data-id="filter-text" data-selected="<?php echo TDU; ?>/images/ico-text-selected.png" data-notselected="<?php echo TDU; ?>/images/ico-text.png"><img src="<?php echo TDU; ?>/images/ico-text-selected.png" alt=""></a></li>
				<li><a href="#" class="selected" data-id="filter-video" data-selected="<?php echo TDU; ?>/images/ico-vdeo-selected.png" data-notselected="<?php echo TDU; ?>/images/ico-vdeo.png"><img src="<?php echo TDU; ?>/images/ico-vdeo-selected.png" alt=""></a></li>
				<li><a href="#" class="selected" data-id="filter-photo" data-selected="<?php echo TDU; ?>/images/ico-photo-selected.png" data-notselected="<?php echo TDU; ?>/images/ico-photo.png"><img src="<?php echo TDU; ?>/images/ico-photo-selected.png" alt=""></a></li>
			</ul>
			<div class="filters hide">
				<input type="checkbox" id="filter-text" name="text" value="text" checked>
				<input type="checkbox" id="filter-video" name="video" value="video" checked>
				<input type="checkbox" id="filter-photo" name="photo" value="photo" checked>
			</div>
			<!-- PRELOAD IMAGES -->
			<div class="hide">
				<img src="<?php echo TDU; ?>/images/ico-text.png" alt="">
				<img src="<?php echo TDU; ?>/images/ico-vdeo.png" alt="">
				<img src="<?php echo TDU; ?>/images/ico-photo.png" alt="">				
			</div>
		</div>
		<div class="item">
			<label class="mobile-hide-dib">State:</label>
			<?php $states = array_merge(array('ALL' => 'ALL'), getStates()); ?>
			<select name="state" class="select-state">
				<?php
				foreach ($states as $key => $value) 
				{
					?>
					<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="item">
			<label class="mobile-hide-dib">Issue:</label>
			<select name="industry" class="select-industry">
				<option value="-1">Your Issue</option>
				<?php 
				$assessments_options = $GLOBALS['assessments_options']->getAll();
				$industry            = $assessments_options['industry'];
				var_dump($industry);
				if($industry)
				{
					foreach ($industry as $key => &$value) 
					{
						?>
						<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row">
		<?php the_content(); ?>
	</div>
	<div class="stories-list cf">
		<?php 
		$options = $GLOBALS['gcoptions']->getAll();
		$items   = $GLOBALS['sotries']->getItems(array('posts_per_page' => intval($options['stories_count']))); 
		echo $GLOBALS['sotries']->wrapItems($items);
		?>		
	</div>
	<div class="btn-more-holder">
		<a href="#" class="btn-green more-stories-ajax">More Stories</a>
	</div>
</div>
<?php get_footer(); ?>