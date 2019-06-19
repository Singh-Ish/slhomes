<?php
	
	/**
	 * Get Properties based on different Criteria
	 */
	class realty_house_get_property
	{
	
		/**
		 * It will echo the properties based on the property type
		 * and exclude the main property from the result
		 *
		 * @param $p_type   property type variable
		 * @param $p_id     post ID ( Property ID )
		 */
		public function related_properties( $p_type, $p_id )
		{
			$property_info = new realty_house_get_info();
			
			$related_property_args = array(
				'posts_per_page' => '3',
				'orderby'        => 'rand',
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'meta_key'       => 'property_property_type',
				'meta_value'     => $p_type
			);
			
			$related_properties = new WP_Query( $related_property_args );
			if ( $related_properties->have_posts() )
			{
				while ( $related_properties->have_posts() )
				{
					
					$related_properties->the_post();
					$post_id = get_the_id();
					if ( $post_id == $p_id )
					{
						continue;
					}
					$property_information = $property_info->property_info( $post_id );
					
					echo '
						<div class="property-box col-xs-6 col-md-4 item">
							<div class="inner-box">
								<a href="' . esc_url( $property_information['url'] ) . '" class="img-container">
									' . ( $property_information['gallery']['count'] > 0 ? $property_information['gallery']['img'][0]['code']['large'] : '' ) . '
								</a>
								<div class="bot-sec">
									<div class="title">' . esc_html( get_the_title() ) . '</div>
									<div class="price"><i class="fa fa-inr"></i>' . esc_html( $property_information['price']['value'] ) . '</div>
									<div class="location">' . esc_html( $property_information['address'] ) . '</div>
									<div class="b-sec">
										<div class="more-info">
											<a href="' . esc_url( $property_information['url'] ) . '" class="btn btn-default">' . esc_html__( 'See Now', 'realty-house-pl' ) . '</a>
										</div>
										<div class="fav-compare">
											<a href="#" class="realty-house-loading compare"></a>
											<a href="#" class="realty-house-star fav"></a>
										</div>
									</div>
								</div>
							</div>
						</div>									
					';
				}
				wp_reset_postdata();
			}
			else
			{
				esc_html_e( 'There is no related property.', 'realty-house-pl' );
			}
		}
		
		
		/**
		 * Returns the properties based on agent ID and property status
		 *
		 * @param $agent_id     Agent ID
		 * @param $p_status     Property Status
		 */
		public function properties_by_agent( $agent_id, $p_status )
		{
			$property_info       = new realty_house_get_info();
			$property_agent_args = array(
				'post_type'   => 'property',
				'post_status' => 'publish',
				'meta_query'  => array(
					'relation' => 'AND',
					array(
						'key'   => 'property_property_status',
						'value' => $p_status
					),
					array(
						'key'     => 'property_agents',
						'value'   => $agent_id,
						'compare' => 'LIKE',
					),
				),
			);
			
			$properties_agent     = new WP_Query( $property_agent_args );
			$property_agent_array = array ();
			
			if ( $properties_agent->have_posts() )
			{
				while ( $properties_agent->have_posts() )
				{
					$properties_agent->the_post();
					$post_id = get_the_id();
					
					$property_agent_array[] = $property_info->property_info( $post_id );
				}
				wp_reset_postdata();
			}
			
			return $property_agent_array;
		}
		
		
		/**
		 * returns all the active ( not sold ) properties of agent
		 *
		 * @param $agent_id     Agent ID
		 * @param $sold_id      ID of sold status
		 */
		public function active_properties_agent( $agent_id, $sold_id )
		{
			$property_info       = new realty_house_get_info();
			$property_agent_args = array(
				'post_type'   => 'property',
				'post_status' => 'publish',
				'meta_query'  => array(
					'relation' => 'AND',
					array(
						'key'     => 'property_property_status',
						'value'   => $sold_id,
						'compare' => 'NOT LIKE',
					),
					array(
						'key'     => 'property_agents',
						'value'   => $agent_id,
						'compare' => 'LIKE',
					),
				),
			);
			
			$properties_agent     = new WP_Query( $property_agent_args );
			$property_agent_array = array ();
			
			if ( $properties_agent->have_posts() )
			{
				while ( $properties_agent->have_posts() )
				{
					$properties_agent->the_post();
					$post_id = get_the_id();
					
					$property_agent_array[] = $property_info->property_info( $post_id );
				}
				wp_reset_postdata();
			}
			
			return $property_agent_array;
		}
		
		
		/**
		 * returns all the properties of an agent
		 *
		 * @param $agent_id Agent ID
		 */
		public function all_agent_properties( $agent_id )
		{
			$property_info       = new realty_house_get_info();
			$property_agent_args = array(
				'post_type'   => 'property',
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'     => 'property_agents',
						'value'   => $agent_id,
						'compare' => 'LIKE',
					),
				),
			);
			
			$properties_agent     = new WP_Query( $property_agent_args );
			$property_agent_array = array ();
			
			if ( $properties_agent->have_posts() )
			{
				while ( $properties_agent->have_posts() )
				{
					$properties_agent->the_post();
					$post_id = get_the_id();
					
					$property_agent_array[] = $property_info->property_info( $post_id );
				}
				wp_reset_postdata();
			}
			
			return $property_agent_array;
		}
		
	}
	
	$realty_house_get_property_obj = new realty_house_get_property();
