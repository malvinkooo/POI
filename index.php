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

// map.on('click', function(e){
// 	var lat = parseFloat(e.latlng.lat.toFixed(7));
// 	var lng = parseFloat(e.latlng.lng.toFixed(7));

// 	console.log(lat, lng);

// 	L.marker([lat, lng]).addTo(map).bindPopup('Some text');
// });
var icons = '<img class="icon delete" src="delete.png">' + '<img class="icon edit" src="edit.png">';
/*gets all places*/
map.on('load', function(){
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/places', true);
	xhr.onreadystatechange = function() {
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
	}
	xhr.send();
});
/*sets new marker*/
map.on('click', function(e){
	var lat = e.latlng.lat;
	var lng = e.latlng.lng;

	var marker = L.marker(e.latlng);
	marker.addTo(map).bindPopup('<input class="input" type="text">' + icons);

	var data = {};
	data['lat'] = lat;
	data['lng'] = lng;
	data = JSON.stringify(data);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'api/places', true);
	xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

	xhr.onreadystatechange = function() {
		if(xhr.readyState === XMLHttpRequest.DONE) {
			var markerData = JSON.parse(xhr.responseText);
			marker.id = markerData['id'];
		}
	}
	xhr.send(data);
});

// map.on('popupclose', function(e){
	// console.dir(e);
	// if(e.popup._contentNode.children[0]) {
	// 	var value = e.popup._contentNode.children[0].value;
	// 	console.log(value);
	// } else {
	// 	var content = e.popup._content;
	// 	console.log(content);
	// }
// });

/*removes marker*/
map.on('popupopen', function(e){
	var currentId = e.popup.markerId;

	document.querySelector('.delete').addEventListener('click', function(){
		e.popup.marker.remove();

		var xhr = new XMLHttpRequest();
		xhr.open('DELETE', 'api/places/' + currentId, true);
		xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
		xhr.onreadystatechange = function() {
			if(xhr.status === 200) {
				if(xhr.readyState === XMLHttpRequest.DONE) {
					console.log('Marker has been removed');
					console.log(xhr.status + ":" + xhr.statusText);
				}
			} else {
				console.log(xhr.status + ":" + xhr.statusText);
				console.log(xhr.responseText);
			}
		}
		xhr.send();
	});
});





map.setView([46.4880795, 30.7410718], 18);
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);
</script>
</html>