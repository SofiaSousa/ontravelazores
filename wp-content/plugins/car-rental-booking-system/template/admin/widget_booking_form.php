<?php
		$Currency=new CRBSCurrency();
        $BookingForm=new CRBSBookingForm();
        
        $bookingForm=$BookingForm->getDictionary();
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_style_id']['id']); ?>"><?php esc_html_e('Style','car-rental-booking-system'); ?>:</label>
            <select class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_style_id']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_style_id']['name']); ?>">
                <option value="1" <?php echo ($this->data['option']['booking_form_style_id']['value']==1 ? 'selected=""' : null); ?>><?php esc_html_e('Style 1','car-rental-booking-system'); ?></option>
                <option value="2" <?php echo ($this->data['option']['booking_form_style_id']['value']==2 ? 'selected=""' : null); ?>><?php esc_html_e('Style 2','car-rental-booking-system'); ?></option>
            </select>
		</p>
<?php
        if(count($bookingForm))
        {
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_id']['id']); ?>"><?php esc_html_e('Booking form','car-rental-booking-system'); ?>:</label>
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
			<label for="<?php echo esc_attr($this->data['option']['booking_form_url']['id']); ?>"><?php esc_html_e('Form action URL address','car-rental-booking-system'); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_url']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_url']['name']); ?>" type="text" value="<?php echo esc_attr($this->data['option']['booking_form_url']['value']); ?>" />
		</p>
<?php
        if(count($Currency->getCurrency()))
        {
?>
		<p>
			<label for="<?php echo esc_attr($this->data['option']['booking_form_currency']['id']); ?>"><?php esc_html_e('Currency','car-rental-booking-system'); ?>:</label>
            <select class="widefat" id="<?php echo esc_attr($this->data['option']['booking_form_currency']['id']); ?>" name="<?php echo esc_attr($this->data['option']['booking_form_currency']['name']); ?>">
<?php
        echo '<option value="" '.(''==$this->data['option']['booking_form_currency']['value'] ? 'selected=""' : null).'>'.esc_html__('- Not selected - ','car-rental-booking-syste').'</option>';
        foreach($Currency->getCurrency() as $index=>$value)
            echo '<option value="'.esc_attr($index).'" '.($index==$this->data['option']['booking_form_currency']['value'] ? 'selected=""' : null).'>'.esc_html($value['name']).'</option>';
?>
            </select>
		</p>
<?php
        }