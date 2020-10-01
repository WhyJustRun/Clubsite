/*jslint browser: true indent: 2*/
/*global define*/

define(function () {
  "use strict";
  var utils = {};
  utils.zeroFill = function (number, width) {
    width -= number.toString().length;
    if (width > 0) {
      return new Array(width + (/\./.test(number) ? 2 : 1) ).join('0') + number;
    }
    return number;
  };

  return utils;
});
