<?php 
error_reporting(0); 
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'Fme_Upload_Files_Front' ) ) { 

	class Fme_Upload_Files_Front { 

		public function __construct() {
		
			add_action( 'wp', array($this, 'Fme_Upload_Files_templates' ));	 
			add_action( 'wp_loaded', array( $this, 'Fme_upload_Files_scripts_front' ) );
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'fme_uploadfile_add_text_to_cart_item' ), 10, 3);
			add_filter( 'woocommerce_get_item_data', array($this,'fme_uploadfile_get_cart_item_in_cart'), 10, 2 );
			add_action( 'woocommerce_before_calculate_totals', array($this,'fme_upload_files_extra_price_add_custom_price'), 20, 1 );
			add_filter('woocommerce_cart_item_price', array($this,'fme_upload_files_display_cart_items_custom_price_details'), 20, 3 );
			add_action( 'woocommerce_cart_calculate_fees', array($this, 'fme_upload_files_ext_woo_add_cart_fee' ));
			add_action( 'template_redirect', array($this,'fme_upload_files_redirect_to_checkout_if_cart' ));
			add_action( 'woocommerce_add_order_item_meta', array($this, 'fme_upload_files_order_item_meta') , 10, 2 );
			add_action( 'woocommerce_checkout_update_order_meta', array($this,'fme_uploaad_files_custom_checkout_field_update_order_meta' ));
			add_action( 'woocommerce_thankyou', array($this, 'fme_upload_files_display_order_data'), 20 );
			add_action( 'woocommerce_view_order', array($this,'fme_upload_files_display_order_data'), 20 );
			
		}

	
		public function fme_upload_files_display_order_data( $fme_upload_files_order_id ) { 		
			$fme_upload_files_order = wc_get_order( $fme_upload_files_order_id );
			$fme_upload_files_get_oder_fee_items = $fme_upload_files_order->get_items('fee');
			update_option('fme_upload_counter_file', count($fme_upload_files_get_oder_fee_items));
			$fme_fee_total = get_option('fme_upload_counter_file');
			$fme_total_count=0;
			foreach ($fme_upload_files_order->get_items() as $item_id => $item ) {
				$custom_field = wc_get_order_item_meta( $item_id, '_fme_upload_count', true );
				if ('' == $custom_field) {
					$fme_v = 0;
					$fme_total_count=$fme_total_count + $fme_v;
				} else {
					$fme_total_count=$fme_total_count + count($custom_field);
				}
				
			}
			$fme_total_upload_file = $fme_fee_total + $fme_total_count;
			update_post_meta( $fme_upload_files_order_id, 'fme_total', $fme_total_upload_file);
			if (!empty($fme_upload_files_get_oder_fee_items)) {
				?>
				<label><strong><?php echo esc_html__('Uploaded Files Data:', 'Fme_Upload_Files'); ?></strong></label>
				<div class="container">          
				  <table class="fme_upload_files_order_fee_table">
					<thead>
					  <tr>
						<th><?php echo esc_html__('File Name:', 'Fme_Upload_Files'); ?></th>
						<th><?php echo esc_html__('File Price:', 'Fme_Upload_Files'); ?></th>
						<th><?php echo esc_html__('Files:', 'Fme_Upload_Files'); ?></th>
					  </tr>
					</thead>
					<tbody>
						<?php 
						foreach ($fme_upload_files_get_oder_fee_items as $item_id => $item_fee ) { 
							?>
						  <tr id="fme_upload_files_order_row">
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

		public function fme_uploaad_files_custom_checkout_field_update_order_meta( $fme_upload_file_order_id ) {
			if ('' != $fme_upload_file_order_id ) {
				WC()->session->__unset( 'fme_upload_files_cart_file_sessions' );
				WC()->session->__unset( 'fme_upload_files_checkout_after_note_file_sessions' );
			}
		}

		public function fme_upload_files_order_item_meta( $fme_upload_file_item_id, $fme_upload_file_values ) { 

			if (!empty($fme_upload_file_values['fme_upload_file_data'])) {
				wc_update_order_item_meta($fme_upload_file_item_id, '_fme_upload_count', $fme_upload_file_values['fme_upload_file_data']);
				foreach ($fme_upload_file_values['fme_upload_file_data'] as $fme_upload_file_key => $values) {
					if (''!= $values['fme_upload_files_discount_type'] && '' != $values['fme_upload_files_discount_price']) {
						if ('Fme_upload_files_percentage' == $values['fme_upload_files_discount_type']) {
							$fme_upload_fileprice = $values['fme_upload_files_price'];
							$fme_upload_files_total_discount = ( $fme_upload_fileprice * $values['fme_upload_files_discount_price'] )/100;
							$fme_upload_files_total_discount = floatval($fme_upload_fileprice)-floatval($fme_upload_files_total_discount);
							$fme_upload_files_dicounted_product_val = floatval($fme_upload_fileprice-$fme_upload_files_total_discount);
						} else if ('Fme_upload_files_fixed' == $values['fme_upload_files_discount_type']) {
							$fme_upload_fileprice =$values['fme_upload_files_price'];
							$fme_upload_filess_total_discount = ( $fme_upload_fileprice - $values['fme_upload_files_discount_price'] );
							$fme_upload_files_dicounted_product_val = esc_attr($values['fme_upload_files_discount_price']);
						}
						$fme_upload_filename =$values['fme_upload_files_name'];
						$fme_upload_filename_pathx = FMEUF_URL . '/uploadsfiles/' . $fme_upload_filename;
					} else {
						$fme_upload_filename = $values['fme_upload_files_name'];
						$fme_upload_filename_pathx = FMEUF_URL . '/uploadsfiles/' . $fme_upload_filename;
					}
					if (''!= $values['fme_upload_files_discount_type'] && '' != $values['fme_upload_files_discount_price']) {
						$fme_upload_files_key = '<b>' . esc_html__('file:' . $fme_upload_file_key, 'Fme_Upload_Files') . '</b> ' . wc_clean( $fme_upload_filename ) . '</br><b>' . esc_html__('file price:', 'Fme_Upload_Files') . '</b>' . wc_clean( wc_price($fme_upload_fileprice)) . '</br><b>' . esc_html__('Discount:', 'Fme_Upload_Files') . '</b>' . wc_clean(str_replace('Fme_', '', $values['fme_upload_files_discount_type']) . '(' . $fme_upload_files_dicounted_product_val . '%)');
					} else {
						$fme_upload_files_key = '<b>' . esc_html__('file:' . $fme_upload_file_key, 'Fme_Upload_Files') . '</b> ' . wc_clean($fme_upload_filename) . '</br><b>' . esc_html__('file price:', 'Fme_Upload_Files') . '</b>' . wc_clean( wc_price($values['fme_upload_files_price']));
					}
					if ('image/jpg'== $values['fme_upload_files_type'] || 'image/png'== $values['fme_upload_files_type'] || 'image/jpeg'== $values['fme_upload_files_type'] || 'image/svg'== $values['fme_upload_files_type'] || 'image/gif'== $values['fme_upload_files_type']) {
						$fme_upload_files_image_val =  '<a href="' . esc_url($fme_upload_filename_pathx) . '" class="fme_thankyou_page" target="_blank" typee="image"><img src="' . esc_url($fme_upload_filename_pathx) . '" id="fme_img_thumbnail" width="100" class="fme_upload_files_img_thumbnail"></a><br/>';
						wc_update_order_item_meta($fme_upload_file_item_id, $fme_upload_files_key, $fme_upload_files_image_val);
					} else {
						$fme_upload_files_val =  '<a href="' . esc_url($fme_upload_filename_pathx) . '" class="fme_thankyou_page1" target="_blank" typee="application/' . $ext . '"">' 
						. esc_html__('Preview', 'Fme_Upload_Files'); 
						'</a>';
						wc_update_order_item_meta($fme_upload_file_item_id, $fme_upload_files_key, $fme_upload_files_val);
					}
				}
			}
		}

		public function fme_upload_files_redirect_to_checkout_if_cart() {
			if ( !is_cart() ) {
				return;
			}
			global $woocommerce;
			if ( is_cart() && WC()->cart->cart_contents_count == 0) {
				WC()->session->__unset( 'fme_upload_files_cart_file_sessions' );
				WC()->session->__unset( 'fme_upload_files_checkout_after_note_file_sessions' );
			} 
			
		}

		public function fme_upload_files_ext_woo_add_cart_fee( $cart ) {

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}
			
			$fme_upload_files_cart_page_rule  = $this->Fme_get_Upload_files_rules();
			foreach ($fme_upload_files_cart_page_rule as $key => $fme_cart_rule) {
				if ('fme_upload_files_cart_page'== $fme_cart_rule['fme_upload_files_display_on_position']) {
					$fme_upload_files_cartpage_rulee  = $fme_cart_rule['fme_upload_files_multiple_files_limit'];
				} else if ('fme_upload_files_checkout_page_after_notes' == $fme_cart_rule['fme_upload_files_display_on_position']) {
					$fme_upload_files_checkout_after_notes_page_rulee  = $fme_cart_rule['fme_upload_files_multiple_files_limit'];
				} else {
					continue;
				}
			}
			$fme_upload_file_cart_file_sessions= WC()->session->get('fme_upload_files_cart_file_sessions' ); 
			$fme_upload_files_checkout_after_note_file_sessions= WC()->session->get('fme_upload_files_checkout_after_note_file_sessions' );

			if ('' != $fme_upload_file_cart_file_sessions) { 
				foreach ($fme_upload_file_cart_file_sessions as $key => $value) {
					if (isset($value['fme_upload_files_cartfilename']) && '' != $value['fme_upload_files_cartfilename']) {
						if (''!= $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_type'] && '' != $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_price']) {
							if ('' == $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price']) {
								$cart->add_fee($value['fme_upload_files_cartfilename'], $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price']);
							} else {
								if ('Fme_upload_files_percentage' == $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_type']) {
									$fme_upload_fileprice = $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price'];
									$fme_upload_file_total_discount = ( $fme_upload_fileprice * $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_price'] )/100;
									$fme_upload_file_total_discount = floatval($fme_upload_fileprice)-floatval($fme_upload_file_total_discount);
									$fme_upload_files_dicounted_cart_val = floatval($fme_upload_file_total_discount-$fme_upload_fileprice);
								} else if ('Fme_upload_files_fixed' == $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_type']) {
									$fme_upload_fileprice = $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price'];
									$fme_upload_file_total_discount = ( $fme_upload_fileprice - $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_discount_price'] );
									$fme_upload_files_dicounted_cart_val = floatval($fme_upload_file_total_discount-$fme_upload_fileprice);
								}
								if ('' != $fme_upload_files_dicounted_cart_val) {
									$cart->add_fee($value['fme_upload_files_cartfilename'] . '(discount:' . esc_attr($fme_upload_files_dicounted_cart_val) . '%)', $fme_upload_file_total_discount);
								} else {
									$cart->add_fee($value['fme_upload_files_cartfilename'], $fme_upload_file_total_discount);
								}
							}
						} else {
							if ('' == $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price']) {
								$cart->add_fee($value['fme_upload_files_cartfilename'], $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price']);
							} else {
								$price = $fme_upload_files_cartpage_rulee[$key]['fme_uploadfiles_price'];
								$cart->add_fee($value['fme_upload_files_cartfilename'], $price );
							}
							
						}
					} 

				}
			}

			if ('' != $fme_upload_files_checkout_after_note_file_sessions) {
				foreach ($fme_upload_files_checkout_after_note_file_sessions as $key => $value) {
					if (isset($value['fme_upload_files_checkoutafternotesfilename']) && '' != $value['fme_upload_files_checkoutafternotesfilename']) {
						if (''!= $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_type'] && '' != $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_price']) {
							if ('' == $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price']) {
								$cart->add_fee($value['fme_upload_files_checkoutafternotesfilename'], $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price']);
							} else {
								if ('Fme_upload_files_percentage' == $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_type']) {
									$fme_upload_fileprice = $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price'];
									$fme_upload_file_total_discount = ( $fme_upload_fileprice * $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_price'] )/100;
									$fme_upload_file_total_discount = floatval($fme_upload_fileprice)-floatval($fme_upload_file_total_discount);
									$fme_upload_files_discounted_checkout_val = floatval($fme_upload_file_total_discount-$fme_upload_fileprice);
								} else if ('Fme_upload_files_fixed' == $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_type']) {
									$fme_upload_fileprice = $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price'];
									$fme_upload_file_total_discount = ( $fme_upload_fileprice - $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_discount_price'] );
									$fme_upload_files_discounted_checkout_val = floatval($fme_upload_file_total_discount-$fme_upload_fileprice);
								}
								if ('' != $fme_upload_files_discounted_checkout_val) {
									$cart->add_fee($value['fme_upload_files_checkoutafternotesfilename'] . '(discount:' . esc_attr($fme_upload_files_discounted_checkout_val) . '%)', $fme_upload_file_total_discount);
								} else {
									$cart->add_fee($value['fme_upload_files_checkoutafternotesfilename'], $fme_upload_file_total_discount);
								}
							}
						} else {
							if ('' == $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price']) {
								$cart->add_fee($value['fme_upload_files_checkoutafternotesfilename'], $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price']);
							} else {
								$price = $fme_upload_files_checkout_after_notes_page_rulee[$key]['fme_uploadfiles_price'];
								$cart->add_fee($value['fme_upload_files_checkoutafternotesfilename'], $price );
							}
							
						}
					} 
				}
			}
		}
		
		public function fme_upload_files_extra_price_add_custom_price( $cart ) {
			foreach ( $cart->get_cart() as $key => $cart_item ) {
				if (isset($cart_item['fme_upload_file_data'])) {	
					$fme_upload_file_subototal_price_arr = array();
					foreach ($cart_item['fme_upload_file_data'] as $key => $value) {
							
						if ('' != $value['fme_upload_files_discount_type'] && '' != $value['fme_upload_files_discount_price']) {		
							if ('Fme_upload_files_percentage' == $value['fme_upload_files_discount_type']) {
								$fme_upload_files_price = $value['fme_upload_files_price'];
								$fme_upload_file_discount_price = $value['fme_upload_files_discount_price'];
								$fme_upload_files_total_discount = ( $fme_upload_files_price * $fme_upload_file_discount_price )/100;
								$fme_upload_files_price = $fme_upload_files_price-$fme_upload_files_total_discount;
						 
							} else if ('Fme_upload_files_fixed' == $value['fme_upload_files_discount_type']) {
								$fme_upload_files_price = $value['fme_upload_files_price'];
								$fme_upload_file_discount_price = $value['fme_upload_files_discount_price'];
								$fme_upload_files_price = ( $fme_upload_files_price - $fme_upload_file_discount_price );
							} 
						} else {
							$fme_upload_files_price = $value['fme_upload_files_price'];
						}
						array_push($fme_upload_file_subototal_price_arr, $fme_upload_files_price);
					}
					$fme_uploadfile_cart_key = $cart_item['key'];
					if ( isset( WC()->cart->cart_contents[ $fme_uploadfile_cart_key ] ) ) {
						$fme_upload_file_subtotal = array_sum($fme_upload_file_subototal_price_arr);
						$fme_upload_file_product = wc_get_product( $cart_item['product_id'] );
						$product_type = $fme_upload_file_product->get_type();
						if ('simple'==$product_type) {
							$fme_product_price_val = get_post_meta($cart_item['product_id'], '_sale_price', true);
							if ('' == $fme_product_price_val || 0 == $fme_product_price_val) {
								$fme_product_price_val= get_post_meta($cart_item['product_id'], '_regular_price', true);
							}
						} else if ('variable'==$product_type) {

							$fme_product_price_val = get_post_meta($cart_item['variation_id'], '_sale_price', true);
							if ('' == $fme_product_price_val || 0 == $fme_product_price_val) {
								$fme_product_price_val= get_post_meta($cart_item['variation_id'], '_regular_price', true);
							}
						}
						if ('fme_upload_files_multiple_disable' == $value['fme_multiple_by_quantity']) {
							$quantity = $cart_item['quantity'];
							$extra = $fme_upload_file_subtotal/$quantity;
							$fme_product_total  = $fme_product_price_val + $extra;
						} else {
							$quantity = $cart_item['quantity'];
							$extra = $fme_upload_file_subtotal;
							$fme_product_total  = $fme_product_price_val + $extra;
						}
						$cart_item['data']->set_price($fme_product_total);
					}
				}
			}
		}

		public function fme_upload_files_display_cart_items_custom_price_details( $fme_upload_file_price, $fme_upload_file_cart_item, $cart_item_key ) {
			if (isset($fme_upload_file_cart_item['fme_upload_file_data'])) {
				$fme_upload_files_price_arr = array();
				$fme_upload_files_discount_array = array();
				foreach ($fme_upload_file_cart_item['fme_upload_file_data'] as $key => $value) {	 
					if ('' != $value['fme_upload_files_name']) {
						if ('' != $value['fme_upload_files_discount_type'] && '' != $value['fme_upload_files_discount_price']) {		
							if ('Fme_upload_files_percentage' == $value['fme_upload_files_discount_type']) {
								$fme_upload_files_price = $value['fme_upload_files_price'];
								$discount_price = $value['fme_upload_files_discount_price'];
								$total_discount = ( $fme_upload_files_price * $discount_price )/100;
								$fme_upload_files_price = $fme_upload_files_price-$total_discount;
							} else if ('Fme_upload_files_fixed' == $value['fme_upload_files_discount_type']) {
								$fme_upload_files_price = $value['fme_upload_files_price'];
								$discount_price = $value['fme_upload_files_discount_price'];
								$fme_upload_files_price = ( $fme_upload_files_price - $discount_price );
								$total_discount = 100 - ( 100 * ( $fme_upload_files_price-$discount_price ) / $fme_upload_files_price ) ;
							} 
						} else {
							$fme_upload_files_price = $value['fme_upload_files_price'];
						}
						array_push($fme_upload_files_price_arr, $fme_upload_files_price);
						array_push($fme_upload_files_discount_array, $value['fme_upload_files_price']);
					}
				}
				$fme_upload_files_cart_key = $cart_item_key;
				if ( isset( WC()->cart->cart_contents[ $fme_upload_files_cart_key ] ) ) {
					$fme_uploadfile_price = array_sum($fme_upload_files_price_arr);
					$fme_uploadfiles_price = array_sum($fme_upload_files_discount_array);
					$fme_uploadfile_discount_val = floatval($fme_uploadfiles_price)-floatval($fme_uploadfile_price);
					if (!empty($fme_uploadfile_discount_val)) {
						
						if ('Fme_upload_files_fixed' == $value['fme_upload_files_discount_type']) {
							$fme_upload_file_discounted_val = '<b>' . esc_html__('Discount :', 'Fme_Upload_Files') . '</b>(' . $fme_uploadfile_discount_val . ')';
						} else {

							$fme_upload_file_discounted_val = '<b>' . esc_html__('Discount :', 'Fme_Upload_Files') . '</b>(' . $fme_uploadfile_discount_val . '%)';
						}
						
					} else {
						$fme_upload_file_discounted_val = '';
					}
					if (!empty($fme_uploadfile_price)) {
						$fme_uploadfile_final_price = '<b>' . esc_html__('Files Prices:', 'Fme_Upload_Files') . '</b>' . wc_price($fme_uploadfile_price);
					}
					$product = $fme_upload_file_cart_item['data'];
					$fme_uploadfiles_product = wc_get_product( $fme_upload_file_cart_item['product_id'] );
					
					$product_type = $fme_uploadfiles_product->get_type();
					if ('simple'==$product_type) {
						$fme_product_price_val = get_post_meta($fme_upload_file_cart_item['product_id'], '_sale_price', true);
						if ('' == $fme_product_price_val || 0 == $fme_product_price_val) {
							$fme_product_price_val= get_post_meta($fme_upload_file_cart_item['product_id'], '_regular_price', true);
						}
					} else if ( 'variable'==$product_type ) {

						$fme_product_price_val = get_post_meta($fme_upload_file_cart_item['variation_id'], '_sale_price', true);
						if ('' == $fme_product_price_val || 0 == $fme_product_price_val) {
							$fme_product_price_val= get_post_meta($fme_upload_file_cart_item['variation_id'], '_regular_price', true);
						}
					}
					$fme_upload_files_product_price = $fme_product_price_val;
					$fme_upload_file_price  = wc_price($fme_upload_files_product_price);
					$fme_upload_file_price .= '<br>' . $fme_uploadfile_final_price;
					$fme_upload_file_price .= '<br>' . $fme_upload_file_discounted_val;

				}

			}

			return $fme_upload_file_price;
		}
		  
		public function Fme_upload_Files_scripts_front() {
			wp_enqueue_script('jquery');
			wp_enqueue_style( 'fme_front_css', plugins_url( 'assets/css/Upload_Files_template.css', __FILE__ ), false , 1.0 );
			wp_enqueue_script( 	'fme_upload_file_front_js', plugins_url( 'assets/js/fme_front_upload_file.js', __FILE__ ), false, 1.0);
			$fme_ewcpm_data = array(
				'admin_url' => admin_url('admin-ajax.php'),
			);
			wp_localize_script('fme_upload_file_front_js', 'ewcpm_php_vars', $fme_ewcpm_data);
			wp_localize_script('fme_upload_file_front_js', 'ajax_url_add_pq', array('ajax_url_add_pq_data' => admin_url('admin-ajax.php')));

		}

		public function fme_upload_file_Uploader( $fme_count, $fme_file_extension, $fme_file_type, $fme_id_name, $fme_filesize_type ) {     

			if ('fme_cart_file' == $fme_id_name) {
				?>
				<ul id="fme_upload_files_preview_cart_file">
					<li>
						<input class="fme_upload_file" id="<?php echo esc_attr($fme_id_name) . esc_attr($fme_count); ?>" onchange = "fme_upload_files_product_page('<?php echo esc_attr($fme_file_extension); ?>', '<?php echo esc_attr($fme_count); ?>', '<?php echo esc_attr($fme_file_type); ?>','<?php echo esc_attr($fme_id_name); ?>', '<?php echo esc_attr(str_replace('fme_', '', $fme_filesize_type)); ?>')" name="fileToUpload[]" type="file">

						<?php 
						$fme_upload_file_cart_file_sessions= WC()->session->get('fme_upload_files_cart_file_sessions' ); 
						if (!empty($fme_upload_file_cart_file_sessions)) { 
							?>
							<span>
							<?php echo esc_attr($fme_upload_file_cart_file_sessions[$fme_count]['fme_upload_files_cartfilename']); ?>
							</span>	
							<?php if ('' != $fme_upload_file_cart_file_sessions[$fme_count]['fme_upload_files_cartfilename']) { ?>
							<a onclick="fme_upload_file_delete_cart_file('<?php echo esc_attr($fme_count); ?>','<?php echo esc_attr($fme_upload_file_cart_file_sessions[$fme_count]['fme_upload_files_cartfilename']); ?>','<?php echo esc_attr($fme_upload_file_cart_file_sessions[$fme_count]['fme_upload_files_cart_id_name']); ?>')" class="btn btn-primary fme_view_cart_file" id="fme_cart_file_view<?php echo esc_attr($$fme_count); ?>">
							   <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								<span><strong><img class="fme_upload_files_iconpreviewcartpage" src="<?php echo esc_url(FMEUF_URL) . 'front/images/delete.png'; ?>" id="fme_iconpreviewcartpage<?php echo esc_attr($fme_count); ?>"></strong></span></a>
							<a href="<?php echo esc_attr($fme_upload_file_cart_file_sessions[$fme_count]['fme_upload_files_cartfile_url']); ?>" class="fme_upload_files_iconpreviewcartpage" target="_blank" typee="image" id="fme_previewlink<?php echo esc_attr($fme_count); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'front/images/previewimage.png'; ?>" id="fme_upload_files_iconpreview"></a>	
							<?php } ?>
							<?php
						}	
						?>
					</li>
				</ul>
				<?php
			} else if ('fme_upload_files_product_file' == $fme_id_name) {
				?>
				<ul id="fme_upload_files_preview_product_file">
					<li>
					<input class="fme_upload_file" id="<?php echo esc_attr($fme_id_name) . esc_attr($fme_count); ?>" onchange = "fme_upload_files_product_page('<?php echo esc_attr($fme_file_extension); ?>', '<?php echo esc_attr($fme_count); ?>', '<?php echo esc_attr($fme_file_type); ?>','<?php echo esc_attr($fme_id_name); ?>', '<?php echo esc_attr(str_replace('fme_', '', $fme_filesize_type)); ?>')" name="fileToUpload[]" type="file">
					<a href="" class="fme_preview_image" target="_blank" typee="image" id="fme_previewlink<?php echo esc_attr($fme_count); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'front/images/previewimage.png'; ?>" id="fme_upload_files_iconpreview"></a>
					<a class="fme_delete_img" onclick="fme_upload_file_delete_preview_image('<?php echo esc_attr($fme_count); ?>','<?php echo esc_attr($fme_id_name); ?>')" typee="image" id="fme_deleteimage<?php echo esc_attr($fme_count); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'front/images/delete.png'; ?>" id="fme_upload_files_iconpreview"></a></a>
					</li>
				</ul>
				<?php
			} else if ('fme_checkout_notes_file' == $fme_id_name) {
				?>
				<ul id="fme_upload_files_preview_cart_file">
					<li>
						<input class="fme_upload_file" id="<?php echo esc_attr($fme_id_name) . esc_attr($fme_count); ?>" onchange = "fme_upload_files_product_page('<?php echo esc_attr($fme_file_extension); ?>', '<?php echo esc_attr($fme_count); ?>', '<?php echo esc_attr($fme_file_type); ?>','<?php echo esc_attr($fme_id_name); ?>', '<?php echo esc_attr(str_replace('fme_', '', $fme_filesize_type)); ?>')" name="fileToUpload[]" type="file">
						<span id="fme_checkout<?php echo esc_attr($fme_count); ?>"></span>
						<?php 
						$fme_upload_files_checkout_after_note_file_sessions= WC()->session->get('fme_upload_files_checkout_after_note_file_sessions' ); 
						if (!empty($fme_upload_files_checkout_after_note_file_sessions)) { 
							?>
						<span id="fme_checkout_filename<?php echo esc_attr($fme_count); ?>">
							<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_count]['fme_upload_files_checkoutafternotesfilename']); ?>
						</span>	
							<?php if ('' != $fme_upload_files_checkout_after_note_file_sessions[$fme_count]['fme_upload_files_checkoutafternotesfilename']) { ?>
							<a onclick="fme_upload_file_delete_cart_file('<?php echo esc_attr($fme_count); ?>','<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_count]['fme_upload_files_checkoutafternotesfilename']); ?>', '<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_count]['fme_upload_files_checkoutafternote_id_name']); ?>')" class="btn btn-primary fme_view_checkout_file" id="fme_checkout_file_view<?php echo esc_attr($fme_count); ?>">
							   <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								<span><strong><img class="fme_upload_files_iconpreviewcheckoutpage" src="<?php echo esc_url(FMEUF_URL) . 'front/images/delete.png'; ?>" id="fme_iconpreviewcheckoutpage<?php echo esc_attr($fme_count); ?>"></strong></span>            
							</a>
							<a href="<?php echo esc_attr($fme_upload_files_checkout_after_note_file_sessions[$fme_count]['fme_upload_files_checkoutafternotesfile_url']); ?>" class="fme_upload_files_iconpreview_checkout_page" target="_blank" typee="image" id="fme_preview_checkout_link<?php echo esc_attr($fme_count); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'front/images/previewimage.png'; ?>" class="fme_upload_files_iconpreview_checkout_page" id="fme_checkout_preview<?php echo esc_attr($fme_count); ?>"></a>	
							<?php } ?>
						<?php } ?>	
					</li>
				</ul>	
				<?php
			}
		}

		public function fme_uploadfile_add_text_to_cart_item( $fme_upload_file_cart_item_data, $product_id, $variation_id ) {
			$fme_upload_file_product_page_rule  = $this->Fme_get_Upload_files_rules();
			foreach ($fme_upload_file_product_page_rule as $key => $fme_rule) {
				if ('fme_upload_files_product_page'== $fme_rule['fme_upload_files_display_on_position']) {
					$fme_upload_file_productpage_rulee  = $fme_rule['fme_upload_files_multiple_files_limit'];
					$fme_multiple_by_quantity = $fme_rule['fme_multiple_by_quantity'];
				} else {
					continue;
				}
			}
			if ( empty( $_FILES ) ) {
				return $fme_upload_file_cart_item_data;
			}
			$fme_uploadfile_array = array();
			foreach ($_FILES as $file_val) {
				foreach ($file_val['name'] as $key => $value) {
					if ('' != $value) {
						$fme_uploadfile_array[$key] = array(
							'fme_upload_files_name' => $file_val['name'][$key],
							'fme_upload_files_type' => $file_val['type'][$key],
							'fme_upload_files_tmp_name'=> $file_val['tmp_name'][$key],
							'fme_upload_files_size' => $file_val['size'][$key],
							'fme_upload_files_price' => $fme_upload_file_productpage_rulee[$key]['fme_uploadfiles_price'],
							'fme_upload_files_discount_type' => $fme_upload_file_productpage_rulee[$key]['fme_uploadfiles_discount_type'],
							'fme_upload_files_discount_price' => $fme_upload_file_productpage_rulee[$key]['fme_uploadfiles_discount_price'],
							'fme_multiple_by_quantity' => $fme_multiple_by_quantity
						);

						$fme_upload_filename = $file_val['name'][$key];
						if (!file_exists( $fme_upload_filename ) ) {
							move_uploaded_file($file_val['tmp_name'][$key], FMEUF_PLUGIN_DIR . '/uploadsfiles/' . $fme_upload_filename); 
						}	
					}	
					
				}
				$fme_upload_file_cart_item_data['fme_upload_file_data'] = $fme_uploadfile_array;	
			} 
			return $fme_upload_file_cart_item_data;		

		}

		public function fme_uploadfile_get_cart_item_in_cart( $fme_upload_file_item_data, $fme_upload_file_cart_item ) {

			if (isset($fme_upload_file_cart_item['fme_upload_file_data'])) {

				foreach ($fme_upload_file_cart_item['fme_upload_file_data'] as $key => $cart_file_val) {
					if (empty($cart_file_val)) {
						return $fme_upload_file_item_data;
					}
					if ( isset($cart_file_val['fme_upload_files_name']) && ! empty($cart_file_val['fme_upload_files_name']) ) {
			
						$fme_upload_files_data = array(
							'fme_uploadfiles_filename'=> $cart_file_val['fme_upload_files_name'],
							'fme_uploadfiles_file_price' => $cart_file_val['fme_upload_files_price'],
							'fme_uploadfiles_discount_type' => $cart_file_val['fme_upload_files_discount_type'],
							'fme_uploadfiles_discount_price' => $cart_file_val['fme_upload_files_discount_price']
						);


						if ('' != $fme_upload_files_data['fme_uploadfiles_discount_type'] &&  '' !=$fme_upload_files_data['fme_uploadfiles_discount_price']) {

							if ('Fme_upload_files_percentage' == $fme_upload_files_data['fme_uploadfiles_discount_type']) {

								$fme_uploadfile_value = '<b>' . esc_html__('filename:', 'Fme_Upload_Files') . '</b>' . wc_clean( $fme_upload_files_data['fme_uploadfiles_filename']) . '</br><b>' . esc_html__('file price:', 'Fme_Upload_Files') . '</b>' . wc_clean( wc_price($fme_upload_files_data['fme_uploadfiles_file_price'])) . '</br><b>' . esc_html__('Discount:', 'Fme_Upload_Files') . '</b>' . wc_clean(str_replace('Fme_upload_files_', '', $fme_upload_files_data['fme_uploadfiles_discount_type']) . '(' . $fme_upload_files_data['fme_uploadfiles_discount_price'] . '%)');

							} else {

								$fme_uploadfile_value = '<b>' . esc_html__('filename:', 'Fme_Upload_Files') . '</b>' . wc_clean( $fme_upload_files_data['fme_uploadfiles_filename']) . '</br><b>' . esc_html__('file price:', 'Fme_Upload_Files') . '</b>' . wc_clean( wc_price($fme_upload_files_data['fme_uploadfiles_file_price'])) . '</br><b>' . esc_html__('Discount:', 'Fme_Upload_Files') . '</b>' . wc_clean(str_replace('Fme_upload_files_', '', $fme_upload_files_data['fme_uploadfiles_discount_type']) . '(' . $fme_upload_files_data['fme_uploadfiles_discount_price'] . ')');
							}

							
						} else {

							$fme_uploadfile_value = '<b>' . esc_html__('filename:', 'Fme_Upload_Files') . '</b>' . wc_clean( $fme_upload_files_data['fme_uploadfiles_filename']) . '</br><b>' . esc_html__('file price:', 'Fme_Upload_Files') . '</b>' . wc_clean( wc_price($fme_upload_files_data['fme_uploadfiles_file_price']));
						}
						$fme_file = $key+1;
						$fme_upload_file_item_data[] = array(
							'key'     => __( 'file:' . $fme_file, 'Fme_Upload_Files' ),
							'value'   => $fme_uploadfile_value,
							'display' => '',
						);
					}  	  
				}
			}

			return $fme_upload_file_item_data;
		}

		public function Fme_Upload_Files_templates() {

			global $post;
			$fme_upload_file_product_id = $post->ID;
			$fme_upload_file_terms = get_the_terms ( $fme_upload_file_product_id, 'product_cat' );
			$fme_upload_file_category_id = $fme_upload_file_terms[0]->term_id;
			$fme_upload_file_rules = $this->Fme_get_Upload_files_rules();
			$fme_upload_file_user_role = wp_get_current_user()->roles;
			$fme_upload_file_valid = false;

			foreach ($fme_upload_file_rules as $value) {

				if ('fme_upload_files_enable' == $value['fme_upload_files_enable_disable_status']) {
					
					if ('fme_upload_files_product_page' == $value['fme_upload_files_display_on_position']) {

						if ('fme_upload_files_product' == $value['fme_upload_files_selected_pc_type']) {
							$fme_upload_files_needle_id = $fme_upload_file_product_id;
						} else if ('fme_upload_files_category' ==$value['fme_upload_files_selected_pc_type']) {
							$fme_upload_files_needle_id = $fme_upload_file_category_id;
						}
						if (!empty($value['fme_upload_files_selected_pc'])) {

							if (in_array($fme_upload_files_needle_id, $value['fme_upload_files_selected_pc'])) {
								if (is_single()) {
									if (!empty($value['fme_upload_files_selected_user_role'])) {
										if (!empty(array_intersect($fme_upload_file_user_role, $value['fme_upload_files_selected_user_role']))) {
												$fme_upload_file_valid = true;
										} else {
											return false;
										}
									} else {
										$fme_upload_file_valid = true;
									}
								} else {
									return false;
								}
							} else {
								$fme_upload_file_valid = false;
							}
						} else {
							if (!empty($value['fme_upload_files_selected_user_role'])) {
								if (!empty(array_intersect($fme_upload_file_user_role, $value['fme_upload_files_selected_user_role']))) {
									$fme_upload_file_valid = true;
								} else {
									return false;
								}
							} else {
								$fme_upload_file_valid = true;
							}
						}

					} else if ('fme_upload_files_cart_page' == $value['fme_upload_files_display_on_position'] || 'fme_upload_files_checkout_page_after_notes' == $value['fme_upload_files_display_on_position']) {

						$fme_upload_file_selectedpc_array = array();
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$fme_upload_file_product_id = $cart_item['product_id'];
							$fme_upload_file_terms = get_the_terms ($fme_upload_file_product_id, 'product_cat' );
							$fme_upload_file_category_id = $fme_upload_file_terms[0]->term_id;
							if ('fme_upload_files_product' == $value['fme_upload_files_selected_pc_type']) {
								array_push($fme_upload_file_selectedpc_array, $fme_upload_file_product_id);
							} else {
								array_push($fme_upload_file_selectedpc_array, $fme_upload_file_category_id);
							}
						}

						if (empty($value['fme_upload_files_selected_pc'])) {
							$fme_upload_file_valid = true;
						} else {
							if (empty(array_intersect($fme_upload_file_selectedpc_array, $value['fme_upload_files_selected_pc']))) {
								continue;
							} else {
								$fme_upload_file_valid = true;
							}
						}

						if (!empty($value['fme_upload_files_selected_user_role'])) {
							if (!empty(array_intersect($fme_upload_file_user_role, $value['fme_upload_files_selected_user_role']))) {
									$fme_upload_file_valid = true;
							} else {
								return false;
							}
						} else {
							$fme_upload_file_valid = true;
						}
					}

					if (false == $fme_upload_file_valid) {
						continue;
					} 
					
					if ('fme_upload_files_product_page'== $value['fme_upload_files_display_on_position']) {
						add_action( 'woocommerce_before_add_to_cart_button', function() use ( $value ) {
							if ('fme_upload_files_multiple' == $value['fme_upload_files_selected_files_type']) {	

								if ('' != $value['fme_upload_files_multiple_files_limit']) {
								
									foreach ($value['fme_upload_files_multiple_files_limit'] as $key => $values) {
										$this->fme_upload_file_Uploader($key, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_upload_files_product_file', $value['fme_upload_files_size_type']);
									}								
								}
							} else if ('fme_upload_files_single' == $value['fme_upload_files_selected_files_type']) {
								if ('' != $value['fme_upload_files_multiple_files_limit']) {
									$fme_i = 0;
									$this->fme_upload_file_Uploader($fme_i, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_upload_files_product_file', $value['fme_upload_files_size_type']);
								}

							}

						});

					} else if ('fme_upload_files_cart_page' == $value['fme_upload_files_display_on_position']) {
						add_action( 'woocommerce_after_cart_table', function() use ( $value ) {
							
							if ('fme_upload_files_multiple' == $value['fme_upload_files_selected_files_type']) {
								$arr = array();
								if ('' != $value['fme_upload_files_multiple_files_limit']) {
									foreach ($value['fme_upload_files_multiple_files_limit'] as $key => $values) {
										$this->fme_upload_file_Uploader($key, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_cart_file', $value['fme_upload_files_size_type']);
									}
								}
							} else if ('fme_upload_files_single' == $value['fme_upload_files_selected_files_type']) {

								if ('' != $value['fme_upload_files_multiple_files_limit']) {
									$fme_i = 0;
									$this->fme_upload_file_Uploader($fme_i, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_cart_file', $value['fme_upload_files_size_type']);	
								}
							}
						}); 

					} else if ('fme_upload_files_checkout_page_after_notes' == $value['fme_upload_files_display_on_position']) {

						add_action( 'woocommerce_after_order_notes', function() use ( $value ) {
							
							if ('fme_upload_files_multiple' == $value['fme_upload_files_selected_files_type']) {

								if ('' != $value['fme_upload_files_multiple_files_limit']) {

									for ($fme_i=0; $fme_i < count($value['fme_upload_files_multiple_files_limit']) ; $fme_i++) {
										$this->fme_upload_file_Uploader($fme_i, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_checkout_notes_file', $value['fme_upload_files_size_type']);
									}
								}
							} else if ('fme_upload_files_single' == $value['fme_upload_files_selected_files_type']) {

								if ('' != $value['fme_upload_files_multiple_files_limit']) {
									$fme_i = 0;
									$this->fme_upload_file_Uploader($fme_i, $value['fme_allowed_file_types'], $value['fme_maximum_uploadfile_size'], 'fme_checkout_notes_file', $value['fme_upload_files_size_type']);	
								}
							}
						});

					} 
				}
				
				
			}
		}

		public function Fme_get_Upload_files_rules() {
			global $post;
			global $woocommerce;
			$fme_upload_files_args = array(
				'post_type'=> 'fme_upload_files',
				'orderby'    => 'ID',
				'post_status' => 'publish',
				'order'    => 'ASC',
				'fields'	=> 'ids',
				'posts_per_page' => -1 // this will retrive all the post that is published 
			);
			$fme_upload_files_get_rules = new WP_Query( $fme_upload_files_args );	
			$fme_upload_Files_rules_array = array();
			foreach ($fme_upload_files_get_rules->get_posts() as $key => $fme_upload_files_postid) {
				$fme_get_status =  get_post_meta($fme_upload_files_postid, 'fme_enable_disable_settings', true);
				$fme_upload_files_display_on_position =  get_post_meta($fme_upload_files_postid, 'fme_display_on_values', true);
				$fme_upload_files_selected_files_type =  get_post_meta($fme_upload_files_postid, 'fme_selection_files', true);
				$fme_upload_files_multival = get_post_meta($fme_upload_files_postid , 'fme_multiple_files_limit', true);
				$fme_upload_files_allowed_file_type = get_post_meta($fme_upload_files_postid, 'fme_allowed_file_types', true); 
				$fme_upload_files_size = get_post_meta($fme_upload_files_postid, 'fme_file_size', true); 
				$fme_upload_files_maximum_file_size = get_post_meta($fme_upload_files_postid, 'fme_maximum_uploadsize', true); 
				$fme_upload_files_selected_pc_type = get_post_meta($fme_upload_files_postid, 'fme_selected_product_category', true); 
				$fme_upload_files_selected_pc = get_post_meta($fme_upload_files_postid, 'fme_selected_items', true);
				$fme_upload_file_user_roles = get_post_meta($fme_upload_files_postid, 'fme_selected_user_role', true); 

				$fme_multiple_by_quantity = get_post_meta($fme_upload_files_postid, 'fme_multiple_by_quantity', true);

				$fme_upload_files_rules = array(
					'fme_upload_files_enable_disable_status' => $fme_get_status,
					'fme_upload_files_display_on_position' => $fme_upload_files_display_on_position,
					'fme_upload_files_selected_files_type' => $fme_upload_files_selected_files_type,
					'fme_upload_files_multiple_files_limit' => $fme_upload_files_multival,
					'fme_allowed_file_types' => $fme_upload_files_allowed_file_type,
					'fme_upload_files_size_type' => $fme_upload_files_size,
					'fme_maximum_uploadfile_size' => $fme_upload_files_maximum_file_size,
					'fme_upload_files_selected_pc_type' => $fme_upload_files_selected_pc_type,
					'fme_upload_files_selected_pc' => $fme_upload_files_selected_pc,
					'fme_upload_files_selected_user_role' => $fme_upload_file_user_roles,
					'fme_multiple_by_quantity' => $fme_multiple_by_quantity
				);

				array_push($fme_upload_Files_rules_array, $fme_upload_files_rules);
			}

			return $fme_upload_Files_rules_array;
		}
	}

	new Fme_Upload_Files_Front();
}
?>
