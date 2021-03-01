<?php    
        global $post;
        
        $Validation=new CRBSValidation();

        $class=array('crbs-main','crbs-booking-form-id-'.$this->data['booking_form_post_id'],'crbs-clear-fix','crbs-hidden');
        
        if($this->data['widget_mode']==1)
            array_push($class,'crbs-booking-form-widget-mode','crbs-booking-form-widget-mode-style-'.$this->data['widget_booking_form_style_id']);
?>
        <div<?php echo CRBSHelper::createCSSClassAttribute($class); ?> id="<?php echo esc_attr($this->data['booking_form_html_id']); ?>">
            
            <form name="crbs-form" enctype="multipart/form-data">
<?php
        if((int)$this->data['meta']['navigation_top_enable']===1)
        {
            if($this->data['widget_mode']!=1)
            {
?>
                <div class="crbs-main-navigation-default crbs-clear-fix" data-step-count="<?php echo count($this->data['step']['dictionary']); ?>">
                    <ul class="crbs-list-reset">
<?php
				foreach($this->data['step']['dictionary'] as $index=>$value)
				{
					$class=array();
					if($index==1) array_push($class,'crbs-state-selected');
?>           
                        <li data-step="<?php echo esc_attr($index); ?>"<?php echo CRBSHelper::createCSSClassAttribute($class); ?> >
                            <div></div>
                            <a href="#">
                                <span>
                                    <span><?php echo esc_html($value['navigation']['number']); ?></span>
                                    <span class="crbs-meta-icon-tick"></span>
                                </span>
                                <span><?php echo esc_html($value['navigation']['label']); ?></span>
                            </a>
                        </li>       
<?php          
				}
?>
                    </ul>
                </div>
                
                <div class="crbs-main-navigation-responsive crbs-clear-fix">
                    <div class="crbs-form-field">
                        <select name="<?php CRBSHelper::getFormName('navigation_responsive'); ?>" data-value="1">
<?php
				foreach($this->data['step']['dictionary'] as $index=>$value)
				{
?>            
                            <option value="<?php echo esc_attr($index); ?>">
                                <?php echo esc_html($value['navigation']['number'].'. '.$value['navigation']['label']); ?>
                            </option>       
<?php          
				}          
?>                
                        </select>
                    </div>
                </div>
<?php
            }
        }
?>
                <div class="crbs-main-content crbs-clear-fix">
<?php
        for($i=1;$i<=($this->data['widget_mode']===1 ? 1 : 5);$i++)
        {
?> 
                    <div class="crbs-main-content-step-<?php echo $i; ?>">
<?php
            $Template=new CRBSTemplate($this->data,PLUGIN_CRBS_TEMPLATE_PATH.'public/public-step-'.$i.'.php');
            echo $Template->output();
?>
                    </div>
<?php
        }
?>
                </div>
                
                <input type="hidden" name="action" data-value=""/>
                
                <input type="hidden" name="<?php CRBSHelper::getFormName('step'); ?>" data-value="1"/>
                <input type="hidden" name="<?php CRBSHelper::getFormName('step_request'); ?>" data-value="1"/>

                <input type="hidden" name="<?php CRBSHelper::getFormName('vehicle_id'); ?>" data-value="0"/>
                <input type="hidden" name="<?php CRBSHelper::getFormName('payment_id'); ?>" data-value="0"/>
                <input type="hidden" name="<?php CRBSHelper::getFormName('booking_extra_id'); ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CRBSHelper::getFormName('booking_form_id'); ?>" data-value="<?php echo esc_attr($this->data['booking_form_post_id']); ?>"/>
                
                <input type="hidden" name="<?php CRBSHelper::getFormName('post_id'); ?>" data-value="<?php echo esc_attr($post->ID); ?>"/>
				
				<input type="hidden" name="<?php CRBSHelper::getFormName('currency'); ?>" data-value="<?php echo esc_attr($this->data['currency']); ?>"/>
				                
            </form>
            
            <div id="crbs-payment-form">
<?php
        foreach($this->data['dictionary']['location'] as $index=>$value)
        {
            if(in_array(3,$value['meta']['payment_id']))
            {
                $PaymentPaypal=new CRBSPaymentPaypal();
                echo $PaymentPaypal->createPaymentForm($post->ID,$index,$value);
            }
        }
?>  
            </div>
<?php
        if((int)$this->data['meta']['form_preloader_enable']===1)
        {
?>
            <div id="crbs-preloader"></div>
<?php
        }
