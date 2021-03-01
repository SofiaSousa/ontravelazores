<?php

/******************************************************************************/
/******************************************************************************/

class CRBSBookingFormElement
{
    /**************************************************************************/
    
    function __construct()
    {
		$this->fieldType=array
		(
			1																	=>	array(__('Text','car-rental-booking-system')),
			3																	=>	array(__('File','car-rental-booking-system')),
			2																	=>	array(__('Select list','car-rental-booking-system')),
		);
    }
	
	/**************************************************************************/
	
	function getFieldType()
	{
		return($this->fieldType);
	}
	
	/**************************************************************************/
	
	function isFieldType($fieldType)
	{
		return(array_key_exists($fieldType,$this->getFieldType()) ? true : false);
	}
    
    /**************************************************************************/
       
    function save($bookingFormId)
    {
        /***/
        
		$formElementPanel=array();
        $formElementPanelPost=CRBSHelper::getPostValue('form_element_panel');
        
        if(isset($formElementPanelPost['id']))
        {
            $Validation=new CRBSValidation();
            
            foreach($formElementPanelPost['id'] as $index=>$value)
            {
                if($Validation->isEmpty($formElementPanelPost['label'][$index])) continue;
                
                if($Validation->isEmpty($value))
                    $value=CRBSHelper::createId();
                
                $formElementPanel[]=array('id'=>$value,'label'=>$formElementPanelPost['label'][$index]);
            }
        }
        
        CRBSPostMeta::updatePostMeta($bookingFormId,'form_element_panel',$formElementPanel); 
        
        $meta=CRBSPostMeta::getPostMeta($bookingFormId);
        
        /***/
        
		$formElementField=array();
        $formElementFieldPost=CRBSHelper::getPostValue('form_element_field');        
        
        if(isset($formElementFieldPost['id']))
        {
            $Validation=new CRBSValidation();
            
            $panelDictionary=$this->getPanel($meta);
            
            foreach($formElementFieldPost['id'] as $index=>$value)
            {
                if(!isset($formElementFieldPost['label'][$index],$formElementFieldPost['field_type'][$index],$formElementFieldPost['mandatory'][$index],$formElementFieldPost['dictionary'][$index],$formElementFieldPost['message_error'][$index],$formElementFieldPost['panel_id'][$index])) continue;
                
                if($Validation->isEmpty($formElementFieldPost['label'][$index])) continue;
                
				if(!$this->isFieldType($formElementFieldPost['field_type'][$index])) continue;
				
				if($formElementFieldPost['field_type'][$index]===2)
				{
					if($Validation->isEmpty($formElementFieldPost['dictionary'][$index])) continue;
				}
				
                if(!$Validation->isBool((int)$formElementFieldPost['mandatory'][$index])) continue;
                else 
                {
                    if($formElementFieldPost['mandatory'][$index]==1)
                    {    
                        if($Validation->isEmpty($formElementFieldPost['message_error'][$index])) continue;
                    }
                }
				
                if(!$this->isPanel($formElementFieldPost['panel_id'][$index],$panelDictionary)) continue;
                
                if($Validation->isEmpty($value))
                    $value=CRBSHelper::createId();
                
                $formElementField[]=array('id'=>$value,'label'=>$formElementFieldPost['label'][$index],'field_type'=>$formElementFieldPost['field_type'][$index],'mandatory'=>$formElementFieldPost['mandatory'][$index],'dictionary'=>$formElementFieldPost['dictionary'][$index],'message_error'=>$formElementFieldPost['message_error'][$index],'panel_id'=>$formElementFieldPost['panel_id'][$index]);
            }
        }  
        
        CRBSPostMeta::updatePostMeta($bookingFormId,'form_element_field',$formElementField); 
        
        /***/
        
		$formElementAgreement=array();
        $formElementAgreementPost=CRBSHelper::getPostValue('form_element_agreement');        
        
        if(isset($formElementAgreementPost['id']))
        {
            $Validation=new CRBSValidation();
            
            foreach($formElementAgreementPost['id'] as $index=>$value)
            {
                if(!isset($formElementAgreementPost['text'][$index])) continue;
                if($Validation->isEmpty($formElementAgreementPost['text'][$index])) continue;
                
                if($Validation->isEmpty($value))
                    $value=CRBSHelper::createId();
                
                $formElementAgreement[]=array('id'=>$value,'text'=>$formElementAgreementPost['text'][$index]);
            }
        }        
        
        CRBSPostMeta::updatePostMeta($bookingFormId,'form_element_agreement',$formElementAgreement);        
    }
    
    /**************************************************************************/
    
    function getPanel($meta)
    {
        $panel=array
        (
            array
            (
                'id'                                                            =>  1,
                'label'                                                         =>  __('- Contact details -','car-rental-booking-system')
            ),
            array
            (
                'id'                                                            =>  2,
                'label'                                                         =>  __('- Billing address -','car-rental-booking-system')                
            )
        );
             
        if(isset($meta['form_element_panel']))
        {
            foreach($meta['form_element_panel'] as $value)
                $panel[]=$value;
        }
        
        return($panel);
    }
	
	/**************************************************************************/
	
