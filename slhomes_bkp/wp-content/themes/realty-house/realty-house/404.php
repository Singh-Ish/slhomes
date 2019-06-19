<?php
	/**
	 *    404.php
	 */
	global $realty_house_opt;
	get_header();
	$has_image = empty( $realty_house_opt['realty-house-404-bg'] );
?>
	<section id="not-found" class="container">
		<div class="l-sec <?php echo (!empty($has_image) ? '' : 'col-xs-7') ?>">
			<h2><span><?php esc_html_e( 'Ooops!! No one seems to be home', 'realty-house' ) ?> :(</span></h2>
			<div class="subtitle"><?php esc_html_e( 'Not sure where everyone went, but this page appears to be abandoned.', 'realty-house' ) ?></div>
			<form class="search-form" role="search" action="<?php echo esc_attr( site_url( '/' ) ); ?>">
				<div class="title"><?php echo sprintf( wp_kses( __( '<a href="%s">Go Home</a> or Try a Search:', 'realty-house' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( home_url( '/' ) ) ) ?></div>
				<div class="search-inputs">
					<input type="search" class="search-field" placeholder="<?php esc_html_e( 'Search Here', 'realty-house' ); ?>" name="s">
					<input type="submit" class="search-submit btn btn-sm btn-default" value="<?php esc_html_e( 'Search', 'realty-house' ); ?>">
				</div>
			</form>
		</div>
		<?php
			if ( ! $has_image )
			{
				echo '
					<div class="r-sec col-xs-5">
						<div class="img-container" data-bg-img="' . esc_attr( $realty_house_opt['realty-house-404-bg']['url'] ) . '"></div>
					</div>
				';
			}
		?>
	</section>
<?php
	get_footer();