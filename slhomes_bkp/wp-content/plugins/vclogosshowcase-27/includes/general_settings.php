<?php 
	if (isset($_REQUEST['iml_submit'])){
		iml_save_update_metas_vc();
	}
	$metas = iml_general_settings_meta_vc();
?>
<div class="imc_wrap">
	    <div class="">
	        <h3>
	        	<i class="icon-cogs"></i><?php echo __('General Settings', 'iml');?>
	        </h3>
	    </div>

	<form method="post" action="">	    
		<div class="stuffbox">
                    <h3 style="background-color: rgb(66, 66, 66) !important;">
                        <label><?php echo __('Responsive Settings', 'iml');?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table indeed_admin_table">
        	                <tbody>
                                <tr>
									<td><?php echo __('Screen Max-Width:', 'iml');?> <b>479px</b></td>
									<td>
										<select name="iml_responsive_settings_small">
											<?php 
												for($i=1;$i<7;$i++){
													$selected = '';
													if($metas['iml_responsive_settings_small']==$i) $selected = 'selected="selected"';
													?>
														<option value="<?php echo $i;?>" <?php echo $selected;?> ><?php echo $i.' '.__('Columns', 'iml');?></option>
													<?php 
												}
												$selected = '';
												if($metas['iml_responsive_settings_small']=='auto') $selected = 'selected="selected"';
												?>
													<option value="auto" <?php echo $selected;?> ><?php echo __('Auto', 'iml');?></option>
												<?php 
											?>
										</select>
									</td>
                                </tr>
                                <tr>
									<td><?php echo __('Screen Min-Width:', 'iml');?> <b>480px</b> <?php echo __('and Screen Max-Width:', 'iml');?> <b>767px</b></td>
									<td>
										<select name="iml_responsive_settings_medium">
											<?php 
												for($i=1;$i<7;$i++){
													$selected = '';
													if($metas['iml_responsive_settings_medium']==$i) $selected = 'selected="selected"';													
													?>
														<option value="<?php echo $i;?>" <?php echo $selected;?> ><?php echo $i.' '.__('Columns', 'iml');?></option>
													<?php 
												}
												$selected = '';
												if($metas['iml_responsive_settings_medium']=='auto') $selected = 'selected="selected"';
												?>
													<option value="auto" <?php echo $selected;?> ><?php echo __('Auto', 'iml');?></option>
												<?php 
											?>
										</select>
									</td>
                                </tr>
                                <tr>
									<td><?php echo __('Screen Min-Width:', 'iml');?> <b>768px</b> <?php echo __('and Screen Max-Width:', 'iml');?> <b>959px</b></td>
									<td>
										<select name="iml_responsive_settings_large">
											<?php 
												for($i=1;$i<7;$i++){
													$selected = '';
													if($metas['iml_responsive_settings_large']==$i) $selected = 'selected="selected"';													
													?>
														<option value="<?php echo $i;?>" <?php echo $selected;?> ><?php echo $i.' '.__('Columns', 'iml');?></option>
													<?php 
												}
												$selected = '';
												if($metas['iml_responsive_settings_large']=='auto') $selected = 'selected="selected"';
												?>
													<option value="auto" <?php echo $selected;?> ><?php echo __('Auto', 'iml');?></option>
												<?php 
											?>
										</select>
									</td>
                                </tr>                                
                            </tbody>
                        </table>
                        <div class="submit">
                            <input type="submit" value="<?php echo __('Save changes', 'iml');?>" name="iml_submit" class="button button-primary button-large" />
                        </div>
                    </div>
		</div>
		<div class="stuffbox">
               <h3 style="background-color: #d9534f !important;">
               		<label><?php echo __('Custom CSS', 'iml');?></label>
               </h3>
               <div class="inside">
			   <div style="margin-left: 10px;"><b><?php echo __('Add   !important;  after each style option and full style path to be sure that it will take effect!', 'iml');?></b></div>
                        <table class="form-table indeed_admin_table">
                        	<tr>
                        		<td>
                        			<textarea name="iml_custom_css" style="min-width: 500px;min-height: 100px;"><?php echo stripslashes($metas['iml_custom_css']);?></textarea>
                        		</td>
                        	</tr>
                        </table>
                    <div class="submit">
                    	<input type="submit" value="<?php echo __('Save changes', 'iml');?>" name="iml_submit" class="button button-primary button-large" />
                    </div>           
               </div>     
        </div> 	
       <div class="stuffbox">
               <h3 style="background: #FDB45C !important;">
               		<label><?php echo __('Default Logo', 'iml');?></label>
               </h3>   
               <div class="inside">   
               		<?php 
               			//preview default client image
               			if (isset($metas['iml_default_logo_img']) && $metas['iml_default_logo_img']){
               				?>
               					<div>
               						<img src="<?php echo $metas['iml_default_logo_img'];?>" class="iml-default-logo-img-admin" id=iml-default-logo-img-admin />
               					</div>               					
               				<?php 
               			} 
               		?> 
               		<input type="text" onClick="open_media_up(this, '#iml-default-logo-img-admin');" name="iml_default_logo_img" value="<?php echo $metas['iml_default_logo_img'];?>" style="width: 500px;"/>  
               		<div class="submit">
                    	<input type="submit" value="<?php echo __('Save changes', 'iml');?>" name="iml_submit" class="button button-primary button-large" />
                    </div>        
               </div>         		
       </div> 
       
		<div class="stuffbox">
		    <h3 style="background-color: rgb(0, 175, 209) !important;">
		        <label><?php _e('External Links', 'iml');?></label>
		    </h3>
		    <div class="inside">
		        <div>
		        	<input type="checkbox" onclick="check_and_h(this, '#iml_target_blank');" <?php if (!empty($metas['iml_target_blank'])) echo 'checked';?> />
		            <input type="hidden" value="<?php echo $metas['iml_target_blank'];?>" name="iml_target_blank" id="iml_target_blank">	            		
		            <?php _e("Open 'URL' in new Window", 'iml');?>            	
				</div>
		        <div class="submit">
		            <input type="submit" value="<?php echo __('Save changes', 'iml');?>" name="iml_submit" class="button button-primary button-large" />
		        </div>            	
		    </div>
		</div>   
		            	
	</form>
		<div class="stuffbox">
                    <h3 style="background: #9972b5 !important;">
                        <label><?php echo __('Post Type', 'iml');?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table indeed_admin_table">
        	                <tbody>
                                <tr>
									<td>
										<?php echo __('Name', 'iml');?>: 
									</td>
                                    <td>
										<input type="text" value="<?php echo IML_POST_TYPE_VC;?>" id="iml_post_type_name" style="min-width: 300px;"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="margin-left: 10px;"><b><?php echo __('If You change the Post Type, the current Logos will not be available anymore!', 'iml');?></b></div>
                        <div class="submit">
                            <input type="button" onClick="iml_change_post_type_name_vc('<?php echo get_site_url();?>');" value="<?php echo __('Save changes', 'iml');?>" name="imlst_submit" class="button button-primary button-large" />
                        </div>
                    </div>
		</div>	
</div>