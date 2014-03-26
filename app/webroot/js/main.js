/*jslint browser: true indent: 2*/
/*global requirejs*/

requirejs.config({
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
    'redactor': 'redactor/redactor',
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
    'jquery.reject': {
      deps: ['jquery']
    },
    'ketchup-boostrap': {
      deps: ['jquery.ketchup', 'bootstrap']
    },
    'redactor': {
      deps: ['jquery']
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
