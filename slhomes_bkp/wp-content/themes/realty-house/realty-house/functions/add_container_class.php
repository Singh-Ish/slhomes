<?php
	if ( ! function_exists( 'realty_house_tm_add_class_body' ) )
	{
		function realty_house_tm_add_class_body( $classes )
		{
			
			global $realty_house_opt, $post;
			if ( is_post_type_archive( 'property' ) || is_tax( 'property-category' ) )
			{
				$classes[] = 'property-listing-pg';
			}
			if ( is_post_type_archive( 'property' ) && ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '3' )
			{
				$classes[] = 'masonry';
			}
			if ( is_post_type_archive( 'property' ) && ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '4' )
			{
				$classes[] = 'map-p-listing';
			}
			if ( is_post_type_archive( 'property' ) && ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '2' )
			{
				$classes[] = 'list';
			}
			if ( is_tax( 'property-category' ) && ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '2' )
			{
				$classes[] = 'list';
			}
			if ( is_page_template( '../templates/search-property.php' ) && ! empty( $realty_house_opt['realty-house-property-search-template'] ) )
			{
				switch ( $realty_house_opt['realty-house-property-search-template'] )
				{
					case( '2' ):
						$classes[] = 'list';
					break;
					case( '3' ):
						$classes[] = 'masonry';
					break;
					case( '4' ):
						$classes[] = 'map-listing';
					break;
				}
				
			}
			if ( is_page_template( '../templates/submit-property.php' ) )
			{
				$classes[] = 'submit-property';
			}
			if ( is_page_template( '../templates/property-compare.php' ) )
			{
				$classes[] = 'compare';
			}
			if ( is_page_template( '../templates/search-property.php' ) )
			{
				$classes[] = 'property-listing-pg';
			}
			if ( get_post_meta( get_the_id(), 'realty_house_page_class', true ) )
			{
				$classes[] = get_post_meta( get_the_id(), 'realty_house_page_class', true );
			}
			
			return $classes;
		}
		
		add_filter( 'body_class', 'realty_house_tm_add_class_body' );
	}