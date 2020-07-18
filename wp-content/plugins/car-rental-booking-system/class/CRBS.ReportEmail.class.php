<?php

/******************************************************************************/
/******************************************************************************/

class CRBSReportEmail
{
	/**************************************************************************/

	function __construct()
	{
	
	}

	/**************************************************************************/

    function send()
    {
        /***/
        
        global $crbs_phpmailer;
        
        $Email=new CRBSEmail();
        $Validation=new CRBSValidation();
        $EmailAccount=new CRBSEmailAccount();
        		
        if((int)CRBSOption::getOption('email_report_status')!=1) return(false);
       		
        if(($emailAccount=$EmailAccount->getDictionary(array('email_account_id'=>CRBSOption::getOption('email_report_sender_email_account_id'))))===false) return(false);
		
        $emailRecipient=preg_split('/;/',CRBSOption::getOption('email_report_recipient_email_address'));
        foreach($emailRecipient as $index=>$value)
        {
            if(!$Validation->isEmailAddress($value,false))
              unset($emailRecipient[$index]);
        }
        if(!count($emailRecipient)) return(false);
        
        $dictionary=$this->getBooking();
        if(!count($dictionary)) return(false);
		
        $emailAccount=$emailAccount[CRBSOption::getOption('email_report_sender_email_account_id')];
        
        $crbs_phpmailer['sender_name']=$emailAccount['meta']['sender_name'];
        $crbs_phpmailer['sender_email_address']=$emailAccount['meta']['sender_email_address'];
        
        $crbs_phpmailer['smtp_auth_enable']=$emailAccount['meta']['smtp_auth_enable'];
        $crbs_phpmailer['smtp_auth_debug_enable']=$emailAccount['meta']['smtp_auth_debug_enable'];
        
        $crbs_phpmailer['smtp_auth_username']=$emailAccount['meta']['smtp_auth_username'];
        $crbs_phpmailer['smtp_auth_password']=$emailAccount['meta']['smtp_auth_password'];
        
        $crbs_phpmailer['smtp_auth_host']=$emailAccount['meta']['smtp_auth_host'];
        $crbs_phpmailer['smtp_auth_port']=$emailAccount['meta']['smtp_auth_port'];
        
        $crbs_phpmailer['smtp_auth_secure_connection_type']=$emailAccount['meta']['smtp_auth_secure_connection_type'];
                
        /***/

        $data['style']=$Email->getEmailStyle();
        $data['booking']=$dictionary;

        /***/
		
        $Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'email_report.php');
        $body=$Template->output();
		
        /***/
        
        $recipient=preg_split('/;/',CRBSOption::getOption('email_report_recipient_email_address'));
        $Email->send($recipient,__('Car Rental Booking System Report','car-rental-booking-system'),$body);
    }
    
    /**************************************************************************/
    
    function getBooking()
    {
		global $post;
		
		$dictionary=array();
		$Booking=new CRBSBooking();
        
		CRBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	CRBSBooking::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'asc','title'=>'asc'),
            'suppress_filters'                                                  =>  true,
            'meta_query'                                                        =>  array
            (
                'relation'                                                      =>  'OR',
                array
                (
                    'key'                                                       =>  PLUGIN_CRBS_CONTEXT.'_pickup_date',
                    'value'                                                     =>  date('d-m-Y'),
                    'compare'                                                   =>  '='
                ),
                
                array
                (
                    'key'                                                       =>  PLUGIN_CRBS_CONTEXT.'_return_date',
                    'value'                                                     =>  date('d-m-Y'),
                    'compare'                                                   =>  '='
                )
            )           
		);

		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
            if(is_null($post)) continue;

            $dictionary[$post->ID]=$Booking->getBooking($post->ID);
		}

		CRBSHelper::preservePost($post,$bPost,0);	
        
        return($dictionary);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/