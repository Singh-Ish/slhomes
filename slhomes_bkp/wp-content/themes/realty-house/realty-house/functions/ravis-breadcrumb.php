<?php
	if ( ! function_exists( 'realty_house_tm_breadcrumbs' ) )
	{
		function realty_house_tm_breadcrumbs()
		{
			/* === OPTIONS === */
			$text['home']     = esc_html__( 'Home', 'realty-house' ); // text for the 'Home' link
			$text['category'] = esc_html__( 'Archive by Category "%s"', 'realty-house' ); // text for a category page
			$text['tax']      = esc_html__( 'Archive for "%s"', 'realty-house' ); // text for a taxonomy page
			$text['search']   = esc_html__( 'Search Results for "%s" Query', 'realty-house' ); // text for a search results page
			$text['tag']      = esc_html__( 'Posts Tagged "%s"', 'realty-house' ); // text for a tag page
			$text['author']   = esc_html__( 'Articles Posted by %s', 'realty-house' ); // text for an author page
			$text['404']      = esc_html__( 'Error 404', 'realty-house' ); // text for the 404 page

			$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
			$showOnHome  = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
			$delimiter   = ''; // delimiter between crumbs
			$before      = '<li class="active">'; // tag before the current crumb
			$after       = '</li>'; // tag after the current crumb
			/* === END OF OPTIONS === */

			global $post;
			$homeLink   = esc_url( home_url( '/' ) );
			$linkBefore = '<li %3$s>';
			$linkAfter  = '</li>';
			$linkAttr   = ' rel="v:url" property="v:title"';
			$link       = wp_kses_post( $linkBefore ) . '<a' . esc_attr( $linkAttr ) . ' href="%1$s">%2$s</a>' . wp_kses_post( $linkAfter );
			
			if ( is_home() && ! is_front_page() )
			{

				$main_page_title = explode( ' | ', wp_title( " | ", false, "right" ) );
				echo '
				<ul>
					<li class="home"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $text['home'] ) . '</a></li>
					<li class="active">' . esc_html( $main_page_title[0] ) . '</li>
				</ul>';

			}
			elseif ( is_home() || is_front_page() )
			{

				if ( $showOnHome == 1 )
				{
					echo '<ul><li class="home"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $text['home'] ) . '</a></li></ul>';
				}

			}
			else
			{

				echo '<ul>' . sprintf( $link, esc_url( $homeLink ), esc_html( $text['home'] ), 'class="home"' ) . esc_html( $delimiter );

				if ( is_category() )
				{
					$thisCat = get_category( get_query_var( 'cat' ), false );
					if ( ! empty( $thisCat->parent ) && $thisCat->parent != 0 )
					{
						$cats = get_category_parents( $thisCat->parent, true, esc_html( $delimiter ) );
						$cats = str_replace( '<a', wp_kses_post( $linkBefore ) . '<a' . $linkAttr, $cats );
						$cats = str_replace( '</a>', '</a>' . wp_kses_post( $linkAfter ), $cats );
						echo wp_kses_post( $cats );
					}
					echo wp_kses_post( $before ) . sprintf( esc_html( $text['category'] ), single_cat_title( '', false ) ) . wp_kses_post( $after );

				}
				elseif ( is_tax() )
				{
					$thisCat = get_category( get_query_var( 'tax_query' ), false );
					if ( ! empty( $thisCat->parent ) && $thisCat->parent != 0 )
					{
						$cats = get_category_parents( $thisCat->parent, true, esc_html( $delimiter ) );
						$cats = str_replace( '<a', wp_kses_post( $linkBefore ) . '<a' . $linkAttr, $cats );
						$cats = str_replace( '</a>', '</a>' . wp_kses_post( $linkAfter ), $cats );
						echo wp_kses_post( $cats );
					}
					echo wp_kses_post( $before ) . sprintf( esc_html( $text['tax'] ), single_cat_title( '', false ) ) . wp_kses_post( $after );

				}
				elseif ( is_search() )
				{
					echo wp_kses_post( $before ) . sprintf( esc_html( $text['search'] ), get_search_query() ) . wp_kses_post( $after );

				}
				elseif ( is_day() )
				{
					echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), '' ) . esc_html( $delimiter );
					echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ), '' ) . esc_html( $delimiter );
					echo wp_kses_post( $before ) . get_the_time( 'd' ) . wp_kses_post( $after );

				}
				elseif ( is_month() )
				{
					echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), '' ) . esc_html( $delimiter );
					echo wp_kses_post( $before ) . get_the_time( 'F' ) . wp_kses_post( $after );

				}
				elseif ( is_year() )
				{
					echo wp_kses_post( $before ) . get_the_time( 'Y' ) . wp_kses_post( $after );

				}
				elseif ( is_single() && ! is_attachment() )
				{
					if ( get_post_type() != 'post' )
					{
						$post_type = get_post_type_object( get_post_type() );
						$slug      = $post_type->rewrite;
						printf( $link, esc_url( $homeLink ) . '/' . $slug['slug'] . '/', $post_type->labels->singular_name, '' );
						if ( $showCurrent == 1 )
						{
							echo esc_html( $delimiter ) . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
						}
					}
					else
					{
						$cat  = get_the_category();
						$cat  = $cat[0];
						$cats = get_category_parents( $cat, true, esc_html( $delimiter ) );
						if ( $showCurrent == 0 )
						{
							$cats = preg_replace( "#^(.+)esc_html( $delimiter )$#", "$1", $cats );
						}
						$cats = str_replace( '<a', wp_kses_post( $linkBefore ) . '<a' . $linkAttr, $cats );
						$cats = str_replace( '</a>', '</a>' . wp_kses_post( $linkAfter ), $cats );
						echo wp_kses_post( $cats );
						if ( $showCurrent == 1 )
						{
							echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
						}
					}

				}
				elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() )
				{
					$post_type = get_post_type_object( get_post_type() );
					echo wp_kses_post( $before ) . $post_type->labels->singular_name . wp_kses_post( $after );

				}
				elseif ( is_attachment() )
				{
					$parent = get_post( $post->post_parent );
					$cat    = get_the_category( $parent->ID );
					$cat    = $cat[0];
					$cats   = get_category_parents( $cat, true, esc_html( $delimiter ) );
					$cats   = str_replace( '<a', wp_kses_post( $linkBefore ) . '<a' . $linkAttr, $cats );
					$cats   = str_replace( '</a>', '</a>' . wp_kses_post( $linkAfter ), $cats );
					echo wp_kses_post( $cats );
					printf( $link, get_permalink( $parent ), $parent->post_title );
					if ( $showCurrent == 1 )
					{
						echo esc_html( $delimiter ) . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}

				}
				elseif ( is_page() && ! $post->post_parent )
				{
					if ( $showCurrent == 1 )
					{
						echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}

				}
				elseif ( is_page() && $post->post_parent )
				{
					$parent_id   = $post->post_parent;
					$breadcrumbs = array();
					while ( $parent_id )
					{
						$page          = get_post( $parent_id );
						$breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ), '' );
						$parent_id     = $page->post_parent;
					}
					$breadcrumbs = array_reverse( $breadcrumbs );
					for ( $i = 0; $i < count( $breadcrumbs ); $i ++ )
					{
						echo wp_kses_post( $breadcrumbs[ $i ] );
						if ( $i != count( $breadcrumbs ) - 1 )
						{
							echo esc_html( $delimiter );
						}
					}
					if ( $showCurrent == 1 )
					{
						echo esc_html( $delimiter ) . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}

				}
				elseif ( is_tag() )
				{
					echo wp_kses_post( $before ) . sprintf( esc_html( $text['tag'] ), single_tag_title( '', false ) ) . wp_kses_post( $after );

				}
				elseif ( is_author() )
				{
					global $author;
					$userdata = get_userdata( $author );
					echo wp_kses_post( $before ) . sprintf( esc_html( $text['author'] ), $userdata->display_name ) . wp_kses_post( $after );

				}
				elseif ( is_404() )
				{
					echo wp_kses_post( $before ) . esc_html( $text['404'] ) . wp_kses_post( $after );
				}
				if ( get_query_var( 'paged' ) )
				{
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					{
						echo ' (';
					}
					echo '<li class="paged_no">'. esc_html__( 'Page', 'realty-house' ) . ' ' . get_query_var( 'paged' ) .'</li>';
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					{
						echo ')';
					}
				}
				echo '</ul>';
			}
		}
	}