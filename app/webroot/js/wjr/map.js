/*jslint browser: true, indent: 2, nomen: true*/
/*global define, google, requirejs*/

define(['jquery', 'underscore', 'async!https://maps.googleapis.com/maps/api/js?key=AIzaSyD93hybZ0IfdVF-NcnTkuG772Nzu9Rkjpc'], function ($, _) {
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
                var marker, color, infoWindow;
                infoWindow = new google.maps.InfoWindow({
                  content: '<a href="' + entity.url +
                           '"><h3>' + entity.name + '</h3></a>'
                });
                color = entity.map_standard.color ? entity.map_standard.color : 'rgba(0,0,0,1)';
                marker = new google.maps.Marker({
                  position: new google.maps.LatLng(entity.lat, entity.lng),
                  icon: {
                      path: "M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z",
                      fillColor: color,
                      fillOpacity: 1.0,
                      anchor: new google.maps.Point(0,0),
                      strokeOpacity: 0.5,
                      strokeWeight: 2.0,
                      strokeColor: '#000000',
                      scale: 2.0
                 },
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
