/*jslint nomen: true, browser: true, indent: 2*/
/*global define*/

define(['jquery', 'underscore', 'ckeditor'], function ($, _, CKEDITOR) {
  'use strict';

  var wysiwyg = {};

  wysiwyg.updateTextareas = function () {
    _.invoke(CKEDITOR.instances, 'updateElement');
  };

  wysiwyg.createRichTextArea = function (element) {
    CKEDITOR.replace($(element).attr('name'));
  };

  return wysiwyg;
});
