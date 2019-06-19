<?php
	/**
	 * Header.php
	 * The header section of the theme
	 */
	global $realty_house_opt;
	
	$realty_house_page_meta_id = ( get_post_meta( get_the_id(), 'realty_house_page_id', true ) ? get_post_meta( get_the_id(), 'realty_house_page_id', true ) : '' );
	if ( class_exists( 'realty_house_get_pages' ) )
	{
		$get_page_obj = new realty_house_get_pages();
	}
?>
	<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php esc_attr( bloginfo( 'charset' ) ); ?>">
		<meta name="description" content="<?php esc_attr( bloginfo( 'description' ) ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=no">
		<?php wp_head(); ?>
	</head>
<body <?php
	echo ( ! empty( $realty_house_page_meta_id ) ) ? ' id="' . esc_attr( $realty_house_page_meta_id ) . '"' : '';
	body_class();
?>
>
	<!-- Main Header -->
	<header id="main-header">
		<div class="inner-container container">
			<div class="logo-container col-xs-3">
				<?php
					if ( isset( $realty_house_opt['logo-image-normal'] ) && $realty_house_opt['logo-image-normal']['url'] != '' )
					{
						echo '<a href="' . esc_url( home_url( '/' ) ) . '"><img src="' . esc_attr( $realty_house_opt['logo-image-normal']['url'] ) . '" alt="' . esc_attr( wp_get_theme()->name ) . '"></a>';
					}
					else
					{
						echo '<a href="' . esc_url( home_url( '/' ) ) . '"><img src="' . REALTY_HOUSE_IMG_PATH . 'logo.png" alt="' . esc_attr( wp_get_theme()->name ) . '"></a>';
					}
				?>
			</div>
			<div class="menu-container col-xs-9">
				<?php
					if ( ! empty( $realty_house_opt['realty-house-submit-property'] ) )
					{
						if ( class_exists( 'realty_house_get_pages' ) )
						{
							$submit_property_page_info = $get_page_obj->page_template( '../templates/submit-property.php' );
							$submit_property_url       = $submit_property_page_info['url'];
						}
						else
						{
							$submit_property_url = '#';
						}
echo do_shortcode('[popuppress id="509"]');

						echo wp_kses_post( '<a href="' . esc_url( $submit_property_url ) . '" class="submit-property-btn"><i class="fa fa-home"></i><i class="fa fa-plus"></i><span class="kk">' . esc_html__( 'Submit Property', 'realty-house' ) . '</span></a>' );
					}
				?>
				<div class="t-sec clearfix">
					<?php
						if ( ! empty( $realty_house_opt['opt-web-site-phone'] ) )
						{
							?>
							<div class="contact-info">
								<i class="fa fa-phone"></i>
								<div class="title"><?php esc_html_e( 'Call Us:', 'realty-house' ) ?></div>
								<div class="value">
									<?php echo esc_html( $realty_house_opt['opt-web-site-phone'] ); ?>
								</div>
							</div>
							<?php
						}
						if ( ! empty( $realty_house_opt['opt-web-site-email'] ) )
						{
							?>
							<div class="contact-info">
								<i class="fa fa-envelope"></i>
								<div class="title"><?php esc_html_e( 'Mail Us:', 'realty-house' ) ?></div>
								<div class="value">
									<?php echo esc_html( $realty_house_opt['opt-web-site-email'] ); ?>
								</div>
							</div>
							<?php
						}
						if ( ! empty( $realty_house_opt['opt-social-twitter'] ) || ! empty( $realty_house_opt['opt-social-facebook'] ) || ! empty( $realty_house_opt['opt-social-gplus'] ) )
						{
							?>
							<div class="contact-info">
								<i class="fa fa-share-alt"></i>
								<div class="title"><?php esc_html_e( 'Follow Us:', 'realty-house' ) ?></div>
								<div class="value">
									<ul class="list-inline">
										<?php
											if ( ! empty( $realty_house_opt['opt-social-twitter'] ) ):
												echo '<li><a href="' . esc_url( $realty_house_opt['opt-social-twitter'] ) . '">' . esc_html__( 'twitter', 'realty-house' ) . '</a></li>';
											endif;
											if ( ! empty( $realty_house_opt['opt-social-facebook'] ) ):
												echo '<li><a href="' . esc_url( $realty_house_opt['opt-social-facebook'] ) . '">' . esc_html__( 'facebook', 'realty-house' ) . '</a></li>';
											endif;
											if ( ! empty( $realty_house_opt['opt-social-gplus'] ) ):
												echo '<li><a href="' . esc_url( $realty_house_opt['opt-social-gplus'] ) . '">' . esc_html__( 'google plus', 'realty-house' ) . '</a></li>';
											endif;
										?>
									</ul>
								</div>
							</div>
							<?php
						}
						if ( is_plugin_active( 'realty-house/realty-house.php' ) && ! empty( $realty_house_opt['realty-house-currency-switcher'] ) )
						{
							echo '<div class="contact-info">' . do_shortcode( '[realty-house-currency-switcher]' ) . '</div>';
						}
					?>
				</div>
				<div class="b-sec clearfix">
					<!-- Main Menu -->
					<?php
						$realty_house_menu_arg = array (
							'theme_location' => 'primary',
							'container'      => 'nav',
							'container_id'   => 'main-menu',
							'menu_class'     => 'main-menu list-inline',
						);
						wp_nav_menu( $realty_house_menu_arg );
					?>
					<!-- END of Main Menu -->

					<div id="main-menu-handle"><i class="fa fa-bars"></i></div><!-- Mobile Menu handle -->
				</div>
			</div>
			<div id="user-profile-menu">
				<ul class="list-inline">
					<?php
						if ( class_exists( 'realty_house_get_pages' ) )
						{
							$bookmark_page_info = $get_page_obj->page_template( '../templates/property-bookmark.php' );
							$bookmark_page_url  = $bookmark_page_info['url'];
						}
						else
						{
							$bookmark_page_url = '#';
						}
						echo '
							<li>
								<a href="' . esc_attr( $bookmark_page_url ) . '"><i class="fa fa-star"></i>' . esc_html__( "Bookmark Properties", 'realty-house' ) . '</a>
							</li>
						';
						
						if ( ! is_user_logged_in() )
						{
							?>
							<li>
								<a id="login-form-url" href="#login-form">
									<i class="fa fa-sign-in"></i><?php esc_html_e( 'Login', 'realty-house' ) ?>
								</a>
							</li>
							<li>
								<a id="login-register-url" href="#register-form">
									<i class="fa fa-user"></i><?php esc_html_e( 'Register', 'realty-house' ) ?>
								</a>
							</li>
							<?php
						}
						else
						{
							if ( class_exists( 'realty_house_get_pages' ) )
							{
								$user_panel_page_info     = $get_page_obj->page_template( '../templates/user-panel.php' );
								$user_panel_page_info_url = $user_panel_page_info['url'];
							}
							else
							{
								$user_panel_page_info_url = '#';
							}
							echo '
								<li>
									<a href="' . esc_attr( $user_panel_page_info_url ) . '?view=saved-search"><i class="fa fa-save"></i>' . esc_html__( "Saved Search", 'realty-house' ) . '</a>
								</li>
								<li>
						<a href="' . home_url('my-account') . '"><i class="fa fa-user"></i>' . esc_html__( "My Profile", 'realty-house' ) . '</a>
								</li>
								<li>
									<a id="logout_button" href="' . esc_url( wp_logout_url( home_url() ) ) . '"><i class="fa fa-sign-out"></i>' . esc_html__( "Logout", 'realty-house' ) . '</a>
								</li>
							';
						}
					?>
				</ul>
			</div>
		</div>
		<div id="mobile-menu-container"></div>
	</header>
	<!-- End of Main Header -->


