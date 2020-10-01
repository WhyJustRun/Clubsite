/*jslint browser: true, indent: 2, nomen: true*/
/*global define, requirejs */

define(['jquery', 'underscore', 'cakebootstrap', 'moment', './forms', './editable', './browser-support', 'jquery.placeholder', 'bootstrap', 'img.srcset.polyfill', 'ketchup-bootstrap'], function ($, _, bootstrapifyCakePHPForms, moment, forms, editable, browserSupport) {
  'use strict';
  var Clubsite = {};

  Clubsite.initialize = function () {
    browserSupport.rejectUnsupportedBrowsers();

    // Components that we load conditionally if needed for the current page
    var components = [
      {
        selector: 'a.lightbox',
        requires: ['jquery.fancybox'],
        load: function (lightboxes) {
          lightboxes.fancybox();
        }
      }, {
        selector: '.date-picker',
        requires: ['bootstrap-datetimepicker'],
        load: function (datePickers) {
          datePickers.datetimepicker({
            pickTime: false,
            format: "YYYY-MM-DD"
          });
        }
      }, {
        selector: '.event-list',
        requires: ['wjr/event-list'],
        loadEach: function (element, EventList) {
          var eventList = new EventList();
          eventList.initialize(element);
        }
      }, {
        selector: '.result-list',
        requires: ['wjr/result-list'],
        loadEach: function (element, ResultList) {
          var resultList = new ResultList();
          resultList.initialize(element);
        }
      }, {
        selector: '.color-picker',
        requires: ['bootstrap-colorpicker'],
        load: function (colorPickers) {
          colorPickers.colorpicker();
        }
      }, {
        selector: '.result-editor',
        requires: ['wjr/result-editor'],
        loadEach: function (element, ResultEditor) {
          var resultEditor = new ResultEditor();
          resultEditor.initialize(element);
        }
      }, {
        selector: '.register-others',
        requires: ['wjr/register-others'],
        loadEach: function (element, RegisterOthers) {
          var registerOthers = new RegisterOthers();
          registerOthers.initialize(element);
        }
      }, {
        selector: '.edit-event-courses',
        requires: ['wjr/edit-event-courses'],
        loadEach: function (element, EventCoursesEditor) {
          var editor = new EventCoursesEditor();
          editor.initialize(element);
        }
      }, {
        selector: '.edit-event-organizers',
        requires: ['wjr/edit-event-organizers'],
        loadEach: function (element, EventOrganizersEditor) {
          var editor = new EventOrganizersEditor();
          editor.initialize(element);
        }
      }, {
        selector: '.simple-marker-map',
        requires: ['wjr/map'],
        loadEach: function (element, map) {
          var markerMap = new map.SimpleMarkerMap();
          markerMap.initialize(element);
        }
      }, {
        selector: '.draggable-marker-map',
        requires: ['wjr/map'],
        loadEach: function (element, map) {
          var markerMap = new map.DraggableMarkerMap();
          markerMap.initialize(element);
        }
      }, {
        selector: '.multi-marker-map',
        requires: ['wjr/map', 'markerclusterer'],
        loadEach: function (element, map) {
          var markerMap = new map.MultiMarkerMap();
          markerMap.initialize(element);
        }
      }, {
        selector: '.simple-person-picker',
        requires: ['wjr/forms'],
        loadEach: function (element, forms) {
          var personPicker = new forms.SimplePersonPicker();
          personPicker.initialize(element);
        }
      }
    ];

    _.each(components, function (component) {
      var results = $(component.selector);
      if (results.length !== 0) {
        requirejs(component.requires, function (dependency) {
          if (component.load !== undefined) {
            component.load(results, dependency);
          } else {
            results.each(function () {
              component.loadEach(this, dependency);
            });
          }
        });
      }
    });

    // Add required to the recaptcha field. This has to be done after page load as the element is created dynamically, and before ketchup parses the data-validate tags
    $('#recaptcha_response_field').attr('data-validate', 'validate(required)');
    $('time.timeago').each(function () {
      var time = moment($(this).attr('datetime'));
      $(this).html(time.fromNow());
    });

    bootstrapifyCakePHPForms();
    $('input, textarea').placeholder();

    forms.validation.checkKetchupFormsAreValidOnSubmit();

    $('.wjr-wysiwyg').each(function () {
      var element = this;
      requirejs(['wjr/wysiwyg'], function (wysiwyg) {
        wysiwyg.createRichTextArea(element);
      });
    });

    $("[data-toggle='tooltip']").tooltip();

    $('.wjr-calendar').each(function () {
      var element = this;
      requirejs(['wjr/calendar'], function (Calendar) {
        Calendar.Initialize(element);
      });
    });

    $('.flickr-photos-container').each(function () {
      var element = this;
      requirejs(['wjr/flickr-photos'], function (FlickrPhotos) {
        FlickrPhotos.Initialize(element);
      });
    });

    editable.initialize();

    // Event editing form
    // Fix for CakePHP form security - exclude the knockout inputs
    $("#EventEditForm").submit(function () {
      $(this).find('[name ^= "ko_unique"]').attr("name", null);
    });
  };

  return Clubsite;
});
