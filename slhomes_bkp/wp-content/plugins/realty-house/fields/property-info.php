<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Room Price Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Home_square_plg_property_info_meta_box
	{
		/**
		 * Array of meta data list for the block dates
		 * @var array
		 */
		public $property_info_meta_fields = array ();
		
		function __construct()
		{
			
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			
			add_action( 'init', array ( $this, 'define_meta_boxes' ) );
			add_action( 'add_meta_boxes', array ( $this, 'add_property_info_meta_box' ) );
			add_action( 'save_post', array ( $this, 'save_property_info_meta' ) );
		}
		
		// Meta Box Info
		function define_meta_boxes()
		{
			global $realty_house_opt;
			
			$poperty_status_arr = array ();
			if ( ! empty( $realty_house_opt['realty-house-property-status'] ) )
			{
				foreach ( $realty_house_opt['realty-house-property-status'] as $index => $property_status_item )
				{
					$poperty_status_arr[ $index + 1 ] = array (
						'label' => esc_html( $property_status_item ),
						'value' => $index + 1
					);
				}
			}
			
			$poperty_type_arr = array ();
			if ( ! empty( $realty_house_opt['realty-house-property-type'] ) )
			{
				foreach ( $realty_house_opt['realty-house-property-type'] as $index => $property_type_item )
				{
					$property_type_item_sections    = explode( '---', $property_type_item );
					$poperty_type_arr[ $index + 1 ] = array (
						'label' => esc_html( $property_type_item_sections[0] ),
						'value' => $index + 1
					);
				}
			}
			
			// Field Array
			$prefix                          = 'property_';
			$this->property_info_meta_fields = array (
				'basic_details'  => array (
					array (
						'label'   => esc_html__( 'Property Status', 'realty-house-pl' ),
						'desc'    => esc_html__( 'Change the property status by this field', 'realty-house-pl' ),
						'id'      => $prefix . 'property_status',
						'type'    => 'select',
						'options' => $poperty_status_arr
					),
					array (
						'label'   => esc_html__( 'Property Type', 'realty-house-pl' ),
						'desc'    => esc_html__( 'Change the property type by this field', 'realty-house-pl' ),
						'id'      => $prefix . 'property_type',
						'type'    => 'select',
						'options' => $poperty_type_arr
					),
					array (
						'label' => esc_html__( 'Bedroom', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the bedroom counts in this field', 'realty-house-pl' ),
						'id'    => $prefix . 'bedroom',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Bathroom', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the bedroom counts in this field', 'realty-house-pl' ),
						'id'    => $prefix . 'bathroom',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Total Building Size ', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the area of Building in "SQF"', 'realty-house-pl' ),
						'id'    => $prefix . 'building_size',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Total Lot Size ', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the area of loy in "SQF"', 'realty-house-pl' ),
						'id'    => $prefix . 'lot_size',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Garages', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the count of parkings in this field', 'realty-house-pl' ),
						'id'    => $prefix . 'parking',
						'type'  => 'text',
					),
					array (
						'label'   => esc_html__( 'Construction Status', 'realty-house-pl' ),
						'desc'    => esc_html__( 'Select the construction status of property', 'realty-house-pl' ),
						'id'      => $prefix . 'construction_status',
						'type'    => 'select',
						'options' => array (
							0 => array (
								'label' => esc_html__( 'Existing', 'realty-house-pl' ),
								'value' => 0
							),
							1 => array (
								'label' => esc_html__( 'Under Construction/Proposed', 'realty-house-pl' ),
								'value' => 1
							)
						),
						'default' => 0
					),
					array (
						'label' => esc_html__( 'Keywords', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the keywords for this property and separate theme with comma', 'realty-house-pl' ),
						'id'    => $prefix . 'keywords',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Property Visits', 'realty-house-pl' ),
						'desc'  => esc_html__( 'How many times this property was visited.', 'realty-house-pl' ),
						'id'    => $prefix . 'visits',
						'type'  => 'show'
					)
				),
				'price'          => array (
					array (
						'label' => esc_html__( 'Price', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add price of the property', 'realty-house-pl' ),
						'id'    => $prefix . 'price',
						'type'  => 'text',
					),
					array (
						'label' => esc_html__( 'Keep Price Confidential', 'realty-house-pl' ),
						'desc'  => esc_html__( 'If you want to hidden the price and replace it with "Call Us", check this checkbox', 'realty-house-pl' ),
						'id'    => $prefix . 'price_hidden',
						'type'  => 'checkbox',
					),
					array (
						'label'   => esc_html__( 'Price Type', 'realty-house-pl' ),
						'desc'    => esc_html__( 'Please select the type of price', 'realty-house-pl' ),
						'id'      => $prefix . 'price_type',
						'type'    => 'select',
						'options' => array (
							array (
								'label' => esc_html__( 'Per Month' ),
								'value' => 1
							),
							array (
								'label' => esc_html__( 'Per Week' ),
								'value' => 2
							),
							array (
								'label' => esc_html__( 'Per Night' ),
								'value' => 3
							),
							array (
								'label' => esc_html__( 'Annual' ),
								'value' => 4
							)
						)
					),
				),
				'gallery'        => array (
					array (
						'label' => esc_html__( 'Image Gallery', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the images of the property', 'realty-house-pl' ),
						'id'    => $prefix . 'slideshow_images',
						'type'  => 'gallery'
					)
				),
				'location'       => array (
					array (
						'label' => esc_html__( 'Address', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Enter the address of property', 'realty-house-pl' ),
						'id'    => $prefix . 'address',
						'type'  => 'text'
					),
					array (
						'label' => esc_html__( 'Longitude', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Enter the longitude of property', 'realty-house-pl' ),
						'id'    => $prefix . 'longitude',
						'type'  => 'text'
					),
					array (
						'label' => esc_html__( 'Latitude ', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Enter the latitude of property', 'realty-house-pl' ),
						'id'    => $prefix . 'latitude',
						'type'  => 'text'
					),
				),
				'neighborhood'   => array (
					array (
						'label' => esc_html__( 'Neighborhood', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add neighborhoods of property with this field.', 'realty-house-pl' ),
						'id'    => $prefix . 'neighborhood',
						'type'  => 'repeatable'
					)
				),
				'nearby_schools' => array (
					array (
						'label' => esc_html__( 'Nearby Schools', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add nearby schools of property with this field.', 'realty-house-pl' ),
						'id'    => $prefix . 'nearby_schools',
						'type'  => 'repeatable'
					)
				),
				'amenities'      => array (
					array (
						'label' => esc_html__( 'Amenities', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add amenities of property with this field.', 'realty-house-pl' ),
						'id'    => $prefix . 'amenities',
						'type'  => 'checkbox'
					)
				),
				'facts'          => array (
					array (
						'label' => esc_html__( 'Facts', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add some facts about the property by this field.', 'realty-house-pl' ),
						'id'    => $prefix . 'facts',
						'type'  => 'repeatable'
					)
				),
				'tags'           => array (
					array (
						'label' => esc_html__( 'New', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property is new', 'realty-house-pl' ),
						'id'    => $prefix . 'new_tag',
						'type'  => 'checkbox'
					),
					array (
						'label' => esc_html__( 'Hot Offer', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property is hot offer', 'realty-house-pl' ),
						'id'    => $prefix . 'hot_offer',
						'type'  => 'checkbox'
					),
					array (
						'label' => esc_html__( 'Featured', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property is featured', 'realty-house-pl' ),
						'id'    => $prefix . 'featured',
						'type'  => 'checkbox'
					),
					array (
						'label' => esc_html__( 'Price Cut', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property\'s price is cut off', 'realty-house-pl' ),
						'id'    => $prefix . 'price_cut',
						'type'  => 'checkbox'
					),
					array (
						'label' => esc_html__( 'Open House', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property is open house', 'realty-house-pl' ),
						'id'    => $prefix . 'open_house',
						'type'  => 'checkbox'
					),
					array (
						'label' => esc_html__( 'Foreclosure', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Check this checkbox if the property is foreclosure', 'realty-house-pl' ),
						'id'    => $prefix . 'foreclosure',
						'type'  => 'checkbox'
					),
				),
				'floor_plan'     => array (
					array (
						'label' => esc_html__( 'Image Gallery', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the floor plan of the property', 'realty-house-pl' ),
						'id'    => $prefix . 'floor_plan',
						'type'  => 'gallery'
					)
				),
				'attachments'    => array (
					array (
						'label' => esc_html__( 'Property Attachments', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add the attachment of the property', 'realty-house-pl' ),
						'id'    => $prefix . 'attachment',
						'type'  => 'gallery'
					)
				),
				'video'          => array (
					array (
						'label' => esc_html__( 'Video', 'realty-house-pl' ),
						'desc'  => esc_html__( 'Add an embed code of property\'s video in this field.', 'realty-house-pl' ),
						'id'    => $prefix . 'video',
						'type'  => 'text',
					)
				),
			);
		}
		
		// Add the Meta Box
		function add_property_info_meta_box()
		{
			add_meta_box( 'property_info_meta_box', // $id
				esc_html__( 'Property info', 'realty-house-pl' ), array (
					$this,
					'show_property_info_meta_box'
				), // $callback
				'property', // $page
				'normal', // $context
				'high' ); // $priority
		}
		
		// Show the Fields in the Post Type section
		function show_property_info_meta_box()
		{
			global $post, $realty_house_opt;
			
			wp_localize_script( 'realty-house-plugin-property-js', 'realty_house_proeprty', array (
				'propertyLat'  => $meta = get_post_meta( $post->ID, 'property_latitude', true ),
				'propertyLong' => $meta = get_post_meta( $post->ID, 'property_longitude', true )
			) );
			
			// Use nonce for verification
			echo '<input type="hidden" name="property_info_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
			
			// Begin the field table and loop
			echo '
                <table class="meta-box-tabs">
                    <tr>
                        <td class="tab-content-box">
                            <div class="tab-content active" id="basic-details">
                                <div class="table-title">' . esc_html__( 'Basic Details', 'realty-house-pl' ) . '</div>
                                <div class="table-container"><table class="form-table">
                                    <tr>
                                        <th>' . esc_html__( 'Property ID :', 'realty-house-pl' ) . '</th>
                                        <td>' . esc_html( $post->ID ) . '</td>
                                    </tr>';
			
			foreach ( $this->property_info_meta_fields['basic_details'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                                                <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
                                                <td>';
				switch ( $field['type'] )
				{
					// select
					case 'select':
						echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
						foreach ( $field['options'] as $option )
						{
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
						}
						echo '</select>';
					break;
					// text
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" />';
					break;
					// textarea
					case 'textarea':
						echo '
                                                            <textarea name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" cols="50" rows="5">' . esc_attr( $meta ) . '</textarea>';
					break;
					case 'show':
						echo ! empty( $meta ) ? esc_html( $meta ) . ' ' . esc_html__( 'Times', 'realty-house-pl' ) : 0 . ' ' . esc_html__( 'Time', 'realty-house-pl' );
					break;
					
				} //end switch
				echo '<span class="description">' . $field['desc'] . '</span></td></tr>';
			} // end foreach
			echo '</table></div>'; // end table
			echo '
                </div>
                <div class="tab-content" id="price">
                    <div class="table-title">' . esc_html__( 'Property Price', 'realty-house-pl' ) . '</div>
                    <div class="table-container">
                        <table class="form-table">';
			
			foreach ( $this->property_info_meta_fields['price'] as $field )
			{
				// get value of this field if it exists for this post
				$meta            = get_post_meta( $post->ID, $field['id'], true );
				$post_status_val = get_post_meta( $post->ID, 'property_property_status', true );
				$rent_id         = array_search( 'For Rent', $realty_house_opt['realty-house-property-status'] ) + 1;
				
				// begin a table row with
				echo '
					<tr ' . ( ( $field['id'] == 'property_price_type' && $post_status_val != $rent_id ) ? esc_attr( 'class=hidden' ) : '' ) . ' ' . ( $field['id'] == 'property_price_type' ? esc_attr( 'id=' . $field['id'] ) : '' ) . '>
	                    <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
	                    <td>';
				switch ( $field['type'] )
				{
					// text
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" placeholder="$"  />
											<span class="price-separated-container">' . esc_attr( ! empty( $meta ) ? number_format( $meta ) : '' ) . '</span>';
					break;
					// textarea
					case 'checkbox':
						echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>';
					break;
					// select
					case 'select':
						echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
						foreach ( $field['options'] as $option )
						{
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
						}
						echo '</select>';
					break;
					
				} //end switch
				echo '<span class="description">' . $field['desc'] . '</span></td></tr>';
			} // end foreach
			echo '
					</table></div>
                    </div>
                    <div class="tab-content" id="gallery">';
			
			// Begin the field table and loop
			echo '
				<div class="table-title">' . esc_html__( 'Property Images', 'realty-house-pl' ) . '</div>
				<div class="table-container"><table class="form-table">';
			foreach ( $this->property_info_meta_fields['gallery'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                                            <td>';
				switch ( $field['type'] )
				{
					// Gallery
					case 'gallery':
						
						echo '
                            <div class="ravis_slideshow_wrapper hide-if-no-js">
                                <ul class="slideshow_images clearfix">';
						
						$slideshow_images = get_post_meta( $post->ID, $field['id'], true );
						
						$attachments = array_filter( explode( '---', $slideshow_images ) );
						
						if ( $attachments )
						{
							foreach ( $attachments as $attachment_id )
							{
								$attachment_id = trim( $attachment_id );
								if ( ! empty( $attachment_id ) )
								{
									echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">' . wp_get_attachment_image( $attachment_id, 'image' ) . '<a href="#" class="delete_slide" title="' . esc_attr( esc_html__( 'Delete image', 'realty-house-pl' ) ) . '"><i class="dashicons dashicons-no"></i></a></li>';
								}
							}
						}
						echo '        
                                </ul>
                                <input type="hidden" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . esc_attr( $slideshow_images ) . ' " />
                                <a href="#" class="add_slideshow_images button button-primary button-large">' . esc_html__( 'Add images', 'realty-house-pl' ) . '</a>
                            </div>
                            ';
					break;
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>'; // end table
			echo '
                </div>
                <div class="tab-content" id="location">
                    <div class="table-title">' . esc_html__( 'Property Location', 'realty-house-pl' ) . '</div>
					<div class="table-container"><table class="form-table">
                        <tr>
                            <td colspan="2"><div id="property_map"></div></td>
                        </tr>';
			
			$property_location_details = array ();
			foreach ( $this->property_info_meta_fields['location'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				
				if ( $field['id'] == 'property_longitude' )
				{
					$property_location_details['propertyLong'] = $meta;
				}
				if ( $field['id'] == 'property_latitude' )
				{
					$property_location_details['propertyLat'] = $meta;
				}
				
				// begin a table row with
				echo '<tr>
				                    <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
				                    <td>';
				switch ( $field['type'] )
				{
					// text
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" />';
					break;
					// textarea
					
				} //end switch
				echo '<span class="description">' . $field['desc'] . '</span></td></tr>';
			} // end foreach
			wp_localize_script( 'realty-house-plugin-property-js', 'realty_house_property', $property_location_details );
			
			echo '</table>
				</div>
                <div class="tab-content" id="neighborhood">
                    <div class="table-title">' . esc_html__( 'Neighborhood', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			foreach ( $this->property_info_meta_fields['neighborhood'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                                                <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
                                                <td>';
				switch ( $field['type'] )
				{
					// repeatable
					case 'repeatable':
						echo '
                            <ul id="' . $field['id'] . '-repeatable" class="custom_repeatable">';
						if ( $meta )
						{
							$i = 0;
							foreach ( $meta as $row )
							{
								echo '
									<li>
			                            <span class="sort"><i class="dashicons dashicons-move"></i></span>
			                            <div class="input-containers">
			                            	<div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][title]" value="' . $row['title'] . '" placeholder="' . esc_html__( 'Title', 'realty-house-pl' ) . '" /></div>
			                            	<div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][distance]" value="' . $row['distance'] . '"  placeholder="' . esc_html__( 'Distance', 'realty-house-pl' ) . '"/></div>
			                            </div>
			                            <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
			                        </li>
                                    ';
								$i ++;
							}
						}
						echo '</ul>
						<a class="repeatable-add button button-primary button-large" href="#">' . esc_html__( 'Add New', 'realty-house-pl' ) . '</a>
                        <ul class="li-tpml" style="display:none;">
                            <li>
                                <span class="sort"><i class="dashicons dashicons-move"></i></span>
                                <div class="input-containers">
                                    <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="title" value="" placeholder="' . esc_html__( 'Title', 'realty-house-pl' ) . '" /></div>
                                    <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="distance" value=""  placeholder="' . esc_html__( 'Distance', 'realty-house-pl' ) . '"/></div>
                                </div>
                                <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
	                        </li>
                        </ul>
                        <span class="description">' . $field['desc'] . '</span>';
					break;
					
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>
                </div>
                <div class="tab-content" id="nearby_schools">
                    <div class="table-title">' . esc_html__( 'Nearby Schools', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			foreach ( $this->property_info_meta_fields['nearby_schools'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                                    <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
                                    <td>';
				switch ( $field['type'] )
				{
					// repeatable
					case 'repeatable':
						echo '
                                        <ul id="' . $field['id'] . '-repeatable" class="custom_repeatable">';
						$i = 0;
						if ( $meta )
						{
							foreach ( $meta as $row )
							{
								echo '
									<li>
	                                    <span class="sort"><i class="dashicons dashicons-move"></i></span>
	                                    <div class="input-containers">
	                                        <div class="input-box"><input type="number" name="' . $field['id'] . '[' . $i . '][rate]" value="' . $row['rate'] . '" placeholder="' . esc_html__( 'Rating', 'realty-house-pl' ) . '" /></div>
	                                        <div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][name]" value="' . $row['name'] . '" placeholder="' . esc_html__( 'School\'s Name', 'realty-house-pl' ) . '" /></div>
	                                        <div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][grade]" value="' . $row['grade'] . '" placeholder="' . esc_html__( 'School\'s Grade', 'realty-house-pl' ) . '" /></div>
	                                        <div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][distance]" value="' . $row['distance'] . '" placeholder="' . esc_html__( 'Distance', 'realty-house-pl' ) . '"/></div>
	                                    </div>
	                                    <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
	                                </li>';
								$i ++;
							}
						}
						echo '</ul>
							<a class="repeatable-add button button-primary button-large" href="#">' . esc_html__( 'Add New', 'realty-house-pl' ) . '</a>
                            <ul class="li-tpml" style="display:none;">
                                <li>
                                    <span class="sort"><i class="dashicons dashicons-move"></i></span>
                                    <div class="input-containers">
                                        <div class="input-box"><input type="number" name="" id="' . $field['id'] . '" data-name="rate" value="" placeholder="' . esc_html__( 'Rating', 'realty-house-pl' ) . '" /></div>
                                        <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="name" value="" placeholder="' . esc_html__( 'School\'s Name', 'realty-house-pl' ) . '" /></div>
                                        <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="grade" value="" placeholder="' . esc_html__( 'School\'s Grade', 'realty-house-pl' ) . '" /></div>
                                        <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="distance" value="" placeholder="' . esc_html__( 'Distance', 'realty-house-pl' ) . '"/></div>
                                    </div>
                                    <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
                                </li>
                            </ul>
                            <span class="description">' . $field['desc'] . '</span>';
					break;
					
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>
                </div>
                <div class="tab-content" id="amenities">
                    <div class="table-title">' . esc_html__( 'Amenities', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">
	                    <tr>
                            <td colspan="2">';
			
			// get value of this field if it exists for this post
			$amenties_info = $this->property_info_meta_fields['amenities'][0]['id'];
			$meta          = get_post_meta( $post->ID, $amenties_info, true );
			
			foreach ( $realty_house_opt['realty-house-property-amenities'] as $index => $field )
			{
				// begin a table row with
				echo '<div class="amenities-box"><label for="' . $amenties_info . '[' . $index . ']"><input type="checkbox" id="' . $amenties_info . '[' . $index . ']" name="' . $amenties_info . '[' . $index . ']" ' . ( ! empty( $meta ) && array_key_exists( $index, $meta ) ? esc_attr( 'checked="checked"' ) : '' ) . '>  ' . esc_html( $field ) . '</label></div>';
			} // end foreach
			echo '</td>
		</tr>
			</table></div>
                </div>
                <div class="tab-content" id="facts">
                    <div class="table-title">' . esc_html__( 'Property Facts', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			foreach ( $this->property_info_meta_fields['facts'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                            <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
                            <td>';
				switch ( $field['type'] )
				{
					// repeatable
					case 'repeatable':
						echo '
                                    <ul id="' . $field['id'] . '-repeatable" class="custom_repeatable">';
						$i = 0;
						if ( $meta )
						{
							foreach ( $meta as $row )
							{
								echo '
										<li>
                                            <span class="sort"><i class="dashicons dashicons-move"></i></span>
                                            <div class="input-containers">
                                                <div class="input-box"><input type="text" name="' . $field['id'] . '[' . $i . '][title]" value="' . $row['title'] . '" placeholder="' . esc_html__( 'Insert a fact', 'realty-house-pl' ) . '" /></div>                                            	
                                            </div>
                                            <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
                                        </li>';
								$i ++;
							}
						}
						echo '</ul>
								<a class="repeatable-add button button-primary button-large" href="#">' . esc_html__( 'Add New', 'realty-house-pl' ) . '</a>
                                <ul class="li-tpml" style="display:none;">
                                    <li>
                                        <span class="sort"><i class="dashicons dashicons-move"></i></span>
                                        <div class="input-containers">
                                            <div class="input-box"><input type="text" name="" id="' . $field['id'] . '" data-name="title" value="" placeholder="' . esc_html__( 'Insert a fact', 'realty-house-pl' ) . '" /></div>                                            
                                        </div>
                                        <a class="repeatable-remove delete" href="#"><i class="dashicons dashicons-no"></i></a>
                                    </li>
                                </ul>
                                <span class="description">' . $field['desc'] . '</span>';
					break;
					
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>
	                </div>
                
                <div class="tab-content" id="tags">
                    <div class="table-title">' . esc_html__( 'Tags', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			foreach ( $this->property_info_meta_fields['tags'] as $field )
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
					case 'checkbox':
						echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>
                                <label for="' . $field['id'] . '"><span class="description">' . $field['desc'] . '</span></label>';
					break;
					
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table></div>
				<div class="tab-content" id="floor_plan">
                    <div class="table-title">' . esc_html__( 'Property Floor Plan', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			
			foreach ( $this->property_info_meta_fields['floor_plan'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
	                                            <td>';
				switch ( $field['type'] )
				{
					// Gallery
					case 'gallery':
						
						echo '
	                            <div class="ravis_slideshow_wrapper hide-if-no-js">
	                                <ul class="slideshow_images clearfix">';
						
						$slideshow_images = get_post_meta( $post->ID, $field['id'], true );
						
						$attachments = array_filter( explode( '---', trim( $slideshow_images ) ) );
						
						if ( $attachments )
						{
							foreach ( $attachments as $attachment_id )
							{
								$attachment_id = trim( $attachment_id );
								if ( ! empty( $attachment_id ) )
								{
									echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">' . wp_get_attachment_image( $attachment_id, 'image' ) . '<a href="#" class="delete_slide" title="' . esc_attr( esc_html__( 'Delete image', 'realty-house-pl' ) ) . '"><i class="dashicons dashicons-no"></i></a></li>';
								}
							}
						}
						echo '        
	                                </ul>
	                                <input type="hidden" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . esc_attr( $slideshow_images ) . ' " />
	                                <a href="#" class="add_slideshow_images button button-primary button-large">' . esc_html__( 'Add Floor Plans', 'realty-house-pl' ) . '</a>
	                            </div>
	                            ';
					break;
				} //end switch
				echo '</td></tr>';
			}
			
			echo '</table></div>
                </div>
                <div class="tab-content" id="attachments">
                    <div class="table-title">' . esc_html__( 'Property Attachment', 'realty-house-pl' ) . '</div>
                    <div class="table-container"><table class="form-table">';
			
			foreach ( $this->property_info_meta_fields['attachments'] as $field )
			{
				// get value of this field if it exists for this post
				$meta = get_post_meta( $post->ID, $field['id'], true );
				// begin a table row with
				echo '<tr>
                                            <td>';
				switch ( $field['type'] )
				{
					// Gallery
					case 'gallery':
						
						echo '
                            <div class="ravis_slideshow_wrapper attachments hide-if-no-js">
                                <ul class="slideshow_images clearfix">';
						
						$slideshow_images = get_post_meta( $post->ID, $field['id'], true );
						
						$attachments = array_filter( explode( '---', trim( $slideshow_images ) ) );
						
						if ( $attachments )
						{
							foreach ( $attachments as $attachment_id )
							{
								$attachment_id = trim( $attachment_id );
								if ( ! empty( $attachment_id ) )
								{
									if ( get_post_mime_type( $attachment_id ) == 'image/jpeg' )
									{
										echo '<li class="image img-box" data-attachment_id="' . esc_attr( $attachment_id ) . '">' . wp_get_attachment_image( $attachment_id, 'image' ) . '<a href="#" class="delete_slide" title="' . esc_attr( esc_html__( 'Delete image', 'realty-house-pl' ) ) . '"><i class="dashicons dashicons-no"></i></a></li>';
									}
									else
									{
										echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">' . basename( get_attached_file( $attachment_id ) ) . '<a href="#" class="delete_slide" title="' . esc_attr( esc_html__( 'Delete image', 'realty-house-pl' ) ) . '"><i class="dashicons dashicons-no"></i></a></li>';
									}
								}
							}
						}
						echo '        
                                </ul>
                                <input type="hidden" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . esc_attr( $slideshow_images ) . ' " />
                                <a href="#" class="add_slideshow_images button button-primary button-large">' . esc_html__( 'Add Attachments', 'realty-house-pl' ) . '</a>
                            </div>
                            ';
					break;
				} //end switch
				echo '</td></tr>';
			}
			echo '</table></div></div>
                             
	                         <div class="tab-content active" id="basic-details">
	                            <div class="table-title">' . esc_html__( 'Property Video', 'realty-house-pl' ) . '</div>
	                            <div class="table-container">
	                                <table class="form-table">';
			
			foreach ( $this->property_info_meta_fields['video'] as $field )
			{
				$meta = get_post_meta( $post->ID, $field['id'], true );
				echo '
		                                    <tr>
		                                        <th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>
		                                        <td>';
				switch ( $field['type'] )
				{
					case 'text':
						echo '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta ) . '" size="40" />';
					break;
				}
				
				echo '<span class="description">' . $field['desc'] . '</span></td>
								            </tr>';
			}
			echo '
	                                </table>
	                            </div>
	                         </div>
                        </td>
                    </tr>
                </table>
            ';
		}
		
		// Save the Data
		function save_property_info_meta( $post_id )
		{
			global $post_id;
			$security_code = '';
			
			if ( isset( $_POST['property_info_meta_box_nonce'] ) && $_POST['property_info_meta_box_nonce'] != '' )
			{
				$security_code = sanitize_text_field( $_POST['property_info_meta_box_nonce'] );
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
			
			// loop through fields and save the data
			foreach ( $this->property_info_meta_fields as $fields )
			{
				foreach ( $fields as $field )
				{
					$old = get_post_meta( $post_id, $field['id'], true );
					$new = ! empty( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : '';
					if ( $new && $new != $old )
					{
						update_post_meta( $post_id, $field['id'], $new );
					}
					elseif ( '' == $new && $old )
					{
						delete_post_meta( $post_id, $field['id'], $old );
					}
				}
				
			} // end foreach
		}
		
	}
	
	$property_info_meta_box_obj = new Home_square_plg_property_info_meta_box;