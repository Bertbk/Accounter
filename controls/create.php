<?php
require_once __DIR__.'/../config-app.php';
include_once(LIBPATH.'/accounts/create_new_account.php');
include_once(LIBPATH.'/email/send_email_new_account.php');
include_once(LIBPATH.'/hashid/create_hashid.php');

$create_success = false;
$errArray = array(); //error messages

if(isset($_POST['submit']))
{
	$required_fields = array(
	'p_title_of_account' => FILTER_SANITIZE_STRING,
	'p_contact_email' => FILTER_VALIDATE_EMAIL
	);
	
	$opt_fields = array(
	'p_description' => FILTER_SANITIZE_STRING
	);
	
	$ErrorMessage = array(
		'p_title_of_account' => 'Title is not valid',
		'p_contact_email' => 'Email address is not valid',
		'p_description' => 'Description is not valid'
   );
	
	$result = filter_input_array(INPUT_POST, array_merge($required_fields, $opt_fields));
	if(!is_null($result))
	{
		//Sanitize email
		if(!empty($result['p_contact_email']) 
			&& !is_null($result['p_contact_email']))
			{$result['p_contact_email'] = filter_var($result['p_contact_email'], FILTER_SANITIZE_EMAIL);}
		//Sanitize description
		if(empty($result['p_description']))
			{$result['p_description'] = null;}
		
		//Check the REQUIERED data
		foreach($required_fields as $key => $val) { 
        if(empty($_POST[$key])) { //If empty
					array_push($errArray, 'Please file the ' . $key . ' field');
        }
        elseif($result[$key] === false) { //If not valid.
            array_push($errArray, $ErrorMessage[$key]);
        }
    }
		//Check the OPTIONNAL data
		foreach($opt_fields as $key => $val) { 
        if(!empty($result[$key]) && $result[$key] === false) { //If not valid.
            array_push($errArray, $ErrorMessage[$key]);
        }
    }
		
		//Data have been filtered.
		if(empty($errArray))
		{
			$hashid = create_hashid();
			$hashid_admin = create_hashid();
			if(is_null($hashid) ||is_null($hashid_admin))
			{
        array_push($errArray, 'Server error: problem while creating hashid.');
			}
			else
			{
				$hashid_admin = $hashid.$hashid_admin;
				
				$title_of_account = $result['p_title_of_account'];
				$contact_email = $result['p_contact_email'];
				$description = $result['p_description'];
				
				$create_success = create_new_account($hashid, $hashid_admin, $title_of_account, $contact_email, $description);
				if(!$create_success)
				{
					array_push($errArray, 'Problem while creating account. Please try again');
				}
				else{
					$email_sent = send_email_new_account($hashid);
					unset($_POST);
					$redirect_to_account_created = 'Location:'.BASEURL.'/account_created.php?hash='.$hashid.'&hash_admin='.$hashid_admin;
					$header_str = $redirect_to_account_created;
					header($header_str);
				}
			}
		}
	}
}

include_once(ABSPATH.'/templates/create.php');
