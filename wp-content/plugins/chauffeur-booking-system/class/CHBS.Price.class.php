<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPrice
{
    /**************************************************************************/
    
    static function format($value,$currencyIndex)
    {
        $Currency=new CHBSCurrency();
        
        $currency=$Currency->getCurrency($currencyIndex);
 
        $value=number_format($value,2,$currency['separator'],$currency['separator2']);
        
        if($currency['position']=='left') 
            $value=$currency['symbol'].$value;
        else $value.=$currency['symbol'];
        
        return($value);
    }
    
    /**************************************************************************/
    
	static function formatToSave($value,$empty=false)
	{
		$Validation=new CHBSValidation();
		
		if(($Validation->isEmpty($value)) && ($empty)) return('');
		
		$value=preg_replace('/,/','.',$value);
		$value=number_format($value,2,'.','');
		return($value);
	}
	
	/**************************************************************************/
	
    static function calculateGross($value,$taxRateId=0,$taxValue=0)
    {
        if($taxRateId!=0)
        {
            $TaxRate=new CHBSTaxRate();
            $dictionary=$TaxRate->getDictionary();
            $taxValue=$dictionary[$taxRateId]['meta']['tax_rate_value'];
        }
        
        $value*=(1+($taxValue/100));
        
        return($value);
    }
    
    /**************************************************************************/
    
    static function getDefaultPrice()
    {
        return(number_format(0.00,2,'.',''));
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/