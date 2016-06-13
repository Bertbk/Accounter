<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['errors']) && !empty($_SESSION['errors']))
{
	?>
	<h2>Errors</h2>
	<div id="errors">
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
	<?php
	unset($_SESSION['errors']);
}
?>

