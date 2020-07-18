<?php

/******************************************************************************/
/******************************************************************************/

class CHBSRequestData
{
    /**************************************************************************/
    
    static function getFromWidget($serviceTypeId,$name,$defaultValue='')
    {
        if((int)self::get('service_type_id')!==(int)$serviceTypeId) return;
        
        return(self::get($name,$defaultValue));
    }
    
    /**************************************************************************/

    static function getCoordinateFromWidget($serviceTypeId,$name)
    {
        if((int)self::get('service_type_id')!==(int)$serviceTypeId) return;
        
        $data=array('lat'=>self::get($name.'_lat'),'lng'=>self::get($name.'_lng'),'address'=>self::get($name.'_address'),'formatted_address'=>self::get($name.'_formatted_address'));
        
        return(json_encode($data,JSON_UNESCAPED_UNICODE));
    }
    
    /**************************************************************************/
    
    static function get($name,$defaultValue='')
    {
        if(array_key_exists($name,$_GET))
            return($_GET[$name]);
    
        return;
    }
    
    /**************************************************************************/
    
    static function post($name,$defaultValue='')
    {
        if(array_key_exists($name,$_POST))
            return($_POST[$name]);
    
        return;
    }    

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/