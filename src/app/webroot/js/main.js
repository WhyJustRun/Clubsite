/*jslint browser: true indent: 2*/
/*global requirejs*/

requirejs.config({
  waitSeconds: 60,
  baseUrl: '/js',
  map: {
    '*': {
      'css': 'require-css/css',
      'async': 'requirejs-plugins/async'
    }
  },
  paths: {
    'jquery.fancybox': 'jquery.fancybox.pack',
    'jquery.jeditable': 'jquery.jeditable.mini',
    'jquery.ketchup': 'jquery.ketchup.all.min',
    'jquery.placeholder': 'jquery.placeholder.min',
    'redactor': 'redactor/redactor.min',
    'ckeditor': 'ckeditor/ckeditor',
    // Replace redactor with ckeditor to switch editors
    'wjr/wysiwyg': 'wjr/wysiwyg.redactor',
    'moment': 'moment.min',
    'underscore': 'underscore-min'
  },
  shim: {
    'bootstrap': {
      deps: ['jquery']
    },
    'bootstrap-colorpicker': {
      deps: ['jquery', 'bootstrap', 'css!/css/bootstrap-colorpicker.css']
    },
    'bootstrap-datetimepicker': {
      deps: ['jquery', 'moment', 'bootstrap', 'css!/css/bootstrap-datetimepicker.min.css']
    },
    'bootstrap-typeahead': {
      deps: ['jquery', 'bootstrap']
    },
    'fullcalendar': {
      deps: ['jquery', 'css!/css/fullcalendar.css']
    },
    'img.srcset.polyfill': {},
    'jquery.fancybox': {
      deps: ['jquery', 'css!/css/jquery.fancybox.css']
    },
    'jquery.jeditable': {
      deps: ['jquery']
    },
    'jquery.ketchup': {
      deps: ['jquery']
    },
    'jquery.placeholder': {
      deps: ['jquery']
    },
    'ketchup-bootstrap': {
      deps: ['jquery.ketchup', 'bootstrap']
    },
    'redactor': {
      deps: ['jquery', 'css!/js/redactor/redactor.css']
    },
    'ckeditor': {
      deps: ['jquery', 'ckeditor-core'],
      exports: 'CKEDITOR'
    }
  }
});

requirejs(['wjr/clubsite'], function (Clubsite) {
  'use strict';
  Clubsite.initialize();
});
