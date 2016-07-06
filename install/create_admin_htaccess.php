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
Create the .htaccess and .htpasswd for the admin page
*/

function create_admin_htaccess($admin_username_arg, $admin_passwd_arg)
{
	$admin_username = $admin_username_arg;
	$admin_passwd = $admin_passwd_arg;
	
	$admin_path = dirname(__FILE__);
	$admin_path = substr($admin_path , 0, strlen($admin_path) - strlen('/install/create_admin_htaccess.php'));
	$htaccess_path = $admin_path.'/admin/.htaccess';
	$htpasswd_path = $admin_path.'/admin/.htpasswd';
	
	$myfile = fopen($htaccess_path, "w") or die();
	$txt = "AuthType Basic";
	fwrite($myfile, $txt);
	$txt = "AuthName \"Administration page.\"";
	fwrite($myfile, $txt);
	$txt = 'AuthUserFile '.$htpasswd_path;
	fwrite($myfile, $txt);
	$txt = "Require valid-user";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	$myfile = fopen($htpasswd_path, "w") or die();
	$txt = $admin_username.':'$admin_passwd.'\n';
	fwrite($myfile, $txt);
	fclose($myfile);
	
	return true;
}