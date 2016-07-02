
<?php 
/*
Hidden form to cancel any action in the account page.
*/
if($admin_mode
&& $edit_mode !== false)
{
	?>
	<form method="post" 
		action="<?php echo ACTIONPATH.'/cancel.php'?>"
		id="form_cancel">
	<input type="hidden" value="<?php echo $my_account['hashid_admin']?>"
		name="p_hashid_account">
	</form>
<?php 
}
?>