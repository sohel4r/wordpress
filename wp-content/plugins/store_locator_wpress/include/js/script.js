var store_wpress_map;
var store_wpress_markers = [];
var store_wpress_infoWindow;
var store_wpress_panorama;

function store_locator_load() {
	//If address detected, geocode it
	var address = jQuery('#store_wpress_address').val();
	
	if(address!='' && (Store_wpress.searched_lat=='' && Store_wpress.searched_lng=='')) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				Store_wpress.searched_lat = results[0].geometry.location.lat();
				Store_wpress.searched_lng = results[0].geometry.location.lng();
				store_locator_load_step2();
			}
			else {
				Store_wpress.searched_lat = '';
				Store_wpress.searched_lng = '';
				alert('Sorry we couldn\'t geocode the given address');
			}
		});		
	}
	else {
		store_locator_load_step2();
	}
}

function store_locator_load_step2() {
	if(Store_wpress.display=='map') {
		init_stores_map();
	}
	else if(Store_wpress.display=='list') {
		init_stores_list();
	}
	else {
		init_stores_map();
		init_stores_list();
	}
}

// ####################
// START closest stores
function search_closest_map_locations() {
	if (navigator.geolocation) navigator.geolocation.getCurrentPosition(search_closest_map_locations_success, search_closest_map_locations_error, {maximumAge:Infinity});
}
function search_closest_map_locations_success(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	Store_wpress.current_lat = lat;
	Store_wpress.current_lng = lng;
	display_map();
}
function search_closest_map_locations_error() {
	if(Store_wpress.nb_display_default>0) display_map();
}
// ##################
// END closest stores

function init_stores_map() {
	
	//execute only the first time the Map locator loads
	if(Store_wpress.first_load=='1') {
		store_wpress_map = new google.maps.Map(document.getElementById('map'), {
			center: new google.maps.LatLng(Store_wpress.lat, Store_wpress.lng),
			zoom: Store_wpress.zoom,
			scrollwheel: false,
			mapTypeId: Store_wpress.map_type,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		});
		store_wpress_infoWindow = new google.maps.InfoWindow();
		Store_wpress.first_load = 0;
	}
	
	//&& (Store_wpress.current_lat=='' && Store_wpress.current_lng=='')
	if(Store_wpress.closest_stores==1) {
		search_closest_map_locations();
	}
	else if(Store_wpress.nb_display_default>0 || (Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='')) {
		display_map();		
	}
}

function display_map() {
	
	//lat & lng setup
	var lat = '';
	var lng = '';
	var nb_display = Store_wpress.nb_display_default;
	if(Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='') {
		lat = Store_wpress.searched_lat;
		lng = Store_wpress.searched_lng;
		nb_display = Store_wpress.nb_display_search;
	}
	else if(Store_wpress.current_lat!='' && Store_wpress.current_lng!='') {
		lat = Store_wpress.current_lat;
		lng = Store_wpress.current_lng;
		nb_display = Store_wpress.nb_display_search;
	}
	
	//alert(nb_display);
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_map&page_number=' + Store_wpress.page_number + '&lat=' + lat + '&lng=' + lng + '&category_id=' + Store_wpress.category_id + '&radius_id=' + Store_wpress.radius_id + '&nb_display=' + nb_display,
		success: function(msg) {
			var locations = msg.locations;
			var markersContent = msg.markersContent;
			var bounds = new google.maps.LatLngBounds();
			
			jQuery('#stores_locator_title').html(msg.title);
			jQuery('#previousNextButtons').html(msg.previousNextButtons);
			if (jQuery('#previousNextButtons2').length > 0) jQuery('#previousNextButtons2').html(msg.previousNextButtons);
			clearLocations();
			
			for (var i=0; i<locations.length; i++) {
				var name = locations[i]['name'];
				var address = locations[i]['address'];
				var distance = parseFloat(locations[i]['distance']);
				var latlng = new google.maps.LatLng(
					parseFloat(locations[i]['lat']),
					parseFloat(locations[i]['lng'])
				);
				//category custom marker
				var marker_icon = locations[i]['marker_icon'];
				
				//if no category marker, set custom marker
				//if(marker_icon==null) marker_icon = Store_wpress.custom_marker;
				
				//createOption(name, distance, i);
				createMarker(latlng, locations[i]['lat'], locations[i]['lng'], markersContent[i], marker_icon);
				
				bounds.extend(latlng);
	       	}
	       	
	       	if(locations.length>1) {
	       		store_wpress_map.fitBounds(bounds);
	       	}
	       	else if(locations.length==0) {
		       	//nothing
	       	}
	       	else {
				store_wpress_map.setCenter(bounds.getCenter());
				store_wpress_map.setZoom(12);
	       	}
		}
	});
}

