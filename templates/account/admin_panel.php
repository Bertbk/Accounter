
<?php if($admin_mode)
{
?>

<div class="row">
<div id="explain_admin" class="col-lg-8 col-lg-offset-2">
<div class="panel panel-danger">

<div id="admin_panel_heading" class="panel-heading cursor_pointer" 
data-toggle="collapse" data-target="#admin_panel_body">
<h2>Administration mode</h2>
</div>
	<div id="admin_panel_body" class="panel-collapse collapse">
	<div class="panel-body">
<p>Welcome to the admin page of the account. You should first add participants to the account and create one (or more) bill(s). The participants can then be added to the bill, with a percentage of participation. For example, if the bill represents a car rental for 4 days and a participant only use it for 3 days, his/her percent should be set to 75% ( = 3/4). A zero percent participation is used when a person paid for something but didn't used it at all.</p>
<p>Each entry can be edited using the <span class="glyphicon glyphicon-pencil"></span> icon or deleted using the <span class="glyphicon glyphicon-trash"></span> icon.
</p>
</div>
<?php if( $edit_mode !== false)
{
	?>
  <div class="panel-footer">
	<h3>Edit mode</h3>
<p>An entry is currently being under edition. It can be canceled :</p>
<form method="post">
	<button type="submit" name="submit_cancel" 
	class="btn btn-default" value="Submit">Cancel edit mode</button>
	</form>
</div>
<?php } //edit ?>

</div>
</div>
</div>
</div>

<?php } //admin?>