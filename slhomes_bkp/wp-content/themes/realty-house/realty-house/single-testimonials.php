<?php
	// single-testimonials.php
	// Singe Page of Testimonials
	
	global $post;
	get_header();
?>
	<!-- Main Container -->
	<section class="page-content container">
		<section class="main-container <?php echo( is_active_sidebar( 'testimonials-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ) ?>">
			<h3 class="rh-title"><span><?php esc_html_e( 'What Clients Say', 'realty-house' ) ?></span></h3>
			<div class="testimonials-main-container clearfix">
				<?php
					if ( have_posts() )
					{
						while ( have_posts() )
						{
							the_post();
							$post_id = get_the_id();
							
							$client_location = ! empty( get_post_meta( $post_id, 'testimonials_guest_location', true ) ) ? get_post_meta( $post_id, 'testimonials_guest_location', true ) : '';
							$thumbnail       = ! empty( get_the_post_thumbnail( $post_id ) ) ? get_the_post_thumbnail( $post_id ) : '<div class="no-image"></div>';
							?>
							<div class="testimonial-box">
								<div class="inner-box">
									<i class="realty-house-quote"></i>
									<div class="content"><?php the_content() ?></div>
									<div class="details-box">
										<div class="img-container">
											<?php echo wp_kses_post( $thumbnail ) ?>
										</div>
										<div class="details">
											<div class="title"><?php the_title() ?></div>
											<div class="location"><?php echo esc_html( $client_location ) ?></div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						realty_house_tm_pagination();
					}
				?>
			</div>
		</section>
		<?php if ( is_active_sidebar( 'testimonials-sidebar' ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'testimonials-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();