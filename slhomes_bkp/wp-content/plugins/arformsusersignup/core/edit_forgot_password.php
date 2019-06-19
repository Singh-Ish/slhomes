<form name="arf_forgot_password_setting" id="arf_forgot_password_setting" method="post" onSubmit="return arf_forgot_password_save();" enctype="multipart/form-data">

<?php if(isset($arfaction) and isset($_GET['msg']) and $_GET['msg'] == '1' ){ ?>
<script type="text/javascript">
	var pageurl = removeVariableFromURL(document.URL, 'msg');
	if(window.history.pushState)	
		window.history.pushState({path:pageurl},'',pageurl);
</script>
<?php }
if( $current_tab == 'forgot_password' )
	$forgot_password_message = $message;
	
$tab = 'forgot_password';
$arfaction == 'edit';
$id = ( isset( $form_list['forgot_password'] ) and !empty($form_list['forgot_password']) ) ? $form_list['forgot_password'] : ''; 

if( $id == '' )
{
	$arfaction	= 'new';
	$arf_default_error_message = self::arf_default_error_message();
			
	$values['id'] = '';		
	$values['form_id'] = '';			
	$values['form_name'] = ''; 				
	$values['forgot_username'] = '';
	$values['forgot_error'] = $arf_default_error_message['forgot_error'];			

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
			                       	  
<div id="arf_forgot_password_table" class="arf_form_table form-table">
	
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel" style="text-align:left; width:800px;">
            <div class="lbltitle" style="font-size: 17px !important;"><?php _e('Forgot Password', 'ARForms-user-registration'); ?> : <?php echo ARF_User_Registration::get_set_name($set_id);?></div>
            <div class="lbltitle lblmainsubtitle"><?php _e('Map form with wordpress forgot password form', 'ARForms-user-registration'); ?></div>
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
        <div class="arf_forgot_form_dropdown"><div class="sltstandard">
        <select name="arf_forgot_password_form" id="arf_forgot_password_form" onChange="arf_forgot_pass_form_change();" style="width:275px;" class="frm-dropdown" data-width="275px" data-size="15">
            <option value=""><?php echo __('Please select form', 'ARForms-user-registration'); ?></option>
            <?php foreach($forms as $form){ ?>
                <option value="<?php echo $form->id; ?>" <?php selected($form->id, $values['form_id']);?>><?php echo armainhelper::truncate($form->name, 33); ?></option>
            <?php } ?>
        </select>
        </div></div><div class="arferrmessage" id="arf_forgot_password_form_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration');  ?></div>
        </div>

    </div>
    
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; width:400px;">
            <div class="lbltitle"><?php _e('Map form fields', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
                                                                                                  
    <div class="arf_table_tr">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Username / Email', 'ARForms-user-registration'); ?></label>&nbsp;&nbsp;<span style="vertical-align:middle" class="arfglobalrequiredfield">*</span>
        </div>
        <div class="arf_table_td inputdiv">
            <div class="arf_forgot_username_dropdown"><?php echo ARF_User_Registration::field_dropdown_custom($values['form_id'], 'arf_forgot_username', 'arf_forgotpass_fields', $values['username'], array('text', 'email'));?></div> <div class="arferrmessage" id="arf_forgot_username_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
    
    
    <div class="arf_table_tr" style="margin-top: 20px;">
        <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; width: 400px;">
            <div class="lbltitle"><?php _e('Validation Error Message', 'ARForms-user-registration'); ?></div>
        </div>
    </div>
    
    <div class="arf_table_tr" id="redirect_url_option">
        <div class="arf_table_td arftdlabel">
            <label class="lblsubtitle"><?php _e('Invalid Username / Email', 'ARForms-user-registration'); ?></label>
        </div>
        <div class="arf_table_td inputdiv">
            <input type="text" name="arf_forgot_error" id="arf_forgot_error" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($values['forgot_error']); ?>" />
        </div>
    </div>
    
    <div style="clear:both"></div>
            	
    <div style="clear:both; margin-top:50px;">
        <button class="greensavebtn" id="save_arf_forgot_password" name="save_arf_forgot_password" style="margin-left:60px;width:100px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/save_icon.png">&nbsp;&nbsp;<?php _e('Save', 'ARForms-user-registration'); ?></button>
        <?php if( isset($_GET['arfaction']) && $_GET['arfaction'] == 'edit' ){ ?>
        &nbsp;&nbsp;&nbsp;
    	<button class="whitecancelbtn" type="submit" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" id="arf_reset_forgot_password" name="arf_reset_forgot_password"><?php _e('Reset', 'ARForms-user-registration') ?></button>
        <?php } ?>
        &nbsp;&nbsp;&nbsp;
        <button class="whitecancelbtn" type="button" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" onclick="location.href='?page=ARForms-user-registration'"><?php _e('Cancel', 'ARForms-user-registration') ?></button>
    </div>

</div>
</form>
<?php unset($values); $values = array(); ?>