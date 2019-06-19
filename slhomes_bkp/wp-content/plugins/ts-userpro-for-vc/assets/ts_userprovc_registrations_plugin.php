<?php
    global $VISUAL_COMPOSER_USERPRO;

	add_action('admin_init',							'TS_USERPROFORVC_Init_Addon');
	function TS_USERPROFORVC_Init_Addon() {
		// Check for Visual Composer Plugin
		$required_vc 	= '4.3.9';
		if (defined('WPB_VC_VERSION')){
			if (version_compare($required_vc, WPB_VC_VERSION, '>')) {
			//if (TS_USERPROFORVC_VersionCompare(WPB_VC_VERSION, '4.3.9') >= 0) {
				add_action('admin_notices', 			'TS_USERPROFORVC_Admin_Notice_Version');
			}
		} else {
			add_action('admin_notices', 				'TS_USERPROFORVC_Admin_Notice_Composer');
		}
		// Check for UserPro Plugin
		if (!function_exists('userpro_version')) {
			add_action('admin_notices', 				'TS_USERPROFORVC_Admin_Notice_UserPro');
		}
	}
	function TS_USERPROFORVC_Admin_Notice_Version() {
		echo '<div class="update-nag"><p>The <strong>UserPro Shortcode Elements for Visual Composer</strong> add-on requires <strong>Visual Composer</strong> version 4.4.0 or greater.</p></div>';	
	}
	function TS_USERPROFORVC_Admin_Notice_Composer() {
		echo '<div class="error"><p>The <strong>UserPro Shortcode Elements for Visual Composer</strong> add-on requires the <strong>Visual Composer</strong> Plugin installed and activated.</p></div>';
	}
	function TS_USERPROFORVC_Admin_Notice_UserPro() {
		echo '<div class="error"><p>The <strong>UserPro Shortcode Elements for Visual Composer</strong> add-on requires the <strong>UserPro</strong> Plugin installed and activated.</p></div>';
	}
	function TS_USERPROFORVC_Admin_Notice_Network() {
		echo '<div class="error"><p>The <strong>UserPro Shortcode Elements for Visual Composer</strong> add-on can not be activated network-wide but only on individual sub-sites.</p></div>';
	}
	
	
	// Function to run if new Blog created on MutliSite
	// ------------------------------------------------	
	add_action('wpmu_new_blog', 						'TS_USERPROFORVC_On_New_BlogSite', 10, 6);
	function TS_USERPROFORVC_On_New_BlogSite($blog_id, $user_id, $domain, $path, $site_id, $meta) {
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite()) {
			if (is_plugin_active_for_network('ts-syntax-enlighterjs/ts-syntax-enlighterjs.php')) {
				$old_blog = $wpdb->blogid;
				switch_to_blog($blog_id);
				array('VISUAL_COMPOSER_ENLIGHTERJS', 	'TS_USERPROFORVC_Set_Plugin_Options');
				switch_to_blog($old_blog);
			}
		}
	}
	

	// Functions to Set / Delete Plugin Options
	// ----------------------------------------
	function TS_USERPROFORVC_Set_Plugin_Options() {

	}
	function TS_USERPROFORVC_Delete_Plugin_Options() {
		if (function_exists('TS_USERPROFORVC_DeleteOptionsPrefixed')) {
			TS_USERPROFORVC_DeleteOptionsPrefixed('ts_userprovc_extend_settings_');
		}
	}    
?>