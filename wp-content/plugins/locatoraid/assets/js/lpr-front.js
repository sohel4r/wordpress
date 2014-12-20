var lpr_map;
var lpr_loc;
var lpr_infowindow;
var lpr_offset = 0;
var lpr_limit = 5;
var lpr_geocoder;
var lpr_start_marker;
var lpr_directions_display;
var lpr_directions_service;
var lpr_log_it;

var lpr_allow_empty = false;

var lpr_meta;
if( document.createElement && (lpr_meta = document.createElement('meta')) )
{
	lpr_meta.name = "viewport";
	lpr_meta.content = "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no";
	document.getElementsByTagName('head').item(0).appendChild( lpr_meta );
}

google.maps.LatLng.prototype.ntsDistanceFrom = function(latlng)
{
	var lat = [this.lat(), latlng.lat()]
	var lng = [this.lng(), latlng.lng()]
	var R = 6378137;
	var dLat = (lat[1]-lat[0]) * Math.PI / 180;
	var dLng = (lng[1]-lng[0]) * Math.PI / 180;
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	Math.cos(lat[0] * Math.PI / 180 ) * Math.cos(lat[1] * Math.PI / 180 ) *
	Math.sin(dLng/2) * Math.sin(dLng/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;
	return Math.round(d);
}

jQuery(document).ready( function()
{
	/* check if current location is supported */
	if( ! navigator.geolocation )
	{
		jQuery( '#lpr-autodetect' ).parent().hide();
	}
	else
	{
		if( lpr_vars.conf_trigger_autodetect == true )
		{
			jQuery('#lpr-autodetect').trigger('click');
		}
	}

	lpr_allow_empty = true;
	lpr_geocoder = new google.maps.Geocoder();

	lpr_map = new google.maps.Map( 
		document.getElementById("lpr-map"),
		{
			zoom:15,
			mapTypeId:google.maps.MapTypeId.ROADMAP
			}
		);

	lpr_infowindow = new google.maps.InfoWindow();
	google.maps.event.addListener(lpr_map, 'click', function(){
		lpr_infowindow.close();
		}); 

	var boxText = document.createElement("div");
	var myOptions = {
		boxStyle: {
			background: "#fff url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat",
			opacity: 0.9,
			width: "280px",
			padding: "10px 10px 10px 10px"
			},
		closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
		infoBoxClearance: new google.maps.Size(1, 1),
		zIndex: null,
		isHidden: false,
		pane: "floatPane",
		enableEventPropagation: true,
		disableAutoPan: false,
		maxWidth: 0,
		pixelOffset: new google.maps.Size(-140, 0)
		};
	lpr_infowindow = new InfoBox(myOptions);

	var address = jQuery('#lpr-search-form').find('[name=search]').val();

	if( lpr_vars.conf_append_search.length > 0 )
	{
		// check if it already ends with append
		if( address.length > 0 )
		{
			if( address.substr(address.length - lpr_vars.conf_append_search.length).toLowerCase() != lpr_vars.conf_append_search.toLowerCase() )
			{
				address = address + ' ' + lpr_vars.conf_append_search;
			}
		}
	}

	var my_hash = window.location.hash;
	if( my_hash )
	{
		my_hash = my_hash.slice(1);
		my_hash = decodeURIComponent( my_hash );
		var my_select = jQuery('#lpr-search-form select[name=search2]');
		if( my_select.length )
		{
			my_select.find('option[value="' + my_hash + '"]').attr('selected', 'selected');
		}
	}

	var search2 = jQuery('#lpr-search-form').find('[name=search2]').val();
	var within = jQuery('#lpr-search-form').find('[name=within]').val();

	if( lpr_vars.start_listing == true )
	{
		lpr_log_it = 0;
		lpr_front_process_search( address, search2, lpr_allow_empty, within );
	}
	else
	{
		lpr_log_it = 1;
		jQuery('#lpr-results').hide();
	}

	lpr_allow_empty = false;

	lpr_directions_service = new google.maps.DirectionsService();
	lpr_directions_display = new google.maps.DirectionsRenderer();
	lpr_directions_display.setMap( lpr_map );
});

jQuery(document).on( 'submit', '#lpr-search-form', function(event) {
	event.preventDefault();
	var address = jQuery( this ).find('[name=search]').val();

	var country = jQuery( this ).find('[name=country]').val();
	if( country )
	{
		if( address )
			address = address + ' ' + country;
		else
			address = country;
	}

	if( lpr_vars.conf_append_search.length > 0 )
	{
		// check if it already ends with append
		if( address.substr(address.length - lpr_vars.conf_append_search.length).toLowerCase() != lpr_vars.conf_append_search.toLowerCase() )
		{
			address = address + ' ' + lpr_vars.conf_append_search;
		}
	}

	var search2 = jQuery( this ).find('[name=search2]').val();
	var within = jQuery( this ).find('[name=within]').val();

	if( jQuery('#lpr-results').is(':hidden') )
	{
		if( address.length || (search2.length && (search2 != ' ')) )
		{
			jQuery('#lpr-results').show();
			lpr_map = new google.maps.Map( document.getElementById("lpr-map"), {zoom:15, mapTypeId:google.maps.MapTypeId.ROADMAP} );
		}
	}

	allow_empty = (typeof allow_empty === "undefined") ? false : allow_empty;
	if( (! allow_empty) && (address.length < 1) && (search2.length < 1) )
		return false;

	var target_div = jQuery( '#lpr-locations' );
	if( target_div )
		target_div.html( '' );

	lpr_directions_display.setMap( null );
	lpr_front_process_search( address, search2, lpr_allow_empty, within );
	});

jQuery(document).ready( function()
{
	var my_hash = window.location.hash;
	if( my_hash )
	{
		my_hash = my_hash.slice(1);
		my_hash = decodeURIComponent( my_hash );
		var my_select = jQuery('#lpr-search-form select[name=search2]');
		if( my_select.length )
		{
			my_select.find('option[value="' + my_hash + '"]').attr('selected', 'selected');
		}
	}
});
