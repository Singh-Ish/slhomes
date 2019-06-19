<?php
	// single-agent.php
	// Agent Details Page
	
	global $post, $realty_house_opt;
	get_header();
	
	$agent_information     = new realty_house_get_info();
	$property_informations = new realty_house_get_property();
	$sold_id               = array_search( 'Sold', $realty_house_opt['realty-house-property-status'] ) + 1;
?>
	<section class="page-content container">
		<section class="main-container <?php echo ( ! empty( $realty_house_opt['realty-house-agent-details-contact-agent'] ) || ! empty( $realty_house_opt['realty-house-agent-details-pro-info'] ) || ! empty( $realty_house_opt['realty-house-agent-rate-status'] ) || is_active_sidebar( 'agent-details-sidebar' ) ) ? 'col-sm-8 col-md-9' : '' ?>">
			<h3 class="rh-title">
				<span><?php esc_html_e( 'Meet The Agent', 'realty-house' ) ?></span>
			</h3>
			<?php
				if ( have_posts() )
				{
					global $realty_house_opt;
					while ( have_posts() )
					{
						the_post();
						$p_id       = get_the_ID();
						$agent_info = $agent_information->agent_info( $p_id );
						echo '
							<div class="agent-listing">
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
												' . ( ! empty( $sold_id ) ? '<div class="sale-stat"><span>' . count( $property_informations->properties_by_agent( $p_id, $sold_id ) ) . '</span> ' . esc_html__( 'Recent Sales', 'realty-house' ) . '</div>' : '' ) . '
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
										</div>
									</div>
								</div>				
							</div>';
						$pp_id = $property_informations->all_agent_properties( $p_id );
						if ( ! empty( $pp_id ) ):
							/**
							 * ------------------------------------------------------------------------------------------
							 *  Map listing section
							 * ------------------------------------------------------------------------------------------
							 */
							if ( ! empty( $realty_house_opt['realty-house-agent-details-map-listing'] ) ):
								$tab_title = $tab_content = $tab_js = '';
								if ( ! empty( $realty_house_opt['realty-house-property-status'] ) ):
									$i = 1;
									foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status ):
										$listing_info = $property_informations->properties_by_agent( $p_id, $index + 1 );
										$data         = array(
											"property" => array()
										);
										
										if ( count( $listing_info ) > 0 ) :
											$tab_title .= '
												<li ' . ( $i == 1 ? 'class="active"' : '' ) . '>
													<a href="#map-container-' . esc_attr( $i ) . '" data-toggle="tab" data-map-id="' . esc_attr( $i ) . '" class="clearfix">' . esc_html( $property_status ) . '</a>
												</li>
											';
											$tab_content .= '
												<div class="tab-pane ' . ( $i == 1 ? 'active' : '' ) . '" id="map-container-' . esc_attr( $i ) . '">
													<div id="map-' . esc_attr( $i ) . '" class="internal-map"></div>
												</div>
											';
											foreach ( $listing_info as $item ):
												$property_array = array(
													"pId"         => $item['id'],
													"longitude"   => $item['long'],
													"latitude"    => $item['lat'],
													"pType"       => $item['type']['icon'],
													"title"       => $item['title'],
													"price"       => $item['price']['generated'],
													"pImage"      => ( $item['gallery']['count'] > 0 ? $item['gallery']['img'][0]['code']['large'] : '' ),
													"location"    => $item['address'],
													"description" => $item['description']['main'],
													"bedroom"     => $item['bedroom'],
													"bathroom"    => $item['bathroom'],
													"size"        => $item['building_size'],
													"pURL"        => esc_url( $item['url'] ),
												);
												array_push( $data['property'], $property_array );
											
											endforeach;
											
											$tab_js .= '
										dynamicInitialize("' . esc_js( 'map-' . $i ) . '", ' . json_encode( $data ) . ');
									';
										endif;
										
										$i ++;
									endforeach;
									
									wp_add_inline_script( 'realty-house-template-js', $tab_js );
								
								
								endif;
								
								echo '
							<div class="details-boxes">
								<div class="title">' . esc_html__( 'Listings & Sales', 'realty-house' ) . ' <i class="fa fa-angle-down"></i></div>
								<div class="content">
									<div class="map-listing-container">
										<div class="tabs-list">
											<ul class="list-inline" role="tablist">
												' . $tab_title . '
											</ul>
										</div>
										<div class="tab-content">
											' . $tab_content . '
										</div>
									</div>
								</div>
							</div>
						';
							endif;
						endif;
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Active Properties Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ! empty( $realty_house_opt['realty-house-agent-details-active-listing'] ) ):
							$active_properties = $property_informations->active_properties_agent( $p_id, $sold_id );
							
							if ( count( $active_properties ) > 0 ):
								?>
								<div class="details-boxes">
									<div class="title"><?php printf( esc_html__( 'Active Listings (%d)', 'realty-house' ), count( $active_properties ) ) ?>
										<i class="fa fa-angle-down"></i></div>
									<div class="content">
										<table>
											<tr>
												<th><?php esc_html_e( 'Property Address', 'realty-house' ); ?></th>
												<th><?php esc_html_e( 'Bedrooms/Bathrooms', 'realty-house' ); ?></th>
												<th><?php esc_html_e( 'Price', 'realty-house' ); ?></th>
											</tr>
											<?php
												foreach ( $active_properties as $active_property ):
													echo '
													<tr>
														<td>
															<a href="' . esc_url( $active_property['url'] ) . '" class="img-container">
																' . ( $active_property['gallery']['count'] > 0 ? $active_property['gallery']['img'][0]['code']['large'] : '' ) . '
															</a>
															' . $active_property['title'] . '
														</td>
														<td>
															' . esc_html( $active_property['bedroom'] ) . ' ' . esc_html__( 'Bed', 'realty-house' ) . ', ' . esc_html( $active_property['bathroom'] ) . ' ' . esc_html__( 'Bath', 'realty-house' ) . '
														</td>
														<td>' . wp_kses_post( $active_property['price']['generated'] ) . '</td>
													</tr>												
												';
												endforeach;
											
											?>
										</table>
									</div>
								</div>
								<?php
							endif;
						endif;
						
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Sold Properties Section
						 * ------------------------------------------------------------------------------------------
						 */
						if ( ! empty( $realty_house_opt['realty-house-agent-details-sold-listing'] ) ):
							$sold_properties = $property_informations->properties_by_agent( $p_id, $sold_id );
							if ( count( $sold_properties ) > 0 ):
								?>
								<div class="details-boxes">
									<div class="title"><?php printf( esc_html__( 'Past Sales (%d All-Time)', 'realty-house' ), count( $sold_properties ) ) ?>
										<i class="fa fa-angle-down"></i></div>
									<div class="content">
										<table class="sold-properties">
											<tr>
												<th><?php esc_html_e( 'Property Address', 'realty-house' ); ?></th>
												<th><?php esc_html_e( 'Bedrooms/Bathrooms', 'realty-house' ); ?></th>
												<th><?php esc_html_e( 'Price', 'realty-house' ); ?></th>
											</tr>
											<?php
												foreach ( $sold_properties as $sold_property ):
													echo '
													<tr>
														<td>
															<a href="' . esc_url( $sold_property['url'] ) . '" class="img-container">
																' . ( $sold_property['gallery']['count'] > 0 ? $sold_property['gallery']['img'][0]['code']['large'] : '' ) . '
															</a>
															' . $sold_property['title'] . '
														</td>
														<td>
															' . esc_html( $sold_property['bedroom'] ) . ' ' . esc_html__( 'Bed', 'realty-house' ) . ', ' . esc_html( $sold_property['bathroom'] ) . ' ' . esc_html__( 'Bath', 'realty-house' ) . '
														</td>
														<td>' . wp_kses_post( $sold_property['price']['generated'] ) . '</td>
													</tr>												
												';
												endforeach;
											?>
										</table>
									</div>
								</div>
								<?php
							endif;
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
							
							$comment_form_fields = array(
								'author' => '<div class="field-row"><input type="text" id="author" name="author" placeholder="'. esc_attr__( 'Name *', 'realty-house' ) .'" required="required"></div>',
								'email'  => '<div class="field-row"><input type="text" id="email" name="email" placeholder="'. esc_attr__( 'Email *', 'realty-house' ) .'" required="required"></div>',
							);
							$comment_form_arg    = array(
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
		<?php
			if ( ! empty( $realty_house_opt['realty-house-agent-details-contact-agent'] ) || ! empty( $realty_house_opt['realty-house-agent-details-pro-info'] ) || ! empty( $realty_house_opt['realty-house-agent-rate-status'] ) || is_active_sidebar( 'agent-details-sidebar' ) ) : ?>
				<!-- Sidebar Section -->
				<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
					<?php
						dynamic_sidebar( 'agent-details-sidebar' );
						
						/**
						 * ------------------------------------------------------------------------------------------
						 *  Property Cotnact Form
						 * ------------------------------------------------------------------------------------------
						 */
						
						if ( ! empty( $realty_house_opt['realty-house-agent-details-contact-agent'] ) ):
							
							echo '
				<div id="property-agent-side-container" class="widget type-1">
					<h3 class="side-title">' . esc_html__( 'Contact Agents', 'realty-house' ) . '</h3>';
							$agent_emails = '';
							echo '
						<div class="content contact-form">
							<form action="#" id="p-details-contact-agents">
								<div class="row-inputs">
									<input type="text" class="user-name" placeholder="' . esc_html__( 'Name', 'realty-house' ) . '" required>
								</div>
								<div class="row-inputs">
									<input type="email" class="user-email" placeholder="' . esc_html__( 'E-mail', 'realty-house' ) . '" required>
								</div>
								<div class="row-inputs">
									<input type="text"  class="user-phone"placeholder="' . esc_html__( 'Phone', 'realty-house' ) . '" required>
								</div>
								<div class="row-inputs">
									<textarea  class="user-message" placeholder="' . esc_html__( 'Message', 'realty-house' ) . '" required></textarea>
								</div>
								<div class="row-inputs response-message-box"></div>
								<div class="row-inputs btn-container">
									<input type="submit" class="btn btn-default btn-sm" value="' . esc_html__( 'Send Now', 'realty-house' ) . '">
								</div>
							</form>
						</div>';
							
							$agent_contact_js = '
						var pDetailsContactAgent = jQuery("#p-details-contact-agents");
						pDetailsContactAgent.on("submit", function (e) {
							e.preventDefault();
							pDetailsContactAgent.addClass("loading");
							var _this          = jQuery(this),
								name           = _this.find(".user-name").val(),
								email          = _this.find(".user-email").val(),
								phone          = _this.find(".user-phone").val(),
								message        = _this.find(".user-message").val(),
								financeInfo    = _this.find(".user-finance-info").val(),
								response       = _this.find(".response-message-box"),
								errorMessage   = "";
				
							if (name == "" || phone == "" || email == "" || message == "") {
								errorMessage += "' . esc_html__( 'Please fill all fields.', 'realty-house' ) . '";
							}				
							var data = {
								action: "p_details_contact_agent",
								name: name,
								email: email,
								phone: phone,
								message: message,
								financeInfo: financeInfo,
								emailReceivers: "' . esc_js( trim( $agent_emails, ',' ) ) . '"
							};
							jQuery.post("' . esc_url( admin_url() ) . 'admin-ajax.php", data, function (data) {
								pDetailsContactAgent.removeClass("loading");
								data = JSON.parse(data);
								console.log(data);
								data.status == false ? response.removeClass("alert-danger alert-success").addClass("alert alert-danger") : response.removeClass("alert-danger alert-success").addClass("alert alert-success");
								response.text(data.message);
							});							
						})
					
					';
							wp_add_inline_script( 'realty-house-template-js', $agent_contact_js );
							?>
							</div>
							<?php
						endif;
						if ( ! empty( $realty_house_opt['realty-house-agent-details-pro-info'] ) ):
							?>
							<div class="box-container">
								<div class="title"><?php esc_html_e( 'Professional Information', 'realty-house' ) ?></div>
								<div class="content">
									<table class="pro-info">
										<tr>
											<td class="title"><?php esc_html_e( 'Address:', 'realty-house' ) ?></td>
											<td><?php echo( ! empty( $agent_info['address'] ) ? esc_html( $agent_info['address'] ) : '' ) ?></td>
										</tr>
										<tr>
											<td class="title"><?php esc_html_e( 'Mobile:', 'realty-house' ) ?></td>
											<td><?php echo( ! empty( $agent_info['mobile'] ) ? esc_html( $agent_info['mobile'] ) : '' ) ?></td>
										</tr>
										<tr>
											<td class="title"><?php esc_html_e( 'ID Name:', 'realty-house' ) ?></td>
											<td><?php echo( ! empty( $agent_info['name'] ) ? esc_html( $agent_info['name'] ) : '' ) ?></td>
										</tr>
										<tr>
											<td class="title"><?php esc_html_e( 'Member S', 'realty-house' ) ?>ince:</td>
											<td><?php echo( ! empty( $agent_info['start_membership'] ) ? esc_html( $agent_info['start_membership'] ) : '' ) ?></td>
										</tr>
										<tr>
											<td class="title"><?php esc_html_e( 'Licenses:', 'realty-house' ) ?></td>
											<td><?php echo( ! empty( $agent_info['license'] ) ? esc_html( $agent_info['license'] ) : '' ) ?></td>
										</tr>
									</table>
								
								</div>
							</div>
							<?php
						endif;
						if ( ! empty( $realty_house_opt['realty-house-agent-rate-status'] ) ):
							?>
							<div class="box-container">
								<div class="title"><?php esc_html_e( 'Agent Rating', 'realty-house' ) ?></div>
								<div class="content">
									<?php
										if ( is_plugin_active( 'realty-house/realty-house.php' ) )
										{
											echo do_shortcode( '[realty-house-agent-rating]' );
										}
									?>
								</div>
							</div>
							<?php
						endif;
					?>
				</aside>
				<!-- Sidebar Section -->
			<?php endif; ?>
	</section>
<?php
	get_footer();