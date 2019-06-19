function arf_ChangeID(id)
{
	document.getElementById('delete_entry_id').value = id;
	arfchangedeletemodalwidth('arfdeletemodabox');
}
		
function arfuserform_action(act, id){
	if(act == 'delete')
	{
		id = document.getElementById('delete_entry_id').value; 
	}
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_user_registration_delete_form&act="+act+"&id="+id,
		success:function(msg){ 			
			jQuery('#arf_user_registration_forms').html(msg);
			jQuery(".sltstandard select").selectpicker();  
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#arf_user_registration_forms .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
	
	if(act == 'delete')
	{
		jQuery('[data-dismiss="arfmodal"]').trigger("click");
	}
	return false;	
}

function arf_user_registration_form_bulk_act(){
	var str = jQuery('form').serialize();
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_user_registration_form_bulk_act&"+str,
		success:function(msg){
			jQuery('#arf_user_registration_forms').html(msg); 
			jQuery(".sltstandard select").selectpicker();  
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#arf_user_registration_forms .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
	return false;
}

function arf_user_registration_save(){
	var req = 0;
	
	if( jQuery('#arf_user_registration_form').val() == '' ) {
		jQuery('.arf_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_user_registration_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_user_registration_form_msg').css('display', 'none');	
	}	
	
	if( jQuery('#arf_username').val() == '' ) {
		jQuery('.arf_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_username_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_username_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_username_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_username_msg').css('display', 'none');	
	}
	
	if( jQuery('#arf_email').val() == '' ) {
		jQuery('.arf_email_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_email_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_email_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_email_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_email_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_email_msg').css('display', 'none');	
	}
	
	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#form_name').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;
}

function arf_user_regisration_form_change(){
	
	var form_id = jQuery('#arf_user_registration_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_user_registration_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.arf_user_registration_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					var dropdown = msg.split('^|^');
					if( dropdown[0] != '' && !jQuery('#'+id).hasClass('password_fields') )
					{
						jQuery('#'+id).html(dropdown[0]);
					}
					else if( dropdown[1] != '' && jQuery('#'+id).hasClass('password_fields') )
					{
						jQuery('#'+id).html(dropdown[1]);	
					}
					jQuery('#'+id).selectpicker('refresh');
				}
			});
			
			if( jQuery('#arf_user_registration_conent .custom_meta_div .custom_meta_row').length > 0 )
			{
				jQuery('#arf_user_registration_conent .arf_custom_meta_fields').each(function(i){
					var id = jQuery(this).attr('id');
					if( id != 'undefined' && id != '' ) {
						var dropdown = msg.split('^|^');
						if( dropdown[0] != '' )
						{
							jQuery('#'+id).html(dropdown[0]);
						}
						jQuery('#'+id).selectpicker('refresh');
					}
				});
			}
			
		}
	});	
}

function removeVariableFromURL(url_string, variable_name) {
	var URL = String(url_string);
	var regex = new RegExp( "\\?" + variable_name + "=[^&]*&?", "gi");
	URL = URL.replace(regex,'?');
	regex = new RegExp( "\\&" + variable_name + "=[^&]*&?", "gi");
	URL = URL.replace(regex,'&');
	URL = URL.replace(/(\?|&)$/,'');
	regex = null;
	return URL;
}
  
function is_auto_login(){
	if( jQuery('#arf_auto_login').is(':checked') ) {
		jQuery('#redirect_url_option').show();	
	} else {
		jQuery('#redirect_url_option').hide();			
	}	
}

