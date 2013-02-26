<?php
if(!class_exists('WC_Add_Ons_Help_Settings')){
	class WC_Add_Ons_Help_Settings{

		public $settings;
		private $tab_name;
		private $hidden_submit;

		/**
		 * Constructor
		 */
		public function __construct(){
			$plugin_prefix = 'wc_add_ons_';
			$plugin_basefile = plugin_basename(__FILE__);
			$plugin_url = plugin_dir_url($plugin_basefile);
			$plugin_path = trailingslashit(dirname(__FILE__));
			$this->tab_name = 'woocommerce_add_ons';
			$this->hidden_submit = $plugin_prefix.'submit';
		}

		/**
		 * Load the class
		 */
		public function load(){
			add_action('admin_init', array($this, 'load_hooks'));
		}

		/**
		 * Load the admin hooks
		 */
		public function load_hooks(){
			// Filters
			add_filter('woocommerce_settings_tabs_array', array(&$this, 'add_settings_tab'));
			// Actions
			add_action('woocommerce_settings_tabs_'.$this->tab_name, array(&$this, 'add_ons_settings'));
			add_action('woocommerce_update_options_'.$this->tab_name, array(&$this, 'save_add_ons_settings'));
			add_action('admin_init', array(&$this, 'load_help'), 20);
		}

		/**
		 * Check if we are on settings page
		 */
		public function is_settings_page(){
			if(isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == $this->tab_name){
				return true;
			}
			else if(isset($_GET['page']) && $_GET['page'] == $this->tab_name){
				return true;
			}
			else{
				return false;
			}
		}

		/**
		 * Load the help system
		 */
		public function load_help(){
			// Get the hookname and load the help tabs
			if($this->is_settings_page()){
				$menu_slug = plugin_basename($_GET['page']);
				$hookname = get_plugin_page_hookname($menu_slug, '');

				add_action('load-'.$hookname, array($this, 'add_help_tabs'));
			}
		}

		/**
		 * Add the help tabs
		 */
		public function add_help_tabs(){
			// Check current admin screen
			$screen = get_current_screen();

			// Don't load help tab system prior WordPress 3.3
			if(!class_exists('WP_Screen') || ! $screen){
				return;
			}

			// Remove all existing tabs
			$screen->remove_help_tabs();

			// Create arrays with help tab titles
			// About the Plugin
			$screen->add_help_tab(array(
				'id' => 'wc_add_ons',
				'title' => __('About the Plugin', 'wc_add_ons'),
				'content' => 
				'<h3>'.wc_add_ons_plugin_name.'</h3>'.
				'<p>'.sprintf(__('Plugin created by <a href="%1$s">Seb\'s Studio</a>.', 'wc_add_ons'), 'http://www.sebs-studio.com').'</p>'.
				'<p>'.__('Plugin Version', 'wc_add_ons').': <b>'.wc_add_ons_plugin_version.'</b></p>'
			));

			// Create help sidebar
			$screen->set_help_sidebar(
				'<p><strong>'.__('More information:', 'wc_add_ons').'</strong></p>'.
				'<p><a href="http://www.sebs-studio.com" target="_blank">'.__('Seb\'s Studio', 'wc_add_ons').'</a></p>'.
				'<p><a href="http://www.sebs-studio.com/wp-plugins/woocommerce-add-ons/" target="_blank">'.__('Plugin Details', 'wc_add_ons').'</a></p>'
			);
		}

		/**
		 * Add a tab to the settings page of WooCommerce.
		 */
		public function add_settings_tab($tabs){
			$tabs[$this->tab_name] = __('Add-Ons', 'wc_add_ons');

			return $tabs;
		}

		/* Display WooCommerce Add Ons Settings */
		public function add_ons_settings(){
			global $post;

			if(!current_user_can('manage_options')){
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			// Get current tab/section
			$current_tab 		= ( empty( $_GET['tab'] ) ) ? 'general' : sanitize_text_field( urldecode( $_GET['tab'] ) );
			$current_section 	= ( empty( $_REQUEST['section'] ) ) ? '' : sanitize_text_field( urldecode( $_REQUEST['section'] ) );

			$current = $current_section ? '' : 'class="current"';

			$links = array( '<a href="' . admin_url('admin.php?page=woocommerce_settings&tab=woocommerce_add_ons') . '" ' . $current . '>' . __( 'Add-Ons', 'wc_add_ons' ) . '</a>' );

			// Load each section.
			$add_on_sections = array(
									'custom' => array(
										'title' => 'Custom Settings',
										'id' => 'custom-settings'
									),
								);

			foreach ( $add_on_sections as $key => $page_section ) {

				$title = empty( $page_section['title'] ) ? ucwords( $page_section['id'] ) : ucwords( $page_section['title'] );

				$current = $page_section['id'] == $current_section ? 'class="current"' : '';

				$links[] = '<a href="' . add_query_arg( 'section', $page_section['id'], admin_url('admin.php?page=woocommerce_settings&tab=woocommerce_add_ons') ) . '" ' . $current . '>' . esc_html( $title ) . '</a>';

			}

			echo '<ul class="subsubsub"><li>' . implode( ' | </li><li>', $links ) . '</li></ul><br class="clear" />';

			if( empty($current) ) woocommerce_admin_fields(woocommerce_add_ons_settings());

			if(!empty($current) ) {

				echo '<p>'.__('Below are the custom settings you have created and set to use.', 'wc_add_ons').'</p>';

				// The Query
				$args = array(
							'post_type' => 'woocommerce_settings',
							'post_status' => 'publish',
							'meta_key' => 'wc_add_ons_use_setting',
							'meta_value' => 'true',
							'order' => 'ASC',
						);
				$query = new WP_Query( $args );

				// The Loop
				while ( $query->have_posts() ) : $query->the_post();
					$setting_title = get_the_title();
					// Settings Information.
					$meta = get_post_meta( $post->ID );
					foreach($meta as $key => $value) {
						$data[$key] = get_post_meta( $post->ID, $key, true );
					}
					if( !empty($data['wc_add_ons_desc']) && $data['wc_add_ons_desc_tip'] == 'false' ){ $description = $data['wc_add_ons_desc']; }else{ $description = ''; }
					if( !empty($data['wc_add_ons_desc_tip']) && $data['wc_add_ons_desc_tip'] == 'true' ){ $desc_tip = $data['wc_add_ons_desc']; }else{ $desc_tip = ''; }
					if(!empty($data['wc_add_ons_ID'])){ $set_ID = $data['wc_add_ons_ID']; }else{ $set_ID = ''; }
					if(!empty($data['wc_add_ons_class'])){ $class = $data['wc_add_ons_class']; }else{ $class = ''; }
					if(!empty($data['wc_add_ons_css'])){ $css = $data['wc_add_ons_css']; }else{ $css = ''; }
					if(!empty($data['wc_add_ons_type'])){
						if($data['wc_add_ons_type'] == 'select' || $data['wc_add_ons_type'] == 'radio'){
							//$generated_code = str_replace("'type' 		=> 'TYPE',", "'type' 		=> 'TYPE',".$wc_add_ons_setting_options, $generated_code);
						}
						$type = $data['wc_add_ons_type'];
					}
					if(!empty($data['wc_add_ons_std'])){ $std = $data['wc_add_ons_std']; }else{ $std = ''; }

					// Load Setting
					$woocommerce_setting = array(  
											'name' 		=> ''.$setting_title.'',
											'desc' 		=> ''.$description.'',
											'desc_tip' 	=> ''.$desc_tip.'',
											'id' 		=> ''.$set_ID.'',
											'class'		=> ''.$class.'',
											'css' 		=> ''.$css.'',
											'type' 		=> ''.$type.'',
											'options'	=> array(),
											'std' 		=> ''.$std.'',
										);
					woocommerce_admin_fields(array($woocommerce_setting));

				endwhile;

				/*if(!empty($woocommerce_setting)){
					foreach($woocommerce_setting as $setting){
			  			woocommerce_admin_fields(array($setting));
					}
				}*/
			}
		}

		/* Save WooCommerce Add Ons Settings */
		public function save_add_ons_settings(){
			woocommerce_update_options(woocommerce_add_ons_settings());
		}

	} // end class
	// Instantiate plugin class and add it to the set of globals.
	$WooCommerce_Add_Ons = new WC_Add_Ons_Help_Settings();
	$WooCommerce_Add_Ons->load();
}
?>