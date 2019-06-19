<?php
	
	class realty_house_price_offer
	{
		public function __construct()
		{
			add_action( 'wp_ajax_nopriv_realty_house_price_offer', array ( $this, 'add_item' ) );
			add_action( 'wp_ajax_realty_house_price_offer', array ( $this, 'add_item' ) );
			
			add_action( 'wp_ajax_nopriv_realty_house_price_offer_delete', array ( $this, 'delete_item' ) );
			add_action( 'wp_ajax_realty_house_price_offer_delete', array ( $this, 'delete_item' ) );
		}
		
		public function add_item()
		{
			global $wpdb;
			$table_price_offer = $wpdb->prefix . "price_offer";
			
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'ajax-send-price-offer-nonce' ) )
			{
				$p_id        = ! empty( $_POST['pID'] ) ? intval( $_POST['pID'] ) : '';
				$name        = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
				$email       = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
				$phone       = ! empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
				$offer       = ! empty( $_POST['offer'] ) ? intval( $_POST['offer'] ) : '';
				$description = ! empty( $_POST['desc'] ) ? sanitize_text_field( $_POST['desc'] ) : '';
				
				if ( ! empty( $p_id ) && ! empty( $name ) && ! empty( $email ) && ! empty( $phone ) && ! empty( $offer ) )
				{
					$query_result = $wpdb->get_row( "SELECT * FROM $table_price_offer WHERE `p_id` = '$p_id' AND `name` = '$name' AND `email` = '$email' AND `phone` = '$phone' AND `offer` = '$offer'" );
					$pre_saved    = count( $query_result );
					if ( $pre_saved === 0 )
					{
						$inserted_val = $wpdb->insert( $table_price_offer, array (
							'p_id'        => $p_id,
							'name'        => $name,
							'email'       => $email,
							'phone'       => $phone,
							'offer'       => $offer,
							'description' => $description,
						), array ( '%d', '%s', '%s', '%s', '%d', '%s' ) );
						if ( $inserted_val )
						{
							$return_value = array (
								'status'  => true,
								'message' => esc_html__( 'Thanks for sending your offer.', 'realty-house-pl' )
							);
						}
						else
						{
							$return_value = array (
								'status'  => false,
								'message' => esc_html__( 'Because of some technical issues you can not send your offer, please try again later.', 'realty-house-pl' )
							);
						}
					}
					else
					{
						$return_value = array (
							'status'  => false,
							'message' => esc_html__( 'You have already sent your offer.', 'realty-house-pl' )
						);
					}
				}
				else
				{
					$return_value = array (
						'status'  => false,
						'message' => esc_html__( 'Please provide all the required information.', 'realty-house-pl' )
					);
				}
			}
			else
			{
				$return_value = array (
					'status'  => false,
					'message' => esc_html__( 'Are your cheating?', 'realty-house-pl' )
				);
			}
			
			echo json_encode( $return_value );
			die();
		}
		
		public function delete_item()
		{
			
			global $wpdb;
			$table_price_offer = $wpdb->prefix . "price_offer";
			
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'price_offer_delete_item' ) )
			{
				$item_id = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
				$p_id    = ! empty( $_POST['pID'] ) ? intval( $_POST['pID'] ) : '';
				
				if ( ! empty( $item_id ) && ! empty( $p_id ) )
				{
					$query_result = $wpdb->get_row( "SELECT * FROM $table_price_offer WHERE `id` = '$item_id' AND `p_id` = '$p_id'" );
					$pre_saved    = count( $query_result );
					if ( $pre_saved > 0 )
					{
						$deleted_rows = $wpdb->delete( $table_price_offer, array (
							'id'   => $item_id,
							'p_id' => $p_id
						), array ( '%d', '%d' ) );
						if ( ! empty( $deleted_rows ) )
						{
							$return_value = array (
								'status'  => true,
								'message' => esc_html__( 'The was removed.', 'realty-house-pl' )
							);
						}
						else
						{
							$return_value = array (
								'status'  => false,
								'message' => esc_html__( 'Because of some technical issue, this item can not be removed. Please try again.', 'realty-house-pl' )
							);
						}
					}
					else
					{
						$return_value = array (
							'status'  => false,
							'message' => esc_html__( 'We can not find this record in database.', 'realty-house-pl' )
						);
					}
				}
				else
				{
					$return_value = array (
						'status'  => false,
						'message' => esc_html__( 'Please provide all the required information.', 'realty-house-pl' )
					);
				}
			}
			else
			{
				$return_value = array (
					'status'  => false,
					'message' => esc_html__( 'Are your cheating?', 'realty-house-pl' )
				);
			}
			
			echo json_encode( $return_value );
			die();
			
		}
	}
	
	$price_offer_obj = new realty_house_price_offer();