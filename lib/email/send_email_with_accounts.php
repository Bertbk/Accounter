<?php
require_once __DIR__.'/../../config-app.php';

function send_email_with_accounts($email_arg, $arrayOfAccounts_arg)
{
	$configs = include(SITEPATH.'/config.php');

	$dest_email = htmlspecialchars($email_arg);
	$dest_email = filter_var($dest_email, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if(!$dest_email)
	{
		return false;
	}

	
	if(is_null($arrayOfAccounts_arg) || empty($arrayOfAccounts_arg))
	{return false;}
	$filtered_array = array(array());
	foreach($arrayOfAccounts_arg as $key => $account)
	{
		if(!isset($account['title']))
			{return false;}
		if(!isset($account['hashid']))
			{if(!preg_match("^[a-z0-9]{16}$", $account['hashid']))
				{ return false;}
			}
		if(!isset($account['hashid_admin']))
			{if(!preg_match("^[a-z0-9]{32}$", $account['hashid_admin']))
				{ return false;}
			}
		$filtered_array[$key]['title'] = filter_var(htmlspecialchars($account['title']), FILTER_SANITIZE_STRING);
		$filtered_array[$key]['hashid'] = filter_var(htmlspecialchars($account['hashid']), FILTER_SANITIZE_STRING);
		$filtered_array[$key]['hashid_admin'] = filter_var(htmlspecialchars($account['hashid_admin']), FILTER_SANITIZE_STRING);
	}
	
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
	$message_txt = "Please find here the list of your accounts :".$br;
	foreach($filtered_array as $account)
	{
		$part_link  = BASEURL.'/account/'.$account['hashid'];
		$admin_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
		$message_txt = $message_txt.'- '.$account['title'].' :'.$br;
		$message_txt = $message_txt."  Participant link: ".$part_link.$br;
		$message_txt = $message_txt."  Admin link      : ".$admin_link.$br;
	}

	$message_html = '<html><head></head><body>';
	$message_html = $message_html.'<p>Please find here the list of your accounts:</p>';
	$message_html = $message_html.'<ul>';
	foreach($filtered_array as $account)
	{
		$message_html = $message_html.'<li>'.$account['title'].':';
		$part_link  = BASEURL.'/account/'.$account['hashid'];
		$admin_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
		$message_html = $message_html.'<ul>';
		$message_html = $message_html."<li>Participant link: <a href='".$part_link."'>".$part_link."</a></li>";
		$message_html = $message_html."<li>Admin link      : <a href='".$admin_link."'>".$admin_link."</a></li>";
		$message_html = $message_html.'</ul>';
		$message_html = $message_html.'</li>';
	}	
	$message_html = $message_html.'</ul>';
	$message_html = $message_html.'</body></html>';
 
	//=====Boundary creation
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Object
	$topic = "[Accounter] Link to your accounts";
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