// ####################
// START closest stores
function search_closest_list_locations() {
	if (navigator.geolocation) navigator.geolocation.getCurrentPosition(search_closest_list_locations_success, search_closest_list_locations_error, {maximumAge:Infinity});
}
function search_closest_list_locations_success(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	Store_wpress.current_lat = lat;
	Store_wpress.current_lng = lng;
	display_stores_list();
}
function search_closest_list_locations_error() {
	display_stores_list();
}
// ##################
// END closest stores

function init_stores_list() {
	if(Store_wpress.closest_stores==1) {
		search_closest_list_locations();
	}
	else if(Store_wpress.nb_display_default>0 || (Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='')) {
		display_stores_list();
	}
}

function display_stores_list() {
	//loading icon
	if(jQuery('#store_locator_list').html()=='') {
		//jQuery('#store_locator_list').addClass('loading_icon');
	}
	
	//lat & lng setup
	var lat = '';
	var lng = '';
	var nb_display = Store_wpress.nb_display_default;
	if(Store_wpress.searched_lat!='' && Store_wpress.searched_lng!='') {
		lat = Store_wpress.searched_lat;
		lng = Store_wpress.searched_lng;
		nb_display = Store_wpress.nb_display_search;
	}
	else if(Store_wpress.current_lat!='' && Store_wpress.current_lng!='') {
		lat = Store_wpress.current_lat;
		lng = Store_wpress.current_lng;
		nb_display = Store_wpress.nb_display_search;
	}
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_list&page_number=' + Store_wpress.page_number + '&lat=' + lat + '&lng=' + lng + '&category_id=' + Store_wpress.category_id + '&radius_id=' + Store_wpress.radius_id + '&nb_display=' + nb_display + '&display_type=' + jQuery('body').data('type'),
		success: function(msg) {
			jQuery('#stores_locator_title').html(msg.title);
			jQuery('#previousNextButtons').html(msg.previousNextButtons);
			if (jQuery('#previousNextButtons2').length > 0) jQuery('#previousNextButtons2').html(msg.previousNextButtons);
			//alert(msg.stores);
			if(msg.stores=='') jQuery('#store_locator_list').html('No results found');
			else jQuery('#store_locator_list').html(msg.stores);
		}
	});
}

jQuery(".displayStoreMap").live('click', function(event) {
	event.preventDefault();
	var id = jQuery(this).attr('id');
	var lat = jQuery(this).attr('lat');
	var lng = jQuery(this).attr('lng');
	
	var content = jQuery('#infowindow_'+id).html();
	var marker_icon = jQuery('#marker_icon_'+id).html();
	
	var latlng = new google.maps.LatLng(
		parseFloat(lat),
		parseFloat(lng)
	);
	
	init_basic_map(lat, lng, '', '');
	
	clearLocations();
	createMarker(latlng, lat, lng, content, marker_icon, 1);
});

function init_basic_map(lat, lng, marker_text, marker_icon) {
	store_wpress_map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(lat, lng),
		zoom: Store_wpress.zoom_detail,
		scrollwheel: false,
		mapTypeId: Store_wpress.map_type,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	});
	
	var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
	
	createMarker(latlng, lat, lng, marker_text, marker_icon);
	
	if(Store_wpress.streetview=='on') streetView(lat,lng);
	
	store_wpress_infoWindow = new google.maps.InfoWindow();
}

function clearLocations() {
	store_wpress_infoWindow.close();
	for (var i = 0; i < store_wpress_markers.length; i++) {
		store_wpress_markers[i].setMap(null);
	}
	store_wpress_markers.length = 0;
}

