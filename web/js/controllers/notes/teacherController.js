/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('teacherController', function ($scope, $http, $location, $alert) {

	$scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
		$("table").tablesorter();	
	});

	$scope.showExamMarks = function(item) {
		send("showexammarks.html", {'id' : item.id})
	}

	$scope.showDeadlineMarks = function(item) {
		send("showdeadlinemarks.html", {'id': item.id});
	}
	
	function askExams() {
		$http.get('getexams.html').success(function(data) {
			$scope.exams = data;
			askDeadlines();
		});
	}

	function askDeadlines() {
		$http.get('getdeadlines.html').success(function(data) {
			$scope.deadlines = data;
			if(!data || (data && data.length == 0)) {
				$("table").tablesorter();
			}
		});
	}

	function init() {
		askExams();
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