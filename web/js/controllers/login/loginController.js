/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';



app.controller('loginController', function ($scope, $http, $location, $alert) {
  
  var alert_mg = new AlertManager();
  alert_mg.addAlert("campos vacios", "Login incorrecto:", "Los campos no pueden estar vacios");
  alert_mg.addAlert("error login", "Login incorrecto:", "El usuario o la contraseña no son correctos. Intentelo de nuevo.");
  
  $scope.submit = function () {
    var data = {
        "LoginForm[email]": $scope.email,
        "LoginForm[password]": $scope.password,
        "LoginForm[rememberME]": $scope.rememberME
      };

    doSubmit($http, 'login.html', data).done(function (data, status, headers, config) {
      if(data.length == 0) {
          alert_mg.showAlert($alert, "campos vacios");
      }
      else if (!data.url){
          alert_mg.showAlert($alert, "error login");
      }
      else {
        window.location.href = data.url;
      }
    });
  };
});