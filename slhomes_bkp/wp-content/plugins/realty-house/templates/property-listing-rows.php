<?php
	/**
	 *  property-listing-rows.php
	 *  Property Listing List View
	 *  Template Name: Property Listing - List
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	$data          = array (
		"property" => array ()
	);
?>
<?php echo do_shortcode('[realty-house-search type="simple" p_location="on" p_status="on" p_type="on" bedrooms="on" bathrooms="on" price="on" max_price="1000000000" amenities="on" ]'); ?>
	<section class="page-content container">
		<section class="main-container <?php echo is_active_sidebar( 'p-listing-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<h3 class="rh-title">
				<span><?php esc_html_e( 'Property Listing', 'realty-house-pl' ) ?></span>
				<?php
					$list_view_url = realty_house_get_pages::page_template( '../templates/property-listing.php' );
					if ( ! empty( $list_view_url ) ):
						?>
						<div class="list-view">
							<a href="#" class="active"><i class="fa fa-th-list"></i></a>
							<a href="<?php echo esc_url( $list_view_url['url'] ) ?>"><i class="fa fa-th"></i></a>
						</div>
					<?php endif; ?>
			</h3>
			<!-- Property Listing -->
			<div class="property-listing-main-container list">
				<div class="inner-container">
					<div class="property-boxes clearfix">
						<?php
							$paged             = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							$new_property_args = array (
								'posts_per_page' => get_option( 'posts_per_page' ),
								'orderby'        => 'post_date',
								'order'          => 'DESC',
								'post_type'      => 'property',
								'post_status'    => 'publish',
								'paged'          => $paged
							);
							
							$new_properties = new WP_Query( $new_property_args );
							
							if ( $new_properties->have_posts() )
							{
								while ( $new_properties->have_posts() )
								{
									$new_properties->the_post();
									$post_id              = get_the_id();
									$property_information = $property_info->property_info( $post_id );
									
									$property_array = array (
										"pId"         => $post_id,
										"longitude"   => $property_information['long'],
										"latitude"    => $property_information['lat'],
										"pType"       => ! empty( $property_information['type']['icon'] ) ? $property_information['type']['icon'] : '',
										"title"       => get_the_title(),
										"price"       => $property_information['price']['generated'],
										"pImage"      => ( $property_information['gallery']['count'] > 0 ? $property_information['gallery']['img'][0]['code']['large'] : '' ),
										"location"    => $property_information['address'],
										"description" => $property_information['description']['main'],
										"bedroom"     => $property_information['bedroom'],
										"bathroom"    => $property_information['bathroom'],
										"size"        => $property_information['building_size'] . ' ' . $property_information['building_size'],
										"pURL"        => esc_url( $property_information['url'] ),
									);
									array_push( $data['property'], $property_array );
									
									echo '
										<div class="property-box item">										
											<div class="inner-box clearfix">';
									
									if ( ! empty( $realty_house_opt['realty-house-property-img-slider'] ) && $property_information['gallery']['count'] > 1 )
									{
										echo '<div class="post-slider list-view">';
										$p_slider_i = 1;
										foreach ( $property_information['gallery']['img'] as $image_item )
										{
											if ( $p_slider_i > $realty_house_opt['realty-house-property-img-slider-count'] )
											{
												continue;
											}
											echo '<div class="items">' . $image_item['code']['large'] . '</div>';
											$p_slider_i ++;
										}
										echo '</div>';
									}
									else
									{
										echo '
											<a href="' . esc_url( $property_information['url'] ) . '" class="img-container col-md-5">
												' . ( $property_information['gallery']['count'] > 0 ? $property_information['gallery']['img'][0]['code']['large'] : '' ) . '
											</a>';
									}
									echo '
												<div class="bot-sec col-md-7">
													<div class="title">' . esc_html( $property_information['title'] ) . '</div>
													<div class="fav-compare">
														' . ( ! empty( $property_information['owner'] ) && $property_information['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $post_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
														<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $property_information['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
														<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $property_information['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
													</div>
													<i class="fa fa-inr"></i><div class="price">' . esc_html( $property_information['price']['value'] ) . '</div>
													<div class="location">' . esc_html( $property_information['address'] ) . '</div>
													<div class="desc" style="display:none;">' . esc_html( $property_information['description']['main'] ) . '</div>
													<div class="features">
														<div class="bed">
															<i class="realty-house-bedroom"></i>
															' . esc_html( $property_information['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house-pl' ) . '
														</div>
														<div class="bath">
															<i class="realty-house-bathroom"></i>
															' . esc_html( $property_information['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house-pl' ) . '
														</div>
														<div class="size">
															<i class="realty-house-size"></i>
															' . esc_html( $property_information['building_size'] ) . ' ' . esc_html( $property_information['area_unit'] ) . '
														</div>
													</div>
													<div class="b-sec">
														<div class="more-info">
															<a href="' . esc_url( $property_information['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house-pl' ) . '</a>
														</div>
													</div>
												</div>
											</div>
										</div>									
									';
								}
								wp_reset_postdata();
							}
						?>
					</div>
					<?php
						realty_house_tm_pagination( $new_properties );
						
						wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
					?>
				</div>
			</div>
		</section>
		<?php if ( is_active_sidebar( 'p-listing-sidebar' ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'p-listing-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();