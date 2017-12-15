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
var map = L.map('map').setView([46.4880795, 30.7410718], 18);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

L.marker([46.4880795, 30.7410718]).addTo(map)
    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.');

// map.on('click', function(e){
// 	var lat = parseFloat(e.latlng.lat.toFixed(7));
// 	var lng = parseFloat(e.latlng.lng.toFixed(7));

// 	console.log(lat, lng);

// 	L.marker([lat, lng]).addTo(map).bindPopup('Some text');
// });

/*get all points*/
// map.on('load', function(e){
// 	var xhr = new XMLHttpRequest();
// 	xhr.open('GET', 'api/points', true);
// 	xhr.onreadychange = function() {
// 		var HTTP_REQUEST_DONE = 4;
// 		if(xhr.readyState === HTTP_REQUEST_DONE) {
// 			console.log(xhr.responseText);
// 		}
// 	}
// 	xhr.send();
// });
</script>
</html>