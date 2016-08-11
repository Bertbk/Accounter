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
Template to display the solutions
 */
?>

<!-- SOLUTION -->
<div class="row">
	<div id="solutions" class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading cursor_pointer" 
				data-toggle="collapse" data-target="#panel-body_solution">
				<h2>Solution</h2>
				<button class="btn btn-default floatright" title="Collapse/Expand"
					data-toggle="collapse" data-target="#panel-body_solution">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
			</div>
			<div id="panel-body_solution" class="panel-collapse collapse in">
				<div class="panel-body">
					<div class="row">
						<div id="opt_solution" class="solution col-md-12">
			<?php if($n_transfer_opt == 0)
			{?>
							<p>No transfer needed: everything is fine!</p>
<?php }
	else{?>
							<div class="row list_solution">
								<div class="col-xs-offset-1 col-xs-5 col-md-offset-2 col-md-4 text-center">
									...must pay...
								</div>
								<div class="col-xs-5 col-md-4 text-center">
									...to...
								</div>
							</div>
<?php
	foreach($tranfers as $transfer)
					{
			?>
							<div class="row list_solution">
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_member padding_member fullwidth" style="background-color:<?php echo '#'.$transfer['payer_color']?>"><?php echo htmlspecialchars($transfer['payer_name'])?></div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="padding_member fullwidth">
										<?php echo $transfer['amount']?>&euro;
									</div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_member padding_member fullwidth" style="background-color:<?php echo '#'.$transfer['receiver_color']?>"><?php echo htmlspecialchars($transfer['receiver_name'])?></div>
								</div>
							</div>
<?php
					}
				}
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
