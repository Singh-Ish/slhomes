<?php
	// archive-service.php
	// Archive Page of Services
	
	global $post;
	get_header();
?>
	<section class="features-main-container">
		<h2 class="rh-title"><span><?php esc_html_e('Our Services', 'realty-house') ?></span></h2>
		<div class="features-container container">
			<?php
				if ( have_posts() )
				{
					while ( have_posts() )
					{
						the_post();
						$post_id = get_the_id();
						$service_icon = (get_post_meta($post_id, 'services_icon' , true) ? get_post_meta($post_id, 'services_icon' , true) : 'fa fa-cog');
						?>
						<div class="features-box col-sm-6 col-md-4">
							<i class="<?php echo esc_attr($service_icon) ?>"></i>
							<div class="title"><?php the_title() ?></div>
							<div class="desc"><?php the_content() ?></div>
						</div>
						<?php
					}
					realty_house_tm_pagination();
				}
			?>
			
		</div>
	</section>
<?php
	get_footer();