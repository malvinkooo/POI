<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <link rel="stylesheet" href="leaflet/leaflet.css"> -->
	<!-- <script src="leaflet/leaflet.js"></script> -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ==" crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js" integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log==" crossorigin=""></script>
	<title>Ponts of Interest</title>
	<style>
		#map {
			height: 450px;
			width: 750px;
		}
		.icon {
			width: 19px;
			padding-left: 5px;
			vertical-align: middle;
			cursor: pointer;
		}
		.input {
			width: 170px;
		}
	</style>
</head>
<body>
<div id="map"></div>
</body>
<script>
var map = L.map('map');
var icons = '<img class="icon delete" src="delete.png">' + '<img class="icon edit" src="edit.png">';

/*gets all places*/
map.on('load', function(){
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/places', true);
	xhr.onreadystatechange = function() {
		if(xhr.status === 200) {
			if(xhr.readyState === XMLHttpRequest.DONE) {
				var data = JSON.parse(xhr.responseText);
				for(var i = 0; i < data.length; i++) {
					var lat = parseFloat(data[i]['lat']);
					var lng = parseFloat(data[i]['lng']);

					if(data[i]['text']) {
						var text = data[i]['text'];
					} else {
						var text = '<input class="input" type="text">';
					}

					var marker = L.marker([lat, lng]);
					var popup = L.popup().setLatLng([lat, lng]).setContent(text + icons);

					popup.marker = marker;
					popup.markerId = data[i]['id'];

					marker.addTo(map).bindPopup(popup);
				}
			}
		} else {
			var error = JSON.parse(xhr.responseText);
			alert(error.error + ':' + error.details);
		}
	}
	xhr.send();
});
/*sets new marker*/
map.on('click', function(e){
	var marker = L.marker(e.latlng);
	var popup = L.popup()
				.setLatLng(e.latlng)
				.setContent('<input class="input" type="text">' + icons);
	popup.marker = marker;
	marker.addTo(map).bindPopup(popup);

	var data = {};
	data['lat'] = e.latlng.lat;
	data['lng'] = e.latlng.lng;
	data = JSON.stringify(data);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'api/places', true);
	xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
	xhr.onreadystatechange = function() {
		if(xhr.status === 200) {
			if(xhr.readyState === XMLHttpRequest.DONE) {
				var markerData = JSON.parse(xhr.responseText);
				popup.markerId = markerData['id'];
			}
		} else {
			var error = JSON.parse(xhr.responseText);
			alert(error.error + ':' + error.details);
		}
	}
	xhr.send(data);
});

/*add text to popup*/
map.on('popupclose', function(e){
	var popup = e.popup;
	var el = popup.getElement().querySelector('input');

	if(el && el.value) {
		var data = {};
		data['text'] = el.value;
		data = JSON.stringify(data);

		var xhr = new XMLHttpRequest();
		xhr.open('PUT', 'api/places/' + e.popup.markerId, true);
		xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
		xhr.onreadystatechange = function() {
			if(xhr.status === 200) {
				if(xhr.readyState === XMLHttpRequest.DONE) {
					popup.setContent(el.value + icons);
				}
			} else {
				var error = JSON.parse(xhr.responseText);
				alert(error.error + ":" + error.details);
			}
		}
		xhr.send(data);
	} else {
		return;
	}
});

/*removes marker*/
map.on('popupopen', function(e){
	document.querySelector('.delete').addEventListener('click', function(){
		e.popup.marker.remove();

		var xhr = new XMLHttpRequest();
		xhr.open('DELETE', 'api/places/' + e.popup.markerId, true);
		xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
		xhr.onreadystatechange = function() {
			if(xhr.status === 200) {
				if(xhr.readyState === XMLHttpRequest.DONE) {
					console.log('Marker has been removed');
					console.log(xhr.status + ":" + xhr.statusText);
				}
			} else {
				var error = JSON.parse(xhr.responseText);
				alert(error.error + ':' + error.details);
			}
		}
		xhr.send();
	});

	document.querySelector('.edit').addEventListener('click', function(){
		e.popup.setContent('<input class="input" type="text">' + icons);
	});
});


map.setView([46.4880795, 30.7410718], 18);
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);
</script>
</html>