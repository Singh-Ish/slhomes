<?php
	
	/*
	 * Plugin Name: Realty House Testimonials
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the testimonials of website
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_testimonials extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_testimonials', // Base ID
				esc_html__( 'Realty House Testimonials', 'realty-house-pl' ), // Name
				array( 'description' => esc_html__( 'Show the testimonials in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			$testimonials_info = new realty_house_get_info();
			
			echo balancetags( $args['before_widget'] );
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			$new_property_args = array(
				'numberposts' => ( $instance['post_num'] != '' ? $instance['post_num'] : 4 ),
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'post_type'   => 'testimonials',
				'post_status' => 'publish'
			);
			
			$testimonials = wp_get_recent_posts( $new_property_args );
			if ( $testimonials )
			{
				echo '<div class="widget-content testimonials">';
				foreach ( $testimonials as $testimonial )
				{
					$t_info = $testimonials_info->testimonials_info($testimonial['ID']);
					
					echo '<div class="testi-box '.(empty( $t_info['has_image'] ) ? esc_html('single') : '').' ">';
						if ( ! empty( $t_info['has_image'] ) )
						{
							echo '
								<div class="l-sec">
									' . wp_kses_post( $t_info['img']['full'] ) . '
								</div>';
						}
					echo '	
						<div class="r-sec">
							<div class="title">' . esc_html( $t_info['name'] ) . '</div>
							<div class="desc">' . esc_html( $t_info['content']['main'] ) . '</div>
						</div>
					</div>
					';
				}
				echo '</div>';
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
			$title    = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'White Clients Say', 'realty-house-pl' );
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
					for="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"><?php esc_html_e( 'Count:', 'realty-house-pl' ); ?></label>
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
			$instance             = array();
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['post_num'] = ( ! empty( $new_instance['post_num'] ) ) ? strip_tags( $new_instance['post_num'] ) : '';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_testimonials' );
		}
		
	}
	
	new Realty_house_testimonials();