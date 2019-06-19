<?php
	if ( ! function_exists( 'realty_house_tm_entry_meta' ) )
	{
		function realty_house_tm_entry_meta( $post_url )
		{
			if ( in_array( get_post_type(), array ( 'post', 'attachment' ) ) )
			{
				$time_string = '<a href="' . esc_url( $post_url ) . '"><time class="entry-date published" datetime="%1$s">%2$s</time></a>';
				$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), get_the_date(), esc_attr( get_the_modified_date( 'c' ) ), get_the_modified_date() );
				
				printf( '<div class="date"><i class="fa fa-calendar"></i>%1$s</div>', wp_kses_post( $time_string ) );
			}
			
			if ( 'post' == get_post_type() )
			{
				printf( '<div class="author"><i class="fa fa-user"></i><a href="%1$s">%2$s</a></div>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() );
			}
			
			if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) )
			{
				echo '<div class="comment"><i class="fa fa-comments-o"></i>';
				comments_popup_link( esc_html__( 'Leave a comment', 'realty-house' ), esc_html__( '1 Comment', 'realty-house' ), esc_html__( '% Comments', 'realty-house' ) );
				echo '</div>';
			}
			if ( is_single() )
			{
				echo '<div class="category"><i class="fa fa-folder"></i>';
				the_category( ', ' );
				echo '</div>';
			}
		}
	}