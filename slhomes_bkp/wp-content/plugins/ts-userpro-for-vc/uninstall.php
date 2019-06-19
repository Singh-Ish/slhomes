<?php
	if (!defined('WP_UNINSTALL_PLUGIN')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}
	global $wpdb;
	if (!function_exists('TS_USERPROFORVC_DeleteOptionsPrefixed')){
		function TS_USERPROFORVC_DeleteOptionsPrefixed($prefix) {
			global $wpdb;
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
		}
	}
	if (!function_exists('TS_USERPROFORVC_Delete_Plugin_Settings')){
		function TS_USERPROFORVC_Delete_Plugin_Settings() {
			if (get_option('ts_userprovc_settings_retain') != 2) {
				TS_USERPROFORVC_DeleteOptionsPrefixed('ts_userprovc_settings_');
			}
		}
	}
	
	if (!is_user_logged_in()) {
		wp_die('You must be logged in to run this script.');
	}

	if (!current_user_can('install_plugins')) {
		wp_die('You do not have permission to run this script.');
	}

	
	if (function_exists('is_multisite') && is_multisite()) {
		if ($networkwide) {
			$old_blog 	= $wpdb->blogid;
			$blogids 	= $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				delete_site_option('ts_userprovc_settings_demo');
				delete_site_option('ts_userprovc_settings_updated');
				delete_site_option('ts_userprovc_settings_created');
				delete_site_option('ts_userprovc_settings_deleted');
				delete_site_option('ts_userprovc_settings_license');
				delete_site_option('ts_userprovc_settings_licenseUpdate');
				delete_site_option('ts_userprovc_settings_licenseInfo');
				delete_site_option('ts_userprovc_settings_licenseKeyed');
				delete_site_option('ts_userprovc_settings_licenseValid');
				delete_site_option('ts_userprovc_settings_versionCurrent');
				delete_site_option('ts_userprovc_settings_versionLatest');
				delete_site_option('ts_userprovc_settings_updateAvailable');
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				TS_USERPROFORVC_Delete_Plugin_Settings();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	TS_USERPROFORVC_Delete_Plugin_Settings();
?>
