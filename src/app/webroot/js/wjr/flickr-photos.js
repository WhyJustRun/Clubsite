/*jslint nomen: true, browser: true indent: 2*/
/*global define, requirejs*/

define(['jquery', 'underscore', 'knockout'], function ($, _, ko) {
  'use strict';
  var FlickrPhotos = {}, photoViewModel;

  photoViewModel = {
    photos: ko.observableArray()
  };


  FlickrPhotos.Initialize = function (element) {
    var count = 2,
      apiKey = element.getAttribute('data-flickr-api-key'),
      tags = element.getAttribute('data-flickr-tags');

    function loadMorePhotos(page) {
      var url = "https://api.flickr.com/services/rest/?method=flickr.photos.search&nojsoncallback=1&api_key=" + apiKey + "&tags=" + tags + "&format=json&extras=date_taken,description,owner_name&per_page=30&page=" + page;
      $.get(url, function (results) {
        if (results.stat === "ok") {
          _.each(results.photos.photo, function (photo) {
            photoViewModel.photos.push({
              id: photo.id,
              page: "https://www.flickr.com/photos/" + photo.owner + "/" + photo.id,
              thumbnailUrl: "https://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + ".jpg",
              largeUrl: "https://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_b.jpg",
              ownerName: photo.ownername,
              description: photo.description,
              dateTaken: photo.datetaken,
              clicked: function () {
                var id = this.id,
                  largeUrl = this.largeUrl;
                requirejs(['jquery.fancybox'], function () {
                  if ($("#flickrPhoto" + id + " img").attr('src') === "") {
                    $("#flickrPhoto" + id + " img").attr('src', largeUrl);
                    $.fancybox.showLoading();

                    $("#flickrPhoto" + id + " img").load(function () {
                      $.fancybox.hideLoading();
                      $.fancybox.open("#flickrPhoto" + id);
                    });
                  } else {
                    $.fancybox.open("#flickrPhoto" + id);
                  }
                });
              }
            });
          });
        }
      });

    }

    $(window).scroll(function () {
      if ($(window).scrollTop() === $(document).height() - $(window).height()) {
        loadMorePhotos(count);
        count += 1;
      }
    });

    loadMorePhotos(1);
    ko.applyBindings(photoViewModel, element);
  };

  return FlickrPhotos;
});
