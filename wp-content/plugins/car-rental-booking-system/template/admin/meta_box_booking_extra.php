<?php 
		echo $this->data['nonce']; 
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-booking-extra-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-extra-2"><?php esc_html_e('Prices','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-booking-extra-1">
                    <ul class="to-form-field-list">
						<?php echo CRBSHelper::createPostIdField(__('Booking extra ID','car-rental-booking-system')); ?>
                        <li>
                            <h5><?php esc_html_e('Locations','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select at least one location in which additive is available.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['location'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('location_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('location_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('location_id_'.$index); ?>"><?php echo esc_html(get_the_title($index)); ?></label>
<?php		
		}
?>                                
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Description','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Description of the additive.','car-rental-booking-system'); ?></span>
                            <div>
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('description'); ?>" id="<?php CRBSHelper::getFormName('description'); ?>"><?php echo esc_html($this->data['meta']['description']); ?></textarea>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Quantity','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Define whether an add-on can be ordered more then once.','car-rental-booking-system'); ?></span>                        
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('quantity_enable_1'); ?>" name="<?php CRBSHelper::getFormName('quantity_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['quantity_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('quantity_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('quantity_enable_0'); ?>" name="<?php CRBSHelper::getFormName('quantity_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['quantity_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('quantity_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Maximum quantity','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('A maximum number possible to order. Integer value from 1 to 9999.','car-rental-booking-system'); ?></span>                        
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('quantity_max'); ?>" id="<?php CRBSHelper::getFormName('quantity_max'); ?>" value="<?php echo esc_attr($this->data['meta']['quantity_max']); ?>" maxlength="4"/>
                            </div>
                        </li>   
                        <li>
                            <h5><?php esc_html_e('Default quantity','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Default quantity. Integer value from 1 to 9999.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('This value cannot be higher than "Maximum quantity".','car-rental-booking-system'); ?>
							</span>                        
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('quantity_default'); ?>" id="<?php CRBSHelper::getFormName('quantity_default'); ?>" value="<?php echo esc_attr($this->data['meta']['quantity_default']); ?>" maxlength="4"/>
                            </div>
                        </li>    
                        <li>
                            <h5><?php _e('State of "Select" button','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('State of the "Select" booking add-ons button.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('"Not selected" means, that button will not be checked by default.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('"Selected" means, that button will be checked by default, but customer is able to uncheck it.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('"Mandatory" means, that button will be checked by default and customer is not able to uncheck it.','car-rental-booking-system'); ?>
							</span>                        
                            <div class="to-radio-button">
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('button_select_default_state_0'); ?>" name="<?php CRBSHelper::getFormName('button_select_default_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['button_select_default_state'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('button_select_default_state_0'); ?>"><?php esc_html_e('Not selected','car-rental-booking-system'); ?></label>
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('button_select_default_state_1'); ?>" name="<?php CRBSHelper::getFormName('button_select_default_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['button_select_default_state'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('button_select_default_state_1'); ?>"><?php esc_html_e('Selected','car-rental-booking-system'); ?></label>
                                <input type="radio" value="2" id="<?php CRBSHelper::getFormName('button_select_default_state_2'); ?>" name="<?php CRBSHelper::getFormName('button_select_default_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['button_select_default_state'],2); ?>/>
                                <label for="<?php CRBSHelper::getFormName('button_select_default_state_2'); ?>"><?php esc_html_e('Mandatory','car-rental-booking-system'); ?></label>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Price','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Price per single addition.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('You can also set prices and tax rates for each vehicle separately in tab named "Prices".','car-rental-booking-system'); ?>
                            </span>
                            <div>
                                <input maxlength="12" type="text" name="<?php CRBSHelper::getFormName('price'); ?>" id="<?php CRBSHelper::getFormName('price'); ?>" value="<?php echo esc_attr($this->data['meta']['price']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Price type','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select price type.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['price_type'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('price_type_'.$index); ?>" name="<?php CRBSHelper::getFormName('price_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['price_type'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('price_type_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>                                
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Tax rate','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select tax rate for the price.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('tax_rate_id_0'); ?>" name="<?php CRBSHelper::getFormName('tax_rate_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['tax_rate_id'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('tax_rate_id_0'); ?>"><?php esc_html_e('- Not set -','car-rental-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('tax_rate_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('tax_rate_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['tax_rate_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('tax_rate_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>    
                    </ul>
                </div>
                <div id="meta-box-booking-extra-2">
<?php
        if((is_array($this->data['dictionary']['vehicle'])) && (count($this->data['dictionary']['vehicle'])))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Prices','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Set prices for each vehicle separately.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-vehicle-price">
                                    <tr>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Vehicle','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Vehicle.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Status','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Enable or disable price for this vehicle.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th> 
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Price','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net price for this vehicle.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>   
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Tax rate','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate for this vehicle.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>    
                                    </tr>
<?php
            foreach($this->data['dictionary']['vehicle'] as $vehicleIndex=>$vehicleValue)
            {
?>               
                                    <tbody id="to-vehicle-<?php echo $vehicleIndex; ?>">
                                        <tr>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <div class="to-field-disabled">
                                                        <?php echo esc_html($vehicleValue['post']->post_title); ?>
                                                    </div>
                                                </div>
                                            </td> 
                                            <td>
                                                <div class="to-clear-fix">
                                                    <div class="to-radio-button">
                                                        <input type="radio" value="1" id="<?php CRBSHelper::getFormName('vehicle_price_'.$vehicleIndex.'_status_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_price['.$vehicleIndex.'][status]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_price'][$vehicleIndex]['status'],1); ?>/>
                                                        <label for="<?php CRBSHelper::getFormName('vehicle_price_'.$vehicleIndex.'_status_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                                        <input type="radio" value="0" id="<?php CRBSHelper::getFormName('vehicle_price_'.$vehicleIndex.'_status_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_price['.$vehicleIndex.'][status]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_price'][$vehicleIndex]['status'],0); ?>/>
                                                        <label for="<?php CRBSHelper::getFormName('vehicle_price_'.$vehicleIndex.'_status_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                                    </div>
                                                </div>
                                            </td> 
                                            <td>
                                                <div class="to-clear-fix">
                                                    <input type="text" name="<?php CRBSHelper::getFormName('vehicle_price['.$vehicleIndex.'][price]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_price'][$vehicleIndex]['price']); ?>" title="<?php esc_attr_e('Enter price.','car-rental-booking-system'); ?>"/>
                                                </div>
                                            </td> 
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CRBSHelper::getFormName('vehicle_price['.$vehicleIndex.'][tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['vehicle_price'][$vehicleIndex]['tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['vehicle_price'][$vehicleIndex]['tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                    </select>                                                        
                                                </div>                                        
                                            </td>
                                        </tr>
                                    </tbody>
<?php
            }
?>
                                </table>
                            </div>
                        </li>
                    </ul>
<?php
        }
?>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('.to').themeOptionElement({init:true});
            });
		</script>