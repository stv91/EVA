/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('examsController', function ($scope, $http, $location, $alert) {
	$scope.prueba = function() {
		$http.get("prueba.html");
	}
}