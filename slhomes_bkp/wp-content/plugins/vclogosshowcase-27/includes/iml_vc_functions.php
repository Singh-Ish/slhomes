<?php
////////////VISUAL COMPOSER FUNCTIONS
function iml_select_cats_settings_field($settings, $value){
    $args = array(
                    'type'                     => IML_POST_TYPE_VC,
                    'child_of'                 => 0,
                    'parent'                   => '',
                    'orderby'                  => 'name',
                    'order'                    => 'ASC',
                    'hide_empty'               => 1,
                    'hierarchical'             => 1,
                    'exclude'                  => '',
                    'include'                  => '',
                    'number'                   => '',
                    'taxonomy'                 => IML_TAXONOMY_VC,
                    'pad_counts'               => false
                );
    $categories = get_categories( $args );
    if(strpos($value, ',')!==FALSE){
    	$value_arr = explode(',', $value);
    }else $value_arr[] = $value;
    $str = "";
    $str .= "<select onChange='iml_select_team_vc(this, \"#hidden_select_cats_vc\");' multiple>";
    $selected = "";
    if(in_array('all', $value_arr)) $selected = "selected='seleted'";
    $str .="<option value='all' $selected>" . __('All', 'iml') . "</option>";
    if(isset($categories) && is_array($categories)){
    	foreach($categories as $cat){
    	    $selected = "";
    	    if(in_array($cat->slug, $value_arr)) $selected = "selected='seleted'";
    		$str .= "<option value='".$cat->slug."' $selected>".$cat->name."</option>";
    	}
    }
    $str .= "</select>";
    $str .= "<input type='hidden' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' value='$value' id='hidden_select_cats_vc' name='".$settings['param_name']."' />";
    return $str;
}
function iml_number_settings_field($settings, $value){
    $str = "";
    if(isset($settings['label']) && $settings['label']!='') $str .= "<div>".$settings['label']."</div>";
    $str .= "<input name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' min='".$settings['param_min_value']."' type='number' value='$value' />";
    if(isset($settings['wrap_div']) && $settings['wrap_div']==TRUE){
        global $imt_global_set_arr_val;
        if(isset($imt_global_set_arr_val[$settings['slider_or_filter']]) && $imt_global_set_arr_val[$settings['slider_or_filter']]==TRUE) $opacity = 1;
        else $opacity = '0.5';
        $str = "<div style='opacity: $opacity;' class='".$settings['wrap_class']."'>".$str."</div>";
    }
    return $str;
}
  
