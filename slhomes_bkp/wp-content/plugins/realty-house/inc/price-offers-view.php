<?php
	global $wpdb;
	$get_info_obj = new realty_house_get_info();
	$table_name   = $wpdb->prefix . 'price_offer';
	$property_id  = ! empty( $_GET['p_id'] ) ? intval( $_GET['p_id'] ) : '';
?>
<div class="wrap">
	<div id="price-offers-page">
		<h1><?php esc_html_e( 'Price Offers List', 'realty-house-pl' ); ?></h1>
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
			<tr>
				<th class="row-number">#</th>
				<th class="property-title"><?php esc_html_e( 'Property Title', 'realty-house-pl' ); ?></th>
				<th class="property-price"><?php esc_html_e( 'Property Price', 'realty-house-pl' ); ?></th>
				<th class="price-offer"><?php esc_html_e( 'Offer', 'realty-house-pl' ); ?></th>
				<th class="offer-date"><?php esc_html_e( 'Date', 'realty-house-pl' ); ?></th>
				<th class="more-details"></th>
				<th class="offer-delete"></th>
			</tr>
			</thead>

			<tbody id="the-list">
			<?php
				if ( ! empty( $property_id ) )
				{
					$price_offer_query = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `p_id`=' . $property_id . ' ORDER BY `date` DESC' );
				}
				else
				{
					$price_offer_query = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `date` DESC' );
					
				}
				
				if ( count( $price_offer_query ) > 0 )
				{
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
						<td class="property-title"><a class="row-title" href="' . admin_url() . 'post.php?post=' . esc_attr( $property_info['id'] ) . '&amp;action=edit" target="_blank">' . esc_html( $property_info['title'] ) . '</a></td>
						<td class="property-price">' . wp_kses_post( $property_info['price']['generated'] ) . '</td>
						<td class="price-offer">' . wp_kses_post( $generated_price ) . '</td>
						<td class="offer-date">' . esc_html( $offer->date ) . '</td>
						<td class="more-details">' . esc_html__( 'More Details', 'realty-house-pl' ) . '<i class="dashicons dashicons-arrow-down-alt2"></i></td>
						<td class="offer-delete" data-id="' . esc_attr( $offer->id ) . '" data-p-id="' . esc_attr( $offer->p_id ) . '"><i class="dashicons dashicons-no"></i></td>
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
			?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			
			jQuery('td.more-details').on('click', function (e) {
				e.preventDefault();
				var _this = jQuery(this);
				_this.toggleClass('active').parent('tr').next().fadeToggle();
			});

			jQuery('.offer-delete').on('click', function (e) {
				e.preventDefault();
				var _this = jQuery(this),
					id    = _this.data('id'),
					pID   = _this.data('p-id');

				jQuery.ajax({
					type:     'POST',
					dataType: 'json',
					url:      '<?php  echo admin_url( 'admin-ajax.php' ) ?>',
					data:     {
						'action':   'realty_house_price_offer_delete',
						'id':       id,
						'pID':      pID,
						'security': '<?php echo wp_create_nonce( 'price_offer_delete_item' ) ?>'
					},
					success:  function (data) {
						if (data.status === true) {
							window.location.reload();
						}
						else {
							alert(data.message);
						}
					}
				});
			});
		})
	</script>
</div>