<?php
	/**
	 *  contact.php
	 *  Contact Page
	 *  Template Name: Contact Page
	 */
	global $post, $realty_house_opt;
	get_header();
	
	if ( have_posts() )
	{
		while ( have_posts() )
		{
			the_post();
			$attachment_info = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			?>
			<section class="contact-form container">
				<div class="l-sec <?php echo ! empty( $attachment_info ) ? 'col-xs-12 col-sm-7' : '' ?>">
					<h3 class="rh-title"><span><?php esc_html_e( 'Get In Touch', 'realty-house' ) ?></span></h3>
					<?php the_content() ?>
				</div>
				<?php if ( ! empty( $attachment_info ) ) :
					?>
					<div class="r-sec col-sm-5 hidden-xs">
						<div class="img-container" data-bg-img="<?php echo esc_url( $attachment_info[0] ) ?>"></div>
					</div>
				<?php endif; ?>
			</section>
			<?php
			
			$contact_map_code = '
				function initializeContact() {
					google.maps.Map.prototype.setCenterWithOffset= function(latlng, offsetX, offsetY) {
						var map = this;
						var ov = new google.maps.OverlayView();
						ov.onAdd = function() {
							var proj = this.getProjection();
							var aPoint = proj.fromLatLngToContainerPixel(latlng);
							aPoint.x = aPoint.x+offsetX;
							aPoint.y = aPoint.y+offsetY;
							map.setCenter(proj.fromContainerPixelToLatLng(aPoint));
						};
						ov.draw = function() {};
						ov.setMap(this);
					};
		
					var myLatLng = new google.maps.LatLng(' . ( ! empty( $realty_house_opt['opt-map-lat'] ) ? esc_js( $realty_house_opt['opt-map-lat'] ) : '40.67' ) . ', ' . ( ! empty( $realty_house_opt['opt-map-lng'] ) ? esc_js( $realty_house_opt['opt-map-lng'] ) : '-73.94' ) . ');
					var mapOptions = {
						zoom: ' . ( ! empty( $realty_house_opt['opt-map-zoom'] ) ? esc_js( $realty_house_opt['opt-map-zoom'] ) : '10' ) . ',
						center: myLatLng,
						// This is where you would paste any style found on Snazzy Maps.
						styles: [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a0d6d1"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#dedede"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#dedede"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f1f1f1"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}],
		
						// Extra options
						scrollwheel: false,
						mapTypeControl: false,
						panControl: false,
						zoomControlOptions: {
							style   : google.maps.ZoomControlStyle.SMALL,
							position: google.maps.ControlPosition.LEFT_BOTTOM
						}
					}
					var map = new google.maps.Map(document.getElementById("contact-map"),mapOptions);
					map.setCenterWithOffset(myLatLng, 0, -120);
		
					var marker = new RichMarker({
						position: myLatLng,
						map:      map,
						flat:     true,
						content:  \'<div id="company-marker">\' +
									  \'<div class="l-sec">\' +
										  \'' . ( ! empty( $realty_house_opt['opt-web-site-company-img']['url'] ) ? '<img src="' . esc_url( $realty_house_opt['opt-web-site-company-img']['url'] ) . '" alt="' . ( ! empty( $realty_house_opt['opt-web-site-company-title'] ) ? esc_html( $realty_house_opt['opt-web-site-company-title'] ) : '' ) . '">' : '' ) . '\' +
										  \'<div class="caption">\' +
											  \'<div class="title">' . ( ! empty( $realty_house_opt['opt-web-site-company-title'] ) ? esc_html( $realty_house_opt['opt-web-site-company-title'] ) : '' ) . '</div>\' +
											  \'<div class="sub-title">' . ( ! empty( $realty_house_opt['opt-web-site-company-subtitle'] ) ? esc_html( $realty_house_opt['opt-web-site-company-subtitle'] ) : '' ) . '</div>\' +
										  \'</div>\' +
									  \'</div>\' +
									  \'<div class="r-sec">\' +
										  \'<div class="main-title">' . ( ! empty( $realty_house_opt['opt-web-site-city'] ) ? esc_html( $realty_house_opt['opt-web-site-city'] ) : '' ) . '</div>\' +
										  \'' . ( ! empty( $realty_house_opt['opt-web-site-address'] ) ? '<div class="location">' . esc_html( $realty_house_opt['opt-web-site-address'] ) . '</div>' : '' ) . '\' +
										  \'' . ( ! empty( $realty_house_opt['opt-web-site-phone'] ) ? '<div class="contact-info"><i class="fa fa-phone"></i><span>' . esc_html__( 'Tel', 'realty-house' ) . ':</span> ' . esc_html( $realty_house_opt['opt-web-site-phone'] ) . '</div>' : '' ) . '\' +
										  \'' . ( ! empty( $realty_house_opt['opt-web-site-email'] ) ? '<div class="contact-info"><i class="fa fa-envelope"></i><span>' . esc_html__( 'Mail', 'realty-house' ) . ':</span> ' . esc_html( $realty_house_opt['opt-web-site-email'] ) . '</div>' : '' ) . '\' +
									  \'</div>\' +
								  \'</div>\'
					});
				}
		
				google.maps.event.addDomListener(window, "load", initializeContact);
			';
			wp_add_inline_script( 'realty-house-template-js', $contact_map_code );
		}
	}
	get_footer();