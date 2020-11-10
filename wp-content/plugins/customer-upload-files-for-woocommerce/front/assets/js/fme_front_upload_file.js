jQuery(document).ready(function(){
	'use strict';
	jQuery('.fme_preview_image').hide();
	jQuery('.fme_delete_img').hide();
});
function  fme_upload_files_product_page(fme_file_ext,fme_count_val,fme_file_maximum_size,fme_id_name,fme_file_sizetype) {
	'use strict';
	var fd = new FormData();
	var file = jQuery(document).find('#'+fme_id_name+fme_count_val);
	var input = jQuery('#'+fme_id_name+fme_count_val)[0];
	var individual_file = file[0].files[0];
	var fileName, fileExtension;
	fileName = individual_file.name;
	fileExtension = fileName.replace(/^.*\./, '');
	var validExtensions = fme_file_ext;
	var validExtensions = validExtensions.split(',');
	if (jQuery.inArray(fileExtension, validExtensions) == -1){
		 alert("Invalid file type Please choose only " + validExtensions + ' files');
		 jQuery('#'+fme_id_name+fme_count_val).val('');
		 jQuery('#fme_previewlink'+fme_count_val).hide();
		 jQuery('#fme_deleteimage'+fme_count_val).hide();
		 return false;
	} else {
		if(fme_file_sizetype =='MB') {
			var fme_selected_file_size = individual_file.size;
			var file_size = fme_selected_file_size / 1000000;
		}
		else if(fme_file_sizetype =='KB') {

			var fme_selected_file_size = individual_file.size;
			var file_size = fme_selected_file_size / 1000;

		} else if(fme_file_sizetype =='GB') {

			var fme_selected_file_size = individual_file.size;
			var file_size = fme_selected_file_size / 1000000000;

		}
		if(file_size > fme_file_maximum_size) {

			alert('File Size must be Less than' + fme_file_maximum_size + fme_file_sizetype);
			jQuery('#'+fme_id_name+fme_count_val).val('');

		} else {

			if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {            
						 
				}
				if (input.files[0].type.indexOf("image") >= 0) {
					jQuery(input).next().attr('href', (window.URL ? URL : webkitURL).createObjectURL(input.files[0]));
					jQuery(input).next().attr('typee','image');
					jQuery(input).next().show();
					jQuery(input).next().next().show();

				} else {

					jQuery(input).next().attr('href', (window.URL ? URL : webkitURL).createObjectURL(input.files[0]));
					jQuery(input).next().attr('typee','file');
					jQuery(input).next().show(); 
					jQuery(input).next().next().show();
				}
				reader.readAsDataURL(input.files[0]);
			}

		}
		
		if ('fme_cart_file' == fme_id_name || 'fme_checkout_notes_file' == fme_id_name) {
			var fme_cart_file_url = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
			var ajaxurl = ewcpm_php_vars.admin_url;
			var file_data = individual_file;
			var form_data = new FormData();
			form_data.append('file', file_data);
			form_data.append('action', 'fme_upload_file_cart_rules');
			form_data.append('fme_upload_files_count', fme_count_val);
			form_data.append('fme_upload_files_url', fme_cart_file_url);
			form_data.append('fme_upload_files_id_name', fme_id_name);
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				contentType: false,
				processData: false,
				data: form_data,
				success: function (response) {     
				 	if(jQuery("[name='update_cart']").length >0){
						jQuery("[name='update_cart']").removeAttr('disabled');
						jQuery("[name='update_cart']").trigger("click");
					} else {
                        jQuery('body').trigger('update_checkout');
                        jQuery('#fme_checkout'+fme_count_val).html(response);
                        jQuery('#fme_checkout_notes_file'+fme_count_val).val('');
                        jQuery('#fme_previewlink'+fme_count_val).show();
                    }
				}
			});

		}
	}
}


function fme_upload_file_delete_preview_image(fme_selected_item_id,fme_id_name) {
	"use strict";
	jQuery('#'+fme_id_name+fme_selected_item_id).val('');
	jQuery('#fme_previewlink'+fme_selected_item_id).hide();
	jQuery('#fme_deleteimage'+fme_selected_item_id).hide();
}

function fme_upload_file_delete_cart_file(fme_upload_files_cart_file_key, fme_upload_files_filename,fme_upload_files_name_position){
	"use strict";
	var ajaxurl = ewcpm_php_vars.admin_url;
	jQuery.ajax({
		url: ajaxurl,
		type: 'post',
		data: {
			action: 'fme_upload_file_delete_cart_files',
			fme_upload_files_cart_file_key:fme_upload_files_cart_file_key,
			fme_upload_files_filename:fme_upload_files_filename,
			fme_upload_files_name_position:fme_upload_files_name_position
		 
		},
		success: function (data) {
			if(jQuery("[name='update_cart']").length >0){
				jQuery("[name='update_cart']").removeAttr('disabled');
				jQuery("[name='update_cart']").trigger("click");
			} else {
	            jQuery('body').trigger('update_checkout');
	            jQuery('#fme_checkout_filename'+fme_upload_files_cart_file_key).remove();
	            jQuery('#fme_checkout_file_view'+fme_upload_files_cart_file_key).remove();
	            jQuery('#fme_checkout_preview'+fme_upload_files_cart_file_key).remove();
	            
	        }
		}   
	});

}


