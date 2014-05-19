<?php
/*
 * @package WordPress
 * Template Name: Stories Submit Page
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
<div id="main" class="center-wrap cf">
	<div class="content-box cf">
		<h2>Share Your Story and Upload Media</h2>
		<p><?php the_content(); ?></p>
		<form action="<?php bloginfo('template_url'); ?>/includes/share_story.php" id="#submit-story-form" class="form-story form-share-story-ajax" method="POST" enctype="multipart/form-data">
			
			<h4>
				<img src="<?php echo TDU; ?>/images/icon-person-mini.png" class="pc-visible-dib" alt="">
				<img src="<?php echo TDU; ?>/images/icon-person.png" class="pc-hide-dib" alt="">
				Tell us about yourself
			</h4>
			<div class="row cf">
				<div class="column width-140">
					<input type="text" placeholder="First Name" name="first_name" required>
				</div>
				<div class="column width-140">
					<input type="text" placeholder="Last Name" name="last_name" required>
				</div>
				<div class="column width-175 last">
					<input type="email" placeholder="Email Address" name="email" required>
				</div>
			</div>
			<div class="row-1 cf">
				<div class="column width-125">
					<input type="text" placeholder="ZIP Code (Optional)" name="zip">
				</div>
				<div class="column width-125">
					<?php $states = getStates(); ?>
					<select name="state" id="state">
						<?php
						foreach ($states as $key => $value) 
						{
							?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div class="column width-238">
					<select name="industry">
						<option value="-1">Your issue (Optional)</option>
						<?php 
						$assessments_options = $GLOBALS['assessments_options']->getAll();
						$industry            = $assessments_options['industry'];
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
			<h4>
				<img src="<?php echo TDU; ?>/images/icon-video-mini.png" class="pc-visible-dib" alt="">
				<img src="<?php echo TDU; ?>/images/icon-video.png" class="pc-hide-dib" alt="">
				Upload Video</h4>
			<div class="row-2 cf">
				<div class="column">
					<input type="text" placeholder="Video Title" name="video_title">
				</div>
				<div class="column">
					<input type="text" placeholder="Video Description" name="video_description">
				</div>
				<div class="column width-140 file last">
					<input type="file" name="video">
				</div>
			</div>
			<h4>
				<img src="<?php echo TDU; ?>/images/icon-photo-mini.png" class="pc-visible-dib" alt="">
				<img src="<?php echo TDU; ?>/images/icon-photo.png" class="pc-hide-dib" alt="">
				Upload Photo
			</h4>
			<div class="row-2 cf">
				<div class="column">
					<input type="text" placeholder="Photo Title" name="photo_title">
				</div>
				<div class="column">
					<input type="text" placeholder="Photo Description" name="photo_description">
				</div>
				<div class="column width-140 file last">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
					<input type="file" name="photo">
				</div>
			</div>
			<h4>
				<img src="<?php echo TDU; ?>/images/icon-link-mini.png" class="pc-visible-dib" alt="">
				<img src="<?php echo TDU; ?>/images/icon-link.png" class="pc-hide-dib" alt="">
				Link to Media
			</h4>
			<div class="row-2 cf">
				<div class="column">
					<input type="text" placeholder="Media Title" name="media_title">
				</div>
				<div class="column">
					<input type="text" placeholder="Media Description" name="media_description">
				</div>
				<div class="column last width-140">
					<input type="text" placeholder="Media Link" class="width-100" name="media_link">
				</div>
			</div>
			<h4>
				<img src="<?php echo TDU; ?>/images/icon-write-mini.png" class="pc-visible-dib" alt="">
				<img src="<?php echo TDU; ?>/images/icon-write.png" class="pc-hide-dib" alt="">
				Write Your Story
			</h4>
			<div class="row-2 cf">
				<input type="text" name="story_title" style="width: 100%;" placeholder="Story title">
			</div>
			<div class="row-2 cf">
				<textarea name="story" cols="30" rows="10" placeholder="Story"  required></textarea>
			</div>
			<div class="submit-row cf">
				<div class="right">
					<span class="agree">
						<input type="checkbox" name="i_agree">
						<label>I agree to <a href="<?php bloginfo('url'); ?>/terms/">Terms of Use</a> and <a href="<?php bloginfo('url'); ?>/privacy-policy/">Privacy Policy</a></label>
					</span>
					<input type="submit" value="Submit" class="btn-green">
				</div>
			</div>
		</form>
	</div>
	<?php get_sidebar('blog'); ?>
</div>
<?php get_footer(); ?>