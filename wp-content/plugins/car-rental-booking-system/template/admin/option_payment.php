        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Booking status after successful payment','car-rental-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Set selected status of the booking after the successful payment.','car-rental-booking-system'); ?></span>
				<div class="to-radio-button">
					<input type="radio" value="-1" id="<?php CRBSHelper::getFormName('booking_status_payment_success_0'); ?>" name="<?php CRBSHelper::getFormName('booking_status_payment_success'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['booking_status_payment_success'],-1); ?>/>
					<label for="<?php CRBSHelper::getFormName('booking_status_payment_success_0'); ?>"><?php esc_html_e('[No changes]','car-rental-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
                    <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('booking_status_payment_success_'.$index); ?>" name="<?php CRBSHelper::getFormName('booking_status_payment_success'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['booking_status_payment_success'],$index); ?>/>
                    <label for="<?php CRBSHelper::getFormName('booking_status_payment_success_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
				</div>
			</li> 
            <li>
                <h5><?php esc_html_e('Booking statuses synchronization','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Synchronize booking statuses between plugin and wooCommerce.','car-rental-booking-system'); ?>
                </span>
				<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status_synchronization'] as $index=>$value)
		{
?>
                    <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CRBSHelper::getFormName('booking_status_synchronization_'.$index); ?>" name="<?php CRBSHelper::getFormName('booking_status_synchronization'); ?>" <?php CRBSHelper::checkedIf($this->data['option']['booking_status_synchronization'],$index); ?>/>
                    <label for="<?php CRBSHelper::getFormName('booking_status_synchronization_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
        }
?>
				</div>
            </li>  
		</ul>

