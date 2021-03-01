        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Booking acceptance by drivers','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enable or disable option to accepting/rejecting booking by drivers.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('Once this option is enabled each booking has to be accepted by assigned driver.','chauffeur-booking-system'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <div class="to-radio-button">
                        <input type="radio" value="1" id="<?php CHBSHelper::getFormName('booking_driver_accept_enable_1'); ?>" name="<?php CHBSHelper::getFormName('booking_driver_accept_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_accept_enable'],1); ?>/>
                        <label for="<?php CHBSHelper::getFormName('booking_driver_accept_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                        <input type="radio" value="0" id="<?php CHBSHelper::getFormName('booking_driver_accept_enable_0'); ?>" name="<?php CHBSHelper::getFormName('booking_driver_accept_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_accept_enable'],0); ?>/>
                        <label for="<?php CHBSHelper::getFormName('booking_driver_accept_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                    </div>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Confirmation page for drivers','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enter page/post ID with booking confirmation form for drivers.','chauffeur-booking-system'); ?><br/>
                    <?php echo sprintf(esc_html__('Please note that this page has to contain shortcode %s.','chauffeur-booking-system'),'['.PLUGIN_CHBS_CONTEXT.'_booking_driver_confirmation]'); ?><br/>
                </span>
                <div class="to-clear-fix">
                    <input type="text" maxlength="6" name="<?php CHBSHelper::getFormName('booking_driver_confirmation_page'); ?>" id="<?php CHBSHelper::getFormName('booking_driver_confirmation_page'); ?>" value="<?php echo esc_attr($this->data['option']['booking_driver_confirmation_page']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Recipient e-mail addresses','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('List of recipient e-mail addresses separated by semicolon on which ones plugin sends notification about accepting/rejecting booking by driver.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('booking_driver_email_recipient'); ?>" id="<?php CHBSHelper::getFormName('booking_driver_email_recipient'); ?>" value="<?php echo esc_attr($this->data['option']['booking_driver_email_recipient']); ?>"/>
                </div>
            </li>              
            <li>
                <h5><?php esc_html_e('New status for accepted booking','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select new status for bookings which have been accepted by drivers.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-radio-button">
                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('booking_driver_status_after_accept_0'); ?>" name="<?php CHBSHelper::getFormName('booking_driver_status_after_accept'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_status_after_accept'],-1); ?>/>
                    <label for="<?php CHBSHelper::getFormName('booking_driver_status_after_accept_0'); ?>"><?php esc_html_e('- No changes - ','chauffeur-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                    <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_driver_status_after_accept_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_driver_status_after_accept'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_status_after_accept'],$index); ?>/>
                    <label for="<?php CHBSHelper::getFormName('booking_driver_status_after_accept_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
                </div>
            </li>    
            <li>
                <h5><?php esc_html_e('New status for rejected booking','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select new status for bookings which have been rejected by drivers.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-radio-button">
                    <input type="radio" value="0" id="<?php CHBSHelper::getFormName('booking_driver_status_after_reject_0'); ?>" name="<?php CHBSHelper::getFormName('booking_driver_status_after_reject'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_status_after_reject'],-1); ?>/>
                    <label for="<?php CHBSHelper::getFormName('booking_driver_status_after_reject_0'); ?>"><?php esc_html_e('- No changes - ','chauffeur-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                    <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_driver_status_after_reject_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_driver_status_after_reject'); ?>" <?php CHBSHelper::checkedIf($this->data['option']['booking_driver_status_after_reject'],$index); ?>/>
                    <label for="<?php CHBSHelper::getFormName('booking_driver_status_after_reject_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
                </div>
            </li>             
        </ul>