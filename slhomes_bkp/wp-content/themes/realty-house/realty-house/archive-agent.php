<?php
	/**
	 *  property-listing.php
	 *  Property Listing Grid View
	 *  Template Name: Property Listing - Grid
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$agent_informations    = new realty_house_get_info();
	$property_informations = new realty_house_get_property();
	$sold_id               = array_search( 'Sold', $realty_house_opt['realty-house-property-status'] ) + 1;
?>
	<section class="page-content container">
		<section class="main-container <?php echo is_active_sidebar( 'agent-listing-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<h3 class="rh-title">
				<span><?php esc_html_e( 'Meet Our Agents', 'realty-house' ) ?></span>
			</h3>
			<div class="agent-listing">
				<?php
					if ( have_posts() )
					{
						while ( have_posts() )
						{
							the_post();
							$post_id    = get_the_id();
							$agent_info = $agent_informations->agent_info( $post_id );
							if ( $agent_info['username'] === 'demo' )
							{
								continue;
							}
							echo '
								<div class="agent-box clearfix">
									<div class="l-sec">
										<a href="' . esc_url( $agent_info['url'] ) . '">
											' . ( ! empty( $agent_info['has_image'] ) ? wp_kses_post( $agent_info['img']['full'] ) : '<div class="no-image"></div>' ) . '
										</a>
									</div>
									<div class="r-sec">
										<div class="t-sec clearfix">
											<div class="col-md-6">
												<div class="name">' . esc_html( $agent_info['name'] ) . '</div>
												<div class="rate">
													<div class="stars">';
							
							for ( $i = 0; $i < 5; $i ++ ):
								echo '<i class="fa fa-star ' . ( $i < $agent_info['rating']['total_rate'] ? esc_attr( 'active' ) : '' ) . '"></i>';
							endfor;
							
							echo '</div>
													<div class="votes">(' . esc_html( $agent_info['rating']['total_vote'] ) . ')</div>
												</div>
												' . ( ! empty( $sold_id ) ? '<div class="sale-stat"><span>' . count( $property_informations->properties_by_agent( $post_id, $sold_id ) ) . '</span> ' . esc_html__( 'Recent Sales', 'realty-house' ) . '</div>' : '' ) . '
											</div>
											<div class="col-md-6">
												<ul class="info">
													' . ( ! empty( $agent_info['address'] ) ? '<li><i class="fa fa-map-marker"></i>' . esc_html( $agent_info['address'] ) . '</li>' : '' ) . '
													' . ( ! empty( $agent_info['phone'] ) ? '<li><i class="fa fa-phone"></i>' . esc_html( $agent_info['phone'] ) . '</li>' : '' ) . '
													' . ( ! empty( $agent_info['email'] ) ? '<li><i class="fa fa-envelope"></i>' . esc_html( $agent_info['email'] ) . '</li>' : '' ) . '
												</ul>
											</div>
										</div>
										<div class="desc">' . esc_html( $agent_info["bio"]["main"] ) . '</div>
										<div class="b-sec clearfix">
											<ul class="social-icons list-inline">
												' . ( ! empty( $agent_info['facebook'] ) ? '<li><a href="' . esc_url( $agent_info['facebook'] ) . '"><i class="fa fa-facebook"></i></a></li>' : '' ) . '
												' . ( ! empty( $agent_info['twitter'] ) ? '<li><a href="' . esc_url( $agent_info['twitter'] ) . '"><i class="fa fa-twitter"></i></a></li>' : '' ) . '
												' . ( ! empty( $agent_info['google_plus'] ) ? '<li><a href="' . esc_url( $agent_info['google_plus'] ) . '"><i class="fa fa-google-plus"></i></a></li>' : '' ) . '
											</ul>
											<a href="' . esc_url( $agent_info['url'] ) . '" class="view-listing">' . esc_html__( 'View Listing', 'realty-house' ) . '</a>
										</div>
									</div>
								</div>				
									';
						}
						wp_reset_postdata();
					}
				?>
			</div>
			<?php realty_house_tm_pagination(); ?>
		</section>
		
		<?php if ( is_active_sidebar( 'agent-listing-sidebar' ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'agent-listing-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();