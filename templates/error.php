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
Template of the error page if no config file exists.
 */
 ?>
 <!DOCTYPE html>

<html>
<head>
<title>Error</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
</head>
<body>

	<div id="content">
		<div class="container">
			<div class="row">
				<header>
					<?php include(__DIR__.'/header/header.php'); ?>
				</header>
			</div>
	<?php include(__DIR__.'/messages/messages.php');?>

			
				<h1>Problem</h1>
				<p>Config file has not been found. Accounter might not have been installed first.</p>

		</div>
	</div> <!-- content -->
</body>
</html>