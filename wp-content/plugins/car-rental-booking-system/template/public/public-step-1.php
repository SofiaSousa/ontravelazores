<?php
		$Location=new CRBSLocation();
        $Validation=new CRBSValidation();
        
		/***/
		
		$columnRightEnable=true;
		
		if(($this->data['widget_mode']==1 ) || ((int)$this->data['meta']['step_1_right_panel_visibility']!==1)) $columnRightEnable=false;
		
		$class=$columnRightEnable ? array('crbs-layout-50x50') : array('crbs-layout-100');
			
		array_push($class,'crbs-clear-fix');
		
		/***/
?>
        <div class="crbs-notice crbs-hidden"></div>

        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?>>

            <div class="crbs-layout-column-left">

                <div class="crbs-form-panel">
<?php
                $selectedCustomerPickupLocation=false;

                if($this->data['widget_mode']!=1)
                {
?> 
                    <label class="crbs-form-panel-label"><?php esc_html_e('Pickup','car-rental-booking-system'); ?></label>
<?php
                }
?>
                    <div class="crbs-form-panel-content crbs-clear-fix">
<?php
				$class=array('crbs-form-field');

				$selectLocationEnable=CRBSBookingFormHelper::enableSelectLocation($this->data['dictionary']['location'],$this->data['meta']);

				if(!$selectLocationEnable) array_push($class,'crbs-hidden');
?>
                        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?>>
							
                            <label><?php esc_html_e('Pickup location','car-rental-booking-system'); ?></label>
                            <select name="<?php CRBSHelper::getFormName('pickup_location_id'); ?>">
<?php
                foreach($this->data['dictionary']['location'] as $index=>$value)
                {
?>
                                <option value="<?php echo esc_attr($index); ?>" <?php CRBSHelper::selectedIf($index,CRBSRequest::get('pickup_location_id',false)); ?>><?php esc_html_e($value['post']->post_title); ?></option>
<?php              
                }
                if((int)$this->data['meta']['customer_pickup_location_enable']===1)
                {
                    $selectedCustomerPickupLocation=-1*$this->data['meta']['customer_pickup_location_id']==CRBSRequest::get('pickup_location_id',false) ? true : false;
?>
                                <option value="-<?php echo esc_attr($this->data['meta']['customer_pickup_location_id']); ?>" <?php CRBSHelper::selectedIf(-1*$this->data['meta']['customer_pickup_location_id'],CRBSRequest::get('pickup_location_id')); ?>><?php esc_html_e('[My own location]','car-rental-booking-system'); ?></option>
<?php
                }
?>
                            </select>
                        </div> 