function iml_checkbox_field_settings_field($settings, $value){
    $str = "";
    if(isset($settings['slider_or_filter'])){
    	global $imt_global_set_arr_val;
    	$imt_global_set_arr_val[$settings['slider_or_filter']] = FALSE;    	
    }
    $checked = "";
    if($value==1 ){
        $checked = "checked='checked'";
        if(isset($settings['slider_or_filter'])) $imt_global_set_arr_val[$settings['slider_or_filter']] = TRUE;
    }
    $str .= "<input type='checkbox' onClick='check_and_h(this, \"#".$settings['id_hidden']."\");".$settings['onClick']."' $checked id='".$settings['id_checkbox']."'/>".$settings['label'];
    $str .= "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='".$settings['id_hidden']."' />";
    if(isset($settings['extra_text']) && $settings['extra_text']!='') $str .= $settings['extra_text'];
    return $str;
}
function iml_checkbox_block_settings_field($settings, $value) {
	$str = "";
    if(isset($settings['checkboxes']) && count($settings['checkboxes'])>0){
    	foreach($settings['checkboxes'] as $k=>$v){
    	    $checked = "";
    	    if(strpos($value, $k)!==false) $checked = "checked='checked'";
    		$str .= "<div><input type='checkbox' value='$k' onClick=\"make_inputh_string(this, '$k', '#".$settings['hidden_id']."');\" $checked />$v</div>";
    	}
    }
    $str .= "<input name='".$settings['hidden_name']."' class='wpb_vc_param_value  ".$settings['hidden_name']." ".$settings['type']."_field' type='hidden' value='$value' id='".$settings['hidden_id']."' />";
    if(isset($settings['wrap_div']) && $settings['wrap_div']==TRUE){
        global $imt_global_set_arr_val;
        if(isset($imt_global_set_arr_val[$settings['slider_or_filter']]) && $imt_global_set_arr_val[$settings['slider_or_filter']]==TRUE) $opacity = 1;
        else $opacity = '0.5';
        $str = "<div style='opacity: $opacity;' class='".$settings['wrap_class']."'>".$str."</div>";
    }
    return $str;
}
function iml_select_themes_settings_field($settings, $value){
    $dir_path = plugin_dir_path(__FILE__);
    $dir_path = str_replace('includes', '', $dir_path);
    $handle = opendir( $dir_path . 'themes' );
    while (false !== ($entry = readdir($handle))) {
        if( $entry!='.' && $entry!='..' ){
            $arr_str = explode('_', $entry);
            $themes_arr[$arr_str[1]] = $arr_str[0];
        }
    }
    ksort($themes_arr);
    $dir_url = str_replace( 'includes/', '', plugin_dir_url(__FILE__) );
    $str = "";
    $str .= "<select class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' name='".$settings['param_name']."' onChange='preview_theme(this.value, \"$dir_url\");'>";
    foreach($themes_arr as $key=>$theme){
        $v = strtolower($theme) . '_' . $key;
        $label = ucfirst($theme) . ' ' . $key;
        $selected = "";
        if($value==$v){
            $selected = "selected='selected'";
        }
        $str .= "<option value='$v' $selected >$label</option>";
        if($selected!='' || !isset($img)) $img = $v;
    }
    $str .= "</select>";
    $img = $dir_url . 'themes/' . $img . '/'.$img.'.jpg';
    $str .= "<img src='$img' class='theme_preview' id='theme_preview'>";
    return $str;
}
function iml_return_effect_dd_settings_field($settings, $value){
    $arr = array("Attention Seekers" => array(
                        "bounce",
                        "pulse",
                        "rubberBand",
                        "shake",
                        "swing",
                        "tada",
                        "wobble"
                    ),
                   "Bouncing Entrances" => array(
                        "bounceIn",
                        "bounceInDown",
                        "bounceInLeft",
                        "bounceInRight",
                        "bounceInUp"
                   ),
                   "Fading Entrances" => array(
                        "fadeIn",
                        "fadeInDown",
                        "fadeInLeft",
                        "fadeInRight",
                        "fadeInUp"
                   ),
                   "Flippers" => array(
                        "flip",
                        "flipInX",
                        "flipInY"
                   ),
                   "Rotating Entrances" => array(
                        "rotateIn",
                        "rotateInDownLeft",
                        "rotateInDownRight",
                        "rotateInUpLeft",
                        "rotateInUpRight"
                   )
                   );
    $str = "";
    $str = "<select class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' name='".$settings['param_name']."' >
                <option value=''>...</option>";
    foreach($arr as $k=>$v){
        $str .= "<optgroup label='$k'>";
        foreach($v as $val){
            $selected = "";
            if($value==$val) $selected = "selected='selected'";
            $str .= "<option value='$val' $selected >$val</option>";
        }
        $str .= "</optgroup>";
    }
    $str .= "</select>";
    return $str;
}
function iml_custom_dropdown_settings_field($settings, $value){
	$str = "";
    if(isset($settings['values']) && count($settings['values'])>0){
        if (isset($settings['label'])) $str .= "<div>".$settings['label']."</div>";
        $str .= "<select class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' name='".$settings['param_name']."'>";
    	foreach($settings['values'] as $k=>$v){
    	    $selected = "";
    	    if(strpos($value, $k)!==false) $selected = "selected='selected'";
    		$str .= "<option value='$k' $selected >$v</option>";
    	}
        $str .= "</select>";
    }
    if(isset($settings['sublabel_div'])) $str .= $settings['sublabel_div'];
    if(isset($settings['wrap_div']) && $settings['wrap_div']==TRUE){
        global $imt_global_set_arr_val;
        if(isset($imt_global_set_arr_val[$settings['slider_or_filter']]) && $imt_global_set_arr_val[$settings['slider_or_filter']]==TRUE) $opacity = 1;
        else $opacity = '0.5';
        $str = "<div style='opacity: $opacity;' class='".$settings['wrap_class']."'>".$str."</div>";
    }
    return $str;
}
function iml_custom_dd_settings_field($settings, $value){
    $str = "";
    $str .= "<select id='".$settings['select_id']."' onclick=\"".$settings['onclick']."\" name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field imtst_custom_dd'>";
    	foreach($settings['values'] as $k=>$v){
    	    $selected = "";
    	    if(strpos($value, $k)!==false) $selected = "selected='selected'";
    		$str .= "<option value='$k' $selected >$v</option>";
    	}
    $str .= "</select>";
    return $str;
}

