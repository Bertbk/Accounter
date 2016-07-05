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
 Admin page to delete/manage every accounts
 */
 
require_once __DIR__.'/../config-app.php';

include_once(LIBPATH.'/accounts/get_accounts.php');
$accounts = get_accounts();

?>

<!DOCTYPE html>

<html>
<head>
	<title>Administration</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/index.css'?>">
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

			<h1>Table of the accounts</h1>
			<div class="row">
				<div class="hidden-xs hidden-sm hidden-md col-lg-2 ">
					<strong>Title</strong>
				</div>
				<div class="hidden-xs hidden-sm hidden-md col-lg-1 ">
					<strong>Author</strong>
				</div>
				<div class="hidden-xs hidden-sm hidden-md col-lg-2 ">
					<strong>Email</strong>
				</div>
				<div class="hidden-xs hidden-sm hidden-md col-lg-2 ">
					<strong>Description</strong>
				</div>
				<div class="hidden-xs hidden-sm hidden-md col-lg-1 ">
					<strong>Expiration date</strong>
				</div>
				<div class="hidden-xs hidden-sm col-md-2 col-lg-1 ">
					<strong>Admin Link</strong>
				</div>
				<div class="hidden-xs hidden-sm col-md-2 col-lg-1 ">
					<strong>Delete</strong>
				</div>
			</div>
		<?php 
		$cpt_account = -1;
		$evenly = "even";
		foreach ($accounts as $account)
		{
			$cpt_account ++;
			$evenly = ($evenly == "even")?"uneven":"even";
		?>
			<div class="<?php 'account_'.$cpt_account?>">
				<div class="row <?php echo 'row_'.$evenly?>">
					<div class="col-lg-2">
						<?php echo htmlspecialchars($account['title'])?>	
					</div>
					<div class="hidden-xs hidden-sm hidden-md col-lg-1 <?php echo 'admin_table_collapse_'.$cpt_account?>">
						<?php echo htmlspecialchars($account['author'])?>	
					</div>
					<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'admin_table_collapse_'.$cpt_account?>">
						<?php echo htmlspecialchars($account['email'])?>	
					</div>
					<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'admin_table_collapse_'.$cpt_account?>">
						<?php echo htmlspecialchars($account['description'])?>	
					</div>
					<div class="hidden-xs hidden-sm hidden-md col-lg-1 <?php echo 'admin_table_collapse_'.$cpt_account?>">
						<?php echo htmlspecialchars($account['date_of_expiration'])?>	
					</div>
					<div class="col-md-2 col-lg-1">
						<a href="<?php echo BASEURL.'/account/'.$account['hashid_admin'].'/admin'?>"><span class="glyphicon glyphicon-link"></span></a>	
					</div>
					<div class="col-md-2 col-lg-1">
						<span class="glyphicon glyphicon-trash"></span>
					</div>
					
			<?php //Collapse button (for mobile>) ?>
					<div class="visible-xs visible-sm col-xs-2">
						<button type="submit" class="btn btn-default"
							data-toggle="collapse" data-target=".<?php echo 'admin_table_collapse_'.$cpt_account?>">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
				</div>
			</div>
<?php } ?>
		</div>
	</div> <!-- content -->
</body>
</html>