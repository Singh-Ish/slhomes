<?php
	
	if ( ! class_exists( 'Redux' ) )
	{
		return;
	}
	
	$opt_name = "realty_house_opt";
	
	
	$theme = wp_get_theme();
	
	load_theme_textdomain( 'realty-house', get_template_directory() . '/languages' );
	
	$args = array (
		'opt_name'             => $opt_name,
		'display_name'         => esc_html__( 'Realty House Theme Options', 'realty-house' ),
		'display_version'      => $theme->get( 'Version' ),
		'menu_type'            => 'menu',
		'allow_sub_menu'       => true,
		'menu_title'           => esc_html__( 'Theme Options', 'realty-house' ),
		'page_title'           => esc_html__( 'Theme Options', 'realty-house' ),
		'google_api_key'       => '',
		'google_update_weekly' => false,
		'async_typography'     => true,
		'admin_bar'            => true,
		'admin_bar_icon'       => 'dashicons-portfolio',
		'admin_bar_priority'   => 50,
		'global_variable'      => '',
		'dev_mode'             => false,
		'customizer'           => false,
		'page_priority'        => null,
		'page_parent'          => 'themes.php',
		'page_permissions'     => 'manage_options',
		'menu_icon'            => '',
		'last_tab'             => '',
		'page_icon'            => 'icon-themes',
		'page_slug'            => '_options',
		'save_defaults'        => true,
		'default_show'         => false,
		'default_mark'         => '',
		'show_import_export'   => true,
		'transient_time'       => 60 * MINUTE_IN_SECONDS,
		'output'               => true,
		'output_tag'           => true,
		'database'             => '',
		'use_cdn'              => true,
		'compiler'             => true,
		'hints'                => array (
			'icon'          => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color'    => 'lightgray',
			'icon_size'     => 'normal',
			'tip_style'     => array (
				'color'   => 'light',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array (
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array (
				'show' => array (
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array (
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		)
	);
	
	Redux::setArgs( $opt_name, $args );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Main Settings', 'realty-house' ),
		'desc'   => esc_html__( 'Main setting of Puna theme is', 'realty-house' ),
		'icon'   => 'el-icon-home',
		'fields' => array (
			array (
				'id'       => 'logo-image-normal',
				'type'     => 'media',
				'title'    => esc_html__( 'Your Logo', 'realty-house' ),
				'desc'     => esc_html__( 'Please provide ".png" or ".jpg" format of your logo and upload suitable size of it. Leave it blank if you want to use the default format of template\'s logo', 'realty-house' ),
				'subtitle' => esc_html__( 'Upload your website\'s logo. It will be replaced with the default logo of template.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-footer-text',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'Footer Text', 'realty-house' ),
				'subtitle' => esc_html__( 'Putt the text you want to be shown in footer in this field.', 'realty-house' ),
				'default'  => esc_html__( '© 2017 Realty House. All Rights Reserved.', 'realty-house' ),
				'mode'     => 'html',
				'theme'    => 'monokai'
			),
			array (
				'id'       => 'opt-sticky-header',
				'type'     => 'switch',
				'title'    => esc_html__( 'Sticky Header', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the sticky header in all pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-submit-property',
				'type'     => 'switch',
				'title'    => esc_html__( 'Submit Property Button in header', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable/Disable the submit property button in header.', 'realty-house' ),
				'default'  => false,
			),
			array (
				'id'    => 'opt-breadcrumb-bg',
				'type'  => 'media',
				'title' => esc_html__( 'Default Breadcrumb Background', 'realty-house' ),
				'desc'  => esc_html__( 'Please provide ".png" or ".jpg" format as background of breadcrumb in all pages.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-testimonial-bg',
				'type'     => 'media',
				'title'    => esc_html__( 'Testimonial Background Image', 'realty-house' ),
				'desc'     => esc_html__( 'Please provide ".png" or ".jpg" format as background of testimonial section.', 'realty-house' ),
				'subtitle' => esc_html__( 'It will be shown if you use the [realty-house-testimonials] shortcode in your pages/files.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-search-in-pages',
				'type'     => 'switch',
				'title'    => esc_html__( 'Include Pages in search result', 'realty-house' ),
				'subtitle' => esc_html__( 'By enabling or disabling this option you can include/exclude the pages from the search result', 'realty-house' ),
				'default'  => false,
			),
			array (
				'id'       => 'opt-smooth-scroll',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Smooth Scrolling', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable/Disable smooth scrolling on your website.', 'realty-house' ),
				'default'  => false,
			),
			array (
				'id'       => 'opt-map-api',
				'type'     => 'text',
				'title'    => esc_html__( 'Google Map API Key', 'realty-house' ),
				'subtitle' => esc_html__( 'Please add your Google map API key in this field.', 'realty-house' ),
				'desc'     => esc_html__( 'You can create your own API key from https://developers.google.com/maps/documentation/javascript/get-api-key', 'realty-house' ),
			),
			array (
				'id'          => 'realty-house-area-unit',
				'type'        => 'select',
				'title'       => esc_html__( 'Area Unit', 'realty-house' ),
				'subtitle'    => esc_html__( 'Select the area unit for your website.', 'realty-house' ),
				'placeholder' => '',
				'options'     => array (
					'sqft' => esc_html__( 'Square Foot (sqft)', 'realty-house' ),
					'sqm'  => esc_html__( 'Square Meter (sqm)', 'realty-house' ),
					'acre' => esc_html__( 'Acre (acre)', 'realty-house' ),
					'ha'   => esc_html__( 'Hectare (ha)', 'realty-house' ),
					'sqkm' => esc_html__( 'Square Kilometre (sqkm)', 'realty-house' ),
					'sqmi' => esc_html__( 'Square Mile (sqmi)', 'realty-house' ),
					'sqyd' => esc_html__( 'Square Yard (sqyd)', 'realty-house' ),
				),
				'default'     => 'sqft',
				'select2'     => array ( 'allowClear' => false )
			),
			array (
				'id'    => 'realty-house-404-bg',
				'type'  => 'media',
				'title' => esc_html__( 'Image in Not Found Page', 'realty-house' ),
				'desc'  => esc_html__( 'Please provide ".png" or ".jpg" format as an image you want to show in 404 page.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-custom-css',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'CSS Code', 'realty-house' ),
				'subtitle' => esc_html__( 'Paste your CSS code here.', 'realty-house' ),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => esc_html__( 'These codes will be added to all pages before </head>', 'realty-house' ),
			)
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title' => esc_html__( 'Property Setting', 'realty-house' ),
		'desc'  => esc_html__( 'Manage Some Settings of properties and property listings', 'realty-house' ),
		'icon'  => 'el-icon-home-alt',
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Main Setting', 'realty-house' ),
		'desc'       => esc_html__( 'Manage Some Settings of properties fields', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'       => 'realty-house-property-status',
				'type'     => 'multi_text',
				'title'    => esc_html__( 'Property Status', 'realty-house' ),
				'subtitle' => esc_html__( 'Add property Status that you support in your website.', 'realty-house' ),
				'default'  => array (
					1 => esc_html__( 'For Sale', 'realty-house' ),
					2 => esc_html__( 'For Rent', 'realty-house' ),
					3 => esc_html__( 'Vacation Rental', 'realty-house' ),
					4 => esc_html__( 'Sold', 'realty-house' )
				)
			),
			array (
				'id'       => 'realty-house-property-type',
				'type'     => 'multi_text',
				'title'    => esc_html__( 'Property Types', 'realty-house' ),
				'subtitle' => esc_html__( 'Add property types and their icons that you support in your website.', 'realty-house' ),
				'desc'     => esc_html__( 'Separate the property type title and it\'s icon\'s name with "---" like :  Apartment---realty-house-apartment', 'realty-house' ),
				'default'  => array (
					1  => esc_html__( 'Apartment---realty-house-apartment', 'realty-house' ),
					2  => esc_html__( 'House---realty-house-house-6', 'realty-house' ),
					3  => esc_html__( 'Villa---realty-house-villa', 'realty-house' ),
					4  => esc_html__( 'Office---realty-house-tower-1', 'realty-house' ),
					5  => esc_html__( 'Industrial---realty-house-factory-1', 'realty-house' ),
					6  => esc_html__( 'Retail---realty-house-shopping-center', 'realty-house' ),
					7  => esc_html__( 'Sport & Entertainment---realty-house-stadium', 'realty-house' ),
					8  => esc_html__( 'Multifamily---realty-house-home-modern-1', 'realty-house' ),
					9  => esc_html__( 'Land---realty-house-plan', 'realty-house' ),
					10 => esc_html__( 'Hotel & Motel---realty-house-hotel-1', 'realty-house' )
				)
			),
			array (
				'id'       => 'realty-house-property-amenities',
				'type'     => 'multi_text',
				'title'    => esc_html__( 'Property Amenities', 'realty-house' ),
				'subtitle' => esc_html__( 'Add property amenities that you want to show in "submit property" and "search property" section', 'realty-house' ),
				'default'  => array (
					0  => esc_html__( 'Parking', 'realty-house' ),
					1  => esc_html__( 'Wi-Fi', 'realty-house' ),
					2  => esc_html__( 'Dishwasher', 'realty-house' ),
					3  => esc_html__( 'Fan', 'realty-house' ),
					4  => esc_html__( 'Toaster', 'realty-house' ),
					5  => esc_html__( 'Bedding', 'realty-house' ),
					6  => esc_html__( 'Cable TV', 'realty-house' ),
					7  => esc_html__( 'Balcony', 'realty-house' ),
					8  => esc_html__( 'Grill', 'realty-house' ),
					9  => esc_html__( 'Lift', 'realty-house' ),
					10 => esc_html__( 'Oven', 'realty-house' ),
					11 => esc_html__( 'Computer', 'realty-house' ),
					12 => esc_html__( 'Microwave', 'realty-house' ),
					13 => esc_html__( 'Coffee Pot', 'realty-house' ),
					14 => esc_html__( 'Pool', 'realty-house' ),
					15 => esc_html__( 'Internet', 'realty-house' ),
					16 => esc_html__( 'DVD', 'realty-house' ),
					17 => esc_html__( 'Marble Floor', 'realty-house' )
				)
			),
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Archive Setting', 'realty-house' ),
		'desc'       => esc_html__( 'Manage Settings of archive page of properties', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'          => 'realty-house-property-archive-template',
				'type'        => 'select',
				'title'       => esc_html__( 'Property Archive Layout', 'realty-house' ),
				'subtitle'    => esc_html__( 'Select which page template you want to be used for archive page of your properties', 'realty-house' ),
				'placeholder' => '',
				'options'     => array (
					1 => esc_html__( 'Grid View', 'realty-house' ),
					2 => esc_html__( 'Row / List View', 'realty-house' ),
					3 => esc_html__( 'Masonry View', 'realty-house' ),
					4 => esc_html__( 'Map View', 'realty-house' )
				),
				'default'     => 1,
				'select2'     => array ( 'allowClear' => false ),
			),
			array (
				'id'       => 'realty-house-property-archive-map',
				'type'     => 'switch',
				'title'    => esc_html__( 'Map in Breadcrumb', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the map instead of breadcrumb for property archive page.', 'realty-house' ),
				'desc'     => esc_html__( 'In "Map View" this option is not available.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-archive-sidebar',
				'type'     => 'switch',
				'title'    => esc_html__( 'Property Archive Page Sidebar', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the sidebar for property archive page.', 'realty-house' ),
				'desc'     => esc_html__( 'In "Map View" this option is not available.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-img-slider',
				'type'     => 'switch',
				'title'    => esc_html__( 'Property Image Slider', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable image slider for property boxes.', 'realty-house' ),
				'desc'     => esc_html__( 'This option will be available for properties with more than one image.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'            => 'realty-house-property-img-slider-count',
				'type'          => 'slider',
				'title'         => esc_html__( 'Max Images for Property Slider', 'realty-house' ),
				'subtitle'      => esc_html__( 'If you enable image slider for property boxes, you can set how many image can be shown in the slider.', 'realty-house' ),
				"default"       => 5,
				"min"           => 1,
				"step"          => 1,
				"max"           => 20,
				'display_value' => 'label'
			)
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Search Result Page Setting', 'realty-house' ),
		'desc'       => esc_html__( 'Manage Settings of property search result page of your website', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'          => 'realty-house-property-search-template',
				'type'        => 'select',
				'title'       => esc_html__( 'Property Search Result Layout', 'realty-house' ),
				'subtitle'    => esc_html__( 'Select which page template you want to be used for property search search page of your website', 'realty-house' ),
				'placeholder' => '',
				'options'     => array (
					1 => esc_html__( 'Grid View', 'realty-house' ),
					2 => esc_html__( 'Row / List View', 'realty-house' ),
					3 => esc_html__( 'Masonry View', 'realty-house' ),
					4 => esc_html__( 'Map View', 'realty-house' )
				),
				'default'     => 1,
				'select2'     => array ( 'allowClear' => false ),
			),
			array (
				'id'       => 'realty-house-property-search-map',
				'type'     => 'switch',
				'title'    => esc_html__( 'Map in Breadcrumb', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the map instead of breadcrumb for property search result page.', 'realty-house' ),
				'desc'     => esc_html__( 'In "Map View" this option is not available.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-search-sidebar',
				'type'     => 'switch',
				'title'    => esc_html__( 'Property Search Result Page Sidebar', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the sidebar for property search page.', 'realty-house' ),
				'desc'     => esc_html__( 'In "Map View" this option is not available.', 'realty-house' ),
				'default'  => true,
			),
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Detail Pages', 'realty-house' ),
		'desc'       => esc_html__( 'Manage section of property detail pages.', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'       => 'realty-house-property-details-gallery',
				'type'     => 'switch',
				'title'    => esc_html__( 'Image Gallery', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the gallery section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-price-offer',
				'type'     => 'switch',
				'title'    => esc_html__( 'Price Offer Section', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the price offer section for the property page..', 'realty-house' ),
				'default'  => false,
			),
			array (
				'id'       => 'realty-house-property-details-amenities',
				'type'     => 'switch',
				'title'    => esc_html__( 'Amenities', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the amenities section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-facts',
				'type'     => 'switch',
				'title'    => esc_html__( 'Facts', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the facts section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-map',
				'type'     => 'switch',
				'title'    => esc_html__( 'Map', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the map section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-neighborhood',
				'type'     => 'switch',
				'title'    => esc_html__( 'Neighborhood', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the Neighborhood section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-schools',
				'type'     => 'switch',
				'title'    => esc_html__( 'Nearby Schools', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the nearby schools section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-floorplan',
				'type'     => 'switch',
				'title'    => esc_html__( 'Floor Plans', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the floor plans section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-attachment',
				'type'     => 'switch',
				'title'    => esc_html__( 'Attachments', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the attachment section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-details-video',
				'type'     => 'switch',
				'title'    => esc_html__( 'Video', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the video section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-similar-properties',
				'type'     => 'switch',
				'title'    => esc_html__( 'Similar Properties', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the similar properties section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-contact-agent',
				'type'     => 'switch',
				'title'    => esc_html__( 'Contact Agents', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the contact agents section of property detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-contact-agent-form',
				'type'     => 'switch',
				'title'    => esc_html__( 'Contact Agents Form', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the contact agents form of property detail pages.', 'realty-house' ),
				'desc'     => esc_html__( 'If you disable the "Contact Agents" section this section automatically will be removed from the page.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-property-contact-agent-form-content',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'Contact Agents Form Placeholder', 'realty-house' ),
				'subtitle' => esc_html__( 'Putt your text to be replaced with "I\'m interested in ..." text in agent contact form', 'realty-house' ),
				'desc'     => esc_html__( 'Absolutely if you disable agent form, this option does not have any effects on your theme.', 'realty-house' ),
			),
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title' => esc_html__( 'Agent Setting', 'realty-house' ),
		'desc'  => esc_html__( 'Manage some settings of agents', 'realty-house' ),
		'icon'  => 'el-icon-user',
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Detail Pages', 'realty-house' ),
		'desc'       => esc_html__( 'Manage section of agent detail pages.', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'       => 'realty-house-agent-details-map-listing',
				'type'     => 'switch',
				'title'    => esc_html__( 'Map Listing', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the property listing on map in agent detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-agent-details-active-listing',
				'type'     => 'switch',
				'title'    => esc_html__( 'Active Listing', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the active listing section of agent detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-agent-details-sold-listing',
				'type'     => 'switch',
				'title'    => esc_html__( 'Past Sales', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the past sales section of agent detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-agent-details-contact-agent',
				'type'     => 'switch',
				'title'    => esc_html__( 'Contact Agent', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the contact agent section of agent detail pages.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-agent-details-pro-info',
				'type'     => 'switch',
				'title'    => esc_html__( 'Professional Information', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the professional information section of property detail pages.', 'realty-house' ),
				'default'  => true,
			)
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'      => esc_html__( 'Rating Setting', 'realty-house' ),
		'desc'       => esc_html__( 'Manage the setting of rating system of agents', 'realty-house' ),
		'subsection' => true,
		'fields'     => array (
			array (
				'id'       => 'realty-house-agent-rate-status',
				'type'     => 'switch',
				'title'    => esc_html__( 'Rating System', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable / Disable the rating system for agents', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-agent-rate-items',
				'type'     => 'multi_text',
				'title'    => esc_html__( 'Rating Items', 'realty-house' ),
				'subtitle' => esc_html__( 'List all the items that you want to be rated on your website', 'realty-house' ),
				'default'  => array (
					1 => esc_html__( 'Local Knowledge', 'realty-house' ),
					2 => esc_html__( 'Process Expertise', 'realty-house' ),
					3 => esc_html__( 'Responsiveness', 'realty-house' ),
					4 => esc_html__( 'Negotiation Skills', 'realty-house' )
				)
			)
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Currency Setting', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the currency of website.', 'realty-house' ),
		'icon'   => 'el-icon-usd',
		'fields' => array (
			array (
				'id'       => 'realty-house-currency-switcher',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Currency Selector', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable/Disable currency selector in header of website.', 'realty-house' ),
				'default'  => true,
			),
			array (
				'id'       => 'realty-house-currency',
				'type'     => 'currency',
				'title'    => esc_html__( 'Manage Currencies', 'realty-house' ),
				'subtitle' => esc_html__( 'The first currency in the list will be the default currency of your website. Currency rates automatically updates Daily. So if you add new currency, please push the "Update The Currency Rate Now".', 'realty-house' ),
				'default'  => array (
					[
						'title'    => esc_html__( 'USD', 'realty-house' ),
						'symbol'   => esc_html__( '$', 'realty-house' ),
						'position' => 1
					],
					[
						'title'    => esc_html__( 'EUR', 'realty-house' ),
						'symbol'   => esc_html__( '€', 'realty-house' ),
						'position' => 0
					]
				)
			)
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Main Slider', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the slider images which is generated by [realty-house-main-slider] shortcode', 'realty-house' ),
		'icon'   => 'el-icon-photo',
		'fields' => array (
			array (
				'id'       => 'realty-house-main-slider',
				'type'     => 'gallery',
				'title'    => esc_html__( 'Manage The Main Slider', 'realty-house' ),
				'subtitle' => esc_html__( 'Select the images that you want to show in main slider of website', 'realty-house' )
			),
		),
	
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Promo Section', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the promo boxes and their content in this section', 'realty-house' ),
		'icon'   => 'el-icon-star',
		'fields' => array (
			array (
				'id'       => 'realty-house-promo',
				'type'     => 'promo',
				'title'    => esc_html__( 'Manage Promo Boxes', 'realty-house' ),
				'subtitle' => esc_html__( 'Add your promo information like title, description, url and etc. in each slide.', 'realty-house' )
			),
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Social Icons', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the website social links. The empty field will not be shown in frontend.', 'realty-house' ),
		'icon'   => 'el-icon-facebook',
		'fields' => array (
			array (
				'id'       => 'opt-social-twitter',
				'type'     => 'text',
				'title'    => esc_html__( 'Twitter', 'realty-house' ),
				'subtitle' => esc_html__( 'Add twitter google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-facebook',
				'type'     => 'text',
				'title'    => esc_html__( 'Facebook', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the facebook plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-gplus',
				'type'     => 'text',
				'title'    => esc_html__( 'Google Plus', 'realty-house' ),
				'subtitle' => esc_html__( 'Add gplus google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-flickr',
				'type'     => 'text',
				'title'    => esc_html__( 'Flickr', 'realty-house' ),
				'subtitle' => esc_html__( 'Add flickr google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-vimeo',
				'type'     => 'text',
				'title'    => esc_html__( 'Vimeo', 'realty-house' ),
				'subtitle' => esc_html__( 'Add vimeo google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-youtube',
				'type'     => 'text',
				'title'    => esc_html__( 'Youtube', 'realty-house' ),
				'subtitle' => esc_html__( 'Add youtube google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-pinterest',
				'type'     => 'text',
				'title'    => esc_html__( 'Pinterest', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the pinterest plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-tumblr',
				'type'     => 'text',
				'title'    => esc_html__( 'Tumblr', 'realty-house' ),
				'subtitle' => esc_html__( 'Add tumblr google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-dribbble',
				'type'     => 'text',
				'title'    => esc_html__( 'Dribbble', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the dribbble plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-digg',
				'type'     => 'text',
				'title'    => esc_html__( 'Digg', 'realty-house' ),
				'subtitle' => esc_html__( 'Add digg google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-linkedin',
				'type'     => 'text',
				'title'    => esc_html__( 'Linkedin', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the linkedin plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-blogger',
				'type'     => 'text',
				'title'    => esc_html__( 'Blogger', 'realty-house' ),
				'subtitle' => esc_html__( 'Add blogger google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-skype',
				'type'     => 'text',
				'title'    => esc_html__( 'Skype', 'realty-house' ),
				'subtitle' => esc_html__( 'Add skype google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-forrst',
				'type'     => 'text',
				'title'    => esc_html__( 'Forrst', 'realty-house' ),
				'subtitle' => esc_html__( 'Add forrst google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-deviantart',
				'type'     => 'text',
				'title'    => esc_html__( 'Deviantart', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the deviantart plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-yahoo',
				'type'     => 'text',
				'title'    => esc_html__( 'Yahoo', 'realty-house' ),
				'subtitle' => esc_html__( 'Add yahoo google plus link in this field.', 'realty-house' )
			),
			array (
				'id'       => 'opt-social-reddit',
				'type'     => 'text',
				'title'    => esc_html__( 'Reddit', 'realty-house' ),
				'subtitle' => esc_html__( 'Add reddit google plus link in this field.', 'realty-house' )
			),
		),
	
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Contact Information', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the contact information of your company', 'realty-house' ),
		'icon'   => 'el-icon-envelope',
		'fields' => array (
			array (
				'id'       => 'opt-web-site-company-title',
				'type'     => 'text',
				'title'    => esc_html__( 'Company Title', 'realty-house' ),
				'subtitle' => esc_html__( 'Put the title of your company located in this field', 'realty-house' ),
			),
			array (
				'id'       => 'opt-web-site-company-subtitle',
				'type'     => 'text',
				'title'    => esc_html__( 'Company Subtitle', 'realty-house' ),
				'subtitle' => esc_html__( 'Put subtitle about your company located in this field', 'realty-house' ),
			),
			array (
				'id'    => 'opt-web-site-company-img',
				'type'  => 'media',
				'title' => esc_html__( 'Company Image', 'realty-house' ),
				'desc'  => esc_html__( 'Please provide ".jpg" format of your company.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-web-site-city',
				'type'     => 'text',
				'title'    => esc_html__( 'City', 'realty-house' ),
				'subtitle' => esc_html__( 'Put city that your company located in this field', 'realty-house' ),
			),
			array (
				'id'       => 'opt-web-site-address',
				'type'     => 'text',
				'title'    => esc_html__( 'Address', 'realty-house' ),
				'subtitle' => esc_html__( 'Put the address of your company in this field', 'realty-house' ),
			),
			array (
				'id'       => 'opt-web-site-email',
				'type'     => 'text',
				'title'    => esc_html__( 'Email', 'realty-house' ),
				'subtitle' => esc_html__( 'Put the email of your company in this field.', 'realty-house' ),
				'validate' => 'email'
			),
			array (
				'id'       => 'opt-web-site-phone',
				'type'     => 'text',
				'title'    => esc_html__( 'Phone', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the phone number of your company.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-map-lat',
				'type'     => 'text',
				'title'    => esc_html__( 'Location Latitude', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the latitude of your website in this field.', 'realty-house' ),
				'default'  => 40.6700
			),
			array (
				'id'       => 'opt-map-lng',
				'type'     => 'text',
				'title'    => esc_html__( 'Location Longitude', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the latitude of your website in this field.', 'realty-house' ),
				'default'  => - 73.9400
			),
			array (
				'id'            => 'opt-map-zoom',
				'type'          => 'slider',
				'title'         => esc_html__( 'Zoom Level', 'realty-house' ),
				'subtitle'      => esc_html__( 'Set the zoom level of Google map in contact page', 'realty-house' ),
				"default"       => 10,
				"min"           => 1,
				"step"          => 1,
				"max"           => 20,
				'display_value' => 'label'
			)
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Blog Setting', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the setting of Blog page.', 'realty-house' ),
		'icon'   => 'el el-list-alt',
		'fields' => array (
			array (
				'id'       => 'realty-house-blog-type',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Excerpt Or Full Blog Content', 'realty-house' ),
				'subtitle' => esc_html__( 'Show excerpt or Full Blog Content On Blog Pages.', 'realty-house' ),
				'options'  => array (
					'1' => esc_html__( 'Excerpt', 'realty-house' ),
					'2' => esc_html__( 'Full', 'realty-house' )
				),
				'default'  => '1'
			),
			array (
				'id'       => 'opt-excerpt-length',
				'type'     => 'text',
				'title'    => esc_html__( 'Excerpt Length', 'realty-house' ),
				'subtitle' => esc_html__( 'Set how many character do you want to show in excerpts.', 'realty-house' ),
				'default'  => '65',
			),
			array (
				'id'       => 'opt-read-more-text',
				'type'     => 'text',
				'title'    => esc_html__( 'Read More Text', 'realty-house' ),
				'subtitle' => esc_html__( 'Change the "Read More" text of Post archive.', 'realty-house' ),
				'default'  => esc_html__( 'Read More', 'realty-house' )
			),
			array (
				'id'    => 'opt-blog-breadcrumb-bg',
				'type'  => 'media',
				'title' => esc_html__( 'Blog Breadcrumb Background', 'realty-house' ),
				'desc'  => esc_html__( 'Please provide ".png" or ".jpg" format as background of breadcrumb in blog pages.', 'realty-house' ),
			),
			array (
				'id'    => 'opt-blog-details-breadcrumb-bg',
				'type'  => 'media',
				'title' => esc_html__( 'Blog Detail Page Breadcrumb Background', 'realty-house' ),
				'desc'  => esc_html__( 'Please provide ".png" or ".jpg" format as background of breadcrumb in blog details pages.', 'realty-house' ),
			),
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Gallery', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the images which are shown in the Gallery page and the Gallery shortcode', 'realty-house' ),
		'icon'   => 'el-icon-picture',
		'fields' => array (
			array (
				'id'       => 'realty-house-main-gallery',
				'type'     => 'gallery',
				'title'    => esc_html__( 'Manage The Main Gallery', 'realty-house' ),
				'subtitle' => esc_html__( 'Manage the image you want to show in Gallery Page and Gallery shortcode.', 'realty-house' ),
			)
		)
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Clients', 'realty-house' ),
		'desc'   => esc_html__( 'Manage your clients by uploading their logos and adding their website\'s url in each slides', 'realty-house' ),
		'icon'   => 'el-icon-group',
		'fields' => array (
			array (
				'id'          => 'realty-house-clients',
				'type'        => 'slides',
				'title'       => esc_html__( 'Manage Clients', 'realty-house' ),
				'subtitle'    => esc_html__( 'Add your client\'s logo and url in each slides', 'realty-house' ),
				'placeholder' => array (
					'title'       => esc_html__( 'Title', 'realty-house' ),
					'description' => esc_html__( 'Description', 'realty-house' )
				),
			),
		),
	) );
	
	Redux::setSection( $opt_name, array (
		'title'  => esc_html__( 'Twitter Settings', 'realty-house' ),
		'desc'   => esc_html__( 'Manage the information of Twitter for showing your latest tweets.', 'realty-house' ),
		'icon'   => 'el-icon-twitter',
		'fields' => array (
			array (
				'id'    => 'info_critical',
				'type'  => 'info',
				'style' => 'critical',
				'icon'  => 'el-icon-info-sign',
				'title' => esc_html__( 'This section JUST work in online website not Local. In localhost you will faced with a fatal error of SSL certificate.', 'realty-house' ),
			),
			array (
				'id'       => 'opt-twitter-active',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Twitter Tracker in Footer', 'realty-house' ),
				'subtitle' => esc_html__( 'Enable/Disable Twitter tracker in footer', 'realty-house' ),
				'default'  => false,
			),
			array (
				'id'       => 'opt-twitter-username',
				'type'     => 'text',
				'title'    => esc_html__( 'Owner', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the owner ( username ) of Twitter account', 'realty-house' )
			),
			array (
				'id'       => 'opt-twitter-consumer-key',
				'type'     => 'text',
				'title'    => esc_html__( 'Consumer Key (API Key)', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the consumer key of your Twitter account', 'realty-house' )
			),
			array (
				'id'       => 'opt-twitter-consumer-secret',
				'type'     => 'text',
				'title'    => esc_html__( 'Consumer Secret (API Secret)', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the consumer secret of your Twitter account', 'realty-house' )
			),
			array (
				'id'       => 'opt-twitter-access-token',
				'type'     => 'text',
				'title'    => esc_html__( 'Access Token', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the access token of your Twitter account', 'realty-house' )
			),
			array (
				'id'       => 'opt-twitter-access-token-secret',
				'type'     => 'text',
				'title'    => esc_html__( 'Access Token Secret', 'realty-house' ),
				'subtitle' => esc_html__( 'Add the access token secret of your Twitter account', 'realty-house' )
			),
			array (
				'id'            => 'opt-twitter-tweet-count',
				'type'          => 'slider',
				'title'         => __( 'Tweet Counts', 'realty-house' ),
				'subtitle'      => __( 'How many tweets you want to show?', 'realty-house' ),
				"default"       => 5,
				"min"           => 1,
				"step"          => 1,
				"max"           => 20,
				'display_value' => 'label'
			)
		)
	) );