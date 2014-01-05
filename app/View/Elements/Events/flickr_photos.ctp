<?php
// Pass in an $eventId
?>
<div id="flickr-photos-container" class="photos-grid">
    <h2>Photos</h2>
    <p>Photos are from Flickr. To add your photos to this section, tag your Flickr photos with: <span class="label label-success" style="vertical-align: baseline">orienteerapp<?= $eventId ?></span> (all one word)</p>
    <ul id="flickr-photos" class="list-unstyled" data-bind="foreach: photos">
        <li class="col-sm-3">
        <a data-bind="attr: { href: '#flickrPhoto' + id, onclick: 'loadFlickrImage(\'' + id + '\',\'' + largeUrl + '\')' }" class="thumbnail">
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

<?php $this->append('secondaryScripts'); ?>
<script type="text/javascript">
    function loadFlickrImage(id, largeUrl) {
        if($("#flickrPhoto" + id + " img").attr('src') == "") {
            $("#flickrPhoto" + id + " img").attr('src', largeUrl);
            $.fancybox.showLoading();

            $("#flickrPhoto" + id + " img").load(function() {
                    $.fancybox.hideLoading();
                    $.fancybox.open("#flickrPhoto" + id);
                    });
        } else {
            $.fancybox.open("#flickrPhoto" + id);
        }
    }

var photoViewModel = {
photos: ko.observableArray()
};

function jsonFlickrApi(results) {
    $(function() {
            if(results.stat == "ok") {
            _.each(results.photos.photo, function(photo) {
                photoViewModel.photos.push({
id: photo.id,
page: "http://www.flickr.com/photos/" + photo.owner + "/" + photo.id,
thumbnailUrl: "http://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + ".jpg", 
largeUrl: "http://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_b.jpg",
ownerName: photo.ownername,
description: photo.description,
dateTaken: photo.datetaken
});
                });
            }
            });
}


$(function() {
        var count = 2;

        $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
            loadMorePhotos(count);
            count++;
            }
            });

        function loadMorePhotos(page) {
        var script = document.createElement('script');
        var url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=<?= Configure::read('Flickr.apiKey') ?>&tags=<?= urlencode("orienteerapp".$eventId) ?>&format=json&extras=date_taken,description,owner_name&per_page=30&page=" + page;
        script.type = 'text/javascript';
        script.src = url;
        $("#flickr-photos-container").append(script);
        }

        loadMorePhotos(1);
        ko.applyBindings(photoViewModel, document.getElementById('flickr-photos-container'));
        });

</script>
<?php $this->end(); ?>
