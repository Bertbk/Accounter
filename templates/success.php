<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['success']) && !empty($_SESSION['success']))
{
	?>
	<div id="success">
	<h2>Success</h2>
	<ul>
	<?php
	foreach ($_SESSION['success'] as $suc)
	{
		?>
<li>
<?php echo htmlspecialchars($suc)?>
</li>		
		<?php
	}
	?>
	</ul>
</div>	
	<?php
	unset($_SESSION['success']);
}
?>


