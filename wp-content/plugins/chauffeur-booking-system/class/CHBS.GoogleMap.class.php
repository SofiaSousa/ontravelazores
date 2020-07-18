<?php

/******************************************************************************/
/******************************************************************************/

class CHBSGoogleMap
{
    /**************************************************************************/

    function __construct()
    {
		$this->position=array
		(
			'TOP_CENTER'														=>	__('Top center','chauffeur-booking-system'),
			'TOP_LEFT'															=>	__('Top left','chauffeur-booking-system'),
			'TOP_RIGHT'															=>	__('Top right','chauffeur-booking-system'),
			'LEFT_TOP'															=>	__('Left top','chauffeur-booking-system'),
			'RIGHT_TOP'															=>	__('Right top','chauffeur-booking-system'),
			'LEFT_CENTER'														=>	__('Left center','chauffeur-booking-system'),
			'RIGHT_CENTER'														=>	__('Right center','chauffeur-booking-system'),
			'LEFT_BOTTOM'														=>	__('Left bottom','chauffeur-booking-system'),
			'RIGHT_BOTTOM'														=>	__('Right bottom','chauffeur-booking-system'),
			'BOTTOM_CENTER'														=>	__('Bottom center','chauffeur-booking-system'),
			'BOTTOM_LEFT'														=>	__('Bottom left','chauffeur-booking-system'),
			'BOTTOM_RIGHT'														=>	__('Bottom right','chauffeur-booking-system')	
		);
        
		$this->mapTypeControlId=array
		(
			'ROADMAP'															=>	__('Roadmap','chauffeur-booking-system'),
			'SATELLITE'															=>	__('Satellite','chauffeur-booking-system'),
			'HYBRID'															=>	__('Hybrid','chauffeur-booking-system'),
			'TERRAIN'															=>	__('Terrain','chauffeur-booking-system')
		);
        
		$this->mapTypeControlStyle=array
		(
			'DEFAULT'															=>	__('Default','chauffeur-booking-system'),
			'HORIZONTAL_BAR'													=>	__('Horizontal Bar','chauffeur-booking-system'),
			'DROPDOWN_MENU'														=>	__('Dropdown Menu','chauffeur-booking-system')
		);
        
        $this->routeAvoid=array
        (
            'tolls'                                                             =>  __('Tolls','chauffeur-booking-system'),
            'highways'                                                          =>  __('Highways','chauffeur-booking-system'),
            'ferries'                                                           =>  __('Ferries','chauffeur-booking-system')
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
}

/******************************************************************************/
/******************************************************************************/