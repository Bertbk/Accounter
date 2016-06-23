<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['errors']) && !empty($_SESSION['errors']))
{
	?>
	<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
	<div class="panel panel-alert">
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

