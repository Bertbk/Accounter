<?php
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/hashid/validate_hashid.php');

function send_email_new_account($account_hashid_arg)
{
	$configs = include(SITEPATH.'/config.php');

	$account_hashid = $account_hashid_arg;
	if(validate_hashid($account_hashid) == false)
		{ return false;}
	
	$account = get_account($account_hashid);
	
	if(empty($account))
	{
		return false;
	}
		
	$dest_email = filter_var($account['email'], FILTER_SANITIZE_EMAIL);
	$dest_email = filter_var($dest_email, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if($dest_email == false)
	{
		return false;
	}
		
	if(!isset($account['title']))
		{return false;}
	if(!isset($account['hashid']))
		{if(!preg_match("^[a-z0-9]{16}$", $account['hashid']))
			{ return false;}
		}
	if(!isset($account['hashid_admin']))
		{if(validate_hashid_admin($account['hashid_admin'])== false)
			{ return false;}
		}
		
	$html_array['title'] = htmlspecialchars($account['title']);			
	$txt_array['title'] = $account['title'];
	
	//send email
	//filter according to some servers
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $dest_email))
	{
		$br = "\r\n";
	}
	else
	{
		$br = "\n";
	}
	
	//=====txt and html text
	$part_link  = BASEURL.'/account/'.$account['hashid'];
	$admin_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
	//txt
	$message_txt = "Your Account ".$txt_array['title']." has been created:".$br;
	$message_txt = $message_txt."  Participant link         : ".$part_link.$br;
	$message_txt = $message_txt."  Admin link (DO NOT SHARE): ".$admin_link.$br;
	//html
	$message_html = '<html><head></head><body>';
	$message_html = $message_html.'<p>Your Account '.$html_array['title'].' has been created:</p>';
	$message_html = $message_html.'<ul>';
	$message_html = $message_html."<li>Participant link         : <a href='".$part_link."'>".$part_link."</a></li>";
	$message_html = $message_html."<li>Admin link (DO NOT SHARE): <a href='".$admin_link."'>".$admin_link."</a></li>";
	$message_html = $message_html.'</ul>';
	$message_html = $message_html.'</body></html>';
 
	//=====Boundary creation
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Object
	$topic = "[Accounter] Links to your account";
	//=========
	 
	//=====Header of the email
	$header = "From: \"Accounter\"".$configs['email'].$br;
	$header.= "Reply-to: \"Accounter\"".$configs['email'].br;
	$header.= "MIME-Version: 1.0".$br;
	$header.= "Content-Type: multipart/alternative;".$br." boundary=\"$boundary\"".$br;
	//==========
	 
	//=====Message creation
	$message = $br."--".$boundary.$br;
	//=====Adding the text version
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$br;
	$message.= "Content-Transfer-Encoding: 8bit".$br;
	$message.= $br.$message_txt.$br;
	//==========
	$message.= $br."--".$boundary.$br;
	//=====Adding the html version
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$br;
	$message.= "Content-Transfer-Encoding: 8bit".$br;
	$message.= $br.$message_html.$br;
	//==========
	$message.= $br."--".$boundary."--".$br;
	$message.= $br."--".$boundary."--".$br;
	//==========
	 
	//=====Sending email
	

if(@mail($dest_email, $topic, $message, $header))
{
 return true;
}else{
 return false;
}
}
