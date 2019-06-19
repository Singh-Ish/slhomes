<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Property Offer Meta box file
	 * ------------------------------------------------------------------------------------------
	 */
	class Home_square_plg_property_offer_meta_box
	{
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			add_action( 'add_meta_boxes', array ( $this, 'add_property_offer_meta_box' ) );
		}
		
		function add_property_offer_meta_box()
		{
			add_meta_box( 'property_offer_meta_box', esc_html__( 'Property Offer', 'realty-house-pl' ), array (
				$this,
				'show_property_offer_meta_box'
			), 'property', 'normal', 'high' );
		}
		
		// Add the Meta Box
		function show_property_offer_meta_box()
		{
			global $post, $wpdb;
			$get_info_obj = new realty_house_get_info();
			$table_name   = $wpdb->prefix . 'price_offer';
			echo '
				<div class="table-title">' . esc_html__( 'Property Offer', 'realty-house-pl' ) . '</div>
				<div class="table-container property-offer-tbl-container">';
			
			$price_offer_query = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `p_id`=\'' . $post->ID . '\' ORDER BY `date` DESC' );
			
			if ( count( $price_offer_query ) > 0 )
			{
				echo '
				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th class="row-number">#</th>
							<th class="property-title">' . esc_html__( 'Property Title', 'realty-house-pl' ) . '</th>
							<th class="property-price">' . esc_html__( 'Property Price', 'realty-house-pl' ) . '</th>
							<th class="price-offer">' . esc_html__( 'Offer', 'realty-house-pl' ) . '</th>
							<th class="offer-date">' . esc_html__( 'Date', 'realty-house-pl' ) . '</th>
							<th class="more-details"></th>
							<th class="offer-delete"></th>
						</tr>
					</thead>
					<tbody id="the-list">';
				
				$offer_i = 1;
				foreach ( $price_offer_query as $offer )
				{
					$property_info   = $get_info_obj->property_info( $offer->p_id );
					$generated_price = number_format( $offer->offer * $property_info['price']['unit']['rate'] );
					$offer_desc      = stripslashes( $offer->description );
					
					if ( $property_info['price']['unit']['position'] == 0 )
					{
						$generated_price .= $property_info['price']['unit']['symbol'];
					}
					else
					{
						$generated_price = $property_info['price']['unit']['symbol'] . ' ' . $generated_price;
					}
					
					echo '
					<tr>
						<td class="row-number">' . esc_html( $offer_i ) . '</td>
						<td class="property-title"><a class="row-title" href="' . esc_url( $property_info['url'] ) . '" target="_blank">' . esc_html( $property_info['title'] ) . '</a></td>
						<td class="property-price">' . wp_kses_post( $property_info['price']['generated'] ) . '</td>
						<td class="price-offer">' . wp_kses_post( $generated_price ) . '</td>
						<td class="offer-date">' . esc_html( $offer->date ) . '</td>
						<td class="more-details">' . esc_html__( 'More Details', 'realty-house-pl' ) . '<i class="dashicons dashicons-arrow-down-alt2"></i></td>
						<td class="offer-delete" data-id="' . esc_attr( $offer->id ) . '" data-p-id="' . esc_attr( $offer->p_id ) . '" data-security="' . wp_create_nonce( 'price_offer_delete_item' ) . '"><i class="dashicons dashicons-no"></i></td>
					</tr>
					<tr class="more-info-box">
						<td colspan="7">
							<div class="info-box">
								<div class="info-row">
									<div class="title">' . esc_html__( 'Name : ', 'realty-house-pl' ) . '</div><div class="value">' . esc_html( $offer->name ) . '</div>
								</div>
								<div class="info-row">
									<div class="title">' . esc_html__( 'Email : ', 'realty-house-pl' ) . '</div><div class="value">' . esc_html( $offer->email ) . '</div>
								</div>
								<div class="info-row">
									<div class="title">' . esc_html__( 'Phone : ', 'realty-house-pl' ) . '</div><div class="value">' . esc_html( $offer->phone ) . '</div>
								</div>
								<div class="info-row">
									<div class="title">' . esc_html__( 'Description : ', 'realty-house-pl' ) . '</div><div class="value">' . esc_html( $offer_desc ) . '</div>
								</div>
							</div>
						</td>
					</tr>
					
					';
					$offer_i ++;
				}
			}
			else
			{
				echo '<div class="no-offers-box">'.esc_html__( 'There is not any offers for this property.', 'realty-house-pl' ).'</div>';
			}
			
			if ( count( $price_offer_query ) > 0 )
			{
				echo '</tbody></table>';
			}
			echo '</div>';
		}
	}
	
	$property_offer_meta_box_obj = new Home_square_plg_property_offer_meta_box;