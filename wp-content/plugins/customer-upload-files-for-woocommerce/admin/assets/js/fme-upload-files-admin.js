jQuery(document).ready(function() {
	"use strict";
	window.onbeforeunload = null;
	jQuery('#fmemultiplefiles').hide();
	jQuery('#fme_upload_files_Products').hide();
	jQuery('#fme_upload_files_category').hide();
	jQuery('#Fme_upload_files_price_single').hide();
	jQuery('.fme_loading_animation').hide();
	jQuery('.fme_loading_frame').hide();
	jQuery('#fme_success_msg').hide();
	jQuery('#fme_settings_msg').hide();
	jQuery('#fme_loader').hide();
	jQuery('#fme_settings_loader').hide();
	jQuery('.fme_upload_files_loader_d').hide();
	jQuery('.fme_loader_edit').hide();
	jQuery('.fme_delete_msg').hide();
	jQuery('.Fme_choosen').select2();
	jQuery('.Multiply_by_Quantity').hide();
	jQuery('.Edit_Multiply_by_Quantity').hide();

	jQuery('input[name="fme-radio-select-display-on"]').on('click', function() {
		"use strict";
		var fme_display_on_value = jQuery("input[name='fme-radio-select-display-on']:checked").val();
		if(fme_display_on_value=='fme_upload_files_product_page'){
			jQuery('.Multiply_by_Quantity').show();
		} else {
			jQuery('.Multiply_by_Quantity').hide();

		};

	});

	jQuery('body').on('click','input[name="fme-edit-radio-select-display-on"]', function() {
		"use strict";
		var fme_display_on_value = jQuery("input[name='fme-edit-radio-select-display-on']:checked").val();
		if(fme_display_on_value=='fme_upload_files_product_page'){
			jQuery('.Edit_Multiply_by_Quantity').show();
		} else {
			jQuery('.Edit_Multiply_by_Quantity').hide();

		};

	});
});


function fme_upload_files_upload_selection_files(fme_upload_file_type) {
	"use strict";
	if('fme_create' == fme_upload_file_type) {
		var FME_selection_files = jQuery('#FME_selection_files').val();
		if('fme_upload_files_multiple' == FME_selection_files) {
			jQuery('#fmemultiplefiles').show();
			jQuery('#Fme_upload_files_price_single').hide();
		} else if('fme_upload_files_single' == FME_selection_files) {
			jQuery('#fmemultiplefiles').hide();
			jQuery('#Fme_upload_files_price_single').show();
		} else {
			jQuery('#fmemultiplefiles').hide();
			jQuery('#Fme_upload_files_price_single').hide();
		}
	} else {
		var FME_selection_files = jQuery('#FME_edit_selection_files').val();
		if('fme_upload_files_multiple' == FME_selection_files) {
			jQuery('#edit_fme_multiple_files').show();
			jQuery('#Fme_uploadfile_price_singles').hide();
		} else if('fme_upload_files_single' == FME_selection_files) {
			jQuery('#edit_fme_multiple_files').hide();
			jQuery('#Fme_uploadfile_price_singles').show();
		} else {
			jQuery('#edit_fme_multiple_files').hide();
			jQuery('#Fme_uploadfile_price_singles').hide();
		}
	}

	

}

function Fme_upload_file_choosen_product_cateory(fme_upload_file_type) {
	"use strict";
	if('fme_create' == fme_upload_file_type) {
		var fme_product_category = jQuery('#fmeproductcategory').val();
		if('fme_upload_files_category' == fme_product_category) {
			jQuery('#fme_upload_files_Products').hide();
			jQuery('#fme_upload_files_category').show();
		} else if('fme_upload_files_product' == fme_product_category) {
			jQuery('#fme_upload_files_Products').show();
			jQuery('#fme_upload_files_category').hide();
		} else {
			jQuery('#fme_upload_files_Products').hide();
			jQuery('#fme_upload_files_category').hide();
		}
	} else {
		var fme_product_category = jQuery('#fme_edit_product_category').val();
		if('fme_upload_files_category' == fme_product_category) {
			jQuery('#Fme_edit_Products').hide();
			jQuery('#Fme_edit_category').show();
		} else if('fme_upload_files_product' == fme_product_category) {
			jQuery('#Fme_edit_Products').show();
			jQuery('#Fme_edit_category').hide();
		} else {
			jQuery('#Fme_edit_Products').hide();
			jQuery('#Fme_edit_category').hide();
		}
	}

	
}

