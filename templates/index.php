<!DOCTYPE html>

<html>
<head>
<title>Accounter</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/index.css'?>">
</head>
<body>

<div id="content">
<header>
<?php include(__DIR__.'/header/header.php'); ?>
</header>


<nav>
<div id="create_account">
<a href="<?php echo BASEURL.'/create.php'?>">
<p><span>$ Create a new account</span></p>
</a>
</div>


<div id="retrieve_account">
<a href="<?php echo BASEURL.'/retrieve_accounts.php'?>">
<p><span><img src="<?php echo BASEURL.'/img/loupe_white.png'?>" alt="search icon"> Retrieve your accounts</span></p>
</a>
</div>

</nav>


<h1>Table of the accounts</h1>

<table style="width:100%" border="1">
 <tr>
<td> ID </td>
<td> hashid </td> 
<td> hashid_admin </td>
<td> Title </td> 
<td> Email </td>
  </tr>
<?php 
foreach ($accounts as $account)
{
?>
	<tr>
    <td><?php echo $account['id']?></td>
    <td> <a href="account/<?php echo $account['hashid']?>"><?php echo $account['hashid']?></a></td>
    <td> <a href="account/<?php echo $account['hashid_admin']?>/admin"><?php echo $account['hashid_admin']?></a></td>
    <td><?php echo htmlspecialchars($account['title'])?></td>
    <td><?php echo htmlspecialchars($account['email'])?></td>
	  </tr>
<?php
}
?>
</table>

</div> <!-- content -->
</body>
</html>