<?php
	/**
	 *  archive-property.php
	 *  Property Listing ArchivePage
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	$data          = array (
		"property" => array ()
	);
	$tax_args      = array (
		'post_type'   => 'property',
		'post_status' => 'publish',
		'tax_query'   => array (
			array (
				'taxonomy' => get_query_var( 'taxonomy' ),
				'field'    => 'slug',
				'terms'    => array ( get_query_var( 'term' ) ),
			)
		)
	);
	
	$tax_query = new WP_Query( $tax_args );
	
	$page_title   = explode( ' | ', wp_title( " | ", false, "right" ) );
	$archive_tpml = ! empty( $realty_house_opt['realty-house-property-archive-template'] ) ? $realty_house_opt['realty-house-property-archive-template'] : 1;
?>
	<section class="page-content <?php echo ( $archive_tpml != 4 ) ? esc_attr( 'container' ) : '' ?>">

		<section class="main-container <?php echo ( is_active_sidebar( 'p-listing-sidebar' ) && ! empty( $realty_house_opt['realty-house-property-archive-sidebar'] ) && $archive_tpml != 4 ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<?php if ( $archive_tpml != 4 ): ?>
				<h3 class="rh-title">
					<span><?php echo esc_html( $page_title[0] ); ?></span>
				</h3>
				<?php
			endif;
				
				switch ( $archive_tpml )
				{
					case( 2 ):
						?>
						<div class="property-listing-main-container list">
							<div class="inner-container">
								<div class="property-boxes clearfix">
									<?php
										if ( $tax_query->have_posts() )
										{
											while ( $tax_query->have_posts() )
											{
												$tax_query->the_post();
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
													"size"        => $property_information['building_size'],
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
													<div class="price">' . wp_kses_post( $property_information['price']['generated'] ) . '</div>
													<div class="location">' . esc_html( $property_information['address'] ) . '</div>
													<div class="desc">' . esc_html( $property_information['description']['main'] ) . '</div>
													<div class="features">
														<div class="bed">
															<i class="realty-house-bedroom"></i>
															' . esc_html( $property_information['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house' ) . '
														</div>
														<div class="bath">
															<i class="realty-house-bathroom"></i>
															' . esc_html( $property_information['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house' ) . '
														</div>
														<div class="size">
															<i class="realty-house-size"></i>
															' . esc_html( $property_information['building_size'] ) . ' ' . esc_html( $property_information['area_unit'] ) . '
														</div>
													</div>
													<div class="b-sec">
														<div class="more-info">
															<a href="' . esc_url( $property_information['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house' ) . '</a>
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
									realty_house_tm_pagination();
									
									wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
								?>
							</div>
						</div>
						<?php
					break;
					case( 3 ):
						?>
						<div class="property-listing-main-container">
							<div class="inner-container">
								<div class="property-boxes row clearfix">
									<?php
										if ( $tax_query->have_posts() )
										{
											while ( $tax_query->have_posts() )
											{
												$tax_query->the_post();
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
													"size"        => $property_information['building_size'],
													"pURL"        => esc_url( $property_information['url'] ),
												);
												array_push( $data['property'], $property_array );
												echo '
										<div class="property-box col-sm-6 col-md-4 item">
											<div class="inner-box">';
												
												$i = 0;
												if ( ! empty( $property_information['tags'] ) )
												{
													foreach ( $property_information['tags'] as $index => $tag )
													{
														if ( $i > 0 )
														{
															continue;
														}
														echo '
														<div class="tag ' . esc_attr( $tag['class'] ) . '">' . esc_html( $tag['label'] ) . '</div>
													';
														
														$i ++;
													}
												}
												echo '
												<div class="title">' . esc_html( $property_information['title'] ) . '</div>
												<div class="price">' . wp_kses_post( $property_information['price']['generated'] ) . '</div>
												<div class="location">' . esc_html( $property_information['address'] ) . '</div>												
												<div class="b-sec">
													<div class="features">
														<div class="bed">
															<i class="realty-house-bedroom"></i>
															' . esc_html( $property_information['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house' ) . '
														</div>
														<div class="bath">
															<i class="realty-house-bathroom"></i>
															' . esc_html( $property_information['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house' ) . '
														</div>
														<div class="size">
															<i class="realty-house-size"></i>
															' . esc_html( $property_information['building_size'] ) . ' ' . esc_html( $property_information['area_unit'] ) . '
														</div>
													</div>
													<div class="fav-compare">
														' . ( ! empty( $property_information['owner'] ) && $property_information['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $post_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
														<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $property_information['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
														<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $property_information['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
													</div>
												</div>';
												if ( ! empty( $realty_house_opt['realty-house-property-img-slider'] ) && $property_information['gallery']['count'] > 1 )
												{
													echo '<div class="post-slider masonry">';
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
												echo '
												<div class="more-info">
													<a href="' . esc_url( $property_information['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house' ) . '</a>
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
									realty_house_tm_pagination();
									
									wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
								?>
							</div>
						</div>
						<?php
					break;
					case( 4 ):
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
										if ( $tax_query->have_posts() )
										{
											while ( $tax_query->have_posts() )
											{
												$tax_query->the_post();
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
													"size"        => $property_information['building_size'],
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
										realty_house_tm_pagination();
										wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
									?>
								</div>
							</div>
							<div class="col-xs-5 property-listing-map">
								<div id="map"></div>
							</div>
						</section>
						<?php
						wp_enqueue_script( "mcustom-scrollbar", REALTY_HOUSE_JS_PATH . 'jquery.mCustomScrollbar.concat.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
					
					break;
					default:
						?>
						<!-- Property Listing -->
						<div class="property-listing-main-container">
							<div class="inner-container">
								<div class="property-boxes clearfix">
									<?php
										if ( $tax_query->have_posts() )
										{
											while ( $tax_query->have_posts() )
											{
												$tax_query->the_post();
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
													"size"        => $property_information['building_size'],
													"pURL"        => esc_url( $property_information['url'] ),
												);
												array_push( $data['property'], $property_array );
												
												echo '
										<div class="property-box col-md-4 item">
											<div class="inner-box">';
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
												echo '
												<div class="bot-sec">
													<div class="title">' . esc_html( get_the_title() ) . '</div>
													<div class="price">' . wp_kses_post( $property_information['price']['generated'] ) . '</div>
													<div class="location">' . esc_html( $property_information['address'] ) . '</div>
													<div class="b-sec">
														<div class="more-info">
															<a href="' . esc_url( $property_information['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house' ) . '</a>
														</div>
														<div class="fav-compare">
															' . ( ! empty( $property_information['owner'] ) && $property_information['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $post_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
															<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $property_information['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
															<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $property_information['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
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
									realty_house_tm_pagination();
									
									wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
								?>
							</div>
						</div>
						<?php
					break;
				}
			
			
			?>


		</section>
		<?php if ( is_active_sidebar( 'p-listing-sidebar' ) && ! empty( $realty_house_opt['realty-house-property-archive-sidebar'] ) && $archive_tpml != 4 ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'p-listing-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();