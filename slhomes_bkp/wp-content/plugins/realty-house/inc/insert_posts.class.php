<?php
	
	/*
	 * Insert Post in WordPress
	 */
	
	class realty_house_insert_posts
	{
		public function __construct()
		{
			add_action( 'init', array ( $this, 'init' ) );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Insert Property Function
		 * ------------------------------------------------------------------------------------------
		 */
		public static function insert_property()
		{
			
			$data_fields = json_decode( str_replace( '\\', '', $_POST["dataFields"] ) );
			
			if ( wp_verify_nonce( $data_fields->security, 'add_property_security_code' ) )
			{
				if ( ! empty( $data_fields->agent_id ) )
				{
					$data_fields->p_agent = array ( $data_fields->agent_id );
				}
				
				if ( ! empty( $data_fields->p_title ) && ! empty( $data_fields->p_type ) && ! empty( $data_fields->p_status ) && ! empty( $data_fields->p_bedrooms ) && ! empty( $data_fields->p_bath ) && ! empty( $data_fields->p_build_size ) && ! empty( $data_fields->p_lot_size ) && ! empty( $data_fields->p_desc ) && ! empty( $data_fields->p_price ) && ! empty( $data_fields->p_address ) && ! empty( $data_fields->p_agent ) && ! empty( $data_fields->p_agreement ) )
				{
					
					if ( $data_fields )
					{
						$post_info = array (
							'post_title'   => $data_fields->p_title,
							'post_content' => $data_fields->p_desc,
							'post_type'    => 'property',
							'post_status'  => 'pending'
						);
						
						$inserted_post_id = wp_insert_post( $post_info );
					}
					
					
					/**
					 * update the post_meta of inserted property post
					 * @var string $prefix "post meta prefix"
					 * @var array $post_meta_array "it contains post_meta IDs and their values"
					 */
					$prefix          = 'property_';
					$post_meta_array = array (
						
						array (
							'id'    => $prefix . 'property_status',
							'value' => $data_fields->p_status
						),
						array (
							'id'    => $prefix . 'property_type',
							'value' => $data_fields->p_type
						),
						array (
							'id'    => $prefix . 'bedroom',
							'value' => $data_fields->p_bedrooms
						),
						array (
							'id'    => $prefix . 'bathroom',
							'value' => $data_fields->p_bath
						),
						array (
							'id'    => $prefix . 'building_size',
							'value' => $data_fields->p_build_size
						),
						array (
							'id'    => $prefix . 'lot_size',
							'value' => $data_fields->p_lot_size
						),
						array (
							'id'    => $prefix . 'parking',
							'value' => $data_fields->p_garages
						),
						array (
							'id'    => $prefix . 'construction_status',
							'value' => $data_fields->p_construction_status
						),
						array (
							'id'    => $prefix . 'keywords',
							'value' => $data_fields->p_keywords
						),
						array (
							'id'    => $prefix . 'price',
							'value' => $data_fields->p_price
						),
						array (
							'id'    => $prefix . 'price_hidden',
							'value' => $data_fields->p_price_privacy
						),
						array (
							'id'    => $prefix . 'price_type',
							'value' => $data_fields->p_price_type
						),
						array (
							'id'    => $prefix . 'slideshow_images',
							'value' => trim( $data_fields->p_gallery, '---' )
						),
						array (
							'id'    => $prefix . 'address',
							'value' => $data_fields->p_address
						),
						array (
							'id'    => $prefix . 'longitude',
							'value' => $data_fields->p_long
						),
						array (
							'id'    => $prefix . 'latitude',
							'value' => $data_fields->p_lat
						),
						array (
							'id'    => $prefix . 'new_tag',
							'value' => $data_fields->p_new_tag
						),
						array (
							'id'    => $prefix . 'hot_offer',
							'value' => $data_fields->p_hot_offer
						),
						array (
							'id'    => $prefix . 'featured',
							'value' => $data_fields->p_featured
						),
						array (
							'id'    => $prefix . 'price_cut',
							'value' => $data_fields->p_price_cut
						),
						array (
							'id'    => $prefix . 'open_house',
							'value' => $data_fields->p_open_house
						),
						array (
							'id'    => $prefix . 'foreclosure',
							'value' => $data_fields->p_foreclosure
						),
						array (
							'id'    => $prefix . 'floor_plan',
							'value' => trim( $data_fields->p_floor_plan, '---' )
						),
						array (
							'id'    => $prefix . 'attachment',
							'value' => trim( $data_fields->p_attachment, '---' )
						),
						array (
							'id'    => $prefix . 'agents',
							'value' => $data_fields->p_agent
						),
					);
					
					
					if ( $data_fields->amenity )
					{
						$amenity_item_arr = array (
							'id'    => $prefix . 'amenities',
							'value' => array ()
						);
						foreach ( $data_fields->amenity as $amenity_item )
						{
							array_push( $amenity_item_arr['value'][ $amenity_item ], 1 );
						}
						
						array_push( $post_meta_array, $amenity_item_arr );
					}
					
					
					if ( count( $data_fields->neighborhood_title ) > 1 )
					{
						$neighborhood_arr = array (
							'id'    => $prefix . 'neighborhood',
							'value' => array ()
						);
						for ( $i = 1; $i < count( $data_fields->neighborhood_title ); $i ++ )
						{
							$neighborhood_arr_val = array (
								'title'    => $data_fields->neighborhood_title[ $i ],
								'distance' => $data_fields->neighborhood_distance[ $i ]
							);
							array_push( $neighborhood_arr['value'], $neighborhood_arr_val );
						}
						array_push( $post_meta_array, $neighborhood_arr );
					}
					
					if ( count( $data_fields->nearby_schools_name ) > 1 )
					{
						$nearby_schools_arr = array (
							'id'    => $prefix . 'nearby_schools',
							'value' => array ()
						);
						for ( $i = 1; $i < count( $data_fields->nearby_schools_name ); $i ++ )
						{
							$nearby_schools_arr_val = array (
								'rate'     => $data_fields->nearby_schools_rate[ $i ],
								'name'     => $data_fields->nearby_schools_name[ $i ],
								'grade'    => $data_fields->nearby_schools_grade[ $i ],
								'distance' => $data_fields->nearby_schools_distance[ $i ],
							);
							array_push( $nearby_schools_arr['value'], $nearby_schools_arr_val );
						}
						array_push( $post_meta_array, $nearby_schools_arr );
					}
					
					
					if ( count( $data_fields->facts ) > 1 )
					{
						$facts_arr = array (
							'id'    => $prefix . 'facts',
							'value' => array ()
						);
						for ( $i = 1; $i < count( $data_fields->facts ); $i ++ )
						{
							$facts_arr_val = array (
								'title' => $data_fields->facts[ $i ]
							);
							array_push( $facts_arr['value'], $facts_arr_val );
						}
						array_push( $post_meta_array, $facts_arr );
					}
					
					// loop through fields and save the data
					foreach ( $post_meta_array as $field )
					{
						update_post_meta( $inserted_post_id, $field['id'], $field['value'] );
					}
					
					$response['status'] = true;
					$response['text']   = esc_html__( 'Thanks for submission, your property will be available after confirmation of administration of website.', 'realty-house-pl' );
				}
				else
				{
					$response['status']  = false;
					$response['errorNo'] = 1;
					$response['text']    = esc_html__( 'Please fill all the required fields', 'realty-house-pl' );
				}
			}
			else
			{
				$response['status']  = false;
				$response['errorNo'] = 2;
				$response['text']    = esc_html__( 'Are you cheating?', 'realty-house-pl' );
			}
			
			echo json_encode( $response );
			die();
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Register Agent Function
		 * ------------------------------------------------------------------------------------------
		 */
		public static function realty_house_register_agent()
		{
			
			$data_fields = json_decode( str_replace( '\\', '', $_POST["dataFields"] ) );
			
			if ( wp_verify_nonce( $data_fields->security, 'be_agent_security_code' ) )
			{
				
				
				if ( ! empty( $data_fields->fname ) && ! empty( $data_fields->lname ) && ! empty( $data_fields->user_name ) && ! empty( $data_fields->email ) && ! empty( $data_fields->confirm_email ) && ! empty( $data_fields->address ) )
				{
					if ( $data_fields->email !== $data_fields->confirm_email )
					{
						$response['status']  = false;
						$response['errorNo'] = 2;
						$response['text']    = esc_html__( 'Email and its confirmation fields are not the same', 'realty-house-pl' );
					}
					
					/*
					 * Check if user has already registered or not in published posts
					 */
					$agent_args = array (
						'post_type'   => 'agent',
						'post_status' => 'publish',
						'meta_query'  => array (
							'relation' => 'OR',
							array (
								'key'   => 'staff_username',
								'value' => $data_fields->user_name
							),
							array (
								'key'   => 'staff_email',
								'value' => $data_fields->email,
							),
						),
					);
					
					$approved_agents = new WP_Query( $agent_args );
					
					if ( $approved_agents->post_count === 0 )
					{
						
						/*
						 * Check if user has already registered or not in pending posts
						 */
						$agent_args_pending = array (
							'post_type'   => 'agent',
							'post_status' => 'pending',
							'meta_query'  => array (
								'relation' => 'OR',
								array (
									'key'   => 'staff_username',
									'value' => $data_fields->user_name
								),
								array (
									'key'   => 'staff_email',
									'value' => $data_fields->email,
								),
							),
						);
						
						$pending_agents = new WP_Query( $agent_args_pending );
						if ( $pending_agents->post_count === 0 )
						{
							
							
							if ( $data_fields )
							{
								$post_info = array (
									'post_title'  => $data_fields->fname . ' ' . $data_fields->lname,
									'post_type'   => 'agent',
									'post_status' => 'pending'
								);
								
								$inserted_post_id = wp_insert_post( $post_info );
							}
							
							
							/**
							 * update the post_meta of inserted property post
							 * @var string $prefix "post meta prefix"
							 * @var array $post_meta_array "it contains post_meta IDs and their values"
							 */
							$prefix          = 'staff_';
							$post_meta_array = array (
								array (
									'id'    => $prefix . 'username',
									'value' => $data_fields->user_name
								),
								array (
									'id'    => $prefix . 'address',
									'value' => $data_fields->address
								),
								array (
									'id'    => $prefix . 'phone',
									'value' => ( ! empty( $data_fields->phone ) ? $data_fields->phone : '' )
								),
								array (
									'id'    => $prefix . 'mobile',
									'value' => ( ! empty( $data_fields->mobile ) ? $data_fields->mobile : '' )
								),
								array (
									'id'    => $prefix . 'email',
									'value' => $data_fields->email
								)
							);
							
							
							// loop through fields and save the data
							foreach ( $post_meta_array as $field )
							{
								update_post_meta( $inserted_post_id, $field['id'], $field['value'] );
							}
							
							$response['status'] = true;
							$response['text']   = esc_html__( 'Thanks for submission, your property will be available after confirmation of administration of website.', 'realty-house-pl' );
						}
						else
						{
							$response['status']  = false;
							$response['errorNo'] = 1;
							$response['text']    = esc_html__( 'Your account is pending now, please be patient to be approved.', 'realty-house-pl' );
						}
					}
					else
					{
						$response['status']  = false;
						$response['errorNo'] = 1;
						$response['text']    = esc_html__( 'You have already registered. Please try another Username or Email.', 'realty-house-pl' );
					}
				}
				else
				{
					$response['status']  = false;
					$response['errorNo'] = 1;
					$response['text']    = esc_html__( 'Please fill all the required fields', 'realty-house-pl' );
				}
			}
			else
			{
				$response['status']  = false;
				$response['errorNo'] = 2;
				$response['text']    = esc_html__( 'Are you cheating?', 'realty-house-pl' );
			}
			echo json_encode( $response );
			die();
		}
		
		public function init()
		{
			add_action( 'wp_ajax_nopriv_realty_house_insert_property', array ( $this, 'insert_property' ) );
			add_action( 'wp_ajax_realty_house_insert_property', array ( $this, 'insert_property' ) );
			
			add_action( 'wp_ajax_nopriv_realty_house_register_agent', array ( $this, 'realty_house_register_agent' ) );
			add_action( 'wp_ajax_realty_house_register_agent', array ( $this, 'realty_house_register_agent' ) );
		}
		
	}
	
	
	$realty_house_insert_posts_obj = new realty_house_insert_posts();