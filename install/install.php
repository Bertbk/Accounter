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
Install webpage.
After the form has been properly filled:
- create the config file
- create the SQL tables
- erase the install dir
*/

$current_url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$base_url = substr($current_url , 0, strlen($current_url) - strlen('/install/install.php'));
if ( !defined('BASEURL') )
	define('BASEURL', $base_url);


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_host' => 'Please provide an host',
		'p_username' => 'Please provide an sql username',
		'p_password' => 'Please provide the sql password',
		'p_dbname' => 'Please provide a database name',
		'p_prefix' => 'Please provide a prefix',
		'p_contact_email' => 'Please provide a contact email'
 );
 
$ErrorMessage = array(
	'p_host' => 'Host is not valid',
	'p_username' => 'Username is not valid',
	'p_password' => 'Password is not valid',
	'p_dbname' => 'Databasename is not valid',
	'p_prefix' => 'prefix is not valid',
	'p_contact_email' => 'Email address is not valid'
 );

if(isset($_POST['submit_install']))
{
	//Manual treatments of arguments
	//HOST
	$key = 'p_host';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$host = $_POST[$key];
	}
	
	//USERNAME
	$key = 'p_username';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$username = $_POST[$key];
	}
		
	//PASSWORD
	$passwd = $_POST[$key];
	
	//DBNAME
	$key = 'p_dbname';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$dbname = $_POST[$key];
	}
		
	//PREFIX
	$key = 'p_prefix';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$prefix = $_POST[$key];
	}
	
	//CONTACT EMAIL
	$key = 'p_contact_email';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$contact_email = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
		$contact_email = filter_var($contact_email, FILTER_VALIDATE_EMAIL);
		if($contact_email == false)
		{ //If not validate
			array_push($errArray, $ErrorMessage[$key]);
		}			
	}

	//ADMIN USERNAME
	$key = 'p_admin_username';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$admin_username = $_POST[$key];
	}

	//ADMIN PASSWORD
	$key = 'p_admin_passwd';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$admin_passwd_clear = $_POST[$key];
		$admin_passwd = crypt($admin_passwd_clear, base64_encode($admin_passwd_clear));
		$admin_passwd_clear = "";
	}

	//TEST DB
	if(empty($errArray))
	{
		include_once(__DIR__.'/test_db.php');
		$db_ok = test_db($host, $username, $passwd, $dbname);
		if($db_ok == false)
		{
			array_push($errArray, 'Cannot connect to Database.');
		}
	}
	
	//Create config file
	if(empty($errArray))
	{
		include_once(__DIR__.'/create_config_file.php');
		$config_created = create_config_file($host, $username, $passwd, $dbname, $prefix, $base_url, $email);
		if($config_created == false)
		{
			array_push($errArray, 'Cannot create config file');
		}
		else{
			array_push($successArray, 'Config file created');
		}
	}

	//Create Admin file
	if(empty($errArray))
	{
		include_once(__DIR__.'/create_admin_htaccess.php');
		$admin_page_created = create_admin_htaccess($admin_username, $admin_passwd);
		if($admin_page_created == false)
		{
			array_push($errArray, 'Cannot create admin protection');
		}
		else{
			array_push($successArray, 'Admin page secured');
		}
	}

	//Create tables
	if(empty($errArray))
	{
		include_once(__DIR__.'/create_tables.php');
		$table_created = create_tables();
		if(empty($table_created))
		{
			array_push($successArray, 'Tables created');
		}
		else
		{
			array_push($errArray, $table_created);
		}
	}
	
	if(!(empty($errArray)))
	{
		$_SESSION['errors'] = $errArray;
	}
	if(!(empty($warnArray)))
	{
		$_SESSION['warnings'] = $warnArray;
	}
	if(!(empty($successArray)))
	{
		$_SESSION['success'] = $successArray;
	}


	//Clean
	if(empty($errArray))
	{
		include_once(__DIR__.'/clean.php');
	}
}
?>


<!DOCTYPE html>

<html>
<head>
<title>Install</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo $base_url.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo $base_url.'/css/global.css'?>">
</head>

<body>


<div id="content">
		<div class="container">
			<div class="row">
				<header>
					<?php include(__DIR__.'/../templates/header/header.php'); ?>
				</header>
			</div>
	<?php include(__DIR__.'/../templates/messages/messages.php');?>

			<h1>Install Accounter</h1>

			<form method="post">
				<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
				<fieldset>
					<legend>SQL database</legend>
					<div class="form-group">
						<label for="input_host">Host<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_host" id="input_host" required
							class="form-control" placeholder="Host">
					</div>
					<div class="form-group">
						<label for="input_username">Username<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_username" id="input_username" required class="form-control"
							placeholder="Username">
					</div>
					<div class="form-group">
						<label for="input_password">Password (can be empty)<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="password" name="p_password" id="input_password" required class="form-control"
							placeholder="Password" value="">
					</div>
					<div class="form-group">
						<label for="input_dbname">Database name<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_dbname" id="input_dbname" required class="form-control"
							placeholder="Database name">
					</div>
					<div class="form-group">
						<label for="input_prefix">Prefix for table (default = cpter_)<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_prefix" id="input_prefix" required class="form-control"
							placeholder="Database name" value="cpter_">
					</div>
				</fieldset>					
				<fieldset>
					<legend>Administration</legend>
					<div class="form-group">
						<label for="input_email">Email address of the server (send email, ...)<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="email" name="p_contact_email" id="input_email" required class="form-control"
							placeholder="Email address">
					</div>

					<div class="form-group">
						<label for="input_admin_username">Admin username<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_admin_username" id="input_admin_username" required class="form-control"
							placeholder="Username">
					</div>

					<div class="form-group">
						<label for="input_admin_passwd">Admin password<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="password" name="p_admin_passwd" id="input_admin_passwd" required class="form-control"
							placeholder="Password to admin page">
					</div>

					<button type="submit" name="submit_install" value="Submit"
						class="btn btn-primary">
						Submit
					</button> 
				</fieldset>
			</form>
		</div>
	</div> <!-- content -->
</body>

</html>