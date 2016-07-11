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
Template to display account parameters: email, description, etc.
When the admin mode is engaged, the panel is red.
 */
?>

<?php //Overlay setting
if($admin_mode 
&& $edit_mode == 'account')
{
	$overlay="highlight";
}
else{
	$overlay = "";
}
?>

<div class="row" id="description_panel">
	<div class="col-xs-12">
	<?php if($admin_mode == true){	?>
		<div class="panel panel-danger <?php echo $overlay?>">
		
		<?php if($edit_mode === 'account' 
			&& $edit_hashid === $my_account['hashid']){?>
		<form method="post"	action="<?php echo ACTIONPATH.'/update_account.php'?>" role="form"
		id="form_edit_account">
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
		</form>
		<?php } ?>
	<?php	}else{?>
		<div class="panel panel-default">
	<?php }?>
			<div class="panel-heading <?php if($overlay==""){echo 'cursorpointer';}?>" 
				<?php if($overlay==""){echo 'data-toggle="collapse" data-target="#account_description_panel"'; }?>>
				<div class="row">
					<div class="col-md-9">
						<h2>
						<?php if($admin_mode == true
								&& $edit_mode === "account" 
								&& $edit_hashid === $my_account['hashid']){?>
								<label for="form_edit_account_name" class="sr-only">Title: </label>							
								<input type="text" name="p_title_of_account" id="form_edit_account_name"
								class="form-control"	value="<?php echo htmlspecialchars($my_account['title'])?>" required
								form="form_edit_account">
								<?php } else{?>
								<?php echo htmlspecialchars($my_account['title'])?>
								<?php }?>
						</h2>
					</div>
					<div class="col-md-3">
		<?php
					if($admin_mode && $edit_mode === false)
					{
						$link_tmp = $link_to_account_admin.'/edit/account/'.$my_account['hashid'].'#edit_tag_'.$my_account['hashid'];
		?>
						<div class="button_account_title">
						
							<button type="submit" class="btn btn-danger dropdown-toggle" 
								data-toggle="dropdown" title="Delete...">
								<span class="glyphicon glyphicon-trash"></span>
								<span class="sr-only">Remove</span>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_participants.php'?>">
										<input type="hidden" name="p_hashid_account" 
											value="<?php echo $my_account['hashid_admin']?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_participants" onclick="event.stopPropagation();">
											Remove all participants
										</button>
									</form>
								</li>
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_bills.php'?>">
										<input type="hidden" name="p_hashid_account" 
											value="<?php echo $my_account['hashid_admin']?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_bills" onclick="event.stopPropagation();">
											Remove all bills
										</button>
									</form>
								</li>
								<li class="li_margin_top">
									<form method="post" action="<?php echo ACTIONPATH.'/delete_account.php'?>">
										<input type="hidden" name="p_hashid_account" 
											value="<?php echo $my_account['hashid_admin']?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_delete_account" onclick="event.stopPropagation();">
											Delete the entire account
										</button>
									</form>
								</li>
							</ul>
						</div>
						<div class="button_account_title">
							<form action="<?php echo $link_tmp?>">
									<button type="submit" value="" class="btn btn-default" 
										title="Edit the account" onclick="event.stopPropagation();">
											<span class="glyphicon glyphicon-pencil"></span>
									</button>
							</form>
						</div>
					<?php } ?>
					<?php if($overlay==""){ ?>
						<button class="btn btn-default floatright" title="Collapse/Expand"
							data-toggle="collapse" data-target="#account_description_panel">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="panel-collapse collapse in" id="account_description_panel">			
				<div class="panel-body">			
					<div class="row">
						<div class="col-xs-12 col-md-4 form-group">
							<?php if($admin_mode == true
									&& $edit_mode === "account" 
									&& $edit_hashid === $my_account['hashid']){?>
								<label class="control-label" for="form_edit_account_author">Author</label> 
								<input class="form-control" id="form_edit_account_author" value="<?php echo htmlspecialchars($my_account['author'])?>" placeholder="Author" name="p_author" required form="form_edit_account" type="text" title="Author">
							<?php }else{?>
								<label class="control-label">Author</label>
								<pre class="form-control-static"><?php echo htmlspecialchars($my_account['author'])?></pre>												
							<?php }?>
						</div>
						
						<div class="col-xs-12 col-md-8 form-group">
							<?php if($admin_mode == true
								&& $edit_mode === "account" 
								&& $edit_hashid === $my_account['hashid'])
								{?>
								<label class="control-label" for="form_edit_account_description">Description</label> 
								<input class="form-control" id="form_edit_account_description" value="<?php echo htmlspecialchars($description_account)?>" placeholder="Description" type="text" form="form_edit_account" name="p_description" title="Description">
							<?php }else {?>
							<label class="control-label">Description</label> 
							<pre class="form-control-static"><?php echo htmlspecialchars($description_account)?></pre>												
							<?php }?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-4 form-group">
							<?php if($admin_mode){?>
								<?php if($edit_mode === "account" 
											&& $edit_hashid === $my_account['hashid']){?>
									<label class="control-label" for="form_edit_account_email">Author's email</label> 
									<input class="form-control" id="form_edit_account_email" value="<?php echo htmlspecialchars($my_account['email'])?>" 
									name="p_contact_email" required form="form_edit_account"  type="email" placeholder="Email address" title="Email address"> 
								<?php }else{?>
									<label class="control-label">Author's email</label> 
									<pre class="form-control-static"><?php echo htmlspecialchars($my_account['email'])?></pre>												
								<?php }?>
							<?php } ?>
						</div>
						<div class="col-xs-12 col-md-4 form-group">
							<label class="control-label">Date of creation</label> 
							<pre class="form-control-static"><?php echo htmlspecialchars($account_date_of_creation)?></pre>								
						</div>
						<div class="col-xs-12 col-md-4 form-group">
							<?php if($admin_mode == true
								&& $edit_mode === "account" 
								&& $edit_hashid === $my_account['hashid'])
								{?>
								<label class="control-label" for="form_edit_account_date_of_expiration">Date of expiration</label> 
								<input class="form-control date_zindex" id="form_edit_account_date_of_expiration" 
								value="<?php echo htmlspecialchars($account_date_of_expiration)?>" 
								form="form_edit_account" name="p_date_of_expiration" type="text" required
								title="Date of expiration">
							<?php }else {?>
							<label class="control-label">Date of expiration</label> 
							<pre class="form-control-static"><?php echo htmlspecialchars($account_date_of_expiration)?></pre>												
							<?php }?>
						</div>
					</div>
				
					<div class="row">
						<div class="col-xs-12 col-md-6 form-group">
							<label for="account_public_link"><a href="<?php echo $link_to_account?>">Public link to the account <span class="btn-link glyphicon glyphicon-link"></span></a></label> 
							<input class="form-control" readonly="readonly" value="<?php echo $link_to_account?>" onclick="select();"
								type="text" id="account_public_link" title="Public link">
						</div>
					<?php if($admin_mode){?>
						<div class="col-xs-12 col-md-6 form-group">
							<label for="account_admin_link"><a href="<?php echo $link_to_account_admin?>">Administrator link to the account <span class="btn-link glyphicon glyphicon-link"></span></a></label> 
							<input class="form-control" readonly="readonly" value="<?php echo $link_to_account_admin?>" onclick="select();"
								type="text" id="account_admin_link"  title="Administrator link">							
						</div>
							<?php } ?>
					</div>
				</div>
			<?php if($admin_mode == true){ ?>
				<div class="panel-footer <?php if($edit_mode == true && $overlay == ""){echo 'highlight';}?>">
				<?php if($edit_mode === "account" 
				&& $edit_hashid === $my_account['hashid']){?>
					<button type="submit" name="submit_update_account" value="Submit"
					 class="btn btn-primary" form="form_edit_account" title="Submit changes">
						Submit changes
					</button> 
					<button type="submit" name="submit_cancel" value="" 
						class="btn btn-primary" form="form_cancel" title="Cancel">
					 Cancel
					</button>
				<?php } elseif($edit_mode == true){?>
						<p>An entry is currently being under edition. It can be canceled :</p>
						<form method="post">
							<button type="submit" name="submit_cancel" 
								class="btn btn-default" value=""
								form="form_cancel" title="Cancel">
								Cancel edit mode
							</button>
						</form>
		<?php }else{ ?>
				<p>Welcome to the admin page of the account!</p>
						<p>You should first add participants to the account and create one (or more) bill(s). Participants can then be added to the bills with a percentage of financial participation. For example, if the bill represents a car rental for 4 days and a participant only use it for 3 days, his/her percent should be set to 75% ( = 3/4). A zero percent participation is used when a person paid for something but didn't used it at all.</p>
						<p>Each entry can be edited using the <span class="glyphicon glyphicon-pencil"></span> icon or deleted using the <span class="glyphicon glyphicon-trash"></span> icon.
						</p>
			<?php } ?>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
