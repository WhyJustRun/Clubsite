/*jslint browser: true indent: 2*/
/*global define, requirejs*/

// Handy extensions to the knockout data binding framework
define(['jquery', 'knockout'], function ($, ko) {
  'use strict';
  ko.bindingHandlers.tooltip = {
    init: function (element, valueAccessor) {
      var local = ko.utils.unwrapObservable(valueAccessor()),
        options = { container: 'body' };

      ko.utils.extend(options, ko.bindingHandlers.tooltip.options);
      ko.utils.extend(options, local);

      $(element).tooltip(options);

      ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
        $(element).tooltip("destroy");
      });
    },
    options: {
      placement: "right",
      trigger: "click"
    }
  };

  return ko;
});
