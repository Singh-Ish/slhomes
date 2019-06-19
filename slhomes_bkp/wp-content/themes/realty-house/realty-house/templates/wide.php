<?php
	/**
	 *    wide.php
	 *    Wide page template
	 *  Template Name: Wide
	 */
	
	get_header();
	
	$realty_house_page_id    = ( get_post_meta( $post->ID, 'realty_house_page_id' ) != null ? get_post_meta( $post->ID, 'realty_house_page_id', true ) : '' );
	$realty_house_page_class = ( get_post_meta( $post->ID, 'realty_house_page_class' ) != null ? get_post_meta( $post->ID, 'realty_house_page_class', true ) : '' );
	
	echo '<section ' . ( isset( $realty_house_page_id ) && $realty_house_page_id != '' ? ( 'id="' . esc_attr( $realty_house_page_id ) . '"' ) : '' ) . ' class="main-bg-color main-page-container' . ( $realty_house_page_class !== '' ? esc_attr( $realty_house_page_class ) : '' ) . '">
			<div class="inner-box">';
	
	if ( have_posts() )
	{
		while ( have_posts() )
		{
			$post_id = get_the_id();
			the_post();
			the_content();
			
			if ( comments_open() )
			{
				echo '<div class="comment-boxes container details-boxes">';
				
				$comment_form_fields = array (
					'author' => '<div class="field-row"><input type="text" id="author" name="author" placeholder="Name *" required="required"></div>',
					'email'  => '<div class="field-row"><input type="text" id="email" name="email" placeholder="Email *" required="required"></div>',
				);
				$comment_form_arg    = array (
					'fields'            => apply_filters( 'comment_form_default_fields', $comment_form_fields ),
					'title_reply'       => esc_html__( 'Write a Comment', 'realty-house' ),
					'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'realty-house' ),
					'cancel_reply_link' => esc_html__( 'Cancel', 'realty-house' ),
					'comment_field'     => '
														<div class="field-row">
															<textarea name="comment" id="comment" placeholder="' . esc_html__( 'Your Comment', 'realty-house' ) . '" required="required"></textarea>
														</div>',
					'must_log_in'       => '<p class="must-log-in">' . sprintf( wp_kses_post( esc_html__( 'You must be <a href="%s">logged in</a> to share your review.', 'realty-house' ) ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
					'label_submit'      => esc_html__( 'Submit', 'realty-house' )
				);
				
				comment_form( $comment_form_arg );
				
				echo '</div>';
			}
		}
	}
	
	
	echo "</div></section>";
	
	get_footer();