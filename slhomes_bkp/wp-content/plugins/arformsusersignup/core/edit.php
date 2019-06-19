<div class="wrap user_registration_page"> 
  <div id="poststuff" class="metabox-holder">
	<div id="post-body">
	  <div class="inside">
		<div class="page_title" style="margin-left:30px; margin-top:30px;"><?php _e('Form Configuration', 'ARForms-user-registration'); ?></div>
  
		<?php $values['current_tab'] = ( !isset($values['current_tab']) || empty($values['current_tab']) ) ? 'user_registration' : $values['current_tab']; 
		$arfaction 	= 'new';
		$set_id		= (isset($set_id) and $set_id != '') ? $set_id : '';
		?>

        <!-- start of setting_tabrow -->
        <div id="arftabmain">
            <ul class="fiveStep" id="mainNav">
                <li id="tab_user_registration" class="arftab firstmenu  current" onmouseover="arfshowloginimg()" onmouseout="arfhideloginimg();"><a href="javascript:arf_change_user_tab('user_registration');"><div class="arf_userreg_icon"></div>&nbsp;<?php _e('User Signup', 'ARForms-user-registration');?></a><div class="arf_fixer_img"></div></li>
                <li id="tab_login" class="arftab secondmenu done nextdone"><div id="tab_login_img" class="arf_fixer_img2"></div><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('login');"><div class="arf_login_icon"></div>&nbsp;<?php _e('Login / Logout', 'ARForms-user-registration');?></a></li>
                <li id="tab_change_password" class="arftab thriddmenu done"><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('change_password');"><div class="arf_changepass_icon"></div>&nbsp;<?php _e('Change / Forgot / Reset Password', 'ARForms-user-registration');?></a></li>
                <li id="tab_edit_profile" class="arftab mainNavNoBg done"><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('edit_profile');"><div class="arf_editprofile_icon"></div>&nbsp;<?php _e('Edit Profile', 'ARForms-user-registration');?></a></li>
            </ul>
        </div>
        <!-- end of setting_tabrow -->
        
        <div id="arf_user_registration_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($values['current_tab'] != 'user_registration'){ echo 'style="display:none;"'; } ?>>
			  
            <?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_user_registration.php'); ?>  
             
        </div> <!-- arf_user_registration_conent wrap -->
        
        
        <div id="arf_login_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($values['current_tab'] != 'login'){ echo 'style="display:none;"'; } ?>>
        	
            <?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_login.php'); ?>
			 
        </div> <!-- arf_login_conent wrap -->
       
        <div id="arf_change_password_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($values['current_tab'] != 'change_password'){ echo 'style="display:none;"'; } ?>>
        	
            <div class="change_password_inside">
            	<?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_change_password.php'); ?>
            </div>
            
            <div style="clear:both;border-bottom: 1px solid #EAEAEA;padding-top: 30px;margin-bottom: 20px;"></div>
            
            <div class="forgot_password_inside">
            	<?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_forgot_password.php'); ?>
            </div>
            
            <div style="clear:both;border-bottom: 1px solid #EAEAEA;padding-top: 30px;margin-bottom: 20px;"></div>
        
        	<div class="reset_password_inside">
            	<?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_reset_password.php'); ?>
            </div>
           
             	
        </div> <!-- arf_change_password_conent wrap -->
        
        
        <div id="arf_edit_profile_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($values['current_tab'] != 'edit_profile'){ echo 'style="display:none;"'; } ?>>
        	
            <?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_profile.php'); ?>
            
        </div> <!-- arf_edit_profile_conent wrap -->

      </div>  
	</div>
  </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	var images = '';
	<?php 
	$attachments = array('edit-icon.png', 'edit-icon-select.png', 'edit-icon-select.png', 'login-icon.png', 'login-icon-select.png', 'navCurrentBtn.png', 'navCurrentBtn2.png', 'navDoneBtn.png', 'navDoneBtn-white.png', 'navLastDoneBtn.png', 'navLastDoneBtn2.png', 'password-icon.png', 'password-icon-select.png', 'user-icon.png', 'user-icon-select.png');	
	foreach($attachments as $attachment)
	{
	?>
	images += '<img src="<?php echo ARF_USER_REGISTRATION_URL.'/images/'.$attachment;?>" style="display:none" />';
	<?php } ?>
	if(images != ''){
		jQuery('body').append(images);
	}
});
</script>   