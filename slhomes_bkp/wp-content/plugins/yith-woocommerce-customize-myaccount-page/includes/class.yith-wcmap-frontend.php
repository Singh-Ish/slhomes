<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH WooCommerce Customize My Account Page
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCMAP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCMAP_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCMAP_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCMAP_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCMAP_VERSION;

		/**
		 * Page templates
		 *
		 * @var string
		 * @since 1.0.0
		 */
		protected $_is_myaccount = false;

		/**
		 * Menu Shortcode
		 *
		 * @access protected
		 * @var string
		 */
		protected $_shortcode_name = 'yith-wcmap-menubar';

		/**
		 * Page templates
		 *
		 * @var string
		 * @since 1.0.0
		 */
		protected $_add_avatar_action = 'yith_wcmap_add_avatar';

		/**
		 * My account endpoint
		 *
		 * @var string
		 * @since 1.0.0
		 */
		protected $_menu_endpoints = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCMAP_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			// plugin init
			add_action( 'init', array( $this, 'init' ) );

			// enqueue scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );

			// check if is shortcode my-account
			add_action( 'template_redirect', array( $this, 'check_myaccount' ), 1 );
			// redirect to the default endpoint
			add_action( 'template_redirect', array( $this, 'redirect_to_default' ), 10 );
			// add custom endpoints content
			add_action( 'template_redirect', array( $this, 'add_custom_endpoint_content' ), 50 );
			// add account menu
			add_action( 'template_redirect', array( $this, 'add_account_menu' ), 99 );

			// shortcode for print my account sidebar
			add_shortcode( $this->_shortcode_name, array( $this, 'my_account_menu' ) );

			// add avatar
			add_action( 'wp_ajax_' . $this->_add_avatar_action, array( $this, 'add_avatar' ) );
			add_action( 'wp_ajax_nopriv_' . $this->_add_avatar_action, array( $this, 'add_avatar' ) );

			add_action( 'init', array( $this, 'add_avatar' ) );

			// shortcodes for my-downloads and view order content
			add_shortcode( 'my_downloads_content', array( $this, 'my_downloads_content' ) );
			add_shortcode( 'view_order_content', array( $this, 'view_order_content' ) );

			// mem if is my account page
			add_action( 'shutdown', array( $this, 'save_is_my_account' ) );
		}

		/**
		 * Init plugins variable
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function init() {

			$this->_menu_endpoints = yith_wcmap_get_endpoints();

			// remove disabled
			foreach( $this->_menu_endpoints as $endpoint => $options ) {
				if( ! $options['active'] ){
					unset( $this->_menu_endpoints[$endpoint] );
				}
			}
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function enqueue_scripts(){

			if( ! $this->_is_myaccount ){
				return;
			};

			$paths      = apply_filters( 'yith_wcmap_stylesheet_paths', array( WC()->template_path() . 'yith-customize-myaccount.css', 'yith-customize-myaccount.css' ) );
			$located    = locate_template( $paths, false, false );
			$search     = array( get_stylesheet_directory(), get_template_directory() );
			$replace    = array( get_stylesheet_directory_uri(), get_template_directory_uri() );
			$stylesheet = ! empty( $located ) ? str_replace( $search, $replace, $located ) : YITH_WCMAP_ASSETS_URL . '/css/ywcmap-frontend.css';

			wp_register_style( 'ywcmap-frontend', $stylesheet );

			wp_register_script( 'ywcmap-frontend', YITH_WCMAP_ASSETS_URL . '/js/ywcmap-frontend.js', array( 'jquery' ), false, true );
			// font awesome
			wp_register_style( 'font-awesome', YITH_WCMAP_ASSETS_URL . '/css/font-awesome.min.css' );

			$suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

			if( get_option( 'yith-wcmap-custom-avatar' ) == 'yes' ) {
				wp_enqueue_script('ywcmap_prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array('jquery'), '3.1.6', true);
				wp_enqueue_style('ywcmap_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css');

				wp_enqueue_script( 'ywcmap-frontend' );
			}


			wp_enqueue_style( 'ywcmap-frontend' );

			wp_enqueue_style( 'font-awesome' );

			wp_localize_script( 'ywcmap-frontend', 'ywcmap', array(
				'ajaxurl'  	 => admin_url( 'admin-ajax.php' ),
				'action_add_avatar' => $this->_add_avatar_action,
			));

			$inline_css = '
				#my-account-menu .logout a,
				#my-account-menu-tab .logout a {
					color:' . get_option('yith-wcmap-logout-color') . ';
					background-color:' . get_option('yith-wcmap-logout-background') . ';
				}
				#my-account-menu .logout:hover a,
				#my-account-menu-tab .logout:hover a {
					color:' . get_option('yith-wcmap-logout-color-hover') . ';
					background-color:' . get_option('yith-wcmap-logout-background-hover') . ';
				}
				.myaccount-menu li a {
					color:' . get_option( 'yith-wcmap-menu-item-color' ). ';
				}
				.myaccount-menu li:hover a,
				.myaccount-menu li.active a {
					color:' . get_option( 'yith-wcmap-menu-item-color-hover' ). ';
				}
			';

			wp_add_inline_style( 'ywcmap-frontend', $inline_css );
		}

		/**
		 * Check if is page my-account and set class variable
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function check_myaccount() {

			global $post;

			if( ! is_null( $post ) && strpos( $post->post_content, '[woocommerce_my_account' ) !== false && is_user_logged_in() ) {
				$this->_is_myaccount = true;
			}
		}

		/**
		 * Redirect to default endpoint
		 *
		 * @access public
		 * @since 1.0.4
		 * @author Francesco Licandro
		 */
		public function redirect_to_default(){

			if( get_option('yith_wcmap_is_my_account', true ) ) {
				return;
			}

			$default = get_option( 'yith-wcmap-default-endpoint', 'dashboard' );

			$current_endpoint = WC()->query->get_current_endpoint();

			if( ! $this->_is_myaccount || $default == 'dashboard' || $current_endpoint ) {
				return;
			}

			$url = wc_get_page_permalink( 'myaccount' );
			$url = isset( $_GET['dashboard'] ) ? $url : wc_get_endpoint_url( $default, '', $url );

			wp_safe_redirect( $url );
			exit;
		}

		/**
		 * Add custom endpoints content
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function add_custom_endpoint_content() {

			if( ! $this->_is_myaccount ){
				return;
			}

			global $wp, $post;

			$active = 'dashboard';
			$endpoints = yith_wcmap_get_endpoints();

			if( empty( $endpoints ) ) {
				return;
			}

			// search for active endpoints
			foreach( $endpoints as $endpoint => $endpoint_opts ) {

				if( ! isset( $wp->query_vars[ $endpoint_opts['slug'] ] ) || ! empty( $wp->query_vars[ $endpoint_opts['slug'] ] ) ) {
					continue;
				}

				$active = $endpoint;
			}

			// set endpoint title
			if( ! empty( $endpoints[$active]['label'] ) && $active != 'dashboard' ) {
				$post->post_title = stripslashes( $endpoints[$active]['label'] );
			}

			// first check in custom content
			if( ! empty( $endpoints[$active]['content'] ) ) {
				$post->post_content = stripslashes( $endpoints[$active]['content'] );
			}
		}

		/**
		 * If is my account add menu to content
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function add_account_menu() {

			global $post;

			if( $this->_is_myaccount ) {

				$position = get_option( 'yith-wcmap-menu-position', 'left' );
				$tab = get_option( 'yith-wcmap-menu-style', 'sidebar' ) == 'tab' ? '-tab' : '';
				$menu = '<div id="my-account-menu' . $tab . '" class="yith-wcmap position-' . $position .'">[' . $this->_shortcode_name . ']</div>';
				$post_content = '<div id="my-account-content" >' . $post->post_content . '</div>';

				$content = ( $position == 'right' && $tab == '' ) ? $post_content . $menu : $menu . $post_content;
				// set new post content
				$post->post_content = $content;
			}
		}

		/**
		 * Output my-account shortcode
		 *
		 * @since 1.0.0
		 * @author Frnacesco Licandro
		 */
		public function my_account_menu() {

			$args = apply_filters( 'yith-wcmap-myaccount-menu-template-args', array(
				'endpoints' => $this->_menu_endpoints,
				'my_account_url' => get_permalink( wc_get_page_id( 'myaccount' ) ),
				'avatar'	=> get_option( 'yith-wcmap-custom-avatar' ) == 'yes'
			));

			ob_start();

			wc_get_template( 'ywcmap-myaccount-menu.php', $args, '', YITH_WCMAP_DIR . 'templates/' );

			return ob_get_clean();

		}

		/**
		 * Add user avatar
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function add_avatar(){

			if( ! isset( $_FILES['ywcmap_user_avatar'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'wp_handle_upload' ) )
				return;

			if ( ! function_exists( 'media_handle_upload' )  ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
			}

			$media_id = media_handle_upload( 'ywcmap_user_avatar', 0 );

			if( is_wp_error( $media_id ) ) {
				return;
			}

			// save media id for filter query in media library
			$medias = get_option('yith-wcmap-users-avatar-ids', array() );
			$medias[] = $media_id;
			// then save
			update_option( 'yith-wcmap-users-avatar-ids', $medias );


			// save user meta
			$user = get_current_user_id();
			update_user_meta( $user, 'yith-wcmap-avatar', $media_id );

		}

		/**
		 * Print my-downloads endpoint content
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function my_downloads_content( $atts ) {

			$content = '';

			ob_start();
					wc_get_template( 'myaccount/my-downloads.php' );
			$content = ob_get_clean();

			// print message if no downloads
			if( ! $content ){
				$content = '<p>' . __( 'There are no available downloads yet.', 'yith-woocommerce-customize-myaccount-page' ) . '</p>';
			}

			return $content;
		}

		/**
		 * Print view-order endpoint content
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function view_order_content( $atts ) {

			extract( shortcode_atts( array(
				'order_count' => 15
			), $atts ) );

			$content = '';
			$order_count = $order_count == 'all' ? -1 : $order_count;

			ob_start();
					wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );
			$content = ob_get_clean();

			// print message if no orders
			if( ! $content ){
				$content = '<p>' . __( 'There are no orders yet.', 'yith-woocommerce-customize-myaccount-page' ) . '</p>';
			}

			return $content;
		}

		/**
		 * Save an option to check if the page is myaccount
		 *
		 * @access public
		 * @since 1.0.4
		 * @author Francesco Licandro
		 */
		public function save_is_my_account(){
			update_option( 'yith_wcmap_is_my_account', $this->_is_myaccount );
		}

	}
}
/**
 * Unique access to instance of YITH_WCMAP_Frontend class
 *
 * @return \YITH_WCMAP_Frontend
 * @since 1.0.0
 */
function YITH_WCMAP_Frontend(){
	return YITH_WCMAP_Frontend::get_instance();
}