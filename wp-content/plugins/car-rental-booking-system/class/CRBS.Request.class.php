<?php

/******************************************************************************/
/******************************************************************************/

class CRBSRequest
{
    /**************************************************************************/
    
    static function get($name,$attribute=true)
    {
        if(array_key_exists($name,$_GET))
        {
            if($attribute) return(esc_attr($_GET[$name]));
            return($_GET[$name]);
        }
        return;
    }
    
    /**************************************************************************/
    
    static function getOnPrefix($prefix,$attribute=true)
    {
        $data=array();
        
        foreach($_GET as $index=>$value)
        {
            $key='/^'.$prefix.'_/';
            if(preg_match($key,$index))
            {
                $data[preg_replace($key,'',$index)]=$value;
            }
        }
        
        if($attribute) return(esc_attr(json_encode($data,JSON_UNESCAPED_UNICODE)));
        
        return($data);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/