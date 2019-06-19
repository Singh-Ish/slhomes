<?php
	
	class realty_house_bookmark_property
	{
		public function __construct()
		{
			add_action( 'init', array( $this, 'init' ) );
		}
		
		public function init()
		{
			add_action( 'wp_ajax_nopriv_realty_house_update_bookmark', array( $this, 'update_bookmark' ) );
			add_action( 'wp_ajax_realty_house_update_bookmark', array( $this, 'update_bookmark' ) );
			add_action( 'wp_login', array( $this, 'login_update' ), 10, 2 );
		}
		
		
		public static function login_update($user_login, $user_id)
		{
			if ( ! empty( $_COOKIE['pBookmark'] ) )
			{
				
				$old_bookmarked = get_user_meta( $user_id->ID, 'bookmarked_properties', true );
				
				if ( ! empty( $old_bookmarked ) )
				{
					$old_bookmarked_array = explode( ',', $old_bookmarked );
					$cookie_array         = explode( ',', $_COOKIE['pBookmark'] );
					
					foreach ( $old_bookmarked_array as $p_id )
					{
						if ( ! in_array( $p_id, $cookie_array ) )
						{
							$cookie_array[] = $p_id;
						}
						else
						{
							continue;
						}
					}
					
					$new_bookmark_str = '';
					foreach ( $cookie_array as $new_bookmark )
					{
						$new_bookmark_str .= ',' . $new_bookmark;
					}
					
					setcookie( 'pBookmark', trim( $new_bookmark_str, ',' ), time() + ( 86400 * 30 ), '/' );
					update_user_meta( $user_id->ID, 'bookmarked_properties', trim( $new_bookmark_str, ',' ) );
				}
				else
				{
					add_user_meta( $user_id->ID, 'bookmarked_properties', $_COOKIE['pBookmark'], true );
				}
			}
			else {
				$old_bookmarked = get_user_meta( $user_id->ID, 'bookmarked_properties', true );
				setcookie( 'pBookmark', trim( $old_bookmarked, ',' ), time() + ( 86400 * 30 ), '/' );
			}
		}
		
		public static function update_bookmark()
		{
			$post_id        = ! empty( $_POST['post_id'] ) ? filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT ) : '';
			$user_id        = get_current_user_id();
			$old_bookmarked = get_user_meta( $user_id, 'bookmarked_properties', true );
			
			if ( ! empty( $post_id ) )
			{
				if ( ! empty( $_COOKIE['pBookmark'] ) )
				{
					$cookie_array = explode( ',', trim( $_COOKIE['pBookmark'], ',' ) );
					
					if ( ( $key = array_search( $post_id, $cookie_array ) ) !== false )
					{
						unset( $cookie_array[ $key ] );
						
						$response['status'] = 2;
						$response['text']   = esc_html__( 'The property is removed from your bookmarked properties.', 'realty-house-pl' );
					}
					else
					{
						$cookie_array[] = $post_id;
						
						$response['status'] = 1;
						$response['text']   = esc_html__( 'The property is bookmarked.', 'realty-house-pl' );
					}
					
					$new_bookmark_str = '';
					foreach ( $cookie_array as $new_bookmark )
					{
						$new_bookmark_str .= ',' . $new_bookmark;
					}
					setcookie( 'pBookmark', trim( $new_bookmark_str, ',' ), time() + ( 86400 * 30 ), '/' );
					
					if ( is_user_logged_in() )
					{
						if ( $old_bookmarked )
						{
							update_user_meta( $user_id, 'bookmarked_properties', trim( $new_bookmark_str, ',' ) );
						}
						else
						{
							add_user_meta( $user_id, 'bookmarked_properties', trim( $new_bookmark_str, ',' ), true );
						}
					}
				}
				else
				{
					setcookie( 'pBookmark', $post_id, time() + ( 86400 * 30 ), '/' );
					$response['status'] = 1;
					$response['text']   = esc_html__( 'The property is bookmarked.', 'realty-house-pl' );
					
					if ( is_user_logged_in() )
					{
						if ( $old_bookmarked )
						{
							update_user_meta( $user_id, 'bookmarked_properties', $post_id );
						}
						else
						{
							add_user_meta( $user_id, 'bookmarked_properties', $post_id, true );
						}
					}
				}
			}
			echo json_encode( $response );
			die();
		}
	}
	
	$realty_house_bookmark_property_obj = new realty_house_bookmark_property();