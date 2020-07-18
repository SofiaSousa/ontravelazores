<?php

/******************************************************************************/
/******************************************************************************/

class CRBSGeofenceChecker
{
    /**************************************************************************/
    
    function pointInPolygon($point,$polygon)
    {
        $c=0;
        $p1=$polygon[0];
        $n=count($polygon);

        for($i=1;$i<=$n;$i++)
        {
            $p2=$polygon[$i%$n];
            if(($point->long>min($p1->long,$p2->long)) && ($point->long<=max($p1->long,$p2->long)) && ($point->lat<=max($p1->lat,$p2->lat)) && ($p1->long!=$p2->long)) 
            {
                $xinters=($point->long-$p1->long)*($p2->lat-$p1->lat)/($p2->long-$p1->long)+$p1->lat;
                if(($p1->lat==$p2->lat) || ($point->lat<=$xinters)) $c++;
            }
            $p1=$p2;
        }
        
        return($c%2!=0);        
    }
    
    /**************************************************************************/
    
    function transformShape($geofence,$dictionary)
    {
        $coordinate=array();
        
        if(!is_array($geofence)) return(false);
        
        foreach($geofence as $geofenceId)
        {
            if(!array_key_exists($geofenceId,$dictionary)) continue;
            
            $object=$dictionary[$geofenceId]['meta']['shape_coordinate'];
          
            if(!is_object($object)) continue;
            
            foreach($object as $objectData)
            {
                $t=array();
                
                foreach($objectData as $objectCoordinate)
                    $t[]=new CRBSPoint($objectCoordinate->lat,$objectCoordinate->lng);
                
                array_push($coordinate,$t);
            }
        }
        
        return($coordinate);        
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/