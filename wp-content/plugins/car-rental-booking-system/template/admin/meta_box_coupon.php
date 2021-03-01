<?php 
		echo $this->data['nonce']; 
        $Date=new CRBSDate();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-coupon-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-coupon-1">
                    <ul class="to-form-field-list">
						<?php echo CRBSHelper::createPostIdField(__('Coupon ID','car-rental-booking-system')); ?>
                        <li>
                            <h5><?php esc_html_e('Coupon code','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Unique, 12-characters coupon code.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['code']); ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Usage count','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Current usage count of the coupon.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['usage_count']); ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Usage limit','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Usage limit of the coupon. Allowed are integer values from range 1-9999. Leave blank for unlimited.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('usage_limit'); ?>" id="<?php CRBSHelper::getFormName('usage_limit'); ?>" value="<?php echo esc_attr($this->data['meta']['usage_limit']); ?>"/>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Vehicles','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select vehicles for which coupon will be applied.','car-rental-booking-system'); ?></span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('vehicle_id_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_id'],-1); ?>/>
								<label for="<?php CRBSHelper::getFormName('vehicle_id_0'); ?>"><?php esc_html_e('- All vehicles -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['vehicle'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('vehicle_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('vehicle_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_id'],$index); ?>/>
								<label for="<?php CRBSHelper::getFormName('vehicle_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
							</div>
						</li>
						<li>
                            <h5><?php esc_html_e('Vehicle categories','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select vehicle categories for which coupon will be applied.','car-rental-booking-system'); ?></span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('vehicle_category_id_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_category_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_category_id'],-1); ?>/>
								<label for="<?php CRBSHelper::getFormName('vehicle_category_id_0'); ?>"><?php esc_html_e('- All vehicles -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['vehicle_category'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('vehicle_category_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('vehicle_category_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_category_id'],$index); ?>/>
								<label for="<?php CRBSHelper::getFormName('vehicle_category_id_'.$index); ?>"><?php echo esc_html($value['name']); ?></label>
<?php		
		}
?>
							</div>							
                        </li>
                        <li>
                            <h5><?php esc_html_e('Active from','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Start date. Leave blank if there is no start date.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('active_date_start'); ?>" id="<?php CRBSHelper::getFormName('active_date_start'); ?>" value="<?php echo $Date->formatDateToDisplay($this->data['meta']['active_date_start']); ?>"/>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Active to','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Stop date. Leave blank if there is no stop date.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('active_date_stop'); ?>" id="<?php CRBSHelper::getFormName('active_date_stop'); ?>" value="<?php echo $Date->formatDateToDisplay($this->data['meta']['active_date_stop']); ?>"/>
                            </div>
                        </li>  						
                        <li>
                            <h5><?php esc_html_e('Percentage discount','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Percentage discount. Allowed are integer numbers from 0-99.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" maxlength="2" name="<?php CRBSHelper::getFormName('discount_percentage'); ?>" id="<?php CRBSHelper::getFormName('discount_percentage'); ?>" value="<?php echo esc_attr($this->data['meta']['discount_percentage']); ?>"/>
                            </div>
                        </li>     
                        <li>
                            <h5><?php esc_html_e('Fixed discount','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Fixed discount. This discount is used only if percentage discount is set to 0.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('discount_fixed'); ?>" id="<?php CRBSHelper::getFormName('discount_fixed'); ?>" value="<?php echo esc_attr($this->data['meta']['discount_fixed']); ?>"/>
                            </div>
                        </li>  
                       <li>
                            <h5><?php esc_html_e('Discount based on rental days number','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php echo __('Enter discount (percentage or fixed) for selected range of rental days. This option works for "Daily" billing type only.','car-rental-booking-system'); ?><br/>
								<?php echo __('Fixed discount is used only if percentage discount is set to 0. If days ranges will not be found, default discount from coupon will be applied.','car-rental-booking-system'); ?><br/>
							</span>
                            <div>
                                <table class="to-table" id="to-table-discount-rental-day-count">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('From','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('To','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Percentage discount','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Percentage discount.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Fixed discount','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Fixed discount.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('discount_rental_day_count[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('discount_rental_day_count[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="2" name="<?php CRBSHelper::getFormName('discount_rental_day_count[discount_percentage][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('discount_rental_day_count[discount_fixed][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['discount_rental_day_count']))
        {
            if(is_array($this->data['meta']['discount_rental_day_count']))
            {
                foreach($this->data['meta']['discount_rental_day_count'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('discount_rental_day_count[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('discount_rental_day_count[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="2" name="<?php CRBSHelper::getFormName('discount_rental_day_count[discount_percentage][]'); ?>" value="<?php echo esc_attr($value['discount_percentage']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('discount_rental_day_count[discount_fixed][]'); ?>" value="<?php echo esc_attr($value['discount_fixed']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','car-rental-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('.to').themeOptionElement({init:true});
                
                var timeFormat='<?php echo CRBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CRBSJQueryUIDatePicker::convertDateFormat(CRBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
				
				$('#to-table-discount-rental-day-count').table();
				
                $('input[name="<?php CRBSHelper::getFormName('vehicle_id'); ?>[]"],input[name="<?php CRBSHelper::getFormName('vehicle_category_id'); ?>[]"]').on('change',function()
                {
                    var checkbox=$(this).parents('li:first').find('input');
                    
                    var value=parseInt($(this).val());
                    if(value===-1)
                    {
                        checkbox.prop('checked',false);
                        checkbox.first().prop('checked',true);
                    }
                    else checkbox.first().prop('checked',false);
                    
                    var checked=[];
                    checkbox.each(function()
                    {
                        if($(this).is(':checked'))
                            checked.push(parseInt($(this).val(),10));
                    });
                    
                    if(checked.length===0)
                    {
                        checkbox.prop('checked',false);
                        checkbox.first().prop('checked',true);
                    }
                    
                    checkbox.button('refresh');
                });
				
            });
		</script>