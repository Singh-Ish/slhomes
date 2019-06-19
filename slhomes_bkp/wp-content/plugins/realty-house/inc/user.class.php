<?php
	
	class realty_house_update_user
	{
		public $function_running = null;
		
		public function __construct()
		{
			add_action( 'init', array( $this, 'init' ) );
		}
		
		public function init()
		{
			add_action( 'publish_agent', array( $this, 'create_new_user' ), 10, 2 );
			add_action( 'wp_trash_post', array( $this, 'disable_user' ) );
			add_action( 'before_delete_post', array( $this, 'delete_user_item' ), 10, 1 );
			add_action( 'delete_user', array( $this, 'delete_agent' ), 10, 1 );
			add_action( 'save_post', array( $this, 'update_user_info' ), 10, 1 );
			add_action( 'save_post', array( $this, 'update_user_info' ), 10, 1 );
			add_action( 'profile_update', array( $this, 'update_agent_info' ), 10, 1 );
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Create new User by publishing the agent posts
		 * ------------------------------------------------------------------------------------------
		 */
		public function create_new_user( $ID, $post )
		{
			$staff_username = get_post_meta( $ID, 'staff_username', true );
			$staff_email    = get_post_meta( $ID, 'staff_email', true );
			
			$user_username = ! empty( $staff_username ) ? get_post_meta( $ID, 'staff_username', true ) : '';
			$user_email    = ! empty( $staff_email ) ? get_post_meta( $ID, 'staff_email', true ) : '';
			
			$user_id = register_new_user( $user_username, $user_email );
			
			if ( ! is_wp_error( $user_id ) )
			{
				$u = new WP_User( $user_id );
				$u->set_role( 'realty_agent' );
			}
			//			else
			//			{
			//				$error_txt = '';
			//
			//				foreach ( $user_id->errors as $error_item )
			//				{
			//					if ( empty( $error_txt ) )
			//					{
			//						$error_txt .= $error_item[0];
			//					};
			//					continue;
			//				}
			//				$response['status']  = false;
			//				$response['errorNo'] = 1;
			//				$response['text']    = $error_txt;
			//			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Disable the user to submit property and update their Agent Profile
		 *  By changing it's role
		 * ------------------------------------------------------------------------------------------
		 */
		
		public function disable_user( $ID )
		{
			if ( get_post_type( $ID ) == 'agent' )
			{
				$staff_username_val = get_post_meta( $ID, 'staff_username', true );
				$user_username      = ! empty( $staff_username_val ) ? get_post_meta( $ID, 'staff_username', true ) : '';
				$user_info          = get_user_by( 'login', $user_username );
				
				$u = new WP_User( $user_info->data->ID );
				$u->set_role( 'subscriber' );
			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Delete User Permanently
		 * ------------------------------------------------------------------------------------------
		 */
		
		public function delete_user_item( $ID )
		{
			if ( get_post_type( $ID ) == 'agent' && $this->function_running !== 'post' )
			{
				$this->function_running = 'agent';
				$staff_username_val     = get_post_meta( $ID, 'staff_username', true );
				$user_username          = ! empty( $staff_username_val ) ? get_post_meta( $ID, 'staff_username', true ) : '';
				
				$user_info = get_user_by( 'login', $user_username );
				if ( ! empty( $user_info ) )
				{
					wp_delete_user( $user_info->data->ID );
				}
				
				$this->function_running = null;
				
			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Remove agent post Permanently when user is removed Permanently
		 * ------------------------------------------------------------------------------------------
		 */
		public function delete_agent( $ID )
		{
			
			if ( $this->function_running !== 'agent' )
			{
				$this->function_running = 'post';
				
				$user_obj        = get_userdata( $ID );
				$agent_post_args = array(
					'post_type'   => 'agent',
					'post_status' => array(
						'publish',
						'pending',
						'draft',
						'auto-draft',
						'future',
						'private',
						'inherit',
						'trash'
					),
					'meta_query'  => array(
						array(
							'key'   => 'staff_username',
							'value' => $user_obj->data->user_login
						)
					)
				);
				
				$agent_posts = new WP_Query( $agent_post_args );
				
				if ( ! empty( $agent_posts->post_count ) )
				{
					foreach ( $agent_posts->posts as $post_item )
					{
						wp_delete_post( $post_item->ID );
					}
				}
				
				$this->function_running = null;
			}
		}
		
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Update User Information
		 * ------------------------------------------------------------------------------------------
		 */
		public function update_user_info( $ID )
		{
			global $post;
			
			if ( (!empty($post) && $post->post_type === 'agent') && $this->function_running !== 'post' )
			{
				$this->function_running = 'agent';
				
				$user_name          = $post->post_title;
				$user_bio           = $post->post_content;
				$staff_email_val    = get_post_meta( $ID, 'staff_email', true );
				$staff_username_val = get_post_meta( $ID, 'staff_username', true );
				$user_email         = ! empty( $staff_email_val ) ? get_post_meta( $ID, 'staff_email', true ) : '';
				$user_username      = ! empty( $staff_username_val ) ? get_post_meta( $ID, 'staff_username', true ) : '';
				$user_info          = get_user_by( 'login', $user_username );
				
				$user_data_array = array(
					'ID'         => $user_info->data->ID,
					'first_name' => $user_name,
					'user_email' => $user_email
				);
				
				update_user_meta( $user_info->data->ID, 'description', $user_bio );
				wp_update_user( $user_data_array );
				
				$this->function_running = null;
			}
		}
		
		/**
		 * ------------------------------------------------------------------------------------------
		 *  Update Agent Information
		 * ------------------------------------------------------------------------------------------
		 */
		public function update_agent_info( $ID )
		{
			
			if ( $this->function_running !== 'agent' )
			{
				$this->function_running = 'post';
				
				$user_obj  = get_userdata( $ID );
				$user_meta = get_user_meta( $ID );
				
				$user_name        = $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0];
				$user_description = $user_meta['description'][0];
				$user_email       = $user_obj->data->user_email;
				$user_username    = $user_obj->data->user_login;
				
				$agent_post_args = array(
					'post_type'   => 'agent',
					'post_status' => array(
						'publish',
						'pending',
						'draft',
						'auto-draft',
						'future',
						'private',
						'inherit',
						'trash'
					),
					'meta_query'  => array(
						array(
							'key'   => 'staff_username',
							'value' => $user_username
						)
					)
				);
				
				$agent_posts = new WP_Query( $agent_post_args );
				
				if ( ! empty( $agent_posts->post_count ) )
				{
					foreach ( $agent_posts->posts as $post_item )
					{
						
						update_post_meta( $post_item->ID, 'staff_email', $user_email );
						$new_post_info = array(
							'ID'           => $post_item->ID,
							'post_title'   => $user_name,
							'post_content' => $user_description
						);
						
						wp_update_post( $new_post_info );
					}
				}
				
				$this->function_running = null;
			}
		}
		
		
		
	}
	
	$realty_house_user_class_obj = new realty_house_update_user();