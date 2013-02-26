<?php
/*
Plugin Name: WooCommerce Add-Ons
Plugin URI: http://www.sebs-studio.com/wp-plugins/woocommerce-add-ons
Description: Enables the administrator to tweak, add or filter the possibilites in WooCommerce. Also allows developers to create settings for their own WooCommerce plugins with ease.
Version: 1.0.0
Author: Sebastien (Sebs Studio)
Author URI: http://www.sebs-studio.com
Requires at least: 3.3.1
Tested up to: 3.5
*/

// Plugin Name.
define('wc_add_ons_plugin_name', 'WooCommerce Add-Ons');

// Plugin Version.
define('wc_add_ons_plugin_version', '1.0.0');

// Plugin Directory.
define('wc_add_ons_plugin_directory', dirname(plugin_basename(__FILE__)));

// Checks if the WooCommerce plugins is installed and active.
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

	/* Localisation */
	load_plugin_textdomain('wc_add_ons', false, wc_add_ons_plugin_directory.'/languages/');

	// ADMIN
	add_action('init', 'wc_add_ons_classes', 20);
	add_action('init', 'register_cpt_woocommerce_settings', 20);
	add_action('init', 'init_cmb_meta_boxes', 9999);
	//add_action('woocommerce_update_options_pages', 'save_service_agreement');
	add_action('admin_head', 'custom_settings_screen_icon');
	add_action('admin_init', 'change_excerpt_to_summary');
	if(get_option('woocommerce_add_ons_filter_by_sale') == 'yes'){
		add_action('restrict_manage_posts', 'woocommerce_products_by_on_sale');
	}
	add_filter('plugin_action_links', 'wc_add_on_plugin_settings_link', 10, 2);
	add_filter('contextual_help', 'wc_add_on_contextual_help', 10, 3);
	add_filter('parse_query', 'on_sale_filter');
	add_filter('woocommerce_shop_order_search_fields', 'woocommerce_shop_order_search_order_total');

	// FRONT END
	add_filter('woocommerce_product_thumbnails_columns', 'wc_thumb_columns');
	add_action('woocommerce_review_order_after_submit', 'add_service_agreement_checkbox');
	add_action('woocommerce_checkout_process', 'check_service_agreement');
	add_action('woocommerce_single_product_summary', 'woocommerce_template_summary_pdf', 10, 2);
	// Third Party Front End
	if(get_option('woocommerce_add_ons_display_msrp_product_loop') == 'yes'){
		add_action('woocommerce_after_shop_loop_item_title', 'loop_item_msrp_price', 9);
	}

	// Include the plugin classes required.
	function wc_add_ons_classes(){
		include(plugin_basename('admin/admin-add-ons-settings.php'));
		include(plugin_basename('admin/admin-woocommerce-settings-post-type.php'));
		include(plugin_basename('admin/admin-woocommerce-settings-meta-box.php'));
		include(plugin_basename('classes/class-help-and-settings-page.php'));
	}

	/** 
	 * Lets you easily create metaboxes with custom fields.
	 * 
	 * https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	 */
	function init_cmb_meta_boxes(){
		if(!class_exists('cmb_meta_box')){
			require_once(plugin_basename('lib/metabox/init.php'));
		}
	}

	// Adds links on the plugin in the plugin menu.
	function wc_add_on_plugin_settings_link($links, $file){
		if($file == plugin_basename(__FILE__)){
			/* Insert the link at the end*/
        	$links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'admin.php?page=woocommerce_settings&tab=woocommerce_add_ons' ), __( 'Settings', 'wc_add_ons' ) );
        	$links['settings'] .= ' | ';
			$links['settings'] .= sprintf( '<a href="%s" target="_blank"> %s </a>', 'https://github.com/seb86/WooCommerce-Add-On', __( 'GitHub', 'wc_add_ons' ) );
		}
		return $links;
	}

	// Add screen icon
	function custom_settings_screen_icon(){
		$post_type = get_current_screen()->post_type;
 
		if('woocommerce_settings' == $post_type){
		?>
		<style type="text/css">
		#icon-woocommerce {
			background: url('<?php echo plugin_dir_url(''); ?>woocommerce/assets/images/icons/woocommerce-icons.png');
			background-position: -359px -5px !important;
		}
		</style>
		<?php
		}
	}

	// Help Tab
	function wc_add_on_contextual_help($contextual_help, $screen_id, $screen){
		// Only add to certain screen(s). The add_help_tab function for screen was introduced in WordPress 3.3.
		if($screen_id != 'woocommerce_settings' || ! method_exists( $screen, 'add_help_tab'))
			return $contextual_help;

			$screen->add_help_tab( array(
									'id' => 'wptuts-overview-tab',
									'title'   => __( 'Overview', 'wc_add_ons' ),
									'content' => '<p>' . __( 'Some help text here', 'wc_add_ons' ) . '</p>',
			)
		);
		return $contextual_help;
	}

	/////////////////////////////////////////////////////////
	// You may now add your functions below.
	function save_service_agreement(){
		if(isset($_POST['woocommerce_service_agreement_page_id'])){
			update_option('woocommerce_service_agreement_page_id', woocommerce_clean($_POST['woocommerce_service_agreement_page_id']));
		}
		else{
			delete_option('woocommerce_service_agreement_page_id');
		}
	}

	/**
	 * Adds a checkbox field on the checkout 
	 * page asking the customer if they agree 
	 * to the service agreement.
	 * 
	 * @since version 1.0.0
	 */
	function add_service_agreement_checkbox(){
		global $woocommerce;
		if(woocommerce_get_page_id('service_agreement') > 0){ ?>
			<p class="form-row service_agreement">
				<label for="service_agreement" class="checkbox"><?php _e('I agree to the', 'wc_add_ons'); ?> <a href="<?php echo esc_url(get_permalink(woocommerce_get_page_id('service_agreement'))); ?>" target="_blank"><?php _e('service agreement', 'wc_add_ons'); ?></a></label>
				<input type="checkbox" class="input-checkbox" name="service_agreement"<?php if(isset($_POST['service_agreement'])){ echo ' checked="checked"'; } ?> id="service_agreement" />
			</p>
		<?php
		}
	}
	function check_service_agreement(){
		global $woocommerce;

		if(!isset($_POST['woocommerce_checkout_update_totals']) && empty($_POST['service_agreement']) && woocommerce_get_page_id('service_agreement') > 0){
			$woocommerce->add_error(__('You must review and agree to our service agreement.', 'wc_add_ons'));
		}
	}

	/**
	 * Filters the number of thumbnail columns 
	 * on the single product page.
	 * 
	 * @since version 1.0.0
	 */
	function wc_thumb_columns(){
		$no_thumbs = get_option('woocommerce_add_ons_thumb_columns');
		if(!$no_thumbs){
			return '3';
		}
		else{
			return $no_thumbs;
		}
	}

	/**
	 * Change 'Excerpt' to 'Product Summary' and 
	 * add the WYSIWYG editor.
	 * 
	 * @since version 1.0.0
	 */
	function change_excerpt_to_summary(){
		// If enabled we remove the original excerpt and add the WYSIWYG editor in place.
		remove_meta_box('postexcerpt', 'product', 'normal');
		add_meta_box('postexcerpt', __('Product Summary', 'wc_add_ons'), 'product_summary_meta_box', 'product', 'normal');
	}
	function product_summary_meta_box(){
		global $wpdb,$post;

		$product_summary = $wpdb->get_row("SELECT `post_excerpt` FROM ".$wpdb->posts." WHERE `id`='".$post->ID."'");
		$post_excerpt = $product_summary->post_excerpt;

		$settings = array(
						'quicktags' 	=> array('buttons' => 'em,strong,link',),
						'text_area_name'=> 'excerpt',
						'quicktags' 	=> true,
						'tinymce' 		=> true,
						'editor_css'	=> '<style>#wp-excerpt-editor-container .wp-editor-area{height:150px; width:100%;}</style>'
						);
		$id = 'excerpt';
		wp_editor($post_excerpt, $id, $settings);
	}

	/**
	 * Add 'On Sale Filter' to Product list in Admin.
	 * 
	 * @since version 1.0.0
	 */
	function on_sale_filter($query){
		global $pagenow, $typenow, $wp_query;

		if($typenow == 'product' && isset($_GET['onsale_check']) && $_GET['onsale_check']){
			if($_GET['onsale_check'] == 'yes'){
				$query->query_vars['meta_compare']  =  '>';
				$query->query_vars['meta_value']    =  0;
				$query->query_vars['meta_key']      =  '_sale_price';
			}
			if($_GET['onsale_check'] == 'no'){
				$query->query_vars['meta_value']    = '';
				$query->query_vars['meta_key']      =  '_sale_price';
			}
		}
	}
	function woocommerce_products_by_on_sale(){
		global $typenow, $wp_query;

		if($typenow == 'product'){
			$onsale_check_yes = '';
			$onsale_check_no  = '';

			if(isset($_GET['onsale_check']) && $_GET['onsale_check'] == 'yes'){
				$onsale_check_yes = ' selected="selected"';
			}

			if(isset($_GET['onsale_check']) && $_GET['onsale_check'] == 'no'){
				$onsale_check_no = ' selected="selected"';
			}

			$output  = "<select name='onsale_check' id='dropdown_onsale_check'>";
			$output .= '<option value="">'.__('Show all products', 'wc_add_ons').'</option>';
			$output .= '<option value="yes"'.$onsale_check_yes.'>'.__('Show products on sale', 'wc_add_ons').'</option>';
			$output .= '<option value="no"'.$onsale_check_no.'>'.__('Show products not on sale', 'wc_add_ons').'</option>';
			$output .= '</select>';
			echo $output;
		}
	}

	/**
	 * Adds the ability to search orders by order total.
	 * 
	 * @since version 1.0.0
	 */
	function woocommerce_shop_order_search_order_total($search_fields){
		$search_fields[] = '_order_total';
		return $search_fields;
	}

	/**
	 * Add attachments to the product summary.
	 * 
	 * @since version 1.0.0
	 */
	function woocommerce_template_summary_pdf(){
		global $woocommerce, $product, $post;

		$args = array(
							'post_type' => 'attachment', 
							'post_status' => null, 
							'post_parent' => $post->ID, 
							'numberposts' => '999', 
							'post_mime_type' => array(
													'application/pdf', 
													'application/vnd.ms-excel', 
													'application/msword'
												)
						);

		$attachments = get_posts($args);

		if($attachments){
			$download = '<ul class="download">';
			foreach($attachments as $attachment){
				setup_postdata($attachment);
					// Setup the attachment icon.
					$attachment_icon = get_post_mime_type($attachment->ID);
					$attachment_icon = explode('/',$attachment_icon);
					$attachment_icon = $attachment_icon[1];
					$attachment_icon = '<img src="'.wc_add_ons_plugin_directory.'/assets/images/'.$attachment_icon.'.png" alt="'.get_the_title($attachment->ID).'" title="'.get_the_title($attachment->ID).'" />';
					// Make the attachment list item
					$download .= '<li><a href="'.wp_get_attachment_url($attachment->ID).'" target="_blank"><span>'.$attachment_icon.get_the_title($attachment->ID).'<span></a></li>';
			}

			$download .= '</ul><div class="clear"></div>';

			echo $download;
		}
	}

	/**
	 * Display the MSRP price in the product loop.
	 * Requires the MSRP plugin extension.
	 * http://www.woothemes.com/products/msrp-pricing/
	 * 
	 * @since version 1.0.0
	 */
	function loop_item_msrp_price(){
		global $product;

		// MSRP pricing enabled?
		$msrp_enabled = get_option('woocommerce_msrp_status', 'never');

		if($msrp_enabled == 'never') return;

		// is the MSRP price set?
		$msrp = get_post_meta($product->id, '_msrp_price', true);

		if(empty($msrp)) return;

		// MSRP label
		$msrp_label = get_option('woocommerce_msrp_description');

		// display the MSRP price
		echo '<div class="woocommerce_msrp">'.($msrp_label ? esc_html__($msrp_label).': ' : '' ).'<span class="woocommerce_msrp_price">'.woocommerce_price($msrp).'</span></div>';
	}

} // end if WooCommerce is active.
?>