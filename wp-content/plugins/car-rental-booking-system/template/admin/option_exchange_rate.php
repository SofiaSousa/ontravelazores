
        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Update','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select exchange rate provider.','car-rental-booking-system'); ?><br/>
					<?php esc_html_e('For the "Fixer.io" provider, you need to enter additional details in "General" tab.','car-rental-booking-system'); ?><br/>
					<?php esc_html_e('All rates will be replaced during process of importing.','car-rental-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
					<select name="<?php CRBSHelper::getFormName('exchange_rate_provider'); ?>" id="<?php CRBSHelper::getFormName('exchange_rate_provider'); ?>">
<?php
						foreach($this->data['dictionary']['exchange_rate_provider'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'">'.$value[0].'</option>';
?>
					</select>
				</div>
            </li> 
            <li>
                <input type="button" name="<?php CRBSHelper::getFormName('import_exchange_rate'); ?>" id="<?php CRBSHelper::getFormName('import_exchange_rate'); ?>" class="to-button to-margin-0" value="<?php esc_attr_e('Import exchange rates','car-rental-booking-system'); ?>"/>
            </li>            
            <li>
                <h5><?php esc_html_e('Rates table','car-rental-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Enter an exchange rate for selected currencies in relation to base currency.','car-rental-booking-system'); ?>
                </span>
                <div>
                    <table class="to-table">
                        <tr>
                            <th style="width:50%">
                                <div>
                                    <?php esc_html_e('Currency','car-rental-booking-system'); ?>
                                    <span class="to-legend">
                                        <?php esc_html_e('Currency.','car-rental-booking-system'); ?>
                                    </span>
                                </div>
                            </th>
                            <th style="width:50%">
                                <div>
                                    <?php esc_html_e('Exchange rate','car-rental-booking-system'); ?>
                                    <span class="to-legend">
                                        <?php esc_html_e('Exchange rate.','car-rental-booking-system'); ?>
                                    </span>
                                </div>
                            </th>
                        </tr> 
<?php
        foreach($this->data['dictionary']['currency'] as $index=>$value)
        {
?>
                        <tr>
                            <td>
                                <div>
                                    <?php echo esc_html__($value['name']).' <b>('.esc_html($value['symbol']).')</b>'; ?>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="text" value="<?php echo esc_attr(array_key_exists($index,(array)$this->data['option']['currency_exchange_rate']) ? $this->data['option']['currency_exchange_rate'][$index] : ''); ?>" name="<?php CRBSHelper::getFormName('currency_exchange_rate['.$index.']'); ?>"/>
                                </div>
                            </td>
                        </tr>
<?php          
        }
?>
                    </table>
                </div>
            </li> 
        </ul>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('#<?php CRBSHelper::getFormName('import_exchange_rate'); ?>').bind('click',function(e) 
				{
					e.preventDefault();
					$('#action').val('<?php echo PLUGIN_CRBS_CONTEXT.'_option_page_import_exchange_rate'; ?>');
					$('#to_form').submit();
					$('#action').val('<?php echo PLUGIN_CRBS_CONTEXT.'_option_page_save'; ?>');
				});
            });
		</script>