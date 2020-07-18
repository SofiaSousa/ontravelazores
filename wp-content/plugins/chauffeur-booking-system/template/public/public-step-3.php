<?php
        $Validation=new CHBSValidation();
        $BookingFormElement=new CHBSBookingFormElement();
?>
        <div class="chbs-layout-25x75 chbs-clear-fix">

            <div class="chbs-layout-column-left"></div>

            <div class="chbs-layout-column-right">

                <div class="chbs-notice chbs-hidden"></div>

                <div class="chbs-client-form"></div>
<?php
        if((int)$this->data['meta']['payment_woocommerce_step_3_enable']===0)
            unset($this->data['dictionary']['payment_woocommerce']);
            
        if(((array_key_exists('payment_woocommerce',$this->data['dictionary'])) && (count($this->data['dictionary']['payment_woocommerce']))) || ((array_key_exists('payment',$this->data['dictionary'])) && (count($this->data['dictionary']['payment']))))
        {
            if((int)$this->data['meta']['price_hide']===0)
            {
?>                
                <h4 class="chbs-payment-header">
                    <?php esc_html_e('Choose payment method','chauffeur-booking-system'); ?>
                </h4>
<?php
                if(array_key_exists('payment_woocommerce',$this->data['dictionary']))
                {
?>
                <ul class="chbs-payment chbs-payment-woocommerce chbs-list-reset">
<?php
                    foreach($this->data['dictionary']['payment_woocommerce'] as $index=>$value)
                    {
?>
                    <li>
                        <a href="#" class="chbs-payment-type-woocommerce-<?php echo esc_attr($value->{'id'}); ?>" data-payment-id="<?php echo esc_attr($value->{'id'}); ?>">               
                            <span class="chbs-payment-name"><?php echo esc_html($value->{'title'}); ?></span>
                            <span class="chbs-meta-icon-tick"></span>
                        </a>
                    </li>                       
<?php
                    }
?>
                </ul>
<?php
                }
                else
                {
?>
                <ul class="chbs-payment chbs-list-reset">
<?php
                    foreach($this->data['dictionary']['payment'] as $index=>$value)
                    {
                        $style=array();
                        $class=array('chbs-payment-type-'.$index);
                        
                        if($Validation->isNotEmpty($this->data['meta']['payment_'.$value[1].'_logo_src']))
                        {
                            $class[]='chbs-payment-background-image';
                            $style['background-image']='url(\''.$this->data['meta']['payment_'.$value[1].'_logo_src'].'\')';
                        }
?>
                    <li>
                        <a href="#" data-payment-id="<?php echo esc_attr($index); ?>" <?php echo CHBSHelper::createCSSClassAttribute($class).' '.CHBSHelper::createStyleAttribute($style);  ?>>               
<?php
                        if($index==1)
                        {
?>
                            <span class="chbs-meta-icon-wallet"></span>
                            <span class="chbs-payment-name"><?php esc_html_e('Cash Payment','chauffeur-booking-system'); ?></span>
<?php
                        }
                        else if($index==4)
                        {
?>                            
                            <span class="chbs-meta-icon-bank"></span>
                            <span class="chbs-payment-name"><?php esc_html_e('Wire Transfer','chauffeur-booking-system'); ?></span>
<?php                          
                        }
?>
                            <span class="chbs-meta-icon-tick"></span>
                        </a>
                    </li>                       
<?php
                    }
?>
               </ul>
<?php
                }
            }
        }
?>
            </div>   
            
        </div>

        <div class="chbs-clear-fix chbs-main-content-navigation-button">
            <a href="#" class="chbs-button chbs-button-style-2 chbs-button-step-prev">
                <span class="chbs-meta-icon-arrow-horizontal-large"></span>
                <?php echo esc_html($this->data['step']['dictionary'][3]['button']['prev']); ?>
            </a> 
            <a href="#" class="chbs-button chbs-button-style-1 chbs-button-step-next">
                <?php echo esc_html($this->data['step']['dictionary'][3]['button']['next']); ?>
                <span class="chbs-meta-icon-arrow-horizontal-large"></span>
            </a> 
        </div>