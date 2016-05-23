<!DOCTYPE html>

<html>

<body>


<?php 
// on se connecte à MySQL 
try
{
$db = new PDO('mysql:host=localhost;dbname=dividethebill;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['submit_sha']))
{
	$sha_to_sql = htmlspecialchars($_POST['sha_to_sql']);
  	 echo '<p> coucou : '.$sha_to_sql.'</p>';
	 
}
?>

</body>
</html>