$(function(){
  $("[id^=show_hide]").click(function(){
	 var target_id = '#' + $(this).attr('id') + '_target';
	 $(target_id).slideToggle("fast");
//    $(this).next().slideToggle("fast");
    return false; // prevent moving down or going to link
  });
});


$('.date_picker').each(function(){
    $(this).datepicker();
});

$(function() {
    $( ".date_picker" ).datepicker({ dateFormat: "dd-mm-yy" })
  });