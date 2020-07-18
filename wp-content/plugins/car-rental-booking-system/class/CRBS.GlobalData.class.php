<?php

/******************************************************************************/
/******************************************************************************/

class CRBSGlobalData
{
	/**************************************************************************/
	
	static function setGlobalData($name,$functionCallback,$refresh=false)
	{
		global $crbsGlobalData;
		
		if(isset($crbsGlobalData[$name]) && (!$refresh)) return($crbsGlobalData[$name]);
		
		$crbsGlobalData[$name]=call_user_func($functionCallback);
		
		return($crbsGlobalData[$name]);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/