function iml_checkbox_field_actv_settings_field($settings, $value){
	$str = "";
	if(isset($settings['slider_or_filter'])){
		global $imt_global_set_arr_val;
		$imt_global_set_arr_val[$settings['slider_or_filter']] = FALSE;
		if($value==1){
			$imt_global_set_arr_val[$settings['slider_or_filter']] = TRUE;
		}
	}
	$checked = "";
	if($value==1){
		$checked = "checked='checked'";
	}
	$str .= "<input type='checkbox' onClick='check_and_h(this, \"#".$settings['id_hidden']."\");".$settings['onClick']."' $checked id='".$settings['id_checkbox']."'/>".$settings['label'];
	$str .= "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='".$settings['id_hidden']."' />";
	if(isset($settings['extra_text']) && $settings['extra_text']!='') $str .= $settings['extra_text'];
	return $str;
}


function iml_sepparate_line_settings_field($settings, $value){
	return "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='' />";
}

function iml_title_block_iml_bkcolor6_settings_field($settings, $value){
	return "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='' />";
}

function iml_title_block_iml_bkcolor1_settings_field($settings, $value){
	return "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='' />";
}

function iml_title_block_iml_bkcolor2_settings_field($settings, $value){
	return "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='' />";
}

function iml_title_block_iml_bkcolor3_settings_field($settings, $value){
	return "<input type='hidden' value='$value' name='".$settings['param_name']."' class='wpb_vc_param_value  ".$settings['param_name']." ".$settings['type']."_field' id='' />";
}


//// assign them all to VC
if (defined('WPB_VC_VERSION')){
	if (version_compare(WPB_VC_VERSION, '4.4')==1){
		/// > 4.4
		vc_add_shortcode_param('iml_select_cats', 'iml_select_cats_settings_field');
		vc_add_shortcode_param('iml_number', 'iml_number_settings_field');
		vc_add_shortcode_param('iml_checkbox_field', 'iml_checkbox_field_settings_field');
		vc_add_shortcode_param('iml_checkbox_block', 'iml_checkbox_block_settings_field');
		vc_add_shortcode_param('iml_select_themes', 'iml_select_themes_settings_field');
		vc_add_shortcode_param('iml_return_effect_dd', 'iml_return_effect_dd_settings_field');
		vc_add_shortcode_param('iml_custom_dropdown', 'iml_custom_dropdown_settings_field');
		vc_add_shortcode_param('iml_custom_dd', 'iml_custom_dd_settings_field');
		vc_add_shortcode_param('iml_checkbox_field_actv', 'iml_checkbox_field_actv_settings_field');
		vc_add_shortcode_param('iml_sepparate_line', 'iml_sepparate_line_settings_field');
		vc_add_shortcode_param('iml_title_block_iml_bkcolor6', 'iml_title_block_iml_bkcolor6_settings_field');
		vc_add_shortcode_param('iml_title_block_iml_bkcolor1', 'iml_title_block_iml_bkcolor1_settings_field');
		vc_add_shortcode_param('iml_title_block_iml_bkcolor2', 'iml_title_block_iml_bkcolor2_settings_field');
		vc_add_shortcode_param('iml_title_block_iml_bkcolor3', 'iml_title_block_iml_bkcolor3_settings_field');		
		
	} else {
		/// < 4.4
		add_shortcode_param('iml_select_cats', 'iml_select_cats_settings_field');
		add_shortcode_param('iml_number', 'iml_number_settings_field');
		add_shortcode_param('iml_checkbox_field', 'iml_checkbox_field_settings_field');
		add_shortcode_param('iml_checkbox_block', 'iml_checkbox_block_settings_field');
		add_shortcode_param('iml_select_themes', 'iml_select_themes_settings_field');
		add_shortcode_param('iml_return_effect_dd', 'iml_return_effect_dd_settings_field');
		add_shortcode_param('iml_custom_dropdown', 'iml_custom_dropdown_settings_field');
		add_shortcode_param('iml_custom_dd', 'iml_custom_dd_settings_field');
		add_shortcode_param('iml_checkbox_field_actv', 'iml_checkbox_field_actv_settings_field');
		add_shortcode_param('iml_sepparate_line', 'iml_sepparate_line_settings_field');
		add_shortcode_param('iml_title_block_iml_bkcolor6', 'iml_title_block_iml_bkcolor6_settings_field');
		add_shortcode_param('iml_title_block_iml_bkcolor1', 'iml_title_block_iml_bkcolor1_settings_field');
		add_shortcode_param('iml_title_block_iml_bkcolor2', 'iml_title_block_iml_bkcolor2_settings_field');
		add_shortcode_param('iml_title_block_iml_bkcolor3', 'iml_title_block_iml_bkcolor3_settings_field');		
	}
}