<?php 
		echo $this->data['nonce']; 
        
        $Date=new CRBSDate();
		$PriceRule=new CRBSPriceRule();
        $Validation=new CRBSValidation();
        
        $BookingFormElement=new CRBSBookingFormElement();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-booking-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-2"><?php esc_html_e('Billing','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-3"><?php esc_html_e('Vehicle','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-4"><?php esc_html_e('Extras','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-5"><?php esc_html_e('Client','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-6"><?php esc_html_e('Payment','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-booking-1">
                    <ul class="to-form-field-list">
						<?php echo CRBSHelper::createPostIdField(__('Booking ID','car-rental-booking-system')); ?>
                        <li>
                            <h5><?php esc_html_e('Status','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Booking status.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('booking_status_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('booking_status_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_status_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('booking_status_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
                            </div>
                        </li>       
                        <li>
                            <h5><?php esc_html_e('Pickup date and time','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Pickup date and time.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['pickup_date']).' '.$Date->formatTimeToDisplay($this->data['meta']['pickup_time']));  ?>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Pickup location','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Pickup location.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['pickup_location_name']);  ?> 
<?php
        if((int)$this->data['meta']['pickup_location_custom']===1)
        {
?>
                                <div class="to-float-right"><a target="_blank" href="https://www.google.com/maps?q=<?php esc_attr_e($this->data['meta']['pickup_location_coordinate_lat'].','.$this->data['meta']['pickup_location_coordinate_lng']); ?>"><?php esc_html_e('Show on map','car-rental-booking-system'); ?></a></div>
<?php
        }
        else
        {
?>
                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','car-rental-booking-system'),null,null,$this->data['meta']['pickup_location_id']); ?></div>

<?php
        }
?>
                            </div>
                        </li>                         
                        <li>
                            <h5><?php esc_html_e('Return date and time','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Return date and time.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['return_date']).' '.$Date->formatTimeToDisplay($this->data['meta']['return_time']));  ?>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Return location','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Return location.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['return_location_name']);  ?>
<?php
        if((int)$this->data['meta']['return_location_custom']===1)
        {
?>
                                <div class="to-float-right"><a target="_blank" href="https://www.google.com/maps?q=<?php esc_attr_e($this->data['meta']['return_location_coordinate_lat'].','.$this->data['meta']['return_location_coordinate_lng']); ?>"><?php esc_html_e('Show on map','car-rental-booking-system'); ?></a></div>
<?php
        }
        else
        {
?>
                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','car-rental-booking-system'),null,null,$this->data['meta']['return_location_id']); ?></div>
<?php
        }
?>
                            </div>
                        </li>                          
                        <li>
                            <h5><?php esc_html_e('Order total amount','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Order total amount.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html(CRBSPrice::format($this->data['billing']['summary']['value_gross'],$this->data['meta']['currency_id']));  ?>
                            </div>
                        </li>  
<?php
        if((int)$this->data['meta']['payment_deposit_type']!==0)
        {
?>
                        <li>
                            <h5><?php esc_html_e('To Pay','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('To Pay (deposit).','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-field-disabled">
                                <?php echo esc_html(CRBSPrice::format($this->data['billing']['summary']['pay'],$this->data['meta']['currency_id']));  ?>
                            </div>
                        </li>              
<?php          
        }

        if($Validation->isNotEmpty($this->data['meta']['comment']))
        {
?>
                        <li>
                            <h5><?php esc_html_e('Comments to order','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Client comments.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['comment']);  ?>
                            </div>
                        </li>                         
<?php
        }
        if($Validation->isNotEmpty($this->data['meta']['coupon_code']))
        {
?>
                        <li>
                            <h5><?php esc_html_e('Coupon code','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Coupon code.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['coupon_code']);  ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Percentage discount','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Percentage discount.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['coupon_discount_percentage']);  ?>%
                            </div>
                        </li>  
<?php
        }
?>
                    </ul>
                </div>
                <div id="meta-box-booking-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Billing','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Billing.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:5%">
                                            <div>
                                                <?php esc_html_e('ID','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('ID.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Item','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name of the item.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:10%">
                                            <div>
                                                <?php esc_html_e('Unit','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name of the unit.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Quantity','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Quantity.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th> 
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Price','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net unit price.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>     
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Value','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net value.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                        <th style="width:15%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Tax','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate in percentage.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>      
                                        <th style="width:15%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Total amount','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Total gross amount.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                             
                                    </tr>
<?php
        $i=0;
        foreach($this->data['billing']['detail'] as $index=>$value)
        {
?>           
                                    <tr>
                                        <td>
                                            <div>
                                                <?php echo esc_html(++$i); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php echo esc_html($value['name']); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div>
                                                <?php echo esc_html($value['unit']); ?>
                                            </div>
                                        </td>                                                
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($value['quantity']); ?>
                                            </div>
                                        </td>     
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($value['price_net']); ?>
                                            </div>
                                        </td>                                             
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($value['value_net']); ?>
                                            </div>
                                        </td>  
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($value['tax_value']); ?>
                                            </div>
                                        </td>  
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($value['value_gross']); ?>
                                            </div>
                                        </td>      
                                    </tr>            
<?php
        }
?>
                                    <tr>
                                        <td><div>-</div></td>
                                        <td><div>-</div></td>
                                        <td><div>-</div></td>
                                        <td class="to-align-right"><div>-</div></td>
                                        <td class="to-align-right"><div>-</div></td>
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($this->data['billing']['summary']['value_net']); ?>
                                            </div>
                                        </td>  
                                        <td class="to-align-right"><div>-</div></td>
                                        <td class="to-align-right">
                                            <div>
                                                <?php echo esc_html($this->data['billing']['summary']['value_gross']); ?>
                                            </div>
                                        </td>      
                                    </tr> 
                                </table>
                            </div>
                        </li>      
                    </ul>
                </div>
                <div id="meta-box-booking-3">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Name','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle name.','car-rental-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['vehicle_name']) ?>
                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','car-rental-booking-system'),null,null,$this->data['meta']['vehicle_id']); ?></div>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Vehicle prices','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Base prices of the vehicle.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table">
                                    <tr>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Price name','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price name.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Value','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Value.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Tax rate','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
<?php
        foreach($PriceRule->getPriceUseType() as $index=>$value)
        {
			$taxRateIndex=$PriceRule->getTaxRateIndexName($index);
?>
                                    <tr>
                                        <td>
                                            <div><?php echo esc_html($value[0]) ?></div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-field-disabled">
                                                    <?php echo CRBSPrice::format($this->data['meta']['price_'.$index.'_value'],$this->data['meta']['currency_id']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-field-disabled">
                                                    <?php echo $this->data['meta']['price_'.$taxRateIndex.'_tax_rate_value'].'%'; ?>
                                                </div>
                                            </div>
                                        </td>                                        
                                    </tr>
<?php          
        }
?>
                                </table>
                            </div>
                        </li>                         
                    </ul>                    
                </div>
                <div id="meta-box-booking-4">
<?php
        if(count($this->data['meta']['booking_extra']))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Booking extras','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('List of add-ons ordered.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Name','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Quantity','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Quantity.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Price','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net unit price.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Value','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net value.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Tax','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate in percentage.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Total amount','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Total gross amount.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                    </tr> 
<?php
            foreach($this->data['meta']['booking_extra'] as $index=>$value)
            {
?>
                                    <tr>
                                        <td style="width:40%">
                                            <div>
                                                <?php echo esc_html($value['name']); ?>
                                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','car-rental-booking-system'),null,null,$value['id']); ?></div>
                                            </div>
                                        </td>
                                        <td class="to-align-right" style="width:10%">
                                            <div>
                                                <?php echo esc_html($value['quantity']); ?>
                                            </div>
                                        </td>
                                        <td class="to-align-right" style="width:10%">
                                            <div>
                                                <?php echo number_format($value['sum_net'],2,'.',''); ?>
                                            </div>
                                        </td>                                        
                                        <td class="to-align-right" style="width:10%">
                                            <div>
                                                <?php echo number_format($value['quantity']*$value['price'],2,'.',''); ?>
                                            </div>
                                        </td>                                            
                                        <td class="to-align-right" style="width:15%">
                                            <div>
                                                <?php echo number_format($value['tax_rate_value'],2,'.',''); ?>
                                            </div>
                                        </td>                                            
                                        <td class="to-align-right" style="width:15%">
                                            <div>
                                                <?php echo number_format(CRBSPrice::calculateGross($value['sum_net'],0,$value['tax_rate_value'])*$value['quantity'],2,'.',''); ?>
                                            </div>
                                        </td>                                              
                                    </tr>      
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
                <div id="meta-box-booking-5">
                   <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Client details','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Client contact details.','car-rental-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('First name:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_first_name']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Last name:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_last_name']) ?></div>                                
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('E-mail address:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_email_address']) ?></div>                                
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Phone number:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_phone_number']) ?></div>                                
                            </div> 
<?php
        if((int)$this->data['meta']['driver_license_attachment_id'])
        {
?>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Driver license:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php edit_post_link(esc_html__('Edit','car-rental-booking-system'),null,null,$this->data['meta']['driver_license_attachment_id']); ?></div>                                
                            </div> 
<?php
        }

        echo $BookingFormElement->displayField(1,$this->data['meta']);
?>
                        </li>
<?php
        if((int)$this->data['meta']['client_billing_detail_enable']===1)
        {
?>
                        <li>
                            <h5><?php esc_html_e('Billing address','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Billing address details.','car-rental-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Company name:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_company_name']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Tax number:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_tax_number']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street name:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_street_name']) ?></div>                                
                            </div>                           
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street number:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_street_number']) ?></div>                                
                            </div>          
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('City:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_city']) ?></div>                                
                            </div>          
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('State:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_state']) ?></div>                                
                            </div>    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Postal code:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_postal_code']) ?></div>                                
                            </div>    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Country:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['client_billing_detail_country_name']) ?></div>                                
                            </div>      
<?php
        echo $BookingFormElement->displayField(2,$this->data['meta']);
?>
                        </li>
<?php          
        }
        
        $panel=$BookingFormElement->getPanel($this->data['meta']);
        
        foreach($panel as $panelIndex=>$panelValue)
        {
            if(in_array($panelValue['id'],array(1,2))) continue;
?>
                        <li>
                            <h5><?php echo esc_html($panelValue['label']); ?></h5>
                            <span class="to-legend">
                                <?php echo esc_html($panelValue['label']); ?>
                            </span>                            
                            <?php echo $BookingFormElement->displayField($panelValue['id'],$this->data['meta']); ?>
                        </li>    
<?php
        }
?>
                    </ul>
                </div>
                <div id="meta-box-booking-6">
<?php
        if($Validation->isNotEmpty($this->data['meta']['payment_name']))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Payment details','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Payment details.','car-rental-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Payment method:','car-rental-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['payment_name']) ?></div>                                
                            </div>
                        </li>
<?php
            if(in_array($this->data['meta']['payment_id'],array(2,3)))
            {
?>
                        <li>
                            <h5><?php esc_html_e('Transactions','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('List of registered transactions for this payment.','car-rental-booking-system'); ?><br/>
                            </span>
<?php
				if(array_key_exists('payment_stripe_data',$this->data['meta']))
				{
					if((is_array($this->data['meta']['payment_stripe_data'])) && (count($this->data['meta']['payment_stripe_data'])))
					{
?>						
                            <div>	
                                <table class="to-table to-table-fixed-layout">
                                     <thead>
                                        <tr>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Transaction ID','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Transaction ID.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Type','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Type.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Date','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Date.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>	
                                            <th style="width:55%">
                                                <div>
                                                    <?php esc_html_e('Details','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Status.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>	
                                    <tbody>
<?php
						foreach($this->data['meta']['payment_stripe_data'] as $index=>$value)
						{
?>
                                        <tr>
                                            <td><div><?php echo esc_html($value->id); ?></div></td>
                                            <td><div><?php echo esc_html($value->type); ?></div></td>
                                            <td><div><?php echo esc_html(date_i18n(CRBSOption::getOption('date_format').' '.CRBSOption::getOption('time_format'),$value->created)); ?></div></td>
                                            <td>
												<div class="to-toggle-details">
													<a href="#"><?php esc_html_e('Toggle details','car-rental-booking-system'); ?></a>
													<div class="to-hidden">
														<pre>
															<?php var_dump($value); ?>
														</pre>
													</div>
												</div>
											</td>
                                        </tr>
<?php
						}
?>
                                    </tbody>
								</table>
							</div>
<?php						
					}
				}
				else if(array_key_exists('payment_paypal_data',$this->data['meta']))
				{
					if((is_array($this->data['meta']['payment_paypal_data'])) && (count($this->data['meta']['payment_paypal_data'])))
					{
?>

                            <div>	
                                <table class="to-table">
                                    <thead>
                                        <tr>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Transaction ID','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Transaction ID.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Status','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Type.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Date','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Date.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>	
                                            <th style="width:55%">
                                                <div>
                                                    <?php esc_html_e('Details','car-rental-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Details.','car-rental-booking-system'); ?></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
						foreach($this->data['meta']['payment_paypal_data'] as $index=>$value)
						{
?>
                                        <tr>
                                            <td><div><?php echo esc_html($value['txn_id']); ?></div></td>
                                            <td><div><?php echo esc_html($value['payment_status']); ?></div></td>
                                            <td><div><?php echo esc_html(date_i18n(CRBSOption::getOption('date_format').' '.CRBSOption::getOption('time_format'),$value['payment_date'])); ?></div></td>
											<td>
												<div class="to-toggle-details">
													<a href="#"><?php esc_html_e('Toggle details','car-rental-booking-system'); ?></a>
													<div class="to-hidden">
														<pre>
															<?php var_dump($value); ?>
														</pre>
													</div>
												</div>
											</td>
                                        </tr>
<?php
						}
?>
                                    </tbody>
                                </table>
                            </div>
<?php				
					}
				}
?>
						</li>
<?php
            }
?>
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
				
				$('.to-toggle-details>a').on('click',function(e)
				{
					e.preventDefault();
					$(this).parents('td:first').css('max-width','0px');
					$(this).next('div').toggleClass('to-hidden');
				});
            });
		</script>