?>
            <div id="crbs-preloader-start"></div>
            
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                var bookingForm=$('#<?php echo esc_attr($this->data['booking_form_html_id']); ?>').CRBSBookingForm(
                {
					booking_form_id												:	<?php echo (int)$this->data['post']->ID; ?>,
					plugin_version                                              :   '<?php echo PLUGIN_CRBS_VERSION; ?>',
                    widget                                                      :
                    {
                        mode                                                    :   <?php echo (int)$this->data['widget_mode']; ?>,
                        booking_form_url                                        :   '<?php echo $this->data['widget_booking_form_url']; ?>'
                    },
                    ajax_url                                                    :   '<?php echo $this->data['ajax_url']; ?>',
                    plugin_url                                                  :   '<?php echo PLUGIN_CRBS_URL; ?>',
                    time_format                                                 :   '<?php echo CRBSOption::getOption('time_format'); ?>',
                    date_format                                                 :   '<?php echo CRBSOption::getOption('date_format'); ?>',
                    date_format_js                                              :   '<?php echo CRBSJQueryUIDatePicker::convertDateFormat(CRBSOption::getOption('date_format')); ?>',
                    timepicker_step                                             :   '<?php echo $this->data['meta']['timepicker_step']; ?>',
                    vehicle_count_enable                                        :   <?php echo (int)$this->data['meta']['vehicle_count_enable']; ?>,
                    summary_sidebar_sticky_enable                               :   <?php echo (int)$this->data['meta']['summary_sidebar_sticky_enable']; ?>,
					location_customer_only_enable								:	<?php echo (int)$this->data['meta']['location_customer_only_enable']; ?>,
					location_the_same_enable									:   <?php echo (int)$this->data['meta']['location_the_same_enable']; ?>,
                    location_date_exclude                                       :   <?php echo json_encode($this->data['location_date_exclude']); ?>,
                    location_business_hour                                      :   <?php echo json_encode($this->data['location_business_hour']); ?>,
                    location_pickup_period										:   <?php echo json_encode($this->data['location_pickup_period'],JSON_UNESCAPED_SLASHES); ?>,
					location_pickup_period_format								:   <?php echo json_encode($this->data['location_pickup_period_format'],JSON_UNESCAPED_SLASHES); ?>,
                    location_coordinate                                         :   <?php echo json_encode($this->data['location_coordinate']); ?>,
                    location_info                                               :   <?php echo json_encode($this->data['location_info']); ?>,
                    location_driver_age                                         :   <?php echo ($this->data['meta']['driver_age_enable']==1 ? json_encode($this->data['location_driver_age']) : '{}'); ?>,
                    location_after_business_hour_pickup_enable					:   <?php echo json_encode($this->data['location_after_business_hour_pickup_enable']); ?>,
					location_after_business_hour_return_enable                  :   <?php echo json_encode($this->data['location_after_business_hour_return_enable']); ?>,
                    location_vehicle_id_default                                 :   <?php echo json_encode($this->data['location_vehicle_id_default']); ?>,
                    location_client_country_default								:	<?php echo json_encode($this->data['location_client_country_default']); ?>,
                    location_payment_paypal_redirect_duration					:	<?php echo json_encode($this->data['location_payment_paypal_redirect_duration']); ?>,					
					client_coordinate                                           :   <?php echo json_encode($this->data['client_coordinate']); ?>,   
                    geolocation_enable                                          :   <?php echo json_encode($this->data['meta']['geolocation_enable']); ?>,
                    customer_pickup_location_restriction_radius                 :   <?php echo (int)$this->data['meta']['customer_pickup_location_restriction_radius']; ?>,
                    customer_pickup_location_restriction_coordinate_lat         :   '<?php echo $this->data['meta']['customer_pickup_location_restriction_coordinate_lat']; ?>',
                    customer_pickup_location_restriction_coordinate_lng         :   '<?php echo $this->data['meta']['customer_pickup_location_restriction_coordinate_lng']; ?>',
                    customer_pickup_location_restriction_country                :   <?php echo json_encode($this->data['meta']['customer_pickup_location_restriction_country']); ?>,
                    customer_return_location_restriction_radius                 :   <?php echo (int)$this->data['meta']['customer_return_location_restriction_radius']; ?>,
                    customer_return_location_restriction_coordinate_lat         :   '<?php echo $this->data['meta']['customer_return_location_restriction_coordinate_lat']; ?>',
                    customer_return_location_restriction_coordinate_lng         :   '<?php echo $this->data['meta']['customer_return_location_restriction_coordinate_lng']; ?>',
                    customer_return_location_restriction_country                :   <?php echo json_encode($this->data['meta']['customer_return_location_restriction_country']); ?>,
					gooogleMapOption                                            :
                    {
                        draggable                                               :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_draggable_enable']; ?>
                        },
                        scrollwheel                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_scrollwheel_enable']; ?>
                        },
                        mapControl                                              :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_map_type_control_enable']; ?>,
                            id                                                  :   '<?php echo $this->data['meta']['google_map_map_type_control_id']; ?>',
                            style                                               :   '<?php echo $this->data['meta']['google_map_map_type_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_map_type_control_position']; ?>'
                        },
                        zoomControl                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_zoom_control_enable']; ?>,
                            style                                               :   '<?php echo $this->data['meta']['google_map_zoom_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_zoom_control_position']; ?>',
                            level                                               :   <?php echo (int)$this->data['meta']['google_map_zoom_control_level']; ?>                            
                        },
                        style                                                   :   <?php echo ($Validation->isEmpty($this->data['meta']['google_map_style']) ? '[]' : $this->data['meta']['google_map_style']); ?>
                    },
                    booking_form_color                                          :   <?php echo json_encode($this->data['booking_form_color']); ?>,
                    is_rtl                                                      :   <?php echo (int)is_rtl(); ?>,
                    scroll_to_booking_extra_after_select_vehicle_enable         :   <?php echo (int)$this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable']; ?>,
					current_date												:   '<?php echo date_i18n('d-m-Y'); ?>',
                    current_time                                                :   '<?php echo date_i18n('H:i'); ?>',
                });
		
				bookingForm.setup();
            });
        </script>
            