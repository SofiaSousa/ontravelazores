<?php

/******************************************************************************/
/******************************************************************************/

class CRBSPrice
{
    /**************************************************************************/
    
    static function format($value,$currencyIndex)
    {
        $Currency=new CRBSCurrency();
        $currency=$Currency->getCurrency($currencyIndex);
        
        $value=self::numberFormat($value,$currencyIndex);
        
        if($currency['position']=='left') 
            $value=$currency['symbol'].$value;
        else $value.=$currency['symbol'];
        
        return($value);
    }
    
    /**************************************************************************/
    
    static function numberFormat($value,$currencyIndex)
    {
        $Currency=new CRBSCurrency();
        $currency=$Currency->getCurrency($currencyIndex);
		
        $value=number_format($value,2,$currency['separator'],$currency['separator2']);
        return($value);
    }
    
    /**************************************************************************/
    
    static function calculateGross($value,$taxRateId=0,$taxValue=0)
    {
        if($taxRateId!=0)
        {
            $TaxRate=new CRBSTaxRate();
            $dictionary=$TaxRate->getDictionary();
            $taxValue=$dictionary[$taxRateId]['meta']['tax_rate_value'];
        }
        
        $value*=(1+($taxValue/100));
        
        return($value);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/