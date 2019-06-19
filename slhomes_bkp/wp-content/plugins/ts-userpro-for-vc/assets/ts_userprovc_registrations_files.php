<?php
    global $VISUAL_COMPOSER_USERPRO;
    $url                                                                        = $VISUAL_COMPOSER_USERPRO->TS_USERPROFORVC_PluginPath;
    // Check if JS Files should be loaded in HEAD or BODY
    if ((get_option('ts_userprovc_settings_loadHeader', 0) == 0)) 	            { $FOOTER = true; } else { $FOOTER = false; }
    
    // Internal Files
    // --------------
    wp_register_style('ts-userprovc-settings',                                  $url . 'css/ts-userprovc-settings.min.css', null, USERPROFORVC_VERSION, 'all');
    wp_register_script('ts-userprovc-settings',						            $url . 'js/ts-userprovc-settings.min.js', array('jquery'), USERPROFORVC_VERSION, $FOOTER);
    wp_register_style('ts-userprovc-composer',				                    $url . 'css/ts-userprovc-composer.min.css', null, USERPROFORVC_VERSION, 'all');
    wp_register_style('ts-userprovc-parameters',				                $url . 'css/ts-userprovc-parameters.min.css', null, USERPROFORVC_VERSION, 'all');
    wp_register_script('ts-userprovc-parameters',						        $url . 'js/ts-userprovc-parameters.min.js', array('jquery'), USERPROFORVC_VERSION, $FOOTER);
    
    
    // Back-End Files
    // --------------
    // NoUiSlider
    wp_register_style('ts-extend-nouislider',									$url . 'css/jquery.vcsc.nouislider.min.css', null, false, 'all');
    wp_register_script('ts-extend-nouislider',									$url . 'js/jquery.vcsc.nouislider.min.js', array('jquery'), false, true);
?>