function fme_upload_file_save_general_settings() {
	"use strict";
	var fme_enable_disable_setting = jQuery('#fme_upload_files_enable_disable_setting').val();
	var fme_display_on_value = jQuery("input[name='fme-radio-select-display-on']:checked").val();
	var fme_selection_files	= jQuery('#FME_selection_files').val();
	var fme_multiple_by_quantity = jQuery('#fme_upload_files_multiple_by_qunatity').val();
	var fme_multiple_files_limit=[];
	if(fme_selection_files=='fme_upload_files_multiple') {
		jQuery('input[name="fme_multiple_file_price[]"]').each(function(){
			var temp_array = {
				fme_uploadfiles_price: jQuery(this).val(),
				fme_uploadfiles_discount_type: jQuery(this).parent().next().find('select[name="fme_discount_type_multiple[]"]').val(),
				fme_uploadfiles_discount_price: jQuery(this).parent().next().next().find('input[name="fme_discount_price_multiple_file[]"]').val() 
			};
			fme_multiple_files_limit.push(temp_array);
		});
	} else if(fme_selection_files=='fme_upload_files_single') {

		var fme_price_uploadfile = jQuery('#fmepriceuploadfile').val();
		var fme_Discount_type = jQuery('#fmeDiscounttype').val();
		var fme_Discount_value = jQuery('#fmeDiscountvalue').val();

		var temp_array = {
				fme_uploadfiles_price: fme_price_uploadfile,
				fme_uploadfiles_discount_type: fme_Discount_type,
				fme_uploadfiles_discount_price: fme_Discount_value 
		};
		fme_multiple_files_limit.push(temp_array);
	} 
	var fme_allowed_file_types = jQuery('#Fme_allowed_file_types').val();
	var fme_file_size = jQuery('#fme_file_size').val();
	var Fme_maximum_uploadsize = jQuery('#Fme-maximum-uploadsize').val();
	var fme_product_category = jQuery('#fmeproductcategory').val();	
	if(fme_product_category=='fme_upload_files_product') {
		var fme_selected_items = jQuery('#Fme_files-product').val();
	} else if(fme_product_category=='fme_upload_files_category') {
		var fme_selected_items = jQuery('#Fme_files-category').val();
	} else {
		var fme_selected_items = '';
	}
	var fme_selected_user_role = jQuery('#Fme_choosen-user-role').val();
	var fme_selected_order_status = jQuery('#Fme_choosen-order-status').val();
	var ajaxurl = ewcpm_php_vars.admin_url;

	if(fme_allowed_file_types==''){
		alert("Please Enter Allowed File Types:");
	} else {
		jQuery('#fme_settings_loader').show();
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'fme_upload_files_save_general_settings',
				fme_enable_disable_setting:fme_enable_disable_setting,
				fme_display_on_value:fme_display_on_value,
				fme_selection_files:fme_selection_files,
				fme_multiple_files_limit:JSON.stringify(fme_multiple_files_limit),
				fme_allowed_file_types:fme_allowed_file_types,
				fme_file_size:fme_file_size,
				Fme_maximum_uploadsize:Fme_maximum_uploadsize,
				fme_product_category:fme_product_category,
				fme_selected_items:fme_selected_items,
				fme_multiple_by_quantity:fme_multiple_by_quantity,
				fme_selected_user_role:fme_selected_user_role,
				fme_selected_order_status:fme_selected_order_status,
			},
			success: function (data) {
				console.log(data);	
				jQuery('#fme_settings_msg').show();
				jQuery('#fme_settings_msg').delay(1000).fadeOut('slow');
				jQuery('#fme_settings_loader').show();
				jQuery('#fme_settings_loader').delay(1000).fadeOut('slow');
				window.onbeforeunload = null;
				location.reload();	

			}   
		});
	}

	
}

