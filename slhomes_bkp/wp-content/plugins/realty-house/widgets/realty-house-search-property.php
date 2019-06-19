<?php
	
	/*
	 * Plugin Name: Realty House Search Property
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the search property form
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_search_property extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_search_property', // Base ID
				esc_html__( 'Realty House Search Property', 'realty-house-pl' ), // Name
				array ( 'description' => esc_html__( 'Show the search form of properties in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array ( $this, 'register_widgets' ) );
			add_action( 'widgets_init', array ( $this, 'add_required_scripts' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			global $realty_house_opt;
			
			extract( $args );
			
			$widget_width  = ! empty( $instance['class'] ) ? $instance['class'] : '';
			$before_widget = str_replace( 'class="', 'class="' . $widget_width . ' ', $args['before_widget'] );
			
			echo balancetags( $before_widget );
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			
			
			$search_url = realty_house_get_pages::page_template( '../templates/search-property.php' );
			
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
			$property_new_tag     = ! empty( $_GET['property_new_tag'] ) ? filter_var( $_GET['property_new_tag'], FILTER_VALIDATE_INT ) : '';
			$property_hot_offer   = ! empty( $_GET['property_hot_offer'] ) ? filter_var( $_GET['property_hot_offer'], FILTER_VALIDATE_INT ) : '';
			$property_featured    = ! empty( $_GET['property_featured'] ) ? filter_var( $_GET['property_featured'], FILTER_VALIDATE_INT ) : '';
			$property_price_cut   = ! empty( $_GET['property_price_cut'] ) ? filter_var( $_GET['property_price_cut'], FILTER_VALIDATE_INT ) : '';
			$property_open_house  = ! empty( $_GET['property_open_house'] ) ? filter_var( $_GET['property_open_house'], FILTER_VALIDATE_INT ) : '';
			$property_foreclosure = ! empty( $_GET['property_foreclosure'] ) ? filter_var( $_GET['property_foreclosure'], FILTER_VALIDATE_INT ) : '';
			
			$paged            = ! empty( $_GET['paged'] ) ? filter_var( $_GET['paged'], FILTER_VALIDATE_INT ) : 2;
			$permaling_option = get_option( 'permalink_structure' );
			echo '
			<div class="content">
				<form action="' . ( ! empty( $permaling_option ) ? esc_url( $search_url['url'] ) : home_url() ) . '" class="property-search-form inner-container" method="get">
					<input type="hidden" name="page_id" value="' . esc_attr( $search_url['id'] ) . '" />
					<div class="t-sec">';
			
			if ( ! empty( $instance['location'] ) && $instance['location'] === 'on' ):
				echo '
								<div class="search-field location" >
									<input type="text" placeholder="' . esc_html__( 'Ex : New York, NY, United States', 'realty-house-pl' ) . '" id="location-search-box" name="location" value="' . esc_attr( $location ) . '">
									<i class="fa fa-map-marker" ></i >
								</div >
							';
			endif;
			
			if ( ! empty( $instance['status'] ) && $instance['status'] === 'on' ):
				echo '
								<div class="search-field" >
									<select id="property-status" name="property-status">
										<option value="">' . esc_html__( 'Property Status', 'realty-house-pl' ) . '</option>';
				foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status_item )
				{
					echo '<option value="' . esc_attr( $index + 1 ) . '" ' . ( ( $index + 1 == $property_status ) ? 'selected="selected"' : '' ) . '>' . esc_html( $property_status_item ) . '</option>';
				}
				echo ' 
									</select>
								</div>
							';
			endif;
			
			if ( ! empty( $instance['p_type'] ) && $instance['p_type'] === 'on' ):
				echo '
								<div class="search-field" >
									<select id="property-type" name="property-type">
										<option value="">' . esc_html__( 'Property Type', 'realty-house-pl' ) . '</option>';
				foreach ( $realty_house_opt['realty-house-property-type'] as $index => $property_type_item )
				{
					$p_type_title = explode( '---', $property_type_item );
					echo '<option value="' . esc_attr( $index + 1 ) . '" ' . ( ( $index + 1 == $property_type ) ? 'selected="selected"' : '' ) . '>' . esc_html( $p_type_title[0] ) . '</option>';
				}
				echo ' 
									</select>
								</div>
							';
			endif;
			
			if ( ! empty( $instance['bed'] ) && $instance['bed'] === 'on' ):
				echo '
								<div class="search-field" >
									<select id="bedroom" name="bedroom">
										<option value="">' . esc_html__( 'Bedroom', 'realty-house-pl' ) . '</option>
										<option value="1" ' . ( ( 1 == $bedroom ) ? 'selected="selected"' : '' ) . '>1</option>
										<option value="2" ' . ( ( 2 == $bedroom ) ? 'selected="selected"' : '' ) . '>2</option>
										<option value="3" ' . ( ( 3 == $bedroom ) ? 'selected="selected"' : '' ) . '>3</option>
										<option value="4" ' . ( ( 4 == $bedroom ) ? 'selected="selected"' : '' ) . '>4</option>
										<option value="5" ' . ( ( 5 == $bedroom ) ? 'selected="selected"' : '' ) . '>5</option>
										<option value="6" ' . ( ( 5 < $bedroom ) ? 'selected="selected"' : '' ) . '>+5</option>
									</select>
								</div>
							';
			endif;
			
			if ( ! empty( $instance['bath'] ) && $instance['bath'] === 'on' ):
				echo '
								<div class="search-field" >
									<select id="bathroom" name="bathroom">
										<option value="">' . esc_html__( 'Bathroom', 'realty-house-pl' ) . '</option>
										<option value="1" ' . ( ( 1 == $bathroom ) ? 'selected="selected"' : '' ) . '>1</option>
										<option value="2" ' . ( ( 2 == $bathroom ) ? 'selected="selected"' : '' ) . '>2</option>
										<option value="3" ' . ( ( 3 == $bathroom ) ? 'selected="selected"' : '' ) . '>3</option>
										<option value="4" ' . ( ( 4 == $bathroom ) ? 'selected="selected"' : '' ) . '>4</option>
										<option value="5" ' . ( ( 5 == $bathroom ) ? 'selected="selected"' : '' ) . '>5</option>
										<option value="6" ' . ( ( 5 < $bathroom ) ? 'selected="selected"' : '' ) . '>+5</option>
									</select>
								</div>
							';
			endif;
			
			if ( ! empty( $instance['garage'] ) && $instance['garage'] === 'on' ):
				echo '
								<div class="search-field" >
									<select id="garages" name="garages">
										<option value="">' . esc_html__( 'Garages', 'realty-house-pl' ) . '</option>
										<option value="1" ' . ( ( 1 == $garages ) ? 'selected="selected"' : '' ) . '>1</option>
										<option value="2" ' . ( ( 2 == $garages ) ? 'selected="selected"' : '' ) . '>2</option>
										<option value="3" ' . ( ( 3 == $garages ) ? 'selected="selected"' : '' ) . '>3</option>
										<option value="4" ' . ( ( 4 == $garages ) ? 'selected="selected"' : '' ) . '>4</option>
										<option value="5" ' . ( ( 5 == $garages ) ? 'selected="selected"' : '' ) . '>5</option>
										<option value="6" ' . ( ( 5 < $garages ) ? 'selected="selected"' : '' ) . '>+5</option>
									</select>
								</div>
							';
			endif;
			
			if ( ! empty( $instance['keywords'] ) && $instance['keywords'] === 'on' ):
				echo '
								<div class="search-field" >
									<input type="text" placeholder="' . esc_html__( 'Keywords', 'realty-house-pl' ) . '" id="property-keywords" name="keywords" value="' . esc_attr( $keywords ) . '">
								</div>
							';
			endif;
			
			$currency_info = new realty_house_currency();
			$price_unit    = $currency_info->get_current_currency();
			
			if ( ! empty( $instance['price'] ) && $instance['price'] === 'on' ):
				echo '
								<div class="search-field">
									<input type="text" id="price_range" class="range-slider" name="price" value="" data-inputValuesSeparator="-" data-from="' . ( ! empty( $price ) ? $price_array[0] : 0 ) . '" data-to="' . ( ! empty( $price ) ? $price_array[1] : ( ! empty( $instance['max_price'] ) ? esc_attr( $instance['max_price'] ) : 1000000000 ) ) . '"  data-min="0" data-max="' . ( ! empty( $instance['max_price'] ) ? esc_attr( $instance['max_price'] ) : 1000000000 ) . '" data-prefix="' . esc_attr( $price_unit['symbol'] ) . '" />
								</div>
							';
			endif;
			
			echo '
								<div class="btn-container">
									<input type="submit" class="btn btn-sm btn-default" value="' . esc_html__( 'Search', 'realty-house-pl' ) . '">
								</div>';
			if ( ( ! empty( $instance['amenities'] ) && $instance['amenities'] === 'on' ) || ( ! empty( $instance['tags'] ) && $instance['tags'] === 'on' ) ):
				echo '<a href="#" class="more-options"><i class="fa fa-plus"></i>More Advanced Search</a>';
			endif;
			
			echo '</div>';
			
			if ( ( ! empty( $instance['amenities'] ) && $instance['amenities'] === 'on' ) || ( ! empty( $instance['tags'] ) && $instance['tags'] === 'on' ) ) :
				echo '<div class="b-sec">';
				
				if ( ! empty( $instance['amenities'] ) && $instance['amenities'] === 'on' ):
					
					foreach ( $realty_house_opt['realty-house-property-amenities'] as $index => $property_amenity )
					{
						echo '
									<div class="search-field">
										<div class="hsq-checkbox check-box-container">
											<label for="property_amenities[' . $index . ']">
												<input type="checkbox" value="1" id="property_amenities[' . $index . ']" name="property_amenities[' . $index . ']" ' . ( ! empty( $property_amenities ) && array_key_exists( $index, $property_amenities ) ? 'checked="checked"' : '' ) . ' >
												<span></span>
												' . esc_html( $property_amenity ) . '
											</label>
										</div>
									</div>
								';
					}
				endif;
				
				if ( ! empty( $instance['tags'] ) && $instance['tags'] === 'on' ):
					echo '
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_new_tag">
											<input type="checkbox" value="1" id="property_new_tag" name="property_new_tag" ' . ( $property_new_tag ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'New', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_hot_offer">
											<input type="checkbox" value="1" id="property_hot_offer" name="property_hot_offer" ' . ( $property_hot_offer ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'Hot Offer', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_featured">
											<input type="checkbox" value="1" id="property_featured" name="property_featured" ' . ( $property_featured ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'Featured', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_price_cut">
											<input type="checkbox" value="1" id="property_price_cut" name="property_price_cut" ' . ( $property_price_cut ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'Price Cut', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_open_house">
											<input type="checkbox" value="1" id="property_open_house" name="property_open_house" ' . ( $property_open_house ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'Open House', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
								<div class="search-field">
									<div class="hsq-checkbox check-box-container">
										<label for="property_foreclosure">
											<input type="checkbox" value="1" id="property_foreclosure" name="property_foreclosure" ' . ( $property_foreclosure ? 'checked="checked"' : '' ) . '>
											<span></span>
											' . esc_html__( 'Foreclosure', 'realty-house-pl' ) . '
										</label>
									</div>
								</div>
							';
				endif;
				
				echo '</div>';
			endif;
			
			echo '</form>
			</div>';
			
			echo balancetags( $args['after_widget'] );
			
		}
		
		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance )
		{
			$title     = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Find Your Home', 'realty-house-pl' );
			$class     = ! empty( $instance['class'] ) ? $instance['class'] : '';
			$location  = ! empty( $instance['location'] ) ? $instance['location'] : 'on';
			$status    = ! empty( $instance['status'] ) ? $instance['status'] : 'on';
			$p_type    = ! empty( $instance['p_type'] ) ? $instance['p_type'] : 'on';
			$bed       = ! empty( $instance['bed'] ) ? $instance['bed'] : 'on';
			$bath      = ! empty( $instance['bath'] ) ? $instance['bath'] : 'on';
			$garage    = ! empty( $instance['garage'] ) ? $instance['garage'] : 'on';
			$price     = ! empty( $instance['price'] ) ? $instance['price'] : 'on';
			$max_price = ! empty( $instance['max_price'] ) ? $instance['max_price'] : 1000000000;
			$keywords  = ! empty( $instance['keywords'] ) ? $instance['keywords'] : 'on';
			$amenities = ! empty( $instance['amenities'] ) ? $instance['amenities'] : 'on';
			$tags      = ! empty( $instance['tags'] ) ? $instance['tags'] : 'on';
			?>
			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'Class:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $class ); ?>">
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'location' ) ); ?>"
					<?php echo ! empty( $location ) && $location != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"><?php esc_html_e( 'Property Location', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'status' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'status' ) ); ?>"
					<?php echo ! empty( $status ) && $status != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'status' ) ); ?>"><?php esc_html_e( 'Property Status', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'p_type' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'p_type' ) ); ?>"
					<?php echo ! empty( $p_type ) && $p_type != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'p_type' ) ); ?>"><?php esc_html_e( 'Property Type', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'bed' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'bed' ) ); ?>"
					<?php echo ! empty( $bed ) && $bed != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bed' ) ); ?>"><?php esc_html_e( 'Bedrooms', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'bath' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'bath' ) ); ?>"
					<?php echo ! empty( $bath ) && $bath != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bath' ) ); ?>"><?php esc_html_e( 'Bathrooms', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'garage' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'garage' ) ); ?>"
					<?php echo ! empty( $garage ) && $garage != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'garage' ) ); ?>"><?php esc_html_e( 'Garages', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'price' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'price' ) ); ?>"
					<?php echo ! empty( $price ) && $price != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'price' ) ); ?>"><?php esc_html_e( 'Price', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'max_price' ) ); ?>"><?php esc_html_e( 'Max Price :', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_price' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'max_price' ) ); ?>" type="number"
					   value="<?php echo esc_attr( $max_price ); ?>">
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'keywords' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'keywords' ) ); ?>"
					<?php echo ! empty( $keywords ) && $keywords != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'keywords' ) ); ?>"><?php esc_html_e( 'Keywords', 'realty-house-pl' ); ?></label>
			</p>
			<hr>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'amenities' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'amenities' ) ); ?>"
					<?php echo ! empty( $amenities ) && $amenities != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'realty-house-pl' ); ?></label>
			</p>
			<p>
				<input type="checkbox"
					   id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>"
					<?php echo ! empty( $tags ) && $tags != 'off' ? ' checked="checked"' : ''; ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php esc_html_e( 'Tags', 'realty-house-pl' ); ?></label>
			</p>
			<?php
		}
		
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance )
		{
			$instance              = array ();
			$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['class']     = ( ! empty( $new_instance['class'] ) ) ? strip_tags( $new_instance['class'] ) : '';
			$instance['location']  = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : 'off';
			$instance['status']    = ( ! empty( $new_instance['status'] ) ) ? strip_tags( $new_instance['status'] ) : 'off';
			$instance['p_type']    = ( ! empty( $new_instance['p_type'] ) ) ? strip_tags( $new_instance['p_type'] ) : 'off';
			$instance['bed']       = ( ! empty( $new_instance['bed'] ) ) ? strip_tags( $new_instance['bed'] ) : 'off';
			$instance['bath']      = ( ! empty( $new_instance['bath'] ) ) ? strip_tags( $new_instance['bath'] ) : 'off';
			$instance['garage']    = ( ! empty( $new_instance['garage'] ) ) ? strip_tags( $new_instance['garage'] ) : 'off';
			$instance['price']     = ( ! empty( $new_instance['price'] ) ) ? strip_tags( $new_instance['price'] ) : 'off';
			$instance['max_price'] = ( ! empty( $new_instance['max_price'] ) ) ? strip_tags( $new_instance['max_price'] ) : 'off';
			$instance['keywords']  = ( ! empty( $new_instance['keywords'] ) ) ? strip_tags( $new_instance['keywords'] ) : 'off';
			$instance['amenities'] = ( ! empty( $new_instance['amenities'] ) ) ? strip_tags( $new_instance['amenities'] ) : 'off';
			$instance['tags']      = ( ! empty( $new_instance['tags'] ) ) ? strip_tags( $new_instance['tags'] ) : 'off';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_search_property' );
		}
		
		public function add_required_scripts()
		{
			global $realty_house_opt;
			$protocol = is_ssl() ? 'https' : 'http';
			wp_enqueue_script( "google-map", $protocol . '://maps.googleapis.com/maps/api/js?libraries=places' . ( ! empty( $realty_house_opt['opt-map-api'] ) ? '&amp;key=' . esc_attr( $realty_house_opt['opt-map-api'] ) : '' ), array (), get_bloginfo( 'version' ), true );
			wp_enqueue_script( 'ion-range-slider ', REALTY_HOUSE_PLG_JS_PATH . 'ion.rangeSlider.min.js', array (), REALTY_HOUSE_PLG_VERSION, true );
		}
		
	}
	
	new Realty_house_search_property();