<?php 
		echo $this->data['nonce']; 
        global $post;
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-booking-form-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-2"><?php esc_html_e('Form elements','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-3"><?php esc_html_e('Styles','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-booking-form-4"><?php esc_html_e('Google Maps','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-booking-form-1">
					<div class="ui-tabs">
						<ul>
							<li><a href="#meta-box-booking-form-1-1"><?php esc_html_e('Main','car-rental-booking-system'); ?></a></li>
							<li><a href="#meta-box-booking-form-1-2"><?php esc_html_e('Locations','car-rental-booking-system'); ?></a></li>
							<li><a href="#meta-box-booking-form-1-3"><?php esc_html_e('Prices','car-rental-booking-system'); ?></a></li>
							<li><a href="#meta-box-booking-form-1-4"><?php esc_html_e('Look & Feel','car-rental-booking-system'); ?></a></li>
						</ul>
						<div id="meta-box-booking-form-1-1">
							<ul class="to-form-field-list">
								<?php echo CRBSHelper::createPostIdField(__('Booking form ID','car-rental-booking-system')); ?>
								<li>
									<h5><?php esc_html_e('Shortcode','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Copy and paste the shortcode on a page.','car-rental-booking-system'); ?></span>
									<div class="to-field-disabled">
<?php
        $shortcode='['.PLUGIN_CRBS_CONTEXT.'_booking_form booking_form_id="'.$post->ID.'"]';
        echo $shortcode;
?>
										<a href="#" class="to-copy-to-clipboard to-float-right" data-clipboard-text="<?php echo esc_attr($shortcode); ?>" data-label-on-success="<?php esc_attr_e('Copied!','car-rental-booking-system') ?>"><?php esc_html_e('Copy','car-rental-booking-system'); ?></a>
									</div>
								</li>                        
								<li>
									<h5><?php esc_html_e('Default booking status','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Default booking status of new order.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
										<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('booking_status_id_default_'.$index); ?>" name="<?php CRBSHelper::getFormName('booking_status_id_default'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_status_id_default'],$index); ?>/>
										<label for="<?php CRBSHelper::getFormName('booking_status_id_default_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>                                
									</div>
								</li>    
								<li>
									<h5><?php esc_html_e('Geolocation','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Enable or disable geolocation.','car-rental-booking-system'); ?></span>
									<div class="to-checkbox-button">
										<input type="checkbox" value="1" id="<?php CRBSHelper::getFormName('geolocation_enable_1'); ?>" name="<?php CRBSHelper::getFormName('geolocation_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['geolocation_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('geolocation_enable_1'); ?>"><?php esc_html_e('Client side','car-rental-booking-system'); ?></label>
										<input type="checkbox" value="2" id="<?php CRBSHelper::getFormName('geolocation_enable_2'); ?>" name="<?php CRBSHelper::getFormName('geolocation_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['geolocation_enable'],2); ?>/>
										<label for="<?php CRBSHelper::getFormName('geolocation_enable_2'); ?>"><?php esc_html_e('Server side','car-rental-booking-system'); ?></label>
									</div>
								</li>                           
								<li>
									<h5><?php esc_html_e('Driver\'s age','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Disable or enable option to checking driver\'s age.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('driver_age_enable_1'); ?>" name="<?php CRBSHelper::getFormName('driver_age_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_age_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('driver_age_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('driver_age_enable_0'); ?>" name="<?php CRBSHelper::getFormName('driver_age_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['driver_age_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('driver_age_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>                                                                   
								<li>
									<h5><?php esc_html_e('WooCommerce','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Enable or disable WooCommerce support for this booking form.','car-rental-booking-system'); ?><br/>
				                        <?php echo sprintf(__('Please make sure that you set up "Checkout page" in <a href="%s">WooCommerce settings</a>','car-rental-booking-system'),admin_url('admin.php?page=wc-settings&tab=advanced')); ?>
									</span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('woocommerce_enable_1'); ?>" name="<?php CRBSHelper::getFormName('woocommerce_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('woocommerce_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('woocommerce_enable_0'); ?>" name="<?php CRBSHelper::getFormName('woocommerce_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('woocommerce_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
                                <li>
                                    <h5><?php esc_html_e('WooCommerce account','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable possibility to create and login via wooCommerce account.','car-rental-booking-system'); ?><br/>
                                        <?php esc_html_e('"Disable" means that login and register form will not be displayed.','car-rental-booking-system'); ?><br/>
                                        <?php esc_html_e('"Enable as option" means that both forms will be available, but logging and/or creating an account depends on user preferences.','car-rental-booking-system'); ?><br/>
                                        <?php esc_html_e('"Enable as mandatory" means that user have to be registered and logged before he sends a booking.','car-rental-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_1'); ?>" name="<?php CRBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],1); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_1'); ?>"><?php esc_html_e('Enable as option','car-rental-booking-system'); ?></label>
                                        <input type="radio" value="2" id="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_2'); ?>" name="<?php CRBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],2); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_2'); ?>"><?php esc_html_e('Enable as mandatory','car-rental-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_0'); ?>" name="<?php CRBSHelper::getFormName('woocommerce_account_enable_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['woocommerce_account_enable_type'],0); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('woocommerce_account_enable_type_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                    </div>
                                </li>
								<li>
									<h5><?php esc_html_e('Extra days','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Add number of days to the booking once the vehicle will be returned after entered time.','car-rental-booking-system'); ?><br/>
										<?php esc_html_e('This option works for "Daily" billing type only.','car-rental-booking-system'); ?>
									</span>
									<div class="to-clear-fix">
										<span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
										<div class="to-radio-button">
											<input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_extra_day_enable_1'); ?>" name="<?php CRBSHelper::getFormName('booking_extra_day_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_extra_day_enable'],1); ?>/>
											<label for="<?php CRBSHelper::getFormName('booking_extra_day_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
											<input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_extra_day_enable_0'); ?>" name="<?php CRBSHelper::getFormName('booking_extra_day_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_extra_day_enable'],0); ?>/>
											<label for="<?php CRBSHelper::getFormName('booking_extra_day_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
										</div>
									</div>
									<div class="to-clear-fix">
										<span class="to-legend-field"><?php esc_html_e('Time after which days will be added:','car-rental-booking-system'); ?></span>
										<input type="text" maxlength="10" class="to-timepicker" name="<?php CRBSHelper::getFormName('booking_extra_day_time'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_extra_day_time']); ?>" id="<?php CRBSHelper::getFormName('booking_extra_day_time'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
									</div>                            
									<div class="to-clear-fix">
										<span class="to-legend-field"><?php esc_html_e('Number of days to add:','car-rental-booking-system'); ?></span>
										<input type="text" maxlength="3" name="<?php CRBSHelper::getFormName('booking_extra_day_number'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_extra_day_number']); ?>" id="<?php CRBSHelper::getFormName('booking_extra_day_number'); ?>" title="<?php esc_attr_e('Enter start date in format DD-MM-YYYY.','car-rental-booking-system'); ?>"/>
									</div>                                
								</li>  
                                <li>
                                    <h5><?php esc_html_e('Vehicles sorting','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select sorting options of vehicles in booking form.','car-rental-booking-system'); ?></span>
                                    <div class="to-clear-fix">
                                        <select name="<?php CRBSHelper::getFormName('vehicle_sorting_type'); ?>">
 <?php
		foreach($this->data['dictionary']['vehicle_sorting_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['vehicle_sorting_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                        </select>
                                    </div>
                                </li>  
							</ul>
						</div>
						<div id="meta-box-booking-form-1-2">
							<ul class="to-form-field-list">
								<li>
									<h5><?php esc_html_e('Locations','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Select at least one location.','car-rental-booking-system'); ?>
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
									<h5><?php esc_html_e('Default pickup location','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Select default pickup location.','car-rental-booking-system'); ?>
									</span>
									<div class="to-checkbox-button">
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('location_pickup_default_id_0'); ?>" name="<?php CRBSHelper::getFormName('location_pickup_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_pickup_default_id'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_pickup_default_id_0'); ?>"><?php echo esc_html_e('[None]','car-rental-booking-system'); ?></label>
										<input type="radio" value="-1" id="<?php CRBSHelper::getFormName('location_pickup_default_id__1'); ?>" name="<?php CRBSHelper::getFormName('location_pickup_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_pickup_default_id'],-1); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_pickup_default_id__1'); ?>"><?php echo esc_html_e('[Customer location]','car-rental-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['location'] as $index=>$value)
		{
?>
										<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('location_pickup_default_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('location_pickup_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_pickup_default_id'],$index); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_pickup_default_id_'.$index); ?>"><?php echo esc_html(get_the_title($index)); ?></label>
<?php		
		}
?>                                
									</div>
								</li>   								
								<li>
									<h5><?php esc_html_e('Default return location','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Select default return location.','car-rental-booking-system'); ?>
									</span>
									<div class="to-checkbox-button">
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('location_return_default_id_0'); ?>" name="<?php CRBSHelper::getFormName('location_return_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_return_default_id'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_return_default_id_0'); ?>"><?php echo esc_html_e('[None]','car-rental-booking-system'); ?></label>
										<input type="radio" value="-1" id="<?php CRBSHelper::getFormName('location_return_default_id__1'); ?>" name="<?php CRBSHelper::getFormName('location_return_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_return_default_id'],-1); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_return_default_id__1'); ?>"><?php echo esc_html_e('[Customer location]','car-rental-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['location'] as $index=>$value)
		{
?>
										<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('location_return_default_id_'.$index); ?>" name="<?php CRBSHelper::getFormName('location_return_default_id'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_return_default_id'],$index); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_return_default_id_'.$index); ?>"><?php echo esc_html(get_the_title($index)); ?></label>
<?php		
		}
?>                                
									</div>
								</li>   								
								<li>
									<h5><?php esc_html_e('Customer location','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Enable or disable possibility to enter own pickup/return location by customer.','car-rental-booking-system'); ?><br/>
										<?php esc_html_e('Enabling this option requires choosing a default location from which settings will be used.','car-rental-booking-system'); ?><br/>
									</span>   
									<div class="to-clear-fix">
										<table class="to-table">
											<tr>
												<th style="width:20%">
													<div></div>
												</th>
												<th style="width:40%">
													<div>
														<?php esc_html_e('Pickup location','car-rental-booking-system'); ?>
														<span class="to-legend">
															<?php esc_html_e('Settings for pickup location.','car-rental-booking-system'); ?>
														</span>
													</div>
												</th>
												<th style="width:40%">
													<div>
														<?php esc_html_e('Return location','car-rental-booking-system'); ?>
														<span class="to-legend">
															<?php esc_html_e('Settings for return location.','car-rental-booking-system'); ?>
														</span>
													</div>
												</th>
											</tr>                                
											<tr>
												<td>
													<div class="to-clear-fix">
														<?php esc_html_e('Status','car-rental-booking-system'); ?>
													</div>
												</td>
												<td>
													<div class="to-clear-fix">
														<div class="to-radio-button">
															<input type="radio" value="1" id="<?php CRBSHelper::getFormName('customer_pickup_location_enable_1'); ?>" name="<?php CRBSHelper::getFormName('customer_pickup_location_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['customer_pickup_location_enable'],1); ?>/>
															<label for="<?php CRBSHelper::getFormName('customer_pickup_location_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
															<input type="radio" value="0" id="<?php CRBSHelper::getFormName('customer_pickup_location_enable_0'); ?>" name="<?php CRBSHelper::getFormName('customer_pickup_location_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['customer_pickup_location_enable'],0); ?>/>
															<label for="<?php CRBSHelper::getFormName('customer_pickup_location_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
														</div>                                                
													</div>
												</td>                                        
												<td>
													<div class="to-clear-fix">
														<div class="to-radio-button">
															<input type="radio" value="1" id="<?php CRBSHelper::getFormName('customer_return_location_enable_1'); ?>" name="<?php CRBSHelper::getFormName('customer_return_location_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['customer_return_location_enable'],1); ?>/>
															<label for="<?php CRBSHelper::getFormName('customer_return_location_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
															<input type="radio" value="0" id="<?php CRBSHelper::getFormName('customer_return_location_enable_0'); ?>" name="<?php CRBSHelper::getFormName('customer_return_location_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['customer_return_location_enable'],0); ?>/>
															<label for="<?php CRBSHelper::getFormName('customer_return_location_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
														</div>                                                
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="to-clear-fix">
														<?php esc_html_e('Default','car-rental-booking-system'); ?>
													</div>
												</td>
												<td>
													<div class="to-clear-fix">
														<select name="<?php CRBSHelper::getFormName('customer_pickup_location_id'); ?>">
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['customer_pickup_location_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
														</select>
													</div>
												</td>                                        
												<td>
													<div class="to-clear-fix">
														<select name="<?php CRBSHelper::getFormName('customer_return_location_id'); ?>">
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['customer_return_location_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
														</select>                                                            
													</div>
												</td>
											</tr>     
										   <tr>
												<td>
													<div class="to-clear-fix">
														<?php esc_html_e('Restriction to country','car-rental-booking-system'); ?><br>
														<span class="to-legend-field"><?php esc_html_e('Select (max. 5) countries','car-rental-booking-system'); ?></span>
													</div>
												</td>
												<td>
													<div class="to-clear-fix">
														<select multiple="multiple" class="to-dropkick-disable" name="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_country[]'); ?>" id="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_country'); ?>">
<?php
		echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['meta']['customer_pickup_location_restriction_country'],-1,false)).'>'.esc_html__(' - Not set -','car-rental-booking-system').'</option>';
		foreach($this->data['dictionary']['country'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['customer_pickup_location_restriction_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
														</select>  
													</div>
												</td>                                        
												<td>
													<div class="to-clear-fix">
														<select multiple="multiple" class="to-dropkick-disable" name="<?php CRBSHelper::getFormName('customer_return_location_restriction_country[]'); ?>" id="<?php CRBSHelper::getFormName('customer_return_location_restriction_country'); ?>">
<?php
		echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['meta']['customer_return_location_restriction_country'],-1,false)).'>'.esc_html__(' - Not set -','car-rental-booking-system').'</option>';
		foreach($this->data['dictionary']['country'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['customer_return_location_restriction_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
														</select>                                                  
													</div>
												</td>
											</tr>                                      
											<tr>
												<td>
													<div class="to-clear-fix">
														<?php esc_html_e('Restriction to area','car-rental-booking-system'); ?><br>
														<span class="to-legend-field"><?php esc_html_e('Address and radius in kilometers','car-rental-booking-system'); ?></span>
													</div>
												</td>
												<td>
													<div class="to-clear-fix">
														<input type="text" name="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_name'); ?>" id="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_name'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_pickup_location_restriction_name']); ?>"/>
														<input type="text" name="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_radius'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_pickup_location_restriction_radius']); ?>" maxlength="5" class="to-margin-top-10"/>
														<input type="hidden" name="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_coordinate_lat'); ?>" id="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_pickup_location_restriction_coordinate_lat']); ?>" class="to-coordinate-lat"/>
														<input type="hidden" name="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_coordinate_lng'); ?>" id="<?php CRBSHelper::getFormName('customer_pickup_location_restriction_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_pickup_location_restriction_coordinate_lng']); ?>" class="to-coordinate-lng"/>
													</div>
												</td>                                        
												<td>
													<div class="to-clear-fix">
														<input type="text" name="<?php CRBSHelper::getFormName('customer_return_location_restriction_name'); ?>" id="<?php CRBSHelper::getFormName('customer_return_location_restriction_name'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_return_location_restriction_name']); ?>"/>
														<input type="text" name="<?php CRBSHelper::getFormName('customer_return_location_restriction_radius'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_return_location_restriction_radius']); ?>" maxlength="5" class="to-margin-top-10"/>
														<input type="hidden" name="<?php CRBSHelper::getFormName('customer_return_location_restriction_coordinate_lat'); ?>" id="<?php CRBSHelper::getFormName('customer_return_location_restriction_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_return_location_restriction_coordinate_lat']); ?>" class="to-coordinate-lat"/>
														<input type="hidden" name="<?php CRBSHelper::getFormName('customer_return_location_restriction_coordinate_lng'); ?>" id="<?php CRBSHelper::getFormName('customer_return_location_restriction_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['customer_return_location_restriction_coordinate_lng']); ?>" class="to-coordinate-lng"/>
													</div>
												</td>
											</tr>                                      
										</table>
									</div>
								</li>  
								<li>
									<h5><?php esc_html_e('Show location drop down list','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Show or hide pickup and return location drop down list if only one is available.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('location_single_display_enable_1'); ?>" name="<?php CRBSHelper::getFormName('location_single_display_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_single_display_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_single_display_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('location_single_display_enable_0'); ?>" name="<?php CRBSHelper::getFormName('location_single_display_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_single_display_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_single_display_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>  
								<li>
									<h5><?php esc_html_e('Use the same locations','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Force customer to use the same (defined) pickup and return locations.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('location_the_same_enable_1'); ?>" name="<?php CRBSHelper::getFormName('location_the_same_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_the_same_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_the_same_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('location_the_same_enable_0'); ?>" name="<?php CRBSHelper::getFormName('location_the_same_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_the_same_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_the_same_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>   
								<li>
									<h5><?php esc_html_e('Show customer location field only','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Show only customer pickup/return location field.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('location_customer_only_enable_1'); ?>" name="<?php CRBSHelper::getFormName('location_customer_only_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_customer_only_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_customer_only_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('location_customer_only_enable_0'); ?>" name="<?php CRBSHelper::getFormName('location_customer_only_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['location_customer_only_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('location_customer_only_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
								
							</ul>
						</div>
						<div id="meta-box-booking-form-1-3">
							<ul class="to-form-field-list">
								<li>
									<h5><?php esc_html_e('Currencies','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Select available currencies.','car-rental-booking-system'); ?><br/>
										<?php esc_html_e('You can set exchange rates for each selected currency in plugin options.','car-rental-booking-system'); ?><br/>
										<?php esc_html_e('You can run booking form with particular currency by adding parameter "currency=CODE" to the query string of page on which booking form is located.','car-rental-booking-system'); ?>
									</span>                        
									<div class="to-clear-fix">
										<select multiple="multiple" class="to-dropkick-disable" name="<?php CRBSHelper::getFormName('currency[]'); ?>">
											<option value="-1" <?php CRBSHelper::selectedIf($this->data['meta']['currency'],-1); ?>><?php esc_html_e('- None -','car-rental-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['currency'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['currency'],$index,false)).'>'.esc_html($value['name']).'</option>';
?>
										</select>                                                
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Coupons','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Enable or disable coupons for this booking form.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('coupon_enable_1'); ?>" name="<?php CRBSHelper::getFormName('coupon_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['coupon_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('coupon_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('coupon_enable_0'); ?>" name="<?php CRBSHelper::getFormName('coupon_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['coupon_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('coupon_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Coupon','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Select coupon which should be automatically used.','car-rental-booking-system'); ?><br/>
									</span>                        
									<div class="to-clear-fix">
										<select name="<?php CRBSHelper::getFormName('coupon_id'); ?>">
											<option value="-1" <?php CRBSHelper::selectedIf($this->data['meta']['coupon_id'],-1); ?>><?php esc_html_e('- None -','car-rental-booking-system'); ?></option>
<?php
        foreach($this->data['dictionary']['coupon'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['coupon_id'],$index,false)).'>'.esc_html($value['meta']['code']).'</option>';
?>
										</select>                                                
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Minimum order value','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Specify minimum gross value of the order.','car-rental-booking-system'); ?>
									</span>
									<div><input type="text" maxlength="12" name="<?php CRBSHelper::getFormName('order_value_minimum'); ?>" value="<?php echo esc_attr($this->data['meta']['order_value_minimum']); ?>"/></div>                                  
								</li>
								<li>
									<h5><?php esc_html_e('Hide fees','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Hide all additional fees in booking summary and include them to the price of selected vehicle.','car-rental-booking-system'); ?>
									</span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_summary_hide_fee_1'); ?>" name="<?php CRBSHelper::getFormName('booking_summary_hide_fee'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_summary_hide_fee'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('booking_summary_hide_fee_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_summary_hide_fee_0'); ?>" name="<?php CRBSHelper::getFormName('booking_summary_hide_fee'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_summary_hide_fee'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('booking_summary_hide_fee_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>  
								<li>
									<h5><?php esc_html_e('Display net prices','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Display net prices and tax separately in booking summary.','car-rental-booking-system'); ?>
									</span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('booking_summary_display_net_price_1'); ?>" name="<?php CRBSHelper::getFormName('booking_summary_display_net_price'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_summary_display_net_price'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('booking_summary_display_net_price_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('booking_summary_display_net_price_0'); ?>" name="<?php CRBSHelper::getFormName('booking_summary_display_net_price'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['booking_summary_display_net_price'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('booking_summary_display_net_price_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>  
								<li>
									<h5><?php esc_html_e('Bill style in summary','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Select type of displayed bill in summary. This option works for "Daily" billing type only.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('summary_bill_type_1'); ?>" name="<?php CRBSHelper::getFormName('summary_bill_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['summary_bill_type'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('summary_bill_type_1'); ?>"><?php esc_html_e('Type 1','car-rental-booking-system'); ?></label>
										<input type="radio" value="2" id="<?php CRBSHelper::getFormName('summary_bill_type_2'); ?>" name="<?php CRBSHelper::getFormName('summary_bill_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['summary_bill_type'],2); ?>/>
										<label for="<?php CRBSHelper::getFormName('summary_bill_type_2'); ?>"><?php esc_html_e('Type 2','car-rental-booking-system'); ?></label>
									</div>
								</li> 
								<li>
									<h5><?php esc_html_e('Price per day','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Display price per rental day on vehicle list.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_price_per_day_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_price_per_day_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_price_per_day_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
							</ul>
						</div>
						<div id="meta-box-booking-form-1-4">
							<ul class="to-form-field-list">
								<li>
                                    <h5><?php esc_html_e('Billing details','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend"><?php esc_html_e('Select default state of billing details section.','car-rental-booking-system'); ?></span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CRBSHelper::getFormName('billing_detail_state_1'); ?>" name="<?php CRBSHelper::getFormName('billing_detail_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['billing_detail_state'],1); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('billing_detail_state_1'); ?>"><?php esc_html_e('Unchecked','car-rental-booking-system'); ?></label>
                                        <input type="radio" value="2" id="<?php CRBSHelper::getFormName('billing_detail_state_2'); ?>" name="<?php CRBSHelper::getFormName('billing_detail_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['billing_detail_state'],2); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('billing_detail_state_2'); ?>"><?php esc_html_e('Checked','car-rental-booking-system'); ?></label>
                                        <input type="radio" value="3" id="<?php CRBSHelper::getFormName('billing_detail_state_3'); ?>" name="<?php CRBSHelper::getFormName('billing_detail_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['billing_detail_state'],3); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('billing_detail_state_3'); ?>"><?php esc_html_e('Mandatory','car-rental-booking-system'); ?></label>
										<input type="radio" value="4" id="<?php CRBSHelper::getFormName('billing_detail_state_4'); ?>" name="<?php CRBSHelper::getFormName('billing_detail_state'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['billing_detail_state'],4); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('billing_detail_state_4'); ?>"><?php esc_html_e('Hidden','car-rental-booking-system'); ?></label>
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('"Thank You" page','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enable or disable "Thank You" page in booking form.','car-rental-booking-system'); ?><br/>
                                        <?php esc_html_e('Please note, that disabling this page is available only if wooCommerce support is enabled.','car-rental-booking-system'); ?><br/>
                                        <?php esc_html_e('Then, customer is redirected to checkout page without information, that order has been sent.','car-rental-booking-system'); ?>
                                    </span>
                                    <div class="to-radio-button">
                                        <input type="radio" value="1" id="<?php CRBSHelper::getFormName('thank_you_page_enable_1'); ?>" name="<?php CRBSHelper::getFormName('thank_you_page_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['thank_you_page_enable'],1); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('thank_you_page_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                        <input type="radio" value="0" id="<?php CRBSHelper::getFormName('thank_you_page_enable_0'); ?>" name="<?php CRBSHelper::getFormName('thank_you_page_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['thank_you_page_enable'],0); ?>/>
                                        <label for="<?php CRBSHelper::getFormName('thank_you_page_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                    </div>
                                </li> 
                                <li>
                                    <h5><?php esc_html_e('"Back to home" button on "Thank you" page','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php esc_html_e('Enter URL address and label for this button.','car-rental-booking-system'); ?><br/>
										<?php esc_html_e('This button is displayed if payment processing is disabled or customer selects wire transfer or cash payment.','car-rental-booking-system'); ?>
                                    </span>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('Label:','car-rental-booking-system'); ?></span>
                                        <div>
                                            <input type="text" name="<?php CRBSHelper::getFormName('thank_you_page_button_back_to_home_label'); ?>" value="<?php echo esc_attr($this->data['meta']['thank_you_page_button_back_to_home_label']); ?>"/>
                                        </div>                     
                                    </div>
                                    <div>
                                        <span class="to-legend-field"><?php esc_html_e('URL address:','car-rental-booking-system'); ?></span>
                                        <div>
                                            <input type="text" name="<?php CRBSHelper::getFormName('thank_you_page_button_back_to_home_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['thank_you_page_button_back_to_home_url_address']); ?>"/>
                                        </div>                     
                                    </div>
                                </li>  
								<li>
									<h5><?php esc_html_e('Time picker','car-rental-booking-system'); ?></h5>
									<span class="to-legend">
										<?php esc_html_e('Time picker settings.','car-rental-booking-system'); ?>
									</span>
                                    <div>
                                        <span class="to-legend-field">
											<?php esc_html_e('Interval - the amount of time, in minutes, between each item in the drop down.','car-rental-booking-system'); ?><br/>
											<?php esc_html_e('Allowed are integer values from 1 to 9999.','car-rental-booking-system'); ?>
										</span>
                                        <div>
											<input type="text" maxlength="4" name="<?php CRBSHelper::getFormName('timepicker_step'); ?>" value="<?php echo esc_attr($this->data['meta']['timepicker_step']); ?>"/>                                 
                                        </div>                     
                                    </div>
                                    <div>
                                        <span class="to-legend-field">
											<?php esc_html_e('Start time for a current date:','car-rental-booking-system'); ?>
										</span>		
										<div class="to-radio-button">
											<input type="radio" value="1" id="<?php CRBSHelper::getFormName('timepicker_today_start_time_type_1'); ?>" name="<?php CRBSHelper::getFormName('timepicker_today_start_time_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['timepicker_today_start_time_type'],1); ?>/>
											<label for="<?php CRBSHelper::getFormName('timepicker_today_start_time_type_1'); ?>"><?php esc_html_e('Timepicker starts based on current time','car-rental-booking-system'); ?></label>
											<input type="radio" value="2" id="<?php CRBSHelper::getFormName('timepicker_today_start_time_type_2'); ?>" name="<?php CRBSHelper::getFormName('timepicker_today_start_time_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['timepicker_today_start_time_type'],2); ?>/>
											<label for="<?php CRBSHelper::getFormName('timepicker_today_start_time_type_2'); ?>"><?php esc_html_e('Timepicker starts based on interval','car-rental-booking-system'); ?></label>
										</div>		
									</div>
								</li> 
								<li>
									<h5><?php esc_html_e('Total count of vehicles','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Display count of vehicles.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('vehicle_count_enable_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_count_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_count_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_count_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('vehicle_count_enable_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_count_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_count_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_count_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li>                        
								<li>
									<h5><?php esc_html_e('Sticky summary sidebar','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Enable or disable sticky option for summary sidebar.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable_1'); ?>" name="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['summary_sidebar_sticky_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable_0'); ?>" name="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['summary_sidebar_sticky_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('summary_sidebar_sticky_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
								<li>
									<h5><?php esc_html_e('Vehicle filter bar','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Display filter bar on vehicle list.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_filter_bar_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable_0'); ?>" name="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_filter_bar_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_filter_bar_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
								<li>
									<h5><?php esc_html_e('Scroll after selecting a vehicle','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Scroll user to booking add-ons section after selecting a vehicle.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_1'); ?>" name="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_0'); ?>" name="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('scroll_to_booking_extra_after_select_vehicle_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
								<li>
									<h5><?php esc_html_e('Fields mandatory','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Select which fields should be marked as mandatory.','car-rental-booking-system'); ?></span>
									<div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['field_mandatory'] as $index=>$value)
		{
?>
										<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('field_mandatory_'.$index); ?>" name="<?php CRBSHelper::getFormName('field_mandatory['.$index.']'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['field_mandatory'],$index); ?>/>
										<label for="<?php CRBSHelper::getFormName('field_mandatory_'.$index); ?>"><?php echo esc_html($value['label']); ?></label>
<?php		
		}
?>                                
									</div>
								</li> 								
								<li>
									<h5><?php esc_html_e('Form preloader','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Enable or disable form preloader.','car-rental-booking-system'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php CRBSHelper::getFormName('form_preloader_enable_1'); ?>" name="<?php CRBSHelper::getFormName('form_preloader_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('form_preloader_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
										<input type="radio" value="0" id="<?php CRBSHelper::getFormName('form_preloader_enable_0'); ?>" name="<?php CRBSHelper::getFormName('form_preloader_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],0); ?>/>
										<label for="<?php CRBSHelper::getFormName('form_preloader_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
									</div>
								</li> 
                                <li>
                                    <h5><?php esc_html_e('Top navigation','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php echo __('Enable or disable top navigation.','car-rental-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CRBSHelper::getFormName('navigation_top_enable_1'); ?>" name="<?php CRBSHelper::getFormName('navigation_top_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['navigation_top_enable'],1); ?>/>
                                            <label for="<?php CRBSHelper::getFormName('navigation_top_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CRBSHelper::getFormName('navigation_top_enable_0'); ?>" name="<?php CRBSHelper::getFormName('navigation_top_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['navigation_top_enable'],0); ?>/>
                                            <label for="<?php CRBSHelper::getFormName('navigation_top_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                        </div>                                
                                    </div>
                                </li>
                                <li>
                                    <h5><?php esc_html_e('Visibility of right panel in step #1','car-rental-booking-system'); ?></h5>
                                    <span class="to-legend">
                                        <?php echo __('Google Maps visibility.','car-rental-booking-system'); ?><br/>
                                        <?php echo __('Please note that this option doesn\'t disable map. It hides map only.','car-rental-booking-system'); ?>
                                    </span>
                                    <div class="to-clear-fix">
                                        <div class="to-radio-button">
                                            <input type="radio" value="1" id="<?php CRBSHelper::getFormName('step_1_right_panel_visibility_1'); ?>" name="<?php CRBSHelper::getFormName('step_1_right_panel_visibility'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['step_1_right_panel_visibility'],1); ?>/>
                                            <label for="<?php CRBSHelper::getFormName('step_1_right_panel_visibility_1'); ?>"><?php esc_html_e('Show','car-rental-booking-system'); ?></label>
                                            <input type="radio" value="0" id="<?php CRBSHelper::getFormName('step_1_right_panel_visibility_0'); ?>" name="<?php CRBSHelper::getFormName('step_1_right_panel_visibility'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['step_1_right_panel_visibility'],0); ?>/>
                                            <label for="<?php CRBSHelper::getFormName('step_1_right_panel_visibility_0'); ?>"><?php esc_html_e('Hide','car-rental-booking-system'); ?></label>
                                        </div>                                
                                    </div>
                                </li> 
								<li>
									<h5><?php esc_html_e('Vehicle attributes','car-rental-booking-system'); ?></h5>
									<span class="to-legend"><?php esc_html_e('Enable or disable visibility of vehicle attributes displayed on list in step #2.','car-rental-booking-system'); ?></span>
									<div class="to-checkbox-button">
										<input type="checkbox" value="1" id="<?php CRBSHelper::getFormName('vehicle_attribute_enable_1'); ?>" name="<?php CRBSHelper::getFormName('vehicle_attribute_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_attribute_enable'],1); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_attribute_enable_1'); ?>"><?php esc_html_e('Number of seats','car-rental-booking-system'); ?></label>
										<input type="checkbox" value="2" id="<?php CRBSHelper::getFormName('vehicle_attribute_enable_2'); ?>" name="<?php CRBSHelper::getFormName('vehicle_attribute_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_attribute_enable'],2); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_attribute_enable_2'); ?>"><?php esc_html_e('Number of suitcases','car-rental-booking-system'); ?></label>
										<input type="checkbox" value="3" id="<?php CRBSHelper::getFormName('vehicle_attribute_enable_3'); ?>" name="<?php CRBSHelper::getFormName('vehicle_attribute_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_attribute_enable'],3); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_attribute_enable_3'); ?>"><?php esc_html_e('Gearbox','car-rental-booking-system'); ?></label>
										<input type="checkbox" value="4" id="<?php CRBSHelper::getFormName('vehicle_attribute_enable_4'); ?>" name="<?php CRBSHelper::getFormName('vehicle_attribute_enable[]'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['vehicle_attribute_enable'],4); ?>/>
										<label for="<?php CRBSHelper::getFormName('vehicle_attribute_enable_4'); ?>"><?php esc_html_e('Fuel state','car-rental-booking-system'); ?></label>
									</div>
								</li>  								
							</ul>							
						</div>
					</div>
				</div>
                <div id="meta-box-booking-form-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Panels','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of user defined panels (group of fields) used in client form.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Default tabs "Contact details" and "Billing address" cannot be modified.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-field">
                                    <tr>
                                        <th style="width:85%">
                                            <div>
                                                <?php esc_html_e('Label','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Label of the panel.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:18%">
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
                                                <input type="hidden" name="<?php CRBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
                                                <input type="text" name="<?php CRBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
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
                                                <input type="hidden" value="<?php echo esc_attr($panelValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($panelValue['label']); ?>" name="<?php CRBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','car-rental-booking-system'); ?>"/>
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
                        <li>
                            <h5><?php esc_html_e('Fields','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of user defined fields used in client form.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('Default fields located in tabs "Contact details" and "Billing address" cannot be modified.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-panel">
                                    <tr>
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Label','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Label of the field.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Type','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Field type.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>										
                                        <th style="width:5%">
                                            <div>
                                                <?php esc_html_e('Mandatory','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Mandatory.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>   
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Values','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('List of possible values to choose separated by semicolon.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>   										
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Error message','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Error message displayed in tooltip when field is empty.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                              
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Panel','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Panel in which field has to be located.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                             
                                        <th style="width:10%">
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
                                            <div class="to-clear-fix">
                                                <input type="hidden" name="<?php CRBSHelper::getFormName('form_element_field[id][]'); ?>"/>
                                                <input type="text" name="<?php CRBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CRBSHelper::getFormName('form_element_field[field_type][]'); ?>" class="to-dropkick-disable" id="form_element_field_field_type">
<?php
        foreach($this->data['dictionary']['field_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'">'.esc_html($value[0]).'</option>';
?>
                                                </select>
                                            </div>									
                                        </td>										
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CRBSHelper::getFormName('form_element_field[mandatory][]'); ?>" class="to-dropkick-disable" id="form_element_field_mandatory">
                                                    <option value="1"><?php esc_html_e('Yes','car-rental-booking-system'); ?></option>
                                                    <option value="0"><?php esc_html_e('No','car-rental-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>										
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" name="<?php CRBSHelper::getFormName('form_element_field[dictionary][]'); ?>" title="<?php esc_attr_e('Enter values of list separated by semicolon.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>   										
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" name="<?php CRBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CRBSHelper::getFormName('form_element_field[panel_id][]'); ?>" class="to-dropkick-disable" id="form_element_field_panel_id">
<?php
        foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
            echo '<option value="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</option>';
?>
                                                </select>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
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
                                                <input type="hidden" value="<?php echo esc_attr($fieldValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($fieldValue['label']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <select  id="<?php CRBSHelper::getFormName('form_element_field_field_type_'.$fieldValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[field_type][]'); ?>">
<?php
        foreach($this->data['dictionary']['field_type'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($fieldValue['field_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
                                                </select>
                                            </div>									
                                        </td>	
                                        <td>
                                            <div class="to-clear-fix">
                                                <select id="<?php CRBSHelper::getFormName('form_element_field_mandatory_'.$fieldValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[mandatory][]'); ?>">
                                                    <option value="1" <?php CRBSHelper::selectedIf($fieldValue['mandatory'],1); ?>><?php esc_html_e('Yes','car-rental-booking-system'); ?></option>
                                                    <option value="0" <?php CRBSHelper::selectedIf($fieldValue['mandatory'],0); ?>><?php esc_html_e('No','car-rental-booking-system'); ?></option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" value="<?php echo esc_attr($fieldValue['dictionary']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[dictionary][]'); ?>" title="<?php esc_attr_e('Enter values of list seperated by semicolon.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td> 
                                        <td>
                                            <div class="to-clear-fix">                                                
                                                <input type="text" value="<?php echo esc_attr($fieldValue['message_error']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select id="<?php CRBSHelper::getFormName('form_element_field_panel_id_'.$fieldValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_field[panel_id][]'); ?>">
<?php
        foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
            echo '<option value="'.esc_attr($value['id']).'" '.(CRBSHelper::selectedIf($fieldValue['panel_id'],$value['id'],false)).'>'.esc_html($value['label']).'</option>';
?>
                                                </select>
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
                        <li>
                            <h5><?php esc_html_e('Agreements','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Table includes list of agreements needed to accept by customer before sending the booking.','car-rental-booking-system'); ?><br/>
                                <?php echo _e('Each agreement consists of approval field (checkbox) and text of agreement.','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-clear-fix">
                                <table class="to-table" id="to-table-form-element-agreement">
                                    <tr>
                                        <th style="width:85%">
                                            <div>
                                                <?php esc_html_e('Text','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Text of the agreement.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:15%">
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
                                                <input type="hidden" name="<?php CRBSHelper::getFormName('form_element_agreement[id][]'); ?>"/>
                                                <input type="text" name="<?php CRBSHelper::getFormName('form_element_agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','car-rental-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','car-rental-booking-system'); ?></a>
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
                                                <input type="hidden" value="<?php echo esc_attr($agreementValue['id']); ?>" name="<?php CRBSHelper::getFormName('form_element_agreement[id][]'); ?>"/>
                                                <input type="text" value="<?php echo esc_attr($agreementValue['text']); ?>" name="<?php CRBSHelper::getFormName('form_element_agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','car-rental-booking-system'); ?>"/>
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
                <div id="meta-box-booking-form-3">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Colors','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Specify color for each group of elements.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <table class="to-table">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Group number','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Group number.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:30%">
                                            <div>
                                                <?php esc_html_e('Default color','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Default value of the color.','car-rental-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Color','car-rental-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('New value (in HEX) of the color.','car-rental-booking-system'); ?>
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
                                                 <input type="text" class="to-color-picker" id="<?php CRBSHelper::getFormName('style_color_'.$index); ?>" name="<?php CRBSHelper::getFormName('style_color['.$index.']'); ?>" value="<?php echo esc_attr($this->data['meta']['style_color'][$index]); ?>"/>
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
                <div id="meta-box-booking-form-4">
                    <ul class="to-form-field-list">  
						<li>
							<h5><?php esc_html_e('Draggable','car-rental-booking-system'); ?></h5>
							<span class="to-legend"><?php echo __('Enable or disable dragging on the map.','car-rental-booking-system'); ?></span> 
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_map_draggable_enable_1'); ?>" name="<?php CRBSHelper::getFormName('google_map_draggable_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_draggable_enable'],1); ?>/>
								<label for="<?php CRBSHelper::getFormName('google_map_draggable_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
								<input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_map_draggable_enable_0'); ?>" name="<?php CRBSHelper::getFormName('google_map_draggable_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_draggable_enable'],0); ?>/>
								<label for="<?php CRBSHelper::getFormName('google_map_draggable_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
							</div>                            
						</li>
						<li>
							<h5><?php esc_html_e('Scrollwheel','car-rental-booking-system'); ?></h5>
							<span class="to-legend"><?php echo __('Enable or disable wheel scrolling on the map.','car-rental-booking-system'); ?></span> 
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable_1'); ?>" name="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_scrollwheel_enable'],1); ?>/>
								<label for="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
								<input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable_0'); ?>" name="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_scrollwheel_enable'],0); ?>/>
								<label for="<?php CRBSHelper::getFormName('google_map_scrollwheel_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
							</div>                            
						</li>
						<li>
                            <h5><?php esc_html_e('Map type control','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter settings for a map type.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_map_map_type_control_enable_1'); ?>" name="<?php CRBSHelper::getFormName('google_map_map_type_control_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_map_type_control_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('google_map_map_type_control_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_map_map_type_control_enable_0'); ?>" name="<?php CRBSHelper::getFormName('google_map_map_type_control_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_map_type_control_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('google_map_map_type_control_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>                                
                            </div>   
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Type:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('google_map_map_type_control_id'); ?>" id="<?php CRBSHelper::getFormName('google_map_map_type_control_id'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['map_type_control_id'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_id'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>  
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Style:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('google_map_map_type_control_style'); ?>" id="<?php CRBSHelper::getFormName('google_map_map_type_control_style'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['map_type_control_style'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_style'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>                              
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Position:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('google_map_map_type_control_position'); ?>" id="<?php CRBSHelper::getFormName('google_map_map_type_control_position'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['position'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['google_map_map_type_control_position'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Zoom','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter settings for a zoom.','car-rental-booking-system'); ?>
                            </span> 
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                                <div class="to-radio-button">
                                    <input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_map_zoom_control_enable_1'); ?>" name="<?php CRBSHelper::getFormName('google_map_zoom_control_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_zoom_control_enable'],1); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('google_map_zoom_control_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                    <input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_map_zoom_control_enable_0'); ?>" name="<?php CRBSHelper::getFormName('google_map_zoom_control_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['google_map_zoom_control_enable'],0); ?>/>
                                    <label for="<?php CRBSHelper::getFormName('google_map_zoom_control_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                                </div>                                
                            </div>  
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Position:','car-rental-booking-system'); ?></span>
                                <select name="<?php CRBSHelper::getFormName('google_map_zoom_control_position'); ?>" id="<?php CRBSHelper::getFormName('google_map_zoom_control_position'); ?>">
<?php
		foreach($this->data['dictionary']['google_map']['position'] as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['meta']['google_map_zoom_control_position'],$index,false)).'>'.esc_html($value).'</option>';
?>
                                </select>                                
                            </div>
                            <div class="to-clear-fix">
                                <span class="to-legend-field"><?php esc_html_e('Level:','car-rental-booking-system'); ?></span>
                            	<div class="to-clear-fix">
									<div id="<?php CRBSHelper::getFormName('google_map_zoom_control_level'); ?>"></div>
									<input type="text" name="<?php CRBSHelper::getFormName('google_map_zoom_control_level'); ?>" id="<?php CRBSHelper::getFormName('google_map_zoom_control_level'); ?>" class="to-slider-range" readonly/>
								</div>	                             
                            </div>   
                        </li>
                        <li>
                            <h5><?php esc_html_e('Style','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter (in JSON format) styles for map.','car-rental-booking-system'); ?><br/>
                                <?php echo sprintf(__('You can create your own styles using <a href="%s" target="_blank">Styling Wizard</a>.','car-rental-booking-system'),'https://mapstyle.withgoogle.com/'); ?><br/>
                            </span> 
                            <div class="to-clear-fix">
                                <textarea rows="1" cols="1" name="<?php CRBSHelper::getFormName('google_map_style'); ?>"><?php echo esc_html($this->data['meta']['google_map_style']); ?></textarea>
                            </div>                          
                        </li>
                    </ul> 
                </div>
            </div>
        </div>

<div id="to-google-map-pickup-location"></div>
<?php
        $GeoLocation=new CRBSGeoLocation();
        $userDefaultCoordinate=$GeoLocation->getCoordinate();
?>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
                /***/
                
                var helper=new CRBSHelper();
                helper.getMessageFromConsole();
                
                /***/
                
				var element=$('.to').themeOptionElement({init:true});
                element.createSlider('#<?php CRBSHelper::getFormName('google_map_zoom_control_level'); ?>',1,21,<?php echo (int)$this->data['meta']['google_map_zoom_control_level']; ?>);
                
                /***/
                
                var timeFormat='<?php echo CRBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CRBSJQueryUIDatePicker::convertDateFormat(CRBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);

                /***/
                
                $('#to-table-form-element-panel').table();
                $('#to-table-form-element-field').table();
                $('#to-table-form-element-agreement').table();
                
                /***/
                
                var bookingForm=$().CRBSBookingFormAdmin();
                bookingForm.googleMapAutocompleteCreate($('#crbs_customer_pickup_location_restriction_name'));
                bookingForm.googleMapAutocompleteCreate($('#crbs_customer_return_location_restriction_name'));  
            });
		</script>