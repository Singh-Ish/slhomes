<?php
	
	//	User Action Class
	
	class Realty_house_user
	{
		public function __construct()
		{
			add_action( 'wp_ajax_nopriv_realty_house_login', array ( $this, 'login_user' ) );
			add_action( 'wp_ajax_realty_house_login', array ( $this, 'login_user' ) );
			
			add_action( 'wp_ajax_nopriv_realty_house_register', array ( $this, 'register_user' ) );
			add_action( 'wp_ajax_realty_house_register', array ( $this, 'register_user' ) );
		}
		
		public function login_user()
		{
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'ajax-login-nonce' ) )
			{
				// Nonce is checked, get the POST data and sign user on
				$info                  = array ();
				$info['user_login']    = sanitize_text_field( $_POST['username'] );
				$info['user_password'] = sanitize_text_field( $_POST['password'] );
				$info['remember']      = true;
				
				$user_signon = wp_signon( $info, false );
				if ( is_wp_error( $user_signon ) )
				{
					$return_value = array (
						'loggedin' => false,
						'message'  => esc_html__( 'Wrong username or password.', 'realty-house' )
					);
				}
				else
				{
					$return_value = array (
						'loggedin' => true,
						'message'  => esc_html__( 'You are logged in.', 'realty-house' )
					);
				}
			}
			else
			{
				$return_value = array (
					'loggedin' => false,
					'message'  => esc_html__( 'Are your cheating?', 'realty-house' )
				);
			}
			
			echo json_encode( $return_value );
			die();
		}
		
		public function register_user()
		{
			if ( ! empty( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'ajax-register-nonce' ) )
			{
				$info                  = array ();
				$info['user_nickname'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = sanitize_user( $_POST['username'] );
				$info['user_email']    = sanitize_email( $_POST['email'] );
				$info['password']      = wp_generate_password();
				$info['role']          = 'subscriber';
				
				// Register the user
				$user_register = wp_insert_user( $info );
				
				if ( is_wp_error( $user_register ) )
				{
					$error = $user_register->get_error_codes();
					
					if ( in_array( 'empty_user_login', $error ) )
					{
						$return_value = array (
							'loggedin' => false,
							'message'  => esc_html__( $user_register->get_error_message( 'empty_user_login' ) )
						);
					}
					elseif ( in_array( 'existing_user_login', $error ) )
					{
						$return_value = array (
							'loggedin' => false,
							'message'  => esc_html__( $user_register->get_error_message( 'existing_user_login' ) )
						);
					}
					elseif ( in_array( 'existing_user_login', $error ) )
					{
						$return_value = array (
							'loggedin' => false,
							'message'  => esc_html__( 'This username is already registered.', 'realty-house' )
						);
					}
					elseif ( in_array( 'existing_user_email', $error ) )
					{
						$return_value = array (
							'loggedin' => false,
							'message'  => esc_html__( 'This email address is already registered.', 'realty-house' )
						);
					}
					
				}
				else
				{
					wp_new_user_notification( $user_register, $info['password'] );
					$return_value = array (
						'loggedin' => true,
						'message'  => esc_html__( 'Thanks for your registration, please check your email.', 'realty-house' )
					);
				}
			}
			else
			{
				$return_value = array (
					'loggedin' => false,
					'message'  => esc_html__( 'Are your cheating?', 'realty-house' )
				);
			}
			
			echo json_encode( $return_value );
			die();
			
		}
	}
	
	$realty_house_user_obj = new Realty_house_user();
	
