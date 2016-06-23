
<?php if($admin_mode)
{
?>


<?php if( $edit_mode !== false)
{
	?>
<div class="row">
<div id="edit_mode_admin" class="col-lg-8 col-lg-offset-2">
<div class="panel panel-info">
  <div class="panel-heading">
	<h2>Edit mode</h2>
	</div>
<div class="panel-body">
<p>An entry is currently being under edition. It can be canceled :</p>
<form method="post"><button type="submit" name="submit_cancel" value="Submit">Cancel</button></form>
</div>
</div>
</div>
</div>
<?php } //edit ?>

<div class="row">
<div id="explain_admin" class="col-lg-8 col-lg-offset-2">
<div class="panel panel-danger">
  <div class="panel-heading">
	<h2>Administration panel</h2>
	</div>
	<div class="panel-body">
<p>Welcome to the admin page of the account. You should first add participants to the account and create one (or more) bill(s). The participants can then be added to the bill, with a percentage of participation. For example, if the bill represents a car rental for 4 days and a participant only use it for 3 days, his/her percent should be set to 75% ( = 3/4). A zero percent participation is used when a person paid for something but didn't used it at all.</p>
<p>Each entry can be edited using the <img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit icon' class="editicon" > icon or deleted using the <img src="<?php echo BASEURL.'/img/delete.png'?>" alt='Delete icon' class="deleteicon" > icon.
</p>
</div></div>
</div>
</div>

<?php } //admin?>