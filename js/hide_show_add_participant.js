$(document).ready(function() {
	$('#a_add_participant').click(function() {
			$('#form_add_participant').slideToggle("fast");
	});
});

$(document).ready(function() {
	$('#a_add_bill').click(function() {
			$('#form_add_bill').slideToggle("fast");
	});
});

$(function(){
  $("[id^=show_hide]").click(function(){
	 var target_id = '#' + $(this).attr('id') + '_target';
	 $(target_id).slideToggle("fast");
//    $(this).next().slideToggle("fast");
    return false; // prevent moving down or going to link
  });
});

