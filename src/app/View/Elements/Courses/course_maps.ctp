<?php
// Params: $event (containing courses).
$courses = $event['Course'];
if(!empty($courses)) { ?>
    <h2>Course Maps</h2>
    <?php
    $mapsPosted = false;
    foreach ($courses as $course) {
        if($this->Media->exists("Course", $course["id"])) {
            $mapsPosted = true;
            ?>
            <h3>
                <?php echo $course["name"] ?>
                <span class="pull-right">
                    <?php
                    $mediaUrl = Router::url($this->Media->mediaUrl('Course', $course['id'], 'image'), true);
                    // Current URL
                    $url = Router::url(null, true);
                    $description = $this->TimePlus->formattedEventDate($event) . ' - ' . $event['Event']['name'] . ' - ' . $course['name'] . ' course';
                    ?>
                    <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="Use Pinterest to collect all the orienteering maps you've run on!">
                        <a href="<?php echo $this->Link->pinterestPinUrl($url, $mediaUrl, $description) ?>"
                        data-pin-do="buttonPin"
                        data-pin-config="none"
                        data-pin-height="28">
                            <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" />
                        </a>
                    </span>
                </span>
            </h3>
            <?php echo $this->Media->linkedImage("Course", $course["id"], '600x600', array(), array('style' => 'max-width: 100%', 'data-pin-hover' => true)) ?>
        <?php
        }
    }
    if(!$mapsPosted) {
        echo "No course maps posted yet.";
    }
}
?>

<script type="text/javascript">
(function(d){
    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
    p.type = 'text/javascript';
    //p.setAttribute('data-pin-hover', true);
    p.async = true;
    p.src = '//assets.pinterest.com/js/pinit.js';
    f.parentNode.insertBefore(p, f);
}(document));
</script>
