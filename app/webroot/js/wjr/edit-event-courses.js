/*jslint nomen: true, browser: true indent: 2*/
/*global define, requirejs, confirm*/

define(['jquery', 'underscore', 'knockout'], function ($, _, ko) {
  'use strict';
  var EventCoursesEditor = function () {
    var viewModel, Course;

    viewModel = {
      courses: ko.observableArray([]),
      addCourse: function (id, name, distance, climb, isScoreO, description) {
        var course = new Course(id, name, distance, climb, isScoreO, description);
        this.courses.push(course);
      },
      addNewCourse: function () {
        this.courses.push(new Course(null, null, null, null, false, null));
      }
    };

    Course = function (id, name, distance, climb, isScoreO, description) {
      this.id = id;
      this.name = ko.observable(name);
      this.distance = ko.observable(distance);
      this.climb = ko.observable(climb);
      this.rankBy = ko.observable(isScoreO ? "points" : "time");
      this.isScoreO = ko.computed(function () {
        return (this.rankBy() === "points");
      }, this);
      this.description = ko.observable(description);
      this.remove = function () {
        // Only need to confirm for deletion if the course hasn't been created locally
        if (this.id !== null) {
          if (confirm("Are you sure you want to delete this course? This will also delete any results associated to the course.")) {
            $.ajax('/courses/delete/' + this.id);
            viewModel.courses.remove(this);
          }
        } else {
          viewModel.courses.remove(this);
        }
      };
    };

    this.initialize = function (element) {
      var courseJson = $(element.getAttribute('data-course-json-element')).val();
      _.each(JSON.parse(courseJson), function (originalCourse) {
        viewModel.addCourse(originalCourse.id, originalCourse.name, originalCourse.distance, originalCourse.climb, originalCourse.isScoreO, originalCourse.description);
      });

      ko.applyBindings(viewModel, element);
    };
  };

  return EventCoursesEditor;
});
