<?php
	/**
	 *  property-listing-map.php
	 *  Property Listing Grid View with Map View
	 *  Template Name: Property Listing - Map View
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	$data          = array (
		"property" => array ()
	);
?>
	<section id="property-listing-map" class="clearfix">
		<div class="col-xs-7 property-listing-container">
			
			<?php if ( is_active_sidebar( 'p-map-listing-sidebar' ) ): ?>
				<!-- Sidebar Section -->
				<div id="side-bar" class="property-listing-map-side-bar">
					<?php dynamic_sidebar( 'p-map-listing-sidebar' ); ?>
				</div>
				<!-- Sidebar Section -->
			<?php endif; ?>

			<div class="property-boxes map-view clearfix">
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
							$property_array       = array (
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
								"size"        => $property_information['building_size'] . ' ' . $property_information['area_unit'],
								"pURL"        => esc_url( $property_information['url'] ),
							);
							array_push( $data['property'], $property_array );
							
							echo '
									<div class="property-box col-md-6 item">
										<div class="inner-box">
											<div class="t-sec">
												<div class="fav-compare">
													' . ( ! empty( $property_information['owner'] ) && $property_information['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $post_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
													<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $property_information['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
													<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $property_information['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
												</div>';
							if ( ! empty( $realty_house_opt['realty-house-property-img-slider'] ) && $property_information['gallery']['count'] > 1 )
							{
								echo '<div class="post-slider">';
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
									<a href="' . esc_url( $property_information['url'] ) . '" class="img-container">
										' . ( $property_information['gallery']['count'] > 0 ? $property_information['gallery']['img'][0]['code']['large'] : '' ) . '
									</a>';
							}
							
							if ( ! empty( $property_information['agents'] ) && $property_information['agents'] > 0 )
							{
								echo '
													<a href="' . esc_url( $property_information['agents']['agent'][0]['url'] ) . '" class="agent-img">
													' . ( ! empty( $property_information['agents']['agent'][0]['has_image'] ) ? wp_kses_post( $property_information['agents']['agent'][0]['img']['thumbnail'] ) : '<div class="no-image"></div>' ) . '
													</a>
												';
							}
							echo '
											</div>
											<div class="b-sec">
												<div class="title">' . esc_html( get_the_title() ) . '</div>
												<div class="price">' . wp_kses_post( $property_information['price']['generated'] ) . '</div>
											</div>
										</div>
									</div>
								';
						}
						wp_reset_postdata();
					}
					realty_house_tm_pagination( $new_properties );
					wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
				?>
			</div>
		</div>
		<div class="col-xs-5 property-listing-map">
			<div id="map"></div>
		</div>
	</section>
<?php
	
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_script( "google-map", $protocol . '://maps.googleapis.com/maps/api/js?libraries=places' . ( ! empty( $realty_house_opt['opt-map-api'] ) ? '&amp;key=' . esc_attr( $realty_house_opt['opt-map-api'] ) : '' ), array (), get_bloginfo( 'version' ), true );
	wp_enqueue_script( "google-map-requirements", REALTY_HOUSE_JS_PATH . 'googlemap-requirement.js', array (
		'jquery',
		'google-map'
	), get_bloginfo( 'version' ), true );
	
	wp_enqueue_script( "mcustom-scrollbar", REALTY_HOUSE_JS_PATH . 'jquery.mCustomScrollbar.concat.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
	
	get_footer();