<?php 
require_once __DIR__.'/../config-app.php';

include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/set_participant.php');
include_once(LIBPATH.'/participants/update_participant.php');
include_once(LIBPATH.'/participants/delete_participant.php');

include_once(LIBPATH.'/payments/get_payments.php');
include_once(LIBPATH.'/payments/get_payment_by_hashid.php');
include_once(LIBPATH.'/payments/set_payment.php');
include_once(LIBPATH.'/payments/update_payment.php');
include_once(LIBPATH.'/payments/get_payments_by_bills.php');
include_once(LIBPATH.'/payments/delete_payment.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bills/get_bill_by_hashid.php');
include_once(LIBPATH.'/bills/set_bill.php');
include_once(LIBPATH.'/bills/update_bill.php');
include_once(LIBPATH.'/bills/delete_bill.php');

include_once(LIBPATH.'/bill_participants/set_bill_participant.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');
include_once(LIBPATH.'/bill_participants/update_bill_participant.php');
include_once(LIBPATH.'/bill_participants/get_free_bill_participants.php');
include_once(LIBPATH.'/bill_participants/delete_bill_participant.php');

include_once(LIBPATH.'/solutions/compute_bill_solutions.php');
include_once(LIBPATH.'/solutions/compute_solution.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


if(isset($_POST['cancel']))
{
	header('location:'.$link_to_account_admin);
}




/*	
//It's a valid hashid, now get the data
if($tmp_action == "participant")
{ $data_to_be_modified = get_participant_by_hashid($account_id, $type_hashid);}
elseif($tmp_action == "payment")
{ $data_to_be_modified = get_payment_by_hashid($account_id, $type_hashid);}
elseif($tmp_action == "bill")
{ $data_to_be_modified = get_bill_by_hashid($account_id, $type_hashid);}
elseif($tmp_action == "bill_participant")
{ $data_to_be_modified = get_bill_participant_by_hashid($account_id, $type_hashid);}
else{break;}
*/

		if(empty($data_to_be_modified))
		{$data_to_be_modified = null;
			break;}
		//Now, we have the data to be modified, so we know what to do.
		//We skip every other arguments
		$type_of_data_to_be_modified = $tmp_type;
		$action_on_data_to_be_modified = $tmp_action;
		break;
	}
	
	//We now know if there is a data to be modified or not. We have to check if the user used the FORM to :
	// - to cancel and come back to "non edit" mode
	// - to add new data
	// - to update new data
	foreach($_POST  as $key => $arg)
	{
		$res_reg;
		$is_good = preg_match("/^submit_(update_|new_)(participant|payment|bill|bill_participant)$/", 
			$key, $res_reg);
		if(!$is_good )
			{continue;}
		if(!isset($_POST[$key]))
			{break;}
		$tmp_edit_or_not = $res_reg[0] =='edit';
		$tmp_type = $res_reg[1];

		//Submit button of an edit form has been clicked
		//Verify that the data to be updated IS of the same type than the data selected
		if($tmp_edit_or_not && $type_of_data_to_be_modified === $tmp_type)
		{
			$action_on_data_to_be_modified = "update";
			break;
		}
		//It's not an update
		//Otherwise, it's a new data to be stored
		$action_on_data_to_be_modified = "new";
		$type_of_data_to_be_modified = $res_reg[1];
		break;
	}	
}

if($action_on_data_to_be_modified == "edit")
	{$edit_mode == true;}
