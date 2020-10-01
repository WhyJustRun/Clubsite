/*jslint browser: true indent: 2*/
/*global define*/

define(['jquery', './iof', 'knockout', 'bootstrap'], function ($, IOF, ko) {
  'use strict';
  return function () {
    var viewModel, fetchResults, url;

    viewModel = {
      resultList: ko.observable()
    };

    fetchResults = function () {
      $.ajax({
        type: "GET",
        url: url,
        dataType: "xml",
        ifModified: true,
        success: function (xml) {
          var resultList = IOF.loadResultsList(xml);
          viewModel.resultList(resultList);
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