<?php
                if(((int)$this->data['meta']['customer_pickup_location_enable']===1) && ($selectLocationEnable))
                {
                    $class=array('crbs-clear-fix','crbs-customer-location');
                    if(!$selectedCustomerPickupLocation) array_push($class,'crbs-hidden');
?>   
                        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?>>

                            <div class="crbs-form-field crbs-form-field-location-autocomplete">
                                <label class="crbs-form-field-label"><?php esc_html_e('My location','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('pickup_location_address'); ?>" id="<?php CRBSHelper::getFormName('pickup_location_address'); ?>" value="<?php echo CRBSRequest::get('pickup_location_address_data_formatted_address'); ?>"/>
                                <input type="hidden" name="<?php CRBSHelper::getFormName('pickup_location_address_data'); ?>" id="<?php CRBSHelper::getFormName('pickup_location_address_data'); ?>"  value="<?php echo CRBSRequest::getOnPrefix('pickup_location_address_data'); ?>"/>
                            </div>

                        </div>                    
<?php                  
                }
?>
                        <div class="crbs-clear-fix">

                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label class="crbs-form-field-label"><?php esc_html_e('Pickup date','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('pickup_date'); ?>" class="crbs-datepicker" value="<?php echo CRBSRequest::get('pickup_date'); ?>"/>
                            </div>

                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label class="crbs-form-field-label"><?php esc_html_e('Pickup time','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('pickup_time'); ?>" class="crbs-timepicker" value="<?php echo CRBSRequest::get('pickup_time'); ?>"/>
                            </div>

                        </div>
                        
                    </div>
                    
                </div>
                
                <div class="crbs-form-panel">
<?php
                $selectedCustomerReturnLocation=false;

                if($this->data['widget_mode']!=1)
                {
?> 
                    <label class="crbs-form-panel-label"><?php esc_html_e('Return','car-rental-booking-system'); ?></label>
<?php
                }
?>
                    <div class="crbs-form-panel-content crbs-clear-fix">
<?php
				$class=array('crbs-form-field');

				$selectLocationEnable=CRBSBookingFormHelper::enableSelectLocation($this->data['dictionary']['location'],$this->data['meta'],'return');

				if(!$selectLocationEnable) array_push($class,'crbs-hidden');
?>
                        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?>>
                            <label><?php esc_html_e('Return location','car-rental-booking-system'); ?></label>
                            <select name="<?php CRBSHelper::getFormName('return_location_id'); ?>">
<?php
                foreach($this->data['dictionary']['location'] as $index=>$value)
                {
?>
                                <option value="<?php echo esc_attr($index); ?>" <?php CRBSHelper::selectedIf($index,CRBSRequest::get('return_location_id'),true); ?>><?php esc_html_e($value['post']->post_title); ?></option>
<?php              
                }
                if((int)$this->data['meta']['customer_return_location_enable']===1)
                {
                    $selectedCustomerReturnLocation=-1*$this->data['meta']['customer_return_location_id']==CRBSRequest::get('pickup_location_id',false) ? true : false;
?>
                                <option value="-<?php echo esc_attr($this->data['meta']['customer_return_location_id']); ?>" <?php CRBSHelper::selectedIf(-1*$this->data['meta']['customer_return_location_id'],CRBSRequest::get('return_location_id',false),true); ?>><?php esc_html_e('[My own location]','car-rental-booking-system'); ?></option>
<?php
                }
?>
                                <option value="-1" <?php CRBSHelper::selectedIf(-1,CRBSRequest::get('return_location_id',false),true); ?>><?php esc_html_e('[Return to the same location]','car-rental-booking-system'); ?></option>
                            </select>
                        </div> 
<?php
                if(((int)$this->data['meta']['customer_return_location_enable']===1) && ($selectLocationEnable))
                {
                    $class=array('crbs-clear-fix','crbs-customer-location');
                    if(!$selectedCustomerReturnLocation) array_push($class,'crbs-hidden'); 
?>   
                        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?>>

                            <div class="crbs-form-field crbs-form-field-location-autocomplete">
                                <label class="crbs-form-field-label"><?php esc_html_e('My location','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('return_location_address'); ?>" id="<?php CRBSHelper::getFormName('return_location_address'); ?>" value="<?php echo CRBSRequest::get('return_location_address_data_formatted_address'); ?>"/>
                                <input type="hidden" name="<?php CRBSHelper::getFormName('return_location_address_data'); ?>" id="<?php CRBSHelper::getFormName('return_location_address_data'); ?>" value="<?php echo CRBSRequest::getOnPrefix('return_location_address_data'); ?>"/>
                            </div>

                        </div>                    
<?php                  
                }
?>
                        <div class="crbs-clear-fix">

                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label class="crbs-form-field-label"><?php esc_html_e('Return date','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('return_date'); ?>" class="crbs-datepicker" value="<?php echo CRBSRequest::get('return_date'); ?>"/>
                            </div>

                            <div class="crbs-form-field crbs-form-field-width-50">
                                <label class="crbs-form-field-label"><?php esc_html_e('Return time','car-rental-booking-system'); ?></label>
                                <input type="text" name="<?php CRBSHelper::getFormName('return_time'); ?>" class="crbs-timepicker" value="<?php echo CRBSRequest::get('return_time'); ?>"/>
                            </div>

                        </div>
                        
                    </div>
                    
                </div>
<?php
                if($this->data['meta']['driver_age_enable']==1)
                {
?>
                <div class="crbs-form-panel">
<?php
                    if($this->data['widget_mode']!=1)
                    {
?>  
                    <label class="crbs-form-panel-label"><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></label>
<?php
                    }
?>
                    <div class="crbs-form-panel-content crbs-clear-fix">
                    
                        <div class="crbs-form-field">
                            <label><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></label>
                            <select name="<?php CRBSHelper::getFormName('driver_age'); ?>"></select>                            
                        </div>  
                    </div>
                    
                </div>
<?php
                }
?>
            </div>
<?php
                if($columnRightEnable)
                {
?>
            <div class="crbs-layout-column-right">
                
                <div class="crbs-google-map">
                    
                    <div id="crbs_google_map"></div>
                    
                    <div id="crbs-location-info-frame">
<?php
                    foreach($this->data['dictionary']['location'] as $index=>$value)
                    {
?>
                        <div data-location-id="<?php echo esc_attr($index); ?>">
                            
                            <div class="crbs-location-info-frame-header">
                                <h4 class="crbs-header"><?php echo esc_html($value['post']->post_title); ?></h4>
                                <a class="crbs-meta-icon-close" href="#" title="<?php esc_attr_e('Close this window','car-rental-booking-system'); ?>"></a>
                            </div>
                            
                            <div class="crbs-location-info-frame-meta-1">
                                
                                <div class="crbs-layout-33x33x33 crbs-clear-fix">
<?php
                        if($this->data['meta']['vehicle_count_enable']==1)
                        {
?>
                                    <div class="crbs-layout-column-left">
                                        <span class="crbs-meta-icon-car"></span>
                                        <div><?php echo $this->data['location_info'][$index]['vehicle_count']; ?></div>
                                        <label><?php esc_html_e('Cars','car-rental-booking-system'); ?></label>
                                    </div>
<?php
                        }
                        if(in_array(CRBSOption::getOption('billing_type'),array(1,2)))
                        {
?>
                                    <div class="crbs-layout-column-center">

                                        <span class="crbs-meta-icon-car-price"></span>
<?php
                            if(CRBSOption::getOption('billing_type')==1)
                            {
?>
                                        <div><?php echo esc_html($this->data['location_info'][$index]['price_rental_hour']); ?></div>
                                        <label><?php esc_html_e('Per hour','car-rental-booking-system'); ?></label>
<?php
                            }
                            else
                            {
?>
                                        <div><?php echo esc_html($this->data['location_info'][$index]['price_rental_day']); ?></div>
                                        <label><?php esc_html_e('Per day','car-rental-booking-system'); ?></label>                                        
<?php
                            }
?>
                                    </div>
<?php
                        }
                        if($this->data['meta']['driver_age_enable']==1)
                        {
?>
                                    <div class="crbs-layout-column-right">

                                        <span class="crbs-meta-icon-car-key"></span>
                                        <div><?php echo esc_html($this->data['location_info'][$index]['driver_age']); ?></div>
                                        <label><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></label>

                                    </div>
<?php
                        }
?>
                                </div>
                                
                            </div>
                            
                            <div class="crbs-location-info-frame-meta-2">
                            
                                <div class="crbs-layout-50x50 crbs-clear-fix">

                                    <div class="crbs-layout-column-left">

                                        <label><?php esc_html_e('Address','car-rental-booking-system'); ?></label>
                                        <div><?php echo $Location->displayAddress($index); ?></div>
                                        
                                        <label><?php esc_html_e('Contact','car-rental-booking-system'); ?></label>
                                        <div>
                                            <?php echo esc_html__('Phone: ','car-rental-booking-system').$value['meta']['contact_detail_phone_number']; ?><br/>
                                            <?php echo esc_html__('Fax: ','car-rental-booking-system').$value['meta']['contact_detail_fax_number']; ?>
                                        </div>
                                        
                                    </div>

                                    <div class="crbs-layout-column-right">

                                        <label><?php esc_html_e('Business hours','car-rental-booking-system'); ?></label>
                                        <ul>
<?php
                        foreach($value['meta']['business_hour'] as $dayIndex=>$dayHour)
                        {
                            $hour=esc_html__('Closed','car-rental-booking-system');

                            if(($Validation->isNotEmpty($dayHour['start'])) || ($Validation->isNotEmpty($dayHour['stop'])))
                                $hour=esc_html(trim($dayHour['start'].'-'.$dayHour['stop']));
?> 
                                            <li>
                                                <span><?php echo date_i18n('l',strtotime('0'.$dayIndex.'-01-2018')); ?></span>
                                                <span><?php echo $hour; ?></span>
                                            </li>
<?php                      
                        }
?>
                                        </ul>

                                    </div>                                

                                </div>

                            </div>
                            
                            <div class="crbs-location-info-frame-button">
                        
                                <div class="crbs-button-checkbox">
                                    <a href="#" class="crbs-state-selected"><?php esc_html_e('Pickup','car-rental-booking-system'); ?></a>
                                    <a href="#"><?php esc_html_e('Return','car-rental-booking-system'); ?></a>
                                </div>
                                
                                <a href="#" class="crbs-button crbs-button-style-1"><?php esc_html_e('Select','car-rental-booking-system'); ?></a>
                                
                            </div>
                            
                        </div>
<?php
                    }
?>
                        
                    </div>
                    
                </div>

            </div>
<?php
                }
?>            
        </div>

        <div class="crbs-clear-fix crbs-main-content-navigation-button">
<?php
                if($this->data['widget_mode']===1)
                {
?>   
            <a href="#" class="crbs-button crbs-button-style-1 crbs-button-widget-submit">
                <?php esc_html_e('Search for car','car-rental-booking-system'); ?>
            </a> 
<?php                  
                }
                else
                {
?>
            <a href="#" class="crbs-button crbs-button-style-1 crbs-button-step-next">
                <?php echo esc_html($this->data['step']['dictionary'][1]['button']['next']); ?>
                <span class="crbs-meta-icon-arrow-horizontal"></span>
            </a> 
<?php
                }
?>
        </div>