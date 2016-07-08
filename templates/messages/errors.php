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
	Display error messages stored in the _Session 
 */
 

if(isset($_SESSION['errors']) && !empty($_SESSION['errors']))
{
	?>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h2>Errors</h2>
			</div>
			<div class="panel-body">
				<ul>
	<?php
	foreach ($_SESSION['errors'] as $erro)
	{
		?>
					<li>
						<?php echo htmlspecialchars($erro)?>
					</li>		
		<?php
	}
	?>
				</ul>
			</div>	
		</div>	
	</div>	
</div>	
	<?php
	unset($_SESSION['errors']);
}
?>

