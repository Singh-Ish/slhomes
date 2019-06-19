function make_inputh_string(divCheck, showValue, hidden_input_id){
    str = jQuery(hidden_input_id).val();
    if(str!='') show_arr = str.split(',');
    else show_arr = new Array();
    if(jQuery(divCheck).is(':checked')){
        show_arr.push(showValue);
    }else{
        var index = show_arr.indexOf(showValue);
        show_arr.splice(index, 1);
    }
    str = show_arr.join(',');
    jQuery(hidden_input_id).val(str);;
}
function preview_theme(value, dir_url){
    img = dir_url + 'themes/' + value + '/' + value + '.jpg';
    id = '#theme_preview';
    jQuery(id).fadeOut(500, function(){
        jQuery(id).attr('src', img);
    });
    jQuery(id).fadeIn(500);
}
function check_and_h(from, where) {
	if (jQuery(from).is(":checked")) {
		jQuery(where).val(1);
	} else {
		jQuery(where).val(0);
	}
}
function check_mf_selector(checkId, target, cssAttr, valueChecked, valueUnchecked, unselectTarget, unselectCheckbox, hiddenFalse){
    if(jQuery(checkId).is(":checked")){
        jQuery(target).css(cssAttr, valueChecked);
        jQuery(unselectTarget).css(cssAttr, valueUnchecked);
        jQuery(unselectCheckbox).attr('checked', false);
        jQuery(hiddenFalse).val(0);
    }
    else jQuery(target).css(cssAttr, valueUnchecked);
}

function iml_select_team_vc(id, target){
	var the_value = jQuery(id).val();
	if(the_value.indexOf('all')>-1){
		jQuery(id).val('all');
		jQuery(target).val('all');
	}else{
		var new_val = the_value.join(',');
		jQuery(target).val(new_val);
	}
}
function iml_change_post_type_name_vc(base_path){
    jQuery.ajax({
        type : "post",
        url : base_path+'/wp-admin/admin-ajax.php',
        data : {
                   action: "iml_change_post_type_vc",
                   post_name: jQuery('#iml_post_type_name').val()
               },
        success: function(response){
        	if(response!='') window.location = base_path+'/wp-admin/edit.php?post_type='+response+'&page=iml_general_settings_vc';
        }
     });
}