<?php 

function realty_house_child_scripts_enqueue()
{
	wp_register_style( 'realty-house-child-theme-style', get_stylesheet_directory_uri() . '/style.css','1.0.0');
	wp_enqueue_style( 'realty-house-child-theme-style');

	//add your scripts
	//wp_register_script( 'realty-house-child-theme-script', get_stylesheet_directory_uri() . '/js/my-scripts.js', '1.0', true );
	//wp_enqueue_script('realty-house-child-theme-script');

}
add_action("wp_enqueue_scripts", "realty_house_child_scripts_enqueue", 10000);