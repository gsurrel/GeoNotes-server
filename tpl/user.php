<h1>Welcome <?php echo $response['data']->username; ?></h1>

<table style="display: inline-block" border>
	<caption>User details</caption>
	<thead><th>Key</th><th>Value</th></thead>
	<tbody>
	<?php	foreach($response['data'] as $key => $value)
			{
				echo "<tr><td>$key</td><td>$value</td></tr>\n";
			} ?>
	</tbody>
</table>
<table style="display: inline-block" border>
	<caption>Pretty settings</caption>
	<thead><th>Key</th><th>Value</th></thead>
	<tbody>
		<?php foreach(json_decode($response['data']->settings) as $key => $value) echo "<tr><td>$key</td><td>$value</td></tr>" ?>
	</tbody>
</table>
