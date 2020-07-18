<?php

/******************************************************************************/
/******************************************************************************/

class CRBSUser
{
	/**************************************************************************/
	
    function isSignIn()
    {
        $user=$this->getCurrentUserData();
        return((int)$user->id===0 ? false : true);
    }
    
    /**************************************************************************/
    
    function signOut()
    {
        
    }
    
    /**************************************************************************/
    
    function signIn($login,$password)
    {
        if($this->isSignIn()) $this->signOut();
   
        $credentials=array
        (
            'user_login'        =>  $login,
            'user_password'     =>  $password,
            'remember'          =>  true
        );
 
        $user=wp_signon($credentials,true);

        if(is_wp_error($user)) return(false);
        
        wp_set_current_user($user->ID);
        
        return(true);
    }
    
    /**************************************************************************/
    
    function getCurrentUserData()
    {
        return(wp_get_current_user());
    }
    
    /**************************************************************************/
    
    function validateCreateUser($email,$login,$password1,$password2)
    {
        $result=array();
        
        $Validation=new CRBSValidation();
        
        if(!$Validation->isEmailAddress($email)) $result[]='EMAIL_INVALID';
        else
        {
            if(email_exists($email)) $result[]='EMAIL_EXISTS';
        }
        
        if($Validation->isEmpty($login)) $result[]='LOGIN_INVALID';
        else
        {
            if(username_exists($login)) $result[]='LOGIN_EXISTS';                
        }
        
        if($Validation->isEmpty($password1)) $result[]='PASSWORD1_INVALID';
        if($Validation->isEmpty($password2)) $result[]='PASSWORD2_INVALID';
        
        if((!in_array('PASSWORD1_INVALID',$result)) && (!in_array('PASSWORD2_INVALID',$result)))
        {
            if(strcmp($password1,$password2)!==0)
                $result[]='PASSWORD_MISMATCH'; 
        }

        return($result);
    }
    
    /**************************************************************************/
    
    function createUser($email,$login,$password)
    {
        $data=array
        (
            'user_login'                                                        =>  $login,
            'user_pass'                                                         =>  $password,
            'user_email'                                                        =>  $email,
            'role'                                                              =>  'customer'
        );
        
        return(wp_insert_user($data));
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/