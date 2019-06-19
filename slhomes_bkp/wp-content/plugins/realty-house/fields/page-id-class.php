<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Page Class Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Home_square_plg_page_class_meta_box
	{
		/**
		 * Array of meta data list for the events
		 * @var array
		 */
		public $page_class_meta_fields = array();
		
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			$prefix = 'realty_house_page_';
			$this->page_class_meta_fields = array(
				array(
					'label' => esc_html__( 'Page ID', 'realty-house-pl' ),
					'desc'  => __( 'Add your custom ID for using it to change the default style. List of the default page IDs are <a href="#" target="_blank">here</a>', 'realty-house-pl' ),
					'id'    => $prefix . 'id',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Page Class', 'realty-house-pl' ),
					'desc'  => __( 'Add your custom class for using it to change the default style. List of the default page classes are <a href="#" target="_blank">here</a>', 'realty-house-pl' ),
					'id'    => $prefix . 'class',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Map on Breadcrumb', 'realty-house-pl' ),
					'desc'  => esc_html__( 'Check if you want to show the map instead of image for your breadcrumb. If you check this checkbox the below image will be overridden by Google Map.', 'realty-house-pl' ),
					'id'    => $prefix . 'map_crumb',
					'type'  => 'checkbox'
				),
				array(
					'label' => esc_html__( 'Page Breadcrumb Background', 'realty-house-pl' ),
					'name'  => 'Image',
					'desc'  => esc_html__( 'Upload your desired image as page\'s breadcrumb.', 'realty-house-pl' ),
					'id'    => $prefix . 'bread_crumb',
					'type'  => 'image'
				)
			);
			
			add_action( 'add_meta_boxes', array( $this, 'add_page_class_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_page_class_meta' ) );
		}
		
		// Add the Meta Box
		function add_page_class_meta_box()
		{
			add_meta_box( 'page_class_meta_box', // $id
				esc_html__( 'Page Setting', 'realty-house-pl' ), array(
					$this,
					'show_page_class_meta_box'
				), // $callback
				'page', // $page
				'normal', // $context
				'high' ); // $priority
		}
		
		// Show the Fields in the Post Type section
		function show_page_class_meta_box()
		{
			global $post;
			
			if ( ( get_page_template_slug( $post->ID ) == '../templates/property-listing.php' ) or ( get_page_template_slug( $post->ID ) == '../templates/property-listing-rows.php' )  or ( get_page_template_slug( $post->ID ) == '../templates/property-listing-masonry.php' )  or ( get_page_template_slug( $post->ID ) == '../templates/property-bookmark.php' )  or ( get_page_template_slug( $post->ID ) == 'templates/contact.php' ) )
			{
				$p_listing_page_template = true;
			}
			
			// Use nonce for verification
			echo '<input type="hidden" name="page_class_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
			
			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ( $this->page_class_meta_fields as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr ' . ( $field['id'] == 'realty_house_page_map_crumb' ? 'id="google-map-bread"' : '' ) . ( $field['id'] == 'realty_house_page_map_crumb' && empty( $p_listing_page_template ) ? 'class="hidden"' : '' ) . '>
                        <th>' . esc_html( $field['label'] ) . '</th>
                        <td>';
				switch ( $field['type'] )
				{
					// text
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" /> 
                                    <br /><span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
					break;
					case 'checkbox':
						echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ' . ( $meta ? ' checked="checked"' : '' ) . '/><span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
					break;
					case 'image':
						$image = get_template_directory_uri() . '/assets/img/internal-heade-default.png';
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
		function save_page_class_meta( $post_id )
		{
			$security_code = '';
			
			if ( isset( $_POST['page_class_meta_box_nonce'] ) && $_POST['page_class_meta_box_nonce'] != '' )
			{
				$security_code = sanitize_text_field( $_POST['page_class_meta_box_nonce'] );
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
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
			
			// loop through fields and save the data
			foreach ( $this->page_class_meta_fields as $field )
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
	
	$page_class_meta_box_obj = new Home_square_plg_page_class_meta_box;

