<?php
        $Currency=new CHBSCurrency();
        $BookingForm=new CHBSBookingForm();
        $ServiceType=new CHBSServiceType();
        
        $bookingForm=$BookingForm->getDictionary();
        $serviceType=$ServiceType->getServiceType();

        if(count($bookingForm))
        {
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_id']['id']); ?>"><?php esc_html_e('Booking form','chauffeur-booking-system'); ?>:</label>
            <select class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_id']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_id']['name']); ?>">
<?php
        foreach($bookingForm as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.($index==$this->data['option']['booking_form_id']['value'] ? 'selected=""' : null).'>'.esc_html($value['post']->post_title).'</option>';
?>
            </select>
		</p>
<?php
        }
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_url']['id']); ?>"><?php esc_html_e('Form action URL address','chauffeur-booking-system'); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_url']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_url']['name']); ?>" type="text" value="<?php echo esc_attr($this->data['option']['booking_form_url']['value']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_url']['id']); ?>"><?php esc_html_e('Open booking form in new window','chauffeur-booking-system'); ?>:</label>
            <select class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_new_window']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_new_window']['name']); ?>">
                <option value="1" <?php echo ($this->data['option']['booking_form_new_window']['value']==1 ? 'selected=""' : null); ?>><?php esc_html_e('Yes','chauffeur-booking-system'); ?></option>
                <option value="0" <?php echo ($this->data['option']['booking_form_new_window']['value']==0 ? 'selected=""' : null); ?>><?php esc_html_e('No','chauffeur-booking-system'); ?></option>
            </select>
		</p>
<?php
        if(count($Currency->getCurrency()))
        {
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_currency']['id']); ?>"><?php esc_html_e('Currency','chauffeur-booking-system'); ?>:</label>
            <select class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_currency']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_currency']['name']); ?>">
<?php
        echo '<option value="" '.(''==$this->data['option']['booking_form_currency']['value'] ? 'selected=""' : null).'>'.esc_html__('- Not selected - ','chauffeur-booking-syste').'</option>';
        foreach($Currency->getCurrency() as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.($index==$this->data['option']['booking_form_currency']['value'] ? 'selected=""' : null).'>'.esc_html($value['name']).'</option>';
?>
            </select>
		</p>
<?php
        }