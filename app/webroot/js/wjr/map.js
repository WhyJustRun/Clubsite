/*jslint browser: true, indent: 2, nomen: true*/
/*global define, google, requirejs*/

define(['jquery', 'underscore', 'async!https://maps.google.com/maps/api/js?sensor=false'], function ($, _) {
  'use strict';
  var map = {};
  map.SimpleMarkerMap = function () {
    this.initialize = function (element) {
      var lat, lng, zoom, mobileUrl, modalEvents,
        mapOptions, marker, center, googleMap, clickHandler, useMobileUrl;
      lat = +(element.getAttribute('data-lat'));
      lng = +(element.getAttribute('data-lng'));
      zoom = +(element.getAttribute('data-zoom'));
      mobileUrl = element.getAttribute('data-mobile-url');
      useMobileUrl = ($(window).width() < 768) && mobileUrl !== null;
      center = new google.maps.LatLng(lat, lng);
      mapOptions = {
        zoom: zoom,
        center: center,
        scrollwheel: false,
        draggable: !useMobileUrl,
        disableDefaultUI: useMobileUrl,
        mapTypeId: google.maps.MapTypeId.HYBRID
      };

      googleMap = new google.maps.Map(element, mapOptions);

      marker = new google.maps.Marker({
        position: center
      });
      marker.setMap(googleMap);

      if (useMobileUrl) {
        modalEvents = ['click'];
        clickHandler = function () {
          window.location = mobileUrl;
        };

        _.each(modalEvents, function (modalEvent) {
          google.maps.event.addListener(googleMap, modalEvent, clickHandler);
        });
      }
    };
  };

  map.DraggableMarkerMap = function () {
    this.initialize = function (element) {
      var latElement, lngElement, zoom, googleMap, options, center, marker;
      latElement = $(element.getAttribute('data-lat-element'));
      lngElement = $(element.getAttribute('data-lng-element'));
      zoom = +(element.getAttribute('data-zoom'));

      center = new google.maps.LatLng(latElement.val(), lngElement.val());
      options = {
        center: center,
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.HYBRID
      };
      googleMap = new google.maps.Map(element, options);

      marker = new google.maps.Marker({
        position: center,
        draggable: true
      });

      google.maps.event.addListener(marker, 'dragend', function () {
        var position = marker.getPosition();
        latElement.val(position.lat());
        lngElement.val(position.lng());
      });

      marker.setMap(googleMap);
    };
  };

  map.MultiMarkerMap = function () {
    this.initialize = function (element) {
      var lat, fetchUrl, fetchEntity, fetchMarkers, fetchBounds,
        lng, zoom, googleMap, options, center, markers, fetchInProgress;
      lat = +(element.getAttribute('data-lat'));
      lng = +(element.getAttribute('data-lng'));
      zoom = +(element.getAttribute('data-zoom'));
      fetchUrl = element.getAttribute('data-fetch-url');
      fetchEntity = element.getAttribute('data-fetch-entity');
      center = new google.maps.LatLng(lat, lng);
      options = {
        center: center,
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.HYBRID
      };

      googleMap = new google.maps.Map(element, options);
      markers = {};

      fetchInProgress = false;
      fetchBounds = null;
      fetchMarkers = function () {
        if (!fetchInProgress) {
          fetchInProgress = true;
          var bounds, ne, sw, url;
          bounds = googleMap.getBounds();
          fetchBounds = bounds;
          ne = bounds.getNorthEast();
          sw = bounds.getSouthWest();
          url = fetchUrl;
          url += '?max_lat=' + ne.lat() + '&min_lat=' + sw.lat();
          url += '&max_lng=' + ne.lng() + '&min_lng=' + sw.lng();
          $.ajax({
            url: url,
            success: function (data) {
              _.each(data[fetchEntity], function (entity) {
                if (_.has(markers, entity.id)) {
                  return;
                }
                var marker, infoWindow;
                infoWindow = new google.maps.InfoWindow({
                  content: '<a href="' + entity.url +
                           '"><h3>' + entity.name + '</h3></a>'
                });
                marker = new google.maps.Marker({
                  position: new google.maps.LatLng(entity.lat, entity.lng)
                });
                google.maps.event.addListener(marker, 'click', function () {
                  infoWindow.open(googleMap, marker);
                });
                marker.setMap(googleMap);
                markers[entity.id] = marker;
              });
            },
            complete: function () {
              fetchInProgress = false;
              // If the bounds have changed since the last fetch, start another fetch
              if (!fetchBounds.equals(googleMap.getBounds())) {
                fetchMarkers();
              }
            }
          });
        }
      };

      google.maps.event.addListener(googleMap, 'bounds_changed', fetchMarkers);
    };
  };

  return map;
});
