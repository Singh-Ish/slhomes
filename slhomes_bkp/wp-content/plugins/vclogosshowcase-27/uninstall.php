<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
global $wpdb;
$post_slug = get_option('iml_post_type_slug');
if($post_slug===FALSE) $post_slug = 'clients';
$wpdb->query("
                DELETE a,b,c,d,e FROM {$wpdb->prefix}posts a
                LEFT JOIN {$wpdb->prefix}term_relationships b ON (a.ID=b.object_id)
                LEFT JOIN {$wpdb->prefix}term_taxonomy c ON (c.term_taxonomy_id=b.term_taxonomy_id)
                LEFT JOIN {$wpdb->prefix}terms d ON (c.term_id = d.term_id)
                LEFT JOIN {$wpdb->prefix}postmeta e ON (a.ID=e.post_id)
                WHERE a.post_type='".$post_slug."';
            ");
$arr = array(   'iml_post_type_slug',
				'iml_responsive_settings_small',
				'iml_responsive_settings_medium',
				'iml_responsive_settings_large',
				'iml_custom_css',
			);
foreach($arr as $value){
	delete_option($value);
}
?>