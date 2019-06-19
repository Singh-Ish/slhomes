<?php
$attr = iml_default_shortcode_values($attr);
$general_settings_data = iml_general_settings_meta_vc();

	#initiate variables
	$final_str = '';
	$html = '';
	$js = '';
	$css = '';
	$tool_tip_js = '';

    $show_arr = explode(',', $attr['show']);
    #ORDER
    switch($attr['order_by']){
    	case 'name':
    		$orderby = 'title';
    	break;
    	case 'date':
    		$orderby = 'date';
    	break;
    	case 'rand':
    		$orderby = 'rand';
    	break;
    	case 'last_name':
    		$orderby = 'name';
    		$last_name = true;
    	break;    	
    }
    #LIMIT
    if ($attr['limit']==0) $limit = -1;
    else $limit = $attr['limit'];
    
    $args = array(
    	'posts_per_page'   => $limit,
    	'orderby'          => $orderby,
    	'order'            => $attr['order'],
    	'post_type'        => IML_POST_TYPE_VC,
    	'post_status'      => 'publish',
        );
    if(isset($attr['filter_set']) && $attr['filter_set']==1){
		if(isset($attr['filter_groups'])){
			$terms_arr = explode(',', $attr['filter_groups']);
			$args['tax_query'] = array(
												array(
													'taxonomy' => IML_TAXONOMY_VC,
													'field' => 'slug',
													'terms' => $terms_arr
											)
										);
		}else{
			$terms_arr = array();
			$group_arg = array(
                    'taxonomy' => IML_TAXONOMY_VC,
                    'type' => IML_POST_TYPE_VC,
					'hide_empty' => 0,
					'orderby' => 'slug');
			$groups_arr = get_categories($group_arg);
			if(isset($groups_arr) && count($groups_arr) > 0 && $groups_arr !== FALSE)
			foreach($groups_arr as $val){
				$terms_arr[] = $val->slug; 
			}
			$args['tax_query'] = array();
		}
    }
    elseif($attr['group']=='all'){
    	$args['tax_query'] = array();
    }
    else{
    	if(strpos($attr['group'], ',')!==FALSE){
    		$attr['group'] = explode(',', $attr['group']);
    	}
    	$args['tax_query'] = array(
						    			array(
						    					'taxonomy' => IML_TAXONOMY_VC,
						    					'field' => 'slug',
						    					'terms' => $attr['group']
						    			)
    								);
    }
    
    #Getting the posts
    $the_posts = get_posts($args);

    #reorder by last name
    if(!empty($last_name)){
    	$the_posts = iml_reorder_by_last_name_vc($the_posts, $attr['order']);
    }     

