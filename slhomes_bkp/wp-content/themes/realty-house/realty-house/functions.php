<?php
	/**
	 *    function.php
	 *    Function of the theme are listed in this file.
	 * ------------------------------------------------------------------------------------------
	 * 1 - Define Constant
	 * ------------------------------------------------------------------------------------------
	 */
	
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
	if ( ! defined( 'REALTY_HOUSE_FRAMEWORK' ) )
	{
		define( 'REALTY_HOUSE_FRAMEWORK', REALTY_HOUSE_THEMEROOT . '/admin/' );
	}
	if ( ! defined( 'REALTY_HOUSE_WIDGET_PATH' ) )
	{
		define( 'REALTY_HOUSE_WIDGET_PATH', REALTY_HOUSE_THEMEROOT . '/widgets/' );
	}
	if ( ! defined( 'REALTY_HOUSE_FUNCTIONS_PATH' ) )
	{
		define( 'REALTY_HOUSE_FUNCTIONS_PATH', REALTY_HOUSE_THEMEROOT . '/functions/' );
	}
	if ( ! defined( 'REALTY_HOUSE_PLUGIN_PATH' ) )
	{
		define( 'REALTY_HOUSE_PLUGIN_PATH', REALTY_HOUSE_THEMEROOT . '/includes/plugins/' );
	}
	if ( ! defined( 'REALTY_HOUSE_CSS_PATH' ) )
	{
		define( 'REALTY_HOUSE_CSS_PATH', REALTY_HOUSE_BASE_URL . '/assets/css/' );
	}
	if ( ! defined( 'REALTY_HOUSE_IMG_PATH' ) )
	{
		define( 'REALTY_HOUSE_IMG_PATH', REALTY_HOUSE_BASE_URL . '/assets/img/' );
	}
	if ( ! defined( 'REALTY_HOUSE_JS_PATH' ) )
	{
		define( 'REALTY_HOUSE_JS_PATH', REALTY_HOUSE_BASE_URL . '/assets/js/' );
	}
	
	if ( ! isset( $content_width ) )
	{
		$content_width = 1200;
	}
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 2 - Include the framework options
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! class_exists( 'ReduxFramework' ) && file_exists( REALTY_HOUSE_FRAMEWORK . 'core/framework.php' ) )
	{
		require_once( REALTY_HOUSE_FRAMEWORK . 'core/framework.php' );
	}
	
	if ( ! isset( $redux_demo ) && file_exists( REALTY_HOUSE_FRAMEWORK . 'options-init.php' ) )
	{
		require_once( REALTY_HOUSE_FRAMEWORK . 'options-init.php' );
	}
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 3 - Realty House Function Setup
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! function_exists( 'realty_house_setup' ) )
	{
		
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		function realty_house_setup()
		{
			
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Realty House Theme, use a find and replace
			 * to change 'realty-house' to the name of your theme in all the template files
			 */
			load_theme_textdomain( 'realty-house', get_template_directory() . '/languages' );
			
			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );
			
			/*
			 * Let WordPress manage the document title.
			 */
			add_theme_support( 'title-tag' );
			
			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array (
				'primary' => esc_html__( 'Primary Menu', 'realty-house' ),
			) );
			
			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array (
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );
			
			/**
			 * Enable support for Post Thumbnails, and declare two sizes.
			 */
			add_theme_support( 'post-thumbnails' );
			
			/*
			 * Enable support for Post Formats.
			 */
			add_theme_support( 'post-formats', array (
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			) );
		}
	} // realty_house_setup
	add_action( 'after_setup_theme', 'realty_house_setup' );
	
	/**
	 *------------------------------------------------------------------------------------------
	 * 4 Add required script files ( css / Js and etc )
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! function_exists( 'realty_house_scripts' ) )
	{
		function realty_house_scripts()
		{
			global $realty_house_opt, $post;
			
			if ( ! defined( 'REALTY_HOUSE_SHORTCODE_WIZARD' ) )
			{
				/**
				 * Add the css files of Realty House
				 */
				wp_enqueue_style( 'realty-house-main-style-file', REALTY_HOUSE_CSS_PATH . 'style.css' );
				if ( file_exists( REALTY_HOUSE_FRAMEWORK . 'style.css' ) )
				{
					wp_enqueue_style( 'realty-house-dynamic-style-file', REALTY_HOUSE_BASE_URL . '/admin/style.css' );
				}
				
				/**
				 * Add the JS files of Realty House
				 */
				wp_enqueue_script( "realty-house-helper-js", REALTY_HOUSE_JS_PATH . 'helper.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				
				$theme_current_locale = 'en';
				if ( get_locale() !== 'en_US' )
				{
					if ( file_exists( REALTY_HOUSE_THEMEROOT . '/assets/js/locales/locales.php' ) )
					{
						require_once( REALTY_HOUSE_THEMEROOT . '/assets/js/locales/locales.php' );
					}
					isset( $theme_locales[ get_locale() ] ) ? $theme_current_locale = $theme_locales[ get_locale() ] : '';
					
					wp_enqueue_script( "realty-house-datepicker-locale-js", REALTY_HOUSE_JS_PATH . 'locales/' . get_locale() . '.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				}
				
				if ( ! empty( $realty_house_opt['opt-smooth-scroll'] ) )
				{
					wp_enqueue_script( "smooth-scroll", REALTY_HOUSE_JS_PATH . 'jquery.SmoothScroll.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				}
				wp_enqueue_script( "owl-carousel", REALTY_HOUSE_JS_PATH . 'owl.carousel.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				wp_enqueue_script( "magnific-popup", REALTY_HOUSE_JS_PATH . 'jquery.magnific-popup.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				wp_enqueue_script( "isotop", REALTY_HOUSE_JS_PATH . 'isotope.pkgd.min.js', array (
					'jquery',
					'imagesloaded'
				), get_bloginfo( 'version' ), true );
				
				if ( is_post_type_archive( 'property' ) )
				{
					$map_in_bread = ! empty( $realty_house_opt['realty-house-property-archive-map'] ) ? true : false;
				}
				elseif ( is_tax( 'property-category' ) )
				{
					$map_in_bread = ! empty( $realty_house_opt['realty-house-property-archive-map'] ) ? true : false;
				}
				elseif ( is_page_template( '../templates/search-property.php' ) )
				{
					$map_in_bread = ! empty( $realty_house_opt['realty-house-property-search-map'] ) ? true : false;
				}
				else
				{
					$map_in_bread = ( get_post_meta( get_the_id(), 'realty_house_page_map_crumb', true ) ? get_post_meta( get_the_id(), 'realty_house_page_map_crumb', true ) : '' );
				}
				
				
				if ( ! empty( $map_in_bread ) || is_singular( 'agent' ) )
				{
					wp_enqueue_script( "google-map", '//maps.googleapis.com/maps/api/js?libraries=places' . ( ! empty( $realty_house_opt['opt-map-api'] ) ? '&key=' . esc_attr( $realty_house_opt['opt-map-api'] ) : '' ), array (), get_bloginfo( 'version' ), true );
					wp_enqueue_script( "google-map-requirements", REALTY_HOUSE_JS_PATH . 'googlemap-requirement.js', array (
						'jquery',
						'google-map'
					), get_bloginfo( 'version' ), true );
				}
				
				wp_enqueue_script( "realty-house-template-js", REALTY_HOUSE_JS_PATH . 'template.min.js', array ( 'jquery' ), get_bloginfo( 'version' ), true );
				
				if ( ! empty ( $realty_house_opt['realty-house-property-status'] ) )
				{
					$rent_id = array_search( 'For Rent', $realty_house_opt['realty-house-property-status'] ) + 1;
				}
				
				// Localized ajaxurl
				wp_localize_script( 'realty-house-template-js', 'realtyHouse', array (
					'ajaxurl'     => esc_url( admin_url( 'admin-ajax.php' ) ),
					'redirecturl' => home_url(),
					'assetsURL'   => REALTY_HOUSE_BASE_URL . '/assets/',
					'rent_id'     => ! empty( $rent_id ) ? $rent_id : ''
				) );
				
				if ( is_singular() )
				{
					wp_enqueue_script( "comment-reply" );
				}
			}
			else
			{
				wp_enqueue_style( 'realty-house-shortcode-wizard-style', REALTY_HOUSE_CSS_PATH . ( 'shortcode-wizard.css' ) );
				wp_enqueue_script( "realty-house-shortcode-tinyMCE-popup-js", site_url() . '/wp-includes/js/tinymce/tiny_mce_popup.js', array ( 'jquery' ), true );
				wp_enqueue_script( "realty-house-shortcode-wizard-js", REALTY_HOUSE_JS_PATH . 'shortcode-wizard.js', array ( 'jquery' ), true );
			}
			
			$option_css_codes = '';
			if ( isset( $realty_house_opt['opt-custom-css'] ) && $realty_house_opt['opt-custom-css'] !== '' )
			{
				$option_css_codes .= $realty_house_opt["opt-custom-css"];
			}
			if ( ! empty( $option_css_codes ) )
			{
				wp_add_inline_style( 'realty-house-main-style-file', $option_css_codes );
			}
			
			$option_js_code = '';
			
			if ( ! empty( $realty_house_opt['opt-sticky-header'] ) )
			{
				$option_js_code .= '
					var headerSec = jQuery("body, #main-header");
					jQuery(window).scroll(function() {
						jQuery(document).scrollTop() > 136 ? headerSec.addClass("sticky") : headerSec.removeClass("sticky");
					});
				';
			}
			if ( ! empty( $option_js_code ) )
			{
				wp_add_inline_script( 'realty-house-template-js', $option_js_code );
			}
			
			
		}
	}
	add_action( 'wp_enqueue_scripts', 'realty_house_scripts' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 5 - Pagination function
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'ravis-pagination.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 6 - Breadcrumb function
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'ravis-breadcrumb.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 7 - Register SideBars
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! function_exists( 'realty_house_register_sidebar' ) )
	{
		function realty_house_register_sidebar()
		{
			register_sidebar( array (
				'name'          => esc_html__( 'Main Widget area', 'realty-house' ),
				'id'            => 'main-side-bar',
				'description'   => esc_html__( 'Appears to the right side of the blog page.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Top Footer Widget area', 'realty-house' ),
				'id'            => 'top-footer',
				'description'   => esc_html__( 'Appears in top of the website footer.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget col-sm-6 col-md-3 %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Property Listing Widget area', 'realty-house' ),
				'id'            => 'p-listing-sidebar',
				'description'   => esc_html__( 'Appears to the right side of property listing pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Property Listing Map View Widget area', 'realty-house' ),
				'id'            => 'p-map-listing-sidebar',
				'description'   => esc_html__( 'Appears at top of property listing section of Map View page template.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Property Details Widget area', 'realty-house' ),
				'id'            => 'p-details-sidebar',
				'description'   => esc_html__( 'Appears to the right side of property details pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Agent Listing Widget area', 'realty-house' ),
				'id'            => 'agent-listing-sidebar',
				'description'   => esc_html__( 'Appears to the right side of agent listing pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Agent Details Widget area', 'realty-house' ),
				'id'            => 'agent-details-sidebar',
				'description'   => esc_html__( 'Appears to the right side of agent details pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'FAQ Widget area', 'realty-house' ),
				'id'            => 'faq-sidebar',
				'description'   => esc_html__( 'Appears to the right side of FAQ pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Testimonials Widget area', 'realty-house' ),
				'id'            => 'testimonials-sidebar',
				'description'   => esc_html__( 'Appears to the right side of testimonials pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Submit Property Widget area', 'realty-house' ),
				'id'            => 'p-submit-sidebar',
				'description'   => esc_html__( 'Appears to the right side of Submit Property pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Be an Agent Widget area', 'realty-house' ),
				'id'            => 'be-agent-sidebar',
				'description'   => esc_html__( 'Appears to the right side of Be an Agent pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array (
				'name'          => esc_html__( 'Property Search Result Widget area', 'realty-house' ),
				'id'            => 'p-search-sidebar',
				'description'   => esc_html__( 'Appears to the right side of property search result pages.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
			
			register_sidebar( array (
				'name'          => esc_html__( 'Property Search Result Map View Widget area', 'realty-house' ),
				'id'            => 'p-map-search-sidebar',
				'description'   => esc_html__( 'Appears at top of search result in map view version.', 'realty-house' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="side-title">',
				'after_title'   => '</h3>',
			) );
		}
	}
	add_action( 'widgets_init', 'realty_house_register_sidebar' );
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 8 - Load post_types in the menu section
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'post-type-in-menu.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 9 - Include required plugins to theme
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'include-plugins.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 10 - Determine whether blog/site has more than one category.
	 * @return bool True of there is more than one category, false otherwise.
	 * ------------------------------------------------------------------------------------------
	 */
	
	function realty_house_categorized_blog()
	{
		if ( false === ( $all_the_cool_cats = get_transient( 'ravis_categories' ) ) )
		{
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array (
				'fields'     => 'ids',
				'hide_empty' => 1,
				
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );
			
			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );
			
			set_transient( 'ravis_categories', $all_the_cool_cats );
		}
		
		if ( $all_the_cool_cats > 1 )
		{
			// This blog has more than 1 category so realty_house_categorized_blog should return true.
			return true;
		}
		else
		{
			// This blog has only 1 category so realty_house_categorized_blog should return false.
			return false;
		}
	}
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 11 - Post Meta function
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'post-meta.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 12 - Include Custom Ravis Widgets
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_WIDGET_PATH . 'ravis-recent-post-thumb.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 13 - Limit the search result for Posts
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! function_exists( 'realty_house_search_filter' ) )
	{
		function realty_house_search_filter( $query )
		{
			global $realty_house_opt;
			
			if ( empty( $realty_house_opt['opt-search-in-pages'] ) )
			{
				if ( $query->is_search )
				{
					$query->set( 'post_type', 'post' );
				}
				
				return $query;
			}
		}
	}
	add_filter( 'pre_get_posts', 'realty_house_search_filter' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 14 - Excerpt setting of blog
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'excerpt-setting.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 15 - Link Posts links
	 * ------------------------------------------------------------------------------------------
	 */
	if ( ! function_exists( 'realty_house_get_link_url' ) )
	{
		function realty_house_get_link_url()
		{
			$has_url = get_url_in_content( get_the_content() );
			
			return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
		}
	}
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 16 - Breadcrumb background function
	 * It will be generated the background image of breadcrumb
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'set-breadcrumb-bg.php' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 17 - Enqueue Google Font
	 * ------------------------------------------------------------------------------------------
	 */
	
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'google-font-enqueue.php' );
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  18 - Add required class to the body
	 * ------------------------------------------------------------------------------------------
	 */
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'add_container_class.php' );
	
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * 19 - Return the post URL
	 * @see get_url_in_content()
	 * @return string The Link format URL.
	 * ------------------------------------------------------------------------------------------
	 */
	
	if ( ! function_exists( 'realty_house_tm_get_link_url' ) ) :
		function realty_house_tm_get_link_url()
		{
			$has_url = get_url_in_content( get_the_content() );
			
			return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
		}
	endif;
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  20 - Include plugin required files
	 * ------------------------------------------------------------------------------------------
	 */
	if ( ! function_exists( 'realty_house_add_plugin_core_file' ) )
	{
		function realty_house_add_plugin_core_file()
		{
			if ( ! function_exists( 'is_plugin_active' ) )
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
		}
	}
	add_action( 'init', 'realty_house_add_plugin_core_file' );
	
	/**
	 * ------------------------------------------------------------------------------------------
	 *  21 - Include User class
	 * ------------------------------------------------------------------------------------------
	 */
	include( REALTY_HOUSE_FUNCTIONS_PATH . 'user.class.php' );

	
