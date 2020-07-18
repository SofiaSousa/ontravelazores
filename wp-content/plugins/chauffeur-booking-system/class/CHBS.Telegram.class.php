<?php

/******************************************************************************/
/******************************************************************************/

class CHBSTelegram
{
    /**************************************************************************/
    
    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function sendMessage($token,$groupId,$message)
    {
		$url=add_query_arg(array('chat_id'=>'-'.$groupId,'text'=>urlencode($message)),'https://api.telegram.org/bot'.$token.'/sendMessage');

        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        $response=curl_exec($ch);
        
        $LogManager=new CHBSLogManager();
        $LogManager->add('telegram',1,print_r(json_decode($response),true));        
        
        curl_close($ch);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/