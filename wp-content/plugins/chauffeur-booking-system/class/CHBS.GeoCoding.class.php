<?php

/******************************************************************************/
/******************************************************************************/

class CHBSGeoCoding
{
    /**************************************************************************/

    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function getCountryCode($lat,$lng)
    {
        $url='https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&key='.CHBSOption::getOption('google_map_api_key');
        
        if(($content=file_get_contents($url))===false) return(false);
        
        $document=json_decode($content);
        
        $LogManager=new CHBSLogManager();
        $LogManager->add('geolocation',1,print_r($document,true));   
        
        if($document->status!='OK') return(false);
        
        foreach($document->results as $result)
        {
            foreach($result->{'address_components'} as $component)
            {
                if(in_array('country',$component->types))
                {
                    return($component->{'short_name'});
                }
            }
        }

        return(false);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/