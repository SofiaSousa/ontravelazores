<?php
		echo $this->data['nonce']; 

        $Date=new CRBSDate();
		$Validation=new CRBSValidation();

		if(($Validation->isEmpty($this->data['meta']['coordinate_latitude'])) || ($Validation->isEmpty($this->data['meta']['coordinate_longitude'])))
		{
?>	
		<div class="notice notice-error">
			<p>
				<?php esc_html_e('Please provide coordinates of location in "Address" tab. Otherwise location will not be available in booking form.','car-rental-booking-system') ?>
			</p>
		</div>
<?php
		}
?>
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-location-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-location-2"><?php esc_html_e('Address','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-location-3"><?php esc_html_e('Availability','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-location-4"><?php esc_html_e('Payments','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-location-5"><?php esc_html_e('Notifications','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-location-6"><?php esc_html_e('Google Calendar','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-location-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Booking period','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Set range (in days) during which customer can send a booking.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Eg. range 1-14 means that customer can send a booking from tomorrow during next two weeks.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Empty values means that booking time is not limited.','car-rental-booking-system'); ?>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('From (number of days - counting from today - since when customer can send a booking):','car-rental-booking-system'); ?></span>
                                <input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('booking_period_from'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_period_from']); ?>"/>
                            </div>   
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('To (number of days - counting from today plus number of days from previous field - until when customer can send a booking):','car-rental-booking-system'); ?></span>
                                <input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('booking_period_to'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_period_to']); ?>"/>
                            </div>  
                        </li> 
						<li>
							<h5><?php esc_html_e('Bookings interval','car-rental-booking-system'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Set interval (in minutes) between bookings.','car-rental-booking-system'); ?></span>
							<div>
								<input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('booking_interval'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_interval']); ?>"/>
							</div>   
						</li>  
                        <li>
                            <h5><?php esc_html_e('Range of days to rent a car','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Maximum and miminum number of days to rent a car (works for "Daily" billing type only).','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Empty or zero values are ignored.','car-rental-booking-system'); ?>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Minimum:','car-rental-booking-system'); ?></span>
                                <input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('vehicle_rent_day_count_min'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_rent_day_count_min']); ?>"/>
                            </div>   
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Maximum:','car-rental-booking-system'); ?></span>
                                <input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('vehicle_rent_day_count_max'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_rent_day_count_max']); ?>"/>
                            </div>  
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Default vehicle','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('Choose a vehicle which will selected by default in booking form for this location.','car-rental-booking-system'); ?><br/>
                            </span>
                            <div>
                                <select name="<?php CRBSHelper::getFormName('vehicle_id_default'); ?>" id="<?php CRBSHelper::getFormName('vehicle_id_default'); ?>">
                                    <option value="-1"><?php esc_html_e('- None - ','car-rental-booking-system'); ?></option>';
<?php
		foreach($this->data['dictionary']['vehicle'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['vehicle_id_default'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                </select>                                
                            </div>  
                        </li>                          
                        <li>
                            <h5><?php esc_html_e('Vehicles availability checking','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select in which way (if at all) plugin has to check if the vehicle is available to book.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('"Vehicle" means, that plugin will check whether vehicle was not marked as unavailable by administrator.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('"Bookings" means, that plugin will check whether vehicle was not booked in the same period already.','car-rental-booking-system'); ?>
                            </span>
                            <div>
                                <div class="to-checkbox-button">
                                    <input type="checkbox" value="2" id="<?php CRBSHelper::getFormName('vehicle_availability_check_type_2'); ?>" name="<?php CRBSHelper::getFormName('vehicle_availability_check_type[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_availability_check_type'],2); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('vehicle_availability_check_type_2'); ?>"><?php esc_html_e('Vehicle','car-rental-booking-system'); ?></label>
                                    <input type="checkbox" value="3" id="<?php CRBSHelper::getFormName('vehicle_availability_check_type_3'); ?>" name="<?php CRBSHelper::getFormName('vehicle_availability_check_type[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_availability_check_type'],3); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('vehicle_availability_check_type_3'); ?>"><?php esc_html_e('Bookings','car-rental-booking-system'); ?></label>
                                    <input type="checkbox" value="1" id="<?php CRBSHelper::getFormName('vehicle_availability_check_type_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_availability_check_type[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_availability_check_type'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('vehicle_availability_check_type_1'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                        </li>       
                        <li>
                            <h5><?php esc_html_e('Pickup vehicle after business hours','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Allow to pickup vehicle after business hours of the location.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable_1'); ?>" name="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['after_business_hour_pickup_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable_0'); ?>" name="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['after_business_hour_pickup_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('after_business_hour_pickup_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Return vehicle after business hours','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Allow to return vehicle after business hours of the location.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('after_business_hour_return_enable_1'); ?>" name="<?php CRBSHelper::getFormName('after_business_hour_return_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['after_business_hour_return_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('after_business_hour_return_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('after_business_hour_return_enable_0'); ?>" name="<?php CRBSHelper::getFormName('after_business_hour_return_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['after_business_hour_return_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('after_business_hour_return_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Driver license','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enable or disable option to require from customer attaching a driver license.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('driver_license_attach_enable_1'); ?>" name="<?php CRBSHelper::getFormName('driver_license_attach_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_license_attach_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('driver_license_attach_enable_1'); ?>"><?php esc_html_e('Enable as mandatory','car-rental-booking-system'); ?></label>
                                <input type="radio" value="2" id="<?php CRBSHelper::getFormName('driver_license_attach_enable_2'); ?>" name="<?php CRBSHelper::getFormName('driver_license_attach_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_license_attach_enable'],2); ?>/>
                                <label for="<?php CRBSHelper::getFormName('driver_license_attach_enable_2'); ?>"><?php esc_html_e('Enable as non-mandatory','car-rental-booking-system'); ?></label>                                
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('driver_license_attach_enable_0'); ?>" name="<?php CRBSHelper::getFormName('driver_license_attach_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_license_attach_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('driver_license_attach_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Driver license file random name','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
								<?php esc_html_e('Enable this option to create random name of driver licence file.','car-rental-booking-system'); ?>
							</span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable_1'); ?>" name="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_license_file_name_random_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable_0'); ?>" name="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_license_file_name_random_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('driver_license_file_name_random_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li> 
                    </ul>
                </div>
                <div id="meta-box-location-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Location','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Start typing to find location.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('location_search'); ?>" id="<?php CRBSHelper::getFormName('location_search'); ?>" value=""/>
                            </div>
                        </li>   						
                        <li>
                            <h5><?php esc_html_e('Address','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify address of the location.','car-rental-booking-system'); ?></span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('address_street'); ?>" id="<?php CRBSHelper::getFormName('address_street'); ?>" value="<?php echo esc_attr($this->data['meta']['address_street']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Street number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('address_street_number'); ?>" id="<?php CRBSHelper::getFormName('address_street_number'); ?>" value="<?php echo esc_attr($this->data['meta']['address_street_number']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Postcode:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('address_postcode'); ?>" id="<?php CRBSHelper::getFormName('address_postcode'); ?>" value="<?php echo esc_attr($this->data['meta']['address_postcode']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('City:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('address_city'); ?>" id="<?php CRBSHelper::getFormName('address_city'); ?>" value="<?php echo esc_attr($this->data['meta']['address_city']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('State:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('address_state'); ?>" id="<?php CRBSHelper::getFormName('address_state'); ?>" value="<?php echo esc_attr($this->data['meta']['address_state']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Country:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('address_country'); ?>" id="<?php CRBSHelper::getFormName('address_country'); ?>">
<?php
		foreach($this->data['dictionary']['country'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['address_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Contact details','car-rental-booking-system'); ?></h5> 
                            <span class="to-legend"><?php esc_html_e('Specify contact details of the location.','car-rental-booking-system'); ?></span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Phone number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('contact_detail_phone_number'); ?>" id="<?php CRBSHelper::getFormName('contact_detail_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['contact_detail_phone_number']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Fax number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('contact_detail_fax_number'); ?>" id="<?php CRBSHelper::getFormName('contact_detail_fax_number'); ?>" value="<?php echo esc_attr($this->data['meta']['contact_detail_fax_number']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('E-mail address:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('contact_detail_email_address'); ?>" id="<?php CRBSHelper::getFormName('contact_detail_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['contact_detail_email_address']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Coordinates','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify coordinates details (latitude, longitude) of the location.','car-rental-booking-system'); ?></span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Latitude:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('coordinate_latitude'); ?>" id="<?php CRBSHelper::getFormName('coordinate_latitude'); ?>" value="<?php echo esc_attr($this->data['meta']['coordinate_latitude']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Longitude:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('coordinate_longitude'); ?>" id="<?php CRBSHelper::getFormName('coordinate_longitude'); ?>" value="<?php echo esc_attr($this->data['meta']['coordinate_longitude']); ?>"/>
                            </div>
                        </li>                        
                    </ul>
                </div>
                <div id="meta-box-location-3">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Business hours','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Specify working days/hours (in HH:MM time format).','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Leave all fields empty if booking is not available for selected day.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <table class="to-table">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Weekday','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Day of the week','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Start time','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Start time in HH:MM time format','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('End time','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('End time in HH:MM time format','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
<?php
		for($i=1;$i<8;$i++)
		{
?>
                                    <tr>
                                        <td>
                                            <div><?php echo $Date->getDayName($i); ?></div>
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker" maxlength="5" name="<?php CRBSHelper::getFormName('business_hour['.$i.'][0]'); ?>" id="<?php CRBSHelper::getFormName('business_hour['.$i.'][0]'); ?>" value="<?php echo esc_attr($this->data['meta']['business_hour'][$i]['start']); ?>" title="<?php esc_attr_e('Enter start time in format HH:MM.','car-rental-booking-system'); ?>"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div>								
                                                <input type="text" class="to-timepicker" maxlength="5" name="<?php CRBSHelper::getFormName('business_hour['.$i.'][1]'); ?>" id="<?php CRBSHelper::getFormName('business_hour['.$i.'][1]'); ?>" value="<?php echo esc_attr($this->data['meta']['business_hour'][$i]['stop']); ?>" title="<?php esc_attr_e('Enter end time in format HH:MM.','car-rental-booking-system'); ?>"/>
                                            </div>
                                        </td>
                                    </tr>
<?php
		}
?>
                                </table>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Exclude dates','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify dates not available for booking. Past (or invalid date ranges) will be removed during saving.','car-rental-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-exclude-date">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Start date','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Start date in DD-MM-YYYY format','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('End date','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('End date in DD-MM-YYYY format','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" maxlength="10" class="to-datepicker" name="<?php CRBSHelper::getFormName('date_exclude_start[]'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="10" class="to-datepicker" name="<?php CRBSHelper::getFormName('date_exclude_stop[]'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>	
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>
<?php
		if(count($this->data['meta']['date_exclude']))
		{
			foreach($this->data['meta']['date_exclude'] as $dateExcludeIndex=>$dateExcludeValue)
			{
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="10" class="to-datepicker" value="<?php echo esc_attr($Date->reverseDate($dateExcludeValue['start'])); ?>" name="<?php CRBSHelper::getFormName('date_exclude_start[]'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="10" class="to-datepicker" value="<?php echo esc_attr($Date->reverseDate($dateExcludeValue['stop'])); ?>" name="<?php CRBSHelper::getFormName('date_exclude_stop[]'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
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
                <div id="meta-box-location-4">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Payment selection','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Set payment method as mandatory to select by the customer.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('payment_mandatory_enable_1'); ?>" name="<?php CRBSHelper::getFormName('payment_mandatory_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_mandatory_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_mandatory_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('payment_mandatory_enable_0'); ?>" name="<?php CRBSHelper::getFormName('payment_mandatory_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_mandatory_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_mandatory_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Payment processing','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable possibility of paying by booking form.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Disabling this option means that customer can choose payment method, but he won\'t be able to pay.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('payment_processing_enable_1'); ?>" name="<?php CRBSHelper::getFormName('payment_processing_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_processing_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_processing_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('payment_processing_enable_0'); ?>" name="<?php CRBSHelper::getFormName('payment_processing_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_processing_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_processing_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li>   
                        <li>
                            <h5><?php esc_html_e('WooCommerce payments on step #3','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable possibility to choose wooCommerce payment method in step #3.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('This option is available if wooCommerce support is enabled.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable_1'); ?>" name="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_woocommerce_step_3_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable_0'); ?>" name="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_woocommerce_step_3_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_woocommerce_step_3_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Default payment','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select default payment method.','car-rental-booking-system'); ?>
                            </span>
                            <div>
								<select name="<?php CRBSHelper::getFormName('payment_default_id'); ?>">
									<option value="-1" <?php CRBSHelper::selectedIf($this->data['meta']['payment_default_id'],-1); ?>><?php esc_html_e('- None -','car-rental-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['payment'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['payment_default_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
								</select>  
                            </div>	
                        </li>
                        <li>
                            <h5><?php esc_html_e('Payment','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select one or more payment methods available in this booking form.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('For some of them you have to enter additional settings.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['payment'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('payment_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('payment_id[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_id'],$index); ?>/>
                                <label for="<?php CRBSHelper::getFormName('payment_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>							
<?php		
		}
?>
                            </div>	
                        </li>
                        <li>
                            <h5><?php esc_html_e('Cash','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for Cash.','car-rental-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_cash_logo_src'); ?>" id="<?php CRBSHelper::getFormName('payment_cash_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_cash_logo_src']); ?>"/>
                                <input type="button" name="<?php CRBSHelper::getFormName('payment_cash_logo_src_browse'); ?>" id="<?php CRBSHelper::getFormName('payment_cash_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Additional information for customer:','car-rental-booking-system'); ?></span>
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('payment_cash_info'); ?>"><?php echo esc_html($this->data['meta']['payment_cash_info']); ?></textarea>
                            </div>
                        </li>						
                        <li>
                            <h5><?php esc_html_e('Stripe','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for Stripe gateway.','car-rental-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Secret API key:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_stripe_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_secret']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Publishable API key:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_stripe_api_key_publishable'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_publishable']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('URL address of redirect after payment:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_stripe_redirect_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_redirect_url_address']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_stripe_logo_src'); ?>" id="<?php CRBSHelper::getFormName('payment_stripe_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_stripe_logo_src']); ?>"/>
                                <input type="button" name="<?php CRBSHelper::getFormName('payment_stripe_logo_src_browse'); ?>" id="<?php CRBSHelper::getFormName('payment_stripe_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Additional information for customer:','car-rental-booking-system'); ?></span>
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('payment_stripe_info'); ?>"><?php echo esc_html($this->data['meta']['payment_stripe_info']); ?></textarea>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Open Stripe window automatically in payment step:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable_1'); ?>" name="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_stripe_window_open_default_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable_0'); ?>" name="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_stripe_window_open_default_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('payment_stripe_window_open_default_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('PayPal','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for PayPal gateway.','car-rental-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('E-mail address:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_paypal_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_paypal_email_address']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Sandbox mode:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>" name="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>" name="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_paypal_logo_src'); ?>" id="<?php CRBSHelper::getFormName('payment_paypal_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_paypal_logo_src']); ?>"/>
                                <input type="button" name="<?php CRBSHelper::getFormName('payment_paypal_logo_src_browse'); ?>" id="<?php CRBSHelper::getFormName('payment_paypal_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Additional information for customer:','car-rental-booking-system'); ?></span>
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('payment_paypal_info'); ?>"><?php echo esc_html($this->data['meta']['payment_paypal_info']); ?></textarea>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Wire transfer','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for Wire transfer.','car-rental-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('payment_wire_transfer_logo_src'); ?>" id="<?php CRBSHelper::getFormName('payment_wire_transfer_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_wire_transfer_logo_src']); ?>"/>
                                <input type="button" name="<?php CRBSHelper::getFormName('payment_wire_transfer_logo_src_browse'); ?>" id="<?php CRBSHelper::getFormName('payment_wire_transfer_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Additional information for customer:','car-rental-booking-system'); ?></span>
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('payment_wire_transfer_info'); ?>"><?php echo esc_html($this->data['meta']['payment_wire_transfer_info']); ?></textarea>
                            </div>
                        </li>	
                    </ul>                    
                </div>
                <div id="meta-box-location-5">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('E-mail messages','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select the sender\'s email account from which the messages will be sent (to clients and to defined recipients) with info about new bookings.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Sender e-mail account:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('booking_new_sender_email_account_id'); ?>" id="<?php CRBSHelper::getFormName('booking_new_sender_email_account_id'); ?>">
<?php
		echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],-1,false)).'>'.esc_html__(' - Not set -','car-rental-booking-system').'</option>';
		foreach($this->data['dictionary']['email_account'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                </select>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('List of recipients e-mail addresses separated by semicolon:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('booking_new_recipient_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_new_recipient_email_address']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Enable/disbale sending e-mail message with notification about new booking to the customers:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_1'); ?>" name="<?php CRBSHelper::getFormName('booking_new_customer_email_notification'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_customer_email_notification'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_0'); ?>" name="<?php CRBSHelper::getFormName('booking_new_customer_email_notification'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_customer_email_notification'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field">
									<?php esc_html_e('Enable/disbale sending e-mail message with notification about new booking to the customers after payment.','car-rental-booking-system'); ?></br>
									<?php esc_html_e('Please note that this option works for bulit-in payment methods only: PayPal and Stripe.','car-rental-booking-system'); ?>
								</span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment_1'); ?>" name="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_customer_email_notification_after_payment'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment_0'); ?>" name="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_customer_email_notification_after_payment'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_customer_email_notification_after_payment_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Enable/disbale sending wooCommerce e-mail message with notification about new booking:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification_1'); ?>" name="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_woocommerce_email_notification'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification_0'); ?>" name="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_new_woocommerce_email_notification'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('booking_new_woocommerce_email_notification_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Nexmo SMS notifications','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('Enter details to be notified via SMS about new booking through <a href="https://www.nexmo.com/" target="_blank">Nexmo</a>.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('nexmo_sms_enable_1'); ?>" name="<?php CRBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('nexmo_sms_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('nexmo_sms_enable_0'); ?>" name="<?php CRBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('nexmo_sms_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API key:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('nexmo_sms_api_key'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Secret API key:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('nexmo_sms_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key_secret']); ?>"/>
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Sender name:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('nexmo_sms_sender_name'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_sender_name']); ?>"/>
                            </div>                               
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Recipient phone number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('nexmo_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_recipient_phone_number']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('nexmo_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_message']); ?>"/>
                            </div>                              
                        </li>
                        <li>
                            <h5><?php esc_html_e('Twilio SMS notifications','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('Enter details to be notified via SMS about new booking through <a href="https://www.twilio.com" target="_blank">Twilio</a>.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('twilio_sms_enable_1'); ?>" name="<?php CRBSHelper::getFormName('twilio_sms_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('twilio_sms_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('twilio_sms_enable_0'); ?>" name="<?php CRBSHelper::getFormName('twilio_sms_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('twilio_sms_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API SID:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('twilio_sms_api_sid'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_sid']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API token:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('twilio_sms_api_token'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_token']); ?>"/>
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Sender phone number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('twilio_sms_sender_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_sender_phone_number']); ?>"/>
                            </div>                               
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Recipient phone number:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('twilio_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_recipient_phone_number']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('twilio_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_message']); ?>"/>
                            </div>                              
                        </li>
						<li>
                            <h5><?php esc_html_e('Telegram notifications','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php _e('Enter details to be notified about new booking through <a href="https://telegram.org/" target="_blank">Telegram Messenger</a>.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('telegram_enable_1'); ?>" name="<?php CRBSHelper::getFormName('telegram_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['telegram_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('telegram_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('telegram_enable_0'); ?>" name="<?php CRBSHelper::getFormName('telegram_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['telegram_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('telegram_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Token:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('telegram_token'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_token']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Group ID:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('telegram_group_id'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_group_id']); ?>"/>
                            </div>
							<div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','car-rental-booking-system'); ?></span>
                                <input type="text" name="<?php CRBSHelper::getFormName('telegram_message'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_message']); ?>"/>
                            </div>
                        </li>
                    </ul>
                </div>    
                <div id="meta-box-location-6">
                    <ul class="to-form-field-list">
                       <li>
                            <h5><?php esc_html_e('Google Calendar','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enable or disable integration with Google Calendar.','car-rental-booking-system'); ?></span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_calendar_enable_1'); ?>" name="<?php CRBSHelper::getFormName('google_calendar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_calendar_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('google_calendar_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_calendar_enable_0'); ?>" name="<?php CRBSHelper::getFormName('google_calendar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_calendar_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('google_calendar_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                            </div>                            
                        </li>       
                        <li>
                            <h5><?php esc_html_e('ID','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Google Calendar ID.','car-rental-booking-system'); ?></span> 
                            <div class="to-clear-fix">
                                <input type="text" name="<?php CRBSHelper::getFormName('google_calendar_id'); ?>" value="<?php echo esc_attr($this->data['meta']['google_calendar_id']); ?>"/>                                 
                            </div>                         
                        </li>
                        <li>
                            <h5><?php esc_html_e('Settings','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Copy/paste the contents of downloaded *.json file.','car-rental-booking-system'); ?></span> 
                            <div class="to-clear-fix">
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('google_calendar_settings'); ?>" id="<?php CRBSHelper::getFormName('google_calendar_settings'); ?>"><?php echo esc_html($this->data['meta']['google_calendar_settings']); ?></textarea>
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
				
				var helper=new CRBSHelper();
                helper.getMessageFromConsole();
				
                /***/
                
				var element=$('.to').themeOptionElement({init:true});
                $('#to-table-exclude-date').table();
                
                /***/
                
                $('input[name="<?php CRBSHelper::getFormName('vehicle_availability_check_type'); ?>[]"]').on('change',function()
                {
                    var value=parseInt($(this).val());
                    var checkbox=$(this).parents('li:first').find('input');
                    
                    if($.inArray(value,[2,3])>-1)
                        checkbox.last().removeAttr('checked');     
                    
                    var checked=[];
                    checkbox.each(function()
                    {
                        if($(this).is(':checked'))
                            checked.push(parseInt($(this).val(),10));
                    });
                    
                    if($.inArray(1,checked)>-1 || checked.length===0)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.last().attr('checked','checked');                        
                    }
                    
                    checkbox.button('refresh');
                });
                
                /***/
				
				element.bindBrowseMedia('.to-button-browse');
				
				/***/
				
                var helper=new CRBSHelper();
                helper.googleMapAutocompleteCreate($('#crbs_location_search'),function(place)
				{
					if(confirm('<?php esc_html_e('Do you want to fill all address details based on this location?') ?>'))
					{
						var key=
						[
							['address_street','route'],
							['address_street_number','street_number'],
							['address_postcode','postal_code'],
							['address_city','locality'],
							['address_state','administrative_area_level_1'],
							['address_country','country']
						];

						for(var i in key)
						{
							for(var j in place.address_components)
							{
								var field=$('[name="crbs_'+key[i][0]+'"]');
								
								field.val('');
								
								if(key[i][1].length)
								{
									if($.inArray(key[i][1],place.address_components[j].types)>-1)
									{
										if(key[i][1]=='country')
										{
											field.val(place.address_components[j].short_name);	
											field.dropkick('refresh');
										}
										else field.val(place.address_components[j].long_name);	
										
										break;
									}
								}							
							}
						}
						
						$('[name="crbs_coordinate_latitude"]').val(place.geometry.location.lat);
						$('[name="crbs_coordinate_longitude"]').val(place.geometry.location.lng);
					}
				});
				
				/***/
				
            });
		</script>
                        