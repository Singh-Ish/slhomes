<?php
	/**
	 * The template for displaying comments
	 *
	 * If the current post is protected by a password and
	 * the visitor has not yet entered the password we will
	 * return early without loading the comments.
	 */
	if ( post_password_required() )
	{
		return;
	}
?>

<div id="comments" class="comment-box-container">
	
	<?php 
		if ( have_comments() ) : ?>
		<div class="comment-box">
			<?php
				wp_list_comments( array(
					'style'       => 'div',
					'short_ping'  => true,
					'avatar_size' => 100,
				) );
			?>
		</div><!-- .comment-list -->
	
	<?php endif; // have_comments() ?>
	
	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
			?>
			<div class="no-comments"><?php esc_html_e( 'Comments are closed.', 'realty-house' ); ?></div>
		<?php endif;
		paginate_comments_links();
	?>
</div><!-- .comments-area -->