function arf_change_user_tab( id )
{
	jQuery('.arf_current_tab').val(id);
	jQuery('.arf_tab_content').hide();
	jQuery('#arf_'+id+'_conent').show();
	
	jQuery('.arftab').removeClass('current lastDone nextdone');
	jQuery('#tab_'+id).addClass('current');
	jQuery('.arftab').addClass('done');
	if( id == 'edit_profile' )
	{
		jQuery('#tab_change_password').removeClass('done').addClass('lastDone');
		jQuery('#tab_edit_profile').removeClass('done lastDone').addClass('current');
	} else if( id == 'change_password' ) {
		jQuery('#tab_login').removeClass('current').addClass('lastDone');
		jQuery('#tab_change_password').removeClass('lastDone done').addClass('current');
		jQuery('#tab_edit_profile').addClass('nextdone');
	} else if( id == 'user_registration' ) {
		jQuery('#tab_user_registration').removeClass('lastDone done').addClass('current');
		jQuery('#tab_login').addClass('nextdone');
	} else if( id == 'login' ) {
		jQuery('#tab_user_registration').removeClass('done').addClass('lastDone');
		jQuery('#tab_login').removeClass('done').addClass('current');
		jQuery('#tab_change_password').addClass('nextdone');
	}
	
	if( !jQuery('#tab_login').hasClass('current') )
		jQuery('#tab_login_img').hide();
		
}

function add_new_custom_meta(is_blank_meta)
{	
	jQuery('.bulk_add').attr('disabled', true);
	jQuery('.add_blank_meta').attr('disabled', true);
	var tab = jQuery('#arf_current_tab').val();
	var parent_id = '';
	if( tab == 'user_registration' )
		parent_id = 'arf_user_registration_conent';
	else if( tab == 'edit_profile' )
		parent_id = 'arf_edit_profile_conent';
	
	if( parent_id == '' )
	{
		if( jQuery('#arf_user_registration_conent').length > 0 )
			parent_id = 'arf_user_registration_conent';
		else if( jQuery('#arf_user_registration_conent').length > 0 )
			parent_id = 'arf_edit_profile_conent';			
	}
	
	var metas = [];
	if( parent_id == 'arf_edit_profile_conent' )
		jQuery('input[name^="custom_meta_profile_array"]').each(function(){ metas.push(this.value); }); 
	else
		jQuery('input[name^="custom_meta_array"]').each(function(){ metas.push(this.value); }); 
	
	if( metas.length > 0 ){	
		var maxValueInArray = Math.max.apply(Math, metas);
		var next_meta_id = parseInt(maxValueInArray) + parseInt(1);
	}
	else
		var next_meta_id = 1;
	
	if( jQuery('#arfaction').val() == 'new' && parent_id == 'arf_user_registration_conent' )
		var form_id = jQuery('#arf_user_registration_form').val();
	else if( jQuery('#arfaction').val() == 'new' && parent_id == 'arf_edit_profile_conent' )
		var form_id = jQuery('#arf_edit_profile_form').val();	
	else if( parent_id == 'arf_edit_profile_conent' )
		var form_id = jQuery('#arf_edit_profile_form').val();		
	else	
		var form_id = jQuery('#arf_user_registration_form').val();		
	
	if( form_id == '' || form_id == 'undefined' )
	{
		form_id = '0';
	}
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=add_custom_meta_field&form_id="+form_id+"&next_meta_id="+next_meta_id+"&parent_id="+parent_id+"&is_blank_meta="+is_blank_meta,
		success:function(msg){
			
			var metas = [];
			if( parent_id == 'arf_edit_profile_conent' )
				jQuery('input[name^="custom_meta_profile_array"]').each(function(){ metas.push(this.value); }); 
			else
				jQuery('input[name^="custom_meta_array"]').each(function(){ metas.push(this.value); }); 
			
			if( metas.length > 0 ){	
				var maxValueInArray = Math.max.apply(Math, metas);
				var next_meta_id = parseInt(maxValueInArray) + parseInt(1);
			}
			else
				var next_meta_id = 1;
			
			if( parent_id != '' )
			{
				jQuery('#'+parent_id+' #add_new_custom_meta_field').hide();
				jQuery('#'+parent_id+' .custom_meta_div').append( '<div id="meta_row_'+next_meta_id+'" class="custom_meta_row">'+msg+'</div>' );
				
			} else {
				jQuery('#add_new_custom_meta_field').hide();
				jQuery('.custom_meta_div').append( '<div id="meta_row_'+next_meta_id+'" class="custom_meta_row">'+msg+'</div>' );				
			}
			jQuery('.bulk_add').attr('disabled', false);
			jQuery('.add_blank_meta').attr('disabled', false);
			jQuery('.sltstandard select').selectpicker();
		}
	});	
		
}

