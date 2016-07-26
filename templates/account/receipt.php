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
Template to display a receipt:
- The payer(s)
- The articles
-- Their recipients
 */
 ?>

<?php 
/*	if(!isset($cpt_spreadsheet)){$cpt_spreadsheet = -1;}
	$cpt_spreadsheet ++;
*/
?>

<?php 
// ========
//  PAYERS 
// ========

$this_rcpt_payers = array();
$this_possible_rcpt_payers = array();
if(isset($my_rcpt_payers[$spreadsheet['id']]))
{$this_rcpt_payers = $my_rcpt_payers[$spreadsheet['id']];}
if(isset($my_possible_rcpt_payers[$spreadsheet['id']]))
{	$this_possible_rcpt_payers = $my_possible_rcpt_payers[$spreadsheet['id']];}

?>
<h3 id="<?php echo 'receipt_payers_'.$cpt_spreadsheet?>">
	Payers (% of payment)
</h3>
<?php // Display the current payers of this receipt
if(!empty($this_rcpt_payers))
{
?>
	<div class="row">		
<?php
	$payer_to_edit = false; // if editing, place a button after the list
	$cpt_rcpt_recipient = -1;
	foreach($this_rcpt_payers as $key => $rcpt_payer)
	{
		$cpt_rcpt_recipient++;
		if($admin_mode === true
			&& $edit_mode === 'rcpt_payer' 
			&& $edit_hashid === $rcpt_payer['hashid'])
		{
			//We found the receipt_payer to be edited. Will be displayed after the other.
			$payer_to_edit = $key;
			continue;
		}
		?>
		<div class="col-xs-12 col-sm-6 col-lg-4 receipt_payer">
			<div class="floatleft width60 padding_member display_member" style="background-color:<?php echo '#'.$rcpt_payer['color']?>">
				<?php
					echo htmlspecialchars($rcpt_payer['name']).' ('.(float)$rcpt_payer['percent_of_payment'].'%)';
				?>
			</div>
			<?php
				if($admin_mode === true
				&& $edit_mode === false){
					$link_tmp = $link_to_account_admin.'/edit/rcpt_payer/'.$rcpt_payer['hashid'].'#edit_tag_'.$rcpt_payer['hashid'];
					?>
			<div class="zeromargin floatleft">
						<form action="<?php echo $link_tmp?>">
							<button type="submit" value="" class="btn btn-default" title="Edit participation">
									<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</form>
			</div>
			<div class="receipt_payer_button">
				<form method="post" 
				class="deleteicon"
				action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_payers/delete_rcpt_payer.php'?>">		
					<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
					<input type="hidden" name="p_hashid_rcpt_payer" value="<?php echo $rcpt_payer['hashid']?>">
					<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
					<button type="submit" class="btn btn-default confirmation" 
						name="submit_delete_rcpt_payer" title="Delete payer">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</form>
			</div>
	<?php	} ?>
		</div>
<?php
}//foreach participant in this receipt
?>
	</div> <?php //row ?>
<?php
	
	if($payer_to_edit !== false)
	{
		$rcpt_payer_tmp = $this_rcpt_payers[$payer_to_edit];
	//Edit activated on a receipt_payer of THIS receipt :
	?>
				<div class="highlight"  id="<?php echo 'edit_tag_'.$edit_hashid?>"
				style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
					<h3>Edit payer <?php echo htmlspecialchars($rcpt_payer_tmp['name']);?></h3>
					<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_payers/update_rcpt_payer.php'?>">

						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
						<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
						<input type="hidden" name="p_hashid_rcpt_payer" value="<?php echo $rcpt_payer_tmp['hashid']?>">
						<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">

						<div class="row form-group row-no-padding">
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="fullwidth padding_member display_member" style="background-color:<?php echo '#'.$rcpt_payer_tmp['color']?>">
									<?php echo htmlspecialchars($rcpt_payer_tmp['name']);?>
								</div>
							</div>
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="input-group">
									<input type="number" step="0.01" min="0" 
										max="<?php echo (float)100-(float)$my_percents_of_payments[$spreadsheet['id']] + (float)$rcpt_payer_tmp['percent_of_payment']?>"
										class="form-control" name="p_percent_of_payment"
										value="<?php echo (float)$rcpt_payer_tmp['percent_of_payment']?>" required>
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<button type="submit" name="submit_update_rcpt_payer" 
							value="Submit" class="btn btn-primary" title="Submit changes">
							Submit changes
						</button> 
						<button type="submit" name="submit_cancel" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" 
							form="form_cancel" class="btn btn-primary" title="Cancel">
						 Cancel
						</button>
					</form>
				</div>
<?php	
//reset temporary variables
$payer_to_edit=false;
$rcpt_payer_tmp=null;
	}
	?>
<?php }//if my_rcpt_payers != empty ?>

	<?php
