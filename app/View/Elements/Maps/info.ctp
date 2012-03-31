<?php if(!empty($map["Map"]["lat"])) { ?>
	<div class="column-box">
		<h3>Location</h3>	
		<?php
		echo $this->Leaflet->simpleMarker($map["Map"]["lat"], $map["Map"]["lng"], 14, '500px');
		?>
	</div>
<?php } ?>
