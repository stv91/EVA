/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('studentController', function ($scope, $http, $location, $alert) {
	function askMarks() {
		$http.get("getstudentmarks.html")
		.success(function(data) {
			$scope.allMarks = data;
		})
	}

	function init() {
		askMarks();
	}
	
	init();
});