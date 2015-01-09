jQuery(document).ready(function($) {
    
		$('.example_link').click(function() {
			$(this).parent().next('.examples').slideToggle();
		});
		
		var i = 10000;
		
		$('.mwi_repeater .add').live('click', function() {
			$(this).parents('tr.msv_row').clone(true)
			.find(".sv_url").attr("name", "mwi_options[multiple_sv]["+i+"][url]").attr("value", "").end()
			.find(".sv_code").attr("name", "mwi_options[multiple_sv]["+i+"][store_view_code]").attr("value", "").end()
			.removeClass('first_row')
			.appendTo('.mwi_repeater');
			i++;
			return false;
		});
		
		$('.mwi_repeater .remove').live('click', function() {
			
			var answer = confirm("Are you sure you want to delete this row?");
      if (answer) {
         $(this).parents('tr.msv_row').remove();
      }else{
         return false;
      }
			
			return false;
		});
		
});