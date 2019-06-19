<form name="arf_login_setting" id="arf_login_setting" method="post" onSubmit="return arf_login_save();" enctype="multipart/form-data">

<?php if(isset($arfaction) and isset($_GET['msg']) and $_GET['msg'] == '1' ){ ?>
<script type="text/javascript">
	var pageurl = removeVariableFromURL(document.URL, 'msg');
	if(window.history.pushState)	
		window.history.pushState({path:pageurl},'',pageurl);
</script>
<?php }
if( $current_tab == 'login' )
	$login_message = $message;
	
$tab = 'login';
$arfaction == 'edit';
$id = ( isset( $form_list['login'] ) and !empty($form_list['login']) ) ? $form_list['login'] : ''; 

if( $id == '' )
{
	$arfaction	= 'new';
	$arf_default_error_message = self::arf_default_error_message();
			
	$values['id'] = '';		
	$values['form_id'] = '';			
	$values['form_name'] = ''; 				
	$values['username'] = '';				
	$values['password'] = '';					
	$values['redirect_url'] = '';
	$values['remember'] = '';
	$values['login_error'] = $arf_default_error_message['login_error'];
} else {
	$values = ARF_User_Registration::arf_get_tab_data($tab, $id);
}
				
?>		  
<input type="hidden" id="arfaction" name="arfaction" value="<?php echo $arfaction; ?>">
<input type="hidden" name="form_id" id="form_id" value="<?php echo $values['form_id'];?>">
<input type="hidden" name="id" id="id" value="<?php echo $values['id'];?>"> 
<input type="hidden" name="form_name" id="form_name" value="<?php echo $values['form_name'];?>">
<input type="hidden" name="arf_current_tab" class="arf_current_tab" id="arf_current_tab" value="<?php echo $current_tab;?>" />
<input type="hidden" name="arf_set_id" class="arf_set_id" id="arf_set_id" value="<?php echo $set_id;?>" />
<input type="hidden" name="arf_set_name" class="arf_set_name" id="arf_set_name" value="<?php echo ARF_User_Registration::get_set_name($set_id);?>" />
           
