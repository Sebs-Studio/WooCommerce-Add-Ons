<?php
function register_cpt_woocommerce_settings() {

    $labels = array( 
        'name' => _x( 'WooCommerce Custom Settings', 'wc_add_on' ),
        'singular_name' => _x( 'WooCommerce Custom Settings', 'wc_add_on' ),
        'menu_name' => _x( 'Custom Settings', 'wc_add_on' ),
        'add_new' => _x( 'Add New', 'wc_add_on' ),
        'add_new_item' => _x( 'Add New WooCommerce Setting', 'wc_add_on' ),
        'edit' => _x( 'Edit', 'wc_add_on' ),
        'edit_item' => _x( 'Edit WooCommerce Setting', 'wc_add_on' ),
        'new_item' => _x( 'New WooCommerce Setting', 'wc_add_on' ),
        'view' => _x( 'View', 'wc_add_on' ),
        'view_item' => _x( 'View WooCommerce Setting', 'wc_add_on' ),
        'search_items' => _x( 'Search WooCommerce Settings', 'wc_add_on' ),
        'not_found' => _x( 'No wc_add_on settings found', 'wc_add_on' ),
        'not_found_in_trash' => _x( 'No wc_add_on settings found in Trash', 'wc_add_on' ),
        'parent' => _x( 'Parent WooCommerce Setting', 'wc_add_on' ),
    );

    $args = array( 
        'labels' => $labels,
        'description' => __( 'Add a new setting to use for WooCommerce. This is used for developers to quickly generate code to use straight away.', 'wc_add_on' ),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_menu' => 'woocommerce',
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => false,
        'supports' => array( 'title' ),
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'can_export' => true,
    );

    register_post_type( 'woocommerce_settings', $args );
}

/**
 * Define Columns for the WooCommerce Custom Setting admin page.
 */
function woocommerce_edit_custom_settings_columns($columns){

	$columns = array();

	$columns["cb"] 			= "<input type=\"checkbox\" />";
	$columns["title"] 		= __("Setting", 'wc_add_on');
	$columns["using"] 		= __("Using", 'wc_add_on');
	$columns["description"] 		= __("Description", 'wc_add_on');
	$columns["tip"] 		= __("Tip", 'wc_add_on');
	$columns["id"]	= __("ID", 'wc_add_on');
	$columns["class"]	= __("Class", 'wc_add_on');
	$columns["css"] = __("CSS", 'wc_add_on');
	$columns["type"] = __("Type", 'wc_add_on');
	$columns["default"] = __("Default", 'wc_add_on');

	return $columns;
}

add_filter('manage_edit-woocommerce_settings_columns', 'woocommerce_edit_custom_settings_columns');


/**
 * Values for Columns on the Coupons admin page.
 *
 * @access public
 * @param mixed $column
 * @return void
 */
function woocommerce_custom_settings_columns($column) {
	global $post, $woocommerce;

	$wc_add_on_using = get_post_meta($post->ID, 'wc_add_ons_use_setting', true);
	$wc_add_on_description = get_post_meta($post->ID, 'wc_add_ons_desc', true);
	$wc_add_on_tip = get_post_meta($post->ID, 'wc_add_ons_desc_tip', true);
	$wc_add_on_id = get_post_meta($post->ID, 'wc_add_ons_ID', true);
	$wc_add_on_class = get_post_meta($post->ID, 'wc_add_ons_class', true);
	$wc_add_on_css = get_post_meta($post->ID, 'wc_add_ons_css', true);
	$wc_add_on_type = get_post_meta($post->ID, 'wc_add_ons_type', true);
	$wc_add_on_default = get_post_meta($post->ID, 'wc_add_ons_std', true);

	switch ($column) {
		case "using" :
			if($wc_add_on_using == true){ echo __('Yes', 'wc_add_on'); }
			else{ echo __('No', 'wc_add_on'); }
		break;
		case "description" :
			echo $wc_add_on_description;
		break;
		case "tip" :
			if($wc_add_on_tip == 'true'){ echo __('Yes', 'wc_add_on'); }
			else{ echo __('No', 'wc_add_on'); }
		break;
		case "id" :
			echo $wc_add_on_id;
		break;
		case "class" :
			echo $wc_add_on_class;
		break;
		case "css" :
			echo $wc_add_on_css;
		break;
		case "type" :
			echo $wc_add_on_type;
		break;
		case "default" :
			echo $wc_add_on_default;
	  	break;
	}
}

add_action( 'manage_woocommerce_settings_posts_custom_column', 'woocommerce_custom_settings_columns', 2 );
?>