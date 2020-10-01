/*jslint browser: true indent: 2*/
/*global define*/

define(['jquery'], function ($) {
  'use strict';
  var wjr = {};
  wjr.core = {};
  wjr.core.domain = $('meta[name="wjr.core.domain"]').attr("content");

  return wjr;
});
