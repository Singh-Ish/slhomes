<?php
	
	/*
	 * Plugin Name: Realty House Featured Agent
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the featured agent of website
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_featured_agent extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_featured_agent', // Base ID
				esc_html__( 'Realty House Featured Agent', 'realty-house-pl' ), // Name
				array( 'description' => esc_html__( 'Show a featured agent in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			
			echo balancetags( $args['before_widget'] );
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			
			if ( ! empty( $instance['agent_id'] ) )
			{
				$agent_info          = new realty_house_get_info();
				$selected_agent_info = $agent_info->agent_info( $instance['agent_id'] );
				echo '
					<div class="agent-box">
						<div class="inner-box">
							<div class="img-container">
								' . ( ! empty( $selected_agent_info['bg_img']['id'] ) ? $selected_agent_info['bg_img']['code'] : '<div class="no-image"></div>' ) . '
							</div>
							<div class="detail-box">
								<div class="agent-img">
									' . ( ! empty( $selected_agent_info['has_image'] ) ? wp_kses_post( $selected_agent_info['img']['full'] ) : '<div class="no-image"></div>' ) . '
								</div>
								<div class="name">' . esc_html( $selected_agent_info['name'] ) . '</div>
								<ul class="info">
									'.(!empty($selected_agent_info['phone']) ? '<li><i class="fa fa-phone"></i>' . esc_html( $selected_agent_info['phone'] ) . '</li>' : '').'
									'.(!empty($selected_agent_info['email']) ? '<li><i class="fa fa-envelope"></i>' . esc_html( $selected_agent_info['email'] ) . '</li>' : '').'
								</ul>
								<a href="' . esc_url( $selected_agent_info['url'] ) . '" class="btn btn-sm btn-default read-more">'.esc_html('Read More', 'realty-house-pl').'</a>
							</div>
						</div>
					</div>
				';
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
			$title    = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Agent', 'realty-house-pl' );
			$agent_id = ! empty( $instance['agent_id'] ) ? $instance['agent_id'] : '';
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
					for="<?php echo esc_attr( $this->get_field_id( 'agent_id' ) ); ?>"><?php esc_html_e( 'Agent ID:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'agent_id' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'agent_id' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $agent_id ); ?>">
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
			$instance['agent_id'] = ( ! empty( $new_instance['agent_id'] ) ) ? strip_tags( $new_instance['agent_id'] ) : '';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_featured_agent' );
		}
		
	}
	
	new Realty_house_featured_agent();