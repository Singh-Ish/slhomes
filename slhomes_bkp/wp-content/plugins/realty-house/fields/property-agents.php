<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Room Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Home_square_plg_property_agents_meta_box
	{
		/**
		 * Array of meta data list for the block dates
		 * @var array
		 */
		public $property_agents_meta_fields = array();
		public $current_agent_id;
		public $current_user_username;
		public $current_user_title;
		public $user_is_administrator;
		
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			// Field Array
			$prefix                            = 'property_';
			$this->property_agents_meta_fields = array(
				array(
					'label' => esc_html__( 'Agents', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Select the agents for this property', 'realty-house-pl' ),
					'id'    => $prefix . 'agents',
					'type'  => 'agent_box'
				)
			);
			
			add_action( 'save_post', array( $this, 'save_property_agents_meta' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_property_agents_meta_box' ) );
		}
		
		public function get_user_information()
		{
			$current_user_info           = wp_get_current_user();
			$this->current_user_username = $current_user_info->data->user_login;
			$this->user_is_administrator = array_key_exists( 'administrator', $current_user_info->caps );
			
			$current_agent_info = array(
				'post_type'   => 'agent',
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'   => 'staff_username',
						'value' => $this->current_user_username
					)
				)
			);

			$properties_agent = new WP_Query( $current_agent_info );
			
			if ( $properties_agent->have_posts() )
			{

				foreach ( $properties_agent->get_posts() as $post_item );
				{
					$this->current_agent_id   = $post_item->ID;
					$this->current_user_title = $post_item->post_title;
				}
			}
			wp_reset_postdata();
		}
		
		
		// Add the Meta Box
		function add_property_agents_meta_box()
		{
			add_meta_box( 'property_agent_meta_box', // $id
				esc_html__( 'Property Agents', 'realty-house-pl' ), array(
					$this,
					'show_property_agents_meta_box'
				), // $callback
				'property', // $page
				'side' ); // $priority
			
		}
		
		// Show the Fields in the Post Type section
		function show_property_agents_meta_box()
		{
			global $post;
			
			// Use nonce for verification
			echo '<input type="hidden" name="property_agents_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
			
			
			// Begin the field table and loop
			echo '
				<div class="table-title">' . esc_html__( 'Property Agents', 'realty-house-pl' ) . '</div>
				<div class="table-container">    
				<table class="form-table">';
			
			foreach ( $this->property_agents_meta_fields as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				$this->get_user_information();
				
				echo '<tr>
    	                <td>';
				switch ( $field['type'] )
				{
					
					// Gallery
					case 'agent_box':
						if ( ! empty( $this->user_is_administrator ) )
						{
							$property_agent_arg = array(
								'posts_per_page' => 1000,
								'post_type'      => 'agent',
								'post_status'    => 'publish'
							);
							$property_agent_arr = get_posts( $property_agent_arg );
							
							echo '<select name="' . $field['id'] . '[]" id="' . $field['id'] . '" multiple="multiple">';
							foreach ( $property_agent_arr as $property_agent )
							{
								echo '<option value="' . $property_agent->ID . '" ' . ( in_array( $property_agent->ID, $meta ) ? esc_html( ' selected="selected"' ) : '' ) . '>' . $property_agent->post_title . '</option>';
							}
							echo '</select><br /><span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
						}
						else
						{
							echo '<div id="single-agent-box">' . esc_html( $this->current_user_title ) . '</div>';
						}
					
					
					break;
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>'; // end table
		}
		
		// Save the Data
		function save_property_agents_meta( $post_id )
		{
			$security_code = '';
			
			if ( isset( $_POST['property_agents_meta_box_nonce'] ) && $_POST['property_agents_meta_box_nonce'] != '' )
			{
				$security_code = sanitize_text_field( $_POST['property_agents_meta_box_nonce'] );
			}
			
			// verify nonce
			if ( ! wp_verify_nonce( $security_code, basename( __FILE__ ) ) )
			{
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			{
				return $post_id;
			}
			// check permissions
			if ( 'property' == $_POST['post_type'] )
			{
				if ( ! current_user_can( 'edit_post', $post_id ) )
				{
					return $post_id;
				}
			}
			
			$this->get_user_information();
			
			// loop through fields and save the data
			foreach ( $this->property_agents_meta_fields as $field )
			{
				$old = get_post_meta( $post_id, $field['id'], true );
				$new = str_replace( ' ,', '', $_POST[ $field['id'] ] );
				if ( $new && $new != $old )
				{
					update_post_meta( $post_id, $field['id'], $new );
				}
				elseif ( '' == $new && $old )
				{
					delete_post_meta( $post_id, $field['id'], $old );
				}
			} // end foreach
			
			
			if ( empty( $this->user_is_administrator ) )
			{
				update_post_meta( $post_id, 'property_agents', array( $this->current_agent_id ) );
			}
			
		}
	}
	
	$property_agents_meta_box_obj = new Home_square_plg_property_agents_meta_box;