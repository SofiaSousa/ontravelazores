<?php
        $Date=new CRBSDate();
        $Validation=new CRBSValidation();
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

				<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#EEEEEE"<?php echo $this->data['style']['base']; ?>>
					
					<tr height="50px"><td></td></tr>
					
					<tr>
						
						<td>
							
                            <table cellspacing="0" cellpadding="0" width="600px" border="0" align="center" bgcolor="#FFFFFF" style="border:solid 1px #E1E8ED;padding:50px">
							
                                <!-- -->
<?php
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
?>
                                <!-- -->
<?php
        $i=0;
        foreach($this->data['booking'] as $index=>$value)
        {
?>
                                <tr>
                                    <td <?php echo $this->data['style']['header']; ?>><?php esc_html_e($value['post']->post_title); ?></td>
                                </tr>
                                <tr><td <?php echo $this->data['style']['separator'][3]; ?>><td></tr>
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Pickup location','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['pickup_location_name']); ?></td>
                                            </tr>	
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Pickup date and time','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($value['meta']['pickup_date']).' '.$Date->formatTimeToDisplay($value['meta']['pickup_time'])); ?></td>
                                            </tr>	
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Return location','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['return_location_name']); ?></td>
                                            </tr>	   
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Return date and time','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($Date->formatDateToDisplay($value['meta']['return_date']).' '.$Date->formatTimeToDisplay($value['meta']['return_time'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Vehicle name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['vehicle_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('First name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['client_contact_detail_first_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Last name','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['client_contact_detail_last_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('E-mail address','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['client_contact_detail_email_address']); ?></td>
                                            </tr>
                                            <tr>
                                                <td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Phone number','car-rental-booking-system'); ?></td>
                                                <td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($value['meta']['client_contact_detail_phone_number']); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
<?php
            if((++$i)!==count($this->data['booking']))
            {
?>
                                <tr><td <?php echo $this->data['style']['separator'][2]; ?>><td></tr>
<?php
            }
        }
?>
							</table>

						</td>

					</tr>
					
					<tr height="50px"><td></td></tr>
		
				</table> 
		
			</body>

		</html>