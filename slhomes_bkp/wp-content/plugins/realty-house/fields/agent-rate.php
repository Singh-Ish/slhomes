<?php
	
	/**
	 * ------------------------------------------------------------------------------------------
	 * Room Rating fields
	 * ------------------------------------------------------------------------------------------
	 */
	class  Realty_house_plg_agent_rate_meta_box
	{
		/**
		 * Array of meta data list for the block dates
		 * @var array
		 */
		public $rooms_rate_meta_fields = array();
		
		function __construct()
		{
			Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
			add_action( 'add_meta_boxes', array( $this, 'add_agent_rate_meta_box' ) );
			add_action( 'init', array( $this, 'init' ) );
		}
		
		function init()
		{
			global $realty_house_opt;
			// Field Array
			$prefix                  = 'agent_rate_';
			$realty_house_rate_items = isset( $realty_house_opt['realty-house-agent-rate-items'] ) ? $realty_house_opt['realty-house-agent-rate-items'] : '';
			if ( isset( $realty_house_rate_items ) && $realty_house_rate_items != '' )
			{
				foreach ( $realty_house_rate_items as $index => $agent_rate_item )
				{
					$this->rooms_rate_meta_fields[] = array(
						'label' => $agent_rate_item,
						'id'    => $prefix . $index,
						'type'  => 'rate'
					);
				}
			}
		}
		
		// Add the Meta Box
		function add_agent_rate_meta_box()
		{
			add_meta_box( 'agent_rate_meta_box', // $id
				esc_html__( 'Agent Rating', 'realty-house-pl' ), // $title
				array( $this, 'show_agent_rate_meta_box' ), // $callback
				'agent', // $page
				'side', // $context
				'low' ); // $priority
		}
		
		
		// Show the Fields in the Post Type section
		function show_agent_rate_meta_box()
		{
			global $post;
			// Use nonce for verification
			
			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ( $this->rooms_rate_meta_fields as $field )
			{
				
				// begin a table row with
				echo '<tr>
    	                <th>' . esc_html( $field['label'] ) . '</th>
    	                <td>';
				switch ( $field['type'] )
				{
					case 'rate':
						$meta_val = get_post_meta( $post->ID, $field['id'], true );
						$meta    = ! empty( $meta_val ) ? get_post_meta( $post->ID, $field['id'], true ) : 0;
						$raw_val = explode( ',', $meta );
						
						$total_val = 0;
						foreach ( $raw_val as $a_val )
						{
							$total_val += intval( $a_val );
						}
						
						$final_val = round( $total_val / count( $raw_val ) );
						
						echo '<div class="rate-result"><div class="star-container"> ';
						for ( $i = 0; $i < 5; $i ++ ):
							echo '<i class="dashicons dashicons-star-filled '.( $i < $final_val ? esc_attr('active') : '' ).'"></i>';
						endfor;
						echo '</div><div class="rate-total-votes">(' . (!empty($raw_val[0]) ? count( $raw_val ) : 0) . ' ' . esc_html__( 'votes', 'realty-house-pl' ) . ')</div></div>';
					break;
					
				} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table>'; // end table
		}
	}
	
	$agent_rate_meta_box = new  Realty_house_plg_agent_rate_meta_box;