if(count($the_posts)>0){
    if($attr['slider_set']==1){
        $parent_class = 'carousel_view';
    }else{
    	$parent_class = 'imc_content_cl';
    }
    $num = rand(1, 10000);
    $div_parent_id = 'indeed_carousel_view_widget_' . $num;
    $arrow_wrapp_id = 'wrapp_arrows_widget_' . $num;
    $ul_id = 'indeed_ul_' . $num;
    
    if(!isset($attr['effect'])) $attr['effect']='';

    $theme_file = IML_DIR_PATH_VC .'themes/'. $attr['theme'] . "/index.php";
    if( file_exists( $theme_file ) ){
    	include( $theme_file );
    } else {
    	return '';
    }
    
    $html .= '<link rel="stylesheet" href="' . IML_DIR_URL_VC . 'themes/' . $attr['theme'] .'/style.css" type="text/css" media="all">';
	$html .= '<link rel="stylesheet" href="'. IML_DIR_URL_VC .'files/css/effect_style.css" type="text/css" media="all"/>';

	//if not set pagination theme, put blank quotes
	if (!isset($attr['pagination_theme'])){
		$attr['pagination_theme'] = '';
		if ($attr['slider_set']){
			$attr['pagination_theme'] = 'pag-theme1';
		}	
	}
	
    $html .= "<div class='".$attr['theme']." ".$attr['pagination_theme']."'>";
    $html .= "<div class='imc_wrapp'>";
    $html .= "<div class='$parent_class' id='$div_parent_id' >";
    $default_item = $list_item_template;
    $li_width = 100 / $attr['columns'];
    $li_width = 'calc('.$li_width.'% - 1px)';
    $j = 1;
    $breaker_div = 1;
    $new_div = 1;
    $total_items = count($the_posts);
    if($attr['slider_set']==1) $items_per_slide = $attr['items_per_slide'];
    else $items_per_slide = $total_items;
if(isset($attr['filter_set']) && $attr['filter_set']==1){
  /********************************* FILTER *******************************/
  $filter_rand_num = rand(1,5000);
  //// additional STYLE
  $css .= '
  					/*.clientContainer li{
						webkit-transition-duration: 0.8s;
						-moz-transition-duration: 0.8s;
						transition-duration: 0.8s;
					}*/
					 .clientFilter_'.$filter_rand_num.' .clientFilterlink-small_text:hover{
						color: #6f7e8a;
						text-decoration: underline;
					}
					.clientFilter-wrapper-small_text  .clientFilter_'.$filter_rand_num.' .current{
						color: #6f7e8a;
						text-decoration: underline;
					}
					 .clientFilter_'.$filter_rand_num.' .clientFilterlink-big_text:hover{
						color: #6f7e8a;
						text-decoration: underline;
					}
					.clientFilter-wrapper-big_text  .clientFilter_'.$filter_rand_num.' .current{
						color: #6f7e8a;
						text-decoration: underline;
					}
					 .clientFilter_'.$filter_rand_num.' .clientFilterlink-small_button:hover{
						background-color:#6f7e8a;
						color:#fff;
					}
					.clientFilter-wrapper-small_button  .clientFilter_'.$filter_rand_num.' .current{
						background-color:#6f7e8a;
						color:#fff;
					}
					 .clientFilter_'.$filter_rand_num.' .clientFilterlink-big_button:hover{
						background-color:#6f7e8a;
						border-color:#6f7e8a;
						color:#fff;
					}
					.clientFilter-wrapper-big_button .clientFilter_'.$filter_rand_num.'  .current{
						background-color:#6f7e8a;
						border-color:#6f7e8a;
						color:#fff;
					}
  ';
  
  if (!isset($attr['layout_mode']) || !$attr['layout_mode']) $attr['layout_mode'] = 'masonry';//default
  //// additional JS
  $js .= "		jQuery(window).load(function(){
						var container = jQuery('.indeed_client_filter_".$filter_rand_num."');
						container.isotope({
							filter: '*',
							layoutMode: '".$attr['layout_mode']."',
							transitionDuration: '1s',
						});
  				";
  if($attr['filter_select_t']=='dropdown'){
  	$js .= "

                        jQuery('.clientFilterlink-select_".$filter_rand_num."').change(function(){
							var selector = jQuery('.clientFilterlink-select_".$filter_rand_num."').val();
							container.isotope({
								filter: selector,
								layoutMode: '".$attr['layout_mode']."',
								transitionDuration: '1s',
							 });
							 return false;
                        });
                   ";
  }else{
  	$js .= "
  						jQuery('.clientFilter_".$filter_rand_num." div').click(function(){
  							jQuery('.clientFilter_".$filter_rand_num." .current').removeClass('current');
  							jQuery(this).addClass('current');
  	
  							var selector = jQuery(this).attr('data-filter');
  							container.isotope({
  									filter: selector,
  									layoutMode: '".$attr['layout_mode']."',
  									transitionDuration: '1s',
  							});
  							return false;
  						});
  					";  	
  }
  	$js .= "});";
  	
  $attr['slider_set'] = 0;//secure slider at 0
  $html .= '<div class="clientFilter-wrapper clientFilter-wrapper-'.$attr['filter_select_t'].'"" style="text-align:'.$attr['filter_align'].';">';
    $html .= '<div class="clientFilter_'.$filter_rand_num.'">';
    if(isset($terms_arr) && count($terms_arr)>0){
            if($attr['filter_select_t']=='dropdown'){
                //DROPDOWN
                    $html .= '<div class="clientFilterlink-'.$attr['filter_select_t'].'">';
                    $html .= '<select class="clientFilterlink-select_'.$filter_rand_num.'">';
                    $html .= '<option value="*">'.__('All', 'iml').'</option>';
                    foreach($terms_arr as $term){
                        $group = get_term_by('slug', $term, IML_TAXONOMY_VC);
                        $html .= '<option value=".'.$term.'">'. $group->name .'</option>';
                    }
                    $html .= '</select>';
                    $html .= '</div>';
					$html .= '</div>';
            }else{
                //SMALL text,button BIG text,buttons
                    $html .= '<div class="clientFilterlink clientFilterlink-'.$attr['filter_select_t'].'" data-filter="*">'.__('All', 'iml').'</div>';
                    foreach($terms_arr as $term){
                        $group = get_term_by('slug', $term, IML_TAXONOMY_VC);
                        $html .= '<div class="clientFilterlink-'.$attr['filter_select_t'].'" data-filter=".'.$term.'">'. $group->name .'</div>';
                    }
                    $html .= '</div>';
            }
    }
  $html .= '</div>';
  $html .= "<ul class='clientContainer indeed_client_filter_".$filter_rand_num."'>";
    foreach($the_posts as $post){
        $team_terms_arr = get_the_terms( $post->ID, IML_TAXONOMY_VC );
        $team_slug_str = '';
        foreach($team_terms_arr as $term_arr){
        	if($team_slug_str!='') $team_slug_str .= ' ';
            $team_slug_str .= $term_arr->slug;
        }

        $html .= "<li style='width: $li_width;' class='{$attr['effect']} indeed-animated-effect $team_slug_str' data-category='$team_slug_str'>";//animated
        /////ITEM HEIGHT
        $list_item_template = str_replace("ITEM_HEIGHT", $attr['item_height'], $list_item_template);
        ////NAME
        if(in_array('name', $show_arr)){
            $name = get_the_title($post->ID);
            $list_item_template = str_replace("IMC_NAME", $name, $list_item_template);
        }else $list_item_template = str_replace("IMC_NAME", "", $list_item_template);

        ////LOGO
        $src = '';
        if (in_array('logo', $show_arr)){
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, false, '' );
            if (isset($src[0]) && $src[0]){
            	//logo for current post
            	$src = $src[0];
            } else {
            	//default logo
            	if (!isset($default_logo)) $default_logo = iml_return_default_logo_vc();
            	$src = $default_logo;            
            }
        }
        $list_item_template = str_replace("IMC_LOGO", $src ,$list_item_template);
        
        ////LINK
        $replace_link = '';
        if(in_array('link', $show_arr)){
            $link = get_post_meta( $post->ID, 'imc_cf_link', true );
            if ($link){
            	if (strpos($link, 'http')  === false) $link = "http://" . $link;
            	$replace_link = ' href="'.$link.'"';
            	if (!empty($general_settings_data['iml_target_blank'])){
            		$replace_link .= ' target="_blank"';
            	}
            	$replace_link .= ' rel="nofollow" ';
            }
        }
        $list_item_template = str_replace("IMC_LINK", $replace_link, $list_item_template);
        
        ////TOOL TIP
        $tool_tip = '';
        if(in_array('tool_tip', $show_arr)){
            $tool_tip_data = get_post_meta( $post->ID, 'imc_cf_tool_tip', true );
            if ($tool_tip_data) {
            	$tool_tip = $tool_tip_data;
            	$tool_tip_js = true;
            }
            add_action('wp_footer', 'iml_vc_footer_scripts');
        }
        $list_item_template = str_replace("IMC_TOOL_TIP", $tool_tip, $list_item_template);
        
        $html .= $list_item_template;
        $html .= "</li>";
        $list_item_template = $default_item;
    }#end of foreach post
    $html .= "<div class='clear'></div></ul>";
}else{
	/********************************* WITHOUT FILTER *******************************/	
    foreach($the_posts as $post){
        if($new_div==1){
            $div_id = $ul_id.'_' . $breaker_div;
            $html .= "<ul id='$div_id' class=''>"; /////ADDING THE UL
        }
        
            $html .= "<li style='width: $li_width;' class='{$attr['effect']} indeed-animated-effect'>";//animated
            
        /////ITEM HEIGHT
        $list_item_template = str_replace("ITEM_HEIGHT", $attr['item_height'], $list_item_template);
        
        ////NAME
        if(in_array('name', $show_arr)){
            $name = get_the_title($post->ID);
            $list_item_template = str_replace("IMC_NAME", $name, $list_item_template);
        }else $list_item_template = str_replace("IMC_NAME", "", $list_item_template);

        ////LOGO
        $src = '';
        if (in_array('logo', $show_arr)){
        	$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, false, '' );
        	if (isset($src[0]) && $src[0]){
        		//logo for current post
        		$src = $src[0];
        	} else {
        		//default logo
        		if (!isset($default_logo)) $default_logo = iml_return_default_logo_vc();
        		$src = $default_logo;
        	}
        }
        $list_item_template = str_replace("IMC_LOGO", $src ,$list_item_template);
        
        
        ////LINK
        $replace_link = '';
        if(in_array('link', $show_arr)){
            $link = get_post_meta( $post->ID, 'imc_cf_link', true );
            if ($link){
            	if (strpos($link, 'http')  === false) $link = "http://" . $link;
            	$replace_link = ' href="'.$link.'" ';
            	if (!empty($general_settings_data['iml_target_blank'])){
            		$replace_link .= ' target="_blank"';
            	}
            	$replace_link .= ' rel="nofollow" ';
            }
        }
        $list_item_template = str_replace("IMC_LINK", $replace_link, $list_item_template);
        
        ////TOOL TIP
        $tool_tip = '';
        if(in_array('tool_tip', $show_arr)){
            $tool_tip_data = get_post_meta( $post->ID, 'imc_cf_tool_tip', true );
            if ($tool_tip_data) {
            	$tool_tip = $tool_tip_data;
            	$tool_tip_js = true;
            }
            add_action('wp_footer', 'iml_vc_footer_scripts');
        }
        $list_item_template = str_replace("IMC_TOOL_TIP", $tool_tip, $list_item_template);
        
        $html .= $list_item_template;
        $html .= "</li>";
        $list_item_template = $default_item;
      if( $j % $items_per_slide==0 || $j==$total_items ){
      	  $breaker_div++;
      	  $new_div = 1;
          $html .= "<div class='clear'></div></ul>";
      }else $new_div = 0;
      $j++;
    }#end of foreach post
    
    #SLIDER JAVASCRIPT
    if($attr['slider_set']==1){
    	$total_pages = $total_items / $items_per_slide;
    	if($total_pages>1){
              $navigation = 'false';
              $bullets = 'false';
              $autoplay = 'false';
			  $autoheight = 'false';
              $stop_hover = 'false';
              $loop = 'false';
              $responsive = 'false';
              $lazy_load = 'false';
			  $autoplayTimeout = 5000;
              $animation_in = 'false';
              $animation_out = 'false';

              if( strpos( $attr['slide_opt'], 'nav_button')!==FALSE) $navigation = 'true';
              if( strpos( $attr['slide_opt'], 'bullets')!==FALSE) $bullets = 'true';
			  if( strpos( $attr['slide_opt'], 'autoheight')!==FALSE) $autoheight = 'true';
              if( strpos( $attr['slide_opt'], 'autoplay')!==FALSE){
              	$autoplay = 'true';
				$autoplayTimeout = $attr['slide_speed'];
              }
              if( strpos( $attr['slide_opt'], 'stop_hover')!==FALSE) $stop_hover = 'true';
			  if( strpos( $attr['slide_opt'], 'loop')!==FALSE) $loop = 'true';
              if( strpos( $attr['slide_opt'], 'responsive')!==FALSE) $responsive = 'true';
              if( strpos( $attr['slide_opt'], 'lazy_load')!==FALSE) $lazy_load = 'true';
              if( isset($attr['animation_in']) && $attr['animation_in']!='none' ) $animation_in = "'{$attr['animation_in']}'";
			  if( isset($attr['animation_out']) && $attr['animation_out']!='none' ) $animation_out = "'{$attr['animation_out']}'";
    		
    		
    		$js .= "
    			jQuery(document).ready(function() {
    				var owl = jQuery('#{$div_parent_id}');
    				owl.owlimlvcCarousel({
											items : 1,
											mouseDrag: true,
											touchDrag: true,
											
											autoHeight: $autoheight,
											
											animateOut: $animation_out,
    										animateIn: $animation_in,
											
											lazyLoad : $lazy_load,
											loop: $loop,
											
											autoplay : $autoplay,
											autoplayTimeout: $autoplayTimeout,
											autoplayHoverPause: $stop_hover,
											autoplaySpeed: {$attr['slide_pagination_speed']},
											
											nav : $navigation,
											navSpeed : {$attr['slide_pagination_speed']},
											navText: [ '', '' ],
											
											dots: $bullets,
											dotsSpeed : {$attr['slide_pagination_speed']},
											
											responsiveClass: $responsive,
                                			responsive:{
											0:{
												nav:false
											},
											450:{
												nav : $navigation
											}
										}
    				});
    			});
    		";
    	}
    }   
}



