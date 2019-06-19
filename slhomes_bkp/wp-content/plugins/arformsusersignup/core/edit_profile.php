<form name="arf_edit_profile_setting" id="arf_edit_profile_setting" method="post" onSubmit="return arf_edit_profile_save();" enctype="multipart/form-data">

<?php if(isset($arfaction) and isset($_GET['msg']) and $_GET['msg'] == '1' ){ ?>
<script type="text/javascript">
	var pageurl = removeVariableFromURL(document.URL, 'msg');
	if(window.history.pushState)	
		window.history.pushState({path:pageurl},'',pageurl);
</script>
<?php }
					
if( $current_tab == 'edit_profile' )
	$edit_profile_message = $message;
	
$tab = 'edit_profile';
$arfaction == 'edit';
$id = ( isset( $form_list['edit_profile'] ) and !empty($form_list['edit_profile']) ) ? $form_list['edit_profile'] : ''; 

if( $id == '' )
{
	$arfaction	= 'new';
	$arf_default_error_message = self::arf_default_error_message();
			
	$values['id'] = '';		
	$values['form_id'] = '';			
	$values['form_name'] = ''; 					
	$values['first_name'] = '';				
	$values['last_name'] = '';
	$values['display_name'] = '';
	$values['email'] = '';								
	$values['password'] = '';					
	$values['verification_mail'] = 0;
	$values['auto_login'] = 0;
	$values['redirect_url'] = '';
	$values['notification'] = 0;
	$values['user_meta'] = array();
	$values['duplicate_email'] = $arf_default_error_message['duplicate_email'];
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
	  
<?php if (isset($edit_profile_message) && $edit_profile_message != ''){ if(is_admin()){ ?><div id="success_message" style="margin-bottom:0px; margin-top:15px; width:95%;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php } echo $edit_profile_message; if(is_admin()){ ?></div></div><?php } } ?>
			                       	  
  <div id="arf_edit_profile_table" class="arf_form_table form-table">
        
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel" style="text-align:left; width:400px;">
                <div class="lbltitle" style="font-size: 17px !important;"><?php _e('Edit Profile', 'ARForms-user-registration'); ?> : <?php echo ARF_User_Registration::get_set_name($set_id);?></div>
                <div class="lbltitle lblmainsubtitle"><?php _e('Map form with wordpress edit profile form', 'ARForms-user-registration'); ?></div>
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
            <div class="arf_editp_form_dropdown"><div class="sltstandard">
            <select name="arf_edit_profile_form" id="arf_edit_profile_form" onChange="arf_edit_profile_form_change();" style="width:275px;" class="frm-dropdown" data-width="275px" data-size="15">
                <option value=""><?php echo __('Please select form', 'ARForms-user-registration'); ?></option>
                <?php foreach($forms as $form){ ?>
                    <option value="<?php echo $form->id; ?>" <?php selected($form->id, $values['form_id']);?>><?php echo armainhelper::truncate($form->name, 33); ?></option>
                <?php } ?>
            </select>
            </div></div><div class="arferrmessage" id="arf_edit_profile_form_msg" style="display:none; clear:both;"><?php _e('This field cannot be blank.', 'ARForms-user-registration');  ?></div>
            </div>
    
        </div>
        
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left;">
                <div class="lbltitle"><?php _e('Map form fields', 'ARForms-user-registration'); ?></div>
            </div>
        </div>
    	
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Email Address', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                <div class="arf_editp_email_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_profile_email', 'arf_editprofile_fields', $values['email']);?></div> 
            </div>
        </div>
        
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('First Name', 'ARForms-user-registration'); ?></label>
            </div>
                
            <div class="arf_table_td inputdiv">
                <div class="arf_editp_first_name_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_profile_first_name', 'arf_editprofile_fields', $values['first_name']);?></div> 
            </div>
        </div>
    
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Last Name', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                <div class="arf_editp_last_name_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_profile_last_name', 'arf_editprofile_fields', $values['last_name']);?></div> 
            </div>
        </div>
    
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Display Name', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                <div class="arf_editp_display_name_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_profile_display_name', 'arf_editprofile_fields', $values['display_name']);?></div> 
            </div>
        </div>
        
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Password', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                <div class="arf_editp_password_dropdown"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'arf_profile_password', 'arf_editprofile_fields password_fields', $values['password']);?></div>
            </div>
        </div>

        <div class="arf_table_tr" style="margin-top: 20px;">
            <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; width: 400px;">
                <div class="lbltitle"><?php _e('Validation Error Message', 'ARForms-user-registration'); ?></div>
            </div>
        </div>
        
        <div class="arf_table_tr" id="redirect_url_option">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Duplicate Email', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                <input type="text" name="arf_duplicate_email" id="arf_duplicate_email" class="txtstandardnew" style="width: 400px;" value="<?php echo esc_attr($values['duplicate_email']); ?>" />
            </div>
        </div>
        
       
        <div class="arf_table_tr" style="margin-top: 20px;">
            <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; padding-right:0; width:185px;">
                <div class="lbltitle"><?php _e('Custom User Meta', 'ARForms-user-registration'); ?></div>
            </div>
            <div style="float: left; margin-left:505px;margin-top: 13px;"><button type="button" class="add_blank_meta" onclick="add_new_custom_meta(1)" ><?php _e('Add Blank Meta', 'ARForms-user-registration'); ?></button></div>
        </div>
            
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Map Custom Meta', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                
                
                <div class="custom_meta_div">
                    <?php $metas_array = $values['user_meta']; ?>
                    <?php
                    if( $metas_array and count($metas_array) > 0 )
                    {
                        foreach($metas_array as $iv => $meta){									
                            ?><div id="meta_row_<?php echo $iv; ?>" class="custom_meta_row">
                                <input type="text" name="custom_meta_profile_name_<?php echo $iv;?>" id="custom_meta_profile_name_<?php echo $iv;?>" value="<?php echo $meta['meta_name'];?>" class="txtstandardnew custom_meta_txtfield" style=" <?php if($meta['is_custom'] == 0) { echo 'display:none;'; }?>" />
                                <div id="custom_metadrop_profile_name_<?php echo $iv; ?>_div" class="arf_custom_meta_name" style=" <?php if($meta['is_custom'] == 1) { echo 'display:none;'; }?>" >
									<?php ARF_User_Registration::arf_custom_meta_dropdown($values['form_id'], 'custom_metadrop_profile_name_'.$iv, 'arf_custom_profile_metad_fields', ($meta['is_custom'] == 1) ? 'arfcustom' : $meta['meta_name']); ?>
                                </div>
                                
                                <div class="arf_custom_meta_value"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'custom_meta_profile_value_'.$iv, 'arf_editprofile_fields arf_custom_meta_fields', $meta['meta_value']);?></div>
                                
                                <div style="padding-top: 5px;">
                                    <span style="margin-left:10px;" class="bulk_add_remove">
                                        <button type="button" class="bulk_add" onClick="add_new_custom_meta(0);">&nbsp;</button>
                                        <span class="bulk_remove" onClick="remove_custom_meta_row(this)">&nbsp;</span>
                                    </span>                                
                                </div> 
                                <input type="hidden" name="custom_meta_profile_array[]" value="<?php echo $iv;?>" />
                           </div>
                           <?php } 
                    } else {
						
						$iv = 0;	
						?><div id="meta_row_<?php echo $iv; ?>" class="custom_meta_row">
                                <input type="text" name="custom_meta_profile_name_<?php echo $iv;?>" id="custom_meta_profile_name_<?php echo $iv;?>" value="<?php echo $meta['meta_name'];?>" class="txtstandardnew custom_meta_txtfield" style=" <?php if($meta['is_custom'] == 0) { echo 'display:none;'; }?>" />
                                <div id="custom_metadrop_profile_name_<?php echo $iv; ?>_div" class="arf_custom_meta_name" style=" <?php if($meta['is_custom'] == 1) { echo 'display:none;'; }?>" >
									<?php ARF_User_Registration::arf_custom_meta_dropdown($values['form_id'], 'custom_metadrop_profile_name_'.$iv, 'arf_custom_profile_metad_fields', ($meta['is_custom'] == 1) ? 'arfcustom' : $meta['meta_name']); ?>
                                </div>
                                
                                <div class="arf_custom_meta_value"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'custom_meta_profile_value_'.$iv, 'arf_editprofile_fields arf_custom_meta_fields', $meta['meta_value']);?></div>
                                
                                <div style="padding-top: 5px;">
                                    <span style="margin-left:10px;" class="bulk_add_remove">
                                        <button type="button" class="bulk_add" onClick="add_new_custom_meta(0);">&nbsp;</button>
                                        <span class="bulk_remove" onClick="remove_custom_meta_row(this)">&nbsp;</span>
                                    </span>                                
                                </div> 
                                <input type="hidden" name="custom_meta_profile_array[]" value="<?php echo $iv;?>" />
                           </div>
                           <?php 
					}
					
					?>
                                                    
                </div>
                
            </div>
        </div>
        
        <!-- Hook for buddypress -->
        <?php if( ARF_User_Registration::is_buddypress_active() ){ ?>
        <div style="clear:both;"></div>
        
        <div class="arf_table_tr" style="margin-top: 20px;">
            <div class="arf_table_td arftdlabel arftdsublabel" style="text-align:left; padding-right:0; width:285px;">
                <div class="lbltitle"><?php _e('BuddyPress Profile Field', 'ARForms-user-registration'); ?></div>
            </div>
        </div>
        
        <div class="arf_table_tr">
            <div class="arf_table_td arftdlabel">
                <label class="lblsubtitle"><?php _e('Map profile field', 'ARForms-user-registration'); ?></label>
            </div>
            <div class="arf_table_td inputdiv">
                
             
                <div class="bp_meta_div">
                    <?php $metas_array = $values['bp_fields'] ? $values['bp_fields'] : array(); ?>
                    
                    <?php					
                    if( $metas_array and count($metas_array) > 0 )
                    {
                        foreach($metas_array as $iv => $meta){	
                            ?><div id="bp_meta_row_<?php echo $iv; ?>" class="arf_bp_row">
                                                               
                                <div id="arf_bp_drop_profile_name_<?php echo $iv; ?>_div" class="arf_bp_name">
									<?php ARF_User_Registration::arf_bp_field_dropdown($values['form_id'], 'bp_drop_profile_name_'.$iv, 'arf_bp_dfields', $meta['field_name']); ?>
                                </div>
								
                                <div class="arf_bp_value"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'bp_field_profile_value_'.$iv, 'arf_user_registration_fields arf_custom_meta_fields', $meta['field_value']);?></div>
                                
                                <div style="padding-top: 5px;">
                                    <span style="margin-left:10px;" class="bulk_add_remove">
                                        <button type="button" class="bulk_add" onClick="add_new_bp_meta();">&nbsp;</button>
                                        <span class="bulk_remove" onClick="remove_bp_meta_row(this)">&nbsp;</span>
                                    </span>                                
                                </div> 
                                <input type="hidden" name="bp_meta_profile_array[]" value="<?php echo $iv;?>" />
                           </div>
                           <?php } 
                    } else {
						
						$iv = 1;	
						?><div id="bp_meta_row_<?php echo $iv; ?>" class="arf_bp_row">
							
							<div id="arf_bp_drop_profile_name_<?php echo $iv; ?>_div" class="arf_bp_name" >
								<?php ARF_User_Registration::arf_bp_field_dropdown($values['form_id'], 'bp_drop_profile_name_'.$iv, 'arf_bp_dfields', $meta['field_name']); ?>
							</div>
							
							<div class="arf_bp_value"><?php echo ARF_User_Registration::field_dropdown($values['form_id'], 'bp_field_profile_value_'.$iv, 'arf_user_registration_fields arf_custom_meta_fields', $meta['field_value']);?></div>
							
							<div style="padding-top: 5px;">
								<span style="margin-left:10px;" class="bulk_add_remove">
									<button type="button" class="bulk_add" onClick="add_new_bp_meta();">&nbsp;</button>
									<span class="bulk_remove" onClick="remove_bp_meta_row(this)">&nbsp;</span>
								</span>                                
							</div> 
							<input type="hidden" name="bp_meta_profile_array[]" value="<?php echo $iv;?>" />
					   </div>
					<?php }
                    ?>
                                                    
                </div>
                
            </div>
        </div>
        <?php } ?>
        <!-- Hook for buddypress end -->
                    
</div>		<!-- end of arf_user_reg_table  --> 

<div style="clear:both"></div>
    
<div style="clear:both; margin-top:50px;">
    <button class="greensavebtn" id="save_arf_edit_profile" name="save_arf_edit_profile" type="submit" style="margin-left:25px;width:100px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/save_icon.png">&nbsp;&nbsp;<?php _e('Save', 'ARForms-user-registration') ?></button>
    <?php if( isset($_GET['arfaction']) && $_GET['arfaction'] == 'edit' ){ ?>
    &nbsp;&nbsp;&nbsp;
    <button class="whitecancelbtn" type="submit" id="arf_reset_edit_profile" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" name="arf_reset_edit_profile"><?php _e('Reset', 'ARForms-user-registration') ?></button>
    <?php } ?>
    &nbsp;&nbsp;&nbsp;
    <button class="whitecancelbtn" type="button" style="background-color: #FFFFFF;border-radius: 3px 3px 3px 3px;height: 41px;width: 101px;" onClick="location.href='?page=ARForms-user-registration'"><?php _e('Cancel', 'ARForms-user-registration') ?></button>
</div>
    
 </form>
<?php unset($values); $values = array(); unset($meta); ?>