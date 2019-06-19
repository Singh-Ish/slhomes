<?php
    global $VISUAL_COMPOSER_USERPRO;
    add_action('admin_menu', 'TS_USERPROFORVC_SyncMenu');
    
    // Create Custom Admin Menu for Plugin
    function TS_USERPROFORVC_SyncMenu() {
        global $VISUAL_COMPOSER_USERPRO;
        global $ts_userprovc_main_page;
        global $ts_userprovc_settings_page;
        global $ts_userprovc_license_page;       
        
        if (get_option('ts_userprovc_settings_mainmenu', 1) == 1) {
            $ts_userprovc_main_page =           add_menu_page(      "VC UserPro SC",           "VC UserPro SC",    	    'manage_options', 	    'TS_USERPROFORVC', 	    'TS_USERPROFORVC_PageExtend', 	    TS_USERPROFORVC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'),     '79.234567890');
        } else {
            $ts_userprovc_main_page =           add_options_page(   "VC UserPro SC",           "VC UserPro SC",    	    'manage_options', 	    'TS_USERPROFORVC', 	    'TS_USERPROFORVC_PageExtend');
        }
        if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
            //$ts_userprovc_license_page =		add_submenu_page( 	'TS_USERPROFORVC', 	        "License Key",              "License Key",      	'manage_options', 	    'TS_USERPROFORVC_License', 		'TS_USERPROFORVC_PageLicense');
        }
        // Define Position of Plugin Menu
        if (get_option('ts_userprovc_settings_mainmenu', 1) == 1) {
            $VISUAL_COMPOSER_USERPRO->settingsLink = "admin.php?page=TS_USERPROFORVC";
        } else {
            $VISUAL_COMPOSER_USERPRO->settingsLink = "options-general.php?page=TS_USERPROFORVC";
        }	
    }

    function TS_USERPROFORVC_PageExtend() {
        global $VISUAL_COMPOSER_USERPRO;        
        if (current_user_can('manage_options')) {
            echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame" style="direction: ltr;">' . "\n";
                include($VISUAL_COMPOSER_USERPRO->assets_dir . 'ts_userprovc_settings_main.php');
            echo '</div>' . "\n";
        } else {
            wp_die('You do not have sufficient permissions to access this page.');
        }
    }
    function TS_USERPROFORVC_PageLicense() {
        if (current_user_can('manage_options')) {		
            echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame" style="direction: ltr; margin-top: 25px;">' . "\n";
                include($VISUAL_COMPOSER_USERPRO->assets_dir . 'ts_userprovc_license.php');
            echo '</div>' . "\n";	
        } else {
            wp_die('You do not have sufficient permissions to access this page.');
        }
    }
?>