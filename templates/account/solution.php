
<!-- SOLUTION -->
<?php if (isset($solution) && is_array($solution) && sizeof($solution) > 0 )
{
?>
<div class="row">
<div id="solutions" class="col-lg-8 col-lg-offset-2">
<div class="panel panel-primary">

  <div class="panel-heading">
		<h2>Solution</h2>
	</div>
	
	<div class="panel-body">
		<div id="basic_solution" class="solution col-md-6">
			<h3>Basic solution</h3>
			<ul>
<?php
	foreach($my_participants as $payer)
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
					<li>
						<span class='bill_participant' style="background-color:<?php echo '#'.$payer['color']?>"><?php echo htmlspecialchars($payer['name'])?></span> 
						must refund <?php echo $refund?> &euro; to 
						<span class='bill_participant' style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo htmlspecialchars($receiver['name'])?></span>
					</li>
<?php
			}
		}
	}
?>
			</ul>
		</div>
		<div id="opt_solution" class="solution col-md-6">
			<h3>&ldquo;Optimal&rdquo; solution</h3>
			<ul>
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
					<li>
						<span class='bill_participant' style="background-color:<?php echo '#'.$payer['color']?>"><?php echo htmlspecialchars($payer['name'])?></span> 
						must refund <?php echo $refund?> &euro; to 
						<span class='bill_participant' style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo htmlspecialchars($receiver['name'])?></span>
					</li>
<?php
			}
		}
	}
?>
			</ul>
		</div>
	</div>
</div>
</div>
</div>
<?php
} //if there is a solution
?>