	function getFieldValueByLabel($label,$meta)
	{
		if(is_array($meta))
		{
			foreach($meta['form_element_field'] as $value)
			{
				if($value['label']==$label) return($value['value']);
			}
		}
		
		return(null);
	}

    /**************************************************************************/
    
    function isPanel($panelId,$panelDictionary)
    {
        foreach($panelDictionary as $value)
        {
            if($value['id']==$panelId) return(true);
        }
        
        return(false);
    }
    
    /**************************************************************************/
    
    function createField($panelId,$meta)
    {
		$html=array(null,null);
		
		$Validation=new CRBSValidation();
        
        if(!array_key_exists('form_element_field',$meta)) return(null);
        
        foreach($meta['form_element_field'] as $value)
        {
            if($value['panel_id']==$panelId)
            {
                $name='form_element_field_'.$value['id'];
                
				if(in_array($value['field_type'],array(1,2)))
				{
					$html[1].=
					'
						<div class="crbs-clear-fix">
							<div class="crbs-form-field crbs-form-field-width-100">
								<label>'.esc_html($value['label']).((int)$value['mandatory']===1 ? ' *' : '').'</label>
					';
				
					if((int)$value['field_type']===2)
					{
						$fieldHtml=null;
						$fieldValue=preg_split('/;/',$value['dictionary']);

						foreach($fieldValue as $fieldValueValue)
						{
							if($Validation->isNotEmpty($fieldValueValue))
								$fieldHtml.='<option value="'.esc_attr($fieldValueValue).'"'.CRBSHelper::selectedIf($fieldValueValue,CRBSHelper::getPostValue($name),false).'>'.esc_html($fieldValueValue).'</option>';
						}

						$html[1].=
						'
							<select name="'.CRBSHelper::getFormName($name,false).'">
								'.$fieldHtml.'
							</select>
						';	
					}
					elseif((int)$value['field_type']===1)
					{
						$html[1].=
						'
							<input type="text" name="'.CRBSHelper::getFormName($name,false).'"  value="'.esc_attr(CRBSHelper::getPostValue($name)).'"/>	
						';
					}

					$html[1].=
					'                            
							</div>                        
						</div>
					';
				}
				elseif((int)$value['field_type']===3)
				{
					$classButton=array(array('crbs-file-upload','crbs-button','crbs-button-style-3'),array('crbs-file-remove'));
			
					$fileName=null;
			
					if($Validation->isEmpty(CRBSHelper::getPostValue($name)))
						array_push($classButton[1],'crbs-hidden');
					else 
					{
						$fileName=CRBSHelper::getPostValue($name);
						array_push($classButton[0],'crbs-hidden');
					}
					
					$html[1].=
					'
						<div class="crbs-form-field">
							<label>'.esc_html($value['label']).((int)$value['mandatory']===1 ? ' *' : '').'</label>
							<div'.CRBSHelper::createCSSClassAttribute($classButton[0]).'>
								<span>'.esc_html__('Upload a file','car-rental-booking-system').'</span>
								<input type="file" name="'.CRBSHelper::getFormName($name,false).'"></input>
							</div>
							<div'.CRBSHelper::createCSSClassAttribute($classButton[1]).'>
								<span>'.esc_html__('Uploaded file:','car-rental-booking-system').'<span>'.esc_html($fileName).'</span></span>
								<span'.CRBSHelper::createCSSClassAttribute(array('crbs-button','crbs-button-style-3')).'>'.esc_html__('Remove file','car-rental-booking-system').'</span>
							</div>
							<input type="hidden" name="'.CRBSHelper::getFormName($name,false).'_type"/>
							<input type="hidden" name="'.CRBSHelper::getFormName($name,false).'_name"/>
							<input type="hidden" name="'.CRBSHelper::getFormName($name,false).'_tmp_name"/>
						</div>	
					';
				}
            }
        }
        
        if(array_key_exists('form_element_panel',$meta))
        {
            if(!in_array($panelId,array(1,2)))
            {
                foreach($meta['form_element_panel'] as $value)
                {
                    if($value['id']==$panelId)
                    {
                        $html[0].=
                        '
                            <label class="crbs-form-panel-label">'.esc_html($value['label']).'</label> 
                        ';
                    }
                }
            }
        }
        
        if($Validation->isNotEmpty($html[0]))
        {
            $html=
            '
                <div class="crbs-form-panel">
                    '.$html[0].'
                    <div class="crbs-form-panel-content crbs-clear-fix">
                        '.$html[1].'
                    </div>
                </div>
            ';
        }
        else $html=$html[1];
        
        return($html);
    }
    
    /**************************************************************************/
    
    function createAgreement($meta)
    {
        $html=null;
        $Validation=new CRBSValidation();
        
        if(!array_key_exists('form_element_agreement',$meta)) return($html);
        
        foreach($meta['form_element_agreement'] as $value)
        {
            $html.=
            '
                <div class="crbs-clear-fix">
                    <span class="crbs-form-checkbox">
                        <span class="crbs-meta-icon-tick"></span>
                    </span>
                    <span>'.$value['text'].'</span>
                    <input type="hidden" name="'.CRBSHelper::getFormName('form_element_agreement_'.$value['id'],false).'" value="0"/> 
                </div>      
            ';
        }
        
        if($Validation->isNotEmpty($html))
        {
            $html=
            '
                <h4 class="crbs-header">'.esc_html__('Agreements','car-rental-booking-system').'</h4>
                <div class="crbs-agreement">
                    '.$html.'
                </div>
            ';
        }
        
        return($html);
    }
    
