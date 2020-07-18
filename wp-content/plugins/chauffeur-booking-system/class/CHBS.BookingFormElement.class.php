<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingFormElement
{
    /**************************************************************************/
    
    function __construct()
    {
        
    }
    
    /**************************************************************************/
       
    function save($bookingFormId)
    {
        /***/
        
		$formElementPanel=array();
        $formElementPanelPost=CHBSHelper::getPostValue('form_element_panel');
        
        if(isset($formElementPanelPost['id']))
        {
            $Validation=new CHBSValidation();
            
            foreach($formElementPanelPost['id'] as $index=>$value)
            {
                if($Validation->isEmpty($formElementPanelPost['label'][$index])) continue;
                
                if($Validation->isEmpty($value))
                    $value=CHBSHelper::createId();
                
                $formElementPanel[]=array('id'=>$value,'label'=>$formElementPanelPost['label'][$index]);
            }
        }
        
        CHBSPostMeta::updatePostMeta($bookingFormId,'form_element_panel',$formElementPanel); 
        
        $meta=CHBSPostMeta::getPostMeta($bookingFormId);
        
        /***/
        
		$formElementField=array();
        $formElementFieldPost=CHBSHelper::getPostValue('form_element_field');        
        
        if(isset($formElementFieldPost['id']))
        {
            $Validation=new CHBSValidation();
            $ServiceType=new CHBSServiceType();
            
            $panelDictionary=$this->getPanel($meta);
            
            foreach($formElementFieldPost['id'] as $index=>$value)
            {
                if(!isset($formElementFieldPost['label'][$index],$formElementFieldPost['mandatory'][$index],$formElementFieldPost['message_error'][$index],$formElementFieldPost['panel_id'][$index])) continue;
                
                if($Validation->isEmpty($formElementFieldPost['label'][$index])) continue;
                
                if(!$Validation->isBool((int)$formElementFieldPost['mandatory'][$index])) continue;
                else 
                {
                    if($formElementFieldPost['mandatory'][$index]==1)
                    {    
                        if($Validation->isEmpty($formElementFieldPost['message_error'][$index])) continue;
                    }
                }
                
                if(!$this->isPanel($formElementFieldPost['panel_id'][$index],$panelDictionary)) continue;

                $serviceTypeIdEnable=preg_split('/\./',$formElementFieldPost['service_type_id_enable_hidden'][$index]);
                if(is_array($serviceTypeIdEnable))
                {
                    foreach($serviceTypeIdEnable as $index2=>$value2)
                    {
                        if(!$ServiceType->isServiceType($value2))
                            unset($serviceTypeIdEnable[$index2]);
                    }
                }

                if(!is_array($serviceTypeIdEnable)) $serviceTypeIdEnable=array();
                
                if($Validation->isEmpty($value))
                    $value=CHBSHelper::createId();
                
                $formElementField[]=array('id'=>$value,'label'=>$formElementFieldPost['label'][$index],'mandatory'=>$formElementFieldPost['mandatory'][$index],'message_error'=>$formElementFieldPost['message_error'][$index],'panel_id'=>$formElementFieldPost['panel_id'][$index],'service_type_id_enable'=>$serviceTypeIdEnable);
            }
        }  
        
        CHBSPostMeta::updatePostMeta($bookingFormId,'form_element_field',$formElementField); 
        
        /***/
        
		$formElementAgreement=array();
        $formElementAgreementPost=CHBSHelper::getPostValue('form_element_agreement');        
        
        if(isset($formElementAgreementPost['id']))
        {
            $Validation=new CHBSValidation();
            
            foreach($formElementAgreementPost['id'] as $index=>$value)
            {
                if(!isset($formElementAgreementPost['text'][$index])) continue;
                if($Validation->isEmpty($formElementAgreementPost['text'][$index])) continue;
                if(!$Validation->isBool($formElementAgreementPost['mandatory'][$index])) continue;
                
                if($Validation->isEmpty($value))
                    $value=CHBSHelper::createId();
                
                $formElementAgreement[]=array('id'=>$value,'text'=>$formElementAgreementPost['text'][$index],'mandatory'=>$formElementAgreementPost['mandatory'][$index]);
            }
        }        
        
        CHBSPostMeta::updatePostMeta($bookingFormId,'form_element_agreement',$formElementAgreement);        
    }
    
    /**************************************************************************/
    
    function getPanel($meta)
    {
        $panel=array
        (
            array
            (
                'id'                                                            =>  1,
                'label'                                                         =>  __('[Contact details]','chauffeur-booking-system')
            ),
            array
            (
                'id'                                                            =>  2,
                'label'                                                         =>  __('[Billing address]','chauffeur-booking-system')                
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
    
    function isPanel($panelId,$panelDictionary)
    {
        foreach($panelDictionary as $value)
        {
            if($value['id']==$panelId) return(true);
        }
        
        return(false);
    }
    
    /**************************************************************************/
    
    function createField($panelId,$serviceTypeId,$meta)
    {
        $html=array(null,null);
        
        if(!array_key_exists('form_element_field',$meta)) return(null);
        
        foreach($meta['form_element_field'] as $value)
        {
            if($value['panel_id']==$panelId)
            {
                if(array_key_exists('service_type_id_enable',$value))
                {
                    if(is_array($value['service_type_id_enable']))
                    {
                        if(!in_array($serviceTypeId,$value['service_type_id_enable']))
                            continue;
                    }
                }
                
                $name='form_element_field_'.$value['id'];
                
                $html[1].=
                '
                    <div class="chbs-clear-fix">
                        <div class="chbs-form-field chbs-form-field-width-100">
                            <label>'.esc_html($value['label']).'</label>
                            <input type="text" name="'.CHBSHelper::getFormName($name,false).'"  value="'.esc_attr(CHBSHelper::getPostValue($name)).'"/>
                        </div>                        
                    </div>
                ';
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
                            <div class="chbs-clear-fix">
                                <label class="chbs-form-label-group">'.esc_html($value['label']).'</label> 
                            </div>
                        ';
                    }
                }
            }
        }
        
        return($html[0].$html[1]);
    }
    
    /**************************************************************************/
    
    function createAgreement($meta)
    {
        $html=null;
        $Validation=new CHBSValidation();
        
        if(!array_key_exists('form_element_agreement',$meta)) return($html);
        
        foreach($meta['form_element_agreement'] as $value)
        {
            $html.=
            '
                <div class="chbs-clear-fix">
                    <span class="chbs-form-checkbox">
                        <span class="chbs-meta-icon-tick"></span>
                    </span>
                    <input type="hidden" name="'.CHBSHelper::getFormName('form_element_agreement_'.$value['id'],false).'" value="0"/> 
                    <div>'.$value['text'].'</div>
                </div>      
            ';
        }
        
        if($Validation->isNotEmpty($html))
        {
            $html=
            '
                <h4 class="chbs-agreement-header">'.esc_html__('Agreements','chauffeur-booking-system').'</h4>
                <div class="chbs-agreement">
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
        
        $Validation=new CHBSValidation();
        
        if(!array_key_exists('form_element_field',$meta)) return($error);
        
        foreach($meta['form_element_field'] as $value)
        {
            $name='form_element_field_'.$value['id'];
            
            if((int)$value['mandatory']===1)
            {
                if(array_key_exists($name,$data))
                {
                    if(array_key_exists('service_type_id_enable',$value))
                    {
                        if(is_array($value['service_type_id_enable']))
                        {
                            if(!in_array($data['service_type_id'],$value['service_type_id_enable']))
                                continue;
                        }
                    }
                    
                    if($value['panel_id']==2)
                    {
                        if((int)$data['client_billing_detail_enable']===1)
                        {
                            if($Validation->isEmpty($data[$name]))
                                $error[]=array('name'=>CHBSHelper::getFormName($name,false),'message_error'=>$value['message_error']);                            
                        }
                    }
                    else
                    {
                        if($Validation->isEmpty($data[$name]))
                            $error[]=array('name'=>CHBSHelper::getFormName($name,false),'message_error'=>$value['message_error']);
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
            if((!array_key_exists('mandatory',$value)) || ((int)$value['mandatory']===1))
            {
                if((int)$data[$name]!==1) return(true);
            }
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
        }
        
        CHBSPostMeta::updatePostMeta($bookingId,'form_element_panel',$meta['form_element_panel']);
        CHBSPostMeta::updatePostMeta($bookingId,'form_element_field',$meta['form_element_field']);
    }
    
    /**************************************************************************/
    
    function sendBookingAgreement($bookingId,$meta,$data)
    {
        if(!array_key_exists('form_element_agreement',$meta)) return;
        
        foreach($meta['form_element_agreement'] as $index=>$value)
        {
            $name='form_element_agreement_'.$value['id']; 
            $meta['form_element_agreement'][$index]['value']=$data[$name];
            $meta['form_element_agreement'][$index]['text']=$value['text'];
        }
        
        CHBSPostMeta::updatePostMeta($bookingId,'form_element_agreement',$meta['form_element_agreement']);
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
                if($type==1)
                {
                    $html.=
                    '
                        <div>
                            <span class="to-legend-field">'.esc_html($value['label']).'</span>
                            <div class="to-field-disabled">'.esc_html($value['value']).'</div>                                
                        </div>    
                    ';
                }
                elseif($type==2)
                {
                    $html.=
                    '
                        <tr>
                            <td '.$argument['style']['cell'][1].'>'.esc_html($value['label']).'</td>
                            <td '.$argument['style']['cell'][2].'>'.esc_html($value['value']).'</td>
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