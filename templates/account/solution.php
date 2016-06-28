
<!-- SOLUTION -->
<?php if (isset($solution) && is_array($solution) && sizeof($solution) > 0 )
{
?>
<div class="row">
	<div id="solutions" class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading cursor_pointer" 
				data-toggle="collapse" data-target="#panel-body_solution">
				<h2>Solution</h2>
			</div>
	
			<div id="panel-body_solution" class="panel-collapse collapse in">
				<div class="panel-body">
					<div class="row">
						<div id="basic_solution" class="solution col-md-6">
							<h3>Basic solution</h3>
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
										<?php echo $refund?>&euro; to 
									</div>
								</div>
								<div class="col-xs-4 col-md-4 col-lg-4">
									<div class="display_bill_participant padding_bill_participant fullwidth" style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo htmlspecialchars($receiver['name'])?></div>
								</div>
							</div>
<?php					}
						}
					} ?>
						</div>
						<div id="opt_solution" class="solution col-md-6">
							<h3>&ldquo;Optimal&rdquo; solution</h3>
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
										<?php echo $refund?>&euro; to 
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
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
} //if there is a solution
?>