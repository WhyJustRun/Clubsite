/*jslint browser: true indent: 2*/
/*global define*/

define(['jquery', 'redactor'], function ($) {
  'use strict';
  var wysiwyg = {};
  wysiwyg.updateTextareas = function () { return; };
  wysiwyg.createRichTextArea = function (element) {
    $(element).redactor({
      toolbarFixed: true,
      toolbarFixedBox: true,
      imageUpload: '/proxies/redactor/uploadImage',
      fileUpload: '/proxies/redactor/uploadFile'
    });
  };

  return wysiwyg;
});