function remove_custom_meta_row( row )
{
	var row_id	= jQuery(row).parents('.custom_meta_row').attr('id');
		row_id	= row_id.replace('meta_row_', '');
		
	var tab = jQuery('#arf_current_tab').val();
	var parent_id = '';
	if( tab == 'user_registration' )
		parent_id = 'arf_user_registration_conent';
	else if( tab == 'edit_profile' )
		parent_id = 'arf_edit_profile_conent';
	
	if(parent_id != '')
	{
		if( jQuery('#'+parent_id+' #meta_row_'+row_id).is(':visible') && jQuery('#'+parent_id+' .custom_meta_div .custom_meta_row').length == 1 )
		{
			if( jQuery('#'+parent_id+' #meta_row_'+row_id).is(':visible') )
			{
				jQuery('#'+parent_id+' #meta_row_'+row_id).find("select option:first").attr('selected','selected');
				jQuery('#'+parent_id+' #meta_row_'+row_id).find("select").selectpicker('refresh');
				jQuery('#'+parent_id+' #meta_row_'+row_id).find(".custom_meta_txtfield").val('');
			}
		} 
		else
		{
			if( jQuery('#'+parent_id+' #meta_row_'+row_id).is(':visible') )	
				jQuery('#'+parent_id+' #meta_row_'+row_id).remove();
		}		
	}
	else
	{
		if( jQuery('#meta_row_'+row_id).is(':visible') && jQuery('#meta_row_'+row_id).parents('.custom_meta_div').find('.custom_meta_row').length < 2 )
		{
			jQuery('#meta_row_'+row_id).find("select option:first").attr('selected','selected');
			jQuery('#meta_row_'+row_id).find("select").selectpicker('refresh');
			jQuery('#meta_row_'+row_id).find(".custom_meta_txtfield").val('');
		} 
		else
		{
			if( jQuery('#meta_row_'+row_id).is(':visible') )
				jQuery('#meta_row_'+row_id).remove();
		}
	}
}

// login form
function arf_login_save(){
	var req = 0;
	
	if( jQuery('#arf_login_form').val() == '' ) {
		jQuery('.arf_log_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_log_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_login_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_log_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_log_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_login_form_msg').css('display', 'none');	
	}	
	
	if( jQuery('#arf_login_username').val() == '' ) {
		jQuery('.arf_login_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_login_username_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_login_username_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_login_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_login_username_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_login_username_msg').css('display', 'none');	
	}
	
	if( jQuery('#arf_login_password').val() == '' ) {
		jQuery('.arf_login_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_login_password_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_login_password_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_login_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_login_password_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_login_password_msg').css('display', 'none');	
	}
	
	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#form_name').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;
} 

function arf_login_form_dropdown_change()
{
	var form_id = jQuery('#arf_login_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_login_form_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.arf_loginform_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					var dropdown = msg.split('^|^');
					if( dropdown[0] != '' && !jQuery('#'+id).hasClass('password_fields') && !jQuery('#'+id).hasClass('checkbox_field') )
					{
						jQuery('#'+id).html(dropdown[0]);
					}
					else if( dropdown[1] != '' && jQuery('#'+id).hasClass('password_fields') && !jQuery('#'+id).hasClass('checkbox_field') )
					{
						jQuery('#'+id).html(dropdown[1]);	
					}
					else if( dropdown[2] != '' && !jQuery('#'+id).hasClass('password_fields') && jQuery('#'+id).hasClass('checkbox_field') )
					{
						jQuery('#'+id).html(dropdown[2]);	
					}
					jQuery('#'+id).selectpicker('refresh');
				}
			});		
		}
	});		
}

// forgot password
function arf_forgot_password_save()
{
	var req = 0;
	
	if( jQuery('#arf_forgot_password_form').val() == '' ) {
		jQuery('.arf_forgot_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_forgot_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_forgot_password_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_forgot_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_forgot_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_forgot_password_form_msg').css('display', 'none');	
	}	
	
	if( jQuery('#arf_forgot_username').val() == '' ) {
		jQuery('.arf_forgot_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_forgot_username_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_forgot_username_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_forgot_username_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_forgot_username_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_forgot_username_msg').css('display', 'none');	
	}

	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#arf_forgot_password_form').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;
		
}