function fme_upload_file_delete_rule(fme_upload_files_rule_id) {
	"use strict";
	var ajaxurl = ewcpm_php_vars.admin_url;
	var checkstr =  confirm('Are you sure you want to delete this?');
	if(checkstr==true){
		jQuery('#fme_loader_deltete'+fme_upload_files_rule_id).show();
			jQuery.ajax({
				url: ajaxurl,
				type: 'post',
				data: {
					action: 'fme_upload_files_delete_rule_file',
					fme_upload_files_rule_id:fme_upload_files_rule_id
				 
				},
				success: function (data) {
				jQuery('#fme_delete_msg'+fme_upload_files_rule_id).show();
				jQuery('#fme_delete_msg'+fme_upload_files_rule_id).delay(1000).fadeOut('slow');	
				jQuery('#fme_loader_deltete'+fme_upload_files_rule_id).delay(1000).fadeOut('slow');	
				window.location.reload();
		
			}   
		});

	}
}


function fme_upload_file_edit_rule(fme_upload_files_rule_id) {
	"use strict";
	var ajaxurl = ewcpm_php_vars.admin_url;
	jQuery.ajax({
		url: ajaxurl,
		type: 'post',
		data: {
			action: 'fme_upload_files_edit_rule_file',
			fme_upload_files_rule_id:fme_upload_files_rule_id
		 
		},
		success: function (data) {	
			jQuery('#fme_editForm_upload_file'+fme_upload_files_rule_id).html(data);
			jQuery('.Fme_edit_choosen'+fme_upload_files_rule_id).select2();
		}   
	});

}


