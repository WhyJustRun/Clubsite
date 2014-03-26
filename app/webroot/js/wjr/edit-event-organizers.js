/*jslint nomen: true, browser: true indent: 2*/
/*global define, requirejs, confirm*/

define(['jquery', 'underscore', 'knockout', './forms'], function ($, _, ko, forms) {
  'use strict';
  var EventOrganizersEditor = function () {
    var availableRoles = [], finishLoadingOrganizers,
      roleById, viewModel, Organizer;

    viewModel = {
      organizers: ko.observableArray([]),
      addOrganizer: function (id, name, roleId) {
        this.organizers.push(new Organizer(id, name, roleId));
      }
    };

    Organizer = function (id, name, roleId) {
      this.id = id;
      this.name = ko.observable(name);
      this.availableRoles = ko.observableArray(availableRoles);
      this.role = ko.observable(roleById(roleId));
      this.remove = function () {
        viewModel.organizers.remove(this);
      };
    };

    this.initialize = function (element) {
      $.getJSON('/roles/index.json', function (data) {
        _.each(data, function (role) {
          var availableRole = {};
          availableRole.id = role.id;
          availableRole.name = role.name;
          availableRoles.push(availableRole);
        });

        finishLoadingOrganizers(element);
      });
    };

    roleById = function (id) {
      _.each(availableRoles, function (role) {
        if (role.id === id) {
          return role;
        }
      });
    };

    finishLoadingOrganizers = function (element) {
      var organizerJson, organizerElement;
      organizerElement = $(element.getAttribute('data-organizer-input'));
      organizerJson = $(element.getAttribute('data-organizer-json-element')).val();
      _.each(JSON.parse(organizerJson), function (originalOrganizer) {
        viewModel.addOrganizer(originalOrganizer.id, originalOrganizer.name, originalOrganizer.role.id);
      });

      forms.personPicker(organizerElement, { maintainInput: false, allowFake: false }, function (person) {
        if (person !== null) {
          organizerElement.val(null);
          viewModel.addOrganizer(person.id, person.name);
        }
      });
      ko.applyBindings(viewModel, element);
    };
  };
  return EventOrganizersEditor;
});