function arf_forgot_pass_form_change()
{
	var form_id = jQuery('#arf_forgot_password_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_forgot_pass_form_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.arf_forgotpass_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					jQuery('#'+id).html(msg);
					jQuery('#'+id).selectpicker('refresh');
				}
			});		
		}
	});		
}

//change password
function arf_change_password_save()
{
	var req = 0;
	
	if( jQuery('#arf_change_password_form').val() == '' ) {
		jQuery('.arf_cpass_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_cpass_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_change_password_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_cpass_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_cpass_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_change_password_form_msg').css('display', 'none');	
	}	
	
	if( jQuery('#arf_new_password').val() == '' ) {
		jQuery('.arf_new_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_new_password_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_new_password_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_new_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_new_password_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_new_password_msg').css('display', 'none');	
	}

	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#form_name').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;		
}



//reset password
function arf_reset_password_save()
{
	var req = 0;
	
	if( jQuery('#arf_reset_password_form').val() == '' ) {
		jQuery('.arf_resert_pass_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_resert_pass_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_reset_password_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_resert_pass_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_resert_pass_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_reset_password_form_msg').css('display', 'none');	
	}	
	
	if( jQuery('#arf_reset_new_password').val() == '' ) {
		jQuery('.arf_reset_new_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_reset_new_password_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_reset_new_password_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_reset_new_password_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_reset_new_password_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_reset_new_password_msg').css('display', 'none');	
	}
	
	if( jQuery('#arf_reset_page_password_form').val() == '' ) {
		jQuery('.arf_reset_page_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_reset_page_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_reset_page_password_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_reset_page_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_reset_page_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_reset_page_password_form_msg').css('display', 'none');	
	}
	
	

	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#form_name').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;		
}
function arf_change_pass_form_change()
{
	var form_id = jQuery('#arf_change_password_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_change_pass_form_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.password_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					jQuery('#'+id).html(msg);
					jQuery('#'+id).selectpicker('refresh');
				}
			});		
		}
	});	
}


function arf_reset_pass_form_change()
{
	var form_id = jQuery('#arf_reset_password_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_reset_pass_form_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.password_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					jQuery('#'+id).html(msg);
					jQuery('#'+id).selectpicker('refresh');
				}
			});
		}
	});	
}






//edit profile
function arf_edit_profile_save()
{
	var req = 0;
	
	if( jQuery('#arf_edit_profile_form').val() == '' ) {
		jQuery('.arf_editp_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
		jQuery('.arf_editp_form_dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
		jQuery('#arf_edit_profile_form_msg').css('display', 'block');
		req++;
	} else {
		jQuery('.arf_editp_form_dropdown .arfbtn.dropdown-toggle').css('border-color', '');
		jQuery('.arf_editp_form_dropdown .arfdropdown-menu.open').css('border-color', '');
		jQuery('#arf_edit_profile_form_msg').css('display', 'none');	
	}	
	
	if( req > 0 ){
		jQuery(window.opera?'html':'html, body').animate({ scrollTop: jQuery('#form_name').offset().top-250 }, 'slow' );
		return false;
	} else	
		return true;	
}

function arf_edit_profile_form_change()
{
	var form_id = jQuery('#arf_edit_profile_form').val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arf_edit_profile_field_dropdown&form_id="+form_id,
		success:function(msg){
			jQuery('.arf_editprofile_fields').each(function(i){
				var id = jQuery(this).attr('id');
				if( id != 'undefined' && id != '' ) {
					var dropdown = msg.split('^|^');
					if( dropdown[0] != '' && !jQuery('#'+id).hasClass('password_fields') )
					{
						jQuery('#'+id).html(dropdown[0]);
					}
					else if( dropdown[1] != '' && jQuery('#'+id).hasClass('password_fields') )
					{
						jQuery('#'+id).html(dropdown[1]);	
					}
					jQuery('#'+id).selectpicker('refresh');
				}
			});	
			
			if( jQuery('#arf_edit_profile_conent .custom_meta_div .custom_meta_row').length > 0 )
			{
				jQuery('#arf_edit_profile_conent .arf_custom_meta_fields').each(function(i){
					var id = jQuery(this).attr('id');
					if( id != 'undefined' && id != '' ) {
						var dropdown = msg.split('^|^');
						if( dropdown[0] != '' )
						{
							jQuery('#'+id).html(dropdown[0]);
						}
						jQuery('#'+id).selectpicker('refresh');
					}
				});
			}
			
		}
	});		
}

