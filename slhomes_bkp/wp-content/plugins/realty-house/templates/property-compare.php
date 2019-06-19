<?php
	/**
	 *  property-compare.php
	 *  Compare Properties
	 *  Template Name: Property Compare
	 */
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
	
	if ( ! empty( $_COOKIE['pCompare'] ) )
	{
		
		$properties_array          = explode( ',', $_COOKIE['pCompare'] );
		$property_in_compare_count = count( $properties_array );
		?>
		<section class="compare-main-container container">
			<div class="compare-inner-container clearfix">
				<div class="col-xs-5 col-md-3 title-list">
					<ul>
						<li class="title clearfix">
							<div class="inner-text">
								<?php esc_html_e( 'Comparing', 'realty-house-pl' ) ?>
								<div class="number"><?php echo esc_html( $property_in_compare_count ) ?></div>
								<?php esc_html_e( 'Properties', 'realty-house-pl' ) ?>
							</div>
						</li>
						<li><?php esc_html_e( 'Property Title', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Property Type', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Property Status', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Bedrooms', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Bathroom', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Building Size', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Lot Size', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Garages', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Construction Status', 'realty-house-pl' ) ?></li>
						<li><?php esc_html_e( 'Property Price', 'realty-house-pl' ) ?></li>
						<?php
							if ( ! empty( $realty_house_opt['realty-house-property-amenities'] ) )
							{
								foreach ( $realty_house_opt['realty-house-property-amenities'] as $amenity )
								{
									echo '<li>' . esc_html( $amenity ) . '</li>';
								}
							}
						?>
						<li><?php esc_html_e( 'Property Tags', 'realty-house-pl' ) ?></li>
					</ul>
				</div>
				<div class="col-xs-7 col-md-9 value-list">
					<div class="property-items-container clearfix property-in-<?php echo esc_attr( $property_in_compare_count ) ?> <?php echo ( $property_in_compare_count > 3 ) ? 'more-3' : '' ?>">
						<?php
							foreach ( $properties_array as $p_id ):
								$p_info = $property_info->property_info( $p_id );
								$rent_id = array_search( 'For Rent', $realty_house_opt['realty-house-property-status'] ) + 1;
								?>
								<div class="property-item">
									<ul>
										<li class="img clearfix">
											<div class="remove-compare-btn" title="<?php esc_html_e( 'Remove Property', 'realty-house-pl' ) ?>" data-p-id="<?php echo esc_html( $p_id ); ?>">
												<i class="fa fa-remove"></i></div>
											<a href="<?php echo esc_url( $p_info['url'] ) ?>">
												<figure>
													<?php echo( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ); ?>
													<figcaption><?php echo esc_html( $p_info['address'] ) ?></figcaption>
												</figure>
											</a>
										</li>
										<li><?php echo esc_html( $p_info['title'] ) ?></li>
										<li><?php echo esc_html( $p_info['type']['title'] ) ?></li>
										<li><?php echo esc_html( $p_info['status'] ) ?></li>
										<li><?php echo ! empty( $p_info['bedroom'] ) ? esc_html( $p_info['bedroom'] ) : '-' ?></li>
										<li><?php echo ! empty( $p_info['bathroom'] ) ? esc_html( $p_info['bathroom'] ) : '-' ?></li>
										<li><?php echo ! empty( $p_info['building_size'] ) ? esc_html( number_format( $p_info['building_size'] ) ) . ' ' . esc_html( $p_info['area_unit'] ) : '-' ?> </li>
										<li><?php echo ! empty( $p_info['lot_size'] ) ? esc_html( number_format( $p_info['lot_size'] ) ) . ' ' . esc_html( $p_info['area_unit'] ) : '-' ?> </li>
										<li><?php echo ! empty( $p_info['parking'] ) ? esc_html( $p_info['parking'] ) : '-' ?></li>
										<li><?php echo esc_html( $p_info['construction_status']['title'] ) ?></li>
										<li class="price"><?php echo wp_kses_post( $p_info['price']['generated'] ); ?></li>
										<?php
											if ( ! empty( $realty_house_opt['realty-house-property-amenities'] ) )
											{
												foreach ( $realty_house_opt['realty-house-property-amenities'] as $amenity )
												{
													if ( count( $p_info['amenities'] ) > 0 )
													{
														$is_has_amenity = in_array( $amenity, $p_info['amenities'] );
														echo '<li ' . ( ! empty( $is_has_amenity ) ? ' class="active"' : '' ) . '><i class="fa fa-check-circle"></i></li>';
													}
													else
													{
														echo '<li><i class="fa fa-check-circle"></i></li>';
													}
												}
											}
										?>
										<li>
											<?php
												$i = 0;
												if ( ! empty( $p_info['tags'] ) )
												{
													foreach ( $p_info['tags'] as $index => $tag )
													{
														if ( $i > 0 )
														{
															continue;
														}
														echo '<div class="tag ' . esc_attr( $tag['class'] ) . '">' . esc_html( $tag['label'] ) . '</div>';
														$i ++;
													}
												}
												else
												{
													echo '&nbsp;';
												}
											?>
										</li>
									</ul>
								</div>
							<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	else
	{
		?>
		<div id="no-property-comparision" class="container">
			<h2><span><?php esc_html_e( 'Nothing to Compare :(', 'realty-house-pl' ) ?></span></h2>
			<div class="subtitle"><?php esc_html_e( 'There is not any properties to compare, please choose the properties from the listings', 'realty-house-pl' ) ?></div>
		</div>
		<?php
	}
	get_footer();