<?php
	
	/*
	 * Plugin Name: Realty House New Properties
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the new properties of website
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_new_properties extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_new_properties', // Base ID
				esc_html__( 'Realty House New Properties', 'realty-house-pl' ), // Name
				array ( 'description' => esc_html__( 'Show the new properties in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array ( $this, 'register_widgets' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			
			$property_info = new realty_house_get_info();
			
			echo balancetags( $args['before_widget'] );
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			$new_property_args = array (
				'numberposts' => ( $instance['post_num'] != '' ? $instance['post_num'] : 4 ),
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'post_type'   => 'property',
				'post_status' => 'publish'
			);
			
			$new_properties = wp_get_recent_posts( $new_property_args );
			if ( $new_properties )
			{
				echo '<div class="widget-content new-properties"><ul>';
				foreach ( $new_properties as $new_property )
				{
					$p_info = $property_info->property_info( $new_property['ID'] );
					
					echo '<li class="clearfix ' . ( $p_info['gallery']['count'] == 0 ? 'single' : '' ) . '">';
					if ( $p_info['gallery']['count'] > 0 )
					{
						echo '
							<div class="img-container col-xs-4">
								<a href="' . esc_url( $p_info['url'] ) . '">
									' . ( $p_info['gallery']['count'] > 0 ? $p_info['gallery']['img'][0]['code']['large'] : '' ) . '
								</a>
							</div>
							';
					}
					echo '
					
							<div class="property-info ' . ( $p_info['gallery']['count'] > 0 ? 'col-xs-8' : 'col-xs-12' ) . '">
								<a href="' . esc_url( $p_info['url'] ) . '" class="title">' . esc_html( $p_info['title'] ) . '</a>
								<div class="features">
									<div class="box">' . esc_html( $p_info['bedroom'] ) . ' ' . esc_html__( 'bd', 'realty-house-pl' ) . '</div>
									<div class="box">' . esc_html( $p_info['bathroom'] ) . ' ' . esc_html__( 'ba', 'realty-house-pl' ) . '</div>
									<div class="box">' . esc_html( $p_info['building_size'] ) . ' ' . esc_html( $p_info['area_unit'] ) . '</div>
								</div>
								<div class="price">' . wp_kses_post( $p_info['price']['generated'] ) . '</div>
							</div>
                        </li>';
				}
				echo '</ul></div>';
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
			$title    = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New Properties', 'realty-house-pl' );
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
						for="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"><?php esc_html_e( 'Post Count:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'post_num' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $post_num ); ?>">
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
			$instance             = array ();
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['post_num'] = ( ! empty( $new_instance['post_num'] ) ) ? strip_tags( $new_instance['post_num'] ) : '';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_new_properties' );
		}
		
	}
	
	new Realty_house_new_properties();