/* Here, we have an account and we know if we are admin or not.
Extract every data about the account and/or do the admin actions
*/
/* PARTICIPANT */
//New DATA
if($admin_mode)
{
	if($action_on_data_to_be_modified = "new")
	{
		if($type_of_data_to_be_modified == "participant")
		{
			$p_name_of_participant = filter_input(INPUT_POST, 'p_name_of_participant', FILTER_SANITIZE_STRING);
			$p_nb_of_people = filter_input(INPUT_POST, 'p_nb_of_people', FILTER_SANITIZE_NUMBER_INT);
			$p_email = filter_input(INPUT_POST, 'p_email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
			if(!empty($p_name_of_participant)){
				$p_participant_recorded = set_participant($account_id, $p_name_of_participant, $p_nb_of_people, $p_email);
			}
			if(!$p_participant_recorded)
			{
				//echo '<p>participant couldn\'t be added.</p>';
			}
		}elseif($type_of_data_to_be_modified == "bill")
		{$p_name_of_bill = filter_input(INPUT_POST, 'p_name_of_bill', FILTER_SANITIZE_STRING);
		$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
		$p_bill_recorded = set_bill($account_id, $p_name_of_bill, $p_description);
		}
		elseif($type_of_data_to_be_modified == "bill_participant")
		{ $p_bill_hashid = filter_input(INPUT_POST, 'p_bill_hashid', FILTER_SANITIZE_STRING);
			$p_bill = get_bill_by_hashid($account_id, $p_bill_hashid);
			$association_ok = true;
			foreach ($_POST['p_participant'] as $particip)
			{
				if(!isset($particip['hashid']))
					{continue;}
				$p_participant_hashid = htmlspecialchars($particip['hashid']);
				$p_participant_hashid = filter_var($p_participant_hashid, FILTER_SANITIZE_STRING);
				$p_percent_of_use = (float)$particip['percent'];
				$p_percent_of_use = filter_var($p_percent_of_use, FILTER_SANITIZE_NUMBER_INT);
				$p_participant = get_participant_by_hashid($account_id, $p_participant_hashid);
				if(empty($p_participant)){continue;}
				$association_ok_bis = set_bill_participant($account_id, $p_bill['id'], 
					$p_participant['id'], $p_percent_of_use);
				$association_ok = $association_ok ||$association_ok_bis;
			}
		}
		elseif($type_of_data_to_be_modified == "payment")
		{ $p_bill_hashid = filter_input(INPUT_POST, 'p_bill_hashid', FILTER_SANITIZE_STRING);
			$p_bill = get_bill_by_hashid($account_id, $p_bill_hashid);

			foreach ($_POST['p_payment'] as $payment)
			{
				if(!isset($payment['payer_hashid']))
					{continue;}
				$p_payer_hashid = htmlspecialchars($payment['payer_hashid']);
				$p_payer_hashid = filter_var($p_payer_hashid, FILTER_SANITIZE_STRING);
				if($p_payer_hashid == null)
					{continue;}
				$p_payer = get_participant_by_hashid($account_id, $p_payer_hashid);
				if(empty($p_payer))
					{continue;}
				$p_payer_id = $p_payer['id'];

				if(!isset($payment['cost']))
					{continue;}
				$p_cost = filter_var($payment['cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				if($p_cost <= 0)
				{continue;}

				if(!isset($payment['recv_hashid']))
					{continue;}
				$p_recv_hashid = htmlspecialchars($payment['recv_hashid']);
				$p_recv_hashid = filter_var($p_recv_hashid, FILTER_SANITIZE_STRING);
				$p_recv_id = null;

				if($p_recv_hashid != '-1')
				{
					$p_recv = get_participant_by_hashid($account_id, $p_recv_hashid);
					if(empty($p_recv))
					{continue;}
					$p_recv_id = $p_recv['id'];
				}
				
				$p_description = filter_var($payment['description'], FILTER_SANITIZE_STRING);
				$p_description = (empty($p_description))?null:$p_description;
				$p_date_payment  = filter_var($payment['date_payment'], FILTER_SANITIZE_STRING);
				$p_date_payment = (empty($p_date_payment))?null:$p_date_payment;
				$p_payment_added = set_payment($account_id, $p_bill['id'], 
				$p_payer_id, $p_cost, $p_recv_id, $p_description, $p_date_payment);
			}
		}
	}
	//Edit: Nothing to do, we already have extracted the data
	//So, next one: Action == update
	else if($action_on_data_to_be_modified = "update")
	{
		if($type_of_data_to_be_modified == "participant")
		{ $name_of_participant = filter_input(INPUT_POST, 'name_of_participant', FILTER_SANITIZE_STRING);
			$nb_of_people = filter_input(INPUT_POST, 'nb_of_people', FILTER_SANITIZE_NUMBER_INT);
			$participant_edited = update_participant($account_id, $participant_id_to_edit, 
				$name_of_participant, $nb_of_people);
			if($participant_edited)
			{
				$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
				header($redirect_url);
			}
		} elseif($type_of_data_to_be_modified == "bill")
		{
			$p_title = filter_input(INPUT_POST, 'p_title', FILTER_SANITIZE_STRING);
			$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
			$bill_edited = update_bill($account_id, $bill_id_to_edit, $p_title, $p_description);
			if($bill_edited)
			{
				$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
				header($redirect_url);
			}
		}elseif($type_of_data_to_be_modified == "bill_participant")
		{ $p_participant_id = filter_input(INPUT_POST, 'p_participant_id', FILTER_SANITIZE_NUMBER_INT);
			$p_percent_of_use = filter_input(INPUT_POST, 'p_percent_of_use', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);	
			$bill_participant_edited = update_bill_participant($account_id, $bill_participant_id_to_edit, $p_participant_id, $p_percent_of_use);
			if($bill_participant_edited)
			{
				$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
				header($redirect_url);
			}
		}elseif($type_of_data_to_be_modified == "payment")
		{
			$payment_edited = false;
			$p_bill_hashid = filter_input(INPUT_POST, 'p_bill_hashid', FILTER_SANITIZE_STRING);
			$p_bill = get_bill_by_hashid($account_id, $p_bill_hashid);
			$p_payer_hashid = filter_input(INPUT_POST, 'p_payer_hashid', FILTER_SANITIZE_STRING);
			$p_payer = get_participant_by_hashid($account_id, $p_payer_hashid);
			if(!empty($p_payer))
			{
				$p_payer_id = $p_payer['id'];
				$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				$p_receiver_hashid = filter_input(INPUT_POST, 'p_receiver_hashid', FILTER_SANITIZE_STRING);
				$recv_pb = false;
				if(is_null($p_receiver_hashid) ||$p_receiver_hashid == -1)
				{
					$p_receiver_id= null;
				}
				else{
					$p_recv = get_participant_by_hashid($account_id, $p_receiver_hashid);
					if(!empty($p_recv))
					{
						$p_receiver_id = $p_recv['id'];
					}
					else
					{$recv_pb = true;}
				}
				if(!$recv_pb)
				{
					$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
					$p_date_payment  = filter_input(INPUT_POST, 'p_date_payment', FILTER_SANITIZE_STRING);		
					$payment_edited = update_payment($account_id, $p_bill['id'], $payment_id_to_edit, 
					$p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_payment);
				}
			}
			if($payment_edited)
			{
				$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
				header($redirect_url);
			}
			else{ //problem
			}
		}
	}
	//Action == Delete
	elseif($action_on_data_to_be_modified = "delete")
	{
		if($type_of_data_to_be_modified == "participant")
		{
			$participant_to_delete = get_participant_by_hashid($account_id, $hashid_delete['participant']);
			$participant_deleted = delete_participant($account_id, $participant_to_delete['id']);
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}	elseif($type_of_data_to_be_modified == "bill")
		{	$bill_to_delete = get_bill_by_hashid($account_id, $hashid_delete['bill']);
			$bill_deleted = delete_bill($account_id, $bill_to_delete['id']);
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}	elseif($type_of_data_to_be_modified == "bill_participant")
		{	$bill_participant_to_delete = 
			get_bill_participant_by_hashid($account_id, $hashid_delete['bill_participant']);
			$bill_participant_deleted = delete_bill_participant($account_id, 
			$bill_participant_to_delete['id']);
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}	elseif($type_of_data_to_be_modified == "payment")
		{	$payment_to_delete = get_payment_by_hashid($account_id, $hashid_delete['payment']);
			$payment_deleted = delete_payment($account_id, $payment_to_delete['id']);
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}
	}
}

/* Computations and values used in display */
$my_bills = get_bills($account_id); // All bills
$my_participants = get_participants($account_id); //All person
$my_bill_participants = get_bill_participants($account_id); // Person that added to a bill
$my_free_bill_participants = get_free_bill_participants($account_id); // Person that can be added to a bill

//Payments
$my_payments_per_bill = get_payments_by_bills($account_id); //All payments

//For JS : create the list of payer to send to JS
$list_of_possible_payers= Array(Array(Array()));
foreach($my_bills as $bill)
{
	$cpt = -1;
	foreach ($my_bill_participants[$bill['id']] as $bill_participant)
	{
		$cpt ++;
		$list_of_possible_payers[$bill['hashid']][$cpt] = 
		Array(
			'part_name' => $bill_participant['name'],
			'part_hashid' => $bill_participant['participant_hashid']
		);
	}
}

//solution
$bill_solutions = compute_bill_solutions($account_id);
$solution = compute_solution($account_id);

$n_participants = 0;
$n_people = 0;
foreach($my_participants  as $participant)
{
	$n_participants += 1 ;
	$n_people += (int)$participant['nb_of_people'] ;
}

include_once(ABSPATH.'/templates/account.php');
?>