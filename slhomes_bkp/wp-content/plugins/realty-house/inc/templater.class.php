<?php
	
	class PageTemplater
	{
		
		private static $instance;
		protected      $templates;
		
		private function __construct()
		{
			
			$this->templates = array ();
			
			// Add a filter to the attributes metabox to inject template into the cache.
			if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) )
			{ // 4.6 and older
				add_filter( 'page_attributes_dropdown_pages_args', array ( $this, 'register_project_templates' ) );
			}
			else
			{ // Add a filter to the wp 4.7 version attributes metabox
				add_filter( 'theme_page_templates', array ( $this, 'add_new_template' ) );
			}
			
			
			// Add a filter to the save post to inject out template into the page cache
			add_filter( 'wp_insert_post_data', array ( $this, 'register_project_templates' ) );
			
			
			// Add a filter to the template include to determine if the page has our
			// template assigned and return it's path
			add_filter( 'template_include', array ( $this, 'view_project_template' ) );
			
			
			// Add your templates to this array.
			$this->templates = array (
				'../templates/property-listing.php'         => esc_html__( 'Property Listing - Grid', 'realty-house-plg' ),
				'../templates/property-listing-rows.php'    => esc_html__( 'Property Listing - List', 'realty-house-plg' ),
				'../templates/property-listing-masonry.php' => esc_html__( 'Property Listing - Masonry', 'realty-house-plg' ),
				'../templates/property-listing-map.php'     => esc_html__( 'Property Listing - Map View', 'realty-house-plg' ),
				'../templates/submit-property.php'          => esc_html__( 'Submit Property', 'realty-house-plg' ),
				'../templates/be-agent.php'                 => esc_html__( 'Be an Agent', 'realty-house-plg' ),
				'../templates/property-compare.php'         => esc_html__( 'Property Compare', 'realty-house-plg' ),
				'../templates/search-property.php'          => esc_html__( 'Search Property', 'realty-house-plg' ),
				'../templates/property-bookmark.php'        => esc_html__( 'Property Bookmark', 'realty-house-plg' ),
				'../templates/user-panel.php'               => esc_html__( 'User Panel', 'realty-house-plg' ),
			);
		}
		
		public static function get_instance()
		{
			
			if ( null == self::$instance )
			{
				self::$instance = new PageTemplater();
			}
			
			return self::$instance;
			
		}
		
		public function add_new_template( $posts_templates )
		{
			$posts_templates = array_merge( $posts_templates, $this->templates );
			
			return $posts_templates;
		}
		
		
		public function register_project_templates( $atts )
		{
			
			// Create the key used for the themes cache
			$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
			
			// Retrieve the cache list.
			// If it doesn't exist, or it's empty prepare an array
			$templates = wp_get_theme()->get_page_templates();
			if ( empty( $templates ) )
			{
				$templates = array ();
			}
			
			// New cache, therefore remove the old one
			wp_cache_delete( $cache_key, 'themes' );
			
			// Now add our template to the list of templates by merging our templates
			// with the existing templates array from the cache.
			$templates = array_merge( $templates, $this->templates );
			
			// Add the modified cache to allow WordPress to pick it up for listing
			// available templates
			wp_cache_add( $cache_key, $templates, 'themes', 1800 );
			
			return $atts;
		}
		
		public function view_project_template( $template )
		{
			
			// Get global post
			global $post;
			
			// Return template if post is empty
			if ( ! $post )
			{
				return $template;
			}
			
			// Return default template if we don't have a custom one defined
			if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) )
			{
				return $template;
			}
			
			$file = plugin_dir_path( __FILE__ ) . get_post_meta( $post->ID, '_wp_page_template', true );
			
			// Just to be safe, we check if the file exist first
			if ( file_exists( $file ) )
			{
				return $file;
			}
			else
			{
				echo esc_html( $file );
			}
			
			// Return template
			return $template;
		}
	}