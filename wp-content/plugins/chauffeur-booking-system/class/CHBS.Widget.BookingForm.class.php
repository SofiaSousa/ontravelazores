<?php

/******************************************************************************/
/******************************************************************************/

class CHBSWidgetBookingForm extends CHBSWidget 
{
	/**************************************************************************/
	
    function __construct() 
	{
		parent::__construct('chbs_widget_booking_form',__('Chauffeur Booking Form','chauffeur-booking-system'),array('description'=>__('Displays booking form.','chauffeur-booking-system')),array());
    }
	
	/**************************************************************************/
	
    function widget($argument,$instance) 
	{
		$argument['_data']['file']='widget_booking_form.php';
		parent::widget($argument,$instance);
    }
	
	/**************************************************************************/
	
    function update($new_instance,$old_instance)
	{
		$instance=array();
        $instance['booking_form_id']=(int)$new_instance['booking_form_id'];
        $instance['booking_form_url']=$new_instance['booking_form_url'];
        $instance['booking_form_new_window']=(int)$new_instance['booking_form_new_window'];
        $instance['booking_form_currency']=$new_instance['booking_form_currency'];
		return($instance);
    }
	
	/**************************************************************************/
	
	function form($instance) 
	{	
		$instance['_data']['file']='widget_booking_form.php';
		$instance['_data']['field']=array('booking_form_id','booking_form_url','booking_form_new_window','booking_form_currency');
		parent::form($instance);
	}
	
	/**************************************************************************/
	
	function register($class=null)
	{
		parent::register(is_null($class) ? get_class() : $class);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/