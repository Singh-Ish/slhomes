<?php 
/**
 * ------------------------------------------------------------------------------------------
 * Testimonials Meta box file
 * ------------------------------------------------------------------------------------------
 */

class Home_square_plg_testimonials_meta_box
{
    /**
     * Array of meta data list for the events
     * @var array
     */
    public $testimonials_meta_fields = array();

    function __construct()
    {
        Realty_house_plg_main::realty_house_plg_load_plugin_text_domain();
        $prefix = 'testimonials_';
        $this->testimonials_meta_fields = array(
            array(
                'label' => esc_html__( 'Guest Location', 'realty-house-pl' ),
                'desc'  => esc_html__( 'Put the guest\'s Location in this field', 'realty-house-pl' ),
                'id'    => $prefix.'guest_location',
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__( 'Guest Email', 'realty-house-pl' ),
                'desc'  => esc_html__( 'Put the guest\'s email in this field', 'realty-house-pl' ),
                'id'    => $prefix.'guest_email',
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__( 'Guest Phone', 'realty-house-pl' ),
                'desc'  => esc_html__( 'Put the guest\'s phone in this field', 'realty-house-pl' ),
                'id'    => $prefix.'guest_phone',
                'type'  => 'text'
            ),
        );

        add_action('add_meta_boxes', array($this, 'add_testimonials_meta_box'));
        add_action('save_post', array($this, 'save_testimonials_meta'));
    }

    // Add the Meta Box
    function add_testimonials_meta_box() {
        add_meta_box(
            'testimonials_meta_box', // $id
            esc_html__( 'Testimonials Extra information', 'realty-house-pl' ),
            array($this, 'show_testimonials_meta_box'), // $callback
            'testimonials', // $page
            'normal', // $context
            'high'); // $priority
    }

    // Show the Fields in the Post Type section
    function show_testimonials_meta_box() 
    {
        global  $post;

        // Use nonce for verification
        echo '<input type="hidden" name="testimonials_meta_box_nonce" value="'.esc_attr( wp_create_nonce(basename(__FILE__)) ).'" />';
             
            // Begin the field table and loop
            echo '<table class="form-table">';
            foreach ($this->testimonials_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row with
                echo '<tr>
                        <th><label for="'.esc_attr($field['id']).'">'.esc_html($field['label']).'</label></th>
                        <td>';
                        switch($field['type']) {
                            // text
                            case 'text':
                                echo '<input type="text" name="'.esc_attr( $field['id'] ).'" id="'.esc_attr( $field['id'] ).'" value="'.esc_attr( $meta ).'" size="30" />
                                    <br /><span class="description">'.esc_html($field['desc']).'</span>';
                            break;
                        } //end switch
                echo '</td></tr>';
            } // end foreach
            echo '</table>'; // end table
    }

    // Save the Data
    function save_testimonials_meta($post_id)
    {
        $security_code = '';
        
        if(isset($_POST['testimonials_meta_box_nonce']) && $_POST['testimonials_meta_box_nonce'] !='')
        {
            $security_code = sanitize_text_field( $_POST['testimonials_meta_box_nonce'] );
        }

        // verify nonce
        if (!wp_verify_nonce($security_code, basename(__FILE__))) 
            return $post_id;
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // check permissions
        if ('testimonials' == $_POST['post_type'])
        {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
        }
         
        // loop through fields and save the data
        foreach ($this->testimonials_meta_fields as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        } // end foreach
    }

}

$testimonials_meta_box_obj = new Home_square_plg_testimonials_meta_box;