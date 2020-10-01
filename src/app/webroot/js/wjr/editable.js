/*jslint browser: true indent: 2*/
/*global define, requirejs*/

define(['jquery'], function ($) {
  'use strict';
  var editable = {};
  editable.initialize = function () {
    var editableContent = $('.page-resource.wjr-editable, .page-resource-title.wjr-editable, .content-block.wjr-editable');
    // Only load jeditable if we actually need to.
    if (editableContent.length !== 0) {
      requirejs(['jquery.jeditable'], function () {
        /*jslint unparam: true*/
        $.editable.addInputType('wysiwyg', {
          element: function (settings, original) {
            var textarea = $('<textarea />');
            if (settings.rows) {
              textarea.attr('rows', settings.rows);
            } else {
              textarea.height(settings.height);
            }
            if (settings.cols) {
              textarea.attr('cols', settings.cols);
            } else {
              textarea.width(settings.width);
            }
            $(this).append(textarea);
            return textarea;
          },
          submit: function (settings, original) {
            requirejs(['./wysiwyg'], function (wysiwyg) {
              wysiwyg.updateTextareas();
            });
          },
          plugin: function (settings, original) {
            // Hacktastic. This fixes an issue with the scroll position jumping on chrome when opening the textarea..
            var textarea = $('textarea', this);
            setTimeout(function () {
              requirejs(['wjr/wysiwyg'], function (wysiwyg) {
                wysiwyg.createRichTextArea(textarea);
              });
            }, 1);
          }
        });
        /*jslint unparam: false*/

        // Allow link clicks to pass through without opening editor. NOTE: doesn't fix the case when the content is edited.. seems like it would be really hacky to get that working because jeditable doesn't have enough callback hooks.
        editableContent.find('a').click(function (e) {
          e.stopPropagation();
        });

        $('.content-block.wjr-editable').editable('/contentBlocks/edit', {
          type      : 'wysiwyg',
          cancel    : 'Cancel',
          submit    : 'Save',
          tooltip   : 'Click to edit…',
          onblur    : 'ignore'
        });

        $('.page-resource.wjr-editable').editable('/pages/edit', {
          type      : 'wysiwyg',
          cancel    : 'Cancel',
          submit    : 'Save',
          tooltip   : 'Click to edit…',
          onblur    : 'ignore'
        });

        $('.page-resource-title.wjr-editable').editable('/pages/edit', {
          type      : 'text',
          cancel    : 'Cancel',
          submit    : 'Save',
          name      : 'name',
          tooltip   : 'Click to edit…',
          onblur    : 'ignore'
        });
      });
    }
  };

  return editable;
});
