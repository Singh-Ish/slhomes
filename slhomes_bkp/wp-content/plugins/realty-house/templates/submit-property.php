<?php
	/**
	 *  submit-property.php
	 *  Submit Property Page
	 *  Template Name: Submit Property
	 */
	
	
	global $post, $realty_house_opt, $current_user;
	get_header();
	$currency_info = new realty_house_currency();
	$curr_info     = $currency_info->get_current_currency();
	$area_unit     = ! empty( $realty_house_opt['realty-house-area-unit'] ) ? strtoupper( $realty_house_opt['realty-house-area-unit'] ) : esc_html__( 'SQF', 'realty-house-pl' );
	if ( ! is_user_logged_in() )
	{
		?>
		<section class="page-content container login-form-page">
<div style="width: 46%;padding: 1em;border: 1px solid #ddd;margin:0 auto;">
			<h3 class="rh-title"><span><?php esc_html_e( 'Login Form', 'realty-house-pl' ) ?></span></h3>
			<?php
				// Login form arguments.
				$args = array (
					'echo'           => true,
					'form_id'        => 'loginform',
					'label_username' => esc_html__( 'Username', 'realty-house-pl' ),
					'label_password' => esc_html__( 'Password', 'realty-house-pl' ),
					'label_log_in'   => esc_html__( 'Log In', 'realty-house-pl' ),
					'id_username'    => 'user_login',
					'id_password'    => 'user_pass',
					'id_submit'      => 'wp-submit',
					'remember'       => false,
					'value_username' => null,
				);
				
				// Calling the login form.
				wp_login_form( $args );
			?>
<a href="http://slhomes.in/forgot-password/">Forgot Password</a><br/>
<a id="login-register-url" href="#register-form">Already Member ?</a><br/>
<h3 style="text-align:center; margin:0 auto;">OR</h3>
<?php echo do_shortcode('[userpro_social_connect width="464px"]'); ?>
</div>
		</section>
		<?php
	}
	else
	{

		$is_addministrator = in_array( 'administrator', $current_user->roles );
		
		?>
		<section class="page-content container">
			<section class="main-container <?php echo is_active_sidebar( 'p-submit-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
				<div id="success-property-submit">
					<h2><span><?php esc_html_e( 'Submission is Complete :)', 'realty-house-pl' ) ?></span></h2>
					<div class="subtitle"><?php esc_html_e( 'Your property will be available after confirmation of administration of website.', 'realty-house-pl' ) ?></div>
				</div>

				<form id="submit-property-form" action="#" name="submit-property-form">
					<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'add_property_security_code' ) ?>">
					<?php
						if ( empty( $is_addministrator ) ) :
							
							$agent_post_args = array (
								'post_type'   => 'agent',
								'post_status' => array (
									'publish',
									'pending',
									'draft',
									'auto-draft',
									'future',
									'private',
									'inherit',
									'trash'
								),
								
							);
							
						
							$agent_id = get_current_user_id();
							?>
							<input type="hidden" name="agent_id" value="<?php echo esc_attr( $agent_id ) ?>">
						<?php endif; ?>
					<h3 class="rh-title"><span><?php esc_html_e( 'Add a Property', 'realty-house-pl' ) ?></span></h3>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property Setting', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table id="p-main-info">
								<tr>
									<td class="title">
										<label for="p_title"><?php esc_html_e( 'Property Title', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<input type="text" id="p_title" name="p_title" data-required="required" placeholder="<?php esc_html_e( 'Property Title', 'realty-house-pl' ) ?>">
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title"><?php esc_html_e( 'Property Category', 'realty-house-pl' ) ?>
										<span>*</span>:
									</td>
									<td>
										<div class="text"><?php esc_html_e( 'Select a property type and its status that best describes this property.', 'realty-house-pl' ) ?></div>
										<div class="select-boxes-container clearfix">
											<select id="p-status" name="p_status" data-required="required">
												<?php
													if ( ! empty( $realty_house_opt['realty-house-property-status'] ) )
													{
														foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status_item )
														{
															echo '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $property_status_item ) . '</option>';
														}
													}
												?>
											</select>
											<select id="p-type" name="p_type" data-required="required">
												<?php
													if ( ! empty( $realty_house_opt['realty-house-property-type'] ) )
													{
														foreach ( $realty_house_opt['realty-house-property-type'] as $index => $property_type_item )
														{
															$property_type_item_sections = explode( '---', $property_type_item );
															echo '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $property_type_item_sections[0] ) . '</option>';
														}
													}
												?>
											</select>
										</div>
										<div class="p-dynamic-text">
											<i class="fa fa-check"></i> <?php esc_html_e( 'You have selected', 'realty-house-pl' ) ?>
											<span class="p-type-text">-</span> >
											<span class="p-status-text">-</span>. <?php esc_html_e( 'Continue and complete the form below.', 'realty-house-pl' ) ?>
										</div>
										<div class="alert alert-danger"><?php esc_html_e( 'Please select the above categories.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_bedrooms"><?php esc_html_e( 'Bedrooms', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<select name="p_bedrooms" id="p_bedrooms" placeholder="<?php echo esc_attr( __( 'How Many Bedrooms?', 'realty-house-pl' ) ) ?>" data-required="required">
											<option value=""></option>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="6">+10</option>
										</select>
										<div class="alert alert-danger"><?php esc_html_e( 'Please select how many bedrooms your property has.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_bath"><?php esc_html_e( 'Bathroom', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<select name="p_bath" id="p_bath" placeholder="<?php echo esc_attr( __( 'How Many Bathroom?', 'realty-house-pl' ) ) ?>" data-required="required">
											<option value=""></option>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="6">+10</option>
										</select>
										<div class="alert alert-danger"><?php esc_html_e( 'Please select how many bathroom your property has.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>


								<tr>
									<td class="title">
										<label for="p_build_size"><?php esc_html_e( 'Total Building Size', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<input type="text" name="p_build_size" id="p_build_size" placeholder="<?php echo esc_html( $area_unit ); ?>" data-required="required">
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_lot_size"><?php esc_html_e( 'Total Lot Size', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<input type="text" name="p_lot_size" id="p_lot_size" placeholder="<?php echo esc_html( $area_unit ); ?>" data-required="required">
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_garages"><?php esc_html_e( 'Garages', 'realty-house-pl' ) ?></label> :
									</td>
									<td>
										<select name="p_garages" id="p_garages" placeholder="<?php echo esc_attr( __( 'How Many Garages?', 'realty-house-pl' ) ) ?>">
											<option value=""></option>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">+5</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="title"><?php esc_html_e( 'Construction Status', 'realty-house-pl' ) ?> :</td>
									<td class="p-construction-field-c p-t">
										<div class="hsq-radio check-box-container">
											<label><input type="radio" name="p_construction_status" checked="checked" value="1"><span></span> <?php esc_html_e( 'Existing', 'realty-house-pl' ) ?>
											</label>
										</div>
										<div class="hsq-radio check-box-container">
											<label><input type="radio" name="p_construction_status" value="2"><span></span> <?php esc_html_e( 'Under Construction/Proposed', 'realty-house-pl' ) ?>
											</label>
										</div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_keywords"><?php esc_html_e( 'Property Keywords', 'realty-house-pl' ) ?></label> :
									</td>
									<td>
										<input type="text" id="p_keywords" name="p_keywords" placeholder="<?php echo esc_attr( __( 'Separate your keywords with comma', 'realty-house-pl' ) ) ?>">
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p_desc"><?php esc_html_e( 'Property Description', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<textarea name="p_desc" id="p_desc" data-required="required"></textarea>
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Price Details', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table>
								<tr>
									<td class="title">
										<label for="p-price"><?php esc_html_e( 'Property Price', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<input type="number" name="p_price" id="p-price" placeholder="<?php echo esc_attr( $curr_info['symbol'] ) ?>" data-required="required">
										<span class="price-separated-container">
											<?php
												echo ( $curr_info['position'] === '1' ) ? '<i class="symbol">' . esc_html( $curr_info['symbol'] ) . '</i><span class="digit"></span>' : '<span class="digit"></span><i class="symbol">' . esc_html( $curr_info['symbol'] ) . '</i>';
											?>
										</span>
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
										<div class="hsq-checkbox check-box-container">
											<label for="p_price_privacy">
												<input type="checkbox" value="1" id="p_price_privacy" name="p_price_privacy">
												<span></span>
												<?php esc_html_e( 'Keep Price Confidential', 'realty-house-pl' ) ?>
											</label>
										</div>
									</td>
								</tr>
								<tr class="p-price-type-container">
									<td class="title">
										<label for="p_price_type"><?php esc_html_e( 'Price type', 'realty-house-pl' ) ?></label> :
									</td>
									<td>
										<select name="p_price_type" id="p_price_type">
											<option value="1"><?php esc_html_e( 'Per Month', 'realty-house-pl' ) ?></option>
											<option value="2"><?php esc_html_e( 'Per Week', 'realty-house-pl' ) ?></option>
											<option value="3"><?php esc_html_e( 'Per Night', 'realty-house-pl' ) ?></option>
											<option value="4"><?php esc_html_e( 'Annual', 'realty-house-pl' ) ?></option>
										</select>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property Location', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table id="property-location">
								<tr>
									<td class="title">
										<label for="p-address"><?php esc_html_e( 'Address', 'realty-house-pl' ) ?></label>
										<span>*</span>:
									</td>
									<td>
										<input type="text" placeholder="<?php echo esc_attr( __( 'Address', 'realty-house-pl' ) ) ?>" name="p_address" id="p-address" data-required="required">
										<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title"></td>
									<td>
										<div id="p-map"></div>
										<div class="text"><?php esc_html_e( 'Drag the marker and put it on your property location.', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p-long"><?php esc_html_e( 'Longitude', 'realty-house-pl' ) ?> </label> :
									</td>
									<td>
										<input type="text" id="p-long" name="p_long" readonly/>
									</td>
								</tr>
								<tr>
									<td class="title">
										<label for="p-lat"><?php esc_html_e( 'Latitude', 'realty-house-pl' ) ?> </label> :
									</td>
									<td>
										<input type="text" id="p-lat" name="p_lat" readonly/>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Neighborhood', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="neighborhood-table">
								<tr>
									<td class="title"></td>
									<td class="clearfix">
										<ul>
											<li id="neighborhood-tmpl">
												<span class="sort"><i class="fa fa-arrows"></i></span>
												<div class="input-containers">
													<div class="input-box">
														<input type="text" name="neighborhood_title" placeholder="<?php esc_html_e( 'Title', 'realty-house-pl' ) ?>"/>
													</div>
													<div class="input-box">
														<input type="text" name="neighborhood_distance" placeholder="<?php esc_html_e( 'Distance', 'realty-house-pl' ) ?>"/>
													</div>
												</div>
												<span class="delete"><i class="fa fa-remove"></i></span>
											</li>
										</ul>
										<div class="btn btn-default btn-sm" id="add-neighborhood"><?php esc_html_e( 'Add Neighborhood', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Nearby Schools', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="nearby-schools-table">
								<tr>
									<td class="title"></td>
									<td class="clearfix">
										<ul>
											<li id="nearby-schools-tmpl">
												<span class="sort"><i class="fa fa-arrows"></i></span>
												<div class="input-containers">
													<div class="input-box">
														<input type="number" name="nearby_schools_rate" placeholder="<?php esc_html_e( 'Rating', 'realty-house-pl' ) ?>"/>
													</div>
													<div class="input-box">
														<input type="text" name="nearby_schools_name" placeholder="<?php esc_html_e( 'School\'s Name', 'realty-house-pl' ) ?>"/>
													</div>
													<div class="input-box">
														<input type="text" name="nearby_schools_grade" placeholder="<?php esc_html_e( 'School\'s Grade', 'realty-house-pl' ) ?>"/>
													</div>
													<div class="input-box">
														<input type="text" name="nearby_schools_distance" placeholder="<?php esc_html_e( 'Distance', 'realty-house-pl' ) ?>"/>
													</div>
												</div>
												<span class="delete"><i class="fa fa-remove"></i></span>
											</li>
										</ul>
										<div class="btn btn-default btn-sm" id="add-nearby-schools"><?php esc_html_e( 'Add School', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Amenities', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="amenities-table">
								<tr>
									<td class="title"></td>
									<td class="clearfix">
										<?php
											if ( ! empty( $realty_house_opt['realty-house-property-amenities'] ) )
											{
												foreach ( $realty_house_opt['realty-house-property-amenities'] as $index => $field )
												{
													// begin a table row with
													echo '
													<div class="hsq-checkbox check-box-container col-xs-6">
														<label for="p-amenities-' . esc_attr( $index ) . '">
															<input type="checkbox" value="' . esc_attr( $index ) . '" id="p-amenities-' . esc_attr( $index ) . '" name="amenity">
															<span></span>
															' . esc_html( $field ) . '
														</label>
													</div>';
												} // end foreach
											}
										?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property Images', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="gallery-table">
								<tr>
									<td class="title"></td>
									<td>
										<div class="uploader-container">
											<div id="gallery-uploader" class="clearfix"></div>
											<div class="btn-container">
												<div class="btn btn-sm add-images-btn" id="add-gallery"><?php esc_html_e( 'Brows...', 'realty-house-pl' ) ?></div>
												<input type="hidden" name="p_gallery" id="p-gallery">
											</div>
										</div>
										<div class="text">
											<i class="fa fa-exclamation-triangle"></i> <?php esc_html_e( 'Listings with photos are viewed 5 times more than those without.', 'realty-house-pl' ) ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Facts', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i>
						</div>
						<div class="content">
							<table class="facts-table">
								<tr>
									<td class="title"></td>
									<td class="clearfix">
										<ul>
											<li id="facts-tmpl">
												<span class="sort"><i class="fa fa-arrows"></i></span>
												<div class="input-containers">
													<div class="input-box">
														<input type="text" name="facts" placeholder="<?php esc_html_e( 'Insert a fact', 'realty-house-pl' ) ?>"/>
													</div>
												</div>
												<span class="delete"><i class="fa fa-remove"></i></span>
											</li>
										</ul>
										<div class="btn btn-default btn-sm" id="add-facts"><?php esc_html_e( 'Add Fact', 'realty-house-pl' ) ?></div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property Tags', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="property-tags">
								<tr>
									<td class="clearfix">
										<div class="tags-box-container">
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-like"></i><?php esc_html_e( 'New', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_new_tag">
														<input type="checkbox" value="1" id="p_new_tag" name="p_new_tag">
														<span></span>
														<?php esc_html_e( 'New', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-hot"></i><?php esc_html_e( 'Hot Offer', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_hot_offer">
														<input type="checkbox" value="1" id="p_hot_offer" name="p_hot_offer">
														<span></span>
														<?php esc_html_e( 'Hot Offer', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-bookmark-star"></i><?php esc_html_e( 'Featured', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_featured">
														<input type="checkbox" value="1" id="p_featured" name="p_featured">
														<span></span>
														<?php esc_html_e( 'Featured', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-price-tag"></i><?php esc_html_e( 'Price Cut', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_price_cut">
														<input type="checkbox" value="1" id="p_price_cut" name="p_price_cut">
														<span></span>
														<?php esc_html_e( 'Price Cut', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-home-2"></i><?php esc_html_e( 'Open House', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_open_house">
														<input type="checkbox" value="1" id="p_open_house" name="p_open_house">
														<span></span>
														<?php esc_html_e( 'Open House', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
											<div class="tags-box">
												<div class="tags-icon">
													<i class="realty-house-museum"></i><?php esc_html_e( 'Foreclosure', 'realty-house-pl' ) ?>
												</div>
												<div class="hsq-checkbox check-box-container">
													<label for="p_foreclosure">
														<input type="checkbox" value="1" id="p_foreclosure" name="p_foreclosure">
														<span></span>
														<?php esc_html_e( 'Foreclosure', 'realty-house-pl' ) ?>
													</label>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property FloorPlans', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="floor-plan-table">
								<tr>
									<td class="title"></td>
									<td>
										<div class="uploader-container">
											<div id="floor-plan-uploader" class="clearfix"></div>
											<div class="btn-container">
												<div class="btn btn-sm add-images-btn" id="add-floor-plan"><?php esc_html_e( 'Brows...', 'realty-house-pl' ) ?></div>
												<input type="hidden" name="p_floor_plan" id="p-floor-plan">
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Property Attachment', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="attachment-table">
								<tr>
									<td class="title"></td>
									<td>
										<div class="uploader-container">
											<div id="attachment-uploader" class="clearfix"></div>
											<div class="btn-container">
												<div class="btn btn-sm add-images-btn" id="add-attachment"><?php esc_html_e( 'Brows...', 'realty-house-pl' ) ?></div>
												<input type="hidden" name="p_attachment" id="p-attachment">
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php if ( ! empty( $is_addministrator ) ) : ?>
						<div class="details-boxes">
							<div class="title"><?php esc_html_e( 'Property Agents', 'realty-house-pl' ) ?>
								<i class="fa fa-angle-down"></i></div>
							<div class="content">
								<table class="agents-table">
									<tr>
										<td class="title">
											<label for="p_agent"><?php esc_html_e( 'Property Agents', 'realty-house-pl' ) ?></label>
											<span>*</span>:
										</td>
										<td>
											<?php
												$property_agent_arg = array (
													'posts_per_page' => 1000,
													'post_type'      => 'agent',
													'post_status'    => 'publish'
												);
												$property_agent_arr = get_posts( $property_agent_arg );
												
												
												echo '<select name="p_agent" id="p_agent" multiple="multiple" data-required="required">';
												foreach ( $property_agent_arr as $property_agent )
												{
													echo '<option value="' . $property_agent->ID . '">' . $property_agent->post_title . '</option>';
												}
												echo '</select>';
											?>
											<div class="alert alert-danger"><?php esc_html_e( 'Please select at-least one agent.', 'realty-house-pl' ) ?></div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					<?php endif; ?>
					<div class="details-boxes">
						<div class="title"><?php esc_html_e( 'Listing Agreement', 'realty-house-pl' ) ?>
							<i class="fa fa-angle-down"></i></div>
						<div class="content">
							<table class="agreement-table">
								<tr>
									<td>
										<div class="hsq-checkbox check-box-container">
											<label for="p_agreement">
												<input type="checkbox" value="1" id="p_agreement" name="p_agreement" data-required="required">
												<span></span>
												<i>*</i> <?php esc_html_e( 'I acknowledge that I am authorized to post this listing and to the best of my knowledge all of the information provided is accurate.', 'realty-house-pl' ) ?>
											</label>
											<div class="alert alert-danger"><?php esc_html_e( 'Please accept the agreement of website.', 'realty-house-pl' ) ?></div>
										</div>
										<div class="btn-container">
											<input type="submit" class="btn btn-sm btn-default" value="<?php esc_html_e( 'Send', 'realty-house-pl' ); ?>">
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div id="form-message-box"></div>
				</form>
			</section>
			<?php if ( is_active_sidebar( 'p-submit-sidebar' ) ): ?>
				<!-- Sidebar Section -->
				<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
					<?php dynamic_sidebar( 'p-submit-sidebar' ); ?>
				</aside>
				<!-- Sidebar Section -->
			<?php endif; ?>
		</section>
		
		<?php
	}
	get_footer();