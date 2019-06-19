<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Page Class Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Realty_house_plg_staff_meta_box
	{
		/**
		 * Array of meta data list for the block dates
		 * @var array
		 */
		public $staff_meta_fields = array();
		
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			// Field Array
			$prefix = 'staff_';
			$this->staff_meta_fields = array(
				array(
					'label' => esc_html__( 'Username', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the username of the agent, please note that this username can not be changed in future.', 'realty-house-pl' ),
					'id'    => $prefix . 'username',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Address', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the address of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'address',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Phone', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the phone number of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'phone',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Mobile', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the mobile number of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'mobile',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Email', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the email of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'email',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Skype', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the Skype ID of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'skype',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Facebook', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the facebook ID of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'facebook',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Twitter', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the twitter ID of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'twitter',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Google Plus', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add the google plus of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'google_plus',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Start Date of Membership', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add from when this agent is member of your website', 'realty-house-pl' ),
					'id'    => $prefix . 'start_membership',
					'type'  => 'date',
				),
				array(
					'label' => esc_html__( 'License', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Add license number of the agent', 'realty-house-pl' ),
					'id'    => $prefix . 'license',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Agent Background Image', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Upload your desired image as background image agent boxes.', 'realty-house-pl' ),
					'id'    => $prefix . 'bg_img',
					'type'  => 'image'
				)
			);
			
			add_action( 'add_meta_boxes', array( $this, 'add_staff_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_staff_meta' ) );
		}
		
		// Add the Meta Box
		function add_staff_meta_box()
		{
			add_meta_box( 'staff_meta_box', // $id
				esc_html__( 'Additional information', 'realty-house-pl' ), array(
					$this,
					'show_staff_meta_box'
				), // $callback
				'agent', // $page
				'normal', // $context
				'high' ); // $priority
		}
		
		// Show the Fields in the Post Type section
		function show_staff_meta_box()
		{
			global $post;
			
			// Use nonce for verification
			echo '<input type="hidden" name="staff_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
			
			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ( $this->staff_meta_fields as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				
				echo '<tr>
                        <th>' . esc_html( $field['label'] ) . '</th>
    	                <td>';
				switch ( $field['type'] )
				{
					// text
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" '. ($field['id'] == 'staff_username' && !empty($meta) ? esc_html('readonly') : '') .' />
                                    <br /><span class="description">' . esc_html( $field['desc'] ) . '</span>';
					break;
					case 'date':
						echo '<input type="text" class="datepicker " name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . ( $meta ? $meta : "" ) . '" size="30" />
    									<span class="description">' . esc_html( $field['desc'] ) . '</span>';
					break;
					case 'image':
						$image = plugins_url() . '/realty-house/assets/img/agent-bg.png';
						echo '<span class="custom_default_image" style="display:none">' . wp_kses_post( $image ) . '</span>';
						if ( $meta )
						{
							$image = wp_get_attachment_image_src( $meta, 'medium' );
							$image = $image[0];
						}
						echo '
                                    <div class="img_uploader_post_meta">
                                        <input name="' . esc_attr( $field['id'] ) . '" type="hidden" class="custom_upload_image" value="' . esc_attr( $meta ) . '" />
                                        <img src="' . esc_attr( $image ) . '" class="custom_preview_image" alt="" /><br />
                                        <input class="custom_upload_image_button button" type="button" value="' . esc_html__( 'Choose Image', 'realty-house-pl' ) . '" />
                                        <small> <a href="#" class="custom_clear_image_button button button-primary">' . esc_html__( 'Remove Image', 'realty-house-pl' ) . '</a></small>
                                        <br clear="all" /><span class="description">' . esc_html( $field['desc'] ) . '</span>
                                    </div>
                                    ';
					break;
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table>'; // end table
		}
		
		// Save the Data
		function save_staff_meta( $post_id )
		{
			$security_code = '';
			
			if ( isset( $_POST['staff_meta_box_nonce'] ) && $_POST['staff_meta_box_nonce'] != '' )
			{
				$security_code = sanitize_text_field( $_POST['staff_meta_box_nonce'] );
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
			if ( 'agent' == $_POST['post_type'] )
			{
				if ( ! current_user_can( 'edit_post', $post_id ) )
				{
					return $post_id;
				}
			}
			
			// loop through fields and save the data
			foreach ( $this->staff_meta_fields as $field )
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
	
	$staff_meta_box_obj = new Realty_house_plg_staff_meta_box;
