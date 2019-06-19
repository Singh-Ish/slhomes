<?php
	// single.php
	// Index Page
	
	global $post;
	get_header();
?>
	<!-- Main Container -->
	<section class="page-content container">
		
		<?php
			if ( is_search() && have_posts() )
			{
				echo '<h3 class="rh-title"><span>' . esc_html__( 'Search results for : ', 'realty-house' ) . '<b>' . get_search_query() . '</b></span></h3>';
			}
			elseif ( is_category() )
			{
				$current_category_info = get_the_category();
				echo '<h3 class="rh-title"><span>' . esc_html__( 'Browsing Category : ', 'realty-house' ) . '<b>' . $current_category_info[0]->name . '</b></span></h3>';
			}
			elseif ( is_tag() )
			{
				echo '<h3 class="rh-title"><span>' . esc_html__( 'Browsing Tag : ', 'realty-house' ) . '<b>' . single_tag_title( '', false ) . '</b></span></h3>';
			}
			elseif ( is_archive() )
			{
				if ( is_day() )
				{
					echo '<h3 class="rh-title"><span>' . esc_html__( 'Daily Archives : ', 'realty-house' ) . '<b>' . get_the_date() . '</b></span></h3>';
				}
				elseif ( is_month() )
				{
					echo '<h3 class="rh-title"><span>' . esc_html__( 'Monthly Archives : ', 'realty-house' ) . '<b>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'realty-house' ) ) . '</b></span></h3>';
					
				}
				elseif ( is_year() )
				{
					echo '<h3 class="rh-title"><span>' . esc_html__( 'Daily Archives : ', 'realty-house' ) . '<b>' . get_the_date( _x( 'Y', 'yearly archives date format', 'realty-house' ) ) . '</b></span></h3>';
				}
				else
				{
					echo '<h3 class="rh-title"><span>' . esc_html__( 'Archives', 'realty-house' ) . '</span></h3>';
				}
			}
			else
			{
				echo '<h3 class="rh-title"><span>' . esc_html__( 'Our Blog Articles', 'realty-house' ) . '</span></h3>';
			}
		?>

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
							<div class="blog-item ' . esc_attr( $post_classes ) . ' ' . esc_attr( $post_format ) . '">';
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
							
							echo '<div class="meta-data clearfix">';
							realty_house_tm_entry_meta( $link );
							echo '</div>
							<div class="desc">';
							
							if ( isset( $realty_house_opt['realty-house-blog-type'] ) && $realty_house_opt['realty-house-blog-type'] == '2' )
							{
								echo( $post_format == 'quote' ? '<blockquote>' : '' );
								the_content();
								echo( $post_format == 'quote' ? '</blockquote>' : '' );
							}
							elseif ( $post_format == 'image' || $post_format == 'gallery' )
							{
								the_content();
							}
							else
							{
								echo( $post_format == 'quote' ? '<blockquote>' : '' );
								the_excerpt();
								echo( $post_format == 'quote' ? '</blockquote>' : '' );
							}
							
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
						}
					}
					else
					{
						if ( is_search() )
						{
							echo '<div id="search-no-result">';
							echo '<h2 class="rh-title"><span>' . esc_html__( 'Ooops!! Nothing Found Now', 'realty-house' ) . ' :(</span></h2>';
							echo '<div class="subtitle">' . esc_html__( 'Please try again with some different keywords.', 'realty-house' ) . '</div>';
							get_search_form();
							echo '</div>';
						}
					}
					realty_house_tm_pagination();
				?>
			</div>
		</section>
		<?php
			get_sidebar();
		?>
	</section>
<?php
	get_footer();