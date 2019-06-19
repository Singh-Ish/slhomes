<?php
/*
Plugin Name:    UserPro Shortcode Elements for Visual Composer
Plugin URI:     http://codecanyon.net/item/userpro-shortcode-elements-for-visual-composer/12098429
Description:    A plugin to add new elements to Visual Composer to easily manage and insert UserPro related shortcodes
Author:         Tekanewa Scripts
Author URI:     http://krautcoding.com/userprovc/
Version:        1.1.2
Text Domain:    ts_userpro_for_vc
Domain Path:	/locale
*/


// Do NOT Load Directly
// --------------------
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
if (!defined('ABSPATH')) exit;


// Define Global Variables
// -----------------------
if (!defined('USERPROFORVC')){
	define('USERPROFORVC', 					dirname(__FILE__));
}
if (!defined('USERPROFORVC_VERSION')){
	define('USERPROFORVC_VERSION', 			'1.1.2');
}
if (!defined('USERPROFORVC_NAME')){
	define('USERPROFORVC_NAME', 			'UserPro Shortcode Elements for Visual Composer');
}


// Ensure that Function for Network Activate is Ready
// --------------------------------------------------
if (!function_exists('is_plugin_active_for_network')) {
	require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}


// Functions that need to be available immediately
// -----------------------------------------------
if (!function_exists('TS_USERPROFORVC_GetResourceURL')){
	function TS_USERPROFORVC_GetResourceURL($relativePath){
		return plugins_url($relativePath, plugin_basename(__FILE__));
	}
}
if (!function_exists('TS_USERPROFORVC_WordPressCheckup')) {
	function TS_USERPROFORVC_WordPressCheckup($version = '3.8') {
		global $wp_version;		
		if (version_compare($wp_version, $version, '>=')) {
			return "true";
		} else {
			return "false";
		}
	}
}
if (!function_exists('TS_USERPROFORVC_IsEditPagePost')){
	function TS_USERPROFORVC_IsEditPagePost($new_edit = null){
		global $pagenow, $typenow;
		$frontend = TS_USERPROFORVC_CheckFrontEndEditor();
		if (function_exists('vc_is_inline')){
			$vc_is_inline = vc_is_inline();
			if ((vc_is_inline() == false) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
				return false;
			} else if ((vc_is_inline() == true) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
				return true;
			} else if (((vc_is_inline() == NULL) || (vc_is_inline() == '')) && (!is_admin())) {
				if ($frontend == true) {
					$vc_is_inline = true;
					return true;
				} else {
					$vc_is_inline = false;
					return false;
				}
			}
		} else {
			$vc_is_inline = false;
			if (!is_admin()) return false;
		}
		if (($frontend == true) && (!is_admin())) {
			return true;
		} else if ($new_edit == "edit") {
			return in_array($pagenow, array('post.php'));
		} else if ($new_edit == "new") {
			return in_array($pagenow, array('post-new.php'));
		} else if ($vc_is_inline == true) {
			return true;
		} else {
			return in_array($pagenow, array('post.php', 'post-new.php'));
		}
	}
}
if (!function_exists('TS_USERPROFORVC_CheckFrontEndEditor')){
	function TS_USERPROFORVC_CheckFrontEndEditor() {
		$url 		= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if ((strpos($url, "vc_editable=true") !== false) || (strpos($url, "vc_action=vc_inline") !== false)) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('TS_USERPROFORVC_GetPluginVersion')){
	function TS_USERPROFORVC_GetPluginVersion() {
		$plugin_data 		= get_plugin_data( __FILE__ );
		$plugin_version 	= $plugin_data['Version'];
		return $plugin_version;
	}
}
if (!function_exists('TS_USERPROFORVC_VersionCompare')){
	function TS_USERPROFORVC_VersionCompare($a, $b) {
		//Compare two sets of versions, where major/minor/etc. releases are separated by dots. 
		//Returns 0 if both are equal, 1 if A > B, and -1 if B < A. 
		$a = explode(".", TS_USERPROFORVC_CustomSTRrTrim($a, ".0")); //Split version into pieces and remove trailing .0 
		$b = explode(".", TS_USERPROFORVC_CustomSTRrTrim($b, ".0")); //Split version into pieces and remove trailing .0 
		//Iterate over each piece of A 
		foreach ($a as $depth => $aVal) {
			if (isset($b[$depth])) {
			//If B matches A to this depth, compare the values 
				if ($aVal > $b[$depth]) {
					return 1; //Return A > B
					//break;
				} else if ($aVal < $b[$depth]) {
					return -1; //Return B > A
					//break;
				}
			//An equal result is inconclusive at this point 
			} else  {
				//If B does not match A to this depth, then A comes after B in sort order 
				return 1; //so return A > B
				//break;
			} 
		} 
		//At this point, we know that to the depth that A and B extend to, they are equivalent. 
		//Either the loop ended because A is shorter than B, or both are equal. 
		return (count($a) < count($b)) ? -1 : 0; 
	}
}
if (!function_exists('TS_USERPROFORVC_CustomSTRrTrim')){
	function TS_USERPROFORVC_CustomSTRrTrim($message, $strip) {
		$lines = explode($strip, $message); 
		$last  = ''; 
		do { 
			$last = array_pop($lines); 
		} while (empty($last) && (count($lines)));
		return implode($strip, array_merge($lines, array($last))); 
	}
}


// Main Class for Visual Composer Extensions
// -----------------------------------------
if (!class_exists('VISUAL_COMPOSER_USERPRO')) {
	// Register / Remove Plugin Settings on Plugin Activation / Removal
	// ----------------------------------------------------------------
	require_once('assets/ts_userprovc_registrations_plugin.php');
	
	// WordPres Register Hooks
	// -----------------------
	register_activation_hook(__FILE__, 		array('VISUAL_COMPOSER_USERPRO', 	'TS_USERPROFORVC_On_Activation'));
	register_deactivation_hook(__FILE__, 	array('VISUAL_COMPOSER_USERPRO', 	'TS_USERPROFORVC_On_Deactivation'));
	register_uninstall_hook(__FILE__, 		array('VISUAL_COMPOSER_USERPRO', 	'TS_USERPROFORVC_On_Uninstall'));	
	
	// Create Plugin Class
	// -------------------
	class VISUAL_COMPOSER_USERPRO {
		// Functions for Plugin Activation / Deactivation / Uninstall
		// ----------------------------------------------------------
		public static function TS_USERPROFORVC_On_Activation($network_wide) {
			if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
			global $wpdb;
			if (!current_user_can('activate_plugins')) {
				return;
			}
			// Check if Plugin has been Activated Before
			if (!get_option('ts_userprovc_settings_envatoInfo')) {
				$memory_required						= 4 * 1024 * 1024;
			} else {
				$memory_required						= 2 * 1024 * 1024;
			}
			$memory_provided							= ini_get('memory_limit');
			$memory_provided 							= preg_replace("/[^0-9]/", "", $memory_provided) * 1024 * 1024;
			$memory_peakusage 							= memory_get_peak_usage(true);
			if (($memory_provided - $memory_peakusage) <= $memory_required) {
				$part1 									= __("Unfortunately, and to prevent a potential system crash, the plugin 'UserPro Shortcode Elements for Visual Composer' could not be activated. It seems your available PHP memory is already close to exhaustion and so there is not enough left for this plugin.", "ts_userpro_for_vc") . '<br/>';
				$part2 									= __('Available Memory:', 'ts_userpro_for_vc') . '' . ($memory_provided / 1024 / 1024) . 'MB / ' . __('Already Utilized Memory:', 'ts_userpro_for_vc') . '' . ($memory_peakusage / 1024 / 1024) . 'MB / ' . __('Required Memory:', 'ts_userpro_for_vc') . '' . ($memory_required / 1024 / 1024) . 'MB<br/>';
				$part3 									= __('Please contact our', 'ts_userpro_for_vc');
				error_log($part1 . ' ' . $part2, 0);
				trigger_error($part1 . ' ' . $part3 . ' <a href="http://support.tekanewascripts.info/">' . __('Plugin Support', 'ts_userpro_for_vc') . '</a> for assistance.', E_USER_ERROR);
			} else {				
				if (function_exists('is_multisite') && is_multisite()) {					
					if ($networkwide) {
						// Options for License Data
						add_site_option('ts_userprovc_settings_demo',									1);
						add_site_option('ts_userprovc_settings_updated', 				            	0);
						add_site_option('ts_userprovc_settings_created', 				            	0);
						add_site_option('ts_userprovc_settings_deleted', 				            	0);
						add_site_option('ts_userprovc_settings_license', 				            	'');
						add_site_option('ts_userprovc_settings_licenseUpdate',							0);
						add_site_option('ts_userprovc_settings_licenseInfo',							'');
						add_site_option('ts_userprovc_settings_licenseKeyed',							'emptydelimiterfix');
						add_site_option('ts_userprovc_settings_licenseValid',							0);
						// Options for Update Data
						add_site_option('ts_userprovc_settings_versionCurrent', 				    	'');
						add_site_option('ts_userprovc_settings_versionLatest', 				    		'');
						add_site_option('ts_userprovc_settings_updateAvailable', 				    	0);
						$old_blog 	= $wpdb->blogid;
						$blogids 	= $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
						foreach ($blogids as $blog_id) {
							switch_to_blog($blog_id);
							TS_USERPROFORVC_Set_Plugin_Options();
						}
						switch_to_blog($old_blog);
						return;
					}
				}
				if ((isset($networkwide)) && (!$networkwide)) {
					// Options for License Data
					add_option('ts_userprovc_settings_demo', 					            			1);
					add_option('ts_userprovc_settings_updated', 				            			0);
					add_option('ts_userprovc_settings_created', 				            			0);
					add_option('ts_userprovc_settings_deleted', 				            			0);
					add_option('ts_userprovc_settings_license', 				            			'');
					add_option('ts_userprovc_settings_licenseUpdate',									0);
					add_option('ts_userprovc_settings_licenseInfo',										'');
					add_option('ts_userprovc_settings_licenseKeyed',									'emptydelimiterfix');
					add_option('ts_userprovc_settings_licenseValid',									0);
					// Options for Update Data
					add_option('ts_userprovc_settings_versionCurrent', 				    				'');
					add_option('ts_userprovc_settings_versionLatest', 				    				'');
					add_option('ts_userprovc_settings_updateAvailable', 				    			0);
				}
				TS_USERPROFORVC_Set_Plugin_Options();
			}
		}
		public static function TS_USERPROFORVC_On_Deactivation($network_wide) {
			global $wpdb;
			if (!current_user_can( 'activate_plugins')) {
				return;
			}
			if (function_exists('is_multisite') && is_multisite()) {
				if ($networkwide) {
					$old_blog 	= $wpdb->blogid;
					$blogids 	= $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id);
					}
					switch_to_blog($old_blog);
					return;
				}
			}
		}
		public static function TS_USERPROFORVC_On_Uninstall($network_wide) {
			global $wpdb;
			if (!current_user_can( 'activate_plugins')) {
				return;
			}
			if ( __FILE__ != WP_UNINSTALL_PLUGIN) {
				return;
			}
			if (function_exists('is_multisite') && is_multisite()) {
				if ($networkwide) {
					$old_blog 	= $wpdb->blogid;
					$blogids 	= $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id);
						//array('VISUAL_COMPOSER_USERPRO', 	'TS_USERPROFORVC_Delete_Plugin_Options');
						TS_USERPROFORVC_Delete_Plugin_Options();
					}
					switch_to_blog($old_blog);
					return;
				}
			}
			//array('VISUAL_COMPOSER_USERPRO', 	'TS_USERPROFORVC_Delete_Plugin_Options');
			TS_USERPROFORVC_Delete_Plugin_Options();
		}

		
		public $TS_USERPROFORVC_VisualComposer_Active			= 'true';
		public $TS_USERPROFORVC_VisualComposer_Version			= '';
		public $TS_USERPROFORVC_PluginIsMultiSiteActive			= 'false';		
		public $TS_USERPROFORVC_VCFrontEditMode					= "false";
		public $TS_USERPROFORVC_VCLeanMapMode					= "false";
		public $TS_USERPROFORVC_VCCurrentVersion				= "";
		public $TS_USERPROFORVC_VCElementsData					= array();
		public $TS_USERPROFORVC_EditorLivePreview				= "true";
		public $TS_USERPROFORVC_ComposiumStandard				= "false";
		public $TS_USERPROFORVC_GoogleMapsPLUS					= "false";
		public $TS_USERPROFORVC_TablenatorVC					= "false";
		
		public $TS_USERPROFORVC_PluginSlug						= "";
		public $TS_USERPROFORVC_PluginPath						= "";
		public $TS_USERPROFORVC_PluginDir						= "";
		
		public $TS_USERPROFORVC_UserRoles						= array();
		
		function __construct() {
			$this->admin_js 									= plugin_dir_path( __FILE__ ) . 'admin/';
			$this->assets_js 									= plugin_dir_path( __FILE__ ) . 'js/';
			$this->assets_css 									= plugin_dir_path( __FILE__ ) . 'css/';
			$this->assets_dir 									= plugin_dir_path( __FILE__ ) . 'assets/';
			$this->images_dir 									= plugin_dir_path( __FILE__ ) . 'images/';
			$this->elements_dir 								= plugin_dir_path( __FILE__ ) . 'elements/';
			$this->parameters_dir 								= plugin_dir_path( __FILE__ ) . 'parameters/';
			
			$this->TS_USERPROFORVC_PluginSlug					= plugin_basename(__FILE__);
			$this->TS_USERPROFORVC_PluginPath					= plugin_dir_url(__FILE__);
			$this->TS_USERPROFORVC_PluginDir 					= plugin_dir_path( __FILE__ );
			$this->TS_USERPROFORVC_PluginActive					= get_option('active_plugins');
			
			require_once($this->assets_dir . 'ts_userprovc_registrations_arrays.php');
			
			// Check and Store VC Version
			// --------------------------
			if (defined('WPB_VC_VERSION')){
				$this->TS_USERPROFORVC_VisualComposer_Version	= WPB_VC_VERSION;
				$this->TS_USERPROFORVC_VisualComposer_Active	= "true";
				if ((TS_USERPROFORVC_VersionCompare(WPB_VC_VERSION, '4.9.0') >= 0) && (function_exists('vc_lean_map'))) {
					$this->TS_USERPROFORVC_VCLeanMapMode 		= "true";
				} else {
					$this->TS_USERPROFORVC_VCLeanMapMode 		= "false";
				}
			} else {
				$this->TS_USERPROFORVC_VCLeanMapMode 			= "false";
				$this->TS_USERPROFORVC_VisualComposer_Active	= "false";
			}
			
			// Check for Standalone Composium Plugin
			// -------------------------------------
			if ((in_array('ts-visual-composer-extend/ts-visual-composer-extend.php', apply_filters('active_plugins', $this->TS_USERPROFORVC_PluginActive))) || (class_exists('VISUAL_COMPOSER_EXTENSIONS'))) {
				$this->TS_USERPROFORVC_ComposiumStandard		= "true";
			} else {
				$this->TS_USERPROFORVC_ComposiumStandard		= "false";
			}
			
			// Check for Standalone Google Maps PLUS Plugin
			// --------------------------------------------
			if ((in_array('ts-googlemaps-for-vc/ts-googlemaps-for-vc.php', apply_filters('active_plugins', $this->TS_USERPROFORVC_PluginActive))) || (class_exists('GOOGLEMAPS_PLUS_VC'))) {
				$this->TS_USERPROFORVC_GoogleMapsPLUS			= "true";
			} else {
				$this->TS_USERPROFORVC_GoogleMapsPLUS			= "false";
			}
			
			// Check for Standalone Tablenator VC Plugin
			// -----------------------------------------
			if ((in_array('ts-advanced-tables-vc/ts-advanced-tables-vc.php', apply_filters('active_plugins', $this->TS_USERPROFORVC_PluginActive))) || (class_exists('TS_ADVANCED_TABLESVC'))) {
				$this->TS_USERPROFORVC_TablenatorVC				= "true";
			} else {
				$this->TS_USERPROFORVC_TablenatorVC				= "false";
			}

			// Other Routine Checks
			// --------------------
			if ($this->TS_USERPROFORVC_VisualComposer_Active == "true") {
				if ($this->TS_USERPROFORVC_PluginIsMultiSiteActive == "true") {
					$this->TS_USERPROFORVC_PluginLicense		= get_site_option('ts_userprovc_settings_license', '');
				} else {
					$this->TS_USERPROFORVC_PluginLicense		= get_option('ts_userprovc_settings_license', '');
				}
				if (($this->TS_USERPROFORVC_PluginLicense != '') && (in_array(base64_encode($this->TS_USERPROFORVC_PluginLicense), $this->TS_USERPROFORVC_Avoid_Duplications))) {
					$this->TS_USERPROFORVC_PluginUsage			= "false";
				} else {
					$this->TS_USERPROFORVC_PluginUsage			= "true";
				}
				if ($this->TS_USERPROFORVC_PluginUsage == "false") {
					if ($this->TS_USERPROFORVC_PluginIsMultiSiteActive == "true") {
						update_site_option('ts_userprovc_settings_demo', 			1);
						update_site_option('ts_userprovc_settings_licenseInfo', 	'');
						update_site_option('ts_userprovc_settings_licenseKeyed', 	'emptydelimiterfix');
						update_site_option('ts_userprovc_settings_licenseValid', 	0);
					} else {
						update_option('ts_userprovc_settings_demo', 				1);
						update_option('ts_userprovc_settings_licenseInfo', 			'');
						update_option('ts_userprovc_settings_licenseKeyed', 		'emptydelimiterfix');
						update_option('ts_userprovc_settings_licenseValid', 		0);
					}
				}			
				
				// Load and Initialize the Auto-Update Class
				// -----------------------------------------
				/*
				if (get_option('ts_userprovc_settings_allowAutoUpdate', 1) == 1) {
					if ($this->TS_USERPROFORVC_PluginUsage == "true") {
						if ($this->TS_USERPROFORVC_PluginIsMultiSiteActive == "true") {
							if ((get_site_option('ts_userprovc_settings_demo', 1) == 0) && (get_option('ts_userprovc_settings_extended', 0) == 0) && ((strpos(get_site_option('ts_userprovc_settings_licenseInfo', ''), get_site_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
								add_action('admin_init', 			array($this, 'TS_USERPROFORVC_ActivateAutoUpdate'));
							}
						} else {
							if ((get_option('ts_userprovc_settings_demo', 1) == 0) && (get_option('ts_userprovc_settings_extended', 0) == 0) && ((strpos(get_option('ts_userprovc_settings_licenseInfo', ''), get_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
								add_action('admin_init', 			array($this, 'TS_USERPROFORVC_ActivateAutoUpdate'));
							}
						}
					}
				}
				*/
				
				// Load Language / Translation Files
				// ---------------------------------
				/*
				if (get_option('ts_userprovc_settings_translationsDomain', 1) == 1) {
					add_action('init',								array($this, 	'TS_USERPROFORVC_LoadTextDomains'), 				9);
				}
				*/
				
				$plugin = plugin_basename( __FILE__ );
				add_filter("plugin_action_links_$plugin", 	array($this, 	"TS_USERPROFORVC_PluginAddSettingsLink"));
				if ($this->TS_USERPROFORVC_PluginIsMultiSiteActive == "true") {
					if (((get_site_option('ts_userprovc_settings_licenseValid', 0) == 1) && ((strpos(get_site_option('ts_userprovc_settings_licenseInfo', ''), get_site_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) || (get_option('ts_userprovc_settings_extended', 0) == 1)) {
						update_site_option('ts_userprovc_settings_demo', 	0);
					} else {
						update_site_option('ts_userprovc_settings_demo', 	1);
					}
				} else {
					if (((get_option('ts_userprovc_settings_licenseValid', 0) == 1) && ((strpos(get_option('ts_userprovc_settings_licenseInfo', ''), get_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) || (get_option('ts_userprovc_settings_extended', 0) == 1)) {
						update_option('ts_userprovc_settings_demo', 		0);
					} else {
						update_option('ts_userprovc_settings_demo', 		1);
					}
				}
				
				// Function to Register / Load External Files on Back-End
				// ------------------------------------------------------
				add_action('admin_enqueue_scripts', 		array($this, 	'TS_USERPROFORVC_Extensions_Admin_Files'),			999999999);
				add_action('admin_head', 					array($this, 	'TS_USERPROFORVC_Extensions_Admin_Head'), 			8888);
				
				// Function to Register / Load External Files on Front-End
				// -------------------------------------------------------
				add_action('wp_enqueue_scripts', 			array($this, 	'TS_USERPROFORVC_Extensions_Front_Main'), 			999999999);
				add_action('wp_head', 						array($this, 	'TS_USERPROFORVC_Extensions_Front_Head'), 			8888);
				add_action('wp_footer', 					array($this, 	'TS_USERPROFORVC_Extensions_Front_Footer'), 		8888);
				
				// Create Custom Admin Menu for Plugin
				// -----------------------------------
				//require_once($this->assets_dir . 'ts_userprovc_registrations_menu.php');			
				
				// Load Shortcode Definitions
				// --------------------------
				add_action('init', 							array($this, 	'TS_USERPROFORVC_RegisterAllShortcodes'), 			888888888);
				add_action('init', 							array($this, 	'TS_USERPROFORVC_AddParametersToComposer'), 		999999999);
			}
		}

		
		// Load Language Domain
		// --------------------
		function TS_USERPROFORVC_LoadTextDomains(){
			load_plugin_textdomain('ts_userpro_for_vc', false, dirname(plugin_basename( __FILE__ )) . '/locale');
		}
		

		// Load and Initialize the Auto-Update Class
		// -----------------------------------------
		function TS_USERPROFORVC_ActivateAutoUpdate() {
			global $pagenow;			
			if (($pagenow == "index.php") || ($pagenow == "plugins.php") || ($pagenow == "update-core.php")) {
				if ($this->TS_USERPROFORVC_PluginIsMultiSiteActive == "true") {
					if (is_admin() && (strlen(get_site_option('ts_userprovc_settings_license')) != 0) && (function_exists('get_plugin_data'))) {
						if ((get_site_option('ts_userprovc_settings_licenseValid', 0) == 1) && (get_option('ts_userprovc_settings_extended', 0) == 0) && ((strpos(get_site_option('ts_userprovc_settings_licenseInfo', ''), get_site_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
							if (!class_exists('TS_USERPROFORVC_AutoUpdate')) {
								require_once ('assets/ts_userprovc_registrations_autoupdate.php');
							}
							// Define Path and Base File for Plugin
							$ts_userprovc_plugin_slug 						= $this->TS_USERPROFORVC_PluginSlug;
							$ts_userprovc_plugin_path						= $this->TS_USERPROFORVC_PluginPath;
							// Get the current version
							$ts_userprovc_plugin_current_version	        = TS_USERPROFORVC_GetPluginVersion();
							// Define Path to Remote Update File
							$ts_userprovc_plugin_remote_path 		        = 'http://www.tekanewascripts.info/Updates/ts-update-vc-enlighterjs-wp.php';
							// Initialize Update Check
							$ts_userprovc_plugin_class 						= new TS_USERPROFORVC_AutoUpdate($ts_userprovc_plugin_current_version, $ts_userprovc_plugin_remote_path, $ts_userprovc_plugin_slug);
							// Retrieve Newest Plugin Version Number
							$ts_userprovc_plugin_latest_version				= $ts_userprovc_plugin_class->getRemote_version();
							// Save Current and Latest Version in WordPress Options
							update_site_option('ts_userprovc_settings_versionCurrent',		$ts_userprovc_plugin_current_version);
							update_site_option('ts_userprovc_settings_versionLatest',		$ts_userprovc_plugin_latest_version);						
						}
					}
				} else {
					if (is_admin() && (strlen(get_option('ts_userprovc_settings_license')) != 0) && (function_exists('get_plugin_data'))) {
						if ((get_option('ts_userprovc_settings_licenseValid', 0) == 1) && (get_option('ts_userprovc_settings_extended', 0) == 0) && ((strpos(get_option('ts_userprovc_settings_licenseInfo', ''), get_option('ts_userprovc_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
							if (!class_exists('TS_USERPROFORVC_AutoUpdate')) {
								require_once ('assets/ts_userprovc_registrations_autoupdate.php');
							}
							// Define Path and Base File for Plugin
							$ts_userprovc_plugin_slug 						= $this->TS_USERPROFORVC_PluginSlug;
							$ts_userprovc_plugin_path						= $this->TS_USERPROFORVC_PluginPath;
							// Get the current version
							$ts_userprovc_plugin_current_version	        = TS_USERPROFORVC_GetPluginVersion();
							// Define Path to Remote Update File
							$ts_userprovc_plugin_remote_path 		        = 'http://www.tekanewascripts.info/Updates/ts-update-vc-enlighterjs-wp.php';
							// Initialize Update Check
							$ts_userprovc_plugin_class 						= new TS_USERPROFORVC_AutoUpdate($ts_userprovc_plugin_current_version, $ts_userprovc_plugin_remote_path, $ts_userprovc_plugin_slug);
							// Retrieve Newest Plugin Version Number
							$ts_userprovc_plugin_latest_version				= $ts_userprovc_plugin_class->getRemote_version();
							// Save Current and Latest Version in WordPress Options
							update_option('ts_userprovc_settings_versionCurrent', 			$ts_userprovc_plugin_current_version);
							update_option('ts_userprovc_settings_versionLatest', 			$ts_userprovc_plugin_latest_version);
						}
					}
				}
			}
		}

		
		// Add additional "Settings" Link to Plugin Listing Page
		// -----------------------------------------------------
		function TS_USERPROFORVC_PluginAddSettingsLink($links) {
			if (current_user_can('manage_options')) {
				//$links[] = '<a href="admin.php?page=TS_USERPROFORVC" target="_parent">' . __( "Settings", "ts_visual_composer_extend" ) . '</a>';
			}
			$links[] = '<a href="http://krautcoding.com/userprovc/documentation/" target="_blank">' . __( "Documentation", "ts_visual_composer_extend" ) . '</a>';
			$links[] = '<a href="http://helpdesk.krautcoding.com/changelog-userpro-shortcode-elements-for-vc/" target="_blank">' . __( "Changelog", "ts_visual_composer_extend" ) . '</a>';
			return $links;
		}
		
		
		// Function to register Scripts and Stylesheets
		// --------------------------------------------
		function TS_USERPROFORVC_Extensions_Registrations() {
			require_once($this->assets_dir . 'ts_userprovc_registrations_files.php');
		}

		
		// Function to load External Files on Back-End when Editing
		// --------------------------------------------------------
		function TS_USERPROFORVC_Extensions_Admin_Files($hook_suffix) {
			global $pagenow, $typenow;
			$screen 		= get_current_screen();
			require_once($this->assets_dir . 'ts_userprovc_registrations_files.php');
			if (empty($typenow) && !empty($_GET['post'])) {
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
			}
			$url			= plugin_dir_url( __FILE__ );
			// Files to be loaded with Visual Composer
			if (TS_USERPROFORVC_IsEditPagePost()) {
				if (($this->TS_USERPROFORVC_ComposiumStandard == "false") && ($this->TS_USERPROFORVC_GoogleMapsPLUS == "false") && ($this->TS_USERPROFORVC_TablenatorVC == "false")) {					
					wp_enqueue_style('ts-extend-nouislider');
					wp_enqueue_script('ts-extend-nouislider');
					wp_enqueue_style('ts-userprovc-parameters');
					wp_enqueue_script('ts-userprovc-parameters');
				}
				wp_enqueue_style('ts-userprovc-composer');
			}
		}
		function TS_USERPROFORVC_Extensions_Admin_Head() {
			global $pagenow, $typenow;
			$screen 		= get_current_screen();
			if (empty($typenow) && !empty($_GET['post'])) {
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
			}
			$url			= plugin_dir_url( __FILE__ );
			if (strpos($screen->id, 'TS_USERPROFORVC') !== false) {

			}
		}

		
		// Function to load External Files on Front-End
		// --------------------------------------------
		function TS_USERPROFORVC_Extensions_Front_Main() {
			global $post;
			// Check For Standard Frontend Page
			if (!is_404() && !is_search() && !is_archive()) {
				$TS_USERPROFORVC_StandardFrontendPage		= "true";
			} else {
				$TS_USERPROFORVC_StandardFrontendPage		= "false";
			}
			// Load Scripts As Needed
			if (!empty($post)) {

			}
		}
		function TS_USERPROFORVC_Extensions_Front_Head() {
			global $post;
			// Check For Standard Frontend Page
			if (!is_404() && !is_search() && !is_archive()) {
				$TS_USERPROFORVC_StandardFrontendPage		= "true";
			} else {
				$TS_USERPROFORVC_StandardFrontendPage		= "false";
			}
			if (!empty($post)) {

			}
		}
		function TS_USERPROFORVC_Extensions_Front_Footer() {
			global $post;
			// Check For Standard Frontend Page
			if (!is_404() && !is_search() && !is_archive()) {
				$TS_USERPROFORVC_StandardFrontendPage		= "true";
			} else {
				$TS_USERPROFORVC_StandardFrontendPage		= "false";
			}
			if (!empty($post)) {

			}
		}
		
		
		// Load Composer Shortcodes + Elements + Add Custom Parameters
		// -----------------------------------------------------------
		function TS_USERPROFORVC_RegisterAllShortcodes() {
			if (function_exists('vc_is_inline')){
				if (vc_is_inline() == true) {
					$this->TS_USERPROFORVC_VCFrontEditMode 				= "true";
				} else {
					if ((vc_is_inline() == NULL) || (vc_is_inline() == '')) {
						if (TS_USERPROFORVC_CheckFrontEndEditor() == true) {
							$this->TS_USERPROFORVC_VCFrontEditMode 		= "true";
						} else {
							$this->TS_USERPROFORVC_VCFrontEditMode 		= "false";
						}	
					} else {
						$this->TS_USERPROFORVC_VCFrontEditMode 			= "false";
					}
				}
			} else {
				$this->TS_USERPROFORVC_VCFrontEditMode 					= "false";
			}
			require_once($this->assets_dir . 'ts_userprovc_registrations_elements.php');
		}
		function TS_USERPROFORVC_AddParametersToComposer() {
			if (($this->TS_USERPROFORVC_ComposiumStandard == "false") && ($this->TS_USERPROFORVC_GoogleMapsPLUS == "false") && ($this->TS_USERPROFORVC_TablenatorVC == "false")) {
				require_once($this->parameters_dir . 'ts_vcsc_parameter_switch.php');
				require_once($this->parameters_dir . 'ts_vcsc_parameter_nouislider.php');
				require_once($this->parameters_dir . 'ts_vcsc_parameter_messenger.php');
				require_once($this->parameters_dir . 'ts_vcsc_parameter_separator.php');
			}
			require_once($this->parameters_dir . 'ts_vcsc_parameter_userprovcusers.php');
			require_once($this->parameters_dir . 'ts_vcsc_parameter_userprovctemplate.php');
			require_once($this->parameters_dir . 'ts_vcsc_parameter_userprovcwidth.php');
		}
	}
}
if (class_exists('VISUAL_COMPOSER_USERPRO')) {
	$VISUAL_COMPOSER_USERPRO = new VISUAL_COMPOSER_USERPRO;
}


// Load Helper Functions
// ---------------------
require_once('assets/ts_userprovc_registrations_functions.php');
?>