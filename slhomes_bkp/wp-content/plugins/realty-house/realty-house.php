<?php
	/**
	 * Plugin Name: Realty House Real Estate Plugin
	 * Plugin URI: http://themeforest.net/user/RavisTheme
	 * Description: Integrated Property Listing system for real estate with Realty House Theme.
	 * Version: 1.5.3
	 * Author: RavisTheme
	 * Author URI: http://themeforest.net/user/RavisTheme
	 * Text Domain: realty-house-pl
	 * Domain Path:  /languages
	 * This is not a free software and you can only use it with Realty House theme.
	 *
	 */
	
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}
	
	define( 'REALTY_HOUSE_PLG_VERSION', '1.0.0' );
	if ( ! defined( 'REALTY_HOUSE_THEMEROOT' ) )
	{
		define( 'REALTY_HOUSE_THEMEROOT', get_template_directory() );
	}
	
	if ( ! defined( 'REALTY_HOUSE_BASE_URL' ) )
	{
		if ( get_template_directory_uri() === get_stylesheet_directory_uri() )
		{
			define( 'REALTY_HOUSE_BASE_URL', get_stylesheet_directory_uri() );
		}
		else
		{
			define( 'REALTY_HOUSE_BASE_URL', get_template_directory_uri() );
		}
	}
	if ( ! defined( 'REALTY_HOUSE_PLG_BASE_URL' ) )
	{
		define( 'REALTY_HOUSE_PLG_BASE_URL', esc_url( plugin_dir_url( __FILE__ ) ) );
	}
	if ( ! defined( 'REALTY_HOUSE_PLG_IMG_PATH' ) )
	{
		define( 'REALTY_HOUSE_PLG_IMG_PATH', REALTY_HOUSE_PLG_BASE_URL . 'assets/img/' );
	}
	if ( ! defined( 'REALTY_HOUSE_PLG_JS_PATH' ) )
	{
		define( 'REALTY_HOUSE_PLG_JS_PATH', REALTY_HOUSE_PLG_BASE_URL . 'assets/js/' );
	}
	if ( ! defined( 'REALTY_HOUSE_PLG_CSS_PATH' ) )
	{
		define( 'REALTY_HOUSE_PLG_CSS_PATH', REALTY_HOUSE_PLG_BASE_URL . 'assets/css/' );
	}
	
	register_activation_hook( __FILE__, array ( 'Realty_house_plg_main', 'realty_house_plg_activate' ) );
	register_deactivation_hook( __FILE__, array ( 'Realty_house_plg_main', 'realty_house_plg_deactivate' ) );
	// register_uninstall_hook( __FILE__, array( 'Realty_house_plg_main', 'uninstall' ) );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Define Constants and Variables
	 * ------------------------------------------------------------------------------------------
	 */
	$realty_house_plg_base = __FILE__;
	define( 'REALTY_HOUSE_PLG_BASE', $realty_house_plg_base );
	define( 'REALTY_HOUSE_PLG_PATH', plugin_dir_path( $realty_house_plg_base ) );
	define( 'REALTY_HOUSE_PLG_URL', plugins_url( '/', $realty_house_plg_base ) );
	define( 'REALTY_HOUSE_PLG_MAIN_FILE', $realty_house_plg_base );
	define( 'REALTY_HOUSE_PLG_FIELDS', REALTY_HOUSE_PLG_PATH . 'fields/' );
	define( 'REALTY_HOUSE_PLG_WIDGETS', REALTY_HOUSE_PLG_PATH . 'widgets/' );
	define( 'REALTY_HOUSE_PLG_SHORTCODES', REALTY_HOUSE_PLG_PATH . 'shortcodes/' );
	define( 'REALTY_HOUSE_PLG_INCLUDES', REALTY_HOUSE_PLG_PATH . 'inc/' );
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Include Meta box classes
	 * ------------------------------------------------------------------------------------------
	 */
	
	require_once( REALTY_HOUSE_PLG_FIELDS . 'services.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'agent.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'agent-rate.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'testimonials.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'page-id-class.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'property-info.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'property-agents.php' );
	require_once( REALTY_HOUSE_PLG_FIELDS . 'property-offers.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Add shortcode class
	 * ------------------------------------------------------------------------------------------
	 */
	
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'shortcode.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add DataBase Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'db.class.php' );
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add Page Templater Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'templater.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add Get information Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'get_info.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add Get Properties Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'get_property.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Get Pages URL
	 * ------------------------------------------------------------------------------------------
	 */
	include( REALTY_HOUSE_PLG_INCLUDES . 'get_pages.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Update user Class
	 * ------------------------------------------------------------------------------------------
	 */
	include( REALTY_HOUSE_PLG_INCLUDES . 'user.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add Posts Class ( Properties, Agents and etc )
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'insert_posts.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Add Statistics Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'stats.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Bookmark Properties Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'bookmark_property.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Currency Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'currency.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Saved Search Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'saved-search.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Price Offer Class
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'price-offer.class.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Agent Rate Updated
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_INCLUDES . 'rate-updater.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Main Class of Ravis Booking
	 * ------------------------------------------------------------------------------------------
	 */
	class Realty_house_plg_main
	{
		
		public function __construct()
		{
			add_action( 'init', array ( $this, 'realty_house_plg_load_plugin_text_domain' ) );
			add_action( 'init', array ( $this, 'realty_house_plg_register_custom_post_type' ) );
			add_action( 'admin_menu', array ( $this, 'realty_house_add_submenu_page' ), 0 );
			// Add Required CSS / JS files
			add_action( 'admin_enqueue_scripts', array ( $this, 'realty_house_plg_register_scripts' ), 10, 1 );
			
			// Add Required CSS / JS files - FrontEnd
			add_action( 'wp_enqueue_scripts', array ( $this, 'realty_house_plg_register_scripts_front' ), 10, 1 );
			
			// Add Button to TinyMCE Functions
			add_filter( 'mce_external_plugins', array ( $this, 'realty_house_plg_add_buttons' ) );
			add_filter( 'mce_buttons', array ( $this, 'realty_house_plg_register_buttons' ) );
			add_action( 'wp_ajax_ravis_tinymce', array ( $this, 'realty_house_plg_ajax_tinymce' ) );
			
			add_action( 'plugins_loaded', array ( 'PageTemplater', 'get_instance' ) );
			
			register_activation_hook( __FILE__, array ( $this, 'realty_house_add_agent_role' ) );
			register_deactivation_hook( __FILE__, array ( $this, 'realty_house_remove_agent_role' ) );
			add_action( 'admin_init', array ( $this, 'realty_house_add_users_capability' ) );
			
			add_filter( 'pre_get_posts', array ( $this, 'realty_house_agent_post_limitation' ) );
			add_filter( 'pre_get_posts', array ( $this, 'realty_house_agent_media_limitation' ) );
			add_filter( 'pre_get_posts', array ( $this, 'realty_house_agent_limitation' ) );
			add_filter( 'admin_body_class', array ( $this, 'realty_house_add_class_body' ) );
			
			$currency_obj = new realty_house_currency();
			add_action( 'automatic_currency_update', array ( $currency_obj, 'update_currency' ) );
			
			add_action( 'wp_ajax_nopriv_p_details_contact_agent', array ( $this, 'realty_house_p_contact_agent' ) );
			add_action( 'wp_ajax_p_details_contact_agent', array ( $this, 'realty_house_p_contact_agent' ) );
			
			
		}
		
		public static function realty_house_plg_activate()
		{
			self::realty_house_required_pages();
			if ( ! wp_next_scheduled( 'automatic_currency_update' ) )
			{
				wp_schedule_event( time(), 'daily', 'automatic_currency_update' );
			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Create Essential Pages
		 * ------------------------------------------------------------------------------------------
		 */
		public static function realty_house_required_pages()
		{
			global $post;
			
			$pages_list = array (
				array (
					'title'     => esc_html__( 'Submit Property', 'realty-house-plg' ),
					'content'   => '',
					'post_name' => 'submit-property',
					'template'  => '../templates/submit-property.php',
				),
				array (
					'title'     => esc_html__( 'Property Search', 'realty-house-plg' ),
					'content'   => '',
					'post_name' => 'search-property',
					'template'  => '../templates/search-property.php',
				),
				array (
					'title'     => esc_html__( 'User Panel', 'realty-house-plg' ),
					'content'   => '',
					'post_name' => 'user-panel',
					'template'  => '../templates/user-panel.php',
				),
			);
			
			foreach ( $pages_list as $new_page )
			{
				
				$page_check   = get_page_by_title( $new_page['title'] );
				$new_page_arr = array (
					'post_type'    => 'page',
					'post_title'   => $new_page['title'],
					'post_content' => $new_page['content'],
					'post_name'    => $new_page['post_name'],
					'post_status'  => 'publish',
					'post_author'  => 1,
				);
				
				if ( ! isset( $page_check->ID ) )
				{
					$new_page_id = wp_insert_post( $new_page_arr );
					if ( ! empty( $new_page['template'] ) )
					{
						update_post_meta( $new_page_id, '_wp_page_template', $new_page['template'] );
					}
				}
			}
		}
		
		public static function realty_house_plg_deactivate()
		{
			wp_clear_scheduled_hook( 'automatic_currency_update' );
		}
		
		/**
		 * Load plugin text domain
		 */
		public static function realty_house_plg_load_plugin_text_domain()
		{
			load_plugin_textdomain( 'realty-house-pl', false, 'realty-house/languages/' );
		}
		
		/**
		 * Register All required post types for Plugin
		 */
		public static function realty_house_plg_register_custom_post_type()
		{
			
			/**
			 * ------------------------------------------------------------------------------------------
			 * Testimonials post_type
			 * ------------------------------------------------------------------------------------------
			 */
			register_post_type( 'testimonials', array (
				'label'               => esc_html__( 'Testimonials', 'realty-house-pl' ),
				'description'         => esc_html__( 'Manage the testimonials which is given by users.', 'realty-house-pl' ),
				'exclude_from_search' => true,
				'public'              => true,
				'has_archive'         => true,
				'rewrite'             => array ( 'slug' => 'testimonials' ),
				'supports'            => array ( 'title', 'editor', 'thumbnail' ),
				'menu_icon'           => 'dashicons-format-status'
			) );
			/**
			 * ------------------------------------------------------------------------------------------
			 * Properties post_type
			 * ------------------------------------------------------------------------------------------
			 */
			register_post_type( 'property', array (
				'label'               => esc_html__( 'Properties', 'realty-house-pl' ),
				'description'         => esc_html__( 'Manage the property of website.', 'realty-house-pl' ),
				'public'              => true,
				'exclude_from_search' => true,
				'has_archive'         => true,
				'rewrite'             => array ( 'slug' => 'property' ),
				'supports'            => array ( 'title', 'editor', 'comments' ),
				'menu_icon'           => 'dashicons-admin-multisite',
				'map_meta_cap'        => true,
				'capabilities'        => array (
					'edit_post'              => "edit_property",
					'read_post'              => "read_property",
					'delete_post'            => "delete_property",
					'edit_posts'             => "edit_properties",
					'edit_others_posts'      => "edit_others_properties",
					'publish_posts'          => "publish_properties",
					'read_private_posts'     => "read_private_properties",
					'read'                   => "read",
					'delete_posts'           => "delete_properties",
					'delete_private_posts'   => "delete_private_properties",
					'delete_published_posts' => "delete_published_properties",
					'delete_others_posts'    => "delete_others_properties",
					'edit_private_posts'     => "edit_private_properties",
					'edit_published_posts'   => "edit_published_properties",
					'create_posts'           => "edit_properties",
				),
			) );
			
			$labels = array (
				'name'          => _x( 'Property Categories', 'Taxonomy plural name', 'realty-house-pl' ),
				'singular_name' => _x( 'Property Category', 'Taxonomy singular name', 'realty-house-pl' ),
			);
			
			$args = array (
				'labels'            => $labels,
				'public'            => true,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'query_var'         => true,
				'rewrite'           => array ( 'slug' => 'property-category' ),
			);
			
			register_taxonomy( 'property-category', array ( 'property' ), $args );
			/**
			 * ------------------------------------------------------------------------------------------
			 * Services post_type
			 * ------------------------------------------------------------------------------------------
			 */
			register_post_type( 'service', array (
				'label'               => esc_html__( 'Service', 'realty-house-pl' ),
				'description'         => esc_html__( 'Manage the services that your website provide.', 'realty-house-pl' ),
				'public'              => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'rewrite'             => array ( 'slug' => 'service' ),
				'supports'            => array ( 'title', 'editor' ),
				'menu_icon'           => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMzgwLjcyMXB4IiBoZWlnaHQ9IjM4MC43MjJweCIgdmlld0JveD0iMCAwIDM4MC43MjEgMzgwLjcyMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzgwLjcyMSAzODAuNzIyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PGc+PHBhdGggZD0iTTEyOS4xOTQsMTk1LjI0NmMwLjI1LTQuMDcyLDAuNS04LjE1LDEuMDM0LTEyLjI2OWMwLjk4Ny05Ljc2NiwzLjEwOC0xOS41NzIsNS4yNzUtMjkuMTExYzEuMzE5LTQuNzU4LDIuNzQ4LTkuNDU4LDQuMjQ3LTE0LjA1M2MxLjkxNy00LjQ4NSwzLjc5My04Ljk0Niw1LjY2NC0xMy4zMDNjNC4yMzUtOC41MDUsOC45Ny0xNi40NTIsMTQuNjIyLTIzLjEwNGMxLjM0Mi0xLjcxNCwyLjY3Mi0zLjM3NSwzLjk3NC01LjA0MmMxLjQ4Ny0xLjQ4NywyLjk3NS0yLjkyOCw0LjQwOS00LjM2OWMzLjAyNy0yLjczNiw1LjY1Mi01LjY5OSw4LjY3NC03Ljg3MmM2LjAyMy00LjQ2MSwxMS4zNTItOC43MDIsMTYuNzc3LTExLjMyOGM1LjIyOS0yLjkxNyw5LjcxMy01LjQ0OSwxMy43NTYtNi44NzhjNy45MjQtMy4xODksMTIuNDIxLTQuNzkyLDEyLjQyMS00Ljc5MnMtMy44MzUsMy4wNDQtMTAuNTM4LDcuOTgyYy0zLjQ2MywyLjI2LTcuMTM0LDUuODI3LTExLjUwMyw5LjQ4MWMtNC41NTUsMy40ODUtOC42NzksOC40ODEtMTMuNTgyLDEzLjI2OWMtMi40NTIsMi40NDYtNC4zNTYsNS41MTktNi42NzUsOC4zNDhjLTEuMTI3LDEuNDQxLTIuMjgzLDIuODkzLTMuNDc1LDQuMzY5Yy0wLjk2NCwxLjYyNy0xLjk2MywzLjI1OS0yLjk3NCw0LjkyNmMtNC4zMjgsNi40MTQtNy41NjMsMTMuOTQzLTEwLjkzNCwyMS41ODJjLTEuMzgzLDMuOTk3LTIuNzgyLDguMDUyLTQuMjEyLDEyLjE0N2MtMC45NzYsNC4yNTgtMi4wNTYsOC41NjItMy4yMzUsMTIuODVjLTEuNzcyLDguODEyLTMuNTA5LDE3LjgtNC4yNTksMjcuMDQyYy0wLjUyMiwzLjM1Mi0wLjc3Miw2LjczMy0xLjA4NiwxMC4xMzdjNzMuNDM2LDAuMDEyLDEzMC4yNzQsMC4wNTgsMjA5LjgwNSwwLjA1OGMwLjI2Ny0zLjQ4NiwwLjQxOC03LjAxOCwwLjQxOC0xMC41NjdjMC02Ni45ODEtNDguODExLTEyMi4xNDctMTExLjYyMS0xMjkuNDU1YzQuMDU1LTMuNzkzLDYuNjIzLTkuMTczLDYuNjIzLTE1LjE1N2MwLjAyMy0xMS41MDItOS4yOTUtMjAuODA5LTIwLjc3NC0yMC44MDljLTExLjQ5MSwwLTIwLjgyLDkuMzA3LTIwLjgyLDIwLjgwOWMwLDUuOTk2LDIuNTU2LDExLjM2Myw2LjYyMiwxNS4xNTdjLTYyLjgwNSw3LjMwOC0xMTEuNjAzLDYyLjQ3NC0xMTEuNjAzLDEyOS40NTVjMCwzLjUzOCwwLjEyNyw3LjAyMywwLjM4OSwxMC40OThDMTE0LjM2OSwxOTUuMjQ2LDEyMS44NTcsMTk1LjI0NiwxMjkuMTk0LDE5NS4yNDZ6Ii8+PHJlY3QgeD0iODMuMzI5IiB5PSIyMDIuOTk1IiB3aWR0aD0iMjk3LjM5MiIgaGVpZ2h0PSIyNi4yMzYiLz48cGF0aCBkPSJNMjU4LjYzMSwyNDcuODU1Yy0xNS4xMTUsNy4zNDMtNjcuODE4LDI2LjM1MS02Ny44MTgsMjYuMzUxbC02Mi4yOTktMy45MDNjMCwwLDM1LjYzNS05LjkxMSw0OS40NDktMTMuMDEzYzEzLjgzOC0zLjA5MSw3LjgxOS0xOC42OTQsMC4xNjgtMTguNjk0Yy03LjY1LDAtNzQuMzI0LDIuNzUzLTc0LjMyNCwyLjc1M0w1OS41MzQsMjU3LjI5bDExLjcxNyw3NC4xNjJjMCwwLDkuMTE1LTE1LjYwNCwxOC4yMDctMTUuNjA0YzkuMTI2LDAsODguMTc0LDIuMTAzLDk4LjkwNCwwYzEwLjczNi0yLjEyNyw3Ni4xMjYtNDYuODU5LDgzLjk1Ny01Mi4wNzZDMjgwLjExNSwyNTguNTc5LDI3My44MDUsMjQwLjUwMSwyNTguNjMxLDI0Ny44NTV6Ii8+PHBvbHlnb24gcG9pbnRzPSIwLDI2NS4yNjEgMCwzNjEuMzk0IDY3LjgzLDM0OC42OTQgNTAuODYxLDI1Ni4zMTMgIi8+PC9nPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48L3N2Zz4='
			) );
			/**
			 * ------------------------------------------------------------------------------------------
			 * Staff post_type
			 * ------------------------------------------------------------------------------------------
			 */
			register_post_type( 'agent', array (
				'label'               => esc_html__( 'Agents', 'realty-house-pl' ),
				'description'         => esc_html__( 'Manage the agents of website.', 'realty-house-pl' ),
				'public'              => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'rewrite'             => array ( 'slug' => 'agent' ),
				'supports'            => array ( 'title', 'editor', 'thumbnail', 'comments' ),
				'menu_icon'           => 'dashicons-businessman',
				'map_meta_cap'        => true,
				'capabilities'        => array (
					'edit_post'              => "edit_agent",
					'read_post'              => "read_agent",
					'delete_post'            => "delete_agent",
					'edit_posts'             => "edit_agents",
					'edit_others_posts'      => "edit_others_agents",
					'publish_posts'          => "publish_agents",
					'read_private_posts'     => "read_private_agents",
					'read'                   => "read",
					'delete_posts'           => "delete_agents",
					'delete_private_posts'   => "delete_private_agents",
					'delete_published_posts' => "delete_published_agents",
					'delete_others_posts'    => "delete_others_agents",
					'edit_private_posts'     => "edit_private_agents",
					'edit_published_posts'   => "edit_published_agents",
					'create_posts'           => "edit_agents",
				),
			) );
			
			/**
			 * ------------------------------------------------------------------------------------------
			 * FAQ post_type
			 * ------------------------------------------------------------------------------------------
			 */
			register_post_type( 'faq', array (
				'label'               => esc_html__( 'FAQ', 'realty-house-pl' ),
				'description'         => esc_html__( 'Manage the FAQ in the website.', 'realty-house-pl' ),
				'public'              => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'rewrite'             => array ( 'slug' => 'faq' ),
				'supports'            => array ( 'title', 'editor' ),
				'menu_icon'           => 'dashicons-clipboard'
			) );
		}
		
		public function realty_house_add_submenu_page()
		{
			add_submenu_page( 'edit.php?post_type=property', esc_html__( 'Price Offers', 'realty-house-pl' ), esc_html__( 'Price Offers', 'realty-house-pl' ), 'manage_options', 'price-offers', array (
				$this,
				'realty_house_submenu_views'
			) );
		}
		
		/*
		 * Adding button to MCE
		 */
		
		public function realty_house_submenu_views()
		{
			require_once( REALTY_HOUSE_PLG_INCLUDES . 'price-offers-view.php' );
		}
		
		/*
		 * Registering button to MCE
		 */
		
		public function realty_house_plg_ajax_tinymce()
		{
			if ( current_user_can( 'edit_posts' ) or current_user_can( 'edit_pages' ) )
			{
				include_once( REALTY_HOUSE_PLG_INCLUDES . 'ravisButtons.php' );
				die();
			}
			else
			{
				die( esc_html__( "You are not allowed to be here", 'realty-house-pl' ) );
			}
		}
		
		public function realty_house_plg_add_buttons( $plugin_array )
		{
			$plugin_array['ravisButtons'] = REALTY_HOUSE_PLG_JS_PATH . 'ravisButtons.min.js';
			
			return $plugin_array;
		}
		
		public function realty_house_plg_register_buttons( $buttons )
		{
			array_push( $buttons, 'ravisShortcodes' );
			
			return $buttons;
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Add Agent Role
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_add_agent_role()
		{
			add_role( 'realty_agent', esc_html__( 'Agent', 'realty-house-plg' ), array (
				'edit_agent'                  => true,
				'read_agent'                  => true,
				'edit_agents'                 => true,
				'edit_others_agents'          => true,
				'publish_agents'              => true,
				'read_private_agents'         => true,
				'read'                        => true,
				'edit_private_agents'         => true,
				'edit_published_agents'       => true,
				'edit_property'               => true,
				'read_property'               => true,
				'delete_property'             => true,
				'edit_properties'             => true,
				'edit_others_properties'      => true,
				'publish_properties'          => true,
				'read_private_properties'     => true,
				'delete_properties'           => true,
				'delete_private_properties'   => true,
				'delete_published_properties' => true,
				'delete_others_properties'    => true,
				'edit_private_properties'     => true,
				'edit_published_properties'   => true,
				'upload_files'                => true
			) );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Remove Agent Role
		 * ------------------------------------------------------------------------------------------
		 */
		
		public function realty_house_remove_agent_role()
		{
			remove_role( 'realty_agent' );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Add Capabilities of Users
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_add_users_capability()
		{
			// Agent Access
			$admin_role = get_role( 'administrator' );
			
			$admin_role->add_cap( 'edit_agent' );
			$admin_role->add_cap( 'read_agent' );
			$admin_role->add_cap( 'delete_agent' );
			$admin_role->add_cap( 'edit_agents' );
			$admin_role->add_cap( 'edit_others_agents' );
			$admin_role->add_cap( 'publish_agents' );
			$admin_role->add_cap( 'read_private_agents' );
			$admin_role->add_cap( 'delete_agents' );
			$admin_role->add_cap( 'delete_private_agents' );
			$admin_role->add_cap( 'delete_published_agents' );
			$admin_role->add_cap( 'delete_others_agents' );
			$admin_role->add_cap( 'edit_private_agents' );
			$admin_role->add_cap( 'edit_published_agents' );
			
			$admin_role->add_cap( 'edit_property' );
			$admin_role->add_cap( 'read_property' );
			$admin_role->add_cap( 'delete_property' );
			$admin_role->add_cap( 'edit_properties' );
			$admin_role->add_cap( 'edit_others_properties' );
			$admin_role->add_cap( 'publish_properties' );
			$admin_role->add_cap( 'read_private_properties' );
			$admin_role->add_cap( 'delete_properties' );
			$admin_role->add_cap( 'delete_private_properties' );
			$admin_role->add_cap( 'delete_published_properties' );
			$admin_role->add_cap( 'delete_others_properties' );
			$admin_role->add_cap( 'edit_private_properties' );
			$admin_role->add_cap( 'edit_published_properties' );
			
			unset( $admin_role );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Limit the access of user to other posts
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_agent_post_limitation( $query )
		{
			global $pagenow;
			
			if ( 'edit.php' != $pagenow || ! $query->is_admin )
			{
				return $query;
			}
			if ( ! current_user_can( 'edit_others_posts' ) && $query->query['post_type'] == 'property' )
			{
				global $user_ID;
				$query->set( 'author', $user_ID );
			}
			
			return $query;
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Limit the access of user to other media files
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_agent_media_limitation( $query )
		{
			global $current_user, $pagenow;
			
			$is_attachment_request = ( $query->get( 'post_type' ) == 'attachment' );
			
			if ( ! $is_attachment_request )
			{
				return;
			}
			
			if ( ! is_a( $current_user, 'WP_User' ) )
			{
				return;
			}
			
			if ( ! in_array( $pagenow, array ( 'upload.php', 'admin-ajax.php' ) ) )
			{
				return;
			}
			
			if ( ! current_user_can( 'delete_pages' ) )
			{
				$query->set( 'author', $current_user->ID );
			}
			
			return;
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Limit the user to edit his/her own agent post
		 * ------------------------------------------------------------------------------------------
		 */
		
		public function realty_house_agent_limitation( $query )
		{
			global $current_user;
			
			if ( ! is_a( $current_user, 'WP_User' ) )
			{
				return;
			}
			if ( is_admin() )
			{
				if ( in_array( 'realty_agent', $current_user->roles ) && ( array_key_exists( 'post_type', $query->query ) && $query->query['post_type'] == 'agent' ) )
				{
					$query->set( 'meta_key', 'staff_username' );
					$query->set( 'meta_value', $current_user->data->user_login );
				}
				else
				{
					return;
				}
			}
			
			return;
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 * Add class to body of admin section based on user
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_add_class_body( $classes )
		{
			global $current_user;
			
			if ( in_array( 'realty_agent', $current_user->roles ) )
			{
				$newClasses = $classes . ' realty_house_agent_role';
			}
			else
			{
				$newClasses = $classes;
			}
			
			return $newClasses;
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Include required plugin js
		 * ------------------------------------------------------------------------------------------
		 */
		
		public function realty_house_plg_register_scripts( $hook )
		{
			global $realty_house_opt, $post;
			//			echo '<pre>';
			//			var_dump( get_role( 'realty_agent' ) );
			//			echo '</pre>';
			if ( ! empty( $realty_house_opt['realty-house-property-status'] ) )
			{
				$rent_id = array_search( 'For Rent', $realty_house_opt['realty-house-property-status'] ) + 1;
			}
			wp_enqueue_style( 'custom-admin-style', REALTY_HOUSE_PLG_CSS_PATH . 'admin.css' );
			if ( $hook == 'post-new.php' || $hook == 'post.php' )
			{
				
				if ( 'property' === $post->post_type )
				{
					wp_enqueue_style( 'select-2-style', REALTY_HOUSE_PLG_CSS_PATH . 'select2.min.css' );
					wp_enqueue_script( "google-map", '//maps.googleapis.com/maps/api/js?libraries=places' . ( ! empty( $realty_house_opt['opt-map-api'] ) ? '&amp;key=' . esc_attr( $realty_house_opt['opt-map-api'] ) : '' ), array (), get_bloginfo( 'version' ), true );
					
					wp_enqueue_script( 'select-2', REALTY_HOUSE_PLG_JS_PATH . 'select2.min.js', array (), REALTY_HOUSE_PLG_VERSION, true );
					wp_enqueue_script( 'realty-house-plugin-property-js', REALTY_HOUSE_PLG_JS_PATH . 'property.min.js', array (), REALTY_HOUSE_PLG_VERSION, true );
					wp_localize_script( 'realty-house-plugin-property-js', 'realty_house_plg', array (
						'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
						'plg_base' => REALTY_HOUSE_PLG_BASE_URL,
						'rent_id'  => $rent_id
					) );
				}
			}
			
			wp_enqueue_style( 'jquery-ui-custom', REALTY_HOUSE_PLG_CSS_PATH . 'jquery-ui.min.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			
			wp_enqueue_script( 'realty-house-plugin-general-js', REALTY_HOUSE_PLG_JS_PATH . 'general.min.js', array (), REALTY_HOUSE_PLG_VERSION, true );
			wp_localize_script( 'realty-house-plugin-general-js', 'realty_house', array (
				'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
				'plg_base' => REALTY_HOUSE_PLG_BASE_URL
			) );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Include required js, css codes and files in frontent
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_plg_register_scripts_front()
		{
			wp_enqueue_script( 'realty-house-plugin-front-js', REALTY_HOUSE_PLG_JS_PATH . 'front.min.js', array ( 'jquery' ), REALTY_HOUSE_PLG_VERSION, true );
			wp_localize_script( 'realty-house-plugin-front-js', 'realty_house_front', array (
				'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
				'plg_base' => REALTY_HOUSE_PLG_BASE_URL
			) );
			
			if ( is_page_template( '../templates/submit-property.php' ) && is_user_logged_in() )
			{
				wp_enqueue_media();
				wp_enqueue_script( "list-box", REALTY_HOUSE_PLG_JS_PATH . 'listbox.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				wp_enqueue_script( "mcustom-scrollbar", REALTY_HOUSE_PLG_JS_PATH . 'jquery.mCustomScrollbar.concat.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				wp_enqueue_script( 'realty-house-plugin-add-property-js', REALTY_HOUSE_PLG_JS_PATH . 'add-property.min.js', array (
					'jquery',
					'jquery-ui-sortable'
				), REALTY_HOUSE_PLG_VERSION, true );
				
				wp_localize_script( 'realty-house-plugin-add-property-js', 'realty_house_add_property', array (
					'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
					'plg_base' => REALTY_HOUSE_PLG_BASE_URL
				) );
			}
			
			if ( is_page_template( '../templates/property-compare.php' ) )
			{
				
				wp_enqueue_script( "mcustom-scrollbar", REALTY_HOUSE_PLG_JS_PATH . 'jquery.mCustomScrollbar.concat.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
			}
			
			if ( is_page_template( '../templates/be-agent.php' ) )
			{
				wp_enqueue_script( 'realty-house-plugin-be-agent-js', REALTY_HOUSE_PLG_JS_PATH . 'be-agent.min.js', array ( 'jquery' ), REALTY_HOUSE_PLG_VERSION, true );
				
				wp_localize_script( 'realty-house-plugin-be-agent-js', 'realty_house_be_agent', array (
					'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
					'plg_base' => REALTY_HOUSE_PLG_BASE_URL
				) );
			}
			if ( defined( 'REALTY_HOUSE_SHORTCODE_WIZARD' ) )
			{
				wp_enqueue_style( 'realty-house-shortcode-wizard-style', REALTY_HOUSE_PLG_CSS_PATH . ( 'admin.css' ) );
				wp_enqueue_script( "realty-house-shortcode-tinyMCE-popup-js", site_url() . '/wp-includes/js/tinymce/tiny_mce_popup.js', array ( 'jquery' ), true );
				wp_enqueue_script( "realty-house-shortcode-wizard-js", REALTY_HOUSE_PLG_JS_PATH . 'shortcode-wizard.min.js', array ( 'jquery' ), true );
			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Send emails for agent - in property detail's page
		 * ------------------------------------------------------------------------------------------
		 */
		public function realty_house_p_contact_agent()
		{
			$name           = ! empty( $_POST['name'] ) ? filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) : '';
			$email          = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			$phone          = ! empty( $_POST['phone'] ) ? filter_var( $_POST['phone'], FILTER_SANITIZE_NUMBER_FLOAT ) : '';
			$message        = ! empty( $_POST['message'] ) ? filter_var( $_POST['message'], FILTER_SANITIZE_STRING ) : '';
			$financeInfo    = ! empty( $_POST['financeInfo'] ) ? $_POST['financeInfo'] : '';
			$emailReceivers = ! empty( $_POST['emailReceivers'] ) ? filter_var( $_POST['emailReceivers'], FILTER_SANITIZE_STRING ) : '';
			$email_sender   = get_option( 'admin_email' );
			$body           = '';
			
			if ( $name && $email && $phone && $message && $emailReceivers )
			{
				$emailReceiversArray = explode( ',', $emailReceivers );
				$subj                = $name . ' - ' . $phone . ' ' . esc_html__( 'sent an email from ', 'realty-house' ) . get_option( 'blogname' );
				$headers[]           = "MIME-Version: 1.0" . "\r\n";
				$headers[]           = "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers[]           = 'From: "' . get_option( 'blogname' ) . '" <' . $email_sender . '>' . "\r\n";
				$headers[]           = 'Reply-To: ' . $name . '" <' . $email . '>';
				
				! empty( $financeInfo ) ? $body = $message . '\r\n' . esc_html__( 'I want to have extra finance information about this property', 'realty-house' ) : $body = $message;
				
				foreach ( $emailReceiversArray as $receiver_email )
				{
					wp_mail( $receiver_email, $subj, $body, $headers );
				}
				$result['status']  = true;
				$result['message'] = esc_html__( "We checked your message and contact you as soon as possible.", 'realty-house' );
			}
			else
			{
				$result['status']  = false;
				$result['message'] = esc_html__( "Please fill the required fields correctly.", 'realty-house' );
			}
			echo json_encode( $result );
			die();
		}
		
		/*
			public static function uninstall() {
				if ( __FILE__ != WP_UNINSTALL_PLUGIN )
					return;
			}
		*/
	}
	
	$realty_house_plg_obj = new Realty_house_plg_main;
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  Realty House Widgets
	 * ------------------------------------------------------------------------------------------
	 */
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-new-properties.php' );
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-featured-properties.php' );
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-featured-agent.php' );
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-testimonials.php' );
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-search-property.php' );
	require_once( REALTY_HOUSE_PLG_WIDGETS . 'realty-house-ask-question.php' );