if($admin_mode && !$edit_mode)
{ 
	if((float)$my_percents_of_payments[$spreadsheet['id']] >= 100)
	{
		?>
		<p>No payer can be added since the 100% are fulfilled.</p>
		<?php
	}
	else{
		//Assign a participant (if there are free guys)
		if(!empty($this_possible_rcpt_payers))
		{
		?>
						<form method="post"	enctype="multipart/form-data"
							action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_payers/new_rcpt_payer.php'?>">
							<fieldset>
								<legend id="<?php echo 'show_hide_receipt_add_payer_'.$cpt_spreadsheet?>"
									class="cursorpointer">
									(+) Add a payer
								</legend>
								<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
								<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
								<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
								<div class="hidden_at_first"
								id=<?php echo 'show_hide_receipt_add_payer_'.$cpt_spreadsheet.'_target'?>>

	<?php
				$cpt = -1;
				foreach($this_possible_rcpt_payers as $member)
				{
					$cpt++;
			?>
									<div class="row form-group assign_member">
										<div class="col-xs-12 col-md-6 col-lg-4 ">
											<div>
												<input type="checkbox" name="p_payer['<?php echo $cpt?>'][p_hashid_member]" 
													value="<?php echo $member['hashid']?>" title="Member"
													id="<?php echo'assign_payer_'.$cpt_spreadsheet.'_'.$cpt?>" >
												<div class="[ btn-group ] fullwidth" style="overflow:hidden">
													<label for="<?php echo 'assign_payer_'.$cpt_spreadsheet.'_'.$cpt?>"
														class="[ btn btn-default ] btn-assign_member">
														<span class="[ glyphicon glyphicon-ok ]"></span>
														<span> </span>
													</label>
													<span class="span-assign_member" >
														<label for="<?php echo 'assign_payer_'.$cpt_spreadsheet.'_'.$cpt?>" 
															class="[ btn btn-default active ] btn-assign_member2"
															style="background-color:<?php echo '#'.$member['color']?>">
																<?php echo htmlspecialchars($member['name'])?>
														</label>
													</span>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-md-6 col-lg-4">
											<label for="<?php echo 'form_available_quantity_'.$cpt_spreadsheet.'_'.$member['id']?>" 
												class="sr-only">
												Percentage of payment
											</label>
											<div class="input-group">
												<input name="p_payer['<?php echo $cpt?>'][p_percent_of_payment]" type="number"
															class="form-control" step="0.01" min="0" 
															max="<?php echo (float)100-(float)$my_percents_of_payments[$spreadsheet['id']]?>"	
															value="<?php echo (float)100-(float)$my_percents_of_payments[$spreadsheet['id']]?>" 
															id="<?php echo 'form_available_quantity_'.$cpt_spreadsheet.'_'.(int)$member['id']?>"
															title="Percentage of payment">
												<span class="input-group-addon">%</span>
											</div>
										</div>
									</div>
			<?php
					}//for each possible member
			?>
									<div class="row form-group assign_member">
										<div class="col-xs-6 col-md-4 col-lg-3 ">
											<div>
												<input type="checkbox" name="" 
													id="<?php echo'form_select_all_payers_'.$cpt_spreadsheet?>"
													onchange="SelectAll(this, '<?php echo "assign_payer_".$cpt_spreadsheet."_"?>')">
												<div class="[ btn-group ] fullwidth" style="overflow:hidden">
													<label for="<?php echo 'form_select_all_payers_'.$cpt_spreadsheet?>"
														class="[ btn btn-default ] btn-assign_member">
														<span class="[ glyphicon glyphicon-ok ]"></span>
														<span> </span>
													</label>
													<span class="span-assign_member" >
														<label for="<?php echo 'form_select_all_payers_'.$cpt_spreadsheet?>" 
															class="[ btn btn-default active ] btn-select_all">
																Select all
														</label>
													</span>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-md-6 col-lg-5 ">
											<div class="input-group">
												<span class="input-group-addon btn btn-default"
												onclick="SetAllPercent('<?php echo 'form_set_all_percent_'.$cpt_spreadsheet?>', '<?php echo $cpt_spreadsheet?>')">Set to all</span>
												<input name="" type="number"
													class="form-control"
													step="0.01" min="0" max="100"	value="100" 
													title="Percentage of usage"
													id="<?php echo 'form_set_all_percent_'.$cpt_spreadsheet?>">
												<span class="input-group-addon">%</span>
											</div>
										</div>
									</div>
									<div class="row form-group">
										<div class="col-xs-12">
											<button type="submit" name="submit_new_rcpt_payer" 
												value="Submit" class="btn btn-primary" title="Submit new participation">
												Submit
											</button>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
<?php
			} //if empty free_participants
		}//If percent <= 100%
	}//if admin
