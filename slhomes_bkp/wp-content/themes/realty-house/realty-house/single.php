<?php
	// single.php
	// Post Details
	
	global $post;
	get_header();
?>
	<!-- Main Container -->
	<section class="page-content container">
		<h3 class="rh-title"><span><?php esc_html_e( 'Real Estate Articles', 'realty-house' ) ?></span></h3>
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
					<div class="blog-item clearfix ' . esc_attr( $post_classes ) . ' ' . esc_attr( $post_format ) . '">';
							if ( get_the_post_thumbnail( $post_id ) !== '' )
							{
								echo '
								<div class="img-container">
									' . get_the_post_thumbnail( $post_id ) . '
								</div>';
							}
							if ( get_the_title() !== '' )
							{
								echo '<div class="title">' . get_the_title() . '</div>';
							}
							
							echo '<div class="meta-data clearfix">';
							realty_house_tm_entry_meta( $link );
							echo '</div>
							<div class="desc">';
							the_content();
							
							wp_link_pages( array(
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
							if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
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
								
								$comment_form_fields = array(
									'author' => '<div class="field-row"><input type="text" id="author" name="author" placeholder="'. esc_attr__( 'Name *', 'realty-house' ) .'" required="required"></div>',
									'email'  => '<div class="field-row"><input type="text" id="email" name="email" placeholder="'. esc_attr__( 'Email *', 'realty-house' ) .'" required="required"></div>',
								);
								$comment_form_arg    = array(
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