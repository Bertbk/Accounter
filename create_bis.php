<?php
//$hashid = base_convert(microtime(false), 10, 36);
$hashid = base_convert(microtime(false), 10, 36);
$hashid_b10 = base_convert($hashid, 36, 10);

$name_of_account = filter_input(INPUT_POST, 'name_of_account', FILTER_SANITIZE_STRING);
$contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
//$hashid = filter_input(INPUT_POST, 'hashid', FILTER_SANITIZE_STRING);


// on se connecte à MySQL 
try
{
$db = new PDO('mysql:host=localhost;dbname=dividethebill;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['new_account']))
{
	try{
	$isok = $db->exec('INSERT INTO accounts(id, hashid, nom, email) VALUES(NULL, '.$hashid_b10.', '.$name_of_account.','.$contact_emails.')');
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}
	if($isok)
	{
	 echo '<p> Ajouté dans la BDD </p>';
	}
	 else
	 {
	 echo '<p>Already in the db</p>';		 
	 }
}



header('Location: DivideTheBill/account/'.$hashid);
?>
