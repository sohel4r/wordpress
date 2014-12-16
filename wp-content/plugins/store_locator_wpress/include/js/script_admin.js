jQuery('#geocode_address_btn').live('click', function(event) {
	event.preventDefault();
	var address = jQuery('#address2geocode').val();
	
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var lat = results[0].geometry.location.lat();
			var lng = results[0].geometry.location.lng();
			jQuery('#add_store_form').hide();
			jQuery('#add_store_form2').show();
			jQuery('#lat').val(lat);
			jQuery('#lng').val(lng);
			jQuery('#address').val(address);
			jQuery('#address_display').html(address);
			var img = '<img src="http://maps.google.com/maps/api/staticmap?center='+lat+','+lng+'&zoom=15&size=300x200&markers=color:red|'+lat+','+lng+'&sensor=false">';
			jQuery('#map_display').html(img);
		}
	});
});

jQuery('#edit_geocode_address').live('click', function(event) {
	event.preventDefault();
	jQuery('#add_store_form').show();
	jQuery('#add_store_form2').hide();
	jQuery('#address2geocode').val(jQuery('#address').val());
});