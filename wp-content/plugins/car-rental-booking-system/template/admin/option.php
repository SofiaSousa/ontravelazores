
		<div class="to to-to" style="display:none">

			<form name="to_form" id="to_form" method="POST" action="#">

				<div id="to_notice"></div> 

				<div class="to-header to-clear-fix">

					<div class="to-header-left">

						<div>
							<h3><?php esc_html_e('QuanticaLabs','car-rental-booking-system'); ?></h3>
							<h6><?php esc_html_e('Plugin Options','car-rental-booking-system'); ?></h6>
						</div>

					</div>

					<div class="to-header-right">

						<div>
							<h3>
								<?php esc_html_e('Car Rental Booking System','car-rental-booking-system'); ?>
							</h3>
							<h6>
								<?php echo sprintf(esc_html__('WordPress Plugin ver. %s','car-rental-booking-system'),PLUGIN_CRBS_VERSION); ?>
							</h6>
							&nbsp;&nbsp;
							<a href="<?php echo esc_url('http://support.quanticalabs.com'); ?>" target="_blank"><?php esc_html_e('Support Forum','car-rental-booking-system'); ?></a>
							<a href="<?php echo esc_url('https://codecanyon.net/user/quanticalabs'); ?>" target="_blank"><?php esc_html_e('Plugin site','car-rental-booking-system'); ?></a>
						</div>

						<a href="<?php echo esc_url('http://quanticalabs.com'); ?>" class="to-header-right-logo"></a>

					</div>

				</div>

				<div class="to-content to-clear-fix">

					<div class="to-content-left">

						<ul class="to-menu" id="to_menu">
							<li>
								<a href="#general"><?php esc_html_e('General','car-rental-booking-system'); ?><span></span></a>
							</li>
                            <li>
								<a href="#import_demo"><?php esc_html_e('Import demo','car-rental-booking-system'); ?><span></span></a>
							</li>
                            <li>
								<a href="#payment"><?php esc_html_e('Payments','car-rental-booking-system'); ?><span></span></a>
							</li>
                            <li>
								<a href="#coupon_creator"><?php esc_html_e('Coupons creator','car-rental-booking-system'); ?><span></span></a>
							</li>
                            <li>
								<a href="#exchange_rate"><?php esc_html_e('Exchange rates','car-rental-booking-system'); ?><span></span></a>
							</li>
                            <li>
								<a href="#log_manager"><?php esc_html_e('Log manager','car-rental-booking-system'); ?><span></span></a>
                                <ul>
									<li><a href="#log_manager_mail"><?php esc_html_e('Mail','car-rental-booking-system'); ?></a></li>
                                    <li><a href="#log_manager_twilio"><?php esc_html_e('Twilio','car-rental-booking-system'); ?></a></li>
									<li><a href="#log_manager_nexmo"><?php esc_html_e('Nexmo/Vonage','car-rental-booking-system'); ?></a></li>
                                    <li><a href="#log_manager_telegram"><?php esc_html_e('Telegram','car-rental-booking-system'); ?></a></li>
                                    <li><a href="#log_manager_geolocation"><?php esc_html_e('Geolocation','car-rental-booking-system'); ?></a></li>
									<li><a href="#log_manager_google_calendar"><?php esc_html_e('Google Calendar','car-rental-booking-system'); ?></a></li>
								</ul>		
							</li>
						</ul>

					</div>

					<div class="to-content-right" id="to_panel">
<?php
		$content=array
        (
            'general',
            'import_demo',
			'payment',
            'coupon_creator',
            'exchange_rate',
            'log_manager_mail',
            'log_manager_nexmo',
            'log_manager_twilio',
            'log_manager_telegram',
            'log_manager_geolocation',
            'log_manager_google_calendar'
        );
        
		foreach($content as $value)
		{
?>
						<div id="<?php echo $value; ?>">
<?php
			$Template=new CRBSTemplate($this->data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/option_'.$value.'.php');
			echo $Template->output(false);
?>
						</div>
<?php
		}
?>
					</div>

				</div>

				<div class="to-footer to-clear-fix">

					<div class="to-footer-left">

						<ul class="to-social-list">
							<li><a href="<?php echo esc_url('http://themeforest.net/user/QuanticaLabs?ref=quanticalabs'); ?>" class="to-social-list-envato" title="<?php esc_attr_e('Envato','car-rental-booking-system'); ?>"></a></li>
							<li><a href="<?php echo esc_url('http://www.facebook.com/QuanticaLabs'); ?>" class="to-social-list-facebook" title="<?php esc_attr_e('Facebook','car-rental-booking-system'); ?>"></a></li>
							<li><a href="<?php echo esc_url('https://twitter.com/quanticalabs'); ?>" class="to-social-list-twitter" title="<?php esc_attr_e('Twitter','car-rental-booking-system'); ?>"></a></li>
							<li><a href="<?php echo esc_url('http://quanticalabs.tumblr.com/'); ?>" class="to-social-list-tumblr" title="<?php esc_attr_e('Tumblr','car-rental-booking-system'); ?>"></a></li>
						</ul>

					</div>
					
					<div class="to-footer-right">
						<input type="submit" value="<?php esc_attr_e('Save changes','car-rental-booking-system'); ?>" name="Submit" id="Submit" class="to-button"/>
					</div>			
				
				</div>
				
				<input type="hidden" name="action" id="action" value="<?php echo esc_attr(PLUGIN_CRBS_CONTEXT.'_option_page_save'); ?>" />
				
				<script type="text/javascript">

					jQuery(document).ready(function($)
					{
						$('.to').themeOption({afterSubmit:function(response)
                        {
                            if(typeof(response.global.reload)!='undefined')
                                location.reload();
                            
                            return(false);
                        }});
                        
						var element=$('.to').themeOptionElement({init:true});
                        
                        element.bindBrowseMedia('input[name="crbs_logo_browse"]');
                        element.bindBrowseMedia('input[name="crbs_attachment_woocommerce_email_browse"]',false,2,'');
					});

				</script>

			</form>
			
		</div>