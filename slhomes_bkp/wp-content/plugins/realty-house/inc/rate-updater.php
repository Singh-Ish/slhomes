<?php
	if ( ! function_exists( 'realty_house_plg_ajax_update_rate_item' ) )
	{
		function realty_house_plg_ajax_update_rate_item()
		{
			global $realty_house_opt;
			
			
			foreach ( $realty_house_opt['realty-house-agent-rate-items'] as $index => $agent_rate_item )
			{
				$agent_rate_meta_fields[] = 'agent_rate_' . $index;
			}
			
			if ( in_array( $_POST['rateItem'], $agent_rate_meta_fields ) )
			{
				$post_id       = intval( $_POST['id'] );
				$rate_item     = sanitize_text_field( $_POST['rateItem'] );
				$rate_value    = intval( $_POST['rateVal'] ) + 1;
				$rate_item_val = get_post_meta( $post_id, $rate_item );
				$old_val       = ( ! empty( $rate_item_val ) ? get_post_meta( $post_id, $rate_item, true ) : '' );
				$new_val_str   = ( $old_val != 0 ? $old_val . ',' . $rate_value : $rate_value );
				update_post_meta( $post_id, $rate_item, $new_val_str );
				
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	add_action( 'wp_ajax_agent_rate_update', 'realty_house_plg_ajax_update_rate_item' );
	add_action( 'wp_ajax_nopriv_agent_rate_update', 'realty_house_plg_ajax_update_rate_item' );