    /**************************************************************************/
    
    function validateField($meta,$data)
    {
        $error=array();
        
        $Validation=new CRBSValidation();
        
        if(!array_key_exists('form_element_field',$meta)) return($error);
        
        foreach($meta['form_element_field'] as $value)
        {
            $name='form_element_field_'.$value['id'];
            
            if((int)$value['mandatory']===1)
            {
				$name1=$name2=$name;
				
				if((int)$value['field_type']===3) $name2=$name1.='_tmp_name';
				
                if(array_key_exists($name1,$data))
                {
                    if($value['panel_id']==2)
                    {
                        if((int)$data['client_billing_detail_enable']===1)
                        {
                            if($Validation->isEmpty($data[$name1]))
                                $error[]=array('name'=>CRBSHelper::getFormName($name2,false),'message_error'=>$value['message_error']);                            
                        }
                    }
                    else
                    {
                        if($Validation->isEmpty($data[$name1]))
                            $error[]=array('name'=>CRBSHelper::getFormName($name2,false),'message_error'=>$value['message_error']);
                    }
                }
            }
        }
        
        return($error);
    }
    
    /**************************************************************************/
    
    function validateAgreement($meta,$data)
    {
        if(!array_key_exists('form_element_agreement',$meta)) return(false);
        
        foreach($meta['form_element_agreement'] as $value)
        {
            $name='form_element_agreement_'.$value['id'];  
            
            if((!array_key_exists($name,$data)) || ((int)$data[$name]!==1))
                return(true);
        }
        
        return(false);
    }
    
    /**************************************************************************/
    
    function sendBookingField($bookingId,$meta,$data)
    {
        if(!array_key_exists('form_element_field',$meta)) return;
        
        foreach($meta['form_element_field'] as $index=>$value)
        {
            $name='form_element_field_'.$value['id']; 
            $meta['form_element_field'][$index]['value']=$data[$name];
			
			if(array_key_exists($name.'_tmp_name',$data))
			{
				$file1=CRBSFile::getUploadPath().'/'.$data[$name.'_tmp_name'];
				$file2=CRBSFile::getUploadPath().'/'.$data[$name.'_name'];
            
				if(rename($file1,$file2))
				{
					$upload=wp_upload_bits($data[$name.'_name'],null,file_get_contents($file2));

					if($upload['error']===false)
					{
						$attachment=array
						(
							'post_title'										=>  $data[$name.'_name'],
							'post_mime_type'									=>  $data[$name.'_type'],
							'post_content'										=>  '',
							'post_status'										=>  'inherit'
						);

						$attachmentId=wp_insert_attachment($attachment,$upload['file'],$bookingId);

						if($attachmentId>0)
						{
							$attachmentData=wp_generate_attachment_metadata($attachmentId,$upload['file']);
							wp_update_attachment_metadata($attachmentId,$attachmentData);
							
							$meta['form_element_field'][$index]['attachment_id']=$attachmentId;
						}
					}
				}
            
				@unlink($file1);
				@unlink($file2);
			}
        }
        
        CRBSPostMeta::updatePostMeta($bookingId,'form_element_panel',$meta['form_element_panel']);
        CRBSPostMeta::updatePostMeta($bookingId,'form_element_field',$meta['form_element_field']);
    }
    
    /**************************************************************************/
    
    function displayField($panelId,$meta,$type=1,$argument=array())
    {
        $html=null;
        
        if(!array_key_exists('form_element_field',$meta)) return($html);
        
        foreach($meta['form_element_field'] as $value)
        {
            if($value['panel_id']==$panelId)
            {
				$fieldValue=esc_html($value['value']);
				$fieldLabel=esc_html($value['label']);
				
				if((int)$value['field_type']===3)
				{
					if((int)$value['attachment_id']>0)
					{
						if(!is_null($file=get_post($value['attachment_id'])))
						{
							if($type===1)
								$fieldValue='<a href="'.get_edit_post_link($value['attachment_id']).'" target="_blank">'.esc_html($file->post_title).'</a>';
							else $fieldValue=esc_html($file->post_title);
						}
						else continue;
					}
					else continue;
				}
				
                if($type==1)
                {
                    $html.=
                    '
                        <div>
                            <span class="to-legend-field">'.$fieldLabel.': </span>
                            <div class="to-field-disabled">'.$fieldValue.'</div>                                
                        </div>    
                    ';
                }
                elseif($type==2)
                {
                    $html.=
                    '
                        <tr>
                            <td '.$argument['style']['cell'][1].'>'.$fieldLabel.'</td>
                            <td '.$argument['style']['cell'][2].'>'.$fieldValue.'</td>
                        </tr>
                    ';                    
                }
            }
        }
        
        return($html);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/