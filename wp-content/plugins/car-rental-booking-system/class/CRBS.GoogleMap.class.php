<?php

/******************************************************************************/
/******************************************************************************/

class CRBSGoogleMap
{
    /**************************************************************************/

    function __construct()
    {
		$this->position=array
		(
			'TOP_CENTER'														=>	__('Top center','car-rental-booking-system'),
			'TOP_LEFT'															=>	__('Top left','car-rental-booking-system'),
			'TOP_RIGHT'															=>	__('Top right','car-rental-booking-system'),
			'LEFT_TOP'															=>	__('Left top','car-rental-booking-system'),
			'RIGHT_TOP'															=>	__('Right top','car-rental-booking-system'),
			'LEFT_CENTER'														=>	__('Left center','car-rental-booking-system'),
			'RIGHT_CENTER'														=>	__('Right center','car-rental-booking-system'),
			'LEFT_BOTTOM'														=>	__('Left bottom','car-rental-booking-system'),
			'RIGHT_BOTTOM'														=>	__('Right bottom','car-rental-booking-system'),
			'BOTTOM_CENTER'														=>	__('Bottom center','car-rental-booking-system'),
			'BOTTOM_LEFT'														=>	__('Bottom left','car-rental-booking-system'),
			'BOTTOM_RIGHT'														=>	__('Bottom right','car-rental-booking-system')	
		);
        
		$this->mapTypeControlId=array
		(
			'ROADMAP'															=>	__('Roadmap','car-rental-booking-system'),
			'SATELLITE'															=>	__('Satellite','car-rental-booking-system'),
			'HYBRID'															=>	__('Hybrid','car-rental-booking-system'),
			'TERRAIN'															=>	__('Terrain','car-rental-booking-system')
		);
        
		$this->mapTypeControlStyle=array
		(
			'DEFAULT'															=>	__('Default','car-rental-booking-system'),
			'HORIZONTAL_BAR'													=>	__('Horizontal Bar','car-rental-booking-system'),
			'DROPDOWN_MENU'														=>	__('Dropdown Menu','car-rental-booking-system')
		);
        
        $this->routeAvoid=array
        (
            'tolls'                                                             =>  __('Tolls','car-rental-booking-system'),
            'highways'                                                          =>  __('Highways','car-rental-booking-system'),
            'ferries'                                                           =>  __('Ferries','car-rental-booking-system')
        );
	}
    
    /**************************************************************************/
    
    function getMapTypeControlStyle()
    {
        return($this->mapTypeControlStyle);
    }
   
     /**************************************************************************/
    
    function getPosition()
    {
        return($this->position);
    }
    
    /**************************************************************************/
    
    function getMapTypeControlId()
    {
        return($this->mapTypeControlId);
    }
    
    /**************************************************************************/
    
    function getRouteAvoid()
    {
        return($this->routeAvoid);
    }
    
    /**************************************************************************/
    
    function getDistance($coordinate)
    {
		$LogManager=new CRBSLogManager();
            
        $url='https://maps.googleapis.com/maps/api/distancematrix/json?origins='.join(',',$coordinate[0]).'&destinations='.join(',',$coordinate[1]).'&key='.CRBSOption::getOption('google_map_api_key');
        
        if(($data=file_get_contents($url))===false) 
		{
			$LogManager->add('geolocation',1,print_r($data,true));   
			return(0);
		}
		
        if(is_null($data=json_decode($data))) 
		{
			$LogManager->add('geolocation',1,print_r(json_decode($data),true));   
			return(0);
		}
		
		$LogManager->add('geolocation',1,print_r(json_decode($data),true)); 
		
        return(round($data->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'value'}/1000));
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/