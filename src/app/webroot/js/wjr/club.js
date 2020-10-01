 /*jslint browser: true indent: 2*/
 /*global define, requirejs*/

 define(['jquery'], function ($) {
   var club = {};
   club.id = $('meta[name="wjr.clubsite.club.id"]').attr("content");
   return club;
 });
