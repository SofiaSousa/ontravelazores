<?php 
		echo $this->data['nonce']; 
		
		if(!count($this->data['meta']['location_id']))
		{
?>	
		<div class="notice notice-error">
			<p>
				<?php esc_html_e('Please assign vehicle to at least one location. Otherwise vehicle will not be available in booking form.','car-rental-booking-system') ?>
			</p>
		</div>
<?php
		}
?>
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-vehicle-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-2"><?php esc_html_e('Prices','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-3"><?php esc_html_e('Attributes','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-4"><?php esc_html_e('Availability','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-vehicle-1">
                    <ul class="to-form-field-list">
						<?php echo CRBSHelper::createPostIdField(__('Vehicle ID','car-rental-booking-system')); ?>
                        <li>
                            <h5><?php esc_html_e('Locations','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select at least one location at which the vehicle is available.','car-rental-booking-system'); ?>
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
                            <h5><?php esc_html_e('Vehicle make','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle make.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('vehicle_make'); ?>" id="<?php CRBSHelper::getFormName('vehicle_make'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_make']); ?>"/>
                            </div>
                        </li>    
                        <li>
                            <h5><?php esc_html_e('Vehicle model','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle model.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('vehicle_model'); ?>" id="<?php CRBSHelper::getFormName('vehicle_model'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_model']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Number of seats','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Maximum number of passengers (or seats). Integer value from 1 to 99.','car-rental-booking-system'); ?></span>
                            <div>
                                <input maxlength="2" type="text" name="<?php CRBSHelper::getFormName('passenger_count'); ?>" id="<?php CRBSHelper::getFormName('passenger_count'); ?>" value="<?php echo esc_attr($this->data['meta']['passenger_count']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Number of suitcases','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Maximum number of suitcases. Integer value from 1 to 99.','car-rental-booking-system'); ?></span>
                            <div>
                                <input maxlength="2" type="text" name="<?php CRBSHelper::getFormName('bag_count'); ?>" id="<?php CRBSHelper::getFormName('bag_count'); ?>" value="<?php echo esc_attr($this->data['meta']['bag_count']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Gearbox','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter type of gearbox.','car-rental-booking-system'); ?></span>
                            <div>
                                <input maxlength="255" type="text" name="<?php CRBSHelper::getFormName('gearbox_type'); ?>" id="<?php CRBSHelper::getFormName('gearbox_type'); ?>" value="<?php echo esc_attr($this->data['meta']['gearbox_type']); ?>"/>
                            </div>
                        </li>                        
                        <li>
                            <h5><?php esc_html_e('Fuel state','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter fuel state.','car-rental-booking-system'); ?></span>
                            <div>
                                <input maxlength="255" type="text" name="<?php CRBSHelper::getFormName('fuel_state'); ?>" id="<?php CRBSHelper::getFormName('fuel_state'); ?>" value="<?php echo esc_attr($this->data['meta']['fuel_state']); ?>"/>
                            </div>
                        </li>   
                        <li>
                            <h5><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter minimum and maximum driver\'s age.','car-rental-booking-system'); ?></span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Minimum:','car-rental-booking-system'); ?></span>
                                <input maxlength="3" type="text" name="<?php CRBSHelper::getFormName('driver_age_min'); ?>" id="<?php CRBSHelper::getFormName('driver_age_min'); ?>" value="<?php echo esc_attr($this->data['meta']['driver_age_min']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Maximum:','car-rental-booking-system'); ?></span>
                                <input maxlength="3" type="text" name="<?php CRBSHelper::getFormName('driver_age_max'); ?>" id="<?php CRBSHelper::getFormName('driver_age_max'); ?>" value="<?php echo esc_attr($this->data['meta']['driver_age_max']); ?>"/>
                            </div>
                        </li>    
                        <li>
                            <h5><?php esc_html_e('"Or similar" label','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enable or disable "or similar" label for the vehicle in booking form.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable_1'); ?>" name="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_vehicle_similar_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable_0'); ?>" name="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_vehicle_similar_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('booking_vehicle_similar_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Group code','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Group code is used to create a set of vehicles. Only one vehicle from the group with the same code will be displayed on booking form..','car-rental-booking-system'); ?></span>
                            <div>
                                <input maxlength="255" type="text" name="<?php CRBSHelper::getFormName('group_code'); ?>" id="<?php CRBSHelper::getFormName('group_code'); ?>" value="<?php echo esc_attr($this->data['meta']['group_code']); ?>"/>
                            </div>
                        </li>                         
                    </ul>
                </div>
                <div id="meta-box-vehicle-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Prices','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Net prices.','car-rental-booking-system'); ?></span>
                            <div>
                                <table class="to-table to-table-price">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Name','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Description','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Description.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Value','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Value.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:10%">
                                            <div>
                                                <?php esc_html_e('Tax','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                          
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Initial','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Initial, fixed fee added to the booking.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_initial_value'); ?>" name="<?php CRBSHelper::getFormName('price_initial_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_initial_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_initial_tax_rate_id'); ?>">

<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>       
												</select>
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Rental per day','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Price for a car rent per day.','car-rental-booking-system'); ?><br/>
                                                <?php esc_html_e('This price applies for "Daily" and "Daily and Hourly" billing type only.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
											<div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_rental_day_value'); ?>" name="<?php CRBSHelper::getFormName('price_rental_day_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_rental_day_value']); ?>"/>
											</div>											
                                        </td>
                                        <td rowspan="2">
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_rental_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_rental_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_rental_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                                
                                            </div>
                                        </td>	
									</tr>									
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Rental per hour','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
												<?php esc_html_e('Price for a car rent per hour.','car-rental-booking-system'); ?><br/>
                                                <?php esc_html_e('This price applies for "Hourly" and "Daily and Hourly" billing type only.','car-rental-booking-system'); ?>
                                             </div>
                                        </td>	
                                        <td>
											<div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_rental_hour_value'); ?>" name="<?php CRBSHelper::getFormName('price_rental_hour_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_rental_hour_value']); ?>"/>
											</div>
                                        </td>											
										
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Price per distance (mile/kilometer) for delivery a vehicle from company (base location) to customer (custom) pickup location.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_delivery_value'); ?>" name="<?php CRBSHelper::getFormName('price_delivery_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_delivery_tax_rate_id'); ?>">

<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>       
												</select>
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery (return)','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Price per distance (mile/kilometer) for delivery vehicle from customer (custom) return location to company (base location).','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_delivery_return_value'); ?>" name="<?php CRBSHelper::getFormName('price_delivery_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_return_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_delivery_return_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>                                 
												</select>
											</div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Deposit','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking.','car-rental-booking-system'); ?><br/>
												<?php esc_html_e('This amount protects the owner against costs related to vehicle damage. Should be returned to the customer, if vehicle has no signs of damage.','car-rental-booking-system'); ?><br/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_deposit_value'); ?>" name="<?php CRBSHelper::getFormName('price_deposit_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_deposit_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_deposit_tax_rate_id'); ?>">

<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_deposit_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_deposit_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>                                 
												</select>
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('One way','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking if car is returned to different location than pickup location.','car-rental-booking-system'); ?><br/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_one_way_value'); ?>" name="<?php CRBSHelper::getFormName('price_one_way_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_one_way_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_one_way_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_one_way_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_one_way_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                   
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Pickup after business hours','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking, if vehicle will be pickup after business hours.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_after_business_hour_pickup_value'); ?>" name="<?php CRBSHelper::getFormName('price_after_business_hour_pickup_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_after_business_hour_pickup_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_after_business_hour_pickup_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_pickup_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_pickup_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                   
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Return after business hours','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking, if vehicle has been returned after business hours.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_after_business_hour_return_value'); ?>" name="<?php CRBSHelper::getFormName('price_after_business_hour_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_after_business_hour_return_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_after_business_hour_return_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                   
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Customer pickup location fee','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking, if customer enter (type) own pickup location.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_customer_pickup_location_value'); ?>" name="<?php CRBSHelper::getFormName('price_customer_pickup_location_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_customer_pickup_location_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_customer_pickup_location_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_pickup_location_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_pickup_location_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                   
                                            </div>
                                        </td>	
									</tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Customer return location fee','car-rental-booking-system'); ?>
                                            </div>
                                        </td>							
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed value added to the booking, if customer enter (type) own return location.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php CRBSHelper::getFormName('price_customer_return_location_value'); ?>" name="<?php CRBSHelper::getFormName('price_customer_return_location_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_customer_return_location_value']); ?>"/>
                                            </div>
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
												<select name="<?php CRBSHelper::getFormName('price_customer_return_location_tax_rate_id'); ?>">
<?php
        echo '<option value="0" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_return_location_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
        foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
        {
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_return_location_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
        }
?>
												</select>                                   
                                            </div>
                                        </td>	
									</tr>										
								</table>
							</div>
						</li>
                    </ul>
                </div>
                <div id="meta-box-vehicle-3">
<?php
        if((isset($this->data['dictionary']['vehicleAttribute'])) && (count($this->data['dictionary']['vehicleAttribute'])))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Attributes','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify attributes of the vehicle.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Attribute name','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Attribute name.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Attribute value','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Attribute value(s).','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>       
<?php
            foreach($this->data['dictionary']['vehicleAttribute'] as $attributeIndex=>$attributeValue)
            {
?>
                                    <tr>
                                        <td>
                                            <div><?php echo esc_html($attributeValue['post']->post_title) ?></div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
<?php
                switch($attributeValue['meta']['attribute_type'])
                {
                    case 1:
?>
                                                <input type="text" id="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.']'); ?>" name="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.']'); ?>" value="<?php echo esc_attr($this->data['meta']['attribute'][$attributeIndex]); ?>"/>
<?php                       
                    break;
                    case 2:
                    case 3:
                            
                        $type=$attributeValue['meta']['attribute_type']==2 ? 'radio' : 'checkbox';
?>
                                                <div class="to-<?php echo esc_attr($type); ?>-button">
                                                    <input type="<?php echo esc_attr($type); ?>" value="-1" id="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.'][0]'); ?>" name="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.'][]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['attribute'][$attributeIndex],-1); ?>/>
                                                    <label for="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.'][0]'); ?>"><?php esc_html_e('- Not set -','car-rental-booking-system'); ?></label>
<?php
                        foreach($attributeValue['meta']['attribute_value'] as $data)
                        {
?>                           
                                                    <input type="<?php echo esc_attr($type); ?>" value="<?php echo (int)$data['id']; ?>" id="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.']['.(int)$data['id'].']'); ?>" name="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.'][]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['attribute'][$attributeIndex],(int)$data['id']); ?>/>
                                                    <label for="<?php CRBSHelper::getFormName('attribute['.$attributeIndex.']['.(int)$data['id'].']'); ?>"><?php echo esc_html($data['value']); ?></label>
<?php
                        }
?>                        
                                                </div>
<?php
                    break;
                }
?>
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
                <div id="meta-box-vehicle-4">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Exclude dates','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify dates in which vehicle is not available. Past (or invalid date ranges) will be removed during saving.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-availability-exclude-date">
                                    <tr>
                                        <th style="width:40%" colspan="2">
                                            <div>
                                                <?php esc_html_e('Start Date','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Enter start date and time (optionally).','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%" colspan="2">
                                            <div>
                                                <?php esc_html_e('End Date','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Enter end date and time (optionally).','car-rental-booking-system'); ?>
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
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>                                        
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>
<?php
        $Date=new CRBSDate();
		if(count($this->data['meta']['date_exclude']))
		{
			foreach($this->data['meta']['date_exclude'] as $dateExcludeIndex=>$dateExcludeValue)
			{
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','car-rental-booking-system'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['start_date'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','car-rental-booking-system'); ?>"  value="<?php echo esc_attr($Date->formatTimeToDisplay($dateExcludeValue['start_time'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','car-rental-booking-system'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['stop_date'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CRBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','car-rental-booking-system'); ?>"  value="<?php echo esc_attr($Date->formatTimeToDisplay($dateExcludeValue['stop_time'])); ?>"/>
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
                
                /***/
                
                $('#to-table-vehicle-attribute input[type="checkbox"]').on('change',function()
                {
                    var value=parseInt($(this).val());

                    var checkbox=$(this).parents('div:first').find('input');

                    if(value===-1)
                    {
                        checkbox.prop('checked',false);
                        checkbox.first().prop('checked',true);
                    }
                    else checkbox.first().prop('checked',false);
                    
                    checkbox.button('refresh');
                });
                
                /***/
                
                $('#to-table-availability-exclude-date').table();
                
                /***/
                
                var timeFormat='<?php echo CRBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CRBSJQueryUIDatePicker::convertDateFormat(CRBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
            });
		</script>