<?php 
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
 /*
Panel to add a recept
 */
 ?>
 
<?php
//Admin only
if($admin_mode && $edit_mode == false)
{
?>
<form method="post"
	id="form_add_recept"
	action="<?php echo ACTIONPATH.'/new_recept.php'?>">
	<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>" >
	<input type="hidden" name="p_cpt_recept" value="<?php echo $n_recepts?>" >
</form>

<div id="add_recept">
	<div class="panel panel-primary">
		<div class="panel-heading cursorpointer" data-toggle="collapse" data-target="#form_add_recept_panel_body">
			<h2>Add a recept</h2>
			<button class="btn btn-default floatright" title="Collapse/Expand"
				data-toggle="collapse" data-target="#form_add_recept_panel_body">
				<span class="glyphicon glyphicon-plus"></span>
			</button>
		</div>
		<div class="panel-body panel-collapse collapse in" id="form_add_recept_panel_body">
			<fieldset>
				<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
				<div class="form-group">
					<label for="form_set_recept_name">Title<span class="glyphicon glyphicon-asterisk red"></span></label>
					<input type="text" name="p_title_of_recept" 
					id="form_set_recept_name" class="form-control" required 
					placeholder="Title" title="Title" form="form_add_recept">
				</div>
				<div class="form-group">
					<label for="form_set_recept_description">Description</label>
					 <textarea name="p_description" id="form_set_recept_description" class="form-control" 
					 placeholder="Description" title="Description" form="form_add_recept"></textarea>
				</div>

				 <button type="submit" name="submit_new_recept" value="Submit"
					class="btn btn-primary" title="Submit new recept" form="form_add_recept">
					Submit
				</button> 
			</fieldset>
		</div>
	</div>
</div>

<?php } //admin mode
?>
