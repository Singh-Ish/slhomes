<?php
	/**
	 * page.php
	 * Default template of pages.
	 */
	
	get_header();
	
	$realty_house_page_id    = ( get_post_meta( $post->ID, 'realty_house_page_id' ) != null ? get_post_meta( $post->ID, 'realty_house_page_id', true ) : '' );
	$realty_house_page_class = ( get_post_meta( $post->ID, 'realty_house_page_class' ) != null ? get_post_meta( $post->ID, 'realty_house_page_class', true ) : '' );
?>
	<!-- Main Container -->
	<section class="page-content container 
		<?php echo ( $realty_house_page_class !== '' ) ? esc_attr( $realty_house_page_class ) : ''; ?>"
		<?php echo isset( $realty_house_page_id ) && $realty_house_page_id != '' ? 'id="' . esc_attr( $realty_house_page_id ) . '"' : ''; ?>>
		<section class="blog-container <?php echo( is_active_sidebar( 'main-side-bar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ) ?>">
			<!-- Post Container -->
			<div class="blog-items list clearfix">
				<?php
					if ( have_posts() )
					{
						global $realty_house_opt;
						while ( have_posts() )
						{
							the_post();
							$post_id        = get_the_id();
							$post_class_arr = get_post_class();
							$post_classes   = '';
							foreach ( $post_class_arr as $post_class )
							{
								$post_classes .= $post_class . ' ';
							}
							$post_format = get_post_format( $post_id ) !== false ? get_post_format( $post_id ) : '';
							
							if ( $post_format == 'link' || $post_format == 'quote' )
							{
								$link = realty_house_tm_get_link_url();
							}
							else
							{
								$link = get_permalink();
							}
							
							echo '
					<div class="blog-item clearfix' . esc_attr( $post_classes ) . ' ' . esc_attr( $post_format ) . '">';
							if ( get_the_post_thumbnail( $post_id ) !== '' )
							{
								echo '
								<a href="' . esc_url( $link ) . '" class="img-container">
									' . get_the_post_thumbnail( $post_id ) . '
								</a>';
							}
							if ( get_the_title() !== '' )
							{
								echo '<a href="' . esc_url( $link ) . '" class="title">' . get_the_title() . '</a>';
							}
							
							echo '<div class="desc">';
							the_content();
							
							wp_link_pages( array (
								'before'      => '<div class="post-pagination-box clearfix">',
								'after'       => '</div>',
								'link_before' => '',
								'link_after'  => '',
								'pagelink'    => '<span>%</span>',
								'separator'   => '',
							) );
							
							echo '</div>
							</div>';
							
							if ( wp_get_post_tags( $post_id ) )
							{
								echo '
								<div class="details-boxes">
									<div class="title">Tags <i class="fa fa-angle-down"></i></div>
									<div class="content">
										<ul class="list-inline tag-list">';
								
								$tag_list = get_the_tags( $post_id );
								foreach ( $tag_list as $single_tag )
								{
									echo '<li><a href="' . esc_url( home_url( '/' ) ) . '/tag/' . esc_attr( $single_tag->slug ) . '">' . esc_html( $single_tag->name ) . '</a><li>';
								}
								echo '</ul>
									</div>
								</div>';
							}
							if ( ! post_password_required() && get_comments_number() != '0' ) :
								echo '
							<div class="comments-container details-boxes">
								<div class="title">';
								comments_number( '', esc_html__( '1 Comment', 'realty-house' ), esc_html__( '% Comments', 'realty-house' ) );
								echo ' <i class="fa fa-angle-down"></i></div>
 	    						<div class="content">';
								comments_template();
								echo '</div>
							</div>';
							endif;
							
							if ( comments_open() )
							{
								echo '<div class="comment-boxes details-boxes">';
								
								$comment_form_fields = array (
									'author' => '<div class="field-row"><input type="text" id="author" name="author" placeholder="' . esc_attr__( 'Name *', 'realty-house' ) . '" required="required"></div>',
									'email'  => '<div class="field-row"><input type="text" id="email" name="email" placeholder="' . esc_attr__( 'Email *', 'realty-house' ) . '" required="required"></div>',
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
							echo '</div>';
						}
					}
				?>
			</div>
		</section>
		<?php
			get_sidebar();
		?>
	</section>
<?php
	get_footer();