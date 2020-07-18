<?php

/******************************************************************************/
/******************************************************************************/

class CRBSOption
{
	/**************************************************************************/
	
	static function createOption($refresh=false)
	{
		return(CRBSGlobalData::setGlobalData(PLUGIN_CRBS_CONTEXT,array('CRBSOption','createOptionObject'),$refresh));				
	}
        
	/**************************************************************************/
	
	static function createOptionObject()
	{	
		return((array)get_option(PLUGIN_CRBS_OPTION_PREFIX.'_option'));
	}
	
	/**************************************************************************/
	
	static function refreshOption()
	{
		return(self::createOption(true));
	}
	
	/**************************************************************************/
	
	static function getOption($name)
	{
		global $crbsGlobalData;

		self::createOption();

		if(!array_key_exists($name,$crbsGlobalData[PLUGIN_CRBS_CONTEXT])) return(null);
		return($crbsGlobalData[PLUGIN_CRBS_CONTEXT][$name]);		
	}

	/**************************************************************************/
	
	static function getOptionObject()
	{
		global $crbsGlobalData;
		return($crbsGlobalData[PLUGIN_CRBS_CONTEXT]);
	}
	
	/**************************************************************************/
	
	static function updateOption($option)
	{
		$nOption=array();
		foreach($option as $index=>$value) $nOption[$index]=$value;
		
		$oOption=self::refreshOption();

		update_option(PLUGIN_CRBS_OPTION_PREFIX.'_option',array_merge($oOption,$nOption));
		
		self::refreshOption();
	}
	
	/**************************************************************************/
	
	static function resetOption()
	{
		update_option(PLUGIN_CRBS_OPTION_PREFIX.'_option',array());
		self::refreshOption();		
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/