jQuery(document).ready(function()
{
	jQuery('#customFieldForm').submit(function(e)
	{
		var customFieldForm = jQuery(this).serialize();
		jQuery.ajax(
		{
			type: "POST",
			url: ajaxurl,
			data: customFieldForm,
			success: function(data)
			{
				jQuery('html, body').animate(
				{
					scrollTop: 0
				}, 'slow');
				jQuery('#woocsv_warning').html(data);
				jQuery("#woocsv_warning").slideDown().delay(2500).slideUp();
			}
		});
		e.preventDefault();
	});
});