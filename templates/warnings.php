<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['warnings']) && !empty($_SESSION['warnings']))
{
	?>
	<div id="warnings">
	<h2>Warnings</h2>
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

