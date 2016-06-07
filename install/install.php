<?php 
include_once(__DIR__.'/create_config_file.php');
include_once(__DIR__.'/test_db.php');


if(isset($_POST['submit_install']))
{
	$host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$passwd = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$passwd = is_null($passwd)?"":$passwd;
	$dbname = filter_input(INPUT_POST, 'dbname', FILTER_SANITIZE_STRING);
	$db_ok = test_db($host, $username, $passwd, $dbname);
	if($db_ok)
	{
		$prefix = filter_input(INPUT_POST, 'prefix', FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$current_url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$base_url = substr($current_url , 0, strlen($current_url) - strlen('/install/install.php'));
		
		$config_created = create_config_file($host, $username, $passwd, $dbname, $prefix, $base_url, $email);
		if($config_created)
		{
			include_once(__DIR__.'/create_tables.php');
			header('location: ../clean_install.php');
		}
	}
}

?>

<html>
<head>

</head>

<body>

<h1>Installation</h1>
<div>
<form method="post">
<div>
		<label for="input_host">
		Host
		</label>		
		<input type="text" name="host" 
		id="input_host" class="input_name" required />
</div>
<div>
		<label for="input_username">
		Username
		</label>		
		<input type="text" name="username" 
		id="input_username" class="input_name" required />
</div>
<div>
		<label for="input_password">
		Password
		</label>		
		<input type="text" name="password" 
		id="input_password" class="input_name" />
</div>
<div>
		<label for="input_dbname">
		Database name
		</label>		
		<input type="text" name="dbname" 
		id="input_dbname" class="input_name" required />
</div>
<div>
		<label for="input_email">
		Email address (retrieve accounts, etc.)
		</label>
		<input type="email" name="email" 
		id="input_email" class="input_name" required/>
</div>

<div>
		<label for="input_prefix">
		Prefix for table (default = cpter_)
		</label>
		<input type="text" name="prefix" 
		id="input_prefix" class="input_name" value='cpter_'/>
</div>
<button type="submit" name="submit_install" value="Submit">Submit</button></div>
</form>
</div>
</body>

</html>