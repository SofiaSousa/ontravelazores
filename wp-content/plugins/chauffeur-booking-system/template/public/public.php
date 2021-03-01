<?php    
        global $post;
        
        $Validation=new CHBSValidation();
        
        $class=array('chbs-main','chbs-booking-form-id-'.$this->data['booking_form_post_id'],'chbs-clear-fix','chbs-hidden');

        if($this->data['widget_mode']==1)
            array_push($class,'chbs-widget','chbs-widget-style-'.$this->data['widget_style']);
        
		array_push($class,$this->data['css_class']);
		
        $widgetServiceTypeId=1;
?>
        <div<?php echo CHBSHelper::createCSSClassAttribute($class); ?> id="<?php echo esc_attr($this->data['booking_form_html_id']); ?>">

            <form name="chbs-form" method="POST" target="<?php echo (($this->data['widget_mode']==1) && ($this->data['widget_booking_form_new_window']==1) ? '_blank' : '_self');  ?>">
<?php
        if((int)$this->data['meta']['navigation_top_enable']===1)
        {
            if($this->data['widget_mode']!=1)
            {
?>
                <div class="chbs-main-navigation-default chbs-clear-fix" data-step-count="<?php echo count($this->data['step']['dictionary']); ?>">
                    <ul class="chbs-list-reset">
<?php
                foreach($this->data['step']['dictionary'] as $index=>$value)
                {
                    $class=array();
                    if($index==1) array_push($class,'chbs-state-selected');
?>           
                        <li data-step="<?php echo esc_attr($index); ?>"<?php echo CHBSHelper::createCSSClassAttribute($class); ?> >
                            <div></div>
                            <a href="#">
                                <span>
                                    <span><?php echo esc_html($value['navigation']['number']); ?></span>
                                    <span class="chbs-meta-icon-tick"></span>
                                </span>
                                <span><?php echo esc_html($value['navigation']['label']); ?></span>
                            </a>
                        </li>       
<?php          
                }
?>
                    </ul>
                </div>
                
                <div class="chbs-main-navigation-responsive chbs-box-shadow chbs-clear-fix">
                    <div class="chbs-form-field">
                        <select name="<?php CHBSHelper::getFormName('navigation_responsive'); ?>" data-value="1">
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
                <div class="chbs-main-content chbs-clear-fix">
<?php
        $step=$this->data['widget_mode']==1 ? 1 : 5;

        for($i=1;$i<=$step;$i++)
        {
?> 
                    <div class="chbs-main-content-step-<?php echo $i; ?>">
<?php
            $Template=new CHBSTemplate($this->data,PLUGIN_CHBS_TEMPLATE_PATH.'public/public-step-'.$i.'.php');
            echo $Template->output();
?>
                    </div>
<?php
        }
?>
                </div>
<?php
        if($this->data['widget_mode']!=1)
        {
?>
                <input type="hidden" name="action" data-value=""/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('step') ?>" data-value="1"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('step_request') ?>" data-value="1"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('payment_id') ?>" data-value="<?php echo (int)$this->data['meta']['payment_default_id']; ?>"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('vehicle_id') ?>" data-value="<?php echo (int)$this->data['vehicle_id_default']; ?>"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('booking_extra_id') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('distance_map') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('duration_map') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_distance') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_duration') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_return_distance') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_return_duration') ?>" data-value="0"/>
     
<?php
        }
?>
                <input type="hidden" name="<?php CHBSHelper::getFormName('distance_sum') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('duration_sum') ?>" data-value="0"/>
            
                <input type="hidden" name="<?php CHBSHelper::getFormName('currency') ?>" data-value="<?php echo esc_attr($this->data['currency']); ?>"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('booking_form_id') ?>" data-value="<?php echo esc_attr($this->data['booking_form_post_id']); ?>"/>

                <input type="hidden" name="<?php CHBSHelper::getFormName('service_type_id') ?>" data-value="<?php echo esc_attr((int)CHBSRequestData::get('service_type_id')==0 ? $this->data['meta']['service_type_id_default'] : (int)CHBSRequestData::get('service_type_id')); ?>"/>

                <input type="hidden" name="<?php CHBSHelper::getFormName('post_id') ?>" data-value="<?php echo esc_attr($post->ID); ?>"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('comment_hidden') ?>" data-value="<?php echo esc_attr(CHBSRequestData::get('comment')); ?>"/>
                
            </form>
