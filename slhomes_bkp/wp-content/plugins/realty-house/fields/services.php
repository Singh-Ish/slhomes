<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Services Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Home_square_plg_services_meta_box
	{
		/**
		 * Array of meta data list for the block dates
		 * @var array
		 */
		public $services_meta_fields = array();
		
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			// Field Array
			$prefix = 'services_';
			$this->services_meta_fields = array(
				array(
					'label' => esc_html__( 'Service Icon', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the title of related icon to this field.', 'realty-house-pl' ),
					'id'    => $prefix . 'icon',
					'type'  => 'text',
				)
			);
			
			add_action( 'add_meta_boxes', array( $this, 'add_services_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_services_meta' ) );
		}
		
		// Add the Meta Box
		function add_services_meta_box()
		{
			add_meta_box( 'services_meta_box', // $id
				esc_html__( 'Service Extra Information', 'realty-house-pl' ), array(
					$this,
					'show_services_meta_box'
				), // $callback
				'service', // $page
				'normal', // $context
				'high' ); // $priority
		}
		
		
		// Show the Fields in the Post Type section
		function show_services_meta_box()
		{
			global $post;
			
			// Use nonce for verification
			echo '<input type="hidden" name="services_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
			
			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ( $this->services_meta_fields as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                        <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
    	                <td>';
				switch ( $field['type'] )
				{
					// checkbox
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="30" />
                                    <br /><span class="description">' . esc_html( $field['desc'] ) . '</span>';
					break;
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table>'; // end table
		}
		
		// Save the Data
		function save_services_meta( $post_id )
		{
			$security_code = '';
			
			if ( isset( $_POST['services_meta_box_nonce'] ) && $_POST['services_meta_box_nonce'] != '' )
			{
				$security_code = sanitize_text_field( $_POST['services_meta_box_nonce'] );
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
			if ( 'services' == $_POST['post_type'] )
			{
				if ( ! current_user_can( 'edit_post', $post_id ) )
				{
					return $post_id;
				}
			}
			
			// loop through fields and save the data
			foreach ( $this->services_meta_fields as $field )
			{
				$old = get_post_meta( $post_id, $field['id'], true );
				$new = $_POST[ $field['id'] ];
				if ( $new && $new != $old )
				{
					update_post_meta( $post_id, $field['id'], $new );
				}
				elseif ( '' == $new && $old )
				{
					delete_post_meta( $post_id, $field['id'], $old );
				}
			} // end foreach
		}
	}
	
	
	$services_meta_obj = new Home_square_plg_services_meta_box;