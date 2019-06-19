<?php
	
	class realty_house_get_pages
	{
		public static function page_template( $page_template )
		{
			$page_arg = array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'meta_key'    => '_wp_page_template',
				'meta_value'  => $page_template,
			);
			
			$page_list = new WP_Query( $page_arg );
			
			if ( $page_list->have_posts() )
			{
				while ( $page_list->have_posts() )
				{
					$page_list->the_post();
					$post_id = get_the_id();
				}
			}
			wp_reset_postdata();
			
			if ( ! empty( $post_id ) )
			{
				$get_page_info['id']  = intval( $post_id );
				$get_page_info['url'] = esc_url( get_permalink( $post_id ) );

				return $get_page_info;
			}
			else
			{
				return false;
			}
		}
	}
	
	$realty_house_get_pages_obj = new realty_house_get_pages();

	
	