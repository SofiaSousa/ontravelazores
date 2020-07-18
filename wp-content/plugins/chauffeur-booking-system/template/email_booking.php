<?php
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $Validation=new CHBSValidation();
        $BookingFormElement=new CHBSBookingFormElement();
        
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
                <style type="text/css">
                    body { direction:rtl; }
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
					
					<tr height="50px"><td <?php echo $this->data['style']['cell'][3]; ?>></td></tr>
					
					<tr>
						
						<td <?php echo $this->data['style']['cell'][3]; ?>>
							
                            <table cellspacing="0" cellpadding="0" width="600px" border="0" align="center" bgcolor="#FFFFFF" style="border:solid 1px #E1E8ED;padding:50px">
							
                                <!-- -->
<?php
        if((int)$this->data['document_header_exclude']!==1)
        {
            $logo=CHBSOption::getOption('logo');
            if($Validation->isNotEmpty('logo'))
            {
?>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
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
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('General','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Title','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo $this->data['booking']['booking_title']; ?></td>
                                            </tr>
<?php
        if(array_key_exists('booking_form_name',$this->data['booking']))
        {
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Booking form name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo $this->data['booking']['booking_form_name']; ?></td>
                                            </tr>     
<?php          
        }
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Status','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['booking_status_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Service type','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['service_type_name']); ?></td>
                                            </tr>	
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Transfer type','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['transfer_type_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Pickup date and time','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($this->data['booking']['meta']['pickup_date']).' '.$Date->formatTimeToDisplay($this->data['booking']['meta']['pickup_time'])); ?></td>
                                            </tr>	
<?php
        if(in_array($this->data['booking']['meta']['service_type_id'],array(1,3)))
        {
            if((int)$this->data['booking']['meta']['transfer_type_id']===3)
            {
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Return date and time','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($this->data['booking']['meta']['return_date']).' '.$Date->formatTimeToDisplay($this->data['booking']['meta']['return_time'])); ?></td>
                                            </tr>                                            
<?php
            }
        }
        
        if((int)$this->data['booking']['meta']['price_hide']===0)
        {
        
?>                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Order total amount','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html(CHBSPrice::format($this->data['booking']['billing']['summary']['value_gross'],$this->data['booking']['meta']['currency_id'])); ?></td>
                                            </tr>	
<?php
            if($this->data['booking']['meta']['payment_deposit_enable']==1)
            {
?>                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php echo sprintf(esc_html('To pay (deposit %s%%)','chauffeur-booking-system'),$this->data['booking']['meta']['payment_deposit_value']); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html(CHBSPrice::format($this->data['booking']['billing']['summary']['pay'],$this->data['booking']['meta']['currency_id'])); ?></td>
                                            </tr>	                                            
<?php          
            }

            if(in_array($this->data['booking']['meta']['service_type_id'],array(1,3)))
            {
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Distance','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>>
                                                    <?php echo $this->data['booking']['billing']['summary']['distance']; ?>
                                                    <?php echo $Length->getUnitShortName($this->data['booking']['meta']['length_unit']); ?>
                                                </td>
                                            </tr>
<?php
            }
        }
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Duration','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>>
                                                    <?php echo esc_html($this->data['booking']['billing']['summary']['duration']);  ?>
                                                </td>
                                            </tr>	
<?php
        if($this->data['booking']['meta']['passenger_enable']==1)
        {
?> 
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Passengers','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html(CHBSBookingHelper::getPassengerLabel($this->data['booking']['meta']['passenger_adult_number'],$this->data['booking']['meta']['passenger_children_number'])); ?></td>
                                            </tr>        
<?php          
        }
        if($Validation->isNotEmpty($this->data['booking']['meta']['comment']))
        {
?>                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Comment','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['comment']); ?></td>
                                            </tr>   
<?php
        }
?>
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Route','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
<?php
        if($this->data['booking']['meta']['service_type_id']==3)
        {
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Route name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['route_name']); ?></td>
                                            </tr>
<?php
        }

        if(in_array($this->data['booking']['meta']['service_type_id'],array(1,3)))
        {
            if($this->data['booking']['meta']['extra_time_enable']==1)
            {
?>
                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Extra time','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatMinuteToTime($this->data['booking']['meta']['extra_time_value'])); ?></td>
                                            </tr>                                            
<?php
            }
        }
?>
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->            
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Route locations','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][3]; ?>>
                                                    <ol <?php echo $this->data['style']['list'][1]; ?>>
