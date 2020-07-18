<?php

/******************************************************************************/
/******************************************************************************/

class CRBSNotice
{
	/**************************************************************************/

	function __construct()
	{
		$this->error=array();
	}

	/**************************************************************************/

	public function addError($fieldName,$errorMessage)
	{
		$this->error[]=array($fieldName,$errorMessage);
	}

	/**************************************************************************/

	public function getError()
	{
		return($this->error);
	}

	/**************************************************************************/

	public function isError()
	{
		return(count($this->error));
	}
	
	/**************************************************************************/
	
	public function createHTML($templatePath,$forceError=false,$error=false,$subtitle=null)
	{
		$data=array();
		
		$data['type']=($forceError ? $error : $this->isError()) ? 'error' : 'success';
		
		if($this->isError())
		{
			$data['title']=esc_html__('Error','car-rental-booking-system');
			$data['subtitle']=is_null($subtitle) ? esc_html__('Changes can not be saved.','car-rental-booking-system') : $subtitle;				
		}
		else
		{
			$data['title']=esc_html__('Success','car-rental-booking-system');
			$data['subtitle']=is_null($subtitle) ? esc_html__('All changes have been saved.','car-rental-booking-system') : $subtitle;			
		}
        
		$Template=new CRBSTemplate($data,$templatePath);
		return($Template->output());
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/