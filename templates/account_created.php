<!DOCTYPE html>

<html>
<head>
<title>Accounts has been created</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
</head>
<body>


<div id="content">
<header>
<?php include(__DIR__.'/header/header.php'); ?>
</header>

<h1>Account created</h1>
<ul>
<li>Contributor link  : <a href="<?php echo $link_contrib?>"><?php echo $link_contrib?> </a></li>
<li>Administrator link: <a href="<?php echo $link_admin?>"><?php echo $link_admin?> </a> </li>
</ul>

</div> <!-- content -->
</body>
</html>