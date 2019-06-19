<?php
	if (!class_exists('TS_USERPROVC_Elements')){
		class TS_USERPROVC_Elements{
			function __construct(){
				global $VISUAL_COMPOSER_USERPRO;
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCFrontEditMode == "true") {
					add_action('init',                              			array($this, 'TS_USERPROFORVC_Prepare_UserRoles'), 8888888);
					if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
						$this->TS_USERPROFORVC_LeanMapRegistration();
					} else {
						add_action('init',										array($this, 'TS_USERPROFORVC_Register_UserPro'), 9999999);
						add_action('init',										array($this, 'TS_USERPROFORVC_Register_SocialConnect'), 9999999);
						//add_action('init',									array($this, 'TS_USERPROFORVC_Register_GridFX'), 9999999);
						add_action('init',										array($this, 'TS_USERPROFORVC_Register_StandardBookmark'), 9999999);
						add_action('init',										array($this, 'TS_USERPROFORVC_Register_PublicBookmark'), 9999999);
						add_action('init',										array($this, 'TS_USERPROFORVC_Register_BookmarkList'), 9999999);
					}
				} else {
					add_action('admin_init',		                			array($this, 'TS_USERPROFORVC_Prepare_UserRoles'), 8888888);
					if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
						add_action('admin_init',                              	array($this, 'TS_USERPROFORVC_LeanMapRegistration'), 9999999);
					} else {
						add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_UserPro'), 9999999);
						add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_SocialConnect'), 9999999);
						//add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_GridFX'), 9999999);
						add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_StandardBookmark'), 9999999);
						add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_PublicBookmark'), 9999999);
						add_action('admin_init',								array($this, 'TS_USERPROFORVC_Register_BookmarkList'), 9999999);
					}
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_USERPROFORVC_LeanMapRegistration() {
				vc_lean_map('userpro', 											array($this, 'TS_USERPROFORVC_Register_UserPro'), null);
				vc_lean_map('userpro_social_connect', 							array($this, 'TS_USERPROFORVC_Register_SocialConnect'), null);
				//vc_lean_map('gridfx', 										array($this, 'TS_USERPROFORVC_Register_GridFX'), null);
				vc_lean_map('userpro_bookmark', 								array($this, 'TS_USERPROFORVC_Register_StandardBookmark'), null);
				vc_lean_map('userpro_publicbookmark', 							array($this, 'TS_USERPROFORVC_Register_PublicBookmark'), null);
				vc_lean_map('userpro_bookmarklist', 							array($this, 'TS_USERPROFORVC_Register_BookmarkList'), null);
			}
			
			function TS_USERPROFORVC_Prepare_UserRoles() {
				global $VISUAL_COMPOSER_USERPRO;
				global $wp_roles;
				foreach ($wp_roles->roles as $key => $value) {
					$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_UserRoles[$value['name']] = $key;
				}
			}
		
			// ---------------
			// Default Elements
			// ---------------
		
			// Template Element [userpro]
			function TS_USERPROFORVC_Register_UserPro() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro Template", "ts_userpro_for_vc" ),
					"base"                      => "userpro",
					"icon" 	                    => "icon-wpb-ts_userprovc_template",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a template element.", "ts_userpro_for_vc"),
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// -------------------------
						// General Template Settings
						// -------------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "Main Template Settings",
						),							
						array(
							"type"              => "userprovc_template",
							"heading"           => __( "Template Type", "ts_userpro_for_vc" ),
							"param_name"        => "template",
							"value"				=> "",
							"admin_label"		=> true,
							"description"       => __( "Select the template type you want to show.", "ts_userpro_for_vc" ),
						),
						/*array(
							"type"              => "dropdown",
							"heading"           => __( "Template Type", "ts_userpro_for_vc" ),
							"param_name"        => "template",
							"width"             => 300,
							"value"             => array(
								__( "Select Template Type", "ts_userpro_for_vc" )			=> "",				//
								// General Forms
								__( "Login Form", "ts_userpro_for_vc" )						=> "login",			//
								__( "Registration Form", "ts_userpro_for_vc" )				=> "register",		//
								__( "Password Reset Form", "ts_userpro_for_vc" )			=> "reset",			//
								// Member Listings
								__( "List", "ts_userpro_for_vc" )							=> "list",			//
								__( "Member Directory", "ts_userpro_for_vc" )				=> "memberlist",	//
								__( "Enhanced Member Directory", "ts_userpro_for_vc" )		=> "emd",			//
								__( "Collage", "ts_userpro_for_vc" )						=> "collage",		//
								// Single User
								__( "View Profile", "ts_userpro_for_vc" )					=> "view",			//
								__( "Edit Profile", "ts_userpro_for_vc" )					=> "edit",			//
								__( "Card", "ts_userpro_for_vc" )							=> "card",			//
								__( "Posts By User", "ts_userpro_for_vc" )					=> "postsbyuser",	//									
								__( "Followers", "ts_userpro_for_vc" )						=> "followers",		//
								__( "Following", "ts_userpro_for_vc" )						=> "following",		//
								// Other Templates
								__( "Frontend Publisher", "ts_userpro_for_vc" )				=> "publish",		//
								__( "Activity Feed", "ts_userpro_for_vc" )					=> "activity",		//
							),
							"admin_label"		=> true,
							"description"       => __( "Select the template type you want to show.", "ts_userpro_for_vc" ),
						),*/						
						/*array(
							"type"              => "dropdown",
							"heading"           => __( "Template Skin", "ts_userpro_for_vc" ),
							"param_name"        => "skin",
							"width"             => 300,
							"value"             => array(
								__( "Default Skin", "ts_userpro_for_vc" )					=> "default",
								__( "Blue Skin", "ts_userpro_for_vc" )						=> "blue",
								__( "Elegant Skin", "ts_userpro_for_vc" )					=> "elegant",
								__( "Pure Skin", "ts_userpro_for_vc" )						=> "pure",
								__( "Dark Skin", "ts_userpro_for_vc" )						=> "dark",
							),
							"admin_label"		=> true,
							"description"       => __( "Skin name to use for this shortcode. By default, skin is set in plugin settings. Default skin is 'default'.", "ts_userpro_for_vc" ),
						),*/							
						array(
							"type"              => "userprovc_users",
							"heading"           => __( "User", "ts_userpro_for_vc" ),
							"param_name"        => "user",
							"width"             => 300,
							"value"				=> "",
							"admin_label"		=> true,
							"dependency"        => array( 'element' => "template", 'value' => array('view', 'card', 'postsbyuser', 'followers', 'following') ),
							"description"       => __( "This option can specify which user to return data for. By default, it uses the current user.", "ts_userpro_for_vc" ),
						),							
						array(
							"type"              => "dropdown",
							"heading"           => __( "Sort Criteria", "ts_userpro_for_vc" ),
							"param_name"        => "sortby",
							"width"             => 300,
							"value"             => array(
								__( "Registration Date", "ts_userpro_for_vc" )				=> "registered",
								__( "Email", "ts_userpro_for_vc" )							=> "email",
								__( "User Name", "ts_userpro_for_vc" )						=> "name",
								__( "User Nice Name", "ts_userpro_for_vc" )					=> "nicename",
								__( "Post Count", "ts_userpro_for_vc" )						=> "post_count",
								__( "User ID", "ts_userpro_for_vc" )						=> "ID",
							),
							"description"       => __( "Sort members by which rule, by default it sort members by 'registered' (Registration Date) you can change this setting to sort members differently.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist', 'collage') ),
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Sort Order", "ts_userpro_for_vc" ),
							"param_name"        => "order",
							"width"             => 300,
							"value"             => array(
								__( "Descending", "ts_userpro_for_vc" )						=> "desc",
								__( "Ascending", "ts_userpro_for_vc" )						=> "asc",
							),
							"description"       => __( "The order in which members are displayed. By default it sort members in descending mode.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist', 'collage') ),
						),							
						array(
							"type"              => "nouislider",
							"heading"           => __( "Followers per Page", "ts_userpro_for_vc" ),
							"param_name"        => "followers_per_page",
							"value"             => "25",
							"min"               => "5",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "To display the pagination with specified number of followers.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('followers') ),
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Followings per Page", "ts_userpro_for_vc" ),
							"param_name"        => "following_per_page",
							"value"             => "25",
							"min"               => "5",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "To display the pagination with specified number of followings.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('following') ),
						),
						// -------------------------
						// Member Directory Settings
						// -------------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_3",
							"seperator"         => "Member Directory Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist') ),
							"group"				=> "Member Directory Settings",
						),
						// Common Settings
						array(
							"type"              => "textfield",
							"heading"           => __( "Hide Users", "ts_userpro_for_vc" ),
							"param_name"        => "exclude",
							"value"             => "",
							"description"       => __( "Hide specific users from appearing in member directory. e.g. 1,3,10 will hide these users from the member list.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist') ),
							"group"				=> "Member Directory Settings",
						),							
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Use Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_paginate",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Turn on/off pagination in member directory. Default: 'Yes' (turned on).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist') ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Top Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_paginate_top",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide pagination on top of member directory. Default: 'Yes' (turned on).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_paginate", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Bottom Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_paginate_bottom",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide pagination on bottom of member directory. Default: 'Yes' (turned on).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_paginate", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Members per Page", "ts_userpro_for_vc" ),
							"param_name"        => "per_page",
							"value"             => "12",
							"min"               => "5",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "How many members to display per each page. Default: 12.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_paginate", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),							
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Use Member Search", "ts_userpro_for_vc" ),
							"param_name"        => "search",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Turn on/off member search in member directory. Default: 'Yes' (turned on).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist') ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Members with Avatar Only", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_withavatar",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If enabled, search will return only members with custom profile photo or avatar. Default: 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Verified Members Only", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_verified",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If enabled, search will return only Verified accounts in member search. Default: 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "No Initial Results", "ts_userpro_for_vc" ),
							"param_name"        => "turn_off_initial_results",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If set to 'Yes', this will turn off initial member results until user makes a search in the member directory. Default: 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Default Search Field", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_default_search",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Enable/disable the default search field in member directory. Default: 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom Filter Options", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_filters",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom fields to support in member directory search e.g. country,age (When search is done through the default search field - this does not set custom filters/dropdowns in member directory) Default is empty, includes default WP user search.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),							
						array(
							"type"              => "textfield",
							"heading"           => __( "Gender Search Option", "ts_userpro_for_vc" ),
							"param_name"        => "search_gender",
							"value"             => "",
							"description"       => __( "If you want to provide a filter option for genders, add the following code to the input above: 'Gender,dropdown'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Country Search Option", "ts_userpro_for_vc" ),
							"param_name"        => "search_country",
							"value"             => "",
							"description"       => __( "If you want to provide a filter option for countries, add the following code to the input above: 'Country,dropdown'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "search", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						// Memberlist Table
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show in Table Layout", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_table",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the member directory in table layout. Default: 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('memberlist') ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom Table Columns", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_table_columns",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom columns for the table; e.g. 'picture,name,role,email_user'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_table", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						// Memberlist v2.0
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Use New Directory Layout", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the member directory (version 2). Default: 'Yes' to use the new member directory layout.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_table", 'value' => '0' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Avatar Size", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_pic_size",
							"value"             => "86",
							"min"               => "25",
							"max"               => "250",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Control the size of member/user profile photo in member directory v2. Default: 86.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",		
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show User Name", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_showname",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide user display name in member directory v2. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Social Links", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_showsocial",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide user social icons in the member directory v2. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show User Description", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_bio",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide user biography or description in member directory v2. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show User Badges", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_showbadges",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide user earned badges in member directory v2. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom Fields (Key)", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_v2_fields",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom fields to display in member directory v2. For example, the default value is: 'age,gender,country' which will display user's Age, Gender and Country. You can enter any custom fields you want seperated by a comma.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '1' ),
							"group"				=> "Member Directory Settings",
						),
						// Memberlist v1.0
						array(
							"type"              => "nouislider",
							"heading"           => __( "Memberlist Width", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_width",
							"value"             => "100%",
							"min"               => "10",
							"max"               => "100",
							"step"              => "1",
							"unit"              => "%",
							"extraction"		=> "true",
							"description"       => __( "The width of member directory div relative to its parent element. Default: '100%' (full width).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Profile in Popup", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_popup_view",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If turned on, user profiles will open in a modal popup. Default: 'No' (open user profile directly).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Avatar Size", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_pic_size",
							"value"             => "120",
							"min"               => "50",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Control the size of member/user profile photo in member directory v1. Default: 120.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Use Round Avatars", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_pic_rounded",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Display member photos rounded or square. Default: 'Yes' (rounded photo).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Top Spacing", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_pic_topspace",
							"value"             => "15",
							"min"               => "5",
							"max"               => "100",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Control the top distance between each user in pixels. Default: 15.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Side Spacing", "ts_userpro_for_vc" ),
							"param_name"        => "memberlist_pic_sidespace",
							"value"             => "30",
							"min"               => "10",
							"max"               => "100",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Control the side distance between each user in pixels. Default: 30.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "memberlist_v2", 'value' => '0' ),
							"group"				=> "Member Directory Settings",	
						),
						// ----------------------------------
						// Enhanced Member Directory Settings
						// ----------------------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_4",
							"seperator"         => "Enhanced Member Directory Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Filters", "ts_userpro_for_vc" ),
							"param_name"        => "emd_filters",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide search and filters on the sidebar of members.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						/*array(
							"type"              => "nouislider",
							"heading"           => __( "Avatar Thumbnail Size", "ts_userpro_for_vc" ),
							"param_name"        => "emd_thumb",
							"value"             => "200",
							"min"               => "50",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Avatar size as number only; e.g. 200 will display profile photos 200px wide.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),*/
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Social Links", "ts_userpro_for_vc" ),
							"param_name"        => "emd_social",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide social icons bar in EMD shortcode. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show User Description", "ts_userpro_for_vc" ),
							"param_name"        => "emd_bio",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Show/hide user description in member directory. Default: 'Yes' to show.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom Fields (Key)", "ts_userpro_for_vc" ),
							"param_name"        => "emd_fields",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom fields to show for each user in list, e.g. 'first_name,last_name,gender,country' will display name, gender and country. This option uses custom field keys.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Column Width in Percent", "ts_userpro_for_vc" ),
							"param_name"        => "emd_col_width",
							"value"             => "22%",
							"min"               => "5",
							"max"               => "48",
							"step"              => "1",
							"unit"              => "%",
							"extraction"		=> "true",
							"description"       => __( "The width of each column in percentage; by default: 22%.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",		
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Column Margin in Percent", "ts_userpro_for_vc" ),
							"param_name"        => "emd_col_margin",
							"value"             => "2%",
							"min"               => "1",
							"max"               => "5",
							"step"              => "1",
							"unit"              => "%",
							"extraction"		=> "true",
							"description"       => __( "The margin for each column in percentage. Example: 2% will leave 2% margin beside each item.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",		
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Use Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "emd_paginate",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Enable/disable pagination in EMD shortcode; default: 'Yes' to enable pagination.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('emd') ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Users per Page", "ts_userpro_for_vc" ),
							"param_name"        => "emd_per_page",
							"value"             => "20",
							"min"               => "5",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "Number of users to show per page, by default: 20 members are shown per page.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "emd_paginate", 'value' => '1' ),
							"group"				=> "Enhanced Member Directory Settings",
						),			
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Top Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "emd_paginate_top",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Enable/disable pagination display on top of member results. Default: 'Yes' to show pagination on top as well as bottom.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "emd_paginate", 'value' => '1' ),
							"group"				=> "Enhanced Member Directory Settings",
						),
						// -------------
						// View Settings
						// -------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_5",
							"seperator"         => "View Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Header Only", "ts_userpro_for_vc" ),
							"param_name"        => "header_only",
							"value"             => "false",
							"render"			=> "string",
							"description"       => __( "Set to 'Yes' in order to show the profile header only (compact view) without the full profile details. Perfect if you want to show profile heading only including social icons and not the complete profile.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Include Fields (Key)", "ts_userpro_for_vc" ),
							"param_name"        => "include_fields",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom fields (or field keys) you want to show in the profile view only. This will exclude any other fields than the one you specify in that option.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Include Fields (Name)", "ts_userpro_for_vc" ),
							"param_name"        => "include_fields_by_name",
							"value"             => "",
							"description"       => __( "The same as above option but uses field name instead of field key (e.g. 'First Name,Last Name') will show only first and last name on the profile.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Include Fields (Type)", "ts_userpro_for_vc" ),
							"param_name"        => "include_fields_by_type",
							"value"             => "",
							"description"       => __( "This include fields based on the allowed field types you set here. A comma seperated list of field types will work. Example: 'select,radio' will show only these fields that are select dropdown or radio group; 'picture,file' will include only photo upload fields and files.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Exclude Fields (Key)", "ts_userpro_for_vc" ),
							"param_name"        => "exclude_fields",
							"value"             => "",
							"description"       => __( "A comma seperated list of custom fields (or field keys) you want to exclude from profile view.This will include any other fields than the one you specify in that option.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Exclude Fields (Name)", "ts_userpro_for_vc" ),
							"param_name"        => "exclude_fields_by_name",
							"value"             => "",
							"description"       => __( "The same as above option but uses field name instead of field key (e.g. 'Age,Region') will exclude these fields from appearing on profile.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Exclude Fields (Type)", "ts_userpro_for_vc" ),
							"param_name"        => "exclude_fields_by_type",
							"value"             => "",
							"description"       => __( "This exclude fields based on field types you set here. A comma seperated list of field types will work. Example: 'textarea' will hide any fields that are textarea fields. 'picture,file' will exclude photo upload fields and files.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view') ),
							"group"				=> "View Settings",
						),
						// ----------------
						// Collage Settings
						// ----------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_6",
							"seperator"         => "Collage Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('collage') ),
							"group"				=> "Collage Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Collages per Page", "ts_userpro_for_vc" ),
							"param_name"        => "collage_per_page",
							"value"             => "25",
							"min"               => "5",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "How many collages to display per page.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('collage') ),
							"group"				=> "Collage Settings",			
						),
						// -------------
						// Card Settings
						// -------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_7",
							"seperator"         => "Card Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('card') ),
							"group"				=> "Card Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Card Width", "ts_userpro_for_vc" ),
							"param_name"        => "card_width",
							"value"             => "250px",
							"min"               => "50",
							"max"               => "2048",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "true",
							"description"       => __( "This is the optimal card width; the default value is 250px.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('card') ),
							"group"				=> "Card Settings",			
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Image Width", "ts_userpro_for_vc" ),
							"param_name"        => "card_img_width",
							"value"             => "250",
							"min"               => "50",
							"max"               => "2048",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "Should match the card width; the default value is 250px.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('card') ),
							"group"				=> "Card Settings",							
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Social Links", "ts_userpro_for_vc" ),
							"param_name"        => "card_showsocial",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the social links in the card profile. Default value is 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('card') ),
							"group"				=> "Card Settings",	
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Description", "ts_userpro_for_vc" ),
							"param_name"        => "card_showbio",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the user description in the card profile. Default value is 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('card') ),
							"group"				=> "Card Settings",	
						),
						// -------------
						// List Settings
						// -------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_8",
							"seperator"         => "List Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Title", "ts_userpro_for_vc" ),
							"param_name"        => "list_heading",
							"value"             => "Latest Members",
							"description"       => __( "This overrides the default heading title for this widget. Default heading is 'Latest Members'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Profile Picture Size", "ts_userpro_for_vc" ),
							"param_name"        => "list_per_page",
							"value"             => "5",
							"min"               => "5",
							"max"               => "250",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "Number of members to return. By default the plugin will return 5 users in compact list.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Sort Criteria", "ts_userpro_for_vc" ),
							"param_name"        => "list_sortby",
							"width"             => 300,
							"value"             => array(
								__( "Registration Date", "ts_userpro_for_vc" )				=> "registered",
								__( "Email", "ts_userpro_for_vc" )							=> "email",
								__( "User Name", "ts_userpro_for_vc" )						=> "name",
								__( "User Nice Name", "ts_userpro_for_vc" )					=> "nicename",
								__( "Post Count", "ts_userpro_for_vc" )						=> "post_count",
								__( "User ID", "ts_userpro_for_vc" )						=> "ID",
							),
							"description"       => __( "Sort members by which rule, by default it sort members by 'registered' (Registration Date) you can change this setting to sort members differently.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Sort Order", "ts_userpro_for_vc" ),
							"param_name"        => "list_order",
							"width"             => 300,
							"value"             => array(
								__( "Descending", "ts_userpro_for_vc" )						=> "desc",
								__( "Ascending", "ts_userpro_for_vc" )						=> "asc",
							),
							"description"       => __( "The order in which members are displayed. By default it sort members in descending mode.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),							
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Thumbnails", "ts_userpro_for_vc" ),
							"param_name"        => "list_showthumb",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the thumbnail display in compact members list.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Thumbnails Size", "ts_userpro_for_vc" ),
							"param_name"        => "list_thumb",
							"value"             => "50",
							"min"               => "25",
							"max"               => "250",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "This controls the width of thumbnail that is being displayed in compact member list. By default this is set to 50.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "list_showthumb", 'value' => '1' ),
							"group"				=> "List Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Social Links", "ts_userpro_for_vc" ),
							"param_name"        => "list_showsocial",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the social links in compact members list. Default value is 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Description", "ts_userpro_for_vc" ),
							"param_name"        => "list_showbio",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This can turn on/off the user description in compact members list. Default value is 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Verified Only", "ts_userpro_for_vc" ),
							"param_name"        => "list_verified",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If you set this to 'Yes', it means you will show only members who have the verified badge on their profile. Default value is 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),
						/*array(
							"type"              => "dropdown",
							"heading"           => __( "Sort Order", "ts_userpro_for_vc" ),
							"param_name"        => "list_relation",
							"width"             => 300,
							"value"             => array(
								__( "Or", "ts_userpro_for_vc" )						=> "or",
								__( "And", "ts_userpro_for_vc" )					=> "and",
							),
							"description"       => __( "This controls the relation of meta queries, can be either 'and' or 'or'. The default value is 'or'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('list') ),
							"group"				=> "List Settings",
						),*/
						// -----------------
						// Register Settings
						// -----------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_9",
							"seperator"         => "Register Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Title", "ts_userpro_for_vc" ),
							"param_name"        => "register_heading",
							"value"             => "Register an Account",
							"description"       => __( "This overrides the default heading that displays in Registration form. Default heading is 'Register an Account'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Sidebar", "ts_userpro_for_vc" ),
							"param_name"        => "register_side",
							"value"             => "Already a member?",
							"description"       => __( "This controls the side text link in Registration heading. Default is 'Already a member?'", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Primary Button", "ts_userpro_for_vc" ),
							"param_name"        => "register_button_primary",
							"value"             => "Register",
							"description"       => __( "The text that appears on the registration button. Default to 'Register'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Secondary Button", "ts_userpro_for_vc" ),
							"param_name"        => "register_button_secondary",
							"value"             => "Login",
							"description"       => __( "This is the text that appears on 2nd button; default to 'Login'. Leave blank to disable this button.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Link: Redirect URL", "ts_userpro_for_vc" ),
							"param_name"        => "register_redirect",
							"value"             => "",
							"description"       => __( "This is blank by default. Enter a custom URL if you want users to be redirected to a specific page after registration.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),
						/*array(
							"type"              => "textfield",
							"heading"           => __( "Form Type", "ts_userpro_for_vc" ),
							"param_name"        => "type",
							"value"             => "",
							"description"       => __( "If you setup multiple registration forms, enter the form unique name e.g. 'new_student', which will load 'new_student' registration form.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),*/
						/*array(
							"type"				=> "switch_button",
							"heading"           => __( "Captcha Field", "ts_userpro_for_vc" ),
							"param_name"        => "register_recaptcha",
							"value"             => "true",
							"render"			=> "string",
							"description"       => __( "This can turn on/off recaptcha on registration forms.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('register') ),
							"group"				=> "Register Settings",
						),*/
						// --------------
						// Login Settings
						// --------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_10",
							"seperator"         => "Login Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Title", "ts_userpro_for_vc" ),
							"param_name"        => "login_heading",
							"value"             => "Login",
							"description"       => __( "This overrides the default heading that displays in Login form. Default heading is 'Login'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Sidebar", "ts_userpro_for_vc" ),
							"param_name"        => "login_side",
							"value"             => "Forgot your password?",
							"description"       => __( "This controls the side text link in Login form heading. Default is 'Forgot your password?'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Primary Button", "ts_userpro_for_vc" ),
							"param_name"        => "login_button_primary",
							"value"             => "Login",
							"description"       => __( "The text that appears on the login button. Default to 'Login'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Secondary Button", "ts_userpro_for_vc" ),
							"param_name"        => "login_button_secondary",
							"value"             => "Create an Account",
							"description"       => __( "This is the text that appears on 2nd button; default to 'Create an Account'. Leave blank to disable this button.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Link: Redirect URL", "ts_userpro_for_vc" ),
							"param_name"        => "login_redirect",
							"value"             => "",
							"description"       => __( "This is blank by default. Enter a custom URL if you want users to be redirected to a specific page after login.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),
						/*array(
							"type"				=> "switch_button",
							"heading"           => __( "Captcha Field", "ts_userpro_for_vc" ),
							"param_name"        => "login_recaptcha",
							"value"             => "true",
							"render"			=> "string",
							"description"       => __( "This can turn on/off recaptcha on login forms.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('login') ),
							"group"				=> "Login Settings",
						),*/
						// ----------------------
						// Posts By User Settings
						// ----------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_11",
							"seperator"         => "Posts By User Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('postsbyuser') ),
							"group"				=> "Posts By User Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Display Mode", "ts_userpro_for_vc" ),
							"param_name"        => "postsbyuser_mode",
							"width"             => 300,
							"value"             => array(
								__( "Grid", "ts_userpro_for_vc" )							=> "grid",
								__( "Compact", "ts_userpro_for_vc" )						=> "compact",
							),
							"description"       => __( "This can be 'grid' or 'compact'. Choose in which layout to show the posts made by user.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('postsbyuser') ),
							"group"				=> "Posts By User Settings",
						),							
						/*array(
							"type"				=> "switch_button",
							"heading"           => __( "Use Pagination", "ts_userpro_for_vc" ),
							"param_name"        => "post_paginate",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "Enable/disable pagination; Default is 'Yes' (Pagination is On by default); specify number of posts on a single page with option below.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('postsbyuser') ),
							"group"				=> "Posts By User Settings",
						),*/						
						array(
							"type"              => "nouislider",
							"heading"           => __( "Number of Posts", "ts_userpro_for_vc" ),
							"param_name"        => "postsbyuser_num",
							"value"             => "12",
							"min"               => "1",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "Number of posts to return. Default is 12.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('postsbyuser') ),
							"group"				=> "Posts By User Settings",
						),
						array(
							"type"              => "posttypes",
							"heading"           => __( "Post Types", "ts_userpro_for_vc" ),
							"param_name"        => "postsbyuser_types",
							"value"             => "post",
							"description"       => __( "This controls which post types are included in the post list. By default it shows 'posts' only.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('postsbyuser') ),
							"group"				=> "Posts By User Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Show Thumbnails", "ts_userpro_for_vc" ),
							"param_name"        => "postsbyuser_showthumb",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "This show/hide the thumbnail of post when using the 'compact' mode to display post list. By default this is set to 'Yes'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "postsbyuser_mode", 'value' => 'compact' ),
							"group"				=> "Posts By User Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Thumbnails Size", "ts_userpro_for_vc" ),
							"param_name"        => "postsbyuser_thumb",
							"value"             => "50",
							"min"               => "25",
							"max"               => "250",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "This controls the width of thumbnail that is being displayed in compact member list. By default this is set to 50.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "postsbyuser_showthumb", 'value' => '1' ),
							"group"				=> "Posts By User Settings",
						),
						// ------------------
						// Frontend Publisher
						// ------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_12",
							"seperator"         => "Frontend Publisher Settings",
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Title", "ts_userpro_for_vc" ),
							"param_name"        => "publish_heading",
							"value"             => "Add a New Post",
							"description"       => __( "This is the title of frontend publisher widget. Default: 'Add a New Post'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Primary Button", "ts_userpro_for_vc" ),
							"param_name"        => "publish_button_primary",
							"value"             => "Publish",
							"description"       => __( "This is the 'publish' button text which submits the post via frontend form. Default: 'Publish'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),							
						array(
							'type' 				=> 'checkbox',
							'heading' 			=> __( 'Deny User Roles', 'js_composer' ),
							'param_name' 		=> 'deny_roles',
							"value"				=> $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_UserRoles,
							"description"       => __( "If you want to disable specific roles from posting from frontend publisher, select those user roles to disable the frontend publisher.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),							
						array(
							"type"              => "posttypes",
							"heading"           => __( "Post Types", "ts_userpro_for_vc" ),
							"param_name"        => "post_type",
							"value"             => "post",
							"description"       => __( "This controls which post types the user is allowed to create. By default it shows 'posts' only.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "taxonomies",
							"heading"           => __( "Allowed Taxonomies", "ts_userpro_for_vc" ),
							"param_name"        => "allowed_taxonomies",
							"value"             => "category,post_tag",
							"description"       => __( "If you enable user to choose his own categories, select the allowed taxonomies here. Default: 'category,post_tag'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "taxonomies",
							"heading"           => __( "Default Taxonomies", "ts_userpro_for_vc" ),
							"param_name"        => "taxonomy",
							"value"             => "",
							"description"       => __( "To automatically add new posts to specific category (instead of letting user choose it), select the category taxonomies here; e.g. category or post_tag or another taxonomy.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Default Categories", "ts_userpro_for_vc" ),
							"param_name"        => "category",
							"value"             => "",
							"description"       => __( "If you set specific taxonomy for new posts, enter the category ID/slug (or multiple category ID's/Slugs) for the new items. This option can be single or comma seperated category ID's or slugs; e.g. 'news,sports,soccer' or '1,8,15'", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "taxonomy", 'not_empty' => true ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Require Title", "ts_userpro_for_vc" ),
							"param_name"        => "require_title",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "To make the title field required or optional. Default is 'Yes' (required)", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Require Content", "ts_userpro_for_vc" ),
							"param_name"        => "require_content",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "To make the content field required or optional. Default is 'Yes' (required)", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Require Featured Image", "ts_userpro_for_vc" ),
							"param_name"        => "require_featured",
							"value"             => "1",
							"render"			=> "numeric",
							"description"       => __( "To make the featured image field required or optional. Default is 'Yes' (required)", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom META Keys", "ts_userpro_for_vc" ),
							"param_name"        => "post_meta",
							"value"             => "",
							"description"       => __( "A comma seperated list of meta keys to collect on the frontend publisher (Post Meta). The value should be keys only e.g. 'custom_url,_my_custom_meta_key'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Custom META Labels", "ts_userpro_for_vc" ),
							"param_name"        => "post_meta_labels",
							"value"             => "",
							"description"       => __( "Required if you use the above option. A comma seperated list of labels to present the meta keys you want to collect. e.g. 'Custom URl,Thumbnail URL'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "post_meta", 'not_empty' => true ),
							"group"				=> "Frontend Publisher Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Require Custom Fields", "ts_userpro_for_vc" ),
							"param_name"        => "require_CUSTOM_FIELD",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "To make the any custom fields (added with option above) required or optional. Default is 'No' (optional)", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "post_meta", 'not_empty' => true ),
							"group"				=> "Frontend Publisher Settings",
						),							
						array(
							"type"              => "textfield",
							"heading"           => __( "Field Order", "ts_userpro_for_vc" ),
							"param_name"        => "publish_field_order",
							"value"             => "",
							"description"       => __( "This option lets you control which fields are included in any frontend publisher shortcode, and also controls the order of fields. Add the fields you want in the order you want with comma as seperator. Example: 'title,content,featured_image,category,post_type,custom_field1'", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('publish') ),
							"group"				=> "Frontend Publisher Settings",
						),
						// -------------
						// Activity Feed
						// -------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_13",
							"seperator"         => "Activity Feed Settings",
							"description"       => __( "", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Text: Heading Title", "ts_userpro_for_vc" ),
							"param_name"        => "activity_heading",
							"value"             => "Recent Activity",
							"description"       => __( "The title of activity feed widget. Default: 'Recent Activity'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "All Users Activity", "ts_userpro_for_vc" ),
							"param_name"        => "activity_all",
							"value"             => "0",
							"render"			=> "numeric",
							"description"       => __( "If this is turned on, activity will be public (All users activity) - If this is turned off only activity of 'followed users' will show. Default: 'No'.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Included Users", "ts_userpro_for_vc" ),
							"param_name"        => "activity_user",
							"value"             => "",
							"description"       => __( "A comma seperated list of users (as IDs) to include in activity feed. Example: to limit activity feed to specific users by IDs only. Default is empty (show all users).", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Activities per Page", "ts_userpro_for_vc" ),
							"param_name"        => "activity_per_page",
							"value"             => "10",
							"min"               => "5",
							"max"               => "250",
							"step"              => "1",
							"unit"              => "",
							"extraction"		=> "false",
							"description"       => __( "Number of activities to display per each load. Default: 10.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),
						/*array(
							"type"              => "dropdown",
							"heading"           => __( "AJAX Refresh", "ts_userpro_for_vc" ),
							"param_name"        => "activity_side",
							"width"             => 300,
							"value"             => array(
								__( "Refresh (Enabled)", "ts_userpro_for_vc" )						=> "refresh",
								__( "None (Disabled)", "ts_userpro_for_vc" )						=> "none",
							),
							"description"       => __( "Enable/disable instant ajax refresh of activity with ajax loader. Default: 'Refresh (Enabled)'", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('activity') ),
							"group"				=> "Activity Feed Settings",
						),*/
						// -------------
						// Global Settings
						// -------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_14",
							"seperator"         => "Global Settings",
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "messenger",
							"param_name"        => "messenger",
							"color"				=> "#A80000",
							"weight"			=> "normal",
							"size"				=> "14",
							"message"           => __( "The following settings are considered 'global' by UserPro but do not apply to every template selected in the tab 'General'.", "ts_userpro_for_vc" ),
							"border_top"		=> "false",
							"margin_top"		=> "0",
							"padding_top"		=> "0",
							"group"				=> "Global Settings",
						),
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Remove Header", "ts_userpro_for_vc" ),
							"param_name"        => "no_header",
							"value"             => "false",
							"render"			=> "string",
							"dependency"        => array( 'element' => "template", 'value' => array('login', 'register', 'reset') ),
							"description"       => __( "If you set this option to true, the shortcode header will not be displayed, allowing you to put your custom header.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),	
						array(
							"type"				=> "switch_button",
							"heading"           => __( "Remove Styling", "ts_userpro_for_vc" ),
							"param_name"        => "no_style",
							"value"             => "false",
							"render"			=> "string",
							"description"       => __( "If you set this option to true, the shortcode will appear in a plain style to match your own design/background.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Label Alignment", "ts_userpro_for_vc" ),
							"param_name"        => "layout",
							"width"             => 300,
							"value"             => array(
								__( "Float", "ts_userpro_for_vc" )									=> "float",
								__( "None", "ts_userpro_for_vc" )									=> "none",
							),
							"description"       => __( "This controls the layout of fields. The value can be 'float' or 'none', by default this value is 'float' It means the label will try to float to left of field. If you set this to 'none', the label will be on a seperate line in your form.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Form Sections", "ts_userpro_for_vc" ),
							"param_name"        => "allow_sections",
							"width"             => 300,
							"value"             => array(
								__( "Enable Sections", "ts_userpro_for_vc" )							=> "1",
								__( "Disable Sections", "ts_userpro_for_vc" )							=> "0",
							),
							"description"       => __( "This option can turn on/off field groups/sections even if you have sections enabled on your form. If you set this to 0, sections will be disabled and fields will appear without sections. Default value is 1.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view', 'edit', 'login', 'register', 'reset') ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Form Sections", "ts_userpro_for_vc" ),
							"param_name"        => "keep_one_section_open",
							"width"             => 300,
							"value"             => array(
								__( "Allow Only One Open Section", "ts_userpro_for_vc" )				=> "0",
								__( "Allow Multiple Open Sections", "ts_userpro_for_vc" )				=> "1",
							),
							"description"       => __( "This can be 1 or 0. If this is turned on, only one collapsible section will be open at one time. To disable this, please set this option to 0.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "allow_sections", 'value' => '1' ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Profile Picture Size", "ts_userpro_for_vc" ),
							"param_name"        => "profile_thumb_size",
							"value"             => "100",
							"min"               => "0",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "false",
							"description"       => __( "This option controls the thumbnail size of profile picture in view/edit mode. By default this is set to 100.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Template Alignment", "ts_userpro_for_vc" ),
							"param_name"        => "align",
							"width"             => 300,
							"value"             => array(
								__( "Center", "ts_userpro_for_vc" )							=> "center",
								__( "Left", "ts_userpro_for_vc" )							=> "left",
								__( "Right", "ts_userpro_for_vc" )							=> "right",
								__( "None", "ts_userpro_for_vc" )							=> "none",
							),
							"description"       => __( "Select how the template should be aligned.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "userprovc_width",
							"heading"           => __( "Template Width", "ts_userpro_for_vc" ),
							"param_name"        => "max_width",
							"value"				=> "480px",
							"seperator"         => "Maximum Width",
							"description"       => __( "Define the maximum width for the template.", "ts_userpro_for_vc" ),
							"dependency"        => array( 'element' => "template", 'value' => array('view', 'edit', 'login', 'register', 'reset', 'publish', 'activity') ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Margin: Top", "ts_userpro_for_vc" ),
							"param_name"        => "margin_top",
							"value"             => "0px",
							"min"               => "0",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "true",
							"description"       => __( "Set the top margin of your shortcode. By default, the top margin is 0.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Margin: Bottom", "ts_userpro_for_vc" ),
							"param_name"        => "margin_bottom",
							"value"             => "30px",
							"min"               => "0",
							"max"               => "500",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "true",
							"description"       => __( "Set the bottom margin of your shortcode. By default, the bottom margin is 30px.", "ts_userpro_for_vc" ),
							"group"				=> "Global Settings",
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
			// Social Connect Element [userpro_social_connect]
			function TS_USERPROFORVC_Register_SocialConnect() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro Social Connect", "ts_userpro_for_vc" ),
					"base"                      => "userpro_social_connect",
					"icon" 	                    => "icon-wpb-ts_userprovc_socialconnect",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a Social Connect element.", "ts_userpro_for_vc"),
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// -----------------------
						// Social Connect Settings
						// -----------------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "Social Connect Settings",
						),
						array(
							"type"              => "nouislider",
							"heading"           => __( "Width", "ts_userpro_for_vc" ),
							"param_name"        => "width",
							"value"             => "250px",
							"min"               => "100",
							"max"               => "1024",
							"step"              => "1",
							"unit"              => "px",
							"extraction"		=> "true",
							"admin_label"		=> true,
							"description"       => __( "Specify the width of the buttons in pixels.", "ts_userpro_for_vc" ),
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
			// GridFX Element [gridfx]	
			function TS_USERPROFORVC_Register_GridFX() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro GridFX", "ts_userpro_for_vc" ),
					"base"                      => "gridfx",
					"icon" 	                    => "icon-wpb-ts_userprovc_gridfx",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a GridFX element.", "ts_userpro_for_vc"),
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// ---------------
						// GridFX Settings
						// ---------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "GridFX Settings",
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
			
			// ---------------
			// Add-On Elements
			// ---------------
			
			// Bookmark Element [userpro_bookmark]
			function TS_USERPROFORVC_Register_StandardBookmark() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro Bookmark", "ts_userpro_for_vc" ),
					"base"                      => "userpro_bookmark",
					"icon" 	                    => "icon-wpb-ts_userprovc_bookmark",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a Bookmark (Add-On) element.", "ts_userpro_for_vc"),
					"show_settings_on_create"	=> false,
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// -----------------
						// Bookmark Settings
						// -----------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "Bookmark Settings",
						),
						array(
							"type"              => "messenger",
							"param_name"        => "messenger",
							"color"				=> "#006BB7",
							"weight"			=> "normal",
							"size"				=> "14",
							"message"            => __( "This shortcode currently does not have any setting options provided by UserPro.", "ts_userpro_for_vc" ),
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
			// Public Bookmark Element [userpro_publicbookmark]
			function TS_USERPROFORVC_Register_PublicBookmark() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro Public Bookmark", "ts_userpro_for_vc" ),
					"base"                      => "userpro_publicbookmark",
					"icon" 	                    => "icon-wpb-ts_userprovc_publicbookmark",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a Public Bookmark (Add-On) element.", "ts_userpro_for_vc"),
					"show_settings_on_create"	=> false,
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// -----------------
						// Bookmark Settings
						// -----------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "Public Bookmark Settings",
						),
						array(
							"type"              => "messenger",
							"param_name"        => "messenger",
							"color"				=> "#006BB7",
							"weight"			=> "normal",
							"size"				=> "14",
							"message"            => __( "This shortcode currently does not have any setting options provided by UserPro.", "ts_userpro_for_vc" ),
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
			// Bookmark List Element [userpro_bookmarklist]
			function TS_USERPROFORVC_Register_BookmarkList() {
				global $VISUAL_COMPOSER_USERPRO;
				$VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData = array(
					"name"                      => __( "UserPro Bookmark List", "ts_userpro_for_vc" ),
					"base"                      => "userpro_bookmarklist",
					"icon" 	                    => "icon-wpb-ts_userprovc_bookmarklist",
					"category"                  => __( "VC UserPro", "ts_userpro_for_vc" ),
					"description"               => __("Place a Bookmark List (Add-On) element.", "ts_userpro_for_vc"),
					"show_settings_on_create"	=> false,
					"admin_enqueue_js"        	=> "",
					"admin_enqueue_css"       	=> "",
					"params"                    => array(
						// -----------------
						// Bookmark Settings
						// -----------------
						array(
							"type"              => "seperator",
							"param_name"        => "seperator_1",
							"seperator"         => "Bookmark List Settings",
						),
						array(
							"type"              => "messenger",
							"param_name"        => "messenger",
							"color"				=> "#006BB7",
							"weight"			=> "normal",
							"size"				=> "14",
							"message"            => __( "This shortcode currently does not have any setting options provided by UserPro.", "ts_userpro_for_vc" ),
						),
					)
				);
				if ($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCLeanMapMode == "true") {
					return $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData;
				} else {			
					vc_map($VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_VCElementsData);
				};
			}
		}
	}
	
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_userpro extends WPBakeryShortCode {};
		class WPBakeryShortCode_gridfx extends WPBakeryShortCode {};
		class WPBakeryShortCode_userpro_social_connect extends WPBakeryShortCode {};
		class WPBakeryShortCode_userpro_bookmark extends WPBakeryShortCode {};
		class WPBakeryShortCode_userpro_publicbookmark extends WPBakeryShortCode {};
		class WPBakeryShortCode_userpro_bookmarklist extends WPBakeryShortCode {};
	}

	// Initialize "TS TS_USERPROVC_Elements" Class
	if (class_exists('TS_USERPROVC_Elements')) {
		$TS_USERPROVC_Elements = new TS_USERPROVC_Elements;
	}
?>