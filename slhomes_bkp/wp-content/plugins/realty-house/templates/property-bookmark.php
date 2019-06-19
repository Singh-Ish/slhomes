<?php
	/**
	 *  property-bookmark.php
	 *  Property Bookmark View
	 *  Template Name: Property Bookmark
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	$data          = array (
		"property" => array ()
	);
?>
	<section class="page-content container">
		<section class="main-container <?php echo is_active_sidebar( 'p-listing-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<h3 class="rh-title"><span><?php esc_html_e( 'Bookmarked Properties', 'realty-house-pl' ) ?></span></h3>
			<?php
				if ( ! empty( $_COOKIE['pBookmark'] ) )
				{
					?>
					<div class="property-listing-main-container list">
						<div class="inner-container">
							<div class="property-boxes clearfix">
								<?php
									$properties_array = explode( ',', trim( $_COOKIE['pBookmark'], ',' ) );
									
									foreach ( $properties_array as $p_id )
									{
										$p_info = $property_info->property_info( $p_id );
										
										$property_array = array (
											"pId"         => $p_id,
											"longitude"   => $p_info['long'],
											"latitude"    => $p_info['lat'],
											"pType"       => ! empty( $p_info['type']['icon'] ) ? $p_info['type']['icon'] : '',
											"title"       => get_the_title(),
											"price"       => $p_info['price']['generated'],
											"pImage"      => ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ),
											"location"    => $p_info['address'],
											"description" => $p_info['description']['main'],
											"bedroom"     => $p_info['bedroom'],
											"bathroom"    => $p_info['bathroom'],
											"size"        => $p_info['building_size'] . ' ' . $p_info['area_unit'],
											"pURL"        => esc_url( $p_info['url'] ),
										);
										array_push( $data['property'], $property_array );
										
										echo '
										<div class="property-box item">										
											<div class="inner-box clearfix">';
										if ( ! empty( $realty_house_opt['realty-house-property-img-slider'] ) && $p_info['gallery']['count'] > 1 )
										{
											echo '<div class="post-slider list-view">';
											$p_slider_i = 1;
											foreach ( $p_info['gallery']['img'] as $image_item )
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
												<a href="' . esc_url( $p_info['url'] ) . '" class="img-container col-md-5">
													' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
												</a>';
										}
										echo '
												<div class="bot-sec col-md-7">
													<div class="title">' . esc_html( $p_info['title'] ) . '</div>
													<div class="fav-compare">
														' . ( ! empty( $p_info['owner'] ) && $p_info['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $p_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
														<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $p_info['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $p_id ) . '"></a>
														<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $p_info['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $p_id ) . '"></a>
													</div>
													<div class="price">' . wp_kses_post( $p_info['price']['generated'] ) . '</div>
													<div class="location">' . esc_html( $p_info['address'] ) . '</div>
													<div class="desc">' . esc_html( $p_info['description']['main'] ) . '</div>
													<div class="features">
														<div class="bed">
															<i class="realty-house-bedroom"></i>
															' . esc_html( $p_info['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house-pl' ) . '
														</div>
														<div class="bath">
															<i class="realty-house-bathroom"></i>
															' . esc_html( $p_info['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house-pl' ) . '
														</div>
														<div class="size">
															<i class="realty-house-size"></i>
															' . esc_html( $p_info['building_size'] ) . ' ' . esc_html( $p_info['area_unit'] ) . '
														</div>
													</div>
													<div class="b-sec">
														<div class="more-info">
															<a href="' . esc_url( $p_info['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house-pl' ) . '</a>
														</div>
													</div>
												</div>
											</div>
										</div>									
									';
										
									}
								?>
							</div>
							<?php
								wp_localize_script( 'realty-house-template-js', 'rawPropertyData', json_encode( $data ) );
							?>
						</div>
					</div>
					<?php
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