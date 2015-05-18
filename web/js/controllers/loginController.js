/* global angular */
/* global $ */
'use strict';

var app = angular.module('EVA', ['ngSanitize', 'mgcrea.ngStrap']);

app.controller('loginController', function ($scope, $http, $location) {
  $scope.submit = function () {
    var req = {
      method: 'POST',
      url: '/login.html',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      transformRequest: function (obj) {
        var str = [];
        for (var p in obj) {
          var value = obj[p];
          if (typeof value == 'boolean')
            value = 'on';
          if (value)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(value));
        }

        return str.join("&");
      },
      data: {
        "LoginForm[email]": $scope.email,
        "LoginForm[password]": $scope.password,
        "LoginForm[rememberME]": $scope.rememberME
      }
    };

    $('#alert-place').hide();
    $http(req).success(function (data, status, headers, config) {

      if(data.length == 0) {
        $('#alert-place').show();
        $scope.alert = {
          title: "Login incorrecto:",
          content: "Los campos no pueden estar vacios",
          type: "danger",
        };
      }
      else if (!data.url){
        $('#alert-place').show();
        $scope.alert = {
          title: "Login incorrecto:",
          content: "El usuario o la contraseña no son correctos. Intentelo de nuevo.",
          type: "danger",
        };
      }
      else {
        window.location.href = data.url;
      }
    });
  };
});