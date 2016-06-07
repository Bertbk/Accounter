<!DOCTYPE html>

<html>
<head>
</head>
<body>
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
    <td> <a href="account/<?php echo $account['hashid']?>"><?php echo $account['hashid']?></</a></td>
    <td> <a href="account/<?php echo $account['hashid_admin']?>/admin"><?php echo $account['hashid_admin']?></</a></td>
    <td><?php echo $account['title']?></</td>
    <td><?php echo $account['email']?></td>
	  </tr>
<?php
}
?>
</table>

<h1>Menu</h1>
<ul><li>
<a href=<?php echo BASEURL.'/create.php'?>>Create a new account</a></li>

<li>
<a href=<?php echo BASEURL.'/retrieve_accounts.php'?>>Retrieve your accounts</a></li>
</ul>
</body>
</html>