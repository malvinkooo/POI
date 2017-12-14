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
			width: 700px;
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
</script>
</html>