<?php
	/**
	 *  user-panel.php
	 *  User Panel Section
	 *  Template Name: User Panel
	 */
	global $post, $realty_house_opt, $wpdb;
	get_header();
	$get_page_obj      = new realty_house_get_pages();
	$current_user_info = wp_get_current_user();
	$current_user_id   = $current_user_info->ID;
	$view              = ! empty( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : '';
?>
	<section class="page-content container">
		<?php
			switch ( $view )
			{
				case( 'saved-search' ):
					$search_page_info  = $get_page_obj->page_template( '../templates/search-property.php' );
					$save_search_table = $wpdb->prefix . 'saved_search';
					$user_saved_search = $wpdb->get_results( "
							SELECT *
							FROM $save_search_table
							WHERE user_id = $current_user_id" );
					
					echo '<div class="saved-search-container">';
					if ( ! empty( $user_saved_search ) )
					{
						?>
						<h3 class="rh-title"><span><?php esc_html_e( 'Saved Search List', 'realty-house-pl' ) ?></span>
						</h3>
						<table>
							<tr>
								<th>#</th>
								<th><?php esc_html_e( 'Title', 'realty-house-pl' ) ?></th>
								<th><?php esc_html_e( 'Delete', 'realty-house-pl' ) ?></th>
							</tr>
							<?php
								$saved_i = 1;
								foreach ( $user_saved_search as $saved_item )
								{
									echo '
										<tr>
											<td>' . esc_html( $saved_i ) . '</td>
											<td class="title"><a href="' . esc_url( $search_page_info['url'] . $saved_item->query ) . '" target="_blank">' . esc_html( $saved_item->title ) . '</a></td>
											<td><a href="#" class="remove-saved-search" data-id="' . esc_attr( $saved_item->id ) . '" data-title="' . esc_attr( $saved_item->title ) . '"><i class="fa fa-remove"></i></a></td>
										</tr>
									';
									$saved_i ++;
								}
							?>
						</table>
						<script type="text/javascript">
							jQuery(document).ready(function () {


							});
						</script>
					<?php
						}
						else
						{
					?>
						<div id="no-property-result">
							<h2><span><?php esc_html_e( 'Nothing Found :(', 'realty-house-pl' ) ?></span></h2>
							<div class="subtitle"><?php esc_html_e( 'There is not any saved search item here.', 'realty-house-pl' ) ?></div>
						</div>
						<?php
					}
					echo '</div>';
					$user_panel_saved_search_js = '
					jQuery(\'.remove-saved-search\').on(\'click\', function (e) {
						e.preventDefault();
						var _this     = jQuery(this),
							itemID    = _this.data(\'id\'),
							itemTitle = _this.data(\'title\');

						jQuery.ajax({
							type:     \'POST\',
							dataType: \'json\',
							url:      \'' . admin_url( 'admin-ajax.php' ) . '\',
							data:     {
								\'action\':   \'realty_house_save_search_delete\',
								\'id\':       itemID,
								\'title\':    itemTitle,
								\'userID\':   ' . esc_js( $current_user_id ) . ',
								\'security\': \' ' . wp_create_nonce( 'saved_search_delete_item' ) . '\'
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
					})';
					wp_add_inline_script( 'realty-house-plugin-front-js', $user_panel_saved_search_js );
				
				break;
				default:
				break;
			}
		
		?>

	</section>
<?php
	
	get_footer();