#align center
if(isset($attr['align_center']) && $attr['align_center']==1) $css .= '#'.$div_parent_id.' ul{text-align: center;}';
#responsive settings

if(isset($general_settings_data['iml_responsive_settings_small']) &&  $general_settings_data['iml_responsive_settings_small']!='auto'){
	$li_w = 100 / $general_settings_data['iml_responsive_settings_small'];
	$css .= '
			@media only screen and (max-width: 479px) {
					#'.$div_parent_id.' ul li{
					width: calc('.$li_w.'% - 1px) !important;
				}
			}
	';
}
if(isset($general_settings_data['iml_responsive_settings_medium']) && $general_settings_data['iml_responsive_settings_medium']!='auto'){
	$li_w = 100 / $general_settings_data['iml_responsive_settings_medium'];
	$css .= '
			@media only screen and (min-width: 480px) and (max-width: 767px){
				#'.$div_parent_id.' ul li{
						width: calc('.$li_w.'% - 1px) !important;
					}
				}
			';
}
if(isset($general_settings_data['iml_responsive_settings_large']) && $general_settings_data['iml_responsive_settings_large']!='auto'){
	$li_w = 100 / $general_settings_data['iml_responsive_settings_large'];
	$css .= '
					@media only screen and (min-width: 768px) and (max-width: 959px){
					#'.$div_parent_id.' ul li{
						width: calc('.$li_w.'% - 1px) !important;
					}
				}
			';
}
#custom css
if(isset($general_settings_data['iml_custom_css']) && $general_settings_data['iml_custom_css']!=''){
	$css .= stripslashes($general_settings_data['iml_custom_css']);
}

    $html .= "</div>";
    $html .= "</div>";//end of imc_wrapp
    $html .= "</div>";//end of theme_n

    if ($tool_tip_js){
    	//add tool tip
        $js .= '
        			jQuery(document).ready(function(){
						jQuery( document ).tooltip({
						    items : ".tool_tip_set",
						    position : {
						                    my: "center top+3",
						                    at: "center bottom+3"
						               }
						});      
    				});      
        		'; 
    } 
    
    $final_str = '<style>'.$css.'</style>'.'<script>'.$js.'</script>'.$html;    
	if( !isset($return_str) || $return_str!=true) echo $final_str;
}

