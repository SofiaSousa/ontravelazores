<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingFormStyle
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->color=array
        (
            1                                                                   =>  array
            (
                'color'                                                         =>  'F3B52E'
            ),
            2                                                                   =>  array
            (
                'color'                                                         =>  'EAECEE'
            ),
            3                                                                   =>  array
            (
                'color'                                                         =>  'FFFFFF'
            ),
            4                                                                   =>  array
            (
                'color'                                                         =>  '778591'
            ),
            5                                                                   =>  array
            (
                'color'                                                         =>  'EAECEE'
            ),
            6                                                                   =>  array
            (
                'color'                                                         =>  '2C3E50'
            ),
            7                                                                   =>  array
            (
                'color'                                                         =>  'CED3D9'
            ),
            8                                                                   =>  array
            (
                'color'                                                         =>  '9EA8B2'
            ),
            9                                                                   =>  array
            (
                'color'                                                         =>  '556677'
            ),
            10                                                                  =>  array
            (
                'color'                                                         =>  'F6F6F6'
            ),            
        );
    }
    
    /**************************************************************************/
    
    function isColor($color)
    {
        return(array_key_exists($color,$this->getColor()));
    }
    
    /**************************************************************************/
    
    function getColor()
    {
        return($this->color);
    }
    
    /**************************************************************************/
    
    function createCSSFile()
    {
		$path=array
		(
			CRBSFile::getMultisiteBlog()
		);
		
		foreach($path as $pathData)
		{
			if(!CRBSFile::dirExist($pathData)) @mkdir($pathData);			
			if(!CRBSFile::dirExist($pathData)) return(false);
		}
        		
		/***/
        
        $content=null;
		
        $Validation=new CRBSValidation();
        $BookingForm=new CRBSBookingForm();
        
        $dictionary=$BookingForm->getDictionary();
        
        foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
        {
            $meta=$dictionaryValue['meta'];

            foreach($this->getColor() as $colorIndex=>$colorValue)
            {
                if((!isset($meta['style_color'][$colorIndex])) || (!$Validation->isColor($meta['style_color'][$colorIndex]))) 
                    $meta['style_color'][$colorIndex]=$colorValue['color'];
            }
            
            $data=array();
		
            $data['color']=$meta['style_color'];
            $data['main_css_class']='.crbs-booking-form-id-'.$dictionaryIndex;

            $Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'public/style.php');
		
            $content.=$Template->output();
        }
        
        if($Validation->isNotEmpty($content))
            file_put_contents(CRBSFile::getMultisiteBlogCSS(),$content); 
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/