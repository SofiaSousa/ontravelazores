
        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Logo','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Select company logo.','chauffeur-booking-system'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('logo'); ?>" id="<?php CHBSHelper::getFormName('logo'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['option']['logo']); ?>"/>
                    <input type="button" name="<?php CHBSHelper::getFormName('logo_browse'); ?>" id="<?php CHBSHelper::getFormName('logo_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                </div>
            </li> 
            <li>
                <h5><?php esc_html_e('Google Maps API key','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php echo sprintf(__('You can generate your own key <a href="%s" target="_blank">here</a>.','chauffeur-booking-system'),'https://developers.google.com/maps/documentation/javascript/get-api-key'); ?><br/>
                    <?php esc_html_e('You have to enable libraries: Places, Maps JavaScript, Roads, Geocoding, Directions.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('google_map_api_key'); ?>" id="<?php CHBSHelper::getFormName('google_map_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['google_map_api_key']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Remove duplicated Google Maps scripts','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enable this option to remove Google Maps script from theme and other, included plugins.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('This option allows to prevent errors related with including the same script more than once.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
                     <div class="to-radio-button">
                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove_1'); ?>" name="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['google_map_duplicate_script_remove'],1); ?>/>
                        <label for="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove_1'); ?>"><?php esc_html_e('Enable (remove)','chauffeur-booking-system'); ?></label>
                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove_0'); ?>" name="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['google_map_duplicate_script_remove'],0); ?>/>
                        <label for="<?php CHBSHelper::getFormName('google_map_duplicate_script_remove_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                    </div>
                </div>
            </li>              
            <li>
                <h5><?php esc_html_e('Currency','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Settings for base currency used in the plugin.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('If these details will be empty, default values for currency will be used.','chauffeur-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('Base currency:','chauffeur-booking-system'); ?></span>
					<select name="<?php CHBSHelper::getFormName('currency'); ?>" id="<?php CHBSHelper::getFormName('currency'); ?>">
<?php
						foreach($this->data['dictionary']['currency'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['currency'],$index,false)).'>'.esc_html($index.' ('.$value['name'].')').'</option>';
?>
					</select>
				</div>
            </li>
            <li>
                <h5><?php esc_html_e('Length unit','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Length unit.','chauffeur-booking-system'); ?></span>
                <div class="to-clear-fix">
					<select name="<?php CHBSHelper::getFormName('length_unit'); ?>" id="<?php CHBSHelper::getFormName('length_unit'); ?>">
<?php
						foreach($this->data['dictionary']['length_unit'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['length_unit'],$index,false)).'>'.esc_html($value[0].' ('.$value[1].')').'</option>';
?>
					</select>
				</div>
            </li>   
            <li>
                <h5><?php esc_html_e('Date format','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the date format to be displayed. More info you can find here %s.','chauffeur-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('date_format'); ?>" id="<?php CHBSHelper::getFormName('date_format'); ?>" value="<?php echo esc_attr($this->data['option']['date_format']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Time format','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the time format to be displayed. More info you can find here %s.','chauffeur-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('time_format'); ?>" id="<?php CHBSHelper::getFormName('time_format'); ?>" value="<?php echo esc_attr($this->data['option']['time_format']); ?>"/>
                </div>
            </li>               
            <li>
                <h5><?php esc_html_e('Default sender e-mail account','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select default e-mail account.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('It will be used to sending email messages to clients about changing of booking status and to driver about assign/unassign to the booking.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
					<select name="<?php CHBSHelper::getFormName('sender_default_email_account_id'); ?>" id="<?php CHBSHelper::getFormName('sender_default_email_account_id'); ?>">
<?php
						echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],-1,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
						foreach($this->data['dictionary']['email_account'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
					</select>
				</div>
            </li> 
            <li>
                <h5><?php esc_html_e('Geolocation server','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select which servers has to handle geolocation requests.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('For some of them, set up extra data could be needed.','chauffeur-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('Server:','chauffeur-booking-system'); ?></span>
                    <div>
                        <select name="<?php CHBSHelper::getFormName('geolocation_server_id'); ?>" id="<?php CHBSHelper::getFormName('geolocation_server_id'); ?>">
<?php
						echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['option']['geolocation_server_id'],-1,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
						foreach($this->data['dictionary']['geolocation_server'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['geolocation_server_id'],$index,false)).'>'.esc_html($value['name']).'</option>';
?>
                        </select>
                    </div>
				</div>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('API key for ipstack server:','chauffeur-booking-system'); ?></span>
                    <div>
                        <input type="text" name="<?php CHBSHelper::getFormName('geolocation_server_id_3_api_key'); ?>" id="<?php CHBSHelper::getFormName('geolocation_server_id_3_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['geolocation_server_id_3_api_key']); ?>"/>
                    </div>
                </div>
            </li>             
            <li>
                <h5><?php esc_html_e('Fixer.io API key','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php echo sprintf(__('Enter API key generated by <a href="%s" target="_blank">Fixer.io</a>.','chauffeur-booking-system'),'https://fixer.io/'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('fixer_io_api_key'); ?>" id="<?php CHBSHelper::getFormName('fixer_io_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['fixer_io_api_key']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Duration/distance in pricing rules','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select which type (one way or one way + return) of distance and duration should be used in pricing rules for return and return (new ride) transfer type.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('For the first option, pricing rule will use full (one way + return) distance/duration, for the second one values for one way will be used.','chauffeur-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <div class="to-radio-button">
                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('pricing_rule_return_use_type_1'); ?>" name="<?php CHBSHelper::getFormName('pricing_rule_return_use_type'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['pricing_rule_return_use_type'],1); ?>/>
                        <label for="<?php CHBSHelper::getFormName('pricing_rule_return_use_type_1'); ?>"><?php esc_html_e('Use sum of distance/duration','chauffeur-booking-system'); ?></label>
                        <input type="radio" value="2" id="<?php CHBSHelper::getFormName('pricing_rule_return_use_type_2'); ?>" name="<?php CHBSHelper::getFormName('pricing_rule_return_use_type'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['pricing_rule_return_use_type'],2); ?>/>
                        <label for="<?php CHBSHelper::getFormName('pricing_rule_return_use_type_2'); ?>"><?php esc_html_e('Use one way distance/duration','chauffeur-booking-system'); ?></label>
                    </div>
                </div>
            </li>   
        </ul>