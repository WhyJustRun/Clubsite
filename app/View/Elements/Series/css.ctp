
<style type="text/css">
<?php
$seriesSet = $this->requestAction('series/index');
foreach($seriesSet as $series) { 
    if(!empty($series["Series"]["color"])) {
?>
.series-<?php echo $series["Series"]["id"] ?> {
    color: <?php echo $series["Series"]["color"] ?>;
}
<?php 
    }
}
?>
</style>