?>

<?php
// ==========
//  ARTICLES
// ==========
?>

					<h3>Articles</h3>
					<div class="row">
					<div id="articles_<?php echo $cpt_spreadsheet?>"
							class="list_articles overflowhidden col-xs-12">
<?php // List of the articles
	if(isset($my_articles[$spreadsheet['id']]) 
		&& is_array($my_articles[$spreadsheet['id']])
		&& count($my_articles[$spreadsheet['id']]) > 0)
	{
		$these_articles = $my_articles[$spreadsheet['id']];
		$cpt_article = -1;
	?>

	<?php
	
$article_to_edit = false; // if editing, place the article after the other
foreach($these_articles as $article)
{
	$cpt_article++;
	if($admin_mode && $edit_mode == 'rcpt_article' 
	&& $article['hashid'] == $edit_hashid)
	{ 
		$article_to_edit = $article;
		continue;
	}
?>
						<div class="row article_item">
							<div class="row text-center">
								<div class="col-xs-4 col-md-2">
									<strong>Product</strong>
								</div>
								<div class="col-xs-4 col-md-2">
									<strong>Quantity/Parts</strong>
								</div>
								<div class="col-xs-4 col-md-2">
									<strong>Total price</strong>
								</div>
						<?php if($admin_mode && !$edit_mode){?>
								<div class="hidden-xs hidden-sm col-xs-1">
									<strong>Edit</strong>
								</div>
								<div class="hidden-xs hidden-sm col-xs-1">
									<strong>Delete</strong>
								</div>
						<?php }?>
							</div>
							<div class="row text-center">
								<div class="col-xs-4 col-md-2">
									<?php echo htmlspecialchars($article['product'])?>
								</div>
								<div class="col-xs-4 col-md-2">
									<?php echo (float)$article['quantity']?>
								</div>
								<div class="col-xs-4 col-md-2">
									<?php echo (float)$article['price']?>&euro;
								</div>
				
			<?php //EDIT BUTTON
					if($admin_mode && !$edit_mode)
						{
			?>							
								<div class="col-xs-2 col-md-1">
				<?php 
				$link_tmp = $link_to_account_admin.'/edit/rcpt_article/'.$article['hashid'].'#edit_tag_'.$article['hashid'];
				?>
									<form action="<?php echo $link_tmp ?>">
										<button type="submit" class="btn btn-default" title="Edit article">
											<span class="glyphicon glyphicon-pencil"></span>
										</button>
									</form>
								</div>
								<div class="col-xs-2 col-md-1">
									<form method="post" 
										class="deleteicon"
										action="<?php echo ACTIONPATH.'/delete_article.php'?>"
											>
											<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
											<input type="hidden" name="p_hashid_article" value="<?php echo $article['hashid']?>">
											<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
											<button type="submit" class="btn btn-default confirmation" 
												name="submit_delete_article" title="Delete article">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</form>
								</div>
				<?php
					}//end if admin + non edit 
					?>
							</div>
							
							<div class="row">
							<?php 
							$this_rcpt_recipients = $my_rcpt_recipients[$spreadsheet['id']][$article['id']];
							$rcpt_recipient_to_edit = false;
							foreach($this_rcpt_recipients as $recipient)
							{
								if($admin_mode && $edit_mode === 'rcpt_recipient'
									&& $recipient['hashid'] === $edit_hashid)
								{ 
									$rcpt_recipient_to_edit = $recipient;
									continue;
								}

							?>
								<div class="col-xs-12 col-sm-6 col-lg-4 rcpt_recipient">
									<div class="floatleft width60 padding_member display_member" style="background-color:<?php echo '#'.$recipient['color']?>">
										<?php
											echo htmlspecialchars($recipient['name']).' ('.(float)$recipient['quantity'].')';
										?>
									</div>
									<?php
										if($admin_mode === true
										&& $edit_mode === false){
											$link_tmp = $link_to_account_admin.'/edit/rcpt_recipient/'.$recipient['hashid'].'#edit_tag_'.$recipient['hashid'];
											?>
									<div class="zeromargin floatleft">
												<form action="<?php echo $link_tmp?>">
													<button type="submit" value="" class="btn btn-default" title="Edit recipient">
															<span class="glyphicon glyphicon-pencil"></span>
													</button>
												</form>
									</div>
									<div class="receipt_payer_button">
										<form method="post" 
										class="deleteicon"
										action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_recipients/delete_rcpt_recipient.php'?>">		
											<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
											<input type="hidden" name="p_hashid_recipient" value="<?php echo $recipient['hashid']?>">
											<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
											<button type="submit" class="btn btn-default confirmation" 
												name="submit_delete_rcpt_recipient" title="Delete recipient">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</form>
									</div>
							<?php	} ?>
								</div>
					<?php	} ?>
							</div>
						
						<?php
	//Display recipient to edit (if exists)
	if($rcpt_recipient_to_edit !== false)
	{
	?>
						<div class="highlight" id="<?php echo 'edit_tag_'.$edit_hashid?>"
							style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
							<h3>Edit recipient <?php echo htmlspecialchars($rcpt_recipient_to_edit['name']);?></h3>
							<form method="post" id="form_edit_recipient_send"
								action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_recipients/update_rcpt_recipient.php'?>">
								<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
								<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
								<input type="hidden" name="p_hashid_article" value="<?php echo $article['hashid']?>">
								<input type="hidden" name="p_hashid_recipient" value="<?php echo $rcpt_recipient_to_edit['hashid']?>">
								<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
								<div class="row form-group row-no-padding">
									<div class="col-xs-6 col-sm-5 col-md-4">
										<div class="fullwidth padding_receipt_payer display_receipt_payer" style="background-color:<?php echo '#'.$rcpt_recipient_to_edit['color']?>">
											<?php echo htmlspecialchars($rcpt_recipient_to_edit['name']);?>
										</div>
									</div>
									<div class="col-xs-6 col-sm-5 col-md-4">
										<div class="input-group">
											<input type="number" min="0" name="p_quantity" step="0.001"
												max="<?php echo (float)$article['quantity'] - (float)$my_rcpt_quantities[$spreadsheet['id']][$article['id']] + (float)$rcpt_recipient_to_edit['quantity']?>"
												class="form-control" 
												value="<?php echo (float)$rcpt_recipient_to_edit['quantity']?>" required>
											<span class="input-group-addon glyphicon glyphicon-scale"></span>
										</div>
									</div>
								</div>
								<button type="submit" name="submit_update_rcpt_recipient" 
									value="Submit" class="btn btn-primary" title="Submit changes">
									Submit changes
								</button> 
								<button type="submit" name="submit_cancel" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" 
									form="form_cancel" class="btn btn-primary" title="Cancel">
								 Cancel
								</button>
							</form>
						</div>
			<?php
		$rcpt_recipient_to_edit= false;
		} //edit this recipient
		?>


						
						
		<?php
		//Add a receipients
		if($admin_mode && !$edit_mode)
			{
						$this_available_rcpt_recipients = $available_rcpt_recipients[$spreadsheet['id']][$article['id']];
						$this_available_quantity = (float)$article['quantity'] - (float)$my_rcpt_quantities[$spreadsheet['id']][$article['id']];
						if(!empty($this_available_rcpt_recipients)
							&& $this_available_quantity > 0)
				{
			?>
							<form method="post"	enctype="multipart/form-data"
								action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_recipients/new_rcpt_recipient.php'?>">
								<fieldset>
									<legend id="<?php echo 'show_hide_receipt_add_recipient_'.$cpt_spreadsheet.'_'.$cpt_article?>"
										class="cursorpointer">
										(+) Add a recipient
									</legend>
									<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
									<input type="hidden" name="p_hashid_article" value="<?php echo $article['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
									<div class="hidden_at_first"
									id=<?php echo 'show_hide_receipt_add_recipient_'.$cpt_spreadsheet.'_'.$cpt_article.'_target'?>>

		<?php
								$cpt = -1;
								foreach($this_available_rcpt_recipients as $member)
								{
									$cpt++;
				?>
										<div class="row form-group assign_member">
											<div class="col-xs-12 col-md-6 col-lg-4 ">
												<div>
													<input type="checkbox" name="p_recipient['<?php echo $cpt?>'][p_hashid_member]" 
														value="<?php echo $member['hashid']?>" title="member"
														id="<?php echo 'assign_recipient_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$cpt?>" >
													<div class="[ btn-group ] fullwidth" style="overflow:hidden">
														<label for="<?php echo 'assign_recipient_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$cpt?>"
															class="[ btn btn-default ] btn-assign_member">
															<span class="[ glyphicon glyphicon-ok ]"></span>
															<span> </span>
														</label>
														<span class="span-assign_member" >
															<label for="<?php echo 'assign_recipient_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$cpt?>" 
																class="[ btn btn-default active ] btn-assign_member2"
																style="background-color:<?php echo '#'.$member['color']?>">
																	<?php echo htmlspecialchars($member['name'])?>
															</label>
														</span>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-md-6 col-lg-4">
												<label for="<?php echo 'form_available_quantity_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$member['id']?>" 
													class="sr-only">
													Quantity
												</label>
												<div class="input-group">
													<input name="p_recipient['<?php echo $cpt?>'][p_quantity]" type="number"
																class="form-control" min="0" step="0.001"
																max="<?php echo (float)$this_available_quantity?>"
																value="<?php echo (float)$this_available_quantity?>"
																id="<?php echo 'form_available_quantity_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$member['id']?>"
																title="Quantity">
													<span class="input-group-addon glyphicon glyphicon-scale"></span>
												</div>
											</div>
										</div>
				<?php
								}//for each participant
				?>
										<div class="row form-group assign_member">
											<div class="col-xs-6 col-md-4 col-lg-3 ">
												<div>
													<input type="checkbox" name="" 
														id="<?php echo'form_select_all_recipients_'.$cpt_spreadsheet.'_'.$cpt_article?>"
														onchange="SelectAll(this, '<?php echo 'assign_recipient_'.$cpt_spreadsheet.'_'.$cpt_article.'_' ?>')">
													<div class="[ btn-group ] fullwidth" style="overflow:hidden">
														<label for="<?php echo 'form_select_all_recipients_'.$cpt_spreadsheet.'_'.$cpt_article?>"
															class="[ btn btn-default ] btn-assign_member">
															<span class="[ glyphicon glyphicon-ok ]"></span>
															<span> </span>
														</label>
														<span class="span-assign_member" >
															<label for="<?php echo 'form_select_all_recipients_'.$cpt_spreadsheet.'_'.$cpt_article?>" 
																class="[ btn btn-default active ] btn-select_all">
																	Select all
															</label>
														</span>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-md-6 col-lg-5 ">
												<div class="input-group">
													<span class="input-group-addon btn btn-default"
													onclick="SetAllValue('<?php echo 'form_set_all_quantity_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$cpt?>', '<?php echo 'form_available_quantity_'.$cpt_spreadsheet.'_'.$cpt_article.'_'?>')">Set to all</span>
													<input name="" type="number"
														class="form-control"
														min="0" value="<?php echo (float)$this_available_quantity?>" 
														title="Quantity"
														id="<?php echo 'form_set_all_quantity_'.$cpt_spreadsheet.'_'.$cpt_article.'_'.$cpt?>">
													<span class="input-group-addon glyphicon glyphicon-scale"></span>
												</div>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-xs-12">
												<button type="submit" name="submit_new_rcpt_recipient" 
													value="Submit" class="btn btn-primary" title="Submit new recipient">
													Submit
												</button>
											</div>
										</div>
									</div>
								</fieldset>
							</form>
							<?php
				}
			}
				?>
						</div>
	<?php	}//foreach current article 

//Display article to edit (if exists)
if($article_to_edit !== false)
{
?>
						<div class="highlight" id="<?php echo 'edit_tag_'.$edit_hashid?>"
							style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
							<h3>Edit article</h3>
							<form method="post" id="form_edit_article_send"
								action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_articles/update_rcpt_article.php'?>">
								<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
								<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
								<input type="hidden" name="p_hashid_article" value="<?php echo $article_to_edit['hashid']?>">
								<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
								
								<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
								<div class="row form-group">
									<div class="col-xs-12 col-lg-4">
										<label for="form_edit_article_product">
											Product<span class="glyphicon glyphicon-asterisk red"></span>
										</label>
										<div class="input-group">
											<input name="p_product" 
												id="form_edit_article_product" value="<?php echo htmlspecialchars($article_to_edit['product'])?>" 
												class="form-control" title="Product" type="text"> 
											<span class="input-group-addon glyphicon glyphicon-tag"></span>
										</div>
									</div>
									<div class="col-xs-12 col-lg-4">
										<label for="form_edit_article_price">
											Price<span class="glyphicon glyphicon-asterisk red"></span>
										</label>
										<div class="input-group">
											<input name="p_price" 
												id="form_edit_article_price" value="<?php echo (float)$article_to_edit['price']?>" 
												class="form-control" title="Product" type="number" min="0" step="0.01"> 
											<span class="input-group-addon glyphicon glyphicon-euro"></span>
										</div>
									</div>
									<div class="col-xs-12 col-lg-4">
										<label for="form_edit_article_quantity">
											Quantity<span class="glyphicon glyphicon-asterisk red"></span>
										</label>
										<div class="input-group">
											<input name="p_quantity" value="<?php echo (float)$article_to_edit['quantity']?>" 
												id="form_edit_article_quantity" 
												class="form-control" title="Product" type="number" min="0"> 
											<span class="input-group-addon glyphicon glyphicon-scale"></span>
										</div>
									</div>
								</div>										
								<button type="submit" name="submit_update_rcpt_article" value="Submit" 
									class="btn btn-primary" title="Update article">
									Submit changes
								</button>
								<button type="submit" name="submit_cancel" 
									value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" class="btn btn-primary"
									form="form_cancel" title="Cancel">
									Cancel
								</button>
							</form>
						</div>
<?php
$article_to_edit = false;
} //edit this article
?>

	<?php
	}//if article exist
	else
	{ 
	?>
		<p>No articles recorded.</p>
		<?php
		}//end else article exists
		?>
					</div>
					</div>
