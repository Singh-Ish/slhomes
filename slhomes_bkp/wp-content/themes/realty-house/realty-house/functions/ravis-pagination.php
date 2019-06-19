<?php
	if ( ! function_exists( 'realty_house_tm_pagination' ) )
	{
		function realty_house_tm_pagination( $input_query_variable = '' )
		{
			global $post;
			if ( ! empty( $input_query_variable ) )
			{
				$wp_query = $input_query_variable;
			}
			else
			{
				global $wp_query;
			}
			
			$wp_query->query_vars['paged'] > 1 ? $current = esc_html( $wp_query->query_vars['paged'] ) : $current = 1;
			
			if ( get_option( 'permalink_structure' ) != '' && ! is_page_template( '../templates/search-property.php' ) )
			{
				$format = 'page/%#%';
				$base   = get_pagenum_link( 1 ) . '%_%';
				$args   = false;
			}
			elseif ( is_page_template( '../templates/search-property.php' ) )
			{
				$base       = get_permalink( $post->ID ) . '%_%';
				$url_option = get_option( 'permalink_structure' );
				
				if ( ! empty( $url_option ) )
				{
					$format = 'page/%#%/';
					$args   = $_GET;
				}
				else
				{
					$format = '&paged=%#%';
					$args   = false;
				}
			}
			else
			{
				$format = ( ! empty( $_SERVER['QUERY_STRING'] ) ? '&paged=%#%' : '?paged=%#%' );
				$base   = get_pagenum_link( 1 ) . '%_%';
				$args   = false;
			}
			
			$pagination = array(
				'base'               => $base,
				'format'             => $format,
				'total'              => $wp_query->max_num_pages,
				'current'            => $current,
				'prev_text'          => '<i class="fa fa-angle-left"></i>',
				'next_text'          => '<i class="fa fa-angle-right"></i>',
				'show_all'           => true,
				'type'               => 'list',
				'add_args'           => $args,
				'before_page_number' => '<span>',
				'after_page_number'  => '</span>'
			);
			
			$pagination_links = paginate_links( $pagination );
			
			if ( $pagination_links != null )
			{
				echo '<div class="pagination-box clearfix">';
				echo wp_kses_post( $pagination_links );
				echo '</div>';
			}
		}
	}