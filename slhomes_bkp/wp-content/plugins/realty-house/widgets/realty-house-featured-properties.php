<?php
	
	/*
	 * Plugin Name: Realty House Featured Properties
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the featured properties of website
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_featured_properties extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_featured_properties', // Base ID
				esc_html__( 'Realty House Featured Properties', 'realty-house-pl' ), // Name
				array( 'description' => esc_html__( 'Show the latest featured properties in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			global $realty_house_opt;
			
			$property_info = new realty_house_get_info();
			
			echo balancetags( $args['before_widget'] );
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			
			switch ( $instance['type'] )
			{
				case( '2' ):
					$new_property_args = array(
						'numberposts' => 1,
						'orderby'     => 'post_date',
						'order'       => 'DESC',
						'meta_key'    => 'property_featured',
						'post_type'   => 'property',
						'post_status' => 'publish'
					);
					
					$featured_properties = wp_get_recent_posts( $new_property_args );
					if ( $featured_properties )
					{
						echo '<div class="widget-content featured-properties">';
						foreach ( $featured_properties as $new_property )
						{
							
							$p_info = $property_info->property_info( $new_property['ID'] );
							echo '
							<div class="property-box">
								<div class="inner-box">';
							if ( $p_info['gallery']['count'] > 0 )
							{
								echo '
									<a href="' . esc_url( $p_info['url'] ) . '" class="img-container">
										' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
									</a>
								';
							}
							
							echo '
									<div class="title">' . esc_html( $p_info['title'] ) . '</div>
									<div class="price-fav">
										<div class="price">' . wp_kses_post( $p_info['price']['generated'] ) . '</div>
										<div class="fav-compare">
											<a href="#" class="realty-house-loading compare compare-btn '. (!empty($p_info['in_comparision']) ? esc_attr('active') : '' ) .'" data-p-id="'. esc_attr($new_property['ID']) .'"></a>
											<a href="#" class="realty-house-star fav bookmark-btn '. (!empty($p_info['bookmarked']) ? esc_attr('active') : '' ) .'" data-p-id="'. esc_attr($new_property['ID']) .'"></a>
										</div>
									</div>
									<div class="location">' . esc_html( $p_info['address'] ) . '</div>
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
								</div>
							</div>';
						}
						echo '</div>';
					}
				break;
				default:
					$new_property_args = array(
						'numberposts' => ( $instance['post_num'] != '' ? $instance['post_num'] : 1 ),
						'orderby'     => 'post_date',
						'order'       => 'DESC',
						'meta_key'    => 'property_featured',
						'post_type'   => 'property',
						'post_status' => 'publish'
					);
					$featured_properties = wp_get_recent_posts( $new_property_args );
					if ( $featured_properties )
					{
						echo '<div class="widget-content featured-properties">';
						foreach ( $featured_properties as $new_property )
						{
							$p_info = $property_info->property_info( $new_property['ID'] );

							echo '
							<div class="property-box clearfix">
								<div class="l-sec col-xs-4">';
							if ( $p_info['gallery']['count'] > 0 )
							{
								echo '
									<a href="' . esc_url( $p_info['url'] ) . '" class="img-container">
										' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
									</a>
								';
							}
							echo '									
								</div>
								<div class="r-sec col-xs-8">
									<a href="' . esc_url( $p_info['url'] ) . '" class="title">' . esc_html( $p_info['title'] ) . '</a>
									<div class="p-type">' . esc_html( $p_info['type']['title'] ) . '</div>
									<div class="price">' . balanceTags( $p_info['price']['generated'] ) . '</div>
								</div>
							</div>';
						}
						echo '</div>';
					}
				break;
				
			}
			
			
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
			$title    = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Listing', 'realty-house-pl' );
			$type     = ! empty( $instance['type'] ) ? $instance['type'] : 1;
			$post_id  = ! empty( $instance['post_id'] ) ? $instance['post_id'] : '';
			$post_num = ! empty( $instance['post_num'] ) ? $instance['post_num'] : 4;
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
					for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Type:', 'realty-house-pl' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
					<option value="1" <?php echo ($type == 1) ? esc_attr( 'selected="selected"' ) : '' ?>><?php esc_html_e( 'Multi', 'realty-house-pl' ) ?></option>
					<option value="2" <?php echo ($type == 2) ? esc_attr( 'selected="selected"' ) : '' ?>><?php esc_html_e( 'Single', 'realty-house-pl' ) ?></option>
				</select>
			</p>
			<p class="p_count_container <?php echo ($type != 1) ? esc_attr( 'hidden' ) : '' ?>">
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"><?php esc_html_e( 'Property Count:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'post_num' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $post_num ); ?>" placeholder="<?php esc_html_e( 'Insert how many properties you want to show?', 'realty-house-pl' ) ?>">
			</p>
			<p class="p_id_container <?php echo ($type != 2) ? esc_attr( 'hidden' ) : '' ?>">
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'Property ID:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $post_id ); ?>" placeholder="<?php esc_html_e( 'Insert the property ID', 'realty-house-pl' ) ?>">
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
			$instance             = array();
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['type']     = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
			$instance['post_id']  = ( ! empty( $new_instance['post_id'] ) ) ? strip_tags( $new_instance['post_id'] ) : '';
			$instance['post_num'] = ( ! empty( $new_instance['post_num'] ) ) ? strip_tags( $new_instance['post_num'] ) : '';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_featured_properties' );
		}
		
	}
	
	new Realty_house_featured_properties();