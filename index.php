<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="leaflet/leaflet.css">
	<script src="leaflet/leaflet.js"></script>
	<title>Ponts of Interest</title>
	<style>
		#map {
			height: 450px;
			width: 750px;
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

/*gets all points*/
map.on('load', function() {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/points', true);
	xhr.onreadystatechange = function() {
		if(xhr.readyState === XMLHttpRequest.DONE) {
			var data = JSON.parse(xhr.responseText);
			for(var i = 0; i < data.length; i++) {
				var lat = parseFloat(data[i]['lat']);
				var lng = parseFloat(data[i]['lng']);
				var text = data[i]['text'];

				L.marker([lat, lng]).addTo(map).
							bindPopup(text);
			}
		}
	}
	xhr.send();
});

/*sets new marker*/
map.on('click', function(e){
	var lat = parseFloat(e.latlng.lat.toFixed(7));
	var lng = parseFloat(e.latlng.lng.toFixed(7));

	L.marker([lat, lng]).addTo(map).bindPopup('<input name type="text">');

	var data = {};
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'api/points', true);
	xhr.onreadystatechange = function() {
		data['lat'] = lat;
		data['lng'] = lng;
		data = JSON.stringify(data);
	}
	xhr.send(data);
	console.log(data);
});





map.setView([46.4880795, 30.7410718], 18);
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);
</script>
</html>