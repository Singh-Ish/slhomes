<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_UserProTemplate')) {
        class TS_Parameter_UserProTemplate {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
                    vc_add_shortcode_param('userprovc_template', array(&$this, 'userprovc_template_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('userprovc_template', array(&$this, 'userprovc_template_settings_field'));
				}
            }        
            function userprovc_template_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_USERPRO;
                $dependency     	= vc_generate_dependencies_attributes($settings);
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
				$class				= isset($settings['class']) ? $settings['class'] : '';
				// Other Settings
				$random_id_number	= rand(100000, 999999);
                $url            	= $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
                $output         	= '';
				$output .= '<div class="ts-userpro-template-container" data-identifier="' . $random_id_number . '" data-select="ts-userpro-template-selector-' . $random_id_number . '" data-hidden="ts-userpro-template-value-' . $random_id_number . '">';
					$output .= '<select id="ts-userpro-template-selector-' . $random_id_number . '" class="ts-userpro-template-selector" data-hidden="ts-userpro-template-value-' . $random_id_number . '" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">';
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
        }
    }
    if (class_exists('TS_Parameter_UserProTemplate')) {
        $TS_Parameter_UserProTemplate = new TS_Parameter_UserProTemplate();
    }
?>