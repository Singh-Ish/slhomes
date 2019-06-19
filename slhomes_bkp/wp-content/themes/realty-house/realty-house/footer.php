<?php
	/*
	  * footer.php
	  * The Footer Section of Theme
	  */
	global $realty_house_opt;
	if ( ! is_page_template( '../templates/property-listing-map.php' ) && ! ( is_post_type_archive( 'property' ) && ( ! empty( $realty_house_opt['realty-house-property-archive-template'] ) && $realty_house_opt['realty-house-property-archive-template'] === '4' ) ) && ! ( is_page_template( '../templates/search-property.php' ) && ( ! empty( $realty_house_opt['realty-house-property-search-template'] ) && $realty_house_opt['realty-house-property-search-template'] === '4' ) ) ):
		
		
		if ( ! empty( $realty_house_opt['realty-house-clients'] ) && ( count( $realty_house_opt['realty-house-clients'] ) !== 1 && ! empty( $realty_house_opt['realty-house-clients'][0]['image'] ) ) ):
			?>
			<div class="brands-main-container">
				<div class="brands-container container">
					<?php
						foreach ( $realty_house_opt['realty-house-clients'] as $client_item )
						{
							echo '
							<div class="brand-box">
								<a href="' . ( ! empty( $client_item['url'] ) ? esc_url( $client_item['url'] ) : '#' ) . '" title="' . esc_attr( $client_item['title'] ) . '">';
							if ( ! empty( $client_item['image'] ) )
							{
								echo '<img src="' . esc_url( $client_item['image'] ) . '" alt="' . esc_html( $client_item['title'] ) . '">';
							}
							echo '
								 </a>
							</div>';
						}
					?>
				</div>
			</div>
			<?php
		endif;
		
		
		// Twitter feeds
		if ( is_plugin_active( 'realty-house/realty-house.php' ) && ! empty( $realty_house_opt['opt-twitter-active'] ) )
	{
		echo do_shortcode( '[realty-house-twitter-feeds]' );
	}
		$is_footer_sidebar_active = is_active_sidebar( 'top-footer' );
		?>
		<footer id="footer" <?php echo empty( $is_footer_sidebar_active ) ? wp_kses_post( 'class="no-widget"' ) : '' ?>>
			<div class="inner-container container">
				<?php
					if ( ! empty( $is_footer_sidebar_active ) )
					{
						?>
						<div class="t-sec clearfix">
							<?php
								/**
								 * Load the "Top Footer" sidebar
								 */
								dynamic_sidebar( "top-footer" );
							?>
						</div>
						<?php
					}
				?>
				<div class="b-sec">
					<div class="copy-right">
						<?php
							/**
							 * Add the footer text which is set by user
							 */
							if ( ! empty( $realty_house_opt['opt-footer-text'] ) )
							{
								echo wp_kses_post( $realty_house_opt['opt-footer-text'] );
							}
						?>
					</div>
				</div>
			</div>
		</footer>
		<div id="login-form" class="login-form mfp-hide">
			<h3 class="rh-title"><span><?php echo esc_html__( "Login Form", 'realty-house' ) ?></span></h3>
			<form class="login-form-box" action="#">
				<div class="error-box"></div>
				<div class="row-fields">
					<input type="text" class="email" placeholder="<?php esc_attr_e( "Username", 'realty-house' ) ?>"/>
				</div>
				<div class="row-fields">
					<input class="pass" placeholder="<?php esc_attr_e( "Password", 'realty-house' ) ?>" type="password"/>
				</div>
<div class="row-fields">
					<a href="http://slhomes.in/forgot-password/">Forgot Password</a>
				</div>
				<div class="row-button-container">
					<input class="btn btn-default" value="<?php esc_attr_e( "Login", 'realty-house' ) ?>" type="submit"/>
				</div>
				<div class="loader"></div>
				<?php wp_nonce_field( 'ajax-login-nonce', 'security-login' ); ?>
			</form>
<h3 style="text-align:center; margin:0 auto;">OR</h3>
<?php echo do_shortcode('[userpro_social_connect]'); ?>
		</div>
		<div id="register-form" class="register-form mfp-hide">
			<h3 class="rh-title"><span><?php echo esc_html__( "Register Form", 'realty-house' ) ?></span></h3>
			<?php echo do_shortcode('[ARForms id=100]'); ?>
<h3 style="text-align:center; margin:0 auto;">OR</h3>
<?php echo do_shortcode('[userpro_social_connect width="460px"]'); ?>
		</div>
		<?php
	endif;
	wp_footer(); ?>
</body>
</html>