        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Billing type','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Select billing type.','car-rental-booking-system'); ?></span>
                <div class="to-clear-fix">
					<select name="<?php CRBSHelper::getFormName('billing_type'); ?>" id="<?php CRBSHelper::getFormName('billing_type'); ?>">
<?php
						foreach($this->data['dictionary']['billing_type'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['option']['billing_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
					</select>
				</div>
            </li>
            <li>
                <h5><?php esc_html_e('Logo','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Select company logo.','car-rental-booking-system'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CRBSHelper::getFormName('logo'); ?>" id="<?php CRBSHelper::getFormName('logo'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['option']['logo']); ?>"/>
                    <input type="button" name="<?php CRBSHelper::getFormName('logo_browse'); ?>" id="<?php CRBSHelper::getFormName('logo_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                </div>
            </li> 
            <li>
                <h5><?php esc_html_e('Google Maps API key','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php echo sprintf(__('You can generate your own key <a href="%s" target="_blank">here</a>.','car-rental-booking-system'),'https://developers.google.com/maps/documentation/javascript/get-api-key'); ?><br/>
                    <?php esc_html_e('You have to enable libraries: Places, Maps JavaScript, Roads, Geocoding, Directions. ','car-rental-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CRBSHelper::getFormName('google_map_api_key'); ?>" id="<?php CRBSHelper::getFormName('google_map_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['google_map_api_key']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Remove duplicated Google Maps scripts','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enable this option to remove Google Maps script from theme and other, included plugins.','car-rental-booking-system'); ?><br/>
                    <?php esc_html_e('This option allows to prevent errors related with including the same script more than once.','car-rental-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
                     <div class="to-radio-button">
                        <input type="radio" value="1" id="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove_1'); ?>" name="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['google_map_duplicate_script_remove'],1); ?>/>
                        <label for="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove_1'); ?>"><?php esc_html_e('Enable (remove)','car-rental-booking-system'); ?></label>
                        <input type="radio" value="0" id="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove_0'); ?>" name="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['google_map_duplicate_script_remove'],0); ?>/>
                        <label for="<?php CRBSHelper::getFormName('google_map_duplicate_script_remove_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                    </div>
                </div>
            </li>     
            <li>
                <h5><?php esc_html_e('Currency','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Currency.','car-rental-booking-system'); ?></span>
                <div class="to-clear-fix">
					<select name="<?php CRBSHelper::getFormName('currency'); ?>" id="<?php CRBSHelper::getFormName('currency'); ?>">
<?php
						foreach($this->data['dictionary']['currency'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['option']['currency'],$index,false)).'>'.esc_html($index.' ('.$value['name'].')').'</option>';
?>
					</select>
				</div>
            </li>
            <li>
                <h5><?php esc_html_e('Date format','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the date format to be displayed. More info you can find here %s.','car-rental-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CRBSHelper::getFormName('date_format'); ?>" id="<?php CRBSHelper::getFormName('date_format'); ?>" value="<?php echo esc_attr($this->data['option']['date_format']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Time format','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the time format to be displayed. More info you can find here %s.','car-rental-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CRBSHelper::getFormName('time_format'); ?>" id="<?php CRBSHelper::getFormName('time_format'); ?>" value="<?php echo esc_attr($this->data['option']['time_format']); ?>"/>
                </div>
            </li>               
            <li>
                <h5><?php esc_html_e('Default sender e-mail account','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select default e-mail account.','car-rental-booking-system'); ?><br/>
                    <?php esc_html_e('It will be used to sending email messages to clients about changing of booking status.','car-rental-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
					<select name="<?php CRBSHelper::getFormName('sender_default_email_account_id'); ?>" id="<?php CRBSHelper::getFormName('sender_default_email_account_id'); ?>">
<?php
						echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],-1,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
						foreach($this->data['dictionary']['email_account'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
					</select>
				</div>
            </li>
            <li>
                <h5><?php esc_html_e('Geolocation server','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select which servers has to handle geolocation requests.','car-rental-booking-system'); ?><br/>
                    <?php esc_html_e('For some of them, set up extra data could be needed.','car-rental-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('Server:','car-rental-booking-system'); ?></span>
                    <div>
                        <select name="<?php CRBSHelper::getFormName('geolocation_server_id'); ?>" id="<?php CRBSHelper::getFormName('geolocation_server_id'); ?>">
<?php
						echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['option']['geolocation_server_id'],-1,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
						foreach($this->data['dictionary']['geolocation_server'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['option']['geolocation_server_id'],$index,false)).'>'.esc_html($value['name']).'</option>';
?>
                        </select>
                    </div>
				</div>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('API key for ipstack server:','car-rental-booking-system'); ?></span>
                    <div>
                        <input type="text" name="<?php CRBSHelper::getFormName('geolocation_server_id_3_api_key'); ?>" id="<?php CRBSHelper::getFormName('geolocation_server_id_3_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['geolocation_server_id_3_api_key']); ?>"/>
                    </div>
                </div>
            </li> 
            <li>
                <h5><?php esc_html_e('WooCommerce new order attachment','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Select file which will be added to the new order e-mail message sent by wooCommerce.','car-rental-booking-system'); ?></span>
                <div class="to-clear-fix">
                    <input type="hidden" name="<?php CRBSHelper::getFormName('attachment_woocommerce_email'); ?>" id="<?php CRBSHelper::getFormName('attachment_woocommerce_email'); ?>" value="<?php echo esc_attr($this->data['option']['attachment_woocommerce_email']); ?>"/>
                    <input type="button" name="<?php CRBSHelper::getFormName('attachment_woocommerce_email_browse'); ?>" id="<?php CRBSHelper::getFormName('attachment_woocommerce_email_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','car-rental-booking-system'); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Booking report','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enable or disable sending (via e-mail) message with complete list of vehicles which will be received and returned for current day.','car-rental-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('Status:','car-rental-booking-system'); ?></span>
                    <div class="to-clear-fix">
                        <div class="to-radio-button">
                            <input type="radio" value="1" id="<?php CRBSHelper::getFormName('email_report_status_1'); ?>" name="<?php CRBSHelper::getFormName('email_report_status'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['email_report_status'],1); ?>/>
                            <label for="<?php CRBSHelper::getFormName('email_report_status_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                            <input type="radio" value="0" id="<?php CRBSHelper::getFormName('email_report_status_0'); ?>" name="<?php CRBSHelper::getFormName('email_report_status'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['email_report_status'],0); ?>/>
                            <label for="<?php CRBSHelper::getFormName('email_report_status_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('Sender account:','car-rental-booking-system'); ?></span>
                    <div class="to-clear-fix">
                        <select name="<?php CRBSHelper::getFormName('email_report_sender_email_account_id'); ?>" id="<?php CRBSHelper::getFormName('email_report_sender_email_account_id'); ?>">
<?php
                            echo '<option value="-1" '.(CRBSHelper::selectedIf($this->data['option']['email_report_sender_email_account_id'],-1,false)).'>'.esc_html__('- Not set -','car-rental-booking-system').'</option>';
                            foreach($this->data['dictionary']['email_account'] as $index=>$value)
                                echo '<option value="'.esc_attr($index).'" '.(CRBSHelper::selectedIf($this->data['option']['email_report_sender_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
                        </select> 
                    </div>
                </div>
                <div class="to-clear-fix">
                    <span class="to-legend-field"><?php esc_html_e('List of recipients e-mail addresses separated by semicolon:','car-rental-booking-system'); ?></span>
                    <div class="to-clear-fix">
                        <input type="text" name="<?php CRBSHelper::getFormName('email_report_recipient_email_address'); ?>" id="<?php CRBSHelper::getFormName('email_report_recipient_email_address'); ?>" value="<?php echo esc_attr($this->data['option']['email_report_recipient_email_address']); ?>"/>
                    </div>                        
                </div>
            </li>
            <li>
                <h5><?php esc_html_e('Fixer.io API key','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php echo sprintf(__('Enter API key generated by <a href="%s" target="_blank">Fixer.io</a>.','car-rental-booking-system'),'https://fixer.io/'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CRBSHelper::getFormName('fixer_io_api_key'); ?>" id="<?php CRBSHelper::getFormName('fixer_io_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['fixer_io_api_key']); ?>"/>
                </div>
            </li>  
        </ul>