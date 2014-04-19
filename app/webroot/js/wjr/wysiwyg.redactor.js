/*jslint browser: true indent: 2*/
/*global define*/

define(['wjr/wjr', 'jquery', 'redactor'], function (wjr, $) {
  'use strict';
  var wysiwyg = {};
  wysiwyg.updateTextareas = function () { return; };
  wysiwyg.createRichTextArea = function (element) {
    $(element).redactor({
      toolbarFixed: true,
      toolbarFixedBox: true,
      imageUpload: wjr.core.domain + '/api/redactor/upload_image',
      fileUpload: wjr.core.domain + '/api/redactor/upload_file'
    });
  };

  return wysiwyg;
});
