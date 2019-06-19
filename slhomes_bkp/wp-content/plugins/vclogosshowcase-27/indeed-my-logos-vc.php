<?php
/*
Plugin Name: Indeed My Logos VC (Visual Composer Version)
Plugin URI: http://www.wpindeed.com/
Description: The best plugin to display a list of logos or clients for Visual Composer
Version: 2.7
Author: indeed
Author URI: http://www.wpindeed.com
*/
if (get_option('iml_post_type_slug')!=FALSE){
	define('IML_POST_TYPE_VC', get_option('iml_post_type_slug'));
	if (IML_POST_TYPE_VC=='clients'){
		define('IML_TAXONOMY_VC', 'clients_groups');
	} else {
		define('IML_TAXONOMY_VC', IML_POST_TYPE_VC.'_groups');
	}
} else {
	global $wpdb;
	$temp_check = $wpdb->get_var("SELECT COUNT(*) as c FROM {$wpdb->postmeta} WHERE meta_key='imc_cf_link';");
	if ($temp_check){
		define('IML_POST_TYPE_VC', 'clients');
		define('IML_TAXONOMY_VC', 'clients_groups');	
	} else {
		define('IML_POST_TYPE_VC', 'iml_clients');
		define('IML_TAXONOMY_VC', 'iml_clients_groups');	
	}
	update_option('iml_post_type_slug', IML_POST_TYPE_VC);
} 

if(!defined('IML_DIR_PATH_VC')){
	define('IML_DIR_PATH_VC', plugin_dir_path ( __FILE__ ));
}
if(!defined('IML_DIR_URL_VC')){
	define('IML_DIR_URL_VC', plugin_dir_url ( __FILE__ ));
}

