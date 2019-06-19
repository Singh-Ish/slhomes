<?php
    if (!class_exists('TS_USERPROVC_Parameters')) {
        class TS_USERPROVC_Parameters {
			private static $instance;	    
            function __construct() {
				if (isset(self::$instance )) {
					return self::$instance;
				} else {
					self::$instance = &$this;
					if (function_exists('vc_add_shortcode_param')) {
						vc_add_shortcode_param('userprovc_users', 			array(&$this, 'registered_users_settings_field'));
						vc_add_shortcode_param('userprovc_template', 		array(&$this, 'template_type_settings_field'));
						vc_add_shortcode_param('userprovc_width', 			array(&$this, 'template_width_settings_field'));
						vc_add_shortcode_param('userprovc_switch', 			array(&$this, 'switch_button_settings_field'));
						vc_add_shortcode_param('userprovc_nouislider', 		array(&$this, 'nouislider_settings_field'));
						vc_add_shortcode_param('userprovc_file', 			array(&$this, 'loadfile_setting_field'));
						vc_add_shortcode_param('userprovc_seperator', 		array(&$this, 'seperator_settings_field'));
						vc_add_shortcode_param('userprovc_messenger', 		array(&$this, 'messenger_settings_field'));
					} else if (function_exists('add_shortcode_param')) {
						add_shortcode_param('userprovc_users', 				array(&$this, 'registered_users_settings_field'));
						add_shortcode_param('userprovc_template', 			array(&$this, 'template_type_settings_field'));
						add_shortcode_param('userprovc_width', 				array(&$this, 'template_width_settings_field'));
						add_shortcode_param('userprovc_switch', 			array(&$this, 'switch_button_settings_field'));
						add_shortcode_param('userprovc_nouislider', 		array(&$this, 'nouislider_settings_field'));
						add_shortcode_param('userprovc_file', 				array(&$this, 'loadfile_setting_field'));
						add_shortcode_param('userprovc_seperator', 			array(&$this, 'seperator_settings_field'));
						add_shortcode_param('userprovc_messenger', 			array(&$this, 'messenger_settings_field'));
					}
				}
            }
			function registered_users_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
				$class				= isset($settings['class']) ? $settings['class'] : '';
				// Other Settings
				$random_id_number	= rand(100000, 999999);
                $url            	= $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         	= '';
				$args = array(
					'blog_id'      => $GLOBALS['blog_id'],
					'role'         => '',
					'meta_key'     => '',
					'meta_value'   => '',
					'meta_compare' => '',
					'meta_query'   => array(),
					'include'      => array(),
					'exclude'      => array(),
					'orderby'      => 'ID',
					'order'        => 'ASC',
					'offset'       => '',
					'search'       => '',
					'number'       => '',
					'count_total'  => false,
					'fields'       => 'all',
					'who'          => ''
				);
				$blogusers			= get_users($args);
				$output .= '<div class="ts-userpro-member-container" data-identifier="' . $random_id_number . '">';
					$output .= '<select id="ts-userpro-member-selector-' . $random_id_number . '" class="ts-userpro-member-selector" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">';
						$output .= '<option value="" data-value="" data-type="global" data-templates="view,edit,card,postsbyuser" ' . selected($value, "") . '>Current User</option>';
						$output .= '<option value="loggedin" data-value="loggedin" data-type="global" data-templates="followers,following" ' . selected($value, "loggedin") . '>Logged-In User</option>';
						$output .= '<option value="author" data-value="author" data-type="global" data-templates="view,edit,card,postsbyuser,followers,following" ' . selected($value, "author") . '>Post Author</option>';
						$output .= '<optgroup label="Registered Users">';
						foreach ($blogusers as $user) {
							$output .= '<option value="' . esc_html($user->display_name) . '" data-value="' . esc_html($user->user_login) . '" data-type="single" data-templates="all" ' . selected($value, esc_html($user->user_login)) . '>' . esc_html($user->display_name) . ' (' . esc_html($user->ID) . ')</option>';
						}
					$output .= '</select>';
					// Hidden Input with Final Value
					$output .= '<input id="ts-userpro-member-value-' . $random_id_number . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' ts-userpro-member-value" name="' . $param_name . '"  style="display: none;"  value="' . $value . '" ' . $dependency . '/>';
				$output .= '</div>';
				return $output;
			}
			function template_type_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
				$class				= isset($settings['class']) ? $settings['class'] : '';
				// Other Settings
				$random_id_number	= rand(100000, 999999);
                $url            	= $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         	= '';
				$output .= '<div class="ts-userpro-template-container" data-identifier="' . $random_id_number . '">';
					$output .= '<select id="ts-userpro-template-selector-' . $random_id_number . '" class="ts-userpro-template-selector" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">';
						$output .= '<option value="" data-value="" disabled ' . ($value == '' ? 'selected' : '') . ' style="display:none;">' . __( "Select Template Type", "ts_userpro_for_vc" ) . '</option>';
						// General Forms
						$output .= '<optgroup label="General Forms">';
						$output .= '<option value="login" data-value="login" ' . selected($value, "login") . '>' . __( "Login Form", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="register" data-value="register" ' . selected($value, "register") . '>' . __( "Registration Form", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="reset" data-value="reset" ' . selected($value, "reset") . '>' . __( "Password Reset Form", "ts_userpro_for_vc" ) . '</option>';
						// Member Listings
						$output .= '<optgroup label="Member Listings">';
						$output .= '<option value="list" data-value="list" ' . selected($value, "list") . '>' . __( "List", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="memberlist" data-value="memberlist" ' . selected($value, "memberlist") . '>' . __( "Member Directory", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="emd" data-value="emd" ' . selected($value, "emd") . '>' . __( "Enhanced Member Directory", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="collage" data-value="collage" ' . selected($value, "collage") . '>' . __( "Collage", "ts_userpro_for_vc" ) . '</option>';
						// Single User Templates
						$output .= '<optgroup label="Single User Templates">';
						$output .= '<option value="view" data-value="view" ' . selected($value, "view") . '>' . __( "View Profile", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="edit" data-value="edit" ' . selected($value, "edit") . '>' . __( "Edit Profile", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="card" data-value="card" ' . selected($value, "card") . '>' . __( "Profile Card", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="postsbyuser" data-value="postsbyuser" ' . selected($value, "postsbyuser") . '>' . __( "Posts By User", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="followers" data-value="followers" ' . selected($value, "followers") . '>' . __( "Followers", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="following" data-value="following" ' . selected($value, "following") . '>' . __( "Following", "ts_userpro_for_vc" ) . '</option>';
						// Other Templates
						$output .= '<optgroup label="Other Templates">';
						$output .= '<option value="publish" data-value="publish" ' . selected($value, "publish") . '>' . __( "Frontend Publisher", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="activity" data-value="activity" ' . selected($value, "activity") . '>' . __( "Activity Feed", "ts_userpro_for_vc" ) . '</option>';
						// Add-On Templates
						$output .= '<optgroup label="3rd Party Add-On Templates">';
						$output .= '<option value="socialwall" data-value="socialwall" ' . selected($value, "socialwall") . '>' . __( "Social Wall (Add-On)", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="rating" data-value="rating" ' . selected($value, "rating") . '>' . __( "Rating (Add-On)", "ts_userpro_for_vc" ) . '</option>';
						$output .= '<option value="reviews" data-value="reviews" ' . selected($value, "reviews") . '>' . __( "Reviews (Add-On)", "ts_userpro_for_vc" ) . '</option>';
					$output .= '</select>';
					// Hidden Input with Final Value
					$output .= '<input id="ts-userpro-template-value-' . $random_id_number . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' ts-userpro-template-value" name="' . $param_name . '"  style="display: none;"  value="' . $value . '" ' . $dependency . '/>';
				$output .= '</div>';
				return $output;
			}
			function template_width_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
				$class				= isset($settings['class']) ? $settings['class'] : '';
				// Other Settings
				$random_id_number	= rand(100000, 999999);
                $url            	= $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         	= '';				
				if (strpos($value, '%') !== FALSE) {
					$responsive		= 'true';
					$width_r		= str_replace("%", "", $value);
					$width_f		= 480;
				} else {
					$responsive		= 'false';
					$width_r		= 100;
					$width_f		= str_replace("px", "", $value);
				}
				// Create Output
				$output .= '<div class="ts-userpro-width-container" data-identifier="' . $random_id_number . '">';
					$output .= '<div id="ts-userpro-width-type-label-' . $random_id_number . '" class="wpb_element_label" style="padding-top: 10px; clear: both; font-weight: normal; font-style: italic;">' . __("Select Width Type", "ts_userpro_for_vc") . '</div>';
					$output .= '<select id="ts-userpro-width-type-selector-' . $random_id_number . '" class="ts-userpro-width-type-selector" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">
						<option value="pixels" data-value="pixels" ' . ($responsive == 'false' ? 'selected' : '') . '>' . __('Fixed Width in Pixels', 'ts_userpro_for_vc') . '</option>
						<option value="percent" data-value="percent" ' . ($responsive == 'true' ? 'selected' : '') . '>' . __('Responsive Width in Percent', 'ts_userpro_for_vc') . '</option>
					</select>';
					// Responsive Width
					$output .= '<div id="ts-userpro-width-responsive-nouislider-' . $random_id_number . '" class="ts-userpro-width-responsive-nouislider-input-slider" style="width: 100%; display: ' . ($responsive == 'true' ? 'block' : 'none') . '; float: left;">';
						$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px; background: #f5f5f5; color: #666666;" class="ts-userprovc-nouislider-serial nouislider-input-selector nouislider-input-composer ts-userpro-width-responsive-nouislider-input" type="text" min="1" max="100" step="1" value="' . $width_r . '"/>';
						$output .= '<span style="float: left; margin-right: 30px; margin-top: 10px; min-width: 10px;" class="unit">%</span>';
						$output .= '<span class="ts-userprovc-nouislider-input-down dashicons-arrow-left-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0;"></span>';
						$output .= '<span class="ts-userprovc-nouislider-input-up dashicons-arrow-right-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0 20px 0 0;"></span>';
						$output .= '<div id="ts-userprovc-nouislider-input-element-responsive-' . $random_id_number . '" class="ts-userprovc-nouislider-input ts-userprovc-nouislider-input-element" data-class="responsive" data-value="' . $width_r . '" data-unit="%" data-extract="false" data-min="1" data-max="100" data-decimals="0" data-step="1" style="width: 320px; float: left; margin-top: 10px;"></div>';
					$output .= '</div>';
					// Fixed Width
					$output .= '<div id="ts-userpro-width-fixed-nouislider-' . $random_id_number . '" class="ts-userpro-width-fixed-nouislider-input-slider" style="width: 100%; display: ' . ($responsive == 'false' ? 'block' : 'none') . '; float: left;">';
						$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px; background: #f5f5f5; color: #666666;" class="ts-userprovc-nouislider-serial nouislider-input-selector nouislider-input-composer ts-userpro-width-fixed-nouislider-input" type="text" min="1" max="2048" step="1" value="' . $width_f . '"/>';
						$output .= '<span style="float: left; margin-right: 30px; margin-top: 10px; min-width: 10px;" class="unit">px</span>';
						$output .= '<span class="ts-userprovc-nouislider-input-down dashicons-arrow-left-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0;"></span>';
						$output .= '<span class="ts-userprovc-nouislider-input-up dashicons-arrow-right-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0 20px 0 0;"></span>';
						$output .= '<div id="ts-userprovc-nouislider-input-element-fixed-' . $random_id_number . '-2" class="ts-userprovc-nouislider-input ts-userprovc-nouislider-input-element" data-class="fixed" data-value="' . $width_f . '" data-unit="%" data-extract="false" data-min="1" data-max="2048" data-decimals="0" data-step="1" style="width: 320px; float: left; margin-top: 10px;"></div>';
					$output .= '</div>';				
					// Hidden Input with Final Value
					$output .= '<input id="ts-userpro-width-type-value-' . $random_id_number . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' ts-userpro-width-type-value" name="' . $param_name . '"  style="display: none;"  value="' . $value . '" ' . $dependency . '/>';
				$output .= '</div>';
				return $output;
			}
            function switch_button_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     = vc_generate_dependencies_attributes($settings);
                $param_name     = isset($settings['param_name'])    ? $settings['param_name']   : '';
                $type           = isset($settings['type'])          ? $settings['type']         : '';
                $on            	= isset($settings['on'])            ? $settings['on']           : __( "Yes", "ts_userpro_for_vc" );
                $off            = isset($settings['off'])           ? $settings['off']          : __( "No", "ts_userpro_for_vc" );
                $style			= isset($settings['style'])         ? $settings['style']        : 'compact'; 			// 'compact' or 'select'
                $width			= isset($settings['width'])         ? $settings['width']        : '100';
                $suffix         = isset($settings['suffix'])        ? $settings['suffix']       : '';
                $class          = isset($settings['class'])         ? $settings['class']        : '';
				$render			= isset($settings['render'])        ? $settings['render']       : 'string';
                $url            = $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         = '';
                $output .= '<div class="ts-switch-button ts-userprovc-switch" data-value="' . (($value == 'true' || $value == 1) ? 'true' : 'false') . '" data-render="' . $render . '" data-width="' . $width . '" data-style="' . $style . '" data-on="' . $on . '" data-off="' . $off . '">';
                    $output .= '<input type="hidden" style="display: none; " class="toggle-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" value="' . $value . '" name="' . $param_name . '"/>';
					$output .= '<input type="hidden" style="display: none; " class="toggle-input" value="' . (($value == 'true' || $value == 1) ? 'true' : 'false') . '"/>';
                    $output .= '<div class="toggle toggle-light" style="width: ' . $width . 'px; height: 20px;">';
                        $output .= '<div class="toggle-slide">';
                            $output .= '<div class="toggle-inner">';
                                $output .= '<div class="toggle-on ' . (($value == 'true' || $value == 1) ? 'active' : '') . '">' . $on . '</div>';
                                $output .= '<div class="toggle-blob"></div>';
                                $output .= '<div class="toggle-off ' . (($value == 'false' || $value == 0) ? 'active' : '') . '">' . $off . '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</div>';
                $output .= '</div>';
                return $output;
            }
            function nouislider_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
                $min            	= isset($settings['min']) ? $settings['min'] : '';
                $max            	= isset($settings['max']) ? $settings['max'] : '';
                $step           	= isset($settings['step']) ? $settings['step'] : '';
                $unit           	= isset($settings['unit']) ? $settings['unit'] : '';
				$extraction			= isset($settings['extraction']) ? $settings['extraction'] : 'true';
                $decimals			= isset($settings['decimals']) ? $settings['decimals'] : 0;
                $suffix         	= isset($settings['suffix']) ? $settings['suffix'] : '';
                $class          	= isset($settings['class']) ? $settings['class'] : '';
				// Other Settings
				$random_id_number	= rand(100000, 999999);
                $url            	= $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
				if ($extraction == "true") {
					$slidervalue	= preg_replace("/[^0-9]/", "", $value);
				} else {
					$slidervalue	= $value;
				}
                $output         	= '';
                $output .= '<div class="ts-userprovc-nouislider-input-slider">';
                    $output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px; background: #f5f5f5; color: #666666;" class="ts-userprovc-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $slidervalue . '"/>';
                    $output .= '<span style="float: left; margin-right: 30px; margin-top: 10px; min-width: 10px;" class="unit">' . $unit . '</span>';
					$output .= '<span class="ts-userprovc-nouislider-input-down dashicons-arrow-left-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0;"></span>';
					$output .= '<span class="ts-userprovc-nouislider-input-up dashicons-arrow-right-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0 20px 0 0;"></span>';
                    $output .= '<div id="ts-userprovc-nouislider-input-element-' . $random_id_number . '" class="ts-userprovc-nouislider-input ts-userprovc-nouislider-input-element" data-class="general" data-unit="' . $unit . '" data-extract="' . $extraction . '" data-value="' . $slidervalue . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 320px; float: left; margin-top: 10px;"></div>';
					// Hidden Input with Final Value
					$output .= '<input id=ts-userprovc-nouislider-value-' . $random_id_number . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' ts-userprovc-nouislider-value" name="' . $param_name . '"  style="display: none;"  value="' . $value . '" ' . $dependency . '/>';
				$output .= '</div>';
                return $output;
            }
            function loadfile_setting_field($settings, $value){
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     = vc_generate_dependencies_attributes($settings);
                $param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           = isset($settings['type']) ? $settings['type'] : '';
                $file_type      = isset($settings['file_type']) ? $settings['file_type'] : '';
                $file_id      	= isset($settings['file_id']) ? $settings['file_id'] : '';
                $file_path      = isset($settings['file_path']) ? $settings['file_path'] : '';
                $url            = $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         = '';
                if (!empty($file_path)) {
                    if ($file_type == "js") {
                        $output .= '<script type="text/javascript" src="' . $url.$file_path . '"></script>';
                    } else if ($file_type == "css") {
                        $output .= '<link rel="stylesheet" id="' . $file_id . '" type="text/css" href="' . $url.$file_path . '" media="all">';
                    }
                }
                return $output;
            }
            function seperator_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     = vc_generate_dependencies_attributes($settings);
                $param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           = isset($settings['type']) ? $settings['type'] : '';
                $seperator		= isset($settings['seperator']) ? $settings['seperator'] : '';
                $url            = $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         = '';
                if ($seperator != '') {
                    $output		.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="border-bottom: 2px solid #DDDDDD; margin-bottom: 10px; margin-top: 10px; padding-bottom: 10px; font-size: 20px; font-weight: bold; color: #BFBFBF;">' . $seperator . '</div>';
                } else {
                    $output		.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="border-bottom: 2px solid #DDDDDD; margin-bottom: 10px; margin-top: 10px; padding-bottom: 10px; font-size: 20px; font-weight: bold; color: #BFBFBF;">' . $value . '</div>';
                }
                return $output;
            }
            function messenger_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     = vc_generate_dependencies_attributes($settings);
                $param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
                $message        = isset($settings['message']) ? $settings['message'] : '';
                $type           = isset($settings['type']) ? $settings['type'] : '';
                $suffix         = isset($settings['suffix']) ? $settings['suffix'] : '';
                $class          = isset($settings['class']) ? $settings['class'] : '';
                $color			= isset($settings['color']) ? $settings['color'] : '#000000';
                $weight			= isset($settings['weight']) ? $settings['weight'] : 'normal';
                $size			= isset($settings['size']) ? $settings['size'] : '12';            
                $margin_top     = isset($settings['margin_top']) ? $settings['margin_top'] : '10';
                $margin_bottom  = isset($settings['margin_bottom']) ? $settings['margin_bottom'] : '10';
                $padding_top    = isset($settings['padding_top']) ? $settings['padding_top'] : '10';
                $padding_bottom = isset($settings['padding_bottom']) ? $settings['padding_bottom'] : '10';
                $border_top     = isset($settings['border_top']) ? $settings['border_top'] : 'true';
                $border_bottom  = isset($settings['border_bottom']) ? $settings['border_bottom'] : 'true';
                $url            = $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         = '';
                if ($message != '') {
                    $output 	.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="text-align: justify; ' . ($border_top == "true" ? "border-top: 1px solid #dddddd;" : "") . ' ' . ($border_bottom == "true" ? "border-bottom: 1px solid #dddddd;" : "") . ' color: ' . $color . '; margin: ' . $margin_top . 'px 0 ' . $margin_bottom . 'px 0; padding: ' . $padding_top . 'px 0 ' . $padding_bottom . 'px 0; font-size: ' . $size . 'px; font-weight: ' . $weight . ';">' . $message . '</div>';
                } else {
                    $output 	.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="text-align: justify; ' . ($border_top == "true" ? "border-top: 1px solid #dddddd;" : "") . ' ' . ($border_bottom == "true" ? "border-bottom: 1px solid #dddddd;" : "") . ' color: ' . $color . '; margin: ' . $margin_top . 'px 0 ' . $margin_bottom . 'px 0; padding: ' . $padding_top . 'px 0 ' . $padding_bottom . 'px 0; font-size: ' . $size . 'px; font-weight: ' . $weight . ';">' . $value . '</div>';
                }
                return $output;
            }
		}
    }
    if (class_exists('TS_USERPROVC_Parameters')) {
        $TS_USERPROVC_Parameters = new TS_USERPROVC_Parameters();
    }
?>