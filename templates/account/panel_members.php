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
Template to display the members
 */
?>

<?php //Overlay setting
if($admin_mode 
&& $edit_mode == 'member')
{
	$overlay="highlight";
}
else{
	$overlay = "";
}
?>

<div id="members" class="panel panel-primary <?php echo $overlay?>">
	<div class="panel-heading cursor_pointer overflowhidden"
		data-toggle="collapse" data-target="#panel-body_members">
		<button class="btn btn-default floatright" title="Collapse/Expand"
			data-toggle="collapse" data-target="#panel-body_members">
			<span class="glyphicon glyphicon-minus"></span>
		</button>
		<h2>Members: <?php echo (int)$n_members ?> (<?php echo (int)$n_people ?>)</h2>
	</div>
	<div class="panel-body panel-collapse collapse in" id="panel-body_members">
<?php if (is_array($my_members) && sizeof($my_members) > 0 ) 
	{
		$member_to_edit = false;
?>
		<div id="div_members">
<?php
		foreach($my_members as $member)
		{
			if($admin_mode 
			&& $edit_mode == 'member' 
			&& $member['hashid'] == $edit_hashid)
			{
				$member_to_edit = $member;
				continue;
			}
?>
			<div class="row row_member">
				<?php
				//Check if the name takes all the width or not (edit/delete button ?)
				if(!($admin_mode && !$edit_mode))
				{	?>
				<div class="col-xs-12">
					<div class="fullwidth padding_member display_member" style="background-color:<?php echo '#'.$member['color']?>">
			<?php	echo htmlspecialchars($member['name']).' ('.(int)$member['nb_of_people'].')';?>
					</div>
				</div>
<?php		}else{	
				$link_tmp = $link_to_account_admin.'/edit/member/'.$member['hashid'].'#edit_tag_'.$member['hashid'];
?>
				<div class="col-xs-7">
					<div class="fullwidth padding_member display_member" style="background-color:<?php echo '#'.$member['color']?>">	
	<?php 		echo htmlspecialchars($member['name']).' ('.(int)$member['nb_of_people'].')';	?>
					</div>
				</div>
				<div class="col-xs-2">
					<form action="<?php echo $link_tmp?>">
							<button type="submit" value="" class="btn btn-default" title="Edit member">
									<span class="glyphicon glyphicon-pencil"></span>
							</button>
					</form>
				</div>
				<div class="col-xs-2">
					<form method="post" 
						class="deleteicon"
						action="<?php echo ACTIONPATH.'/members/delete_member.php'?>">
						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
						<input type="hidden" name="p_hashid_member" value="<?php echo $member['hashid']?>"/>
						<button type="submit" class="btn btn-default confirmation" 
							name="submit_delete_member" value="Submit" title="Delete member">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</form>
				</div>
<?php }//if/else admin
?>
			</div>
<?php }//Foreach
?>
		
<?php if($member_to_edit !== false){ 
					//EDIT A member
?>
			<h3 id="<?php echo 'edit_tag_'.$edit_hashid?>">Edit <?php echo htmlspecialchars($member_to_edit['name'])?></h3>
			<form method="post"
				action="<?php echo ACTIONPATH.'/members/update_member.php'?>"
				role="form">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_member" value="<?php echo $member_to_edit['hashid']?>">
				<div class="row form-group row-no-padding">
					<div class="col-xs-9">
						<input type="text" name="p_name_of_member" class="form-control"
							value="<?php echo htmlspecialchars($member_to_edit['name'])?>" required
							title="Name of member">
					</div>
					<div class="col-xs-3">
						<input type="number" name="p_nb_of_people" class="form-control"
							min="1" step="1" value="<?php echo (int)$member_to_edit['nb_of_people']?>" required
							title="Number of people">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-6">
						<button type="submit" name="submit_update_member" value="Submit"
							title="Submit changes" class="btn btn-primary">
							Submit changes
						</button>
					</div>
					<div class="col-xs-6">
						<button type="submit" name="submit_cancel" value="#members"
						form="form_cancel" title="Cancel" class="btn btn-primary">
							Cancel
						</button> 
					</div>
				</div>
			</form>
<?php 
			$member_to_edit = false;
			} ?>
		</div>
<?php 
	}//if !empty(members)
//==================================
	//Admin only: Add a member
if($admin_mode && $edit_mode === false)
{ ?>
		<div id="div_add_member">
<!-- Add member-->
			<form method="post" 
				action="<?php echo ACTIONPATH.'/members/new_member.php'?>">
				<fieldset>
					<legend id="show_hide_add_member"
						class="cursorpointer">
						Add a member
					</legend>
					<div id="show_hide_add_member_target" 
						class="hidden_at_first">
						<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
						<input type="hidden" name="p_hashid_account" 
							value="<?php echo $my_account['hashid_admin']?>">
						<div class="row">
							<p class="col-xs-10 xs-offset-1">
								Name<span class="glyphicon glyphicon-asterisk red"></span> / Number of people<span class="glyphicon glyphicon-asterisk red"></span>
							</p>
						</div>
						<?php //the "inner_member_form" is used to add row with JS
						?>
						<div id="inner_member_form">
							<div class="row form-group row-no-padding">
								<div class="col-xs-9">
									<label for="form_set_member_name_0" class="sr-only">
										Name
									</label>
									<input type="text" name="p_new_member[0][p_name]" 
										id="form_set_member_name_0" class="form-control" 
										placeholder="Name" title="Name" required>
								</div>
								<div class="col-xs-3">
									<label for="form_set_member_nbpeople_0" class="sr-only">
										Nb. of people
									</label>
									<div class="input-group">
										<input type="number" name="p_new_member[0][p_nb_of_people]" value="1" 
										id="form_set_member_nbpeople_0" class="form-control"
										step="1" min="1"
										title="Number of people" required>
									</div>
								</div>
							</div>
						</div>
<!--						<p><a href="#" onclick="AddmemberLine();return false;">(+) Add a row</a></p>		 -->
						<button type="submit" name="submit_new_member" class="btn btn-primary" 
							value="Submit" title="Submit new member">
							Submit
						</button>
						<button type="button" class="btn btn-primary" 
							value="" title="Add a row" onclick="AddMemberLine();return false;">
							Add a row
						</button>
					</div>
				</fieldset>
			</form>
		</div>
<?php } //admin mode
?>
	</div>
</div>
