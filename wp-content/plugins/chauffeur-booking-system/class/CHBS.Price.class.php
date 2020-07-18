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
}

/******************************************************************************/
/******************************************************************************/