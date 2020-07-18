<?php
        echo $this->data['html']['start'];
        echo do_shortcode('['.PLUGIN_CRBS_CONTEXT.'_booking_form booking_form_id="'.(int)$this->data['instance']['booking_form_id'].'" widget_booking_form_style_id="'.(int)$this->data['instance']['booking_form_style_id'].'"  widget_mode="1" widget_booking_form_url="'.$this->data['instance']['booking_form_url'].'"]');
        echo $this->data['html']['stop'];