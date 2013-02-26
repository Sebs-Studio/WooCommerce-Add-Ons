<?php
function wc_add_ons_create_meta_boxes($meta_boxes){
	global $post;

	if(isset($_GET['post'])){ $settings_ID = $_GET['post']; }else{ $settings_ID = ''; } // Post ID.

	$prefix = 'wc_add_ons_'; // Prefix for all fields.

	$meta_boxes[] = array(
        'id'         => 'woocommerce_add_ons',
        'title'      => 'WooCommerce Setting',
        'pages'      => array('woocommerce_settings'),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true,
        'fields'     => array(
			// Add Setting to WooCommerce - Add-Ons Settings Page - RADIO Inline
			array(
                'name' => __( 'Use Setting ?', 'wc_add_on' ),
                'desc' => __( 'If enabled, this setting will be added on the WooCommerce settings page under (Add-Ons).', 'wc_add_on' ),
                'id'   => $prefix . 'use_setting',
				'std' => 'false',
                'type' => 'radio_inline',
				'options' => array(
					array( 'name' => 'No', 'value' => 'false', ),
					array( 'name' => 'Yes', 'value' => 'true', ),
				),
            ),
            // Description - TEXT
            array(
                'name' => __( 'Description', 'wc_add_on' ),
                'id'   => $prefix . 'desc',
                'type' => 'text',
            ),
			// Description is Tip - RADIO Inline
			array(
                'name' => __( 'Description Tip', 'wc_add_on' ),
                'desc' => __( 'If enabled, the description above will become a hover tip in your settings.', 'wc_add_on' ),
                'id'   => $prefix . 'desc_tip',
				'std' => 'false',
                'type' => 'radio_inline',
				'options' => array(
					array( 'name' => 'No', 'value' => 'false', ),
					array( 'name' => 'Yes', 'value' => 'true', ),
				),
            ),
			// ID - TEXT
            array(
                'name' => __( 'ID', 'wc_add_on' ),
                'desc' => __( 'Identify the setting by the ID name', 'wc_add_on' ),
                'id'   => $prefix . 'ID',
                'type' => 'text_medium',
            ),
            // Class - TEXT
            array(
                'name' => __( 'Class', 'wc_add_on' ),
                'desc' => __( 'Identify the setting by the CSS class', 'wc_add_on' ),
                'id'   => $prefix . 'class',
                'type' => 'text_medium',
            ),
            // CSS - TEXT
            array(
                'name' => __( 'CSS', 'wc_add_on' ),
                'desc' => __( 'Apply additional styling to your settings field.', 'wc_add_on' ),
                'id'   => $prefix . 'css',
                'type' => 'text_medium',
            ),
            // Setting Type - SELECT
            array(
                'name' => __( 'Type of Setting', 'wc_add_on' ),
                'desc' => __( 'Select the type of output the admin field will generate for you in the WooCommerce Settings.', 'wc_add_on' ),
                'id'   => $prefix . 'type',
                'type' => 'select',
				'options' => array(
					array( 'name' => __( 'Title', 'wc_add_on' ), 'value' => 'title', ),
					array( 'name' => __( 'Section End', 'wc_add_on' ), 'value' => 'sectionend', ),
					array( 'name' => __( 'Text', 'wc_add_on' ), 'value' => 'text', ),
					array( 'name' => __( 'Colour', 'wc_add_on' ), 'value' => 'color', ),
					array( 'name' => __( 'Image Size', 'wc_add_on' ), 'value' => 'image_width', ),
					array( 'name' => __( 'Select', 'wc_add_on' ), 'value' => 'select', ),
					//array( 'name' => __( 'Radio', 'wc_add_on' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'wc_add_on' ), 'value' => 'checkbox', ),
					array( 'name' => __( 'Textarea', 'wc_add_on' ), 'value' => 'textarea', ),
					array( 'name' => __( 'Single Select Page', 'wc_add_on' ), 'value' => 'single_select_page', ),
					array( 'name' => __( 'Single Select Country', 'wc_add_on' ), 'value' => 'single_select_country', ),
					array( 'name' => __( 'Multi Select Countries', 'wc_add_on' ), 'value' => 'multi_select_countries', ),
				),
            ),
            // Default - TEXT
            array(
                'name' => __( 'Default Value', 'wc_add_on' ),
                'desc' => __( 'Enter the default value of the setting selected.', 'wc_add_on' ),
                'id'   => $prefix . 'std',
                'type' => 'text_medium',
            ),
            // How many Options - SELECT
			array(
                'name' => __( 'How many options ?', 'wc_add_on' ),
                'desc' => __( 'Select the amount of options you want to apply to this field setting.', 'wc_add_on' ),
                'id'   => $prefix . 'options_num',
				'std' => '2',
                'type' => 'select',
				'options' => array(
					array( 'name' => '2', 'value' => '2', ),
					array( 'name' => '3', 'value' => '3', ),
					array( 'name' => '4', 'value' => '4', ),
					array( 'name' => '5', 'value' => '5', ),
					array( 'name' => '6', 'value' => '6', ),
					array( 'name' => '7', 'value' => '7', ),
					array( 'name' => '8', 'value' => '8', ),
					array( 'name' => '9', 'value' => '9', ),
					array( 'name' => '10', 'value' => '10', ),
					array( 'name' => '11', 'value' => '11', ),
					array( 'name' => '12', 'value' => '12', ),
					array( 'name' => '13', 'value' => '13', ),
					array( 'name' => '14', 'value' => '14', ),
				),
				'type_selected' => 'select',
            ),
			// Options (Hidden) - MULTI TEXT
            array(
                'name' => __( 'Options', 'wc_add_on' ),
                'desc' => __( 'Enter the display name and the value for the option.', 'wc_add_on' ),
                'id'   => $prefix . 'option',
                'type' => 'text_multi',
				'type_selected' => 'select',
			),
		)
    );

	$generated_code = "woocommerce_admin_fields( array(
			array(  
				'name' 		=> 'TITLE',
				'desc' 		=> 'DESCRIPTION',
				'desc_tip' 	=> 'TIP',
				'id' 		=> 'ID',
				'class'		=> 'CLASS',
				'css' 		=> 'CSS',
				'type' 		=> 'TYPE',
				'std' 		=> 'STD',
			),
		)
	);";

	$options_num = get_post_meta( $settings_ID, 'wc_add_ons_options_num', true );
	if(!empty($options_num)){
		$generated_code_options_start = "
				'options' => array(";
		$generated_code_options_end = "
				),";

		$wc_add_ons_setting_options = $generated_code_options_start.''.$generated_code_options_end;
	}

	if( isset($settings_ID) && !empty($settings_ID) ) {
		$meta = get_post_meta( $settings_ID );
		foreach($meta as $key => $value) {
			$data[$key] = get_post_meta( $settings_ID, $key, true );
		}
		if(!empty($data['wc_add_ons_desc'])){ $generated_code = str_replace('DESCRIPTION', $data['wc_add_ons_desc'], $generated_code); }
		if( !empty($data['wc_add_ons_desc_tip']) && $data['wc_add_ons_desc_tip'] == 'true' ){ $generated_code = str_replace('TIP', $data['wc_add_ons_desc_tip'], $generated_code); }else{ $generated_code = str_replace("
				'desc_tip' 	=> 'TIP',", '', $generated_code); }
		if(!empty($data['wc_add_ons_ID'])){ $generated_code = str_replace('ID', $data['wc_add_ons_ID'], $generated_code); }
		if(!empty($data['wc_add_ons_class'])){ $generated_code = str_replace('CLASS', $data['wc_add_ons_class'], $generated_code); }
		if(!empty($data['wc_add_ons_css'])){ $generated_code = str_replace('CSS', $data['wc_add_ons_css'], $generated_code); }
		if(!empty($data['wc_add_ons_type'])){
			if($data['wc_add_ons_type'] == 'select' || $data['wc_add_ons_type'] == 'radio'){ $generated_code = str_replace("'type' 		=> 'TYPE',", "'type' 		=> 'TYPE',".$wc_add_ons_setting_options, $generated_code); }
			$generated_code = str_replace('TYPE', $data['wc_add_ons_type'], $generated_code);
		}
		if(!empty($data['wc_add_ons_std'])){ $generated_code = str_replace('STD', $data['wc_add_ons_std'], $generated_code); }

	}

	$generated_code = str_replace('TITLE', get_the_title( $settings_ID ), $generated_code);

	$generated_code_options = "'options' => array(
					array( 'name' => 'value' ),
				),";

	$meta_boxes[] = array(
        'id'         => 'woocommerce_add_ons_generated_code',
        'title'      => 'Generated Code',
        'pages'      => array('woocommerce_settings'),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => false,
        'fields'     => array(
            // Generated Code - TEXTAREA
            array(
                'name' => __( 'Generated Code', 'wc_add_on' ),
                'id'   => $prefix . 'code',
                'type' => 'nothing',
				'desc' => $generated_code,
            ),
		)
	);

	return $meta_boxes;
}
add_filter('cmb_meta_boxes', 'wc_add_ons_create_meta_boxes');

// Multi Text
add_action( 'cmb_render_text_multi', 'cmb_render_text_multi', 10, 2 );
function cmb_render_text_multi( $field, $meta ){
	global $post;

	if(isset($_GET['post'])){ $settings_ID = $_GET['post']; }else{ $settings_ID = ''; } // Post ID.

	$option_num = get_post_meta( $settings_ID, 'wc_add_ons_options_num', true );

	for ($i=0; $i<count($option_num); $i++){
		echo __('Name', 'wc_add_on').': <input type="text" name="', $field['id'], '_name['.$i.']" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" style="width:28%" /> '.__('Value', 'wc_add_ons').': <input type="text" name="', $field['id'], '_value['.$i.']" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" style="width:28%" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
	}
}

// Nothing
add_action( 'cmb_render_nothing', 'cmb_render_nothing', 10, 2 );
function cmb_render_nothing( $field, $meta ){
	echo '<pre>'.$field['desc'].'</pre>';
}

?>