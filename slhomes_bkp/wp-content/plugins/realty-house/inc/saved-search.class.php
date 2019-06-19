<?php
	
	class realty_house_saved_search
	{
		public function __construct()
		{
			add_action( 'wp_ajax_nopriv_realty_house_save_search', array ( $this, 'add_item' ) );
			add_action( 'wp_ajax_realty_house_save_search', array ( $this, 'add_item' ) );
			
			add_action( 'wp_ajax_nopriv_realty_house_save_search_delete', array ( $this, 'delete_item' ) );
			add_action( 'wp_ajax_realty_house_save_search_delete', array ( $this, 'delete_item' ) );
		}
		
		public function add_item()
		{
			global $wpdb;
			$table_saved_search = $wpdb->prefix . "saved_search";
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'ajax-save-search-nonce' ) )
			{
				$user_id = ! empty( $_POST['userID'] ) ? intval( $_POST['userID'] ) : '';
				$title   = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
				$query   = ! empty( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';
				$query   = ! empty( $query ) ? str_replace( '/search-property/', '', $query ) : '';
				
				if ( ! empty( $user_id ) && ! empty( $title ) && ! empty( $query ) )
				{
					$query_result = $wpdb->get_row( "SELECT * FROM $table_saved_search WHERE `query` = '$query' AND `user_id` = '$user_id' AND `title` = '$title'" );
					$pre_saved    = count( $query_result );
					if ( $pre_saved === 0 )
					{
						$inserted_val = $wpdb->insert( $table_saved_search, array (
							'query'   => $query,
							'user_id' => $user_id,
							'title'   => $title
						), array ( '%s', '%d', '%s' ) );
						if ( $inserted_val )
						{
							$return_value = array (
								'status'  => true,
								'message' => esc_html__( 'Your search is saved correctly.', 'realty-house-pl' )
							);
						}
						else
						{
							$return_value = array (
								'status'  => false,
								'message' => esc_html__( 'Because of some technical issue we can not save your search.', 'realty-house-pl' )
							);
						}
					}
					else
					{
						$return_value = array (
							'status'  => false,
							'message' => esc_html__( 'You have already saved this search.', 'realty-house-pl' )
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
			$table_saved_search = $wpdb->prefix . "saved_search";
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'saved_search_delete_item' ) )
			{
				$user_id = ! empty( $_POST['userID'] ) ? intval( $_POST['userID'] ) : '';
				$title   = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
				$item_id = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
				
				if ( ! empty( $user_id ) && ! empty( $title ) && ! empty( $item_id ) )
				{
					$query_result = $wpdb->get_row( "SELECT * FROM $table_saved_search WHERE `id` = '$item_id' AND `user_id` = '$user_id' AND `title` = '$title'" );
					$pre_saved    = count( $query_result );
					if ( $pre_saved > 0 )
					{
						$deleted_rows = $wpdb->delete( $table_saved_search, array (
							'id'      => $item_id,
							'user_id' => $user_id,
							'title'   => $title,
						), array ( '%d', '%d', '%s' ) );
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
	
	$saved_search_obj = new realty_house_saved_search();