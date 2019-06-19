<?php 
	global $newarf_user_registration_version;
	if(version_compare($newarf_user_registration_version, '1.3', '<'))
	{
		global $wpdb;
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_user_registration_forms ADD `reset_password_options` longtext DEFAULT NULL after `change_password_options` ");
	}
	
	update_option('arf_user_registration_version','1.3');
	
	global $newarf_user_registration_version;
	$newarf_user_registration_version = '1.3';
?>