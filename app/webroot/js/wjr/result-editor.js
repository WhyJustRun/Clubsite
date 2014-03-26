/*jslint browser: true, indent: 2, nomen: true*/
/*global define, confirm, alert*/

define(['jquery', 'underscore', 'knockout', './utils', './forms'], function ($, _, ko, utils, forms) {
  'use strict';
  return function () {
    var _statuses = null, statuses, sortedStatuses,
      ResultStatus, Course, Result, User, Event,
      viewModel, loadObjects, eventId;
    statuses = function () {
      var map;
      if (_statuses === null) {
        // TODO: We shouldn't need this non-standard mapping after we start using IOF standard statuses..
        map = {
          'ok': 'Finished',
          'inactive': 'Inactive',
          'did_not_start': 'DNS',
          'active': 'In Progress',
          'finished': 'Unofficial',
          'mis_punch': 'MP',
          'did_not_enter': 'DNE',
          'did_not_finish': 'DNF',
          'disqualified': 'DSQ',
          'not_competing': 'NC',
          'sport_withdrawal': 'Sport Withdrawal',
          'over_time': 'Over Time',
          'moved': 'Moved',
          'moved_up': 'Moved Up',
          'cancelled': 'Cancelled'
        };

        _statuses = {};
        _.each(map, function (k, v) {
          _statuses[k] = new ResultStatus(v, k);
        });
      }

      return _statuses;
    };

    sortedStatuses = function () {
      return _.values(statuses()).sort(function (a, b) { return a.name.localeCompare(b.name); });
    };

    ResultStatus = function (id, name) {
      this.id = id;
      this.name = (name === null) ? statuses()[id] : name;
    };

    Course = function (id, name, distance, climb, event_id, description, results, is_score_o) {
      this.id = id;
      this.name = name;
      this.distance = distance;
      this.climb = climb;
      this.event_id = event_id;
      this.description = description;
      this.results = ko.observableArray(results);
      this.is_score_o = ko.observable(is_score_o);
      this.rankBy = ko.computed({
        read: function () {
          return this.is_score_o() ? "points" : "time";
        },
        write: function (value) {
          this.is_score_o((value === "points"));
        },
        owner: this
      });
    };

    Result = function (id, user, course_id, time_seconds, status, points, registrant_comment, official_comment, score_points) {
      var hoursCount = Math.floor(time_seconds / 3600),
        minutesCount = Math.floor((time_seconds - hoursCount * 3600) / 60),
        secondsCount = time_seconds - hoursCount * 3600 - minutesCount * 60,
        millisecondsCount = Math.round((secondsCount % 1) * 1000);

      this.id = id;
      this.user = user;
      this.course_id = course_id;
      this.hours = ko.observable(time_seconds ? utils.zeroFill(hoursCount, 2) : '00');
      this.minutes = ko.observable(time_seconds ? utils.zeroFill(minutesCount, 2) : '00');
      this.seconds = ko.observable(time_seconds ? utils.zeroFill(Math.floor(secondsCount), 2) : '00');
      this.milliseconds = ko.observable(time_seconds ? utils.zeroFill(millisecondsCount, 3) : '000');
      this.statuses = sortedStatuses;
      this.status = ko.observable(status || 'ok');
      this.points = points;
      this.registrant_comment = ko.observable(registrant_comment);
      this.official_comment = ko.observable(official_comment);
      this.score_points = ko.observable(score_points);

      this.remove = function () {
        if (this.id) {
          if (!confirm("Are you sure you want to delete this competitor? The entry be deleted from the server immediately.")) {
            return;
          }
          $.ajax('/results/delete/' + this.id);
        }

        var courses = viewModel.courses(),
          i;
        for (i = 0; i < viewModel.courses().length; i += 1) {
          courses[i].results.remove(this);
        }
      };
    };

    User = function (id, name) {
      this.id = id;
      this.name = name;
    };

    Event = function (id, name, date) {
      this.id = id;
      this.name = name;
      this.date = date;
    };

    viewModel = {
      courses : ko.observableArray(),
      event : ko.observable(),
      userName: ko.observable(),
      selectedCourse: ko.observable(),
      addCompetitorToCourse: function (id, name) {
        var options, successHandler, user;
        if (!viewModel.selectedCourse()) {
          alert("Adding competitor failed, because a course wasn't selected.");
          return;
        }

        if (id === undefined) {
          // create user asynchronously, then add to the UI once we've go the user ID.
          options = {
            userName: name,
            eventId: viewModel.event().id
          };
          successHandler = function (userID) {
            viewModel.addCompetitorToCourse($.parseJSON(userID), name);
          };
          $.post("/users/add", options, successHandler);
        } else {
          user = new User(id, name);
          viewModel.selectedCourse().results.push(new Result(undefined, user, viewModel.selectedCourse().id, undefined, undefined, undefined, undefined, undefined, undefined));
        }
      }
    };

    loadObjects = function () {
      // TODO: We should use the result list xml api.
      $.getJSON('/events/view/' + eventId + '.json', function (data) {
        viewModel.event(new Event(data.Event.id, data.Event.name, data.Event.date));
        var courses = data.Course;
        _.each(courses, function (course) {
          var results = course.Result,
            importedResults = [];

          _.each(results, function (result) {
            var user = new User(result.User.id, result.User.name);
            importedResults.push(new Result(result.id, user, result.course_id, result.time_seconds, result.status, result.points, result.registrant_comment, result.official_comment, result.score_points));
          });

          viewModel.courses.push(new Course(course.id, course.name, course.distance, course.climb, course.event_id, course.description, importedResults, course.is_score_o));
        });
      });
    };

    this.initialize = function (element) {
      eventId = element.getAttribute('data-event-id');
      loadObjects();
      ko.applyBindings(viewModel, element);
      var options = { maintainInput: false, createNew: true },
        pickerElement = $('#competitorResults');
      forms.personPicker(pickerElement, options, function (person) {
        if (person !== null) {
          viewModel.addCompetitorToCourse(person.id, person.name);
        }
      });
    };
  };
});
