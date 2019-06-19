<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_UserProWidth')) {
        class TS_Parameter_UserProWidth {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
                    vc_add_shortcode_param('userprovc_width', array(&$this, 'userprovc_width_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('userprovc_width', array(&$this, 'userprovc_width_settings_field'));
				}
            }        
            function userprovc_width_settings_field($settings, $value) {
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
				$output .= '<div class="ts-userpro-width-container ts-nouislider-input-slider-wrapper clearFixMe ts-settings-parameter-gradient-grey" data-identifier="' . $random_id_number . '" data-responsive="ts-userpro-width-responsive-nouislider-' . $random_id_number . '" data-fixed="ts-userpro-width-fixed-nouislider-' . $random_id_number . '" data-select="ts-userpro-width-type-selector-' . $random_id_number . '" data-hidden="ts-userpro-width-type-value-' . $random_id_number . '">';
					$output .= '<div id="ts-userpro-width-type-label-' . $random_id_number . '" class="wpb_element_label" style="clear: both; font-weight: normal; font-style: italic;">' . __("Select Width Type", "ts_userpro_for_vc") . '</div>';
					$output .= '<select id="ts-userpro-width-type-selector-' . $random_id_number . '" class="ts-userpro-width-type-selector" data-responsive="ts-userpro-width-responsive-nouislider-' . $random_id_number . '" data-fixed="ts-userpro-width-fixed-nouislider-' . $random_id_number . '" data-hidden="ts-userpro-width-type-value-' . $random_id_number . '" data-identifier="' . $random_id_number . '" style="margin-bottom: 10px;">
									<option value="pixels" data-value="pixels" ' . ($responsive == 'false' ? 'selected' : '') . '>' . __('Fixed Width in Pixels', 'ts_userpro_for_vc') . '</option>
									<option value="percent" data-value="percent" ' . ($responsive == 'true' ? 'selected' : '') . '>' . __('Responsive Width in Percent', 'ts_userpro_for_vc') . '</option>
								</select>';
					// Responsive Width
					$output .= '<div id="ts-userpro-width-responsive-nouislider-' . $random_id_number . '" class="ts-userpro-width-responsive-nouislider-input-slider ts-nouislider-input-slider" style="width: 100%; display: ' . ($responsive == 'true' ? 'block' : 'none') . '; float: left;">';			
						$output .= '<input class="ts-nouislider-input-serial nouislider-input-selector nouislider-input-composer" type="text" min="0" max="100" step="1" value="' . $width_r . '"/>';
						$output .= '<span class="ts-nouislider-input-unit">%</span>';
						$output .= '<span class="ts-nouislider-input-min">' . number_format_i18n(0, 0) . '</span>';
						$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left"></span>';
						$output .= '<div id="ts-userprovc-nouislider-input-element-responsive-' . $random_id_number . '" class="ts-nouislider-input ts-nouislider-input-element" data-init="false" data-extract="true" data-callback="" data-pips="false" data-tooltip="false" data-value="' . $width_r . '" data-min="0" data-max="100" data-decimals="0" data-step="1" data-class="responsive" data-unit="%" style="width: 280px; float: left; margin-top: 10px;"></div>';
						$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right"></span>';
						$output .= '<span class="ts-nouislider-input-max">' . number_format_i18n(100, 0) . '</span>';
					$output .= '</div>';
					// Fixed Width
					$output .= '<div id="ts-userpro-width-fixed-nouislider-' . $random_id_number . '" class="ts-userpro-width-fixed-nouislider-input-slider ts-nouislider-input-slider" style="width: 100%; display: ' . ($responsive == 'false' ? 'block' : 'none') . '; float: left;">';
						$output .= '<input class="ts-nouislider-input-serial nouislider-input-selector nouislider-input-composer" type="text" min="1" max="2048" step="1" value="' . $width_f . '"/>';
						$output .= '<span class="ts-nouislider-input-unit">px</span>';
						$output .= '<span class="ts-nouislider-input-min">' . number_format_i18n(1, 0) . '</span>';
						$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left"></span>';
						$output .= '<div id="ts-userprovc-nouislider-input-element-fixed-' . $random_id_number . '" class="ts-nouislider-input ts-nouislider-input-element" data-init="false" data-extract="true" data-callback="" data-pips="false" data-tooltip="false" data-value="' . $width_f . '" data-min="1" data-max="2048" data-decimals="0" data-step="1" data-class="fixed" data-unit="px" style="width: 280px; float: left; margin-top: 10px;"></div>';
						$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right"></span>';
						$output .= '<span class="ts-nouislider-input-max">' . number_format_i18n(2048, 0) . '</span>';
					$output .= '</div>';				
					// Hidden Input with Final Value
					$output .= '<input id="ts-userpro-width-type-value-' . $random_id_number . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' ts-userpro-width-type-value" name="' . $param_name . '" data-responsive="ts-userpro-width-responsive-nouislider-' . $random_id_number . '" data-fixed="ts-userpro-width-fixed-nouislider-' . $random_id_number . '" data-select="ts-userpro-width-type-selector-' . $random_id_number . '" style="display: none;"  value="' . $value . '" ' . $dependency . '/>';
				$output .= '</div>';
				return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_UserProWidth')) {
        $TS_Parameter_UserProWidth = new TS_Parameter_UserProWidth();
    }
?>