function is_change_auto_login(){
	if( jQuery('#arf_change_auto_login').is(':checked') ) {
		jQuery('#change_redirect_url_option').show();	
	} else {
		jQuery('#change_redirect_url_option').hide();			
	}	
}

function arf_change_custom_meta(form_id, field_id)
{	
	if(jQuery('#'+field_id).val() == 'custom' )
	{
		var	parent_id = ( field_id.indexOf('profile') > 0 ) ? 'arf_edit_profile_conent' : 'arf_user_registration_conent';
		var parent_name	= ( field_id.indexOf('profile') > 0 ) ? 'profile_' : ''; 
		var next_meta_data = field_id.split('_');
		if( parent_name == 'profile_' )
			var	next_meta = ( next_meta_data[4] !== undefined ) ? next_meta_data[4] : 1;	
		else
			var	next_meta = ( next_meta_data[3] !== undefined ) ? next_meta_data[3] : 1;	
			
		jQuery('#custom_meta_'+parent_name+'name_'+next_meta).show();
		jQuery('#'+field_id+'_div').hide();
	}
	
}

function arfshowloginimg()
{
	if( jQuery('#tab_login').hasClass('current') )
		jQuery('#tab_login_img').show();
	else
		jQuery('#tab_login_img').hide();
}
function arfhideloginimg()
{
	jQuery('#tab_login_img').hide();	
}
jQuery(document).ready(function(){
	if( jQuery.isFunction( jQuery().tooltipster ) )	
	{
		setTimeout(function(){
			jQuery('.arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}, 10);
	}
});

function is_changepass_entry_enable()
{
	if( jQuery('#arf_changepass_entry_enable').is(':checked') )
	{
		jQuery('#arf_changepass_hide_password').removeAttr('disabled');
		jQuery('#arf_changepass_hide_password').attr('checked', true);
	}
	else
		jQuery('#arf_changepass_hide_password').attr('disabled', true);		
}

function is_login_entry_enable()
{
	if( jQuery('#arf_login_entry_enable').is(':checked') )
	{
		jQuery('#arf_login_hide_password').removeAttr('disabled');
		jQuery('#arf_login_hide_password').attr('checked', true);
	}
	else
		jQuery('#arf_login_hide_password').attr('disabled', true);	
}

function is_edit_profile_enable()
{
	if( jQuery('#arf_edit_profile_enable').is(':checked') )
	{
		jQuery('#arf_editprofile_hide_password').removeAttr('disabled');
		jQuery('#arf_editprofile_hide_password').attr('checked', true);
	}
	else
		jQuery('#arf_editprofile_hide_password').attr('disabled', true);		
}

function add_new_bp_meta()
{	
	jQuery('.bulk_add').attr('disabled', true);
	jQuery('.add_blank_meta').attr('disabled', true);
	var tab = jQuery('#arf_current_tab').val();
	var parent_id = '';
	if( tab == 'user_registration' )
		parent_id = 'arf_user_registration_conent';
	else if( tab == 'edit_profile' )
		parent_id = 'arf_edit_profile_conent';
	
	if( parent_id == '' )
	{
		if( jQuery('#arf_user_registration_conent').length > 0 )
			parent_id = 'arf_user_registration_conent';
		else if( jQuery('#arf_user_registration_conent').length > 0 )
			parent_id = 'arf_edit_profile_conent';			
	}
	
	var metas = [];
	if( parent_id == 'arf_edit_profile_conent' )
		jQuery('input[name^="bp_meta_profile_array"]').each(function(){ metas.push(this.value); }); 
	else
		jQuery('input[name^="bp_meta_array"]').each(function(){ metas.push(this.value); }); 
	
	if( metas.length > 0 ){	
		var maxValueInArray = Math.max.apply(Math, metas);
		var next_meta_id = parseInt(maxValueInArray) + parseInt(1);
	}
	else
		var next_meta_id = 1;
	
	if( jQuery('#arfaction').val() == 'new' && parent_id == 'arf_user_registration_conent' )
		var form_id = jQuery('#arf_user_registration_form').val();
	else if( jQuery('#arfaction').val() == 'new' && parent_id == 'arf_edit_profile_conent' )
		var form_id = jQuery('#arf_edit_profile_form').val();	
	else if( parent_id == 'arf_edit_profile_conent' )
		var form_id = jQuery('#arf_edit_profile_form').val();		
	else	
		var form_id = jQuery('#arf_user_registration_form').val();		
	
	if( form_id == '' || form_id == 'undefined' )
	{
		form_id = '0';
	}
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=add_bp_field&form_id="+form_id+"&next_meta_id="+next_meta_id+"&parent_id="+parent_id,
		success:function(msg){
			
			var metas = [];
			if( parent_id == 'arf_edit_profile_conent' )
				jQuery('input[name^="bp_meta_profile_array"]').each(function(){ metas.push(this.value); }); 
			else
				jQuery('input[name^="bp_meta_array"]').each(function(){ metas.push(this.value); }); 
			
			if( metas.length > 0 ){	
				var maxValueInArray = Math.max.apply(Math, metas);
				var next_meta_id = parseInt(maxValueInArray) + parseInt(1);
			}
			else
				var next_meta_id = 1;
			
			if( parent_id != '' )
			{
				jQuery('#'+parent_id+' #add_new_custom_meta_field').hide();
				jQuery('#'+parent_id+' .bp_meta_div').append( '<div id="bp_meta_row_'+next_meta_id+'" class="arf_bp_row">'+msg+'</div>' );
				
			} else {
				jQuery('#add_new_custom_meta_field').hide();
				jQuery('.bp_meta_div').append( '<div id="bp_meta_row_'+next_meta_id+'" class="arf_bp_row">'+msg+'</div>' );				
			}
			jQuery('.bulk_add').attr('disabled', false);
			jQuery('.add_blank_meta').attr('disabled', false);
			jQuery('.sltstandard select').selectpicker();
		}
	});	
		
}

function remove_bp_meta_row(row)
{
	var row_id	= jQuery(row).parents('.arf_bp_row').attr('id');
		row_id	= row_id.replace('bp_meta_row_', '');
		
	var tab = jQuery('#arf_current_tab').val();
	var parent_id = '';
	if( tab == 'user_registration' )
		parent_id = 'arf_user_registration_conent';
	else if( tab == 'edit_profile' )
		parent_id = 'arf_edit_profile_conent';
	
	if(parent_id != '')
	{
		if( jQuery('#'+parent_id+' #bp_meta_row_'+row_id).is(':visible') && jQuery('#'+parent_id+' .bp_meta_div .arf_bp_row').length == 1 )
		{
			if( jQuery('#'+parent_id+' #bp_meta_row_'+row_id).is(':visible') )
			{
				jQuery('#'+parent_id+' #bp_meta_row_'+row_id).find("select").each(function(){
					jQuery(this).find("option:first").attr('selected','selected');
					jQuery(this).selectpicker('refresh');
				});
				jQuery('#'+parent_id+' #bp_meta_row_'+row_id).find(".custom_meta_txtfield").val('');
			}
		} 
		else
		{
			if( jQuery('#'+parent_id+' #bp_meta_row_'+row_id).is(':visible') )	
				jQuery('#'+parent_id+' #bp_meta_row_'+row_id).remove();
		}		
	}
	else
	{
		if( jQuery('#bp_meta_row_'+row_id).is(':visible') && jQuery('#bp_meta_row_'+row_id).parents('.bp_meta_div').find('.arf_bp_row').length < 2 )
		{
			jQuery('#bp_meta_row_'+row_id).find("select").each(function(){
				jQuery(this).find("option:first").attr('selected','selected');
				jQuery(this).selectpicker('refresh');
			});
			jQuery('#bp_meta_row_'+row_id).find(".custom_meta_txtfield").val('');
		} 
		else
		{
			if( jQuery('#bp_meta_row_'+row_id).is(':visible') )
				jQuery('#bp_meta_row_'+row_id).remove();
		}
	}
}