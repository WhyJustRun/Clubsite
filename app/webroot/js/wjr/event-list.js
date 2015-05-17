/*jslint nomen: true, browser: true, indent: 2*/
/*global define, requirejs*/

define(['jquery', 'moment', 'knockout', './club'], function ($, moment, ko, club) {
  'use strict';
  var EventList, SingleFetcher, TimeFetcher, IOF;
  SingleFetcher = function (url, coloringStyle) {
    this.fetch = function (options) {
      $.ajax({
        type: "GET",
        url: url,
        dataType: "xml",
        cache: false,
        success: function (xml) {
          options.onSuccess(IOF.loadEventsList(xml, coloringStyle));
        }
      });
    };
  };

  // A fetcher that fetches a time interval of events
  TimeFetcher = function (url, coloringStyle) {
    var startTime = null, endTime = null;

    /**
     * Params: options
     * - direction ('newer', or 'older')
     * - onSuccess (callback, params: newEvents, startTime, endTime, newer)
     * - onComplete (callback, called regardless of success or failure)
     */
    this.fetch = function (options) {
      var fetchingNewer,
        fetchStartTime,
        fetchEndTime;

      if (startTime === null || endTime === null) {
        // Initial fetch
        fetchStartTime = moment.utc().subtract('months', 1);
        fetchEndTime = moment.utc().add('months', 2);
      } else if (options.direction === 'newer') {
        fetchStartTime = endTime;
        fetchEndTime = endTime.clone().add('months', 6);
      } else if (options.direction === 'older') {
        fetchStartTime = startTime.clone().subtract('months', 6);
        fetchEndTime = startTime;
      }

      if (startTime === null || fetchStartTime.isBefore(startTime)) {
        startTime = fetchStartTime;
      }

      fetchingNewer = false;
      if (endTime === null || fetchEndTime.isAfter(endTime)) {
        fetchingNewer = true;
        endTime = fetchEndTime;
      }

      $.ajax({
        type: 'GET',
        url: url,
        data: {
          start: fetchStartTime.unix(),
          end: fetchEndTime.unix()
        },
        dataType: 'xml',
        cache: false,
        success: function (xml) {
          var events = IOF.loadEventsList(xml, coloringStyle);
          options.onSuccess(events, startTime, endTime, fetchingNewer);
        },
        complete: function () {
          if (options.onComplete !== undefined) {
            options.onComplete();
          }
        }
      });
    };
  };

  EventList = function () {
    var fetcher,
      viewModel = {
        startTime : ko.observable(),
        endTime : ko.observable(),
        events : ko.observableArray()
      };

    viewModel.formattedStartTime = ko.computed(function () {
      var hasStartTime = (this.startTime() === null);
      return hasStartTime ? this.startTime().format('MMMM Do YYYY') : null;
    }, viewModel);

    viewModel.formattedEndTime = ko.computed(function () {
      var hasEndTime = (this.endTime() === null);
      return hasEndTime ? this.endTime().format('MMMM Do YYYY') : null;
    }, viewModel);

    // Assumes the DOM is ready
    this.initialize = function (element) {
      var hasTimeWindow = false,
        url,
        olderButton,
        newerButton,
        successHandler,
        coloringStyle = "default";
      if (element.getAttribute("data-event-list-type") === 'time-window') {
        hasTimeWindow = true;
      }

      if (element.hasAttribute("data-event-list-coloring")) {
        coloringStyle = element.getAttribute("data-event-list-coloring");
      }

      url = element.getAttribute("data-event-list-url");
      ko.applyBindings(viewModel, element);

      if (hasTimeWindow) {
        fetcher = new TimeFetcher(url, coloringStyle);
        successHandler = function (newEvents, newStartTime, newEndTime, newer) {
          var index = newer ? viewModel.events().length : 0;
          // This adds the new events to the view model.
          viewModel.events.splice.apply(viewModel.events, [index, 0].concat(newEvents));
          viewModel.startTime(newStartTime);
          viewModel.endTime(newEndTime);
        };

        // Fetch the initial events
        fetcher.fetch({ onSuccess: successHandler });

        requirejs(['ladda', 'css!/css/ladda-themeless.min.css'], function (Ladda) {
          // Set up fetching when the buttons are pressed
          olderButton = $(element.getAttribute('data-event-list-older-button'));
          olderButton.click(function () {
            var ladda = Ladda.create(olderButton.get(0)).start();
            fetcher.fetch({
              direction: 'older',
              onSuccess: successHandler,
              onComplete: function () {
                ladda.stop();
              }
            });
          });

          newerButton = $(element.getAttribute('data-event-list-newer-button'));
          newerButton.click(function () {
            var ladda = Ladda.create(newerButton.get(0)).start();
            fetcher.fetch({
              direction: 'newer',
              onSuccess: successHandler,
              onComplete: function () {
                ladda.stop();
              }
            });
          });
        });
      } else {
        fetcher = new SingleFetcher(url, coloringStyle);
        fetcher.fetch({
          onSuccess: function (events) {
            viewModel.events(events);
          }
        });
      }
    };
  };

  IOF = {};
  IOF.Event = function (id, name, url, startTime, endTime, classification, series, club) {
    var ymd = 'MMMM Do YYYY',
      md = 'MMMM Do',
      sep = ' - ';
    this.id = id;
    this.name = name;
    this.startTime = startTime;
    this.endTime = endTime;
    this.classification = classification;
    this.series = series;
    this.url = url;
    this.clubAcronym = club.acronym;
    if (!startTime.isSame(endTime, 'day')) {
      if (!startTime.isSame(endTime, 'month')) {
        if (!startTime.isSame(endTime, 'year')) {
          this.date = startTime.format(ymd) + sep + endTime.format(ymd);
        } else {
          this.date = startTime.format(md) + sep + endTime.format(ymd);
        }
      } else {
        this.date = startTime.format(md) + sep + endTime.format('Do, YYYY');
      }
    } else {
      this.date = startTime.format(ymd);
    }
  };

  IOF.loadEventsList = function (xml, coloringStyle) {
    var events = [];
    /*jslint unparam: true*/
    $(xml.documentElement).children("Event").each(function (index, element) {
      var eventID = $(element).children("Id").text(),
        eventName = $(element).children("Name").text(),
        extensions = $(element).children("Extensions"),
        series = extensions.children('Series'),
        color,
        startTimeDate = $(element).children("StartTime"),
        startDate = startTimeDate.children("ISODate").text(),
        endTimeDate = $(element).children("EndTime"),
        endDate = endTimeDate.children("ISODate").text(),
        url = $(element).children('URL').text(),
        organizerElement = $(element).children('Organiser'),
        clubAcronym = organizerElement.children('ShortName').text(),
        clubId = organizerElement.children('Id').text(),
        classification = $(element).children('Classification').text(),
        event;
        // Need to get dates to be of format: 2011-10-10T14:48:00.000z
    
        if (coloringStyle === "club-only") {
          if (clubId == club.id) {
            color = '#238216';
          } else {
            color = '#000000';
          }
        } else {
          color = series.children('Color').text();
        }

        event = new IOF.Event(
          eventID,
          eventName,
          url,
          moment(startDate),
          moment(endDate),
          classification,
          { color: color },
          { acronym: clubAcronym }
        );
      events.push(event);
    });
    /*jslint unparam: false*/
    return events;
  };

  return EventList;
});
