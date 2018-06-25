<div id="map" style="height:300px; width:100%;"></div>

<?php
$lat = 40.00;
$lon = -35.00;
$zoom  = 2;

if($item['d_coord_lat'] != '' && $item['d_coord_long'] != '') {
	$lat = $item['d_coord_lat'];
	$lon = $item['d_coord_long'];
	$zoom = 14;
}
?>

<script type="text/javascript">
var map = new L.Map('map', {center: new L.LatLng(<?php echo $lat, ', ', $lon; ?>), zoom: <?php echo $zoom ?>});
var osm = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors' });
map.addLayer(osm);
L.marker([<?php echo $lat, ', ', $lon; ?>]).addTo(map).bindPopup("location");
</script>