<?php
        if($this->data['widget_mode']!=1)
        {
            if(isset($this->data['dictionary']['payment']))
            {
                if(array_key_exists(3,$this->data['dictionary']['payment']))
                {
                    $PaymentPaypal=new CHBSPaymentPaypal();
                    echo $PaymentPaypal->createPaymentForm($post->ID,$this->data);
                }
            }
        }
        
        if((int)$this->data['meta']['form_preloader_enable']===1)
        {    
            $style=array(array(),array());
            
            if($Validation->isNotEmpty($this->data['meta']['form_preloader_image_src']))
                $style[1]['background-image']='url(\''.$this->data['meta']['form_preloader_image_src'].'\')';
                    
            $style[0]['background-color']=CHBSColor::HEX2RGBA($this->data['meta']['form_preloader_background_color'].hexdec($this->data['meta']['form_preloader_background_opacity']));
?>
            <div id="chbs-preloader" <?php echo CHBSHelper::createStyleAttribute($style[0]); ?>>
                <div<?php echo CHBSHelper::createStyleAttribute($style[1]); ?>></div>
            </div>
<?php
        }
?>           
            <div id="chbs-preloader-start"></div>
            
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                var bookingForm=$('#<?php echo esc_attr($this->data['booking_form_html_id']); ?>').chauffeurBookingForm(
                {
					booking_form_id												:	<?php echo (int)$this->data['post']->ID; ?>,
                    plugin_version                                              :   '<?php echo PLUGIN_CHBS_VERSION; ?>',
                    ajax_url                                                    :   '<?php echo $this->data['ajax_url']; ?>',
                    length_unit                                                 :   <?php echo (int)CHBSOption::getOption('length_unit'); ?>,
                    time_format                                                 :   '<?php echo CHBSOption::getOption('time_format'); ?>',
                    date_format                                                 :   '<?php echo CHBSOption::getOption('date_format'); ?>',
                    date_format_js                                              :   '<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>',
                    message                                                     :
                    {
                        designate_route_error                                   :   '<?php esc_html_e('It is not possible to create a route between chosen points.','chauffeur-booking-system'); ?>',
                        place_geometry_error                                    :   '<?php esc_html_e('Google Maps API cannot find details for this place.','chauffeur-booking-system'); ?>'
                    },
                    text                                                        :
                    {
                        unit_length_short                                       :   '<?php esc_html_e('km','chauffeur-booking-system')  ?>',
                        unit_time_hour_short                                    :   '<?php esc_html_e('h','chauffeur-booking-system')  ?>',
                        unit_time_minute_short                                  :   '<?php esc_html_e('h','chauffeur-booking-system')  ?>',
                    },
                    date_exclude                                                :   <?php echo json_encode($this->data['meta']['date_exclude']); ?>,
                    datetime_min												:   '<?php echo $this->data['datetime_period']['min']; ?>',
					datetime_max												:   '<?php echo $this->data['datetime_period']['max']; ?>',
                    datetime_min_format											:   '<?php echo $this->data['datetime_period']['min_format']; ?>',
					datetime_max_format											:   '<?php echo $this->data['datetime_period']['max_format']; ?>',
                    business_hour                                               :   <?php echo json_encode($this->data['meta']['business_hour']); ?>,
                    timepicker_step                                             :   <?php echo (int)$this->data['meta']['timepicker_step']; ?>,
                    timepicker_dropdown_list_enable                             :   <?php echo (int)$this->data['meta']['timepicker_dropdown_list_enable']; ?>,
                    summary_sidebar_sticky_enable                               :   <?php echo (int)$this->data['meta']['summary_sidebar_sticky_enable']; ?>,
                    ride_time_multiplier                                        :   <?php echo $this->data['meta']['ride_time_multiplier']; ?>,
                    extra_time_unit                                             :   <?php echo (int)$this->data['meta']['extra_time_unit']; ?>,
                    driving_zone                                                :
                    {
                        pickup                                                  :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['driving_zone_restriction_pickup_location_enable']; ?>,
                            country                                             :   <?php echo json_encode($this->data['meta']['driving_zone_restriction_pickup_location_country']); ?>, 
                            area                                                :
                            {
                                radius                                          :   <?php echo (int)$this->data['meta']['driving_zone_restriction_pickup_location_area_radius']; ?>,
                                coordinate                                      :
                                {
                                    lat                                         :   '<?php echo $this->data['meta']['driving_zone_restriction_pickup_location_area_coordinate_lat']; ?>',
                                    lng                                         :   '<?php echo $this->data['meta']['driving_zone_restriction_pickup_location_area_coordinate_lng']; ?>'
                                }
                            }
                        },
                        dropoff                                                 :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['driving_zone_restriction_dropoff_location_enable']; ?>,
                            country                                             :   <?php echo json_encode($this->data['meta']['driving_zone_restriction_dropoff_location_country']); ?>,
                            area                                                :
                            {
                                radius                                          :   <?php echo (int)$this->data['meta']['driving_zone_restriction_dropoff_location_area_radius']; ?>,
                                coordinate                                      :
                                {
                                    lat                                         :   '<?php echo $this->data['meta']['driving_zone_restriction_dropoff_location_area_coordinate_lat']; ?>',
                                    lng                                         :   '<?php echo $this->data['meta']['driving_zone_restriction_dropoff_location_area_coordinate_lng']; ?>'
                                }
                            }                       
                        }
                    },
                    gooogle_map_option                                          :
                    {
                        route_avoid                                             :   <?php echo json_encode($this->data['meta']['google_map_route_avoid']); ?>,
                        draggable                                               :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_draggable_enable']; ?>
                        },
                        draggable_location                                      :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_draggable_location_enable']; ?>
                        },                               
                        traffic_layer                                           :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_traffic_layer_enable']; ?>
                        },
                        scrollwheel                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_scrollwheel_enable']; ?>
                        },
                        map_control                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_map_type_control_enable']; ?>,
                            id                                                  :   '<?php echo $this->data['meta']['google_map_map_type_control_id']; ?>',
                            style                                               :   '<?php echo $this->data['meta']['google_map_map_type_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_map_type_control_position']; ?>'
                        },
                        zoom_control                                            :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_zoom_control_enable']; ?>,
                            style                                               :   '<?php echo $this->data['meta']['google_map_zoom_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_zoom_control_position']; ?>',
                            level                                               :   <?php echo (int)$this->data['meta']['google_map_zoom_control_level']; ?>                            
                        },
                        default_location                                        :
                        {
                            type                                                :   <?php echo (int)$this->data['meta']['google_map_default_location_type']; ?>,
                            coordinate                                          :
                            {
                                lat                                             :   '<?php echo $this->data['meta']['google_map_default_location_fixed_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['google_map_default_location_fixed_coordinate_lng']; ?>'
                            }
                        },
                    },
                    base_location                                               :
                    {
                        coordinate                                              :
                        {
                            lat                                                 :   '<?php echo $this->data['meta']['base_location_coordinate_lat']; ?>',
                            lng                                                 :   '<?php echo $this->data['meta']['base_location_coordinate_lng']; ?>'
                        }
                    },
                    widget                                                      :
                    {
                        mode                                                    :   <?php echo (int)$this->data['widget_mode']; ?>,
                        booking_form_url                                        :   '<?php echo $this->data['widget_booking_form_url']; ?>'
                    },
                    rtl_mode                                                    :   <?php echo (int)is_rtl(); ?> ,
                    scroll_to_booking_extra_after_select_vehicle_enable         :   <?php echo (int)$this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable']; ?>,
                    current_date                                                :   '<?php echo date_i18n('d-m-Y'); ?>',
                    current_time                                                :   '<?php echo date_i18n('H:i'); ?>',
                    google_autosugestion_address_type                           :   <?php echo (int)$this->data['meta']['google_autosugestion_address_type']; ?>,
                    icon_field_enable                                           :   <?php echo (int)$this->data['meta']['icon_field_enable']; ?>,
					use_my_location_link_enable									:	<?php echo (int)$this->data['meta']['use_my_location_link_enable']; ?>,
					client_country_code											:	'<?php echo $this->data['client_country_code']; ?>'	
               });
               bookingForm.setup();
            });
        </script>
            