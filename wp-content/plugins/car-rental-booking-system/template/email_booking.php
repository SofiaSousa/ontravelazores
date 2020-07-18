<?php
        $Date=new CRBSDate();
        $Validation=new CRBSValidation();
        $BookingFormElement=new CRBSBookingFormElement();
        
        if((int)$this->data['document_header_exclude']!==1)
        {
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">

			<head>
<?php
            if(is_rtl())
            {
?>
				<style>
                    body
                    {
                        direction:rtl;
                    }
				</style>
<?php		
            }
?>
			</head>

			<body>
<?php
        }
?>
				<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#EEEEEE"<?php echo $this->data['style']['base']; ?>>
					
					<tr height="50px"><td></td></tr>
					
					<tr>
						
						<td>
							
                            <table cellspacing="0" cellpadding="0" width="600px" border="0" align="center" bgcolor="#FFFFFF" style="border:solid 1px #E1E8ED;padding:50px">
							
                                <!-- -->
<?php
        if((int)$this->data['document_header_exclude']!==1)
        {
            $logo=CRBSOption::getOption('logo');
            if($Validation->isNotEmpty($logo))
            {
?>
                                <tr>
                                    <td>
                                        <img style="max-width:100%;height:auto;" src="<?php echo esc_attr($logo); ?>" alt=""/>
                                        <br/><br/>
                                    </td>
                                </tr>                           
<?php
            }
        }
?>
                                <!-- -->
                                
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('General','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Title','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo $this->data['booking']['booking_title']; ?></td>
                                            </tr>                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Status','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['booking_status_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Pickup location','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['pickup_location_name']); ?></td>
                                            </tr>	
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Pickup date and time','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($this->data['booking']['meta']['pickup_date']).' '.$Date->formatTimeToDisplay($this->data['booking']['meta']['pickup_time'])); ?></td>
                                            </tr>	
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Return location','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['return_location_name']); ?></td>
                                            </tr>	   
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Return date and time','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($this->data['booking']['meta']['return_date']).' '.$Date->formatTimeToDisplay($this->data['booking']['meta']['return_time'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Rental period','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html(CRBSBookingHelper::calculateRentalDayCount($this->data['booking']['meta']['pickup_date'],$this->data['booking']['meta']['pickup_time'],$this->data['booking']['meta']['return_date'],$this->data['booking']['meta']['return_time'],$this->data['booking']['meta'],$this->data['booking']['meta']['billing_type'])).esc_html__(' days','car-rental-booking-system'); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Order total amount','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html(CRBSPrice::format($this->data['booking']['billing']['summary']['value_gross'],$this->data['booking']['meta']['currency_id'])); ?></td>
                                            </tr>	

<?php
        if($Validation->isNotEmpty($this->data['booking']['meta']['comment']))
        {
?>                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Comment','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['comment']); ?></td>
                                            </tr>   
<?php
        }
?>
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Vehicle','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Vehicle name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['vehicle_name']); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->
                                            
<?php
        if(count($this->data['booking']['meta']['booking_extra']))
        {
?>                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Add-ons','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>                                            
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td>
                                                    <ol <?php echo $this->data['style']['list'][1]; ?>>
<?php
            foreach($this->data['booking']['meta']['booking_extra'] as $index=>$value)
            {
?>
                                                        <li>
                                                            <?php echo esc_html($value['quantity']); ?>
                                                            <?php esc_html_e('x','car-rental-booking-system'); ?>
                                                            <?php echo esc_html($value['name']); ?> -
                                                            <?php echo CRBSPrice::format(CRBSPrice::calculateGross($value['price'],0,$value['tax_rate_value'])*$value['quantity'],$this->data['booking']['meta']['currency_id']); ?>
                                                        </li> 
<?php
            }
?>
                                                    </ol>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
<?php
        }
?>
                                            
                                <!-- -->
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Client details','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('First name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_first_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Last name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_last_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('E-mail address','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_email_address']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Phone number','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_phone_number']); ?></td>
                                            </tr>
<?php
        echo $BookingFormElement->displayField(1,$this->data['booking']['meta'],2,array('style'=>$this->data['style']));
?>
                                        </table>
                                    </td>
                                </tr> 
                                            
                                <!-- -->
                                            
<?php
        if((int)$this->data['booking']['meta']['client_billing_detail_enable']===1)
        {
?>                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Billing address','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Company name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_company_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Tax number','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_tax_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Street name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_street_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Street number','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_street_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('City','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_city']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('State','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_state']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Postal code','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_postal_code']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Country','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['client_billing_detail_country_name']); ?></td>
                                            </tr>
<?php
            echo $BookingFormElement->displayField(2,$this->data['booking']['meta'],2,array('style'=>$this->data['style']));
?>
                                        </table>
                                    </td>
                                </tr>  
<?php
        }
        
        $panel=$BookingFormElement->getPanel($this->data['booking']['meta']);
        
        foreach($panel as $panelIndex=>$panelValue)
        {
            if(in_array($panelValue['id'],array(1,2))) continue;
?>
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php echo esc_html($panelValue['label']); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">   
<?php
            echo $BookingFormElement->displayField($panelValue['id'],$this->data['booking']['meta'],2,array('style'=>$this->data['style']));
?>                                            
                                        </table>
                                    </td>
                                </tr>
<?php
        }
?>
                                            
                                <!-- -->
                                
<?php
        if($Validation->isNotEmpty($this->data['booking']['meta']['payment_name']))
        {
?>
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Payment','car-rental-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Payment','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['payment_name']); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>  
<?php    
        }
?>
							</table>

						</td>

					</tr>
					
					<tr height="50px"><td></td></tr>
		
				</table> 
<?php
        if((int)$this->data['document_header_exclude']!==1)
        {
?>				
			</body>

		</html>
<?php
        }
?>