<?php 
		echo $this->data['nonce']; 
        
        $Length=new CHBSLength();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-vehicle-1"><?php esc_html_e('General','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-2"><?php esc_html_e('Prices','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-3"><?php esc_html_e('Attributes','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-vehicle-4"><?php esc_html_e('Availability','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-vehicle-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Vehicle make','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle make.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CHBSHelper::getFormName('vehicle_make'); ?>" id="<?php CHBSHelper::getFormName('vehicle_make'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_make']); ?>"/>
                            </div>
                        </li>    
                        <li>
                            <h5><?php esc_html_e('Vehicle model','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle model.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CHBSHelper::getFormName('vehicle_model'); ?>" id="<?php CHBSHelper::getFormName('vehicle_model'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle_model']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Company','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Company name of vehcile owner.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <select name="<?php CHBSHelper::getFormName('vehicle_company_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle_company_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['vehicle_company'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle_company_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                </select>                                                  
                            </div>
                        </li>                        
                        <li>
                            <h5><?php esc_html_e('Number of passengers','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Maximum number of passengers (or seats). Integer value from 1 to 99.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input maxlength="2" type="text" name="<?php CHBSHelper::getFormName('passenger_count'); ?>" id="<?php CHBSHelper::getFormName('passenger_count'); ?>" value="<?php echo esc_attr($this->data['meta']['passenger_count']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Number of suitcases','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Maximum number of suitcases. Integer value from 1 to 99.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input maxlength="2" type="text" name="<?php CHBSHelper::getFormName('bag_count'); ?>" id="<?php CHBSHelper::getFormName('bag_count'); ?>" value="<?php echo esc_attr($this->data['meta']['bag_count']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Vehicle standard','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Vehicle standrad. Integer value from 1 to 4.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input maxlength="1" type="text" name="<?php CHBSHelper::getFormName('standard'); ?>" id="<?php CHBSHelper::getFormName('standard'); ?>" value="<?php echo esc_attr($this->data['meta']['standard']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Base location','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Vehicle base location.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('This option overwrites base location from booking form.','chauffeur-booking-system'); ?>
                            </span>
                            <div>
                                <input type="text" name="<?php CHBSHelper::getFormName('base_location'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location']); ?>"/>
                                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_coordinate_lat'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location_coordinate_lat']); ?>"/>
                                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_coordinate_lng'); ?>" value="<?php echo esc_attr($this->data['meta']['base_location_coordinate_lng']); ?>"/>
                            </div>                                  
                        </li>   
                        <li>
                            <h5><?php esc_html_e('Gallery','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Click on button below to create a gallery for vehicle.','chauffeur-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <input type="hidden" name="<?php CHBSHelper::getFormName('gallery_image_id'); ?>" id="<?php CHBSHelper::getFormName('gallery_image_id'); ?>" value="<?php echo esc_attr(implode('.',$this->data['meta']['gallery_image_id'])); ?>"/>
                                <input type="button" name="<?php CHBSHelper::getFormName('gallery_image_id_browse'); ?>" id="<?php CHBSHelper::getFormName('gallery_image_id_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                            </div>
                        </li>                         
                    </ul>
                </div>
                <div id="meta-box-vehicle-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Price type','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select price type.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('For a "Variable pricing" final price of the ride depends on distance or time.','chauffeur-booking-system'); ?><br/>
                                <?php esc_html_e('For a "Fixed pricing" final price of the ride is independent on distance or time.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['price_type'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('price_type_'.$index); ?>" name="<?php CHBSHelper::getFormName('price_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['price_type'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('price_type_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
                            </div>
                        </li>
<?php
        $class=array(1=>array('to-price-type-1'),2=>array('to-price-type-2'));
        array_push($class[$this->data['meta']['price_type']==1 ? 2 : 1],'to-state-disabled');
?>
                        <li>
                            <h5><?php esc_html_e('Prices','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Net prices.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table to-table-price">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:10%">
                                            <div>
                                                <?php esc_html_e('Type','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Type.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:36%">
                                            <div>
                                                <?php esc_html_e('Description','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Description.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:18%">
                                            <div>
                                                <?php esc_html_e('Value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:18%">
                                            <div>
                                                <?php esc_html_e('Tax','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                          
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed price for a ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_fixed_value'); ?>" id="<?php CHBSHelper::getFormName('price_fixed_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_fixed_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_fixed_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed (return)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed price for a return ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_fixed_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_fixed_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_fixed_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_fixed_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed (return, new ride)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed price for a return, new ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_fixed_return_new_ride_value'); ?>" id="<?php CHBSHelper::getFormName('price_fixed_return_new_ride_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_fixed_return_new_ride_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_fixed_return_new_ride_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_new_ride_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_new_ride_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Initial','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed value which is added to the order sum.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_initial_value'); ?>" id="<?php CHBSHelper::getFormName('price_initial_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_initial_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_initial_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                     
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per kilometer of ride from base to customer pickup location.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_delivery_value'); ?>" id="<?php CHBSHelper::getFormName('price_delivery_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_delivery_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>   
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery (return)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per kilometer of ride from customer drop off location to base.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_delivery_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_delivery_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_delivery_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>    
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php echo $Length->label(-1,3); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per distance.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_distance_value'); ?>" id="<?php CHBSHelper::getFormName('price_distance_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_distance_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_distance_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php echo $Length->label(-1,3); ?><?php esc_html_e(' (return)'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per distance for return ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_distance_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_distance_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_distance_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_distance_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php echo $Length->label(-1,3); ?><?php esc_html_e(' (return, new ride)'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per distance for return, new ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_distance_return_new_ride_value'); ?>" id="<?php CHBSHelper::getFormName('price_distance_return_new_ride_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_distance_return_new_ride_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_distance_return_new_ride_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_new_ride_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_new_ride_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>  
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per hour','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_hour_value'); ?>" id="<?php CHBSHelper::getFormName('price_hour_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_hour_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_hour_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per hour (return)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour for return ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_hour_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_hour_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_hour_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_hour_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                    
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per hour (return, new ride)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour for return, new ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_hour_return_new_ride_value'); ?>" id="<?php CHBSHelper::getFormName('price_hour_return_new_ride_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_hour_return_new_ride_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_hour_return_new_ride_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_return_new_ride_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_return_new_ride_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>    
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per extra time (hour)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?><br/>
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour for extra time.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_extra_time_value'); ?>" id="<?php CHBSHelper::getFormName('price_extra_time_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_extra_time_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_extra_time_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_extra_time_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_extra_time_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per adult','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per adult.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_passenger_adult_value'); ?>" id="<?php CHBSHelper::getFormName('price_passenger_adult_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_passenger_adult_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_passenger_adult_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_adult_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_adult_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>   
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per child','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per child.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_passenger_children_value'); ?>" id="<?php CHBSHelper::getFormName('price_passenger_children_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_passenger_children_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_passenger_children_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_children_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_children_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
        if((isset($this->data['dictionary']['vehicle_attribute'])) && (count($this->data['dictionary']['vehicle_attribute'])))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Attributes','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify attributes of the vehicle.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Attribute name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Attribute name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Attribute value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Attribute value(s).','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>       
<?php
            foreach($this->data['dictionary']['vehicle_attribute'] as $attributeIndex=>$attributeValue)
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
                                                <input type="text" id="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.']'); ?>" name="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.']'); ?>" value="<?php echo esc_attr($this->data['meta']['attribute'][$attributeIndex]); ?>"/>
<?php                       
                    break;
                    case 2:
                    case 3:
                            
                        $type=$attributeValue['meta']['attribute_type']==2 ? 'radio' : 'checkbox';
?>
                                                <div class="to-<?php echo esc_attr($type); ?>-button">
                                                    <input type="<?php echo esc_attr($type); ?>" value="-1" id="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.'][0]'); ?>" name="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.'][]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['attribute'][$attributeIndex],-1); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.'][0]'); ?>"><?php esc_html_e('- Not set -','chauffeur-booking-system'); ?></label>
<?php
                        foreach($attributeValue['meta']['attribute_value'] as $data)
                        {
?>                           
                                                    <input type="<?php echo esc_attr($type); ?>" value="<?php echo (int)$data['id']; ?>" id="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.']['.(int)$data['id'].']'); ?>" name="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.'][]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['attribute'][$attributeIndex],(int)$data['id']); ?>/>
                                                    <label for="<?php CHBSHelper::getFormName('attribute['.$attributeIndex.']['.(int)$data['id'].']'); ?>"><?php echo esc_html($data['value']); ?></label>
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
                            <h5><?php esc_html_e('Exclude dates','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify dates in which vehicle is not available. Past (or invalid date ranges) will be removed during saving.','chauffeur-booking-system'); ?></span>
                            <div>	
                                <table class="to-table" id="to-table-availability-exclude-date">
                                    <tr>
                                        <th style="width:40%" colspan="2">
                                            <div>
                                                <?php esc_html_e('Start Date','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Enter start date and time (optionally).','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%" colspan="2">
                                            <div>
                                                <?php esc_html_e('End Date','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Enter end date and time (optionally).','chauffeur-booking-system'); ?>
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
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>                                        
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>
<?php
        $Date=new CHBSDate();
		if(count($this->data['meta']['date_exclude']))
		{
			foreach($this->data['meta']['date_exclude'] as $dateExcludeIndex=>$dateExcludeValue)
			{
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['startDate'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','chauffeur-booking-system'); ?>"  value="<?php echo esc_attr($Date->formatTimeToDisplay($dateExcludeValue['startTime'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start date.','chauffeur-booking-system'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($dateExcludeValue['stopDate'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('date_exclude[]'); ?>" title="<?php esc_attr_e('Enter start time.','chauffeur-booking-system'); ?>"  value="<?php echo esc_attr($Date->formatTimeToDisplay($dateExcludeValue['stopTime'])); ?>"/>
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
                            <h5><?php esc_html_e('Day number','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select number of the day in which vehicle is available.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('vehicle_availability_day_number_0'); ?>" name="<?php CHBSHelper::getFormName('vehicle_availability_day_number[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_availability_day_number'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('vehicle_availability_day_number_0'); ?>"><?php esc_html_e('- All days -','chauffeur-booking-system') ?></label>
<?php
        foreach($this->data['dictionary']['day'] as $index=>$value)
        {
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('vehicle_availability_day_number_'.$index); ?>" name="<?php CHBSHelper::getFormName('vehicle_availability_day_number[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_availability_day_number'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('vehicle_availability_day_number_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php
        }
?>                                
                            </div>                        
                        </li>
                    </ul>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
                var helper=new Helper();
                helper.getMessageFromConsole();
                
                /***/
                
				var element=$('.to').themeOptionElement({init:true});
                
                /***/
                
                $('#to-table-vehicle-attribute input[type="checkbox"]').on('change',function()
                {
                    var value=parseInt($(this).val());

                    var checkbox=$(this).parents('div:first').find('input');

                    if(value===-1)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.first().attr('checked','checked');
                    }
                    else checkbox.first().removeAttr('checked');
                    
                    checkbox.button('refresh');
                });
                
                /***/
                
                $('#to-table-availability-exclude-date').table();
                
                /***/
                
                var timeFormat='<?php echo CHBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
                
                /***/
                
                toTogglePriceType('.to input[name="<?php CHBSHelper::getFormName('price_type'); ?>"]','.to .to-table-price');
                
                /***/

                element.bindBrowseMedia('input[name="chbs_gallery_image_id_browse"]',true,2);
                
                /***/
                
                toCreateAutocomplete('input[name="<?php CHBSHelper::getFormName('base_location'); ?>"]');
                
                /***/
                
                $('input[name="<?php CHBSHelper::getFormName('vehicle_availability_day_number'); ?>[]"]').on('change',function()
                {
                    var checkbox=$(this).parents('li:first').find('input');
                    
                    var value=parseInt($(this).val());
                    if(value===-1)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.first().attr('checked','checked');
                    }
                    else checkbox.first().removeAttr('checked');
                    
                    var checked=[];
                    checkbox.each(function()
                    {
                        if($(this).is(':checked'))
                            checked.push(parseInt($(this).val(),10));
                    });
                    
                    if(checked.length===0)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.first().attr('checked','checked');
                    }
                    
                    checkbox.button('refresh');
                });
                
                /***/
            });
		</script>