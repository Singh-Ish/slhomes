<?php
	add_action( 'admin_head-nav-menus.php', 'realty_house_tm_add_metabox_menu_posttype_archive' );

	function realty_house_tm_add_metabox_menu_posttype_archive()
	{
		add_meta_box(
			'wpclean-metabox-nav-menu-posttype',
			esc_html__('Realty House Post Archives', 'realty-house'), 'realty_house_tm_metabox_menu_posttype_archive',
			'nav-menus',
			'side',
			'default'
		);
	}

	function realty_house_tm_metabox_menu_posttype_archive()
	{
		$post_types = get_post_types(
			array(
				'show_in_nav_menus' => true,
				'has_archive'       => true
			),
			'object'
		);

		if ($post_types) {
			$items      = array();
			$loop_index = 999999;

			foreach ($post_types as $post_type) {
				$item = new stdClass();
				$loop_index++;

				$item->object_id        = $loop_index;
				$item->db_id            = 0;
				$item->object           = 'post_type_' . $post_type->query_var;
				$item->menu_item_parent = 0;
				$item->type             = 'custom';
				$item->title            = esc_html($post_type->labels->name);
				$item->url              = get_post_type_archive_link($post_type->query_var);
				$item->target           = '';
				$item->attr_title       = '';
				$item->classes          = array();
				$item->xfn              = '';

				$items[] = $item;
			}

			$walker = new Walker_Nav_Menu_Checklist(array());

			echo '
	    <div id="posttype-archive" class="posttypediv">
	    	<div id="tabs-panel-posttype-archive" class="tabs-panel tabs-panel-active">
	    		<ul id="posttype-archive-checklist" class="categorychecklist form-no-clear">';
			echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $items), 0, (object)array('walker' => $walker));
			echo '
	    		</ul>
	    	</div>
	    </div>

	    <p class="button-controls">
		    <span class="add-to-menu">
		    	<input type="submit"' . disabled(1, 0) . ' class="button-secondary submit-add-to-menu right" value="' . esc_attr(esc_html__('Add to Menu', 'realty-house')) . '" name="add-posttype-archive-menu-item" id="submit-posttype-archive" />
		    	<span class="spinner"></span>
		    </span>
	    </p>';
		}
	}