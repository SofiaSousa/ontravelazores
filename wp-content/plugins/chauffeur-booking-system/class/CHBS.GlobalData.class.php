<?php

/******************************************************************************/
/******************************************************************************/

class CHBSGlobalData
{
	/**************************************************************************/
	
	static function setGlobalData($name,$functionCallback,$refresh=false)
	{
		global $chbsGlobalData;
		
		if(isset($chbsGlobalData[$name]) && (!$refresh)) return($chbsGlobalData[$name]);
		
		$chbsGlobalData[$name]=call_user_func($functionCallback);
		
		return($chbsGlobalData[$name]);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/