<?php		
	 // Add articles
		if($admin_mode && !$edit_mode)
		{?>
		<!-- Add article -->
		<?php
			if(!empty($my_rcpt_payers[$spreadsheet['id']]))
			{
	?>
					<form method="post" 
						action="<?php echo ACTIONPATH.'/spreadsheets/receipts/rcpt_articles/new_rcpt_article.php'?>"
						role="form">
						<fieldset>								
							<legend id="<?php echo 'show_hide_receipt_add_article_'.$cpt_spreadsheet?>"
								class="cursorpointer">
								(+) Add an article
							</legend>
							<div  class="hidden_at_first"	id="<?php echo 'show_hide_receipt_add_article_'.$cpt_spreadsheet.'_target'?>">
								<div id="<?php echo 'div_option_add_article_'.$cpt_spreadsheet?>">
									<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
									<input type="hidden" name="p_hashid_account" value ="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_spreadsheet" value ="<?php echo $spreadsheet['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
									<div id="div_set_article_<?php echo $cpt_spreadsheet?>">
										<div class="row form-group">
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_product_'.$cpt_spreadsheet?>_0">Product<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<input name="p_article[0][p_product]" 
														id="form_set_article_product_<?php echo $cpt_spreadsheet?>_0" 
														class="form-control" title="Product" type="text"> 
													<span class="input-group-addon glyphicon glyphicon-tag"></span>
												</div>
											</div>
											
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_quantity_'.$cpt_spreadsheet?>_0">Quantity/Parts<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<input name="p_article[0][p_quantity]" 
														id="form_set_article_quantity_<?php echo $cpt_spreadsheet?>_0" 
														class="form-control" title="Product" type="number" 
														min="0" value="1"> 
													<span class="input-group-addon glyphicon glyphicon-scale"></span>
												</div>
											</div>

											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_price_'.$cpt_spreadsheet?>_0">Total price<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<input name="p_article[0][p_price]" 
														id="form_set_article_price_<?php echo $cpt_spreadsheet?>_0" 
														class="form-control" title="Product" type="number" min="0" step="0.01"> 
													<span class="input-group-addon glyphicon glyphicon-euro"></span>
												</div>
											</div>
											
										</div>										
									</div>
								</div>
								<div>
									<button type="submit" name="submit_new_article" value="Submit" 
										title="Submit new article" class="btn btn-primary">
										Submit
									</button>
									<button type="button" name="add_row"
										title="Add a row" class="btn btn-primary" 
										onclick="AddArticleLine(<?php echo $cpt_spreadsheet?>);return false;">
										Add a row
									</button>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		}//if receipt_payers not empty (ie: article possible)
?>
	<?php
	} //if for displaying possibilities
?>

