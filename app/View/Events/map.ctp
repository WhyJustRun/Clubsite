<style class="text/css">
html, body {
    padding: 0;
    height: 100%;
}
</style>

<?= $this->Leaflet->simpleMarker($event["Event"]["lat"], $event["Event"]["lng"], 14, '100%'); ?>
<script type="text/javascript">
$(window).resize(function () {
    map.setView(new L.LatLng(<?= $event["Event"]["lat"]?>, <?= $event["Event"]["lng"] ?>), 14);
});
</script>
