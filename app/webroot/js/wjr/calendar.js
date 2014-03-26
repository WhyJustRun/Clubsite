/*jslint browser: true indent: 2*/
/*global define, requirejs*/
define(['./wjr', './club', 'jquery', 'fullcalendar'], function (wjr, club, $) {
  'use strict';
  var Calendar = {};
  Calendar.Initialize = function (element) {
    element = $(element);
    requirejs(['domReady!'], function () {
      var initialLoad = true,
        poppingState = false;

      element.fullCalendar({
        eventSources: [wjr.core.domain + '/club/' + club.id + '/events.json?external_significant_events=all&prefix_club_acronym=external_only'],
        year: element.attr('data-calendar-year'),
        month: element.attr('data-calendar-month'),
        date: element.attr('data-calendar-day'),
        timeFormat: 'h:mmtt',
        firstDay: 1,
        // From google code discussion
        viewDisplay : function (view) {
          var url, options, date;
          date = $.fullCalendar.formatDate(view.start, 'dd-MM-yyyy');
          url = "/events/index/" + date;
          options = { viewMode: view.name, start: view.start };
          // IE doesn't support replaceState..
          if (window.history.replaceState) {
            //I had to do a little bit of juggling to get it to only run items when necessary
            //There might be a better way to do this but I couldn't find one
            if (initialLoad) { //Replace the current state to set up state variables.  URL should be identical
              history.replaceState(options, "Event Calendar", url);
              window.onpopstate = function (event) { //set up onpopstate handler
                if (!initialLoad) { //the browser kept trying to pop the state on intial load
                  var start = event.state.start;
                  if (typeof start === 'string') { //even though i stored a date object, it was coming back as a string for some reason
                    start = $.fullCalendar.parseDate(start);
                  }
                  poppingState = true; //don't re-push state
                  $('#calendar').fullCalendar('gotoDate', start);
                  poppingState = true; //don't re-push state
                  $('#calendar').fullCalendar('changeView', event.state.viewMode);
                }
                initialLoad = false;
              };
            } else {
              if (!poppingState) {
                history.pushState(options, "Event Calendar", url);
              } else {
                poppingState = false;
              }
            }
          }
        }
      });
    });

  };

  return Calendar;
});
