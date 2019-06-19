<?php
	/*
	Register Fonts
	*/

	function realty_house_tm_google_fonts_url()
	{
		$font_url = '';

		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Google font: on or off', 'realty-house' ) )
		{
			$font_url = add_query_arg( 'family', urlencode( 'Lato:400,300,300italic,400italic,700,700italic,900' ), "https://fonts.googleapis.com/css" );
		}

		return $font_url;
	}

	/*
	Enqueue scripts and styles.
	*/
	function realty_house_tm_google_font_scripts()
	{
		wp_enqueue_style( 'google-fonts', realty_house_tm_google_fonts_url(), array(), '1.0.0' );
	}

	add_action( 'wp_enqueue_scripts', 'realty_house_tm_google_font_scripts' );