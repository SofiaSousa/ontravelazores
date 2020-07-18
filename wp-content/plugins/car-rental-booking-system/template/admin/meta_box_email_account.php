<?php 
		echo $this->data['nonce']; 
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-email-account-1"><?php esc_html_e('Sender','car-rental-booking-system'); ?></a></li>
                    <li><a href="#meta-box-email-account-2"><?php esc_html_e('SMTP Authentication','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-email-account-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Name','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Name.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('sender_name'); ?>" id="<?php CRBSHelper::getFormName('sender_name'); ?>" value="<?php echo esc_attr($this->data['meta']['sender_name']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('E-mail address','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('E-mail address.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('sender_email_address'); ?>" id="<?php CRBSHelper::getFormName('sender_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['sender_email_address']); ?>"/>
                            </div>
                        </li>
                    </ul>
                </div>       
                <div id="meta-box-email-account-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('SMTP Auth','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enable or disable SMTP Auth.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('smtp_auth_enable_1'); ?>" name="<?php CRBSHelper::getFormName('smtp_auth_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['smtp_auth_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('smtp_auth_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('smtp_auth_enable_0'); ?>" name="<?php CRBSHelper::getFormName('smtp_auth_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['smtp_auth_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('smtp_auth_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>							
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Username','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Username.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('smtp_auth_username'); ?>" id="<?php CRBSHelper::getFormName('smtp_auth_username'); ?>" value="<?php echo esc_attr($this->data['meta']['smtp_auth_username']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Password','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Password.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="password" name="<?php CRBSHelper::getFormName('smtp_auth_password'); ?>" id="<?php CRBSHelper::getFormName('smtp_auth_password'); ?>" value="<?php echo esc_attr($this->data['meta']['smtp_auth_password']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Host','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Host.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('smtp_auth_host'); ?>" id="<?php CRBSHelper::getFormName('smtp_auth_host'); ?>" value="<?php echo esc_attr($this->data['meta']['smtp_auth_host']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Port','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Port.','car-rental-booking-system'); ?></span>
                            <div>
                                <input type="text" name="<?php CRBSHelper::getFormName('smtp_auth_port'); ?>" id="<?php CRBSHelper::getFormName('smtp_auth_port'); ?>" value="<?php echo esc_attr($this->data['meta']['smtp_auth_port']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Secure connection type','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Secure connection type.','car-rental-booking-system'); ?></span>
                            <div class="to-radio-button">
<?php
        foreach($this->data['dictionary']['secure_connection_type'] as $secureConnectionTypeIndex=>$secureConnectionTypeData)
        {
?>
                                <input type="radio" value="<?php echo esc_attr($secureConnectionTypeIndex); ?>" id="<?php CRBSHelper::getFormName('smtp_auth_secure_connection_type_'.$secureConnectionTypeIndex); ?>" name="<?php CRBSHelper::getFormName('smtp_auth_secure_connection_type'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['smtp_auth_secure_connection_type'],$secureConnectionTypeIndex); ?>/>
                                <label for="<?php CRBSHelper::getFormName('smtp_auth_secure_connection_type_'.$secureConnectionTypeIndex); ?>"><?php echo esc_html($secureConnectionTypeData[0]); ?></label>							
<?php		
        }
?>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Debug','car-rental-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enable or disable debugging.','car-rental-booking-system'); ?><br/>
                                <?php esc_html_e('You can check result of debugging in Chrome/Firebug console (after submit form).','car-rental-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CRBSHelper::getFormName('smtp_auth_debug_enable_1'); ?>" name="<?php CRBSHelper::getFormName('smtp_auth_debug_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['smtp_auth_debug_enable'],1); ?>/>
                                <label for="<?php CRBSHelper::getFormName('smtp_auth_debug_enable_1'); ?>"><?php esc_html_e('Enable','car-rental-booking-system'); ?></label>							
                                <input type="radio" value="0" id="<?php CRBSHelper::getFormName('smtp_auth_debug_enable_0'); ?>" name="<?php CRBSHelper::getFormName('smtp_auth_debug_enable'); ?>" <?php CRBSHelper::checkedIf($this->data['meta']['smtp_auth_debug_enable'],0); ?>/>
                                <label for="<?php CRBSHelper::getFormName('smtp_auth_debug_enable_0'); ?>"><?php esc_html_e('Disable','car-rental-booking-system'); ?></label>							
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('.to').themeOptionElement({init:true});
            });
		</script>