<?php
	$default_home_page = $static_home_page = false;
	
	if ( is_front_page() && is_home() )
	{
		$default_home_page = true;
	}
	elseif ( is_front_page() )
	{
		$static_home_page = true;
	}
	
	if ( $static_home_page !== true && ! is_page_template( 'templates/wide-no-bread.php' ) && ! is_page_template( '../templates/property-listing-map.php' ) && ! ( is_post_type_archive( 'property' ) && ( ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '4' ) ) && ! ( is_page_template( '../templates/search-property.php' ) && ( ! empty( $realty_house_opt['realty-house-property-search-template'] ) && $realty_house_opt['realty-house-property-search-template'] === '4' ) ) )
	{
		
		if ( is_post_type_archive( 'property' ) )
		{
			$map_bread = ! empty( $realty_house_opt['realty-house-property-archive-map'] ) ? true : false;
			
		}
		elseif ( is_page_template( '../templates/search-property.php' ) )
		{
			$map_bread = ! empty( $realty_house_opt['realty-house-property-search-map'] ) ? true : false;
		}
		else
		{
			$map_bread = ( get_post_meta( get_the_id(), 'realty_house_page_map_crumb', true ) ? get_post_meta( get_the_id(), 'realty_house_page_map_crumb', true ) : '' );
		}
		if ( is_404() )
		{
			$map_bread = false;
		}
		?>
		<!-- Breadcrumb -->
		<section id="breadcrumb"
			<?php
				if ( ! empty( $map_bread ) )
				{
					echo 'class="map"';
				}
				else
				{
					$bg_img_info = realty_house_tm_breadcrumb_bg( get_the_id() );
					if ( $bg_img_info['has_image'] === false )
					{
						echo 'class="no-bg-img"';
					}
					echo 'data-bg-img="' . esc_attr( $bg_img_info['img_src'] ) . '"';
				}
			?>
		>
			<?php
				if ( ! empty( $map_bread ) )
				{
					if ( is_page_template( 'templates/contact.php' ) )
					{
						echo '<div id="contact-map"></div>';
					}
					else
					{
						echo '<div id="map"></div>';
					}
				}
			?>

			<div class="inner-container container">
				<?php
					if ( ! is_home() || ! is_front_page() )
					{
						?>
						<div class="breadcrumb">
							<?php
								if ( function_exists( 'realty_house_tm_breadcrumbs' ) )
								{
									realty_house_tm_breadcrumbs();
								}
							?>
						</div>
						<?php
					}
				?>
			</div>
		</section>
		<!-- End of Breadcrumb -->
		<?php
	}