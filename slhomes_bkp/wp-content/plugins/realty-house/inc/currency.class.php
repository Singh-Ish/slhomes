<?php
	
	class realty_house_currency
	{
		function __construct()
		{
			add_action( 'wp_ajax_nopriv_realty_house_update_currency', array ( $this, 'update_currency' ) );
			add_action( 'wp_ajax_realty_house_update_currency', array ( $this, 'update_currency' ) );
			
			add_action( 'wp_ajax_nopriv_realty_house_currency_cookie', array ( $this, 'set_currency_cookie_ajax' ) );
			add_action( 'wp_ajax_realty_house_currency_cookie', array ( $this, 'set_currency_cookie_ajax' ) );
			
			add_action( 'init', array ( $this, 'set_currency_cookie' ) );
		}
		
		public function update_currency()
		{
			global $wpdb, $realty_house_opt;
			
			$currency_count = count( $realty_house_opt['realty-house-currency'] );
			
			if ( $currency_count > 1 )
			{
				$curr_i         = 0;
				$currency_query = '';
				foreach ( $realty_house_opt['realty-house-currency'] as $currency_item )
				{
					if ( $curr_i === 0 )
					{
						$default_currency = $currency_item['title'];
					}
					else
					{
						$currency_query .= '"' . $default_currency . $currency_item['title'] . '",';
					}
					$curr_i ++;
				}
				
				$currency_rate_response = wp_remote_get( 'http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in (' . trim( $currency_query, ',' ) . ')&format=json&diagnostics=true&env=store://datatables.org/alltableswithkeys' );
				$currency_rate_json     = json_decode( $currency_rate_response['body'] );
				
				$table_name = $wpdb->prefix . "currency_rates";
				$curr_j     = 0;
				foreach ( $realty_house_opt['realty-house-currency'] as $currency_item )
				{
					
					$currency_id = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $table_name WHERE currency = %s", $currency_item['title'] ) );
					
					if ( empty( $currency_id ) )
					{
						if ( $curr_j === 0 )
						{
							$wpdb->insert( $table_name, array (
								'currency'         => $currency_item['title'],
								'default_currency' => 1,
								'rate'             => 1
							), array (
								'%s',
								'%d',
								'%d'
							) );
						}
						else
						{
							$wpdb->insert( $table_name, array (
								'currency'         => $currency_item['title'],
								'default_currency' => 0,
								'rate'             => $currency_count > 2 ? $currency_rate_json->query->results->rate[ ( $curr_j - 1 ) ]->Rate : $currency_rate_json->query->results->rate->Rate
							), array (
								'%s',
								'%d',
								'%s'
							) );
						}
					}
					else
					{
						if ( $curr_j === 0 )
						{
							$wpdb->update( $table_name, array (
								'default_currency' => 1,
								'rate'             => 1
							), array ( 'id' => $currency_id->id ), array (
								'%d',
								'%d'
							), array (
								'%d'
							) );
						}
						else
						{
							$wpdb->update( $table_name, array (
								'default_currency' => 0,
								'rate'             => $currency_count > 2 ? $currency_rate_json->query->results->rate[ ( $curr_j - 1 ) ]->Rate : $currency_rate_json->query->results->rate->Rate
							), array ( 'id' => $currency_id->id ), array (
								'%d',
								'%s'
							), array (
								'%d'
							) );
						}
					}
					
					$curr_j ++;
				}
				
				$response['status'] = true;
				$response['text']   = esc_html__( 'All the currencies are update', 'realty-house-pl' );
				
				echo json_encode( $response );
				die();
			}
		}
		
		public function set_currency_cookie()
		{
			global $realty_house_opt;
			
			if ( ! empty( $realty_house_opt['realty-house-currency'] ) )
			{
				$current_currency  = ! empty( $_COOKIE['currencyTitle'] ) ? $_COOKIE['currencyTitle'] : '';
				$currency_is_valid = false;
				
				foreach ( $realty_house_opt['realty-house-currency'] as $currency_item )
				{
					if ( in_array( $current_currency, $currency_item ) )
					{
						$currency_is_valid = true;
					}
				}
				
				if ( $currency_is_valid == false )
				{
					setcookie( 'currencyTitle', $realty_house_opt['realty-house-currency'][0]['title'], time() + ( 86400 * 30 ), '/' );
					setcookie( 'currencySymbol', $realty_house_opt['realty-house-currency'][0]['symbol'], time() + ( 86400 * 30 ), '/' );
					setcookie( 'currencyPosition', $realty_house_opt['realty-house-currency'][0]['position'], time() + ( 86400 * 30 ), '/' );
					setcookie( 'currencyRate', 1, time() + ( 86400 * 30 ), '/' );
				}
			}
		}
		
		public function set_currency_cookie_ajax()
		{
			global $realty_house_opt, $wpdb;
			$currency = is_string( $_POST['currency'] ) ? $_POST['currency'] : '';
			
			if ( ! empty( $currency ) )
			{
				foreach ( $realty_house_opt['realty-house-currency'] as $currency_item )
				{
					if ( $currency_item['title'] == $currency )
					{
						$currency_symbol   = $currency_item['symbol'];
						$currency_position = empty( $currency_item['position'] ) ? 0 : $currency_item['position'];
						$table_name        = $wpdb->prefix . "currency_rates";
						$currency_rate     = $wpdb->get_row( $wpdb->prepare( "SELECT rate FROM $table_name WHERE currency = %s", $currency ) );
						
						setcookie( 'currencyTitle', $currency, time() + ( 86400 * 30 ), '/' );
						setcookie( 'currencySymbol', $currency_symbol, time() + ( 86400 * 30 ), '/' );
						setcookie( 'currencyPosition', $currency_position, time() + ( 86400 * 30 ), '/' );
						setcookie( 'currencyRate', $currency_rate->rate, time() + ( 86400 * 30 ), '/' );
						
						$status = true;
					}
				}
			}
			else
			{
				$status = false;
			}
			
			echo esc_html( $status );
			die();
		}
		
		public function get_current_currency()
		{
			global $realty_house_opt;
			if ( ! empty( $_COOKIE['currencyTitle'] ) && ! empty( $_COOKIE['currencySymbol'] ) && ! empty( $_COOKIE['currencyRate'] ) && ( ! empty( $_COOKIE['currencyPosition'] ) || $_COOKIE['currencyPosition'] === '0' ) )
			{
				$currency['title']    = $_COOKIE['currencyTitle'];
				$currency['symbol']   = $_COOKIE['currencySymbol'];
				$currency['position'] = $_COOKIE['currencyPosition'];
				$currency['rate']     = $_COOKIE['currencyRate'];
			}
			else
			{
				$currency['title']    = $realty_house_opt['realty-house-currency'][0]['title'];
				$currency['symbol']   = $realty_house_opt['realty-house-currency'][0]['symbol'];
				$currency['position'] = $realty_house_opt['realty-house-currency'][0]['position'];
				$currency['rate']     = 1;
			}
			
			return $currency;
		}
	}
	
	$realty_house_currency_obj = new realty_house_currency();