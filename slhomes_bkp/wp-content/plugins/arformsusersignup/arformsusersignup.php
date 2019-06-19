<?php
/*
Plugin Name: User Signup For ARForms (shared on wplocker.com)
Description: Extension for Arforms plugin for user signup facility and user management
Version: 1.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms-user-registration
*/

define('ARF_USER_REGISTRATION_DIR', WP_PLUGIN_DIR.'/arformsusersignup');

define('ARF_USER_REGISTRATION_URL', WP_PLUGIN_URL.'/arformsusersignup');

load_plugin_textdomain('ARForms-user-registration', false, 'arformsusersignup/languages/' );

global $arf_user_registration;
$arf_user_registration = new ARF_User_Registration();

global $arf_user_registration_version;
$arf_user_registration_version = '1.3';

global $arf_user_registration_shortname;
$arf_user_registration_shortname = 'ARFUS';

class ARF_User_Registration {
	
	function ARF_User_Registration(){
	
		add_action('init', array(&$this, 'arf_user_registration_db_check') );
		
		register_activation_hook(__FILE__, array("ARF_User_Registration", 'install') );
		
		register_uninstall_hook(__FILE__, array("ARF_User_Registration", 'uninstall') );
		
		add_action('admin_menu', array(&$this, 'arf_user_registration_menu'), 27);

		add_action('arfaftercreateentry', array(&$this, 'arf_user_registration_submission'), 99, 2);
		
		add_action('admin_notices', array(&$this, 'arf_user_registration_admin_notices') );
						
		add_action('wp_ajax_arf_user_registration_delete_form', array(&$this, 'arf_user_registration_delete_form') );
		
		add_action('wp_ajax_arf_user_registration_form_bulk_act', array(&$this, 'arf_user_registration_form_bulk_act') );
						
		add_action('admin_enqueue_scripts', array( &$this, 'arf_set_js'), 11);
		
		add_action('admin_enqueue_scripts', array( &$this, 'arf_set_css'), 11);
		
		add_action('wp_ajax_arf_user_registration_field_dropdown', array(&$this, 'arf_user_registration_field_dropdown') );
		
		add_action('wp_ajax_arf_login_form_field_dropdown', array(&$this, 'arf_login_form_field_dropdown') );
		
		add_action('wp_ajax_arf_forgot_pass_form_field_dropdown', array(&$this, 'arf_forgot_pass_form_field_dropdown') );
		
		add_action('wp_ajax_arf_change_pass_form_field_dropdown', array(&$this, 'arf_change_pass_form_field_dropdown') );
		
		add_action('wp_ajax_arf_reset_pass_form_field_dropdown', array(&$this, 'arf_change_pass_form_field_dropdown') );
				
		add_action('wp_ajax_arf_edit_profile_field_dropdown', array(&$this, 'arf_edit_profile_field_dropdown') );
																										 					
		add_action('arfbeforedestroyform', array(&$this, 'arfdelete_user_registration_form'), 11, 1);
				
		add_action('init', array(&$this, 'wp_arfuser_registration_autoupdate'));
		
		add_filter('upgrader_pre_install', array(&$this, 'arfuser_registration_backup'), 10, 2);
		
		add_action('admin_init', array( &$this, 'upgrade_user_registration_data'));
		
		add_action( 'wp_ajax_add_custom_meta_field', array(&$this, 'add_custom_meta_field') );
		
		add_action( 'wp_ajax_add_bp_field', array(&$this, 'add_bp_field') );
		
		add_action( 'arf_predisplay_form', array(&$this, 'arf_validate_field'), 10, 1 );
				
		if( version_compare(self::get_arforms_version(), '2.5.4', '>=') )
		{  		
			add_filter( 'arf_is_validateform_outside', array(&$this, 'arf_validateform_outside'), 10, 2 );
		
			add_filter( 'arf_validate_form_outside_errors', array(&$this, 'arf_prevalidate_field'), 10, 4 );
		}
		else
		{
			add_filter( 'arf_validateform_outside', array(&$this, 'arf_validateform_outside'), 10, 2 );
					
			add_action( 'wp_ajax_arf_prevalidate_field', array(&$this, 'arf_prevalidatefield') );
	
			add_action( 'wp_ajax_nopriv_arf_prevalidate_field', array(&$this, 'arf_prevalidatefield') );		
		}
		
		add_shortcode('ARForms_logout', array(&$this, 'arf_logut_fnc'));
		
		add_shortcode('ARForms_username', array(&$this, 'arf_username_fnc'));
		
		add_filter( 'arfpredisplayform', array(&$this, 'arfcheckpredisplayform'), 10, 1 );		// make reqiured user loggin
		
		add_filter( 'arf_before_create_formentry', array(&$this, 'arfprevententry'), 10, 1 );	// prevent entry creation
		
		add_action( 'arfbeforecreateentry', array(&$this, 'arfdologin'), 10, 1 );				// for login redirect before entry create 
		
		add_filter( 'arfpredisplayformcols', array(&$this, 'arfprechangeformcols'), 10, 2 ); 	// filter entry cols
		
		add_filter( 'arfpredisplaycolsitems', array(&$this, 'arfprechangecolsitems'), 10, 2 );	// filter entry cols data
		
		add_filter( 'arfpredisplayonecol', array(&$this, 'arfprechangeonecol'), 10, 2 ); 		// filter one etry data
		
		add_filter( 'arfsetupnewentry', array(&$this, 'arfchangesetupvar'), 100, 1 );			// change field default values
		
		add_filter( 'widget_text', array(&$this, 'widget_text_filter_logout'), 9 );

		add_filter( 'widget_text', array(&$this, 'widget_text_filter_username'), 9);
		
		add_action('arf_after_paypal_successful_paymnet', array(&$this, 'arf_register_user_after_payment'), 20, 3 ); 
		
		add_action( 'arf_is_resetform_aftersubmit', array(&$this, 'is_resetform_aftersubmit'), 10, 2);
		
		add_action('wp',  array(&$this, 'reset_pass_response'), 5);
	
	}
	
