<?php
function imc_checkupdatecf_vc($custom_field, $value, $post_id){
    //create or update a custom field
    $data = get_post_meta($post_id, $custom_field, TRUE);
    if(isset($data)) update_post_meta($post_id, $custom_field, $value);
    else add_post_meta($post_id, $custom_field, $value, TRUE);
}
function imc_metabox_cf_vc( $clients ){
    $link = esc_html(get_post_meta($clients->ID, 'imc_cf_link', true));
    $tool_tip = esc_html(get_post_meta($clients->ID, 'imc_cf_tool_tip', true));
    $str = "<div class='imc_wrap'>";
    $str .="<table class='it-table'>
			    <tr>
			    	<td class='it-label'><i class='icon-external-link'></i> Client Link : </td>
				    <td>
				    	<input type='text' value='$link' name='imc_cf_link' />
				    </td>
			    </tr>
			    <tr>
			    	<td class='it-label' valign='top'><i class='icon-text-width'></i> Description (<span style='font-style:italic;'>Tool Tip</span>) : </td>
				    <td>
				    	<textarea name='imc_cf_tool_tip' id='tool_tip_hidden' >".$tool_tip."</textarea>
				    </td>
			    </tr>
		    </table>
    		<div class='clear'></div></div>";
    echo $str;
}

function imc_save_cfvalues_vc( $post_id ){
    if( isset($_POST['imc_cf_link']) ) imc_checkupdatecf_vc('imc_cf_link', $_POST['imc_cf_link'], $post_id);
    if( isset($_POST['imc_cf_tool_tip']) ){
        $value = strip_tags($_POST['imc_cf_tool_tip']); // REMOVE THE TAGS FROM TOOL TIP
        imc_checkupdatecf_vc('imc_cf_tool_tip', $value, $post_id);
    }
}

function iml_save_update_metas_vc(){
	$arr = iml_general_settings_meta_vc();
	foreach($arr as $key=>$value){
		if(get_option($key)!==FALSE){
			update_option($key, $_REQUEST[$key]);
		}else{
			add_option($key, $_REQUEST[$key]);
		}
	}
}

function iml_general_settings_meta_vc(){
	$arr = array(
			'iml_responsive_settings_small' => 1,
			'iml_responsive_settings_medium' => 2,
			'iml_responsive_settings_large' => 'auto',
			'iml_custom_css' => '',
			'iml_default_logo_img' => IML_DIR_URL_VC.'files/image/default_logo.png',
			'iml_target_blank' => 1,
	);
	foreach($arr as $key=>$value){
		if(get_option($key)!==FALSE){
			$arr[$key] = get_option($key);
		}
	}
	return $arr;
}

function iml_reorder_by_last_name_vc($arr, $order){
	$temp_arr = array();
	$j = 0;
	foreach($arr as $obj){
		$name = get_the_title($obj->ID);
		try {
			if (strpos($name, ' ')!==FALSE){
				$name_arr = explode(' ', $name);
				if ($name_arr){
					$name = '';
					$count_name_arr = count($name_arr);
					for ($x=$count_name_arr-1; $x>=0; $x--){
						$name .= $name_arr[$x];
					}
				}			
			}			
		} catch (Exception $e){}
		
		if (isset($name) && $name!=''){
			if (array_key_exists($name, $temp_arr)){
				$temp_arr[$name.$j] = $obj;
				$j++;
			} else {
				$temp_arr[$name] = $obj;
			}
		} else {
			$temp_arr[] = $obj;
		}
	}
	if ($order=='ASC'){
		ksort($temp_arr);
	} else {
		krsort($temp_arr);
	}
	return $temp_arr;
}

function iml_return_default_logo_vc(){
	$default_logo = get_option('iml_default_logo_img');
	if (!$default_logo) $default_logo = IML_DIR_URL_VC.'files/image/default_logo.png';
	return $default_logo;
}

function iml_default_shortcode_values($arr){
	/*
	 * check every shortcode arg if it's not empty
	* @param array with meta for shortcode
	* @return array
	*/
	$default_arr = array(
							'group' => 'all',
							'limit' => 10,
							'order_by' => '',
							'order' => 'ASC',
							'entry_information' => '',
							'show' => 'logo',
							'template' => '',
							'theme' => '',
							'effect' => '',
							'columns' => '',
							'align_center' => 0,
							'item_height' => 100,
							'slider_set' => 0,
							'items_per_slide' => 6,
							'slide_speed' => 5000,
							'slide_pagination_speed' => 500,
							'slide_opt' => 'bullets,nav_button,autoplay,stop_hover,responsive,autoheight,loop',
							'pagination_theme' => '',
							'animation_in' => '',
							'animation_out' => '',
							'filter_title' => '',
							'filter_set' => 0,
							'filter_select_t' => '',
							'filter_align' => '',
							'layout_mode' => '', 
						);
	foreach ($default_arr as $k=>$v){
		if (empty($arr[$k])) $arr[$k] = $v;
	}
	return $arr;
}