<?php
        foreach($this->data['booking']['meta']['coordinate'] as $index=>$value)
        {
            $address=esc_html($value['address']);
            if(array_key_exists('formatted_address',$value))
                $address='<b>'.esc_html($value['formatted_address']).'</b> '.$address;           
?>
                                                        <li <?php echo $this->data['style']['list'][2]; ?>><a href="https://www.google.com/maps/?q=<?php echo esc_attr($value['lat']).','.esc_attr($value['lng']); ?>" target="_blank"><?php echo $address; ?></a></li>
<?php
        }
?>
                                                    </ol>
                                                </td>
                                            </tr>   
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Vehicle','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Vehicle name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['vehicle_name']); ?></td>
                                            </tr>
<?php
		if(array_key_exists('vehicle_bag_count',$this->data['booking']))
		{
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Bag count','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['vehicle_bag_count']); ?></td>
                                            </tr>
<?php
		}
		if(array_key_exists('vehicle_passenger_count',$this->data['booking']))
		{		
?>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Passengers count','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['vehicle_passenger_count']); ?></td>
                                            </tr>
<?php
		}
?>
                                        </table>
                                    </td>
                                </tr>
                                            
                                <!-- -->
                                            
<?php
        if(count($this->data['booking']['meta']['booking_extra']))
        {
?>                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Extra','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>                                            
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][3]; ?>>
                                                    <ol <?php echo $this->data['style']['list'][1]; ?>>
<?php
            foreach($this->data['booking']['meta']['booking_extra'] as $index=>$value)
            {
?>
                                                        <li <?php echo $this->data['style']['list'][2]; ?>>
                                                            <?php echo esc_html($value['quantity']); ?>
                                                            <?php esc_html_e('x','chauffeur-booking-system'); ?>
                                                            <?php echo esc_html($value['name']); ?>
<?php
                if((int)$this->data['booking']['meta']['price_hide']===0)
                {
                                                            echo ' - '.CHBSPrice::format(CHBSPrice::calculateGross($value['price'],0,$value['tax_rate_value'])*$value['quantity'],$this->data['booking']['meta']['currency_id']);
                }
?>
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
                                            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Client details','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('First name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_first_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Last name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_last_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('E-mail address','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_contact_detail_email_address']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Phone number','chauffeur-booking-system'); ?></td>
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
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Billing address','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">                                            
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Company name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_company_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Tax number','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_tax_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Street name','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_street_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Street number','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_street_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('City','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_city']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('State','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_state']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Postal code','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['client_billing_detail_postal_code']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Country','chauffeur-booking-system'); ?></td>
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
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php echo esc_html($panelValue['label']); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">   
<?php
            echo $BookingFormElement->displayField($panelValue['id'],$this->data['booking']['meta'],2,array('style'=>$this->data['style']));
?>                                            
                                        </table>
                                    </td>
                                </tr>
<?php
        }
        
        if((array_key_exists('form_element_agreement',$this->data['booking']['meta'])) && (is_array($this->data['booking']['meta']['form_element_agreement'])) && (count($this->data['booking']['meta']['form_element_agreement'])))
        {
?>            
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Agreements','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>   
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][3]; ?>>
                                                    <ol <?php echo $this->data['style']['list'][1]; ?>>
<?php
            foreach($this->data['booking']['meta']['form_element_agreement'] as $index=>$value)
            {
?>
                                                        <li <?php echo $this->data['style']['list'][2]; ?>>
                                                            <?php echo ((int)$value['value']===1 ? __('[YES]','chauffeur-booking-system') : __('[NO]','chauffeur-booking-system')).' '.$value['text']; ?>
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
                                
<?php
        if(!empty($this->data['booking']['meta']['payment_id']))
        {
?>
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Payment','chauffeur-booking-system'); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
                                <tr>
                                    <td <?php echo $this->data['style']['cell'][3]; ?>>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Payment','chauffeur-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>>
<?php 
            echo esc_html($this->data['booking']['payment_name']); 
            if($Validation->isNotEmpty($this->data['booking']['woocommerce_payment_url']))
                echo '<br><a href="'.esc_url($this->data['booking']['woocommerce_payment_url']).'" target="_blank">'.__('Click to pay for this order','chauffeur-booking-system').'</a>';
?>
                                                </td>
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
					
					<tr height="50px"><td <?php echo $this->data['style']['cell'][3]; ?>></td></tr>
		
				</table> 
<?php
        if((int)$this->data['document_header_exclude']!==1)
        {
?>
			</body>

		</html>
<?php
        }
        else echo '<br style="height:40px;">';