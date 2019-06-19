<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_UserProUsers')) {
        class TS_Parameter_UserProUsers {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
                    vc_add_shortcode_param('userprovc_users', array(&$this, 'userprovc_users_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('userprovc_users', array(&$this, 'userprovc_users_settings_field'));
				}
            }        
            function userprovc_users_settings_field($settings, $value) {
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
					$output .= '<select id="ts-userpro-member-selector-' . $random_id_number . '" class="ts-userpro-member-selector" data-hidden="ts-userpro-member-value-' . $random_id_number . '" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">';
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
        }
    }
    if (class_exists('TS_Parameter_UserProUsers')) {
        $TS_Parameter_UserProUsers = new TS_Parameter_UserProUsers();
    }
?>