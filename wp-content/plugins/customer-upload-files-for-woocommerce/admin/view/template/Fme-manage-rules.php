<?php
if (!defined('ABSPATH')) {
	exit;
}
global $wp_roles;
global $post;
global $woocommerce;
$Fme_args = array(
'post_type'=> 'fme_upload_files',
'orderby'    => 'ID',
'post_status' => 'publish',
'order'    => 'ASC',
'posts_per_page' => -1 // this will retrive all the post that is published 
);
$Fme_Upload_Files_rules = new WP_Query( $Fme_args );
?>
<h4 class="FMEheading4"><?php echo esc_html__('Upload Files Rules:', 'Fme_Upload_Files'); ?> </h4>
<?php 
$fme_upload_file_rule_key = 1;
if ( $Fme_Upload_Files_rules->have_posts() ) { 		
	while ( $Fme_Upload_Files_rules->have_posts() ) {
		$Fme_Upload_Files_rules->the_post(); 
		$fme_postid = get_the_ID(); 
		?>
		<div class="row" id="fme_upload_files_manage_rules">
			<div class="col-md-6">
				<label id="Fmelabel"><?php echo esc_html__('Rule:', 'Fme_Upload_Files'); ?></label>
				<span>
					<?php echo 'Rule' . esc_attr($fme_upload_file_rule_key) . ''; ?>
				</span><br/>
				<label id="Fmelabel"><?php echo esc_html__('Enable/Disable:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
				<?php 
					$fme_upload_files_get_status =  get_post_meta($fme_postid, 'fme_enable_disable_settings', true);
					$fme_upload_files_get_status =  str_replace('fme_upload_files_', '', $fme_upload_files_get_status); 
					echo esc_attr(ucfirst($fme_upload_files_get_status));
				?>
				</span><br/>
				<label id="Fmelabel"><?php echo esc_html__('Display File Position:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
				<?php 
					$fme_upload_files_display_on_position =  get_post_meta($fme_postid, 'fme_display_on_values', true);
					$fme_upload_files_display_on_position =  str_replace('fme_upload_files_', '', $fme_upload_files_display_on_position); 
					echo esc_attr(str_replace('_', ' ', ucfirst($fme_upload_files_display_on_position)));
					
				?>
				</span><br/>
				<label id="Fmelabel"><?php echo esc_html__('Files Type:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
				<?php 
					$fme_upload_file_type =  get_post_meta($fme_postid, 'fme_selection_files', true);
					$fme_upload_file_type =  str_replace('fme_upload_files_', '', $fme_upload_file_type);
					echo esc_attr(ucfirst($fme_upload_file_type));
				?>
				</span>
				<span>
				<?php 
					$fme_upload_file_multival_arr = get_post_meta($fme_postid , 'fme_multiple_files_limit', true);
					echo '(' . esc_attr(count($fme_upload_file_multival_arr)) . ')';
				?>
				<table class="table table-striped">
				<thead>
				  <tr>
					<th><?php echo esc_html__('File:', 'Fme_Upload_Files'); ?></th>
					<th><?php echo esc_html__('Price:', 'Fme_Upload_Files'); ?></th>
					<th><?php echo esc_html__('Discount Type:', 'Fme_Upload_Files'); ?></th>
					<th><?php echo esc_html__('Discount Price:', 'Fme_Upload_Files'); ?></th>
				  </tr>
				</thead>
				<tbody>
					<?php 
					foreach ($fme_upload_file_multival_arr as $key => $value) {
						?>
					<tr>
						<td><?php echo esc_attr($key+1); ?></td>
						<td><?php echo esc_attr($value['fme_uploadfiles_price']); ?></td>
						<td><?php echo esc_attr(str_replace('Fme_upload_files_', '', $value['fme_uploadfiles_discount_type'])); ?></td>
						<td>
							<?php
							if ('' != $value['fme_uploadfiles_discount_price']) {
								echo esc_attr($value['fme_uploadfiles_discount_price'] . '%'); 
							} 
							?>
						</td>
					</tr>
						<?php
					}
					?>
				  
				</tbody>
			  </table>
			</div>
			<div class="col-md-6">
				<label id="Fmelabel"><?php echo esc_html__('User Roles:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
					<?php 
					
					$fme_upload_file_user_roles = get_post_meta($fme_postid, 'fme_selected_user_role', true); 
					if (!empty($fme_upload_file_user_roles)) {
						$fme_roles = implode(',', $fme_upload_file_user_roles);
						echo esc_attr(ucfirst($fme_roles));
					}
					?>
				</span><br/>
				</span>
				<label id="Fmelabel"><?php echo esc_html__('Allowed File Type:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
					<?php 
						$fme_upload_file_allowed_file_type = get_post_meta($fme_postid, 'fme_allowed_file_types', true); 
						echo esc_attr($fme_upload_file_allowed_file_type);
					?>
				</span><br/>
				<label id="Fmelabel"><?php echo esc_html__('Maximum File Size:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
					<?php 
						$fme_upload_file_size = get_post_meta($fme_postid, 'fme_file_size', true);
						$fme_upload_file_size = str_replace('fme_', '', $fme_upload_file_size);
						$fme_upload_file_maximum_file_size = get_post_meta($fme_postid, 'fme_maximum_uploadsize', true); 
						echo esc_attr($fme_upload_file_maximum_file_size . $fme_upload_file_size);
					?>
				</span><br/>
				<label id="Fmelabel"><?php echo esc_html__('Display On:', 'Fme_Upload_Files'); ?></label>
				<span id="fme_upload_files_rules_values">
					<?php 
					$fme_upload_files_items_type_pc = get_post_meta($fme_postid, 'fme_selected_product_category', true); 
					$fme_upload_files_items_type_pc = str_replace('fme_upload_files_', '', $fme_upload_files_items_type_pc);
					if ('' == $fme_upload_files_items_type_pc) {
						echo 'All Products';
					} else {
						echo esc_attr(ucfirst($fme_upload_files_items_type_pc));
					}						
					?>
				</span><br/>
				<span>
					<?php 
					$fme_upload_files_selected_product = get_post_meta($fme_postid, 'fme_selected_items', true);
					?>
					<label id="Fmelabel"><?php echo esc_html__('Selected ' . $fme_upload_files_items_type_pc . ':', 'Fme_Upload_Files'); ?></label>
					<?php
					if ('product' == $fme_upload_files_items_type_pc ) {
						if (!empty($fme_upload_files_selected_product)) {
							foreach ($fme_upload_files_selected_product as $key => $value) {
								echo esc_attr(get_the_title($value)) . ',';
							} 
						}
					} else {
						if (!empty($fme_upload_files_selected_product)) {
							foreach ($fme_upload_files_selected_product as $key => $value) {
								$fme_upload_file_category = get_term($value);
								echo esc_attr($fme_upload_file_category->name) . ',';
							}	 
						}
					}
					?>
				</span><br/>
				<span>
					<?php 
					if ('product_page' == $fme_upload_files_display_on_position) {
						$fme_multiple_by_quantity = get_post_meta($fme_postid, 'fme_multiple_by_quantity', true);
						?>
						<label><?php echo esc_html__('Multiply by Quantity:', 'Fme_Upload_Files'); ?></label>
						<?php 
						$fme_multiple_by_quantity_val = str_replace('fme_upload_files_multiple_', '' , $fme_multiple_by_quantity); 
						echo esc_attr(ucfirst($fme_multiple_by_quantity_val));	
					}
					?>
				</span>
			</div>
			<button type="button" onclick="fme_upload_file_delete_rule(<?php echo esc_attr($fme_postid); ?>)"  id="Fme_upload_files_deletebtn"><?php echo esc_html__('Delete', 'Fme_Upload_Files'); ?></button>
			<span class="fme_upload_files_loader_d" id="fme_loader_deltete<?php echo esc_attr($fme_postid); ?>"><img src="<?php echo esc_url(FMEUF_URL) . 'admin/images/spinner.gif'; ?>" class=""></span>
			<button href="#Fme_edit_file_modal<?php echo esc_attr($fme_postid); ?>" type="button" id="Fme_upload_files_editbtn" data-toggle="modal" onclick="fme_upload_file_edit_rule(<?php echo filter_var($fme_postid); ?>);"><?php echo esc_html__('Edit Rule', 'Fme_Upload_Files'); ?></button>
			</div>
			<div class="modal fade Fme_upload_files_edit_file_modal" id="Fme_edit_file_modal<?php echo esc_attr($fme_postid); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" id="Fme_upload_files_modal_dialogue">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title" id="myModalLabel"><?php echo esc_html__('Edit Upload Files Settings', 'Fme_Upload_Files'); ?></h4>
						</div>
						<div class="modal-body fme_upload_files_editForm_upload_file" id="fme_editForm_upload_file<?php echo esc_attr($fme_postid); ?>">
						</div>
						<div class="modal-footer">
							<div class="fme_upload_files_update_msg " id="fme_update_msg<?php echo esc_attr($fme_postid); ?>">
								
								<p><?php echo esc_html__('Update General Settings Successfully!', ' extendons_menu_cart_plugin'); ?></p>
								
							</div>
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo esc_html__('Close', 'Fme_Upload_Files'); ?></button>
							<button type="button" onclick="fme_upload_file_update_general_settings(<?php echo esc_attr($fme_postid); ?>)" class="btn btn-success"><?php echo esc_html__('Update Rule Settings', 'Fme_Upload_Files'); ?></button>
						</div>
					</div>
				</div> 
			</div>
			<span class="fme_delete_msg" id="fme_delete_msg<?php echo esc_attr($fme_postid); ?>">
				<p><?php echo esc_html__('Delete Rule Successfully!', ' extendons_menu_cart_plugin'); ?></p>
			</span>
		<?php
		$fme_upload_file_rule_key++;
	} 

} else {
	?>
	<div class="row" id="fme_upload_files_empty_rule">
		<div class="col-md-12">
			<?php echo 'Rule is Empty!'; ?>
		</div>
	</div>
	<?php
}

?>

