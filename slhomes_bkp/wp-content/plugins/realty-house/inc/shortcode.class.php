<?php
	
	/**
	 * Ravis Short code Class
	 */
	class Realty_house_plg_shortcode
	{
		
		function __construct()
		{
			add_action( 'init', array ( $this, 'init' ) );
			
		}
		
		/**
		 * Add all the Realty House Shortcodes to the theme
		 */
		public function init()
		{
			add_shortcode( 'realty-house-social-icons', array ( $this, 'realty_house_social_icon' ) );
			add_shortcode( 'realty-house-main-gallery', array ( $this, 'realty_house_main_gallery' ) );
			add_shortcode( 'realty-house-services', array ( $this, 'realty_house_services' ) );
			add_shortcode( 'realty-house-main-slider', array ( $this, 'realty_house_main_slider' ) );
			add_shortcode( 'realty-house-be-agent', array ( $this, 'realty_house_be_agent' ) );
			add_shortcode( 'realty-house-fun-facts', array ( $this, 'realty_house_fun_facts' ) );
			add_shortcode( 'realty-house-property-slider', array ( $this, 'realty_house_property_slider' ) );
			add_shortcode( 'realty-house-agent-rating', array ( $this, 'realty_house_agent_rating' ) );
			add_shortcode( 'realty-house-agent', array ( $this, 'realty_house_agent' ) );
			add_shortcode( 'realty-house-testimonials', array ( $this, 'realty_house_testimonials' ) );
			add_shortcode( 'realty-house-new-property', array ( $this, 'realty_house_new_properties' ) );
			add_shortcode( 'realty-house-other-properties', array ( $this, 'realty_house_other_properties' ) );
			add_shortcode( 'realty-house-map-listing', array ( $this, 'realty_house_map_listing' ) );
			add_shortcode( 'realty-house-contact', array ( $this, 'realty_house_contact' ) );
			add_shortcode( 'realty-house-video', array ( $this, 'realty_house_video' ) );
			add_shortcode( 'realty-house-search', array ( $this, 'realty_house_search' ) );
			add_shortcode( 'realty-house-currency-switcher', array ( $this, 'realty_house_currency_switcher' ) );
			add_shortcode( 'realty-house-twitter-feeds', array ( $this, 'realty_house_twitter_feeds' ) );
			add_shortcode( 'realty-house-promo', array ( $this, 'realty_house_promo' ) );
			add_shortcode( 'realty-house-agent-contact-form', array ( $this, 'realty_house_agent_contact_form' ) );
			add_shortcode( 'realty-house-send-price-offer', array ( $this, 'realty_house_send_price_offer' ) );
			add_filter( 'widget_text', 'do_shortcode' );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate the main image slider
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_main_slider()
		{
			global $realty_house_opt;
			$realty_house_main_slider_code = '';
			$slider_items_id               = isset( $realty_house_opt["realty-house-main-slider"] ) ? explode( ',', $realty_house_opt["realty-house-main-slider"] ) : '';
			
			if ( isset( $realty_house_opt["realty-house-main-slider"] ) )
			{
				$realty_house_main_slider_code .= '
				<div class="realty-main-slider">';
				
				if ( $slider_items_id[0] !== '' )
				{
					foreach ( $slider_items_id as $slider_item_id )
					{
						$slide                         = wp_get_attachment_image_src( intval( $slider_item_id ), 'full' );
						$realty_house_main_slider_code .= '
							<div class="items">
					            <img src="' . esc_url( $slide[0] ) . '" alt="3"/>
					        </div>';
					}
				}
				else
				{
					$realty_house_main_slider_code .= '
						<div class="items">
				            <img src="' . REALTY_HOUSE_IMG_PATH . 'slider-placeholder.png" alt="' . esc_attr__( 'No Image', 'realty-house-pl' ) . '"/>
				        </div>';
				}
				
				$realty_house_main_slider_code .= '</div>';
			}
			else
			{
				esc_html_e( 'There is not any slides', 'realty-house-pl' );
			}
			
			/**
			 * Restore original Post Data
			 */
			wp_reset_postdata();
			
			return balancetags( $realty_house_main_slider_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate the service
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_services( $attr )
		{
			global $post;
			
			/**
			 * Service Slider's Attribute
			 */
			$realty_house_services_attr = shortcode_atts( array (
				'title'      => esc_html__( 'Our Services', 'realty-house-pl' ),
				'post_count' => 6,
				'class'      => 'container'
			), $attr );
			
			/**
			 * Generate the Query for getting the posts
			 * @var array $args
			 */
			$args                  = array (
				'post_type'      => 'service',
				'post_status'    => 'publish',
				'posts_per_page' => intval( $realty_house_services_attr['post_count'] ),
				'order'          => 'DESC',
				'orderby'        => 'date'
			);
			$realty_house_services = new WP_Query( $args );
			
			/**
			 * Loading post for making the service_slider
			 */
			if ( $realty_house_services->have_posts() )
			{
				/**
				 * Generating the service_slider HTML codes
				 * @var string     realty_house_service_slider_code
				 */
				
				$realty_house_services_code = '
				<section class="features-main-container">
					<h2 class="rh-title"><span>' . esc_html( $realty_house_services_attr['title'] ) . '</span></h2>
					<div class="features-container ' . esc_attr( $realty_house_services_attr['class'] ) . '">';
				/**
				 * Loop for getting post data
				 */
				while ( $realty_house_services->have_posts() )
				{
					$realty_house_services->the_post();
					$post_id      = get_the_id();
					$meta_val     = get_post_meta( $post_id, 'services_icon', true );
					$service_icon = ( ! empty( $meta_val ) ? get_post_meta( $post_id, 'services_icon', true ) : 'fa fa-cog' );
					echo '</pre>';
					$realty_house_services_code .= '
								<div class="features-box col-sm-6 col-md-4">
									<i class="' . esc_attr( $service_icon ) . '"></i>
									<div class="title">' . esc_html( get_the_title() ) . '</div>
									<div class="desc">' . wp_kses_post( get_the_content() ) . '</div>
								</div>';
				}
				$realty_house_services_code .= ' 
					</div>
		        </section>';
			}
			else
			{
				// no posts found
				$realty_house_services_code = esc_html__( 'There is not any services', 'realty-house-pl' );
			}
			
			/**
			 * Restore original Post Data
			 */
			wp_reset_postdata();
			
			return balancetags( $realty_house_services_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate Social icons
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_social_icon( $attr )
		{
			global $realty_house_opt;
			
			/**
			 * Service Slider's Attribute
			 */
			$realty_house_social_icon_attr = shortcode_atts( array (
				'id'    => 'social-icons',
				'print' => false
			), $attr );
			
			
			$social_icons_codes = '<ul class="list-inline list-unstyled social-icons">';
			if ( ! empty( $realty_house_opt['opt-social-twitter'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-twitter'] ) . '" class="ravis-icon-twitter"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-facebook'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-facebook'] ) . '" class="ravis-icon-facebook"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-gplus'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-gplus'] ) . '" class="ravis-icon-google-plus"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-flickr'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-flickr'] ) . '" class="ravis-icon-flickr"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-rss'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-rss'] ) . '" class="ravis-icon-rss"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-vimeo'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-vimeo'] ) . '" class="ravis-icon-vimeo"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-youtube'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-youtube'] ) . '" class="ravis-icon-youtube"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-pinterest'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-pinterest'] ) . '" class="ravis-icon-pinterest"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-tumblr'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-tumblr'] ) . '" class="ravis-icon-tumblr"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-dribbble'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-dribbble'] ) . '" class="ravis-icon-dribbble"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-digg'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-digg'] ) . '" class="ravis-icon-digg"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-linkedin'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-linkedin'] ) . '" class="ravis-icon-linkedin"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-blogger'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-blogger'] ) . '" class="ravis-icon-blogger"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-skype'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-skype'] ) . '" class="ravis-icon-skype"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-forrst'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-forrst'] ) . '" class="ravis-icon-forrst"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-deviantart'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-deviantart'] ) . '" class="ravis-icon-deviantart"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-yahoo'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-yahoo'] ) . '" class="ravis-icon-yahoo"></a></li>';
			endif;
			if ( ! empty( $realty_house_opt['opt-social-reddit'] ) ):
				$social_icons_codes .= '<li><a href="' . esc_url( $realty_house_opt['opt-social-reddit'] ) . '" class="ravis-icon-reddit"></a></li>';
			endif;
			
			$social_icons_codes .= '</ul>';
			
			if ( ( ! isset( $realty_house_social_icon_attr['print'] ) or $realty_house_social_icon_attr['print'] == false ) && $social_icons_codes != '' )
			{
				return '<div class="social-icons-box" ' . ( isset( $realty_house_social_icon_attr['id'] ) && $realty_house_social_icon_attr['id'] !== '' ? 'id="' . esc_attr( $realty_house_social_icon_attr['id'] ) . '"' : '' ) . '>' . balancetags( $social_icons_codes ) . '</div>';
			}
			else
			{
				if ( $social_icons_codes != '' )
				{
					echo '<div class="social-icons-box" ' . ( isset( $realty_house_social_icon_attr['id'] ) && $realty_house_social_icon_attr['id'] !== '' ? 'id="' . esc_attr( $realty_house_social_icon_attr['id'] ) . '"' : '' ) . '>' . balancetags( $social_icons_codes ) . '</div>';
				}
			}
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate the main Gallery
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_main_gallery( $attr )
		{
			global $realty_house_opt;
			
			/**
			 * Main Gallery's Attribute
			 */
			$realty_house_main_gallery_attr = shortcode_atts( array (
				'title'     => esc_html__( 'Our Gallery', 'realty-house-pl' ),
				'columns'   => 2,
				'img_count' => 12
			), $attr );
			
			
			$realty_house_main_gallery_code = $img_list_class = '';
			$gallery_items_id               = ! empty( $realty_house_opt["realty-house-main-gallery"] ) ? explode( ',', $realty_house_opt["realty-house-main-gallery"] ) : '';
			
			switch ( $realty_house_main_gallery_attr['columns'] )
			{
				case( 3 ):
					$column_class = 'col-md-4';
				break;
				case( 4 ):
					$column_class = 'col-md-3';
				break;
				default:
					$column_class = 'col-md-6';
				break;
			}
			
			if ( ! empty( $realty_house_opt["realty-house-main-gallery"] ) )
			{
				
				$realty_house_main_gallery_code .= '
					<section id="gallery" class="container">
						<h3 class="rh-title"><span>' . esc_html( $realty_house_main_gallery_attr['title'] ) . '</span></h3>
						<ul class="image-main-box clearfix">';
				
				$img_i = 1;
				foreach ( $gallery_items_id as $gallery_item_id )
				{
					if ( isset( $realty_house_main_gallery_attr['img_count'] ) && $realty_house_main_gallery_attr['img_count'] != '' )
					{
						if ( $img_i > $realty_house_main_gallery_attr['img_count'] )
						{
							continue;
						}
					}
					$image = get_post( intval( $gallery_item_id ) );
					if ( ! empty( $image ) )
					{
						$realty_house_main_gallery_code .= '
						<li class="item col-xs-6 ' . esc_attr( $column_class ) . '">
							<figure>
								<img src="' . esc_url( $image->guid ) . '" alt="' . esc_attr( $image->post_excerpt ) . '"/>
								<a href="' . esc_url( $image->guid ) . '" class="more-details" data-title="' . esc_attr( $image->post_excerpt ) . '">' . esc_html__( 'Enlarge', 'realty-house-pl' ) . '</a>
								<figcaption><div class="title">' . esc_attr( $image->post_excerpt ) . '</div></figcaption>
							</figure>
						</li>';
					}
					$img_i ++;
				}
				
				$realty_house_main_gallery_code .= '
						</ul>
					</section>';
			}
			else
			{
				esc_html_e( 'There is not any slides', 'realty-house-pl' );
			}
			
			/**
			 * Restore original Post Data
			 */
			wp_reset_postdata();
			
			return balancetags( $realty_house_main_gallery_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Be an Agent Section
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_be_agent( $attr )
		{
			$be_agent_page_url          = realty_house_get_pages::page_template( '../templates/be-agent.php' );
			$realty_house_be_agent_attr = shortcode_atts( array (
				'title'     => esc_html__( 'Become a Real Estate Agent', 'realty-house-pl' ),
				'subtitle'  => esc_html__( 'Join us and our real estate community members!', 'realty-house-pl' ),
				'bg_img'    => '',
				'agent_img' => ''
			), $attr );
			
			$realty_house_be_agent_code = '
					<div id="be-agent-section" data-parallax="scroll"' . ( ! empty( $realty_house_be_agent_attr['bg_img'] ) ? 'data-bg-img="' . esc_attr( $realty_house_be_agent_attr['bg_img'] ) . '"' : '' ) . '>
						<div class="inner-container container">
							<div class="agent-pic" ' . ( ! empty( $realty_house_be_agent_attr['agent_img'] ) ? 'data-bg-img="' . esc_url( $realty_house_be_agent_attr['agent_img'] ) . '"' : '' ) . '></div>
							<div class="text-box col-xs-8">
								<h2><span>' . esc_html( $realty_house_be_agent_attr['title'] ) . '</span></h2>
								<div class="subtitle">' . esc_html( $realty_house_be_agent_attr['subtitle'] ) . '</div>
								<a href="' . esc_url( $be_agent_page_url['url'] ) . '" class="btn btn-sm">' . esc_html__( 'More Info', 'realty-house-pl' ) . '</a>
							</div>
						</div>
					</div>
			';
			
			return balancetags( $realty_house_be_agent_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Fun Facts Section
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_fun_facts( $attr )
		{
			
			$total_stats = new realty_house_stats();
			
			$realty_house_fun_facts_attr = shortcode_atts( array (
				'title' => esc_html__( 'Fun Facts', 'realty-house-pl' )
			), $attr );
			
			$realty_house_fun_facts_code = '
				<div class="fun-fact-section container">
					<h2 class="rh-title"><span>' . esc_html( $realty_house_fun_facts_attr['title'] ) . '</span></h2>
					<div class="counter-container clearfix">
						<div class="counter-box col-xs-6 col-md-3">
							<div class="icon"></div>
							<div class="title">' . esc_html__( 'All Properties', 'realty-house-pl' ) . '</div>
							<div class="value" data-from="0" data-to="' . esc_attr( $total_stats->all_properties() ) . '"></div>
						</div>
						<div class="counter-box col-xs-6 col-md-3">
							<div class="icon"></div>
							<div class="title">' . esc_html__( 'Active Properties', 'realty-house-pl' ) . '</div>
							<div class="value" data-from="0" data-to="' . esc_attr( $total_stats->active_properties() ) . '"></div>
						</div>
						<div class="counter-box col-xs-6 col-md-3">
							<div class="icon"></div>
							<div class="title">' . esc_html__( 'Sold Properties', 'realty-house-pl' ) . '</div>
							<div class="value" data-from="0" data-to="' . esc_attr( $total_stats->sold_properties() ) . '"></div>
						</div>
						<div class="counter-box col-xs-6 col-md-3">
							<div class="icon"></div>
							<div class="title">' . esc_html__( 'Professional Agents', 'realty-house-pl' ) . '</div>
							<div class="value" data-from="0" data-to="' . esc_attr( $total_stats->all_agents() ) . '"></div>
						</div>
					</div>
				</div>			
			';
			
			return balancetags( $realty_house_fun_facts_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Property Slider
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_property_slider( $attr )
		{
			$get_property_obj                  = new realty_house_get_info();
			$realty_house_property_slider_attr = shortcode_atts( array (
				'property_ids' => ''
			), $attr );
			
			$realty_house_property_slider_code = '';
			
			if ( $realty_house_property_slider_attr['property_ids'] )
			{
				
				$properties_id_array = explode( ',', $realty_house_property_slider_attr['property_ids'] );
				
				$realty_house_property_slider_code .= '
					<div class="realty-main-slider">';
				
				foreach ( $properties_id_array as $property_id_array )
				{
					$post_type_val = get_post_type( trim( $property_id_array ) );
					if ( ! empty( $post_type_val ) && get_post_type( trim( $property_id_array ) ) == 'property' )
					{
						
						$p_info                            = $get_property_obj->property_info( trim( $property_id_array ) );
						$realty_house_property_slider_code .= '
							<div class="items">
								<div class="img-container" data-bg-img="' . ( $p_info['gallery']['count'] > 0 ? esc_url( $p_info['gallery']['img'][0]['url'] ) : REALTY_HOUSE_IMG_PATH . 'slider-placeholder.png' ) . '"></div>
								<div class="slide-caption container" >
									<div class="inner-box col-sm-6 col-lg-5" >
										<h3>' . esc_html( $p_info['title'] ) . '</h3>
										<div class="subtitle">
											<i class="glyphicon glyphicon-ok"></i> ' . esc_html( $p_info['type']['title'] ) . ' ' . esc_html( $p_info['status'] ) . '
										</div>
										<div class="desc"> ' . esc_html( $p_info['description']['main'] ) . '</div>
										<ul class="features">
											<li>
												<div class="title">' . esc_html__( 'Listing ID :', 'realty-house-pl' ) . '</div>
												<div class="value">' . esc_html( $p_info['id'] ) . '</div>
											</li>
											<li>
												<div class="title">' . esc_html__( 'Location :', 'realty-house-pl' ) . '</div>
												<div class="value">' . esc_html( $p_info['address'] ) . '</div>
											</li>
											<li>
												<div class="title">' . esc_html__( 'Status :', 'realty-house-pl' ) . '</div>
												<div class="value">' . esc_html( $p_info['status'] ) . '</div>
											</li>
											<li>
												<div class="title">' . esc_html__( 'Bedrooms :', 'realty-house-pl' ) . '</div>
												<div class="value">' . esc_html( $p_info['bedroom'] ) . '</div>
											</li>
											<li>
												<div class="title">' . esc_html__( 'Bathrooms :', 'realty-house-pl' ) . '</div>
												<div class="value">' . esc_html( $p_info['bathroom'] ) . '</div>
											</li>
										</ul >
										<div class="price"><i class="fa fa-inr"></i>' .  esc_html( $p_info['price']['value'] ) . '</div>
										<a href = "' . esc_url( $p_info['url'] ) . '" class="more-info">' . esc_html__( 'More Info', 'realty-house-pl' ) . '</a>
									</div>
								</div>
							</div>
							  ';
					}
				}
				$realty_house_property_slider_code .= '</div>';
			}
			else
			{
				$realty_house_property_slider_code = esc_html__( 'There is not any properties', 'realty-house-pl' );
			}
			wp_reset_postdata();
			
			return balancetags( $realty_house_property_slider_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate Agent slider
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_agent( $attr )
		{
			/**
			 * Realty House agent's Attribute
			 */
			$realty_house_agent_attr = shortcode_atts( array (
				'title' => esc_html__( 'Our Team And Advisors', 'realty-house-pl' ),
				'count' => 6
			), $attr );
			
			
			$args                     = array (
				'post_type'      => 'agent',
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'orderby'        => 'date',
				'posts_per_page' => $realty_house_agent_attr['count']
			);
			$realty_house_agent_query = new WP_Query( $args );
			
			
			$get_info_obj = new realty_house_get_info();
			
			if ( $realty_house_agent_query->have_posts() )
			{
				$realty_house_agent_code = '
				<div class="our-agent-main-container">
					<div class="our-agent-container container">
						<h2 class="rh-title"><span>' . esc_html( $realty_house_agent_attr['title'] ) . '</span></h2>
						<div class="agent-box-container container owl-carousel owl-theme clearfix">';
				
				while ( $realty_house_agent_query->have_posts() )
				{
					$realty_house_agent_query->the_post();
					$post_id = get_the_ID();
					
					$agent_info = $get_info_obj->agent_info( $post_id );
					if ( $agent_info['username'] === 'demo' )
					{
						continue;
					}
					
					$realty_house_agent_code .= '
						<div class="agent-box">
							<div class="inner-box">
								<div class="img-container">
									' . ( ! empty( $agent_info['bg_img']['id'] ) ? $agent_info['bg_img']['code'] : '<div class="no-image"></div>' ) . '
								</div>
								<div class="detail-box">
									<div class="agent-img">
										' . ( ! empty( $agent_info['has_image'] ) ? wp_kses_post( $agent_info['img']['full'] ) : '<div class="no-image"></div>' ) . '
									</div>
									<div class="title-box">
										<div class="name">' . esc_html( $agent_info['name'] ) . '</div>
										<a href="' . esc_url( $agent_info['url'] ) . '" class="btn btn-sm btn-default read-more">' . esc_html( 'Read More', 'realty-house-pl' ) . '</a>
									</div>
									<ul class="info">
										' . ( ! empty( $agent_info['phone'] ) ? '<li><i class="fa fa-phone"></i>' . esc_html( $agent_info['phone'] ) . '</li>' : '' ) . '
									' . ( ! empty( $agent_info['email'] ) ? '<li><i class="fa fa-envelope"></i>' . esc_html( $agent_info['email'] ) . '</li>' : '' ) . '
									</ul>
								</div>
							</div>
						</div>';
				}
				$realty_house_agent_code .= '
						</div>
					</div>
				</div>
			';
				
			}
			else
			{
				$realty_house_agent_code = esc_html__( 'There is not any Agents', 'realty-house-pl' );
			}
			
			wp_reset_postdata();
			
			
			return balancetags( $realty_house_agent_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Testimonials slider
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_testimonials( $attr )
		{
			
			/**
			 * Realty House agent's Attribute
			 */
			$realty_house_testimonials_attr = shortcode_atts( array (
				'title' => esc_html__( 'What Clients Say', 'realty-house-pl' ),
				'count' => 3,
				// Type can be "simple" or "tab"
				'type'  => 'simple'
			), $attr );
			
			$args                            = array (
				'post_type'      => 'testimonials',
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'orderby'        => 'date',
				'posts_per_page' => $realty_house_testimonials_attr['count']
			);
			$realty_house_testimonials_query = new WP_Query( $args );
			
			$get_info_obj = new realty_house_get_info();
			
			if ( $realty_house_testimonials_query->have_posts() )
			{
				
				if ( $realty_house_testimonials_attr['type'] == 'tab' )
				{
					$tab_content = $tab_title = '';
					$test_i      = 1;
					while ( $realty_house_testimonials_query->have_posts() )
					{
						$realty_house_testimonials_query->the_post();
						$post_id           = get_the_ID();
						$testimonials_info = $get_info_obj->testimonials_info( $post_id );
						
						$tab_content .= '<div class="tab-pane fadeInUp in ' . ( $test_i === 1 ? esc_attr( 'active' ) : '' ) . '" id="testi-' . esc_attr( $test_i ) . '">' . esc_html( $testimonials_info['content']['main'] ) . '</div>';
						$tab_title   .= '
						<li ' . ( $test_i === 1 ? esc_attr( 'class=active' ) : '' ) . '>
							<a href="#testi-' . esc_attr( $test_i ) . '" data-toggle="tab" class="clearfix">
								<span class="img-container col-md-5">
									' . ( ! empty( $testimonials_info['name'] ) ? $testimonials_info['img']['full'] : '<div class="no-image"></div>' ) . '
								</span>
								<span class="details col-md-7">
									<span class="title">' . esc_html( $testimonials_info['name'] ) . '</span>
									<span class="location">' . esc_html( $testimonials_info['location'] ) . '</span>
								</span>
							</a>
						</li>';
						
						$test_i ++;
					}
					
					$realty_house_testimonials_code = '
						<div class="testimonials-main-container slider container">
							<h2 class="rh-title"><span>' . esc_html( $realty_house_testimonials_attr['title'] ) . '</span></h2>
							<div class="testimonials-container">
								<div class="tab-content">
									<i class="realty-house-quote"></i>
									' . balanceTags( $tab_content ) . '
								</div>
								
								<div class="tabs-list">
									<ul class="list-inline" role="tablist">
										' . balanceTags( $tab_title ) . '
									</ul>
								</div>
							</div>
						</div>';
					
				}
				else
				{
					
					$realty_house_testimonials_code = '
					<section class="testimonials-main-box">
						<h2 class="rh-title"><span>' . esc_html( $realty_house_testimonials_attr['title'] ) . '</span></h2>
						<div class="testimonials-main-container container">';
					
					while ( $realty_house_testimonials_query->have_posts() )
					{
						$realty_house_testimonials_query->the_post();
						$post_id           = get_the_ID();
						$testimonials_info = $get_info_obj->testimonials_info( $post_id );
						
						$realty_house_testimonials_code .= '
									<div class="testimonial-box col-xs-6 col-md-4">
										<div class="inner-box">
											<i class="realty-house-quote"></i>
											<div class="content">' . esc_html( $testimonials_info['content']['main'] ) . '</div>
											<div class="details-box">
												<div class="img-container">
													' . ( ! empty( $testimonials_info['name'] ) ? $testimonials_info['img']['full'] : '<div class="no-image"></div>' ) . '
												</div>
												<div class="details">
													<div class="title">' . esc_html( $testimonials_info['name'] ) . '</div>
													<div class="location">' . esc_html( $testimonials_info['location'] ) . '</div>
												</div>
											</div>
										</div>
									</div>';
					}
					
					$realty_house_testimonials_code .= '
						</div>
					</section>';
				}
			}
			else
			{
				$realty_house_testimonials_code = esc_html__( 'There is not any Testimonials', 'realty-house-pl' );
			}
			
			wp_reset_query();
			
			return balancetags( $realty_house_testimonials_code );
			
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Other Properties
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_other_properties( $attr )
		{
			global $realty_house_opt;
			$property_info = new realty_house_get_info();
			$search_url    = realty_house_get_pages::page_template( '../templates/search-property.php' );
			/**
			 * Realty House Other Properties Attribute
			 */
			$realty_house_other_properties_attr = shortcode_atts( array (
				'title'           => esc_html__( 'Other Properties', 'realty-house-pl' ),
				'new_tag'         => '',
				'hot_offer'       => '',
				'featured'        => '',
				'price_cut'       => '',
				'open_house'      => '',
				'foreclosure'     => '',
				'property_status' => ''
			), $attr );
			
			$property_tags_array             = array (
				'new_tag'     => esc_html__( 'New Properties', 'realty-house-pl' ),
				'hot_offer'   => esc_html__( 'Hot Offer Properties', 'realty-house-pl' ),
				'featured'    => esc_html__( 'Featured Properties', 'realty-house-pl' ),
				'price_cut'   => esc_html__( 'Price Cut Properties', 'realty-house-pl' ),
				'open_house'  => esc_html__( 'Open House Properties', 'realty-house-pl' ),
				'foreclosure' => esc_html__( 'Foreclosure Properties', 'realty-house-pl' )
			);
			$realty_house_other_property_box = '';
			
			// Tag Section Generator
			foreach ( $property_tags_array as $property_tag => $property_tag_title )
			{
				$tags_query_param = array ();
				if ( $property_tag == 'new_tag' && $realty_house_other_properties_attr['new_tag'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_new_tag',
						'value' => 'on'
					);
				}
				
				if ( $property_tag == 'hot_offer' && $realty_house_other_properties_attr['hot_offer'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_hot_offer',
						'value' => 'on'
					);
				}
				
				if ( $property_tag == 'featured' && $realty_house_other_properties_attr['featured'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_featured',
						'value' => 'on'
					);
				}
				
				if ( $property_tag == 'price_cut' && $realty_house_other_properties_attr['price_cut'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_price_cut',
						'value' => 'on'
					);
				}
				
				if ( $property_tag == 'open_house' && $realty_house_other_properties_attr['open_house'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_open_house',
						'value' => 'on'
					);
				}
				
				if ( $property_tag == 'foreclosure' && $realty_house_other_properties_attr['foreclosure'] != '' )
				{
					$tags_query_param[] = array (
						'key'   => 'property_foreclosure',
						'value' => 'on'
					);
				}
				
				if ( ! empty( $tags_query_param ) )
				{
					$property_tags_args = array (
						'post_type'   => 'property',
						'post_status' => 'publish',
						'orderby'     => 'post_date',
						'order'       => 'DESC',
						'meta_query'  => $tags_query_param
					);
					
					$property_tags_result = new WP_Query( $property_tags_args );
					
					
					if ( $property_tags_result->have_posts() )
					{
						$i = 1;
						while ( $property_tags_result->have_posts() )
						{
							if ( $i > 1 )
							{
								break;
							}
							$property_tags_result->the_post();
							$post_id = get_the_id();
							$p_info  = $property_info->property_info( $post_id );
							
							$realty_house_other_property_box .= '
								<div class="property-box col-sm-6">
									<a href="' . esc_url( $p_info['url'] ) . '" class="img-container">
										' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
										<span class="caption">
											<span class="t-sec">
									            ' . esc_html( $p_info['title'] ) . '
									            -
									           <i class="fa fa-inr"></i>' .esc_html( $p_info['price']['value'] ) . '
								           	</span>
								           	<span class="b-sec">
								        		<span class="bed">
													<i class="realty-house-bedroom"></i>
													' . esc_html( $p_info['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house-pl' ) . '
												</span>
												<span class="bath">
													<i class="realty-house-bathroom"></i>
													' . esc_html( $p_info['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house-pl' ) . '
												</span>
												<span class="size">
													<i class="realty-house-size"></i>
													' . esc_html( $p_info['building_size'] ) . ' ' . esc_html( $p_info['area_unit'] ) . '
												</span>
											</span>
										</span>
									</a>
									<div class="desc">
										<div class="title">' . esc_html( $property_tag_title ) . '</div>
										<a href="' . esc_url( $search_url['url'] ) . '?property_' . esc_attr( $property_tag ) . '=1" class="property-counter"><i class="fa fa-search"></i>' . esc_html( $property_tags_result->post_count ) . '</a>
										<a href="' . esc_url( $search_url['url'] ) . '?property_' . esc_attr( $property_tag ) . '=1" class="more-home"><i class="fa fa-caret-right"></i>' . esc_html__( 'More homes like this', 'realty-house-pl' ) . '</a>
									</div>
								</div>';
							
							$i ++;
						}
						wp_reset_postdata();
					}
				}
			}
			
			// Property Status Generator
			$property_status_array = explode( ',', $realty_house_other_properties_attr['property_status'] );
			
			foreach ( $property_status_array as $property_status )
			{
				$tags_query_param = array (
					array (
						'key'   => 'property_property_status',
						'value' => trim( $property_status )
					)
				);
				
				$property_status_args = array (
					'post_type'   => 'property',
					'post_status' => 'publish',
					'orderby'     => 'post_date',
					'order'       => 'DESC',
					'meta_query'  => $tags_query_param
				);
				
				$property_status_result = new WP_Query( $property_status_args );
				
				if ( $property_status_result->have_posts() )
				{
					$i = 1;
					while ( $property_status_result->have_posts() )
					{
						if ( $i > 1 )
						{
							break;
						}
						$property_status_result->the_post();
						$post_id = get_the_id();
						$p_info  = $property_info->property_info( $post_id );
						
						$realty_house_other_property_box .= '
								<div class="property-box col-sm-6">
									<a href="' . esc_url( $p_info['url'] ) . '" class="img-container">
										' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
										<span class="caption">
								            ' . esc_html( $p_info['title'] ) . '
								            -										            
								            ' . wp_kses_post( $p_info['price']['generated'] ) . '
										</span>
									</a>
									<div class="desc">
										<div class="title">' . esc_html( $realty_house_opt['realty-house-property-status'][ trim( $property_status ) - 1 ] ) . '</div>
										<a href="' . esc_url( $search_url['url'] ) . '?property-status=' . esc_attr( trim( $property_status ) ) . '" class="property-counter"><i class="fa fa-search"></i>' . esc_html( $property_status_result->post_count ) . '</a>
										<a href="' . esc_url( $search_url['url'] ) . '?property-status=' . esc_attr( trim( $property_status ) ) . '" class="more-home"><i class="fa fa-caret-right"></i>' . esc_html__( 'More homes like this', 'realty-house-pl' ) . '</a>
									</div>
								</div>';
						
						$i ++;
					}
					wp_reset_postdata();
				}
			}
			
			$realty_house_other_properties = '
			<div class="other-listing-container container">
				<h2 class="rh-title"><span>' . esc_html( $realty_house_other_properties_attr['title'] ) . '</span></h2>
				<div class="listing-container clearfix">
					' . balanceTags( $realty_house_other_property_box ) . '				
				</div>
			</div>';
			
			return balancetags( $realty_house_other_properties );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate Video Tour
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_video( $attr )
		{
			/**
			 * Realty House Video Attribute
			 */
			$realty_house_video_attr = shortcode_atts( array (
				'title'       => esc_html__( 'Take a Video Tour!', 'realty-house-pl' ),
				'sub_title'   => esc_html__( 'Customized Marketing', 'realty-house-pl' ),
				'btn_text'    => esc_html__( 'Take Tour', 'realty-house-pl' ),
				'video_img'   => '',
				'video_url'   => '#',
				'description' => '',
			), $attr );
			
			
			$realty_house_video_content = '
				<div class="video-tour-main-container">
					<div class="inner-container container">';
			
			if ( ! empty( $realty_house_video_attr['video_img'] ) )
			{
				$realty_house_video_content .= '
					<div class="video-box col-md-6">
						<a href="' . esc_html( $realty_house_video_attr['video_url'] ) . '" class="video-frame-url ' . ( $realty_house_video_attr['video_url'] != '#' && ! empty( $realty_house_video_attr['video_url'] ) ? esc_attr( 'video-url' ) : '' ) . '">
							<img src="' . esc_url( $realty_house_video_attr['video_img'] ) . '" alt="' . esc_html( $realty_house_video_attr['title'] ) . '">		
							<i></i>
						</a>
					</div>';
			}
			
			$realty_house_video_content .= '
							<div class="video-desc ' . ( ! empty( $realty_house_video_attr['video_img'] ) ? esc_attr( 'col-md-6' ) : '' ) . '">
							<h2 class="rh-title"><span>' . esc_html( $realty_house_video_attr['title'] ) . '</span></h2>
							<div class="subtitle">' . esc_html( $realty_house_video_attr['sub_title'] ) . '</div>
							<div class="desc">' . esc_html( $realty_house_video_attr['description'] ) . '</div>
							<a href="' . esc_html( $realty_house_video_attr['video_url'] ) . '" class="btn btn-default btn-sm take-tour ' . ( $realty_house_video_attr['video_url'] != '#' && ! empty( $realty_house_video_attr['video_url'] ) ? esc_attr( 'video-url' ) : '' ) . '">' . esc_html( $realty_house_video_attr['btn_text'] ) . '</a>
						</div>
					</div>
				</div>';
			
			return balancetags( $realty_house_video_content );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Generate the rating based on the items which is added on the theme options
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_agent_rating()
		{
			global $realty_house_opt;
			
			
			echo '<div class="rate-box-container clearfix">';
			if ( is_user_logged_in() && ! empty( $realty_house_opt['realty-house-agent-rate-items'] ) )
			{
				foreach ( $realty_house_opt['realty-house-agent-rate-items'] as $index => $rate_item )
				{
					
					$value     = get_post_meta( get_the_id(), 'agent_rate_' . $index, true );
					$raw_val   = explode( ',', $value );
					$total_val = 0;
					foreach ( $raw_val as $a_val )
					{
						$total_val += intval( $a_val );
					}
					
					$final_val = round( $total_val / count( $raw_val ) );
					echo '
					<div class="rate-box">
				        <div class="title">' . esc_html( $rate_item ) . '</div>
				        <select class="rate-items" name="' . esc_attr( $rate_item ) . '">';
					for ( $i = 0; $i < 5; $i ++ )
					{
						echo '<option value="' . esc_attr( $i ) . '" ' . ( $i == ( $final_val - 1 ) ? esc_attr( 'selected="selected"' ) : '' ) . '>agent_rate_' . esc_html( $index ) . '</option>';
					}
					echo '</select>
				    </div>				
				';
				}
				
				/**
				 * generate the required js file for activating the rating items
				 */
				wp_enqueue_script( 'barrating-js', REALTY_HOUSE_PLG_JS_PATH . 'jquery.barrating.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				$barrating_js_code = '
				jQuery(document).ready(function(){
					jQuery(".rate-items").barrating("show",{
						theme: "fontawesome-stars",
						onSelect:function(value, text, event) {
							if (typeof(event) !== "undefined") {
								var postID = ' . esc_js( get_the_id() ) . ',
									data = {
										action: "agent_rate_update",
										id: postID,
										rateVal: value,
										rateItem: text
									};
								jQuery.post("' . esc_js( admin_url() ) . 'admin-ajax.php", data);
							}
	                    }
					});
				});';
				
				wp_add_inline_script( 'barrating-js', $barrating_js_code );
			}
			elseif ( ! empty( $realty_house_opt['realty-house-agent-rate-items'] ) )
			{
				foreach ( $realty_house_opt['realty-house-agent-rate-items'] as $index => $rate_item )
				{
					
					$value     = get_post_meta( get_the_id(), 'agent_rate_' . $index, true );
					$raw_val   = explode( ',', $value );
					$total_val = 0;
					foreach ( $raw_val as $a_val )
					{
						$total_val += $a_val;
					}
					
					$final_val = round( $total_val / count( $raw_val ) );
					
					echo '
					<div class="rate-box">
				        <div class="title">' . esc_html( $rate_item ) . '</div>
				        <div class="votes-box">
					        <div class="star-container">';
					for ( $i = 0; $i < 5; $i ++ ):
						echo '<i class="fa fa-star ' . ( $i < $final_val ? esc_attr( 'active' ) : '' ) . '"></i>';
					endfor;
					echo '
							</div>
							<div class="rate-total-votes">(' . count( $raw_val ) . ' ' . esc_html__( 'votes', 'realty-house-pl' ) . ')</div>
						</div>
					</div>				
				';
				}
			}
			echo '</div>';
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate new Properties
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_new_properties( $attr )
		{
			global $realty_house_opt;
			/**
			 * Realty House Properties Listing Attribute
			 */
			$realty_house_new_properties_attr = shortcode_atts( array (
				'title'          => esc_html__( 'Our Listings', 'realty-house-pl' ),
				'property_count' => 6,
				'class'          => ''
			), $attr );
			
			
			/**
			 * Generate the Query for getting the posts
			 * @var array $args
			 */
			$args                               = array (
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'posts_per_page' => intval( $realty_house_new_properties_attr['property_count'] ),
				'order'          => 'DESC',
				'orderby'        => 'date'
			);
			$realty_house_new_properties_result = new WP_Query( $args );
			
			$new_property_boxes       = '';
			$new_property_sorts_array = array ();
			
			if ( $realty_house_new_properties_result->have_posts() )
			{
				
				$property_info = new realty_house_get_info();
				while ( $realty_house_new_properties_result->have_posts() )
				{
					$realty_house_new_properties_result->the_post();
					$post_id = get_the_ID();
					$p_info  = $property_info->property_info( $post_id );
					
					$p_type_title = ! empty( $p_info['type'] ) ? $p_info['type']['title'] : '';
					
					if ( ! in_array( $p_type_title . ' ' . $p_info['status'], $new_property_sorts_array ) )
					{
						$new_property_sorts_array[] = $p_type_title . ' ' . $p_info['status'];
					}
					$new_property_boxes .= '
						<div class="property-box col-sm-6 col-md-4 item ' . esc_attr( str_replace( ' ', '-', strtolower( $p_type_title . ' ' . $p_info['status'] ) ) ) . '">
							<a href="' . esc_url( $p_info['url'] ) . '"><div class="inner-box">';
					
					$i = 0;
					if ( ! empty( $p_info['tags'] ) )
					{
						foreach ( $p_info['tags'] as $index => $tag )
						{
							if ( $i > 0 )
							{
								continue;
							}
							$new_property_boxes .= '<div class="tag ' . esc_attr( $tag['class'] ) . '">' . esc_html( $tag['label'] ) . '</div>';
							$i ++;
						}
					}
					
					$new_property_boxes .= '
								<div class="title">' . esc_html( $p_info['title'] ) . '</div>
								<div class="price"><i class="fa fa-inr"></i>' .  esc_html( $p_info['price']['value'] ) . '</div>
								<div class="location">' . esc_html( $p_info['address'] ) . '</div>
								<div class="b-sec">
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
									<div class="fav-compare">
										' . ( ! empty( $p_info['owner'] ) && $p_info['owner'] === true ? '<a href="' . admin_url() . 'post.php?post=' . esc_attr( $post_id ) . '&amp;action=edit" target="_blank" class="glyphicon glyphicon-edit"></a>' : '' ) . '
										<a href="#" class="realty-house-loading compare compare-btn ' . ( ! empty( $p_info['in_comparision'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
										<a href="#" class="realty-house-star fav bookmark-btn ' . ( ! empty( $p_info['bookmarked'] ) ? esc_attr( 'active' ) : '' ) . '" data-p-id="' . esc_attr( $post_id ) . '"></a>
									</div>
								</div>';
					if ( ! empty( $realty_house_opt['realty-house-property-img-slider'] ) && $p_info['gallery']['count'] > 1 )
					{
						$new_property_boxes .= '<div class="post-slider masonry">';
						$p_slider_i         = 1;
						foreach ( $p_info['gallery']['img'] as $image_item )
						{
							if ( $p_slider_i > $realty_house_opt['realty-house-property-img-slider-count'] )
							{
								continue;
							}
							$new_property_boxes .= '<div class="items">' . $image_item['code']['large'] . '</div>';
							$p_slider_i ++;
						}
						$new_property_boxes .= '</div>';
					}
					else
					{
						$new_property_boxes .= '
											<a href="' . esc_url( $p_info['url'] ) . '" class="img-container">
												' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
											</a>';
					}
					$new_property_boxes .= '
								<div class="more-info">
									<a href="' . esc_url( $p_info['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house-pl' ) . '</a>
								</div>
							</div></a>
						</div>';
				}
				wp_reset_query();
			}
			
			$realty_house_new_properties_code = '
				<div class="property-listing-main-container masonry">
					<div class="inner-container container' . esc_attr( $realty_house_new_properties_attr['class'] ) . '">
						<h2 class="rh-title"><span>' . esc_html( $realty_house_new_properties_attr['title'] ) . '</span></h2>
			
						<div class="sort-section-container">
							<ul class="list-inline">
								<li><a href="#" data-filter="*" class="active">' . esc_html__( 'All', 'realty-house-pl' ) . '</a></li>';
			
			foreach ( $new_property_sorts_array as $new_property_sorts )
			{
				$realty_house_new_properties_code .= '<li><a href="#" data-filter=".' . str_replace( ' ', '-', strtolower( $new_property_sorts ) ) . '">' . esc_html( $new_property_sorts ) . '</a></li>';
			}
			
			$realty_house_new_properties_code .= '</ul>
						</div>
						<div class="property-boxes clearfix">
							' . balancetags( $new_property_boxes ) . '
						</div>
					</div>
				</div>';
			
			return balancetags( $realty_house_new_properties_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Map Listing
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_map_listing( $attr )
		{
			$property_info = new realty_house_get_info();
			$data          = array (
				"property" => array ()
			);
			/**
			 * Realty House Map Listings Attribute
			 */
			$realty_house_map_listings_attr = shortcode_atts( array (
				'new_tag'         => '',
				'hot_offer'       => '',
				'featured'        => '',
				'price_cut'       => '',
				'open_house'      => '',
				'foreclosure'     => '',
				'property_status' => ''
			), $attr );
			$map_query_param                = array (
				'relation' => 'OR',
			);
			
			
			if ( ! empty( $realty_house_map_listings_attr['new_tag'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_new_tag',
					'value' => 'on'
				);
			}
			
			if ( ! empty( $realty_house_map_listings_attr['hot_offer'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_hot_offer',
					'value' => 'on'
				);
			}
			
			if ( ! empty( $realty_house_map_listings_attr['featured'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_featured',
					'value' => 'on'
				);
			}
			
			if ( ! empty( $realty_house_map_listings_attr['price_cut'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_price_cut',
					'value' => 'on'
				);
			}
			
			if ( ! empty( $realty_house_map_listings_attr['open_house'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_open_house',
					'value' => 'on'
				);
			}
			
			if ( ! empty( $realty_house_map_listings_attr['foreclosure'] ) )
			{
				$map_query_param[] = array (
					'key'   => 'property_foreclosure',
					'value' => 'on'
				);
			}
			
			// Property Status Generator
			$property_status_array = explode( ',', $realty_house_map_listings_attr['property_status'] );
			
			foreach ( $property_status_array as $property_status )
			{
				$map_query_param[] = array (
					'key'   => 'property_property_status',
					'value' => trim( $property_status )
				);
			}
			
			$property_map_args = array (
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'orderby'        => 'post_date',
				'order'          => 'DESC',
				'posts_per_page' => - 1,
				'meta_query'     => $map_query_param
			);
			
			$property_map_result = new WP_Query( $property_map_args );
			
			if ( $property_map_result->have_posts() )
			{
				while ( $property_map_result->have_posts() )
				{
					$property_map_result->the_post();
					$post_id = get_the_id();
					$p_info  = $property_info->property_info( $post_id );
					
					$property_array = array (
						"pId"         => $post_id,
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
				}
				wp_reset_postdata();
			}
			
			$random_id                = 'map-' . random_int( 10000, 100000 );
			$realty_house_map_listing = '
			<div class="map-listing-container">
				<div id="' . esc_attr( $random_id ) . '"></div>
			</div>';
			
			$protocol = is_ssl() ? 'https' : 'http';
			wp_enqueue_script( "google-map", $protocol . '://maps.googleapis.com/maps/api/js?libraries=places' . ( ! empty( $realty_house_opt['opt-map-api'] ) ? '&amp;key=' . esc_attr( $realty_house_opt['opt-map-api'] ) : '' ), array (), get_bloginfo( 'version' ), true );
			wp_enqueue_script( "google-map-requirements", REALTY_HOUSE_PLG_JS_PATH . 'googlemap-requirement.js', array (
				'jquery',
				'google-map'
			), get_bloginfo( 'version' ), true );
			
			$property_data_json = json_encode( $data );
			
			$google_map_script = '
				var mapOptions = {
					zoom:               5,
					// This is where you would paste any style found on Snazzy Maps.
					styles:[{"featureType": "water", "elementType": "geometry", "stylers":[{"color": "#a0d6d1"}, {"lightness": 17}] }, {"featureType": "landscape", "elementType": "geometry", "stylers":[{"color": "#f2f2f2"}, {"lightness": 20}] }, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers":[{"color": "#dedede"}, {"lightness": 17}] }, {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers":[{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}] }, {"featureType": "road.arterial", "elementType": "geometry", "stylers":[{"color": "#dedede"}, {"lightness": 18}] }, {"featureType": "road.local", "elementType": "geometry", "stylers":[{"color": "#ffffff"}, {"lightness": 16}] }, {"featureType": "poi", "elementType": "geometry", "stylers":[{"color": "#f1f1f1"}, {"lightness": 21}] }, {"elementType": "labels.text.stroke", "stylers":[{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}] }, {"elementType": "labels.text.fill", "stylers":[{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}] }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "geometry", "stylers":[{"color": "#f2f2f2"}, {"lightness": 19}] }, {"featureType": "administrative", "elementType": "geometry.fill", "stylers":[{"color": "#fefefe"}, {"lightness": 20}] }, {"featureType": "administrative", "elementType": "geometry.stroke", "stylers":[{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}] }],
					// Extra options
					scrollwheel:        false,
					mapTypeControl:     false,
					panControl:         false,
					zoomControlOptions: {
						style:    google.maps.ZoomControlStyle.SMALL,
						position: google.maps.ControlPosition.LEFT_BOTTOM
					}
				};
				var map        = new google.maps.Map(document.getElementById(\'' . esc_js( $random_id ) . '\'), mapOptions);
			
				var markers = [],
					rawPropertyData = ' . $property_data_json . ';
			
				if (typeof rawPropertyData !== \'undefined\') {
								
					var rawPropertyDataJson = rawPropertyData,
						bounds              = new google.maps.LatLngBounds();
			
					for (var i = 0; i < rawPropertyDataJson.property.length; i++) {
						var dataProperty   = rawPropertyDataJson.property[i],
							latLng         = new google.maps.LatLng(dataProperty.latitude, dataProperty.longitude),
							propertyType   = dataProperty.pType,
							propertyId     = dataProperty.pId,
							boxText        = document.createElement("div"),
							infoboxOptions = {
								content:                boxText,
								disableAutoPan:         false,
								pixelOffset:            new google.maps.Size(-125, 8),
								zIndex:                 null,
								alignBottom:            true,
								maxWidth:               200,
								boxClass:               "infobox-main-container",
								enableEventPropagation: true,
								closeBoxURL:            realtyHouse.assetsURL + "img/close.png",
								infoBoxClearance:       new google.maps.Size(1, 1)
							},
							marker         = new RichMarker({
								position: latLng,
								map:      map,
								flat:     true,
								content:  \'<div class="map-marker \' + propertyType + \'" id="p_id_\' + propertyId + \'"></div>\'
							});
			
						markers.push(marker);
			
						bounds.extend(markers[i].getPosition());
			
						boxText.innerHTML  =
							\'<div id="property-marker">\' +
							\'<div class="l-sec">\' +
							\'<a href="\' + dataProperty.pURL + \'">\' +
							dataProperty.pImage +
							\'</a>\' +
							\'<div class="caption">\' +
							\'<div class="title">\' + dataProperty.title + \'</div>\' +
							\'<div class="price-box">\' + dataProperty.price + \'</div>\' +
							\'</div>\' +
							\'</div>\' +
							\'<div class="r-sec">\' +
							\'<div class="location">\' + dataProperty.location + \'</div>\' +
							\'<div class="features">\' +
							\'<div class="bed"><i class="realty-house-bedroom"></i>\' + dataProperty.bedroom + \' bd</div>\' +
							\'<div class="bath"><i class="realty-house-bathroom"></i>\' + dataProperty.bathroom + \' ba</div>\' +
							\'<div class="size"><i class="realty-house-size"></i>\' + dataProperty.size + \'</div>\' +
							\'</div>\' +
							\'<div class="desc-title">Description</div>\' +
							\'<div class="desc">\' + dataProperty.description + \'</div>\' +
							\'</div>\' +
							\'</div>\';
						markers[i].infobox = new InfoBox(infoboxOptions);
			
						google.maps.event.addListener(marker, \'click\', (function (marker, i) {
							return function () {
								var h;
								for (h = 0; h < markers.length; h++) {
									markers[h].infobox.close();
								}
								markers[i].infobox.open(map, this);
							}
						})(marker, i));
			
						map.fitBounds(bounds);
					}
					var clusterStyles = [
						{
							url:    realtyHouse.assetsURL + \'img/pattern.png\',
							height: 42,
							width:  42
						}
					];
					var markerCluster = new MarkerClusterer(map, markers, {styles: clusterStyles, maxZoom: 15});
				}
			';
			
			wp_add_inline_script( "google-map-requirements", $google_map_script );
			
			return balancetags( $realty_house_map_listing );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate the contact section
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_contact( $attr )
		{
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			/**
			 * Realty House Contact Section Attribute
			 */
			$realty_house_contact_attr = shortcode_atts( array (
				'title'        => esc_html__( 'Get In Touch', 'realty-house-pl' ),
				'contact_7_id' => '',
				'img'          => ''
			), $attr );
			
			$realty_house_contact_code = '
				<section class="contact-form container">
					<div class="l-sec ' . ( ! empty( $realty_house_contact_attr['img'] ) ? esc_attr( 'col-xs-12 col-sm-7' ) : '' ) . '">
						<h3 class="rh-title"><span>' . esc_html( $realty_house_contact_attr['title'] ) . '</span></h3>';
			
			if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) && ! empty( $realty_house_contact_attr['contact_7_id'] ) )
			{
				$realty_house_contact_code .= do_shortcode( '[contact-form-7 id="' . esc_html( $realty_house_contact_attr['contact_7_id'] ) . '"]' );
			}
			$realty_house_contact_code .= '</div>';
			
			
			if ( ! empty( $realty_house_contact_attr['img'] ) )
			{
				$realty_house_contact_code .= '
						<div class="r-sec col-sm-5 hidden-xs">
							<div class="img-container" data-bg-img="' . esc_url( $realty_house_contact_attr['img'] ) . '"></div>
						</div>';
			}
			$realty_house_contact_code .= '</section>';
			
			return balancetags( $realty_house_contact_code );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Search From
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_search( $attr )
		{
			global $realty_house_opt;
			$currency_info = new realty_house_currency();
			$price_unit    = $currency_info->get_current_currency();
			$search_url    = realty_house_get_pages::page_template( '../templates/search-property.php' );
			$random_id     = 'search-box-' . random_int( 10000, 100000 );
			/**
			 * Realty House Search Section Attribute
			 */
			$realty_house_search_attr = shortcode_atts( array (
				/*
				 * Type be switched between 'simple' & 'accordion'
				 */
				'type'       => 'simple',
				'p_location' => '',
				'p_status'   => '',
				'p_type'     => '',
				'bedrooms'   => '',
				'bathrooms'  => '',
				'garages'    => '',
				'price'      => '',
				'keywords'   => '',
				'amenities'  => '',
				'tags'       => '',
				'max_price'  => 1000000000
			), $attr );
			
			$permalink_val = get_option( 'permalink_structure' );
			switch ( $realty_house_search_attr['type'] )
			{
				case( 'accordion' ):
					$realty_house_search_code = '
						<section id="' . esc_attr( $random_id ) . '" class="accordion container">
							<form class="inner-container property-search-form" action="' . ( ! empty( $permalink_val ) ? esc_url( $search_url['url'] ) : home_url() ) . '">
								<div class="fields-main-container active">
									<div class="title-box">' . esc_html__( 'Listing', 'realty-house-pl' ) . '</div>
									<div class="content-box clearfix">';
					
					if ( ! empty( $realty_house_search_attr['p_status'] ) )
					{
						$realty_house_search_code .= '
									<div class="search-field">
										<select id="property-status" name="property-status">
											<option value="">' . esc_html__( 'Property Status', 'realty-house-pl' ) . '</option>';
						
						foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status_item )
						{
							$realty_house_search_code .= '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $property_status_item ) . '</option>';
						}
						
						$realty_house_search_code .= '
										</select>
									</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['p_type'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<select id="property-type" name="property-type">
									<option value="">' . esc_html__( 'Property Type', 'realty-house-pl' ) . '</option>';
						
						foreach ( $realty_house_opt['realty-house-property-type'] as $index => $property_type_item )
						{
							$p_type_title             = explode( '---', $property_type_item );
							$realty_house_search_code .= '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $p_type_title[0] ) . '</option>';
						}
						$realty_house_search_code .= '
								</select>
							</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['bedrooms'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<select id="property-bedroom" name="bedroom">
									<option value="">' . esc_html__( 'Bedroom', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['bathrooms'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<select id="property-bathroom" name="bathroom">
									<option value="">' . esc_html__( 'Bathroom', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['price'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<input type="text" id="price_range" class="range-slider" name="price" value="" data-inputValuesSeparator="-"  data-min="0" data-max="' . esc_attr( $realty_house_search_attr['max_price'] ) . '" data-prefix="' . esc_attr( $price_unit['symbol'] ) . '" />
							</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['garages'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<select id="property-garage" name="garages">
									<option value="">' . esc_html__( 'Garages', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['keywords'] ) )
					{
						$realty_house_search_code .= '
							<div class="search-field">
								<input type="text" id="property-keyword" name="keywords" placeholder="' . esc_html__( 'Keywords', 'realty-house-pl' ) . '"/>
							</div>';
					}
					
					$realty_house_search_code .= '
									</div>
								</div>';
					
					if ( ! empty( $realty_house_search_attr['p_location'] ) )
					{
						$realty_house_search_code .= '
								<div class="fields-main-container">
									<div class="title-box">' . esc_html__( 'Address', 'realty-house-pl' ) . '</div>
									<div class="content-box clearfix">
										<div class="search-field location">
											<input type="text" placeholder="' . esc_html__( 'Location', 'realty-house-pl' ) . '" id="location-search-box" name="location">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['amenities'] ) )
					{
						$realty_house_search_code .= '
								<div class="fields-main-container">
									<div class="title-box">' . esc_html__( 'Features', 'realty-house-pl' ) . '</div>
									<div class="content-box clearfix">';
						foreach ( $realty_house_opt['realty-house-property-amenities'] as $index => $property_amenity )
						{
							$realty_house_search_code .= '
									<div class="col-xs-6 search-field">
										<div class="hsq-checkbox check-box-container">
											<label for="property_amenities[' . $index . ']">
												<input type="checkbox" value="1" id="property_amenities[' . $index . ']" name="property_amenities[' . $index . ']">
												<span></span>
												' . esc_html( $property_amenity ) . '
											</label>
										</div>
									</div>
								';
						}
						$realty_house_search_code .= '					                      
									</div>
								</div>';
					}
					
					if ( ! empty( $realty_house_search_attr['tags'] ) )
					{
						$realty_house_search_code .= '
								<div class="fields-main-container">
									<div class="title-box">' . esc_html__( 'Tags', 'realty-house-pl' ) . '</div>
									<div class="content-box clearfix">';
						$realty_house_search_code .= '
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_new_tag">
													<input type="checkbox" value="1" id="property_new_tag" name="property_new_tag">
													<span></span>
													' . esc_html__( 'New', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_hot_offer">
													<input type="checkbox" value="1" id="property_hot_offer" name="property_hot_offer">
													<span></span>
													' . esc_html__( 'Hot Offer', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_featured">
													<input type="checkbox" value="1" id="property_featured" name="property_featured">
													<span></span>
													' . esc_html__( 'Featured', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_price_cut">
													<input type="checkbox" value="1" id="property_price_cut" name="property_price_cut">
													<span></span>
													' . esc_html__( 'Price Cut', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_open_house">
													<input type="checkbox" value="1" id="property_open_house" name="property_open_house">
													<span></span>
													' . esc_html__( 'Open House', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
										<div class="col-xs-6 search-field">
											<div class="hsq-checkbox check-box-container">
												<label for="property_foreclosure">
													<input type="checkbox" value="1" id="property_foreclosure" name="property_foreclosure">
													<span></span>
													' . esc_html__( 'Foreclosure', 'realty-house-pl' ) . '
												</label>
											</div>
										</div>
									';
						$realty_house_search_code .= '
									</div>
								</div>';
					}
					
					$realty_house_search_code .= '

								<div class="btn-container">
									<button class="btn btn-sm btn-default">
										<i class="realty-house-search"></i>
										' . esc_html__( 'Search', 'realty-house-pl' ) . '
									</button>
								</div>
							</form>
						</section>';
				
				break;
				default:
					$realty_house_search_code = '
						<section id="' . esc_attr( $random_id ) . '" class="container horizontal">
							<div class="inner-container">
								<div class="title">' . esc_html__( 'Advanced Search', 'realty-house-pl' ) . ' <i class="fa fa-caret-down"></i></div>
								<form class="field-main-container property-search-form" action="' . ( ! empty( $permalink_val ) ? esc_url( $search_url['url'] ) : home_url() ) . '">
									<div class="main-search-sec">
										<div class="t-sec clearfix">';
					
					if ( ! empty( $realty_house_search_attr['p_location'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<input type="text" placeholder="' . esc_html__( 'Location', 'realty-house-pl' ) . '" id="location-search-box" name="location">
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['p_status'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="property-status" name="property-status">
									<option value="">' . esc_html__( 'Property Status', 'realty-house-pl' ) . '</option>';
						
						foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status_item )
						{
							$realty_house_search_code .= '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $property_status_item ) . '</option>';
						}
						
						$realty_house_search_code .= '
								</select>
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['p_type'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="property-type" name="property-type">
									<option value="">' . esc_html__( 'Property Type', 'realty-house-pl' ) . '</option>';
						
						foreach ( $realty_house_opt['realty-house-property-type'] as $index => $property_type_item )
						{
							$p_type_title             = explode( '---', $property_type_item );
							$realty_house_search_code .= '<option value="' . esc_attr( $index + 1 ) . '">' . esc_html( $p_type_title[0] ) . '</option>';
						}
						$realty_house_search_code .= '
								</select>
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['price'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="price_range" name="price">
									<option value="">Price Range</option>
									<option value="5Lakhs-10Lakhs">5Lakhs-10Lakhs</option>
									<option value="10Lakhs-20Lakhs">10Lakhs-20Lakhs</option>
									<option value="20Lakhs-30Lakhs">20Lakhs-30Lakhs</option>
									<option value="30Lakhs-40Lakhs">30Lakhs-40Lakhs</option>
									<option value="40Lakhs-50Lakhs">40Lakhs-50Lakhs</option>
									<option value="50Lakhs-60Lakhs">50Lakhs-60Lakhs</option>
									<option value="60Lakhs-70Lakhs">60Lakhs-70Lakhs</option>
									<option value="70Lakhs-80Lakhs">70Lakhs-80Lakhs</option>
									<option value="80Lakhs-90Lakhs">80Lakhs-90Lakhs</option>
									<option value="90Lakhs-1Cr">90Lakhs-1Cr</option>
									<option value="1Cr-5Cr">1Cr-5Cr</option>
									<option value="5Cr-10Cr">5Cr-10Cr</option>
									<option value="10Cr-15Cr">10Cr-15Cr</option>
									<option value="15Cr-20Cr">15Cr-20Cr</option>
									<option value="20Cr-25Cr">20Cr-25Cr</option>
									<option value="25Cr-30Cr">25Cr-30Cr</option>
									<option value="30Cr-35Cr">30Cr-35Cr</option>
									<option value="35Cr-40Cr">35Cr-40Cr</option>
									<option value="40Cr-45Cr">40Cr-45Cr</option>
									<option value="45Cr-50Cr">45Cr-50Cr</option>
									<option value="50Cr-55Cr">50Cr-55Cr</option>
									<option value="55Cr-60Cr">55Cr-60Cr</option>
									<option value="60Cr-65Cr">60Cr-65Cr</option>
									<option value="65Cr-70Cr">65Cr-70Cr</option>
									<option value="70Cr-75r">75Cr-80Cr</option>
									<option value="80Cr-85Cr">80Cr-85Cr</option>
									<option value="85Cr-90Cr">85Cr-90Cr</option>
									<option value="90Cr-100Cr">90Cr-100Cr</option>
								</select>
							</div>';
									$realty_house_search_code .= '';
					}
					if ( ! empty( $realty_house_search_attr['bedrooms'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="property-bedroom" name="bedroom">
									<option value="">' . esc_html__( 'Bedroom', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['bathrooms'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="property-bathroom" name="bathroom">
									<option value="">' . esc_html__( 'Bathroom', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['garages'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<select id="property-garage" name="garages">
									<option value="">' . esc_html__( 'Garages', 'realty-house-pl' ) . '</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">+5</option>
								</select>
							</div>';
					}
					if ( ! empty( $realty_house_search_attr['keywords'] ) )
					{
						$realty_house_search_code .= '
							<div class="col-xs-6 col-sm-3 search-field">
								<input type="text" id="property-keyword" name="keywords" placeholder="' . esc_html__( 'Keywords', 'realty-house-pl' ) . '"/>
							</div>';
					}
					$realty_house_search_code .= '
						</div>
						<div class="b-sec clearfix">
							<button class="search-btn btn-sm btn pull-right">' . esc_html__( 'Search', 'realty-house-pl' ) . '</button>';
					
					if ( ! empty( $realty_house_search_attr['amenities'] ) || ! empty( $realty_house_search_attr['tags'] ) )
					{
						$realty_house_search_code .= '<a href="#" class="more-options"><i class="fa fa-plus"></i>' . esc_html__( 'More Advanced Search', 'realty-house-pl' ) . '</a>';
					}
					$realty_house_search_code .= '
							</div>
						</div>';
					
					if ( ! empty( $realty_house_search_attr['amenities'] ) || ! empty( $realty_house_search_attr['tags'] ) )
					{
						$realty_house_search_code .= '<div class="advanced-search-sec clearfix">';
						
						if ( ! empty( $realty_house_search_attr['amenities'] ) )
						{
							foreach ( $realty_house_opt['realty-house-property-amenities'] as $index => $property_amenity )
							{
								$realty_house_search_code .= '
									<div class="col-xs-6 col-sm-3 col-md-2 search-field">
										<div class="hsq-checkbox check-box-container">
											<label for="property_amenities[' . $index . ']">
												<input type="checkbox" value="1" id="property_amenities[' . $index . ']" name="property_amenities[' . $index . ']">
												<span></span>
												' . esc_html( $property_amenity ) . '
											</label>
										</div>
									</div>
								';
							}
						}
						
						if ( ! empty( $realty_house_search_attr['tags'] ) )
						{
							
							$realty_house_search_code .= '
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_new_tag">
											<input type="checkbox" value="1" id="property_new_tag" name="property_new_tag">
											<span></span>
											' . esc_html__( 'New', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_hot_offer">
											<input type="checkbox" value="1" id="property_hot_offer" name="property_hot_offer">
											<span></span>
											' . esc_html__( 'Hot Offer', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_featured">
											<input type="checkbox" value="1" id="property_featured" name="property_featured">
											<span></span>
											' . esc_html__( 'Featured', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_price_cut">
											<input type="checkbox" value="1" id="property_price_cut" name="property_price_cut">
											<span></span>
											' . esc_html__( 'Price Cut', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_open_house">
											<input type="checkbox" value="1" id="property_open_house" name="property_open_house">
											<span></span>
											' . esc_html__( 'Open House', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-2 search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_foreclosure">
											<input type="checkbox" value="1" id="property_foreclosure" name="property_foreclosure">
											<span></span>
											' . esc_html__( 'Foreclosure', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
							';
						}
						
						$realty_house_search_code .= '</div>';
					}
					
					$realty_house_search_code .= '
								</form>
							</div>
						</section>';
				break;
				
			}
			
			
			return balancetags( $realty_house_search_code );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Currency Switcher
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_currency_switcher()
		{
			global $realty_house_opt;
			
			$currency_currency = ! empty( $_COOKIE['currencyTitle'] ) && is_string( $_COOKIE['currencyTitle'] ) ? $_COOKIE['currencyTitle'] : '';
			
			$random_id = 'currency-switcher-select-' . random_int( 10000, 100000 );
			
			$options_code = '';
			foreach ( $realty_house_opt['realty-house-currency'] as $currency )
			{
				$options_code .= '<option value="' . esc_attr( $currency['title'] ) . '" ' . selected( $currency['title'], $currency_currency, false ) . '>' . esc_attr( $currency['title'] ) . '</option>';
			}
			
			$realty_house_currency_switcher = '<select id="' . esc_attr( $random_id ) . '">' . balanceTags( $options_code ) . '</select>';
			
			return balancetags( $realty_house_currency_switcher );
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Twitter lists
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_twitter_feeds()
		{
			global $realty_house_opt;
			
			require_once( 'TwitterAPIExchange.php' );
			
			$settings  = array (
				'oauth_access_token'        => $realty_house_opt['opt-twitter-access-token'],
				'oauth_access_token_secret' => $realty_house_opt['opt-twitter-access-token-secret'],
				'consumer_key'              => $realty_house_opt['opt-twitter-consumer-key'],
				'consumer_secret'           => $realty_house_opt['opt-twitter-consumer-secret']
			);
			$user_name = $realty_house_opt['opt-twitter-username'];
			
			$url           = "https://api.twitter.com/1.1/statuses/user_timeline.json";
			$requestMethod = "GET";
			$getfield      = '?screen_name=' . $user_name . '&count=20';
			
			$twitter     = new TwitterAPIExchange( $settings );
			$tweets_list = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
			
			$realty_house_tweets = '
				<div class="twitter-news-ticker-mcontainer">
					<div class="twitter-news-ticker-mcontainer container">
						<div class="icon-container col-sm-1">
							<div class="icon-box">
								<i class="realty-house-home"></i>
								<i class="fa fa-twitter"></i>
							</div>
						</div>
						<ul class="twitter-news-ticker col-sm-10">';
			
			$i = 1;
			foreach ( $tweets_list as $tweet_item )
			{
				if ( $i >= intval( $realty_house_opt['opt-twitter-tweet-count'] ) )
				{
					continue;
				}
				
				$tweet_url           = 'https://twitter.com/' . $user_name . '/status/' . $tweet_item->id;
				$realty_house_tweets .= '<li><a href="' . esc_url( $tweet_url ) . '">' . esc_html( $tweet_item->text ) . '</a><span class="date"> / ' . date( 'j F Y - g:i a', strtotime( $tweet_item->created_at ) ) . '</span></li>';
				$i ++;
			}
			
			$realty_house_tweets .= '
						</ul>
						<div class="btn-container col-sm-1">
							<div class="prev-button fa fa-angle-left"></div>
							<div class="next-button fa fa-angle-right"></div>
						</div>
					</div>
				</div>';
			
			return balancetags( $realty_house_tweets );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Promo Section
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_promo()
		{
			global $realty_house_opt;
			$realty_house_promo_code = '<section class="quick-links-container container">';
			foreach ( $realty_house_opt['realty-house-promo'] as $promo_box )
			{
				$realty_house_promo_code .= '
				<a href="' . esc_url( $promo_box['url'] ) . '"><div class="quick-links-box col-xs-6 col-lg-4">
					<i class="' . esc_attr( $promo_box['icon'] ) . '"></i>
					<div class="title">' . esc_html( $promo_box['title'] ) . '</div>
					<div class="desc">' . esc_html( $promo_box['desc'] ) . '</div>
					<a href="' . esc_url( $promo_box['url'] ) . '" class="btn btn1 btn-default more-info">' . esc_html( $promo_box['btn_text'] ) . '</a>
				</div></a>
				';
				
			}
			
			$realty_house_promo_code .= '</section>';
			
			return balancetags( $realty_house_promo_code );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Contact Agent Form
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_agent_contact_form( $attr )
		{
			/**
			 * Realty House Contact Section Attribute
			 */
			$realty_house_agent_contact_form_attr = shortcode_atts( array (
				'p_address'    => '',
				'agent_emails' => '',
				'placeholder'  => ''
			), $attr );
			
			if ( ! empty( $realty_house_agent_contact_form_attr['p_address'] ) && ! empty( $realty_house_agent_contact_form_attr['agent_emails'] ) )
			{
				if ( empty( $realty_house_agent_contact_form_attr['placeholder'] ) )
				{
					$property_address = sprintf( esc_html__( 'I am interested in %s', 'realty-house-pl' ), $realty_house_agent_contact_form_attr['p_address'] );
				}
				else
				{
					$property_address = esc_html( $realty_house_agent_contact_form_attr['placeholder'] );
				}
				
				$realty_house_agent_contact_form_code = '
				<div class="contact-form">
					<form action="#" id="p-details-contact-agents">
						<div class="row-inputs">
							<input type="text" class="user-name" placeholder="' . esc_html__( 'Name', 'realty-house-pl' ) . '" required>
						</div>
						<div class="row-inputs">
							<input type="email" class="user-email" placeholder="' . esc_html__( 'E-mail', 'realty-house-pl' ) . '" required>
						</div>
						<div class="row-inputs">
							<input type="text"  class="user-phone" placeholder="' . esc_html__( 'Phone', 'realty-house-pl' ) . '" required>
						</div>
						<div class="row-inputs">
							<textarea  class="user-message" placeholder="' . esc_html__( 'Message', 'realty-house-pl' ) . '" required>' . esc_html( $property_address ) . '</textarea>
						</div>
						<div class="row-inputs">
							<div class="hsq-checkbox check-box-container">
								<label for="field-finance">
									<input type="checkbox" class="user-finance-info" id="field-finance">
									<span></span>
									' . esc_html__( 'I want financing information', 'realty-house-pl' ) . '
								</label>
							</div>
						</div>
						<div class="row-inputs response-message-box"></div>
						<div class="row-inputs btn-container">
							<input type="submit" class="btn btn-default btn-sm" value="' . esc_html__( 'Send Now', 'realty-house-pl' ) . '">
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
						errorMessage += "' . esc_html__( 'Please fill all fields.', 'realty-house-pl' ) . '";
					}
					var data = {
						action: "p_details_contact_agent",
						name: name,
						email: email,
						phone: phone,
						message: message,
						financeInfo: financeInfo,
						emailReceivers: "' . esc_js( trim( $realty_house_agent_contact_form_attr['agent_emails'], ',' ) ) . '"
					};
					jQuery.post("' . esc_url( admin_url() ) . 'admin-ajax.php", data, function (data) {
						pDetailsContactAgent.removeClass("loading");
						data = JSON.parse(data);
						console.log(data);
						data.status == false ? response.removeClass("alert-danger alert-success").addClass("alert alert-danger") : response.removeClass("alert-danger alert-success").addClass("alert alert-success");
						response.text(data.message);
					});
				})';
				wp_add_inline_script( 'realty-house-plugin-front-js', $agent_contact_js );
			}
			else
			{
				$realty_house_agent_contact_form_code = esc_html__( 'Please provide address and email of agents.', 'realty-house-pl' );
			}
			
			return balancetags( $realty_house_agent_contact_form_code );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Generate Send Price Offer For a Property
		 * ------------------------------------------------------------------------------------------
		 */
		function realty_house_send_price_offer( $attr )
		{
			$realty_house_send_price_offer_attr = shortcode_atts( array (
				'p_id' => ''
			), $attr );
			
			$realty_house_send_price_offer_code = '
				<a href="#price-offer" id="send-price-offer">' . esc_html__( 'Do you have any offers?', 'realty-house-pl' ) . '</a>
				<div id="price-offer" class="price-offer mfp-hide">
					<h3 class="rh-title"><span>' . esc_html__( 'Let Us Know Your Offer', 'realty-house-pl' ) . '</span></h3>
					<form class="price-offer-form-box" action="#">
						<input type="hidden" class="p_id" value="' . ( ! empty( $realty_house_send_price_offer_attr['p_id'] ) ? intval( $realty_house_send_price_offer_attr['p_id'] ) : '' ) . '">
						<div class="error-box"></div>
						<div class="row-fields">
							<input type="text" class="name" placeholder="' . esc_attr__( 'Name', 'realty-house-pl' ) . '"/>
						</div>
						<div class="row-fields">
							<input type="email" class="email" placeholder="' . esc_attr__( 'Email', 'realty-house-pl' ) . '"/>
						</div>
						<div class="row-fields">
							<input type="text" class="phone" placeholder="' . esc_attr__( 'Phone', 'realty-house-pl' ) . '"/>
						</div>
						<div class="row-fields">
							<input type="number" class="offer" placeholder="' . esc_attr__( 'Price Offer', 'realty-house-pl' ) . '"/>
						</div>
						<div class="row-fields">
							<textarea name="description" class="desc" placeholder="' . esc_attr__( 'Your Comment', 'realty-house-pl' ) . '"></textarea>
						</div>
						<div class="row-button-container">
							<input class="btn btn-default" value="' . esc_attr__( 'Send', 'realty-house-pl' ) . '" type="submit"/>
						</div>
						<div class="loader"></div>
						' . wp_nonce_field( 'ajax-send-price-offer-nonce', 'ajax-send-price-offer' ) . '
					</form>
				</div>';
			
			$send_offer_js_codes = "
				var priceOfferForm = jQuery('#price-offer').find('form');
				priceOfferForm.on('submit', function (e) {
					e.preventDefault();
					priceOfferForm.addClass('loading');
					priceOfferForm.find('.error-box').removeClass('alert alert-danger').text('');
					var name           = priceOfferForm.find('.name'),
						email          = priceOfferForm.find('.email'),
						phone          = priceOfferForm.find('.phone'),
						offer          = priceOfferForm.find('.offer'),
						desc           = priceOfferForm.find('.desc'),
						pID            = priceOfferForm.find('.p_id'),
						fieldsValArray = [name, email, phone, offer, desc, pID];
			
					if (name.val() !== '' && email.val() !== '' && phone.val() !== '' && offer.val() !== '' && pID.val() !== '') {
						jQuery.ajax({
							type:     'POST',
							dataType: 'json',
							url:      '" . esc_url( admin_url( 'admin-ajax.php' ) ) . "',
							data:     {
								'action':   'realty_house_price_offer',
								'name':     name.val(),
								'email':    email.val(),
								'phone':    phone.val(),
								'offer':    offer.val(),
								'desc':     desc.val(),
								'pID':      pID.val(),
								'security': priceOfferForm.find('#ajax-send-price-offer').val()
							},
							success:  function (data) {
								priceOfferForm.removeClass('loading');
								for (var i = 0; i < fieldsValArray.length; i++) {
									fieldsValArray[i].parent().removeClass('has-error');
								}
								if (data.status === true) {
									priceOfferForm.find('.error-box').addClass('alert alert-success').text(data.message);
								}
								else {
									priceOfferForm.find('.error-box').addClass('alert alert-danger').text(data.message);
								}
							}
						});
					}
					else {
						priceOfferForm.removeClass('loading');
						for (var i = 0; i < fieldsValArray.length; i++) {
							fieldsValArray[i].val() === '' ? fieldsValArray[i].parent().addClass('has-error') : fieldsValArray[i].parent().removeClass('has-error');
						}
						return false;
					}
				})";
			
			wp_add_inline_script( 'realty-house-plugin-front-js', $send_offer_js_codes );
			
			return balancetags( $realty_house_send_price_offer_code );
		}
		
	}
	
	$ravis_shortcode_obj = new Realty_house_plg_shortcode;