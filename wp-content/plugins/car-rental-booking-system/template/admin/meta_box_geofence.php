<?php 
		echo $this->data['nonce']; 
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-tax-rate-1"><?php esc_html_e('General','car-rental-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-tax-rate-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Geofence','car-rental-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Geofence.','car-rental-booking-system'); ?></span>
                            <div class="to-clear-fix">
                                <input type="text" class="to-width-full" name="<?php CRBSHelper::getFormName('google_map_autocomplete'); ?>" id="<?php CRBSHelper::getFormName('google_map_autocomplete'); ?>" value="" title="<?php esc_attr_e('Enter location.','car-rental-booking-system'); ?>"/>
                            </div>
                            <div class="to-clear-fix">
                                <div id="to-google-map"></div>
                            </div>
                            <div class="to-clear-fix to-float-right">
                                <?php esc_html_e('Options:','car-rental-booking-system'); ?>
                                <a href="#" id="<?php CRBSHelper::getFormName('shape_remove'); ?>"><?php esc_html_e('Remove selected shape','car-rental-booking-system'); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <input type="hidden" value="<?php echo esc_attr(json_encode($this->data['meta']['shape_coordinate'])); ?>" name="<?php CRBSHelper::getFormName('shape_coordinate'); ?>" id="<?php CRBSHelper::getFormName('shape_coordinate'); ?>"/>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('.to').themeOptionElement({init:true});
                
                var geofence=$().CRBSGeofenceAdmin(
                {
                    coordinate                                                  :   <?php echo json_encode($this->data['coordinate']); ?>
                });
                
                geofence.init();
            });
		</script>