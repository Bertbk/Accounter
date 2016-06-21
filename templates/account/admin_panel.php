
<?php if($admin_mode)
{
?>
<div id="explain_admin">
<h2>Administration panel</h2>
	<div>
<p>Welcome to the admin page of the account. You should first add participants to the account and create one (or more) bill(s). The participan can then be added to the bill, with a percentage of participation. For example, a full-time participant is 100%, if the bill represents a car rental for 4 days and a participant only use it for 3 days, it should be set to 75% (=3/4).</p>
<p>Each entry can be edited using the <img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit icon' class="editicon" > icon or deleted using the <img src="<?php echo BASEURL.'/img/delete.png'?>" alt='Delete icon' class="deleteicon" > icon.
</p>
</div>
</div>

<?php if( $edit_mode !== false)
{
	?>
<div id="edit_mode_admin">
<h2>Edit mode</h2>
<div>
<p>An entry is currently being under edition. It can be canceled :</p>
<form method="post"><button type="submit" name="submit_cancel" value="Submit">Cancel</button></form>
</div>
</div>
<?php } //edit ?>
<?php } //admin?>