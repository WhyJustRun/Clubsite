
<style type="text/css">
<?php
$seriesSet = $this->requestAction('series/index');
foreach($seriesSet as $series) { 
    if(!empty($series["Series"]["color"])) {
?>
.series-<?= $series["Series"]["id"] ?> {
	color: <?= $series["Series"]["color"] ?>;
}
<?php 
    }
}
?>
</style>
