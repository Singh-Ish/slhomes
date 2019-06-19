<?php
	
	/*
	 * Plugin Name: Realty House Ask Question
	 * Plugin URI: http://www.RavisTheme.com
	 * Description: Show the ask question box
	 * Version: 1.0
	 * Author: Joseph_a
	 * Author URI: http://themeforest.net/user/RavisTheme
	 */
	
	class Realty_house_ask_question extends WP_Widget
	{
		
		function __construct()
		{
			parent::__construct( 'realty_house_ask_question', // Base ID
				esc_html__( 'Realty House Ask Question', 'realty-house-pl' ), // Name
				array( 'description' => esc_html__( 'Show Ask Question Box in the sidebars', 'realty-house-pl' ), ) // Args
			);
			
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}
		
		/**
		 * Front-end display of widget
		 **/
		public function widget( $args, $instance )
		{
			extract( $args );
			
			$widget_width  = ! empty( $instance['class'] ) ? $instance['class'] : '';
			$before_widget = str_replace( 'class="', 'class="' . $widget_width . ' ', $args['before_widget'] );
			
			echo balancetags( $before_widget );
			
			if ( ! empty( $instance['title'] ) )
			{
				echo balancetags( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . balancetags( $args['after_title'] );
			}
			echo '<div class="widget-content ask-question">
			<div class="content">
				<div class="contact-form">
					<div class="contact-info">';
			
			if ( ! empty( $instance['phone'] ) )
			{
				echo '<span>' . esc_html__( 'Call Us: ', 'realty-house-pl' ) . '</span>' . ( ! empty( $instance['phone'] ) ? esc_html( $instance['phone'] ) : '' ) . '</div>';
			}
			
			if ( ! empty( $instance['form'] ) )
			{
				if ( ! empty( $instance['phone'] ) )
				{
					echo '<div class="separator">' . esc_html__( 'or', 'realty-house-pl' ) . '</div>';
				}
				echo do_shortcode( $instance['form'] );
			}
			echo '
					</div>
				</div>
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
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Ask a Question', 'realty-house-pl' );
			$form  = ! empty( $instance['form'] ) ? $instance['form'] : '';
			$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
			$class = ! empty( $instance['class'] ) ? $instance['class'] : 'type-1';
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
					   value="<?php echo esc_attr( $class ); ?>" placeholder="<?php esc_html_e( 'type-1 / type-2', 'realty-house-pl' ) ?>">
			</p>
			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone Number:', 'realty-house-pl' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $phone ); ?>">
			</p>
			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>"><?php esc_html_e( 'Form ShortCode:', 'realty-house-pl' ); ?></label>
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form' ) ); ?>" placeholder="<?php esc_html_e( 'Paset the Contact 7 shortcode in this field', 'realty-house-pl' ) ?>"><?php echo esc_attr( $form ); ?></textarea>
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
			$instance          = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['form']  = ( ! empty( $new_instance['form'] ) ) ? strip_tags( $new_instance['form'] ) : '';
			$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'] ) : '';
			$instance['class'] = ( ! empty( $new_instance['class'] ) ) ? strip_tags( $new_instance['class'] ) : '';
			
			return $instance;
		}
		
		public function register_widgets()
		{
			register_widget( 'Realty_house_ask_question' );
		}
		
	}
	
	new Realty_house_ask_question();