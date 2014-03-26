/*jslint browser: true indent: 2*/
/*global define, requirejs */
// Set browser support (we require browsers to completely support CORS)
define(['jquery', 'jquery.reject', 'css!/css/jquery.reject.css'], function ($) {
  'use strict';
  var support = {};
  support.rejectUnsupportedBrowsers = function () {
    $.reject({
      reject: {
        msie5: true,
        msie6: true,
        msie7: true,
        msie8: true,
        msie9: true,
        firefox1: true,
        firefox2: true,
        firefox3: true,
        opera7: true,
        opera8: true,
        opera9: true,
        opera10: true,
        opera11: true,
        safari2: true,
        safari3: true
      },
      imagePath: '/img/jreject/',
      display: ["chrome", "firefox", "safari", "opera"],
      header: 'Did you know your web browser is out of date?',
      paragraph1: 'Your browser is not supported by this website. We recommend you upgrade your browser.'
    });
  };

  return support;
});
