/*jslint browser: true, indent: 2*/
/*global define, confirm, alert*/

define(['jquery', './forms'], function ($, forms) {
  'use strict';
  return function () {
    this.initialize = function (element) {
      element = $(element);
      element.find('#RegisterOthersUserId').val(null);

      var options = { maintainInput: true, allowNew: true };
      forms.personPicker(element.find('#RegisterOthersUserName'), options, function (person) {
        element.find('#RegisterOthersUserId').val((person !== null) ? person.id : null);
      });

      function completeSubmit(courseId, userId) {
        location.href = "/courses/register/" + courseId + "/" + userId;
      }

      element.find('#RegisterOthersSubmit').click(function () {
        var userId, courseId, userName;
        userId = element.find('#RegisterOthersUserId').val();
        courseId = element.find('#RegisterOthersCourse').val();
        if (!userId) {
          userName = element.find('#RegisterOthersUserName').val();
          if (userName) {
            if (userName.indexOf(" ") !== -1) {
              if (confirm("This registration will create a new user in the system. Are you sure " + userName + " isn't already an WhyJustRun user?")) {
                $.post('/users/add', { userName: userName }, function (data) {
                  completeSubmit(courseId, $.parseJSON(data));
                });
              } else {
                alert("Thanks! Please re-enter the participant's name and choose the matching person from the dropdown.");
              }
            } else {
              alert("Please enter the participant's full name.");
            }
          } else {
            alert("Please enter the participant name");
          }
        } else {
          completeSubmit(courseId, userId);
        }
      });
    };
  };
});
