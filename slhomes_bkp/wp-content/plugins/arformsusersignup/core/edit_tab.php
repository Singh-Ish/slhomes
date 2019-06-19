<?php 
global $wpdb; 
//get details

$set_id = isset($_REQUEST['set_id']) ? $_REQUEST['set_id'] : '-1';
$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_form_sets WHERE id = %d", $set_id) );

if( $result )
	$result = $result[0];

$options	= maybe_unserialize( $result->options );	
$current_tab= isset($options['current_tab']) ? $options['current_tab'] : 'user_registration';	
$form_list	= $options['form_list'];

if( isset($_GET['msg']) and $_GET['msg'] == 1 )
	$message = __('Form is successfully updated.', 'ARForms-user-registration');
?>
<div class="wrap user_registration_page"> 

  <div id="poststuff" class="metabox-holder">
	<div id="post-body">
	  <div class="inside">
		
        <div class="page_title"><?php echo $tabs_array[ $tab ]; ?></div>
        <div style="clear:both;"></div>
		<?php $current_tab = ( !isset($current_tab) || empty($current_tab) ) ? 'user_registration' : $current_tab; ?>

        <!-- start of setting_tabrow -->
        <div id="arftabmain">
            <ul class="fiveStep" id="mainNav">
                
                <li id="tab_user_registration" class="arftab firstmenu <?php if($current_tab == 'user_registration') { echo 'current'; } else if($current_tab == 'login'){ echo 'lastDone'; } else { echo 'done'; }?>" onmouseover="arfshowloginimg()" onmouseout="arfhideloginimg();"><a href="javascript:arf_change_user_tab('user_registration');"><div class="arf_userreg_icon"></div>&nbsp;<?php _e('User Signup', 'ARForms-user-registration'); ?></a><div class="arf_fixer_img"></div></li>
                
                <li id="tab_login" class="arftab secondmenu <?php if($current_tab == 'login') { echo 'current'; } else if($current_tab == 'change_password' || $current_tab == 'forgot_password' || $current_tab == 'reset_password' ){ echo 'lastDone'; } else if($current_tab == 'user_registration'){ echo 'done nextdone'; } else { echo 'done'; }?>"><div id="tab_login_img" class="arf_fixer_img2"></div><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('login');"><div class="arf_login_icon"></div>&nbsp;<?php _e('Login / Logout', 'ARForms-user-registration'); ?></a></li>
                
                <li id="tab_change_password" class="arftab thriddmenu <?php if($current_tab == 'change_password' || $current_tab == 'forgot_password' || $current_tab == 'reset_password') { echo 'current'; } else if($current_tab == 'edit_profile'){ echo 'lastDone'; } else if($current_tab == 'login'){ echo 'done nextdone'; } else { echo 'done'; }?>"><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('change_password');"><div class="arf_changepass_icon"></div>&nbsp;<?php _e('Change / Forgot / Reset Password', 'ARForms-user-registration'); ?></a></li>
                
                <li id="tab_edit_profile" class="arftab mainNavNoBg <?php if($current_tab == 'edit_profile') { echo 'current'; } else if($current_tab == 'change_password' || $current_tab == 'forgot_password' || $current_tab == 'reset_password' ){ echo 'done nextdone'; } else { echo 'done'; }?>"><div class="arf_fixer_img"></div><a href="javascript:arf_change_user_tab('edit_profile');"><div class="arf_editprofile_icon"></div>&nbsp;<?php _e('Edit Profile', 'ARForms-user-registration'); ?></a></li>
            </ul>
        </div>

        <!-- end of setting_tabrow -->
        
        
        <div id="arf_user_registration_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($current_tab != 'user_registration'){ ?>style="display:none;"<?php } ?> >
			  
            <?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_user_registration.php'); ?>  
             
        </div> <!-- arf_user_registration_conent wrap -->
        
        <div id="arf_login_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($current_tab != 'login'){ ?>style="display:none;"<?php } ?>>
        	
            <?php include_once(ARF_USER_REGISTRATION_DIR.'/core/edit_login.php'); ?>
			 
        </div> <!-- arf_login_conent wrap -->
        
        <div id="arf_change_password_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($current_tab != 'change_password' && $current_tab != 'forgot_password' && $current_tab != 'reset_password'){ ?>style="display:none;"<?php } ?>>
        	<?php 
			$change_password_message = ( $current_tab == 'change_password' || $current_tab == 'forgot_password' || $current_tab == 'reset_password'  ) ? $message : '';
			?>
            <?php if (isset($change_password_message) && $change_password_message != ''){ if(is_admin()){ ?><div id="success_message" style="margin-bottom:0px; margin-top:15px; width:95%;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php } echo $change_password_message; if(is_admin()){ ?></div></div><?php } } ?>
            
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
        
        <div id="arf_edit_profile_conent" class="arf_tab_content frm_settings_form wrap_content" <?php if($current_tab != 'edit_profile'){ ?>style="display:none;"<?php } ?>>
        	
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