function fme_upload_file_update_general_settings(fme_update_post_id) {
	"use strict";
	var fme_edit_enable_disable_setting = jQuery('#fme_edit_enable_disable_setting').val();
	var fme_edit_display_on_value = jQuery("input[name='fme-edit-radio-select-display-on']:checked").val();
	var fme_edit_selection_files	= jQuery('#FME_edit_selection_files').val();
	var fme_multiple_by_quantity = jQuery('#fme_upload_files_update_multiple_by_qunatity').val();
	var fme_edit_multiple_files_limit=[];
	console.log(fme_edit_selection_files);
	if(fme_edit_selection_files=='fme_upload_files_multiple') {
		jQuery('input[name="fme_multiple_file_price[]"]').each(function(){
			var temp_array = {
				fme_uploadfiles_price: jQuery(this).val(),
				fme_uploadfiles_discount_type: jQuery(this).parent().next().find('select[name="fme_discount_type_multiple[]"]').val(),
				fme_uploadfiles_discount_price: jQuery(this).parent().next().next().find('input[name="fme_discount_price_multiple_file[]"]').val() 
			};
			fme_edit_multiple_files_limit.push(temp_array);

		});
	} else if(fme_edit_selection_files=='fme_upload_files_single') {

			var fme_edit_price_uploadfile = jQuery('#fme_editpriceuploadfile').val();
			var fme_edit_Discount_type = jQuery('#fme_editDiscounttype').val();
			var fme_edit_Discount_value = jQuery('#fme_editDiscountvalue').val();

			var temp_array = {
				fme_uploadfiles_price: fme_edit_price_uploadfile,
				fme_uploadfiles_discount_type: fme_edit_Discount_type,
				fme_uploadfiles_discount_price: fme_edit_Discount_value 
			};
			fme_edit_multiple_files_limit.push(temp_array);

	} 
	var fme_edit_allowed_file_types = jQuery('#Fme_edit_allowed_file_types').val();
	var fme_edit_file_size = jQuery('#fme_edit_file_size').val();
	var fme_edit_maximum_uploadsize = jQuery('#Fme_edit_maximum_uploadsize').val();
	var fme_edit_product_category = jQuery('#fme_edit_product_category').val();	

	if(fme_edit_product_category=='fme_upload_files_product') {
		var fme_edit_selected_items = jQuery('#Fme_edit_products').val();
	} else if(fme_edit_product_category=='fme_upload_files_category') {
		var fme_edit_selected_items = jQuery('#Fme_edit_categories').val();
	} else {
		var fme_edit_selected_items = '';
	}
	var fme_edit_selected_user_role = jQuery('#Fme_edit_choosen_user_role').val();
	var fme_edit_selected_order_status = jQuery('#Fme_edit_choosen_order_status').val();

	var fme_edit_set_price_upload_file_enable = jQuery('#fme_set_edit_price_upload_file').val();

	var ajaxurl = ewcpm_php_vars.admin_url;
	jQuery.ajax({
		url: ajaxurl,
		type: 'post',
		data: {
			action: 'fme_upload_files_update_general_settings',
			fme_update_post_id:fme_update_post_id,
			fme_edit_enable_disable_setting:fme_edit_enable_disable_setting,
			fme_edit_display_on_value:fme_edit_display_on_value,
			fme_edit_selection_files:fme_edit_selection_files,
			fme_edit_multiple_files_limit:JSON.stringify(fme_edit_multiple_files_limit),
			fme_edit_allowed_file_types:fme_edit_allowed_file_types,
			fme_edit_file_size:fme_edit_file_size,
			fme_edit_maximum_uploadsize:fme_edit_maximum_uploadsize,
			fme_edit_product_category:fme_edit_product_category,
			fme_edit_selected_items:fme_edit_selected_items,
			fme_edit_selected_user_role:fme_edit_selected_user_role,
			fme_multiple_by_quantity:fme_multiple_by_quantity,
			fme_edit_selected_order_status:fme_edit_selected_order_status,
		},
		success: function (data) {	
			jQuery('#fme_update_msg'+fme_update_post_id).show();
			jQuery('#fme_update_msg'+fme_update_post_id).delay(1000).fadeOut('slow');	
		 	window.location.reload();	
		}   
	});
}

jQuery(function () {
    jQuery("body").on("click", ".remove", function () {
    	"use strict";
        jQuery(this).closest("tr").remove();
    });
});

function fme_uploadfile_multiplefile(fme_upload_filetype) {
	"use strict";
	if (fme_upload_filetype=='fme_create') {
		var div = jQuery("<tr />");
	    div.html(fme_upload_file_GetDynamicTextBox(""));
	    jQuery("#TextBoxContainer").append(div);
	} else if (fme_upload_filetype=='fme_edit') {
		var div = jQuery("<tr />");
	    div.html(fme_upload_file_GetDynamicTextBox(""));
	    jQuery("#TextBoxContainer1").append(div);

	}
	
}
function fme_upload_file_GetDynamicTextBox(fme_upload_file_value) {
	"use strict";
    return '<td><input min="0" id="fme_files_input_filed_price" name ="fme_multiple_file_price[]" type="number" value = "' + fme_upload_file_value + '" class="form-control" /></td>' + '<td colspan="4"><select name="fme_discount_type_multiple[]" class="form-control"><option value=""> Discount Type </option><option value="Fme_upload_files_fixed">Fixed</option><option value="Fme_upload_files_percentage">Percentage</option></select></td>' + '<td colspan="1"><input min="0" id="fme_files_input_filed_price" name = "fme_discount_price_multiple_file[]" type="number" value = "' + fme_upload_file_value + '" class="form-control" /></td>' + '<td colspan="3"><button type="button" class="btn btn-danger remove">x</button></td>'
}

