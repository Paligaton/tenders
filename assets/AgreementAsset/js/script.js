$(document).ready(function(){
	$('[name = "tender_status"]').change(function(){
		if ($(this).val() == 1)
		{
			$('.div_reason').show(250);
		}
		else if ($(this).val() == 2)
		{
			$('.div_reason').hide(250);
		}
	});
});