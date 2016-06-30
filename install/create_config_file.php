<?php

function create_config_file($host_arg, $username_arg, $password_arg, $dbname_arg,
														$prefix_arg, $baseurl_arg, $email_arg)
{
	$host = $host_arg;
	$username = $username_arg;
	$password = $password_arg;
	$dbname = $dbname_arg;
	$prefix = $prefix_arg;
	$baseurl = $baseurl_arg;
	$email = $email_arg;
		
	try
	{
	$query_db = 'mysql:host='.$host.'; dbname='.$dbname.'; charset=utf8';
	$db = new PDO($query_db, $username, $password);
	}
	catch (Exception $e)
	{
			die();
			return false;
	}
	
	
	$myfile = fopen(__DIR__."/../site/config.php", "w") or die();
	$txt = "<?php \nreturn array(\n";
	fwrite($myfile, $txt);
	$txt = "'host' => '".$host."',\n";
	fwrite($myfile, $txt);
	$txt = "'username' => '".$username."',\n";
	fwrite($myfile, $txt);
	$txt = "'password' => '".$password."',\n";
	fwrite($myfile, $txt);
	$txt = "'dbname' => '".$dbname."',\n";
	fwrite($myfile, $txt);
	$txt = "'prefix_table' => '".$prefix."',\n";
	fwrite($myfile, $txt);
	$txt = "'baseurl' => '".$baseurl."',\n";
	fwrite($myfile, $txt);
	$txt = "'email	' => '".$email."'\n";
	fwrite($myfile, $txt);	
	$txt = ");\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	//Empty file
	$myfile = fopen(__DIR__."/../site/index.html", "w") or die();
	$txt = "";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	return true;
}