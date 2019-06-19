<?php
	/**
	 *  search-property.php
	 *  Property Search Result Page
	 *  Template Name: Property Search Result
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	$data          = array (
		"property" => array ()
	);
	$page_title    = explode( ' | ', wp_title( " | ", false, "right" ) );
	$search_tpml   = ! empty( $realty_house_opt['realty-house-property-search-template'] ) ? $realty_house_opt['realty-house-property-search-template'] : 1;
	
	$location             = ! empty( $_GET['location'] ) ? filter_var( $_GET['location'], FILTER_SANITIZE_STRING ) : '';
	$property_status      = ! empty( $_GET['property-status'] ) ? filter_var( $_GET['property-status'], FILTER_VALIDATE_INT ) : '';
	$property_type        = ! empty( $_GET['property-type'] ) ? filter_var( $_GET['property-type'], FILTER_VALIDATE_INT ) : '';
	$bedroom              = ! empty( $_GET['bedroom'] ) ? filter_var( $_GET['bedroom'], FILTER_VALIDATE_INT ) : '';
	$bathroom             = ! empty( $_GET['bathroom'] ) ? filter_var( $_GET['bathroom'], FILTER_VALIDATE_INT ) : '';
	$garages              = ! empty( $_GET['garages'] ) ? filter_var( $_GET['garages'], FILTER_VALIDATE_INT ) : '';
	$keywords             = ! empty( $_GET['keywords'] ) ? filter_var( $_GET['keywords'], FILTER_SANITIZE_STRING ) : '';
	$price                = ! empty( $_GET['price'] ) ? filter_var( $_GET['price'], FILTER_SANITIZE_STRING ) : '';
	$price_array          = explode( '-', $price );
	$property_amenities   = ! empty( $_GET['property_amenities'] ) ? $_GET['property_amenities'] : '';
	$property_new_tag     = ! empty( $_GET['property_new_tag'] ) ? esc_html( 'on' ) : '';
	$property_hot_offer   = ! empty( $_GET['property_hot_offer'] ) ? esc_html( 'on' ) : '';
	$property_featured    = ! empty( $_GET['property_featured'] ) ? esc_html( 'on' ) : '';
	$property_price_cut   = ! empty( $_GET['property_price_cut'] ) ? esc_html( 'on' ) : '';
	$property_open_house  = ! empty( $_GET['property_open_house'] ) ? esc_html( 'on' ) : '';
	$property_foreclosure = ! empty( $_GET['property_foreclosure'] ) ? esc_html( 'on' ) : '';
	
	$search_query_param = array ( 'relation' => 'AND' );
	
	if ( ! empty( $location ) )
	{
		$search_query_param[] = array (
			'key'     => 'property_address',
			'value'   => $location,
			'compare' => 'LIKE'
		);
	}
	
	if ( ! empty( $property_status ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_property_status',
			'value' => $property_status
		);
	}
	
	if ( ! empty( $property_type ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_property_type',
			'value' => $property_type
		);
	}
	
	if ( ! empty( $bedroom ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_bedroom',
			'value' => $bedroom
		);
	}
	
	if ( ! empty( $bathroom ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_bathroom',
			'value' => $bathroom
		);
	}
	
	if ( ! empty( $garages ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_parking',
			'value' => $garages
		);
	}
	
	if ( ! empty( $keywords ) )
	{
		$search_query_param[] = array (
			'key'     => 'property_keywords',
			'value'   => $keywords,
			'compare' => 'LIKE'
		);
	}
	
	if ( ! empty( $price_array ) )
	{
		$search_query_param[] = array (
			'key'     => 'property_price',
			'value'   => $price_array,
			'type'    => 'numeric',
			'compare' => 'BETWEEN',
		);
	}
	
	if ( ! empty( $property_amenities ) )
	{
		$search_query_param[] = array (
			'key'     => 'property_amenities',
			'value'   => $property_amenities,
			'compare' => 'IN'
		);
	}
	
	if ( ! empty( $property_new_tag ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_new_tag',
			'value' => $property_new_tag
		);
	}
	
	if ( ! empty( $property_hot_offer ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_hot_offer',
			'value' => $property_hot_offer
		);
	}
	
	if ( ! empty( $property_featured ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_featured',
			'value' => $property_featured
		);
	}
	
	if ( ! empty( $property_price_cut ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_price_cut',
			'value' => $property_price_cut
		);
	}
	
	if ( ! empty( $property_open_house ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_open_house',
			'value' => $property_open_house
		);
	}
	
	if ( ! empty( $property_foreclosure ) )
	{
		$search_query_param[] = array (
			'key'   => 'property_foreclosure',
			'value' => $property_foreclosure
		);
	}
	
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	
	$property_search_args = array (
		'post_type'   => 'property',
		'post_status' => 'publish',
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'paged'       => $paged,
		'meta_query'  => $search_query_param
	);
	
	$property_search_result = new WP_Query( $property_search_args );
?>
	<section class="page-content <?php echo ( $search_tpml != 4 ) ? esc_attr( 'container' ) : '' ?>">

		<section class="main-container <?php echo ( is_active_sidebar( 'p-search-sidebar' ) && ! empty( $realty_house_opt['realty-house-property-search-sidebar'] ) && $search_tpml != 4 ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<?php if ( $search_tpml != 4 ): ?>
				<h3 class="rh-title">
					<span><?php echo esc_html( $page_title[0] ); ?></span>
					<?php
						if ( is_user_logged_in() )
						{
							?>
							<a href="#save-search-form" id="save-search-btn" class="pull-right">
								<span class="info-content">
									<span class="title"><?php esc_html_e( 'Save Search', 'realty-house' ) ?></span>
									<?php esc_html_e( 'You can save your search result for future use. All of your saved search will be listed in saved search menu.', 'realty-house' ) ?>
								</span>
								<i class="fa fa-save"></i>
							</a>
							<?php
						}
					?>
				</h3>
				<?php
			endif;
				
				if ( is_user_logged_in() )
				{
					$current_user = wp_get_current_user();
					?>
					<div id="save-search-form" class="mfp-hide">
						<h3 class="rh-title"><span><?php echo esc_html__( 'Save Search', 'realty-house' ) ?></span></h3>
						<form class="save-search-form-box" action="#">
							<div class="error-box"></div>
							<div class="row-fields">
								<input type="text" name="search-title" placeholder="<?php esc_attr_e( "Title", 'realty-house' ) ?>"/>
							</div>
							<div class="row-button-container">
								<input class="btn btn-default" value="<?php esc_attr_e( "save", 'realty-house' ) ?>" type="submit"/>
							</div>
							<div class="loader"></div>
							<input type="hidden" name="current-user" value="<?php echo esc_attr( $current_user->ID ) ?>">
							<?php wp_nonce_field( 'ajax-save-search-nonce', 'security-save-search' ); ?>
						</form>
					</div>
					<?php
				}
				
				if ( $property_search_result->have_posts() )
				{
					switch ( $search_tpml )
					{
						case( 2 ):
							?>
							<div class="property-listing-main-container list">
								<div class="inner-container">
									<div class="property-boxes clearfix">
										<?php
											
											while ( $property_search_result->have_posts() )
											{
												$property_search_result->the_post();
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
													<div class="price">' . balanceTags( $property_information['price']['generated'] ) . '</div>
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
										?>
									</div>
									<?php
										realty_house_tm_pagination( $property_search_result );
										$encode_data = json_encode( $data );
										wp_localize_script( 'realty-house-template-js', 'rawPropertyData', ( ! empty( $encode_data ) ? $encode_data : '' ) );
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
											
											while ( $property_search_result->have_posts() )
											{
												$property_search_result->the_post();
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
													"size"        => $property_information['building_size'] . ' ' . $property_information['building_size'],
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
										?>
									</div>
									<?php
										realty_house_tm_pagination( $property_search_result );
										$encode_data = json_encode( $data );
										wp_localize_script( 'realty-house-template-js', 'rawPropertyData', ( ! empty( $encode_data ) ? $encode_data : '' ) );
									?>
								</div>
							</div>
							<?php
						break;
						case( 4 ):
							?>
							<section id="property-listing-map" class="clearfix">
								<div class="col-xs-7 property-listing-container">
									
									<?php if ( is_active_sidebar( 'p-map-search-sidebar' ) ): ?>
										<!-- Sidebar Section -->
										<div id="side-bar" class="property-listing-map-side-bar">
											<?php dynamic_sidebar( 'p-map-search-sidebar' ); ?>
										</div>
										<!-- Sidebar Section -->
									<?php endif; ?>

									<div class="property-boxes map-view clearfix">
										<?php
											
											while ( $property_search_result->have_posts() )
											{
												$property_search_result->the_post();
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
													"size"        => $property_information['building_size'] . ' ' . $property_information['building_size'],
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
																			' . ( ! empty( $property_information['agents']['agent'][0]['has_image'] ) ? balancetags( $property_information['agents']['agent'][0]['img']['thumbnail'] ) : '<div class="no-image"></div>' ) . '
																			</a>
																	';
												}
												echo '
															</div>
															<div class="b-sec">
																<div class="title">' . esc_html( get_the_title() ) . '</div>
																<div class="price">' . balanceTags( $property_information['price']['generated'] ) . '</div>
															</div>
														</div>
													</div>
												';
											}
											wp_reset_postdata();
											realty_house_tm_pagination( $property_search_result );
											$encode_data = json_encode( $data );
											wp_localize_script( 'realty-house-template-js', 'rawPropertyData', ( ! empty( $encode_data ) ? $encode_data : '' ) );
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
											while ( $property_search_result->have_posts() )
											{
												$property_search_result->the_post();
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
													"size"        => $property_information['building_size'] . ' ' . $property_information['building_size'],
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
																<i class="fa fa-inr"></i><div class="price"> ' . balanceTags( $property_information['price']['value'] ) . '</div>
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
										?>
									</div>
									<?php
										realty_house_tm_pagination( $property_search_result );
										$encode_data = json_encode( $data );
										wp_localize_script( 'realty-house-template-js', 'rawPropertyData', ( ! empty( $encode_data ) ? $encode_data : '' ) );
									?>
								</div>
							</div>
							<?php
						break;
					}
				}
				else
				{
					?>
					<div id="no-property-result">
						<h2><span><?php esc_html_e( 'Nothing Found :(', 'realty-house-pl' ) ?></span></h2>
						<div class="subtitle"><?php esc_html_e( 'There is not any properties to be matched with your criteria', 'realty-house-pl' ) ?></div>
					</div>
					<?php
				}
			?>
		</section>
		<?php if ( is_active_sidebar( 'p-search-sidebar' ) && ! empty( $realty_house_opt['realty-house-property-search-sidebar'] ) && $search_tpml != 4 ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'p-search-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();