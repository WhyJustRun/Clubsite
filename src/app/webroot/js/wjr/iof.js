/*jslint nomen: true, browser: true indent: 2*/
/*global define*/

define(['jquery', 'underscore', 'knockout', 'moment', './utils', './binding'], function ($, _, ko, moment, utils) {
  'use strict';
  var IOF = {};
  IOF.ResultList = function (event, creationTime) {
    this.event = ko.observable(event);
    this.creationTime = ko.observable(creationTime);
    this.formattedCreationTime = ko.computed(function () {
      var time = this.creationTime();
      return time ? time.format("dddd, MMMM Do YYYY [at] h:mm:ss a") : null;
    }, this);
  };

  IOF.Event = function (id, name, startTime, courses) {
    this.id = id;
    this.name = name;
    this.startTime = startTime;
    this.courses = courses;
  };

  IOF.Course = function (id, name, results, scoringType, millisecondTiming) {
    this.id = id;
    this.name = name;
    this.results = ko.observableArray(results);
    this.hasComments = ko.computed(function () {
      return _.some(this.results(), function (result) {
        return (result.officialComment !== "");
      });
    }, this);
    this.hasWhyJustRunPoints = ko.computed(function () {
      return _.some(this.results(), function (result) {
        return (result.scores.WhyJustRun !== undefined);
      });
    }, this);
    this.scoringType = scoringType;
    this.isScore = (scoringType === 'Points');
    this.isTimed = (scoringType === 'Timed');
    this.millisecondTiming = millisecondTiming;
  };

  IOF.friendlyStatuses = {
    'OK': '',
    'Inactive': 'Inactive',
    'DidNotStart': 'DNS',
    'Active': 'In Progress',
    'Finished': 'Unofficial',
    'MissingPunch': 'MP',
    'DidNotFinish': 'DNF',
    'DidNotEnter': 'DNE',
    'Disqualified': 'DSQ',
    'NotCompeting': 'NC',
    'SportWithdrawal': 'Sport Withdrawal',
    'OverTime': 'Over Time',
    'Moved': 'Moved',
    'MovedUp': 'Moved Up',
    'Cancelled': 'Cancelled'
  };

  IOF.Result = function (time, position, status, scores, officialComment, person) {
    if (time !== null && !isNaN(time)) {
      this.time = time;
      this.hours = utils.zeroFill((time - time % 3600) / 3600, 2);
      this.minutes = utils.zeroFill((time - time % 60) / 60 - this.hours * 60, 2);
      var millis = Math.round((time % 1) * 1000);
      this.seconds = utils.zeroFill(Math.floor(time) % 60, 2);
      this.milliseconds = utils.zeroFill(millis, 3);
    } else {
      this.time = this.hours = this.minutes = this.seconds = this.milliseconds = null;
    }

    this.status = status;
    this.friendlyStatus = IOF.friendlyStatuses[status];
    this.position = position;
    this.scores = scores;
    this.person = person;
    this.officialComment = officialComment;
  };

  IOF.Person = function (id, givenName, familyName, profileUrl) {
    this.id = id;
    this.givenName = givenName;
    this.familyName = familyName;
    this.profileUrl = profileUrl;
  };

  IOF.loadResultsList = function (xml) {
    var resultList, date, event, courses, iofEvent;

    resultList = $(xml.documentElement);
    date = moment(resultList.attr('createTime'));
    event = resultList.children("Event").first();
    courses = [];
    resultList.children('ClassResult').each(function () {
      var element, classElement, courseId, courseName, courseExtensions,
        scoringType, results, millisecondTiming;
      element = $(this);
      classElement = element.children('Class');
      courseId = classElement.children("Id").text();
      courseName = classElement.children('Name').text();
      courseExtensions = element.children("Course").children('Extensions');
      scoringType = courseExtensions.children('ScoringType').text();
      results = [];
      millisecondTiming = false;
      element.children('PersonResult').each(function () {
        var prElement = $(this), person, personGivenName, personFamilyName,
          personProfileUrl, personId, resultElement, resultTime,
          resultStatus, resultExtensions, officialComment, resultPosition,
          resultScores, result;
        person = prElement.children("Person").first();
        personGivenName = person.children("Name").children("Given").text();
        personFamilyName = person.children("Name").children("Family").text();
        personProfileUrl = person.children("Contact[type='WebAddress']").text();
        personId = person.children("Id").text();
        resultElement = prElement.children("Result");
        resultTime = parseFloat(resultElement.children("Time").text());
        resultStatus = resultElement.children("Status").text();
        resultExtensions = resultElement.children("Extensions");
        officialComment = resultExtensions.children("OfficialComment").text();
        resultPosition = resultElement.children("Position").text();
        resultScores = {};
        prElement.children("Result").children("Score").each(function () {
          resultScores[$(this).attr("type")] = $(this).text();
        });
        result = new IOF.Result(resultTime, resultPosition, resultStatus, resultScores, officialComment, new IOF.Person(personId, personGivenName, personFamilyName, personProfileUrl));
        if (!millisecondTiming &&
            result.milliseconds !== '000' &&
            result.milliseconds !== null) {
          millisecondTiming = true;
        }
        results.push(result);
      });
      courses.push(new IOF.Course(courseId, courseName, results, scoringType, millisecondTiming));
    });

    iofEvent = new IOF.Event(event.children("Id").text(), event.children("Name").text(), null, courses);
    return new IOF.ResultList(iofEvent, date);
  };

  return IOF;
});
