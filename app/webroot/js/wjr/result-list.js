/*jslint browser: true indent: 2*/
/*global define*/

define(['jquery', './iof', 'knockout', 'bootstrap'], function ($, IOF, ko) {
  'use strict';
  return function () {
    var viewModel, fetchResults, url;

    viewModel = {
      event: ko.observable(),
      courses: ko.observableArray(),
      creationDate: ko.observable()
    };

    fetchResults = function () {
      $.ajax({
        type: "GET",
        url: url,
        dataType: "xml",
        ifModified: true,
        success: function (xml) {
          var result = IOF.loadResultsList(xml);
          viewModel.event(result[0]);
          viewModel.courses(result[1]);
          viewModel.creationDate(result[2].format("dddd, MMMM Do YYYY [at] h:mm:ss a"));
        }
      });
    };

    this.initialize = function (element) {
      var modeAttr = 'data-result-list-mode',
        mode = element.hasAttribute(modeAttr) ? element.getAttribute(modeAttr) : 'normal';

      url = element.getAttribute("data-result-list-url");

      fetchResults();

      if (mode === 'live') {
        window.setInterval(fetchResults, 5000);
      }

      ko.applyBindings(viewModel, element);
    };

    return this;
  };
});
