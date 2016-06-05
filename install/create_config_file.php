
<?php

function create_config_file($host_arg, $username_arg, $password_arg, $dbname_arg,
														$prefix_arg, $baseurl_arg)
{
	$host = htmlspecialchars($host_arg);
	$username = htmlspecialchars($username_arg);
	$password = htmlspecialchars($password_arg);
	$dbname = htmlspecialchars($dbname_arg);
	$prefix = htmlspecialchars($prefix_arg);
	$baseurl = htmlspecialchars($baseurl_arg);
	
	try
	{
	$query_db = 'mysql:host='.$host.'; dbname='.$dbname.'; charset=utf8';
	$db = new PDO($query_db, $username, $password);
	}
	catch (Exception $e)
	{
			die('Fail to connect : ' . $e->getMessage());
			return false;
	}
	
	
	$myfile = fopen("../site/config.php", "w") or die("Unable to open config file!");
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
	$txt = "'baseurl' => '".$baseurl."'\n";
	fwrite($myfile, $txt);
	$txt = ");\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	//Empty file
	$myfile = fopen("../site/index.html", "w") or die("Unable to open config file!");
	$txt = "";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	return true;
}