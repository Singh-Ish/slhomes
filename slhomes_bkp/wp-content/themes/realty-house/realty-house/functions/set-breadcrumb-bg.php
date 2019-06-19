<?php
	if ( ! function_exists( 'realty_house_tm_breadcrumb_bg' ) )
	{
		function realty_house_tm_breadcrumb_bg( $page_id )
		{
			global $realty_house_opt;
			
			$breadcrumb_bg_id      = get_post_meta( $page_id, 'realty_house_page_bread_crumb', true );
			$breadcrumb_bg_img     = wp_get_attachment_image_src( $breadcrumb_bg_id, 'full' );
			$breadcrumb_bg_new     = ( ! empty( $breadcrumb_bg_img[0] ) ? $breadcrumb_bg_img[0] : '' );
			$default_breadcrumb_bg = ( ! empty( $realty_house_opt['opt-breadcrumb-bg']['url'] ) ? $realty_house_opt['opt-breadcrumb-bg']['url'] : '' );
			
			if ( is_post_type_archive( 'guest_book' ) && ! empty( $realty_house_opt['opt-guestbook-breadcrumb-bg']['url'] ) )
			{
				if ( ! empty( $realty_house_opt['opt-guestbook-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-guestbook-breadcrumb-bg']['url'];
				}
			}
			elseif ( ( is_post_type_archive( 'rooms' ) || is_page_template( 'templates/room-grid-sidebar.php' ) || is_page_template( 'templates/room-masonry.php' ) || is_page_template( 'templates/room-list.php' ) ) && ! empty( $realty_house_opt['opt-rooms-breadcrumb-bg']['url'] ) )
			{
				if ( ! empty( $realty_house_opt['opt-rooms-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-rooms-breadcrumb-bg']['url'];
				}
			}
			elseif ( ( is_page_template( 'templates/gallery-grid.php' ) || is_page_template( 'templates/gallery-masonry.php' ) ) && ! empty( $realty_house_opt['opt-gallery-breadcrumb-bg']['url'] ) )
			{
				if ( ! empty( $realty_house_opt['opt-gallery-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-gallery-breadcrumb-bg']['url'];
				}
			}
			elseif ( is_404() && ! empty( $realty_house_opt['opt-404-breadcrumb-bg']['url'] ) )
			{
				if ( ! empty( $realty_house_opt['opt-404-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-404-breadcrumb-bg']['url'];
				}
			}
			elseif ( ( is_home() && ! empty( $realty_house_opt['opt-blog-breadcrumb-bg']['url'] ) ) || is_search() )
			{
				if ( ! empty( $realty_house_opt['opt-blog-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-blog-breadcrumb-bg']['url'];
				}
			}
			elseif ( is_singular() )
			{
				if ( ! empty( $realty_house_opt['opt-blog-details-breadcrumb-bg']['url'] ) )
				{
					$internal_page_bread = $realty_house_opt['opt-blog-details-breadcrumb-bg']['url'];
				}
			}
			
			if ( ! empty( $breadcrumb_bg_new ) )
			{
				$return_array['has_image'] = true;
				$return_array['img_src']   = $breadcrumb_bg_new;
				
				return $return_array;
			}
			elseif ( isset( $internal_page_bread ) )
			{
				$return_array['has_image'] = true;
				$return_array['img_src']   = $internal_page_bread;
				
				return $return_array;
			}
			elseif ( ! empty( $default_breadcrumb_bg ) )
			{
				$return_array['has_image'] = true;
				$return_array['img_src']   = $default_breadcrumb_bg;
				
				return $return_array;
			}
			else
			{
				$return_array['has_image'] = false;
				$return_array['img_src']   = REALTY_HOUSE_IMG_PATH . 'bread-bg.png';
				
				return $return_array;
			}
		}
	}