#Languages
add_action('init', 'iml_load_language_vc');
function iml_load_language_vc(){
	load_plugin_textdomain( 'iml', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}

#FUNCTIONS
include_once IML_DIR_PATH_VC . 'includes/functions.php';
/////VISUAL COMPOSER
function iml_check_vc(){
    if(function_exists('vc_map')){
      //gettings cats
      $args = array(
                    'taxonomy'=>IML_TAXONOMY_VC,
                    'type'=>IML_POST_TYPE_VC,
      				'orderby' => 'slug'
      				);
      $cats = get_categories($args);
      if(isset($cats) && count($cats)>0){
          foreach($cats as $cat){
                $cat_arr[$cat->slug] = $cat->name;
                $cat_arr_keys[] = $cat->slug;
          }
          $cats_str = implode(',', $cat_arr_keys);
      }else{
            $cat_arr = array();
            $cats_str = '';
      }
        include_once IML_DIR_PATH_VC . 'includes/iml_vc_functions.php';
        vc_map( array(
        			   "name" => "Indeed My Logos",
        			   "base" => "indeed-clients",
        			   "icon" => "icon-wpb-ttp",
                       "description" => "Show My Logos",
    			       "class" => "indeed-my-logos",
    			       "category" => __('Content', "js_composer"),
        			   "params" => array(
        									array(
        										"type" => "iml_select_cats",
        										"heading" => __("Group", 'iml'),
        										"param_name" => "group",
                                                "admin_label" => true,
        										"value" => "all",
        										"description" => "Select one or many Groups with Logos"
        									),
        									array(
        										"type" => "iml_number",
        										"heading" => __("Number of items", 'iml'),
        										"param_name" => "limit",
                                                "admin_label" => true,
        										"value" => 10,
        										"param_min_value" => 1,
        										"description" => ""
        									),
				        			   		array(
				        			   				"type" => 'iml_sepparate_line',
				        			   				"param_name" => "line1",
				        			   				'value' => '',
				        			   		),
        								   array(
        										"type" => "iml_custom_dd",
        										"heading" => __("Order By", 'iml'),
                                                "select_id" => "order_by_sel",
                                                "onclick" => "",
        										"param_name" => "order_by",
        										"values" => array("date" => __("Date", 'iml'), "name" => __("Name", 'iml'), 'last_name' => __('Last Name', 'iml'), "rand" => __("Random", 'iml') )
        									),
        									array(
        										"type" => "dropdown",
        										"heading" => __("Order Type", 'iml'),
        										"param_name" => "order",
        										"value" => array("ASC", "DESC")#don't translate this
        									),
				        			   		array(
				        			   				"type" => 'iml_sepparate_line',
				        			   				"param_name" => "line2",
				        			   				'value' => '',
				        			   		),
				        			   		array(
				        			   				"type" => 'iml_title_block_iml_bkcolor6',
				        			   				"heading" => __("Entry Information", 'imtst'),
				        			   				"wrap_div" => TRUE,
				        			   				"param_name" => "entry_information",
				        			   				"wrap_class" => "iml_vc_title"
				        			   		),        			   		
        									array(
        										  "type" => 'iml_checkbox_block',
        										  "heading" => __("Custom Field To Show:", 'iml'),
        										  "hidden_name" => "show",
                                                  "hidden_id" => "hidden_i_cf",
        										  "param_name" => "show",
        										  "checkboxes" => array("name" => __("Name", 'iml'),
                                                                        "logo" => __("Logo", 'iml'),
                                                                        "link" => __("URL Open", 'iml'),
                                                                        "tool_tip" => __("ToolTip", 'iml')
                                                                        ),
        										  "value" => "logo"
        									),
				        			   		array(
				        			   				"type" => 'iml_title_block_iml_bkcolor1',
				        			   				"heading" => __("Template", 'iml'),
				        			   				"wrap_div" => TRUE,
				        			   				"param_name" => "template",
				        			   				"wrap_class" => "iml_vc_title"
				        			   		),        			   		
                                            array(
                                                "type" => "iml_select_themes",
                                                "heading" => __("Select a Theme", 'iml'),
                                                "param_name" => "theme",
                                                "value" => ""
                                            ),
                                            array(
                                                "type" => "iml_return_effect_dd",
                                                "heading" => __("Effect", 'iml'),
                                                "param_name" => "effect",
                                                "value" => ""
                                            ),
                                            array(
        										"type" => "dropdown",
        										"heading" => __("Number of Columns:", 'iml'),
        										"param_name" => "columns",
        										"value" => array(1,2,3,4,5,6,7,8,9,10,11,12),
												"std" => 6
                                            ),
				        			   		array(
				        			   				"type" => "iml_checkbox_field",
				        			   				"label" => __("Align The Items Centered", 'iml'),
				        			   				"id_checkbox" => "align_center",
				        			   				"value" => 0,
				        			   				"id_hidden" => "h_align_center",
				        			   				"param_name" => "align_center",
				        			   				"onClick" => "",
				        			   		),        			   		
        									array(
        										"type" => "iml_number",
        										"heading" => __("Height Ratio", 'iml'),
        										"param_name" => "item_height",
        										"value" => 100,
        										"param_min_value" => 0,
												"param_max_value" => 100,
        										"description" => ""
        									),
                                            ///////slider
				        			   		array(
				        			   				"type" => 'iml_title_block_iml_bkcolor2',
				        			   				"heading" => __("Slider ShowCase", 'iml'),
				        			   				"wrap_div" => TRUE,
				        			   				"param_name" => "slider_title",
				        			   				"wrap_class" => "iml_vc_title"
				        			   		),        
                                            array(
                                                "type" => "iml_checkbox_field_actv",
                                                "label" => __("Show as Slider", 'iml'),
                                            	"heading" => __("Activate the Slider", 'iml'),
                                                "id_checkbox" => "slider_main_checkbox",
                                                "value" => 0,
                                                "id_hidden" => "show_as_slider",
                                                "param_name" => "slider_set",
                                                "onClick" => "check_mf_selector(this, \".slider_options\", \"opacity\", 1, \"0.5\",\".filter_options\", \"#filter_main_checkbox\",\"#show_as_filter\");",
                                                "slider_or_filter" => "slider",
                                            	"extra_text" => "<div class='warning_grey_span'>" . __('If Slider Showcase is used, Filter Showcase is disabled.', 'iml') . "</div>"
                                            ),
                                            array(
        										"type" => "iml_number",
                                                "heading" => __("Items per Slide:", 'iml'),
                                            	'label' => '',
        										"param_name" => "items_per_slide",
        										"value" => 6,
        										"param_min_value" => 1,
        										"description" => "",
                                                "wrap_div" => TRUE  ,
                                                "wrap_class" => "slider_options",
                                                "slider_or_filter" => "slider",
        									),
        									array(
        										"type" => "iml_number",
        										"heading" => __("Slide TimeOut", 'iml'),
        										'label' => '',
        										"param_name" => "slide_speed",
        										"value" => 5000,
        										"param_min_value" => 1,
        										"description" => "",
                                                "wrap_div" => TRUE,
                                                "wrap_class" => "slider_options",
                                                "slider_or_filter" => "slider",
        									),
        									array(
        										"type" => "iml_number",
        										"heading" => __("Pagination Speed", 'iml'),
        										'label' => '',
        										"param_name" => "slide_pagination_speed",
        										"value" => 500,
        										"param_min_value" => 1,
        										"description" => "",
                                                "wrap_div" => TRUE,
                                                "wrap_class" => "slider_options",
                                                "slider_or_filter" => "slider",
        									),
        									array(
        										  "type" => 'iml_checkbox_block',
        										  "heading" => "",
        										  "hidden_name" => "slide_opt",
                                                  "hidden_id" => "hidden_slide_opt",
        										  "param_name" => "slide_opt",
        										  "checkboxes" => array("bullets"=>__("Bullets", 'iml'), "nav_button"=>__("Nav Button", 'iml'), "autoplay"=>__("Autoplay", 'iml'), "stop_hover"=>__("Stop Hover",'iml'), "responsive"=>__("Responsive", 'iml'), 'autoheight'=>__('Auto Height', 'iml'), "lazy_load"=>__("Lazy Load", 'iml'), 'loop'=>__('Play in Loop', 'iml') ),
        										  "value" => "bullets,nav_button,autoplay,stop_hover,responsive,autoheight,loop",
                                                  "wrap_div" => TRUE,
                                                  "wrap_class" => "slider_options",
                                                  "slider_or_filter" => "slider",
        									),
				        			   		array(
				        			   				"type" => "iml_custom_dropdown",
				        			   				"label" => __("Pagination Theme", 'iml'),
				        			   				"param_name" => "pagination_theme",
				        			   				"values" => array("pag-theme1"=>__("Pagination Theme 1", 'iml'), "pag-theme2"=>__("Pagination Theme 2", 'iml'), "pag-theme3"=>__("Pagination Theme 3", 'iml'),),
				        			   				"value" => "",
				        			   				"wrap_div" => TRUE,
				        			   				"wrap_class" => "slider_options",
				        			   				"slider_or_filter" => "slider"
				        			   		),
				        			   		array(
				        			   				"type" => "iml_custom_dropdown",
				        			   				"label" => __("Animation Slide In", 'iml'),
				        			   				"param_name" => "animation_in",
				        			   				"values" => array("none"=>__("None", 'iml'), "fadeIn"=>__("fadeIn", 'iml'), "fadeInDown"=>__("fadeInDown", 'iml'), "fadeInUp"=>__("fadeInUp", 'iml'), "slideInDown"=>__("slideInDown", 'iml'), "slideInUp"=>__("slideInUp", 'iml'), "flip"=>__("flip", 'iml'),
				        			   						"flipInX"=>__("flipInX", 'iml'),"flipInY"=>__("flipInY", 'iml'),"bounceIn"=>__("bounceIn", 'iml'),"bounceInDown"=>__("bounceInDown", 'iml'),"bounceInUp"=>__("bounceInUp", 'iml'),"rotateIn"=>__("rotateIn", 'iml'),"rotateInDownLeft"=>__("rotateInDownLeft", 'iml'),
				        			   						"rotateInDownRight"=>__("rotateInDownRight", 'iml'),"rollIn"=>__("rollIn", 'iml'),"zoomIn"=>__("zoomIn", 'iml'),"zoomInDown"=>__("zoomInDown", 'iml'),"zoomInUp"=>__("zoomInUp", 'iml')),
				        			   				"value" => "",
				        			   				"wrap_div" => TRUE,
				        			   				"wrap_class" => "slider_options",
				        			   				"slider_or_filter" => "slider"
				        			   		),
				        			   		array(
				        			   				"type" => "iml_custom_dropdown",
				        			   				"label" => __("Animation Slide Out", 'iml'),
				        			   				"param_name" => "animation_out",
				        			   				"values" => array("none"=>__("None", 'iml'), "fadeOut"=>__("fadeOut", 'iml'), "fadeOutDown"=>__("fadeOutDown", 'iml'), "fadeOutUp"=>__("fadeOutUp", 'iml'), "slideOutDown"=>__("slideOutDown", 'iml'), "slideOutUp"=>__("slideOutUp", 'iml'), "flip"=>__("flip", 'iml'),
				        			   						"flipOutX"=>__("flipOutX", 'iml'),"flipOutY"=>__("flipOutY", 'iml'),"bounceOut"=>__("bounceOut", 'iml'),"bounceOutDown"=>__("bounceOutDown", 'iml'),"bounceOutUp"=>__("bounceOutUp", 'iml'),"rotateOut"=>__("rotateOut", 'iml'),"rotateOutUpLeft"=>__("rotateOutUpLeft", 'iml'),
				        			   						"rotateOutUpRight"=>__("rotateOutUpRight", 'iml'),"rollOut"=>__("rollOut", 'iml'),"zoomOut"=>__("zoomOut", 'iml'),"zoomOutDown"=>__("zoomOutDown", 'iml'),"zoomOutUp"=>__("zoomOutUp", 'iml')),
				        			   				"value" => "",
				        			   				"wrap_div" => TRUE,
				        			   				"wrap_class" => "slider_options",
				        			   				"slider_or_filter" => "slider"
				        			   		),        			   		
                                            ////filter
				        			   		array(
				        			   				"type" => 'iml_title_block_iml_bkcolor3',
				        			   				"heading" => __("Filter ShowCase", 'imtst'),
				        			   				"wrap_div" => TRUE,
				        			   				"param_name" => "filter_title",
				        			   				"wrap_class" => "iml_vc_title"
				        			   		),        
                                            array(
                                                "type" => "iml_checkbox_field_actv",
                                            	"heading" => __("Activate the Filter", 'iml'),
                                                "label" => __("Show Filter", 'iml'),
                                                "id_checkbox" => "filter_main_checkbox",
                                                "value" => 0,
                                                "id_hidden" => "show_as_filter",
                                                "param_name" => "filter_set",
                                                "onClick" => "check_mf_selector(this, \".filter_options\", \"opacity\", 1, \"0.5\", \".slider_options\", \"#slider_main_checkbox\", \"#show_as_slider\");",
                                                "slider_or_filter" => "filter",
                                            	"extra_text" => "<div class='warning_grey_span'>" . __('If Filter Showcase is used, Slider Showcase is disabled.', 'iml') . "</div>"
                                            		
                                            ),
				        			   		array(
				        			   				"type" => 'iml_sepparate_line',
				        			   				"param_name" => "line3",
				        			   				'value' => '',
				        			   		),
        									array(
        										  "type" => 'iml_checkbox_block',
        										  "heading" => __("Groups List", 'iml'),
        										  "label" => '',
        										  "hidden_name" => "filter_groups",
                                                  "hidden_id" => "hidden-filtergroups",
        										  "param_name" => "filter_groups",
        										  "checkboxes" => $cat_arr,
        										  "value" => '',
                                                  "wrap_div" => TRUE,
                                                  "wrap_class" => "filter_options",
                                                  "slider_or_filter" => "filter",
        									),
				        			   		array(
				        			   				"type" => 'iml_sepparate_line',
				        			   				"param_name" => "line4",
				        			   				'value' => '',
				        			   		),        			   		
                                            array(
                                                  "type" => "iml_custom_dropdown",
                                                  "heading" => __("Theme",'iml'),
                                            	  'label' => '',
                                                  "param_name" => "filter_select_t",
                                                  "values" => array('small_text'=>__('Small Text', 'iml'), 'big_text'=>__('Big Text', 'iml'), 'small_button'=>__('Small Button', 'iml'),'big_button'=>__('Big Buttons', 'iml'),'dropdown'=>__('Drop Down', 'iml') ),
                                                  "value" => "",
                                                  "wrap_div" => TRUE,
                                                  "wrap_class" => "filter_options" ,
                                                  "slider_or_filter" => "filter",
                                            ),
                                            array(
                                                  "type" => "iml_custom_dropdown",
                                                  "heading" => __("Align", 'iml'),
                                            	  'label' => '',
                                                  "param_name" => "filter_align",
                                                  "values" => array('left'=>__('Left', 'iml'),'center'=>__('Center', 'iml'),'right'=>__('Right', 'iml') ),
                                                  "value" => "",
                                                  "wrap_div" => TRUE,
                                                  "wrap_class" => "filter_options",
                                                  "slider_or_filter" => "filter",
                                            ),
				        			   		array(
				        			   				"type" => "iml_custom_dropdown",
				        			   				"heading" => __("Layout Mode", 'iml'),
				        			   				"param_name" => "layout_mode",
				        			   				"values" => array('masonry'=>__('masonry', 'iml'),'fitRows'=>__('fitRows', 'iml') ),
				        			   				"value" => "",
				        			   				"wrap_div" => TRUE,
				        			   				"wrap_class" => "filter_options",
				        			   				"slider_or_filter" => "filter",
				        			   		),
        								)
        			)
        );
        add_action("admin_enqueue_scripts", 'iml_admin_header');
        function iml_admin_header(){
            wp_enqueue_style( 'iml_style', IML_DIR_URL_VC . 'files/css/style.css', array(), null );
            wp_enqueue_script( 'iml_js_functions', IML_DIR_URL_VC . 'files/js/functions.js', array(), null );
        }
    }
}
add_action( 'init', 'iml_check_vc' );

add_action( 'init', 'imc_init_post_type_vc' );
function imc_init_post_type_vc() {
  $labels = array(
    'name'               => __('Clients', 'iml'),
    'singular_name'      => __('Client', 'iml'),
    'add_new'            => __('Add New Client', 'iml'),
    'add_new_item'       => __('Add New Client', 'iml'),
    'edit_item'          => __('Edit Client', 'iml'),
    'new_item'           => __('New Client', 'iml'),
    'all_items'          => __('All Clients', 'iml'),
    'view_item'          => __('View Client', 'iml'),
    'search_items'       => __('Search Client', 'iml'),
    'not_found'          => __('No Clients available', 'iml'),
    'not_found_in_trash' => __('No Clients found in Trash', 'iml'),
    'parent_item_colon'  => '',
    'menu_name'          => __('My Logos', 'iml')
  );
  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 8,
    'menu_icon'          => IML_DIR_URL_VC . 'files/image/ed-gray.png',
    'supports'           => array( 'title', 'thumbnail' )
  );
    register_post_type( IML_POST_TYPE_VC, $args );
}
////////////TAXONOMY
add_action( 'init', 'imc_taxonomy_clients_vc', 0 );
function imc_taxonomy_clients_vc() {
	$labels = array(
		'name'              => _x( 'Groups', 'taxonomy general name' ),
		'singular_name'     => _x( 'Group', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Groups', 'iml' ),
		'all_items'         => __( 'All Groups', 'iml' ),
		'parent_item'       => __( 'Parent Group', 'iml' ),
		'parent_item_colon' => __( 'Parent Group:', 'iml' ),
		'edit_item'         => __( 'Edit Group', 'iml' ),
		'update_item'       => __( 'Update Group', 'iml' ),
		'add_new_item'      => __( 'Add New Group', 'iml' ),
		'new_item_name'     => __( 'New Group Name', 'iml' ),
		'menu_name'         => __( 'Groups', 'iml' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => IML_TAXONOMY_VC ),
	);
register_taxonomy( IML_TAXONOMY_VC, IML_POST_TYPE_VC, $args );
}
////RENAME FEATURED IMAGE TO LOGO
add_action('do_meta_boxes', 'imc_change_image_box_vc');
function imc_change_image_box_vc(){
    remove_meta_box( 'postimagediv', IML_POST_TYPE_VC, 'side' );
    add_meta_box('postimagediv', __('Logo'), 'post_thumbnail_meta_box', IML_POST_TYPE_VC, 'normal', 'high');
}
add_action('admin_head-post-new.php', 'imc_change_thumbnail_html_vc');
add_action('admin_head-post.php', 'imc_change_thumbnail_html_vc');
function imc_change_thumbnail_html_vc( $content ) {
    if ( isset($GLOBALS['post_type']) && $GLOBALS['post_type']==IML_POST_TYPE_VC )
      add_filter('admin_post_thumbnail_html', 'imc_rename_thumb_vc');
}
function imc_rename_thumb_vc($content){
     return str_replace(__('featured image'), __('Logo', 'iml'),$content);
}

add_action( 'admin_menu', 'imc_shortcode_menu_page_vc' );
function imc_shortcode_menu_page_vc(){
	add_submenu_page( 'edit.php?post_type='.IML_POST_TYPE_VC, __('General Settings', 'iml'), __('General Settings', 'iml'), 'manage_options', 'iml_general_settings_vc', 'iml_general_settings_vc' );
}
$ext_menu = 'edit.php?post_type=' . IML_POST_TYPE_VC;
include_once plugin_dir_path(__FILE__) . 'extensions_plus/index.php';

function iml_general_settings_vc(){
	include IML_DIR_PATH_VC . 'includes/general_settings.php';
}

///IMAGE COLUMN
add_filter('manage_edit-'.IML_POST_TYPE_VC.'_columns', 'imc_img_admin_column_vc');
function imc_img_admin_column_vc($columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = __('Client Name', 'iml');
    $new_columns['logo'] = __('Logo', 'iml');
    $new_columns['taxonomy-'.IML_TAXONOMY_VC] = __('Groups', 'iml');
    $new_columns['date'] = _x('Date', 'column name');
    return $new_columns;
}

/////////CUSTOM FIELD
    //////////INFO
    add_action( 'add_meta_boxes', 'imc_custom_field_be_vc' );
    function imc_custom_field_be_vc(){
        add_meta_box('team_personal_info',
                     __('Information', 'iml'),
                     'imc_metabox_cf_vc', //function available in function.php
                     IML_POST_TYPE_VC,
                     'normal',
                     'low');
    }
    add_action('save_post', 'imc_save_cfvalues_vc');
    
////// SET ADMIN HEADER
add_action("admin_enqueue_scripts", 'imc_admin_header_vc');
function imc_admin_header_vc(){
    $screen = get_current_screen();
    if( $screen->post_type==IML_POST_TYPE_VC ){
    		wp_enqueue_style( 'iml_font-awesome', IML_DIR_URL_VC . 'files/css/font-awesome.min.css', array(), null  );
            wp_enqueue_style( 'iml_style_vc', IML_DIR_URL_VC . 'files/css/style.css', array(), null  );            
            wp_enqueue_style( 'iml_style_front_end_vc', IML_DIR_URL_VC . 'files/css/style-front_end.css', array(), null  );
            wp_enqueue_style( 'iml_owl_carousel_vc', IML_DIR_URL_VC.'files/css/owl.carousel.css' );
        if( function_exists( 'wp_enqueue_media' ) ){
            wp_enqueue_media();
            wp_enqueue_script( 'iml_open_media_3_5_vc', IML_DIR_URL_VC.'files/js/open_media_3_5.js', array(), null );
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'iml_open_media_3_4_vc', IML_DIR_URL_VC.'files/js/open_media_3_4.js', array(), null );
        }
            wp_enqueue_script('jquery');
            wp_enqueue_script( 'iml_jquery_ui_script_vc', IML_DIR_URL_VC . 'files/js/jquery-ui.js', array(), null );
            wp_enqueue_script( 'iml_functions_vc', IML_DIR_URL_VC . 'files/js/functions.js', array(), null  );
            wp_enqueue_script( 'iml_owl_carousel_vc', IML_DIR_URL_VC.'files/js/owl.carousel.js', array(), null );
            wp_enqueue_script ( 'iml_jquery_isotope_vc', IML_DIR_URL_VC.'files/js/isotope.pkgd.min.js', array(), null );
    }
}
function iml_vc_footer_scripts(){
    wp_enqueue_script( 'iml_jquery_ui', IML_DIR_URL_VC . 'files/js/jquery-ui.js', array(), null );
}

