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

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['warnings']) && !empty($_SESSION['warnings']))
{
	?>
	<h2>Warnings</h2>
	<div id="warnings">
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
	<?php
	unset($_SESSION['warnings']);
}
?>

