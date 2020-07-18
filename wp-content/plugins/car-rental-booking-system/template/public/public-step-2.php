        
        <div class="crbs-layout-25x75 crbs-clear-fix">

            <div class="crbs-layout-column-left"></div>

            <div class="crbs-layout-column-right">
<?php
        if((int)$this->data['meta']['vehicle_filter_bar_enable']===1)
        {
?>
                <div class="crbs-vehicle-filter crbs-clear-fix">
                    
                    <div class="crbs-form-panel">
 
                        <label class="crbs-form-panel-label"><?php esc_html_e('Refine Your Search','car-rental-booking-system'); ?></label>

                        <div class="crbs-form-panel-content crbs-clear-fix">
                    
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label class="crbs-form-field-label"><?php esc_html_e('Number of Seats','car-rental-booking-system'); ?></label>
                                <select name="<?php CRBSHelper::getFormName('vehicle_passenger_count'); ?>">
<?php
            for($i=$this->data['vehicle_passenger_count_range']['min'];$i<=$this->data['vehicle_passenger_count_range']['max'];$i++)
            {
?>
                                    <option value="<?php echo esc_attr($i); ?>"<?php echo ($i==1 ? ' selected="selected"' : ''); ?>><?php echo esc_html($i); ?></option>
<?php
            }
?>
                                </select>
                            </div>

                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label><?php esc_html_e('Number of Bags','car-rental-booking-system'); ?></label>
                                <select name="<?php CRBSHelper::getFormName('vehicle_bag_count'); ?>">
<?php
            for($i=$this->data['vehicle_bag_count_range']['min'];$i<=$this->data['vehicle_bag_count_range']['max'];$i++)
            {
?>
                                    <option value="<?php echo esc_attr($i); ?>"<?php echo ($i==1 ? ' selected="selected"' : ''); ?>><?php echo esc_html($i); ?></option>
<?php
            }
?>
                                </select>
                            </div>
                    
                            <div class="crbs-form-field crbs-form-field-width-33">
                                <label><?php esc_html_e('Vehicle Type','car-rental-booking-system'); ?></label>
                                <select name="<?php CRBSHelper::getFormName('vehicle_category'); ?>">
                                    <option value="0"><?php esc_html_e('- All vehicles -','car-rental-booking-system') ?></option>
<?php
            foreach($this->data['dictionary']['vehicle_category'] as $index=>$value)
            {
?>
                                    <option value="<?php echo esc_attr($index); ?>"><?php echo esc_html($value['name']); ?></option>
<?php
            }
?>
                                </select>                        
                            </div>
                        
                        </div>
                        
                    </div>
                    
                </div>
<?php
        }
?>
                <div class="crbs-notice crbs-hidden"></div>
                
                <div class="crbs-vehicle-list">
                    <ul class="crbs-list-reset"></ul>
                </div>
                
                <div class="crbs-booking-extra"></div>

            </div>

        </div>

        <div class="crbs-clear-fix crbs-main-content-navigation-button">
            <a href="#" class="crbs-button crbs-button-style-2 crbs-button-step-prev">
                <span class="crbs-meta-icon-arrow-horizontal"></span>
                <?php echo esc_html($this->data['step']['dictionary'][2]['button']['prev']); ?>
            </a> 
            <a href="#" class="crbs-button crbs-button-style-1 crbs-button-step-next">
                <?php echo esc_html($this->data['step']['dictionary'][2]['button']['next']); ?>
                <span class="crbs-meta-icon-arrow-horizontal"></span>
            </a> 
        </div>