add_action('wp_enqueue_scripts', 'imc_front_end_head_vc');
function imc_front_end_head_vc(){
	wp_enqueue_style( 'iml_owl.carousel_vc', IML_DIR_URL_VC . 'files/css/owl.carousel.css' );
    wp_enqueue_style( 'iml_font-awesome', IML_DIR_URL_VC . 'files/css/font-awesome.min.css', array(), null  );
    wp_enqueue_style( 'iml_style_front_end_vc', IML_DIR_URL_VC . 'files/css/style-front_end.css', array(), null  );
    //wp_enqueue_style( 'iml_effect_style', IML_DIR_URL_VC . 'files/css/effect_style.css ');
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'iml_owl_carousel_vc', IML_DIR_URL_VC . 'files/js/owl.carousel.js', array(), null );
    wp_enqueue_script ( 'iml_jquery_isotope_vc', IML_DIR_URL_VC . 'files/js/isotope.pkgd.min.js', array(), null );
}
////////SHORTCODE
add_shortcode( 'indeed-clients', 'imc_shortcode_func_vc' );
function imc_shortcode_func_vc($attr){
    $return_str = true;
    include IML_DIR_PATH_VC . 'includes/imc_view.php';
    return $final_str;
}

#Enable feature image for IML_POST_TYPE
add_action( 'init', 'iml_theme_suport_vc');
function iml_theme_suport_vc(){
	$postTypes = get_theme_support( 'post-thumbnails' );
	if(isset($postTypes) && is_array($postTypes)){
		$postTypes[] = IML_POST_TYPE;
		add_theme_support( 'post-thumbnails', $postTypes );
	}else{
		add_theme_support( 'post-thumbnails' );
	}
}
add_action('manage_posts_custom_column',  'iml_display_columns_vc' );
function iml_display_columns_vc($name) {
	global $post;
	$screen = get_current_screen();
	if( is_plugin_active('indeed-my-logos/indeed-my-logos.php') ) return;
	if($screen->post_type==IML_POST_TYPE_VC){
		switch($name){
	        case 'logo':
	            $logo = '';
	            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false );
	            if($src!='' && isset($src[0]) && $src[0]){
	            	$logo = $src[0];
	            } else {
	            	$logo = iml_return_default_logo_vc();
	            }
	            if ($logo){
	            	echo "<img src='".$logo."' width='80' title='{$post->post_title}'/>";
	            }	            
	        break;
		}
	}
}

///Ajax change post type name
function iml_change_post_type_vc(){
	if(isset($_REQUEST['post_name']) && $_REQUEST['post_name']!=''){
		if(get_option('iml_post_type_slug')!==FALSE) update_option('iml_post_type_slug', $_REQUEST['post_name']);
		else add_option('iml_post_type_slug', $_REQUEST['post_name']);
		echo $_REQUEST['post_name'];
	}
	die();
}
add_action('wp_ajax_iml_change_post_type_vc', 'iml_change_post_type_vc');
add_action('wp_ajax_nopriv_iml_change_post_type_vc', 'iml_change_post_type_vc');
