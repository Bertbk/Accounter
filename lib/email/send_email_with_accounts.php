<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
/*

Send an email with every associated accounts (title, links).
*/

require_once __DIR__.'/../../config-app.php';
include_once(LIBPATH.'/hashid/validate_hashid.php');

function send_email_with_accounts($email_arg, $arrayOfAccounts_arg)
{
	$configs = include(SITEPATH.'/config.php');

	$dest_email = filter_var($email_arg, FILTER_SANITIZE_EMAIL);
	$dest_email = filter_var($dest_email, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if($dest_email === false)
	{
		return false;
	}

	
	if(is_null($arrayOfAccounts_arg) || empty($arrayOfAccounts_arg))
	{return false;}
	$html_array = array(array());
	$txt_array = array(array());
	
	foreach($arrayOfAccounts_arg as $key => $account)
	{
		if(!isset($account['title']))
			{return false;}
		if(!isset($account['hashid']))
			{return false;}
		if(!isset($account['hashid_admin']))
			{return false;}
		
		$hashid = $hashid_arg;
		if(validate_hashid($hashid)== false)
		{	return array();	}

		$hashid_admin = $hashid_admin_arg;
		if(validate_hashid_admin($hashid_admin)== false)
		{	return array();	}

		$html_array[$key]['title'] = htmlspecialchars($account['title']);
		$html_array[$key]['hashid'] = $hashid;
		$html_array[$key]['hashid_admin'] = $hashid_admin;

		$txt_array[$key]['title'] = htmlspecialchars($account['title']);
		$txt_array[$key]['hashid'] = $hashid;
		$txt_array[$key]['hashid_admin'] = $hashid_admin;
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
	foreach($txt_array as $txt_msg)
	{
		$part_link  = BASEURL.'/account/'.$txt_msg['hashid'];
		$admin_link = BASEURL.'/account/'.$txt_msg['hashid_admin'].'/admin';
		$message_txt = $message_txt.'- '.$txt_msg['title'].' :'.$br;
		$message_txt = $message_txt."  Participant link: ".$part_link.$br;
		$message_txt = $message_txt."  Admin link      : ".$admin_link.$br;
	}

	$message_html = '<html><head></head><body>';
	$message_html = $message_html.'<p>Please find here the list of your accounts:</p>';
	$message_html = $message_html.'<ul>';
	foreach($html_array as $html_msg)
	{
		$message_html = $message_html.'<li>'.$html_msg['title'].':';
		$part_link  = BASEURL.'/account/'.$html_msg['hashid'];
		$admin_link = BASEURL.'/account/'.$html_msg['hashid_admin'].'/admin';
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
