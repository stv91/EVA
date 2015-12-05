/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('studentController', function ($scope, $http, $location, $alert) {

	$scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
	   	$("table").tablesorter();
	   	$("table thead:first-child *").unbind('sort');
	   	$("table thead:first-child *").unbind('click');
	   	$("table thead:first-child *").unbind('keyup');
	   	$("table thead:first-child *").unbind('mousedown');
	   	$("table thead:first-child *").unbind('mouseup');
	   	$("table thead:first-child *").unbind('selectstart');
	});

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

app.directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
});