	function reset_pass_response(){
			
			global $wpdb;
			
			if( !self::is_arforms_support() )
			return;
			
			if(!isset($_REQUEST['key']) || !isset($_REQUEST['login']) || empty($_REQUEST['key']) || empty($_REQUEST['login']))
			return;
			
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'arp' && isset($_REQUEST['rspset_id']) && !empty($_REQUEST['rspset_id'])){
				
			$set_id = $_REQUEST['rspset_id'];
			$as_reset_password_data = $wpdb->get_row( $wpdb->prepare('SELECT reset_password_options FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE id = %d and mapped_with = %s', $set_id,'reset_password'));
				if(empty($as_reset_password_data->reset_password_options)){
					return;
				}
				$reset_pass_options = maybe_unserialize($as_reset_password_data->reset_password_options); 
				if(get_the_ID() == trim($reset_pass_options['reset_page_password_form'])){
						$_SESSION['arf_reset_pass'] = $_REQUEST;
						$rec_link =	get_permalink($reset_pass_options['reset_page_password_form']);
						if(strstr($rec_link, "?")){
							$rec_link = $rec_link.'&action=arp';
						}else{
							$rec_link = $rec_link.'?action=arp';
						}
						header('Location:'.$rec_link);
				}else{
					return;	
				}
				
			}else{ 
				return;
			}
	}
	
	function arf_user_registration_db_check(){
		
		$arf_user_registration_version = get_option('arf_user_registration_version');
		
		if( !isset($arf_user_registration_version) || $arf_user_registration_version ==''  )
			self::install();
	}

	function install(){
		$arf_user_registration_version = get_option('arf_user_registration_version');
		
		if( !isset($arf_user_registration_version) || $arf_user_registration_version ==''  ) {
			
			global $wpdb, $arf_user_registration_version;
			
			update_option('arf_user_registration_version', $arf_user_registration_version);	
			
				$logout_option = array();
				
				$logout_option['logout_label'] = __('Logout', 'ARForms-user-registration');
				
				$logout_option['logout_redirect'] = get_home_url();
				
				$logout_option['logout_css'] = '';
					
				$logout_option = maybe_serialize( $logout_option );
				
			update_option('arf_logout_setting', $logout_option);
				
							
			$charset_collate = '';
			
			if( !empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	
			if( !empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
						
						
			$table_name = $wpdb->prefix.'arf_user_registration_forms';		
			
			$sql = "CREATE TABLE {$table_name} (
	
					id int(11) NOT NULL auto_increment,
					
					form_id int(11) NOT NULL,
					
					form_name varchar(255) default NULL,
					
					user_meta longtext default NULL,
					
					options longtext default NULL,
					
					login_options longtext default NULL, 
					
					forgot_password_options	longtext default NULL, 	
					
					change_password_options	longtext default NULL, 	
					
					reset_password_options longtext default NULL, 	
					
					edit_profile_options longtext default NULL, 	
					
					set_id int(11) NOT NULL,
					
					mapped_with varchar(255) default NULL,
					
					created_at datetime NOT NULL,
					 	
					PRIMARY KEY (id)
	
				  ) {$charset_collate};";
				
			dbDelta($sql);
			
			$table_name = $wpdb->prefix.'arf_form_sets';		
			
			$sql = "CREATE TABLE {$table_name} (
	
					id int(11) NOT NULL auto_increment,
					
					name varchar(255) default NULL,
					
					options longtext default NULL,
										
					created_at datetime NOT NULL,
					
					update_at datetime NOT NULL,
					 	
					PRIMARY KEY (id)
	
				  ) {$charset_collate};";
				
			dbDelta($sql);
			
			self::getwpversion();
		}
	}

	function uninstall(){
		global $wpdb;
		if ( is_multisite() ) {		
			$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
			if ($blogs) {
				foreach($blogs as $blog) {
					switch_to_blog($blog['blog_id']);
					
					delete_option('arf_user_registration_version');					
					delete_option('arf_logout_setting');
					$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_user_registration_forms');
					$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_form_sets');
				}
				restore_current_blog();
			}
			
		} else {		
			delete_option('arf_user_registration_version');
			delete_option('arf_logout_setting');
			$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_user_registration_forms');
			$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_form_sets');
		}
		
	}
	

	function arf_user_registration_admin_notices() {
		
		if( !self::is_arforms_support() )
			echo "<div class='updated'><p>".__('User Signup For ARForms plugin requires ARForms Plugin installed and active.', 'ARForms-user-registration')."</p></div>";
			
		else if( !version_compare(self::get_arforms_version(), '2.5.2', '>=') )
			echo "<div class='updated'><p>".__('User Signup For ARForms plugin requires ARForms plugin installed with version 2.5.2 or higher.', 'ARForms-user-registration')."</p></div>";						
	}
	
	function is_arforms_support(){
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		return is_plugin_active( 'arforms/arforms.php' );
		
	}
	
	function is_arforms_paypal_support(){
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		return is_plugin_active( 'arformspaypal/arformspaypal.php' );
		
	}
	
	function get_arforms_version(){
		
		$arf_db_version = get_option('arf_db_version');
		
		return (isset($arf_db_version)) ? $arf_db_version : 0;
	}
	
	function get_arforms_paypal_version(){
		
		$arf_paypal_db_version = get_option('arf_paypal_version');
		
		return (isset($arf_paypal_db_version)) ? $arf_paypal_db_version : 0;
	}

	function route(){
		
		if( isset($_REQUEST['page']) and $_REQUEST['page'] == 'ARForms-user-registration' and isset($_GET['arfaction']) and ( $_GET['arfaction'] == 'new' ) ){
			return self::new_form();		
		} else if( isset($_REQUEST['page']) and $_REQUEST['page'] == 'ARForms-user-registration' and isset($_GET['arfaction']) and ( $_GET['arfaction'] == 'edit' ) ){
			return self::edit_form();
		} else {				
			return self::list_forms();		
		}
		
	}

	function arf_user_registration_menu(){
		
		if( version_compare(self::get_arforms_version(), '2.5.2', '>=') ) {
			
			if(current_user_can('administrator')){
		
				global $current_user;
	
				$paypalcapabilities = array(

				'arfusersignup' => __('Configure Usersignup', 'ARForms-user-registration'),
		
				);
		
				$arfroles = $paypalcapabilities;
	
	
				foreach($arfroles as $arfrole => $arfroledescription)
	
					$current_user->add_cap( $arfrole );
	
	
				unset($arfroles);
	
	
				unset($arfrole);
	
	
				unset($arfroledescription);
	
	
			}
				
			add_submenu_page('ARForms', 'ARForms | '. __('User Signup Configuration', 'ARForms-user-registration'), __('User Signup Configuration', 'ARForms-user-registration'), 'arfusersignup', 'ARForms-user-registration', array('ARF_User_Registration', 'route') );
					
		}		
		
	}
	
	function arf_set_js(){
		
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-user-registration" ) 
		{
			wp_register_script('arfuserregistration-js', ARF_USER_REGISTRATION_URL . '/js/arf_userregistration.js',array('jquery'));
			wp_enqueue_script( 'arfuserregistration-js');
			
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			
			wp_enqueue_script( 'jquery', ARFURL . '/datatables/media/js/jquery.js');
			wp_enqueue_script( 'jquery_dataTables', ARFURL . '/datatables/media/js/jquery.dataTables.js');
			wp_enqueue_script( 'ColVis', ARFURL . '/datatables/media/js/ColVis.js');
			wp_enqueue_script( 'FixedColumns', ARFURL . '/datatables/media/js/FixedColumns.js');
			wp_enqueue_script( 'jqplot_min',ARFURL . '/js/linechart/jquery.jqplot.min.js');
			wp_enqueue_script( 'barRenderer_min',ARFURL . '/js/linechart/jqplot.barRenderer.min.js');
			wp_enqueue_script( 'logAxisRenderer_min',ARFURL . '/js/linechart/jqplot.logAxisRenderer.min.js');	
			wp_enqueue_script( 'canvasTextRenderer_min',ARFURL . '/js/linechart/jqplot.canvasTextRenderer.min.js');	
			wp_enqueue_script( 'canvasAxisLabelRenderer_min',ARFURL . '/js/linechart/jqplot.canvasAxisLabelRenderer.min.js');	
			wp_enqueue_script( 'canvasAxisTickRenderer_min',ARFURL . '/js/linechart/jqplot.canvasAxisTickRenderer.min.js');	
			wp_enqueue_script( 'dateAxisRenderer_min',ARFURL . '/js/linechart/jqplot.dateAxisRenderer.min.js');	
			wp_enqueue_script( 'categoryAxisRenderer_min',ARFURL . '/js/linechart/jqplot.categoryAxisRenderer.min.js');		
			wp_enqueue_script( 'highlighter_min',ARFURL . '/js/linechart/jqplot.highlighter.min.js');
			?>
	        <!--[if lt IE 9]><script language="javascript" type="text/javascript" src='<?php echo ARFURL;?>/js/linechart/jquery.excanvas.min.js'"></script><![endif]-->
			<?php
			wp_register_script('arf_tooltipster', ARFURL.'/js/jquery.tooltipster.js', array('jquery'));
			wp_enqueue_script('arf_tooltipster');
		}
	}

	function arf_set_css(){
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-user-registration" ) 
		{
			wp_register_style('arfuserregistration-css', ARF_USER_REGISTRATION_URL . '/css/arf_userregistration.css');
			wp_enqueue_style('arfuserregistration-css');
			
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css');
			wp_enqueue_style('jqplot_min_css',ARFURL . '/js/linechart/jquery.jqplot.min.css');
			
			wp_register_style('arf_tooltipster_css', ARFURL.'/css/tooltipster.css');
			wp_enqueue_style('arf_tooltipster_css');		
		}
	}

	function arf_user_registration_submission($entry_id, $form_id){
		
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM  '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
				
		if( !$form_data || count($form_data) < 1 )
			return;
		
		$form_data 	= $form_data[0];
		
		if( $form_data->mapped_with == 'user_registration' )
			self::arf_register_user($entry_id, $form_id, $form_data);
		else if( $form_data->mapped_with == 'login' )
			self::arf_user_login($entry_id, $form_id, $form_data);
		else if( $form_data->mapped_with == 'forgot_password' )
			self::arf_fogot_password($entry_id, $form_id, $form_data);		
		else if( $form_data->mapped_with == 'change_password')
			self::arf_change_password($entry_id, $form_id, $form_data);
		else if( $form_data->mapped_with == 'reset_password')
			self::arf_reset_password($entry_id, $form_id, $form_data);
		else if( $form_data->mapped_with == 'edit_profile')
			self::arf_edit_profile($entry_id, $form_id, $form_data);
				
		return;			 		 
	}
	
	function arf_register_user($entry_id, $form_id, $form_data)
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
	
		// if paypal configured with arforms	
		if( self::is_arforms_paypal_support() && version_compare(self::get_arforms_paypal_version(), '1.2', '>=') )
		{
			$paypal_form	= $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_paypal_forms WHERE form_id = %d", $form_id) );
			
			if( $paypal_form )
			{
				$options = maybe_unserialize( $paypal_form->options );
								
				// check for conditional logic
				if( $options['paypal_condition'] && $options['paypal_condition'] == 1 )
				{	
					global $arfrecordmeta, $wpdb;
					$entry_ids = array( $entry_id );
					$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
				
					$mapped_conditional_field = array();
					$rules = $options['conditional_logic']['rules'] ? $options['conditional_logic']['rules'] : array();				
					if( $rules )
					{
						foreach( $rules as $rule )
						{
							if( $rule['field_id'] != '' )
								$mapped_conditional_field[] = $rule['field_id']; 													
						}				
					}				  
				
					$mapped_field_values = array();
					if( count($values) > 0 ){
						foreach( $values as $value ){				
							
							if( $mapped_conditional_field )
							{
								foreach($mapped_conditional_field as $rule_field)
								{
									if( $rule_field == $value->field_id )
										$mapped_field_values[$value->field_id] = $value->entry_value;  								
								}
							}
							
							foreach($paypal_fields as $paypal_field_key => $paypal_field_id ){
								if( $value->field_id == $paypal_field_id )
									$paypal_values[$paypal_field_key] = $value->entry_value; 	
							}
													 
						}
					}
					
					$mapped_conditional_field = array();
				
					$rules = $options['conditional_logic']['rules'] ? $options['conditional_logic']['rules'] : array();
					
					if( $rules )
					{
						$matched = 0;
						$total_rules = 0;
						
						foreach( $rules as $rule )
						{
							if( $rule['field_id'] != '' )
							{
								$total_rules++;
								
								$value1 = isset( $mapped_field_values[ $rule['field_id'] ] ) ? $mapped_field_values[ $rule['field_id'] ] : '';
								
								$value1 = trim( strtolower($value1) );
			
								$value2 = trim( strtolower( $rule['value'] ) );
								
								$operator = $rule['operator'];
								
								if( ARF_Paypal::calculate_rule($value1, $value2, $operator, $rule['field_type']) )
									$matched++;								
							}
								
						}
						
						// check if condtion matched
						if( ($options['conditional_logic']['if_cond'] == 'all' && $total_rules == $matched) || ($options['conditional_logic']['if_cond'] == 'any' && $matched > 0) )
						{
							// condition matched
							return;
						}
						else
						{
							// condition not matched
							//return false;
						}
						
					} else {
						// configured form with paypal also, so create user after paypament
						return;
					}
					  
				} else {				
					// configured form with paypal also, so create user after paypament
					return;
				}
				
			}
		}
		
		$options = maybe_unserialize( $form_data->options ); 
		
		$meta_options = maybe_unserialize( $form_data->user_meta ); 
		
		if( empty($options['username']) || empty($options['email']) )
			return; 
					
		$mapped_field = array();
		
		$mapped_field[] = $options['username'];
		$mapped_field[] = $options['first_name'];
		$mapped_field[] = $options['last_name'];
		$mapped_field[] = $options['display_name'];
		$mapped_field[] = $options['email'];
		$mapped_field[] = $options['password'];
		
		if( count($meta_options) > 0 )
		{
			foreach( $meta_options as $meta_arr )
			{
				if( $meta_arr['meta_value'] != '' )
					$mapped_field[] = $meta_arr['meta_value']; 	
			}			
		}
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$user_name 	= @$field_values[ $options['username'] ];
		$user_email = @$field_values[ $options['email'] ];
		$password	= @$field_values[ $options['password'] ];
		 
		if( empty($user_name) || empty($user_email) )
			return; 
		
		// if username not exists
		if( !username_exists( $user_name ) && !email_exists( $user_email ) )
		{
		
			if( !empty($password) )
			{
				$user_id = wp_create_user($user_name, $password, $user_email);
				if(is_wp_error($user_id))
                	return;
				
				ARF_User_Registration::set_user_meta($user_id, $form_data, $field_values, $entry_id);
			}
			else
			{
				$password 	= wp_generate_password();
				$user_id 	= wp_create_user($user_name, $password, $user_email);
				if(is_wp_error($user_id))
                	return;
			
				ARF_User_Registration::set_user_meta($user_id, $form_data, $field_values, $entry_id);
			}
			
			// set role to user
			if( $user_id != '' && $options['role'] != '' )
			{
				$user = new WP_User($user_id);
				global $wp_roles;
				$all_roles = array_keys($wp_roles->roles);
				
				if( ! in_array($options['role'], $all_roles ) )
					$options['role'] = 'subscriber';
					
            	$user->set_role( $options['role'] );
			}
			
			//send password to user 
			if( isset($options['notification']) && $options['notification'] == 1 )
				wp_new_user_notification($user_id, $password);
			
			if( isset($options['auto_login']) && $options['auto_login'] == 1 )
			{
				$credentials = array();
				$credentials['user_login'] = $user_name;
				$credentials['user_password'] = $password;
				$credentials['remember'] = false;
				
				$result = wp_signon( $credentials );
				
				$re_url = $options['redirect_url'];
				
				if( empty($re_url) )
					$re_url = get_home_url();
				
				if( !is_wp_error($result) )
				{
					if($arfsettings->form_submit_type == 1)
						echo '^conf_method=addon|^|';	
						
					echo '<script type="text/javascript" language="javascript">location.href="'.$re_url.'";</script>';
					
					if($arfsettings->form_submit_type == 1)
						echo '^conf_method=';
					
					exit;
				}
					
			}	
											
		}
		else
		{
			//if username already exists.
		}
	}
	
	function arf_user_login($entry_id, $form_id, $form_data)
	{
		
		global $wpdb, $arfrecordmeta, $arfsettings;
	
		$options = maybe_unserialize( $form_data->login_options ); 
		
		if( $options['login_entry_enable'] == 0 )
			return; 
				
		if( empty($options['username']) || empty($options['password']) )
			return; 
					
		$mapped_field = array();
		
		$mapped_field[] = $options['username'];
		$mapped_field[] = $options['password'];
		$mapped_field[] = $options['remember'];
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$user_name 	= @$field_values[ $options['username'] ];
		$password	= @$field_values[ $options['password'] ];
		 
		if( empty($user_name) || empty($password) )
			return; 
		
		$credentials = array();
		$credentials['user_login'] 		= $user_name;
		$credentials['user_password'] 	= $password;
		$credentials['remember'] 		= ( isset($field_values[ $options['remember'] ]) and !empty($field_values[ $options['remember'] ]) ) ? true : false;
		$result = wp_signon( $credentials );
		
		$re_url = $options['redirect_url'];
		
		if( empty($re_url) )
			$re_url = get_home_url();
		
		if( !is_wp_error($result) )
		{
			if($arfsettings->form_submit_type == 1)
				echo '^conf_method=addon|^|';	
				
			echo '<script type="text/javascript" language="javascript">location.href="'.$re_url.'";</script>';
			
			if($arfsettings->form_submit_type == 1)
				echo '^conf_method=';
							
			exit;
		}
					
	}
	
	function arf_fogot_password($entry_id, $form_id, $form_data)
	{
		
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		$options = maybe_unserialize( $form_data->forgot_password_options ); 
		
		if( $options['forgot_entry_enable'] == 0 )
			return; 
			
		if( empty($options['username']) )
			return; 
					
		$mapped_field = array();
		
		$mapped_field[] = $options['username'];
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$user_name 	= $field_values[ $options['username'] ];
		 
		if( empty($user_name) )
			return;
		
		$user_login = $user_name; 
		
		global $wpdb, $current_site;

		if ( empty( $user_login ) ) {
			return false;
		} else if ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $user_login ) );
			if ( empty( $user_data ) )
			   return false;
		} else {
			$login = trim($user_login);
			$user_data = get_user_by('login', $login);
		}
	
		do_action('lostpassword_post');
	
		if ( !$user_data ) return false;
	
		// redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
	
		do_action('retreive_password', $user_login);  // Misspelled and deprecated
		do_action('retrieve_password', $user_login);
		
	
		$allow = apply_filters('allow_password_reset', true, $user_data->ID);
	
		if ( ! $allow )
			return false;
		else if ( is_wp_error($allow) )
			return false;
	
		$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
		if ( empty($key) ) {
			// Generate something random for a key...
			$key = wp_generate_password(20, false);
			do_action('retrieve_password_key', $user_login, $key);
			// Now insert the new md5 key into the db
			$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
		}
		$message = __('Someone requested that the password be reset for the following account:','ARForms-user-registration') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s','ARForms-user-registration'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.','ARForms-user-registration') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:','ARForms-user-registration') . "\r\n\r\n";
		
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
		
		 
	
		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
		$title = sprintf( __('[%s] Password Reset','ARForms-user-registration'), $blogname );
	
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);
		
		@wp_mail($user_email, $title, $message);
		
	
	}
	
	
	
	function arf_reset_password($entry_id, $form_id, $form_data)
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		if(isset($_SESSION['arf_reset_pass']['login']) && !empty($_SESSION['arf_reset_pass']['login'])){
			$userlogin = $_SESSION['arf_reset_pass']['login'];	
		}else{
			$_SESSION['arf_reset_pass'] = '';
			return;
		}
		
		
		if(is_user_logged_in())
			return; 
		
		$options = maybe_unserialize( $form_data->reset_password_options ); 
		
		if( $options['reset_pass_entry_enable'] == 0 )
			return; 	
			
		if(empty($options['reset_new_password']))
			return; 
					
		$mapped_field = array();
		
		$mapped_field[] = $options['reset_new_password'];
		
		$mapped_field =  array_unique($mapped_field);
		
		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
		
		//user details
		$new_password 	=   @$field_values[$options['reset_new_password']];
		
		if(trim($new_password) == ''){
			return; 
		}
		
		$user = get_user_by('login',$userlogin);
		if(empty($user->ID))
			return; 
		
		wp_set_password($new_password, $user->ID);
		$_SESSION['arf_reset_pass'] = '';	
		
		
		return;
	}
	
	
	
	function arf_change_password($entry_id, $form_id, $form_data)
	{	
		global $wpdb, $arfrecordmeta, $arfsettings;
	
		$options = maybe_unserialize( $form_data->change_password_options ); 
		
		if( $options['changepass_entry_enable'] == 0 )
			return; 
			
		if( empty($options['new_password']) )
			return; 
					
		$mapped_field = array();
		
		$mapped_field[] = $options['new_password'];
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$new_password 	= @$field_values[ $options['new_password'] ];
		 
		if( empty($new_password) || !is_user_logged_in() )
			return;
		
		global $current_user;
		
		$username 	= $current_user->data->user_login; 
		$user_id	= $current_user->ID;
		
		$userdata 	= new WP_User( $user_id );
        $user 		= get_object_vars( $userdata->data );
		
		$user['user_pass'] = $new_password;
				
		$user_id = wp_update_user($user);
					
		if( is_wp_error($user_id) )
			return;
		
		if( isset($options['notification']) && $options['notification'] == 1 )	
			wp_password_change_notification( $current_user );
		
	}
	
	function arf_edit_profile($entry_id, $form_id, $form_data)
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
	
		$options = maybe_unserialize( $form_data->edit_profile_options ); 
		
		if( $options['edit_profile_enable'] == 0 )
			return; 
			
		$meta_options = maybe_unserialize( $form_data->user_meta ); 
		
		if( !is_user_logged_in() )
			return;
								
		$mapped_field = array();
		
		$mapped_field[] = $options['first_name'];
		$mapped_field[] = $options['last_name'];
		$mapped_field[] = $options['display_name'];
		$mapped_field[] = $options['email'];
		$mapped_field[] = $options['password'];
		
		if( count($meta_options) > 0 )
		{
			foreach( $meta_options as $meta_arr )
			{
				if( $meta_arr['meta_value'] != '' )
					$mapped_field[] = $meta_arr['meta_value']; 	
			}			
		}
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$user_email 	= @$field_values[ $options['email'] ];
		$password		= @$field_values[ $options['password'] ];
		
		global $current_user;
		
		$username 	= $current_user->data->user_login; 
		$user_id	= $current_user->ID;
		
		$userdata 	= new WP_User( $user_id );
        $user 		= get_object_vars( $userdata->data );
		
		if( isset($password) && $password != '' )
			$user['user_pass'] = $password;
		else
			unset( $user['user_pass'] );	
		
		if( isset($user_email) && $user_email != '' && $user['user_email'] != $user_email && !email_exists( $user_email ) )
			$user['user_email'] = $user_email;	
						
		$user_id = wp_update_user($user);
				
		self::set_user_meta($user_id, $form_data, $field_values, $entry_id, true);	//update user meta.
				
	}
	

	function set_user_meta($user_id, $form_data, $field_values, $entry_id, $is_update_profile = false)
	{
	
		if(!$user_id)
            return;
		
		if( $is_update_profile )
			$options = maybe_unserialize( $form_data->edit_profile_options ); 		
		else	
			$options = maybe_unserialize( $form_data->options ); 
		
		$meta_options = maybe_unserialize( $form_data->user_meta ); 
		
		if( $is_update_profile )
			update_user_meta($user_id, 'arf_update_entry_id', $entry_id);		
		else	
			update_user_meta($user_id, 'arf_entry_id', $entry_id);
		
		//for standard meta
		$metas_array = array(
						'first_name' 	=> $options['first_name'],
						'last_name'		=> $options['last_name'],
						'display_name'	=> $options['display_name'],
						);
		
		foreach($metas_array as $meta_key => $meta_id )
		{
			if( isset($field_values[ $meta_id ]) )
			{
				if( $meta_key == 'display_name' && !empty($field_values[ $meta_id ]) )
					ARF_User_Registration::change_user_property($user_id, 'display_name', $field_values[ $meta_id ]);
				else if( !empty($field_values[ $meta_id ]) )	
					update_user_meta($user_id, $meta_key,  $field_values[ $meta_id ] );
			}
		}
		
		//for update custom_meta
		if( count($meta_options) > 0 )
		{
			foreach( $meta_options as $meta_arr )
			{
				if( $meta_arr['meta_value'] != '' )
				{
					$meta_key 	= $meta_arr['meta_name'];
					$meta_value = isset($field_values[ $meta_arr['meta_value'] ]) ? $field_values[ $meta_arr['meta_value'] ] : '';  
				
					if($meta_key == 'user_url' && $meta_value) {
						ARF_User_Registration::change_user_property($user_id, 'user_url', $meta_value);
					}
					else if( empty($meta_value) ) {
						delete_user_meta($user_id, $meta_key);
					}
					else{
						update_user_meta($user_id, $meta_key, $meta_value);
					}

				}
										
			}			
		}
		
		// for buddypress profile fields.
		$bp_fields = $options['bp_fields']; 
		if( $bp_fields && self::is_buddypress_active() )
		{
			global $wpdb, $bp, $arfrecordmeta;
        	require_once(WP_PLUGIN_DIR . '/buddypress/bp-xprofile/bp-xprofile-functions.php');

        	$table = $bp->profile->table_name_data;
			
			$entry_ids = array( $entry_id );
			$arf_values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
			
			$arfbp_fields = array();
			if( count($bp_fields) > 0 )
			{
				foreach( $bp_fields as $bpfield )
				{
					if( $bpfield['field_value'] != '' )
						$arfbp_fields[] = $bpfield['field_value']; 	
				}			
			}
			
			$arfbp_values = array();
			if( $arf_values && $arfbp_fields )
			{
				foreach($arf_values as $arffield)
				{
					if( in_array($arffield->field_id, $arfbp_fields) )
						$arfbp_values[ $arffield->field_id ] = isset($arffield->entry_value) ? $arffield->entry_value : ''; 
				}
			}
			
			foreach($bp_fields as $bpfield)
			{						
				if( ! $bpfield['field_name'] || ! $bpfield['field_value'] )
					continue;
					
				$bp_field_value = isset($arfbp_values[ $bpfield['field_value'] ]) ? $arfbp_values[ $bpfield['field_value'] ] : '';
				
				if(version_compare(BP_VERSION, '1.6', '<')) {
					$arfbp_field = new BP_XProfile_Field();
					$arfbp_field->bp_xprofile_field($bpfield['field_name']);
				} else {
					require_once(WP_PLUGIN_DIR . '/buddypress/bp-xprofile/bp-xprofile-classes.php');
					$arfbp_field = new BP_XProfile_Field($bpfield['field_name']);
				}
				
				if($arfbp_field->type == 'datebox' )
				{
					$date = $bp_field_value;  
					if (preg_match('/^\d{1-2}\/\d{1-2}\/\d{4}$/', $date)){ 
						global $style_settings;
            			$date = armainhelper::convert_date($date, $style_settings->date_format, 'Y-m-d');
        			}		
					$date = str_replace('/', '-', $date);
					
					$bp_field_value = @strtotime($date);
				}
				
				xprofile_set_field_data($bpfield['field_name'], $user_id, maybe_unserialize($bp_field_value) );												
			}	
		}
		
	}
	
	function change_user_property($user_id, $field_name, $field_value)
	{	
		if(!$user_id)
            return;
					
		$user = new WP_User($user_id);
        $userdata = $user->data;

        $new_userdata = new stdClass();
        $new_userdata->ID = $userdata->ID;
        $new_userdata->$field_name = $field_value;

        $res = wp_update_user(get_object_vars($new_userdata));
		
	}
		
	function list_forms($message = ''){
	
		require(ARF_USER_REGISTRATION_DIR.'/core/list_forms.php');	
	}
	
	function arf_user_registration_delete_form(){
		$id = $_POST['id'];
		$action = $_POST['act'];
		if( $action == 'delete' && $id ){
			$res = self::delete_set_forms( $id );
			$message = __('Record is deleted successfully.', 'ARForms-user-registration');
			$errors = array();
			self::arf_user_registration_forms($message, $errors);
		}
	die();	
	}
	
	function delete_forms($id = 0){
		if( $id == 0 )
			return;
		
		if( $id ){
			global $wpdb;
						
			$res = $wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
			
			return $res;
		}	
	}
	
	function delete_set_forms($set_id = 0)
	{
		if( $set_id == 0 )
			return;
		
		if( $set_id ){
			global $wpdb;						
			$result	= $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			if( $result )
			{
				$result = $result[0];
				$options= maybe_unserialize($result->options);
				if( count($options['form_list']) > 0 )
				{
					foreach($options['form_list'] as $mapped => $formid)
						self::delete_forms( $formid );	
				}	
			}
			$res 	= $wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			return $res;
		}
	}

	function arf_user_registration_form_bulk_act(){
		
        if(!isset($_POST)) return;

        global $arfform;

        $bulkaction = armainhelper::get_param('action1');
		
		$message = '';
		
		$errors = array();
		
        if($bulkaction == -1)

        $bulkaction = armainhelper::get_param('action2');


        if(!empty($bulkaction) and strpos($bulkaction, 'bulk_') === 0){

            if(isset($_GET) and isset($_GET['action1']))

                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action1'], '', $_SERVER['REQUEST_URI']);

            if(isset($_GET) and isset($_GET['action2']))

                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);

            $bulkaction = str_replace('bulk_', '', $bulkaction);

        }else{

            $bulkaction = '-1';

            if(isset($_POST['bulkaction']) and $_POST['bulkaction1'] != '-1')

                $bulkaction = $_POST['bulkaction1'];

            else if(isset($_POST['bulkaction2']) and $_POST['bulkaction2'] != '-1')

                $bulkaction = $_POST['bulkaction2'];
        }

        $ids = armainhelper::get_param('item-action', '');

        if (empty($ids)){
            $errors[] = __('Please select one or more records.', 'ARForms-user-registration');
        }else{                
			if(!is_array($ids))
				$ids = explode(',', $ids);

			if(is_array($ids)){
				if($bulkaction == 'delete'){
				
					foreach($ids as $fid)
						$res_var = self::delete_set_forms($fid);
				
					if($res_var) { $message = __('Record is deleted successfully.', 'ARForms-user-registration'); }
					
				}

			}

        }
		
		self::arf_user_registration_forms($message, $errors);		
    
	die();
	}
	
	
	function arf_user_registration_forms($message='', $errors = array()){
		global $wpdb;
		$actions['bulk_delete'] = __('Delete', 'ARForms-user-registration');
		?>
<script type="text/javascript" charset="utf-8">
// <![CDATA[
jQuery(document).ready( function () {
	jQuery.fn.dataTableExt.oPagination.four_button = {
	
	"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
	{	
		nFirst = document.createElement( 'span' );
		nPrevious = document.createElement( 'span' );
		
		
		
		var nInput = document.createElement( 'input' );
		var nPage = document.createElement( 'span' );
		var nOf = document.createElement( 'span' );
		nOf.className = "paginate_of";
		nInput.className = "current_page_no";
		nPage.className = "paginate_page";
		nInput.type = "text";
		nInput.style.width = "40px";
		nInput.style.height = "26px";
		nInput.style.display = "inline";
		
		 
		nPaging.appendChild( nPage );
		
		
		 
		jQuery(nInput).keyup( function (e) {
					 
			if ( e.which == 38 || e.which == 39 )
			{
				this.value++;
			}
			else if ( (e.which == 37 || e.which == 40) && this.value > 1 )
			{
				this.value--;
			}
 
			if ( this.value == "" || this.value.match(/[^0-9]/) )
			{
				
				return;
			}
 
			var iNewStart = oSettings._iDisplayLength * (this.value - 1);
			if ( iNewStart > oSettings.fnRecordsDisplay() )
			{
				
				oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay()-1) /
					oSettings._iDisplayLength)-1) * oSettings._iDisplayLength;
				fnCallbackDraw( oSettings );
				return;
			}
 
			oSettings._iDisplayStart = iNewStart;
			fnCallbackDraw( oSettings );
		} );
 
		
		
		
		nNext = document.createElement( 'span' );
		nLast = document.createElement( 'span' );
		var nFirst = document.createElement( 'span' );
		var nPrevious = document.createElement( 'span' );
		var nPage = document.createElement( 'span' );
		var nOf = document.createElement( 'span' );
		
		nNext.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/next_normal-icon.png')";
		nNext.style.backgroundRepeat = "no-repeat";
		nNext.style.backgroundPosition = "center";
		nNext.title = "Next";
		
		nLast.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/last_normal-icon.png')";
		nLast.style.backgroundRepeat = "no-repeat";
		nLast.style.backgroundPosition = "center";
		nLast.title = "Last";
		
		nFirst.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/first_normal-icon.png')";
		nFirst.style.backgroundRepeat = "no-repeat";
		nFirst.style.backgroundPosition = "center";
		nFirst.title = "First";
		
		nPrevious.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/previous_normal-icon.png')";
		nPrevious.style.backgroundRepeat = "no-repeat";
		nPrevious.style.backgroundPosition = "center";		
		nPrevious.title = "Previous";		
		
		nFirst.appendChild( document.createTextNode( ' ' ) );
		nPrevious.appendChild( document.createTextNode( ' ' ) );
		
		nLast.appendChild( document.createTextNode( ' ' ) );
		nNext.appendChild( document.createTextNode( ' ' ) );
		
		 
		nOf.className = "paginate_button nof";
		 
		nPaging.appendChild( nFirst );
		nPaging.appendChild( nPrevious );
		
		nPaging.appendChild( nInput );
		nPaging.appendChild( nOf );
		
		nPaging.appendChild( nNext );
		nPaging.appendChild( nLast );
		 
		jQuery(nFirst).click( function () {
			oSettings.oApi._fnPageChange( oSettings, "first" );
			fnCallbackDraw( oSettings );
		} );
		 
		jQuery(nPrevious).click( function() {
			oSettings.oApi._fnPageChange( oSettings, "previous" );
			fnCallbackDraw( oSettings );
		} );
		 
		jQuery(nNext).click( function() {
			oSettings.oApi._fnPageChange( oSettings, "next" );
			fnCallbackDraw( oSettings );
		} );
		 
		jQuery(nLast).click( function() {
			oSettings.oApi._fnPageChange( oSettings, "last" );
			fnCallbackDraw( oSettings );
		} );
		 
		
		jQuery(nFirst).bind( 'selectstart', function () { return false; } );
		jQuery(nPrevious).bind( 'selectstart', function () { return false; } );
		jQuery('span', nPaging).bind( 'mousedown', function () { return false; } );
		jQuery('span', nPaging).bind( 'selectstart', function () { return false; } );
		jQuery(nNext).bind( 'selectstart', function () { return false; } );
		jQuery(nLast).bind( 'selectstart', function () { return false; } );
	},
	 
	
	"fnUpdate": function ( oSettings, fnCallbackDraw )
	{
		if ( !oSettings.aanFeatures.p )
		{
			return;
		}
		 
		
		var an = oSettings.aanFeatures.p;
		for ( var i=0, iLen=an.length ; i<iLen ; i++ )
		{
			var buttons = an[i].getElementsByTagName('span');
			
			
			if ( oSettings._iDisplayStart === 0 )
			{
				
				buttons[1].className = "paginate_disabled_first arfhelptip";
				buttons[2].className = "paginate_disabled_previous arfhelptip";
			}
			else
			{
				
				buttons[1].className = "paginate_enabled_first arfhelptip";
				buttons[2].className = "paginate_enabled_previous arfhelptip";
			}

			if ( oSettings.fnDisplayEnd() == oSettings.fnRecordsDisplay() )
			{
				buttons[4].className = "paginate_disabled_next arfhelptip";
				buttons[5].className = "paginate_disabled_last arfhelptip";
			}
			else
			{
				
				buttons[4].className = "paginate_enabled_next arfhelptip";
				buttons[5].className = "paginate_enabled_last arfhelptip";
			}


			
			if ( !oSettings.aanFeatures.p )
			{
				return;
			}
			var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
			var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
			
			if(document.getElementById('of_grid'))
				of_grid = document.getElementById('of_grid').value;
			else
				of_grid = 'of';
			
			var an = oSettings.aanFeatures.p;
			for ( var i=0, iLen=an.length ; i<iLen ; i++ )
			{
				var spans = an[i].getElementsByTagName('span');
				var inputs = an[i].getElementsByTagName('input');
				spans[spans.length-3].innerHTML =" "+of_grid+" "+iPages
				inputs[0].value = iCurrentPage;
			}
		}
	}
}
	
