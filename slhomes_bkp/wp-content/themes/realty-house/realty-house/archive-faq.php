<?php
	// archive-faq.php
	// Archive Page of FAQ page
	
	global $post;
	get_header();
?>
	<!-- Main Container -->
	<section class="page-content container">
		<section class="main-container <?php echo( is_active_sidebar( 'faq-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ) ?>">
			<h3 class="rh-title"><span><?php esc_html_e( 'Real Estate FAQ', 'realty-house' ) ?></span></h3>
			<div class="faq-main-container">
				<?php
					if ( have_posts() )
					{
						$faq_i = 1;
						while ( have_posts() )
						{
							the_post();
							$post_id = get_the_id();
							?>
							<div class="faq-box <?php echo ( $faq_i < 3 ) ? esc_attr( 'opened' ) : '' ?>">
								<div class="title">
									<i class="realty-house-question"></i>
									<?php the_title() ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content"><?php the_content() ?></div>
							</div>
							<?php
							$faq_i ++;
						}
						realty_house_tm_pagination();
					}
				?>
			</div>
		</section>
		<?php if ( is_active_sidebar( 'faq-sidebar' ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'faq-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();