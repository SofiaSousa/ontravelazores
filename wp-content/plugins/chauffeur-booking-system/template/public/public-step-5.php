<?php
        $Validation=new CHBSValidation();
?>
        <div class="chbs-clear-fix chbs-booking-complete chbs-hidden">   
            <div class="chbs-meta-icon-tick">
                <div></div>
                <div></div>
            </div>
            <h3><?php esc_html_e('Thank you for your order','chauffeur-booking-system'); ?></h3>
            <p class="chbs-booking-complete-payment-cash">
                <a href="<?php echo ($Validation->isEmpty($this->data['meta']['thank_you_page_button_back_to_home_url_address']) ? the_permalink() : esc_url($this->data['meta']['thank_you_page_button_back_to_home_url_address'])); ?>" class="chbs-button chbs-button-style-1"><?php echo ($Validation->isEmpty($this->data['meta']['thank_you_page_button_back_to_home_label']) ? esc_html__('Back to home','chauffeur-booking-system') : $this->data['meta']['thank_you_page_button_back_to_home_label']); ?></a>
            </p>
            <p class="chbs-booking-complete-payment-paypal">
                <?php _e('You will be redirected to the payment page within <span>5</span> second.','chauffeur-booking-system'); ?>
            </p>
            <p class="chbs-booking-complete-payment-stripe">
                <a href="#" class="chbs-button chbs-button-style-1"><?php esc_html_e('Pay via Stripe','chauffeur-booking-system'); ?></a>
            </p>
            <p class="chbs-booking-complete-payment-wire-transfer">
                <?php echo nl2br($this->data['meta']['payment_wire_transfer_info']); ?>
            </p>
            <p class="chbs-booking-complete-payment-woocommerce">
                <a href="#" class="chbs-button chbs-button-style-1"><?php esc_html_e('Pay for order','chauffeur-booking-system'); ?></a>
            </p>
        </div>