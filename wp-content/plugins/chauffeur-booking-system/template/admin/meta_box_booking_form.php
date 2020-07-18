<?php 
		echo $this->data['nonce']; 
        
        global $post;
        $Date=new CHBSDate();
        $Length=new CHBSLength();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-booking-form-1"><?php esc_html_e('General','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-2"><?php esc_html_e('Availability','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-3"><?php esc_html_e('Payments','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-4"><?php esc_html_e('Driving zone','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-5"><?php esc_html_e('Form elements','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-6"><?php esc_html_e('Notifications','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-7"><?php esc_html_e('Google Maps','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-8"><?php esc_html_e('Google Calendar','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-9"><?php esc_html_e('Styles','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-booking-form-1">
                    <div class="ui-tabs">
                        <ul>
                            <li><a href="#meta-box-booking-form-1-1"><?php esc_html_e('Main','chauffeur-booking-system'); ?></a></li>
                            <li><a href="#meta-box-booking-form-1-2"><?php esc_html_e('Vehicles','chauffeur-booking-system'); ?></a></li>
                            <li><a href="#meta-box-booking-form-1-3"><?php esc_html_e('Locations','chauffeur-booking-system'); ?></a></li>
                            <li><a href="#meta-box-booking-form-1-4"><?php esc_html_e('Passengers','chauffeur-booking-system'); ?></a></li>
                            <li><a href="#meta-box-booking-form-1-5"><?php esc_html_e('Prices','chauffeur-booking-system'); ?></a></li>
                            <li><a href="#meta-box-booking-form-1-6"><?php esc_html_e('Look & feel','chauffeur-booking-system'); ?></a></li>
                        </ul>     
                        <div id="meta-box-booking-form-1-1">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('Shortcode','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Copy and paste the shortcode on a page.','chauffeur-booking-system'); ?></span>
                                    <div class="to-field-disabled">
<?php
        $shortcode='['.PLUGIN_CHBS_CONTEXT.'_booking_form booking_form_id="'.$post->ID.'"]';
        echo $shortcode;
?>
                                        <a href="#" class="to-copy-to-clipboard to-float-right" data-clipboard-text="<?php echo esc_attr($shortcode); ?>" data-label-on-success="<?php esc_attr_e('Copied!','chauffeur-booking-system') ?>"><?php esc_html_e('Copy','chauffeur-booking-system'); ?></a>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Service type offered','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select at least one available type of service: distance (point-to-point), hourly, flat-rate for defined routes.','chauffeur-booking-system'); ?></span>
                                    <div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['service_type'] as $index=>$value)
		{
?>
                                        <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('service_type_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('service_type_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['service_type_id'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('service_type_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Default service type','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select default service type. It will selected by default on booking form.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['service_type'] as $index=>$value)
		{
?>
                                        <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('service_type_id_default_'.$index); ?>" name="<?php CHBSHelper::getFormName('service_type_id_default'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['service_type_id_default'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('service_type_id_default_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
                                    </div>
                                </li>                         
                                <li>
                                    <h5><?php esc_html_e('Transfer type','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Enable or disable transfer type (one way, return, return - new ride) for choosen services.','chauffeur-booking-system'); ?></span>
                                    <div>
                                        <table class="to-table">
                                            <tr>
                                                <th style="width:30%">
                                                    <div>
                                                        <?php esc_html_e('Service','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Service type offered.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:70%">
                                                    <div>
                                                        <?php esc_html_e('Transfer type','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Transfer type','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Distance','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-checkbox-button">
                                                            <input type="checkbox" value="1" id="<?php CHBSHelper::getFormName('transfer_type_enable_1_1'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_1[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_1'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_1_1'); ?>"><?php esc_html_e('One way','chauffeur-booking-system'); ?></label>
                                                            <input type="checkbox" value="2" id="<?php CHBSHelper::getFormName('transfer_type_enable_1_2'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_1[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_1'],2); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_1_2'); ?>"><?php esc_html_e('Return','chauffeur-booking-system'); ?></label>
                                                            <input type="checkbox" value="3" id="<?php CHBSHelper::getFormName('transfer_type_enable_1_3'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_1[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_1'],3); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_1_3'); ?>"><?php esc_html_e('Return (new ride)','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Flat rate','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-checkbox-button">
                                                            <input type="checkbox" value="1" id="<?php CHBSHelper::getFormName('transfer_type_enable_3_1'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_3[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_3'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_3_1'); ?>"><?php esc_html_e('One way','chauffeur-booking-system'); ?></label>
                                                            <input type="checkbox" value="2" id="<?php CHBSHelper::getFormName('transfer_type_enable_3_2'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_3[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_3'],2); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_3_2'); ?>"><?php esc_html_e('Return','chauffeur-booking-system'); ?></label>
                                                            <input type="checkbox" value="3" id="<?php CHBSHelper::getFormName('transfer_type_enable_3_3'); ?>" name="<?php CHBSHelper::getFormName('transfer_type_enable_3[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['transfer_type_enable_3'],3); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('transfer_type_enable_3_3'); ?>"><?php esc_html_e('Return (new ride)','chauffeur-booking-system'); ?></label>
                                                         </div>
                                                    </div>
                                                </td>
                                            </tr>                                    
                                        </table>
                                    </div>
                                </li>                        
                                <li>
                                    <h5><?php esc_html_e('Routes','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Select routes that are available to book.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option is available for "Flat rate" service type only.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-checkbox-button">
                                        <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('route_id_0'); ?>" name="<?php CHBSHelper::getFormName('route_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_id'],-1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('route_id_0'); ?>"><?php esc_html_e('- All routes -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['route'] as $index=>$value)
		{
?>
                                        <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('route_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('route_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_id'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('route_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Routes label','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable empty (not selected) item on location list.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('route_list_item_empty_enable_1'); ?>" name="<?php CHBSHelper::getFormName('route_list_item_empty_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_list_item_empty_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('route_list_item_empty_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('route_list_item_empty_enable_0'); ?>" name="<?php CHBSHelper::getFormName('route_list_item_empty_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_list_item_empty_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('route_list_item_empty_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>    
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field"><?php esc_html_e('Label:','chauffeur-booking-system'); ?></span>
                                        <input type="text" name="<?php CHBSHelper::getFormName('route_list_item_empty_text'); ?>" value="<?php echo esc_attr($this->data['meta']['route_list_item_empty_text']); ?>"/>
                                    </div>                                    
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Booking extras','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select categories, from which addons are available to book.','chauffeur-booking-system'); ?></span>
                                    <div class="to-checkbox-button">
                                        <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('booking_extra_category_id_0'); ?>" name="<?php CHBSHelper::getFormName('booking_extra_category_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_extra_category_id'],-1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_extra_category_id_0'); ?>"><?php esc_html_e('- All extras -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['booking_extra_category'] as $index=>$value)
		{
?>
                                        <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_extra_category_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_extra_category_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_extra_category_id'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_extra_category_id_'.$index); ?>"><?php echo esc_html($value['name']); ?></label>
<?php		
		}
?>
                                    </div>
                                </li>      
                                <li>
                                    <h5><?php esc_html_e('Show booking extras categories','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable showing add-ons grouped in categories.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Please note that add-on has to be assigned to at least one category, otherwise it won\'t be displayed (when this option is enabled).','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('booking_extra_category_display_enable_1'); ?>" name="<?php CHBSHelper::getFormName('booking_extra_category_display_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_extra_category_display_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('booking_extra_category_display_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('booking_extra_category_display_enable_0'); ?>" name="<?php CHBSHelper::getFormName('booking_extra_category_display_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_extra_category_display_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('booking_extra_category_display_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Currencies','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Select available currencies.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('You can set exchange rates for each selected currency in plugin options.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('You can run booking form with particular currency by adding parameter "currency=CODE" to the query string of page on which booking form is located.','chauffeur-booking-system'); ?>
                                    </span>                        
                                    <div class="to-clear-fix">
                                        <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('currency[]'); ?>">
                                            <option value="-1" <?php CHBSHelper::selectedIf($this->data['meta']['currency'],-1); ?>><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['currency'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['currency'],$index,false)).'>'.esc_html($value['name']).'</option>';
?>
                                        </select>                                                
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Booking period','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Set range (in days, hours or minutes) during which customer can send a booking.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Eg. range 1-14 days means that customer can send a booking from tomorrow during next two weeks.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Allowed are integer values from range 0-9999. Empty values means that booking time is not limited.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('From (number of days/hours/minutes - counting from now - since when customer can send a booking):','chauffeur-booking-system'); ?></span>
                                        <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('booking_period_from'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_period_from']); ?>"/>
                                    </div>   
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('To (number of days/hours/minutes - counting from now plus number of days/hours/minutes from previous field - until when customer can send a booking):','chauffeur-booking-system'); ?></span>
                                        <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('booking_period_to'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_period_to']); ?>"/>
                                    </div>  
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('booking_period_type_1'); ?>" name="<?php CHBSHelper::getFormName('booking_period_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_period_type'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_period_type_1'); ?>"><?php esc_html_e('Days','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="2" id="<?php CHBSHelper::getFormName('booking_period_type_2'); ?>" name="<?php CHBSHelper::getFormName('booking_period_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_period_type'],2); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_period_type_2'); ?>"><?php esc_html_e('Hours','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="3" id="<?php CHBSHelper::getFormName('booking_period_type_3'); ?>" name="<?php CHBSHelper::getFormName('booking_period_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_period_type'],3); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_period_type_3'); ?>"><?php esc_html_e('Minutes','chauffeur-booking-system'); ?></label>
                                    </div>                            
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Bookings interval','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Set interval (in minutes) between bookings which contain the same vehicle.','chauffeur-booking-system'); ?></span>
                                    <div>
                                        <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('booking_vehicle_interval'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_vehicle_interval']); ?>"/>
                                    </div>   
                                </li>                                               
                                <li>
                                    <h5><?php esc_html_e('Vehicles availability','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Enable this option if you would like to prevent against sending orders which contain vehicles added to other orders with the same date/time of the ride.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable_1'); ?>" name="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['prevent_double_vehicle_booking_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable_0'); ?>" name="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['prevent_double_vehicle_booking_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('prevent_double_vehicle_booking_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Vehicles availability for the same orders','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable this option if you would like to display vehicles for orders with the same details and free seats for passengers.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This options works for fixed locations only with the same date/time.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable_1'); ?>" name="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_in_the_same_booking_passenger_sum_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable_0'); ?>" name="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_in_the_same_booking_passenger_sum_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('vehicle_in_the_same_booking_passenger_sum_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>   
                                <li>
                                    <h5><?php esc_html_e('Minimum distance','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
<?php 
        if(CHBSOption::getOption('length_unit')==2)
            esc_html_e('Minimum distance (in miles) required to send a booking.','chauffeur-booking-system'); 
        else esc_html_e('Minimum distance (in kilometers) required to send a booking.','chauffeur-booking-system');
        
        echo '<br/>';
        esc_html_e('Allowed are integer numbers from 0 to 99999.','chauffeur-booking-system');
        echo '<br/>';
        esc_html_e('This option is available for "Distance" service only.','chauffeur-booking-system');
?>
                                    </span>
<?php
        $distance=$this->data['meta']['distance_minimum'];
        if(CHBSOption::getOption('length_unit')==2)
            $distance=round($Length->convertUnit($distance,1,2),1);
?>
                                    <div><input type="text" maxlength="5" name="<?php CHBSHelper::getFormName('distance_minimum'); ?>" value="<?php echo esc_attr($distance); ?>"/></div>                                  
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Minimum duration','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Specify minimum duration (in minutes) of the ride.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Allowed are integer numbers from 0 to 999999999.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option is available for "Distance" and "Hourly" service only.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div><input type="text" maxlength="9" name="<?php CHBSHelper::getFormName('duration_minimum'); ?>" value="<?php echo esc_attr($this->data['meta']['duration_minimum']); ?>"/></div>                                  
                                </li>                                   
                                <li>
                                    <h5><?php esc_html_e('Minimum order value','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Specify minimum gross value of the order.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div><input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('order_value_minimum'); ?>" value="<?php echo esc_attr($this->data['meta']['order_value_minimum']); ?>"/></div>                                  
                                </li>                         
                                <li>
                                    <h5><?php esc_html_e('Billing details','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select default state of billing details section.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('billing_detail_state_1'); ?>" name="<?php CHBSHelper::getFormName('billing_detail_state'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['billing_detail_state'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('billing_detail_state_1'); ?>"><?php esc_html_e('Unchecked','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="2" id="<?php CHBSHelper::getFormName('billing_detail_state_2'); ?>" name="<?php CHBSHelper::getFormName('billing_detail_state'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['billing_detail_state'],2); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('billing_detail_state_2'); ?>"><?php esc_html_e('Checked','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="3" id="<?php CHBSHelper::getFormName('billing_detail_state_3'); ?>" name="<?php CHBSHelper::getFormName('billing_detail_state'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['billing_detail_state'],3); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('billing_detail_state_3'); ?>"><?php esc_html_e('Mandatory','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('States','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('List of available states separated by semicolon which will be available to choose in billing details.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div><input type="text"  name="<?php CHBSHelper::getFormName('billing_detail_list_state'); ?>" value="<?php echo esc_attr($this->data['meta']['billing_detail_list_state']); ?>"/></div>                                  
                                </li>                                    
                                <li>
                                    <h5><?php esc_html_e('Default booking status','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Default booking status of new order.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                                        <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_status_default_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_status_default_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_status_default_id'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_status_default_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>                                
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Default driver','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Driver assigned to the new bookings.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="-1" id="<?php CHBSHelper::getFormName('driver_default_id_0'); ?>" name="<?php CHBSHelper::getFormName('driver_default_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driver_default_id'],-1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('driver_default_id_0'); ?>"><?php esc_html_e('- None - ','chauffeur-booking-system'); ?></label>							
<?php
		foreach($this->data['dictionary']['driver'] as $index=>$value)
		{
?>
                                        <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('driver_default_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('driver_default_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driver_default_id'],$index); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('driver_default_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>                                
                                    </div>
                                </li>                                                
                                <li>
                                    <h5><?php esc_html_e('Server side geolocation','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable server side geolocation. This options allows e.g set valid customer country in booking form.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('You can change settings of geolocation server in "Plugin Options".','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('geolocation_server_side_enable_1'); ?>" name="<?php CHBSHelper::getFormName('geolocation_server_side_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['geolocation_server_side_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('geolocation_server_side_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('geolocation_server_side_enable_0'); ?>" name="<?php CHBSHelper::getFormName('geolocation_server_side_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['geolocation_server_side_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('geolocation_server_side_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('WooCommerce support','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable manage bookings and payments by WooCommerce plugin.','chauffeur-booking-system'); ?><br/>
                                        <?php echo sprintf(__('Please make sure that you set up "Checkout page" in <a href="%s">WooCommerce settings</a>','chauffeur-booking-system'),admin_url('admin.php?page=wc-settings&tab=advanced')); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('woocommerce_enable_1'); ?>" name="<?php CHBSHelper::getFormName('woocommerce_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('woocommerce_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('woocommerce_enable_0'); ?>" name="<?php CHBSHelper::getFormName('woocommerce_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('woocommerce_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('WooCommerce account','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable possibility to create and login via wooCommerce account.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('"Disable" means that login and register form will not be displayed.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('"Enable as option" means that both forms will be available, but logging and/or creating an account depends on user preferences.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('"Enable as mandatory" means that user have to be registered and logged before he sends a booking.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_1'); ?>" name="<?php CHBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_1'); ?>"><?php esc_html_e('Enable as option','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="2" id="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_2'); ?>" name="<?php CHBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],2); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_2'); ?>"><?php esc_html_e('Enable as mandatory','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_0'); ?>" name="<?php CHBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('woocommerce_account_enable_type_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Coupons','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Enable or disable coupons for this booking form.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('coupon_enable_1'); ?>" name="<?php CHBSHelper::getFormName('coupon_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['coupon_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('coupon_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('coupon_enable_0'); ?>" name="<?php CHBSHelper::getFormName('coupon_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['coupon_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('coupon_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Ride time multiplier','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter value (multiplier) for ride time.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Allowed are real numbers from range 0-99.99.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                         <input maxlength="5" type="text" name="<?php CHBSHelper::getFormName('ride_time_multiplier'); ?>" value="<?php echo esc_attr($this->data['meta']['ride_time_multiplier']); ?>"/>
                                   </div>	                              
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Booking title','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Title (use %s to enter booking ID).','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <input type="text" name="<?php CHBSHelper::getFormName('booking_title'); ?>" id="<?php CHBSHelper::getFormName('booking_title'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_title']); ?>"/>
                                    </div> 
                                </li>   
                            </ul>                    
                        </div>                        
                        <div id="meta-box-booking-form-1-2">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('Default vehicle','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select vehicle which has to be checked by default on booking form.','chauffeur-booking-system'); ?></span>
                                    <div class="to-clear-fix">
                                        <select name="<?php CHBSHelper::getFormName('vehicle_id_default'); ?>">
                                            <option value="-1"><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
 <?php
		foreach($this->data['dictionary']['vehicle'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle_id_default'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                        </select>
                                    </div>
                                </li>     
                                <li>
                                    <h5><?php esc_html_e('Vehicle categories','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select categories, from which vehicles are available to book.','chauffeur-booking-system'); ?></span>
                                    <div class="to-checkbox-button">
                                        <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('vehicle_category_id_0'); ?>" name="<?php CHBSHelper::getFormName('vehicle_category_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_category_id'],-1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('vehicle_category_id_0'); ?>"><?php esc_html_e('- All categories -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['vehicle_category'] as $index=>$value)
		{
?>
                                    <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('vehicle_category_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('vehicle_category_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_category_id'],$index); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('vehicle_category_id_'.$index); ?>"><?php echo esc_html($value['name']); ?></label>
<?php		
		}
?>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Vehicle filter','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable selected filters in filter bar.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-checkbox-button">
                                            <input type="checkbox" value="1" id="<?php CHBSHelper::getFormName('vehicle_filter_enable_1'); ?>" name="<?php CHBSHelper::getFormName('vehicle_filter_enable[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_filter_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('vehicle_filter_enable_1'); ?>"><?php esc_html_e('Passengers','chauffeur-booking-system'); ?></label>
                                            <input type="checkbox" value="2" id="<?php CHBSHelper::getFormName('vehicle_filter_enable_2'); ?>" name="<?php CHBSHelper::getFormName('vehicle_filter_enable[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_filter_enable'],2); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('vehicle_filter_enable_2'); ?>"><?php esc_html_e('Suitcases','chauffeur-booking-system'); ?></label>
                                            <input type="checkbox" value="3" id="<?php CHBSHelper::getFormName('vehicle_filter_enable_3'); ?>" name="<?php CHBSHelper::getFormName('vehicle_filter_enable[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_filter_enable'],3); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('vehicle_filter_enable_3'); ?>"><?php esc_html_e('Standard','chauffeur-booking-system'); ?></label>
                                            <input type="checkbox" value="4" id="<?php CHBSHelper::getFormName('vehicle_filter_enable_4'); ?>" name="<?php CHBSHelper::getFormName('vehicle_filter_enable[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_filter_enable'],4); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('vehicle_filter_enable_4'); ?>"><?php esc_html_e('Type','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Vehicles sorting','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select sorting options of vehicles in booking form.','chauffeur-booking-system'); ?></span>
                                    <div class="to-clear-fix">
                                        <select name="<?php CHBSHelper::getFormName('vehicle_sorting_type'); ?>">
 <?php
		foreach($this->data['dictionary']['vehicle_sorting_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle_sorting_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                        </select>
                                    </div>
                                </li>    
                                <li>
                                    <h5><?php esc_html_e('Vehicle pagination','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter number of vehicles displayed on single page.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Value 0 means that all vehicles will be displayed on single page and pagination won\'t be displayed.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('vehicle_pagination_vehicle_per_page'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_pagination_vehicle_per_page']); ?>"/>
                                    </div> 
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Limit number of vehicles','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Limit number of displayed vehicles.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Value 0 means that all vehicles will be displayed on single page.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('vehicle_limit'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_limit']); ?>"/>
                                    </div> 
                                </li>                                 
                            </ul>
                        </div>
                        <div id="meta-box-booking-form-1-3">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('Base location','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Company base location.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('If it is set up, then the cost of ride from base location to pick up location will be added to sum of the order.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option is available for "Distance" and "Hourly" service type.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <input type="text" name="<?php CHBSHelper::getFormName('base_location'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location']); ?>"/>
                                        <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location_coordinate_lat']); ?>"/>
                                        <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location_coordinate_lng']); ?>"/>
                                    </div>                                  
                                </li>                                                 
                                <li>
                                    <h5><?php esc_html_e('Fixed locations','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter fixed pickup/drop off location for selected service.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <table class="to-table">
                                            <tr>
                                                <th style="width:20%">
                                                    <div>
                                                        <?php esc_html_e('Service','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Service type offered.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:40%">
                                                    <div>
                                                        <?php esc_html_e('Pickup location','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Pickup location.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:40%">
                                                    <div>
                                                        <?php esc_html_e('Drop off location','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Drop off location.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Distance','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('location_fixed_pickup_service_type_1[]'); ?>">
                                                            <option value="-1" <?php CHBSHelper::selectedIf($this->data['meta']['location_fixed_pickup_service_type_1'],-1); ?>><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['location_fixed_pickup_service_type_1'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                                        </select>                                                
                                                     </div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('location_fixed_dropoff_service_type_1[]'); ?>">
                                                            <option value="-1" <?php CHBSHelper::selectedIf($this->data['meta']['location_fixed_dropoff_service_type_1'],-1); ?>><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['location_fixed_dropoff_service_type_1'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                                        </select>                                                
                                                     </div>
                                                </td>
                                            </tr>                                    
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Hourly','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                         <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('location_fixed_pickup_service_type_2[]'); ?>">
                                                             <option value="-1" <?php CHBSHelper::selectedIf($this->data['meta']['location_fixed_pickup_service_type_2'],-1); ?>><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['location_fixed_pickup_service_type_2'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                         <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('location_fixed_dropoff_service_type_2[]'); ?>">
                                                             <option value="-1" <?php CHBSHelper::selectedIf($this->data['meta']['location_fixed_dropoff_service_type_2'],-1); ?>><?php esc_html_e('- None -','chauffeur-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['location_fixed_dropoff_service_type_2'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>                                        
                                        </table>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Fixed locations label','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable empty (not selected) item on fixed location list.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable_1'); ?>" name="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['location_fixed_list_item_empty_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable_0'); ?>" name="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['location_fixed_list_item_empty_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field"><?php esc_html_e('Label:','chauffeur-booking-system'); ?></span>
                                        <input type="text" name="<?php CHBSHelper::getFormName('location_fixed_list_item_empty_text'); ?>" value="<?php echo esc_attr($this->data['meta']['location_fixed_list_item_empty_text']); ?>"/>
                                    </div>
                                </li>      
                                <li>
                                    <h5><?php esc_html_e('Fixed locations autocomplete','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable autocomplete feature on fixed location lists.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable_1'); ?>" name="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['location_fixed_autocomplete_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable_0'); ?>" name="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['location_fixed_autocomplete_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('location_fixed_autocomplete_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>    
                                <li>
                                    <h5><?php esc_html_e('Waypoints','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable possibility of adding waypoints by customer.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option is available for "Distance" mode only if fixed locations (pickup and/or drop-off) are not defined.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('waypoint_enable_1'); ?>" name="<?php CHBSHelper::getFormName('waypoint_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['waypoint_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('waypoint_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('waypoint_enable_0'); ?>" name="<?php CHBSHelper::getFormName('waypoint_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['waypoint_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('waypoint_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>   
                                <li>
                                    <h5><?php esc_html_e('Location autocomplete address type','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Select type of address.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_autosugestion_address_type_1'); ?>" name="<?php CHBSHelper::getFormName('google_autosugestion_address_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_autosugestion_address_type'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('google_autosugestion_address_type_1'); ?>"><?php esc_html_e('Full address','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="2" id="<?php CHBSHelper::getFormName('google_autosugestion_address_type_0'); ?>" name="<?php CHBSHelper::getFormName('google_autosugestion_address_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_autosugestion_address_type'],2); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('google_autosugestion_address_type_0'); ?>"><?php esc_html_e('Short address','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>  
                            </ul>
                        </div>
                        <div id="meta-box-booking-form-1-4">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('Passengers','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable possibility to set number of passengers (adults, children) for a particular service types.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option will work correctly for the "Variable" price type only. You have to set price per passenger (adult,children).','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <table class="to-table">
                                            <tr>
                                                <th style="width:20%">
                                                    <div>
                                                        <?php esc_html_e('Service','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Service type offered.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:40%">
                                                    <div>
                                                        <?php esc_html_e('Adults','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Adults.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:40%">
                                                    <div>
                                                        <?php esc_html_e('Children','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Children.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Distance','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_1'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_1'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_1_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_1'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_1'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_1_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>                                    
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Hourly','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_2'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_2'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_2_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_2'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_2'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_2_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>  
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Flat rate','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_3'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_adult_enable_service_type_3'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_adult_enable_service_type_3_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3_1'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_3'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3_0'); ?>" name="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['passenger_children_enable_service_type_3'],0); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('passenger_children_enable_service_type_3_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>  
                                        </table>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Default number of passengers','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter number (or leave empty) default number of passengers.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Adults:','chauffeur-booking-system'); ?></span>
                                        <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_adult_default_number'); ?>" value="<?php echo esc_attr($this->data['meta']['passenger_adult_default_number']); ?>"/>
                                    </div> 
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Children:','chauffeur-booking-system'); ?></span>
                                        <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_children_default_number'); ?>" value="<?php echo esc_attr($this->data['meta']['passenger_children_default_number']); ?>"/>
                                    </div> 
                                </li>                        
                                <li>
                                    <h5><?php esc_html_e('Show price per passengers','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Show price per single passenger next to vehicle in second step.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('show_price_per_single_passenger_1'); ?>" name="<?php CHBSHelper::getFormName('show_price_per_single_passenger'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['show_price_per_single_passenger'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('show_price_per_single_passenger_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('show_price_per_single_passenger_0'); ?>" name="<?php CHBSHelper::getFormName('show_price_per_single_passenger'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['show_price_per_single_passenger'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('show_price_per_single_passenger_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>    
                            </ul>
                        </div>
                        <div id="meta-box-booking-form-1-5">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('Calculation method','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Select one of available price calculation method of services.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Calculation by "Distance" and "Distance + Time" will not work if passengers functionality is enabled or vehicle uses fixed prices.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <table class="to-table">
                                            <tr>
                                                <th style="width:20%">
                                                    <div>
                                                        <?php esc_html_e('Service','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Service type offered.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th style="width:80%">
                                                    <div>
                                                        <?php esc_html_e('Method','chauffeur-booking-system'); ?>
                                                        <span class="to-legend">
                                                            <?php esc_html_e('Calculation method.','chauffeur-booking-system'); ?>
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Distance','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('calculation_method_service_type_1_1'); ?>" name="<?php CHBSHelper::getFormName('calculation_method_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['calculation_method_service_type_1'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('calculation_method_service_type_1_1'); ?>"><?php esc_html_e('Distance','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="2" id="<?php CHBSHelper::getFormName('calculation_method_service_type_1_2'); ?>" name="<?php CHBSHelper::getFormName('calculation_method_service_type_1'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['calculation_method_service_type_1'],2); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('calculation_method_service_type_1_2'); ?>"><?php esc_html_e('Distance + Time','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>                                    
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Hourly','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix"><?php esc_html_e('No options available for this service type.','chauffeur-booking-system'); ?></div>
                                                </td>
                                            </tr>  
                                            <tr>
                                                <td>
                                                    <div><?php esc_html_e('Flat rate','chauffeur-booking-system'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="to-clear-fix">
                                                        <div class="to-radio-button">
                                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('calculation_method_service_type_3_1'); ?>" name="<?php CHBSHelper::getFormName('calculation_method_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['calculation_method_service_type_3'],1); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('calculation_method_service_type_3_1'); ?>"><?php esc_html_e('Distance','chauffeur-booking-system'); ?></label>
                                                            <input type="radio" value="2" id="<?php CHBSHelper::getFormName('calculation_method_service_type_3_2'); ?>" name="<?php CHBSHelper::getFormName('calculation_method_service_type_3'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['calculation_method_service_type_3'],2); ?>/>
                                                            <label for="<?php CHBSHelper::getFormName('calculation_method_service_type_3_2'); ?>"><?php esc_html_e('Distance + Time','chauffeur-booking-system'); ?></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>  
                                        </table>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Hide fees','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Hide all addtional fees (inital, delivery) in booking summary and include them to the price of selected vehicle.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('booking_summary_hide_fee_1'); ?>" name="<?php CHBSHelper::getFormName('booking_summary_hide_fee'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_summary_hide_fee'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_summary_hide_fee_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('booking_summary_hide_fee_0'); ?>" name="<?php CHBSHelper::getFormName('booking_summary_hide_fee'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_summary_hide_fee'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('booking_summary_hide_fee_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Hide prices','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Hide all prices and summary.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('If this feature is enabled, all prices and payment methods are hidden for customers. Please note that support for wooCommerce are disabled in this case.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('price_hide_1'); ?>" name="<?php CHBSHelper::getFormName('price_hide'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['price_hide'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('price_hide_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('price_hide_0'); ?>" name="<?php CHBSHelper::getFormName('price_hide'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['price_hide'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('price_hide_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Split order sum','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Split order sum to net and tax value in summary section.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('order_sum_split_1'); ?>" name="<?php CHBSHelper::getFormName('order_sum_split'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['order_sum_split'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('order_sum_split_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('order_sum_split_0'); ?>" name="<?php CHBSHelper::getFormName('order_sum_split'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['order_sum_split'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('order_sum_split_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>  
                                <li>
                                    <h5><?php esc_html_e('Show net prices and hide tax','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Show net prices and hide tax - tax value will be displayed in last step only.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('show_net_price_hide_tax_1'); ?>" name="<?php CHBSHelper::getFormName('show_net_price_hide_tax'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['show_net_price_hide_tax'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('show_net_price_hide_tax_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('show_net_price_hide_tax_0'); ?>" name="<?php CHBSHelper::getFormName('show_net_price_hide_tax'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['show_net_price_hide_tax'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('show_net_price_hide_tax_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>                                 
                            </ul>
                        </div>
                        <div id="meta-box-booking-form-1-6">
                            <ul class="to-form-field-list">
                                <li>
                                    <h5><?php esc_html_e('"Choose a Vehicle" step','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable second step named "Choose a Vehicle" in booking form.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Please note, that this option is available if you have defined single vehicle only.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Please also note, that settings related with vehicles availability are ignored in this case.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('step_second_enable_1'); ?>" name="<?php CHBSHelper::getFormName('step_second_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['step_second_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('step_second_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('step_second_enable_0'); ?>" name="<?php CHBSHelper::getFormName('step_second_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['step_second_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('step_second_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('"Thank You" page','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable "Thank You" page in booking form.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Please note, that disabling this page is available only if wooCommerce support is enabled.','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('Then, customer is redirected to checkout page without information, that order has been sent.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('thank_you_page_enable_1'); ?>" name="<?php CHBSHelper::getFormName('thank_you_page_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['thank_you_page_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('thank_you_page_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('thank_you_page_enable_0'); ?>" name="<?php CHBSHelper::getFormName('thank_you_page_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['thank_you_page_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('thank_you_page_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('"Back to home" button on "Thank you" page','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter URL address and label for this button.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Label:','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" name="<?php CHBSHelper::getFormName('thank_you_page_button_back_to_home_label'); ?>" value="<?php echo esc_attr($this->data['meta']['thank_you_page_button_back_to_home_label']); ?>"/>
                                        </div>                     
                                    </div>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('URL address:','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" name="<?php CHBSHelper::getFormName('thank_you_page_button_back_to_home_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['thank_you_page_button_back_to_home_url_address']); ?>"/>
                                        </div>                     
                                    </div>
                                </li>                                 
                                <li>
                                    <h5><?php esc_html_e('Form preloader','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter properties of preloader.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('form_preloader_enable_1'); ?>" name="<?php CHBSHelper::getFormName('form_preloader_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('form_preloader_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('form_preloader_enable_0'); ?>" name="<?php CHBSHelper::getFormName('form_preloader_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('form_preloader_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Image:','chauffeur-booking-system'); ?></span>
                                        <input type="text" name="<?php CHBSHelper::getFormName('form_preloader_image_src'); ?>" id="<?php CHBSHelper::getFormName('form_preloader_image_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['form_preloader_image_src']); ?>"/>
                                        <input type="button" name="<?php CHBSHelper::getFormName('form_preloader_image_src_browse'); ?>" id="<?php CHBSHelper::getFormName('form_preloader_image_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                                    </div>  
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Background opacity:','chauffeur-booking-system'); ?></span>
                                        <div id="<?php CHBSHelper::getFormName('form_preloader_background_opacity'); ?>"></div>
                                        <input type="text" name="<?php CHBSHelper::getFormName('form_preloader_background_opacity'); ?>" id="<?php CHBSHelper::getFormName('form_preloader_background_opacity'); ?>" class="to-slider-range" readonly/>
                                    </div>	     
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Background color:','chauffeur-booking-system'); ?></span>
                                        <div class="to-clear-fix">	
                                            <input type="text" class="to-color-picker" id="<?php CHBSHelper::getFormName('form_preloader_background_color'); ?>" name="<?php CHBSHelper::getFormName('form_preloader_background_color'); ?>" value="<?php echo esc_attr($this->data['meta']['form_preloader_background_color']); ?>"/>
                                        </div>
                                    </div>                                    
                                </li>   
                                <li>
                                    <h5><?php esc_html_e('Top navigation','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php echo __('Enable or disable top navigation.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('navigation_top_enable_1'); ?>" name="<?php CHBSHelper::getFormName('navigation_top_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['navigation_top_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('navigation_top_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('navigation_top_enable_0'); ?>" name="<?php CHBSHelper::getFormName('navigation_top_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['navigation_top_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('navigation_top_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>                                
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Icons in fields','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable showing icons in fields of booking form.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('icon_field_enable_1'); ?>" name="<?php CHBSHelper::getFormName('icon_field_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['icon_field_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('icon_field_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('icon_field_enable_0'); ?>" name="<?php CHBSHelper::getFormName('icon_field_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['icon_field_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('icon_field_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>                                 
                                <li>
                                    <h5><?php esc_html_e('Visibility of right panel in step #1','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php echo __('Google Maps and ride info visibility.','chauffeur-booking-system'); ?><br/>
                                        <?php echo __('Please note that this option doesn\'t disable map. It hides map only.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('step_1_right_panel_visibility_1'); ?>" name="<?php CHBSHelper::getFormName('step_1_right_panel_visibility'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['step_1_right_panel_visibility'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('step_1_right_panel_visibility_1'); ?>"><?php esc_html_e('Show','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('step_1_right_panel_visibility_0'); ?>" name="<?php CHBSHelper::getFormName('step_1_right_panel_visibility'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['step_1_right_panel_visibility'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('step_1_right_panel_visibility_0'); ?>"><?php esc_html_e('Hide','chauffeur-booking-system'); ?></label>
                                        </div>                                
                                    </div>
                                </li>    
                                <li>
                                    <h5><?php esc_html_e('Vehicles description','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Show or hide vehicles description (info) by default in step #2 of booking form.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('vehicle_more_info_default_show_1'); ?>" name="<?php CHBSHelper::getFormName('vehicle_more_info_default_show'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_more_info_default_show'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('vehicle_more_info_default_show_1'); ?>"><?php esc_html_e('Show','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('vehicle_more_info_default_show_0'); ?>" name="<?php CHBSHelper::getFormName('vehicle_more_info_default_show'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_more_info_default_show'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('vehicle_more_info_default_show_0'); ?>"><?php esc_html_e('Hide','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>  
                                
                                <li>
                                    <h5><?php esc_html_e('Extra time','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Choose whether you want to offer the option of extra time (in hours).','chauffeur-booking-system'); ?><br/>
                                        <?php esc_html_e('This option is available for "Distance" and "Flat rate" services only.','chauffeur-booking-system'); ?>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('extra_time_enable_1'); ?>" name="<?php CHBSHelper::getFormName('extra_time_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['extra_time_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('extra_time_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('extra_time_enable_0'); ?>" name="<?php CHBSHelper::getFormName('extra_time_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['extra_time_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('extra_time_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Specify the minimum (integer value from 0 to 9999) and maximum (integer value from 1 to 9999) extra time in selected time unit:','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('extra_time_range_min'); ?>" value="<?php echo esc_attr($this->data['meta']['extra_time_range_min']); ?>"/>
                                        </div>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('extra_time_range_max'); ?>" value="<?php echo esc_attr($this->data['meta']['extra_time_range_max']); ?>"/>
                                        </div>
                                    </div>                          
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Step (integer value from 1 to 9999):','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('extra_time_step'); ?>" value="<?php echo esc_attr($this->data['meta']['extra_time_step']); ?>"/>
                                        </div>
                                    </div> 
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Unit:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('extra_time_unit_1'); ?>" name="<?php CHBSHelper::getFormName('extra_time_unit'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['extra_time_unit'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('extra_time_unit_1'); ?>"><?php esc_html_e('Minutes','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('extra_time_unit_2'); ?>" name="<?php CHBSHelper::getFormName('extra_time_unit'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['extra_time_unit'],2); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('extra_time_unit_2'); ?>"><?php esc_html_e('Hours','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Duration','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Rental time of the vehicle (in hours).','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Specify the minimum (integer value from 1 to 9999) and maximum (integer value from 1 to 9999) rental time of the vehicle:','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('duration_min'); ?>" value="<?php echo esc_attr($this->data['meta']['duration_min']); ?>"/>
                                        </div>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('duration_max'); ?>" value="<?php echo esc_attr($this->data['meta']['duration_max']); ?>"/>
                                        </div>
                                    </div>                          
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Step (integer value from 1 to 9999):','chauffeur-booking-system'); ?></span>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('duration_step'); ?>" value="<?php echo esc_attr($this->data['meta']['duration_step']); ?>"/>
                                        </div>
                                    </div>                                  
                                </li>    
                                <li>
                                    <h5><?php esc_html_e('Timepicker','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Timepicker settings: dropdown list status and interval.','chauffeur-booking-system'); ?><br/>
                                    </span>
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field"><?php esc_html_e('Dropdown list stautus:','chauffeur-booking-system'); ?></span>
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable_1'); ?>" name="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['timepicker_dropdown_list_enable'],1); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable_0'); ?>" name="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['timepicker_dropdown_list_enable'],0); ?>/>
                                            <label for="<?php CHBSHelper::getFormName('timepicker_dropdown_list_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                        </div>
                                    </div>
                                    <div class="to-clear-fix">
                                        <span class="to-legend-field">
                                            <?php esc_html_e('Interval - the amount of time, in minutes, between each item in the dropdown.','chauffeur-booking-system'); ?>
                                            <?php esc_html_e('Allowed are integer values from 1 to 9999.','chauffeur-booking-system'); ?>
                                        </span>
                                        <div>
                                            <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('timepicker_step'); ?>" value="<?php echo esc_attr($this->data['meta']['timepicker_step']); ?>"/>                               
                                        </div>
                                    </div>
                                </li>                                 
                                <li>
                                    <h5><?php esc_html_e('Sticky summary sidebar','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Enable or disable sticky option for summary sidebar.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable_1'); ?>" name="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['summary_sidebar_sticky_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable_0'); ?>" name="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['summary_sidebar_sticky_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('summary_sidebar_sticky_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('Scroll after selecting a vehicle','chauffeur-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Scroll user to booking add-ons section after selecting a vehicle.','chauffeur-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_1'); ?>" name="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable'],1); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_0'); ?>" name="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable'],0); ?>/>
                                        <label for="<?php CHBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                    </div>
                                </li>  
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="meta-box-booking-form-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Business hours','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Specify working days/hours.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Leave all fields empty if booking is not available for selected day.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <table class="to-table">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Weekday','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Day of the week.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Start Time','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Start time.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('End Time','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('End time.','chauffeur-booking-system'); ?>
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
                                                <input type="text" class="to-timepicker-custom" maxlength="5" name="<?php CHBSHelper::getFormName('business_hour['.$i.'][0]'); ?>" id="<?php CHBSHelper::getFormName('business_hour['.$i.'][0]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($this->data['meta']['business_hour'][$i]['start'])); ?>" title="<?php esc_attr_e('Enter start time.','chauffeur-booking-system'); ?>"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div>								
                                                <input type="text" class="to-timepicker-custom" maxlength="5" name="<?php CHBSHelper::getFormName('business_hour['.$i.'][1]'); ?>" id="<?php CHBSHelper::getFormName('business_hour['.$i.'][1]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($this->data['meta']['business_hour'][$i]['stop'])); ?>" title="<?php esc_attr_e('Enter end time.','chauffeur-booking-system'); ?>"/>
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
                            <h5><?php esc_html_e('Exclude dates','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify dates not available for booking. Past (or invalid date ranges) will be removed during saving.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-availability-exclude-date">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Start Date','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Start date.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('End Date','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('End date.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude_start[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude_stop[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>	
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
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
                                                <input type="text" class="to-datepicker-custom" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['start'])); ?>" name="<?php CHBSHelper::getFormName('date_exclude_start[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['stop'])); ?>" name="<?php CHBSHelper::getFormName('date_exclude_stop[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>	
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>							
<?php
			}
		}
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>    
                <div id="meta-box-booking-form-3">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Payment selection','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Set payment method as mandatory to select by customer.','chauffeur-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('payment_mandatory_enable_1'); ?>" name="<?php CHBSHelper::getFormName('payment_mandatory_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_mandatory_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_mandatory_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('payment_mandatory_enable_0'); ?>" name="<?php CHBSHelper::getFormName('payment_mandatory_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_mandatory_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_mandatory_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Payment processing','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable possibility of paying by booking form.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Disabling this option means that customer can choose payment method, but he won\'t be able to pay.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('payment_processing_enable_1'); ?>" name="<?php CHBSHelper::getFormName('payment_processing_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_processing_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_processing_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('payment_processing_enable_0'); ?>" name="<?php CHBSHelper::getFormName('payment_processing_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_processing_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_processing_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>
                        </li>     
                        <li>
                            <h5><?php esc_html_e('WooCommerce payments on step #3','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable possibility to choose wooCommerce payment method in step #3.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('This option is available if wooCommerce support is enabled.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable_1'); ?>" name="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_woocommerce_step_3_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable_0'); ?>" name="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_woocommerce_step_3_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_woocommerce_step_3_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Deposit','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable deposit.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('This option is not available if wooCommerce support is enabled.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('payment_deposit_enable_1'); ?>" name="<?php CHBSHelper::getFormName('payment_deposit_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_deposit_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('payment_deposit_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('payment_deposit_enable_0'); ?>" name="<?php CHBSHelper::getFormName('payment_deposit_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_deposit_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('payment_deposit_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                    
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Percentage value of the deposit:','chauffeur-booking-system'); ?></span>
                                <div id="<?php CHBSHelper::getFormName('payment_deposit_value'); ?>"></div>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_deposit_value'); ?>" id="<?php CHBSHelper::getFormName('payment_deposit_value'); ?>" class="to-slider-range" readonly/>
                            </div>	
                        </li>    
                        <li>
                            <h5><?php esc_html_e('Payment','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select one or more built-in payment methods available in this booking form. For some of them you have to enter additional settings.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Please note, that these methods will not be available if the wooCommerce support is enabled.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['payment'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('payment_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('payment_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('payment_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>							
<?php		
		}
?>
                            </div>	
                        </li>
                        <li id="to-payment-id-2">
                            <h5><?php esc_html_e('Stripe','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for Stripe gateway.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Secret API key:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_stripe_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_secret']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Publishable API key:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_stripe_api_key_publishable'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_publishable']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('URL address of redirect after payment:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_stripe_redirect_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_redirect_url_address']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_stripe_logo_src'); ?>" id="<?php CHBSHelper::getFormName('payment_stripe_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_stripe_logo_src']); ?>"/>
                                <input type="button" name="<?php CHBSHelper::getFormName('payment_stripe_logo_src_browse'); ?>" id="<?php CHBSHelper::getFormName('payment_stripe_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                            </div>
                        </li>
                        <li id="to-payment-id-3">
                            <h5><?php esc_html_e('PayPal','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for PayPal gateway.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('E-mail address:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_paypal_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_paypal_email_address']); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Enable sandbox mode:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>" name="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>" name="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                  
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_paypal_logo_src'); ?>" id="<?php CHBSHelper::getFormName('payment_paypal_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_paypal_logo_src']); ?>"/>
                                <input type="button" name="<?php CHBSHelper::getFormName('payment_paypal_logo_src_browse'); ?>" id="<?php CHBSHelper::getFormName('payment_paypal_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                            </div>
                        </li> 
                        <li id="to-payment-id-4">
                            <h5><?php esc_html_e('Wire transfer','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for wire transfer.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Additional information for customer:','chauffeur-booking-system'); ?></span>
                                <textarea rows="1" cols="1" name="<?php CHBSHelper::getFormName('payment_wire_transfer_info'); ?>"><?php echo esc_html($this->data['meta']['payment_wire_transfer_info']); ?></textarea>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_wire_transfer_logo_src'); ?>" id="<?php CHBSHelper::getFormName('payment_wire_transfer_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_wire_transfer_logo_src']); ?>"/>
                                <input type="button" name="<?php CHBSHelper::getFormName('payment_wire_transfer_logo_src_browse'); ?>" id="<?php CHBSHelper::getFormName('payment_wire_transfer_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                            </div>
                        </li> 
                        <li>
                            <h5><?php esc_html_e('Cash','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter settings for cash.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Logo:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('payment_cash_logo_src'); ?>" id="<?php CHBSHelper::getFormName('payment_cash_logo_src'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['payment_cash_logo_src']); ?>"/>
                                <input type="button" name="<?php CHBSHelper::getFormName('payment_cash_logo_src_browse'); ?>" id="<?php CHBSHelper::getFormName('payment_cash_logo_src_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                            </div>
                        </li>                           
                    </ul>
                </div>
                <div id="meta-box-booking-form-4">
                   <ul class="to-form-field-list"> 
                        <li>
                            <h5><?php esc_html_e('Driving zone','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable restriction of driving zone to selected areas.','chauffeur-booking-system'); ?><br/>
                            </span>   
                            <div class="to-clear-fix">
                                <table class="to-table">
                                    <tr>
                                        <th style="width:20%">
                                            <div></div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Pickup location','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Settings for pickup location.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('Drop off location','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Settings for return location.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>                                
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Status','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-radio-button">
                                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable_1'); ?>" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driving_zone_restriction_pickup_location_enable'],1); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable_0'); ?>" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driving_zone_restriction_pickup_location_enable'],0); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                </div>                                                
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <div class="to-radio-button">
                                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable_1'); ?>" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driving_zone_restriction_dropoff_location_enable'],1); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable_0'); ?>" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['driving_zone_restriction_dropoff_location_enable'],0); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                                </div>                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Restriction to country','chauffeur-booking-system'); ?><br>
                                                <span class="to-legend-field"><?php esc_html_e('Select (max. 5) countries','chauffeur-booking-system'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_country[]'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_country'); ?>">
<?php
		echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['meta']['driving_zone_restriction_pickup_location_country'],-1,false)).'>'.esc_html__(' - Not set -','chauffeur-booking-system').'</option>';
		foreach($this->data['dictionary']['country'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['driving_zone_restriction_pickup_location_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                                </select>  
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select multiple="multiple" class="to-dropkick-disable" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_country[]'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_country'); ?>">
<?php
		echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['meta']['driving_zone_restriction_dropoff_location_country'],-1,false)).'>'.esc_html__(' - Not set -','chauffeur-booking-system').'</option>';
		foreach($this->data['dictionary']['country'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['driving_zone_restriction_dropoff_location_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                                </select>                                                  
                                            </div>
                                        </td>
                                    </tr>                                      
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Restriction to area','chauffeur-booking-system'); ?><br>
                                                <span class="to-legend-field"><?php esc_html_e('Address and radius in kilometers','chauffeur-booking-system'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <input type="text" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_pickup_location_area']); ?>"/>
                                                <input type="text" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area_radius'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_pickup_location_area_radius']); ?>" maxlength="5" class="to-margin-top-10"/>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area_coordinate_lat'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_pickup_location_area_coordinate_lat']); ?>" class="to-coordinate-lat"/>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area_coordinate_lng'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_pickup_location_area_coordinate_lng']); ?>" class="to-coordinate-lng"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input type="text" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_dropoff_location_area']); ?>"/>
                                                <input type="text" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area_radius'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_dropoff_location_area_radius']); ?>" maxlength="5" class="to-margin-top-10"/>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area_coordinate_lat'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_dropoff_location_area_coordinate_lat']); ?>" class="to-coordinate-lat"/>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area_coordinate_lng'); ?>" id="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['driving_zone_restriction_dropoff_location_area_coordinate_lng']); ?>" class="to-coordinate-lng"/>
                                            </div>
                                        </td>
                                    </tr>                                      
                                </table>
                            </div>
                        </li>  
                   </ul>
                </div>
                <div id="meta-box-booking-form-5">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Panels','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of user defined panels (group of fields) used in client form.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Default tabs "Contact details" and "Billing address" cannot be modified.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-field">
                                    <tr>
                                        <th style="width:85%">
                                            <div>
                                                <?php esc_html_e('Label','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Label of the panel.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:18%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
                                                <input type="text" name="<?php CHBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>
<?php
        if(isset($this->data['meta']['form_element_panel']))
        {
            foreach($this->data['meta']['form_element_panel'] as $panelValue)
            {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="hidden" value="<?php echo esc_attr($panelValue['id']); ?>" name="<?php CHBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($panelValue['label']); ?>" name="<?php CHBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>     
<?php              
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>                
                        </li>
                        <li>
                            <h5><?php esc_html_e('Fields','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of user defined fields used in client form.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Default fields located in tabs "Contact details" and "Billing address" cannot be modified.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-panel">
                                    <tr>
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Label','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Label of the field.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:5%">
                                            <div>
                                                <?php esc_html_e('Mandatory','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Mandatory.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Error message','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Error message displayed in tooltip when field is empty.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>     
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Panel','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Panel in which field has to be located.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Service types','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Services to which field has to be assigned.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>    
                                        <th style="width:5%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div class="to-clear-fix">
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('form_element_field[id][]'); ?>"/>
                                                <input type="text" name="<?php CHBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('form_element_field[mandatory][]'); ?>" class="to-dropkick-disable" id="form_element_field_mandatory">
                                                    <option value="1"><?php esc_html_e('Yes','chauffeur-booking-system'); ?></option>
                                                    <option value="0"><?php esc_html_e('No','chauffeur-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" name="<?php CHBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>     
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('form_element_field[panel_id][]'); ?>" id="form_element_field_panel_id" class="to-dropkick-disable">
<?php
        foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
            echo '<option value="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</option>';
?>
                                                </select>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">                                            
                                                <select name="<?php CHBSHelper::getFormName('form_element_field[service_type_id_enable][]'); ?>" id="form_element_field_service_type_id_enable" class="to-dropkick-disable chbs-service-type-id-enable" multiple="multiple" size="<?php echo (int)count($this->data['dictionary']['service_type']); ?>">
<?php
		foreach($this->data['dictionary']['service_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" selected="selected">'.esc_html($value[0]).'</option>';
?>
                                                </select>
                                                <input type="hidden" value="" name="<?php CHBSHelper::getFormName('form_element_field[service_type_id_enable_hidden][]'); ?>"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>
<?php
        if(isset($this->data['meta']['form_element_field']))
        {
            foreach($this->data['meta']['form_element_field'] as $fieldValue)
            {
?>               
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <input type="hidden" value="<?php echo esc_attr($fieldValue['id']); ?>" name="<?php CHBSHelper::getFormName('form_element_field[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($fieldValue['label']); ?>" name="<?php CHBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select id="<?php CHBSHelper::getFormName('form_element_field_mandatory_'.$fieldValue['id']); ?>" name="<?php CHBSHelper::getFormName('form_element_field[mandatory][]'); ?>">
                                                    <option value="1" <?php CHBSHelper::selectedIf($fieldValue['mandatory'],1); ?>><?php esc_html_e('Yes','chauffeur-booking-system'); ?></option>
                                                    <option value="0" <?php CHBSHelper::selectedIf($fieldValue['mandatory'],0); ?>><?php esc_html_e('No','chauffeur-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" value="<?php echo esc_attr($fieldValue['message_error']); ?>" name="<?php CHBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('form_element_field[panel_id][]'); ?>" id="<?php CHBSHelper::getFormName('form_element_field_panel_id_'.$fieldValue['id']); ?>" >
<?php
                foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
                    echo '<option value="'.esc_attr($value['id']).'" '.(CHBSHelper::selectedIf($fieldValue['panel_id'],$value['id'],false)).'>'.esc_html($value['label']).'</option>';
?>
                                                </select>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">                                            
                                                <select name="<?php CHBSHelper::getFormName('form_element_field[service_type_id_enable][]'); ?>" id="<?php CHBSHelper::getFormName('form_element_field_service_type_id_enable_'.$fieldValue['id']); ?>"  class="to-dropkick-disable chbs-service-type-id-enable" multiple="multiple" size="<?php echo (int)count($this->data['dictionary']['service_type']); ?>">
<?php
                foreach($this->data['dictionary']['service_type'] as $index=>$value)
                {
                    $selected=false;
                    
                    if(!array_key_exists('service_type_id_enable',$fieldValue) || (!count($fieldValue['service_type_id_enable'])))
                        $selected=true;
                    else
                    {
                        if(in_array($index,$fieldValue['service_type_id_enable']))
                            $selected=true;
                    } 
                    
                    echo '<option value="'.esc_attr($index).'" '.($selected ? ' selected="selected"' : '').'>'.esc_html($value[0]).'</option>';
                }
?>
                                                </select>
                                                <input type="hidden" value="" name="<?php CHBSHelper::getFormName('form_element_field[service_type_id_enable_hidden][]'); ?>"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>           
<?php              
            }
        }
?>                                    
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>                
                        </li>
                        <li>
                            <h5><?php esc_html_e('Agreements','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of agreements needed to accept by customer before sending the booking.','chauffeur-booking-system'); ?><br/>
                                <?php echo _e('Each agreement consists of approval field (checkbox) and text of agreement.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-agreement">
                                    <tr>
                                        <th style="width:60%">
                                            <div>
                                                <?php esc_html_e('Text','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Text of the agreement.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Mandatory','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Mandatory.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="hidden" name="<?php CHBSHelper::getFormName('form_element_agreement[id][]'); ?>"/>
                                                <input type="text" name="<?php CHBSHelper::getFormName('form_element_agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('form_element_agreement[mandatory][]'); ?>" class="to-dropkick-disable" id="form_element_agreement_mandatory">
                                                    <option value="1"><?php esc_html_e('Yes','chauffeur-booking-system'); ?></option>
                                                    <option value="0"><?php esc_html_e('No','chauffeur-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>      
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>
<?php
        if(isset($this->data['meta']['form_element_agreement']))
        {
            foreach($this->data['meta']['form_element_agreement'] as $agreementValue)
            {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="hidden" value="<?php echo esc_attr($agreementValue['id']); ?>" name="<?php CHBSHelper::getFormName('form_element_agreement[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($agreementValue['text']); ?>" name="<?php CHBSHelper::getFormName('form_element_agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select id="<?php CHBSHelper::getFormName('form_element_agreement_mandatory_'.$agreementValue['id']); ?>" name="<?php CHBSHelper::getFormName('form_element_agreement[mandatory][]'); ?>">
                                                    <option value="1" <?php CHBSHelper::selectedIf((isset($agreementValue['mandatory']) ? $agreementValue['mandatory'] : 1),1); ?>><?php esc_html_e('Yes','chauffeur-booking-system'); ?></option>
                                                    <option value="0" <?php CHBSHelper::selectedIf((isset($agreementValue['mandatory']) ? $agreementValue['mandatory'] : 1),0); ?>><?php esc_html_e('No','chauffeur-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>                               
<?php
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>                
                        </li>
                    </ul>
                </div>
                <div id="meta-box-booking-form-6">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('E-mail messages','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select the sender\'s email account from which the messages will be sent (to clients and to defined recipients) with info about new bookings.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Sender e-mail account:','chauffeur-booking-system'); ?></span>
                                <select name="<?php CHBSHelper::getFormName('booking_new_sender_email_account_id'); ?>" id="<?php CHBSHelper::getFormName('booking_new_sender_email_account_id'); ?>">
<?php
		echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],-1,false)).'>'.esc_html__(' - Not set -','chauffeur-booking-system').'</option>';
		foreach($this->data['dictionary']['email_account'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                                </select>
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('List of recipients e-mail addresses separated by semicolon:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('booking_new_recipient_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_new_recipient_email_address']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Nexmo SMS notifications','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('Enter details to be notified via SMS about new booking through <a href="https://www.nexmo.com/" target="_blank">Nexmo</a>.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('nexmo_sms_enable_1'); ?>" name="<?php CHBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('nexmo_sms_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('nexmo_sms_enable_0'); ?>" name="<?php CHBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('nexmo_sms_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API key:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('nexmo_sms_api_key'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Secret API key:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('nexmo_sms_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key_secret']); ?>"/>
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Sender name:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('nexmo_sms_sender_name'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_sender_name']); ?>"/>
                            </div>                               
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Recipient phone number:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('nexmo_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_recipient_phone_number']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('nexmo_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_message']); ?>"/>
                            </div>                              
                        </li>
                        <li>
                            <h5><?php esc_html_e('Twilio SMS notifications','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php echo __('Enter details to be notified via SMS about new booking through <a href="https://www.twilio.com" target="_blank">Twilio</a>.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('twilio_sms_enable_1'); ?>" name="<?php CHBSHelper::getFormName('twilio_sms_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('twilio_sms_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('twilio_sms_enable_0'); ?>" name="<?php CHBSHelper::getFormName('twilio_sms_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('twilio_sms_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API SID:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('twilio_sms_api_sid'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_sid']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('API token:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('twilio_sms_api_token'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_token']); ?>"/>
                            </div>                                    
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Sender phone number:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('twilio_sms_sender_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_sender_phone_number']); ?>"/>
                            </div>                               
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Recipient phone number:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('twilio_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_recipient_phone_number']); ?>"/>
                            </div>                                
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('twilio_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_message']); ?>"/>
                            </div>                              
                        </li>
						<li>
                            <h5><?php esc_html_e('Telegram notifications','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php _e('Enter details to be notified about new booking through <a href="https://telegram.org/" target="_blank">Telegram Messenger</a>.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('telegram_enable_1'); ?>" name="<?php CHBSHelper::getFormName('telegram_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['telegram_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('telegram_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('telegram_enable_0'); ?>" name="<?php CHBSHelper::getFormName('telegram_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['telegram_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('telegram_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Token:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('telegram_token'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_token']); ?>"/>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Group ID:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('telegram_group_id'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_group_id']); ?>"/>
                            </div>
							<div>
                                <span class="to-legend-field"><?php esc_html_e('Message:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('telegram_message'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_message']); ?>"/>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="meta-box-booking-form-7">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Default location','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select based on which settings default location will be shown on map.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('When you choose "Browser geolocation" (requires SSL) customer will be asked about permission to locate current position. If customer agrees, browser will use his location.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('In all other cases location from text field "Fixed location" will be used by default.','chauffeur-booking-system'); ?><br/>
                            </span>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Type:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_default_location_type_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_default_location_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_default_location_type'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_default_location_type_1'); ?>"><?php esc_html_e('Browser geolocation','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="2" id="<?php CHBSHelper::getFormName('google_map_default_location_type_2'); ?>" name="<?php CHBSHelper::getFormName('google_map_default_location_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_default_location_type'],2); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_default_location_type_2'); ?>"><?php esc_html_e('Fixed location','chauffeur-booking-system'); ?></label>
                                </div>
                            </div>
                            <div>
                                <span class="to-legend-field"><?php esc_html_e('Fixed location:','chauffeur-booking-system'); ?></span>
                                <input type="text" name="<?php CHBSHelper::getFormName('google_map_default_location_fixed'); ?>" value="<?php echo esc_attr($this->data['meta']['google_map_default_location_fixed']); ?>"/>
                                <input type="hidden" name="<?php CHBSHelper::getFormName('google_map_default_location_fixed_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['google_map_default_location_fixed_coordinate_lat']); ?>"/>
                                <input type="hidden" name="<?php CHBSHelper::getFormName('google_map_default_location_fixed_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['google_map_default_location_fixed_coordinate_lng']); ?>"/>
                            </div>                                  
                        </li>   
                        <li>
                            <h5><?php esc_html_e('Avoid','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Indicates that the calculated route(s) should avoid the indicated features.','chauffeur-booking-system'); ?></span> 
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('google_map_route_avoid_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_route_avoid[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_route_avoid'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_route_avoid_0'); ?>"><?php esc_html_e('- None - ','chauffeur-booking-system'); ?></label>							
<?php
		foreach($this->data['dictionary']['google_map']['route_avoid'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('google_map_route_avoid_'.$index); ?>" name="<?php CHBSHelper::getFormName('google_map_route_avoid[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_route_avoid'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_route_avoid_'.$index); ?>"><?php echo esc_html($value); ?></label>		<?php		
		}
?>
                            </div>	
                       </li>                       
                       <li>
                            <h5><?php esc_html_e('Traffic layer','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enable or disable traffic layer on the map.','chauffeur-booking-system'); ?></span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_traffic_layer_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_traffic_layer_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_traffic_layer_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>                            
                       </li>
                       <li>
                            <h5><?php esc_html_e('Draggable locations','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable possibility to drag and drop locations.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('This option allows to create route based on Google Maps drag and drop feature.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('This is available for "Distance" service type only if fixed locations are disabled.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_draggable_location_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_draggable_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_draggable_location_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_draggable_location_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_draggable_location_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_draggable_location_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_draggable_location_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_draggable_location_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>                            
                       </li>
                       <li>
                            <h5><?php esc_html_e('Draggable map','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enable or disable draggable on the map.','chauffeur-booking-system'); ?></span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_draggable_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_draggable_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_draggable_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_draggable_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_draggable_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_draggable_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_draggable_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_draggable_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>                            
                       </li>
                       <li>
                            <h5><?php esc_html_e('Scrollwheel','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enable or disable wheel scrolling on the map.','chauffeur-booking-system'); ?></span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_scrollwheel_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_scrollwheel_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_map_scrollwheel_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>                            
                        </li>
                        <li>
                            <h5><?php esc_html_e('Map type control','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter settings for a map type.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_map_type_control_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_map_type_control_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_map_type_control_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_map_type_control_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_map_type_control_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_map_type_control_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_map_type_control_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_map_type_control_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                
                            </div>   
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Type:','chauffeur-booking-system'); ?></span>
                                <select name="<?php CHBSHelper::getFormName('google_map_map_type_control_id'); ?>" id="<?php CHBSHelper::getFormName('google_map_map_type_control_id'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['map_type_control_id'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_id'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>  
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Style:','chauffeur-booking-system'); ?></span>
                                <select name="<?php CHBSHelper::getFormName('google_map_map_type_control_style'); ?>" id="<?php CHBSHelper::getFormName('google_map_map_type_control_style'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['map_type_control_style'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_style'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>                              
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Position:','chauffeur-booking-system'); ?></span>
                                <select name="<?php CHBSHelper::getFormName('google_map_map_type_control_position'); ?>" id="<?php CHBSHelper::getFormName('google_map_map_type_control_position'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['position'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_position'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Zoom','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter seetings for a zoom.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','chauffeur-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_zoom_control_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_zoom_control_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_zoom_control_enable'],1); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_zoom_control_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_zoom_control_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_zoom_control_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_map_zoom_control_enable'],0); ?>/>
                                    <label for="<?php CHBSHelper::getFormName('google_map_zoom_control_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                                </div>                                
                            </div>  
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Position:','chauffeur-booking-system'); ?></span>
                                <select name="<?php CHBSHelper::getFormName('google_map_zoom_control_position'); ?>" id="<?php CHBSHelper::getFormName('google_map_zoom_control_position'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['position'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['google_map_zoom_control_position'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Level:','chauffeur-booking-system'); ?></span>
                            	<div class="to-clear-fix">
									<div id="<?php CHBSHelper::getFormName('google_map_zoom_control_level'); ?>"></div>
									<input type="text" name="<?php CHBSHelper::getFormName('google_map_zoom_control_level'); ?>" id="<?php CHBSHelper::getFormName('google_map_zoom_control_level'); ?>" class="to-slider-range" readonly/>
								</div>	                             
                            </div>                              
                        </li>                       
                    </ul> 
                </div>
                <div id="meta-box-booking-form-8">
                    <ul class="to-form-field-list">
                       <li>
                            <h5><?php esc_html_e('Google Calendar','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enable or disable integration with Google Calendar.','chauffeur-booking-system'); ?></span> 
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_calendar_enable_1'); ?>" name="<?php CHBSHelper::getFormName('google_calendar_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_calendar_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_calendar_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_calendar_enable_0'); ?>" name="<?php CHBSHelper::getFormName('google_calendar_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['google_calendar_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('google_calendar_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>                            
                        </li>       
                        <li>
                            <h5><?php esc_html_e('ID','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Google Calendar ID.','chauffeur-booking-system'); ?></span> 
                            <div class="to-clear-fix">
                                <input type="text" name="<?php CHBSHelper::getFormName('google_calendar_id'); ?>" value="<?php echo esc_attr($this->data['meta']['google_calendar_id']); ?>"/>                                 
                            </div>                         
                        </li>
                        <li>
                            <h5><?php esc_html_e('Settings','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Copy/paste the contents of downloaded *.json file.','chauffeur-booking-system'); ?></span> 
                            <div class="to-clear-fix">
                                <textarea rows="1" cols="1" name="<?php CHBSHelper::getFormName('google_calendar_settings'); ?>" id="<?php CHBSHelper::getFormName('google_calendar_settings'); ?>"><?php echo esc_html($this->data['meta']['google_calendar_settings']); ?></textarea>
                            </div>                         
                        </li>
                    </ul>
                </div>
                <div id="meta-box-booking-form-9">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Colors','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Specify color for each group of elements.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('Please note that in some cases "resetting browser cache<" will be required.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('If you use server cache control plugins in your WordPress, you need to clear its own cache as well.','chauffeur-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <table class="to-table">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Group number','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Group number.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Default color','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Default value of the color.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Color','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('New value (in HEX) of the color.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
<?php
		foreach($this->data['dictionary']['color'] as $index=>$value)
		{
?>
                                    <tr>
                                        <td>
                                            <div><?php echo $index; ?>.</div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <span class="to-color-picker-sample to-color-picker-sample-style-1" style="background-color:#<?php echo esc_attr($value['color']); ?>"></span>
                                                <span><?php echo '#'.esc_html($value['color']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">	
                                                 <input type="text" class="to-color-picker" id="<?php CHBSHelper::getFormName('style_color_'.$index); ?>" name="<?php CHBSHelper::getFormName('style_color['.$index.']'); ?>" value="<?php echo esc_attr($this->data['meta']['style_color'][$index]); ?>"/>
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
            </div>
        </div>
<?php
        $GeoLocation=new CHBSGeoLocation();
        
        if((int)$this->data['meta']['geolocation_server_side_enable']===1)
            $userDefaultCoordinate=$GeoLocation->getCoordinate();
        else $userDefaultCoordinate=array('lat'=>0,'lng'=>0);
?>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
                /***/
                
                var helper=new Helper();
                helper.getMessageFromConsole();
                
                /***/
                
				var element=$('.to').themeOptionElement({init:true});
                element.createSlider('#<?php CHBSHelper::getFormName('google_map_zoom_control_level'); ?>',1,21,<?php echo (int)$this->data['meta']['google_map_zoom_control_level']; ?>);
                element.createSlider('#<?php CHBSHelper::getFormName('payment_deposit_value'); ?>',0,100,<?php echo (int)$this->data['meta']['payment_deposit_value']; ?>);
                element.createSlider('#<?php CHBSHelper::getFormName('form_preloader_background_opacity'); ?>',0,100,<?php echo (int)$this->data['meta']['form_preloader_background_opacity']; ?>);
                
                /***/
                
                toPreventCheckbox($('input[name="<?php CHBSHelper::getFormName('route_id'); ?>[]"]'));
                toPreventCheckbox($('input[name="<?php CHBSHelper::getFormName('vehicle_category_id'); ?>[]"]'));
                toPreventCheckbox($('input[name="<?php CHBSHelper::getFormName('google_map_route_avoid'); ?>[]"]'));            
                toPreventCheckbox($('input[name="<?php CHBSHelper::getFormName('booking_extra_category_id'); ?>[]"]'));
                
                /***/
                
                var timeFormat='<?php echo CHBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
                
                /***/
                
                toCreateAutocomplete('input[name="<?php CHBSHelper::getFormName('base_location'); ?>"]');
                toCreateAutocomplete('input[name="<?php CHBSHelper::getFormName('google_map_default_location_fixed'); ?>"]');
                
                toCreateAutocomplete('input[name="<?php CHBSHelper::getFormName('driving_zone_restriction_pickup_location_area'); ?>"]');
                toCreateAutocomplete('input[name="<?php CHBSHelper::getFormName('driving_zone_restriction_dropoff_location_area'); ?>"]'); 
                
                /***/
                
                $('#to-table-form-element-panel').table();
                $('#to-table-form-element-field').table();
                $('#to-table-form-element-agreement').table();
                $('#to-table-availability-exclude-date').table();
                
                /***/
                
                $('#post').on('submit',function()
                {
                    $('select.chbs-service-type-id-enable').each(function()
                    {
                        var option=[];
                        $(this).children('option:selected').each(function() { option.push($(this).val()); });
                        $(this).next('input').val(option.join('.'));
                    });
                });               
                
                /***/
                
                element.bindBrowseMedia('.to-button-browse');
            });
		</script>