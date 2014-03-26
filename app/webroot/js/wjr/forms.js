/*jslint nomen: true, browser: true indent: 2*/
/*global define, requirejs*/

define(['jquery', 'underscore'], function ($, _) {
  "use strict";
  var forms = {};

  forms.SimplePersonPicker = function () {
    this.initialize = function (element) {
      var options = {}, callback, targetElement, configs;
      targetElement = $(element.getAttribute('data-user-id-target'));

      configs = [['data-maintain-input', 'maintainInput'],
                 ['data-allow-fake', 'allowFake'],
                 ['data-create-new', 'createNew'],
                 ['data-allow-new', 'allowNew']];

      _.each(configs, function (config) {
        var dataAttribute = config[0], option  = config[1], setting;
        if (element.hasAttribute(dataAttribute)) {
          setting = element.getAttribute(dataAttribute);
          options[option] = (setting === 'true') ? true : false;
        }
      });

      callback = function (person) {
        // REVIEW: Should we set val to null when person is null?
        if (person !== null) {
          targetElement.val(person.id);
        }
      };

      forms.personPicker(element, options, callback);
    };
  };

  // Callback should take a person object with id, name. Callback can also be called with null (no person selected)
  // Maintain input will keep the selected user's name in the input after the input loses focus. "allowNew" will keep the input populated even if there is no user in the system with the given name. "createNew" will add an option to create a new user with the given name.
  forms.personPicker = function (element, options, callback) {
    requirejs(['bootstrap-typeahead'], function () {
      var displayName = null,
        defaults = {
          'allowNew': false,
          'allowFake': true,
          'createNew': false
        };
      options = options || {};
      options = $.extend({}, defaults, options);

      $(element).typeahead({
        source: function (typeahead, query) {
          $.ajax({
            url: "/users/index.json?term=" + query + "&allowFake=" + (options.allowFake ? 'true' : 'false'),
            success: function (data) {
              if (options.createNew) {
                data.push({
                  position: "bottom",
                  name: query,
                  identifiableName: 'Create New User (' + query + ')'
                });
              }
              typeahead.process(data);
            }
          });
        },
        onselect: function (person) {
          callback(person);
          if (options.maintainInput) {
            displayName = person.name;
            $(element).val(displayName);
          }
          $(element).blur();
        },
        property: "identifiableName"
      });

      $(element).blur(function () {
        if (!options.allowNew) {
          if ($(element).val() === "") {
            callback(null);
          } else if ($(element).val() !== displayName) {
            $(element).val(displayName);
          }
        }
      });
    });
  };

  forms.validation = {};
  forms.validation.checkKetchupFormsAreValidOnSubmit = function () {
    $('[data-validate="ketchup"]').each(function () {
      requirejs(['jquery.ketchup'], function () {
        var jqe = $(this);
        jqe.submit(function () {
          jqe.ketchup();
          return jqe.ketchup('isValid');
        });
      });
    });
  };

  return forms;
});
