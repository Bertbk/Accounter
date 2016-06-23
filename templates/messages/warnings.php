<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['warnings']) && !empty($_SESSION['warnings']))
{
	?>
	<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
	<div class="panel panel-warning">
	<div class="panel-heading">
	<h2>Warnings</h2>
	</div>
	<div class="panel-body">
	<ul>
	<?php
	foreach ($_SESSION['warnings'] as $warn)
	{
		?>
<li>
<?php echo htmlspecialchars($warn)?>
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
	unset($_SESSION['warnings']);
}
?>

