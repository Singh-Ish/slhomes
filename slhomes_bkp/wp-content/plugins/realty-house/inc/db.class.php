<?php
	
	class realty_house_db_class
	{
		public function __construct()
		{
			add_action( 'init', array ( $this, 'required_tables' ) );
		}
		
		public function required_tables()
		{
			global $wpdb;
			$table_name      = $wpdb->prefix . "currency_rates";
			$charset_collate = $wpdb->get_charset_collate();
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name )
			{
				/**
				 * Create Currency table
				 * @var string table_name
				 */
				$sql = "CREATE TABLE $table_name (
					id int NOT NULL AUTO_INCREMENT,
					currency VARCHAR(5) NOT NULL,
					rate DECIMAL( 15, 6 ) NOT NULL,
					default_currency int NOT NULL DEFAULT '0',
					UNIQUE KEY id (id)
				) $charset_collate;";
				
				
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}
			
			$table_saved_search = $wpdb->prefix . "saved_search";
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_saved_search'" ) != $table_saved_search )
			{
				/**
				 * Create Currency table
				 * @var string table_name
				 */
				$sql1 = "CREATE TABLE $table_saved_search (
					id int NOT NULL AUTO_INCREMENT,
					query text NOT NULL,
					user_id int NOT NULL,
					title text NOT NULL,
					UNIQUE KEY id (id)
				) $charset_collate;";
				
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql1 );
			}
			
			$table_price_offer = $wpdb->prefix . "price_offer";
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_price_offer'" ) != $table_price_offer )
			{
				/**
				 * Create Currency table
				 * @var string table_name
				 */
				$sql2 = "CREATE TABLE $table_price_offer (
					id int NOT NULL AUTO_INCREMENT,
					name text NOT NULL,
					email text NOT NULL,
					phone text NOT NULL,
					offer int NOT NULL,
					description text NOT NULL,
					p_id int NOT NULL,
					date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					UNIQUE KEY id (id)
				) $charset_collate;";
				
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql2 );
			}
		}
	}
	
	$ravis_database_obj = new realty_house_db_class;