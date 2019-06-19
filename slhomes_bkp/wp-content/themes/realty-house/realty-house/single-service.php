<?php
	// single-service.php
	// Single Page of Services
	
	global $post;
	get_header();
?>
	<section class="features-main-container">
		<div class="features-container container single">
			<?php
				if ( have_posts() )
				{
					while ( have_posts() )
					{
						the_post();
						$post_id = get_the_id();
						$service_icon = (get_post_meta($post_id, 'services_icon' , true) ? get_post_meta($post_id, 'services_icon' , true) : 'fa fa-cog');
						?>
						<div class="features-box">
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