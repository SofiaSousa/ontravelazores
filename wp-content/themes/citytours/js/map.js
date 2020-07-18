"use strict";

var markers = [];
var mapObject;

function renderMap( _center, markersData, zoom, mapType, mapTypeControl, mapId, show_filter ) {
	var mapOptions;
	var marker;

	 if ( show_filter == undefined || show_filter == 'false') { 
		mapOptions = {
						zoom: zoom,
						center: new google.maps.LatLng(_center[0], _center[1]),
						mapTypeId: mapType,

						mapTypeControl: mapTypeControl,
						mapTypeControlOptions: {
							position: google.maps.ControlPosition.TOP_LEFT
						},
						panControl: false,
						panControlOptions: {
							position: google.maps.ControlPosition.TOP_RIGHT
						},
						zoomControl: true,
						zoomControlOptions: {
							style: google.maps.ZoomControlStyle.LARGE,
							position: google.maps.ControlPosition.RIGHT_BOTTOM
						},
						scrollwheel: false,
						scaleControl: true,
						scaleControlOptions: {
							position: google.maps.ControlPosition.TOP_LEFT
						},
						streetViewControl: true,
						streetViewControlOptions: {
							position: google.maps.ControlPosition.RIGHT_BOTTOM
						},
						styles: [/*map styles*/]
					};	
	} else { 
		mapOptions = {
						zoom: zoom,
						center: new google.maps.LatLng(_center[0], _center[1]),
						mapTypeId: mapType,

						mapTypeControl: mapTypeControl,
						mapTypeControlOptions: {
							position: google.maps.ControlPosition.TOP_LEFT
						},
						panControl: false,
						panControlOptions: {
							position: google.maps.ControlPosition.TOP_RIGHT
						},
						zoomControl: true,
						zoomControlOptions: {
							style: google.maps.ZoomControlStyle.LARGE,
							position: google.maps.ControlPosition.LEFT_CENTER
						},
						scrollwheel: false,
						scaleControl: true,
						scaleControlOptions: {
							position: google.maps.ControlPosition.TOP_LEFT
						},
						streetViewControl: true,
						streetViewControlOptions: {
							position: google.maps.ControlPosition.LEFT_CENTER
						},
						styles: [/*map styles*/]
					};
	}

	if ( mapId == undefined ) { 
		mapObject = new google.maps.Map( document.getElementById('map'), mapOptions );
	} else { 
		mapObject = new google.maps.Map( document.getElementById(mapId), mapOptions );
	}

	for (var key in markersData) {
		markersData[key].forEach(function (item) {
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(item.location_latitude, item.location_longitude),
				map: mapObject,
				icon: item.type,
				title: item.name,
			});

			if ('undefined' === typeof markers[key])
				markers[key] = [];
			markers[key].push(marker);
			google.maps.event.addListener(marker, 'click', (function () {
				closeInfoBox();
				getInfoBox(item).open(mapObject, this);
				mapObject.setCenter(new google.maps.LatLng(item.location_latitude, item.location_longitude));
			}));
		});
	}
}

function onHtmlClick(key){
    google.maps.event.trigger(markers[key][0], "click");
}

function hideAllMarkers () {
	for (var key in markers) {
		markers[key].forEach(function (marker) {
			marker.setMap(null);
		});
	}
};
	
function toggleMarkers (category) {
	hideAllMarkers();
	closeInfoBox();
	
	if ('showall' == category) {
		for (var key in markers) {
			markers[key].forEach(function (marker) {
				marker.setMap(mapObject);
				marker.setAnimation(google.maps.Animation.DROP);
			});
		}
	} else {
		if ('undefined' === typeof markers[category])
			return false;
		markers[category].forEach(function (marker) {
			marker.setMap(mapObject);
			marker.setAnimation(google.maps.Animation.DROP);

		});
	}
};

function closeInfoBox() {
	jQuery('div.infoBox').remove();
};

function getInfoBox(item) {
	return new InfoBox({
		content:
		'<div class="marker_info" id="marker_info">' +
		'<img width="280" height="140" src="' + item.map_image_url + '" alt=""/>' +
		'<h3>'+ item.name_point +'</h3>' +
		'<span>'+ item.description_point +'</span>' +
		'<a href="'+ item.url_point + '" class="btn_1">' + button_text + '</a>' +
		'</div>',
		disableAutoPan: true,
		maxWidth: 0,
		pixelOffset: new google.maps.Size(40, -190),
		closeBoxMargin: '5px -20px 2px 2px',
		closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
		isHidden: false,
		pane: 'floatPane',
		enableEventPropagation: true
	});
};