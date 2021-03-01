<?php 
		echo $this->data['nonce'];
        
        $Date=new CRBSDate();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-price-rule-1"><?php esc_html_e('Rules','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-price-rule-2"><?php esc_html_e('Prices','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-price-rule-1">
                    <ul class="to-form-field-list">
						<?php echo CRBSHelper::createPostIdField(__('Price rule ID','car-rental-booking-system')); ?>
                        <li>
                            <h5><?php esc_html_e('Booking forms','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select forms.','car-rental-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('booking_form_id_0'); ?>" name="<?php CRBSHelper::getFormName('booking_form_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_form_id'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('booking_form_id_0'); ?>"><?php esc_html_e('- All forms -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['booking_form'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('booking_form_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('booking_form_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_form_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('booking_form_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Pickup location','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select pickup location.','car-rental-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('pickup_location_id_0'); ?>" name="<?php CRBSHelper::getFormName('pickup_location_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['pickup_location_id'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('pickup_location_id_0'); ?>"><?php esc_html_e('- All locations -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['location'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('pickup_location_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('pickup_location_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['pickup_location_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('pickup_location_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Return location','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select return location.','car-rental-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('return_location_id_0'); ?>" name="<?php CRBSHelper::getFormName('return_location_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['return_location_id'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('return_location_id_0'); ?>"><?php esc_html_e('- All locations -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['location'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('return_location_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('return_location_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['return_location_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('return_location_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>  	
                       <li>
                            <h5><?php esc_html_e('Pickup geofence','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Geofence for pickup locations. This option works for customer locations only.','car-rental-booking-system'); ?>
							</span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('location_geofence_pickup_0'); ?>" name="<?php CRBSHelper::getFormName('location_geofence_pickup[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_geofence_pickup'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('location_geofence_pickup_0'); ?>"><?php esc_html_e('- None -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['geofence'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('location_geofence_pickup_'.$index); ?>" name="<?php CRBSHelper::getFormName('location_geofence_pickup[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_geofence_pickup'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('location_geofence_pickup_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>
						<li>
                            <h5><?php esc_html_e('Return geofence','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Geofence for return locations. This option works for customer locations only.','car-rental-booking-system'); ?>
							</span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('location_geofence_return_0'); ?>" name="<?php CRBSHelper::getFormName('location_geofence_return[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_geofence_return'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('location_geofence_return_0'); ?>"><?php esc_html_e('- None -','car-rental-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['geofence'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('location_geofence_return_'.$index); ?>" name="<?php CRBSHelper::getFormName('location_geofence_return[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_geofence_return'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('location_geofence_return_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>   						
                        <li>
                            <h5><?php esc_html_e('Vehicles','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select vehicles.','car-rental-booking-system'); ?></span>
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
                            <h5><?php esc_html_e('Day number','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select the starting day of the rental week.','car-rental-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CRBSHelper::getFormName('pickup_day_number_0'); ?>" name="<?php CRBSHelper::getFormName('pickup_day_number[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['pickup_day_number'],-1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('pickup_day_number_0'); ?>"><?php esc_html_e('- All days -','car-rental-booking-system') ?></label>
<?php
        for($i=1;$i<=7;$i++)
        {
?>
                                <input type="checkbox" value="<?php echo esc_attr($i); ?>" id="<?php CRBSHelper::getFormName('pickup_day_number_'.$i); ?>" name="<?php CRBSHelper::getFormName('pickup_day_number[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['pickup_day_number'],$i); ?>/>
                                <label for="<?php CRBSHelper::getFormName('pickup_day_number_'.$i); ?>"><?php echo esc_html(date_i18n('l',strtotime('Sunday +'.$i.' days'))); ?></label>
<?php
        }
?>                                
                            </div>                        
                        </li>
                        <li>
                            <h5><?php esc_html_e('Dates','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Enter pickup or rental dates.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('In case if the prices are not specified, plugin treats below dates as pickup dates.','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('Otherwise, plugin uses below dates to calculate price for entire period (you have to also define price type in tab named "Prices").','car-rental-booking-system'); ?>
							</span>
                            <div>
                                <table class="to-table" id="to-table-rental-date">
                                    <tr>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('From','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('To','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Price','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price.','car-rental-booking-system'); ?>
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
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('pickup_date[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('pickup_date[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('pickup_date[price][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>                         
<?php
        if(isset($this->data['meta']['pickup_date']))
        {
            if(is_array($this->data['meta']['pickup_date']))
            {
                foreach($this->data['meta']['pickup_date'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('pickup_date[start][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['start'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CRBSHelper::getFormName('pickup_date[stop][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['stop'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" mexlength="12" name="<?php CRBSHelper::getFormName('pickup_date[price][]'); ?>" value="<?php echo esc_attr($value['price']); ?>"/>
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
                        <li>
                            <h5><?php esc_html_e('Rental days number','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Enter number of rental days (works for "Daily" billing type only).','car-rental-booking-system'); ?><br/>
								<?php esc_html_e('To use prices defined in below table, you have to choose proper source type of price in tab named "Prices".','car-rental-booking-system'); ?>
							</span>
                            <div>
                                <table class="to-table" id="to-table-rental-day-number">
                                    <tr>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('From','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('To','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Price','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price.','car-rental-booking-system'); ?>
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
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_day_count[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_day_count[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('rental_day_count[price][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['rental_day_count']))
        {
            if(is_array($this->data['meta']['rental_day_count']))
            {
                foreach($this->data['meta']['rental_day_count'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_day_count[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_day_count[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('rental_day_count[price][]'); ?>" value="<?php echo esc_attr($value['price']); ?>"/>
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
                        <li>
                            <h5><?php esc_html_e('Rental hours number','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter number of rental hours (works for "Hourly" billing type only).','car-rental-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-rental-hour-number">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','car-rental-booking-system'); ?>
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
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('rental_hour_count[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('rental_hour_count[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['rental_hour_count']))
        {
            if(is_array($this->data['meta']['rental_hour_count']))
            {
                foreach($this->data['meta']['rental_hour_count'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_hour_count[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CRBSHelper::getFormName('rental_hour_count[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
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
                        <li>
                            <h5><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enter driver\'s age.','car-rental-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-driver-age">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','car-rental-booking-system'); ?>
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
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('driver_age[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('driver_age[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['driver_age']))
        {
            if(is_array($this->data['meta']['driver_age']))
            {
                foreach($this->data['meta']['driver_age'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="3" name="<?php CRBSHelper::getFormName('driver_age[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="3" name="<?php CRBSHelper::getFormName('driver_age[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
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
                <div id="meta-box-price-rule-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Price source','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select price source.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
								<select name="<?php CRBSHelper::getFormName('price_source_type'); ?>">
<?php
        foreach($this->data['dictionary']['price_source_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_source_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
								</select>
							</div>
						</li>
                        <li>
                            <h5><?php esc_html_e('Next rule processing','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('This option determine, whether prices will be set up based on this rule only or plugin has to processing next rule based on priority (order).','car-rental-booking-system'); ?>
                            </span>               
                            <div>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('process_next_rule_enable_1'); ?>" name="<?php CRBSHelper::getFormName('process_next_rule_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['process_next_rule_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('process_next_rule_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('process_next_rule_enable_0'); ?>" name="<?php CRBSHelper::getFormName('process_next_rule_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['process_next_rule_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('process_next_rule_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>  
                            </div>                              
                        </li>   
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
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Description','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Description.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Price alter','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price alter type.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>    
                                        <th style="width:20%">
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
                                                <select name="<?php CRBSHelper::getFormName('price_initial_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_initial_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_rental_day_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_rental_day_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_rental_hour_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_rental_hour_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_delivery_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_delivery_return_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_delivery_return_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_deposit_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_deposit_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_one_way_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_one_way_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <?php esc_html_e('Fixed value added to the booking, if vehicle will be pickuped after business hours.','car-rental-booking-system'); ?>
                                            </div>
                                        </td>
										<td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CRBSHelper::getFormName('price_after_business_hour_pickup_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_pickup_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_after_business_hour_return_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_after_business_hour_return_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_customer_pickup_location_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_pickup_location_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
                                                <select name="<?php CRBSHelper::getFormName('price_customer_return_location_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
        {
			echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['price_customer_return_location_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
                                                </select>                                                  
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
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
                /***/
                
				$('.to').themeOptionElement({init:true});
                
                /***/
                
                $('input[name="<?php CRBSHelper::getFormName('booking_form_id'); ?>[]"],input[name="<?php CRBSHelper::getFormName('pickup_location_id'); ?>[]"],input[name="<?php CRBSHelper::getFormName('return_location_id'); ?>[]"],input[name="<?php CRBSHelper::getFormName('vehicle_id'); ?>[]"],input[name="<?php CRBSHelper::getFormName('pickup_day_number'); ?>[]"],input[name="<?php CRBSHelper::getFormName('location_geofence_pickup'); ?>[]"],input[name="<?php CRBSHelper::getFormName('location_geofence_return'); ?>[]"]').on('change',function()
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
                
                /***/
                
                $('#to-table-rental-date').table();
                $('#to-table-rental-day-number').table();
                $('#to-table-rental-hour-number').table();
                $('#to-table-driver-age').table();
                
                /***/
                
                var timeFormat='<?php echo CRBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CRBSJQueryUIDatePicker::convertDateFormat(CRBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
                
                /***/
            });
		</script>