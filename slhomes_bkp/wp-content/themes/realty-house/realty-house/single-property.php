<?php
	// single-property.php
	// Property Details Page
	
	global $post, $realty_house_opt;
	get_header();
	
	$property_info = new realty_house_get_info();
?>
<?php echo do_shortcode('[realty-house-search type="simple" p_location="on" p_status="on" p_type="on" bedrooms="on" bathrooms="on" price="on" max_price="1000000000" amenities="on" ]'); ?>
	<section class="page-content container">
		<section class="main-container <?php echo is_active_sidebar( 'p-details-sidebar' ) || ! empty( $realty_house_opt['realty-house-property-contact-agent'] ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
		
			<?php
				if ( have_posts() )
				{
					global $realty_house_opt;
					while ( have_posts() )
					{
						the_post();
						$p_id   = get_the_ID();
						$p_info = $property_info->property_info( $p_id );
						
						if ( ! empty( $realty_house_opt['realty-house-property-details-gallery'] ) ):
							$m_slider_item = $t_slider_item = '';
							if ( ! empty( $p_info["gallery"]['img'] ) )
							{
								foreach ( $p_info["gallery"]['img'] as $p_image )
								{
									$m_slider_item .= '<div class="items">' . $p_image["code"]["large"] . '</div>';
									$t_slider_item .= '<div class="items">' . $p_image["code"]["medium"] . '</div>';
								}
								
							}
							?>
							
<div class="property-main-info-container">
							<h1><?php echo esc_html( $p_info["title"] ) ?></h1>
							<div class="price">
								<i class="fa fa-inr"></i>
								<?php
							
									echo esc_html( $p_info['price']['value'] );
									if ( is_plugin_active( 'realty-house/realty-house.php' ) && ! empty( $realty_house_opt['realty-house-property-price-offer'] ) )
									{
										echo do_shortcode( '[realty-house-send-price-offer p_id="' . esc_html( $p_info["id"] ) . '"]' );
									}
								?>
							</div>
							<div class="loc-sec">
								<div class="location">
									<i class="fa fa-map-marker"></i> <?php echo esc_html( $p_info["address"] ) ?></div>
								<div class="fav-compare">
									<?php echo( ! empty( $p_info['owner'] ) && $p_info['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $p_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) ?>
									<a href="#" class="realty-house-loading compare compare-btn <?php echo( ! empty( $p_info['in_comparision'] ) ? esc_attr( 'active' ) : '' ) ?>" data-p-id="<?php echo esc_attr( $p_id ); ?>"></a>
									<a href="#" class="realty-house-star fav bookmark-btn <?php echo( ! empty( $p_info['bookmarked'] ) ? esc_attr( 'active' ) : '' ) ?>" data-p-id="<?php echo esc_attr( $p_id ); ?>"></a>
								</div>
								<div class="view-stat"><i class="realty-house-viewed"></i>
									<?php
										if ( ! empty( $p_info['visits'] ) )
										{
											echo esc_html( $p_info['visits'] );
											$old = (int) get_post_meta( $p_id, 'property_visits', true );
											update_post_meta( $p_id, 'property_visits', $old + 1 );
										}
										else
										{
											echo '0';
											update_post_meta( $p_id, 'property_visits', 1 );
										}
									?>
								</div>
							</div>
							<div class="features">
								<div class="bed">
									<i class="realty-house-bedroom"></i>
									<?php echo esc_html( $p_info['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house' ) ?>
								</div>
								<div class="bath">
									<i class="realty-house-bathroom"></i>
									<?php echo esc_html( $p_info['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house' ) ?>
								</div>
								<div class="size">
									<i class="realty-house-size"></i>
									<?php echo esc_html( $p_info['building_size'] ) . ' ' . esc_html( $p_info['area_unit'] ) ?>
								</div>
							</div>
							
						</div>

						<div style="float:left;width:50%;">	<div id="property-slider"><?php echo wp_kses_post( $m_slider_item ) ?></div>
							<div id="thumbnail-slider"><?php echo wp_kses_post( $t_slider_item ) ?></div></div>
							<div class="desc" style="float:left;width:48%;margin-left:1em;text-align:center;">
					<h3>About Property</h3>
							<?php echo wp_kses_post( $p_info["description"]["main"] . ' <br>' . $p_info["description"]["extended"] ) ?></div>
							<?php
						endif;
						?>
						
						<?php
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Amenities Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ( ! empty( $p_info['amenities'] ) && count( $p_info['amenities'] ) > 0 ) && ! empty( $realty_house_opt['realty-house-property-details-amenities'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Amenities', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div class="amenities-container clearfix">
										<?php
											foreach ( $p_info['amenities'] as $amenity )
											{
												echo '<div class="amenities-box col-xs-6 col-sm-4 col-md-2"><i class="fa fa-check-circle"></i>' . esc_html( $amenity ) . '</div>';
											}
										?>
									</div>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Facts Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( count( $p_info['facts'] ) > 0 && ! empty( $realty_house_opt['realty-house-property-details-facts'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Facts', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<ul class="facts-container clearfix">
										<li class="facts-box col-xs-6 col-md-3">
											<?php echo esc_html( $p_info['type']['title'] ) . ' - ' . esc_html( $p_info['status'] ); ?>
										</li>
										<?php
											if ( ! empty( $p_info['facts'] ) )
											{
												foreach ( $p_info['facts'] as $fact )
												{
													echo '<li class="facts-box col-xs-6 col-md-3">' . esc_html( $fact['title'] ) . '</li>';
												}
											}
										?>
									</ul>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Google Map Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ! empty( $realty_house_opt['realty-house-property-details-map'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'On Map', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div id="property-map" class="internal-map" data-lat="<?php echo esc_attr( $p_info['lat'] ) ?>" data-long="<?php echo esc_attr( $p_info['long'] ) ?>"></div>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Neighborhood Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( $p_info['neighborhood'] && ! empty( $realty_house_opt['realty-house-property-details-neighborhood'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Neighborhood', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<ul class="facts-container clearfix">
										<?php
											if ( ! empty( $p_info['neighborhood'] ) )
											{
												foreach ( $p_info['neighborhood'] as $neighborhood )
												{
													echo '<li class="facts-box col-xs-6">' . esc_html( $neighborhood['title'] ) . '<span>( ' . esc_html( $neighborhood['distance'] ) . ' )</span></li>';
												}
											}
										?>
									</ul>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Nearby Schools Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( count( $p_info['nearby_schools'] ) > 0 && ! empty( $realty_house_opt['realty-house-property-details-schools'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Nearby Schools', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<table class="school-nearby">
										<tr>
											<th colspan="2"><?php esc_html_e( 'School Rating', 'realty-house' ) ?></th>
											<th class="center"><?php esc_html_e( 'Grades', 'realty-house' ) ?></th>
											<th class="center"><?php esc_html_e( 'Distance', 'realty-house' ) ?></th>
										</tr>
										<?php
											if ( ! empty( $p_info['nearby_schools'] ) )
											{
												foreach ( $p_info['nearby_schools'] as $nearby_school )
												{
													echo '
													<tr>
														<td class="rate-box">
															<div class="rate-num">' . esc_html( $nearby_school['rate'] ) . '</div>
															<div class="rate-range">' . esc_html__( 'out of 10', 'realty-house' ) . '</div>
														</td>
														<td class="title">' . esc_html( $nearby_school['name'] ) . '</td>
														<td>' . esc_html( $nearby_school['grade'] ) . '</td>
														<td>' . esc_html( $nearby_school['distance'] ) . '</td>
													</tr>
												';
												}
											}
										?>
									</table>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Video Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ! empty( $p_info['video'] ) && ! empty( $realty_house_opt['realty-house-property-details-video'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Video', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div class="video-box clearfix">
										<iframe src="<?php echo esc_url( $p_info['video'] ) ?>" frameborder="0" allowfullscreen></iframe>
									</div>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Floor Planes Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( $p_info['floor_plan']['count'] > 0 && ! empty( $realty_house_opt['realty-house-property-details-floorplan'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Floor Plans', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<ul class="image-main-box floor-plan clearfix">
										<?php
											$floor_i = 0;
											foreach ( $p_info['floor_plan']['img'] as $floor_p )
											{
												echo '
													<li class="item col-xs-6 col-sm-4 col-md-3 ' . ( $floor_i > 3 ? esc_attr( 'hide' ) : '' ) . ' ' . ( $floor_i === 3 ? esc_attr( 'more-image' ) : '' ) . '">
														<a href="' . wp_kses_post( $floor_p['url'] ) . '" class="more-details" ' . ( $floor_i >= 3 ? 'data-image-desc="' . esc_attr( '+' . ( $p_info['floor_plan']['count'] - 3 ) ) . ' ' . esc_html__( 'More', 'realty-house' ) : '' ) . '"' . '>' . wp_kses_post( $floor_p['code'] ) . '</a>
													</li>

												';
												$floor_i ++;
											}
										?>
									</ul>
								</div>
							</div>
							
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Attachments Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( $p_info['attachment']['count'] > 0 && ! empty( $realty_house_opt['realty-house-property-details-attachment'] ) ):
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Attachments', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div class="attachment-container clearfix">
										<?php
											foreach ( $p_info['attachment']['item'] as $attachment_item )
											{
												echo '
													<a href="' . esc_url( $attachment_item['url'] ) . '" class="attachment-box col-xs-6 col-md-3" download>
														<i class="ext-icon ' . esc_attr( str_replace( '/', '-', $attachment_item['mime_type'] ) ) . '"></i>
														<span class="name">' . esc_html( $attachment_item['title'] ) . '</span>
													</a>
												';
											}
										?>
									</div>
								</div>
							</div>
							
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Related Properties
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ! empty( $realty_house_opt['realty-house-property-similar-properties'] ) ):
							$rel_properties = new realty_house_get_property();
							?>
							<div class="details-boxes">
								<div class="title"><?php esc_html_e( 'Similar Properties', 'realty-house' ) ?>
									<i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div class="property-listing-main-container">
										<div class="inner-container">
											<div class="property-boxes clearfix">
												<?php
													if ( ! empty( $p_info['type']['id'] ) )
													{
														$rel_properties->related_properties( $p_info['type']['id'], $p_info['id'] );
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Comment Section
						 * ------------------------------------------------------------------------------------------
						 */
						$comment_count = get_comments_number();
						if ( ! post_password_required() && comments_open() && ! empty( $comment_count ) ) :
							echo '
							<div class="comments-container details-boxes">
								<div class="title">';
							comments_number( '', esc_html__( '1 Comment', 'realty-house' ), esc_html__( '% Comments', 'realty-house' ) );
							echo ' <i class="fa fa-angle-down"></i></div>
 	    						<div class="content">';
							comments_template();
							echo '</div>
							</div>';
						endif;
						
						if ( comments_open() )
						{
							echo '<div class="comment-boxes details-boxes">';
							
							$comment_form_fields = array (
								'author' => '<div class="field-row"><input type="text" id="author" name="author" placeholder="' . esc_attr__( 'Name *', 'realty-house' ) . '" required="required"></div>',
								'email'  => '<div class="field-row"><input type="text" id="email" name="email" placeholder="' . esc_attr__( 'Email *', 'realty-house' ) . '" required="required"></div>',
							);
							$comment_form_arg    = array (
								'fields'            => apply_filters( 'comment_form_default_fields', $comment_form_fields ),
								'title_reply'       => esc_html__( 'Write a Comment', 'realty-house' ),
								'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'realty-house' ),
								'cancel_reply_link' => esc_html__( 'Cancel', 'realty-house' ),
								'comment_field'     => '
														<div class="field-row">
															<textarea name="comment" id="comment" placeholder="' . esc_html__( 'Your Comment', 'realty-house' ) . '" required="required"></textarea>
														</div>',
								'must_log_in'       => '<p class="must-log-in">' . sprintf( wp_kses_post( esc_html__( 'You must be <a href="%s">logged in</a> to share your review.', 'realty-house' ) ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
								'label_submit'      => esc_html__( 'Submit', 'realty-house' )
							);
							
							comment_form( $comment_form_arg );
							
							echo '</div>';
						}
						echo '</div>';
					}
				}
			?>
		</section>
		<?php if ( is_active_sidebar( 'p-details-sidebar' ) || ! empty( $realty_house_opt['realty-house-property-contact-agent'] ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php
					dynamic_sidebar( 'p-details-sidebar' );
					
					/**
					 * ------------------------------------------------------------------------------------------
					 *  Property Agent List
					 * ------------------------------------------------------------------------------------------
					 */
					
					if ( ! empty( $realty_house_opt['realty-house-property-contact-agent'] ) ):
						if ( ! empty( $p_info['agents']['count'] ) && $p_info['agents']['count'] > 0 ) :
							echo '<div id="property-agent-side-container">
						<h3 class="side-title">' . esc_html__( 'Contact Agents', 'realty-house' ) . '</h3>';
							$agent_emails = '';
							foreach ( $p_info['agents']['agent'] as $agent ):
								echo '
									<div class="agent-box">
										<div class="inner-box">
											<div class="img-container">
												' . ( ! empty( $agent['bg_img']['id'] ) ? $agent['bg_img']['code'] : '<div class="no-image"></div>' ) . '
											</div>
											<div class="detail-box">
												<div class="agent-img">
													' . ( ! empty( $agent['has_image'] ) ? wp_kses_post( $agent['img']['full'] ) : '<div class="no-image"></div>' ) . '
												</div>
												<div class="name">' . esc_html( $agent['name'] ) . '</div>
												<ul class="info">
													' . ( ! empty( $agent['phone'] ) ? '<li><i class="fa fa-phone"></i>' . esc_html( $agent['phone'] ) . '</li>' : '' ) . '
													' . ( ! empty( $agent['email'] ) ? '<li><i class="fa fa-envelope"></i>' . esc_html( $agent['email'] ) . '</li>' : '' ) . '
												</ul>
												<a href="' . esc_url( $agent['url'] ) . '" class="btn btn-sm btn-default read-more">' . esc_html( 'Read More', 'realty-house' ) . '</a>
											</div>
										</div>
									</div>';
								$agent_emails .= ! empty( $agent['email'] ) ? esc_html( $agent['email'] ) . ',' : '';
							endforeach;
							
							if ( ! empty( $realty_house_opt['realty-house-property-contact-agent-form'] ) && is_plugin_active( 'realty-house/realty-house.php' ) ):
								$agent_form_placeholder = ! empty( $realty_house_opt['realty-house-property-contact-agent-form-content'] ) ? esc_html( $realty_house_opt['realty-house-property-contact-agent-form-content'] ) : '';
								echo do_shortcode( '[realty-house-agent-contact-form p_address="' . esc_html( $p_info["address"] ) . '" agent_emails="' . esc_html( $agent_emails ) . '" placeholder="' . esc_html( $agent_form_placeholder ) . '"]' );
							endif;
						endif;
					endif;
				?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>
<?php
	get_footer();