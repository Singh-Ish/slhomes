<?php
	
	class realty_house_get_info
	{
		/*
		 * Get All Property Information
		 */
		public function property_info( $p_id )
		{
			global $realty_house_opt, $current_user;
			$property_info = array ();
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Basic Details
			 * ------------------------------------------------------------------------------------------
			 */
			
			$property_info['id']    = $p_id;
			$property_info['title'] = get_the_title( $p_id );
			
			// Property Status
			$property_status            = get_post_meta( $p_id, 'property_property_status', true );
			$property_info['status_id'] = ! empty( $property_status ) ? $property_status : '';
			$property_info['status']    = ! empty( $property_status ) ? $realty_house_opt['realty-house-property-status'][ $property_status - 1 ] : '';
			
			// Property Type
			$property_type = get_post_meta( $p_id, 'property_property_type', true );
			if ( ! empty( $property_type ) )
			{
				$property_type_info             = explode( '---', $realty_house_opt['realty-house-property-type'][ $property_type - 1 ] );
				$property_info['type']['id']    = $property_type;
				$property_info['type']['title'] = $property_type_info[0];
				$property_info['type']['icon']  = $property_type_info[1];
			}
			$property_info['area_unit'] = ! empty( $realty_house_opt['realty-house-area-unit'] ) ? esc_html( $realty_house_opt['realty-house-area-unit'] ) : esc_html__( 'sqft', 'realty-house-pl' );
			$property_bedroom           = get_post_meta( $p_id, 'property_bedroom', true );
			$property_bathroom          = get_post_meta( $p_id, 'property_bathroom', true );
			$property_building_size     = get_post_meta( $p_id, 'property_building_size', true );
			$property_lot_size          = get_post_meta( $p_id, 'property_lot_size', true );
			$property_parking           = get_post_meta( $p_id, 'property_parking', true );
			$property_video             = get_post_meta( $p_id, 'property_video', true );
			
			$property_info['bedroom']       = ! empty( $property_bedroom ) ? get_post_meta( $p_id, 'property_bedroom', true ) : '';
			$property_info['bathroom']      = ! empty( $property_bathroom ) ? get_post_meta( $p_id, 'property_bathroom', true ) : '';
			$property_info['building_size'] = ! empty( $property_building_size ) ? get_post_meta( $p_id, 'property_building_size', true ) : '';
			$property_info['lot_size']      = ! empty( $property_lot_size ) ? get_post_meta( $p_id, 'property_lot_size', true ) : '';
			$property_info['parking']       = ! empty( $property_parking ) ? get_post_meta( $p_id, 'property_parking', true ) : '';
			$property_info['video']         = ! empty( $property_video ) ? get_post_meta( $p_id, 'property_video', true ) : '';
			
			// Construction Status
			$construction_status                           = get_post_meta( $p_id, 'property_construction_status', true );
			$property_info['construction_status']['value'] = $construction_status;
			if ( $construction_status == 1 )
			{
				$property_info['construction_status']['title'] = esc_html__( 'Under Construction/Proposed', 'realty-house-pl' );
			}
			else
			{
				$property_info['construction_status']['title'] = esc_html__( 'Existing', 'realty-house-pl' );
			}
			$property_info['visits'] = get_post_meta( $p_id, 'property_visits', true );
			
			// Keywords
			$property_keywords = get_post_meta( $p_id, 'property_keywords', true );
			if ( ! empty( $property_keywords ) )
			{
				$property_keywords_array = explode( ',', $property_keywords );
				foreach ( $property_keywords_array as $keyword )
				{
					$property_info['keywords'][] = $keyword;
				}
			}
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Price
			 * ------------------------------------------------------------------------------------------
			 */
			$currency_info                          = new realty_house_currency();
			$property_info['price']['unit']         = $currency_info->get_current_currency();
			$property_info['price']['value']        = get_post_meta( $p_id, 'property_price', true );
			$property_price_hidden_val              = get_post_meta( $p_id, 'property_price_hidden', true );
			$property_info['price']['confidential'] = ( ! empty( $property_price_hidden_val ) ? true : false );
			
			$property_price_type = get_post_meta( $p_id, 'property_price_type', true );
			if ( ! empty( $property_price_type ) )
			{
				$property_info['price']['type']['id'] = $property_price_type;
				
				switch ( $property_price_type )
				{
					case ( 1 ):
						$property_info['price']['type']['value'] = esc_html__( 'Per Month' );
					break;
					case ( 2 ):
						$property_info['price']['type']['value'] = esc_html__( 'Per Week' );
					break;
					case ( 3 ):
						$property_info['price']['type']['value'] = esc_html__( 'Per Night' );
					break;
					case ( 4 ):
						$property_info['price']['type']['value'] = esc_html__( 'Annual' );
					break;
				}
			}
			$generated_price = number_format( $property_info['price']['value'] * $property_info['price']['unit']['rate'] );
			
			if ( $property_info['price']['unit']['position'] == 0 )
			{
				$generated_price .= $property_info['price']['unit']['symbol'];
			}
			else
			{
				$generated_price = $property_info['price']['unit']['symbol'] . ' ' . $generated_price;
			}
			if ( ! empty( $property_info['price']['type'] ) && $property_info['status_id'] == '2' )
			{
				$generated_price .= ' <span>' . esc_html( $property_info['price']['type']['value'] ) . '</span>';
			}
			
			$property_info['price']['generated'] = ! empty( $property_info['price']['confidential'] ) ? esc_html__( 'Call Us', 'realty-house-pl' ) : $generated_price;
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Images
			 * ------------------------------------------------------------------------------------------
			 */
			$property_images    = get_post_meta( $p_id, 'property_slideshow_images', true );
			$property_thumb_arr = array ();
			$trimmed_image_val  = trim( $property_images );
			if ( ! empty( $trimmed_image_val ) )
			{
				$property_thumb_arr = explode( '---', $property_images );
				$i                  = 0;
				foreach ( $property_thumb_arr as $property_image )
				{
					$property_image                                             = trim( $property_image );
					$property_info['gallery']['img'][ $i ]['id']                = $property_image;
					$property_info['gallery']['img'][ $i ]['url']               = wp_get_attachment_url( $property_image );
					$property_info['gallery']['img'][ $i ]['code']['thumbnail'] = wp_get_attachment_image( $property_image, 'thumbnail' );
					$property_info['gallery']['img'][ $i ]['code']['medium']    = wp_get_attachment_image( $property_image, 'medium' );
					$property_info['gallery']['img'][ $i ]['code']['large']     = wp_get_attachment_image( $property_image, 'large' );
					$property_info['gallery']['img'][ $i ]['code']['full']      = wp_get_attachment_image( $property_image, 'full' );
					$i ++;
				}
			}
			$property_info['gallery']['count'] = ( ! empty( $property_images ) ? count( $property_thumb_arr ) : 0 );
			
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Location
			 * ------------------------------------------------------------------------------------------
			 */
			$property_info['address'] = get_post_meta( $p_id, 'property_address', true );
			$property_info['long']    = get_post_meta( $p_id, 'property_longitude', true );
			$property_info['lat']     = get_post_meta( $p_id, 'property_latitude', true );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Neighborhood
			 * ------------------------------------------------------------------------------------------
			 */
			$property_info['neighborhood'] = get_post_meta( $p_id, 'property_neighborhood', true );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Nearby Schools
			 * ------------------------------------------------------------------------------------------
			 */
			$property_info['nearby_schools'] = get_post_meta( $p_id, 'property_nearby_schools', true );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Amenities
			 * ------------------------------------------------------------------------------------------
			 */
			$property_amenities         = get_post_meta( $p_id, 'property_amenities', true );
			$property_info['amenities'] = array ();
			if ( ! empty( $property_amenities ) )
			{
				foreach ( $property_amenities as $index => $property_amenity )
				{
					$property_info['amenities'][] = $realty_house_opt['realty-house-property-amenities'][ $index ];
				}
			}
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Facts
			 * ------------------------------------------------------------------------------------------
			 */
			$property_info['facts'] = get_post_meta( $p_id, 'property_facts', true );
			
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Tags
			 * ------------------------------------------------------------------------------------------
			 */
			if ( get_post_meta( $p_id, 'property_new_tag', true ) )
			{
				$property_info['tags']['new_tag']['class'] = 'new_tag';
				$property_info['tags']['new_tag']['label'] = esc_html__( 'New', 'realty-house-pl' );
				
			}
			if ( get_post_meta( $p_id, 'property_hot_offer', true ) )
			{
				$property_info['tags']['hot_offer']['class'] = 'hot_offer';
				$property_info['tags']['hot_offer']['label'] = esc_html__( 'Hot Offer', 'realty-house-pl' );
				
			}
			if ( get_post_meta( $p_id, 'property_featured', true ) )
			{
				$property_info['tags']['featured']['class'] = 'featured';
				$property_info['tags']['featured']['label'] = esc_html__( 'Featured', 'realty-house-pl' );
			}
			
			if ( get_post_meta( $p_id, 'property_price_cut', true ) )
			{
				$property_info['tags']['price_cut']['class'] = 'price_cut';
				$property_info['tags']['price_cut']['label'] = esc_html__( 'Price Cut', 'realty-house-pl' );
			}
			
			if ( get_post_meta( $p_id, 'property_open_house', true ) )
			{
				$property_info['tags']['open_house']['class'] = 'open_house';
				$property_info['tags']['open_house']['label'] = esc_html__( 'Open House', 'realty-house-pl' );
			}
			
			if ( get_post_meta( $p_id, 'property_foreclosure', true ) )
			{
				$property_info['tags']['foreclosure']['class'] = 'foreclosure';
				$property_info['tags']['foreclosure']['label'] = esc_html__( 'Foreclosure', 'realty-house-pl' );
			}
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Floor Plan
			 * ------------------------------------------------------------------------------------------
			 */
			$property_floor_plan = get_post_meta( $p_id, 'property_floor_plan', true );
			$property_plan_arr   = array ();
			$trimmed_plan_val    = trim( $property_floor_plan );
			if ( ! empty( $trimmed_plan_val ) )
			{
				$property_plan_arr = explode( '---', $property_floor_plan );
				$i                 = 0;
				foreach ( $property_plan_arr as $property_plan )
				{
					$property_plan                                    = trim( $property_plan );
					$property_info['floor_plan']['img'][ $i ]['id']   = $property_plan;
					$property_info['floor_plan']['img'][ $i ]['url']  = wp_get_attachment_url( $property_plan );
					$property_info['floor_plan']['img'][ $i ]['code'] = wp_get_attachment_image( $property_plan, 'image' );
					$i ++;
				}
			}
			$property_info['floor_plan']['count'] = ( ! empty( $property_floor_plan ) ? count( $property_plan_arr ) : 0 );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Attachments
			 * ------------------------------------------------------------------------------------------
			 */
			$property_attachments     = get_post_meta( $p_id, 'property_attachment', true );
			$property_attachments_arr = array ();
			$trimmed_attachment_val   = trim( $property_attachments );
			if ( ! empty( $trimmed_attachment_val ) )
			{
				$property_attachments_arr = explode( '---', $property_attachments );
				$i                        = 0;
				foreach ( $property_attachments_arr as $property_attachment )
				{
					$property_info['attachment']['item'][ $i ]['id']        = trim( $property_attachment );
					$property_info['attachment']['item'][ $i ]['title']     = get_the_title( $property_attachment );
					$property_info['attachment']['item'][ $i ]['url']       = wp_get_attachment_url( $property_attachment );
					$property_info['attachment']['item'][ $i ]['mime_type'] = get_post_mime_type( $property_attachment );
					$i ++;
				}
			}
			$property_info['attachment']['count'] = ( ! empty( $property_attachments ) ? count( $property_attachments_arr ) : 0 );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Property Agents
			 * ------------------------------------------------------------------------------------------
			 */
			$property_agents = get_post_meta( $p_id, 'property_agents', true );
			if ( ! empty( $property_agents ) )
			{
				if ( gettype( $property_agents ) === 'string' )
				{
					$property_agents = array ( $property_agents );
				}
				$property_info['agents']['count'] = count( $property_agents );
				foreach ( $property_agents as $property_agent )
				{
					$property_info['agents']['agent'][] = self::agent_info( $property_agent );
				}
			}
			
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Extra Information
			 * ------------------------------------------------------------------------------------------
			 */
			$property_info['description'] = get_extended( get_post_field( 'post_content', $p_id ) );
			$property_info['url']         = get_permalink( $p_id );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Check if this property is the agent's property or not
			 * ------------------------------------------------------------------------------------------
			 */
			if ( empty( $current_user->ID ) )
			{
				$property_info['owner'] = false;
			}
			elseif ( in_array( 'administrator', $current_user->roles ) )
			{
				$property_info['owner'] = true;
			}
			else
			{
				
				$agent_post_args = array (
					'post_type'   => 'agent',
					'post_status' => array (
						'publish',
						'pending',
						'draft',
						'auto-draft',
						'future',
						'private',
						'inherit',
						'trash'
					),
					'meta_query'  => array (
						array (
							'key'   => 'staff_username',
							'value' => $current_user->data->user_login
						)
					)
				);
				
				$agent_posts = new WP_Query( $agent_post_args );
				
				if ( ! empty( $agent_posts->post_count ) )
				{
					foreach ( $agent_posts->posts as $post_item )
					{
						$agent_id = $post_item->ID;
					}
				}
				$property_agent_val = get_post_meta( $p_id, 'property_agents', true );
				if ( ! empty( $agent_id ) )
				{
					if ( ! empty( $property_agent_val ) && in_array( $agent_id, get_post_meta( $p_id, 'property_agents', true ) ) )
					{
						$property_info['owner'] = true;
					}
					else
					{
						$property_info['owner'] = false;
					}
				}
				else
				{
					$property_info['owner'] = false;
				}
			}
			
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Check if in Comparision
			 * ------------------------------------------------------------------------------------------
			 */
			if ( ! empty( $_COOKIE['pCompare'] ) )
			{
				$p_compare_array                 = explode( ',', $_COOKIE['pCompare'] );
				$property_info['in_comparision'] = ( in_array( $p_id, $p_compare_array ) ? true : false );
			}
			else
			{
				$property_info['in_comparision'] = false;
			}
			/**
			 * ------------------------------------------------------------------------------------------
			 *  Check if is bookmarked
			 * ------------------------------------------------------------------------------------------
			 */
			if ( ! empty( $_COOKIE['pBookmark'] ) )
			{
				$p_bookmark_array            = explode( ',', trim( $_COOKIE['pBookmark'], ',' ) );
				$property_info['bookmarked'] = ( in_array( $p_id, $p_bookmark_array ) ? true : false );
			}
			else
			{
				$property_info['bookmarked'] = false;
			}
			
			return $property_info;
		}
		
		
		/*
		 * Get All Agent Information
		 */
		public function agent_info( $agent_id )
		{
			global $realty_house_opt;
			$agent_info = array ();
			
			$agent_info['has_image'] = has_post_thumbnail( $agent_id );
			if ( $agent_info['has_image'] )
			{
				$agent_info['img']['thumbnail'] = get_the_post_thumbnail( $agent_id, 'thumbnail' );
				$agent_info['img']['medium']    = get_the_post_thumbnail( $agent_id, 'medium' );
				$agent_info['img']['large']     = get_the_post_thumbnail( $agent_id, 'large' );
				$agent_info['img']['full']      = get_the_post_thumbnail( $agent_id, 'full' );
			}
			
			$agent_info['name']             = get_the_title( $agent_id );
			$agent_info['bio']              = get_extended( get_post_field( 'post_content', $agent_id ) );
			$agent_info['username']         = get_post_meta( $agent_id, 'staff_username', true );
			$agent_info['address']          = get_post_meta( $agent_id, 'staff_address', true );
			$agent_info['phone']            = get_post_meta( $agent_id, 'staff_phone', true );
			$agent_info['mobile']           = get_post_meta( $agent_id, 'staff_mobile', true );
			$agent_info['email']            = get_post_meta( $agent_id, 'staff_email', true );
			$agent_info['skype']            = get_post_meta( $agent_id, 'staff_skype', true );
			$agent_info['facebook']         = get_post_meta( $agent_id, 'staff_facebook', true );
			$agent_info['twitter']          = get_post_meta( $agent_id, 'staff_twitter', true );
			$agent_info['google_plus']      = get_post_meta( $agent_id, 'staff_google_plus', true );
			$agent_info['google_plus']      = get_post_meta( $agent_id, 'staff_google_plus', true );
			$agent_info['start_membership'] = get_post_meta( $agent_id, 'staff_start_membership', true );
			$agent_info['license']          = get_post_meta( $agent_id, 'staff_license', true );
			
			$agent_bg_img                 = get_post_meta( $agent_id, 'staff_bg_img', true );
			$agent_info['bg_img']['id']   = $agent_bg_img;
			$agent_info['bg_img']['url']  = wp_get_attachment_url( $agent_bg_img );
			$agent_info['bg_img']['code'] = wp_get_attachment_image( $agent_bg_img, 'image' );
			$agent_info['url']            = get_permalink( $agent_id );
			
			
			if ( ! empty( $realty_house_opt['realty-house-agent-rate-items'] ) ):
				$general_rate = $all_votes = 0;
				foreach ( $realty_house_opt['realty-house-agent-rate-items'] as $index => $rate_item ):
					
					$value     = get_post_meta( get_the_id(), 'agent_rate_' . $index, true );
					$raw_val   = explode( ',', $value );
					$total_val = 0;
					foreach ( $raw_val as $a_val )
					{
						$total_val += $a_val;
						if ( ! empty( $a_val ) )
						{
							$all_votes += 1;
						}
					}
					$final_val = round( $total_val / count( $raw_val ) );
					
					$general_rate += $final_val;
					
					$agent_info['rating']['items'][ 'agent_rate_' . $index ]['title'] = $rate_item;
					$agent_info['rating']['items'][ 'agent_rate_' . $index ]['value'] = $final_val;
					$agent_info['rating']['items'][ 'agent_rate_' . $index ]['votes'] = count( $raw_val );
				
				endforeach;
				
				$agent_info['rating']['rate_items_count'] = count( $realty_house_opt['realty-house-agent-rate-items'] );
				$agent_info['rating']['total_rate']       = round( $general_rate / count( $realty_house_opt['realty-house-agent-rate-items'] ) );
				$agent_info['rating']['total_vote']       = $all_votes;
			
			endif;
			
			
			return $agent_info;
		}
		
		
		/*
		 * Get All of the Testimonial Information
		 */
		public function testimonials_info( $testimonials_id )
		{
			$testimonials_info['has_image'] = has_post_thumbnail( $testimonials_id );
			if ( $testimonials_info['has_image'] )
			{
				$testimonials_info['img']['thumbnail'] = get_the_post_thumbnail( $testimonials_id, 'thumbnail', 'class=post-img' );
				$testimonials_info['img']['medium']    = get_the_post_thumbnail( $testimonials_id, 'medium', 'class=post-img' );
				$testimonials_info['img']['large']     = get_the_post_thumbnail( $testimonials_id, 'large', 'class=post-img' );
				$testimonials_info['img']['full']      = get_the_post_thumbnail( $testimonials_id, 'full', 'class=post-img' );
			}
			
			$testimonials_info['name']     = get_the_title( $testimonials_id );
			$testimonials_info['content']  = get_extended( get_post_field( 'post_content', $testimonials_id ) );
			$testimonials_info['location'] = get_post_meta( $testimonials_id, 'testimonials_guest_location', true );
			$testimonials_info['email']    = get_post_meta( $testimonials_id, 'testimonials_guest_email', true );
			$testimonials_info['phone']    = get_post_meta( $testimonials_id, 'testimonials_guest_phone', true );
			
			return $testimonials_info;
		}
	}
	
	$realty_house_get_info_obj = new realty_house_get_info();
	
	