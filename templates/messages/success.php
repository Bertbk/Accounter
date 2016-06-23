<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['success']) && !empty($_SESSION['success']))
{
	?>
	<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
	<div class="panel panel-success">
	<div class="panel-heading">
	<h2>Success</h2>
	</div>
	<div class="panel-body">
	<ul>
	<?php
	foreach ($_SESSION['success'] as $succ)
	{
		?>
<li>
<?php echo htmlspecialchars($succ)?>
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
	unset($_SESSION['success']);
}
?>

