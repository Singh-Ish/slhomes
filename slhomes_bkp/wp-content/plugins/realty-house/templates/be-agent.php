<?php
	/**
	 *  be-agent.php
	 *  Ba an Agent Page
	 *  Template Name: Be an Agent
	 */
	global $post, $realty_house_opt;
	get_header();

?>
	<section class="page-content container">
		<section class="main-container <?php echo is_active_sidebar( 'be-agent-sidebar' ) ? esc_attr( 'col-sm-8 col-md-9' ) : '' ?> ">
			<h3 class="rh-title"><span><?php esc_html_e( 'Registration Information', 'realty-house-pl' ) ?></span></h3>
			<div id="success-agent-registration">
				<h2><span><?php esc_html_e( 'Registration is Complete :)', 'realty-house-pl' ) ?></span></h2>
				<div class="subtitle"><?php esc_html_e( 'After confirmation of administration of website, you can submit your properties.', 'realty-house-pl' ) ?></div>
			</div>
			<form id="be-agent-form" action="#" name="be-agent-form" class="main-contact-form">
				<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'be_agent_security_code' ) ?>">
				<div class="field-row">
					<label for="fname"><?php esc_html_e( 'First Name', 'realty-house-pl' ) ?><span>*</span> :</label>
					<input type="text" id="fname" name="fname" data-required="required">
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="field-row">
					<label for="lname"><?php esc_html_e( 'Last Name', 'realty-house-pl' ) ?><span>*</span> :</label>
					<input type="text" id="lname" name="lname" data-required="required">
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="field-row">
					<label for="user-name"><?php esc_html_e( 'Username', 'realty-house-pl' ) ?><span>*</span> :</label>
					<input type="text" id="user-name" name="user_name" data-required="required">
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="field-row">
					<label for="email"><?php esc_html_e( 'Email', 'realty-house-pl' ) ?><span>*</span> :</label>
					<input type="email" id="email" name="email" data-required="required">
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="field-row">
					<label for="confirm_email"><?php esc_html_e( 'Confirm Email', 'realty-house-pl' ) ?><span>*</span> :</label>
					<input type="email" id="confirm_email" name="confirm_email" data-required="required">
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
					<div class="alert alert-danger mis-matched-error"><?php esc_html_e( 'Email and its confirmation fields are not the same', 'realty-house-pl' ) ?></div>
				</div>
				<div class="field-row">
					<label for="phone"><?php esc_html_e( 'Phone', 'realty-house-pl' ) ?> :</label>
					<input type="text" id="phone" name="phone">
				</div>
				<div class="field-row">
					<label for="mobile"><?php esc_html_e( 'Mobile', 'realty-house-pl' ) ?> :</label>
					<input type="text" id="mobile" name="mobile">
				</div>
				<div class="field-row">
					<label for="address"><?php esc_html_e( 'Address', 'realty-house-pl' ) ?><span>*</span> :</label>
					<textarea id="address" name="address" data-required="required"></textarea>
					<div class="alert alert-danger"><?php esc_html_e( 'Please fill this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div id="form-message-box"></div>
				<div class="field-row btn-container">
					<input type="submit" class="btn btn-default btn-sm" value="<?php esc_html_e( 'Send', 'realty-house-pl' ) ?>">
				</div>
			</form>
		</section>
		<?php if ( is_active_sidebar( 'be-agent-sidebar' ) ): ?>
			<!-- Sidebar Section -->
			<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'be-agent-sidebar' ); ?>
			</aside>
			<!-- Sidebar Section -->
		<?php endif; ?>
	</section>

<?php
	get_footer();