jQuery("#store_wpress_category_filter").live('change', function(event) {
	event.preventDefault();
	var category_id = jQuery(this).val();
	Store_wpress.page_number = 1;
	Store_wpress.category_id = category_id;
	store_locator_load();
});

jQuery("#store_wpress_distance_filter").live('change', function(event) {
	event.preventDefault();
	var radius_id = jQuery(this).val();
	Store_wpress.page_number = 1;
	Store_wpress.radius_id = radius_id;
	store_locator_load();
});

jQuery("#store_wpress_search_btn").live('click', function(event) {
	event.preventDefault();
	Store_wpress.page_number = 1;
	Store_wpress.searched_lat = '';
	Store_wpress.searched_lng = '';
	store_locator_load();
});

jQuery("#store_locator_next").live('click', function(event) {
	event.preventDefault();
	Store_wpress.page_number = (Store_wpress.page_number+1);
	store_locator_load();
});

jQuery("#store_locator_previous").live('click', function(event) {
	event.preventDefault();
	Store_wpress.page_number = (Store_wpress.page_number-1);
	store_locator_load();
});

function createMarker(latlng, lat, lng, html, marker_icon, window_flag) {
	
	if(marker_icon===null || marker_icon===undefined || marker_icon==='') marker_icon=Store_wpress.custom_marker;
	
	var marker = new google.maps.Marker({
		map: store_wpress_map,
		position: latlng,
		icon: marker_icon,
		animation: google.maps.Animation.DROP
	});
	
	if(window_flag==1) {
		store_wpress_infoWindow.setContent(html);
		store_wpress_infoWindow.open(store_wpress_map, marker);
		setStreetView(latlng);		
	}
	else {
		google.maps.event.addListener(marker, 'click', function() {
			store_wpress_infoWindow.setContent(html);
			store_wpress_infoWindow.open(store_wpress_map, marker);
			setStreetView(latlng);
		});
	}
	
	store_wpress_markers.push(marker);
}

// ##################
// START Street view
function setStreetView(latlng) {
    store_wpress_panorama = store_wpress_map.getStreetView();
    store_wpress_panorama.setPosition(latlng);
    store_wpress_panorama.setPov({
      heading: 265,
      zoom:1,
      pitch:0}
    );
}

jQuery("#displayStreetView").live('click', function(event) {
	event.preventDefault();
	store_wpress_panorama.setVisible(true);
});

function streetView(lat,lng) {
	var dom = 'streetview';
	panorama = new google.maps.StreetViewPanorama(document.getElementById(dom));
	displayStreetView(lat,lng, dom);
}

function displayStreetView(lat,lng, dom) {
	var latlng = new google.maps.LatLng(lat,lng);
	
	var panoramaOptions = {
	  position: latlng,
	  panControl: true,
	  linksControl: true,
	  enableCloseButton: true,
	  disableDoubleClickZoom: true,
	  addressControl: false,
	  visible: true,
	  pov: {
	    heading: 270,
	    pitch: 0,
	    zoom: 1
	  }
	};
	store_wpress_panorama = new google.maps.StreetViewPanorama(document.getElementById(dom),panoramaOptions);
	store_wpress_map.setStreetView(store_wpress_panorama);
}

// ###################
// START Widget stores
function display_widget_closest_stores() {
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(closest_stores_detectionSuccess, closest_stores_detectionError, {maximumAge:Infinity});
	}
}

function closest_stores_detectionSuccess(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	
	var page_number = 1;
	
	//loading icon
	if(jQuery('#widget_store_locator_list').html()=='') {
		jQuery('#widget_store_locator_list').addClass('loading_icon');
	}
	
	jQuery.ajax({
		type: 'POST',
		url: Store_wpress.ajaxurl,
		dataType: 'json',
		data: 'action=store_wpress_listener&method=display_list&page_number=' + page_number + '&lat=' + lat + '&lng=' + lng + '&nb_display=' + Store_wpress.widget_nb_display + '&no_info_links=1&widget_display=1',
		success: function(msg) {
			var stores = msg.stores;
			jQuery('#widget_store_locator_list').html(stores);
		}
	});
}

function closest_stores_detectionError() {
	jQuery('#widget_store_locator_list').html('You need to share your location in order to view the locations list. <a href="javascript:window.location.reload();">Reload the page?</a>');
}
// #################
// END Widget stores