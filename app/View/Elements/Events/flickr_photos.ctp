<?php
// Pass in an $eventId
?>
<div class="flickr-photos-container" data-flickr-tags="<?php echo urlencode("whyjustrun".$eventId) ?>,<?php echo urlencode("orienteerapp".$eventId) ?>" data-flickr-api-key="<?php echo Configure::read('Flickr.apiKey') ?>" class="photos-grid">
    <h2>Photos</h2>
    <p>Photos are from Flickr. To add your photos to this section, tag your Flickr photos with: <span class="label label-success" style="vertical-align: baseline">whyjustrun<?php echo $eventId ?></span> (all one word)</p>
    <ul class="flickr-photos list-unstyled" data-bind="foreach: photos">
        <li class="col-sm-3">
        <a data-bind="attr: { href: '#flickrPhoto' + id }, click: clicked" class="thumbnail">
            <div data-bind="style: { backgroundImage: 'url(' + thumbnailUrl + ')' }">

            </div>
        </a>

        <div data-bind="attr: { id: 'flickrPhoto' + id }" style="display:none; ">
            <div class="pull-right">
                Taken: <span data-bind='text: dateTaken'></span><br/>
                By: <span data-bind='text: ownerName'></span>
            </div>
            <a class='btn btn-large btn-primary' data-bind='attr: { href: page }'>Photo on Flickr</a>
            <br/><br/>
            <img src="" />
        </div>
        </li>
    </ul>
</div>
