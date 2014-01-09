<h2>List of <?php if($_POST['action'] === 'list_mine') echo 'my '; ?>notes</h2>
<div id="map" style='height: 550px; width: 700px; margin: auto;'></div>
<style>
	.leaflet-popup-content > form > input, .leaflet-popup-content > form > textarea {
		display: block;
		width: 300px;
		margin: auto;
	}
</style>
<script>
	// initialize the map on the "map" div with a given center and zoom
	var map = new L.Map('map', {
		center: new L.LatLng(20, 0),
		zoom: 1,
		minZoom: 1
	});
	// create a tile layer
	var mapquestUrl = 'http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png';
	var mapquest = new L.TileLayer(mapquestUrl, {maxZoom: 18, subdomains: '1234'});
	map.addLayer(mapquest);
	// Data
	var data = <?php echo json_encode($response['data']); ?>;
	for(i=0; i<data.length; i++)
	{
		var marker = L.marker([data[i].lat, data[i].lon]).addTo(map);
		//marker.bindPopup("<b>"+data[i].title+"</b><br>"+data[i].text);
		marker.bindPopup('<form method="POST"><input type="hidden" name="action" value="note_edit"/><input type="hidden" name="id" value="'+data[i].ID+'"/><input type="text" name="title" value="'+data[i].title+'"/><textarea name="text">'+data[i].text+'</textarea><label for="lifetime">Lifetime</label><input type="text" name="lifetime" value="'+data[i].lifetime+'"/><label for="lang">Language</label><input type="text" name="lang" value="'+data[i].lang+'"/><label for="cat">Category</label><input type="text" name="cat" value="'+data[i].cat+'"/><br/><input type="submit" value="\'note_edit\'"/></form>');

	}
</script>
<?php
	foreach($response['data'] as $note)
	{ ?>
<table style="display: inline-block" border>
<thead><th>Key</th><th>Value</th></thead>
<tbody>
<?php	foreach($note as $key => $value)
		{
			echo "<tr><td>$key</td><td>$value</td></tr>\n";
		} ?>
</tbody>
</table>
<?php } ?>
