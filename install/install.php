<?php 
include_once(__DIR__.'/create_config_file.php');
include_once(__DIR__.'/test_db.php');
include_once(__DIR__.'/create_tables.php');

$current_url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$base_url = substr($current_url , 0, strlen($current_url) - strlen('/install/install.php'));

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
	
	//TEST DB
	if(empty($errArray))
	{
		$db_ok = test_db($host, $username, $passwd, $dbname);
		if($db_ok == false)
		{
			array_push($errArray, 'Cannot connect to Database.');
		}
	}

	//Create tables
	if(empty($errArray))
	{
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
	
	//Create config file
	if(empty($errArray))
	{
		$config_created = create_config_file($host, $username, $passwd, $dbname, $prefix, $base_url, $email);
		if($config_created == false)
		{
			array_push($errArray, 'Cannot create config file');
		}
		else{
			array_push($successArray, 'Config file created');
		}
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
				<fieldset>
					<legend class="sr-only">Install Accounter</legend>
					<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
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
					
					<div class="form-group">
						<label for="input_email">Email address (retrieve accounts, etc.)<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="email" name="p_contact_email" id="input_email" required class="form-control"
							placeholder="Email address">
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