jQuery('#example').dataTable( {
	"sDom": '<"H"lfr>t<"footer"ip>',	//<"H"lCfr>t<"footer"ip>
	"sPaginationType": "four_button",
	"bJQueryUI": true,
	"bPaginate": true,
	"bAutoWidth" : false,					
	"aoColumnDefs": [
		{ "bVisible": false, "aTargets": [] },
		{ "bSortable": false, "aTargets": [ 0, 5 ] }
	],
	"oColVis": {
	   "aiExclude": [ 0, 5 ]
	},
	
	});
});
		
// ]]>

jQuery(document).ready( function () { 	

jQuery("#cb-select-all-1").click(function () {
	  jQuery('input[name="item-action[]"]').attr('checked', this.checked);
});


jQuery('input[name="item-action[]"]').click(function(){

	if(jQuery('input[name="item-action[]"]').length == jQuery('input[name="item-action[]"]:checked').length) {
		jQuery("#cb-select-all-1").attr("checked", "checked");
	} else {
		jQuery("#cb-select-all-1").removeAttr("checked");
	}

});

});
						
</script>

<?php
	if (isset($message) && $message != ''){ if(is_admin()){ ?><div id="success_message" style="margin-bottom:0px; margin-top:15px; width:95%;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php } echo $message; if(is_admin()){ ?></div></div><?php } }
	
	if( isset($errors) && is_array($errors) && count($errors) > 0 ){ ?>	
	<div style="margin-bottom:0px; margin-top:8px;">	
		<ul id="frm_errors" style="margin-bottom: 0px; margin-top: 0px;">	
			<?php foreach( $errors as $error )
				echo '<li><div class="arferrmsgicon"></div><div id="error_message">' . stripslashes($error) . '</div></li>';
			?>	
		</ul>	
	</div>
<?php } ?>

<div style="position:absolute;right:50px;">
    <button class="greensavebtn" type="button" onclick="location.href='<?php echo admin_url('admin.php?page=ARForms-user-registration&arfaction=new');?>';" style="width:190px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/plus-icon.png">&nbsp;&nbsp;<?php _e('Configure New Set', 'ARForms-user-registration') ?></button>
</div>

<div class="alignleft actions">
    <?php 
    $two = '1';
    echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";
    echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms-user-registration') . "</option>\n";

    foreach ( $actions as $name => $title ) {
        $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';

        echo "\t<option value='$name'$class>$title</option>\n";
    }

    echo "</select></div>\n";
    
    echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__('Apply','ARForms-user-registration').'" />';
    echo "\n";
    
    ?>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
  <thead>
    <tr>
        <th class="center" style="width:50px;"><div style="display:inline-block; position:relative;"><input id="cb-select-all-1" type="checkbox" class="chkstanard"><label for="cb-select-all-1"  class="cb-select-all"><span></span></label></div></th>
        <th class=""><?php _e('Sr. No.', 'ARForms-user-registration'); ?></th>                
        <th class=""><?php _e('Name', 'ARForms-user-registration'); ?></th>
        <th class=""><?php _e('Mapped With', 'ARForms-user-registration'); ?></th>
        <th class=""><?php _e('Update Date', 'ARForms-user-registration'); ?></th>
        <th class="col_action" style="width:110px;"><?php _e('Action', 'ARForms-user-registration'); ?></th>
    </tr> 
  </thead>
  </tbody>        
<?php
$forms = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."arf_form_sets ORDER BY id DESC" );	    
if( count($forms) > 0 ){
$i = 1;
foreach( $forms as $form ){ 
	$options = maybe_unserialize($form->options);
	$mapped_str = "";
	if( $options['form_list'] and count($options['form_list']) > 0 )
	{
		foreach($options['form_list'] as $mapped => $formid)
		{
			if( ARF_User_Registration::get_mapped_form_name( $formid ) != '' )		
				$mapped_str .= ARF_User_Registration::get_mapped_form_name( $formid ).", "; 
		}
	}
	$mapped_str = rtrim($mapped_str, ", ");
	?>     
    <tr>
        <td class="center"><input id="cb-item-action-<?php echo $form->id;?>" class="chkstanard" type="checkbox" value="<?php echo $form->id;?>" name="item-action[]"><label for="cb-item-action-<?php echo $form->id;?>"><span></span></label></td>
        <td><?php echo $i; ?></td>                
        <td class="form_name"><a class="row-title" href="<?php echo wp_nonce_url( "?page=ARForms-user-registration&arfaction=edit&set_id={$form->id}" ); ?>"><?php echo $form->name; ?></a></td>
        <td class=""><?php echo $mapped_str; ?></td>
        <td><?php echo date(get_option('date_format'), strtotime($form->update_at)); ?></td>                
        <td><?php 
				$edit_link = "?page=ARForms-user-registration&arfaction=edit&set_id={$form->id}";
					
				   echo "<a href='" . wp_nonce_url( $edit_link ) . "'><img src='".ARFIMAGESURL."/edit-icon22.png' onmouseover=\"this.src='".ARFIMAGESURL."/edit-icon_hover22.png';\" class='arfhelptip' title='".__('Edit Configuration','ARForms-user-registration')."' onmouseout=\"this.src='".ARFIMAGESURL."/edit-icon22.png';\" /></a>";
				                                                              
                   echo "<img src='".ARFIMAGESURL."/delete_icon223.png' title=".__("Delete","ARForms-user-registration")." class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon223_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon223.png';\" onclick=\"arf_ChangeID({$form->id})\" data-toggle='arfmodal' href='#delete_form_message' style='cursor:pointer'/></a>";

         ?></td>
    </tr>
<?php $i++; } } ?>
    </tbody>     
</table>
    <div class="clear"></div>
    <input type="hidden" name="show_hide_columns" id="show_hide_columns" value="<?php _e('Show / Hide columns','ARForms-user-registration');?>"/>
    <input type="hidden" name="search_grid" id="search_grid" value="<?php _e('Search','ARForms-user-registration');?>"/>
    <input type="hidden" name="entries_grid" id="entries_grid" value="<?php _e('entries','ARForms-user-registration');?>"/>
    <input type="hidden" name="show_grid" id="show_grid" value="<?php _e('Show','ARForms-user-registration');?>"/>
    <input type="hidden" name="showing_grid" id="showing_grid" value="<?php _e('Showing','ARForms-user-registration');?>"/>
    <input type="hidden" name="to_grid" id="to_grid" value="<?php _e('to','ARForms-user-registration');?>"/>
    <input type="hidden" name="of_grid" id="of_grid" value="<?php _e('of','ARForms-user-registration');?>"/>
    <input type="hidden" name="no_match_record_grid" id="no_match_record_grid" value="<?php _e('No matching records found','ARForms-user-registration');?>"/>
    <input type="hidden" name="no_record_grid" id="no_record_grid" value="<?php _e('No data available in table','ARForms-user-registration');?>"/>
    <input type="hidden" name="filter_grid" id="filter_grid" value="<?php _e('filtered from','ARForms-user-registration');?>"/>
    <input type="hidden" name="totalwd_grid" id="totalwd_grid" value="<?php _e('total','ARForms-user-registration');?>"/>
    
    <div class="alignleft actions2">
            <?php 
            $two = '2';
            echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";
            echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms-user-registration') . "</option>\n";
    
            foreach ( $actions as $name => $title ) {
                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
    
                echo "\t<option value='$name'$class>$title</option>\n";
            }
    
            echo "</select></div>\n";
            
            echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__('Apply','ARForms-user-registration').'" />'; 
            echo "\n";
            
            ?>
    </div>
    <div class="footer_grid"></div>

    <?php		
		
	}
		
	function edit_form(){
		global $wpdb, $arfform;
		
		if( isset($_REQUEST['arf_reset_user_registration']) )
		{	
			self::reset_edited_form('user_registration');
			$arfaction = 'edit';		
			$tab = 'user_registration';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		if( isset($_REQUEST['arf_reset_login']) )
		{	
			self::reset_edited_form('login');
			$arfaction = 'edit';		
			$tab = 'login';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		if( isset($_REQUEST['arf_reset_forgot_password']) )
		{	
			self::reset_edited_form('forgot_password');
			$arfaction = 'edit';		
			$tab = 'forgot_password';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		if( isset($_REQUEST['arf_reset_change_password']) )
		{	
			self::reset_edited_form('change_password');
			$arfaction = 'edit';		
			$tab = 'change_password';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		if( isset($_REQUEST['arf_reset_edit_profile']) )
		{	
			self::reset_edited_form('edit_profile');
			$arfaction = 'edit';		
			$tab = 'edit_profile';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		
		if( isset($_REQUEST['arf_reset_reset_password']) )
		{	
			self::reset_edited_form('reset_password');
			$arfaction = 'edit';		
			$tab = 'reset_password';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
			return;
		}
		
		
		//save edit form
		if( isset($_REQUEST['save_arf_user_registration']) )
		{
			$id = self::save_edit_part('user_registration');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');					
			$tab = 'user_registration';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		} 
		else if( isset($_REQUEST['save_arf_login']) )
		{
			$id = self::save_edit_part('login');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			$tab = 'login';					
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		} 
		else if( isset($_REQUEST['save_arf_forgot_password']) )
		{
			$id = self::save_edit_part('forgot_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			$tab = 'forgot_password';								
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_change_password']) )
		{
			$id = self::save_edit_part('change_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			$tab = 'change_password';									
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_reset_password']) )
		{
			$id = self::save_edit_part('reset_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			$tab = 'reset_password';									
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_edit_profile']) )
		{
			$id = self::save_edit_part('edit_profile');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			$tab = 'edit_profile';									
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');				
		} 
		else 
		{
			$id = $_REQUEST['id'];
			$arfaction = 'edit';
			require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
							
	}
	
	function reset_edited_form($tab='')
	{
		global $wpdb;
		
		if( ! $tab )
			return;
		
		$id = $_REQUEST['id'];
		
		$form_id = $_REQUEST['form_id'];
		
		self::remove_form_setlist($id);
		
		$wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d AND form_id = %d", $id, $form_id) );
		
		if( isset($_REQUEST['arf_set_id']) and $_REQUEST['arf_set_id'] != '' )
		{
			$set_id = $_REQUEST['arf_set_id'];
				
			$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			if( $result )
			{
				$result		= $result[0];
				$set_options= maybe_unserialize( $result->options );							
				$set_options['current_tab']= $tab; 			
				$set_options= maybe_serialize( $set_options );
				
				$wpdb->update($wpdb->prefix."arf_form_sets", array('options'=>$set_options, 'update_at'=> current_time('mysql') ), array('id'=>$set_id));
			}
		}
		 
	}
	
	function field_dropdown($form_id, $name='', $class='', $default_value=''){
		
		global $arffield, $MdlDb;  

        $field_list = array();

        if(is_numeric($form_id)){

            $exclude = "'divider','captcha','break','html','file','like'";

            $field_list = $arffield->getAll("fi.type not in (". $exclude .") and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <div class="sltstandard">
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" style="width:225px;" class="<?php echo $class; ?> frm-dropdown" data-width="275px" data-size="6">
            <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
            <?php 
			if( count($field_list) > 0 ){
			$i=1;
			foreach($field_list as $field){ 
				if( in_array($field->type, array('checkbox', 'radio')) && $field->name == '' )
				{
					$field->name = $field->type.' '.$i;
					$i++;			
				}
				?>
                <option value="<?php echo $field->id; ?>" <?php selected($default_value, $field->id);?>><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php } } ?>
        </select>
        </div>
		<?php 
		
	}
	
	function arf_user_registration_field_dropdown(){
		
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $exclude = "'divider','captcha','break','html','file','like'";

            $field_list = $arffield->getAll("fi.type not in (". $exclude .") and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){			
 		$i=1;           
			foreach($field_list as $field){ 
				if( in_array($field->type, array('checkbox', 'radio')) && $field->name == '' )
				{
					$field->name = $field->type.' '.$i;
					$i++;			
				}
				?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
		echo '^|^';
        
		$field_list = $arffield->getAll("fi.type in ('password') and fi.form_id=". (int)$form_id, 'field_order');
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		    
	die();
	}
	
	function arf_login_form_field_dropdown(){
		
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $exclude = "'divider','captcha','break','html','file','like'";

            $field_list = $arffield->getAll("fi.type not in (". $exclude .") and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
		$i=1;    
            foreach($field_list as $field){ 
				if( in_array($field->type, array('checkbox', 'radio')) && $field->name == '' )
				{
					$field->name = $field->type.' '.$i;
					$i++;			
				}
			?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
		echo '^|^';
        
		$field_list = $arffield->getAll("fi.type in ('password') and fi.form_id=". (int)$form_id, 'field_order');
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
		echo '^|^';
        
		$field_list = $arffield->getAll("fi.type in ('checkbox') and fi.form_id=". (int)$form_id, 'field_order');
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){			
        $i=1;    
			foreach($field_list as $field){ 
				if( in_array($field->type, array('checkbox', 'radio')) && $field->name == '' )
				{
					$field->name = $field->type.' '.$i;
					$i++;			
				}
			?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		    
	die();
	}
	
	function arf_forgot_pass_form_field_dropdown()
	{
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $exclude = "'text','email'";

            $field_list = $arffield->getAll("fi.type in (". $exclude .") and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
	die();
	}
	
	function arf_change_pass_form_field_dropdown()
	{
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $field_list = $arffield->getAll("fi.type in ('password') and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
	die();
	}
	
	function arf_reset_pass_form_field_dropdown()
	{
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $field_list = $arffield->getAll("fi.type in ('password') and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
	die();
	}
	
	function arf_edit_profile_field_dropdown()
	{
		
		global $arffield, $MdlDb;  

		$form_id = $_REQUEST['form_id'];
		
        $field_list = array();

        if(is_numeric($form_id)){

            $exclude = "'divider','captcha','break','html','file','like'";

            $field_list = $arffield->getAll("fi.type not in (". $exclude .") and fi.form_id=". (int)$form_id, 'field_order');
        }
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		
		echo '^|^';
        
		$field_list = $arffield->getAll("fi.type in ('password') and fi.form_id=". (int)$form_id, 'field_order');
		?>
        <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
        <?php 
        if( count($field_list) > 0 ){
            foreach($field_list as $field){ ?>
                <option value="<?php echo $field->id; ?>"><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php 
            } 
        }
		    
	die();
	}
	
	function arfdelete_user_registration_form( $form_id ){
		global $wpdb, $db_record;
		
		if( !$form_id )	
			return;
		
		$form_data = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE form_id = %d", $form_id) );
		
		if( count($form_data) > 0 ){
			$form_data = $form_data[0];
			self::remove_form_setlist($form_data->id);
			$res = $wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $form_data->id) );
		}
					
	}
	

	function getwpversion()
	{
		global $arf_user_registration_version;
		$bloginformation = array();
		$str = self::get_rand_alphanumeric(10);

		$bloginformation[] = get_bloginfo('name');;
		$bloginformation[] = get_bloginfo('description');
		$bloginformation[] = home_url();
		$bloginformation[] = get_bloginfo('admin_email');
		$bloginformation[] = get_bloginfo('version');
		$bloginformation[] = get_bloginfo('language');
		$bloginformation[] = $arf_user_registration_version;
		$bloginformation[] = $_SERVER['SERVER_ADDR'];
		$bloginformation[] = $str;
		
		update_option('wp_arf_user_registration_get_version',$str);
		
		$valstring = implode("||",$bloginformation);
		$encodedval = base64_encode($valstring);
		
		$urltopost = "http://reputeinfosystems.net/arforms/wpinfo_user_registration.php";
		$response = wp_remote_post( $urltopost, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'wpversion' => $encodedval ),
			'cookies' => array()
			)
		);
	}
	
	function assign_rand_value($num) {

		switch($num) {
			case "1"  : $rand_value = "a"; break;
			case "2"  : $rand_value = "b"; break;
			case "3"  : $rand_value = "c"; break;
			case "4"  : $rand_value = "d"; break;
			case "5"  : $rand_value = "e"; break;
			case "6"  : $rand_value = "f"; break;
			case "7"  : $rand_value = "g"; break;
			case "8"  : $rand_value = "h"; break;
			case "9"  : $rand_value = "i"; break;
			case "10" : $rand_value = "j"; break;
			case "11" : $rand_value = "k"; break;
			case "12" : $rand_value = "l"; break;
			case "13" : $rand_value = "m"; break;
			case "14" : $rand_value = "n"; break;
			case "15" : $rand_value = "o"; break;
			case "16" : $rand_value = "p"; break;
			case "17" : $rand_value = "q"; break;
			case "18" : $rand_value = "r"; break;
			case "19" : $rand_value = "s"; break;
			case "20" : $rand_value = "t"; break;
			case "21" : $rand_value = "u"; break;
			case "22" : $rand_value = "v"; break;
			case "23" : $rand_value = "w"; break;
			case "24" : $rand_value = "x"; break;
			case "25" : $rand_value = "y"; break;
			case "26" : $rand_value = "z"; break;
			case "27" : $rand_value = "0"; break;
			case "28" : $rand_value = "1"; break;
			case "29" : $rand_value = "2"; break;
			case "30" : $rand_value = "3"; break;
			case "31" : $rand_value = "4"; break;
			case "32" : $rand_value = "5"; break;
			case "33" : $rand_value = "6"; break;
			case "34" : $rand_value = "7"; break;
			case "35" : $rand_value = "8"; break;
			case "36" : $rand_value = "9"; break;
		}
		return $rand_value;
	}

	function get_rand_alphanumeric($length) {
		if ($length>0) {
			$rand_id="";
			for ($i=1; $i<=$length; $i++) {
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,36);
				$rand_id .= self::assign_rand_value($num);
			}
		}
		return $rand_id;
	}
	
	function wp_arfuser_registration_autoupdate(){
		
		if( !self::is_arforms_support() )
			return;
			
		require_once(FORMPATH.'/core/wp_ar_auto_update.php');		
		
		$wp_arfuser_registration_plugin_current_version = '1.3'; 
		$wp_arfuser_registration_plugin_remote_path = 'http://www.reputeinfosystems.com/tf/plugins/arforms/updatecheck_arf_user_registration.php'; 
		$wp_arfuser_registration_plugin_slug = 'arformsusersignup/arformsusersignup.php';
		new wp_ar_auto_update($wp_arfuser_registration_plugin_current_version, $wp_arfuser_registration_plugin_remote_path, $wp_arfuser_registration_plugin_slug);
	}
	
	function arfuser_registration_backup(){
		$databaseversion = get_option('arf_user_registration_version');
		update_option('old_arf_user_registration_version',$databaseversion);
	}
	
	function upgrade_user_registration_data(){
		
		global $newarf_user_registration_version;
		
		if(!isset($newarf_user_registration_version) || $newarf_user_registration_version == "")
			$newarf_user_registration_version = get_option('arf_user_registration_version');
		
		if(version_compare($newarf_user_registration_version, '1.3', '<'))
		{
			$path = ARF_USER_REGISTRATION_DIR.'/core/upgrade_arfuser_registration_latest_data.php';
			include($path);
		}
	}
	
	function user_registration_form_dropdown($name, $default_value ='', $blank=true, $onchange = false){
		global $wpdb;
		$forms = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms" );	 
		?>
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?> style="width:275px;" data-width="275px" data-size="20">
        	<option value=""><?php echo ($blank == 1) ? '' : '- '. $blank .' -'; ?></option>
            <?php 
			if( count($forms) > 0 ){
				foreach($forms as $form){					
					?>
                    <option value="<?php echo $form->form_id; ?>" <?php selected($default_value, $form->form_id); ?>><?php echo self::get_form_name($form->form_id); ?> (<?php echo self::get_order_count($form->form_id); ?>)</option> 
            		<?php
				}
			}
			?>
        </select>
        <?php
	}
	
	function get_form_name($form_id=0){
		global $wpdb;
		$form_name = $wpdb->get_results( $wpdb->prepare("SELECT name FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $form_id) );	
		$form_name = $form_name[0];
		return (isset($form_name->name)) ? $form_name->name : '';
	}
	
	function add_custom_meta_field()
	{
	$next_meta_id = $_POST['next_meta_id'];
	$form_id 	= $_POST['form_id'];
	$parent_id 	= ( $_POST['parent_id'] == 'arf_edit_profile_conent' ) ? 'profile_' : ''; 
	if( isset($_POST['is_blank_meta']) && $_POST['is_blank_meta'] == '1' )
	{
		?>
        <input type="text" name="custom_meta_<?php echo $parent_id;?>name_<?php echo $next_meta_id; ?>" id="custom_meta_<?php echo $parent_id;?>name_<?php echo $next_meta_id; ?>" value="" class="txtstandardnew custom_meta_txtfield" />
        <input type="hidden" name="<?php echo 'custom_metadrop_'.$parent_id.'name_'.$next_meta_id; ?>" id="<?php echo 'custom_metadrop_'.$parent_id.'name_'.$next_meta_id; ?>" value="arfcustom" />
        <?php
	}
	else 
	{ ?>
  	<input type="text" name="custom_meta_<?php echo $parent_id;?>name_<?php echo $next_meta_id; ?>" id="custom_meta_<?php echo $parent_id;?>name_<?php echo $next_meta_id; ?>" value="" class="txtstandardnew custom_meta_txtfield" style="display:none;" />
    <div id="custom_metadrop_<?php echo $parent_id ?>name_<?php echo $next_meta_id; ?>_div" class="arf_custom_meta_name">
    	<?php self::arf_custom_meta_dropdown($form_id, 'custom_metadrop_'.$parent_id.'name_'.$next_meta_id, 'arf_custom_meta'.$parent_id.'d_fields', ''); ?>
    </div>
    <?php } ?>
    <div class="arf_custom_meta_value"><?php echo ARF_User_Registration::field_dropdown($form_id, 'custom_meta_'.$parent_id.'value_'.$next_meta_id, 'arf_custom_meta_fields', '');?></div>
    
    <div style="padding-top: 5px;">
        <span style="margin-left:10px;" class="bulk_add_remove">
            <span class="bulk_add" onclick="add_new_custom_meta(0);">&nbsp;</span>
            <span class="bulk_remove" onclick="remove_custom_meta_row(this)">&nbsp;</span>
        </span>                                
    </div> 
    <input type="hidden" name="custom_meta_<?php echo $parent_id;?>array[]" value="<?php echo $next_meta_id; ?>" />
    <?php
	
	die();
	}
	
	function arf_validate_field( $form )
	{
		global $wpdb;
		
		$form_id2	= $form->id; 
		if( $form->id >= 10000 )
		{
			$form_data2 = $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form->id) );
			$form_data2 = $form_data2[0];
			$form_id2	= $form_data2->form_id; 		
		}
		
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id2) );
		
		if( count($form_data2) < 1 )
			return;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'login' ){
			
			$login_options = maybe_unserialize( $form_data2->login_options );
				
			if( empty($login_options['username']) || empty($login_options['password']) )
				return;
				
            echo '<div id="arf_message_error" class="frm_error_style" style="display:none;">
                <div class="msg-detail">
                    <div class="msg-description-success">'.$login_options['login_error'].'</div>
                </div>
            </div>';
            	 	
		}

	}
	
	function arf_prevalidate_field($arf_errors, $form_id, $values, $arf_form_data=array())
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		if( $form_id >= 10000 )
		{
			$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM  '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form_id) );
			$form_data 	= $form_data[0];
			$form_id	= $form_data->form_id; 		
		}
		
		$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM  '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
				
		if( count($form_data) < 1 )
			return $arf_errors;
		
		$form_data 	= $form_data[0];
		
		$arf_default_error_message = self::arf_default_error_message();
		
		if( $form_data->mapped_with == 'user_registration' )
		{				
			$options = maybe_unserialize( $form_data->options ); 
			
			$meta_options = maybe_unserialize( $form_data->user_meta ); 
			
			if( empty($options['username']) || empty($options['email']) )
				return $arf_errors; 
								
			if( isset($values['item_meta']) && $values['item_meta'] )
			{
				foreach($values['item_meta'] as $field_id => $field_value )
				{
					if( ! is_array($field_value) && $options['username'] == $field_id && username_exists( $field_value ) )
						$arf_errors[ $field_id ] = ( !isset($options['duplicate_username']) || empty($options['duplicate_username'])  ) ? $arf_default_error_message['duplicate_username'] : $options['duplicate_username'];
					
					if( ! is_array($field_value) && $options['email'] == $field_id && email_exists( $field_value ) )
						$arf_errors[ $field_id ] = ( !isset($options['duplicate_email']) || empty($options['duplicate_email'])  ) ? $arf_default_error_message['duplicate_email'] : $options['duplicate_email'];	
									
				}
			}
				
		} else if( $form_data->mapped_with == 'login' ){
			
			$login_options = maybe_unserialize( $form_data->login_options );
				
			if( empty($login_options['username']) || empty($login_options['password']) )
				return $arf_errors; 	
			
			if( isset($values['item_meta']) && $values['item_meta'] )
			{
				$userdata = array();
				foreach($values['item_meta'] as $field_id => $field_value )
				{					
					if( ! is_array($field_value) && $login_options['username'] == $field_id && username_exists( $field_value ) )
						$userdata = get_user_by( 'login', $field_value );
				}
				
				foreach($values['item_meta'] as $field_id => $field_value )
				{		
					if( ! is_array($field_value) && $login_options['username'] == $field_id && !username_exists( $field_value ) )
					{
						$arf_errors['arf_message_error'] = ( !isset($login_options['login_error']) || empty($login_options['login_error'])  ) ? $arf_default_error_message['login_error'] : $login_options['login_error'];
					}
					else if( ! is_array($field_value) && $login_options['password'] == $field_id && ! wp_check_password( $field_value, (isset($userdata->data->user_pass) ? $userdata->data->user_pass : $userdata->user_pass), $userdata->ID) )
					{
						$arf_errors['arf_message_error'] = ( !isset($login_options['login_error']) || empty($login_options['login_error'])  ) ? $arf_default_error_message['login_error'] : $login_options['login_error'];
					}
				}	
			}
					
		} else if( $form_data->mapped_with == 'forgot_password' ){
				
			$forgot_password_options = maybe_unserialize( $form_data->forgot_password_options );
			
			if( empty($forgot_password_options['username']) )
				return $arf_errors;
			
			if( isset($values['item_meta']) && $values['item_meta'] )
			{
				foreach($values['item_meta'] as $field_id => $field_value )
				{					
					if( ! is_array($field_value) && is_email( $field_value ) ) 
					{
						if( $forgot_password_options['username'] == $field_id && !email_exists( $field_value ) )
							$arf_errors[ $field_id ] = ( !isset($forgot_password_options['forgot_error']) || empty($forgot_password_options['forgot_error'])  ) ? $arf_default_error_message['forgot_error'] : $forgot_password_options['forgot_error'];							
					}
					else
					{
						if( ! is_array($field_value) && $forgot_password_options['username'] == $field_id && !username_exists( $field_value ) )
							$arf_errors[ $field_id ] = ( !isset($forgot_password_options['forgot_error']) || empty($forgot_password_options['forgot_error'])  ) ? $arf_default_error_message['forgot_error'] : $forgot_password_options['forgot_error'];
					}
						
				 }
			}
							
		} else if( $form_data->mapped_with == 'change_password' ){
				
			$change_password_options = maybe_unserialize( $form_data->change_password_options );
			
			if( empty($change_password_options['old_password']) || !is_user_logged_in() )
				return $arf_errors; 
				
			global $current_user;
			
			if( isset($values['item_meta']) && $values['item_meta'] )
			{
				foreach($values['item_meta'] as $field_id => $field_value )
				{
					if( ! is_array($field_value) && $change_password_options['old_password'] == $field_id && !wp_check_password( $field_value, $current_user->data->user_pass, $current_user->ID) )
						$arf_errors[ $field_id ] = ( !isset($change_password_options['change_password_error']) || empty($change_password_options['change_password_error'])  ) ? $arf_default_error_message['change_password_error'] : $change_password_options['change_password_error'];			
				}
			}
					
		} else if( $form_data->mapped_with == 'edit_profile' ) {
						
			$options = maybe_unserialize( $form_data->edit_profile_options ); 
			
			if( empty($options['email']) )
				return $arf_errors;
				
			if( ! is_user_logged_in() )
				return $arf_errors;
				 
			global $current_user;
			
			$username 	= $current_user->data->user_login; 
			$user_id	= $current_user->ID;
			
			$userdata 	= new WP_User( $user_id );
			$user 		= get_object_vars( $userdata->data );		
								
			if( isset($values['item_meta']) && $values['item_meta'] )
			{
				foreach($values['item_meta'] as $field_id => $field_value )
				{				
					if( ! is_array($field_value) && $options['email'] == $field_id && $user['user_email'] != $field_value && email_exists( $field_value ) )
						$arf_errors[ $field_id ] = ( !isset($options['duplicate_email']) || empty($options['duplicate_email'])  ) ? $arf_default_error_message['duplicate_email'] : $options['duplicate_email'];						
				}
			}
				
		}
		
		return $arf_errors;
	}
	
	
	function arf_prevalidatefield()
	{
		$form_id = $_POST['form_id'];
		
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		if( $form_id >= 10000 )
		{
			$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM  '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form_id) );
			$form_data 	= $form_data[0];
			$form_id	= $form_data->form_id; 		
		}
				
		$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM  '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
				
		if( count($form_data) < 1 )
			return;
		
		$form_data 	= $form_data[0];
		
		$errors = array();
		
		$arf_default_error_message = self::arf_default_error_message();
		
		if( $form_data->mapped_with == 'user_registration' )
		{				
			$options = maybe_unserialize( $form_data->options ); 
			
			$meta_options = maybe_unserialize( $form_data->user_meta ); 
			
			if( empty($options['username']) || empty($options['email']) )
				return; 
								
			if( isset($_POST['item_meta']) && $_POST['item_meta'] )
			{
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{
					if( ! is_array($field_value) && $options['username'] == $field_id && username_exists( $field_value ) )
						$errors[ $field_id ] = ( !isset($options['duplicate_username']) || empty($options['duplicate_username'])  ) ? $arf_default_error_message['duplicate_username'] : $options['duplicate_username'];
					
					if( ! is_array($field_value) && $options['email'] == $field_id && email_exists( $field_value ) )
						$errors[ $field_id ] = ( !isset($options['duplicate_email']) || empty($options['duplicate_email'])  ) ? $arf_default_error_message['duplicate_email'] : $options['duplicate_email'];	
									
				}
			}
				
		} else if( $form_data->mapped_with == 'login' ){
			
			$login_options = maybe_unserialize( $form_data->login_options );
				
			if( empty($login_options['username']) || empty($login_options['password']) )
				return; 	
			
			if( isset($_POST['item_meta']) && $_POST['item_meta'] )
			{
				$userdata = array();
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{					
					if( ! is_array($field_value) && $login_options['username'] == $field_id && username_exists( $field_value ) )
						$userdata = get_user_by( 'login', $field_value );
				}
				
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{		
					if( ! is_array($field_value) && $login_options['username'] == $field_id && !username_exists( $field_value ) )
					{
						$errors['arf_message_error'] = ( !isset($login_options['login_error']) || empty($login_options['login_error'])  ) ? $arf_default_error_message['login_error'] : $login_options['login_error'];
					}
					else if( ! is_array($field_value) && $login_options['password'] == $field_id && ! wp_check_password( $field_value, (isset($userdata->data->user_pass) ? $userdata->data->user_pass : $userdata->user_pass), $userdata->ID) )
					{
						$errors['arf_message_error'] = ( !isset($login_options['login_error']) || empty($login_options['login_error'])  ) ? $arf_default_error_message['login_error'] : $login_options['login_error'];
					}
				}	
			}
					
		} else if( $form_data->mapped_with == 'forgot_password' ){
				
			$forgot_password_options = maybe_unserialize( $form_data->forgot_password_options );
			
			if( empty($forgot_password_options['username']) )
				return;
			
			if( isset($_POST['item_meta']) && $_POST['item_meta'] )
			{
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{
					if( ! is_array($field_value) && is_email( $field_value ) ) 
					{
						if( $forgot_password_options['username'] == $field_id && !email_exists( $field_value ) )
							$errors[ $field_id ] = ( !isset($forgot_password_options['forgot_error']) || empty($forgot_password_options['forgot_error'])  ) ? $arf_default_error_message['forgot_error'] : $forgot_password_options['forgot_error'];							
					}
					else
					{
						if( ! is_array($field_value) && $forgot_password_options['username'] == $field_id && !username_exists( $field_value ) )
							$errors[ $field_id ] = ( !isset($forgot_password_options['forgot_error']) || empty($forgot_password_options['forgot_error'])  ) ? $arf_default_error_message['forgot_error'] : $forgot_password_options['forgot_error'];
					}
						
				 }
			}
							
		} else if( $form_data->mapped_with == 'change_password' ){
				
			$change_password_options = maybe_unserialize( $form_data->change_password_options );
			
			if( empty($change_password_options['old_password']) || !is_user_logged_in() )
				return; 
				
			global $current_user;
			
			if( isset($_POST['item_meta']) && $_POST['item_meta'] )
			{
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{
					if( ! is_array($field_value) && $change_password_options['old_password'] == $field_id && !wp_check_password( $field_value, $current_user->data->user_pass, $current_user->ID) )
						$errors[ $field_id ] = ( !isset($change_password_options['change_password_error']) || empty($change_password_options['change_password_error'])  ) ? $arf_default_error_message['change_password_error'] : $change_password_options['change_password_error'];			
				}
			}
					
		} else if( $form_data->mapped_with == 'edit_profile' ) {
						
			$options = maybe_unserialize( $form_data->edit_profile_options ); 
			
			if( empty($options['email']) )
				return;
				
			if( ! is_user_logged_in() )
				return;
				 
			global $current_user;
			
			$username 	= $current_user->data->user_login; 
			$user_id	= $current_user->ID;
			
			$userdata 	= new WP_User( $user_id );
			$user 		= get_object_vars( $userdata->data );		
								
			if( isset($_POST['item_meta']) && $_POST['item_meta'] )
			{
				foreach($_POST['item_meta'] as $field_id => $field_value )
				{				
					if( ! is_array($field_value) && $options['email'] == $field_id && $user['user_email'] != $field_value && email_exists( $field_value ) )
						$errors[ $field_id ] = ( !isset($options['duplicate_email']) || empty($options['duplicate_email'])  ) ? $arf_default_error_message['duplicate_email'] : $options['duplicate_email'];						
				}
			}
				
		}
		
		if( count($errors) > 0 )
		{
			foreach( $errors as $field_id => $error )
			{
				echo $field_id.'^|^'.$error.'~|~'; 	
			}
		}
		else
		{
			echo 0;
		}
	
	die();
	}
	
	function arf_validateform_outside($flag, $form)
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		$form_id = $form->id;		
		if( $form_id >= 10000 )
		{
			$form_data 		= $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM  '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form_id) );
			$form_data 		= $form_data[0];
			$form_id		= $form_data->form_id; 		
		}
				
		$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM  '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
				
		if( count($form_data) < 1 )
			return $flag;
		
		$form_data 	= $form_data[0];
		
		if( $form_data->mapped_with == 'user_registration' )
		{	
			$options = maybe_unserialize( $form_data->options ); 
						
			if( !empty($options['username']) && !empty($options['email']) )
				return true;
			
		} else if( $form_data->mapped_with == 'login' ){
			
			$login_options = maybe_unserialize( $form_data->login_options );
			
			if( !empty($login_options['username']) && !empty($login_options['password']) )
				return true; 
					
		} else if( $form_data->mapped_with == 'forgot_password' ){
			
			$forgot_password_options = maybe_unserialize( $form_data->forgot_password_options );
			
			if( !empty($forgot_password_options['username']) )
				return true; 	
		} else if( $form_data->mapped_with == 'change_password' ){
			
			$change_password_options = maybe_unserialize( $form_data->change_password_options );
			
			if( !empty($change_password_options['old_password']) )
				return true; 	
				
		} else if( $form_data->mapped_with == 'edit_profile' ){	
			
			$options = maybe_unserialize( $form_data->edit_profile_options ); 
			
			if( !empty($options['email']) )
				return true;			
		}
			 
		return $flag;
	}
	
	function field_dropdown_custom($form_id, $name='', $class='', $default_value='', $field_type = array()){
		
		global $arffield, $MdlDb;  

        $field_list = array();
        if(is_numeric($form_id)){
			$include = "";			
			if($field_type)
			{	
				foreach($field_type as $type)
				{
					$include .= "'".$type."',";		
				}		
			}
			
			$include = rtrim($include, ",");			
            $field_list = $arffield->getAll("fi.type in (". $include .") and fi.form_id=". (int)$form_id, 'field_order');			
        }
		?>
        <div class="sltstandard">
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" style="width:225px;" class="<?php echo $class; ?> frm-dropdown" data-width="275px" data-size="15">
            <option value=""><?php echo __('Select Field', 'ARForms-user-registration'); ?></option>
            <?php 
			if( count($field_list) > 0 ){
			$i=1;
			foreach($field_list as $field){ 
				if( in_array($field->type, array('checkbox', 'radio')) && $field->name == '' )
				{
					$field->name = $field->type.' '.$i;
					$i++;			
				}
				?>
                <option value="<?php echo $field->id; ?>" <?php selected($default_value, $field->id);?>><?php echo armainhelper::truncate($field->name, 33); ?></option>
            <?php } } ?>
        </select>
        </div>
		<?php 		
	}
	
	function save_edit_part($tab)
	{
			
		global $wpdb, $arfform;
		
		$set_id = isset( $_REQUEST['set_id'] ) ? $_REQUEST['set_id'] : '';
		
		if( $set_id == '' )
			$set_id = isset( $_REQUEST['arf_set_id'] ) ? $_REQUEST['arf_set_id'] : '';
		
		if( $set_id == '' )
		{
			$set_array = array();
		
			$set_array['name']		= stripslashes_deep( $_REQUEST['arf_set_name'] );
			$set_option				= array(
										'current_tab'	=> esc_attr($_REQUEST['arf_current_tab']),
										'form_list'		=> array(),
										); 	
			$set_array['options']	= maybe_serialize( $set_option );
			$set_array['created_at']= current_time('mysql');
			$set_array['update_at']	= current_time('mysql');
			 
			$res 	= $wpdb->insert($wpdb->prefix."arf_form_sets", $set_array);
			$set_id = $wpdb->insert_id; 
		} else {
			$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			if( $result )
			{
				$result 	= $result[0];
				$set_option	= maybe_unserialize($result->options);
				$set_option['current_tab'] = esc_attr($_REQUEST['arf_current_tab']);   
				
				$set_option	= maybe_serialize($set_option);	
				$wpdb->update($wpdb->prefix."arf_form_sets", array('options'=>$set_option, 'update_at'=> current_time('mysql') ), array('id'=>$set_id));
			}
		} 
		
		if( $tab == 'user_registration' ){
		
			$id = $_POST['id'];		
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				$formid = $_REQUEST['arf_user_registration_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 

				$options['username'] = esc_attr($_REQUEST['arf_username']);
				
				$options['first_name'] = esc_attr($_REQUEST['arf_first_name']);
				
				$options['last_name'] = esc_attr($_REQUEST['arf_last_name']);
				
				$options['display_name'] = esc_attr($_REQUEST['arf_display_name']);
				
				$options['email'] = esc_attr($_REQUEST['arf_email']);
								
				$options['password'] = esc_attr($_REQUEST['arf_password']);
					
				$options['role'] = esc_attr($_REQUEST['arf_role']);
				
				$options['auto_login'] = isset($_REQUEST['arf_auto_login']) ? 1 : 0;
				
				$options['redirect_url'] = esc_attr($_REQUEST['arf_redirect_url']);
				
				$options['notification'] = isset( $_REQUEST['arf_notification'] ) ? 1 : 0;
				
				$options['verification_mail'] = isset( $_REQUEST['arf_verification_mail'] ) ? 1 : 0;
				
				$options['duplicate_username'] = esc_attr($_REQUEST['arf_duplicate_username']);
				
				$options['duplicate_email'] = esc_attr($_REQUEST['arf_duplicate_email']);
				
				$options['signup_hide_password'] = isset( $_REQUEST['arf_signup_hide_password'] ) ? 1 : 0;
				
				$bp_fields = array();
				$meta_array = isset($_REQUEST['bp_meta_array']) ? $_REQUEST['bp_meta_array'] : array();
								
				if( count($meta_array) > 0 ) {
					$i = 1;
					foreach($meta_array as $v){
						$is_custom = 0;
						$custom_meta_name		= @stripslashes_deep($_REQUEST['bp_drop_name_'.$v]);						
						$custom_meta_name_value = @stripslashes_deep($_REQUEST['bp_field_value_'.$v]);
						
						$bp_fields[$i]= array(
												'id' => $i, 
												'field_name' => $custom_meta_name,
												'field_value'=> $custom_meta_name_value,
												'is_custom'	=> 0, 
												);													 
					$i++;
					}
				
				}
				
				$options['bp_fields']		= $bp_fields;
				
								
				$meta_option = array();
				$custom_metas = array();
				
				$meta_array = isset($_REQUEST['custom_meta_array']) ? $_REQUEST['custom_meta_array'] : array();
								
				if( count($meta_array) > 0 ) {
					$i = 1;
					foreach($meta_array as $v){
						$is_custom = 0;
						$custom_meta_name		= @stripslashes_deep($_REQUEST['custom_metadrop_name_'.$v]);
						if( $custom_meta_name == 'arfcustom' )
						{
							$custom_meta_name = @stripslashes_deep($_REQUEST['custom_meta_name_'.$v]);
							$is_custom	= 1;	
						}
						$custom_meta_name_value = @stripslashes_deep($_REQUEST['custom_meta_value_'.$v]);
						
						$custom_metas[$i]= array(
												'id' => $i, 
												'meta_name' => $custom_meta_name,
												'meta_value'=> $custom_meta_name_value,
												'is_custom'	=> $is_custom, 
												);													 
					$i++;
					}
				
				}
				
				$meta_option = maybe_serialize( $custom_metas );
				
				$new_values['user_meta'] = $meta_option; 
												
				$new_values['options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'user_registration';
				
				$new_values['set_id'] = $set_id;
									
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id)); 
				
				self::save_formto_setlist($set_id, $id, 'user_registration');
								
			} else {	
				// insert
				$new_form_id = $_REQUEST['arf_user_registration_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
																				
					$options['username'] = esc_attr($_REQUEST['arf_username']);
				
					$options['first_name'] = esc_attr($_REQUEST['arf_first_name']);
				
					$options['last_name'] = esc_attr($_REQUEST['arf_last_name']);
				
					$options['display_name'] = esc_attr($_REQUEST['arf_display_name']);
				
					$options['email'] = esc_attr($_REQUEST['arf_email']);
								
					$options['password'] = esc_attr($_REQUEST['arf_password']);
					
					$options['role'] = esc_attr($_REQUEST['arf_role']);
					
					$options['auto_login'] = isset($_REQUEST['arf_auto_login']) ? 1 : 0;
				
					$options['redirect_url'] = esc_attr($_REQUEST['arf_redirect_url']);
					
					$options['notification'] = isset( $_REQUEST['arf_notification'] ) ? 1 : 0;
					
					$options['verification_mail'] = isset( $_REQUEST['arf_verification_mail'] ) ? 1 : 0;
					
					$options['duplicate_username'] = esc_attr($_REQUEST['arf_duplicate_username']);
				
					$options['duplicate_email'] = esc_attr($_REQUEST['arf_duplicate_email']);
					
					$options['signup_hide_password'] = isset( $_REQUEST['arf_signup_hide_password'] ) ? 1 : 0;
					
					$bp_fields = array();
					$meta_array = isset($_REQUEST['bp_meta_array']) ? $_REQUEST['bp_meta_array'] : array();
									
					if( count($meta_array) > 0 ) {
						$i = 1;
						foreach($meta_array as $v){
							$is_custom = 0;
							$custom_meta_name		= @stripslashes_deep($_REQUEST['bp_drop_name_'.$v]);						
							$custom_meta_name_value = @stripslashes_deep($_REQUEST['bp_field_value_'.$v]);
							
							$bp_fields[$i]= array(
													'id' => $i, 
													'field_name' => $custom_meta_name,
													'field_value'=> $custom_meta_name_value,
													'is_custom'	=> 0, 
													);													 
						$i++;
						}
					
					}
					
					$options['bp_fields']		= $bp_fields;
														
					$meta_option = array();
					$custom_metas = array();
					
					$meta_array = isset($_REQUEST['custom_meta_array']) ? $_REQUEST['custom_meta_array'] : array();
									
					if( count($meta_array) > 0 ) {
						$i = 1;
						foreach($meta_array as $v){
							$is_custom = 0;
							$custom_meta_name		= @stripslashes_deep($_REQUEST['custom_metadrop_name_'.$v]);
							if( $custom_meta_name == 'arfcustom' )
							{
								$custom_meta_name 		= @stripslashes_deep($_REQUEST['custom_meta_name_'.$v]);
								$is_custom	= 1;	
							}
							$custom_meta_name_value = @stripslashes_deep($_REQUEST['custom_meta_value_'.$v]);
							
							$custom_metas[$i]= array(
													'id' => $i, 
													'meta_name' => $custom_meta_name,
													'meta_value'=> $custom_meta_name_value,
													'is_custom'	=> $is_custom, 
													);													 
						$i++;
						}
					
					}
					
					$meta_option = maybe_serialize( $custom_metas );
					
					$new_values['user_meta'] = $meta_option; 
					
									
					$new_values['options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'user_registration';
					
					$new_values['set_id'] = $set_id;
									
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'user_registration');
							
				}
			}
		} 
		else if( $tab == 'login' )
		{
			$id = $_POST['id'];		
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				
				$formid = $_REQUEST['arf_login_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 
																													
				$options['username'] = esc_attr($_REQUEST['arf_login_username']);
								
				$options['password'] = esc_attr($_REQUEST['arf_login_password']);
				
				$options['remember'] = esc_attr($_REQUEST['arf_login_remember']);
				
				$options['redirect_url'] = esc_attr($_REQUEST['arf_login_redirect_url']);
				
				$options['login_error'] = esc_attr($_REQUEST['arf_login_error']);
				
				$options['login_entry_enable'] = isset($_REQUEST['arf_login_entry_enable']) ? 1 : 0;
				
				$options['login_hide_password'] = isset($_REQUEST['arf_login_hide_password']) ? 1 : 0;
																	
				$new_values['login_options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'login';
				
				$new_values['set_id'] = $set_id;
								
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id));
				
				self::save_formto_setlist($set_id, $id, 'login');
				
				//option save for logout
				$logout_option = array();
				
				$logout_option['logout_label'] = esc_attr($_REQUEST['arf_logout_label']);
				
				$logout_option['logout_redirect'] = esc_attr($_REQUEST['arf_logout_redirect_url']);
				
				$logout_option['logout_css'] = stripslashes_deep($_REQUEST['arf_logout_css']);
				
				$logout_option = maybe_serialize( $logout_option );
				
				update_option('arf_logout_setting', $logout_option);	 
					
			} else {
				// insert
				$new_form_id = $_REQUEST['arf_login_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
																																			
					$options['username'] = esc_attr($_REQUEST['arf_login_username']);
							
					$options['password'] = esc_attr($_REQUEST['arf_login_password']);
					
					$options['remember'] = esc_attr($_REQUEST['arf_login_remember']);
					
					$options['redirect_url'] = esc_attr($_REQUEST['arf_login_redirect_url']);
									
					$options['login_error'] = esc_attr($_REQUEST['arf_login_error']);
					
					$options['login_entry_enable'] = isset($_REQUEST['arf_login_entry_enable']) ? 1 : 0;
					
					$options['login_hide_password'] = isset($_REQUEST['arf_login_hide_password']) ? 1 : 0;
					
					$new_values['login_options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'login';
					
					$new_values['set_id'] = $set_id;
					
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'login');
					
					//option save for logout
					$logout_option = array();
					
					$logout_option['logout_label'] = esc_attr($_REQUEST['arf_logout_label']);
					
					$logout_option['logout_redirect'] = esc_attr($_REQUEST['arf_logout_redirect_url']);
					
					$logout_option['logout_css'] = stripslashes_deep($_REQUEST['arf_logout_css']);
					
					$logout_option = maybe_serialize( $logout_option );
					
					update_option('arf_logout_setting', $logout_option);											
				} 
				
			}
			
			//return $set_id;
		}
		else if( $tab == 'forgot_password' )
		{
			$id = $_POST['id'];		
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				
				$formid = $_REQUEST['arf_forgot_password_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 

				$options['username'] = esc_attr($_REQUEST['arf_forgot_username']);
								
				$options['forgot_error'] = esc_attr($_REQUEST['arf_forgot_error']);
				
				$options['forgot_entry_enable'] = isset($_REQUEST['arf_forgot_entry_enable']) ? 1 : 0;
																	
				$new_values['forgot_password_options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'forgot_password';
				
				$new_values['set_id'] = $set_id;
				
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id)); 
				
				self::save_formto_setlist($set_id, $id, 'forgot_password');
					
			} else {
				// insert
				$new_form_id = $_REQUEST['arf_forgot_password_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
																				
					$options['username'] = esc_attr($_REQUEST['arf_forgot_username']);
							
					$options['forgot_error'] = esc_attr($_REQUEST['arf_forgot_error']);
					
					$options['forgot_entry_enable'] = isset($_REQUEST['arf_forgot_entry_enable']) ? 1 : 0;
					
					$new_values['forgot_password_options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'forgot_password';
					
					$new_values['set_id'] = $set_id;
					
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'forgot_password');						
				} 
				
			}
			
			//return $set_id;
		}
		else if( $tab == 'change_password' )
		{
			$id = $_POST['id'];		
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				
				$formid = $_REQUEST['arf_change_password_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 

				$options['old_password'] = esc_attr($_REQUEST['arf_old_password']);
				
				$options['new_password'] = esc_attr($_REQUEST['arf_new_password']);
								
				$options['change_password_error'] = esc_attr($_REQUEST['arf_change_password_error']);
				
				$options['notification'] = isset($_REQUEST['arf_changepass_notification']) ? 1 : 0;
				
				$options['changepass_entry_enable'] = isset($_REQUEST['arf_changepass_entry_enable']) ? 1 : 0;
				
				$options['changepass_hide_password'] = isset($_REQUEST['arf_changepass_hide_password']) ? 1 : 0;
																							
				$new_values['change_password_options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'change_password';
				
				$new_values['set_id'] = $set_id;
				
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id)); 
				
				self::save_formto_setlist($set_id, $id, 'change_password');
					
			} else {
				// insert
				$new_form_id = $_REQUEST['arf_change_password_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
					
					$options['old_password'] = esc_attr($_REQUEST['arf_old_password']);
																				
					$options['new_password'] = esc_attr($_REQUEST['arf_new_password']);
							
					$options['change_password_error'] = esc_attr($_REQUEST['arf_change_password_error']);
					
					$options['notification'] = isset($_REQUEST['arf_changepass_notification']) ? 1 : 0;
					
					$options['changepass_entry_enable'] = isset($_REQUEST['arf_changepass_entry_enable']) ? 1 : 0;
					
					$options['changepass_hide_password'] = isset($_REQUEST['arf_changepass_hide_password']) ? 1 : 0;
					
					$new_values['change_password_options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'change_password';
					
					$new_values['set_id'] = $set_id;
					
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'change_password');						
				} 
				
			}
			
			//return $set_id;
			
		} 
		else if( $tab == 'reset_password' )
		{
			echo $id = $_POST['id'];		
			
			
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				
				$formid = $_REQUEST['arf_reset_password_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 

				$options['reset_new_password'] = esc_attr($_REQUEST['arf_reset_new_password']);
																				
				$options['reset_page_password_form'] = esc_attr($_REQUEST['arf_reset_page_password_form']);

				$options['reset_pass_entry_enable'] = isset($_REQUEST['arf_reset_pass_entry_enable']) ? 1 : 0;
						   	
				$new_values['reset_password_options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'reset_password';
				
				$new_values['set_id'] = $set_id;
				
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id)); 
				
				self::save_formto_setlist($set_id, $id, 'reset_password');
					
			} else {
				// insert
				$new_form_id = $_REQUEST['arf_reset_password_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
					
					$options['reset_new_password'] = esc_attr($_REQUEST['arf_reset_new_password']);
																				
					$options['reset_page_password_form'] = esc_attr($_REQUEST['arf_reset_page_password_form']);

					$options['reset_pass_entry_enable'] = isset($_REQUEST['arf_reset_pass_entry_enable']) ? 1 : 0;
					
					$new_values['reset_password_options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'reset_password';
					
					$new_values['set_id'] = $set_id;
					
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'reset_password');						
				} 
				
			}
			
			
			//return $set_id;
			
		}
		else if( $tab == 'edit_profile' ){
		
			$id = $_POST['id'];		
			
			if( isset($id) and $id != '' and $_POST['arfaction'] == 'edit' ) {
				// update
				$formid = $_REQUEST['arf_edit_profile_form'];
				
				$arf_form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $formid) );
				$form_chk = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );
				if( count($arf_form_chk) == 0 || count($form_chk) == 0 ) { 
					echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms-user-registration&err=1').'";</script>';
				}
			
				$form_data = $arfform->getOne($formid);
				
				$new_values['form_id'] = $formid;
					
				$new_values['form_name'] = $form_data->name; 

				$options['first_name'] = esc_attr($_REQUEST['arf_profile_first_name']);
				
				$options['last_name'] = esc_attr($_REQUEST['arf_profile_last_name']);
				
				$options['display_name'] = esc_attr($_REQUEST['arf_profile_display_name']);
				
				$options['email'] = esc_attr($_REQUEST['arf_profile_email']);
								
				$options['password'] = esc_attr($_REQUEST['arf_profile_password']);

				$options['duplicate_email'] = esc_attr($_REQUEST['arf_duplicate_email']);
				
				$options['editprofile_hide_password'] = isset($_REQUEST['arf_editprofile_hide_password']) ? 1 : 0;
				
				$options['edit_profile_enable'] = isset($_REQUEST['arf_edit_profile_enable']) ? 1 : 0;
				
				$bp_fields = array();
				$meta_array = isset($_REQUEST['bp_meta_profile_array']) ? $_REQUEST['bp_meta_profile_array'] : array();
								
				if( count($meta_array) > 0 ) {
					$i = 1;
					foreach($meta_array as $v){
						$is_custom = 0;
						$custom_meta_name		= @stripslashes_deep($_REQUEST['bp_drop_profile_name_'.$v]);						
						$custom_meta_name_value = @stripslashes_deep($_REQUEST['bp_field_profile_value_'.$v]);
						
						$bp_fields[$i]= array(
												'id' => $i, 
												'field_name' => $custom_meta_name,
												'field_value'=> $custom_meta_name_value,
												'is_custom'	=> 0, 
												);													 
					$i++;
					}
				
				}
				
				$options['bp_fields']		= $bp_fields;
				
				$meta_option = array();
				$custom_metas = array();
				
				$meta_array = isset($_REQUEST['custom_meta_profile_array']) ? $_REQUEST['custom_meta_profile_array'] : array();
								
				if( count($meta_array) > 0 ) {
					$i = 1;
					foreach($meta_array as $v){
						
						$is_custom = 0;
						$custom_meta_name		= @stripslashes_deep($_REQUEST['custom_metadrop_profile_name_'.$v]);
						if( $custom_meta_name == 'arfcustom' )
						{
							$custom_meta_name 	= @stripslashes_deep($_REQUEST['custom_meta_profile_name_'.$v]);
							$is_custom			= 1;	
						}
						$custom_meta_name_value = @stripslashes_deep($_REQUEST['custom_meta_profile_value_'.$v]);
						
						$custom_metas[$i]= array(
												'id' => $i, 
												'meta_name' => $custom_meta_name,
												'meta_value'=> $custom_meta_name_value,
												'is_custom'	=> $is_custom,  
												);													 
					$i++;
					}
				
				}
				
				$meta_option = maybe_serialize( $custom_metas );
				
				$new_values['user_meta'] = $meta_option; 
												
				$new_values['edit_profile_options'] = maybe_serialize( $options );
				
				$new_values['mapped_with'] = 'edit_profile';
				
				$new_values['set_id'] = $set_id;
				
				$res = $wpdb->update($wpdb->prefix."arf_user_registration_forms", $new_values, array('id' => $id)); 
				
				self::save_formto_setlist($set_id, $id, 'edit_profile');
								
			} else {	
				// insert
				$new_form_id = $_REQUEST['arf_edit_profile_form'];
				
				if( isset($new_form_id) and $new_form_id != '' ) {
					$form_data = $arfform->getOne($new_form_id);
					
					$new_values['form_id'] = $new_form_id;
					
					$new_values['form_name'] = $form_data->name; 
																				
					$options['first_name'] = esc_attr($_REQUEST['arf_profile_first_name']);
				
					$options['last_name'] = esc_attr($_REQUEST['arf_profile_last_name']);
				
					$options['display_name'] = esc_attr($_REQUEST['arf_profile_display_name']);
				
					$options['email'] = esc_attr($_REQUEST['arf_profile_email']);
								
					$options['password'] = esc_attr($_REQUEST['arf_profile_password']);
					
					$options['duplicate_email'] = esc_attr($_REQUEST['arf_duplicate_email']);
						
					$options['editprofile_hide_password'] = isset($_REQUEST['arf_editprofile_hide_password']) ? 1 : 0;
					
					$options['edit_profile_enable'] = isset($_REQUEST['arf_edit_profile_enable']) ? 1 : 0;
					
					$bp_fields = array();
					$meta_array = isset($_REQUEST['bp_meta_profile_array']) ? $_REQUEST['bp_meta_profile_array'] : array();
									
					if( count($meta_array) > 0 ) {
						$i = 1;
						foreach($meta_array as $v){
							$is_custom = 0;
							$custom_meta_name		= @stripslashes_deep($_REQUEST['bp_drop_profile_name_'.$v]);						
							$custom_meta_name_value = @stripslashes_deep($_REQUEST['bp_field_profile_value_'.$v]);
							
							$bp_fields[$i]= array(
													'id' => $i, 
													'field_name' => $custom_meta_name,
													'field_value'=> $custom_meta_name_value,
													'is_custom'	=> 0, 
													);													 
						$i++;
						}
					
					}
					
					$options['bp_fields']		= $bp_fields;
					
								
					$meta_option = array();
					$custom_metas = array();
					
					$meta_array = isset($_REQUEST['custom_meta_profile_array']) ? $_REQUEST['custom_meta_profile_array'] : array();
									
					if( count($meta_array) > 0 ) {
						$i = 1;
						foreach($meta_array as $v){							
							$is_custom = 0;
							$custom_meta_name		= @stripslashes_deep($_REQUEST['custom_metadrop_profile_name_'.$v]);
							if( $custom_meta_name == 'arfcustom' )
							{
								$custom_meta_name 	= @stripslashes_deep($_REQUEST['custom_meta_profile_name_'.$v]);
								$is_custom			= 1;	
							}
							$custom_meta_name_value = @stripslashes_deep($_REQUEST['custom_meta_profile_value_'.$v]);
							
							$custom_metas[$i]= array(
													'id' => $i, 
													'meta_name' => $custom_meta_name,
													'meta_value'=> $custom_meta_name_value,
													'is_custom'	=> $is_custom,   
													);													 
						$i++;
						}
					
					}
					
					$meta_option = maybe_serialize( $custom_metas );
					
					$new_values['user_meta'] = $meta_option; 
					
									
					$new_values['edit_profile_options'] = maybe_serialize( $options );
					
					$new_values['created_at'] = current_time('mysql');
					
					$new_values['mapped_with'] = 'edit_profile';
					
					$new_values['set_id'] = $set_id;
									
					$id = $wpdb->insert($wpdb->prefix."arf_user_registration_forms", $new_values); 
					
					$id = $wpdb->insert_id;
					
					self::save_formto_setlist($set_id, $id, 'edit_profile');
							
				}
			}
			
		}			
		
		self::remove_extra_form_configuration();
		return $set_id;	 
	}
	
	function remove_extra_form_configuration()
	{
		global $wpdb;
		
		$mapped_form = array();
		
		$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."arf_form_sets" ); 
		if( $results )
		{
			foreach($results as $result)
			{
				$set_options= maybe_unserialize( $result->options );			
				$form_list	= $set_options['form_list'];
				if( $form_list and count($form_list) > 0 )
				{
					foreach($form_list as $mapped => $formid)
						$mapped_form[] = (int)$formid;
				}
			}
		
		}
		
		$mapped_form = array_unique( $mapped_form );
		
		if( $mapped_form )
		{
			$wpdb->query( "DELETE FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id not in (".implode(',', $mapped_form).")" );
		}
					 
	}
	
	function save_formto_setlist($set_id = 0, $form_id = 0, $mapped)
	{
		global $wpdb;
		
		if( !$set_id || !$form_id )
			return;
		
		$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
		if( $result )
		{
			$result		= $result[0];
			$set_options= maybe_unserialize( $result->options );			
			$form_list	= $set_options['form_list'];
			if( !in_array($form_id, $form_list) )
				$form_list[ $mapped ] = $form_id;
			
			$set_options['form_list']	= $form_list;
			
			$set_options= maybe_serialize( $set_options );
			
			$wpdb->update($wpdb->prefix."arf_form_sets", array('options'=>$set_options, 'update_at'=> current_time('mysql') ), array('id'=>$set_id));			
		}	
			
	}
	
	function arf_get_tab_data($tab='user_registration', $id)
	{
		global $wpdb, $arfform;
		if( $tab == 'user_registration' ){
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];		
			
			$options = maybe_unserialize( $form_data->options );				
			$meta_options = maybe_unserialize( $form_data->user_meta );			
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( count($options) > 0 ){
				foreach( $options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			$values['user_meta'] = $meta_options; 
			
			return $values; 		
		}
		else if( $tab == 'login' )
		{
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];			
			$login_options = maybe_unserialize( $form_data->login_options );			
						
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( count($login_options) > 0 ){
				foreach( $login_options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			return $values; 																	
		}
		else if( $tab == 'forgot_password' )
		{
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];			
			$forgot_password_options = maybe_unserialize( $form_data->forgot_password_options );			
						
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( count($forgot_password_options) > 0 ){
				foreach( $forgot_password_options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			return $values; 																	
		}
		else if( $tab == 'change_password' )
		{
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];			
			$change_password_options = maybe_unserialize( $form_data->change_password_options );			
						
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( $change_password_options and count($change_password_options) > 0 ){
				foreach( $change_password_options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			return $values; 																	
		}
		else if( $tab == 'reset_password' )
		{
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];			
			$reset_password_options = maybe_unserialize( $form_data->reset_password_options );			
			
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( $reset_password_options and count($reset_password_options) > 0 ){
				foreach( $reset_password_options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			return $values; 																	
		}
		else if( $tab == 'edit_profile' )
		{
			$form_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $id) );				
			$form_data = $form_data[0];			
			$edit_profile_options = maybe_unserialize( $form_data->edit_profile_options );			
			$meta_options = maybe_unserialize( $form_data->user_meta );
						
			$values = array();
			
			$values['id'] = $form_data->id;		
			$values['form_id'] = $form_data->form_id;			
			$values['form_name'] = $form_data->form_name; 				
			
			if( $edit_profile_options and count($edit_profile_options) > 0 ){
				foreach( $edit_profile_options as $option_key => $option_val ){
					$values[$option_key] = $option_val;
				}
			}
			
			$values['user_meta'] = $meta_options;
			
			return $values; 																	
		}
				
	}
	
	function new_form()
	{	
		
		global $wpdb, $arfform;
		 
		if( isset($_REQUEST['save_arf_user_registration']) )
		{
			$id = self::save_edit_part('user_registration');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');					
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
            	echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		} 
		else if( isset($_REQUEST['save_arf_login']) )
		{
			$id = self::save_edit_part('login');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
            	echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		} 
		else if( isset($_REQUEST['save_arf_forgot_password']) )
		{
			$id = self::save_edit_part('forgot_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
            	echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';								
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_change_password']) )
		{
			$id = self::save_edit_part('change_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
            	echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_reset_password']) )
		{
			$id = self::save_edit_part('reset_password');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
            	echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');
		}
		else if( isset($_REQUEST['save_arf_edit_profile']) )
		{
			$id = self::save_edit_part('edit_profile');
			$arfaction = 'edit';		
			$message = __('Form is successfully updated.', 'ARForms-user-registration');
			if(isset($_GET['arfaction']) && $_GET['arfaction'] == 'new') {
				$location = admin_url('admin.php?page=ARForms-user-registration&arfaction=edit&set_id='.$id.'&msg=1');
           		echo '<script type="text/javascript" language="javascript">location.href="'.$location.'";</script>';								
			}
			else 
				require(ARF_USER_REGISTRATION_DIR.'/core/edit_tab.php');	
		} else { 

			require(ARF_USER_REGISTRATION_DIR.'/core/edit.php');
		}
			
	}
	
	function mapped_with_array()
	{
		$mapped_with = array(
						'user_registration' => __('User Signup', 'ARForms-user-registration'),
						'login' 			=> __('Login', 'ARForms-user-registration'),
						'forgot_password' 	=> __('Forgot Password', 'ARForms-user-registration'),
						'change_password'	=> __('Change Password', 'ARForms-user-registration'),
						'reset_password'	=> __('Reset Password', 'ARForms-user-registration'),
						'edit_profile' 		=> __('Edit Profile', 'ARForms-user-registration'),
						'logout' 			=> __('Logout', 'ARForms-user-registration'),
						);
						
		return $mapped_with;				
	}
	
	function get_default_user_meta($exclude = array()) {
        global $wpdb;

        $metas = array();
        $default_metas = $wpdb->get_results("select distinct meta_key from {$wpdb->prefix}usermeta ORDER BY umeta_id ASC");

        foreach($default_metas as $key) {
            if(!in_array($key->meta_key, $exclude))
                $metas[$key->meta_key] = $key->meta_key;
        }

        return $metas;
    }
	
	function arf_default_error_message()
	{
		$error_message = array(
							'duplicate_username'	=> stripslashes( __('Username already exists', 'ARForms-user-registration') ),
							'duplicate_email'		=> stripslashes( __('Email already exists', 'ARForms-user-registration') ),
							'login_error'			=> stripslashes( __('Invalid username or password', 'ARForms-user-registration') ),
							'forgot_error' 			=> stripslashes( __('Invalid username or email', 'ARForms-user-registration') ),
							'change_password_error' => stripslashes( __('Invalid old password', 'ARForms-user-registration') ),
							);
							
		return $error_message; 					
	}
	
	function arf_username_fnc( $atts )
	{
		global $current_user;
      	get_currentuserinfo();

		 if( is_user_logged_in() )
		{ 		
			$arf_username= " ".$current_user->user_login." " ;
		}
		return $arf_username;
	}
	
	function arf_logut_fnc( $atts )
	{
		$button_type = isset($atts['type']) ? $atts['type'] : 'link'; 
		
		if( is_user_logged_in() )
		{
			$logout_content = "";
			$logout_setting = maybe_unserialize( get_option('arf_logout_setting') );
			
			$logout_label 	= ( isset($logout_setting['logout_label']) && !empty($logout_setting['logout_label']) ) ? $logout_setting['logout_label'] : __('Logout', 'ARForms-user-registration');  
			
			$logout_redirect= ( isset($logout_setting['logout_redirect']) && !empty($logout_setting['logout_redirect']) ) ? $logout_setting['logout_redirect'] : get_home_url(); 
			
			if( stripslashes_deep( $logout_setting['logout_css'] ) != '' ) { 
			
				$logout_content .= '<style type="text/css">
                .arf_logout_button { '.stripslashes_deep( $logout_setting['logout_css'] ).'}
                </style>'; 
            }
				
			if( $button_type == 'button' )
			{				
				
                $logout_content .= '<button type="button" class="arf_logout_button" onclick="location.href=\''.wp_logout_url( $logout_redirect ).'\';">'.$logout_label.'</button>';
			}
			else
			{
				$logout_content .= '<a class="arf_logout_button" href="'.wp_logout_url( $logout_redirect ).'">'.$logout_label.'</a>';
			}
			
			return $logout_content;
		}
		
	}
	
	function arf_custom_meta_dropdown($form_id, $name, $class, $value)
	{
		$metas_list = self::get_default_user_meta(array('arf_entry_id','arf_update_entry_id','user_url','description'));
		if( $value == 'arfcustom' )
		{
			?>
			<input type="hidden" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="arfcustom" />
			<?php
		}
		else { ?>
        <div class="sltstandard">
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" style="width:225px;" class="<?php echo $class; ?> frm-dropdown" data-width="275px" data-size="6" <?php /*?>onchange="arf_change_custom_meta('<?php echo $form_id;?>', '<?php echo $name; ?>');"<?php */?>>
            <option value=""><?php echo __('Select Meta Option', 'ARForms-user-registration'); ?></option>
           <?php /*?> <option class="custom_meta" value="custom" <?php selected($value, 'custom');?>><?php echo __('Add Custom Meta', 'ARForms-user-registration'); ?></option><?php */?>
            <option value="user_url" <?php selected($value, 'user_url');?>><?php echo __('Website', 'ARForms-user-registration'); ?></option>
            <option value="description" <?php selected($value, 'description');?>><?php echo __('Biographical Information', 'ARForms-user-registration'); ?></option>
            <?php 
			if( count($metas_list) > 0 ){
			foreach($metas_list as $meta_key => $meta_name){ ?>
                <option value="<?php echo $meta_key; ?>" <?php selected($value, $meta_key);?>><?php echo $meta_name; ?></option>
            <?php } } ?>
        </select>
        </div>
		<?php
		} 	
	}
	
	function get_set_name( $set_id = '' )
	{
		global $wpdb;
		 
		$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
		if( $result )
		{
			$result = $result[0];
			return $result->name; 	
		} else {
			$result = $wpdb->get_results("SELECT count(*) AS set_count FROM ".$wpdb->prefix."arf_form_sets");
			if( $result )
			{
				$result = $result[0];
				return __('Set', 'ARForms-user-registration').' '.($result->set_count + 1);
			}
			else{
				return __('Set', 'ARForms-user-registration').' 1';
			}
		}
		  
	}
	
	function get_mapped_form_name($form_id)
	{
		global $wpdb;
		
		if( ! $form_id )
			return;
		
		$mapped_with_array = ARF_User_Registration::mapped_with_array();
		
		$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms WHERE id = %d", $form_id) );
		if( $result )
		{
			$result = $result[0];
			return isset($mapped_with_array[ $result->mapped_with ]) ? $mapped_with_array[ $result->mapped_with ] : '';	
		}
			
	}
	
	function remove_form_setlist($form_id, $set_id='')
	{
		global $wpdb;
		
		if( !$form_id )
			return;
			
		if( $set_id )
		{
			$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			if( $result )
			{
				$result		= $result[0];
				$set_options= maybe_unserialize( $result->options );			
				$form_list	= $set_options['form_list'];
				
				if(($key = array_search($form_id, $form_list)) !== false) {
    				unset($form_list[$key]);
				}

				$set_options['form_list']	= $form_list;
				
				$set_options= maybe_serialize( $set_options );
				
				$wpdb->update($wpdb->prefix."arf_form_sets", array('options'=>$set_options, 'update_at'=> current_time('mysql') ), array('id'=>$set_id));
			}
		} 
		else
		{
			$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_form_sets");
			if( $results )
			{
				foreach($results as $result )
				{
					$set_options= maybe_unserialize( $result->options );			
					$form_list	= $set_options['form_list'];
					
					if(($key = array_search($form_id, $form_list)) !== false) {
						unset($form_list[$key]);
					}
	
					$set_options['form_list']	= $form_list;
					
					$set_options= maybe_serialize( $set_options );
					
					$wpdb->update($wpdb->prefix."arf_form_sets", array('options'=>$set_options, 'update_at'=> current_time('mysql') ), array('id'=>$result->id));
				}
			}
		
		}
		
	}
		
	function arfcheckpredisplayform( $form )
	{
		global $wpdb;
		
		$form_id2	= $form->id; 
		if( $form->id >= 10000 )
		{
			$form_data2 = $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form->id) );
			$form_data2 = $form_data2[0];
			$form_id2	= $form_data2->form_id; 		
		}
		
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id2) );
		
		if( count($form_data2) < 1 )
			return $form;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'change_password' || $form_data2->mapped_with == 'edit_profile' ){
			global $arfsettings;
			$arfsettings->login_msg = __('You must loggedin to view this form', 'ARForms-user-registration');
			$form->is_loggedin		= 1;
		}
		
		return $form;
	}
	
	function arfprevententry( $values )
	{
		global $wpdb;
				
		$form_id2	= $values['form_id']; 
		
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id2) );
		
		if( count($form_data2) < 1 )
			return $values;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'login' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->login_options ); 
			
			if( $options['login_entry_enable'] != 0 )
				return $values; 
				 
			if( empty($options['username']) || empty($options['password']) )
				return $values; 
						
			$mapped_field = array();
			
			$mapped_field[] = $options['username'];
			$mapped_field[] = $options['password'];
			$mapped_field[] = $options['remember'];
			
			$mapped_field =  array_unique($mapped_field);
			
			//user details
			$user_name 	= @$values['item_meta'][ $options['username'] ];
			$password	= @$values['item_meta'][ $options['password'] ];
			$remember	= isset($values['item_meta'][ $options['remember'] ]) ? 1 : 0;
		
			$values = array(
						'arfprevent_form_id'=> $form_id2, 
						'login_username'	=> $user_name,
						'login_password'	=> $password,
						'login_remember'	=> $remember,
						);		
		} else if( $form_data2->mapped_with == 'change_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->change_password_options ); 
			
			if( $options['changepass_entry_enable'] != 0 )
				return $values; 
				 
			if( empty($options['new_password']) )
				return $values; 
						
			$mapped_field = array();
			
			$mapped_field[] = $options['password'];
			
			$mapped_field =  array_unique($mapped_field);
			
			//user details
			$password	= @$values['item_meta'][ $options['new_password'] ];
		
			$values = array(
						'arfprevent_form_id' 		=> $form_id2, 
						'arfchangepass_password'	=> $password,
						);		
		
		} else if( $form_data2->mapped_with == 'forgot_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->forgot_password_options ); 
			
			if( $options['forgot_entry_enable'] != 0 )
				return $values; 
				 
			if( empty($options['username']) )
				return $values; 
					
			$mapped_field = array();
		
			$mapped_field[] = $options['username'];
			
			$mapped_field =  array_unique($mapped_field);
			
			//user details
			$username	= @$values['item_meta'][ $options['username'] ];
			
		
			$values = array(
						'arfprevent_form_id' 	=> $form_id2, 
						'arfforgot_username'	=> $username,
						);		
		
		} else if( $form_data2->mapped_with == 'reset_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
			$options = maybe_unserialize( $form_data2->reset_password_options ); 
			
			
			if( $options['reset_pass_entry_enable'] != 0 )
				return $values; 
				 
			if( empty($options['reset_new_password']) )
				return $values; 
					
			$mapped_field = array();
		
			$mapped_field[] = $options['reset_new_password'];
			
			$mapped_field =  array_unique($mapped_field);
			
			//user details
			$password	= @$values['item_meta'][ $options['reset_new_password']];
			
			$values = array(
						'arfprevent_form_id' 	=> $form_id2, 
						'arf_resetpass_password'	=> $password,
						
						);
		
		} else if( $form_data2->mapped_with == 'edit_profile' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->edit_profile_options ); 
			
			if( $options['edit_profile_enable'] != 0 )
				return $values; 
				
			if( ! is_user_logged_in() )
				return $values;
													 								
			//user profile details
			$first_name		= @$values['item_meta'][ $options['first_name'] ];
			$last_name		= @$values['item_meta'][ $options['last_name'] ];
			$display_name	= @$values['item_meta'][ $options['display_name'] ];
			$email			= @$values['item_meta'][ $options['email'] ];
			$password		= @$values['item_meta'][ $options['password'] ];
		
			$values = array(
						'arfprevent_form_id' 			=> $form_id2, 
						'arfeditprofile_first_name'		=> $first_name,
						'arfeditprofile_last_name'		=> $last_name,
						'arfeditprofile_display_name'	=> $display_name,
						'arfeditprofile_email'			=> $email,
						'arfeditprofile_password'		=> $password,
						'arfeditprofile_item_meta'		=> $values['item_meta'], 
						);		
		}
		
		return $values;
	}
	
	function arfdologin( $values )
	{ 	
		global $wpdb;
		
				
		$form_id2	= $values['arfprevent_form_id']; 
		
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id2) );
		
		if( count($form_data2) < 1 )
			return;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'login' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->login_options ); 
			
			if( $options['login_entry_enable'] != 0 )
				return; 
				
			if( empty($options['username']) || empty($options['password']) )
				return; 
			
			$user_name	= $values['login_username'];
			$password	= $values['login_password'];
			$remember	= $values['login_remember'];
				
			$credentials = array();
			$credentials['user_login'] 		= $user_name;
			$credentials['user_password'] 	= $password;
			$credentials['remember'] 		= $remember;
					
			$result = wp_signon( $credentials );
			
			$re_url = $options['redirect_url'];
			
			if( empty($re_url) )
				$re_url = get_home_url();
			
			if( !is_wp_error($result) )
			{
				if($arfsettings->form_submit_type == 1)
					echo '^conf_method=addon|^|';	
					
				echo '<script type="text/javascript" language="javascript">location.href="'.$re_url.'";</script>';
				
				if($arfsettings->form_submit_type == 1)
					echo '^conf_method=';
								
				exit;
			}
			
		} 
		  else if( $form_data2->mapped_with == 'change_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->change_password_options ); 
			
			if( $options['changepass_entry_enable'] != 0 )
				return; 
				
			if( empty($options['new_password']) || !is_user_logged_in() )
				return; 
			
			$new_password	= $values['arfchangepass_password'];
					
			global $current_user;
			
			$username 	= $current_user->data->user_login; 
			$user_id	= $current_user->ID;
			
			$userdata 	= new WP_User( $user_id );
			$user 		= get_object_vars( $userdata->data );
			
			$user['user_pass'] = $new_password;
					
			$user_id = wp_update_user($user);
						
			if( is_wp_error($user_id) )
				return;
			
			if( isset($options['notification']) && $options['notification'] == 1 )	
				wp_password_change_notification($current_user);
					
		} 
		  else if( $form_data2->mapped_with == 'forgot_password' ){
		
			global $wpdb, $arfrecordmeta, $arfsettings;
			
			
			$options = maybe_unserialize( $form_data2->forgot_password_options ); 
			$user_send_link = $reset_password_set_id = '';
			// Reset Link 
			$set_id = (isset($form_data2->set_id) && !empty($form_data2->set_id)) ? $form_data2->set_id : '';
			$set_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );
			if( $set_data )
				$set_data = $set_data[0];
			$reset_password = maybe_unserialize( $set_data->options);
			$reset_password_set_id =  (isset($reset_password['form_list']['reset_password']) && !empty($reset_password['form_list']['reset_password'])) ? $reset_password['form_list']['reset_password'] : '';	
			$as_reset_password_data = $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE id = %d', $reset_password_set_id) );
			
			if( $as_reset_password_data)
				$as_reset_password_data = $as_reset_password_data[0];
				
			if($as_reset_password_data->mapped_with == 'reset_password' ){
				$reset_pass_options = maybe_unserialize($as_reset_password_data->reset_password_options); 
				if(!empty($reset_pass_options['reset_page_password_form'])){
					$user_send_link = get_permalink($reset_pass_options['reset_page_password_form']);
				}
			}
			
			if( $options['forgot_entry_enable'] != 0 )
				return; 
				
			if( empty($options['username']) )
				return;
					
			//user details
			$user_name 	= $values['arfforgot_username'];
			 
			if( empty($user_name) )
				return;
			
			$user_login = $user_name; 
			
			global $wpdb, $current_site;
	
			if ( empty( $user_login ) ) {
				return false;
			} else if ( strpos( $user_login, '@' ) ) {
				$user_data = get_user_by( 'email', trim( $user_login ) );
				if ( empty( $user_data ) )
				   return false;
			} else {
				$login = trim($user_login);
				$user_data = get_user_by('login', $login);
			}
		
			do_action('lostpassword_post');
		
			if ( !$user_data ) return false;
		
			// redefining user_login ensures we return the right case in the email
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
		
			do_action('retreive_password', $user_login);  // Misspelled and deprecated
			do_action('retrieve_password', $user_login);
		
			$allow = apply_filters('allow_password_reset', true, $user_data->ID);
		
			if ( ! $allow )
				return false;
			else if ( is_wp_error($allow) )
				return false;
		
				$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			//if ( empty($key) ) {
				// Generate something random for a key...
				$key = wp_generate_password(20, false);
				do_action('retrieve_password_key', $user_login, $key);
				// Now insert the new md5 key into the db
				//$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
				
				if ( empty( $wp_hasher ) ) {
					require_once ABSPATH . 'wp-includes/class-phpass.php';
					$wp_hasher = new PasswordHash( 8, true );
				}
				$hashed = $wp_hasher->HashPassword( $key );
				$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
			
			//}
			
			if(!empty($user_send_link)){
			
				if(strstr($user_send_link, "?")){
					$sendlink = $user_send_link."&action=arp&key=$key&login=".rawurlencode($user_login)."&rspset_id=".$reset_password_set_id;
				}else{
					$sendlink = $user_send_link."?action=arp&key=$key&login=".rawurlencode($user_login)."&rspset_id=".$reset_password_set_id;
				}
				
			}else{
				$sendlink = '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
			}
			
			
			$message = __('Someone requested that the password be reset for the following account:','ARForms-user-registration') . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf(__('Username: %s','ARForms-user-registration'), $user_login) . "\r\n\r\n";
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.','ARForms-user-registration') . "\r\n\r\n";
			$message .= __('To reset your password, visit the following address:','ARForms-user-registration') . "\r\n\r\n";
			$message .= $sendlink;
			
		
			if ( is_multisite() )
				$blogname = $GLOBALS['current_site']->site_name;
			else
				// The blogname option is escaped with esc_html on the way into the database in sanitize_option
				// we want to reverse this for the plain text arena of emails.
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		
			$title = sprintf( __('[%s] Password Reset','ARForms-user-registration'), $blogname );
		
			$title = apply_filters('retrieve_password_title', $title);
			$message = apply_filters('retrieve_password_message', $message, $key);
			
			@wp_mail($user_email, $title, $message);
		
		}
		  else if( $form_data2->mapped_with == 'reset_password' ){
		   		
				global $wpdb, $arfrecordmeta, $arfsettings;
		
				if(isset($_SESSION['arf_reset_pass']['login']) && !empty($_SESSION['arf_reset_pass']['login']) && isset($_SESSION['arf_reset_pass']['action']) && $_SESSION['arf_reset_pass']['action'] == 'arp'){
					$userlogin = $_SESSION['arf_reset_pass']['login'];	
				}else{
					$_SESSION['arf_reset_pass'] = '';
					return;
				}
				
				
				if(is_user_logged_in())
					return; 
				
				$options = maybe_unserialize( $form_data2->reset_password_options ); 
				
				if( $options['reset_pass_entry_enable'] != 0 )
					return; 	
					
				if(empty($options['reset_new_password']))
					return; 
							
				
				
				//user details
				
				$new_password 	=   $values['arf_resetpass_password'];
				
				if(trim($new_password) == ''){
					return; 
				}
				
				$user = get_user_by('login',$userlogin);
				if(empty($user->ID))
					return; 
				
				wp_set_password($new_password, $user->ID);
				$_SESSION['arf_reset_pass'] = '';	
				

				return;	
					
		  } 	
		  else if( $form_data2->mapped_with == 'edit_profile' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->edit_profile_options ); 
			
			if( $options['edit_profile_enable'] != 0 )
				return; 
				
			if( ! is_user_logged_in() )
				return;
												
			//user details
			$user_email 	= @$values[ 'arfeditprofile_email' ];
			$password		= @$values[ 'arfeditprofile_password' ];
			
			global $current_user;
			
			$username 	= $current_user->data->user_login; 
			$user_id	= $current_user->ID;
			
			$userdata 	= new WP_User( $user_id );
			$user 		= get_object_vars( $userdata->data );
			
			if( isset($password) && $password != '' )
				$user['user_pass'] = $password;
			else
				unset( $user['user_pass'] );	
			
			if( isset($user_email) && $user_email != '' && $user['user_email'] != $user_email && !email_exists( $user_email ) )
				$user['user_email'] = $user_email;	
							
			$user_id = wp_update_user($user);
			
			//set custom meta.							
			$meta_options = maybe_unserialize( $form_data2->user_meta ); 
						
			//for standard meta
			$metas_array = array(
							'first_name' 	=> @$values[ 'arfeditprofile_first_name' ], 	//$options['first_name'],
							'last_name'		=> @$values[ 'arfeditprofile_last_name' ], 		//$options['last_name'],
							'display_name'	=> @$values[ 'arfeditprofile_display_name' ],	//$options['display_name'],
							);
			
			foreach($metas_array as $meta_key => $meta_value )
			{
				if( $meta_key == 'display_name' && !empty( $meta_value ) )
					ARF_User_Registration::change_user_property($user_id, 'display_name', $meta_value );
				else if( $meta_key != 'display_name' && !empty( $meta_value ) )	
					update_user_meta($user_id, $meta_key, $meta_value );
			}
			
			
			$field_values =   @$values['arfeditprofile_item_meta'];
			
			//for update custom_meta
			if( count($meta_options) > 0 )
			{
				foreach( $meta_options as $meta_arr )
				{
					if( $meta_arr['meta_value'] != '' )
					{
						$meta_key 	= $meta_arr['meta_name'];
						$meta_value = isset($field_values[ $meta_arr['meta_value'] ]) ? $field_values[ $meta_arr['meta_value'] ] : '';  
					
						if($meta_key == 'user_url' && $meta_value) {
							ARF_User_Registration::change_user_property($user_id, 'user_url', $meta_value);
						}
						else if( empty($meta_value) ) {
							delete_user_meta($user_id, $meta_key);
						}
						else{
							update_user_meta($user_id, $meta_key, $meta_value);
						}
	
					}
											
				}			
			}
			// end of set custom meta.
						
			// for update buddypress field
			$bp_fields = $options['bp_fields']; 
			if( $bp_fields && self::is_buddypress_active() )
			{
				global $wpdb, $bp, $arfrecordmeta;
				require_once(WP_PLUGIN_DIR . '/buddypress/bp-xprofile/bp-xprofile-functions.php');
	
				$table = $bp->profile->table_name_data;
											
				$arfbp_fields = array();
				if( count($bp_fields) > 0 )
				{
					foreach( $bp_fields as $bpfield )
					{
						if( $bpfield['field_value'] != '' )
							$arfbp_fields[] = $bpfield['field_value']; 	
					}			
				}
				
				$arfbp_values = array();
				if( $field_values && $arfbp_fields )
				{
					foreach($field_values as $fieldid => $arffieldvalue)
					{
						if( in_array($fieldid, $arfbp_fields) )
							$arfbp_values[ $fieldid ] = isset($arffieldvalue) ? $arffieldvalue : ''; 
					}
				}
				
				foreach($bp_fields as $bpfield)
				{						
					if( ! $bpfield['field_name'] || ! $bpfield['field_value'] )
						continue;
						
					$bp_field_value = isset($arfbp_values[ $bpfield['field_value'] ]) ? $arfbp_values[ $bpfield['field_value'] ] : '';
					
					if(version_compare(BP_VERSION, '1.6', '<')) {
						$arfbp_field = new BP_XProfile_Field();
						$arfbp_field->bp_xprofile_field($bpfield['field_name']);
					} else {
						require_once(WP_PLUGIN_DIR . '/buddypress/bp-xprofile/bp-xprofile-classes.php');
						$arfbp_field = new BP_XProfile_Field($bpfield['field_name']);
					}
					
					if($arfbp_field->type == 'datebox' )
					{
						$date = $bp_field_value;  
						
						if (preg_match('/^\d{1-2}\/\d{1-2}\/\d{4}$/', $date)){ 
							global $style_settings;
							$date = armainhelper::convert_date($date, $style_settings->date_format, 'Y-m-d');
						}
								
						$date = str_replace("/", "-", $date);
												
						$bp_field_value = @strtotime($date);
					}
										
					xprofile_set_field_data($bpfield['field_name'], $user_id, maybe_unserialize($bp_field_value) );												
				}	
			}			
			// end od update buddypress field.	 
			
		}
				
	}
	
	function arfprechangeformcols($form_cols, $form_id)
	{
		if( ! $form_id )
			return $form_cols;
			
		global $wpdb;
				
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
		
		if( count($form_data2) < 1 )
			return $form_cols;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'login' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->login_options ); 
			
			if( $options['login_hide_password'] == 1 && $options['password'] != '' )
			{
				if( $form_cols )
				{
					foreach($form_cols as $col_key => $col_val)	
					{
						if( $options['password'] == $col_val->id )
							unset($form_cols[ $col_key ]);
					}
				}
			} 
		
		} else if( $form_data2->mapped_with == 'change_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->change_password_options ); 
			
			if( $options['changepass_hide_password'] == 1 && $options['new_password'] != '' )
			{
				if( $form_cols )
				{
					foreach($form_cols as $col_key => $col_val)	
					{
						if( $options['new_password'] == $col_val->id )
							unset($form_cols[ $col_key ]);
							
						if( $options['old_password'] == $col_val->id )
							unset($form_cols[ $col_key ]);	
					}
				}
			}
			
		} else if( $form_data2->mapped_with == 'user_registration' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->options ); 
			
			if( $options['signup_hide_password'] == 1 && $options['password'] != '' )
			{
				if( $form_cols )
				{
					foreach($form_cols as $col_key => $col_val)	
					{
						if( $options['password'] == $col_val->id )
							unset($form_cols[ $col_key ]);
					}
				}
			} 
		
		} else if( $form_data2->mapped_with == 'edit_profile' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->edit_profile_options ); 
			
			if( $options['editprofile_hide_password'] == 1 && $options['password'] != '' )
			{
				if( $form_cols )
				{
					foreach($form_cols as $col_key => $col_val)	
					{
						if( $options['password'] == $col_val->id )
							unset($form_cols[ $col_key ]);
					}
				}
			} 
		}
		
		return $form_cols; 				
	}
	
	function arfprechangecolsitems($items, $form_id)
	{
		if( ! $form_id )
			return $items;	
		
		global $wpdb;
				
		$form_data2 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
		
		if( count($form_data2) < 1 )
			return $items;
		
		$form_data2 	= $form_data2[0];
		
		if( $form_data2->mapped_with == 'change_password' ){
			
			global $wpdb, $arfrecordmeta, $arfsettings;
	
			$options = maybe_unserialize( $form_data2->change_password_options ); 
			
			if( $items )
			{
				foreach($items as $items_key => $items_val)	
				{	
					$username = "";
					if( $items_val->user_id )
					{
						$username_data = get_user_by( 'id', $items_val->user_id );
						if( $username_data )
							$username	= isset( $username_data->user_login ) ? $username_data->user_login : ""; 
					}
				}
			}
				 
		}
			
		return $items;		
	}
	
	function arfprechangeonecol($items, $form_id)
	{
		if( ! $form_id )
			return $items;	
		
		return $items;		
	}
	
	function arfchangesetupvar( $values )
	{
		if( ! is_user_logged_in() )
			return $values;
																	
		global $current_user, $wpdb;
			
		if( $values['fields'] )
		{
			$formid = "";
			foreach( $values['fields'] as $fields )
			{
				if( $fields['form_id'] && $fields['form_id'] != '' )
				{ 
					$formid = $fields['form_id'];
					break;
				}  
			}
			$is_ref_form = false;
			if( $formid >= 10000 )
			{
				$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM  '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $formid) );
				$form_data 	= $form_data[0];
				$formid		= $form_data->form_id; 
				$is_ref_form = true;		
			}
			 
			$form_data2 = $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $formid) );
			
			if( count($form_data2) < 1 )
				return $values;
		
			$form_data2 	= $form_data2[0];
		
			
			if( $form_data2->mapped_with == 'edit_profile' ){
				
				$options		= maybe_unserialize( $form_data2->edit_profile_options );
				
				$meta_options 	= maybe_unserialize( $form_data2->user_meta );
				
				$current_user_meta	= get_user_meta( $current_user->data->ID, '', '' );
				
				if( $current_user->data )
				{
					foreach( $current_user->data as $user_meta_key => $user_meta_value )
					{		
						// get user email and set as field default value				
						if( $user_meta_key == 'user_email' && ! empty( $options['email'] ) )
						{
							foreach( $values['fields'] as $arr_key => $field )
							{
								if( $is_ref_form )
									$arffield_id	= arfieldhelper::get_actual_id($field['id']);
								else 
									$arffield_id	= $field['id'];	
							
								if( $arffield_id == $options['email'] )
								{	
									$values['fields'][ $arr_key ]['value']			= $user_meta_value;
									$values['fields'][ $arr_key ]['default_value']	= $user_meta_value;
									$values['fields'][ $arr_key ]['clear_on_focus']	= 0;
									$values['fields'][ $arr_key ]['default_blank']	= 1;
								} 		
							}
						}
						
						// get user display name and set as field default value		
						if( $user_meta_key == 'display_name' && ! empty( $options['display_name'] ) )
						{
							foreach( $values['fields'] as $arr_key => $field )
							{
								if( $is_ref_form )
									$arffield_id	= arfieldhelper::get_actual_id($field['id']);
								else 
									$arffield_id	= $field['id'];
									
								if( $arffield_id == $options['display_name'] )
								{	
									$values['fields'][ $arr_key ]['value']			= $user_meta_value;
									$values['fields'][ $arr_key ]['default_value']	= $user_meta_value;
									$values['fields'][ $arr_key ]['clear_on_focus']	= 0;
									$values['fields'][ $arr_key ]['default_blank']	= 1;
								} 		
							}
						}
						
						$website_meta_key = "";
						if( $meta_options )
						{						
							foreach( $meta_options as $custom_meta_arr )
							{
								if( $custom_meta_arr['meta_name'] == 'user_url' && ! empty( $custom_meta_arr['meta_value'] ) )
									$website_meta_key = $custom_meta_arr['meta_value']; 
							}
						}
						
						// get user website and set as field default value		
						if( $user_meta_key == 'user_url' && ! empty( $website_meta_key ) )
						{
							foreach( $values['fields'] as $arr_key => $field )
							{
								if( $is_ref_form )
									$arffield_id	= arfieldhelper::get_actual_id($field['id']);
								else 
									$arffield_id	= $field['id'];
									
								if( $arffield_id == $website_meta_key )
								{	
									$values['fields'][ $arr_key ]['value']			= $user_meta_value;
									$values['fields'][ $arr_key ]['default_value']	= $user_meta_value;
									$values['fields'][ $arr_key ]['clear_on_focus']	= 0;
									$values['fields'][ $arr_key ]['default_blank']	= 1;
								} 		
							}
						}
						
					}
				}
				
				// add meta of first name and last name				
				$meta_options[]	= array(
									'meta_name'	=> 'first_name',
									'meta_value'=> $options['first_name'],
									);
				 
				$meta_options[]	= array(
									'meta_name'	=> 'last_name',
									'meta_value'=> $options['last_name'],
									);
				
				$meta_exclude	= array( 'user_url', 'display_name', 'user_email' );	
				
				//set custom meta's value to field default value.
				foreach( $meta_options as $custom_meta )
				{
					if( !empty($custom_meta['meta_name']) && !empty($custom_meta['meta_value']) && ! in_array($custom_meta['meta_name'], $meta_exclude ) )
					{
						foreach( $values['fields'] as $arr_key => $field )
						{
							if( $is_ref_form )
								$arffield_id	= arfieldhelper::get_actual_id($field['id']);
							else 
								$arffield_id	= $field['id'];
								
							if( $arffield_id == $custom_meta['meta_value'] )
							{
								$user_meta_value = @$current_user_meta[ $custom_meta['meta_name'] ][0]; 							
								$values['fields'][ $arr_key ]['value']			= $user_meta_value;
								$values['fields'][ $arr_key ]['default_value']	= $user_meta_value;
								$values['fields'][ $arr_key ]['clear_on_focus']	= 0;
								$values['fields'][ $arr_key ]['default_blank']	= 1;
							} 		
						}
					}	
				}
				
				// set buddypress field value
				$bp_fields = $options['bp_fields'];
				global $wpdb, $bp;
				if( $bp_fields && self::is_buddypress_active() ){
					if( function_exists("xprofile_get_field_data") )
					{
						foreach($bp_fields as $bpfield)
						{
							if( ! $bpfield['field_name'] || ! $bpfield['field_value'] )
								continue;
							
							foreach( $values['fields'] as $arr_key => $field )
							{
								if( $is_ref_form )
									$arffield_id	= arfieldhelper::get_actual_id($field['id']);
								else 
									$arffield_id	= $field['id'];
								
								if( $arffield_id == $bpfield['field_value'] )
								{
									$bp_field_value = xprofile_get_field_data($bpfield['field_name'], $current_user->data->ID);							
									$values['fields'][ $arr_key ]['value']			= $bp_field_value;
									$values['fields'][ $arr_key ]['default_value']	= $bp_field_value;
									$values['fields'][ $arr_key ]['clear_on_focus']	= 0;
									$values['fields'][ $arr_key ]['default_blank']	= 1;
								}
							}
																
						}
					}
				}					 
				// set buddypress field value end					 
			}
						 
		}
		
		return $values;
	}
	
	function widget_text_filter_logout( $content ){
    	
		$regex = '/\[\s*ARForms_logout\s+.*\]/';
		
    	return preg_replace_callback( $regex, array($this, 'widget_text_filter_callback_logout'), $content );
    }

    function widget_text_filter_callback_logout( $matches ) {
        return do_shortcode( $matches[0] );
    }
	
	function widget_text_filter_username( $content ){
    	
		$regex = '/\[\s*ARForms_username\]/';
		
    	return preg_replace_callback( $regex, array($this, 'widget_text_filter_callback_username'), $content );
    }

    function widget_text_filter_callback_username( $matches ) {
        return do_shortcode( $matches[0] );
    }
	
	
	function arf_register_user_after_payment($form_id, $entry_id, $txn_id)
	{
		if( !$entry_id || !$form_id )
			return;
			
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		// check form is maaped with user sign up.
		
		$form_data 	= $wpdb->get_row( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
					
		if( !$form_data )
			return;
				
		$options = maybe_unserialize( $form_data->options ); 
		
		$meta_options = maybe_unserialize( $form_data->user_meta ); 
		
		if( empty($options['username']) || empty($options['email']) )
			return; 
							
		$mapped_field = array();
		
		$mapped_field[] = $options['username'];
		$mapped_field[] = $options['first_name'];
		$mapped_field[] = $options['last_name'];
		$mapped_field[] = $options['display_name'];
		$mapped_field[] = $options['email'];
		$mapped_field[] = $options['password'];
		
		if( count($meta_options) > 0 )
		{
			foreach( $meta_options as $meta_arr )
			{
				if( $meta_arr['meta_value'] != '' )
					$mapped_field[] = $meta_arr['meta_value']; 	
			}			
		}
		
		$mapped_field =  array_unique($mapped_field);

		$entry_ids = array( $entry_id );
		$values = $arfrecordmeta->getAll("it.field_id != 0 and it.entry_id in (". implode(',', $entry_ids).")", " ORDER BY fi.field_order");
		
		$field_values = array();
		if( $values )
		{
			foreach($values as $field)
			{
				if( in_array($field->field_id, $mapped_field) )
					$field_values[ $field->field_id ] = $field->entry_value; 
			}
		}
			 	
		//user details
		$user_name 	= @$field_values[ $options['username'] ];
		$user_email = @$field_values[ $options['email'] ];
		$password	= @$field_values[ $options['password'] ];
		 
		if( empty($user_name) || empty($user_email) )
			return; 
		
		// if username not exists
		if( !username_exists( $user_name ) && !email_exists( $user_email ) )
		{
		
			if( !empty($password) )
			{
				$user_id = wp_create_user($user_name, $password, $user_email);
				if(is_wp_error($user_id))
                	return;
				
				ARF_User_Registration::set_user_meta($user_id, $form_data, $field_values, $entry_id);
			}
			else
			{
				$password 	= wp_generate_password();
				$user_id 	= wp_create_user($user_name, $password, $user_email);
				if(is_wp_error($user_id))
                	return;
			
				ARF_User_Registration::set_user_meta($user_id, $form_data, $field_values, $entry_id);
			}
			
			// set role to user
			if( $user_id != '' && $options['role'] != '' )
			{
				$user = new WP_User($user_id);
				global $wp_roles;
				$all_roles = array_keys($wp_roles->roles);
				
				if( ! in_array($options['role'], $all_roles ) )
					$options['role'] = 'subscriber';
					
            	$user->set_role( $options['role'] );
			}
			
			//send password to user 
			if( isset($options['notification']) && $options['notification'] == 1 )
				wp_new_user_notification($user_id, $password);
														
		}
		else
		{
			//if username already exists.
		}
				
	}
	
	function is_buddypress_active(){
	
		if( ! defined('BP_VERSION') )
			return false;
			
		return true;	
	}	
	
	function arf_bp_field_dropdown($form_id, $name, $class, $value)
	{
		$field_list = self::get_default_bb_field();
		?>
		<div class="sltstandard">
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" style="width:225px;" class="<?php echo $class; ?> frm-dropdown" data-width="275px" data-size="6">
            <?php 
			if( count($field_list) > 0 ){
			foreach($field_list as $field){ ?>
                <option value="<?php echo $field['value']; ?>" <?php selected($value, $field['value']);?>><?php echo $field['name']; ?></option>
            <?php } } ?>
        </select>
        </div>
        <?php
	}
	
	function get_default_bb_field()
	{
		require_once(WP_PLUGIN_DIR . '/buddypress/bp-xprofile/bp-xprofile-classes.php');

        // get BP field groups
        $groups = BP_XProfile_Group::get(array('fetch_fields' => true ));
	
        $buddypress_fields = array();
        $i = 0;
        foreach($groups as $group) {

            if(!is_array($group->fields))
                continue;

            foreach($group->fields as $field) {
                $buddypress_fields[$i]['name'] = $field->name;
                $buddypress_fields[$i]['value'] = $field->id;
                $i++;
            }
        }

        return $buddypress_fields;
	}	
	
	function add_bp_field()
	{
		$next_meta_id = $_POST['next_meta_id'];
		$form_id 	= $_POST['form_id'];
		$parent_id 	= ( $_POST['parent_id'] == 'arf_edit_profile_conent' ) ? 'profile_' : ''; 
		?>		
		<div id="arf_bp_drop_<?php echo $parent_id ?>name_<?php echo $next_meta_id; ?>_div" class="arf_bp_name">
			<?php self::arf_bp_field_dropdown($form_id, 'bp_drop_'.$parent_id.'name_'.$next_meta_id, 'arf_bp'.$parent_id.'d_fields', ''); ?>
		</div>
		
		<div class="arf_bp_value"><?php echo ARF_User_Registration::field_dropdown($form_id, 'bp_field_'.$parent_id.'value_'.$next_meta_id, 'arf_custom_meta_fields', '');?></div>
		
		<div style="padding-top: 5px;">
			<span style="margin-left:10px;" class="bulk_add_remove">
				<span class="bulk_add" onclick="add_new_bp_meta();">&nbsp;</span>
				<span class="bulk_remove" onclick="remove_bp_meta_row(this)">&nbsp;</span>
			</span>                                
		</div> 
		<input type="hidden" name="bp_meta_<?php echo $parent_id;?>array[]" value="<?php echo $next_meta_id; ?>" />
		<?php
	
	die();
	}
	
	function is_resetform_aftersubmit($flag, $form)
	{
		global $wpdb, $arfrecordmeta, $arfsettings;
		
		$form_id = $form->id;		
		if( $form_id >= 10000 )
		{
			$form_data 		= $wpdb->get_results( $wpdb->prepare('SELECT form_id FROM  '.$wpdb->prefix.'arf_ref_forms WHERE id = %d', $form_id) );
			$form_data 		= $form_data[0];
			$form_id		= $form_data->form_id; 		
		}
				
		$form_data 	= $wpdb->get_results( $wpdb->prepare('SELECT * FROM  '.$wpdb->prefix.'arf_user_registration_forms WHERE form_id = %d', $form_id) );
				
		if( count($form_data) < 1 )
			return $flag;
		
		$form_data 	= $form_data[0];
		
		if( $form_data->mapped_with == 'edit_profile' )
		{
			return false;
		}
		
		return $flag;
	}
		
}
?>