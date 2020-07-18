<?php 
		echo $this->data['nonce']; 
        
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $Validation=new CHBSValidation();
        
        $BookingFormElement=new CHBSBookingFormElement();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-booking-1"><?php esc_html_e('General','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-2"><?php esc_html_e('Billing','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-3"><?php esc_html_e('Route','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-4"><?php esc_html_e('Vehicle','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-5"><?php esc_html_e('Extras','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-6"><?php esc_html_e('Client','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-7"><?php esc_html_e('Payment','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-booking-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Status','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Booking status.','chauffeur-booking-system'); ?></span>
                            <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_status_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_status_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_status_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('booking_status_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
                            </div>
                        </li>      
                        <li>
                            <h5><?php esc_html_e('Driver','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Driver assigned to this booking.','chauffeur-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="-1" id="<?php CHBSHelper::getFormName('driver_id_0'); ?>" name="<?php CHBSHelper::getFormName('driver_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driver_id'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('driver_id_0'); ?>"><?php esc_html_e('- None - ','chauffeur-booking-system'); ?></label>

<?php
		foreach($this->data['dictionary']['driver'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('driver_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('driver_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driver_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('driver_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
        }
?>
                            </div>
                        </li>                              
                        <li>
                            <h5><?php esc_html_e('Service type','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Service type.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['service_type_name']);  ?>
                            </div>
                        </li>   
                        <li>
                            <h5><?php esc_html_e('Transfer type','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Transfer type.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['transfer_type_name']) ?>
                            </div>
                        </li>    
                        <li>
                            <h5><?php esc_html_e('Pickup date and time','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Pickup date and time.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['pickup_date']).' '.$Date->formatTimeToDisplay($this->data['meta']['pickup_time']));  ?>
                            </div>
                        </li> 
<?php
        if(in_array($this->data['meta']['service_type_id'],array(1,3)))
        {
            if((int)$this->data['meta']['transfer_type_id']===3)
            {
?>
                       <li>
                            <h5><?php esc_html_e('Return date and time','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Return date and time.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['return_date']).' '.$Date->formatTimeToDisplay($this->data['meta']['return_time']));  ?>
                            </div>
                        </li>                         
<?php
            }
        }
?>
                        <li>
                            <h5><?php esc_html_e('Order total amount','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Order total amount.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html(CHBSPrice::format($this->data['billing']['summary']['value_gross'],$this->data['meta']['currency_id']));  ?>
                            </div>
                        </li>  
<?php
        if($this->data['meta']['payment_deposit_enable']==1)
        {
?>
                        <li>
                            <h5><?php esc_html_e('To Pay','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo sprintf(esc_html('To Pay (deposit %s%%).','chauffeur-booking-system'),$this->data['meta']['payment_deposit_value']); ?>
                            </span>
                            <div class="to-field-disabled">
                                <?php echo esc_html(CHBSPrice::format($this->data['billing']['summary']['pay'],$this->data['meta']['currency_id']));  ?>
                            </div>
                        </li>              
<?php          
        }

        if(in_array($this->data['meta']['service_type_id'],array(1,3)))
        {
?>
                        <li>
                            <h5><?php esc_html_e('Distance','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo $Length->label($this->data['meta']['length_unit'],2); ?>
                            </span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['billing']['summary']['distance']);  ?>
                            </div>
                        </li>              
<?php
        }
?>
                        <li>
                            <h5><?php esc_html_e('Duration','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Rental time of the vehicle in hours.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['billing']['summary']['duration']);  ?>
                            </div>
                        </li> 
<?php
        if($this->data['meta']['passenger_enable']==1)
        {
?>         
                        <li>
                            <h5><?php esc_html_e('Passengers','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Number of passengers.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html(CHBSBookingHelper::getPassengerLabel($this->data['meta']['passenger_adult_number'],$this->data['meta']['passenger_children_number'])); ?>
                            </div>
                        </li>             
<?php          
        }
        if($Validation->isNotEmpty($this->data['meta']['comment']))
        {
?>
                        <li>
                            <h5><?php esc_html_e('Comments to order','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Client comments.','chauffeur-booking-system'); ?></span>
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
                            <h5><?php esc_html_e('Coupon code','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Coupon code.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['coupon_code']);  ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Percentage discount','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Percentage discount.','chauffeur-booking-system'); ?></span>
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
                            <h5><?php esc_html_e('Billing','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Billing.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:5%">
                                            <div>
                                                <?php esc_html_e('ID','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('ID.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Item','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name of the item.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:10%">
                                            <div>
                                                <?php esc_html_e('Unit','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name of the unit.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Quantity','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Quantity.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th> 
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Price','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net unit price.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>     
                                        <th style="width:10%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                        <th style="width:15%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Tax','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate in percentage.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>      
                                        <th style="width:15%" class="to-align-right">
                                            <div>
                                                <?php esc_html_e('Total amout','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Total gross amount.','chauffeur-booking-system'); ?>
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
<?php
        if($this->data['meta']['service_type_id']==3)
        {
?>
                        <li>
                            <h5><?php esc_html_e('Route name','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Route name.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['route_name']) ?>
                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','chauffeur-booking-system'),null,null,$this->data['meta']['route_id']); ?></div>
                            </div>
                        </li>   
<?php
        }
?>
                        <li>
                            <h5><?php esc_html_e('Route','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Route.','chauffeur-booking-system'); ?></span>
<?php
        foreach($this->data['meta']['coordinate'] as $index=>$value)
        {
            $address=esc_html($value['address']);
            
            if(array_key_exists('formatted_address',$value))
            {
                if($Validation->isNotEmpty($value['formatted_address']))
                    $address='<b>'.esc_html($value['formatted_address']).'</b><br/>'.$address;
            }
?>        
                            <div class="to-field-disabled">
                                <?php echo $address; ?>
                            </div>
<?php
        }
?>
                        </li> 
<?php
        if(in_array($this->data['meta']['service_type_id'],array(1,3)))
        {
            if($this->data['meta']['extra_time_enable']==1)
            {
                $Date=new CHBSDate();
?>              
                        <li>
                            <h5><?php esc_html_e('Extra time','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Extra time in hours.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($Date->formatMinuteToTime($this->data['meta']['extra_time_value'])); ?>
                            </div>
                        </li>                    
<?php   
            }         
        }
?>
                    </ul>
                </div>
                <div id="meta-box-booking-4">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Name','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle name.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['vehicle_name']) ?>
                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','chauffeur-booking-system'),null,null,$this->data['meta']['vehicle_id']); ?></div>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Vehicle prices','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Base prices of vehicle.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table">
                                    <tr>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Price name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Tax rate','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
<?php
        $price=array
        (
            'fixed'                                                             =>  array(__('Fixed','chauffeur-booking-system')),
            'fixed_return'                                                      =>  array(__('Fixed (return)','chauffeur-booking-system')),
            'fixed_return_new_ride'                                             =>  array(__('Fixed (return, new ride)','chauffeur-booking-system')),
            'initial'                                                           =>  array(__('Initial','chauffeur-booking-system')),
            'delivery'                                                          =>  array(__('Delivery','chauffeur-booking-system')), 
            'delivery_return'                                                   =>  array(__('Delivery (return)','chauffeur-booking-system')), 
            'distance'                                                          =>  array(__($Length->label($this->data['meta']['length_unit'],3),'chauffeur-booking-system')),
            'distance_return'                                                   =>  array(__($Length->label($this->data['meta']['length_unit'],3).__(' (return)','chauffeur-booking-system'),'chauffeur-booking-system')),
            'distance_return_new_ride'                                          =>  array(__($Length->label($this->data['meta']['length_unit'],3).__(' (return, new ride)','chauffeur-booking-system'),'chauffeur-booking-system')),
            'hour'                                                              =>  array(__('Per hour','chauffeur-booking-system')),
            'hour_return'                                                       =>  array(__('Per hour (return)','chauffeur-booking-system')),
            'hour_return_new_ride'                                              =>  array(__('Per hour (return, new ride)','chauffeur-booking-system')),
            'extra_time'                                                        =>  array(__('Extra time (per hour)','chauffeur-booking-system')),
            'passenger_adult'                                                   =>  array(__('Per passenger (adult)','chauffeur-booking-system')),
            'passenger_children'                                                =>  array(__('Per passenger (child)','chauffeur-booking-system'))
        );
        
        foreach($price as $index=>$value)
        {
?>
                                    <tr>
                                        <td>
                                            <div><?php echo esc_html($value[0]) ?></div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-field-disabled">
                                                    <?php echo CHBSPrice::format($this->data['meta']['price_'.$index.'_value'],$this->data['meta']['currency_id']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-field-disabled">
                                                    <?php echo $this->data['meta']['price_'.$index.'_tax_rate_value'].'%'; ?>
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
                <div id="meta-box-booking-5">
<?php
        if(count($this->data['meta']['booking_extra']))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Booking extras','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('List of addons ordered.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Quantity','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Quantity.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Price','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net unit price.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Net value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Tax','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate in percentage.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th class="to-align-right" style="width:10%">
                                            <div>
                                                <?php esc_html_e('Total amout','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Total gross amount.','chauffeur-booking-system'); ?>
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
                                                <div class="to-float-right"><?php edit_post_link(esc_html__('Edit','chauffeur-booking-system'),null,null,$value['id']); ?></div>
                                            </div>
                                        </td>
                                        <td class="to-align-right" style="width:10%">
                                            <div>
                                                <?php echo esc_html($value['quantity']); ?>
                                            </div>
                                        </td>
                                        <td class="to-align-right" style="width:10%">
                                            <div>
                                                <?php echo number_format($value['price'],2,'.',''); ?>
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
                                                <?php echo number_format(CHBSPrice::calculateGross($value['price'],0,$value['tax_rate_value'])*$value['quantity'],2,'.',''); ?>
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
                <div id="meta-box-booking-6">
                   <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Client details','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Client contact details.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('First name:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_first_name']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Last name:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_last_name']) ?></div>                                
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('E-mail address:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_email_address']) ?></div>                                
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Phone number:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_contact_detail_phone_number']) ?></div>                                
                            </div> 
<?php
        echo $BookingFormElement->displayField(1,$this->data['meta']);
?>
                        </li>
<?php
        if((int)$this->data['meta']['client_billing_detail_enable']===1)
        {
?>
                        <li>
                            <h5><?php esc_html_e('Billing address','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Billing address details.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Company name:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_company_name']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Tax number:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_tax_number']) ?></div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street name:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_street_name']) ?></div>                                
                            </div>                           
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street number:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_street_number']) ?></div>                                
                            </div>          
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('City:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_city']) ?></div>                                
                            </div>          
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('State:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_state']) ?></div>                                
                            </div>    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Postal code:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['meta']['client_billing_detail_postal_code']) ?></div>                                
                            </div>    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Country:','chauffeur-booking-system'); ?></span>
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
        
        if((array_key_exists('form_element_agreement',$this->data['meta'])) && (is_array($this->data['meta']['form_element_agreement'])) && (count($this->data['meta']['form_element_agreement'])))
        {
?>
                        <li>
                            <h5><?php esc_html_e('Agreements','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Agreements.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:80%">
                                            <div>
                                                <?php esc_html_e('Agreement','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Text of the agreement.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Customer reply','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Customer reply.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                       
                                    </tr>
<?php
            foreach($this->data['meta']['form_element_agreement'] as $index=>$value)
            {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <?php echo $value['text']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php echo ((int)$value['value']===1 ? esc_html__('Yes','chauffeur-booking-system') : esc_html__('No','chauffeur-booking-system')); ?>
                                            </div>
                                        </td>
                                    </tr>
<?php
            }
?>
                                </table>
                            </div>
                        </li>                        
<?php
        }
?>
                    </ul>
                </div>
                <div id="meta-box-booking-7">
<?php
        if(!empty($this->data['meta']['payment_id']))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Payment details','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Payment details.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Payment method:','chauffeur-booking-system'); ?></span>
                                <div class="to-field-disabled"><?php echo esc_html($this->data['payment_name']) ?></div>                                
                            </div>
                        </li>
<?php
            if($this->data['meta']['payment_deposit_enable']==1)
            {
?>
                        <li>
                            <h5><?php esc_html_e('Deposit','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Deposit.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['payment_deposit_value']).'%';  ?>
                            </div>
                        </li>              
<?php          
            }

            if(in_array($this->data['meta']['payment_id'],array(2,3)))
            {
                if((array_key_exists('payment_data',$this->data['meta'])) && (is_array($this->data['meta']['payment_data'])))
                {
?>
                        <li>
                            <h5><?php esc_html_e('Transactions','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('List of registered transactions for this payment.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>	
                                <table class="to-table">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">
                                                <div>
                                                    <?php esc_html_e('Transaction ID','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Transaction ID.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Type','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Type.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:20%">
                                                <div>
                                                    <?php esc_html_e('Date','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Date.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>	
                                            <th style="width:15%">
                                                <div>
                                                    <?php esc_html_e('Status','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Status.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>
                                            <th style="width:20%">
                                                <div>
                                                    <?php esc_html_e('Amount','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Amount.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>	
                                            <th style="width:10%">
                                                <div>
                                                    <?php esc_html_e('Currency','chauffeur-booking-system'); ?>
                                                    <span class="to-legend"><?php esc_html_e('Currency.','chauffeur-booking-system'); ?></span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
                    foreach($this->data['meta']['payment_data'] as $index=>$value)
                    {
?>
                                        <tr>
                                            <td><div><?php echo esc_html($value['txn_id']); ?></div></td>
                                            <td><div><?php echo esc_html($value['payment_type']); ?></div></td>
                                            <td><div><?php echo esc_html($value['payment_date']); ?></div></td>
                                            <td><div><?php echo esc_html($value['payment_status']); ?></div></td>
                                            <td><div><?php echo esc_html($value['mc_gross']); ?></div></td>
                                            <td><div><?php echo esc_html($value['mc_currency']); ?></div></td>
                                        </tr>
<?php
                    }
?>
                                    </tbody>
                                </table>
                            </div>
                        </li>
<?php				
                }
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
            });
		</script>