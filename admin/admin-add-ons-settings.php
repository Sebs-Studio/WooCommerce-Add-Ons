<?php
/* WooCommerce Add Ons Settings */
function woocommerce_add_ons_settings(){

	$settings = array(

		array(
			'name' => __('Front Settings', 'wc_add_ons'),
			'type' => 'title',
			'id' => 'woocommerce_add_ons_settings',
			'desc' => __('The following controls WooCommerce on the front of your site.', 'wc_add_ons')
		),

		array(
			'name' => __('Remove Product Images', 'wc_add_ons'),
			'desc' => __('This will remove all product images on all products.', 'wc_add_ons'),
			'desc_tip' => false,
			'id' => 'woocommerce_add_ons_hide_product_images',
			'std' => 'no',
			'type' => 'checkbox'
		),

		array(
			'name' => __('No. Product Thumbnails', 'wc_add_ons'),
			'id' => 'woocommerce_add_ons_thumb_columns',
			'css' => 'min-width:50px;',
			'class' => 'chosen_select_nostd',
			'std' => '3',
			'type' => 'select',
			'options' => array(
											'1' => '1',
											'2' => '2',
											'3' => '3',
											'4' => '4',
											'5' => '5',
											'6' => '6',
											'7' => '7',
											'8' => '8',
											'9' => '9',
											'10' => '10',
										),
			'desc' => __('Select the number of thumbnails on the single product page you want displayed.', 'wc_add_ons'),
			'desc_tip' => true
		),

		array(
			'name' 		=> __('Service Agreement Page', 'wc_add_ons'),
			'desc' 		=> __('If you define a \'Service Agreement\' page the customer will be asked if they agree to it when checking out.', 'wc_add_ons'),
			'tip' 		=> '',
			'id' 		=> 'woocommerce_service_agreement_page_id',
			'std' 		=> '',
			'class'		=> 'chosen_select_nostd',
			'css' 		=> 'min-width:300px;',
			'type' 		=> 'single_select_page',
			'desc_tip'	=> true,
		),

		array('type' => 'sectionend', 'id' => 'woocommerce_add_ons_settings'),

		array(
			'name' => __('Third-Party Extensions Add-Ons (Front)', 'wc_add_ons'),
			'type' => 'title',
			'id' => 'woocommerce_add_ons_settings',
			'desc' => __('The following controls WooCommerce extensions developed by other third-party developers on the front of your site.', 'wc_add_ons')
		),

		array(
			'name' => __('Display the MSRP price in the product loop.', 'wc_add_ons'),
			'desc' => __('Requires the MSRP plugin.', 'wc_add_ons').' - http://www.woothemes.com/products/msrp-pricing/',
			'desc_tip' => false,
			'id' => 'woocommerce_add_ons_display_msrp_product_loop',
			'std' => 'no',
			'type' => 'checkbox'
		),

		array('type' => 'sectionend', 'id' => 'woocommerce_add_ons_settings'),

		array(
			'name' => __('Admin Settings', 'wc_add_ons'),
			'type' => 'title',
			'id' => 'woocommerce_add_ons_settings',
			'desc' => __('The following controls WooCommerce in the Control Panel.', 'wc_add_ons')
		),

		array(
			'name' => __('Filter products by sale', 'wc_add_ons'),
			'desc' => __('Enabling this will add a new dropdown menu that will allow you to search your products that are on for sale.', 'wc_add_ons'),
			'desc_tip' => false,
			'id' => 'woocommerce_add_ons_filter_by_sale',
			'std' => 'yes',
			'type' => 'checkbox'
		),

		array('type' => 'sectionend', 'id' => 'woocommerce_add_ons_settings'),
	);

	return $settings;
}
?>