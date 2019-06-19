<?php
	
	/**
	 * Provide Stats of website/post types and etc
	 */
	class realty_house_stats
	{
		
		/**
		 * Return count of ALL the published properties
		 * @return int
		 */
		public function all_properties()
		{
			$all_properties = array(
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'posts_per_page' => - 1
			);
			
			$all_properties_items = new WP_Query( $all_properties );
			
			if ( $all_properties_items->have_posts() )
			{
				$property_counts = $all_properties_items->post_count;
			}
			else
			{
				$property_counts = 0;
			}
			
			return $property_counts;
		}
		
		
		/**
		 * Return count of SOLD the published properties
		 * @return int
		 */
		public function sold_properties()
		{
			global $realty_house_opt;
			$sold_id = array_search( 'Sold', $realty_house_opt['realty-house-property-status'] ) + 1;
			
			$sold_properties = array(
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'meta_key'       => 'property_property_status',
				'meta_value'     => $sold_id,
				'posts_per_page' => - 1
			);
			
			$sold_properties_item = new WP_Query( $sold_properties );
			
			if ( $sold_properties_item->have_posts() )
			{
				$property_counts = $sold_properties_item->post_count;
			}
			else
			{
				$property_counts = 0;
			}
			
			return $property_counts;
		}
		
		/**
		 * Return count of ACTIVE the published properties
		 * @return int
		 */
		public function active_properties()
		{
			global $realty_house_opt;
			$sold_id = array_search( 'Sold', $realty_house_opt['realty-house-property-status'] ) + 1;
			
			$active_properties = array(
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => 'property_property_status',
						'value'   => $sold_id,
						'compare' => '!=',
					)
				),
				'posts_per_page' => - 1
			);
			
			$active_properties_item = new WP_Query( $active_properties );
			
			if ( $active_properties_item->have_posts() )
			{
				$property_counts = $active_properties_item->post_count;
			}
			else
			{
				$property_counts = 0;
			}
			
			return $property_counts;
		}
		
		/**
		 * Return count of ALL the published agents
		 * @return int
		 */
		public function all_agents()
		{
			$all_agents = array(
				'post_type'      => 'agent',
				'post_status'    => 'publish',
				'posts_per_page' => - 1
			);
			
			$all_agents_items = new WP_Query( $all_agents );
			
			if ( $all_agents_items->have_posts() )
			{
				$agent_counts = $all_agents_items->post_count;
			}
			else
			{
				$agent_counts = 0;
			}
			
			return $agent_counts;
		}
		
		
	}