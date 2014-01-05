<style class="text/css">
html, body {
    padding: 0;
    height: 100%;
}
</style>

<?= $this->Leaflet->simpleMarker($event["Event"]["lat"], $event["Event"]["lng"], 14, '100%'); ?>
<?php
$this->append('secondaryScripts');
?>
<script type="text/javascript">
$(window).resize(function () {
    map.setView(new L.LatLng(<?= $event["Event"]["lat"]?>, <?= $event["Event"]["lng"] ?>), 14);
});
</script>
<?php
$this->end();
?>
