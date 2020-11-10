<?php 
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'Fme_Upload_Files_Admin' ) ) { 

	class Fme_Upload_Files_Admin extends Fme_Ext_Upload_Files {
		
		public function __construct() {

			add_action( 'wp_loaded', array($this,'fme_upload_files_custom_posttype' ));	
			add_action( 'init', array( $this, 'fme_upload_files_load_text_domain' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'Fme_upload_files_admin_scripts' ) );

			add_action('wp_ajax_fme_upload_files_save_general_settings', array($this, 'fme_upload_files_save_general_settings'));
			add_action('wp_ajax_nopriv_fme_upload_files_save_general_settings', array($this, 'fme_upload_files_save_general_settings'));

			add_action('wp_ajax_fme_upload_files_delete_rule_file', array($this, 'fme_upload_files_delete_rule'));
			add_action('wp_ajax_nopriv_fme_upload_files_delete_rule_file', array($this, 'fme_upload_files_delete_rule'));	

			add_action('wp_ajax_fme_upload_files_edit_rule_file', array($this, 'fme_upload_files_edit_rule_file'));
			add_action('wp_ajax_nopriv_fme_upload_files_edit_rule_file', array($this, 'fme_upload_files_edit_rule_file'));	

			add_action('wp_ajax_fme_upload_files_update_general_settings', array($this, 'fme_upload_files_update_general_settings'));
			add_action('wp_ajax_nopriv_fme_upload_files_update_general_settings', array($this, 'fme_upload_files_update_general_settings'));	

			add_action('wp_ajax_fme_upload_file_cart_rules', array($this, 'fme_upload_files_cart_rules_files'));
			add_action('wp_ajax_nopriv_fme_upload_file_cart_rules', array($this, 'fme_upload_files_cart_rules_files'));	

			add_action('wp_ajax_fme_upload_file_delete_cart_files', array($this, 'fme_upload_files_delete_cart_file_selected'));
			add_action('wp_ajax_nopriv_fme_upload_file_delete_cart_files', array($this, 'fme_upload_files_delete_cart_file_selected'));	

			add_action('add_meta_boxes', array($this,'fme_upload_files_add_meta_boxes'));

			add_filter( 'manage_edit-shop_order_columns', array($this,'custom_shop_order_column'), 20 );
			add_action( 'manage_shop_order_posts_custom_column' , array($this,'custom_orders_list_column_content'), 20, 2 );
			
			add_filter('woocommerce_settings_tabs_array', array($this,'fma_upload_files_woocommerce_settings_tabs_array'), 50 );//admin		

			add_action( 'woocommerce_settings_fme_upload_files', array($this,'fma_upload_files_settings' )); //admin

		}


		// creating a tab under Woocommerce->settings-> (Order On Whatsapp)
		public function fma_upload_files_woocommerce_settings_tabs_array( $tabs ) {
			$tabs['fme_upload_files'] = __('Upload Files', 'Fme_Upload_Files');
			return $tabs;
		}

		public function fma_upload_files_settings() {

			require_once( FMEUF_PLUGIN_DIR . 'admin/view/upload-files-admin-settings-page.php' );
		}


		public function fme_upload_files_add_meta_boxes() {
			add_meta_box( 'fme_upload_files_meta_fields', __('Upload Files', 'woocommerce'), array($this, 'fme_upload_files_meta_boxes'), 'shop_order', 'normal', 'low', null);
		}

		public function custom_shop_order_column( $fme_columns ) {
			$fme_reordered_columns = array();
			foreach ( $fme_columns as $key => $column) {
				$fme_reordered_columns[$key] = $column;
				if ('order_status'==$key ) {
					$fme_reordered_columns['fme_upload_counter'] = esc_html__( 'Upload Counter', 'Fme_Upload_Files');
				}
			}
			return $fme_reordered_columns;
		}
		
		public function custom_orders_list_column_content( $column, $post_id ) {
			switch ( $column ) {
				case 'fme_upload_counter':
					global $post;
					$fme_upload_files_order_id = $post->ID;
					$fme_uploadfiles_order = new WC_Order( $fme_upload_files_order_id );
					$fme_upload_counter = get_post_meta( $post_id, 'fme_total', true );
					if (!empty($fme_upload_counter)) {
						echo esc_attr($fme_upload_counter);
					} else {
						echo esc_html__('0', 'Fme_Upload_Files');
					}
					break;
			}
		}
		
		public function fme_upload_files_meta_boxes() {
			global $post;
			$fme_upload_files_order_id = $post->ID;
			$fme_uploadfiles_order = new WC_Order( $fme_upload_files_order_id );
			$fme_uploadfiles_get_oder_fee_items = $fme_uploadfiles_order->get_items('fee');
			if (!empty($fme_uploadfiles_get_oder_fee_items)) {
				?>
				<div class="container">          
					<table class="table table-striped">
						<thead>
						  <tr>
							<th style="float:left;"><?php echo esc_html__('File Name:', 'Fme_Upload_Files'); ?></th>
							<th><?php echo esc_html__('File Price:', 'Fme_Upload_Files'); ?></th>
							<th><?php echo esc_html__('Files:', 'Fme_Upload_Files'); ?></th>
						  </tr>
						</thead>
						<tbody>
							<?php 
							foreach ($fme_uploadfiles_get_oder_fee_items as $item_id => $item_fee ) { 
								?>
								<tr>
									<td>
										<?php 
											$fme_upload_files_fee_name = $item_fee->get_name(); 
											echo esc_attr($fme_upload_files_fee_name); 
										?>
									</td>
									<td>
										<?php 
											$fme_upload_files_fee_price = get_woocommerce_currency_symbol() . $item_fee->get_total();
											echo esc_attr($fme_upload_files_fee_price); 
										?>
									</td>
									<td>
										<a href="<?php echo esc_url(FMEUF_URL) . '/uploadsfiles/' . esc_attr(trim(preg_replace('/\s*\([^)]*\)/', '', $fme_upload_files_fee_name))); ?>" class="fme_thankyou_page1" target="_blank"><?php echo esc_html__('Preview' , 'Fme_Upload_Files'); ?></a>
									</td>
								</tr>
					  <?php } ?>
						</tbody>
					</table>
				</div>
				<?php
			}
		   
		}
		
		public function fme_upload_files_cart_rules_files() {

			if (isset($_REQUEST['fme_upload_files_count']) || isset($_REQUEST['fme_upload_files_url']) || isset($_REQUEST['fme_upload_files_id_name'])) {
				$fme_upload_files_buttonID= filter_var($_REQUEST['fme_upload_files_count']);
				$fme_upload_files_id_name = filter_var($_REQUEST['fme_upload_files_id_name']);
			}
			if ('fme_cart_file' == $fme_upload_files_id_name) {
				$fme_array = array();
				foreach ($_FILES as $key => $_file) {
					if ( isset( $_file['name']) && '' != $_file['name']) { 
						$fme_filename = $_file['name'];
						if (!file_exists( $fme_filename ) ) {
							move_uploaded_file($_file['tmp_name'], FMEUF_PLUGIN_DIR . '/uploadsfiles/' . $fme_filename); 
						}
						$fme_upload_files_url = FMEUF_URL . '/uploadsfiles/' . $fme_filename;	
						$fme_cart_file_session_array[filter_var($_REQUEST['fme_upload_files_count'])] = array(

							'fme_upload_files_cartfilename' => $_file['name'],
							'fme_upload_files_cartfilesize' => $_file['size'],
							'fme_upload_files_carttmp_name' => $_file['tmp_name'],
							'fme_upload_files_carttype' => $_file['type'],
							'fme_upload_files_cartfile_url' => $fme_upload_files_url,
							'fme_upload_files_cart_id_name' => $fme_upload_files_id_name
						);
					}

					if ( isset( WC()->session ) ) {
						if (WC()->session->get( 'fme_upload_files_cart_file_sessions' )=='') {
							$fme_array[$fme_upload_files_buttonID]=$fme_cart_file_session_array[filter_var($_REQUEST['fme_upload_files_count'])];
						} else {
							$fme_upload_files_cart_file_sessions= WC()->session->get( 'fme_upload_files_cart_file_sessions' );
							for ($i=0; $i<count($fme_upload_files_cart_file_sessions); $i++) {
								$fme_array[$i]=$fme_upload_files_cart_file_sessions[$i];
							}
							$fme_array[$fme_upload_files_buttonID]=$fme_cart_file_session_array[$fme_upload_files_buttonID];
						}
					}
					WC()->session->set( 'fme_upload_files_cart_file_sessions', $fme_array);
					
				}
			} else if ('fme_checkout_notes_file' == $fme_upload_files_id_name) {

				$fme_array = array();
				foreach ($_FILES as $key => $_file) {
					if ( isset( $_file['name']) && '' != $_file['name']) { 
						$fme_filename = $_file['name'];
						if (!file_exists( $fme_filename ) ) {
							move_uploaded_file($_file['tmp_name'], FMEUF_PLUGIN_DIR . '/uploadsfiles/' . $fme_filename); 
						}
						$fme_upload_files_url = FMEUF_URL . '/uploadsfiles/' . $fme_filename;	
						$fme_cart_file_session_array[filter_var($_REQUEST['fme_upload_files_count'])] = array(

							'fme_upload_files_checkoutafternotesfilename' => $_file['name'],
							'fme_upload_files_checkoutafternotesfilesize' => $_file['size'],
							'fme_upload_files_checkoutafternotestmp_name' => $_file['tmp_name'],
							'fme_upload_files_checkoutafternotestype' => $_file['type'],
							'fme_upload_files_checkoutafternotesfile_url' => $fme_upload_files_url,
							'fme_upload_files_checkoutafternote_id_name' => $fme_upload_files_id_name

						);
					}
					if ( isset( WC()->session ) ) {
						if (WC()->session->get( 'fme_upload_files_checkout_after_note_file_sessions' )=='') {
							$fme_array[$fme_upload_files_buttonID]=$fme_cart_file_session_array[filter_var($_REQUEST['fme_upload_files_count'])];
						} else {
							$fme_upload_files_cart_file_sessions= WC()->session->get( 'fme_upload_files_checkout_after_note_file_sessions' );
							for ($i=0; $i<count($fme_upload_files_cart_file_sessions); $i++) {
								$fme_array[$i]=$fme_upload_files_cart_file_sessions[$i];
							}
							$fme_array[$fme_upload_files_buttonID]=$fme_cart_file_session_array[$fme_upload_files_buttonID];
						}
					}
					WC()->session->set( 'fme_upload_files_checkout_after_note_file_sessions', $fme_array);
					
				}
				$fme_upload_files_checkout_after_note_file_sessions= WC()->session->get('fme_upload_files_checkout_after_note_file_sessions' ); 
				if (!empty($fme_upload_files_checkout_after_note_file_sessions)) { 
					?>
					<span id="fme_checkout_filename<?php echo esc_attr($fme_upload_files_buttonID); ?>">
					<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_buttonID]['fme_upload_files_checkoutafternotesfilename']); ?>
					</span>	
					<span>
					<?php 
					if ('' != $fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_buttonID]['fme_upload_files_checkoutafternotesfilename']) {  
						?>
					<a onclick="fme_upload_file_delete_cart_file('<?php echo esc_attr($fme_upload_files_buttonID); ?>','<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_buttonID]['fme_upload_files_checkoutafternotesfilename']); ?>', '<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_buttonID]['fme_upload_files_checkoutafternote_id_name']); ?>')" class="btn btn-primary fme_view_checkout_file" id="fme_checkout_file_view<?php echo esc_attr($fme_upload_files_buttonID); ?>">
					   <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						<span><strong><img class="fme_upload_files_iconpreviewcheckoutpage" src="<?php echo esc_url(FMEUF_URL) . 'front/images/delete.png'; ?>" id="fme_iconpreviewcheckoutpage<?php echo esc_attr($fme_upload_files_buttonID); ?>"></strong></span>            
					</a>
					<a href="<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_buttonID]['fme_upload_files_checkoutafternotesfile_url']); ?>" class="fme_upload_files_iconpreview_checkout_page" target="_blank" typee="image" id="fme_preview_checkout_link<?php echo esc_attr($fme_count); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'front/images/previewimage.png'; ?>" id="fme_checkout_preview<?php echo esc_attr($fme_upload_files_buttonID); ?>" class="fme_upload_files_iconpreview_checkout_page"></a>
					</span>	
					<?php } ?>
					<?php
				}

			}

			
			wp_die();
		}


		public function fme_upload_files_delete_cart_file_selected() {

			$fme_upload_files_cart_file_key = isset($_REQUEST['fme_upload_files_cart_file_key']) ? filter_var($_REQUEST['fme_upload_files_cart_file_key']) : '';
			$fme_filename = isset($_REQUEST['fme_upload_files_filename']) ? filter_var($_REQUEST['fme_upload_files_filename']) : '';
			$fme_upload_files_name_position = isset($_REQUEST['fme_upload_files_name_position']) ? filter_var($_REQUEST['fme_upload_files_name_position']) : '';
			if ('fme_checkout_notes_file' == $fme_upload_files_name_position) {
				$fme_upload_files_checkout_after_note_file_sessions= WC()->session->get('fme_upload_files_checkout_after_note_file_sessions' ); 
				unset($fme_upload_files_checkout_after_note_file_sessions[$fme_upload_files_cart_file_key]);
				WC()->session->set( 'fme_upload_files_checkout_after_note_file_sessions', $fme_upload_files_checkout_after_note_file_sessions);
			} else if ('fme_cart_file' == $fme_upload_files_name_position) {
				$fme_upload_files_cart_file_sessions= WC()->session->get('fme_upload_files_cart_file_sessions' ); 
				unset($fme_upload_files_cart_file_sessions[$fme_upload_files_cart_file_key]);
				WC()->session->set( 'fme_upload_files_cart_file_sessions', $fme_upload_files_cart_file_sessions);
				
			}
			wp_die();
		}

		public function fme_upload_files_load_text_domain() {
			load_plugin_textdomain('Fme_Upload_Files', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}

		public function fme_upload_files_custom_posttype() {

			register_post_type( 'fme_upload_files',
				array(
					'labels' => array(
						'name' => esc_html__( 'Fme_Upload_Files' , 'Fme_Upload_Files'),
						'singular_name' => esc_html__( 'Fme_Upload_Files' , 'Fme_Upload_Files')
					),
					'public' => true,
					'has_archive' => true,
					'rewrite' => array('slug' => 'Fme_Upload_Files'),
					'show_in_rest' => true,
					'show_ui' => true,
					'show_in_menu'  => false,

				)
			);
		}


		public function fme_upload_files_save_general_settings() {

			$fme_enable_disable_setting = isset($_REQUEST['fme_enable_disable_setting']) ? filter_var($_REQUEST['fme_enable_disable_setting']) : '';
			$fme_display_on_value = isset($_REQUEST['fme_display_on_value']) ? filter_var($_REQUEST['fme_display_on_value']) : '';
			$fme_selection_files = isset($_REQUEST['fme_selection_files']) ? filter_var($_REQUEST['fme_selection_files']) : '';		
			$fme_multiple_by_quantity = isset($_REQUEST['fme_multiple_by_quantity']) ? filter_var($_REQUEST['fme_multiple_by_quantity']) : '';

			$fme_multiple_files_limit = isset($_REQUEST['fme_multiple_files_limit']) ? stripslashes(filter_var($_REQUEST['fme_multiple_files_limit'])) : '';	

			$fme_files_array = json_decode($fme_multiple_files_limit, true);

			$fme_upload_files_allowed_file_types = isset($_REQUEST['fme_allowed_file_types']) ? filter_var($_REQUEST['fme_allowed_file_types']) : '';
			$fme_maximum_uploadsize = isset($_REQUEST['Fme_maximum_uploadsize']) ? filter_var($_REQUEST['Fme_maximum_uploadsize']) : '';
			$fme_upload_file_size = isset($_REQUEST['fme_file_size']) ? filter_var($_REQUEST['fme_file_size']) : '';
			$fme_selected_product_category = isset($_REQUEST['fme_product_category']) ? filter_var($_REQUEST['fme_product_category']) : '';
			$fme_selected_items = isset($_REQUEST['fme_selected_items']) ? array_map('filter_var', $_REQUEST['fme_selected_items']) : '';
			$fme_selected_user_role = isset($_REQUEST['fme_selected_user_role']) ? array_map('filter_var', $_REQUEST['fme_selected_user_role']) : '';
			
			$fme_post_id = wp_insert_post(
				array(
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author'		=>	'Fme_Upload_Files',
					'post_name'		=>	'Fme_Upload_Files',
					'post_title'		=>	'Fme_Upload_Files',
					'post_status'		=>	'publish',
					'post_type'		=>	'fme_upload_files'
				)
			);

			if ($fme_post_id) {

				update_post_meta($fme_post_id, 'fme_enable_disable_settings', $fme_enable_disable_setting);
				update_post_meta($fme_post_id, 'fme_display_on_values', $fme_display_on_value);
				update_post_meta($fme_post_id, 'fme_selection_files', $fme_selection_files);
				update_post_meta($fme_post_id, 'fme_multiple_files_limit', $fme_files_array);
				update_post_meta($fme_post_id, 'fme_allowed_file_types', $fme_upload_files_allowed_file_types);
				update_post_meta($fme_post_id, 'fme_file_size', $fme_upload_file_size);
				update_post_meta($fme_post_id, 'fme_maximum_uploadsize', $fme_maximum_uploadsize);
				update_post_meta($fme_post_id, 'fme_selected_product_category', $fme_selected_product_category);
				update_post_meta($fme_post_id, 'fme_selected_items', $fme_selected_items);
				update_post_meta($fme_post_id, 'fme_selected_user_role', $fme_selected_user_role);
				update_post_meta($fme_post_id, 'fme_multiple_by_quantity', $fme_multiple_by_quantity);
			}

			wp_die();
		}


		public function fme_upload_files_delete_rule() {

			global $wpdb;
			$delete_post_id = isset($_REQUEST['fme_upload_files_rule_id']) ? filter_var($_REQUEST['fme_upload_files_rule_id']) : '';
			if ( 'fme_upload_files' === get_post_type( $delete_post_id ) ) {
				wp_delete_post($delete_post_id);
			}
			wp_die();
		}


		public function fme_upload_files_edit_rule_file() {

			$fme_upload_files_edit_post_id = isset($_REQUEST['fme_upload_files_rule_id']) ? filter_var($_REQUEST['fme_upload_files_rule_id']) : '';
			$fme_upload_files_get_status =  get_post_meta($fme_upload_files_edit_post_id, 'fme_enable_disable_settings', true);
			$fme_upload_files_display_on_position =  get_post_meta($fme_upload_files_edit_post_id, 'fme_display_on_values', true);
			$fme_upload_files_selected_files =  get_post_meta($fme_upload_files_edit_post_id, 'fme_selection_files', true);
			$fme_upload_files_multival_arr = get_post_meta($fme_upload_files_edit_post_id , 'fme_multiple_files_limit', true);
			$fme_upload_files_allowed_file_type = get_post_meta($fme_upload_files_edit_post_id, 'fme_allowed_file_types', true); 
			$fme_upload_files_maximum_file_size = get_post_meta($fme_upload_files_edit_post_id, 'fme_maximum_uploadsize', true); 
			$fme_upload_file_size = get_post_meta($fme_upload_files_edit_post_id, 'fme_file_size', true); 
			$fme_selected_product_category = get_post_meta($fme_upload_files_edit_post_id, 'fme_selected_product_category', true); 
			$fme_selected_product_item = get_post_meta($fme_upload_files_edit_post_id, 'fme_selected_items', true);
			$fme_selected_user_roles = get_post_meta($fme_upload_files_edit_post_id, 'fme_selected_user_role', true); 
			$fme_multiple_by_quantity = get_post_meta($fme_upload_files_edit_post_id, 'fme_multiple_by_quantity', true);
			?>
			<div class="container-fluid">
				<div class="row" id="fme_upload_files_FormSettings">
					<div class="col-xs-4 col-md-3 col-md-offset-1">
						<label id="fme_upload_files_label"><?php echo esc_html__('Enable/Disable', 'Fme_Upload_Files'); ?></label>
					</div>
					<div class="col-xs-4 col-md-4">
						<select id="fme_edit_enable_disable_setting">
							<option value="" <?php selected('', $fme_upload_files_get_status, true); ?>><?php echo esc_html__('Choose visibility', 'Fme_Upload_Files'); ?></option>
							<option value="fme_upload_files_enable"  <?php selected('fme_upload_files_enable', $fme_upload_files_get_status, true); ?>><?php echo esc_html__('	Enable', 'Fme_Upload_Files'); ?></option>
							<option value="fme_upload_files_disable" <?php selected('fme_upload_files_disable', $fme_upload_files_get_status, true); ?>><?php echo esc_html__('Disable', 'Fme_Upload_Files'); ?></option>
						</select>
					</div>
				</div>
				<div class="row" id="fme_upload_files_FormSettings">
					<div class="col-xs-4 col-md-3 col-md-offset-1">
						<label  id="fme_upload_files_label"><?php echo esc_html__('Display On', 'Fme_Upload_Files'); ?></label>
					</div>
					<div class="col-xs-4 col-md-6">
						<input type="radio" <?php checked('fme_upload_files_product_page', $fme_upload_files_display_on_position, true); ?> id="fme-product-page" class="fme_upload_files_radio" name="fme-edit-radio-select-display-on" value="fme_upload_files_product_page">
						<label for="fme-product-page" id="fme_upload_files_radio"><?php echo esc_html__('Product Page', 'Fme_Upload_Files'); ?></label>
						<br/>
						<input type="radio" <?php checked('fme_upload_files_cart_page', $fme_upload_files_display_on_position, true); ?>  id="fme-cart-page" class="fme_upload_files_radio" name="fme-edit-radio-select-display-on" value="fme_upload_files_cart_page">
						<label for="fme-cart-page" id="fme_upload_files_radio">
							<?php echo esc_html__('Cart Page', 'Fme_Upload_Files'); ?>
						</label>
						<br/>
						<input type="radio"  <?php checked('fme_upload_files_checkout_page_after_notes', $fme_upload_files_display_on_position, true); ?>  id="fme-checkout-page-after-notes" class="fme_upload_files_radio" name="fme-edit-radio-select-display-on" value="fme_upload_files_checkout_page_after_notes">
						<label for="fme-checkout-page" id="fme_upload_files_radio">
							<?php echo esc_html__('Checkout Page > After Notes', 'Fme_Upload_Files'); ?>
						</label>
					</div>
				</div>
				<div class="row" id="fme_upload_files_FormSettings">
					<div class="col-xs-4 col-md-3 col-md-offset-1">
						<label id="fme_upload_files_label"><?php echo esc_html__('Single/Multiple Files:', 'Fme_Upload_Files'); ?></label>
					</div>
					<div class="col-xs-4 col-md-6">
						<select id="FME_edit_selection_files" onchange="fme_upload_files_upload_selection_files('fme_edit');">
							<option value="" <?php selected('', $fme_upload_files_selected_files, true); ?>><?php echo esc_html__('Select one', 'Fme_Upload_Files'); ?></option>
							<option value="fme_upload_files_single" <?php selected('fme_upload_files_single', $fme_upload_files_selected_files, true); ?>><?php echo esc_html__('Single', 'Fme_Upload_Files'); ?></option>
							<option value="fme_upload_files_multiple" <?php selected('fme_upload_files_multiple', $fme_upload_files_selected_files, true); ?>><?php echo esc_html__('Multiple', 'Fme_Upload_Files'); ?></option>
						</select>
					</div>
					<div class="col-md-7 col-md-offset-4">
						<span id="edit_fme_multiple_files" 
						<?php 
						if ('fme_upload_files_multiple' == $fme_upload_files_selected_files) {
							echo "style='display:block'";
						} else {
							echo "style='display:none'";
						}  
						?>
						>
						<table class="table table-responsive" id="fme-upload-file-multiple-table">
							<thead>
								<tr>	
								<td><?php echo esc_html__('Price:', 'Fme_Upload_Files'); ?></td>
								<td colspan="4"><?php echo esc_html__('Discount Type:', 'Fme_Upload_Files'); ?></td>
								<td colspan="1"><?php echo esc_html__('Discount Price:', 'Fme_Upload_Files'); ?></td>
								<td colspan="3"><?php echo esc_html__('Action:', 'Fme_Upload_Files'); ?></td>
								</tr>
								</thead>
								<tbody id="TextBoxContainer1">
								<?php foreach ($fme_upload_files_multival_arr as $key => $value) { ?>	
									<tr>
									<td>
										<input placeholder="price" id="fme_files_input_filed_price" name ="fme_multiple_file_price[]" type="number" min="0" value = "<?php echo esc_attr($value['fme_uploadfiles_price']); ?>" class="form-control" />
									</td>
									<td colspan="4">
									<select name="fme_discount_type_multiple[]" class="form-control">
										<option value="" <?php selected('', $value['fme_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Discount Type', 'Fme_Upload_Files'); ?> </option>
										<option value="Fme_upload_files_fixed" <?php selected('Fme_upload_files_fixed', $value['fme_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Fixed', 'Fme_Upload_Files'); ?></option>
										<option value="Fme_upload_files_percentage" <?php selected('Fme_upload_files_percentage', $value['fme_uploadfiles_discount_type']); ?>> <?php echo esc_html__('Percentage', 'Fme_Upload_Files'); ?></option>
									</select>
									</td>
									<td colspan="1">
										<input placeholder="discount price" id="fme_files_input_filed_price" name ="fme_discount_price_multiple_file[]" type="number" min="0" value = "<?php echo esc_attr($value['fme_uploadfiles_discount_price']); ?>" class="form-control" />
									</td>
									<td>
										<button type="button" class="btn btn-danger remove">x</button>
									</td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<th>
											<img src="<?php echo esc_url(FMEUF_URL) . 'admin/images/fme-flat-plus-icon.png'; ?>" id="fme_upload_files_plus_icon_multiple" onclick="fme_uploadfile_multiplefile('fme_edit');">
										</th>
									</tr>
								</tfoot>
						</table>
						</span>
						<div id="Fme_uploadfile_price_singles" 
						<?php 
						if ('fme_upload_files_single' == $fme_upload_files_selected_files) { 
							echo "style='display:block'";
						} else {
							echo "style='display:none'";
						}
						?>
						>
						<?php 
						for ($i=0; $i<1; $i++) { 
							?>
							<div class="row" id="FMEFormSettingsupload">
								<div class="col-xs-4 col-md-3 col-md-offset-1">
									<label id="fme_upload_files_label"><?php echo esc_html__('Price:', 'Fme_Upload_Files'); ?></label>
								</div>
								<div class="col-xs-4 col-md-6">
									<input type="number" name="fmepriceuploadfile" value="<?php echo esc_attr($fme_upload_files_multival_arr[$i]['fme_uploadfiles_price']); ?>" id="fme_editpriceuploadfile">
								</div>
							</div>
							<div class="row" id="fme_upload_files_Discount">
								<div class="col-xs-4 col-md-3 col-md-offset-1">
									<label id="fme_upload_files_label"><?php echo esc_html__('Discount Type:', 'Fme_Upload_Files'); ?></label>
								</div>
								<div class="col-xs-4 col-md-6">
									<select id="fme_editDiscounttype">
										<option value="" <?php selected('', $fme_upload_files_multival_arr[$i]['fme_uploadfiles_discount_type']); ?>><?php echo esc_html__('Select Discount Type', 'Fme_Upload_Files'); ?></option>
										<option value="Fme_upload_files_fixed" <?php selected('Fme_upload_files_fixed', $fme_upload_files_multival_arr[$i]['fme_uploadfiles_discount_type']); ?>><?php echo esc_html__('Fixed', 'Fme_Upload_Files'); ?></option>
										<option value="Fme_upload_files_percentage" <?php selected('Fme_upload_files_percentage', $fme_upload_files_multival_arr[$i]['fme_uploadfiles_discount_type']); ?>><?php echo esc_html__('Percentage', 'Fme_Upload_Files'); ?></option>
									</select>
								</div>
							</div>
							<div class="row" id="fme_editDiscountval">
								<div class="col-xs-4 col-md-3 col-md-offset-1">
									<label id="fme_upload_files_label"><?php echo esc_html__('Discount value:', 'Fme_Upload_Files'); ?></label>
								</div>
							<div class="col-xs-4 col-md-6">
								<input type="number" min="0" oninput="validity.valid||(value='')" name="fmeDiscountvalue" value="<?php echo esc_attr($fme_upload_files_multival_arr[$i]['fme_uploadfiles_discount_price']); ?>" id="fme_editDiscountvalue">
							</div>
						</div>	    			
						<?php } ?>	 				
					</div>
				</span>
			</div>
		</div>
		<div class="row" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Allowed File Types:', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
				<input type="text" class="form-control Fme-file-type" name="Fme_allowed_file_types" id="Fme_edit_allowed_file_types" value="<?php echo esc_attr($fme_upload_files_allowed_file_type); ?>">
				<span class="fme_upload_files_description"><?php echo esc_html__('Specify which file types are allowed for uploading, seperate by commas.', 'Fme_Upload_Files'); ?>
				</span>
		</div>
		</div>

		<div class="row" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('File Size:', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-4">
				<select id="fme_edit_file_size">
					<option value="fme_KB" <?php selected('fme_KB', $fme_upload_file_size, true); ?>><?php echo esc_html__('KB', 'Fme_Upload_Files'); ?></option>
					<option value="fme_MB"  <?php selected('fme_MB', $fme_upload_file_size, true); ?>><?php echo esc_html__('MB', 'Fme_Upload_Files'); ?></option>
					<option value="fme_GB" <?php selected('fme_GB', $fme_upload_file_size, true); ?>><?php echo esc_html__('GB', 'Fme_Upload_Files'); ?></option>
				</select>
			</div>
		</div>
		<div class="row" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Maximum Upload Size:', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-4">
				<input type="number" oninput="validity.valid||(value='')" min="0" class="form-control Fme-upload-size" name="Fme-maximum-uploadsize" value="<?php echo esc_attr($fme_upload_files_maximum_file_size); ?>" id="Fme_edit_maximum_uploadsize">
				<span class="fme_upload_files_description"><?php echo esc_html__('Enter uploaded file size.', 'Fme_Upload_Files'); ?>
				</span><br/>
			</div>
			</div>

			<?php if ('fme_upload_files_product_page' == $fme_upload_files_display_on_position) { ?>	
		<div class="row Edit_Multiply_by_Quantity" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Multiply By Quantity:', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-4">
				<select id="fme_upload_files_update_multiple_by_qunatity">
					<option value="" <?php selected('' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Choose one', 'Fme_Upload_Files'); ?></option>
					<option value="fme_upload_files_multiple_enable" <?php selected('fme_upload_files_multiple_enable' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Enable', 'Fme_Upload_Files'); ?></option>
					<option value="fme_upload_files_multiple_disable" <?php selected('fme_upload_files_multiple_disable' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Disable', 'Fme_Upload_Files'); ?></option>
				</select><br/>
				<span class="fme_upload_files_description"><?php echo esc_html__('Enble to Multiply uploaded files by Quantity.', 'Fme_Upload_Files'); ?>
				</span><br/>
			</div>
		</div>
		<?php } else { ?>	
			<div class="row Edit_Multiply_by_Quantity" id="fme_upload_files_FormSettings" style="display: none;">
				<div class="col-xs-4 col-md-3 col-md-offset-1">
					<label id="fme_upload_files_label"><?php echo esc_html__('Multiply By Quantity:', 'Fme_Upload_Files'); ?></label>
				</div>
				<div class="col-xs-4 col-md-4">
					<select id="fme_upload_files_update_multiple_by_qunatity">
						<option value="" <?php selected('' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Choose one', 'Fme_Upload_Files'); ?></option>
						<option value="fme_upload_files_multiple_enable" <?php selected('fme_upload_files_multiple_enable' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Enable', 'Fme_Upload_Files'); ?></option>
						<option value="fme_upload_files_multiple_disable" <?php selected('fme_upload_files_multiple_disable' , $fme_multiple_by_quantity); ?>><?php echo esc_html__('Disable', 'Fme_Upload_Files'); ?></option>
					</select><br/>
					<span class="fme_upload_files_description"><?php echo esc_html__('Enble to Multiply uploaded files by Quantity.', 'Fme_Upload_Files'); ?>
					</span><br/>
				</div>
			</div>
				<?php
		} 
		?>
		<div class="row" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Product/Category Restriction', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-5">
				<select class="form-control fmeproductcategory" id="fme_edit_product_category" name="selectpc[]" onchange="Fme_upload_file_choosen_product_cateory('fme_edit');">
					<option value="" <?php selected('', $fme_selected_product_category, true); ?>><?php echo esc_html__('Visible for every product:', 'extCCFA'); ?></option>
					<option value="fme_upload_files_product" <?php selected('fme_upload_files_product', $fme_selected_product_category, true); ?>><?php echo esc_html__('Product', 'extCCFA'); ?></option>
					<option value="fme_upload_files_category" <?php selected('fme_upload_files_category', $fme_selected_product_category, true); ?>><?php echo esc_html__('Category', 'extCCFA'); ?></option>
				</select>
				<span class="fme_upload_files_description"><?php echo esc_html__('Upload field can optionally visible/hidden only if the selected products are in cart/order.', 'Fme_Upload_Files'); ?>
				</span>
			</div>
		</div>
		<div class="row" id="Fme_edit_Products" 
			<?php 
			if ('fme_upload_files_category' == $fme_selected_product_category || '' == $fme_selected_product_category) {
				echo "style='display:none'";
			} 
			?>
			>
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Select Product', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-6">
			<?php 
			global $post;
			$fme_upload_file_product = array(
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'post_type' => array( 'product')
			);
			$fme_upload_files_Products = get_posts($fme_upload_file_product);
			if (!empty($fme_upload_files_Products)) { 
				?>
			<select class="Fme_edit_choosen<?php echo esc_attr($fme_upload_files_edit_post_id); ?>" id="Fme_edit_products" multiple="multiple" name="">
				<?php
				foreach ($fme_upload_files_Products as $products) {
					?>
					<option value="<?php echo esc_attr($products->ID); ?>"<?php selected(in_array($products->ID, $fme_selected_product_item), true); ?>><?php echo filter_var($products->post_title); ?></option>
					<?php
				}

				?>
			</select>
			<?php }; ?>
			</div>
		</div>
		<div class="row" id="Fme_edit_category" 
			<?php 
			if ('fme_upload_files_product' == $fme_selected_product_category || '' == $fme_selected_product_category) { 
				echo "style='display:none'";
			} 
			?>
			>
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('Select category', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-6">
			<?php 
			$fme_upload_files_category = array(
				'taxonomy' => 'product_cat',
			);
			$fme_upload_product_categories = get_terms($fme_upload_files_category);
			if (!empty($fme_upload_product_categories)) { 
				?>
			<select class="Fme_edit_choosen<?php echo esc_attr($fme_upload_files_edit_post_id); ?>" id="Fme_edit_categories" multiple="multiple" name="">
				<?php
				foreach ($fme_upload_product_categories as $category) {
					?>
					<option value="<?php echo esc_attr($category->term_id); ?>"<?php selected(in_array($category->term_id, $fme_selected_product_item), true); ?>><?php echo esc_attr($category->name); ?></option>
					<?php
				}
				?>
			</select>
			<?php } ?>
			</div>
		</div>

		<div class="row" id="fme_upload_files_FormSettings">
			<div class="col-xs-4 col-md-3 col-md-offset-1">
				<label id="fme_upload_files_label"><?php echo esc_html__('User role', 'Fme_Upload_Files'); ?></label>
			</div>
			<div class="col-xs-4 col-md-6">
			<?php
			global $wp_roles;
			$fme_upload_files_roles = $wp_roles->get_names();
			if (!empty($fme_upload_files_roles)) {
				?>
				<select class="Fme_edit_choosen<?php echo esc_attr($fme_upload_files_edit_post_id); ?>" id="Fme_edit_choosen_user_role" multiple="multiple" name="">
				<?php
				foreach ($fme_upload_files_roles as $key => $value) {
					?>
					<option value="<?php echo filter_var(strtolower($value)); ?>" <?php selected(in_array(strtolower($value), $fme_selected_user_roles), true); ?>>
					<?php echo filter_var($value); ?>
					</option>
					<?php
				}
				?>
				</select>
				<?php
			}
			?>
			<br/>
			<span class="fme_upload_files_description"><?php echo esc_html__('Selecting at least one role will make the upload field to be visible/unvisible to that role..', 'Fme_Upload_Files'); ?>
			</span>
				</div>
			</div>
		</div>
			<?php 
			wp_die();
		}


		public function fme_upload_files_update_general_settings() {

			$fme_update_post_id = isset($_REQUEST['fme_update_post_id']) ? filter_var($_REQUEST['fme_update_post_id']) : '';
			$fme_enable_disable_setting = isset($_REQUEST['fme_edit_enable_disable_setting']) ? filter_var($_REQUEST['fme_edit_enable_disable_setting']) : '';
			$fme_display_on_value = isset($_REQUEST['fme_edit_display_on_value']) ? filter_var($_REQUEST['fme_edit_display_on_value']) : '';
			$fme_selection_files = isset($_REQUEST['fme_edit_selection_files']) ? filter_var($_REQUEST['fme_edit_selection_files']) : '';

			$fme_multiple_by_quantity = isset($_REQUEST['fme_multiple_by_quantity']) ? filter_var($_REQUEST['fme_multiple_by_quantity']) : '';
	
			$fme_edit_multiple_files_limit = isset($_REQUEST['fme_edit_multiple_files_limit']) ? stripslashes(filter_var($_REQUEST['fme_edit_multiple_files_limit'])) : '';	
			
			$fme_edit_files_array = json_decode($fme_edit_multiple_files_limit, true);

			$fme_upload_files_allowed_file_types = isset($_REQUEST['fme_edit_allowed_file_types']) ? filter_var($_REQUEST['fme_edit_allowed_file_types']) : '';
			$fme_edit_file_size = isset($_REQUEST['fme_edit_file_size']) ? filter_var($_REQUEST['fme_edit_file_size']) : '';
			$fme_maximum_uploadsize = isset($_REQUEST['fme_edit_maximum_uploadsize']) ? filter_var($_REQUEST['fme_edit_maximum_uploadsize']) : '';
			$fme_selected_product_category = isset($_REQUEST['fme_edit_product_category']) ? filter_var($_REQUEST['fme_edit_product_category']) : '';
			$fme_selected_items = isset($_REQUEST['fme_edit_selected_items']) ? array_map('filter_var', $_REQUEST['fme_edit_selected_items']) : '';
			$fme_selected_user_role = isset($_REQUEST['fme_edit_selected_user_role']) ? array_map('filter_var', $_REQUEST['fme_edit_selected_user_role']) : '';

			if ('' != $fme_update_post_id) {

				update_post_meta($fme_update_post_id, 'fme_enable_disable_settings', $fme_enable_disable_setting);
				update_post_meta($fme_update_post_id, 'fme_display_on_values', $fme_display_on_value);
				update_post_meta($fme_update_post_id, 'fme_selection_files', $fme_selection_files);
				update_post_meta($fme_update_post_id, 'fme_multiple_files_limit', $fme_edit_files_array);
				update_post_meta($fme_update_post_id, 'fme_allowed_file_types', $fme_upload_files_allowed_file_types);
				update_post_meta($fme_update_post_id, 'fme_file_size', $fme_edit_file_size);
				update_post_meta($fme_update_post_id, 'fme_maximum_uploadsize', $fme_maximum_uploadsize);
				update_post_meta($fme_update_post_id, 'fme_selected_product_category', $fme_selected_product_category);
				update_post_meta($fme_update_post_id, 'fme_selected_items', $fme_selected_items);
				update_post_meta($fme_update_post_id, 'fme_selected_user_role', $fme_selected_user_role);
				update_post_meta($fme_update_post_id, 'fme_multiple_by_quantity', $fme_multiple_by_quantity);
			}

			wp_die();
		}

		public function Fme_upload_files_admin_scripts() {	

			if (isset($_GET['tab'])) {

				if (is_admin() && 'fme_upload_files'== $_GET['tab']) {
					wp_enqueue_style('jquery');
					wp_enqueue_style( 'bootstrap-min-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), false , 1.0 );
					wp_enqueue_style( 'fme_upload_files_setting_css', plugins_url( 'assets/css/Upload_Files_Admin.css', __FILE__ ), false , 1.0 );
					wp_enqueue_script( 'bootstrap-min-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), false, 1.0 );
					wp_enqueue_script( 'fme_upload_files_setting_js', plugins_url( 'assets/js/fme-upload-files-admin.js', __FILE__ ), false, 1.0 );
					wp_enqueue_script( 'select2-min-js', plugins_url( 'assets/js/select2.min.js', __FILE__ ), false, 1.0 );
					wp_enqueue_style( 'select2-min-css', plugins_url( 'assets/css/select2.min.css', __FILE__ ), false , 1.0 );
					$ewcpm_data = array(
						'admin_url' => admin_url('admin-ajax.php'),
					);
					wp_localize_script('fme_upload_files_setting_js', 'ewcpm_php_vars', $ewcpm_data);
					wp_localize_script('fme_upload_files_setting_js', 'ajax_url_add_pq', array('ajax_url_add_pq_data' => admin_url('admin-ajax.php')));
				}
			}
		}



	}

	new Fme_Upload_Files_Admin();
}
