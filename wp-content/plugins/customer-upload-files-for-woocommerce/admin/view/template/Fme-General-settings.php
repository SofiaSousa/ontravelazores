 <h4 class="fme_upload_files_heading4">
	<?php echo esc_html__('Description, file types restriction, etc.' , 'Fme_Upload_Files'); ?>
 </h4>
<div class="container-fluid">
	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Enable/Disable', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
			<select id="fme_upload_files_enable_disable_setting">
				<option value=""><?php echo esc_html__('Choose visibility', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_enable"><?php echo esc_html__('Enable', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_disable"><?php echo esc_html__('Disable', 'Fme_Upload_Files'); ?></option>
			</select>
		</div>
	 </div>
	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label  id="fme_upload_files_label"><?php echo esc_html__('Display On', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-8">
			<input type="radio" id="fme-product-page" class="fme_upload_files_radio" name="fme-radio-select-display-on" value="fme_upload_files_product_page">
			<label for="fme-product-page" id="fme_upload_files_radio"><?php echo esc_html__('Product Page', 'Fme_Upload_Files'); ?></label>
			<br/>
			<input type="radio" id="fme-cart-page" class="fme_upload_files_radio" name="fme-radio-select-display-on" value="fme_upload_files_cart_page">
			<label for="fme-cart-page" id="fme_upload_files_radio">
				<?php echo esc_html__('Cart Page', 'Fme_Upload_Files'); ?>
			</label>
			<br/>
			<input type="radio" id="fme-checkout-page-after-notes" class="fme_upload_files_radio" name="fme-radio-select-display-on" value="fme_upload_files_checkout_page_after_notes">
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
			<select id="FME_selection_files" onchange="fme_upload_files_upload_selection_files('fme_create');">
				<option value=""><?php echo esc_html__('Select one', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_single"><?php echo esc_html__('Single', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_multiple"><?php echo esc_html__('Multiple', 'Fme_Upload_Files'); ?></option>
			</select>
				<span id="fmemultiplefiles">
					<table class="table table-responsive" id="fme-upload-file-multiple-table">
					<thead>
						<tr>	
							<td><?php echo esc_html__('Price:', 'Fme_Upload_Files'); ?></td>
							<td colspan="4"><?php echo esc_html__('Discount Type:', 'Fme_Upload_Files'); ?></td>
							<td colspan="1"><?php echo esc_html__('Discount value:', 'Fme_Upload_Files'); ?></td>
							<td colspan="3"><?php echo esc_html__('Action:', 'Fme_Upload_Files'); ?></td>
						</tr>
					</thead>
					<tbody id="TextBoxContainer">
					</tbody>
					<tfoot>
					  <tr>
						<th colspan="5">
							<img src="<?php echo esc_url(FMEUF_URL) . 'admin/images/fme-flat-plus-icon.png'; ?>" id="fme_upload_files_plus_icon_multiple" onclick="fme_uploadfile_multiplefile('fme_create');">
						</th>
					  </tr>
					</tfoot>
					</table>
				</span>
				<div id="Fme_upload_files_price_single">
					<div class="row" id="fme_upload_files_FormSettingsupload">
						<div class="col-xs-4 col-md-3 col-md-offset-1">
							<label id="fme_upload_files_label"><?php echo esc_html__('Price:', 'Fme_Upload_Files'); ?></label>
						</div>
						<div class="col-xs-4 col-md-6">
							<input type="number" name="fmepriceuploadfile" value="" id="fmepriceuploadfile">
						</div>
					</div>
					<div class="row" id="fme_upload_files_Discount">
						<div class="col-xs-4 col-md-3 col-md-offset-1">
							<label id="fme_upload_files_label"><?php echo esc_html__('Discount Type:', 'Fme_Upload_Files'); ?></label>
						</div>
						<div class="col-xs-4 col-md-6">
							<select id="fmeDiscounttype">
								<option value=""><?php echo esc_html__('Select Discount Type', 'Fme_Upload_Files'); ?></option>
								<option value="Fme_upload_files_fixed"><?php echo esc_html__('Fixed', 'Fme_Upload_Files'); ?></option>
								<option value="Fme_upload_files_percentage"><?php echo esc_html__('Percentage', 'Fme_Upload_Files'); ?></option>
							</select>
						</div>
					</div>
					<div class="row" id="fme_upload_files_Discountval">
						<div class="col-xs-4 col-md-3 col-md-offset-1">
							<label id="fme_upload_files_label"><?php echo esc_html__('Discount value:', 'Fme_Upload_Files'); ?></label>
						</div>
						<div class="col-xs-4 col-md-6">
							<input type="number" min="0" name="fmeDiscountvalue" value="" id="fmeDiscountvalue" oninput="validity.valid||(value='');">
						</div>
				</div>
			</div>
			</span>
		</div>
	</div>

	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Allowed File Types:', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
			<input type="text" class="form-control Fme-file-type" name="Fme_allowed_file_types" id="Fme_allowed_file_types" value="">
			<span class="fme_upload_files_description"><?php echo esc_html__('Specify which file types are allowed for uploading, seperate by commas.', 'Fme_Upload_Files'); ?>
			</span>
		</div>
	</div>


	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('File Size:', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
			<select id="fme_upload_files_file_size">
				<option value="fme_upload_files_KB"><?php echo esc_html__('KB', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_MB"><?php echo esc_html__('MB', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_GB"><?php echo esc_html__('GB', 'Fme_Upload_Files'); ?></option>
			</select>
		</div>
	 </div>

	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Maximum Upload Size:', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
			<input type="number" min="0" class="form-control Fme-upload-size" name="Fme-maximum-uploadsize" value="" id="Fme-maximum-uploadsize" oninput="validity.valid||(value='');">
			<span class="fme_upload_files_description"><?php echo esc_html__('Enter uploaded file size.', 'Fme_Upload_Files'); ?>
			</span><br/>
		</div>
	</div>

	<div class="row Multiply_by_Quantity" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Multiply By Quantity:', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-4">
			<select id="fme_upload_files_multiple_by_qunatity">
				<option value=""><?php echo esc_html__('Choose one', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_multiple_enable"><?php echo esc_html__('Enable', 'Fme_Upload_Files'); ?></option>
				<option value="fme_upload_files_multiple_disable"><?php echo esc_html__('Disable', 'Fme_Upload_Files'); ?></option>
			</select><br/>
			<span class="fme_upload_files_description"><?php echo esc_html__('Enble to Multiply uploaded files by Quantity.', 'Fme_Upload_Files'); ?>
			</span><br/>
		</div>
	</div>
	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Product/Category Restriction', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-5">
			<select class="form-control fmeproductcategory" id="fmeproductcategory" name="selectpc[]" onchange="Fme_upload_file_choosen_product_cateory('fme_create');">
				<option value=""><?php echo esc_html__('Visible for every product:', 'extCCFA'); ?></option>
				<option value="fme_upload_files_product"><?php echo esc_html__('Product', 'extCCFA'); ?></option>
				<option value="fme_upload_files_category"><?php echo esc_html__('Category', 'extCCFA'); ?></option>
			</select>
			<span class="fme_upload_files_description">
				<?php 
					echo esc_html__('Upload field can optionally visible/hidden only if the selected products are in cart/order.', 'Fme_Upload_Files'); 
				?>
			</span>
		</div>
	</div>
	<div class="row" id="fme_upload_files_Products">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Select Product', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-6">
			<?php 
			global $post;
			$fme_upload_files_uploadfiles_product = array(
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'post_type' => array( 'product')
			);
			$fme_upload_files_woo_Products = get_posts($fme_upload_files_uploadfiles_product);
			if (!empty($fme_upload_files_woo_Products)) { 
				?>
				<select class="Fme_choosen" id="Fme_files-product" multiple="multiple" name="">
					<?php
					foreach ($fme_upload_files_woo_Products as $products) {
						?>
						<option value="<?php echo esc_attr($products->ID); ?>"><?php echo filter_var($products->post_title); ?></option>
						<?php
					}

					?>
				</select>
		<?php }; ?>
		</div>
	</div>
	<div class="row" id="fme_upload_files_category">
		<div class="col-xs-4 col-md-3 col-md-offset-1">
			<label id="fme_upload_files_label"><?php echo esc_html__('Select category', 'Fme_Upload_Files'); ?></label>
		</div>
		<div class="col-xs-4 col-md-6">
			<?php 
			$fme_upload_files_woo_category = array(
				'taxonomy' => 'product_cat',
			);
			$fme_upload_files_products_categories = get_terms($fme_upload_files_woo_category);
			if (!empty($fme_upload_files_products_categories)) { 
				?>
				<select class="Fme_choosen" id="Fme_files-category" multiple="multiple" name="">
					<?php
					foreach ($fme_upload_files_products_categories as $category) {
						?>
						<option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_attr($category->name); ?></option>
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
			$fme_upload_files_default_roles = $wp_roles->get_names();
			if (!empty($fme_upload_files_default_roles)) {
				?>
				<select class="Fme_choosen" id="Fme_choosen-user-role" multiple="multiple" name="">
					<?php
					foreach ($fme_upload_files_default_roles as $key => $value) {
						?>
							<option value="<?php echo filter_var(strtolower($value)); ?>">
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
			<span class="fme_upload_files_description">
			<?php 
				echo esc_html__('Selecting at least one role will make the upload field to be visible/unvisible to that role..', 'Fme_Upload_Files'); 
			?>
			</span>
	
		</div>
	</div>
	<div class="row" id="fme_upload_files_FormSettings">
		<div class="col-xs-4 col-md-3 col-md-offset-10">
			<span id="fme_settings_loader"><img src="<?php echo esc_url(FMEUF_URL) . 'admin/images/spinner.gif'; ?>" class=""></span>
			<input type="button" name="Fme_save_settings" onclick="fme_upload_file_save_general_settings();" value="Save Settings" class="btn btn-primary">
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-xs-3">
			<span id="fme_settings_msg">
				<p><?php echo esc_html__('Save General Settings Successfully!', ' extendons_menu_cart_plugin'); ?></p>
			</span>
		</div>
	</div>
</div>
