<?php 
	get_header(); 
	the_post();

	$meta            = get_post_meta(get_the_id(), 'meta', true);	
	$recommendations = get_post_meta(get_the_id(), 'recommendations', true);
    echo "<!--<pre>";
    var_dump($recommendations);
    echo "</pre>-->";	
	$size            = getFileSize($meta['pdf_url']);
	$options 		 = $GLOBALS['gcoptions']->getAll();
?>
<div class="data-section">
	<div class="holder">
		<div class="center-wrap">
			<h1>
            <?php the_title(); ?> 
            <?php if (get_field('subtitle')) the_field('subtitle'); ?>
            </h1>
			<div class="block cf">
				<?php
				if(has_post_thumbnail())
				{
					?>
					<div class="video-box">
						<?php the_post_thumbnail('assessment-image'); ?>
						<a href="<?php echo $meta['video_url']; ?>" class="ico-video fancybox-media"></a>
					</div>
					<?php
				}
				?>
				<div class="quotes-holder">
					<blockquote class="box-quote">
						<q>“<?php echo $meta['quote_first']; ?>”</q>
						<cite>-- <a href="<?php echo $meta['qf_source_url']; ?>"><?php echo $meta['qf_source']; ?></a></cite>
					</blockquote>
					<blockquote class="box-quote q1">
						<q>“<?php echo $meta['quote_second']; ?>”</q>
						<cite>-- <a href="<?php echo $meta['qs_source_url']; ?>"><?php echo $meta['qs_source']; ?></a></cite>
					</blockquote>
				</div>
			</div>
			<div class="btn-download-row">
				<a href="<?php echo $meta['pdf_url']; ?>" class="btn-green-big" target="_blank">Download the Full Assessment</a>
				<span class="file-info"><?php echo $size; ?>kb PDF Document</span>
			</div>
		</div>
	</div>
</div>
<div id="main" class="center-wrap cf">
	<div id="content" class="main-content content-1 cf">
		<h2 class="title-blue">Overview</h2>
		<?php      
			the_excerpt();
		?>
		<div class="data-box">
			<?php 
			$identifier = intval((get_the_id()*1000000));
			$data_url   = get_permalink().'#!/'.$identifier;
			?>
			<a href="#" class="btn-box" data-identifier="<?php echo $identifier; ?>" data-url="<?php echo $data_url; ?>" >View Full Summary and Key Findings <i class="arrow"></i></a>
			<div class="content">
				<div class="holder cf">
					<?php 						     
						the_content();
					?>
                    <div>
                        <?php echo $options['comments_instructions']; ?>
                    </div>
				</div>
			</div>
		</div>
		<h2 class="title-blue">Recommendations</h2>
		<?php 		
		foreach ($recommendations as $id => $recommendation) 
		{
			$star       = (intval($recommendation['featured'])) ? 'star' : '';
			$agree      = intval($recommendation['agree']);
			$disagree   = intval($recommendation['disagree']);
			$sum        = $agree + $disagree;
			$percent    = ($agree > 0 && $sum > 0) ? intval($agree/($sum/100)) : 0;
			$identifier = intval((get_the_id()*1000000)  + $id);
			$data_url   = get_permalink().'#!/'.$identifier;
			?>
			<article class="r-box" id="r-box-<?php echo $id; ?>">
				<header class="cf">
					<h1 class="<?php echo $star; ?>"><?php echo $recommendation['title']; ?></h1>
					<a href="#" class="link-view">View Full Recommendation</a>
				</header>
				<div class="content">
					<div class="holder cf">
						<?php echo $recommendation['content']; ?>
					</div>
				</div>
				<footer class="cf">
					<div class="buttons cf" data-id="<?php echo $id; ?>" data-post-id="<?php echo get_the_id(); ?>">
						<a href="#" class="btn-agree">AGREE</a>
						<a href="#" class="btn-disagree">DISAGREE</a>
					</div>
					<a href="#" class="link-comments mobile-hide"  data-id="<?php echo $id; ?>" data-identifier="<?php echo $identifier; ?>" data-url="<?php echo $data_url; ?>">0 Comments</a>
					<p class="info"><strong><?php echo $percent; ?>%</strong> of <?php echo $sum; ?> people <strong class="blue">AGREE</strong></p>
					<a href="#" class="link-comments mobile-visible-dib" data-id="<?php echo $id; ?>" data-identifier="<?php echo $identifier; ?>" data-url="<?php echo $data_url; ?>">0 Comments</a>
				</footer>
			</article>
			<div id="r-comments-<?php echo $id; ?>" class="r-comments">	
				<?php echo $options['comments_instructions']; ?>
			</div>
			<?php	
		}
		?>
		
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
//added lighbox code to the same page
?>
    <div class="lightbox hide" id="email-lightbox">
        <div class="holder">
            <h2>Email Foundation Leadership</h2>
            <?php
            $contact = get_field('contact_form');
            $contact_shortcode = '[contact-form-7 id="'.$contact->ID.'" title="'.$contact->post_title.'"]';
            if(!empty($contact->ID)):?>
                <?php echo do_shortcode($contact_shortcode); ?>
            <?php else:?>
                <?php echo do_shortcode('[contact-form-7 class:form-efl id="470" title="Untitled"]'); ?>
            <?php endif;?>
        </div>
    </div>
    <div class="lightbox lb1 hide" id="thank-you-lightbox">
        <div class="holder">
            <h2>Thank you</h2>
            <?php echo do_shortcode('[contact-form-7 id="471" title="Thank you"]'); ?>
        </div>
    </div>
    <div class="lightbox-mask hide"></div>
<?php //get_template_part('lightbox', 'assessment'); ?>


    <script type="text/javascript">
        jQuery(function($){
            var msgSelect = $('#email-lightbox textarea[name="msg"]');
            $('body').on('keyup', '#email-lightbox input[name="firstname"]', function(){
                msgSelect.val(msgSelect.val().replace('{{fname}}', $(this).val()));
            });
            $('body').on('keyup', '#email-lightbox input[name="lastname"]', function(){
                msgSelect.val(msgSelect.val().replace('{{lname}}', $(this).val()));
            });
        });
    </script>
<?php get_footer(); ?>