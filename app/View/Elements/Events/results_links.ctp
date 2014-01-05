<?php
// Params: $event
$urlKeys = array('results_url' => 'Results at ', 'routegadget_url' =>  'RouteGadget at ');
foreach ($urlKeys as $urlKey => $titlePrefix) {
    if (!empty($event['Event'][$urlKey]) && $url = $event['Event'][$urlKey]) { ?>
        <div class="btn-group">
            <a href="<?= $url ?>" class="btn btn-primary"><?= $titlePrefix.parse_url($url, PHP_URL_HOST) ?></a>
        </div>
        <br/><br/>
<?php
    }
}
?>
