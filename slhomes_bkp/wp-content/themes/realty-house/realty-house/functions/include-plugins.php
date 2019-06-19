<?php
	require_once REALTY_HOUSE_THEMEROOT . '/includes/class-tgm-plugin-activation.php';
	add_action( 'tgmpa_register', 'realty_house_register_required_plugins' );
	
	function realty_house_register_required_plugins()
	{
		$plugins = array (
			array (
				'name'               => 'Realty House Plugin',
				'slug'               => 'realty-house',
				'source'             => REALTY_HOUSE_PLUGIN_PATH . 'realty-house-1.5.3.zip',
				'required'           => true,
				'version'            => '1.5.3',
				'force_activation'   => false,
				'force_deactivation' => false
			),
			array (
				'name'               => 'Contact Form 7',
				'slug'               => 'contact-form-7',
				'required'           => true,
				'version'            => '1.0.0',
				'force_activation'   => false,
				'force_deactivation' => false
			)
		);
		
		$config = array (
			'default_path' => '',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
			'strings'      => array (
				'page_title'                      => esc_html__( 'Install Required Plugins', 'realty-house' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'realty-house' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'realty-house' ),
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'realty-house' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'realty-house' ),
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'realty-house' ),
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'realty-house' ),
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'realty-house' ),
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'realty-house' ),
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'realty-house' ),
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'realty-house' ),
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'realty-house' ),
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'realty-house' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'realty-house' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'realty-house' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'realty-house' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'realty-house' ),
				'nag_type'                        => 'updated'
			)
		);
		tgmpa( $plugins, $config );
	}