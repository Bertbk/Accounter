//Menu for a payment
function configureDropDownLists(payer, receiver) {
    var choice1 = payer.value;
	receiver.length= 0;
	createOptionDropDown(receiver, 'Group', -1);
	for (i = 0; i < payer.options.length; i++) {
		if(payer.value != payer.options[i].value && payer.options[i].value != 'null')
		{
			createOptionDropDown(receiver, payer.options[i].text, payer.options[i].value);
		}
	}
}
function createOptionDropDown(ddl, text, value) {
	var opt = document.createElement('option');
	opt.value = value;
	opt.text = text;
	ddl.options.add(opt);
}


//Add a form for a payment (multiple submit) 
function AddPaymentLine(name_of_people, hashid_of_people, cpt_bill)
{
	if(typeof AddPaymentLine.counter == 'undefined')
	{
		AddPaymentLine.counter = 1;
	}
	
	if(name_of_people.length != hashid_of_people.length)
	{return false;}
//on va juste ajouter un <p>caca</p> au bon endroit :)
	var div_payment=document.createElement("div");
	div_payment.setAttribute("class",	"div_set_payment_" + cpt_bill);
	
	var span_payer = document.createElement("span");
	var span_cost = document.createElement("span");
	var span_recv = document.createElement("span");
	var span_desc = document.createElement("span");
	var span_date = document.createElement("span");

	var select_payer = document.createElement("select");
	select_payer.id = "form_set_payment_payer_"+ cpt_bill + "_" + AddPaymentLine.counter
	var input_cost = document.createElement("input");
	input_cost.id = "form_set_payment_cost_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_cost.type="number";
	input_cost.min="0";
	input_cost.step="0.01";
	input_cost.className += " input_paymt_cost";
	var select_receiver = document.createElement("select");
	select_receiver.id = "form_set_payment_recv_"+ cpt_bill + "_" + AddPaymentLine.counter;
	var input_description = document.createElement("input");
	input_description.id = "form_set_payment_desc_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_description.type="text";
	input_description.className += " input_paymt_desc";
	var input_date = document.createElement("input");
	input_date.id = "form_set_payment_date_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_date.type="date";
	input_date.className += " date_picker";
	input_date.className += " input_paymt_date";
	
	//Construct the list of payer
	var opt_null = document.createElement("option");
	opt_null.selected=true;
	opt_null.disabled=true;
	opt_null.value = null;
	opt_null.text = " -- select a payer -- ";
	select_payer.appendChild(opt_null);
	for (var i =0; i < name_of_people.length; i ++)
	{
		var opt = document.createElement("option");
		opt.value = hashid_of_people[i];
		opt.text = name_of_people[i];
		select_payer.appendChild(opt);
	}

	var opt_group = document.createElement("option");
	opt_group.selected=true;
	opt_group.value = "-1";
	opt_group.text = "Group";
	select_receiver.appendChild(opt_group);
	
	select_payer.onchange=function(){
		configureDropDownLists(this, select_receiver);
	};

	//Set label
	var label_payer = document.createElement("Label");
	label_payer.setAttribute("for", select_payer.id);
	label_payer.innerHTML="Payer";
	var label_cost = document.createElement("Label");
	label_cost.setAttribute("for", input_cost.id);
	label_cost.innerHTML="Cost";
	var label_recv = document.createElement("Label");
	label_recv.setAttribute("for", select_receiver.id);
	label_recv.innerHTML="Receiver";
	var label_desc = document.createElement("Label");
	label_desc.setAttribute("for", input_description.id);
	label_desc.innerHTML="Description";
	var label_date = document.createElement("Label");
	label_date.setAttribute("for", input_date.id);
	label_date.innerHTML="Date of payment";
	
	//Add to div...
	//Payer
	span_payer.appendChild(label_payer);
	span_payer.appendChild(select_payer);
	div_payment.appendChild(span_payer);
	//Cost
	span_cost.appendChild(label_cost);
	span_cost.appendChild(input_cost);
	div_payment.appendChild(span_cost);
	//Receiver
	span_recv.appendChild(label_recv);
	span_recv.appendChild(select_receiver);
	div_payment.appendChild(span_recv);
	//Description
	span_desc.appendChild(label_desc);
	span_desc.appendChild(input_description);
	div_payment.appendChild(span_desc);
	//Date
	span_date.appendChild(label_date);
	span_date.appendChild(input_date);
	div_payment.appendChild(span_date);

	var form_to_add = document.getElementById('div_option_add_payment_' + cpt_bill);
	form_to_add.appendChild(div_payment);
	
   AddPaymentLine.counter ++;
   return false;
}
