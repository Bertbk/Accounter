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
				<h2>Solutions</h2>
				<button class="btn btn-default floatright" title="Collapse/Expand"
					data-toggle="collapse" data-target="#panel-body_solution">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
			</div>
			<div id="panel-body_solution" class="panel-collapse collapse in">
				<div class="panel-body">
					<div class="row">
						<div id="basic_solution" class="solution col-md-6">
							<h3>&ldquo;Standard&rdquo; solution</h3>
		<?php if($n_transfer == 0)
			{?>
							<p>No transfer</p>
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
<?php	foreach($my_participants as $payer)
				{
					$uid = $payer['id'];
					if(!isset($solution[$uid])){continue;}
					foreach($my_participants as $receiver)
					{
						$vid = $receiver['id'];
						if(!isset($solution[$uid][$vid])){continue;}
						$refund = number_format((float)$solution[$uid][$vid], 2, '.', '');
						if($refund > 0)
						{
?>
							<div class="row list_solution">
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_bill_participant padding_bill_participant fullwidth" style="background-color:<?php echo '#'.$payer['color']?>"><?php echo htmlspecialchars($payer['name'])?></div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="padding_bill_participant fullwidth">
										<?php echo $refund?>&euro;
									</div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_bill_participant padding_bill_participant fullwidth" style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo htmlspecialchars($receiver['name'])?></div>
								</div>
							</div>
<?php					}
						}
					} ?>
	<?php }?>
						</div>
						<div id="opt_solution" class="solution col-md-6">
							<h3>&ldquo;Optimized&rdquo; solution</h3>
			<?php if($n_transfer_opt == 0)
			{?>
							<p>No transfer</p>
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
	foreach($my_participants as $payer)
					{
						$uid = $payer['id'];
						if(!isset($solution[$uid])){continue;}
						foreach($my_participants as $receiver)
						{
							$vid = $receiver['id'];
							if(!isset($solution_opt[$uid][$vid])){continue;}
											$refund = number_format((float)$solution_opt[$uid][$vid], 2, '.', '');
							if($refund > 0)
							{
?>
							<div class="row list_solution">
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_bill_participant padding_bill_participant fullwidth" style="background-color:<?php echo '#'.$payer['color']?>"><?php echo htmlspecialchars($payer['name'])?></div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="padding_bill_participant fullwidth">
										<?php echo $refund?>&euro;
									</div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_bill_participant padding_bill_participant fullwidth" style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo htmlspecialchars($receiver['name'])?></div>
								</div>
							</div>
<?php
							}
						}
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