<?php if (isset($login_message) && $login_message != ''){ if(is_admin()){ ?><div id="success_message" style="margin-bottom:0px; margin-top:15px; width:95%;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php } echo $login_message; if(is_admin()){ ?></div></div><?php } } ?>
			                       	  
<div id="arf_login_table" class="arf_form_table form-table">
    
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel" style="text-align:left; width:800px;">
            <div class="lbltitle" style="font-size: 17px !important;"><?php _e('Login form', 'ARForms-user-registration'); ?> : <?php echo ARF_User_Registration::get_set_name($set_id);?></div>
            <div class="lbltitle lblmainsubtitle"><?php _e('Map form with wordpress login form', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
        
    <div class="arf_table_tr">
    <div class="arf_table_td arftdlabel">
        <label class="lblsubtitle"><?php _e('Map Form', 'ARForms-user-registration'); ?>&nbsp;&nbsp;</label><span style="vertical-align:middle" class="arfglobalrequiredfield">*</span>
    </div>
    <div class="arf_table_td inputdiv">
        <?php 
        global $arfform; 
        
        $exclude_ids = array('-1');
        $form_data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."arf_user_registration_forms" );
        if( count( $form_data ) > 0 ){
            foreach( $form_data as $form_new ){
                if( $form_new->form_id != $values['form_id'] )	
					$exclude_ids[] = $form_new->form_id;	
            }
        }
        
		$exclude_ids = array_unique( $exclude_ids );
        
		$where = "is_template=0 AND is_enable=1 AND (status is NULL OR status = '' OR status = 'published') AND id not in (". implode(',', $exclude_ids).")";
        
		$forms = $arfform->getAll($where, ' ORDER BY name');					
        ?>
        <div class="arf_log_form_dropdown"><div class="sltstandard">
        <select name="arf_login_form" id="arf_login_form" onChange="arf_login_form_dropdown_change();" style="width:275px;" class="frm-dropdown" data-width="275px" data-size="15">
            <option value=""><?php echo __('Please select form', 'ARForms-user-registration'); ?></option>
            <?php foreach($forms as $form){ ?>
                <option value="<?php echo $form->id; ?>" <?php selected($form->id, $values['form_id']);?>><?php echo armainhelper::truncate($form->name, 33); ?></option>
            <?php } ?>
        </select>
        </div></div><div class="arferrmessage" id="arf_login_form_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration');  ?></div>
        </div>

    </div>
    
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left;">
            <div class="lbltitle"><?php _e('Map form fields', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
                                                                                                  
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Username', 'ARForms-user-registration'); ?></label>&nbsp;&nbsp;<span style="vertical-align:middle" class="arfglobalrequiredfield">*</span>
        </div>
        <div class="arf_table_td inputdiv">
            <div class="arf_login_username_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_login_username', 'arf_loginform_fields', $values['username']);?></div> <div class="arferrmessage" id="arf_login_username_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
    
    
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Password', 'ARForms-user-registration'); ?></label>&nbsp;&nbsp;<span style="vertical-align:middle" class="arfglobalrequiredfield">*</span>
        </div>
        <div class="arf_table_td inputdiv">
            <div class="arf_login_password_dropdown"><?php echo ARF_User_Registration::field_dropdown_custom($values['form_id'], 'arf_login_password', 'arf_loginform_fields password_fields', $values['password'], array('password'));?></div>
            <div class="arferrmessage" id="arf_login_password_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration'); ?></div> 
        </div>
    </div>
    
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Remember Me', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <div class="arf_login_remember_dropdown"><?php echo ARF_User_Registration::field_dropdown_custom($values['form_id'], 'arf_login_remember', 'arf_loginform_fields checkbox_field', $values['remember'], array('checkbox'));?></div><div class="arferrmessage" id="arf_login_remember_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration'); ?></div> 
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Redirect Url after', 'ARForms-user-registration'); ?><br /><?php _e('Successful login', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <input type="text" name="arf_login_redirect_url" id="arf_login_redirect_url" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($values['redirect_url']); ?>" />
        </div>
    </div>
    
    <div class="arf_table_tr" style="margin-top: 20px;">
        <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; width: 400px;">
            <div class="lbltitle"><?php _e('Validation Error Message', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Login Error message', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <input type="text" name="arf_login_error" id="arf_login_error" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($values['login_error']); ?>" />
        </div>
    </div>
    
    
    <div style="clear:both;border-bottom: 1px solid #EAEAEA;padding-top: 20px;"></div>
    
      
    <div class="arf_table_tr" style="margin-top: 20px;">
        <div class="arf_table_td arftdlabel" style="text-align:left; width: 800px;">
            <div class="lbltitle" style="font-size: 17px !important;"><?php _e('Logout', 'ARForms-user-registration'); ?> : <?php echo ARF_User_Registration::get_set_name($set_id);?></div>
            <div class="lbltitle lblmainsubtitle"><?php _e('Add following shortcode where you want to show Logout button', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Logout Shortcode', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <div class="arf_logout_button">
            	<label class="lblsubtitle" style="text-shadow:none;display: block; font-weight:bold;color:#1BBAE1;">[ARForms_logout type="link"]- <?php _e('Logout link', 'ARForms-user-registration'); ?></label>
            	<label class="lblsubtitle" style="text-shadow:none;display: block; font-weight:bold;color:#1BBAE1;">[ARForms_logout type="button"]- <?php _e('Logout button', 'ARForms-user-registration'); ?></label>
                	<label class="lblsubtitle" style="text-shadow:none;display: block; font-weight:bold;color:#1BBAE1;">[ARForms_username]- <?php _e('Display Username', 'ARForms-user-registration'); ?></label>                
            </div>
        </div>
    </div>
    
    <?php $logout_setting = maybe_unserialize( get_option('arf_logout_setting') ); ?>
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Logout Label', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <input type="text" name="arf_logout_label" id="arf_logout_label" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($logout_setting['logout_label']); ?>" />
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Redirect Url after logout', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <input type="text" name="arf_logout_redirect_url" id="arf_logout_redirect_url" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($logout_setting['logout_redirect']); ?>" />
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Logout Link / Button Class', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            arf_logout_button
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Logout Link / Button CSS', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <textarea name="arf_logout_css" id="arf_logout_css" class="txtmultinew" style="width: 400px;"><?php echo stripslashes_deep($logout_setting['logout_css']); ?></textarea>
            <div class="lblsubtitle" style="clear:both;">e.g. background-color:#FF0000;</div>
        </div>
    </div>
    
    <div style="clear:both"></div>
            	
    <div style="clear:both; margin-top:50px;">
        <button class="greensavebtn" id="save_arf_login" name="save_arf_login" style="margin-left:60px;width:100px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/save_icon.png">&nbsp;&nbsp;<?php _e('Save', 'ARForms-user-registration') ?></button>
        <?php if( isset($_GET['arfaction']) && $_GET['arfaction'] == 'edit' ){ ?>
        &nbsp;&nbsp;&nbsp;
    	<button class="whitecancelbtn" type="submit" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" id="arf_reset_login" name="arf_reset_login"><?php _e('Reset', 'ARForms-user-registration') ?></button>
        <?php } ?>
        &nbsp;&nbsp;&nbsp;
        <button class="whitecancelbtn" type="button" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" onclick="location.href='?page=ARForms-user-registration'"><?php _e('Cancel', 'ARForms-user-registration') ?></button>
    </div>

</div>
</form>
<?php unset($values); $values = array(); ?>