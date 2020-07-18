<?php

/******************************************************************************/
/******************************************************************************/

class CRBSLogManager
{
	/**************************************************************************/
	
    function __construct()
	{
        $this->type=array
        (
            'mail'                                                              =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new booking to the customer.','car-rental-booking-system')
                ),
                2                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new booking on defined e-mail addresses.','car-rental-booking-system')
                ),               
                3                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new changes in the booking to the customer.','car-rental-booking-system')
                )
            ),
            'nexmo'                                                             =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new booking on defined phone number.','car-rental-booking-system')
                )
            ),
            'twilio'                                                            =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new booking on defined phone number.','car-rental-booking-system')
                )                
            ),
            'telegram'                                                          =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Sending an notification about new booking on defined phone number.','car-rental-booking-system')
                )                
            ),
            'google_calendar'                                                   =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Adding a new event to the calendar.','car-rental-booking-system')
                )            
            ),
            'geolocation'                                                       =>  array
            (
                1                                                               =>  array
                (
                    'description'                                               =>  __('Calculate distance between two locations.','car-rental-booking-system')
                ),
                2                                                               =>  array
                (
                    'description'                                               =>  __('Getting country information based on customer IP address.','car-rental-booking-system')
                )                
            )
        );
	}
        
	/**************************************************************************/
	
	function add($type,$event,$message)
	{	
        $Validation=new CRBSValidation();
        
        if($Validation->isEmpty($message)) return;
        
		$logType=$this->get($type);
        
        array_unshift($logType,array
        (
            'event'                                                             =>  $event,
            'timestamp'                                                         =>  strtotime('now'),
            'message'                                                           =>  $message
        ));
        
        if(count($logType)>9) $logType=array_slice($logType,0,10);
        
        $logFull=$this->get();
        $logFull[$type]=$logType;
        
        update_option(PLUGIN_CRBS_OPTION_PREFIX.'_log',$logFull);
	}
	
	/**************************************************************************/
	
	function get($type=null)
	{
        $log=get_option(PLUGIN_CRBS_OPTION_PREFIX.'_log');

        if(!is_array($log)) $log=array();
        if(is_null($type)) return($log);
        
        if(!array_key_exists($type,$log)) $log[$type]=array();
        if(!is_array($log[$type])) $log[$type]=array();
        
        return($log[$type]);
    }
	
	/**************************************************************************/
	
    function show($type)
    {
        $log=$this->get($type);
        
        if(!count($log)) return;
        
        $Validation=new CRBSValidation();
        
        $i=0;
        $html=null;
        
        foreach($log as $value)
        {
            if($Validation->isNotEmpty($html)) $html.='<br/>';
            
            $html.=
            '
                <li>
                    <div class="to-field-disabled to-field-disabled-full-width">
                        ['.(++$i).']['.date('d-m-Y G:i:s',$value['timestamp']).']<br/>
                        <b>'.$this->type[$type][$value['event']]['description'].'</b><br/><br/>
                        '.nl2br(htmlspecialchars($value['message'])).'
                    </div>
                </li>
            ';
        }
        
        $html='<ul>'.$html.'</ul>';
        
        return($html);
    }
    
    /**************************************************************************/

    function logWPMailError($wp_error)
    {
        global $crbs_logEvent;
        
        $this->add('mail',$crbs_logEvent,print_r($wp_error,true));
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/