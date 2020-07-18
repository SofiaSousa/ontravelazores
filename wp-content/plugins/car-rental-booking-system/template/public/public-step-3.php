<?php
        $BookingFormElement=new CRBSBookingFormElement();
?>
        <div class="crbs-layout-25x75 crbs-clear-fix">

            <div class="crbs-layout-column-left"></div>

            <div class="crbs-layout-column-right">

                <div class="crbs-notice crbs-hidden"></div>
                
                <div class="crbs-client-form"></div>

                <div id="crbs-payment"></div>
<?php        
        echo $BookingFormElement->createAgreement($this->data['meta']);
?>
            </div>   
            
        </div>

        <div class="crbs-clear-fix crbs-main-content-navigation-button">
            <a href="#" class="crbs-button crbs-button-style-2 crbs-button-step-prev">
                <span class="crbs-meta-icon-arrow-horizontal"></span>
                <?php echo esc_html($this->data['step']['dictionary'][3]['button']['prev']); ?>
            </a> 
            <a href="#" class="crbs-button crbs-button-style-1 crbs-button-step-next">
                <?php echo esc_html($this->data['step']['dictionary'][3]['button']['next']); ?>
                <span class="crbs-meta-icon-arrow-horizontal"></span>
            </a> 
        </div>